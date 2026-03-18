@extends('layouts.app')
@section('content')
 <!-- **************** MAIN CONTENT START **************** -->
 <main>
    <section class="pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
    <div>
        <h4>{{ tr('Something went wrong. Please seek assistance by') }} <a class="text-warning link" href="{{ route('contact.show') }}">{{ tr('contacting') }}</a> {{ tr('the administrator or your instructor.') }}</h4>
        <p>{{ $error }}</p> <!-- Display the error message passed from the controller -->
        <!-- You can customize this view further as needed -->
    </div>
</div>
</div>
</div>
</section>
</main>
<!-- **************** MAIN CONTENT END **************** -->

@endsection
