@extends('layouts.app')
@section('title', 'View User — IMConnect')

@section('content')
<div class="page-wrapper" style="max-width:780px;">

    <div style="margin-bottom:20px; display:flex; gap:10px; align-items:center;">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to users
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-sm">
            <i class="fas fa-pen"></i> Edit account
        </a>
    </div>

    {{-- Hero card --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="display:flex; align-items:center; gap:18px; flex-wrap:wrap;">
            <div class="avatar avatar-lg" style="{{ !$user->is_active ? 'opacity:0.5;' : '' }}">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div style="flex:1;">
                <div style="font-size:20px; font-weight:800; letter-spacing:-0.3px;">
                    {{ $user->name }}
                    @if(!$user->is_active)
                        <span class="badge badge-danger" style="font-size:12px; vertical-align:middle; margin-left:8px;">
                            <i class="fas fa-ban"></i> Deactivated
                        </span>
                    @endif
                </div>
                <div style="font-size:13.5px; color:var(--text-secondary); margin-top:4px;">
                    {{ $user->email }}
                    &bull;
                    @if($user->isStudent())
                        <span class="badge badge-student">Student</span>
                    @else
                        <span class="badge badge-alumni">Alumni</span>
                    @endif
                </div>
                <div style="font-size:12.5px; color:var(--text-muted); margin-top:6px; font-family:monospace;">
                    KPMIM ID: {{ $user->kpmim_id ?? 'Not set' }}
                </div>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <form method="POST"
                      action="{{ route('admin.users.toggle-active', $user) }}"
                      onsubmit="return confirm('{{ $user->is_active
                          ? 'Deactivate ' . $user->name . '\'s account? They will be immediately logged out.'
                          : 'Reactivate ' . $user->name . '\'s account?' }}')">
                    @csrf @method('PATCH')
                    @if($user->is_active)
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-ban"></i> Deactivate
                        </button>
                    @else
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-check-circle"></i> Reactivate
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

        {{-- Account details --}}
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-id-card"></i> Account details</h2>
            </div>
            <div class="card-body">
                <div class="profile-detail">
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-phone"></i> Phone</span>
                        <span class="detail-value">{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-calendar"></i> Joined</span>
                        <span class="detail-value">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-circle"></i> Status</span>
                        <span class="detail-value">
                            @if($user->is_active)
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge badge-danger">Deactivated</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile details --}}
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user-circle"></i> Profile details</h2>
            </div>
            <div class="card-body">
                @if($user->isStudent() && $user->studentProfile)
                    @php $p = $user->studentProfile; @endphp
                    <div class="profile-detail">
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-book"></i> Course</span>
                            <span class="detail-value">{{ $p->course->name }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-graduation-cap"></i> Grad. Year</span>
                            <span class="detail-value">{{ $p->graduation_year }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-bullseye"></i> Career interest</span>
                            <span class="detail-value">{{ $p->career_interest ?? '—' }}</span>
                        </div>
                    </div>
                @elseif($user->isAlumni() && $user->alumniProfile)
                    @php $p = $user->alumniProfile; @endphp
                    <div class="profile-detail">
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-book"></i> Course</span>
                            <span class="detail-value">{{ $p->course->name }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-building"></i> Company</span>
                            <span class="detail-value">{{ $p->company ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-industry"></i> Industry</span>
                            <span class="detail-value">{{ $p->industry ?? '—' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label"><i class="fas fa-check-circle"></i> Approval</span>
                            <span class="detail-value">
                                @if($p->status === 'approved')
                                    <span class="badge badge-accepted">Approved</span>
                                @elseif($p->status === 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @else
                                    <span class="badge badge-rejected">Rejected</span>
                                @endif
                            </span>
                        </div>
                    </div>
                @else
                    <p style="color:var(--text-muted); font-size:13.5px;">No profile data found.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Mentorship activity --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h2><i class="fas fa-handshake"></i> Mentorship activity</h2>
            <span class="badge badge-info">{{ $requests->count() }} total</span>
        </div>
        @if($requests->isEmpty())
            <div class="card-body">
                <div class="empty-state" style="padding:28px 0;">
                    <i class="fas fa-handshake"></i>
                    <h3>No mentorship activity</h3>
                    <p>This user has no mentorship requests.</p>
                </div>
            </div>
        @else
            <div class="table-wrapper" style="border:none; box-shadow:none; border-radius:0;">
                <table>
                    <thead>
                        <tr>
                            <th>{{ $user->isStudent() ? 'Alumni' : 'Student' }}</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Responded</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                        <tr>
                            <td style="font-weight:600;">
                                @if($user->isStudent())
                                    {{ $req->alumni->user->name }}
                                @else
                                    {{ $req->student->user->name }}
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'accepted')
                                    <span class="badge badge-accepted"><i class="fas fa-check"></i> Accepted</span>
                                @elseif($req->status === 'pending')
                                    <span class="badge badge-pending"><i class="fas fa-hourglass-half"></i> Pending</span>
                                @else
                                    <span class="badge badge-rejected"><i class="fas fa-times"></i> Declined</span>
                                @endif
                            </td>
                            <td style="font-size:13px; color:var(--text-muted);">
                                {{ $req->created_at->format('d M Y') }}
                            </td>
                            <td style="font-size:13px; color:var(--text-muted);">
                                {{ $req->responded_at?->format('d M Y') ?? '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Password reset --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-key"></i> Reset password</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-warning" style="margin-bottom:20px;">
                <i class="fas fa-exclamation-triangle"></i>
                <span>
                    Only reset a password if the user has requested it or cannot access their account.
                </span>
            </div>
            <form method="POST"
                  action="{{ route('admin.users.reset-password', $user) }}"
                  onsubmit="return confirm('Reset the password for {{ $user->name }}?')"
                  style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
                @csrf @method('PATCH')
                <div class="form-group" style="flex:1; min-width:200px; margin:0;">
                    <label for="new_password">New password</label>
                    <input type="password" id="new_password" name="new_password"
                           placeholder="Min. 6 characters">
                </div>
                <div class="form-group" style="flex:1; min-width:200px; margin:0;">
                    <label for="new_password_confirmation">Confirm new password</label>
                    <input type="password" id="new_password_confirmation"
                           name="new_password_confirmation"
                           placeholder="Re-enter new password">
                </div>
                <button type="submit" class="btn btn-danger" style="align-self:flex-end;">
                    <i class="fas fa-key"></i> Reset password
                </button>
            </form>
            @error('new_password')
                <div class="alert alert-danger" style="margin-top:12px;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>
    </div>

</div>
@endsection