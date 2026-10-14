<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ธรรมศึกษา|สำนักเรียนวัดสารอด</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary: #16a085;
            --primary-dark: #0e7060;
            --primary-light: #1abc9c;
            --gold: #f39c12;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Sarabun', sans-serif; background: #fff; color: #333; margin: 0; font-size: 16px; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Sarabun', sans-serif; }
        p, span, a, label, input, textarea, select, button { font-family: 'Sarabun', sans-serif; }

        /* ── Navbar ── */
        .navbar-main {
            background: rgba(255,255,255,.97);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 1px 0 rgba(0,0,0,.06);
            position: sticky;
            top: 0;
            z-index: 999;
            padding: 0;
            transition: box-shadow .3s;
        }
        .navbar-main.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
        .navbar-main .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .navbar-main .nav-logo {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .navbar-main .nav-logo:hover { text-decoration: none; color: var(--primary); }
        .navbar-main .nav-logo .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .nav-links a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: .88rem;
            font-weight: 500;
            color: #475569;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
        }
        .nav-links a i { font-size: .8rem; opacity: .7; }
        .nav-links a:hover {
            background: #f0fdf9;
            color: var(--primary);
        }
        .nav-links a:hover i { opacity: 1; }
        .btn-login {
            background: var(--primary);
            color: #fff;
            border-radius: 8px;
            padding: 9px 22px;
            font-weight: 600;
            font-size: .88rem;
            border: none;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(22,160,133,.25);
        }
        .btn-login:hover { background: var(--primary-dark); color: #fff; text-decoration: none; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(22,160,133,.3); }
        .nav-toggle {
            display: none;
            background: none;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 1.2rem;
            color: #475569;
            cursor: pointer;
            transition: all .2s;
        }
        .nav-toggle:hover { background: #f8fafc; border-color: #cbd5e1; }
        .nav-links-wrap {
            display: flex;
            align-items: center;
        }
        @media (max-width: 991.98px) {
            .nav-links a { padding: 8px 12px; font-size: .84rem; }
        }
        @media (max-width: 767.98px) {
            .nav-toggle { display: block; }
            .nav-links-wrap {
                display: none;
                position: absolute;
                top: 64px;
                left: 0; right: 0;
                background: #fff;
                box-shadow: 0 12px 32px rgba(0,0,0,.1);
                border-top: 1px solid #f1f5f9;
                padding: 12px 16px;
                z-index: 998;
            }
            .nav-links-wrap.open { display: block; }
            .nav-links {
                flex-direction: column;
                gap: 2px;
            }
            .nav-links a {
                width: 100%;
                padding: 12px 16px;
                border-radius: 10px;
                font-size: .92rem;
            }
            .nav-links a:hover { background: #f0fdf9; }
            .nav-links .btn-login {
                margin-top: 8px;
                justify-content: center;
                padding: 12px 20px;
            }
        }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(135deg, #0d6651 0%, var(--primary) 45%, var(--primary-light) 100%);
            color: #fff;
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 400px; height: 400px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -40px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }
        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,.2);
            border: 1px solid rgba(255,255,255,.4);
            border-radius: 20px;
            padding: 6px 18px;
            font-size: .85rem;
            margin-bottom: 20px;
            letter-spacing: .5px;
        }
        .hero h1 {
            font-size: 2.8rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .hero p.lead {
            font-size: 1.15rem;
            opacity: .9;
            max-width: 560px;
            margin-bottom: 36px;
            line-height: 1.7;
        }
        .btn-hero-primary {
            background: #fff;
            color: var(--primary);
            border-radius: 30px;
            padding: 14px 36px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 20px rgba(0,0,0,.15);
            transition: transform .2s, box-shadow .2s;
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,.2); color: var(--primary-dark); text-decoration: none; }
        .btn-hero-secondary {
            background: rgba(255,255,255,.15);
            color: #fff;
            border: 2px solid rgba(255,255,255,.5);
            border-radius: 30px;
            padding: 12px 32px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background .2s;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }
        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 50px;
        }
        .hero-stat .num { font-size: 2rem; font-weight: 800; }
        .hero-stat .label { font-size: .82rem; opacity: .8; margin-top: 2px; }
        .hero-visual {
            position: relative;
            z-index: 2;
        }
        .hero-card-mock {
            background: rgba(255,255,255,.95);
            border-radius: 16px;
            padding: 24px;
            color: #333;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
            max-width: 340px;
            margin: 0 auto;
        }
        .hero-card-mock .card-tag {
            background: #e8f8f5;
            color: var(--primary);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 12px;
        }
        .hero-card-mock h6 { font-weight: 700; margin-bottom: 14px; font-size: 1rem; }
        .progress-row { margin-bottom: 10px; }
        .progress-row .label-row { display: flex; justify-content: space-between; font-size: .8rem; color: #666; margin-bottom: 4px; }
        .progress { height: 8px; border-radius: 4px; background: #eee; }
        .progress-bar { border-radius: 4px; }
        .score-badge {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin-top: 14px;
        }
        .score-badge .big { font-size: 2rem; font-weight: 800; }
        .score-badge .small { font-size: .8rem; opacity: .85; }

        /* ── Section titles ── */
        .section-title { font-size: 2rem; font-weight: 800; color: #1a1a2e; }
        .section-sub { color: #666; font-size: 1rem; max-width: 560px; margin: 0 auto; line-height: 1.7; }
        .divider-line {
            width: 50px; height: 4px;
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            border-radius: 2px;
            margin: 14px auto 20px;
        }

        /* ── Features ── */
        .features-section { background: #f8fffe; padding: 80px 0; }
        .feature-card {
            background: #fff;
            border-radius: 16px;
            padding: 32px 24px;
            height: 100%;
            box-shadow: 0 2px 16px rgba(0,0,0,.06);
            transition: transform .25s, box-shadow .25s;
            border-bottom: 3px solid transparent;
        }
        .feature-card:hover { transform: translateY(-6px); box-shadow: 0 12px 32px rgba(0,0,0,.1); border-bottom-color: var(--primary); }
        .feature-icon {
            width: 58px; height: 58px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 18px;
        }
        .feature-card h5 { font-weight: 700; margin-bottom: 10px; color: #1a1a2e; }
        .feature-card p { color: #666; font-size: .92rem; line-height: 1.7; margin: 0; }

        /* ── How it works ── */
        .steps-section { padding: 80px 0; }
        .step-item { text-align: center; padding: 0 20px; }
        .step-num {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; font-weight: 800;
            margin: 0 auto 18px;
        }
        .step-connector {
            position: absolute;
            top: 28px;
            left: 60%;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, var(--primary-light), #ddd);
        }
        .step-item h6 { font-weight: 700; margin-bottom: 8px; }
        .step-item p { color: #777; font-size: .88rem; line-height: 1.6; }

        /* ── Pre-test Banner ── */
        .pretest-banner {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: #fff;
            padding: 70px 0;
        }
        .pretest-banner h2 { font-size: 2.2rem; font-weight: 800; }
        .pretest-banner p { opacity: .9; font-size: 1.05rem; }
        .btn-pretest {
            background: #fff;
            color: var(--primary);
            border-radius: 30px;
            padding: 14px 40px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(0,0,0,.2);
            transition: transform .2s;
        }
        .btn-pretest:hover { transform: translateY(-3px); color: var(--primary-dark); text-decoration: none; }

        /* ── Roles ── */
        .roles-section { padding: 80px 0; background: #fff; }
        .role-card {
            border-radius: 16px;
            padding: 36px 28px;
            height: 100%;
            text-align: center;
            transition: transform .2s;
        }
        .role-card:hover { transform: translateY(-4px); }
        .role-card.student { background: #eafaf1; }
        .role-card.teacher { background: #fef9e7; }
        .role-card.admin   { background: #eaf0fb; }
        .role-icon {
            width: 70px; height: 70px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 18px;
        }
        .role-card.student .role-icon { background: #27ae60; color: #fff; }
        .role-card.teacher .role-icon { background: var(--gold); color: #fff; }
        .role-card.admin   .role-icon { background: #3498db; color: #fff; }
        .role-card h5 { font-weight: 700; margin-bottom: 12px; }
        .role-card ul { list-style: none; padding: 0; margin: 0; text-align: left; }
        .role-card ul li { padding: 5px 0; font-size: .9rem; color: #555; }
        .role-card ul li::before { content: '✓  '; font-weight: 700; color: var(--primary); }

        /* ── Footer ── */
        footer {
            background: linear-gradient(180deg, #0f172a 0%, #1a1a2e 100%);
            color: rgba(255,255,255,.7);
            padding: 0 0 0;
        }
        /* stats strip */
        .ft-stats-strip {
            background: rgba(255,255,255,.04);
            border-top: 1px solid rgba(255,255,255,.07);
            border-bottom: 1px solid rgba(255,255,255,.07);
            padding: 22px 0;
        }
        .ft-stats-grid {
            display: flex; justify-content: center; flex-wrap: wrap; gap: 0;
        }
        .ft-stat {
            text-align: center; padding: 8px 24px;
            border-right: 1px solid rgba(255,255,255,.08);
        }
        .ft-stat:last-child { border-right: none; }
        .ft-stat .fsv { font-size: 1.45rem; font-weight: 800; line-height: 1; display: block; }
        .ft-stat .fsl { font-size: .7rem; color: rgba(255,255,255,.4); margin-top: 3px; display: block; letter-spacing:.3px; }
        /* divider */
        .ft-divider { border-color: rgba(255,255,255,.08); margin: 0; }
        /* main body */
        .ft-body { padding: 40px 0 24px; }
        footer .footer-brand { color: #fff; font-size: 1.15rem; font-weight: 700; margin-bottom: 8px; }
        footer .footer-desc { font-size: .85rem; line-height: 1.7; max-width: 280px; color: rgba(255,255,255,.5); }
        footer .footer-link { color: rgba(255,255,255,.5); text-decoration: none; display: block; padding: 4px 0; font-size: .87rem; transition: color .15s; }
        footer .footer-link:hover { color: var(--primary-light); text-decoration: none; }
        footer .footer-col-title { color: rgba(255,255,255,.9); font-weight: 700; margin-bottom: 14px; font-size: .88rem; letter-spacing: .3px; }
        footer .ft-bottom { padding: 16px 0; border-top: 1px solid rgba(255,255,255,.07); font-size: .78rem; color: rgba(255,255,255,.3); text-align: center; }
        @media (max-width: 575px) {
            .ft-stat { padding: 8px 14px; }
            .ft-stat .fsv { font-size: 1.15rem; }
        }

        @media (max-width: 767px) {
            .hero { padding: 70px 0 50px; }
            .hero h1 { font-size: 1.9rem; }
            .hero-stats { gap: 24px; flex-wrap: wrap; }
            .hero-visual { margin-top: 40px; }
            .hero-card-mock { max-width: 100%; }
            .section-title { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

<!-- ── Navbar ── -->
<nav class="navbar-main" id="mainNav">
    <div class="container">
        <a href="{{ route('landing') }}" class="nav-logo">
            <div class="logo-icon"><i class="fas fa-book-open"></i></div>
            <span>ธรรมศึกษา|สำนักเรียนวัดสารอด</span>
        </a>
        <button class="nav-toggle" onclick="document.getElementById('navMenu').classList.toggle('open')" aria-label="เมนู">
            <i class="fas fa-bars"></i>
        </button>
        <div class="nav-links-wrap" id="navMenu">
            <div class="nav-links">
                <a href="{{ route('exam_announcements.index') }}">
                    <i class="fas fa-bullhorn"></i>ประกาศรอบสอบ
                </a>
                @if($examRegistrationOpen ?? false)
                    <a href="{{ route('public_exam_registration') }}">
                        <i class="fas fa-id-card"></i>สมัครสอบธรรมศึกษา
                    </a>
                @endif
                <a href="{{ route('knowhow.index') }}">
                    <i class="fas fa-lightbulb"></i>คลังความรู้
                </a>
                <a href="{{ route('pretest.index') }}">
                    <i class="fas fa-pencil-alt"></i>Pre-Test ฟรี
                </a>
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>เข้าสู่ระบบ
                </a>
            </div>
        </div>
    </div>
</nav>
<script>
window.addEventListener('scroll', function() {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 10);
});
</script>

<!-- ── Hero ── -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star mr-1" style="color:var(--gold);"></i>ธรรมศึกษา|สำนักเรียนวัดสารอด ออนไลน์
                </div>
                <h1>ทดสอบความรู้<br>ธรรมศึกษา<br><span style="color:#a8f0e0;">ได้ทุกที่ ทุกเวลา</span></h1>
                <p class="lead">ระบบข้อสอบออนไลน์สำหรับนักเรียนธรรมศึกษา รองรับทั้งข้อสอบปรนัยและอัตนัย มีใบประกาศเมื่อผ่านเกณฑ์</p>
                <div class="d-flex flex-wrap" style="gap:14px;">
                    <a href="{{ route('pretest.index') }}" class="btn-hero-primary">
                        <i class="fas fa-play-circle"></i>ลองทำแบบทดสอบฟรี
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero-secondary">
                        <i class="fas fa-user"></i>เข้าสู่ระบบ
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="num">ฟรี</div>
                        <div class="label">Pre-Test ไม่ต้องสมัคร</div>
                    </div>
                    <div class="hero-stat">
                        <div class="num">24/7</div>
                        <div class="label">เข้าถึงได้ตลอดเวลา</div>
                    </div>
                    <div class="hero-stat">
                        <div class="num">ใบ<i class="fas fa-certificate ml-1" style="font-size:1.4rem;color:var(--gold);"></i></div>
                        <div class="label">ประกาศเมื่อผ่าน</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-visual mt-5 mt-lg-0">
                <div class="hero-card-mock">
                    {{-- Tab header --}}
                    <div style="display:flex;gap:0;margin-bottom:14px;background:#f0fdf4;border-radius:10px;padding:3px;">
                        <button id="tab-top" onclick="switchTab('top')"
                                style="flex:1;padding:7px 0;border:none;border-radius:8px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .2s;background:var(--primary);color:#fff;">
                            <i class="fas fa-trophy mr-1"></i>สูงสุด 3 อันดับ
                        </button>
                        <button id="tab-recent" onclick="switchTab('recent')"
                                style="flex:1;padding:7px 0;border:none;border-radius:8px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .2s;background:transparent;color:var(--primary);">
                            <i class="fas fa-clock mr-1"></i>ล่าสุด
                        </button>
                    </div>

                    {{-- Top 3 panel --}}
                    <div id="panel-top">
                        @php
                            $medals = ['🥇','🥈','🥉'];
                            $barColors = ['var(--gold)','#94a3b8','#cd7f32'];
                        @endphp
                        @forelse($topScores as $i => $s)
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                            <span style="font-size:1.3rem;flex-shrink:0;width:28px;text-align:center;">{{ $medals[$i] ?? ($i+1).'.' }}</span>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:3px;">
                                    <span style="font-size:.85rem;font-weight:700;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:130px;">{{ $s['name'] }}</span>
                                    <span style="font-size:.82rem;font-weight:800;color:{{ $s['pct'] >= 60 ? '#16a34a' : '#ef4444' }};flex-shrink:0;margin-left:6px;">{{ $s['pct'] }}%</span>
                                </div>
                                <div style="height:5px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                                    <div style="height:100%;width:{{ $s['pct'] }}%;background:{{ $barColors[$i] ?? 'var(--primary)' }};border-radius:4px;transition:width .5s;"></div>
                                </div>
                                <div style="font-size:.7rem;color:#94a3b8;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $s['exam'] }}</div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center;color:#94a3b8;font-size:.82rem;padding:16px 0;">ยังไม่มีข้อมูล</div>
                        @endforelse
                    </div>

                    {{-- Recent panel --}}
                    <div id="panel-recent" style="display:none;">
                        @forelse($recentScores as $i => $s)
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                            <div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $s['passed'] ? '#dcfce7' : '#fee2e2' }};">
                                <i class="fas fa-{{ $s['passed'] ? 'check' : 'times' }}" style="font-size:.65rem;color:{{ $s['passed'] ? '#16a34a' : '#ef4444' }};"></i>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:3px;">
                                    <span style="font-size:.85rem;font-weight:700;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:130px;">{{ $s['name'] }}</span>
                                    <span style="font-size:.82rem;font-weight:800;color:{{ $s['pct'] >= 60 ? '#16a34a' : '#ef4444' }};flex-shrink:0;margin-left:6px;">{{ $s['pct'] }}%</span>
                                </div>
                                <div style="height:5px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                                    <div style="height:100%;width:{{ $s['pct'] }}%;background:{{ $s['passed'] ? 'var(--primary)' : '#f87171' }};border-radius:4px;"></div>
                                </div>
                                <div style="font-size:.7rem;color:#94a3b8;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $s['exam'] }}
                                    @if($s['at']) · {{ $s['at']->diffForHumans() }} @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center;color:#94a3b8;font-size:.82rem;padding:16px 0;">ยังไม่มีข้อมูล</div>
                        @endforelse
                    </div>
                </div>
                <script>
                function switchTab(t) {
                    document.getElementById('panel-top').style.display    = t === 'top'    ? '' : 'none';
                    document.getElementById('panel-recent').style.display = t === 'recent' ? '' : 'none';
                    document.getElementById('tab-top').style.background    = t === 'top'    ? 'var(--primary)' : 'transparent';
                    document.getElementById('tab-top').style.color         = t === 'top'    ? '#fff'           : 'var(--primary)';
                    document.getElementById('tab-recent').style.background = t === 'recent' ? 'var(--primary)' : 'transparent';
                    document.getElementById('tab-recent').style.color      = t === 'recent' ? '#fff'           : 'var(--primary)';
                }
                </script>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4" style="gap:10px;">
            <div>
                <h2 class="section-title mb-1"><i class="fas fa-users" style="color:var(--primary);margin-right:8px;"></i>ติดตามรายชื่อผู้สมัครสอบ</h2>
                <p class="text-muted mb-0">แสดงชื่อ-สกุล ระดับชั้นที่ลงสอบ และสถานะการอนุมัติ อัปเดตอัตโนมัติทุก 15 วินาที</p>
            </div>
            <div class="d-flex flex-wrap" style="gap:8px;">
                <a href="{{ route('exam_announcements.index') }}" class="btn-register">
                    <i class="fas fa-bullhorn mr-1"></i>กำหนดการและประกาศ
                </a>
                @if($examRegistrationOpen ?? false)
                <a href="{{ route('public_exam_registration') }}" class="btn-login">
                    <i class="fas fa-id-card mr-1"></i>สมัครสอบ
                </a>
                @endif
            </div>
        </div>
        <div id="live-exam-registrations" aria-live="polite" aria-busy="true">
            <div class="alert alert-info mb-0"><i class="fas fa-spinner fa-spin mr-2"></i>กำลังโหลดรายชื่อผู้สมัคร...</div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted"><i class="fas fa-shield-alt mr-1"></i>เพื่อคุ้มครองข้อมูลส่วนบุคคล ระบบไม่แสดงเลขประจำตัวประชาชน วันเกิด หรือข้อมูลติดต่อ</small>
            <div id="live-exam-updated" class="text-muted small"></div>
        </div>
    </div>
</section>
<style>
    .exam-session-panel { background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:16px; overflow:hidden; border:1px solid #e9ecef; }
    .exam-session-header { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; cursor:pointer; user-select:none; transition:background .2s; gap:10px; flex-wrap:wrap; }
    .exam-session-header:hover { background:#f8fffe; }
    .exam-session-header .session-info { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
    .exam-session-header .session-year { font-size:1.1rem; font-weight:700; color:#1a1a2e; }
    .exam-session-header .session-meta { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .exam-session-header .session-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:600; }
    .session-badge.open { background:#e8f5e9; color:#2e7d32; }
    .session-badge.closed { background:#fce4ec; color:#c62828; }
    .session-badge.completed { background:#e3f2fd; color:#1565c0; }
    .session-badge.draft { background:#fff3e0; color:#e65100; }
    .session-badge.count { background:#f3e5f5; color:#7b1fa2; }
    .exam-session-toggle { width:32px; height:32px; border-radius:50%; background:#f0f0f0; display:flex; align-items:center; justify-content:center; transition:transform .3s, background .2s; flex-shrink:0; }
    .exam-session-toggle.open { transform:rotate(180deg); background:#e8f5e9; }
    .exam-session-body { max-height:0; overflow:hidden; transition:max-height .4s ease; }
    .exam-session-body.open { max-height:2000px; }
    .exam-reg-table { width:100%; border-collapse:collapse; }
    .exam-reg-table thead th { background:#f8f9fa; padding:10px 16px; font-size:.8rem; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e9ecef; position:sticky; top:0; }
    .exam-reg-table tbody td { padding:10px 16px; font-size:.88rem; color:#333; border-bottom:1px solid #f0f0f0; }
    .exam-reg-table tbody tr:hover { background:#f8fffe; }
    .exam-reg-table tbody tr:last-child td { border-bottom:none; }
    /* ── Status pill (themed) ── */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px 3px 8px;
        border-radius: 20px;
        font-size: .78rem;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .status-pill .status-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }

    /* ลงทะเบียนแล้ว → ส้มอ่อน */
    .status-pill.registered {
        background: #fff3e0;
        color: #e65100;
        border-color: #ffcc80;
    }
    .status-pill.registered .status-dot { background: #f57c00; }

    /* ตรวจสอบแล้ว → ฟ้าอ่อน */
    .status-pill.checked {
        background: #e3f2fd;
        color: #0d47a1;
        border-color: #90caf9;
    }
    .status-pill.checked .status-dot { background: #1976d2; }

    /* มีสิทธิ์สอบ → เขียวอ่อน */
    .status-pill.eligible {
        background: #e8f5e9;
        color: #1b5e2f;
        border-color: #a5d6a7;
    }
    .status-pill.eligible .status-dot { background: #2e7d32; }

    /* ขาดสอบ → แดงอ่อน */
    .status-pill.absent {
        background: #fce4ec;
        color: #880e4f;
        border-color: #f48fb1;
    }
    .status-pill.absent .status-dot { background: #c62828; }

    /* ── Tier tag (themed) ── */
    .tier-tag {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: .78rem;
        font-weight: 600;
        border: 1px solid transparent;
    }
    /* ชั้นตรี → เขียวมิ้นต์ */
    .tier-tag.tree {
        background: #e0f2f1;
        color: #00695c;
        border-color: #80cbc4;
    }
    /* ชั้นโท → ม่วงน้ำเงิน */
    .tier-tag.tho {
        background: #e8eaf6;
        color: #283593;
        border-color: #9fa8da;
    }
    /* ชั้นเอก → ชมพูเข้ม */
    .tier-tag.ek {
        background: #fce4ec;
        color: #880e4f;
        border-color: #f48fb1;
    }
    .exam-session-body .table-wrap { max-height:400px; overflow-y:auto; }
    .exam-session-body .showing-info { padding:8px 16px; font-size:.8rem; color:#999; background:#fafafa; border-bottom:1px solid #f0f0f0; }
    .exam-session-body .empty-msg { padding:32px 16px; text-align:center; color:#999; font-size:.9rem; }
    @media(max-width:576px) {
        .exam-session-header { padding:12px 14px; }
        .exam-reg-table thead th, .exam-reg-table tbody td { padding:8px 10px; font-size:.8rem; }
        .exam-session-header .session-year { font-size:.95rem; }
    }
</style>

<!-- ── ค้นหารายชื่อผู้สอบผ่าน ── -->
<section class="py-5" id="search-passed-section">
    <div class="container">
        <div class="text-center mb-4">
            <div class="hero-badge" style="background:#fef3c7;color:#92400e;border:none;"><i class="fas fa-award mr-1"></i>ค้นหาผลสอบ</div>
            <h2 class="section-title mt-2">ค้นหารายชื่อผู้สอบผ่าน</h2>
            <div class="divider-line"></div>
            <p class="section-sub">ค้นหาจากฐานข้อมูลผู้สอบนักธรรม-ธรรมศึกษาที่สอบผ่าน สำนักเรียนวัดสารอด</p>
        </div>

        <div class="search-passed-card">
            {{-- ค้นหาหลัก --}}
            <div class="sp-search-main">
                <div class="sp-input-wrap">
                    <i class="fas fa-search sp-input-icon"></i>
                    <input type="text" id="spSearchName" class="sp-input" placeholder="พิมพ์ชื่อ หรือ ฉายาเพื่อค้นหา..." autocomplete="off" onkeydown="if(event.key==='Enter')spSearch()">
                </div>
                <button class="sp-btn-search" onclick="spSearch()"><i class="fas fa-search mr-1"></i>ค้นหา</button>
            </div>

            {{-- ปุ่มเปิด/ยุบ ค้นหาขั้นสูง --}}
            <button class="sp-adv-toggle" onclick="spToggleAdvanced()" id="spAdvToggle">
                <i class="fas fa-sliders-h mr-1"></i>ค้นหาขั้นสูง
                <i class="fas fa-chevron-down sp-chevron" id="spAdvChevron"></i>
            </button>

            {{-- ค้นหาขั้นสูง (ยุบได้) --}}
            <div class="sp-advanced" id="spAdvanced">
                <div class="sp-adv-grid">
                    <div class="sp-adv-field">
                        <label><i class="fas fa-book mr-1"></i>ประเภทหลักสูตร</label>
                        <select id="spCourseType" class="sp-select">
                            <option value="">ทุกประเภท</option>
                            <option value="นักธรรม">นักธรรม</option>
                            <option value="ธรรมศึกษา">ธรรมศึกษา</option>
                        </select>
                    </div>
                    <div class="sp-adv-field">
                        <label><i class="fas fa-layer-group mr-1"></i>ชั้นสอบ</label>
                        <select id="spExamLevel" class="sp-select">
                            <option value="">ทุกชั้น</option>
                            <option value="ตรี">ตรี</option>
                            <option value="โท">โท</option>
                            <option value="เอก">เอก</option>
                        </select>
                    </div>
                    <div class="sp-adv-field">
                        <label><i class="fas fa-calendar mr-1"></i>ปีการศึกษา (พ.ศ.)</label>
                        <input type="text" id="spYear" class="sp-input-sm" placeholder="เช่น 2568" maxlength="4">
                    </div>
                    <div class="sp-adv-field">
                        <label><i class="fas fa-id-badge mr-1"></i>เลขที่ประจำตัวสอบ</label>
                        <input type="text" id="spSeatNo" class="sp-input-sm" placeholder="เช่น กท ๑๑๖๘/๑๐๗๗">
                    </div>
                </div>
                <div class="sp-adv-actions">
                    <button class="sp-btn-search" onclick="spSearch()"><i class="fas fa-search mr-1"></i>ค้นหา</button>
                    <button class="sp-btn-clear" onclick="spClear()"><i class="fas fa-undo mr-1"></i>ล้างค่า</button>
                </div>
            </div>

            {{-- ผลลัพธ์ --}}
            <div id="spResults" class="sp-results" style="display:none;"></div>
        </div>
    </div>
</section>
<style>
    .search-passed-card { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,.06); border:1px solid #e5e7eb; padding:28px; max-width:800px; margin:0 auto; }
    .sp-search-main { display:flex; gap:10px; align-items:stretch; }
    .sp-input-wrap { flex:1; position:relative; }
    .sp-input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:.9rem; }
    .sp-input { width:100%; padding:12px 14px 12px 40px; border:2px solid #e5e7eb; border-radius:10px; font-size:.95rem; font-family:'Sarabun',sans-serif; transition:border-color .2s; outline:none; }
    .sp-input:focus { border-color:var(--primary); }
    .sp-btn-search { background:var(--primary); color:#fff; border:none; border-radius:10px; padding:12px 24px; font-size:.9rem; font-weight:600; font-family:'Sarabun',sans-serif; cursor:pointer; transition:all .2s; white-space:nowrap; display:inline-flex; align-items:center; gap:4px; }
    .sp-btn-search:hover { background:var(--primary-dark); transform:translateY(-1px); }
    .sp-btn-clear { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; border-radius:10px; padding:10px 20px; font-size:.88rem; font-weight:500; font-family:'Sarabun',sans-serif; cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:4px; }
    .sp-btn-clear:hover { background:#e2e8f0; }
    .sp-adv-toggle { display:flex; align-items:center; gap:4px; background:none; border:none; color:#64748b; font-size:.85rem; font-weight:500; font-family:'Sarabun',sans-serif; cursor:pointer; padding:10px 0 2px; transition:color .2s; }
    .sp-adv-toggle:hover { color:var(--primary); }
    .sp-chevron { font-size:.65rem; transition:transform .3s; margin-left:4px; }
    .sp-chevron.open { transform:rotate(180deg); }
    .sp-advanced { max-height:0; overflow:hidden; transition:max-height .35s ease, padding .35s ease, opacity .25s ease; opacity:0; padding:0 0; }
    .sp-advanced.open { max-height:300px; opacity:1; padding:16px 0 4px; }
    .sp-adv-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px; }
    .sp-adv-field label { display:block; font-size:.8rem; font-weight:600; color:#475569; margin-bottom:5px; }
    .sp-select, .sp-input-sm { width:100%; padding:9px 12px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:.88rem; font-family:'Sarabun',sans-serif; outline:none; transition:border-color .2s; background:#fff; }
    .sp-select:focus, .sp-input-sm:focus { border-color:var(--primary); }
    .sp-adv-actions { display:flex; gap:8px; }
    .sp-results { margin-top:20px; }
    .sp-result-header { font-size:.9rem; color:#475569; padding:10px 14px; background:#f0fdf4; border-radius:8px; margin-bottom:12px; }
    .sp-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .sp-table { width:100%; border-collapse:collapse; min-width:700px; }
    .sp-table thead th { background:#f8fafc; padding:10px 12px; font-size:.76rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e5e7eb; text-align:left; white-space:nowrap; }
    .sp-table tbody td { padding:10px 12px; font-size:.85rem; color:#334155; border-bottom:1px solid #f1f5f9; }
    .sp-table tbody tr:hover { background:#f0fdf9; }
    .sp-table tbody tr:last-child td { border-bottom:none; }
    .sp-pass { display:inline-block; background:#dcfce7; color:#166534; padding:2px 10px; border-radius:12px; font-size:.78rem; font-weight:600; }
    .sp-other { display:inline-block; background:#fef3c7; color:#92400e; padding:2px 10px; border-radius:12px; font-size:.78rem; font-weight:600; }
    .sp-empty { text-align:center; padding:32px 16px; color:#94a3b8; font-size:.9rem; }
    .sp-empty i { font-size:2rem; display:block; margin-bottom:10px; color:#e2e8f0; }
    .sp-loading { text-align:center; padding:24px; color:#64748b; font-size:.88rem; }
    @media(max-width:576px) {
        .search-passed-card { padding:18px; }
        .sp-search-main { flex-direction:column; }
        .sp-adv-grid { grid-template-columns:1fr; }
    }
</style>

<!-- ── Features ── -->
<section class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="hero-badge" style="background:#e8f8f5;color:var(--primary);border:none;">ฟีเจอร์หลัก</div>
            <h2 class="section-title mt-2">ครบครัน รองรับทุกความต้องการ</h2>
            <div class="divider-line"></div>
            <p class="section-sub">ระบบออกแบบมาสำหรับการเรียนการสอนธรรมศึกษาโดยเฉพาะ</p>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#e8f8f5;color:var(--primary);">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <h5>Pre-Test ฟรี</h5>
                    <p>ทดลองทำข้อสอบได้ทันทีโดยไม่ต้องสมัครสมาชิก รองรับข้อสอบปรนัยและอัตนัย มีเฉลยทันทีหลังส่ง</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fef9e7;color:#f39c12;">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h5>ใบประกาศนียบัตร</h5>
                    <p>ผ่านเกณฑ์แล้วรับใบประกาศได้ทันที พร้อมรหัสยืนยันที่ตรวจสอบได้ออนไลน์</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#eaf0fb;color:#3498db;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h5>จับเวลาอัตโนมัติ</h5>
                    <p>ระบบนับถอยหลังเวลาอัตโนมัติ เมื่อหมดเวลาส่งให้โดยอัตโนมัติ ป้องกันการเกินเวลาที่กำหนด</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fdf2f8;color:#8e44ad;">
                        <i class="fas fa-random"></i>
                    </div>
                    <h5>สุ่มข้อสอบ & Pool</h5>
                    <p>ออกแบบให้สุ่มลำดับคำถามและสุ่มดึงข้อสอบจาก Pool ป้องกันการจำลำดับข้อสอบ</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#e8f8f5;color:#27ae60;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h5>ระบบครูผู้สอน</h5>
                    <p>ครูจัดการข้อสอบ ออกข้อสอบ นำเข้า JSON ตรวจข้อเขียน และออกใบประกาศได้ทันที</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fef9e7;color:#e67e22;">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h5>รายงานผล</h5>
                    <p>ดูผลการสอบย้อนหลัง กรองตามห้องเรียน วิชา และสถานะการผ่าน Export ข้อมูลได้</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── How it works ── -->
<section class="steps-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">เริ่มต้นง่าย ใช้ได้เลย</h2>
            <div class="divider-line"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-6 col-md-3 mb-4 position-relative">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <h6>เลือกแบบทดสอบ</h6>
                    <p>เลือกวิชาและระดับที่ต้องการทำข้อสอบ</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="step-item">
                    <div class="step-num">2</div>
                    <h6>ทำแบบทดสอบ</h6>
                    <p>ตอบคำถามภายในเวลาที่กำหนดไว้</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="step-item">
                    <div class="step-num">3</div>
                    <h6>ดูผลทันที</h6>
                    <p>เห็นคะแนนและเฉลยคำตอบทันทีหลังส่ง</p>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-4">
                <div class="step-item">
                    <div class="step-num" style="background:linear-gradient(135deg,var(--gold),#f1c40f);"><i class="fas fa-certificate" style="font-size:1rem;"></i></div>
                    <h6>รับใบประกาศ</h6>
                    <p>ผ่านเกณฑ์รับใบประกาศดิจิทัลได้ทันที</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── Pre-test Banner ── -->
<section class="pretest-banner">
    <div class="container text-center">
        <div class="mb-3" style="font-size:3rem;">✏️</div>
        <h2>ลองทำ Pre-Test ฟรีได้เลย!</h2>
        <p class="mb-4">ไม่ต้องสมัครสมาชิก ทำได้ทันที ดูเฉลยทันทีหลังส่ง</p>
        <a href="{{ route('pretest.index') }}" class="btn-pretest">
            <i class="fas fa-play-circle"></i>เริ่มทำแบบทดสอบฟรี
        </a>
    </div>
</section>

<!-- ── Roles ── -->
<section class="roles-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">เหมาะสำหรับทุกบทบาท</h2>
            <div class="divider-line"></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="role-card student h-100">
                    <div class="role-icon"><i class="fas fa-user-graduate"></i></div>
                    <h5>นักเรียน</h5>
                    <ul>
                        <li>ทำข้อสอบออนไลน์</li>
                        <li>ดูประวัติการสอบ</li>
                        <li>รับและแสดงใบประกาศ</li>
                        <li>ตรวจสอบเฉลยคำตอบ</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="role-card teacher h-100">
                    <div class="role-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <h5>ครูผู้สอน</h5>
                    <ul>
                        <li>สร้างและจัดการข้อสอบ</li>
                        <li>Import ข้อสอบผ่าน JSON</li>
                        <li>ตรวจข้อเขียนออนไลน์</li>
                        <li>ออกใบประกาศนียบัตร</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="role-card admin h-100">
                    <div class="role-icon"><i class="fas fa-user-shield"></i></div>
                    <h5>ผู้ดูแลระบบ</h5>
                    <ul>
                        <li>จัดการผู้ใช้ทั้งหมด</li>
                        <li>ดูรายงานภาพรวม</li>
                        <li>จัดการห้องเรียน / วิชา</li>
                        <li>ดูสถิติการใช้งาน</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── What's New ── -->
@if($updates->isNotEmpty())
<section style="background:#f8fafc;padding:60px 0 40px;border-top:1px solid #e2e8f0;">
    <div class="container">
        <div class="text-center mb-5">
            <span style="background:#dcfce7;color:#16a34a;border-radius:20px;padding:5px 16px;font-size:.82rem;font-weight:700;letter-spacing:.5px;">
                <i class="fas fa-bell mr-1"></i>WHAT'S NEW
            </span>
            <h2 class="section-title mt-3">อัพเดทล่าสุด</h2>
            <div class="divider-line"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div style="position:relative;">
                    {{-- Timeline line --}}
                    <div style="position:absolute;left:18px;top:0;bottom:0;width:2px;background:linear-gradient(to bottom,#16a34a,#e2e8f0);border-radius:2px;"></div>

                    @foreach($updates as $upd)
                    @php $meta = $upd->getTypeMeta(); @endphp
                    <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:24px;position:relative;">
                        {{-- Timeline dot --}}
                        <div style="width:38px;height:38px;border-radius:50%;background:{{ $meta['bg'] }};border:3px solid {{ $meta['color'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;">
                            <i class="fas {{ $meta['icon'] }}" style="color:{{ $meta['color'] }};font-size:.82rem;"></i>
                        </div>
                        {{-- Content --}}
                        <div style="flex:1;background:#fff;border-radius:14px;padding:16px 20px;box-shadow:0 2px 10px rgba(0,0,0,.06);border:1px solid #f1f5f9;">
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:6px;">
                                <span style="background:{{ $meta['bg'] }};color:{{ $meta['color'] }};border-radius:12px;padding:2px 10px;font-size:.72rem;font-weight:700;">
                                    {{ $meta['label'] }}
                                </span>
                                <span style="font-size:.75rem;color:#94a3b8;">
                                    <i class="fas fa-clock mr-1"></i>
                                    @php
                                        $dt = $upd->published_at ?? $upd->created_at;
                                        $thaiM = ['','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
                                        echo $dt->day . ' ' . $thaiM[$dt->month] . ' ' . ($dt->year + 543);
                                    @endphp
                                </span>
                            </div>
                            <div style="font-weight:700;color:#1e293b;font-size:.95rem;margin-bottom:4px;">{{ $upd->title }}</div>
                            @if($upd->description)
                            <div style="font-size:.85rem;color:#64748b;line-height:1.6;">{{ $upd->description }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ── Footer ── -->
<footer>

    {{-- ── Stats strip ── --}}
    <div class="ft-stats-strip">
        <div class="container">
            <div class="ft-stats-grid">
                <div class="ft-stat">
                    <span class="fsv" style="color:#1abc9c;">{{ number_format($visitorStats['total'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-eye mr-1"></i>ผู้เข้าชมรวม</span>
                </div>
                <div class="ft-stat">
                    <span class="fsv" style="color:#3b82f6;">{{ number_format($visitorStats['unique'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-fingerprint mr-1"></i>IP ไม่ซ้ำ</span>
                </div>
                <div class="ft-stat">
                    <span class="fsv" style="color:#f59e0b;">{{ number_format($visitorStats['returning'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-redo mr-1"></i>IP กลับมาซ้ำ</span>
                </div>
                <div class="ft-stat">
                    <span class="fsv" style="color:#a78bfa;">{{ number_format($visitorStats['today'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-sun mr-1"></i>เข้าชมวันนี้</span>
                </div>
                <div class="ft-stat">
                    <span class="fsv" style="color:#34d399;">{{ number_format($siteStats['students'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-user-graduate mr-1"></i>นักเรียน</span>
                </div>
                <div class="ft-stat">
                    <span class="fsv" style="color:#60a5fa;">{{ number_format($siteStats['exam_sets'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-layer-group mr-1"></i>ชุดข้อสอบ</span>
                </div>
                <div class="ft-stat">
                    <span class="fsv" style="color:#fb923c;">{{ number_format($siteStats['attempts'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-pencil-alt mr-1"></i>ผู้เข้าสอบ</span>
                </div>
                <div class="ft-stat">
                    <span class="fsv" style="color:#4ade80;">{{ number_format($siteStats['passed'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-check-circle mr-1"></i>สอบผ่าน</span>
                </div>
                <div class="ft-stat">
                    <span class="fsv" style="color:#f9a8d4;">{{ number_format($siteStats['certificates'] ?? 0) }}</span>
                    <span class="fsl"><i class="fas fa-certificate mr-1"></i>ใบประกาศ</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Main body ── --}}
    <div class="ft-body">
        <div class="container">
            <div class="row">
                {{-- Brand --}}
                <div class="col-md-4 mb-4">
                    <div class="footer-brand">
                        <i class="fas fa-book-open mr-2" style="color:#1abc9c;"></i>ธรรมศึกษา|สำนักเรียนวัดสารอด
                    </div>
                    <p class="footer-desc">ระบบข้อสอบออนไลน์ สำหรับนักเรียนและครูผู้สอน<br>ใช้งานง่าย เข้าถึงได้ทุกที่ทุกเวลา</p>
                    <div class="mt-3" style="font-size:.78rem;color:rgba(255,255,255,.35);">
                        <i class="fas fa-school mr-1" style="color:#1abc9c;"></i>
                        สำนักเรียนวัดสารอด กรุงเทพฯ
                    </div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.25);margin-top:3px;padding-left:14px;">
                        พระปริยัติธรรม &nbsp;·&nbsp; แผนกบาลี &nbsp;·&nbsp; แผนกธรรม
                    </div>
                </div>

                {{-- เมนู --}}
                <div class="col-6 col-md-2 mb-4">
                    <div class="footer-col-title">เมนู</div>
                    <a href="{{ route('landing') }}" class="footer-link">หน้าแรก</a>
                    <a href="{{ route('knowhow.index') }}" class="footer-link">คลังความรู้</a>
                    <a href="{{ route('pretest.index') }}" class="footer-link">Pre-Test ฟรี</a>
                    <a href="{{ route('exam_announcements.index') }}" class="footer-link">กำหนดการและประกาศรอบสอบ</a>
                    @if($examRegistrationOpen ?? false)
                        <a href="{{ route('public_exam_registration') }}" class="footer-link">สมัครสอบธรรมศึกษา</a>
                    @endif
                    <a href="{{ route('login') }}" class="footer-link">เข้าสู่ระบบ</a>
                    <a href="{{ route('register') }}" class="footer-link">สมัครสมาชิก</a>
                </div>

                {{-- ฟีเจอร์ --}}
                <div class="col-6 col-md-3 mb-4">
                    <div class="footer-col-title">ฟีเจอร์</div>
                    <a href="#" class="footer-link">ระบบข้อสอบ</a>
                    <a href="#" class="footer-link">ใบประกาศนียบัตร</a>
                    <a href="{{ route('certificates.verify', 'example') }}" class="footer-link">ตรวจสอบใบประกาศ</a>
                    <a href="{{ route('pretest.stats') }}" class="footer-link">สถิติการสอบ</a>
                </div>

                {{-- เริ่มต้น --}}
                <div class="col-md-3 mb-4">
                    <div class="footer-col-title">เริ่มต้น</div>
                    <a href="{{ route('pretest.index') }}" class="footer-link">
                        <i class="fas fa-play-circle mr-1" style="color:#1abc9c;font-size:.7rem;"></i>ทดลองฟรี ไม่ต้องสมัคร
                    </a>
                    <a href="{{ route('register') }}" class="footer-link">
                        <i class="fas fa-user-plus mr-1" style="color:#3b82f6;font-size:.7rem;"></i>สมัครนักเรียน
                    </a>
                    <a href="{{ route('login') }}" class="footer-link">
                        <i class="fas fa-chalkboard-teacher mr-1" style="color:#f59e0b;font-size:.7rem;"></i>เข้าสู่ระบบครู
                    </a>
                    {{-- pre-test count badge --}}
                    <div class="mt-3 d-inline-flex align-items-center"
                         style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
                                border-radius:20px;padding:5px 12px;font-size:.75rem;color:rgba(255,255,255,.5);">
                        <i class="fas fa-file-alt mr-2" style="color:#1abc9c;"></i>
                        Pre-Test {{ number_format($siteStats['pretest'] ?? 0) }} ชุด พร้อมสอบ
                    </div>
                </div>
            </div>

            {{-- Sponsor --}}
            <div style="border-top:1px solid rgba(255,255,255,.07);padding-top:20px;margin-top:4px;">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:12px;">
                    <div style="font-size:.78rem;color:rgba(255,255,255,.35);">
                        <i class="fas fa-hands-helping mr-1" style="color:#1abc9c;"></i>
                        <strong style="color:rgba(255,255,255,.55);">พัฒนาโดย</strong>
                        สำนักเรียนวัดสารอด | 2569
                    </div>
                    <div class="d-flex flex-wrap" style="gap:6px;">
                        <span style="background:rgba(22,160,133,.25);color:#1abc9c;border:1px solid rgba(22,160,133,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">Quiz</span>
                        <span style="background:rgba(41,128,185,.25);color:#3b82f6;border:1px solid rgba(41,128,185,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">Kru.One</span>
                        <span style="background:rgba(142,68,173,.25);color:#a78bfa;border:1px solid rgba(142,68,173,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">ALevel</span>
                        <span style="background:rgba(192,57,43,.25);color:#f87171;border:1px solid rgba(192,57,43,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">Pali</span>
                        <span style="background:rgba(211,84,0,.25);color:#fb923c;border:1px solid rgba(211,84,0,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">Tutor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Bottom bar ── --}}
    <div class="ft-bottom">
        &copy; {{ date('Y') }} ธรรมศึกษา|สำนักเรียนวัดสารอด &mdash; สงวนลิขสิทธิ์
        &nbsp;·&nbsp; พัฒนาโดย สำนักเรียนวัดสารอด | 2569
        @include('partials.theme_toggle')
    </div>

</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var container = document.getElementById('live-exam-registrations');
    var updated = document.getElementById('live-exam-updated');
    var endpoint = @json(route('exam_registrations.live'));
    var hasLoaded = false;
    var openPanels = {};

    var statusKeyMap = {
        'ลงทะเบียนแล้ว': 'registered',
        'ตรวจสอบแล้ว': 'checked',
        'มีสิทธิ์สอบ': 'eligible',
        'ขาดสอบ': 'absent'
    };

    var tierKeyMap = {
        'ธรรมศึกษาชั้นตรี': 'tree',
        'ธรรมศึกษาชั้นโท': 'tho',
        'ธรรมศึกษาชั้นเอก': 'ek'
    };

    var sessionStatusMap = {
        'เปิดรับสมัคร': 'open',
        'ปิดรับสมัคร': 'closed',
        'เสร็จสิ้น': 'completed',
        'ฉบับร่าง': 'draft'
    };

    var statusIcons = {
        'registered': 'fa-clock',
        'checked': 'fa-search',
        'eligible': 'fa-check-circle',
        'absent': 'fa-times-circle'
    };

    function el(tag, cls, html) {
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (html !== undefined) n.innerHTML = html;
        return n;
    }

    function togglePanel(id) {
        var body = document.getElementById('panel-body-' + id);
        var toggle = document.getElementById('panel-toggle-' + id);
        if (body.classList.contains('open')) {
            body.classList.remove('open');
            toggle.classList.remove('open');
            openPanels[id] = false;
        } else {
            body.classList.add('open');
            toggle.classList.add('open');
            openPanels[id] = true;
        }
    }

    function render(sessions) {
        container.textContent = '';
        if (!sessions.length) {
            container.appendChild(el('div', 'alert alert-info mb-0', '<i class="fas fa-info-circle mr-2"></i>ยังไม่มีรอบสอบที่เผยแพร่รายชื่อผู้สมัคร'));
            return;
        }

        sessions.forEach(function (session, idx) {
            var panel = el('div', 'exam-session-panel');
            var sKey = sessionStatusMap[session.status] || 'draft';
            var isOpen = openPanels[session.id] !== undefined ? openPanels[session.id] : (idx === 0);

            var header = el('div', 'exam-session-header');
            header.onclick = function () { togglePanel(session.id); };

            var info = el('div', 'session-info');
            info.appendChild(el('span', 'session-year', '<i class="fas fa-calendar-alt mr-1" style="color:var(--primary);"></i> ปีการศึกษา ' + session.academic_year));

            var meta = el('div', 'session-meta');
            if (session.exam_date) {
                meta.appendChild(el('span', 'session-badge ' + sKey, '<i class="fas fa-calendar-day mr-1"></i>วันสอบ ' + session.exam_date));
            }
            meta.appendChild(el('span', 'session-badge ' + sKey, session.status));
            meta.appendChild(el('span', 'session-badge count', '<i class="fas fa-user-friends mr-1"></i>' + session.registrations_count + ' คน'));
            info.appendChild(meta);
            header.appendChild(info);

            var toggle = el('div', 'exam-session-toggle' + (isOpen ? ' open' : ''));
            toggle.id = 'panel-toggle-' + session.id;
            toggle.innerHTML = '<i class="fas fa-chevron-down" style="font-size:.8rem;color:#666;"></i>';
            header.appendChild(toggle);
            panel.appendChild(header);

            var body = el('div', 'exam-session-body' + (isOpen ? ' open' : ''));
            body.id = 'panel-body-' + session.id;

            if (!session.registrations.length) {
                body.appendChild(el('div', 'empty-msg', '<i class="fas fa-inbox mr-2" style="font-size:1.5rem;color:#ddd;"></i><br>ยังไม่มีผู้สมัครในรอบนี้'));
            } else {
                if (session.registrations_count > session.registrations.length) {
                    body.appendChild(el('div', 'showing-info', '<i class="fas fa-info-circle mr-1"></i>แสดงรายชื่อล่าสุด ' + session.registrations.length + ' จาก ' + session.registrations_count + ' คน'));
                }
                var tableWrap = el('div', 'table-wrap');
                var table = el('table', 'exam-reg-table');
                var thead = document.createElement('thead');
                var hrow = document.createElement('tr');
                var headers = [
                    {text: '<i class="fas fa-hashtag mr-1"></i>ลำดับ', width: '60px'},
                    {text: '<i class="fas fa-user mr-1"></i>ชื่อ-สกุล'},
                    {text: '<i class="fas fa-layer-group mr-1"></i>ระดับชั้นที่ลงสอบ'},
                    {text: '<i class="fas fa-clipboard-check mr-1"></i>สถานะการอนุมัติ'}
                ];
                headers.forEach(function(h) {
                    var th = el('th', '', h.text);
                    if (h.width) th.style.width = h.width;
                    hrow.appendChild(th);
                });
                thead.appendChild(hrow);
                table.appendChild(thead);

                var tbody = document.createElement('tbody');
                session.registrations.forEach(function (reg, i) {
                    var row = document.createElement('tr');
                    row.appendChild(el('td', '', '<span style="color:#aaa;font-weight:600;">' + (i + 1) + '</span>'));
                    row.appendChild(el('td', '', '<strong>' + reg.name + '</strong>'));

                    var tKey = tierKeyMap[reg.tier] || '';
                    row.appendChild(el('td', '', '<span class="tier-tag ' + tKey + '">' + reg.tier + '</span>'));

                    var sKey = statusKeyMap[reg.status] || 'registered';
                    var icon = statusIcons[sKey] || 'fa-circle';
                    row.appendChild(el('td', '', '<span class="status-pill ' + sKey + '"><span class="status-dot"></span><i class="fas ' + icon + '" style="font-size:.65rem;"></i> ' + reg.status + '</span>'));

                    tbody.appendChild(row);
                });
                table.appendChild(tbody);
                tableWrap.appendChild(table);
                body.appendChild(tableWrap);
            }
            panel.appendChild(body);
            container.appendChild(panel);
        });
    }

    function refresh() {
        fetch(endpoint, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
            .then(function (response) {
                if (!response.ok) throw new Error('ไม่สามารถโหลดรายชื่อผู้สมัครได้');
                return response.json();
            })
            .then(function (data) {
                render(data.sessions || []);
                hasLoaded = true;
                container.setAttribute('aria-busy', 'false');
                updated.innerHTML = '<i class="fas fa-sync-alt mr-1"></i>อัปเดตล่าสุด ' + new Date().toLocaleTimeString('th-TH');
            })
            .catch(function () {
                container.setAttribute('aria-busy', 'false');
                if (!hasLoaded) {
                    container.textContent = '';
                    container.appendChild(el('div', 'alert alert-warning mb-0', '<i class="fas fa-exclamation-triangle mr-2"></i>ไม่สามารถโหลดรายชื่อผู้สมัครได้ กรุณาลองใหม่อีกครั้ง'));
                }
                updated.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>อัปเดตข้อมูลไม่สำเร็จ ระบบจะลองใหม่อัตโนมัติ';
            });
    }

    refresh();
    window.setInterval(refresh, 15000);
})();

// ── ค้นหาผู้สอบผ่าน ──
(function () {
    var endpoint = @json(route('exam_results.search'));
    var advOpen = false;

    window.spToggleAdvanced = function () {
        advOpen = !advOpen;
        var panel = document.getElementById('spAdvanced');
        var chevron = document.getElementById('spAdvChevron');
        if (advOpen) {
            panel.classList.add('open');
            chevron.style.transform = 'rotate(180deg)';
        } else {
            panel.classList.remove('open');
            chevron.style.transform = '';
        }
    };

    window.spClear = function () {
        document.getElementById('spSearchName').value = '';
        document.getElementById('spCourseType').value = '';
        document.getElementById('spExamLevel').value = '';
        document.getElementById('spYear').value = '';
        document.getElementById('spSeatNo').value = '';
        document.getElementById('spResults').style.display = 'none';
        document.getElementById('spResults').innerHTML = '';
    };

    window.spSearch = function () {
        var q = document.getElementById('spSearchName').value.trim();
        var courseType = document.getElementById('spCourseType').value;
        var examLevel = document.getElementById('spExamLevel').value;
        var year = document.getElementById('spYear').value.trim();
        var seatNo = document.getElementById('spSeatNo').value.trim();

        if (!q && !courseType && !examLevel && !year && !seatNo) {
            var box = document.getElementById('spResults');
            box.style.display = 'block';
            box.innerHTML = '<div class="sp-empty"><i class="fas fa-info-circle"></i> กรุณาพิมพ์ชื่อ หรือเลือกเงื่อนไขค้นหา</div>';
            return;
        }

        var params = [];
        if (q) params.push('q=' + encodeURIComponent(q));
        if (courseType) params.push('course_type=' + encodeURIComponent(courseType));
        if (examLevel) params.push('exam_level=' + encodeURIComponent(examLevel));
        if (year) params.push('year=' + encodeURIComponent(year));
        if (seatNo) params.push('seat_no=' + encodeURIComponent(seatNo));

        var box = document.getElementById('spResults');
        box.style.display = 'block';
        box.innerHTML = '<div class="sp-loading"><i class="fas fa-spinner fa-spin"></i> กำลังค้นหา...</div>';

        fetch(endpoint + '?' + params.join('&'), { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (!data.results || !data.results.length) {
                    box.innerHTML = '<div class="sp-empty"><i class="fas fa-search"></i> ไม่พบรายชื่อที่ตรงกับเงื่อนไข</div>';
                    return;
                }

                var html = '<div class="sp-result-header"><i class="fas fa-award mr-1"></i>พบ <strong>' + data.total + '</strong> รายการ';
                if (data.total > 50) html += ' (แสดง 50 รายการแรก)';
                html += '</div>';

                html += '<div class="sp-table-wrap"><table class="sp-table"><thead><tr>'
                    + '<th>#</th><th>เลขที่สอบ</th><th>ชื่อ-ฉายา-สกุล</th>'
                    + '<th>วัด/โรงเรียน</th><th>อำเภอ</th><th>จังหวัด</th>'
                    + '<th>ประเภท</th><th>ชั้น</th><th>ปี พ.ศ.</th><th>ผล</th>'
                    + '</tr></thead><tbody>';

                data.results.forEach(function (r, i) {
                    var statusCls = r.status === 'สอบได้' ? 'sp-pass' : 'sp-other';
                    html += '<tr>'
                        + '<td>' + (i + 1) + '</td>'
                        + '<td>' + (r.seat_no || '-') + '</td>'
                        + '<td><strong>' + r.name + '</strong></td>'
                        + '<td>' + (r.temple || '-') + '</td>'
                        + '<td>' + (r.district || '-') + '</td>'
                        + '<td>' + (r.province || '-') + '</td>'
                        + '<td>' + (r.course_type || '-') + '</td>'
                        + '<td>' + (r.exam_level || '-') + '</td>'
                        + '<td>' + (r.year || '-') + '</td>'
                        + '<td><span class="' + statusCls + '">' + (r.status || '-') + '</span></td>'
                        + '</tr>';
                });

                html += '</tbody></table></div>';
                box.innerHTML = html;
            })
            .catch(function () {
                box.innerHTML = '<div class="sp-empty"><i class="fas fa-exclamation-triangle"></i> เกิดข้อผิดพลาด กรุณาลองใหม่</div>';
            });
    };
})();
</script>
</body>
</html>
