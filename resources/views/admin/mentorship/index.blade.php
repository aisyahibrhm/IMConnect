@extends('layouts.app')
@section('title', 'Mentorship Monitor — IMConnect')

@section('content')
<div class="dashboard-container">
    <div class="welcome-box">
        <h1><i class="fas fa-handshake"></i> Mentorship Activity Monitor</h1>
        <p>Real-time overview of all mentorship requests across the platform.</p>
    </div>

    {{-- Stats --}}
    <div class="stats-grid" style="margin-bottom:30px;">
        <div class="stat-card" style="border-left-color:#3498db;">
            <div class="stat-icon"><i class="fas fa-list"></i></div>
            <div class="stat-info"><h2>{{ $stats['total'] }}</h2><p>Total Requests</p></div>
        </div>
        <div class="stat-card border-yellow">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-info"><h2>{{ $stats['pending'] }}</h2><p>Pending</p></div>
        </div>
        <div class="stat-card border-green">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info"><h2>{{ $stats['accepted'] }}</h2><p>Accepted</p></div>
        </div>
        <div class="stat-card" style="border-left-color:#c0392b;">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-info"><h2>{{ $stats['rejected'] }}</h2><p>Rejected</p></div>
        </div>
    </div>

    <div class="form-container">
        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.mentorship.index') }}"
              style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px;">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search student or alumni name..."
                   style="flex:1; min-width:200px;">
            <select name="status" style="min-width:160px;">
                <option value="">All Statuses</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="button-primary" style="text-transform:none;">
                <i class="fas fa-search"></i> Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.mentorship.index') }}" class="button-outline" style="padding:10px 16px;">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </form>

        <table class="alumni-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Alumni</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Responded</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td>{{ $requests->firstItem() + $loop->index }}</td>
                    <td><strong>{{ $req->student->user->name }}</strong></td>
                    <td>{{ $req->alumni->user->name }}</td>
                    <td>{{ $req->student->course->name }}</td>
                    <td>
                        @php
                            $style = match($req->status) {
                                'accepted' => '',
                                'rejected' => 'background:#f8d7da;color:#721c24;border-color:#f5c6cb;',
                                default    => '',
                            };
                            $class = $req->isAccepted() ? 'status-resolved' : 'status-pending';
                        @endphp
                        <span class="{{ $class }}" style="{{ $style }}">
                            {{ ucfirst($req->status) }}
                        </span>
                    </td>
                    <td>{{ $req->created_at->format('d M Y') }}</td>
                    <td>{{ $req->responded_at?->format('d M Y') ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:#888; padding:30px;">
                        <i class="fas fa-info-circle"></i> No mentorship requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">{{ $requests->links() }}</div>
    </div>
</div>
@endsection