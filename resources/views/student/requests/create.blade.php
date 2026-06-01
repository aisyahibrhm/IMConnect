@extends('layouts.app')
@section('title', 'Request Mentorship — IMConnect')

@section('content')
<div class="page-wrapper" style="max-width:620px;">

    <div style="margin-bottom:20px;">
        <a href="{{ route('student.alumni.show', $alumni) }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to profile
        </a>
    </div>

    <div class="page-header">
        <h1><i class="fas fa-paper-plane"></i> Request Mentorship</h1>
        <p>Send a formal mentorship request to this alumni.</p>
    </div>

    {{-- Alumni mini-card --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="display:flex; align-items:center; gap:14px;">
            <div class="avatar avatar-lg">{{ strtoupper(substr($alumni->user->name, 0, 2)) }}</div>
            <div>
                <div style="font-weight:700; font-size:16px;">{{ $alumni->user->name }}</div>
                <div style="font-size:13px; color:var(--text-secondary);">
                    {{ $alumni->job_position ?? 'Alumni' }}
                    @if($alumni->company) &bull; {{ $alumni->company }} @endif
                </div>
                <div style="margin-top:6px; display:flex; gap:6px; flex-wrap:wrap;">
                    @if($alumni->industry)
                        <span class="badge badge-info">{{ $alumni->industry }}</span>
                    @endif
                    <span class="badge" style="background:var(--crimson-subtle);
                                               color:var(--crimson);
                                               border:1px solid var(--crimson-muted);">
                        {{ $alumni->course->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Request form --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-edit"></i> Your message</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-info" style="margin-bottom:20px;">
                <i class="fas fa-info-circle"></i>
                <span>
                    A personal message helps alumni understand your goals.
                    It's optional but strongly recommended.
                </span>
            </div>

            <form method="POST"
                  action="{{ route('student.requests.store', $alumni) }}"
                  id="requestForm"
                  onsubmit="return confirmSubmit()">
                @csrf

                <div class="form-group">
                    <label for="message">
                        Message to {{ Str::words($alumni->user->name, 1, '') }}
                        <span style="font-weight:400; color:var(--text-muted);">(optional)</span>
                    </label>
                    <textarea id="message" name="message"
                              rows="5"
                              maxlength="1000"
                              placeholder="Introduce yourself — share your course, career goals, and what kind of guidance you're hoping to receive..."
                              oninput="updateCount(this)">{{ old('message') }}</textarea>
                    <div style="display:flex; justify-content:space-between; margin-top:5px;">
                        <span style="font-size:12px; color:var(--text-muted);">Max 1000 characters</span>
                        <span id="charCount" style="font-size:12px; color:var(--text-muted);">0 / 1000</span>
                    </div>
                    @error('message')
                        <div class="error-message visible">
                            <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div style="display:flex; gap:10px; margin-top:8px; flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i> Send Request
                    </button>
                    <a href="{{ route('student.alumni.show', $alumni) }}"
                       class="btn btn-ghost btn-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function updateCount(el) {
    document.getElementById('charCount').textContent = el.value.length + ' / 1000';
}

function confirmSubmit() {
    return confirm('Send this mentorship request to {{ $alumni->user->name }}? You can track the status in My Requests.');
}
</script>
@endsection