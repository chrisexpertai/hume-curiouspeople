<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CourseImporterService;
use Illuminate\Http\Request;
use App\Models\Category;

class CourseImportController extends Controller
{
    public function __construct(private CourseImporterService $importer) {}

    /**
     * Show the import form.
     */
    public function index()
    {
        $categories = Category::orderBy('category_name')->get();
        return view('admin.course-import.index', compact('categories'));
    }

    /**
     * AJAX: parse the pasted text and return a preview structure.
     */
    public function preview(Request $request)
    {
        $request->validate(['text' => 'required|string|min:20']);

        try {
            $parsed = $this->importer->parse($request->input('text'));

            // Build a lightweight summary for the frontend tree
            $summary = [
                'title'       => $parsed['title'] ?: '(No title found — add a # Heading at the top)',
                'description' => $parsed['description'],
                'sections'    => array_map(function ($section) {
                    $lectureCount = count($section['lectures']);
                    $hasQuiz      = !empty($section['quiz']);
                    $quizQCount   = $hasQuiz ? count($section['quiz']['questions']) : 0;

                    $lectures = array_map(fn($l) => [
                        'title'       => $l['title'],
                        'needs_media' => $l['needs_media'],
                        'key_term_count' => count($l['key_terms']),
                        'has_steps'   => !empty($l['steps']),
                    ], $section['lectures']);

                    return [
                        'name'          => $section['name'],
                        'lecture_count' => $lectureCount,
                        'has_quiz'      => $hasQuiz,
                        'quiz_title'    => $hasQuiz ? $section['quiz']['title'] : null,
                        'quiz_q_count'  => $quizQCount,
                        'lectures'      => $lectures,
                    ];
                }, $parsed['sections']),
            ];

            return response()->json(['ok' => true, 'summary' => $summary]);

        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * POST: parse + create the course in the database.
     */
    public function import(Request $request)
    {
        $request->validate([
            'text'               => 'required|string|min:20',
            'category_id'        => 'required|exists:categories,id',
            'price_plan'         => 'required|in:free,paid,subscription',
            'level'              => 'required|integer|between:0,3',
            'status'             => 'required|in:0,1',
            'generate_quizzes'   => 'nullable|boolean',
            'media_placeholders' => 'nullable|boolean',
        ]);

        try {
            $parsed = $this->importer->parse($request->input('text'));

            if (empty($parsed['title'])) {
                return back()
                    ->withInput()
                    ->withErrors(['text' => 'No course title found. Add a line starting with "# Your Course Title" at the top of your document.']);
            }

            if (empty($parsed['sections'])) {
                return back()
                    ->withInput()
                    ->withErrors(['text' => 'No sections detected. Use "## Section Name" headings to define sections.']);
            }

            $course = $this->importer->createCourse($parsed, [
                'category_id'        => $request->input('category_id'),
                'price_plan'         => $request->input('price_plan'),
                'level'              => $request->input('level'),
                'status'             => $request->input('status'),
                'generate_quizzes'   => (bool) $request->input('generate_quizzes', true),
                'media_placeholders' => (bool) $request->input('media_placeholders', true),
            ]);

            $lectureCount = $course->lectures->count();
            $quizCount    = $course->quizzes->count();
            $sectionCount = $course->sections->count();

            return redirect()
                ->route('admin_courses')
                ->with('success',
                    "Course \"{$course->title}\" imported successfully! "
                    . "{$sectionCount} sections, {$lectureCount} lectures, {$quizCount} quizzes created."
                );

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['text' => 'Import failed: ' . $e->getMessage()]);
        }
    }
}
