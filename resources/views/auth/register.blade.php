@extends('layouts.app')
@section('title', 'Register — IMConnect')

@section('content')
<div class="auth-page">
<div class="auth-card" style="max-width:560px;">

    <div class="auth-card-header">
        <img src="{{ asset('images/kpmmara.png') }}" alt="KPMIM Logo">
        <h1>Create an Account</h1>
        <p>Join the IMConnect alumni network</p>
    </div>

    <div class="auth-card-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Role toggle --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:22px;">
            <label id="tab-student" onclick="setType('student')"
                   style="display:flex; align-items:center; justify-content:center; gap:8px;
                          padding:11px; border-radius:var(--radius-md); cursor:pointer;
                          border:2px solid var(--crimson); background:var(--crimson);
                          color:#fff; font-weight:600; font-size:13.5px; transition:all 0.18s;">
                <i class="fas fa-user-graduate"></i> Student
            </label>
            <label id="tab-alumni" onclick="setType('alumni')"
                   style="display:flex; align-items:center; justify-content:center; gap:8px;
                          padding:11px; border-radius:var(--radius-md); cursor:pointer;
                          border:2px solid var(--border-strong); background:transparent;
                          color:var(--text-secondary); font-weight:600; font-size:13.5px; transition:all 0.18s;">
                <i class="fas fa-briefcase"></i> Alumni
            </label>
        </div>
        <input type="hidden" name="type" id="typeField" value="student">

        <div id="alumniNotice" class="alert alert-warning" style="display:none;">
            <i class="fas fa-info-circle"></i>
            <span>Alumni accounts require <strong>administrator approval</strong> before you can log in.</span>
        </div>

        <form method="POST" action="{{ route('register') }}" id="registerForm" onsubmit="return validateRegister()">
            @csrf
            <input type="hidden" name="type" id="typeInput" value="{{ old('type', 'student') }}">

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}" placeholder="ALI BIN ABU">
                    <div id="nameError" class="error-message"><i class="fas fa-exclamation-circle" style="font-size:11px;"></i><span></span></div>
                </div>
                <div class="form-group">
                    <label for="kpmim_id">KPMIM ID (Matric No.)</label>
                    <input type="text" id="kpmim_id" name="kpmim_id"
                           value="{{ old('kpmim_id') }}" placeholder="e.g. ICS24-02-027">
                    <div id="kpmimError" class="error-message"><i class="fas fa-exclamation-circle" style="font-size:11px;"></i><span></span></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Personal email</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}" placeholder="you@gmail.com">
                    <div id="emailError" class="error-message"><i class="fas fa-exclamation-circle" style="font-size:11px;"></i><span></span></div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone number</label>
                    <input type="text" id="phone" name="phone"
                           value="{{ old('phone') }}" placeholder="01XXXXXXXXX">
                    <div id="phoneError" class="error-message"><i class="fas fa-exclamation-circle" style="font-size:11px;"></i><span></span></div>
                </div>
            </div>

            <div class="form-group">
                <label for="college_email">College email</label>
                <input type="email" id="college_email" name="college_email"
                       value="{{ old('college_email') }}"
                       placeholder="icsxxxxx@inderamahkota.kpm.edu.my">
                <div id="collegeEmailError" class="error-message"><i class="fas fa-exclamation-circle" style="font-size:11px;"></i><span></span></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="course_id">Course</label>
                    <select id="course_id" name="course_id">
                        <option value="" disabled selected>Select course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="graduation_year">Graduation year</label>
                    <input type="number" id="graduation_year" name="graduation_year"
                           value="{{ old('graduation_year') }}"
                           min="2000" max="{{ date('Y') + 5 }}" placeholder="{{ date('Y') }}">
                    <div id="yearError" class="error-message"><i class="fas fa-exclamation-circle" style="font-size:11px;"></i><span></span></div>
                </div>
            </div>

            {{-- Alumni-only fields --}}
            <div id="alumniFields" style="display:none;">
                <div style="height:1px; background:var(--border); margin:4px 0 18px;"></div>
                <p style="font-size:12px; font-weight:600; text-transform:uppercase;
                          letter-spacing:0.5px; color:var(--text-muted); margin-bottom:14px;">
                    Professional details (optional now, complete later)
                </p>
                <div class="form-row">
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company"
                               value="{{ old('company') }}" placeholder="e.g. Petronas">
                    </div>
                    <div class="form-group">
                        <label for="job_position">Job position</label>
                        <input type="text" id="job_position" name="job_position"
                               value="{{ old('job_position') }}" placeholder="e.g. Software Engineer">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="industry">Industry</label>
                        <input type="text" id="industry" name="industry"
                               value="{{ old('industry') }}" placeholder="e.g. Technology">
                    </div>
                    <div class="form-group">
                        <label for="years_experience">Years of experience</label>
                        <input type="number" id="years_experience" name="years_experience"
                               value="{{ old('years_experience') }}" min="0" max="50" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="form-row" style="margin-top:4px;">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 6 characters">
                    <div id="passwordError" class="error-message"><i class="fas fa-exclamation-circle" style="font-size:11px;"></i><span></span></div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password">
                    <div id="confirmError" class="error-message"><i class="fas fa-exclamation-circle" style="font-size:11px;"></i><span></span></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width:100%; margin-top:6px;">
                <i class="fas fa-user-check"></i>
                <span id="submitLabel">Create Student Account</span>
            </button>
        </form>

        <div class="divider"></div>
        <p style="text-align:center; font-size:13.5px; color:var(--text-secondary);">
            Already registered?
            <a href="{{ route('login') }}" style="color:var(--crimson); font-weight:600;">Sign in</a>
        </p>
    </div>
