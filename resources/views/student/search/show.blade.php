@extends('layouts.app')
@section('title', '{{ $alumni->user->name }} — IMConnect')

@section('content')
<div class="page-wrapper" style="max-width:720px;">

    <div style="margin-bottom:20px;">
        <a href="{{ route('student.alumni.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to search
        </a>
    </div>

    {{-- Profile hero card --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">
            <div style="display:flex; align-items:flex-start; gap:18px; flex-wrap:wrap;">
                <div class="avatar avatar-lg">
                    {{ strtoupper(substr($alumni->user->name, 0, 2)) }}
                </div>
                <div style="flex:1; min-width:200px;">
                    <h2 style="font-size:22px; font-weight:800; letter-spacing:-0.3px; margin-bottom:4px;">
                        {{ $alumni->user->name }}
                    </h2>
                    @if($alumni->job_position || $alumni->company)
                        <p style="font-size:15px; color:var(--text-secondary); margin-bottom:10px;">
                            {{ $alumni->job_position ?? '' }}
                            @if($alumni->job_position && $alumni->company) &bull; @endif
                            {{ $alumni->company ?? '' }}
                        </p>
                    @endif

                    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px;">
                        @if($alumni->industry)
                            <span class="badge badge-info">
                                <i class="fas fa-industry" style="font-size:10px;"></i>
                                {{ $alumni->industry }}
                            </span>
                        @endif
                        <span class="badge" style="background:var(--crimson-subtle);
                                                   color:var(--crimson);
                                                   border:1px solid var(--crimson-muted);">
                            <i class="fas fa-graduation-cap" style="font-size:10px;"></i>
                            {{ $alumni->course->name }}
                        </span>
                        @if($alumni->graduation_year)
                            <span class="badge" style="background:var(--surface-2);
                                                       color:var(--text-secondary);
                                                       border:1px solid var(--border);">
                                Class of {{ $alumni->graduation_year }}
                            </span>
                        @endif
                    </div>

                    {{-- Social links --}}
                    @if($alumni->linkedin_url || $alumni->instagram_url)
                        <div style="display:flex; gap:8px; align-items:center;">
                            <span style="font-size:12.5px; color:var(--text-muted);">Connect:</span>
                            @if($alumni->linkedin_url)
                                <a href="{{ $alumni->linkedin_url }}" target="_blank"
                                   class="social-icon linkedin" title="LinkedIn profile"
                                   onclick="event.stopPropagation()">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            @endif
                            @if($alumni->instagram_url)
                                <a href="{{ $alumni->instagram_url }}" target="_blank"
                                   class="social-icon instagram" title="Instagram profile"
                                   onclick="event.stopPropagation()">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Career details --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h2><i class="fas fa-briefcase"></i> Career details</h2>
        </div>
        <div class="card-body">
            <div class="profile-detail">
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-building"></i> Company</span>
                    <span class="detail-value">{{ $alumni->company ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-id-badge"></i> Job position</span>
                    <span class="detail-value">{{ $alumni->job_position ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-industry"></i> Industry</span>
                    <span class="detail-value">{{ $alumni->industry ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-clock"></i> Experience</span>
                    <span class="detail-value">
                        {{ $alumni->years_experience ? $alumni->years_experience . ' year(s)' : '—' }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-book"></i> Course</span>
                    <span class="detail-value">{{ $alumni->course->name }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Request Mentorship --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-handshake"></i> Mentorship</h2>
        </div>
        <div class="card-body">
            @if($existingRequest)
                @if($existingRequest->isPending())
                    <div class="alert alert-warning">
                        <i class="fas fa-hourglass-half"></i>
                        <div>
                            <strong>Request already sent.</strong>
                            You submitted a mentorship request to this alumni on
                            {{ $existingRequest->created_at->format('d M Y') }}.
                            Please wait for their response.
                        </div>
                    </div>
                    <a href="{{ route('student.requests.show', $existingRequest) }}"
                       class="btn btn-ghost btn-sm" style="margin-top:4px;">
                        <i class="fas fa-eye"></i> View my request
                    </a>
                @elseif($existingRequest->isAccepted())
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>You are connected!</strong>
                            This alumni has accepted your mentorship request.
                        </div>
                    </div>
                    <a href="{{ route('student.requests.show', $existingRequest) }}"
                       class="btn btn-success btn-sm" style="margin-top:4px;">
                        <i class="fas fa-address-book"></i> View contact details
                    </a>
                @endif
            @else
                <p style="font-size:14px; color:var(--text-secondary); margin-bottom:16px;">
                    Interested in getting guidance from <strong>{{ $alumni->user->name }}</strong>?
                    Send a mentorship request and introduce yourself.
                </p>
                <a href="{{ route('student.requests.create', $alumni) }}" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Request Mentorship
                </a>
            @endif
        </div>
    </div>

</div>
@endsection