@extends('front.dashboard.courses.layout')

@section('content')
    @include(theme('dashboard.courses.course_nav'))



    @if ($auth_user->isAdmin)
        @php

            $faqs = \App\Models\Faq::where('course_id', $course->id)->get();

        @endphp
    @endif

    <div class="card-body">
        <!-- Step content START -->
        <div class="bs-stepper-content">
            <!-- Title -->
            <h4>{{ tr('Additional information') }}</h4>
            <hr> <!-- Divider -->



            <div class="row g-4">
                <!-- Edit faq START -->
                <div class="col-12">
                    <div class="bg-light border rounded p-2 p-sm-4">
                        <div class="d-sm-flex justify-content-sm-between align-items-center mb-3">
                            <h5 class="mb-2 mb-sm-0">{{ tr('Upload FAQs') }}</h5>
                            <a href="#" class="btn btn-sm btn-primary-soft mb-0" data-bs-toggle="modal"
                                data-bs-target="#addQuestion"><i class="bi bi-plus-circle me-2"></i>{{ tr('Add FAQ') }}</a>
                        </div>

                        <div class="row g-4">
                            @forelse($faqs as $faq)
                                <div class="col-12">
                                    <div class="bg-body p-3 p-sm-4 border rounded">
                                        <div class="d-sm-flex justify-content-sm-between align-items-center mb-2">
                                            <h6 class="mb-0">{{ $faq->question }}</h6>
                                            <div class="align-middle">
                                                <a href="#"
                                                    class="btn btn-sm btn-success-soft btn-round me-1 mb-1 mb-md-0"
                                                    data-bs-toggle="modal" data-bs-target="#editQuestionModal"
                                                    data-id="{{ $faq->id }}"><i class="far fa-fw fa-edit"></i></a>
                                                <!-- Delete form -->
                                                <form
                                                    action="{{ route('courses.faqs.destroy', ['id' => $course->id, 'faq_id' => $faq->id]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger-soft btn-round mb-0"><i
                                                            class="fas fa-fw fa-times"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                        <p>{{ $faq->answer }}</p>
                                    </div>
                                </div>




                                <!-- Edit FAQ Modal -->
                                <div class="modal fade" id="editQuestionModal" tabindex="-1"
                                    aria-labelledby="editQuestionLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-dark">
                                                <h5 class="modal-title text-white" id="editQuestionLabel">
                                                    {{ tr('Edit FAQ') }}</h5>
                                                <button type="button" class="btn btn-sm btn-light mb-0"
                                                    data-bs-dismiss="modal" aria-label="Close"><i
                                                        class="bi bi-x-lg"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="editFaqForm"
                                                    action="{{ route('courses.faqs.update', ['id' => $course->id, 'faq_id' => $faq->id]) }}"
                                                    method="POST" class="row text-start g-3">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="faq_id" value="{{ $faq->id }}">
                                                    <!-- Question -->
                                                    <div class="col-12">
                                                        <label class="form-label">{{ tr('Question') }}</label>
                                                        <input id="editQuestionInput" class="form-control" type="text"
                                                            name="question" placeholder="Write a question">
                                                    </div>
                                                    <!-- Answer -->
                                                    <div class="col-12 mt-3">
                                                        <label class="form-label">{{ tr('Answer') }}</label>
                                                        <textarea id="editAnswerInput" class="form-control" name="answer" rows="4" placeholder="Write an answer"
                                                            spellcheck="false"></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger-soft my-0"
                                                    data-bs-dismiss="modal">{{ tr('Close') }}</button>
                                                <button type="button" class="btn btn-success my-0"
                                                    id="saveChangesBtn">{{ tr('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p>No FAQs found.</p>
                                </div>
                            @endforelse


                        </div>
                    </div>
                </div>
                <!-- Edit faq END -->





                <form action="" method="post">
                    @csrf



                    <!-- Tags START -->
                    <div class="col-12">
                        <div class="bg-light border rounded p-4">
                            <h5 class="mb-0">{{ tr('Tags') }}</h5>
                            <!-- Comment -->
                            <div class="mt-3">
                                <textarea name="tags" id="tags" class="form-control" rows="5">{{ $course->tags }}</textarea>
                                <span
                                    class="small">{{ tr('Maximum of 14 keywords. Keywords should all be in lowercase. e.g. javascript, react, marketing. 1 tag per line!') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Tags END -->
                    {{--
                <!-- Reviewer START -->
                <div class="col-12">
                    <div class="bg-light border rounded p-4">
                        <h5 class="mb-0">Message to a reviewer</h5>
                        <!-- Comment -->
                        <div class="mt-3">
                            <textarea class="form-control" id="reviewer_message" name="reviewer_message" rows="3">{{ old('reviewer_message') }}</textarea>
                            @error('reviewer_message')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                            <div class="form-check mb-0 mt-2">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">
                                    Any images, sounds, or other assets that are not my own work, have been appropriately licensed for use in the file preview or main course. Other than these items, this work is entirely my own and I have full rights to sell it here.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Reviewer END --> --}}

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary next-btn mb-0">Save Tags</button>
                    </div>
                </form>

            </div>
        </div>
    </div>





    <!-- Add FAQ Modal -->
    <div class="modal fade" id="addQuestion" tabindex="-1" aria-labelledby="addQuestionLabel" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white" id="addQuestionLabel">Add FAQ</h5>
                    <button type="button" class="btn btn-sm btn-light mb-0" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <form id="addFaqForm" class="row text-start g-3">
                        @csrf
                        <!-- Question -->
                        <div class="col-12">
                            <label class="form-label">{{ tr('Question') }}</label>
                            <input id="questionInput" class="form-control" type="text" name="question"
                                placeholder="Write a question">
                        </div>
                        <!-- Answer -->
                        <div class="col-12 mt-3">
                            <label class="form-label">{{ tr('Answer') }}</label>
                            <textarea id="answerInput" class="form-control" name="answer" rows="4" placeholder="Write an answer"
                                spellcheck="false"></textarea>
                        </div>
                        <!-- Hidden fields for CSRF token and course ID -->
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger-soft my-0"
                        data-bs-dismiss="modal">{{ tr('Close') }}</button>
                    <button type="button" id="saveFaqBtn" class="btn btn-success my-0">Save FAQ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Your custom JavaScript code -->
    <script>
        $(document).ready(function() {
            $('#saveFaqBtn').click(function() {
                // Serialize form data
                var formData = $('#addFaqForm').serialize();

                // Send AJAX request
                $.ajax({
                    url: '{{ route('courses.faqs.store', $course->id) }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Reload the page or perform any other action
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>



    <script>
        $('#editQuestionModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var faqId = button.data('id');
            var modal = $(this);

            // Make an AJAX request to fetch the FAQ data
            $.ajax({
                url: '/api/faqs/' + faqId,
                method: 'GET',
                success: function(response) {
                    // Populate the form fields with the fetched data
                    modal.find('#editQuestionInput').val(response.question);
                    modal.find('#editAnswerInput').val(response.answer);
                },
                error: function() {
                    alert('Failed to fetch FAQ data');
                }
            });
        });

        // Submit form when Save Changes button is clicked
        $('#saveChangesBtn').on('click', function() {
            $('#editFaqForm').submit();
        });
    </script>
@endsection
