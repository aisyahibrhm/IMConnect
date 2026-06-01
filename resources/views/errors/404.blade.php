@extends('layouts.app')
@section('title', '404 — Not Found')

@section('content')
<div class="page-wrapper" style="max-width:500px; text-align:center; padding-top:60px;">
    <div class="card">
        <div class="card-body" style="padding:48px 32px;">
            <div style="font-size:64px; color:var(--text-muted); margin-bottom:16px;">
                <i class="fas fa-search"></i>
            </div>
            <h1 style="font-size:22px; font-weight:700; margin-bottom:8px;">Page Not Found</h1>
            <p style="color:var(--text-secondary); margin-bottom:24px;">
                The page you're looking for doesn't exist or has been moved.
            </p>
            <a href="{{ auth()->check() ? url('/') : route('login') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Go to dashboard
            </a>
        </div>
    </div>
</div>
@endsection