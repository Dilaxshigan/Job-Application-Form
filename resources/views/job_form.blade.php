@extends('layouts.app')
@section('main-content')

<div class="bg-light d-flex align-items-center justify-content-center vh-100">
<div class="bg-white p-4 rounded shadow w-100" style="max-width: 500px;">
        <h1 class="text-center mb-4">Job Application Form</h1>
        @if (session('success'))
            <div class="alert alert-success">
               <div class="text-center"> {{ session('success') }} 
                @if (session('cv_url'))
                    <p class="mt-2">
                        <strong>Upload CV URL: </strong>
                        <a href="{{ session('cv_url') }}" target="_blank">Click and View your Resume</a>
                    </p>
                @endif
                </div>
            </div>
        @endif
        <form action="{{ route('job.application.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control">
                @error('name')
                            <span class="text-danger fw-semibold">Error message goes here</span>
                            @enderror
            </div>

            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control">
                @error('email')
                            <span class="text-danger fw-semibold">Error message goes here</span>
                            @enderror
            </div>

            <!-- Phone Number Field -->
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" name="phone" id="phone" class="form-control">
                @error('phone')
                            <span class="text-danger fw-semibold">Error message goes here</span>
                            @enderror
            </div>

            <!-- CV Upload Field -->
            <div class="mb-3">
                <label for="cv" class="form-label">Upload CV (PDF or DOCX)</label>
                <input type="file" name="cv" id="cv" class="form-control" accept=".pdf,.docx">
                @error('cv')
                            <span class="text-danger fw-semibold">Error message goes here</span>
                            @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-warning">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection
@section('styles')
<link rel="stylesheet" href="#">
@endsection
@section('scripts')
<script src="#"></script>
@endsection