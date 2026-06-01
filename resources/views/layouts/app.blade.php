<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IMConnect — KPMIM')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    @if(session('success'))
        <div class="popup success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="popup error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <header>
        <img src="{{ asset('images/kpmmara.png') }}" alt="KPMIM">
        <div class="header-divider"></div>
        <h2>IMConnect</h2>

        <nav>
            @guest
                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> <span>Login</span></a>
                <a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> <span>Register</span></a>
            @else
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.home') }}"><i class="fas fa-th-large"></i> <span>Dashboard</span></a>
                    <a href="{{ route('admin.alumni.index') }}"><i class="fas fa-user-check"></i> <span>Approvals</span></a>
                    <a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> <span>Users</span></a>
                    <a href="{{ route('admin.mentorship.index') }}"><i class="fas fa-handshake"></i> <span>Mentorship</span></a>
                @elseif(Auth::user()->role === 'alumni')
                    <a href="{{ route('alumni.home') }}"><i class="fas fa-th-large"></i> <span>Dashboard</span></a>
                    <a href="{{ route('alumni.profile') }}"><i class="fas fa-id-card"></i> <span>My Profile</span></a>
                    <a href="{{ route('alumni.requests.index') }}"><i class="fas fa-envelope-open-text"></i> <span>Requests</span></a>
                @elseif(Auth::user()->role === 'student')
                    <a href="{{ route('student.home') }}"><i class="fas fa-th-large"></i> <span>Dashboard</span></a>
                    <a href="{{ route('student.profile.edit') }}"><i class="fas fa-user-edit"></i> <span>My Profile</span></a>
                    <a href="{{ route('student.alumni.index') }}"><i class="fas fa-search"></i> <span>Find Alumni</span></a>
                    <a href="{{ route('student.requests.index') }}"><i class="fas fa-paper-plane"></i> <span>My Requests</span></a>
                @endif

                <a href="#" class="nav-logout"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ Str::words(Auth::user()->name, 1, '') }}</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            @endguest
        </nav>
    </header>

    <main>@yield('content')</main>

    <footer>
        <div class="footer-content">
            <div class="footer-left">
                <p><strong>Kolej Profesional MARA Indera Mahkota</strong></p>
                <p>Jalan Sungai Lembing, 25200 Kuantan, Pahang</p>
                <p>Tel: 09-573 8333 &nbsp;&bull;&nbsp; Fax: 09-573 8334</p>
                <p>info@kpmim.edu.my</p>
            </div>
            <div class="footer-right">
                <p><strong>Follow Us</strong></p>
                <div class="social-links">
                    <a href="https://www.facebook.com/mediakpmim" target="_blank" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/kpminderamahkota" target="_blank" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} IMConnect &mdash; KPMIM Alumni Management System
        </div>
    </footer>

</body>
</html>