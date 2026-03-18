<!-- resources/views/admin/instructor_requests/index.blade.php -->

@extends('layouts.admin')

@section('content')



	<!-- Page main content START -->
	<div class="page-content-wrapper border">

		<!-- Title -->
		<div class="row mb-3">
			<div class="col-12">
				<h1 class="h3 mb-2 mb-sm-0">{{ $title }}</h1>
			</div>
		</div>

		<!-- Main card START -->
		<div class="card bg-transparent border">

		<!-- Card header START -->
<div class="card-header bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <!-- Search bar -->
            <div class="col-md-6">
                <form class="rounded position-relative" action="{{ route('admin.instructor.requests') }}" method="GET">
                    <input class="form-control bg-body" type="search" placeholder="{{ tr('Search') }}" aria-label="Search" name="search">
                    <button class="bg-transparent p-2 position-absolute top-50 end-0 translate-middle-y border-0 text-primary-hover text-reset" type="submit">
                        <i class="fas fa-search fs-6 "></i>
                    </button>
                </form>
            </div>

            <!-- Sort by filter -->
            <div class="col-md-6">
                <form action="{{ route('admin.instructor.requests') }}" method="GET" class="d-flex justify-content-end align-items-center">
                    <select class="form-select js-choice border-0 z-index-9 bg-transparent me-3" aria-label=".form-select-sm" name="sort_by">
                        <option value="">{{ tr('Sort by') }}</option>
                        <option value="newest">{{ tr('Newest') }}</option>
                        <option value="oldest">{{ tr('Oldest') }}</option>
                        <option value="accepted">{{ tr('Accepted') }}</option>
                        <option value="rejected">{{ tr('Rejected') }}</option>
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
								<th scope="col" class="border-0 rounded-start">{{ tr('Instructor name') }}</th>
								<th scope="col" class="border-0">{{ tr('Subject') }}</th>
								<th scope="col" class="border-0">{{ tr('Requested Date') }}</th>
								<th scope="col" class="border-0 rounded-end">{{ tr('Action') }}</th>
							</tr>
						</thead>



						<!-- Table body START -->


						<tbody>

                            @foreach ($requests as $request)
                            <!-- Table row -->
                            <tr>
                                <!-- Table data -->
                                <td>
                                    <div class="d-flex align-items-center position-relative">
                                        <!-- Image -->
                                        <div class="avatar avatar-md">
                                            <img src="/assets/images/avatar/09.jpg" class="rounded-circle" alt="">
                                        </div>
                                        <div class="mb-0 ms-2">
                                            <!-- Title -->
                                            <h6 class="mb-0"><a href="#" class="stretched-link">{{ $request->user->name }}</a></h6>
                                        </div>
                                    </div>
                                </td>

                                <!-- Table data -->
                                <td class="text-center text-sm-start">
                                    <h6 class="mb-0">{{ $request->field_of_study }}</h6>
                                </td>

                                <!-- Table data -->
                                <td>{{ $request->created_at }}</td>

                                <!-- Table data -->
                                <td>
                                    <div class="d-flex">
                                        <form method="post" class="w-30 me-1" action="{{ route('admin.instructor.request.approve', $request->id) }}">

                                            @csrf
                                            <button type="submit" class="btn btn-success-soft mb-1">{{ tr('Accept') }}</button>
                                        </form>

                                        <form method="post" class="w-30 me-1" action="{{ route('admin.instructor.request.decline', $request->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary-soft mb-1">{{ tr('Reject') }}</button>
                                        </form>

                                        <button type="button" class="btn btn-primary-soft mb-0" onclick="showModal('{{ $request->id }}')">{{ tr('View App') }}</button>
                                    </div>
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

			<!-- Card footer START -->
			<div class="card-footer bg-transparent pt-0">


            <!-- Pagination START -->
            <div class="d-sm-flex justify-content-sm-between align-items-sm-center mt-4 mt-sm-3">
            <!-- Content -->
            <p class="mb-0 text-center text-sm-start">
                Showing {{ $requests->firstItem() }} to {{ $requests->lastItem() }} of {{ $requests->total() }} entries
            </p>
            <!-- Pagination -->
            {{ $requests->links('pagination::bootstrap-4') }}
            </div>
            <!-- Pagination END -->


			</div>
			<!-- Card footer END -->
		</div>
		<!-- Main card END -->



<!-- Modal for request details -->
@foreach ($requests as $request)
    <div class="modal fade" id="appDetail_{{ $request->id }}" tabindex="-1" aria-labelledby="appDetailLabel_{{ $request->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white" id="appDetailLabel_{{ $request->id }}">{{ tr('Applicant details') }}</h5>
                    <button type="button" class="btn btn-sm btn-light mb-0" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body p-5">
                    <!-- Display request details here -->
                    <span class="small">{{ tr('Applicant Name') }}:</span>
                    <h6 class="mb-3">{{ $request->user->name }}</h6>

                    <span class="small">{{ tr('Applicant Email id') }}:</span>
                    <h6 class="mb-3">{{ $request->user->email }}</h6>



                        <span class="small">{{ tr('Contact Number') }}:</span>
                        <p class="text-dark mb-2">{{ $request->user->contact_number }}</p>



                        <span class="small">{{ tr('Address') }}</span>
                        <p class="text-dark mb-2">{{ $request->user->address }}</p>

                        <span class="small">{{ tr('Date of Birth') }}</span>
                        <p class="text-dark mb-2">{{ $request->user->date_of_birth }}</p>


                        <span class="small">{{ tr('Highest Degree Earned') }}</span>
                        <p class="text-dark mb-2">{{ $request->highest_degree }}</p>


                        <span class="small">{{ tr('Name of Institutiond') }}</span>
                        <p class="text-dark mb-2">{{ $request->institution }}</p>

                        <span class="small">{{ tr('Field of Study') }}</span>
                        <p class="text-dark mb-2">{{ $request->field_of_study }}</p>


                        <span class="small">{{ tr('Year of Graduation') }}</span>
                        <p class="text-dark mb-2">{{ $request->year_of_graduation }}</p>


                        <span class="small">{{ tr('Teaching Experience') }}</span>
                        <p class="text-dark mb-2">{{ $request->teaching_experience }} years</p>


                        <span class="small">{{ tr('Previous Teaching Positions Held') }}</span>
                        <p class="text-dark mb-2">{{ $request->previous_positions }}</p>


                        <span class="small">{{ tr('Subjects or Courses Taught') }}</span>
                        <p class="text-dark mb-2">{{ $request->subjects_taught }}</p>


                        <span class="small">{{ tr('Educational Institutions Worked A') }}:</span>
                        <p class="text-dark mb-2">{{ $request->institutions_worked }}</p>


                        <span class="small">{{ tr('Teaching Certificates') }}: </span>
                        <p class="text-dark mb-2">{{ $request->teaching_certificates }}</p>


                        <span class="small">{{ tr('Specialized Training or Workshops Attended') }}: </span>
                        <p class="text-dark mb-2">{{ $request->training_attended }}</p>


                        <span class="small">{{ tr('Teaching Philosophy') }}: </span>
                        <p class="text-dark mb-2">{{ $request->teaching_philosophy }}</p>



                        <span class="small">{{ tr('LMS Familiarity') }}: </span>
                        <p class="text-dark mb-2">{{ $request->lms_familiarity }}</p>

                        <span class="small">{{ tr('Experience with Educational Technologies') }}: </span>
                        <p class="text-dark mb-2">{{ $request->edu_technology_experience }}</p>


                        <span class="small">{{ tr('Proficiency in Relevant Software or Tools') }}: </span>
                        <p class="text-dark mb-2">{{ $request->software_proficiency }}</p>

                        <span class="small">{{ tr('References') }}: </span>
                        <p class="text-dark mb-2">{{ $request->references }}</p>


                        <span class="small">{{ tr('Additional Questions or Custom Fields') }}: </span>
                        <p class="text-dark mb-2">{{ $request->additional_questions }}</p>


                        <span class="small">{{ tr('Preferences for Course Levels or Subjects') }}: </span>
                        <p class="text-dark mb-2"> {{ $request->preferences }}</p>

                        <span class="small">{{ tr('Availability for Teaching Hours') }}: </span>
                        <p class="text-dark mb-2"> {{ $request->availability }}</p>


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
