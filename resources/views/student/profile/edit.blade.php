@extends('layouts.app')
@section('title', 'My Profile — IMConnect')

@section('content')
<div class="page-wrapper" style="max-width:600px;">

    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> My Profile</h1>
        <p>Update your interests so we can recommend the most relevant alumni mentors.</p>
    </div>

    {{-- Account info --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="display:flex; align-items:center; gap:16px;">
            <div class="avatar avatar-lg">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <div style="font-size:18px; font-weight:700;">{{ Auth::user()->name }}</div>
                <div style="font-size:13px; color:var(--text-secondary);">
                    {{ $student->course->name }} &bull; Class of {{ $student->graduation_year }}
                </div>
                <div style="font-size:13px; color:var(--text-muted); margin-top:2px;">
                    {{ Auth::user()->email }}
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('student.profile.update') }}">
        @csrf @method('PUT')

        <div class="form-container">
            <div class="card-header">
                <h2><i class="fas fa-bullseye"></i> Career interests</h2>
            </div>
            <div class="form-body">
                <div class="alert alert-info" style="margin-bottom:20px;">
                    <i class="fas fa-lightbulb"></i>
                    <span>
                        These fields power your personalised alumni recommendations.
                        Be specific — e.g. "Software Engineering" rather than "IT".
                    </span>
                </div>

                <div class="form-group">
                    <label for="career_interest">Career interest</label>
                    <input type="text" id="career_interest" name="career_interest"
                           value="{{ old('career_interest', $student->career_interest) }}"
                           placeholder="e.g. Software Engineering, Accounting, Marketing">
                    <span style="font-size:12px; color:var(--text-muted); margin-top:5px; display:block;">
                        What job role or career path are you aiming for?
                    </span>
                </div>

                <div class="form-group">
                    <label for="industry_interest">Industry interest</label>
                    <input type="text" id="industry_interest" name="industry_interest"
                           value="{{ old('industry_interest', $student->industry_interest) }}"
                           placeholder="e.g. Technology, Finance, Healthcare, Education">
                    <span style="font-size:12px; color:var(--text-muted); margin-top:5px; display:block;">
                        Which industry do you want to work in?
                    </span>
                </div>
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <a href="{{ route('student.home') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Save interests
            </button>
        </div>
    </form>

</div>
@endsection