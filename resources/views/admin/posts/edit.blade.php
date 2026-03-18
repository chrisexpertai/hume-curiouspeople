@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card card-body">
        <h1>{{ tr('Edit Post') }}</h1>
        <form action="{{ route('admin.posts.update', $post->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">{{ tr('Title') }}:</label>
                <input type="text" class="form-control" name="title" value="{{ $post->title }}" required>
            </div>

            <!-- Topic -->
            <div class="mb-3">
                <label for="topic" class="form-label">{{ tr('Topic') }}:</label>
                <input type="text" class="form-control" name="topic" value="{{ $post->topic }}" placeholder="{{ tr('Enter the topic of the post') }}">
            </div>

            <!-- Tags -->
            <div class="mb-3">
                <label for="tags" class="form-label">{{ tr('Tags') }}:</label>
                <input type="text" class="form-control" name="tags" value="{{ $post->tags->pluck('name')->implode(',') }}" placeholder="{{ tr('Enter tags separated by commas') }}">
            </div>

            <!-- Short Description -->
            <div class="mb-3">
                <label for="short_description" class="form-label">{{ tr('Short Description') }}:</label>
                <textarea class="form-control" id="short_description" name="short_description" required rows="5">{{ $post->short_description }}</textarea>
            </div>

            <!-- Content -->
            <div class="mb-3">
                <label for="content" class="form-label">{{ tr('Content') }}:</label>
                <textarea class="form-control" id="editor" name="content" required>{{ $post->content }}</textarea>
            </div>

            <!-- Existing Featured Image -->
            <div class="mb-3">
                @if($post->featured_image)
                    <label for="existing_image" class="form-label">{{ tr('Existing Featured Image') }}:</label>
                    <div class="d-flex align-items-center">
                        <img src="{{ asset($post->featured_image) }}" class="img-fluid mb-4 mr-3" alt="{{ tr('Existing Featured Image') }}">
                        <button type="button" class="btn btn-danger" id="deleteFeaturedImageBtn">{{ tr('Delete Image') }}</button>
                    </div>
                    <input type="hidden" name="remove_featured_image" id="remove_featured_image" value="0">
                @endif
            </div>

            <!-- New Featured Image -->
            <div class="mb-3">
                <label for="featured_image" class="form-label">{{ tr('New Featured Image') }}: ({{ tr('Optional') }}) </label>
                <input type="file" class="form-control" name="featured_image">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">{{ tr('Update') }}</button>
        </form>
    </div>
</div>

<!-- Include CKEditor JS -->
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

    // Handle delete featured image button click
    document.getElementById('deleteFeaturedImageBtn').addEventListener('click', function() {
        if (confirm("{{ tr('Are you sure you want to delete this image?') }}")) {
            // Send an AJAX request to delete the image
            fetch("{{ route('admin.posts.delete_image', $post->slug) }}", {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
            .then(response => {
                if (response.ok) {
                    // Reload the page
                    window.location.reload();
                } else {
                    alert("{{ tr('Failed to delete image.') }}");
                }
            })
            .catch(error => {
                console.error(error);
                alert("{{ tr('An error occurred while processing your request.') }}");
            });
        }
    });
</script>
@endsection
