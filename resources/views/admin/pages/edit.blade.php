@extends('layouts.admin')

@section('content')

<div class="container">
    <div class="card card-body">
        <h1>{{ tr('Edit Page') }}</h1>
        <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">{{ tr('Page Title') }}:</label>
                <input type="text" class="form-control" name="title" value="{{ $page->title }}" required>
            </div>

            <div class="mb-3">
                <label for="slug" class="form-label">{{ tr('Page Slug') }}: ({{ tr('optional') }}) </label>
                <input type="text" class="form-control" name="slug" value="{{ $page->slug }}">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">{{ tr('Content') }}:</label>
                <textarea class="form-control" id="editor" name="content" required>{{ $page->content }}</textarea>
            </div>

            <div class="mb-3">
                <label for="featured_image" class="form-label">{{ tr('Featured Image') }}: ({{ tr('optional') }}) </label>
                @if ($page->featured_image)
                <div class="d-flex align-items-center">
                    <img src="{{ asset($page->featured_image ?: '/assets/images/placeholder-image.png') }}" alt="Featured Image" class="img-thumbnail mr-2" style="max-width: 150px;">
                    <button type="button" class="btn btn-sm btn-danger" id="deleteImageBtn">{{ tr('Delete Image') }}</button>
                </div>
                @endif
                <input type="file" class="form-control mt-2" name="featured_image">
            </div>

            <button type="submit" class="btn btn-primary">{{ tr('Update') }}</button>
        </form>
    </div>
</div>

<!-- Include CKEditor JS -->
<script src="/assets/vendor/ckeditor5/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });

    // Handle delete image button click
    document.getElementById('deleteImageBtn').addEventListener('click', function() {
        if (confirm("{{ tr('Are you sure you want to delete this image?') }}")) {
            // Send an AJAX request to delete the image
            fetch("{{ route('admin.pages.delete_image', $page->slug) }}", {
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
