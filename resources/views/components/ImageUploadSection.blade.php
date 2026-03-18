<!-- resources/views/components/ImageUploadSection.blade.php -->

<div class="form-group row">
    <label for="{{ $identifier }}_img" class="col-sm-4 control-label">@lang('admin.trust_image')</label>
    <div class="col-sm-8">
        <label>
            <input type="radio" name="{{ $identifier }}_image_source" value="upload" {{ get_option("{$identifier}_image_source", "upload") === "upload" ? 'checked' : '' }}> Upload
        </label>
        <label>
            <input type="radio" name="{{ $identifier }}_image_source" value="url" {{ get_option("{$identifier}_image_source", "upload") === "url" ? 'checked' : '' }}> URL
        </label>

        <div class="image-upload-container" id="{{ $identifier }}_upload-container" style="{{ get_option("{$identifier}_image_source", "upload") === "upload" ? 'display: block;' : 'display: none;' }}">
            {!! image_upload_form("{$identifier}_img", get_option("{$identifier}_img")) !!}
        </div>

        <div class="url-input-container" id="{{ $identifier }}_url-container" style="{{ get_option("{$identifier}_image_source", "upload") === "url" ? 'display: block;' : 'display: none;' }}">
            <input type="text" class="form-control" id="{{ $identifier }}_url_input" name="{{ $identifier }}_image[url]" value="{{ get_option("{$identifier}_image.url") }}">
        </div>
    </div>
</div>
