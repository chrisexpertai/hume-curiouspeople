@extends('layouts.admin')

@section('page-header-right')
    <a href="{{route('category_index')}}" class="btn btn-secondary me-2" data-bs-toggle="tooltip" title="@lang('admin.categories')">
        <i class="bi bi-folder2"></i>
    </a>

    <button type="submit" form="form-category" class="btn btn-primary" data-bs-toggle="tooltip" title="@lang('admin.save')">
        <i class="bi bi-save"></i>
    </button>
@endsection

@section('content')

    <form action="" id="form-category" method="post" enctype="multipart/form-data">
        @csrf

        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="card-header">@lang('admin.add_new_category')</h2>

                    <div class="mb-3">
                        <label for="category_name" class="form-label">@lang('admin.category_name')</label>
                        <input type="text" name="category_name" value="" class="form-control" id="category_name" placeholder="@lang('admin.category_name')">
                        {!! $errors->has('category_name')? '<p class="help-text">'.$errors->first('category_name').'</p>':'' !!}
                    </div>

                    <div class="mb-3">
                        <label for="parent" class="form-label">@lang('admin.parent')</label>
                        <select name="parent" id="parent" class="form-select">
                            <option value="0">@lang('admin.select_category')</option>
                            @if($categories->count() > 0)
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}"> {!! $category->category_name !!} </option>
                                    @foreach($category->sub_categories as $subCategory)
                                        <option value="{{$subCategory->id}}"> &nbsp;&nbsp;&nbsp; &raquo; {!! $subCategory->category_name !!} </option>
                                    @endforeach
                                @endforeach
                            @endif
                        </select>
                        {!! $errors->has('parent')? '<p class="help-block">'.$errors->first('parent').'</p>':'' !!}
                    </div>

                  <div class="mb-3">
                        <label class="form-label">{{__a('select_icon')}}</label>
                        <select class="form-select select2icon" name="icon_class">
                            <option value="0">{{__a('select_icon')}}</option>
                            @foreach(icon_classes() as $icon => $val)
                                <option value="{{ $icon }}" data-icon="{{ $icon }}">{{ $icon }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">@lang('admin.status')</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="publish" value="1" checked="checked">
                            <label class="form-check-label" for="publish">
                                {{__a('publish')}}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="unpublish" value="0">
                            <label class="form-check-label" for="unpublish">
                                {{__a('unpublish')}}
                            </label>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" form="form-category" class="btn btn-primary">
                            <i class="bi bi-save"></i> {{__a('save')}}
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </form>



<script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script>
<script src="/assets/vendor/jquery/jquery-ui.min.js"></script>
  <script src="/assets/vendor/select2/select2.min.js"></script>
<link href="/assets/vendor/select2/select2.css" rel="stylesheet"/>


@endsection
