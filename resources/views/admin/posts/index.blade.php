@extends('layouts.admin')

@section('content')
<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="card-header bg-transparent border-bottom px-0">
        <h1 class="mb-0">{{ tr('Manage Posts') }}</h1>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">{{ tr('Create New Post') }}</a>
            </div>

            @if ($posts->isEmpty())
                <div class="alert alert-info" role="alert">
                    {{ tr('No Posts Available') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ tr('Title') }}</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td>
                                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-sm btn-info mr-2">{{ tr('View') }}</a>
                                        <a href="{{ route('admin.posts.edit', $post->slug) }}" class="btn btn-sm btn-warning mr-2">{{ tr('Edit') }}</a>
                                        <form action="{{ route('admin.posts.destroy', $post->slug) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">{{ tr('delete_button') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">{{ tr('No Posts Avaialble') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
