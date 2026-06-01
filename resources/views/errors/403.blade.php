@extends('layouts.app')
@section('title', '403 — Access Denied')

@section('content')
<div class="page-wrapper" style="max-width:500px; text-align:center; padding-top:60px;">
    <div class="card">
        <div class="card-body" style="padding:48px 32px;">
            <div style="font-size:64px; color:var(--crimson); margin-bottom:16px;">
                <i class="fas fa-lock"></i>
            </div>
            <h1 style="font-size:22px; font-weight:700; margin-bottom:8px;">Access Denied</h1>
            <p style="color:var(--text-secondary); margin-bottom:24px;">
                You don't have permission to view this page.
            </p>
            <a href="{{ url()->previous() }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Go back
            </a>
        </div>
    </div>
</div>
@endsection