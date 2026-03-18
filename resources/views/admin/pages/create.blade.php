@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="card card-body ">
<div class="container p-5">
    <h1 class="mb-4">{{ tr('Create a New Page') }}</h1>
    <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">{{ tr('Page Title') }}:</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">{{ tr('Page Slug') }}: ({{ tr('optional_label') }})</label>
            <input type="text" class="form-control" id="slug" name="slug">
        </div>

        <!-- Container for the editor -->
        <div id="editor">
         </div>

        <!-- Hidden textarea element to store editor content -->
        <textarea name="content" id="content" style="display:none;"></textarea>

        <div class="mb-3">
            <label for="featured_image" class="form-label">{{ tr('Featured Image') }}: ({{ tr('optional_label') }})</label>
            <input type="file" class="form-control" id="featured_image" name="featured_image">
        </div>

        <button type="submit" class="btn btn-primary">{{ tr('Create Page') }}</button>
    </form>
</div>
</div>
<script src="/assets/vendor/ckeditor5/ckeditor.js"></script>
<script>
    const editorTextarea = document.querySelector('#content');
    const editorContainer = document.querySelector('#editor');

    ClassicEditor
        .create(editorContainer)
        .then(editor => {
            // Add the editor content to the hidden textarea on change
            editor.model.document.on('change:data', () => {
                editorTextarea.value = editor.getData();
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>





@endsection
