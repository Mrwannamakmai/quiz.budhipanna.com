<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $examSet->name }} — Pre-Test V-Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; margin: 0; }
        .fas,.far,.fal { font-family: "Font Awesome 5 Free" !important; }
        .fab { font-family: "Font Awesome 5 Brands" !important; }

        /* ── Navbar ── */
        .pt-nav { background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.08); padding: 10px 0; position: sticky; top: 0; z-index: 999; }
        .pt-nav .inner { display: flex; align-items: center; justify-content: space-between; }
        .pt-nav .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .pt-nav .logo-icon { width: 38px; height: 38px; background: linear-gradient(135deg,#16a085,#1abc9c); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; }
        .pt-nav .logo-text { font-size: 1.1rem; font-weight: 700; color: #16a085; }
        .pt-nav .back-link { color: #64748b; font-size: .88rem; text-decoration: none; display: flex; align-items: center; gap: 6px; padding: 7px 14px; border: 1.5px solid #e2e8f0; border-radius: 20px; transition: all .15s; }
        .pt-nav .back-link:hover { background: #f1f5f9; color: #1e293b; text-decoration: none; }
        .pt-nav .login-btn { background: #16a085; color: #fff; border-radius: 20px; padding: 7px 20px; font-weight: 600; text-decoration: none; font-size: .9rem; transition: background .15s; }
        .pt-nav .login-btn:hover { background: #138d75; color: #fff; text-decoration: none; }

        /* ── Hero band ── */
        .show-hero {
            background: linear-gradient(135deg,#0f766e 0%,#14b8a6 50%,#2dd4bf 100%);
            padding: 36px 0 28px; color: #fff; position: relative; overflow: hidden;
        }
        .show-hero::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='1.5' fill='white' opacity='.07'/%3E%3C/svg%3E");
        }
        .show-hero .inner { position: relative; }
        .show-hero .breadcrumb-strip { font-size: .8rem; opacity: .75; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .show-hero .breadcrumb-strip a { color: #fff; text-decoration: none; opacity: .8; }
        .show-hero .breadcrumb-strip a:hover { opacity: 1; }
        .show-hero h1 { font-size: 1.55rem; font-weight: 800; margin: 0 0 8px; line-height: 1.3; }
        .show-hero .hero-desc { font-size: .92rem; opacity: .85; margin: 0; max-width: 600px; }
        .show-hero .hero-tags { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 14px; }
        .show-hero .tag { background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.25);
                          border-radius: 20px; padding: 4px 12px; font-size: .75rem; font-weight: 600; }

        /* ── Main layout ── */
        .show-body { padding: 28px 0 40px; }
        .show-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
        @media (max-width: 768px) { .show-grid { grid-template-columns: 1fr; } }

        /* ── Info card ── */
        .info-card { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.07); border: 1.5px solid #f1f5f9; overflow: hidden; }
        .info-card-head { padding: 18px 20px 14px; border-bottom: 1.5px solid #f1f5f9; }
        .info-card-head h6 { margin: 0; font-size: .88rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .5px; }
        .info-card-body { padding: 20px; }

        /* meta grid */
        .meta-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; }
        .meta-box { background: #f8fafc; border-radius: 10px; padding: 14px 12px; text-align: center; border: 1.5px solid #f1f5f9; }
        .meta-box .mv { font-size: 1.4rem; font-weight: 800; line-height: 1; display: block; }
        .meta-box .ml { font-size: .72rem; color: #94a3b8; margin-top: 4px; display: block; }

        /* stats row */
        .stats-row { display: flex; gap: 0; border: 1.5px solid #f1f5f9; border-radius: 10px; overflow: hidden; margin-top: 14px; }
        .stat-col { flex: 1; text-align: center; padding: 12px 8px; border-right: 1.5px solid #f1f5f9; }
        .stat-col:last-child { border-right: none; }
        .stat-col .sv { font-size: 1.1rem; font-weight: 800; display: block; }
        .stat-col .sl { font-size: .68rem; color: #94a3b8; }

        /* pass rate bar */
        .pass-bar-wrap { margin-top: 16px; }
        .pass-bar-label { display: flex; justify-content: space-between; font-size: .78rem; color: #64748b; margin-bottom: 5px; }
        .pass-bar-bg { height: 8px; background: #f1f5f9; border-radius: 6px; }
        .pass-bar-fill { height: 8px; border-radius: 6px; transition: width .8s ease; }

        /* ── Start card (sticky sidebar) ── */
        .start-card { background: #fff; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,.1); border: 1.5px solid #f1f5f9; overflow: hidden; position: sticky; top: 80px; }
        .start-card-accent { height: 5px; background: linear-gradient(90deg,#0f766e,#14b8a6,#2dd4bf); }
        .start-card-body { padding: 22px 20px; }
        .start-card-body h5 { font-size: 1rem; font-weight: 800; color: #1e293b; margin: 0 0 6px; }
        .start-card-body .sc-desc { font-size: .82rem; color: #94a3b8; margin: 0 0 18px; }

        .quick-metas { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 18px; }
        .qm { background: #f8fafc; border-radius: 8px; padding: 10px 12px; }
        .qm .qmv { font-size: .95rem; font-weight: 700; color: #1e293b; display: block; }
        .qm .qml { font-size: .7rem; color: #94a3b8; }

        .btn-start-main {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 13px; border-radius: 10px;
            background: linear-gradient(135deg,#0f766e,#16a34a);
            color: #fff; font-size: 1rem; font-weight: 800;
            text-decoration: none; border: none; cursor: pointer;
            transition: all .2s; box-shadow: 0 4px 14px rgba(15,118,110,.35);
        }
        .btn-start-main:hover { filter: brightness(1.08); transform: translateY(-1px); color: #fff; text-decoration: none; box-shadow: 0 6px 20px rgba(15,118,110,.45); }

        .login-notice { background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 10px;
                        padding: 12px 14px; font-size: .8rem; color: #1e40af; margin-top: 12px;
                        display: flex; align-items: flex-start; gap: 8px; }
        .login-notice i { color: #3b82f6; margin-top: 2px; flex-shrink: 0; }
        .login-notice a { color: #1d4ed8; font-weight: 600; }

        /* ── Footer ── */
        footer { background: linear-gradient(180deg,#0f172a 0%,#1a1a2e 100%); color: rgba(255,255,255,.6); padding: 0; margin-top: 50px; }
        .ft-stats-strip { background: rgba(255,255,255,.04); border-top: 1px solid rgba(255,255,255,.07); border-bottom: 1px solid rgba(255,255,255,.07); padding: 20px 0; }
        .ft-stats-grid { display: flex; justify-content: center; flex-wrap: wrap; gap: 0; }
        .ft-stat { text-align: center; padding: 8px 20px; border-right: 1px solid rgba(255,255,255,.08); }
        .ft-stat:last-child { border-right: none; }
        .ft-stat .fsv { font-size: 1.35rem; font-weight: 800; line-height: 1; display: block; }
        .ft-stat .fsl { font-size: .68rem; color: rgba(255,255,255,.38); margin-top: 3px; display: block; }
        .ft-body { padding: 32px 0 20px; }
        .ft-brand { color: #fff; font-size: 1.1rem; font-weight: 700; margin-bottom: 6px; }
        .ft-desc { font-size: .84rem; color: rgba(255,255,255,.45); line-height: 1.7; max-width: 260px; }
        .ft-col-title { color: rgba(255,255,255,.85); font-weight: 700; margin-bottom: 12px; font-size: .85rem; }
        .ft-link { color: rgba(255,255,255,.48); text-decoration: none; display: block; padding: 3px 0; font-size: .85rem; transition: color .15s; }
        .ft-link:hover { color: #1abc9c; text-decoration: none; }
        .ft-bottom { padding: 14px 0; border-top: 1px solid rgba(255,255,255,.07); font-size: .76rem; color: rgba(255,255,255,.28); text-align: center; }
        @media (max-width: 575px) { .ft-stat { padding: 7px 12px; } .ft-stat .fsv { font-size: 1.1rem; } .meta-grid { grid-template-columns: repeat(3,1fr); } }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="pt-nav">
    <div class="container inner">
        <a href="{{ route('landing') }}" class="logo">
            <div class="logo-icon"><i class="fas fa-book-open"></i></div>
            <span class="logo-text">V-Quiz</span>
        </a>
        <div class="d-flex align-items-center" style="gap:10px;">
            <a href="{{ route('pretest.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> รายการแบบทดสอบ
            </a>
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt mr-1"></i>เข้าสู่ระบบ
            </a>
        </div>
    </div>
</nav>

<!-- Hero -->
<div class="show-hero">
    <div class="container inner">
        <div class="breadcrumb-strip">
            <a href="{{ route('landing') }}">หน้าแรก</a>
            <i class="fas fa-chevron-right" style="font-size:.6rem;"></i>
            <a href="{{ route('pretest.index') }}">Pre-Test ฟรี</a>
            <i class="fas fa-chevron-right" style="font-size:.6rem;"></i>
            <span>{{ $examSet->name }}</span>
        </div>
        <h1><i class="fas fa-file-alt mr-2" style="opacity:.85;"></i>{{ $examSet->name }}</h1>
        @if($examSet->description)
        <p class="hero-desc">{{ $examSet->description }}</p>
        @endif
        <div class="hero-tags">
            <span class="tag"><i class="fas fa-check-circle mr-1"></i>ฟรี ไม่ต้องสมัคร</span>
            @if($examSet->classroom)
            <span class="tag"><i class="fas fa-graduation-cap mr-1"></i>{{ $examSet->classroom->name }}</span>
            @endif
            @if($examSet->subject)
            <span class="tag"><i class="fas fa-book mr-1"></i>{{ $examSet->subject->name }}</span>
            @endif
        </div>
    </div>
</div>

<!-- Body -->
<div class="show-body">
    <div class="container">
        <div class="show-grid">

            {{-- ── Left: Info ── --}}
            <div>
                {{-- Meta boxes --}}
                <div class="info-card mb-4">
                    <div class="info-card-head">
                        <h6><i class="fas fa-info-circle mr-2" style="color:#14b8a6;"></i>รายละเอียดชุดข้อสอบ</h6>
                    </div>
                    <div class="info-card-body">
                        <div class="meta-grid">
                            <div class="meta-box">
                                <span class="mv" style="color:#1e293b;">{{ $questionCount }}</span>
                                <span class="ml"><i class="fas fa-question-circle mr-1" style="color:#14b8a6;"></i>จำนวนข้อ</span>
                            </div>
                            <div class="meta-box">
                                <span class="mv" style="color:#f59e0b;">{{ $examSet->time_limit ? $examSet->time_limit : '∞' }}</span>
                                <span class="ml"><i class="fas fa-clock mr-1" style="color:#f59e0b;"></i>นาที</span>
                            </div>
                            <div class="meta-box">
                                <span class="mv" style="color:#16a34a;">{{ $examSet->pass_percentage }}%</span>
                                <span class="ml"><i class="fas fa-percent mr-1" style="color:#16a34a;"></i>เกณฑ์ผ่าน</span>
                            </div>
                        </div>

                        @if($examSet->attempt_count > 0)
                        <div class="stats-row mt-3">
                            <div class="stat-col">
                                <span class="sv" style="color:#3b82f6;">{{ number_format($examSet->view_count) }}</span>
                                <span class="sl"><i class="fas fa-eye mr-1"></i>ผู้เข้าชม</span>
                            </div>
                            <div class="stat-col">
                                <span class="sv" style="color:#f59e0b;">{{ number_format($examSet->attempt_count) }}</span>
                                <span class="sl"><i class="fas fa-pencil-alt mr-1"></i>ผู้ทำข้อสอบ</span>
                            </div>
                            <div class="stat-col">
                                <span class="sv" style="color:#16a34a;">{{ number_format($examSet->pass_count) }}</span>
                                <span class="sl"><i class="fas fa-check mr-1"></i>ผ่านเกณฑ์</span>
                            </div>
                            @if($passRate !== null)
                            <div class="stat-col">
                                <span class="sv" style="color:{{ $passRate >= 50 ? '#16a34a' : '#ef4444' }};">{{ $passRate }}%</span>
                                <span class="sl"><i class="fas fa-chart-pie mr-1"></i>อัตราผ่าน</span>
                            </div>
                            @endif
                        </div>

                        @if($passRate !== null)
                        <div class="pass-bar-wrap">
                            <div class="pass-bar-label">
                                <span>อัตราผ่านการสอบ</span>
                                <span style="font-weight:700;color:{{ $passRate >= 50 ? '#16a34a' : '#ef4444' }};">{{ $passRate }}%</span>
                            </div>
                            <div class="pass-bar-bg">
                                <div class="pass-bar-fill" style="width:{{ min($passRate,100) }}%;background:{{ $passRate >= 50 ? 'linear-gradient(90deg,#16a34a,#22c55e)' : 'linear-gradient(90deg,#dc2626,#f87171)' }};"></div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                {{-- Notice --}}
                <div class="info-card">
                    <div class="info-card-head">
                        <h6><i class="fas fa-lightbulb mr-2" style="color:#f59e0b;"></i>ก่อนเริ่มทำข้อสอบ</h6>
                    </div>
                    <div class="info-card-body">
                        <ul style="padding-left:18px;margin:0;color:#475569;font-size:.88rem;line-height:2;">
                            <li>ทำได้ไม่จำกัดจำนวนครั้ง</li>
                            @if($examSet->time_limit)
                            <li>มีเวลา <strong>{{ $examSet->time_limit }} นาที</strong> เมื่อหมดเวลาระบบส่งอัตโนมัติ</li>
                            @else
                            <li>ไม่จำกัดเวลา ทำได้ตามสบาย</li>
                            @endif
                            <li>เกณฑ์ผ่าน <strong>{{ $examSet->pass_percentage }}%</strong> ขึ้นไป</li>
                            <li>ดูเฉลยได้ทันทีหลังส่งคำตอบ</li>
                            <li style="color:#1d4ed8;">Pre-Test ฟรีไม่บันทึกผลในระบบ — <a href="{{ route('login') }}" style="color:#1d4ed8;font-weight:600;">เข้าสู่ระบบ</a> เพื่อบันทึกและรับใบประกาศ</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ── Right: Start card ── --}}
            <div>
                <div class="start-card">
                    <div class="start-card-accent"></div>
                    <div class="start-card-body">
                        <h5><i class="fas fa-play-circle mr-2" style="color:#16a34a;"></i>พร้อมเริ่มสอบ</h5>
                        <p class="sc-desc">ตรวจสอบรายละเอียดแล้วกดปุ่มเริ่มได้เลย</p>

                        <div class="quick-metas">
                            <div class="qm">
                                <span class="qmv" style="color:#1e293b;">{{ $questionCount }} ข้อ</span>
                                <span class="qml"><i class="fas fa-question-circle mr-1" style="color:#14b8a6;"></i>จำนวนข้อสอบ</span>
                            </div>
                            <div class="qm">
                                <span class="qmv" style="color:#f59e0b;">{{ $examSet->time_limit ? $examSet->time_limit.' นาที' : 'ไม่จำกัด' }}</span>
                                <span class="qml"><i class="fas fa-clock mr-1" style="color:#f59e0b;"></i>เวลาสอบ</span>
                            </div>
                            <div class="qm">
                                <span class="qmv" style="color:#16a34a;">{{ $examSet->pass_percentage }}%</span>
                                <span class="qml"><i class="fas fa-percent mr-1" style="color:#16a34a;"></i>เกณฑ์ผ่าน</span>
                            </div>
                            <div class="qm">
                                <span class="qmv" style="color:#7c3aed;">ไม่จำกัด</span>
                                <span class="qml"><i class="fas fa-redo mr-1" style="color:#7c3aed;"></i>จำนวนครั้ง</span>
                            </div>
                        </div>

                        @if($errors->any())
                        <div class="alert alert-danger small py-2 mb-3">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $errors->first() }}
                        </div>
                        @endif

                        <form action="{{ route('pretest.start', $examSet) }}" method="POST">
                            @csrf
                            @guest
                            <div style="margin-bottom:14px;">
                                <label style="font-size:.8rem;font-weight:700;color:#334155;display:block;margin-bottom:6px;">
                                    <i class="fas fa-user mr-1" style="color:#16a34a;"></i>ชื่อสำหรับใบประกาศ <span style="color:#ef4444;">*</span>
                                </label>
                                <input type="text" name="guest_name"
                                       value="{{ old('guest_name') }}"
                                       placeholder="กรอกชื่อ-นามสกุล"
                                       maxlength="100"
                                       style="width:100%;padding:10px 13px;border:1.5px solid #d1d5db;border-radius:8px;font-size:.9rem;font-family:inherit;outline:none;transition:border-color .2s;background:#fff;"
                                       onfocus="this.style.borderColor='#16a34a'" onblur="this.style.borderColor='#d1d5db'">
                                <p style="font-size:.73rem;color:#94a3b8;margin:5px 0 0;"><i class="fas fa-info-circle mr-1"></i>ชื่อนี้จะปรากฏบนใบประกาศนียบัตร</p>
                            </div>
                            @endguest
                            <button type="submit" class="btn-start-main">
                                <i class="fas fa-play-circle" style="font-size:1.1rem;"></i>
                                เริ่มทำแบบทดสอบ
                            </button>
                        </form>

                        @guest
                        <div class="login-notice" style="margin-top:12px;">
                            <i class="fas fa-info-circle"></i>
                            <span>สมาชิก? <a href="{{ route('login') }}">เข้าสู่ระบบ</a> เพื่อบันทึกผลและดูประวัติ</span>
                        </div>
                        @endguest

                        <div class="text-center mt-3">
                            <a href="{{ route('pretest.index') }}" style="font-size:.8rem;color:#94a3b8;text-decoration:none;">
                                <i class="fas fa-arrow-left mr-1"></i>กลับหน้ารายการ
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    {{-- Stats strip --}}
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
                    <span class="fsl"><i class="fas fa-sun mr-1"></i>วันนี้</span>
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

    {{-- Main body --}}
    <div class="ft-body">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="ft-brand"><i class="fas fa-book-open mr-2" style="color:#1abc9c;"></i>V-Quiz</div>
                    <p class="ft-desc">ระบบข้อสอบออนไลน์ สำหรับนักเรียนและครูผู้สอน<br>ใช้งานง่าย เข้าถึงได้ทุกที่ทุกเวลา</p>
                    <div class="mt-3" style="font-size:.76rem;color:rgba(255,255,255,.3);">
                        <i class="fas fa-school mr-1" style="color:#1abc9c;"></i>
                        สำนักเรียนวัดสารอด เขตราษฏร์บูรณะ กรุงเทพฯ
                    </div>
                    <div style="font-size:.73rem;color:rgba(255,255,255,.22);margin-top:2px;padding-left:14px;">
                        พระปริยัติธรรม &nbsp;·&nbsp; แผนกบาลี &nbsp;·&nbsp; แผนกธรรม
                    </div>
                </div>
                <div class="col-6 col-md-2 mb-4">
                    <div class="ft-col-title">เมนู</div>
                    <a href="{{ route('landing') }}" class="ft-link">หน้าแรก</a>
                    <a href="{{ route('pretest.index') }}" class="ft-link">Pre-Test ฟรี</a>
                    <a href="{{ route('login') }}" class="ft-link">เข้าสู่ระบบ</a>
                    <a href="{{ route('register') }}" class="ft-link">สมัครสมาชิก</a>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <div class="ft-col-title">ฟีเจอร์</div>
                    <a href="#" class="ft-link">ระบบข้อสอบ</a>
                    <a href="#" class="ft-link">ใบประกาศนียบัตร</a>
                    <a href="{{ route('pretest.stats') }}" class="ft-link">สถิติการสอบ</a>
                    <a href="{{ route('pretest.guide') }}" class="ft-link">คู่มือการสอบ</a>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="ft-col-title">เริ่มต้น</div>
                    <a href="{{ route('pretest.index') }}" class="ft-link">
                        <i class="fas fa-play-circle mr-1" style="color:#1abc9c;font-size:.68rem;"></i>ทดลองฟรี ไม่ต้องสมัคร
                    </a>
                    <a href="{{ route('register') }}" class="ft-link">
                        <i class="fas fa-user-plus mr-1" style="color:#3b82f6;font-size:.68rem;"></i>สมัครนักเรียน
                    </a>
                    <a href="{{ route('login') }}" class="ft-link">
                        <i class="fas fa-chalkboard-teacher mr-1" style="color:#f59e0b;font-size:.68rem;"></i>เข้าสู่ระบบครู
                    </a>
                </div>
            </div>
            <div style="border-top:1px solid rgba(255,255,255,.07);padding-top:18px;">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:10px;">
                    <div style="font-size:.76rem;color:rgba(255,255,255,.32);">
                        <i class="fas fa-hands-helping mr-1" style="color:#1abc9c;"></i>
                        <strong style="color:rgba(255,255,255,.5);">สนับสนุนโดย</strong>
                        วัดสารอด | สำนักเรียนวัดสารอด เขตราษฎร์บูรณะ กรุงเทพฯ
                    </div>
                    <div class="d-flex flex-wrap" style="gap:5px;">
                        <span style="background:rgba(22,160,133,.2);color:#1abc9c;border:1px solid rgba(22,160,133,.3);border-radius:20px;padding:2px 10px;font-weight:700;font-size:.7rem;">Quiz</span>
                        <span style="background:rgba(41,128,185,.2);color:#3b82f6;border:1px solid rgba(41,128,185,.3);border-radius:20px;padding:2px 10px;font-weight:700;font-size:.7rem;">Kru.One</span>
                        <span style="background:rgba(142,68,173,.2);color:#a78bfa;border:1px solid rgba(142,68,173,.3);border-radius:20px;padding:2px 10px;font-weight:700;font-size:.7rem;">ALevel</span>
                        <span style="background:rgba(192,57,43,.2);color:#f87171;border:1px solid rgba(192,57,43,.3);border-radius:20px;padding:2px 10px;font-weight:700;font-size:.7rem;">Pali</span>
                        <span style="background:rgba(211,84,0,.2);color:#fb923c;border:1px solid rgba(211,84,0,.3);border-radius:20px;padding:2px 10px;font-weight:700;font-size:.7rem;">Tutor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ft-bottom">
        &copy; {{ date('Y') }} V-Quiz &mdash; สงวนลิขสิทธิ์
        &nbsp;·&nbsp; พัฒนาโดยทีมงานสำนักเรียนวัดสารอด
    </div>
</footer>

</body>
</html>
