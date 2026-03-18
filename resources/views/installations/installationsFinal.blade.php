<!-- installationsFinal.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Final Installation Step - Migrate Tables</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('installationsFinal') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Migrate Tables</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
