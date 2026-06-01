@extends('layouts.app')
@section('title', 'Request Detail — IMConnect')

@section('content')
<div class="page-wrapper" style="max-width:680px;">

    <div style="margin-bottom:20px;">
        <a href="{{ route('student.requests.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to my requests
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-handshake"></i> Mentorship Request</h2>
            @if($request->isPending())
                <span class="badge badge-pending"><i class="fas fa-hourglass-half"></i> Awaiting response</span>
            @elseif($request->isAccepted())
                <span class="badge badge-accepted"><i class="fas fa-check-circle"></i> Accepted</span>
            @else
                <span class="badge badge-rejected"><i class="fas fa-times-circle"></i> Declined</span>
            @endif
        </div>

        <div class="card-body">
            {{-- Alumni info --}}
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:22px;">
                <div class="avatar avatar-lg">
                    {{ strtoupper(substr($request->alumni->user->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-size:18px; font-weight:700;">{{ $request->alumni->user->name }}</div>
                    <div style="font-size:13px; color:var(--text-secondary);">
                        {{ $request->alumni->job_position ?? '' }}
                        @if($request->alumni->company) &bull; {{ $request->alumni->company }} @endif
                    </div>
                    @if($request->alumni->industry)
                        <span class="badge badge-info" style="margin-top:6px;">
                            {{ $request->alumni->industry }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="profile-detail">
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-paper-plane"></i> Sent</span>
                    <span class="detail-value">{{ $request->created_at->format('d F Y, h:i A') }}</span>
                </div>
                @if($request->responded_at)
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-reply"></i> Responded</span>
                        <span class="detail-value">{{ $request->responded_at->format('d F Y, h:i A') }}</span>
                    </div>
                @endif
            </div>

            {{-- Your message --}}
            @if($request->message)
                <div style="margin-top:18px;">
                    <p style="font-size:12.5px; font-weight:600; text-transform:uppercase;
                              letter-spacing:0.4px; color:var(--text-muted); margin-bottom:8px;">
                        Your message
                    </p>
                    <div class="request-message">&ldquo;{{ $request->message }}&rdquo;</div>
                </div>
            @endif

            {{-- Status-specific content --}}
            @if($request->isPending())
                <div class="alert alert-warning" style="margin-top:20px;">
                    <i class="fas fa-hourglass-half"></i>
                    <span>
                        Your request is waiting for a response.
                        You'll be notified once <strong>{{ Str::words($request->alumni->user->name, 1, '') }}</strong> replies.
                    </span>
                </div>

            @elseif($request->isAccepted())
                <div class="alert alert-success" style="margin-top:20px;">
                    <i class="fas fa-check-circle"></i>
                    <span>
                        <strong>Congratulations!</strong>
                        {{ Str::words($request->alumni->user->name, 1, '') }} has accepted your request.
                        Their contact details are now available below.
                    </span>
                </div>

                <div class="contact-reveal">
                    <h4><i class="fas fa-address-book"></i> Alumni contact details</h4>
                    <p><i class="fas fa-envelope"></i> {{ $request->alumni->user->email }}</p>
                    <p><i class="fas fa-phone"></i> {{ $request->alumni->phone }}</p>
                    @if($request->alumni->linkedin_url)
                        <p>
                            <i class="fab fa-linkedin"></i>
                            <a href="{{ $request->alumni->linkedin_url }}" target="_blank"
                               style="color:var(--success-text);">
                                LinkedIn Profile
                            </a>
                        </p>
                    @endif
                    <p style="font-size:12px; margin-top:10px; opacity:0.8;">
                        <i class="fas fa-info-circle"></i>
                        Reach out via WhatsApp or email to begin your mentorship.
                    </p>
                </div>

            @elseif($request->isRejected())
                <div class="alert alert-danger" style="margin-top:20px;">
                    <i class="fas fa-times-circle"></i>
                    <div>
                        <strong>This request was declined.</strong>
                        @if($request->rejection_reason)
                            <br>
                            <span style="font-size:13.5px; margin-top:4px; display:block;">
                                {{ $request->rejection_reason }}
                            </span>
                        @else
                            <span> You may search for another alumni mentor.</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('student.alumni.index') }}"
                   class="btn btn-outline" style="margin-top:14px;">
                    <i class="fas fa-search"></i> Find another mentor
                </a>
            @endif

        </div>
    </div>

</div>
@endsection