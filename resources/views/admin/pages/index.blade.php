@extends('layouts.admin')

@section('content')






	<!-- Page main content START -->
	<div class="page-content-wrapper border">

		<!-- Title -->


		<!-- Card START -->
		<div class="card bg-transparent">

			<!-- Card header START -->
			<div class="card-header bg-transparent border-bottom px-0">
        <h1 class="mb-0">{{ tr('Manage Pages') }}</h1>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">{{ tr('Create New Page') }}</a>
    </div>

    @if ($pages->isEmpty())
        <div class="alert alert-info" role="alert">
            No pages available.
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
                    @foreach ($pages as $page)
                        <tr>
                            <td>{{ $page->title }}</td>
                            <td>
                                <a href="{{ route('pages.show', $page->slug) }}" class="btn btn-sm btn-info mr-2">{{ tr('View') }}</a>
                                <a href="{{ route('admin.pages.edit', $page->slug) }}" class="btn btn-sm btn-warning mr-2">{{ tr('Edit') }}</a>
                                <form action="{{ route('admin.pages.destroy', $page->slug) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this page?')">{{ tr('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $pages->links() }}
        </div>
    @endif
</div>

</div>
@endsection
