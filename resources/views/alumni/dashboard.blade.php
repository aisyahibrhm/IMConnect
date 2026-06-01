@extends('layouts.app')
@section('title', 'Alumni Dashboard — IMConnect')

@section('content')
<div class="dashboard-container">

    <div class="welcome-box">
        <h1><i class="fas fa-hand-wave" style="color:var(--crimson);"></i>
            Welcome back, {{ Str::words(Auth::user()->name, 2, '') }}
        </h1>
        <p>Manage your profile and respond to mentorship requests from students.</p>
    </div>

    @if(!$profileComplete)
    <div class="alert alert-warning" style="margin-bottom:24px;">
        <i class="fas fa-exclamation-triangle"></i>
        <span>
            Your profile is incomplete. Students won't be able to find you in search results.
            <a href="{{ route('alumni.profile') }}" style="font-weight:700; color:var(--warning-text);">
                Complete your profile &rarr;
            </a>
        </span>
    </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending requests</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['accepted'] }}</div>
                <div class="stat-label">Accepted mentorships</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['rejected'] }}</div>
                <div class="stat-label">Declined requests</div>
            </div>
        </div>
    </div>

    <div class="grid-cards">

        {{-- Profile Summary --}}
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-id-card"></i> My Profile</h2>
                <a href="{{ route('alumni.profile') }}" class="btn btn-outline btn-sm">
                    <i class="fas fa-pen"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:18px;">
                    <div class="avatar avatar-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-size:16px; font-weight:700;">{{ Auth::user()->name }}</div>
                        <div style="font-size:13px; color:var(--text-secondary);">
                            {{ $alumni->job_position ?? 'Position not set' }}
                            @if($alumni->company)
                                &bull; {{ $alumni->company }}
                            @endif
                        </div>
                        <div style="margin-top:6px; display:flex; gap:6px;">
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
                        </div>
                    </div>
                </div>

                <div class="profile-detail">
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-graduation-cap"></i> Course</span>
                        <span class="detail-value">{{ $alumni->course->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-industry"></i> Industry</span>
                        <span class="detail-value">{{ $alumni->industry ?? '—' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-clock"></i> Experience</span>
                        <span class="detail-value">
                            {{ $alumni->years_experience ? $alumni->years_experience . ' years' : '—' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Requests --}}
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-envelope-open-text"></i> Pending Requests</h2>
                @if($stats['pending'] > 0)
                    <span class="badge badge-pending">{{ $stats['pending'] }} new</span>
                @endif
            </div>
            <div class="card-body" style="padding:0;">
                @forelse($recentRequests->where('status','pending') as $req)
                    <a href="{{ route('alumni.requests.show', $req) }}"
                       style="display:flex; align-items:center; gap:13px; padding:14px 20px;
                              border-bottom:1px solid var(--border); text-decoration:none;
                              transition:background 0.15s;"
                       onmouseover="this.style.background='var(--surface-2)'"
                       onmouseout="this.style.background='transparent'">
                        <div class="avatar">{{ strtoupper(substr($req->student->user->name, 0, 2)) }}</div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-weight:600; font-size:14px; color:var(--text-primary);">
                                {{ $req->student->user->name }}
                            </div>
                            <div style="font-size:12.5px; color:var(--text-muted);">
                                {{ $req->student->course->name }}
                                &bull; {{ $req->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <span class="badge badge-pending">Pending</span>
                    </a>
                @empty
                    <div class="empty-state" style="padding:40px 24px;">
                        <i class="fas fa-inbox"></i>
                        <h3>No pending requests</h3>
                        <p>You're all caught up!</p>
                    </div>
                @endforelse
                @if($stats['pending'] > 5)
                    <a href="{{ route('alumni.requests.index') }}"
                       style="display:block; padding:12px 20px; text-align:center; font-size:13px;
                              font-weight:600; color:var(--crimson); text-decoration:none; border-top:1px solid var(--border);">
                        View all {{ $stats['pending'] }} requests &rarr;
                    </a>
                @endif
            </div>
        </div>

    </div>

    <div style="text-align:center; margin-top:8px;">
        <a href="{{ route('alumni.requests.index') }}" class="btn btn-outline">
            <i class="fas fa-list"></i> View All Mentorship Requests
        </a>
    </div>

</div>
@endsection