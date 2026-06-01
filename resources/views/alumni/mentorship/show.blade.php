@extends('layouts.app')
@section('title', 'Request Detail — IMConnect')

@section('content')
<div class="page-wrapper" style="max-width:680px;">

    <div style="margin-bottom:20px;">
        <a href="{{ route('alumni.requests.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-handshake"></i> Mentorship Request</h2>
            @if($request->isPending())
                <span class="badge badge-pending"><i class="fas fa-hourglass-half"></i> Pending</span>
            @elseif($request->isAccepted())
                <span class="badge badge-accepted"><i class="fas fa-check-circle"></i> Accepted</span>
            @else
                <span class="badge badge-rejected"><i class="fas fa-times-circle"></i> Declined</span>
            @endif
        </div>

        <div class="card-body">
            {{-- Student info --}}
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:22px;">
                <div class="avatar avatar-lg">
                    {{ strtoupper(substr($request->student->user->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:17px; font-weight:700;">
                        {{ $request->student->user->name }}
                    </div>
                    <div style="font-size:13px; color:var(--text-secondary);">
                        <i class="fas fa-book" style="font-size:12px;"></i>
                        {{ $request->student->course->name }}
                    </div>
                    @if($request->student->career_interest)
                    <div style="font-size:13px; color:var(--text-secondary); margin-top:3px;">
                        <i class="fas fa-bullseye" style="font-size:12px;"></i>
                        Career interest: {{ $request->student->career_interest }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="profile-detail">
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-calendar-alt"></i> Submitted</span>
                    <span class="detail-value">{{ $request->created_at->format('d F Y, h:i A') }}</span>
                </div>
                @if($request->responded_at)
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-reply"></i> Responded</span>
                    <span class="detail-value">{{ $request->responded_at->format('d F Y, h:i A') }}</span>
                </div>
                @endif
            </div>

            @if($request->message)
            <div style="margin-top:18px;">
                <p style="font-size:12.5px; font-weight:600; text-transform:uppercase;
                          letter-spacing:0.4px; color:var(--text-muted); margin-bottom:8px;">
                    Student's message
                </p>
                <div class="request-message">
                    &ldquo;{{ $request->message }}&rdquo;
                </div>
            </div>
            @endif

            {{-- Contact reveal on acceptance --}}
            @if($request->isAccepted())
                <div class="contact-reveal">
                    <h4><i class="fas fa-address-book"></i> Student contact details</h4>
                    <p><i class="fas fa-envelope"></i> {{ $request->student->user->email }}</p>
                    <p><i class="fas fa-phone"></i> {{ $request->student->phone }}</p>
                    <p style="font-size:12px; margin-top:10px; opacity:0.8;">
                        <i class="fas fa-info-circle"></i>
                        Continue your mentorship via WhatsApp or email.
                    </p>
                </div>

                @if($request->rejection_reason)
                <div style="margin-top:14px;">
                    <span style="font-size:12.5px; color:var(--text-muted);">Note:</span>
                    <p style="font-size:13.5px;">{{ $request->rejection_reason }}</p>
                </div>
                @endif

            @elseif($request->isPending())
                <div style="display:flex; gap:10px; margin-top:22px; flex-wrap:wrap;">
                    <form method="POST" action="{{ route('alumni.requests.accept', $request) }}"
                          onsubmit="return confirm('Accept this mentorship request from {{ $request->student->user->name }}?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Accept Request
                        </button>
                    </form>
                    <button onclick="document.getElementById('rejectForm').style.display='block'; this.style.display='none';"
                            class="btn btn-ghost" style="color:#c81e1e; border-color:#f0bbbb;">
                        <i class="fas fa-times"></i> Decline Request
                    </button>
                </div>

                <div id="rejectForm" style="display:none; margin-top:16px; padding:16px;
                                            background:var(--surface-2); border-radius:var(--radius-md);
                                            border:1px solid var(--border);">
                    <form method="POST" action="{{ route('alumni.requests.reject', $request) }}"
                          onsubmit="return confirm('Decline this request?')">
                        @csrf @method('PATCH')
                        <div class="form-group">
                            <label>Reason for declining (optional)</label>
                            <textarea name="rejection_reason" rows="3"
                                      placeholder="Let the student know why..."></textarea>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times-circle"></i> Confirm Decline
                            </button>
                            <button type="button"
                                    onclick="document.getElementById('rejectForm').style.display='none';"
                                    class="btn btn-ghost btn-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection