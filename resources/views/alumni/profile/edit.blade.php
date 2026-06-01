@extends('layouts.app')
@section('title', 'Edit Profile — IMConnect')

@section('content')
<div class="page-wrapper" style="max-width:720px;">

    <div class="page-header">
        <h1><i class="fas fa-id-card"></i> My Profile</h1>
        <p>Keep your professional details up to date so students can find and connect with you.</p>
    </div>

    {{-- Profile header card --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="display:flex; align-items:center; gap:16px;">
            <div class="avatar avatar-lg">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <div style="font-size:18px; font-weight:700;">{{ Auth::user()->name }}</div>
                <div style="font-size:13px; color:var(--text-secondary);">
                    {{ $alumni->course->name }} &bull; Class of {{ $alumni->graduation_year }}
                </div>
                <div style="margin-top:6px;">
                    <span class="badge badge-approved">
                        <i class="fas fa-check-circle"></i> Approved Alumni
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('alumni.profile.update') }}">
        @csrf @method('PUT')

        {{-- Professional Details --}}
        <div class="form-container">
            <div class="card-header">
                <h2><i class="fas fa-briefcase"></i> Professional details</h2>
            </div>
            <div class="form-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="company">Company / Organisation</label>
                        <input type="text" id="company" name="company"
                               value="{{ old('company', $alumni->company) }}"
                               placeholder="e.g. Petronas, CIMB Bank">
                    </div>
                    <div class="form-group">
                        <label for="job_position">Job position / Title</label>
                        <input type="text" id="job_position" name="job_position"
                               value="{{ old('job_position', $alumni->job_position) }}"
                               placeholder="e.g. Software Engineer">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="industry">Industry / Sector</label>
                        <input type="text" id="industry" name="industry"
                               value="{{ old('industry', $alumni->industry) }}"
                               placeholder="e.g. Technology, Finance, Education">
                    </div>
                    <div class="form-group">
                        <label for="years_experience">Years of experience</label>
                        <input type="number" id="years_experience" name="years_experience"
                               value="{{ old('years_experience', $alumni->years_experience) }}"
                               min="0" max="50" placeholder="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- Social Media --}}
        <div class="form-container">
            <div class="card-header">
                <h2><i class="fas fa-share-alt"></i> Social media links</h2>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label for="linkedin_url">
                        <i class="fab fa-linkedin" style="color:#0a66c2;"></i>
                        LinkedIn profile URL
                    </label>
                    <input type="url" id="linkedin_url" name="linkedin_url"
                           value="{{ old('linkedin_url', $alumni->linkedin_url) }}"
                           placeholder="https://linkedin.com/in/your-name">
                    @error('linkedin_url')
                        <div class="error-message visible">
                            <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="instagram_url">
                        <i class="fab fa-instagram" style="color:#c13584;"></i>
                        Instagram profile URL
                    </label>
                    <input type="url" id="instagram_url" name="instagram_url"
                           value="{{ old('instagram_url', $alumni->instagram_url) }}"
                           placeholder="https://instagram.com/your-handle">
                    @error('instagram_url')
                        <div class="error-message visible">
                            <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="facebook_url">
                        <i class="fab fa-facebook" style="color:#1877f2;"></i>
                        Facebook profile URL
                    </label>
                    <input type="url" id="facebook_url" name="facebook_url"
                           value="{{ old('facebook_url', $alumni->facebook_url) }}"
                           placeholder="https://facebook.com/your.profile">
                    @error('facebook_url')
                        <div class="error-message visible">
                            <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                {{-- Preview social icons --}}
                @if($alumni->linkedin_url || $alumni->instagram_url || $alumni->facebook_url)
                <div style="margin-top:6px;">
                    <p style="font-size:12.5px; color:var(--text-muted); margin-bottom:8px;">
                        Preview — visible to students:
                    </p>
                    <div style="display:flex; gap:8px;">
                        @if($alumni->linkedin_url)
                            <a href="{{ $alumni->linkedin_url }}" target="_blank" class="social-icon linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        @endif
                        @if($alumni->instagram_url)
                            <a href="{{ $alumni->instagram_url }}" target="_blank" class="social-icon instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if($alumni->facebook_url)
                            <a href="{{ $alumni->facebook_url }}" target="_blank" class="social-icon facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <a href="{{ route('alumni.home') }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Save Profile
            </button>
        </div>
    </form>
</div>
@endsection