</div>
</div>

<script>
let currentType = '{{ old("type", "student") }}';

function setType(type) {
    currentType = type;
    document.getElementById('typeInput').value = type;

    const ts = document.getElementById('tab-student');
    const ta = document.getElementById('tab-alumni');
    const af = document.getElementById('alumniFields');
    const an = document.getElementById('alumniNotice');
    const sl = document.getElementById('submitLabel');

    const activeStyle  = 'border:2px solid var(--crimson); background:var(--crimson); color:#fff;';
    const inactiveStyle= 'border:2px solid var(--border-strong); background:transparent; color:var(--text-secondary);';

    if (type === 'alumni') {
        ta.style.cssText += activeStyle;
        ts.style.cssText += inactiveStyle;
        af.style.display = 'block';
        an.style.display = 'flex';
        sl.textContent   = 'Submit Alumni Registration';
    } else {
        ts.style.cssText += activeStyle;
        ta.style.cssText += inactiveStyle;
        af.style.display = 'none';
        an.style.display = 'none';
        sl.textContent   = 'Create Student Account';
    }
}

document.addEventListener('DOMContentLoaded', () => setType(currentType));

function validateRegister() {
    let valid = true;
    document.querySelectorAll('.error-message').forEach(e => e.classList.remove('visible'));

    const name    = document.getElementById('name').value.trim();
    const kpmimId = document.getElementById('kpmim_id').value.trim();
    const email   = document.getElementById('email').value.trim();
    const cEmail  = document.getElementById('college_email').value.trim();
    const phone   = document.getElementById('phone').value.trim();
    const year    = parseInt(document.getElementById('graduation_year').value);
    const pass    = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;

    const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRx = /^01[0-9]{8,9}$/;
    const kpmimRx = /^[A-Z]{2,6}\d{2}-\d{2}-\d{3}$/;

    if (name.length < 3)  { showErr('nameError', 'Name must be at least 3 characters.'); valid = false; }
    if (!kpmimRx.test(kpmimId)) { showErr('kpmimError', 'Enter a valid matric number (e.g. ICS24-02-027).'); valid = false; }
    if (!emailRx.test(email)) { showErr('emailError', 'Enter a valid email address.'); valid = false; }
    if (!emailRx.test(cEmail) || !cEmail.endsWith('@inderamahkota.kpm.edu.my')) {
        showErr('collegeEmailError', 'Must end with @inderamahkota.kpm.edu.my.'); valid = false;
    }
    if (!phoneRx.test(phone)) { showErr('phoneError', 'Must start with 01, 10–11 digits.'); valid = false; }
    if (!year || year < 2000 || year > new Date().getFullYear() + 5 ) {
        showErr('yearError', 'Enter a valid graduation year (up to ' + (new Date().getFullYear() + 5) + ').'); valid = false;
    }
    if (pass.length < 6) { showErr('passwordError', 'Password must be at least 6 characters.'); valid = false; }
    if (pass !== confirm) { showErr('confirmError', 'Passwords do not match.'); valid = false; }
    return valid;
}

function showErr(id, msg) {
    const el = document.getElementById(id);
    el.querySelector('span').textContent = msg;
    el.classList.add('visible');
}
</script>
@endsection