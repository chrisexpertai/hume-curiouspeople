@extends('layouts.dashboard')

@section('content')
    <div class="card">
        <div class="card-body">

            <form id="editform" method="post">
                @csrf

                <div class="form-group">
                    <div class="toolbar-container"></div>
                    <div class="content-container">
                        <div id="description" contenteditable="true">{!! html_entity_decode($document->doc_detail) !!}</div>
                    </div>
                </div>

                <textarea name="docdesc" id="docdesc" style="display:none"></textarea>
                <input type="hidden" name="docid" value="{{ $document->id }}">
            </form>

        </div>
    </div>


    <script  src="{{ asset('assets/vendor/jquery/jquery-1.12.0.min.js') }}"></script>
    <script src="/assets/vendor/ckeditor5d/ckeditor.js"></script>
    <script>
        const textarea = document.querySelector('#docdesc');
        let editor;

        DecoupledEditor
            .create(document.querySelector('#description'))
            .then(neweditor => {
                const toolbarContainer = document.querySelector('.toolbar-container');
                editor = neweditor;
                toolbarContainer.appendChild(editor.ui.view.toolbar.element);

                // Autosave every 5 seconds (adjust the interval as needed)
                setInterval(saveDocument, 6000);
            })
            .catch(error => {
                console.error(error);
            });
        $("#savedoc").on("click", function() {
            saveDocument();
        });
        // document.getElementById('savedoc').onclick = () => {
        //     saveDocument();
        // };

        function saveDocument() {
            textarea.value = editor.getData();

            // Use AJAX to send the document content to the server for saving
            fetch('{{ route('save_document') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        docid: document.querySelector('input[name="docid"]').value,
                        docdesc: textarea.value,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Save successful:', data);
                })
                .catch(error => {
                    console.error('Error saving document:', error);
                });
        }
    </script>
@endsection
