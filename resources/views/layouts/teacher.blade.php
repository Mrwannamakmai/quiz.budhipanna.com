@extends('layouts.app')

@section('body')
    <div class="sidebar" style="background:#1a3a4a;">
        <div class="brand">
            <i class="fas fa-chalkboard-teacher"></i>
            <span class="brand-text">ครู/ผู้สอน</span>
            <button class="sidebar-toggle" title="ยุบ/ขยาย"><i class="fas fa-chevron-left"></i></button>
        </div>
        <div class="nav-links">
            <div class="nav-section">จัดการข้อสอบ</div>
            <a href="{{ route('teacher.dashboard') }}"
                class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" data-title="หน้าหลัก">
                <i class="fas fa-home fa-fw"></i> <span>หน้าหลัก</span>
            </a>
            <a href="{{ route('teacher.subjects.index') }}"
                class="nav-link {{ request()->routeIs('teacher.subjects.*') ? 'active' : '' }}" data-title="หมวดวิชา">
                <i class="fas fa-layer-group fa-fw"></i> <span>หมวดวิชา</span>
            </a>
            <a href="{{ route('teacher.question_bank.index') }}"
                class="nav-link {{ request()->routeIs('teacher.question_bank.*') ? 'active' : '' }}"
                data-title="คลังข้อสอบ">
                <i class="fas fa-database fa-fw"></i> <span>คลังข้อสอบ</span>
            </a>
            <a href="{{ route('teacher.exam_sets.index') }}"
                class="nav-link {{ request()->routeIs('teacher.exam_sets.*') ? 'active' : '' }}" data-title="ชุดข้อสอบ">
                <i class="fas fa-file-alt fa-fw"></i> <span>ชุดข้อสอบ</span>
            </a>
            <div class="nav-section">ผลการสอบ</div>
            <a href="{{ route('teacher.results.index') }}"
                class="nav-link {{ request()->routeIs('teacher.results.*') ? 'active' : '' }}" data-title="ผลนักเรียน">
                <i class="fas fa-chart-bar fa-fw"></i> <span>ผลนักเรียน</span>
            </a>
            <a href="{{ route('teacher.analytics.index') }}"
                class="nav-link {{ request()->routeIs('teacher.analytics.*') ? 'active' : '' }}"
                data-title="วิเคราะห์พฤติกรรม">
                <i class="fas fa-brain fa-fw"></i> <span>วิเคราะห์พฤติกรรม</span>
            </a>
            <div class="nav-section">คลังความรู้</div>
            <a href="{{ route('teacher.articles.index') }}"
                class="nav-link {{ request()->routeIs('teacher.articles.*') ? 'active' : '' }}" data-title="Know How">
                <i class="fas fa-lightbulb fa-fw"></i> <span>Know How</span>
            </a>
            <a href="{{ route('daily.index') }}" class="nav-link {{ request()->routeIs('daily.*') ? 'active' : '' }}"
                data-title="Daily Challenge">
                <span style="width:1em;display:inline-block;text-align:center;">🔥</span> <span>Daily Challenge</span>
            </a>
            @include('modules._menu', ['area' => 'teacher'])
            @if (auth()->user()->isSuperAdmin())
                <div class="nav-section">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link" data-title="หน้าแอดมิน">
                    <i class="fas fa-cogs fa-fw"></i> <span>หน้าแอดมิน</span>
                </a>
            @endif
            <div class="nav-section">บัญชี</div>
            <a href="{{ route('profile.edit') }}"
                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-title="อัพเดทข้อมูล">
                <i class="fas fa-user-edit fa-fw"></i> <span>อัพเดทข้อมูล</span>
            </a>
            @if (\App\Support\Settings\Settings::moduleEnabled('exam_registration'))
                <a href="{{ route('exam_registration.index') }}"
                    class="nav-link {{ request()->routeIs('exam_registration.*') ? 'active' : '' }}" data-title="สมัครสอบ">
                    <i class="fas fa-id-card fa-fw"></i> <span>สมัครสอบ</span>
                </a>
            @endif
            <a href="#" class="nav-link" data-title="ออกระบบ"
                onclick="event.preventDefault();document.getElementById('logout-form2').submit()">
                <i class="fas fa-sign-out-alt fa-fw"></i> <span>ออกระบบ</span>
            </a>
            <form id="logout-form2" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center">
                <button class="topbar-toggle" title="เมนู"><i class="fas fa-bars"></i></button>
                <span class="font-weight-bold">@yield('page-title')</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-primary mr-2">ครู</span>
                <strong>{{ auth()->user()->name }}</strong>
            </div>
        </div>
        <div class="page-body">
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
    </div>
@endsection
