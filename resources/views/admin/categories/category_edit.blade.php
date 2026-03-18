@extends('layouts.admin')

@section('page-header-right')
    <a href="{{ route('category_index') }}" class="btn btn-secondary me-2" data-bs-toggle="tooltip" title="@lang('admin.categories')">
        <i class="bi bi-folder2"></i>
    </a>

    <a href="{{ route('category_create') }}" class="btn btn-primary me-2" data-bs-toggle="tooltip" title="@lang('admin.category_add')">
        <i class="bi bi-plus"></i>
    </a>

    <button type="submit" form="form-category" class="btn btn-primary" data-bs-toggle="tooltip" title="@lang('admin.save')">
        <i class="bi bi-save"></i>
    </button>
@endsection

@section('content')


<script src="/assets/vendor/select2/select2.min.js"></script>
<link rel="stylesheet" href="/assets/vendor/select2/select2.css">

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        <form action="" id="form-category" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="category_name" class="form-label">@lang('admin.category_name')</label>
                                <input type="text" name="category_name" value="{{ $category->category_name }}" class="form-control" id="category_name" placeholder="@lang('admin.category_name')">
                                @if($errors->has('category_name'))
                                    <p class="help-text">{{ $errors->first('category_name') }}</p>
                                @endif
                            </div>



                            <div class="mb-3">
                                <label for="parent" class="form-label">@lang('admin.parent')</label>
                                <select name="parent" id="parent" class="form-select">
                                    <option value="0">@lang('admin.select_category')</option>
                                    @if($categories->count() > 0)
                                        @foreach($categories as $parent)
                                            <option value="{{ $parent->id }}" {{ selected($category->category_id, $parent->id) }}>
                                                {{ $parent->category_name }}
                                            </option>

                                            @foreach($parent->sub_categories as $subCategory)
                                                <option value="{{ $subCategory->id }}" {{ selected($category->category_id, $subCategory->id) }}>
                                                    &nbsp;&nbsp;&nbsp; &raquo; {!! $subCategory->category_name !!}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('parent'))
                                    <p class="help-text">{{ $errors->first('parent') }}</p>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 form-labe">{{__a('select_icon')}}</label>
                                <div class="col-sm-12">
                                    <select class="form-control select2icon" name="icon_class">
                                        <option value="0">{{__a('select_icon')}}</option>
                                        @foreach(icon_classes() as $icon => $val)
                                            <option value="{{$icon}}" {{selected($icon, $category->icon_class)}} data-icon="{{$icon}}">{{$icon}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>







                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="publish" value="1" {{ $category->status == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="publish">
                                        @lang('admin.publish')
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="unpublish" value="0" {{ $category->status == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="unpublish">
                                        @lang('admin.unpublish')
                                    </label>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" form="form-category" class="btn btn-primary">
                                    <i class="bi bi-save"></i> {{ __a('save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="/assets/vendor/jquery/jquery-ui.min.js"></script>
      <script src="/assets/vendor/select2/select2.min.js"></script>
    <link href="/assets/vendor/select2/select2.css" rel="stylesheet" />



@endsection
