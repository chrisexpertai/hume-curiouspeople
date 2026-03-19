<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseImporterService
{
    // Phrases that signal a section contains UI/visual content needing a screenshot placeholder
    const MEDIA_TRIGGERS = [
        'dashboard', 'screen', 'form', 'upload', 'image', 'photo', 'view',
        'interface', 'portal', 'grid', 'calendar', 'map', 'chart', 'checklist',
        'check-in', 'check in', 'check out', 'camera', 'drag', 'modal', 'button',
    ];

    // Plausible "wrong answer" distractors for purpose questions
    const GENERIC_DISTRACTORS = [
        'To process financial transactions and generate invoices',
        'To manage user authentication and password resets',
        'To generate automated reports and analytics exports',
        'To schedule maintenance tasks and send notifications',
        'To import and export data in bulk CSV format',
        'To configure system-wide application settings',
        'To manage email communication templates',
        'To monitor real-time system performance metrics',
        'To handle customer support ticket escalations',
        'To manage inventory stock levels and reorder points',
    ];

    // -------------------------------------------------------------------------
    // PUBLIC: parse raw text → structured array
    // -------------------------------------------------------------------------

    public function parse(string $rawText): array
    {
        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $rawText));

        $result = [
            'title'       => '',
            'description' => '',
            'sections'    => [],
        ];

        $currentSection = null;
        $currentLecture = null;
        $currentLabel   = null;
        $introLines     = [];
        $seenTitle      = false;
        $inCodeBlock    = false;
        $sectionSort    = 0;

        foreach ($lines as $rawLine) {
            $line    = rtrim($rawLine);
            $trimmed = trim($line);

            // ── Code block toggle ────────────────────────────────────────────
            if (str_starts_with($trimmed, '```')) {
                $inCodeBlock = !$inCodeBlock;
                if ($currentLecture !== null) {
                    if ($inCodeBlock) {
                        $this->appendToLecture($currentLecture, ['type' => 'code_open']);
                    } else {
                        $this->appendToLecture($currentLecture, ['type' => 'code_close']);
                    }
                }
                continue;
            }

            if ($inCodeBlock) {
                if ($currentLecture !== null) {
                    $this->appendToLecture($currentLecture, ['type' => 'code_line', 'text' => $line]);
                }
                continue;
            }

            // ── H1 → Course title ────────────────────────────────────────────
            if (preg_match('/^#\s+(.+)$/', $trimmed, $m)) {
                $result['title'] = trim($m[1]);
                $seenTitle       = true;
                continue;
            }

            // ── H2 → Section ─────────────────────────────────────────────────
            if (preg_match('/^##\s+(.+)$/', $trimmed, $m)) {
                $sectionName = trim($m[1]);

                // Close current lecture & section
                $this->closeLecture($currentSection, $currentLecture);
                $this->closeSection($result, $currentSection);

                // Skip table-of-contents headings
                if (strtolower($sectionName) === 'table of contents') {
                    $currentSection = null;
                    $currentLecture = null;
                    $currentLabel   = null;
                    continue;
                }

                $sectionSort++;
                $currentSection = $this->newSection($sectionName, $sectionSort);
                $currentLecture = null;
                $currentLabel   = null;
                continue;
            }

            // ── H3 → Lecture ─────────────────────────────────────────────────
            if (preg_match('/^###\s+(.+)$/', $trimmed, $m)) {
                $this->closeLecture($currentSection, $currentLecture);

                if ($currentSection === null) {
                    $sectionSort++;
                    $currentSection = $this->newSection('Introduction', $sectionSort);
                }

                $lectureTitle   = trim($m[1]);
                $lectureSort    = count($currentSection['lectures']) + 1;
                $currentLecture = $this->newLecture($lectureTitle, $lectureSort);
                $currentLabel   = null;
                continue;
            }

            // ── Horizontal rule ───────────────────────────────────────────────
            if (preg_match('/^---+$/', $trimmed) || preg_match('/^\*\*\*+$/', $trimmed)) {
                continue;
            }

            // ── Empty line ────────────────────────────────────────────────────
            if ($trimmed === '') {
                $currentLabel = null;
                if ($currentLecture !== null) {
                    $this->appendToLecture($currentLecture, ['type' => 'spacer']);
                }
                continue;
            }

            // ── Introduction text (before first H2) ──────────────────────────
            if ($currentSection === null) {
                if ($seenTitle) {
                    $introLines[] = $trimmed;
                }
                continue;
            }

            // ── Labeled field: "Purpose:", "Key fields:", "Actions:", etc. ───
            $labelPattern = '/^(Purpose|Key items?|Key fields?|Key screens?|Actions?'
                . '|Features?|Core [a-z ]+fields?|Modules?|Submodules?|Integration'
                . '|Available [a-z ]+|Common subtypes?|Key columns?|Key items?)'
                . '(\s+\([^)]*\))?\s*:\s*(.*)$/iu';

            if (preg_match($labelPattern, $trimmed, $m)) {
                $label      = trim($m[1]);
                $ctx        = trim($m[2], " \t()");
                $inlineText = trim($m[3]);

                $currentLabel = strtolower($label);

                // Ensure a lecture exists
                if ($currentLecture === null) {
                    $sort           = count($currentSection['lectures']) + 1;
                    $currentLecture = $this->newLecture($currentSection['name'], $sort);
                }

                // Purpose → also save as description
                if ($currentLabel === 'purpose' && $inlineText) {
                    $currentLecture['description'] = $inlineText;
                    $currentSection['all_purposes'][] = $inlineText;
                }

                $this->appendToLecture($currentLecture, [
                    'type'  => 'label',
                    'label' => $label . ($ctx ? " ({$ctx})" : ''),
                    'text'  => $inlineText,
                ]);

                // Check for media triggers in label text
                $this->checkMediaTriggers($currentLecture, $inlineText);
                continue;
            }

            // ── Bullet list item: - or * or • ────────────────────────────────
            if (preg_match('/^[-*•]\s+(.+)$/', $trimmed, $m)) {
                $item = trim($m[1]);

                if ($currentLecture === null) {
                    $sort           = count($currentSection['lectures']) + 1;
                    $currentLecture = $this->newLecture($currentSection['name'], $sort);
                }

                $this->appendToLecture($currentLecture, ['type' => 'li', 'list' => 'ul', 'text' => $item]);

                // Extract key terms when under a relevant label
                if ($currentLabel && preg_match('/^(key|features?|actions?|modules?|screens?)/i', $currentLabel)) {
                    foreach (explode(',', $item) as $chunk) {
                        $term = preg_replace('/\s*[-–:]\s*.+$/', '', trim($chunk));
                        $term = preg_replace('/\s*\([^)]*\)/', '', $term);
                        $term = trim($term);
                        if ($term && strlen($term) <= 80) {
                            $currentLecture['key_terms'][]        = $term;
                            $currentSection['all_key_terms'][]    = $term;
                        }
                    }
                }

                $this->checkMediaTriggers($currentLecture, $item);
                continue;
            }

            // ── Numbered list item ────────────────────────────────────────────
            if (preg_match('/^(\d+)\.\s+(.+)$/', $trimmed, $m)) {
                $step = trim($m[2]);

                if ($currentLecture === null) {
                    $sort           = count($currentSection['lectures']) + 1;
                    $currentLecture = $this->newLecture($currentSection['name'], $sort);
                }

                $this->appendToLecture($currentLecture, ['type' => 'li', 'list' => 'ol', 'text' => $step]);
                $currentLecture['steps'][]     = $step;
                $currentSection['all_steps'][] = $step;
                continue;
            }

            // ── Bold key: value (e.g. **Name:** value) ───────────────────────
            if (preg_match('/^\*\*([^*]+)\*\*\s*[-–:]\s*(.+)$/', $trimmed, $m)) {
                if ($currentLecture !== null) {
                    $this->appendToLecture($currentLecture, [
                        'type'  => 'keyval',
                        'key'   => trim($m[1], ': '),
                        'value' => $m[2],
                    ]);
                }
                continue;
            }

            // ── Regular paragraph ─────────────────────────────────────────────
            if ($currentLecture !== null) {
                $this->appendToLecture($currentLecture, ['type' => 'p', 'text' => $trimmed]);
            } elseif ($currentSection !== null) {
                $currentSection['intro'] .= '<p>' . $this->inline($trimmed) . '</p>';
            }
        }

        // Close any open lecture / section
        $this->closeLecture($currentSection, $currentLecture);
        $this->closeSection($result, $currentSection);

        $result['description'] = implode(' ', array_filter($introLines));

        // ── Generate quizzes ──────────────────────────────────────────────────
        $allPurposes = [];
        foreach ($result['sections'] as $s) {
            foreach ($s['all_purposes'] as $p) {
                $allPurposes[] = ['section' => $s['name'], 'purpose' => $p];
            }
        }
        foreach ($result['sections'] as &$section) {
            $section['quiz'] = $this->generateQuiz($section, $allPurposes);
        }
        unset($section);

        return $result;
    }

    // -------------------------------------------------------------------------
    // PUBLIC: create the course in the database
    // -------------------------------------------------------------------------

    public function createCourse(array $parsed, array $settings): Course
    {
        $userId             = Auth::id();
        $categoryId         = (int) $settings['category_id'];
        $pricePlan          = $settings['price_plan']         ?? 'free';
        $status             = (int) ($settings['status']      ?? 0);
        $level              = (int) ($settings['level']       ?? 0);
        $generateQuizzes    = (bool) ($settings['generate_quizzes']   ?? true);
        $mediaPlaceholders  = (bool) ($settings['media_placeholders'] ?? true);

        // 1. Course ───────────────────────────────────────────────────────────
        $course = Course::create([
            'user_id'         => $userId,
            'title'           => $parsed['title'] ?: 'Imported Course',
            'slug'            => unique_slug($parsed['title'] ?: 'Imported Course', 'Course'),
            'short_description' => Str::limit(strip_tags($parsed['description']), 220),
            'description'     => $parsed['description'] ?: ($parsed['title'] ?: 'Imported Course'),
            'category_id'     => $categoryId,
            'price_plan'      => $pricePlan,
            'status'          => $status,
            'level'           => $level,
            'last_updated_at' => now(),
        ]);

        $course->instructors()->attach($userId, ['added_at' => now()]);

        // 2. Sections ─────────────────────────────────────────────────────────
        foreach ($parsed['sections'] as $sectionData) {
            $section = Section::create([
                'course_id'    => $course->id,
                'section_name' => $sectionData['name'],
                'sort_order'   => $sectionData['sort_order'],
            ]);

            $contentSort = 0;

            // If section has no lectures, make one lecture from section intro
            if (empty($sectionData['lectures'])) {
                if ($sectionData['intro']) {
                    $contentSort++;
                    Content::create([
                        'user_id'    => $userId,
                        'course_id'  => $course->id,
                        'section_id' => $section->id,
                        'title'      => $sectionData['name'],
                        'slug'       => unique_slug($sectionData['name'], 'Content'),
                        'text'       => $sectionData['intro'],
                        'item_type'  => 'lecture',
                        'status'     => 1,
                        'sort_order' => $contentSort,
                    ]);
                }
            } else {
                foreach ($sectionData['lectures'] as $lec) {
                    $contentSort++;
                    $html = $this->renderLectureHtml($lec, $mediaPlaceholders);

                    Content::create([
                        'user_id'    => $userId,
                        'course_id'  => $course->id,
                        'section_id' => $section->id,
                        'title'      => $lec['title'],
                        'slug'       => unique_slug($lec['title'], 'Content'),
                        'text'       => $html,
                        'item_type'  => 'lecture',
                        'status'     => 1,
                        'sort_order' => $contentSort,
                    ]);
                }
            }

            // 3. Quiz at end of section ────────────────────────────────────────
            if ($generateQuizzes && !empty($sectionData['quiz'])) {
                $contentSort++;
                $quizData = $sectionData['quiz'];

                $quiz = Content::create([
                    'user_id'       => $userId,
                    'course_id'     => $course->id,
                    'section_id'    => $section->id,
                    'title'         => $quizData['title'],
                    'slug'          => unique_slug($quizData['title'], 'Content'),
                    'text'          => $quizData['description'],
                    'item_type'     => 'quiz',
                    'status'        => 1,
                    'sort_order'    => $contentSort,
                    'options'       => json_encode([
                        'passing_score'    => 70,
                        'time_limit'       => 0,
                        'questions_limit'  => count($quizData['questions']),
                    ]),
                    'quiz_gradable' => 0,
                ]);

                $qSort = 0;
                foreach ($quizData['questions'] as $qData) {
                    $qSort++;
                    $question = Question::create([
                        'user_id'    => $userId,
                        'quiz_id'    => $quiz->id,
                        'title'      => $qData['title'],
                        'type'       => $qData['type'],
                        'score'      => $qData['score'],
                        'sort_order' => $qSort,
                    ]);

                    $oSort = 0;
                    foreach ($qData['options'] as $opt) {
                        $oSort++;
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'title'       => $opt['title'],
                            'is_correct'  => $opt['is_correct'] ? 1 : 0,
                            'd_pref'      => 'text',
                            'sort_order'  => $oSort,
                        ]);
                    }
                }
            }
        }

        $course->sync_everything();

        return $course;
    }

    // -------------------------------------------------------------------------
    // PRIVATE: parse helpers
    // -------------------------------------------------------------------------

    private function newSection(string $name, int $sort): array
    {
        return [
            'name'          => $name,
            'sort_order'    => $sort,
            'lectures'      => [],
            'intro'         => '',
            'all_key_terms' => [],
            'all_steps'     => [],
            'all_purposes'  => [],
            'quiz'          => null,
        ];
    }

    private function newLecture(string $title, int $sort): array
    {
        return [
            'title'       => $title,
            'description' => '',
            'sort_order'  => $sort,
            'blocks'      => [],   // structured content blocks
            'key_terms'   => [],
            'steps'       => [],
            'needs_media' => false,
        ];
    }

    private function appendToLecture(array &$lecture, array $block): void
    {
        $lecture['blocks'][] = $block;
    }

    private function closeLecture(?array &$section, ?array &$lecture): void
    {
        if ($section !== null && $lecture !== null) {
            $section['lectures'][] = $lecture;
        }
        $lecture = null;
    }

    private function closeSection(array &$result, ?array &$section): void
    {
        if ($section !== null) {
            $result['sections'][] = $section;
        }
        $section = null;
    }

    private function checkMediaTriggers(array &$lecture, string $text): void
    {
        if ($lecture['needs_media']) return;
        $lower = strtolower($text);
        foreach (self::MEDIA_TRIGGERS as $trigger) {
            if (str_contains($lower, $trigger)) {
                $lecture['needs_media'] = true;
                return;
            }
        }
    }

    // -------------------------------------------------------------------------
    // PRIVATE: render blocks → HTML
    // -------------------------------------------------------------------------

    private function renderLectureHtml(array $lecture, bool $mediaPlaceholders): string
    {
        $html = '';

        if ($lecture['description']) {
            $html .= '<p class="lead">' . $this->inline($lecture['description']) . '</p>';
        }

        if ($mediaPlaceholders && $lecture['needs_media']) {
            $html .= '<div style="border:2px dashed #6c757d;padding:16px;margin:16px 0;border-radius:6px;background:#f8f9fa;">'
                . '<strong>📷 [MEDIA PLACEHOLDER]</strong> '
                . 'Add a screenshot or demonstration video for: <em>' . htmlspecialchars($lecture['title']) . '</em>'
                . '</div>';
        }

        $html .= $this->renderBlocks($lecture['blocks']);

        return $html;
    }

    private function renderBlocks(array $blocks): string
    {
        $html     = '';
        $listBuf  = [];
        $listType = null;

        $flushList = function () use (&$html, &$listBuf, &$listType) {
            if ($listBuf) {
                $tag   = $listType ?? 'ul';
                $html .= "<{$tag}>" . implode('', $listBuf) . "</{$tag}>";
                $listBuf  = [];
                $listType = null;
            }
        };

        $inCode    = false;
        $codeLines = [];

        foreach ($blocks as $block) {
            $type = $block['type'];

            if ($type === 'code_open') {
                $flushList();
                $inCode    = true;
                $codeLines = [];
                continue;
            }
            if ($type === 'code_close') {
                $inCode = false;
                $html  .= '<pre><code>' . htmlspecialchars(implode("\n", $codeLines)) . '</code></pre>';
                $codeLines = [];
                continue;
            }
            if ($type === 'code_line') {
                $codeLines[] = $block['text'];
                continue;
            }
            if ($inCode) {
                $codeLines[] = $block['text'] ?? '';
                continue;
            }

            if ($type === 'li') {
                $tag = $block['list'] ?? 'ul';
                if ($listType && $listType !== $tag) {
                    $flushList();
                }
                $listType  = $tag;
                $listBuf[] = '<li>' . $this->inline($block['text']) . '</li>';
                continue;
            }

            // Any non-list block flushes current list
            $flushList();

            if ($type === 'spacer') {
                continue;
            }
            if ($type === 'p') {
                $html .= '<p>' . $this->inline($block['text']) . '</p>';
                continue;
            }
            if ($type === 'label') {
                $labelHtml = '<p><strong>' . htmlspecialchars($block['label']) . ':</strong>';
                if ($block['text']) {
                    $labelHtml .= ' ' . $this->inline($block['text']);
                }
                $labelHtml .= '</p>';
                $html .= $labelHtml;
                continue;
            }
            if ($type === 'keyval') {
                $html .= '<p><strong>' . htmlspecialchars($block['key']) . ':</strong> '
                    . $this->inline($block['value']) . '</p>';
                continue;
            }
        }

        $flushList();

        return $html;
    }

    // Render inline markdown: **bold**, *italic*, `code`
    private function inline(string $text): string
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $text);
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
        return $text;
    }

    // -------------------------------------------------------------------------
    // PRIVATE: quiz generation
    // -------------------------------------------------------------------------

    private function generateQuiz(array $section, array $allPurposes): ?array
    {
        $questions   = [];
        $sectionName = $section['name'];

        // Purposes from other sections → wrong answers for "purpose" questions
        $otherPurposes = array_values(array_filter(
            $allPurposes,
            fn($p) => $p['section'] !== $sectionName
        ));
        $otherPurposeTexts = array_column($otherPurposes, 'purpose');

        // ── Q1: What is the purpose of [lecture]? ────────────────────────────
        foreach ($section['lectures'] as $lecture) {
            if (count($questions) >= 1) break;
            if (!$lecture['description']) continue;

            $correct = Str::limit($lecture['description'], 120, '…');
            $wrongs  = $this->buildWrongAnswers($correct, $otherPurposeTexts, 3);

            $questions[] = [
                'title'   => "What is the primary purpose of the \"{$lecture['title']}\" feature?",
                'type'    => 'radio',
                'score'   => 5,
                'options' => $this->shuffledOptions($correct, $wrongs),
            ];
        }

        // ── Q2: Key terms (checkbox) ─────────────────────────────────────────
        $allTerms = array_values(array_unique($section['all_key_terms']));
        if (count($allTerms) >= 4) {
            shuffle($allTerms);
            $terms = array_filter($allTerms, fn($t) => strlen($t) <= 60);
            $terms = array_values($terms);

            if (count($terms) >= 2) {
                $correct1 = $terms[0];
                $correct2 = $terms[1];
                $distractors = self::GENERIC_DISTRACTORS;
                shuffle($distractors);

                $questions[] = [
                    'title'   => "Which of the following are found in the \"{$sectionName}\" section? (Select all that apply)",
                    'type'    => 'checkbox',
                    'score'   => 5,
                    'options' => $this->shuffledRaw([
                        ['title' => $correct1, 'is_correct' => true],
                        ['title' => $correct2, 'is_correct' => true],
                        ['title' => Str::limit($distractors[0], 80, '…'), 'is_correct' => false],
                        ['title' => Str::limit($distractors[1], 80, '…'), 'is_correct' => false],
                    ]),
                ];
            }
        }

        // ── Q3: First step in a workflow ─────────────────────────────────────
        $allSteps = $section['all_steps'];
        if (count($allSteps) >= 4) {
            $firstStep  = Str::limit($allSteps[0], 100, '…');
            $otherSteps = array_slice($allSteps, 1);
            shuffle($otherSteps);
            $wrongs = array_map(fn($s) => Str::limit($s, 100, '…'), array_slice($otherSteps, 0, 3));

            // Find the workflow lecture name
            $workflowName = $sectionName;
            foreach ($section['lectures'] as $lec) {
                if (!empty($lec['steps'])) {
                    $workflowName = $lec['title'];
                    break;
                }
            }

            $questions[] = [
                'title'   => "When completing \"{$workflowName}\", what is the FIRST step?",
                'type'    => 'radio',
                'score'   => 5,
                'options' => $this->shuffledOptions($firstStep, $wrongs),
            ];
        }

        // ── Q4: True / False ─────────────────────────────────────────────────
        foreach ($section['lectures'] as $lecture) {
            if (count($questions) >= 4) break;
            if (!$lecture['description']) continue;

            $stmt = Str::limit($lecture['description'], 150, '…');
            $questions[] = [
                'title'   => "True or False: The \"{$lecture['title']}\" section is used to: \"{$stmt}\"",
                'type'    => 'radio',
                'score'   => 3,
                'options' => [
                    ['title' => 'True',  'is_correct' => true],
                    ['title' => 'False', 'is_correct' => false],
                ],
            ];
        }

        if (empty($questions)) {
            return null;
        }

        return [
            'title'       => "Knowledge Check: {$sectionName}",
            'description' => "Test your understanding of the {$sectionName} section.",
            'questions'   => $questions,
        ];
    }

    private function buildWrongAnswers(string $correct, array $fromPurposes, int $count): array
    {
        $wrongs = [];
        foreach ($fromPurposes as $p) {
            $limited = Str::limit($p, 120, '…');
            if ($limited !== $correct) {
                $wrongs[] = $limited;
            }
            if (count($wrongs) >= $count) break;
        }

        // Pad with generic distractors
        $distractors = self::GENERIC_DISTRACTORS;
        shuffle($distractors);
        foreach ($distractors as $d) {
            if (count($wrongs) >= $count) break;
            $limited = Str::limit($d, 120, '…');
            if ($limited !== $correct && !in_array($limited, $wrongs)) {
                $wrongs[] = $limited;
            }
        }

        return array_slice($wrongs, 0, $count);
    }

    private function shuffledOptions(string $correct, array $wrongs): array
    {
        $options = [['title' => $correct, 'is_correct' => true]];
        foreach ($wrongs as $w) {
            $options[] = ['title' => $w, 'is_correct' => false];
        }
        shuffle($options);
        return $options;
    }

    private function shuffledRaw(array $options): array
    {
        shuffle($options);
        return $options;
    }
}
