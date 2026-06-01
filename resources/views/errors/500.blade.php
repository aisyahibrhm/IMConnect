@extends('layouts.app')
@section('title', '500 — Server Error')

@section('content')
<div class="page-wrapper" style="max-width:500px; text-align:center; padding-top:60px;">
    <div class="card">
        <div class="card-body" style="padding:48px 32px;">
            <div style="font-size:64px; color:var(--crimson); margin-bottom:16px;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 style="font-size:22px; font-weight:700; margin-bottom:8px;">Something went wrong</h1>
            <p style="color:var(--text-secondary); margin-bottom:24px;">
                An unexpected error occurred. Please try again or contact the administrator.
            </p>
            <a href="{{ url('/') }}" class="btn btn-outline">
                <i class="fas fa-home"></i> Return home
            </a>
        </div>
    </div>
</div>
@endsection