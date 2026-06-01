@extends('layouts.app')
@section('title', 'Alumni Approvals — IMConnect')

@section('content')
<div class="dashboard-container">
    <div class="welcome-box">
        <h1><i class="fas fa-user-check"></i> Alumni Registration Approvals</h1>
        <p>Review and manage alumni registration requests.</p>
    </div>

    {{-- Tabs --}}
    <div style="display:flex; gap:10px; margin-bottom:25px; flex-wrap:wrap;">
        <a href="#pending"
           style="padding:8px 20px; background:#841c26; color:white; border-radius:6px;
                  text-decoration:none; font-weight:bold;">
            <i class="fas fa-clock"></i> Pending
            <span style="background:white;color:#841c26;border-radius:10px;
                         padding:1px 7px;font-size:12px;margin-left:5px;">
                {{ $pending->total() }}
            </span>
        </a>
        <a href="#approved"
           style="padding:8px 20px; background:#27ae60; color:white; border-radius:6px;
                  text-decoration:none; font-weight:bold;">
            <i class="fas fa-check-circle"></i> Approved ({{ $approved->total() }})
        </a>
        <a href="#rejected"
           style="padding:8px 20px; background:#c0392b; color:white; border-radius:6px;
                  text-decoration:none; font-weight:bold;">
            <i class="fas fa-times-circle"></i> Rejected ({{ $rejected->total() }})
        </a>
    </div>

    {{-- Pending --}}
    <div id="pending" class="form-container" style="margin-bottom:30px;">
        <h2 class="section-heading"><i class="fas fa-clock"></i> Pending Approvals</h2>
        @if($pending->isEmpty())
            <p style="color:#888;"><i class="fas fa-check-circle"></i> No pending registrations.</p>
        @else
            <table class="alumni-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>KPMIM ID</th>
                        <th>Email</th>
                        <th>College Email</th>
                        <th>Course</th>
                        <th>Grad. Year</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $alumni)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $alumni->user->name }}</strong></td>
                        <td style="font-family:monospace; font-size:13px; color:var(--text-secondary);">
                            {{ $alumni->user->kpmim_id ?? '—' }}
                        </td>
                        <td>{{ $alumni->user->email }}</td>
                        <td>{{ $alumni->college_email }}</td>
                        <td>{{ $alumni->course->name }}</td>
                        <td>{{ $alumni->graduation_year }}</td>
                        <td>{{ $alumni->created_at->format('d M Y') }}</td>
                        <td style="display:flex; gap:8px; flex-wrap:wrap;">
                            <form method="POST"
                                  action="{{ route('admin.alumni.approve', $alumni) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="button"
                                        style="padding:6px 14px; font-size:13px; text-transform:none;">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <form method="POST"
                                  action="{{ route('admin.alumni.reject', $alumni) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="button"
                                        style="padding:6px 14px; font-size:13px;
                                               text-transform:none; background:#c0392b;">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:15px;">{{ $pending->links() }}</div>
        @endif
    </div>

    {{-- Approved --}}
    <div id="approved" class="form-container" style="margin-bottom:30px;">
        <h2 class="section-heading" style="color:#27ae60; border-color:#27ae60;">
            <i class="fas fa-check-circle"></i> Approved Alumni
        </h2>
        @if($approved->isEmpty())
            <p style="color:#888;">No approved alumni yet.</p>
        @else
            <table class="alumni-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>KPMIM ID</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Company</th>
                        <th>Industry</th>
                        <th>Approved On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approved as $alumni)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $alumni->user->name }}</strong></td>
                        <td>{{ $alumni->user->kpmim_id ?? '—' }}</td>
                        <td>{{ $alumni->user->email }}</td>
                        <td>{{ $alumni->course->name }}</td>
                        <td>{{ $alumni->company ?? '—' }}</td>
                        <td>{{ $alumni->industry ?? '—' }}</td>
                        <td>{{ $alumni->approved_at?->format('d M Y') ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:15px;">{{ $approved->links() }}</div>
        @endif
    </div>

    {{-- Rejected --}}
    <div id="rejected" class="form-container">
        <h2 class="section-heading" style="color:#c0392b; border-color:#c0392b;">
            <i class="fas fa-times-circle"></i> Rejected Registrations
        </h2>
        @if($rejected->isEmpty())
            <p style="color:#888;">No rejected registrations.</p>
        @else
            <table class="alumni-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>KPMIM ID</th>
                        <th>Email</th>
                        <th>College Email</th>
                        <th>Course</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rejected as $alumni)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $alumni->user->name }}</td>
                        <td>{{ $alumni->user->kpmim_id ?? '—' }}</td>
                        <td>{{ $alumni->user->email }}</td>
                        <td>{{ $alumni->college_email }}</td>
                        <td>{{ $alumni->course->name }}</td>
                        <td>{{ $alumni->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:15px;">{{ $rejected->links() }}</div>
        @endif
    </div>

</div>
@endsection