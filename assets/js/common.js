// public/js/common.js

document.addEventListener('DOMContentLoaded', function () {
    // Intercept file input changes globally
    document.addEventListener('change', function (event) {
        var element = event.target;

        // Check if the changed element is a file input
        if (element.type === 'file') {
            // Trigger UniSharp FileManager upload logic
            handleFileUpload(element);
        }
    });
});

// Function to handle UniSharp FileManager file upload
function handleFileUpload(fileInput) {
    // Ensure the file input is not disabled or hidden
    if (!fileInput.disabled && !fileInput.hidden) {
        var form = document.createElement('form');
        form.action = '/upload'; // Update with your actual upload route
        form.method = 'post';
        form.enctype = 'multipart/form-data';

        var csrfTokenInput = document.createElement('input');
        csrfTokenInput.type = 'hidden';
        csrfTokenInput.name = '_token';
        csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]').content;

        form.appendChild(csrfTokenInput);
        form.appendChild(fileInput.cloneNode());

        // Append the form to the document and submit it
        document.body.appendChild(form);
        form.submit();

        // Remove the form from the document
        document.body.removeChild(form);
    }
}
