@extends('layouts.app')

@section('body')
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-dharmachakra"></i>
            <span class="brand-text">สำนักเรียนวัดสารอด</span>
            <button class="sidebar-toggle" title="ยุบ/ขยาย"><i class="fas fa-chevron-left"></i></button>
        </div>
        <div class="nav-links">
            <div class="nav-section">ภาพรวม</div>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-title="แดชบอร์ด">
                <i class="fas fa-tachometer-alt fa-fw"></i> <span>แดชบอร์ด</span>
            </a>
            <div class="nav-section">จัดการระบบ</div>
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-title="ผู้ใช้งาน">
                <i class="fas fa-users fa-fw"></i> <span>ผู้ใช้งาน</span>
            </a>
            <a href="{{ route('admin.classrooms.index') }}"
                class="nav-link {{ request()->routeIs('admin.classrooms.*') ? 'active' : '' }}" data-title="ห้องเรียน">
                <i class="fas fa-school fa-fw"></i> <span>ห้องเรียน</span>
            </a>
            <a href="{{ route('admin.courses.index') }}"
                class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" data-title="วิชา / หลักสูตร">
                <i class="fas fa-book fa-fw"></i> <span>วิชา / หลักสูตร</span>
            </a>
            <a href="{{ route('admin.exam_sessions.index') }}"
                class="nav-link {{ request()->routeIs('admin.exam_sessions.*') ? 'active' : '' }}" data-title="รอบสอบธรรมศึกษา">
                <i class="fas fa-calendar-alt fa-fw"></i> <span>รอบสอบ</span>
            </a>
            <div class="nav-section">ฟีเจอร์</div>
            <a href="{{ route('admin.updates.index') }}"
                class="nav-link {{ request()->routeIs('admin.updates.*') ? 'active' : '' }}" data-title="อัพเดทระบบ">
                <i class="fas fa-bullhorn fa-fw"></i> <span>อัพเดทระบบ</span>
            </a>
            <a href="{{ route('daily.index') }}" class="nav-link {{ request()->routeIs('daily.*') ? 'active' : '' }}"
                data-title="Daily Challenge">
                <span style="width:1em;display:inline-block;text-align:center;">🔥</span> <span>Daily Challenge</span>
            </a>
            @include('modules._menu', ['area' => 'admin'])
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                data-title="ตั้งค่าระบบ">
                <i class="fas fa-sliders-h fa-fw"></i> <span>ตั้งค่าระบบ</span>
            </a>
            <div class="nav-section">รายงาน</div>
            <a href="{{ route('admin.reports.attempts') }}"
                class="nav-link {{ request()->routeIs('admin.reports.attempts') ? 'active' : '' }}" data-title="ผลการสอบ">
                <i class="fas fa-clipboard-list fa-fw"></i> <span>ผลสอบ</span>
            </a>
            <a href="{{ route('admin.reports.certificates') }}"
                class="nav-link {{ request()->routeIs('admin.reports.certificates') ? 'active' : '' }}"
                data-title="ใบประกาศ">
                <i class="fas fa-certificate fa-fw"></i> <span>ใบประกาศ</span>
            </a>
            <a href="{{ route('admin.reports.visitors') }}"
                class="nav-link {{ request()->routeIs('admin.reports.visitors') ? 'active' : '' }}"
                data-title="Visitor Logs">
                <i class="fas fa-eye fa-fw"></i> <span>Visitor Logs</span>
            </a>
            <div class="nav-section">บัญชีส่วนตัว</div>
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
            <a href="{{ route('teacher.exam_sets.index') }}" class="nav-link" data-title="หน้าครู">
                <i class="fas fa-chalkboard-teacher fa-fw"></i> <span>ครู/ผู้สอน</span>
            </a>
            <a href="#" class="nav-link" data-title="ออกระบบ"
                onclick="event.preventDefault();document.getElementById('logout-form').submit()">
                <i class="fas fa-sign-out-alt fa-fw"></i> <span>ออกระบบ</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center">
                <button class="topbar-toggle" title="เมนู"><i class="fas fa-bars"></i></button>
                <span class="font-weight-bold">@yield('page-title', 'แดชบอร์ด')</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-danger mr-2">SuperAdmin</span>
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
