@extends('layouts.admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@section('content')
    @include('inc.flash_msg')
    <div class="page-body pt-5">
        <div class="col-xxl-10 col-md-11 col-12 px-md-0 px-2 mx-auto">
            <div class=" module module-settings-group-website-group " id="module-settings-group-website-group"
                data-type="settings/group/website_group" parent-module="settings/group/website_group"
                parent-module-id="module-settings-group-website-group">
                <script>
                    $(document).ready(function() {
                        $('body .main > main').addClass('page-settings');
                    });
                </script>


                <script type="text/javascript">
                    /* <![CDATA[ */
                    window.pageData = @json(pageJsonData());
                    /* ]]> */
                </script>


                <div class=" module module-admin-modules-info " id="module-settings-group-website-group-admin-modules-info"
                    back_button_url="settings" parent-module-id="module-settings-group-website-group"
                    parent-module="settings/group/website_group" data-type="admin/modules/info">
                </div>
                <div class=" module module-files-admin " id="module-settings-group-website-group-files-admin"
                    data-mw-title="Files" parent-module-id="module-settings-group-website-group"
                    parent-module="settings/group/website_group" data-type="files/admin">
                    <div id="files-manager">
                        <div class="mw-file-manager-root mw-fm-none-selected" data-view="list">
                            <div class="mw-file-manager-spinner"></div>
                            <div class="mw-file-manager-top-bar">
                                <div class="mw-file-manager-top-bar">
                                    <div class="mw-file-manager-top-bar-actions">
                                        <div class="mw-file-manager-create-methods-dropdown">


                                            <button type="button" data-toggle="tooltip" title="Upload" id="button-upload"
                                                data-upload-success="reload"
                                                class="btn btn-success mw-file-manager-create-methods-dropdown-add"><svg
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 472 472"
                                                    style="width: 14px; margin-inline-end:undefinedpx;"
                                                    xml:space="preserve">
                                                    <path fill="white"
                                                        d="M472 185H287V0H185v185H0v102h185v185h102V287h185V185z"></path>
                                                </svg></button>




                                            <div class="row g-2 mw-file-manager-search">

                                                <div class="col">
                                                    <form action="" method="get" class="">

                                                        <input type="text"name="q" value="{{ request('q') }}"
                                                            class="form-control" placeholder="{{ tr('Search') }}">
                                                </div>
                                                <div class="col-auto">
                                                    <button type="submit"
                                                        class="btn btn-icon mw-file-manager-search-button"
                                                        aria-label="Button">

                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                            <path d="M21 21l-6 -6"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                </form>

                                            </div>




                                            <div class="container">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <p class="mb-0 text-right">
                                                            Found <strong>{{ $medias->total() }}</strong> media |
                                                            {{ tr('Allowed File Types') }} :
                                                            <code>{{ get_option('allowed_file_types') }}</code>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="row my-3">
                                                    <div class="col-sm-12">
                                                        <div id="statusMsg"></div>
                                                    </div>
                                                </div>

                                                @if ($medias->count())
                                                    <div class="row media-manager-grid-wrap">
                                                        @foreach ($medias as $media)
                                                            <div class="col-md-3">
                                                                <div id="media-grid-id-{{ $media->id }}"
                                                                    class="media-manager-single-grid">
                                                                    <a href="javascript:;" data-toggle="sc-modal"
                                                                        data-target="#adminFileManagerModal"
                                                                        data-media-info="{{ json_encode($media->media_info) }}">
                                                                        <img class="card-img-top"
                                                                            src="{{ $media->thumbnail }}"
                                                                            alt="{{ $media->name }}"
                                                                            title="{{ $media->name }}" />
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            {!! no_data() !!}
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="row my-5">
                                                    <div class="col-sm-12">
                                                        {!! $medias->appends(['filter_name' => request('filter_name')])->links() !!}
                                                    </div>
                                                </div>
                                            </div>





                                            <!-- Modal -->
                                            <div class="modal fade" id="adminFileManagerModal" tabindex="-1" role="dialog"
                                                aria-labelledby="adminFileManagerModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="adminFileManagerModalLabel">
                                                                @lang('admin.media_details')</h5>
                                                            <button type="button" class="btn btn-sm btn-light mb-0"
                                                                data-bs-dismiss="modal" aria-label="Close"><i
                                                                    class="bi bi-x-lg"></i></button>

                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <img id="mediaManagerPreviewScreen"
                                                                        src="{{ asset('assets/images/placeholder-image.png') }}"
                                                                        class="mediaManagerPreviewScreen img-fluid" />
                                                                </div>
                                                                <div class="col-md-6">

                                                                    <div class="adminMediaModalInfoSide">
                                                                        <p id="mediaModalFileID" class="m-1">
                                                                            <strong>ID:</strong> #<span></span>
                                                                        </p>
                                                                        <p id="mediaModalFileName" class="m-1">
                                                                            <strong>{{ tr('File name') }}:</strong>
                                                                            <span></span>
                                                                        </p>
                                                                        <p id="mediaModalFileType" class="m-1">
                                                                            <strong>{{ tr('File Type') }}:</strong>
                                                                            <span></span>
                                                                        </p>
                                                                        <p id="mediaModalFileUploadedOn" class="m-1">
                                                                            <strong>{{ tr('Uploaded on') }}:</strong>
                                                                            <span></span>
                                                                        </p>
                                                                        <p id="mediaModalFileSize" class="m-1">
                                                                            <strong>{{ tr('File Size') }}:</strong>
                                                                            <span></span>
                                                                        </p>
                                                                    </div>

                                                                    <hr />

                                                                    <form id="adminMediaManagerModalForm" method="post">
                                                                        @csrf
                                                                        <input type="hidden" id="sc_modal_info_media_id"
                                                                            name="media_id" value="">
                                                                        <div class="form-group row">
                                                                            <label for="mediaFileTitle"
                                                                                class="col-sm-4 col-form-label col-form-label-sm text-right">{{ tr('Title') }}</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm"
                                                                                    name="title" id="mediaFileTitle">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="mediaFileAltText"
                                                                                class="col-sm-4 col-form-label col-form-label-sm text-right"{{ tr('Alt Text') }}</label>
                                                                                <div class="col-sm-8">
                                                                                    <input type="text"
                                                                                        class="form-control form-control-sm"
                                                                                        name="alt_text"
                                                                                        id="mediaFileAltText">

                                                                                    <div id="formWorkingIconWrap"
                                                                                        class="my-3"></div>
                                                                                </div>
                                                                        </div>

                                                                    </form>


                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-light mb-0"
                                                                data-bs-dismiss="modal" aria-label="Close"> Close <i
                                                                    class="bi bi-x-lg"></i></button>
                                                            <button type="button" id="media-info-modal-trash-btn"
                                                                class="btn btn-sm btn-danger"><i
                                                                    class="la la-trash-o"></i>
                                                                {{ __a('delete_permanently') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- <script src="/assets/admin/js/custom.js"></script> --}}

    {{-- <script src="/assets/vendor/filemanager/filemanager.js"></script> --}}
    <script src="/public/assets/js/filemanager.js"></script>
@endsection
