<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ tr('File Manager') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row mb-3">
                 <div class="col-sm-4">

                    <a href="{{route('load_filemanager')}}" data-toggle="tooltip" title="" id="button-refresh" class="btn btn-default" data-original-title="Refresh"><i class="la la-refresh"></i></a>

                    <button type="button" data-toggle="tooltip" title="Upload" id="button-upload" class="btn btn-primary"><i class="la la-upload"></i></button>
                    <button type="button" data-toggle="tooltip" title="Insert Media" class="btn btn-info mediaInsertBtn"><i class="la la-plus-circle"></i></button>
                    <button type="button" data-toggle="tooltip" title="Delete" id="button-delete" class="btn btn-danger"><i class="la la-trash-o"></i></button>
                </div>


                <div class="col-sm-7">
                    <div class="input-group">
                        <input type="text" name="filemanager-search" value="{{request('filter_name')}}" placeholder="{{ tr('Search..') }}" class="form-control">
                        <span class="input-group-btn">
                            <button type="button" data-toggle="tooltip" title="Search" id="button-search" class="btn btn-primary">
                                <i class="la la-search"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <div class="col-sm-1">
                    <button type="button" class="btn btn-sm btn-light mb-0" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>

            </div>

            <div class="row mt-2 mb-0">
                <div class="col-sm-12">
                    <p class="mb-0 allowed_file_text"> @lang('admin.allowed_file_types') :
                        <code>{{get_option('allowed_file_types')}}</code>
                    </p>
                </div>
            </div>

            <hr class="mb-0" />
            <div class="row">
                <div class="col-md-12">
                    <div id="statusMsg"></div>
                </div>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="media-manager-grid-wrap">
                            @if($medias->count())
                                <div class="row">
                                    @foreach($medias as $media)
                                        <div class="col-md-4 mb-3">
                                            <div id="media-grid-id-{{$media->id}}" class="media-manager-single-grid">
                                                <a href="javascript:;" class="media-modal-thumbnail" data-media-info="{{json_encode($media->media_info)}}">
                                                    <img src="{{$media->thumbnail}}" alt="{{$media->name}}" title="{{$media->name}}" />
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light p-3">
                            <div class="adminMediaModalInfoSide">
                                <img id="mediaManagerPreviewScreen" src="{{asset('/assets/images/placeholder-image.png')}}" class="mediaManagerPreviewScreen img-fluid" />
                                <hr>
                                <button type="button" class="btn btn-primary mediaInsertBtn">
                                    <i class="la la-plus-circle"></i> {{__a('insert_selected_media')}}
                                </button>                                <hr>

                                <p id="mediaModalFileID" class="m-1"><strong>ID:</strong> #<span id="mediaModalFileID"></span></p>
                                <p id="mediaModalFileName" class="m-1"><strong>{{ tr('File name') }}:</strong> <span id="mediaModalFileName"></span></p>
                                <p id="mediaModalFileType" class="m-1"><strong>{{ tr('File Type') }}:</strong> <span id="mediaModalFileType"></span></p>
                                <p id="mediaModalFileUploadedOn" class="m-1"><strong>{{ tr('Uploaded on') }}:</strong> <span id="mediaModalFileUploadedOn"></span></p>
                                <p id="mediaModalFileSize"  class="m-1"><strong>{{ tr('File Size') }}:</strong> <span id="mediaModalFileSize"></span></p>
                            </div>
                            <hr>




                            <form id="adminMediaManagerModalForm" method="post"> @csrf
                                <input type="hidden" id="sc_modal_info_media_id" name="media_id" value="">
                                <div class="mb-3">
                                    <label for="mediaFileTitle" class="form-label">{{ tr('Title') }}</label>
                                    <input type="text" class="form-control" id="mediaFileTitle" name="title">
                                </div>
                                <div class="mb-3">
                                    <label for="mediaFileAltText" class="form-label"{{ tr('Alt Text') }}</label>
                                    <input type="text" class="form-control" id="mediaFileAltText" name="alt_text">
                                    <div id="formWorkingIconWrap" class="my-3"></div>
                                </div>
                            </form>
                                          </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! $medias->appends(['filter_name' => request('filter_name')])->links() !!}
            </div>
        </div>
    </div>


</div>
