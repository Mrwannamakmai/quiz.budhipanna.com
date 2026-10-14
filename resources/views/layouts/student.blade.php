@extends('layouts.app')

@section('body')
    <nav class="navbar navbar-expand-lg navbar-dark" style="background:#16a085; margin-left:0;">
        <a class="navbar-brand font-weight-bold" href="{{ route('student.dashboard') }}">
            <i class="fas fa-dharmachakra mr-1"></i> คลังข้อสอบออนไลน์
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navStudent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navStudent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('student.dashboard') }}"><i class="fas fa-home mr-1"></i>หน้าหลัก</a>
                </li>
                <li class="nav-item {{ request()->routeIs('student.exams.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('student.exams.index') }}"><i
                            class="fas fa-file-alt mr-1"></i>ข้อสอบ</a>
                </li>
                <li class="nav-item {{ request()->routeIs('student.certificates.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('student.certificates.index') }}"><i
                            class="fas fa-certificate mr-1"></i>ใบประกาศ</a>
                </li>
                <li class="nav-item {{ request()->routeIs('student.leaderboard.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('student.leaderboard.index') }}"><i
                            class="fas fa-trophy mr-1"></i>จัดอันดับ</a>
                </li>
                @include('modules._menu', ['area' => 'student', 'style' => 'navbar'])
                <li class="nav-item {{ request()->routeIs('daily.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('daily.index') }}" style="display:flex;align-items:center;gap:5px;">
                        🔥 Daily
                        @if ((auth()->user()->daily_streak ?? 0) > 0)
                            <span
                                style="background:rgba(255,255,255,.25);border-radius:10px;padding:1px 7px;font-size:.72rem;font-weight:700;">{{ auth()->user()->daily_streak }}</span>
                        @endif
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        <i class="fas fa-user-circle mr-1"></i>{{ auth()->user()->name }}
                        @if (auth()->user()->classroom)
                            <span class="badge badge-light ml-1">{{ auth()->user()->classroom->name }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <h6 class="dropdown-header">ข้อมูลสมาชิก</h6>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit mr-1"></i> ข้อมูลส่วนตัว
                        </a>
                        @if (\App\Support\Settings\Settings::moduleEnabled('exam_registration'))
                            <a class="dropdown-item" href="{{ route('exam_announcements.index') }}">
                                <i class="fas fa-bullhorn mr-1"></i> ประกาศรอบสอบ
                            </a>
                            <a class="dropdown-item" href="{{ route('exam_registration.index') }}">
                                <i class="fas fa-id-card mr-1"></i> สมัครสอบธรรมศึกษา
                            </a>
                            <a class="dropdown-item" href="{{ route('exam_registration.list') }}">
                                <i class="fas fa-clipboard-list mr-1"></i> ประวัติการสมัครสอบของฉัน
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <small class="dropdown-item-text text-muted">{{ auth()->user()->email }}</small>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault();document.getElementById('logout-form3').submit()">
                            <i class="fas fa-sign-out-alt mr-1"></i> ออกระบบ
                        </a>
                        <form id="logout-form3" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div style="margin-left:0; padding:24px; min-height:calc(100vh - 56px); background:#f4f6f9;">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @yield('content')
    </div>

    <footer style="background:#1a1a2e;color:#bdc3c7;padding:22px 0 12px;font-size:.82rem;">
        <div class="container-fluid px-4">
            <div class="d-flex flex-wrap justify-content-center mb-2" style="gap:8px;">
                <span
                    style="background:#16a085;color:#fff;border-radius:20px;padding:3px 14px;font-weight:700;font-size:.78rem;">Quiz</span>
                <span
                    style="background:#2980b9;color:#fff;border-radius:20px;padding:3px 14px;font-weight:700;font-size:.78rem;">Kru.One</span>
                <span
                    style="background:#8e44ad;color:#fff;border-radius:20px;padding:3px 14px;font-weight:700;font-size:.78rem;">ALevel</span>
                <span
                    style="background:#c0392b;color:#fff;border-radius:20px;padding:3px 14px;font-weight:700;font-size:.78rem;">Pali</span>
                <span
                    style="background:#d35400;color:#fff;border-radius:20px;padding:3px 14px;font-weight:700;font-size:.78rem;">Tutor</span>
            </div>
            <div class="text-center mb-1" style="font-size:.8rem;">
                <i class="fas fa-hands-helping mr-1" style="color:#1abc9c;"></i>
                <strong style="color:#ecf0f1;">สนับสนุนโดย</strong> วัดสารอด | สำนักเรียนวัดสารอด เขตราษฎร์บูรณะ กรุงเทพฯ
            </div>
            <div class="text-center mb-2" style="font-size:.78rem;">
                <i class="fas fa-school mr-1" style="color:#1abc9c;"></i>
                สำนักเรียนวัดสารอด เขตราษฏร์บูรณะ กรุงเทพฯ
                &nbsp;<span style="color:#4a6278;">|</span>&nbsp; พระปริยัติธรรม
                &nbsp;<span style="color:#4a6278;">|</span>&nbsp; แผนกบาลี
                &nbsp;<span style="color:#4a6278;">|</span>&nbsp; แผนกธรรม
            </div>
            <hr style="border-color:#2c3e50;margin:6px 0 8px;">
            <div class="text-center" style="color:#636e72;font-size:.74rem;">
                &copy; {{ date('Y') }} คลังข้อสอบออนไลน์ &mdash; สงวนลิขสิทธิ์
            </div>
        </div>
    </footer>
@endsection
