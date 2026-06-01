@extends('layouts.app')
@section('title', 'My Requests — IMConnect')

@section('content')
<div class="dashboard-container">

    <div class="page-header">
        <h1><i class="fas fa-paper-plane"></i> My Mentorship Requests</h1>
        <p>Track the status of all your submitted mentorship requests.</p>
    </div>

    {{-- Pending --}}
    <h2 class="section-heading"><i class="fas fa-hourglass-half"></i> Awaiting response</h2>

    @if($pending->isEmpty())
        <div class="card" style="margin-bottom:28px;">
            <div class="empty-state" style="padding:36px;">
                <i class="fas fa-hourglass"></i>
                <h3>No pending requests</h3>
                <p>You have no requests waiting for a response right now.</p>
            </div>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:32px;">
            @foreach($pending as $req)
                <a href="{{ route('student.requests.show', $req) }}"
                   style="text-decoration:none; color:inherit;">
                <div class="request-card" style="cursor:pointer;"
                     onmouseover="this.style.borderColor='var(--crimson-muted)'"
                     onmouseout="this.style.borderColor='var(--border)'">
                    <div class="request-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="avatar">
                                {{ strtoupper(substr($req->alumni->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:700; font-size:15px;">
                                    {{ $req->alumni->user->name }}
                                </div>
                                <div style="font-size:12.5px; color:var(--text-muted);">
                                    <i class="fas fa-clock" style="font-size:11px;"></i>
                                    Sent {{ $req->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <span class="badge badge-pending">
                            <i class="fas fa-hourglass-half"></i> Pending
                        </span>
                    </div>
                    @if($req->message)
                        <div class="request-message">
                            &ldquo;{{ Str::limit($req->message, 120) }}&rdquo;
                        </div>
                    @endif
                </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Accepted --}}
    <h2 class="section-heading" style="border-color:#b8debb; color:var(--success-text);">
        <i class="fas fa-check-circle" style="color:#0e9f6e;"></i> Accepted mentorships
    </h2>

    @if($accepted->isEmpty())
        <div class="card" style="margin-bottom:28px;">
            <div class="empty-state" style="padding:36px;">
                <i class="fas fa-handshake"></i>
                <h3>No accepted mentorships yet</h3>
                <p>When an alumni accepts your request, their contact details will appear here.</p>
            </div>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:32px;">
            @foreach($accepted as $req)
                <a href="{{ route('student.requests.show', $req) }}"
                   style="text-decoration:none; color:inherit;">
                <div class="request-card" style="border-color:var(--success-border); cursor:pointer;"
                     onmouseover="this.style.borderColor='#0e9f6e'"
                     onmouseout="this.style.borderColor='var(--success-border)'">
                    <div class="request-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="avatar" style="background:#def7ec; color:#0e9f6e;">
                                {{ strtoupper(substr($req->alumni->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:700; font-size:15px;">
                                    {{ $req->alumni->user->name }}
                                </div>
                                <div style="font-size:12.5px; color:var(--text-muted);">
                                    <i class="fas fa-check-circle" style="font-size:11px; color:#0e9f6e;"></i>
                                    Accepted {{ $req->responded_at?->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span class="badge badge-accepted">
                                <i class="fas fa-check"></i> Accepted
                            </span>
                            <span style="font-size:12.5px; color:var(--success-text); font-weight:600;
                                         display:flex; align-items:center; gap:4px;">
                                <i class="fas fa-address-book" style="font-size:11px;"></i>
                                Contact revealed
                            </span>
                        </div>
                    </div>
                </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Declined --}}
    <h2 class="section-heading" style="border-color:var(--danger-border); color:var(--danger-text);">
        <i class="fas fa-times-circle" style="color:#c81e1e;"></i> Declined requests
    </h2>

    @if($rejected->isEmpty())
        <div class="card">
            <div class="empty-state" style="padding:36px;">
                <i class="fas fa-times-circle"></i>
                <h3>No declined requests</h3>
                <p>Requests that were not accepted will appear here.</p>
            </div>
        </div>
    @else
        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:12px;">
            @foreach($rejected as $req)
                <a href="{{ route('student.requests.show', $req) }}"
                   style="text-decoration:none; color:inherit;">
                <div class="request-card" style="border-color:var(--danger-border); opacity:0.85; cursor:pointer;">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                        <div class="avatar" style="background:var(--danger-bg); color:#c81e1e; width:36px; height:36px; font-size:13px;">
                            {{ strtoupper(substr($req->alumni->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:14px;">{{ $req->alumni->user->name }}</div>
                            <div style="font-size:12px; color:var(--text-muted);">
                                {{ $req->responded_at?->format('d M Y') ?? $req->created_at->format('d M Y') }}
                            </div>
                        </div>
                        <span class="badge badge-rejected" style="margin-left:auto;">
                            <i class="fas fa-times"></i> Declined
                        </span>
                    </div>
                    <p style="font-size:12.5px; color:var(--text-muted);">
                        You may search for another alumni mentor.
                    </p>
                </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
@endsection