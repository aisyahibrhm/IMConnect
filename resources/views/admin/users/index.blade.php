@extends('layouts.app')
@section('title', 'Manage Users — IMConnect')

@section('content')
<div class="dashboard-container">

    <div class="page-header">
        <h1><i class="fas fa-users"></i> User Management</h1>
        <p>View, search, edit, and manage all student and alumni accounts.</p>
    </div>

    {{-- Count cards --}}
    <div class="stats-grid" style="margin-bottom:24px;">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $counts['total'] }}</div>
                <div class="stat-label">Total users</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $counts['students'] }}</div>
                <div class="stat-label">Students</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon crimson"><i class="fas fa-user-tie"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $counts['alumni'] }}</div>
                <div class="stat-label">Alumni</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-user-slash"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $counts['inactive'] }}</div>
                <div class="stat-label">Deactivated</div>
            </div>
        </div>
    </div>

    <div class="form-container">
        {{-- Search & Filter --}}
        <div class="card-header" style="border-bottom:1px solid var(--border); margin:-1px -1px 20px; padding:16px 24px;">
            <form method="GET" action="{{ route('admin.users.index') }}"
                  style="display:flex; gap:10px; flex-wrap:wrap; width:100%;" id="filterForm">

                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search name, email or KPMIM ID..."
                       style="flex:1; min-width:200px;">

                <select name="role" onchange="document.getElementById('filterForm').submit()"
                        style="min-width:150px;">
                    <option value="">All roles</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Students</option>
                    <option value="alumni"  {{ request('role') === 'alumni'  ? 'selected' : '' }}>Alumni</option>
                </select>

                <select name="status" onchange="document.getElementById('filterForm').submit()"
                        style="min-width:150px;">
                    <option value="">All statuses</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>

                @if(request('search') || request('role') || request('status'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>KPMIM ID</th>
                        <th>Role</th>
                        <th>Course</th>
                        <th>Account status</th>
                        <th>Joined</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="color:var(--text-muted); font-size:12px;">
                            {{ $users->firstItem() + $loop->index }}
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div class="avatar" style="width:34px; height:34px; font-size:12px;
                                     {{ !$user->is_active ? 'opacity:0.45;' : '' }}">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; font-size:13.5px;
                                         {{ !$user->is_active ? 'color:var(--text-muted);' : '' }}">
                                        {{ $user->name }}
                                    </div>
                                    <div style="font-size:12px; color:var(--text-muted);">
                                        {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:13px; font-family:monospace; color:var(--text-secondary);">
                            {{ $user->kpmim_id ?? '—' }}
                        </td>
                        <td>
                            @if($user->isStudent())
                                <span class="badge badge-student">
                                    <i class="fas fa-user-graduate" style="font-size:10px;"></i>
                                    Student
                                </span>
                            @else
                                <span class="badge badge-alumni">
                                    <i class="fas fa-user-tie" style="font-size:10px;"></i>
                                    Alumni
                                </span>
                            @endif
                        </td>
                        <td style="font-size:13px; color:var(--text-secondary);">
                            @if($user->isStudent())
                                {{ $user->studentProfile?->course?->name ?? '—' }}
                            @else
                                {{ $user->alumniProfile?->course?->name ?? '—' }}
                            @endif
                        </td>
                        <td>
                            @if(!$user->is_active)
                                <span class="badge badge-danger">
                                    <i class="fas fa-ban" style="font-size:10px;"></i>
                                    Deactivated
                                </span>
                            @elseif($user->isAlumni())
                                @php $alumniStatus = $user->alumniProfile?->status ?? 'unknown'; @endphp
                                @if($alumniStatus === 'approved')
                                    <span class="badge badge-accepted">
                                        <i class="fas fa-check" style="font-size:10px;"></i>
                                        Approved
                                    </span>
                                @elseif($alumniStatus === 'pending')
                                    <span class="badge badge-pending">
                                        <i class="fas fa-hourglass-half" style="font-size:10px;"></i>
                                        Pending
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times" style="font-size:10px;"></i>
                                        Rejected
                                    </span>
                                @endif
                            @else
                                <span class="badge badge-active">
                                    <i class="fas fa-circle" style="font-size:8px;"></i>
                                    Active
                                </span>
                            @endif
                        </td>
                        <td style="font-size:12.5px; color:var(--text-muted);">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; justify-content:flex-end;">
                                {{-- View --}}
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="btn btn-icon" title="View profile">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn btn-icon" title="Edit account">
                                    <i class="fas fa-pen"></i>
                                </a>
                                {{-- Toggle active --}}
                                <form method="POST"
                                      action="{{ route('admin.users.toggle-active', $user) }}"
                                      onsubmit="return confirm('{{ $user->is_active
                                          ? 'Deactivate ' . $user->name . '\'s account? They will be immediately logged out.'
                                          : 'Reactivate ' . $user->name . '\'s account?' }}')">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-icon {{ $user->is_active ? 'danger' : 'success' }}"
                                            title="{{ $user->is_active ? 'Deactivate' : 'Reactivate' }}">
                                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state" style="padding:40px;">
                                <i class="fas fa-users-slash"></i>
                                <h3>No users found</h3>
                                <p>Try adjusting your search or filter criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">{{ $users->links() }}</div>
    </div>
</div>
@endsection