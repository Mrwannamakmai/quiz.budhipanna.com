@extends('layouts.app')

@section('body')
    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-crown" style="color:#f59e0b;"></i>
            <span class="brand-text">Boss Dashboard</span>
            <button class="sidebar-toggle" title="ยุบ/ขยาย"><i class="fas fa-chevron-left"></i></button>
        </div>
        <div class="nav-links">
            <div class="nav-section">ภาพรวม</div>
            <a href="{{ route('boss.dashboard') }}"
                class="nav-link {{ request()->routeIs('boss.dashboard') ? 'active' : '' }}" data-title="แดชบอร์ด">
                <i class="fas fa-tachometer-alt fa-fw"></i> <span>แดชบอร์ด</span>
            </a>
            
            <div class="nav-section">จัดการข้อมูล</div>
            <a href="{{ route('boss.registrations.index') }}"
                class="nav-link {{ request()->routeIs('boss.registrations.*') ? 'active' : '' }}" data-title="ผู้สมัครสอบ">
                <i class="fas fa-users fa-fw"></i> <span>ผู้สมัครสอบ</span>
            </a>

            <div class="nav-section">บัญชีของฉัน</div>
            <a href="{{ route('profile.edit') }}"
                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-title="แก้ไขโปรไฟล์">
                <i class="fas fa-user-edit fa-fw"></i> <span>แก้ไขโปรไฟล์</span>
            </a>
            <a href="#" class="nav-link" data-title="ออกจากระบบ"
                onclick="event.preventDefault();document.getElementById('logout-form-boss').submit()">
                <i class="fas fa-sign-out-alt fa-fw"></i> <span>ออกจากระบบ</span>
            </a>
            <form id="logout-form-boss" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <div class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center">
                <button class="topbar-toggle" title="เมนู"><i class="fas fa-bars"></i></button>
                <span class="font-weight-bold">@yield('page-title', 'Boss Dashboard')</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge mr-2" style="background:#f59e0b;color:#fff;">Boss</span>
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
