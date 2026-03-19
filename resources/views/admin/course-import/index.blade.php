@extends('layouts.admin')

@section('content')
<div class="page-content-wrapper border">

    {{-- Page header --}}
    <div class="row mb-4">
        <div class="col-12 d-sm-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1">Course Importer</h1>
                <p class="text-muted mb-0">Paste a structured document and generate a full course with sections, lectures, and quizzes automatically.</p>
            </div>
            <a href="{{ route('admin_courses') }}" class="btn btn-outline-secondary mt-2 mt-sm-0">
                <i class="bi bi-arrow-left me-1"></i> All Courses
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- ── Left column: input form ── --}}
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary bg-opacity-10 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Document Input</h5>
                </div>
                <div class="card-body d-flex flex-column">

                    {{-- Document format help --}}
                    <div class="alert alert-info py-2 mb-3 small">
                        <strong>Supported format:</strong>
                        Use Markdown-style headings to define structure:
                        <code># Course Title</code> &nbsp;·&nbsp;
                        <code>## Section Name</code> &nbsp;·&nbsp;
                        <code>### Lecture Title</code><br>
                        Add <code>Purpose:</code>, <code>Key fields:</code>, <code>Actions:</code>, <code>Features:</code>
                        labels to auto-generate quizzes from the content.
                    </div>

                    {{-- Paste area --}}
                    <div class="mb-3 flex-grow-1">
                        <label for="docText" class="form-label fw-semibold">Paste your document here</label>
                        <textarea id="docText" name="text" class="form-control font-monospace"
                            rows="18" placeholder="# My Course Title&#10;&#10;## Section One&#10;&#10;### Lecture One&#10;Purpose: Introduce the topic.&#10;..."></textarea>
                        <div class="form-text">Minimum 20 characters. The parser understands Markdown headings, bullet lists, numbered steps, and labeled fields.</div>
                    </div>

                    {{-- Parse preview button --}}
                    <div class="d-flex gap-2">
                        <button type="button" id="btnPreview" class="btn btn-secondary flex-grow-1">
                            <i class="bi bi-eye me-1"></i> Parse &amp; Preview Structure
                        </button>
                        <button type="button" id="btnClear" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Right column: settings + preview ── --}}
        <div class="col-lg-6">

            {{-- Course settings card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success bg-opacity-10 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Course Settings</h5>
                </div>
                <div class="card-body">
                    <form id="importForm" method="POST" action="{{ route('admin.course-import.import') }}">
                        @csrf

                        {{-- Hidden textarea that mirrors the paste area on submit --}}
                        <input type="hidden" name="text" id="hiddenText">

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">— Select category —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->getCategoryNameParent() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price Plan</label>
                                <select name="price_plan" class="form-select">
                                    <option value="free"         {{ old('price_plan','free') === 'free'         ? 'selected' : '' }}>Free</option>
                                    <option value="paid"         {{ old('price_plan','free') === 'paid'         ? 'selected' : '' }}>Paid</option>
                                    <option value="subscription" {{ old('price_plan','free') === 'subscription' ? 'selected' : '' }}>Subscription</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Level</label>
                                <select name="level" class="form-select">
                                    <option value="0" {{ old('level','0') == '0' ? 'selected' : '' }}>All Levels</option>
                                    <option value="1" {{ old('level','0') == '1' ? 'selected' : '' }}>Beginner</option>
                                    <option value="2" {{ old('level','0') == '2' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="3" {{ old('level','0') == '3' ? 'selected' : '' }}>Advanced</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Initial Status</label>
                                <select name="status" class="form-select">
                                    <option value="0" {{ old('status','0') == '0' ? 'selected' : '' }}>Draft (hidden)</option>
                                    <option value="1" {{ old('status','0') == '1' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-block">Options</label>
                                <div class="form-check form-switch mt-1">
                                    <input class="form-check-input" type="checkbox" name="generate_quizzes"
                                        id="chkQuizzes" value="1" {{ old('generate_quizzes', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chkQuizzes">Generate quizzes</label>
                                </div>
                                <div class="form-check form-switch mt-1">
                                    <input class="form-check-input" type="checkbox" name="media_placeholders"
                                        id="chkMedia" value="1" {{ old('media_placeholders', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="chkMedia">Add media placeholders</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <button type="submit" id="btnImport" class="btn btn-primary w-100" disabled>
                            <i class="bi bi-cloud-upload me-2"></i>Import Course
                        </button>
                        <div class="form-text text-center mt-1">Preview the structure first, then import.</div>

                    </form>
                </div>
            </div>

            {{-- Structure preview card --}}
            <div class="card shadow-sm" id="previewCard" style="display:none;">
                <div class="card-header bg-warning bg-opacity-10 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Parsed Structure</h5>
                    <span id="previewBadge" class="badge bg-secondary">0 sections</span>
                </div>
                <div class="card-body p-0" style="max-height:440px;overflow-y:auto;">
                    <div id="previewTree" class="p-3"></div>
                </div>
            </div>

            {{-- Loading spinner --}}
            <div id="previewLoading" class="text-center py-4" style="display:none;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">Parsing document…</p>
            </div>

        </div>{{-- /right col --}}
    </div>{{-- /row --}}

    {{-- ── Format reference ── --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-question-circle me-1"></i>Document Format Reference</h6>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <h6 class="fw-semibold">Structure (Headings)</h6>
                    <table class="table table-sm table-bordered small mb-0">
                        <thead class="table-light"><tr><th>Syntax</th><th>Creates</th></tr></thead>
                        <tbody>
                            <tr><td><code># Title</code></td><td>Course title</td></tr>
                            <tr><td><code>## Name</code></td><td>Section</td></tr>
                            <tr><td><code>### Name</code></td><td>Lecture</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-semibold">Labeled Fields (Quiz Sources)</h6>
                    <table class="table table-sm table-bordered small mb-0">
                        <thead class="table-light"><tr><th>Label</th><th>Effect</th></tr></thead>
                        <tbody>
                            <tr><td><code>Purpose:</code></td><td>Lecture description + quiz Q</td></tr>
                            <tr><td><code>Key fields:</code></td><td>Key terms → quiz options</td></tr>
                            <tr><td><code>Key items:</code></td><td>Key terms → quiz options</td></tr>
                            <tr><td><code>Actions:</code></td><td>Quiz-able list items</td></tr>
                            <tr><td><code>Features:</code></td><td>Quiz-able list items</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-semibold">Content Types</h6>
                    <table class="table table-sm table-bordered small mb-0">
                        <thead class="table-light"><tr><th>Syntax</th><th>Creates</th></tr></thead>
                        <tbody>
                            <tr><td><code>- item</code> or <code>* item</code></td><td>Bullet list</td></tr>
                            <tr><td><code>1. step</code></td><td>Numbered step</td></tr>
                            <tr><td><code>**bold**</code></td><td>Bold text</td></tr>
                            <tr><td><code>`code`</code></td><td>Inline code</td></tr>
                            <tr><td><code>```...```</code></td><td>Code block</td></tr>
                            <tr><td><code>---</code></td><td>Ignored (separator)</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const docText      = document.getElementById('docText');
    const hiddenText   = document.getElementById('hiddenText');
    const btnPreview   = document.getElementById('btnPreview');
    const btnClear     = document.getElementById('btnClear');
    const btnImport    = document.getElementById('btnImport');
    const previewCard  = document.getElementById('previewCard');
    const previewTree  = document.getElementById('previewTree');
    const previewBadge = document.getElementById('previewBadge');
    const spinner      = document.getElementById('previewLoading');

    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Restore any previously entered text (back navigation)
    @if(old('text'))
    docText.value = @json(old('text'));
    @endif

    // ── Clear ────────────────────────────────────────────────────────────────
    btnClear.addEventListener('click', () => {
        docText.value = '';
        previewCard.style.display = 'none';
        spinner.style.display     = 'none';
        btnImport.disabled        = true;
        previewTree.innerHTML     = '';
    });

    // ── Parse & Preview ──────────────────────────────────────────────────────
    btnPreview.addEventListener('click', async () => {
        const text = docText.value.trim();
        if (!text || text.length < 20) {
            alert('Please paste a document of at least 20 characters.');
            return;
        }

        btnPreview.disabled   = true;
        previewCard.style.display = 'none';
        spinner.style.display = 'block';

        try {
            const res = await fetch('{{ route('admin.course-import.preview') }}', {
                method:  'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept':       'application/json',
                },
                body: JSON.stringify({ text }),
            });

            const data = await res.json();
            spinner.style.display = 'none';

            if (!data.ok) {
                alert('Parse error: ' + (data.error || 'Unknown error'));
                return;
            }

            renderPreview(data.summary);
            hiddenText.value       = text;
            btnImport.disabled     = false;
            previewCard.style.display = 'block';

        } catch (err) {
            spinner.style.display = 'none';
            alert('Network error: ' + err.message);
        } finally {
            btnPreview.disabled = false;
        }
    });

    // ── Sync hidden textarea on import submit ────────────────────────────────
    document.getElementById('importForm').addEventListener('submit', () => {
        hiddenText.value = docText.value;
    });

    // ── Render preview tree ──────────────────────────────────────────────────
    function renderPreview(summary) {
        const sections = summary.sections || [];
        previewBadge.textContent = sections.length + ' section' + (sections.length !== 1 ? 's' : '');
        previewBadge.className   = 'badge bg-' + (sections.length ? 'success' : 'secondary');

        let html = '';

        // Course title
        html += `<div class="fw-bold fs-5 mb-3">
            <i class="bi bi-mortarboard-fill text-primary me-2"></i>
            ${escHtml(summary.title)}
        </div>`;

        if (!sections.length) {
            html += '<div class="alert alert-warning">No sections detected. Make sure you have <code>## Section</code> headings.</div>';
            previewTree.innerHTML = html;
            return;
        }

        sections.forEach((section, si) => {
            const collapseId = 'sec-' + si;
            html += `
            <div class="border rounded mb-2 overflow-hidden">
                <button class="btn btn-light w-100 text-start d-flex align-items-center gap-2 px-3 py-2"
                    type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}">
                    <i class="bi bi-folder2 text-warning"></i>
                    <span class="fw-semibold flex-grow-1">${escHtml(section.name)}</span>
                    <span class="badge bg-primary bg-opacity-75 ms-1">${section.lecture_count} lecture${section.lecture_count !== 1 ? 's' : ''}</span>
                    ${section.has_quiz ? '<span class="badge bg-success ms-1"><i class="bi bi-patch-question me-1"></i>' + section.quiz_q_count + ' Qs</span>' : ''}
                    <i class="bi bi-chevron-down ms-auto"></i>
                </button>
                <div class="collapse" id="${collapseId}">
                    <ul class="list-group list-group-flush">`;

            section.lectures.forEach(lec => {
                const mediaIcon = lec.needs_media
                    ? '<span class="badge bg-info bg-opacity-75 ms-2" title="Media placeholder will be added"><i class="bi bi-camera me-1"></i>media</span>'
                    : '';
                html += `
                        <li class="list-group-item py-2 px-3 d-flex align-items-center">
                            <i class="bi bi-play-circle text-primary me-2"></i>
                            <span class="flex-grow-1">${escHtml(lec.title)}</span>
                            ${mediaIcon}
                        </li>`;
            });

            if (section.has_quiz) {
                html += `
                        <li class="list-group-item py-2 px-3 d-flex align-items-center bg-success bg-opacity-10">
                            <i class="bi bi-patch-question-fill text-success me-2"></i>
                            <span class="flex-grow-1 fst-italic text-success">${escHtml(section.quiz_title)}</span>
                            <span class="badge bg-success">${section.quiz_q_count} questions</span>
                        </li>`;
            }

            html += `    </ul>
                </div>
            </div>`;
        });

        // Summary counts
        const totalLectures = sections.reduce((a, s) => a + s.lecture_count, 0);
        const totalQuizzes  = sections.filter(s => s.has_quiz).length;
        const totalQs       = sections.reduce((a, s) => a + s.quiz_q_count, 0);
        const mediaCount    = sections.reduce((a, s) => a + s.lectures.filter(l => l.needs_media).length, 0);

        html += `
        <div class="mt-3 p-3 bg-light border rounded small">
            <div class="row g-2 text-center">
                <div class="col-3">
                    <div class="fw-bold text-primary fs-5">${sections.length}</div>
                    <div class="text-muted">Sections</div>
                </div>
                <div class="col-3">
                    <div class="fw-bold text-primary fs-5">${totalLectures}</div>
                    <div class="text-muted">Lectures</div>
                </div>
                <div class="col-3">
                    <div class="fw-bold text-success fs-5">${totalQuizzes}</div>
                    <div class="text-muted">Quizzes (${totalQs} Qs)</div>
                </div>
                <div class="col-3">
                    <div class="fw-bold text-info fs-5">${mediaCount}</div>
                    <div class="text-muted">Media spots</div>
                </div>
            </div>
        </div>`;

        previewTree.innerHTML = html;
    }

    function escHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
@endpush
