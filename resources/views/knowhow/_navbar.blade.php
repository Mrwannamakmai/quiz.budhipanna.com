<style>
    :root {
        --primary: #16a085;
        --primary-dark: #0e7060;
        --primary-light: #1abc9c;
        --gold: #f39c12;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Sarabun', sans-serif;
        background: #f0f4f8;
        color: #333;
        margin: 0;
        font-size: 16px;
    }

    .navbar-main {
        background: #fff;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
        position: sticky;
        top: 0;
        z-index: 999;
        padding: 12px 0;
    }

    .navbar-main .nav-logo {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .navbar-main .nav-logo .logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
    }

    .btn-login {
        background: var(--primary);
        color: #fff;
        border-radius: 25px;
        padding: 8px 24px;
        font-weight: 600;
        border: none;
        text-decoration: none;
        display: inline-block;
        transition: background .2s;
    }

    .btn-login:hover {
        background: var(--primary-dark);
        color: #fff;
        text-decoration: none;
    }

    .btn-register {
        color: var(--primary);
        border: 2px solid var(--primary);
        border-radius: 25px;
        padding: 6px 22px;
        font-weight: 600;
        background: transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .2s;
    }

    .btn-register:hover {
        background: var(--primary);
        color: #fff;
        text-decoration: none;
    }

    .btn-register.active-nav {
        background: var(--primary);
        color: #fff;
    }
</style>

<nav class="navbar-main">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('landing') }}" class="nav-logo">
            <div class="logo-icon"><i class="fas fa-book-open"></i></div>
            <span>ศูนย์ข้อสอบออนไลน์</span>
        </a>
        <div class="d-flex align-items-center" style="gap:10px;">
            <a href="{{ route('knowhow.index') }}" class="btn-register active-nav d-none d-md-inline-flex">
                <i class="fas fa-lightbulb" style="font-size:.85rem;"></i>คลังความรู้
            </a>
            <a href="{{ route('pretest.index') }}" class="btn-register d-none d-md-inline-flex">
                <i class="fas fa-pencil-alt" style="font-size:.85rem;"></i>Pre-Test ฟรี
            </a>
            @auth
                <a href="{{ route('home') }}" class="btn-login">
                    <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fas fa-sign-in-alt mr-1"></i>เข้าสู่ระบบ
                </a>
            @endauth
        </div>
    </div>
</nav>
