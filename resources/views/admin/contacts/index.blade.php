<!-- resources/views/admin/contacts/index.blade.php -->

@extends('layouts.admin')

@section('content')

<!-- Page main content START -->
<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-2 mb-sm-0">{{ tr('Contact Messages') }}</h1>
        </div>
    </div>

    <!-- Main card START -->
    <div class="card bg-transparent border">

        <div class="card-header bg-light border-bottom">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <!-- Search bar -->
                    <div class="col-md-6">
                        <form class="rounded position-relative" action="{{ route('contacts') }}" method="GET">
                            <input class="form-control bg-body" type="search" placeholder="{{ tr('Search') }}" aria-label="Search" name="search">
                            <button class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset" type="submit">
                                <i class="fas fa-search fs-6 "></i>
                            </button>
                        </form>
                    </div>

                    <!-- Sort by filter -->
                    <div class="col-md-6">
                        <form action="{{ route('contacts') }}" method="GET" class="d-flex justify-content-end align-items-center">
                            <select class="form-select js-choice border-0 z-index-9 bg-transparent me-3" aria-label=".form-select-sm" name="sort_by">
                                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest Message First</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest Message First</option>
                            </select>
                            <button type="submit" class="btn btn-primary">{{ tr('Apply') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card header END -->

        <!-- Card body START -->
        <div class="card-body">
            <!-- Instructor request table START -->
            <div class="table-responsive border-0">
                <table class="table table-dark-gray align-middle p-4 mb-0 table-hover">

                    <!-- Table head -->
                    <thead>
                        <tr>
                            <th scope="col" class="border-0 rounded-start">{{ tr('Name') }}</th>
                            <th scope="col" class="border-0">{{ tr('Email') }}</th>
                            <th scope="col" class="border-0">{{ tr('Date') }}</th>
                            <th scope="col" class="border-0 rounded-end">{{ tr('Actions') }}</th>
                        </tr>
                    </thead>

                    <!-- Table body START -->
                    <tbody>
                        @foreach ($contacts as $contact)
                        <tr>
                            <td>{{ $contact->name }}</td>
                            <td>{{ $contact->email }}</td>
                            <td>{{ $contact->created_at }}</td>
                            <td>
                                <button class="delete-btn btn btn-primary-soft" onclick="deleteContact({{ $contact->id }})">{{ tr('Delete') }}</button>
                                 <button type="button" class="btn btn-primary" onclick="showModal('{{ $contact->id }}')">{{ tr('View Message') }}</button>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <!-- Table body END -->
                </table>
            </div>
            <!-- Instructor request table END -->
        </div>
        <!-- Card body END -->
    </div>
    <!-- Main card END -->
</div>
<!-- Page main content END -->




<script>
    function applySort() {
        const sortSelect = document.getElementById('sortSelect');
        const selectedOption = sortSelect.value;
        window.location.href = `/admin/contacts?sort=${selectedOption}`;
    }

    function deleteContact(contactId) {
if (confirm("Are you sure you want to delete this contact?")) {
    // Get the CSRF token from the meta tag
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

    // Perform AJAX request for contact deletion
    fetch(`/admin/contacts/${contactId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Refresh the page or update the UI as needed
                location.reload();
            } else {
                alert('Failed to delete contact. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
}
}

    document.querySelectorAll('.message-card').forEach(card => {
        card.addEventListener('click', () => {
            const fullMessage = card.getAttribute('data-message');
            document.getElementById('fullMessage').innerText = fullMessage;
            openPopup();
        });
    });

    function openPopup() {
        document.getElementById('messagePopup').style.display = 'flex';
    }

    function closePopup() {
        document.getElementById('messagePopup').style.display = 'none';
    }
</script>



<!-- Modal for request details -->
@foreach ($contacts as $contact)
<div class="modal fade" id="appDetail_{{ $contact->id }}" tabindex="-1" aria-labelledby="appDetailLabel_{{ $contact->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white" id="appDetailLabel_{{ $contact->id }}">Contact details</h5>
                    <button type="button" class="btn btn-sm btn-light mb-0" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body p-5">
                    <div class="message-details">
                        <p><strong>{{ tr('Name:') }}</strong> {{ $contact->name }}</p>
                        <p><strong>Email:</strong> {{ $contact->email }}</p>
                        <p><strong>Phone:</strong> {{ $contact->phone }}</p>
                        <p><strong>Subject:</strong> {{ $contact->subject }}</p>
                        <p><strong>Message:</strong> {{ $contact->message }}</p>
                        <p><strong>Status:</strong> {{ $contact->status }}</p>
                    </div>


                    </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger-soft my-0" data-bs-dismiss="modal">{{ tr('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    // Function to show modal by request ID
    function showModal(requestId) {
        // Construct modal ID based on request ID
        var modalId = '#appDetail_' + requestId;
        // Show the modal corresponding to the request ID
        $(modalId).modal('show');
    }
</script>


@endsection

