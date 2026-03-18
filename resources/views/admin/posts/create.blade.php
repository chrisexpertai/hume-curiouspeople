<!-- resources/views/admin/posts/create.blade.php -->

@extends('layouts.admin')

@section('content')

<div class="container">

    <div class="card card-body ">
    <div class="container">
        <h1>{{ tr('Create a New Post') }}</h1>
        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">{{ tr('Title') }}:</label>
                <input type="text" class="form-control" name="title" required>
            </div>

            <div class="mb-3">
                <label for="topic" class="form-label">{{ tr('Topic') }}: ({{ tr('required') }}) </label>
                <input type="text" class="form-control" name="topic" placeholder="Enter the topic of the post" required>
            </div>


            <div class="mb-3">
                <label for="tags" class="form-label">{{ tr('Tags') }}: ({{ tr('required') }}) </label>
                <input type="text" class="form-control" name="tags" placeholder="Enter tags separated by commas" required>
            </div>

            <div class="mb-3">
                <label for="short_description" class="form-label">{{ tr('Top Content') }}:</label>
                <textarea class="form-control summernote" name="short_description" rows="10" required></textarea>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">{{ tr('Lower Content') }}:</label>
                <textarea id="editor" class="form-control summernote" name="content" required></textarea>
            </div>

            <div class="mb-3">
                <label for="featured_image" class="form-label">{{ tr('Featured Image') }}: ({{ tr('optional') }}) </label>
                <input type="file" class="form-control" name="featured_image">
            </div>

            <button type="submit" class="btn btn-primary">{{ tr('Create Post') }}</button>
        </form>
    </div>

</div><script src="/assets/vendor/ckeditor5/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editorTextarea = document.querySelector('textarea[name="content"]');
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
    });
</script>


@endsection
