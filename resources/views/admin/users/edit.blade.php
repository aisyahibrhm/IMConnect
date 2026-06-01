@extends('layouts.app')
@section('title', 'Edit User — IMConnect')

@section('content')
<div class="page-wrapper" style="max-width:600px;">

    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to profile
        </a>
    </div>

    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit User Account</h1>
        <p>Update basic account information for this user.</p>
    </div>

    {{-- User identity card --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="display:flex; align-items:center; gap:14px;">
            <div class="avatar avatar-lg">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            <div>
                <div style="font-size:16px; font-weight:700;">{{ $user->name }}</div>
                <div style="font-size:13px; color:var(--text-secondary);">
                    {{ $user->kpmim_id ?? 'No KPMIM ID' }}
                    &bull;
                    @if($user->isStudent())
                        <span class="badge badge-student">Student</span>
                    @else
                        <span class="badge badge-alumni">Alumni</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info" style="margin-bottom:20px;">
        <i class="fas fa-info-circle"></i>
        <span>
            Only basic account fields can be edited here.
            Course, KPMIM ID, and role cannot be changed after registration.
        </span>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')

        <div class="form-container">
            <div class="card-header">
                <h2><i class="fas fa-id-card"></i> Account information</h2>
            </div>
            <div class="form-body">

                <div class="form-group">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Full name in caps">
                    @error('name')
                        <div class="error-message visible">
                            <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           placeholder="user@email.com">
                    @error('email')
                        <div class="error-message visible">
                            <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone number</label>
                    <input type="text" id="phone" name="phone"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="01XXXXXXXXX">
                    @error('phone')
                        <div class="error-message visible">
                            <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Read-only info --}}
                <div style="background:var(--surface-2); border-radius:var(--radius-md);
                            padding:14px 16px; margin-top:6px;">
                    <p style="font-size:12px; font-weight:600; text-transform:uppercase;
                              letter-spacing:0.4px; color:var(--text-muted); margin-bottom:10px;">
                        Read-only fields
                    </p>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div>
                            <p style="font-size:12px; color:var(--text-muted);">KPMIM ID</p>
                            <p style="font-size:13.5px; font-weight:600; font-family:monospace;">
                                {{ $user->kpmim_id ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p style="font-size:12px; color:var(--text-muted);">Role</p>
                            <p style="font-size:13.5px; font-weight:600;">
                                {{ ucfirst($user->role) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Save changes
            </button>
        </div>
    </form>

</div>
@endsection