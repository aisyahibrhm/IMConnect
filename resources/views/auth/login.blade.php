@extends('layouts.app')
@section('title', 'Login — IMConnect')

@section('content')
<div class="auth-page">
<div class="auth-card">

    <div class="auth-card-header">
        <img src="{{ asset('images/kpmmara.png') }}" alt="KPMIM Logo">
        <h1>Welcome to IMConnect</h1>
        <p>KPMIM Alumni Mentorship System</p>
    </div>

    <div class="auth-card-body">

        @if($errors->any() || session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() ?: session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm" onsubmit="return validateLogin()">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="your@email.com" autocomplete="email">
                <div id="emailError" class="error-message">
                    <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                    <span></span>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Enter your password" autocomplete="current-password">
                <div id="passwordError" class="error-message">
                    <i class="fas fa-exclamation-circle" style="font-size:11px;"></i>
                    <span></span>
                </div>
            </div>

            <div class="remember-me" style="margin-bottom:20px;">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" style="margin:0; font-weight:400; cursor:pointer;">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="divider"></div>

        <p style="text-align:center; font-size:13.5px; color:var(--text-secondary);">
            Don't have an account?
            <a href="{{ route('register') }}" style="color:var(--crimson); font-weight:600;">
                Register here
            </a>
        </p>
    </div>
</div>
</div>

<script>
function validateLogin() {
    let valid = true;
    document.querySelectorAll('.error-message').forEach(el => el.classList.remove('visible'));
    const email = document.getElementById('email').value.trim();
    const pass  = document.getElementById('password').value;
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showErr('emailError', 'Enter a valid email address.'); valid = false;
    }
    if (pass.length < 6) {
        showErr('passwordError', 'Password must be at least 6 characters.'); valid = false;
    }
    return valid;
}
function showErr(id, msg) {
    const el = document.getElementById(id);
    el.querySelector('span').textContent = msg;
    el.classList.add('visible');
}
</script>
@endsection