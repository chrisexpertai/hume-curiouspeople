@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="/themes/Helsinki/academy/assets/css/academy.css">

<style>
   .md\:grid-cols-2 {
     grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
   }

</style>
<main>
    @if(!empty(session()->has('msg')))
    <div class="alert alert-success my-25 d-flex align-items-center">
        <i data-feather="check-square" width="50" height="50" class="mr-2"></i>
        {{ session()->get('msg') }}
    </div>
@endif
@include('sections.contact.skin-1')

<section>
	<div class="container">
		<div class="row g-4 g-lg-0 align-items-center">

			<div class="col-md-6 align-items-center text-center">
				<!-- Image -->
				<img src="/assets/images/element/contact.svg" class="h-400px" alt="">

				<!-- Social media button -->
				<div class="d-sm-flex align-items-center justify-content-center mt-2 mt-sm-4">
					<h5 class="mb-0">{{ tr('Follow us on') }}:</h5>
					<ul class="list-inline mb-0 ms-sm-2">
						<li class="list-inline-item"> <a class="fs-5 me-1 text-facebook" href="#"><i class="fab fa-fw fa-facebook-square"></i></a> </li>
						<li class="list-inline-item"> <a class="fs-5 me-1 text-instagram" href="#"><i class="fab fa-fw fa-instagram"></i></a> </li>
						<li class="list-inline-item"> <a class="fs-5 me-1 text-twitter" href="#"><i class="fab fa-fw fa-twitter"></i></a> </li>
						<li class="list-inline-item"> <a class="fs-5 me-1 text-linkedin" href="#"><i class="fab fa-fw fa-linkedin-in"></i></a> </li>
						<li class="list-inline-item"> <a class="fs-5 me-1 text-dribbble" href="#"><i class="fas fa-fw fa-basketball-ball"></i></a> </li>
						<li class="list-inline-item"> <a class="fs-5 me-1 text-pinterest" href="#"><i class="fab fa-fw fa-pinterest"></i></a> </li>
					</ul>
				</div>
			</div>

			<!-- Contact form START -->
			<div class="col-md-6">
				<!-- Title -->
				<h2 class="mt-4 mt-md-0">{{ tr('Let\'s talk') }}</h2>
				<p>{{ tr('To request a quote or want to meet up for coffee, contact us directly or fill out the form and we will get back to you promptly') }}</p>

				<form action="{{ route('contact.store') }}" method="post">
                    @csrf
                    <!-- Name -->
                    <div class="mb-4 bg-light-input">
                        <label for="yourName" class="form-label">{{ tr('Your name') }} *</label>
                        <input type="text" name="name" class="form-control form-control-lg" id="yourName" value="{{ old('name') }}" placeholder="{{ tr('Name') }}">
                    </div>
                    <!-- Email -->
                    <div class="mb-4 bg-light-input">
                        <label for="emailInput" class="form-label">{{ tr('Email address') }} *</label>
                        <input type="email" name="email" class="form-control form-control-lg" id="emailInput" value="{{ old('email') }}" placeholder="{{ tr('Email') }}">
                    </div>
                    <!-- Phone -->
                    <div class="mb-4 bg-light-input">
                        <label for="phoneInput" class="form-label">{{ tr('Phone Number') }}</label>
                        <input type="text" name="phone" class="form-control form-control-lg" id="phoneInput" value="{{ old('phone') }}" placeholder="{{ tr('Phone Number') }}">
                    </div>
                    <!-- Subject -->
                    <div class="mb-4 bg-light-input">
                        <label for="subjectInput" class="form-label">{{ tr('Subject') }} *</label>
                        <input type="text" name="subject" class="form-control form-control-lg" id="subjectInput" value="{{ old('subject') }}" placeholder="{{ tr('Subject') }}">
                    </div>
                    <!-- Message -->
                    <div class="mb-4 bg-light-input">
                        <label for="textareaBox" class="form-label">{{ tr('Message') }} *</label>
                        <textarea name="message" class="form-control" id="textareaBox" rows="4" placeholder="Message">{{ old('message') }}</textarea>
                    </div>
                    <!-- Button -->
                    <div class="d-grid">
                        <button class="btn btn-lg btn-primary mb-0" type="submit">{{ tr('Send Message') }}</button>
                    </div>
                </form>

			</div>
			<!-- Contact form END -->
		</div>
	</div>
</section>

@include('sections.map.skin-1')
@endsection
