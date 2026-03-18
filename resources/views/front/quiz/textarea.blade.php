<div class="piksera-course-player-question " data-number-question="3">
    <div class="piksera-course-player-question__header">
       <h3 class="piksera-course-player-question__title">
        {{$question->title}}
       </h3>
    </div>


<div class="form-group">
    <textarea class="form-control" id="textarea{{$question->id}}" rows="4" name="questions[{{$question->id}}]" ></textarea>
    <p class="text-muted my-3"><small></small></p>
</div>


<script src="/assets/vendor/ckeditor5/ckeditor.js"></script>

<script>
   ClassicEditor
    .create( document.querySelector('#textarea{{$question->id}}'), {
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
    } )
    .then(editor => {
        const toolbarContainer = document.querySelector('main .toolbar-container');
        toolbarContainer.prepend(editor.ui.view.toolbar.element);
        window.editor = editor;
    })
    .catch(err => {
        console.error(err.stack);
    });

</script>



