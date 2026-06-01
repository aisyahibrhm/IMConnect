@extends('layouts.app')
@section('title', 'Admin Dashboard — IMConnect')

@section('content')
<div class="dashboard-container">

    <div class="welcome-box">
        <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
        <p>Welcome back, <strong>{{ Auth::user()->name }}</strong>. Here is your system overview.</p>
    </div>

    {{-- Inactive users notice --}}
    @php $inactiveCount = \App\Models\User::where('role','!=','admin')->where('is_active', false)->count(); @endphp
    @if($inactiveCount > 0)
    <div class="alert alert-warning" style="margin-bottom:24px;">
        <i class="fas fa-user-slash"></i>
        <span>
            There are <strong>{{ $inactiveCount }}</strong> deactivated user account(s) on the platform.
            <a href="{{ route('admin.users.index', ['status' => 'inactive']) }}"
               style="font-weight:700; color:var(--warning-text);">
                View them &rarr;
            </a>
        </span>
    </div>
    @endif

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card border-blue">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-info">
                <h2>{{ $stats['total_students'] }}</h2>
                <p>Registered Students</p>
            </div>
        </div>
        <div class="stat-card border-green">
            <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
            <div class="stat-info">
                <h2>{{ $stats['total_alumni'] }}</h2>
                <p>Approved Alumni</p>
            </div>
        </div>
        <div class="stat-card border-yellow">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h2>{{ $stats['pending_approvals'] }}</h2>
                <p>Pending Approvals</p>
            </div>
        </div>
        <div class="stat-card" style="border-left-color:#9b59b6;">
            <div class="stat-icon"><i class="fas fa-handshake"></i></div>
            <div class="stat-info">
                <h2>{{ $stats['total_requests'] }}</h2>
                <p>Total Mentorship Requests</p>
            </div>
        </div>
        <div class="stat-card" style="border-left-color:#2ecc71;">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h2>{{ $stats['accepted_requests'] }}</h2>
                <p>Accepted Requests</p>
            </div>
        </div>
        <div class="stat-card" style="border-left-color:#e67e22;">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-info">
                <h2>{{ $stats['pending_requests'] }}</h2>
                <p>Pending Requests</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <h2 class="section-heading"><i class="fas fa-bolt"></i> Quick Actions</h2>
    <div class="action-grid" style="margin-bottom:40px;">
        <a href="{{ route('admin.alumni.index') }}" class="action-card">
            <i class="fas fa-user-check fa-2x"></i>
            <h3>Alumni Approvals</h3>
            @if($stats['pending_approvals'] > 0)
                <span class="status-pending">{{ $stats['pending_approvals'] }} pending</span>
            @endif
        </a>
        <a href="{{ route('admin.users.index') }}" class="action-card">
            <i class="fas fa-users fa-2x"></i>
            <h3>Manage Users</h3>
        </a>
        <a href="{{ route('admin.mentorship.index') }}" class="action-card">
            <i class="fas fa-handshake fa-2x"></i>
            <h3>Mentorship Monitor</h3>
        </a>
    </div>

    <div class="grid-cards">
        {{-- Pending Approvals --}}
        <div class="card">
            <h2><i class="fas fa-clock"></i> Pending Alumni Approvals</h2>
            @forelse($recentApprovals as $alumni)
                <div style="display:flex; justify-content:space-between; align-items:center;
                            padding:10px 0; border-bottom:1px solid #eee;">
                    <div>
                        <strong>{{ $alumni->user->name }}</strong><br>
                        <small style="color:#666;">{{ $alumni->course->name }} &bull; {{ $alumni->college_email }}</small>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <form method="POST" action="{{ route('admin.alumni.approve', $alumni) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="button-icon" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.alumni.reject', $alumni) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="button-icon danger" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="color:#888;"><i class="fas fa-check-circle"></i> No pending approvals.</p>
            @endforelse
            @if($stats['pending_approvals'] > 5)
                <a href="{{ route('admin.alumni.index') }}" style="display:block; margin-top:12px; color:#841c26;">
                    View all {{ $stats['pending_approvals'] }} pending &rarr;
                </a>
            @endif
        </div>

        {{-- Recent Mentorship Activity --}}
        <div class="card">
            <h2><i class="fas fa-handshake"></i> Recent Mentorship Activity</h2>
            @forelse($recentRequests as $req)
                <div style="padding:10px 0; border-bottom:1px solid #eee;">
                    <div style="display:flex; justify-content:space-between;">
                        <span><strong>{{ $req->student->user->name }}</strong></span>
                        @php
                            $badgeClass = match($req->status) {
                                'accepted' => 'status-resolved',
                                'rejected' => 'status-pending',
                                default    => 'status-pending',
                            };
                            $badgeStyle = $req->status === 'rejected'
                                ? 'background:#f8d7da;color:#721c24;border-color:#f5c6cb;'
                                : '';
                        @endphp
                        <span class="{{ $badgeClass }}" style="{{ $badgeStyle }}">
                            {{ ucfirst($req->status) }}
                        </span>
                    </div>
                    <small style="color:#666;">
                        <i class="fas fa-arrow-right"></i> {{ $req->alumni->user->name }}
                        &bull; {{ $req->created_at->diffForHumans() }}
                    </small>
                </div>
            @empty
                <p style="color:#888;"><i class="fas fa-info-circle"></i> No mentorship requests yet.</p>
            @endforelse
            <a href="{{ route('admin.mentorship.index') }}" style="display:block; margin-top:12px; color:#841c26;">
                View all activity &rarr;
            </a>
        </div>
    </div>

</div>
@endsection