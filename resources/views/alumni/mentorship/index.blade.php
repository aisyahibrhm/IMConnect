@extends('layouts.app')
@section('title', 'Mentorship Requests — IMConnect')

@section('content')
<div class="dashboard-container">

    <div class="page-header">
        <h1><i class="fas fa-envelope-open-text"></i> Mentorship Requests</h1>
        <p>Review and respond to mentorship requests from students.</p>
    </div>

    {{-- Pending --}}
    <h2 class="section-heading"><i class="fas fa-hourglass-half"></i> Pending requests</h2>

    @if($pending->isEmpty())
        <div class="card" style="margin-bottom:28px;">
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No pending requests</h3>
                <p>New mentorship requests from students will appear here.</p>
            </div>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:14px; margin-bottom:32px;">
        @foreach($pending as $req)
            <div class="request-card">
                <div class="request-card-header">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="avatar">{{ strtoupper(substr($req->student->user->name, 0, 2)) }}</div>
                        <div>
                            <div style="font-weight:700; font-size:15px;">
                                {{ $req->student->user->name }}
                            </div>
                            <div style="font-size:12.5px; color:var(--text-muted);">
                                <i class="fas fa-book" style="font-size:11px;"></i>
                                {{ $req->student->course->name }}
                                &bull;
                                <i class="fas fa-clock" style="font-size:11px;"></i>
                                {{ $req->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <span class="badge badge-pending">Pending</span>
                </div>

                @if($req->message)
                    <div class="request-message">
                        &ldquo;{{ $req->message }}&rdquo;
                    </div>
                @else
                    <div class="request-message" style="color:var(--text-muted); font-style:normal;">
                        <i class="fas fa-info-circle"></i> No message provided.
                    </div>
                @endif

                <div style="display:flex; gap:10px; margin-top:14px;">
                    <form method="POST" action="{{ route('alumni.requests.accept', $req) }}"
                          onsubmit="return confirm('Accept this mentorship request from {{ $req->student->user->name }}?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Accept
                        </button>
                    </form>
                    <button onclick="toggleReject({{ $req->id }})" class="btn btn-ghost" style="color:#c81e1e; border-color:#f0bbbb;">
                        <i class="fas fa-times"></i> Decline
                    </button>
                    <a href="{{ route('alumni.requests.show', $req) }}" class="btn btn-ghost" style="margin-left:auto;">
                        <i class="fas fa-eye"></i> View details
                    </a>
                </div>

                {{-- Reject form (toggled) --}}
                <div id="reject-{{ $req->id }}" style="display:none; margin-top:12px;">
                    <form method="POST" action="{{ route('alumni.requests.reject', $req) }}"
                          onsubmit="return confirm('Decline this request?')">
                        @csrf @method('PATCH')
                        <div class="form-group">
                            <label for="reason-{{ $req->id }}">Reason for declining (optional)</label>
                            <textarea id="reason-{{ $req->id }}" name="rejection_reason"
                                      rows="2"
                                      placeholder="Let the student know why you're unable to take this on..."></textarea>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times-circle"></i> Confirm Decline
                            </button>
                            <button type="button" onclick="toggleReject({{ $req->id }})" class="btn btn-ghost btn-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
        </div>
    @endif

    {{-- Responded --}}
    <h2 class="section-heading"><i class="fas fa-history"></i> Previous responses</h2>

    @if($responded->isEmpty())
        <div class="card">
            <div class="empty-state" style="padding:40px;">
                <i class="fas fa-history"></i>
                <h3>No responses yet</h3>
                <p>Requests you've accepted or declined will appear here.</p>
            </div>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Submitted</th>
                        <th>Responded</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($responded as $req)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div class="avatar" style="width:32px; height:32px; font-size:12px;">
                                    {{ strtoupper(substr($req->student->user->name, 0, 2)) }}
                                </div>
                                <span style="font-weight:600;">{{ $req->student->user->name }}</span>
                            </div>
                        </td>
                        <td style="color:var(--text-secondary); font-size:13px;">
                            {{ $req->student->course->name }}
                        </td>
                        <td style="font-size:13px; color:var(--text-muted);">
                            {{ $req->created_at->format('d M Y') }}
                        </td>
                        <td style="font-size:13px; color:var(--text-muted);">
                            {{ $req->responded_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td>
                            @if($req->isAccepted())
                                <span class="badge badge-accepted"><i class="fas fa-check"></i> Accepted</span>
                            @else
                                <span class="badge badge-rejected"><i class="fas fa-times"></i> Declined</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('alumni.requests.show', $req) }}" class="btn btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">{{ $responded->links() }}</div>
    @endif

</div>

<script>
function toggleReject(id) {
    const el = document.getElementById('reject-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection