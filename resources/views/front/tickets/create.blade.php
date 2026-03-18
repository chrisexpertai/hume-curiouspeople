@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border rounded-3">
                <div class="card-header">
                    <h2 class="card-title">{{  tr('Create ticket') }}</h2>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('user.tickets.create') }}" class="create-ticket-form">
                        @csrf

                        <div class="mb-3">
                            <label for="subject" class="form-label">{{  tr('Subject') }}</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">{{  tr('Message') }}</label>
                            <textarea class="form-control" id="content" name="content" rows="6"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">{{  tr('Create a Ticket') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/vendor/ckeditor5/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'underline',
                        'strikethrough',
                        'subscript',
                        'superscript',
                        '|',
                        'fontColor',
                        'fontBackgroundColor',
                        '|',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'alignment',
                        '|',
                        'link',
                        'blockQuote',
                        'insertTable',
                        '|',
                        'undo',
                        'redo',
                        '|',
                        'highlight',
                        'horizontalLine',
                        '|',
                        'removeFormat',
                        'code',
                        'codeBlock',
                        '|',
                        'htmlEmbed',
                        'specialCharacters'
                    ],
                    shouldNotGroupWhenFull: true
                },
                language: 'en',
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:full',
                        'imageStyle:side',
                        'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                },
                licenseKey: '',
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(err => {
                console.error(err.stack);
            });

        // Update textarea with CKEditor data before form submission
        document.querySelector('.create-ticket-form').addEventListener('submit', function () {
            const contentTextarea = document.querySelector('#content');
            contentTextarea.value = window.editor.getData();
        });
    });
</script>






@endsection
