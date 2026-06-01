@extends('layouts.app')
@section('title', 'Student Dashboard — IMConnect')

@section('content')
<div class="dashboard-container">

    {{-- Welcome --}}
    <div class="welcome-box">
        <h1>Welcome, {{ Str::words(Auth::user()->name, 2, '') }} 👋</h1>
        <p>Discover alumni mentors, submit requests, and track your mentorship journey.</p>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Awaiting response</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-handshake"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['accepted'] }}</div>
                <div class="stat-label">Active mentorships</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon crimson"><i class="fas fa-paper-plane"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total requests sent</div>
            </div>
        </div>
    </div>

    {{-- Recommended Alumni --}}
    <div class="card" style="margin-bottom:28px;">
        <div class="card-header">
            <h2><i class="fas fa-star"></i> Recommended for you</h2>
            <a href="{{ route('student.alumni.index') }}" class="btn btn-ghost btn-sm">
                Browse all <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="card-body">
            @if(!$profileComplete)
                {{-- Profile incomplete prompt --}}
                <div style="display:flex; align-items:center; gap:18px; padding:10px 0;">
                    <div style="width:56px; height:56px; border-radius:var(--radius-lg);
                                background:var(--crimson-subtle); display:flex; align-items:center;
                                justify-content:center; flex-shrink:0; font-size:26px; color:var(--crimson);">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div style="flex:1;">
                        <p style="font-weight:700; font-size:15px; margin-bottom:4px; color:var(--text-primary);">
                            Complete your profile to get personalised recommendations
                        </p>
                        <p style="font-size:13.5px; color:var(--text-secondary); margin-bottom:12px;">
                            Add your career interest and industry preference so we can match you
                            with the most relevant alumni mentors.
                        </p>
                        <a href="{{ route('student.profile.edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-pen"></i> Update my profile
                        </a>
                    </div>
                </div>

            @elseif($recommendations->isEmpty())
                <div class="empty-state" style="padding:30px 0;">
                    <i class="fas fa-search"></i>
                    <h3>No matches found yet</h3>
                    <p>We couldn't find alumni matching your profile right now. Try browsing all alumni.</p>
                    <a href="{{ route('student.alumni.index') }}" class="btn btn-outline" style="margin-top:14px;">
                        <i class="fas fa-users"></i> Browse alumni
                    </a>
                </div>

            @else
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                    @foreach($recommendations as $rec)
                        <a href="{{ route('student.alumni.show', $rec) }}" class="alumni-card">
                            <div class="alumni-card-header">
                                <div class="avatar">
                                    {{ strtoupper(substr($rec->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="alumni-card-name">{{ $rec->user->name }}</div>
                                    <div class="alumni-card-meta">
                                        <i class="fas fa-briefcase" style="font-size:11px;"></i>
                                        {{ $rec->job_position ?? 'Position not set' }}
                                    </div>
                                </div>
                            </div>
                            <div class="alumni-card-tags">
                                @if($rec->industry)
                                    <span class="badge badge-info">{{ $rec->industry }}</span>
                                @endif
                                @if($rec->course)
                                    <span class="badge" style="background:var(--crimson-subtle);
                                                               color:var(--crimson);
                                                               border:1px solid var(--crimson-muted);">
                                        {{ $rec->course->code }}
                                    </span>
                                @endif
                            </div>
                            {{-- Match strength indicator --}}
                            <div style="display:flex; align-items:center; gap:6px; margin-top:4px;">
                                @php $pct = min(100, ($rec->match_score / 100) * 100); @endphp
                                <div style="flex:1; height:4px; background:var(--border);
                                            border-radius:var(--radius-full); overflow:hidden;">
                                    <div style="width:{{ $pct }}%; height:100%;
                                                background:var(--crimson);
                                                border-radius:var(--radius-full);"></div>
                                </div>
                                <span style="font-size:11.5px; color:var(--text-muted); white-space:nowrap;">
                                    {{ $rec->match_score }}% match
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent request activity --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-paper-plane"></i> My recent requests</h2>
            <a href="{{ route('student.requests.index') }}" class="btn btn-ghost btn-sm">
                View all <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse($recentRequests as $req)
                <a href="{{ route('student.requests.show', $req) }}"
                   style="display:flex; align-items:center; gap:13px; padding:14px 20px;
                          border-bottom:1px solid var(--border); text-decoration:none;
                          transition:background 0.15s; color:inherit;"
                   onmouseover="this.style.background='var(--surface-2)'"
                   onmouseout="this.style.background='transparent'">
                    <div class="avatar">
                        {{ strtoupper(substr($req->alumni->user->name, 0, 2)) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:600; font-size:14px;">{{ $req->alumni->user->name }}</div>
                        <div style="font-size:12.5px; color:var(--text-muted);">
                            {{ $req->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @if($req->isPending())
                        <span class="badge badge-pending"><i class="fas fa-hourglass-half"></i> Pending</span>
                    @elseif($req->isAccepted())
                        <span class="badge badge-accepted"><i class="fas fa-check"></i> Accepted</span>
                    @else
                        <span class="badge badge-rejected"><i class="fas fa-times"></i> Declined</span>
                    @endif
                </a>
            @empty
                <div class="empty-state" style="padding:40px 24px;">
                    <i class="fas fa-paper-plane"></i>
                    <h3>No requests yet</h3>
                    <p>Find an alumni mentor and send your first mentorship request.</p>
                    <a href="{{ route('student.alumni.index') }}" class="btn btn-primary" style="margin-top:14px;">
                        <i class="fas fa-search"></i> Find alumni
                    </a>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection