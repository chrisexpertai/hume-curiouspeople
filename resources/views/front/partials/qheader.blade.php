@php
    $previous = $content->previous;
    $next = $content->next;
    $is_completed = false;
    if ($auth_user && $content->is_completed) {
        $is_completed = true;
    }
@endphp



<!-- Add this line to the head section of your Blade layout -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

{{-- <script src="{{ asset('assets/js/filemanager.js') }}"></script> --}}
<script src="{{ asset('assets/plugins/ckeditor5d/ckeditor.js') }}"></script>
<script>
    const textarea = document.querySelector('#assignment_text');
    var editor;

    DecoupledEditor
        .create(document.querySelector('#assignment_text'))
        .then(neweditor => {
            const toolbarContainer = document.querySelector('.toolbar-container');
            editor = neweditor;
            toolbarContainer.appendChild(editor.ui.view.toolbar.element);

        })
        .catch(error => {
            console.error(error);
        });
    // $(document).on("click", function (){

    //     textarea.value = editor.getData();
    //     document.getElementById('editform').submit();
    // })
    // document.getElementById('savedoc').onclick = () => {
    //     textarea.value = editor.getData();
    //     document.getElementById('editform').submit();
    // }
</script>

<script>
    window.initialDarkMode = @json(session('dark_mode', false));
</script>
<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--bs-body-bg) display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .pix-loader {
        border: 3px solid rgb(255 255 255 / 50%);
        border-radius: 50%;
        width: 45px;
        height: 45px;
        position: relative;
        animation: expandAndInvisible 0s ease-out infinite;
        margin: 20px auto;
    }

    @keyframes expandAndInvisible {

        0%,
        100% {
            transform: scale(0.8);
            opacity: 0.8;
        }

        50% {
            transform: scale(1);
            opacity: 0;
        }
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Simulate loading delay (you can replace this with actual loading logic)
        setTimeout(function() {
            // Hide loader
                    if (document.getElementById("overlay"))
                        document.getElementById("overlay").style.display = "none";;

            // Display content
             if (document.getElementById("content"))
                        document.getElementById("content").style.display = "block";
        }, 500); // Adjust the delay time as needed
    });
</script>

<!-- main js -->



@section('page-js')
@endsection
