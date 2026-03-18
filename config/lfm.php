<?php

/*
|--------------------------------------------------------------------------
| Documentation for this config :
|--------------------------------------------------------------------------
| online  => http://unisharp.github.io/laravel-filemanager/config
| offline => vendor/unisharp/laravel-filemanager/docs/config.md
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
     */

    'use_package_routes' => true,

    /*
    |--------------------------------------------------------------------------
    | Shared folder / Private folder
    |--------------------------------------------------------------------------
    |
    | If both options are set to false, then shared folder will be activated.
    |
     */

    'allow_private_folder' => true,

    // Flexible way to customize client folders accessibility
    // If you want to customize client folders, publish tag="lfm_handler"
    // Then you can rewrite userField function in App\Handler\ConfigHandler class
    // And set 'user_field' to App\Handler\ConfigHandler::class
    // Ex: The private folder of user will be named as the user id.
    'private_folder_name' => UniSharp\LaravelFilemanager\Handlers\ConfigHandler::class,

    'allow_shared_folder' => true,

    'shared_folder_name' => 'shares',

    /*
    |--------------------------------------------------------------------------
    | Folder Names
    |--------------------------------------------------------------------------
     */

    'folder_categories' => [
        'file' => [
            'folder_name' => 'files',
            'startup_view' => 'list',
            'max_size' => 50000, // size in KB
            'thumb' => true,
            'thumb_width' => 80,
            'thumb_height' => 80,
            'valid_mime' => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'text/plain',
            ],
        ],
        'image' => [
            'folder_name' => 'photos',
            'startup_view' => 'grid',
            'max_size' => 50000, // size in KB
            'thumb' => true,
            'thumb_width' => 80,
            'thumb_height' => 80,
            'valid_mime' => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
     */

    'paginator' => [
        'perPage' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload / Validation
    |--------------------------------------------------------------------------
     */

    'disk'                     => 'public',
    // 'disk' => 's3',

    'rename_file' => false,

    'rename_duplicates' => false,

    'alphanumeric_filename' => false,

    'alphanumeric_directory' => false,

    'should_validate_size' => false,

    'should_validate_mime' => true,

    // behavior on files with identical name
    // setting it to true cause old file replace with new one
    // setting it to false show `error-file-exist` error and stop upload
    'over_write_on_duplicate' => false,

    // mimetypes of executables to prevent from uploading
    'disallowed_mimetypes' => ['text/x-php', 'text/html', 'text/plain'],

    // extensions of executables to prevent from uploading
    'disallowed_extensions' => ['php', 'html'],

    // Item Columns
    'item_columns' => ['name', 'url', 'time', 'icon', 'is_file', 'is_image', 'thumb_url'],

    /*
    |--------------------------------------------------------------------------
    | Thumbnail
    |--------------------------------------------------------------------------
     */

    // If true, image thumbnails would be created during upload
    'should_create_thumbnails' => true,

    'thumb_folder_name' => 'thumbs',

    // Create thumbnails automatically only for listed types.
    'raster_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
    ],

    'thumb_img_width' => 200, // px

    'thumb_img_height' => 200, // px

    'file_manager' => [
        'images_folder_name' => 'photos',
        'files_folder_name' => 'files',
        'trash_folder_name' => 'trash',
        'upload_folder_name' => 'uploads',
        'shared_folder_name' => 'shares',
        'thumb_folder_name' => 'thumbs',
        'file_type_array' => ['pdf', 'zip', 'doc', 'docx'],
        'allowed_file_type' => 'pdf|zip|doc|docx',
        'show_folder_size' => true,
        'upload_allow_type' => 'image/png,image/jpeg,image/jpg,image/gif,application/zip,application/pdf',
        'new_file_mime' => 'text/plain',
        'new_folder_mime' => 'application/octet-stream',
        'media_thumb' => true,
        'media' => [
            'small' => [
                'width' => 64,
                'height' => 64,
            ],
            'medium' => [
                'width' => 128,
                'height' => 128,
            ],
            'large' => [
                'width' => 256,
                'height' => 256,
            ],
        ],
        'absolute_path' => false,
        'middlewares' => ['web', 'auth'],
        'rename_files' => true,
        'rename_folders' => true,
        'readonly' => false,
        'allow_new_folder' => true,
        'allow_upload' => true,
        'allow_folder_download' => true,
        'allow_file_download' => true,
        'allow_create_folder' => true,
        'allow_rename_folder' => true,
        'allow_rename_file' => true,
        'allow_delete_folder' => true,
        'allow_delete_file' => true,
        'allow_share_folder' => true,
        'allow_share_file' => true,
        'allow_download_folder' => true,
        'allow_download_file' => true,
        'file_type_not_image' => 'pdf|zip|doc|docx',
        'max_image_upload_size' => 3 * 1024, // KB
        'max_file_upload_size' => 5 * 1024, // KB
        'max_download_files' => 10,
        'hide_folder_ext' => false,
        'hide_file_ext' => false,
        'disk' => '',
        'access_control' => 'UniSharp\LaravelFilemanager\Middlewares\AccessControl',
        'thumb_img_width' => 200,
        'thumb_img_height' => 200,
        'open_folder' => true, // Set this to true to open the upload dialog when a folder is clicked
    ],


    /*
    |--------------------------------------------------------------------------
    | File Extension Information
    |--------------------------------------------------------------------------
     */

    'file_type_array' => [
        'pdf' => 'Adobe Acrobat',
        'doc' => 'Microsoft Word',
        'docx' => 'Microsoft Word',
        'xls' => 'Microsoft Excel',
        'xlsx' => 'Microsoft Excel',
        'zip' => 'Archive',
        'gif' => 'GIF Image',
        'jpg' => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png' => 'PNG Image',
        'ppt' => 'Microsoft PowerPoint',
        'pptx' => 'Microsoft PowerPoint',
    ],

    /*
    |--------------------------------------------------------------------------
    | php.ini override
    |--------------------------------------------------------------------------
    |
    | These values override your php.ini settings before uploading files
    | Set these to false to ingnore and apply your php.ini settings
    |
    | Please note that the 'upload_max_filesize' & 'post_max_size'
    | directives are not supported.
     */
    'php_ini_overrides' => [
        'memory_limit' => '256M',
    ],
];
