@extends('front.dashboard.courses.layout')

@section('content')
    @include(theme('dashboard.courses.course_nav'))

    <div class="container">
        <div class="curriculum-top-nav d-flex bg-white mb-4 p-3 border rounded-3">
            <h4 class="flex-grow-1"><i class="fas fa-list-alt me-2"></i>{{__t('new_section')}}</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="section_name" class="form-label">{{__t('section_name')}}</label>
                        <input type="text" name="section_name" class="form-control" id="section_name" placeholder="{{__t('section_name_eg')}}" value="">
                        <div class="invalid-feedback">
                            <strong></strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>{{__t('create_section')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
