@extends('layouts.app')
@section('main-content')

<div class="bg-light">
    <div class="container mt-5">
       @if (session('success'))
            <div class="alert alert-success col-md-8 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger col-md-8 text-center">
                {{ session('error') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h1 class="text-center">Extracted CV Data</h1>
            </div>
            <div class="card-body">
                <h3>Personal Info</h3>
                <p>{{ $cvData->personal_info }}</p>

                <h3>Education</h3>
                <p>{{ $cvData->education }}</p>

                <h3>Qualifications</h3>
                <p>{{ $cvData->qualifications }}</p>

                <h3>Projects</h3>
                <p>{{ $cvData->projects }}</p>

                <h3>CV Link</h3>
                <a href="{{ $cvData->cv_url }}" target="_blank">Download CV</a>
            </div>
        </div>
    </div>
</div>

@endsection
@section('styles')
<link rel="stylesheet" href="#">
@endsection
@section('scripts')
<script src="#"></script>
@endsection