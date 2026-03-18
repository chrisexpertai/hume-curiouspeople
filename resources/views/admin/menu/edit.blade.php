<!-- resources/views/admin/menu/edit.blade.php -->

@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Edit Menu</h2>
        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">{{ tr('Name') }}:</label>
                <input type="text" class="form-control" name="name" value="{{ $menu->name }}" required>
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">{{ tr('URL') }}:</label>
                <input type="text" class="form-control" name="url" value="{{ $menu->url }}" required>
            </div>

            <div class="mb-3">
                <label for="icon" class="form-label">{{ tr('Icon') }}:</label>
                <input type="text" class="form-control" name="icon" value="{{ $menu->icon }}">
            </div>

            <div class="mb-3">
                <label for="order" class="form-label">{{ tr('Sort Order') }}:</label>
                <input type="number" class="form-control" name="order" value="{{ $menu->order }}" required>
            </div>

            <button type="submit" class="btn btn-primary">{{ tr('Update Menu') }}</button>
        </form>
    </div>
@endsection
