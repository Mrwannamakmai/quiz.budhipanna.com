<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เปิดประตูแห่งการฝึกฝนปัญญา ทดลองทำแบบทดสอบ ไม่ต้องสมัครสมาชิก</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; margin: 0; }
        .fas,.far,.fab,.fal { font-family: "Font Awesome 5 Free" !important; }
        .fab { font-family: "Font Awesome 5 Brands" !important; }

        /* ─── Navbar ─── */
        .pt-nav { background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.08); padding: 10px 0; position: sticky; top: 0; z-index: 999; }
        .pt-nav .inner { display: flex; align-items: center; justify-content: space-between; }
        .pt-nav .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .pt-nav .logo-icon { width: 38px; height: 38px; background: linear-gradient(135deg,#16a085,#1abc9c); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; }
        .pt-nav .logo-text { font-size: 1.1rem; font-weight: 700; color: #16a085; }
        .pt-nav .login-btn { background: #16a085; color: #fff; border-radius: 20px; padding: 7px 20px; font-weight: 600; text-decoration: none; font-size: .9rem; transition: background .15s; }
        .pt-nav .login-btn:hover { background: #138d75; color: #fff; text-decoration: none; }

        /* ─── Hero ─── */
        .pt-hero {
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 40%, #2dd4bf 100%);
            color: #fff; padding: 56px 0 44px; text-align: center;
            position: relative; overflow: hidden;
        }
        .pt-hero::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='1.5' fill='white' opacity='.08'/%3E%3C/svg%3E");
        }
        .pt-hero * { position: relative; }
        .pt-hero h2 { font-size: 1.8rem; font-weight: 800; margin-bottom: 8px; text-shadow: 0 2px 8px rgba(0,0,0,.12); }
        .pt-hero p { font-size: 1rem; opacity: .9; margin-bottom: 0; }

        /* ─── Stats ─── */
        .pt-stats { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; margin-top: 24px; }
        .pt-stat {
            background: rgba(255,255,255,.18); backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 12px; padding: 12px 24px; text-align: center; min-width: 110px;
        }
        .pt-stat .val { font-size: 1.5rem; font-weight: 800; display: block; }
        .pt-stat .lbl { font-size: .78rem; opacity: .85; }

        /* ─── Level nav ─── */
        .level-nav { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 0; }
        .level-nav a {
            padding: 10px 22px; border-radius: 25px; font-size: .88rem; font-weight: 600;
            border: 2px solid #e2e8f0; background: #fff; color: #64748b;
            text-decoration: none; transition: all .2s; display: inline-flex; align-items: center; gap: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .level-nav a:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); text-decoration: none; }
        .level-nav a .nav-icon { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .7rem; color: #fff; }
        .level-nav a .cnt { font-size: .72rem; background: #f1f5f9; border-radius: 10px; padding: 1px 8px; color: #94a3b8; margin-left: 2px; }

        /* ─── Top bar (view toggle) ─── */
        .pt-topbar { display: flex; align-items: center; justify-content: space-between; margin: 20px 0 20px; flex-wrap: wrap; gap: 10px; }
        .pt-topbar-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .pt-views { display: flex; gap: 0; border: 1.5px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
        .pt-views button { border: none; background: #fff; padding: 8px 16px; font-size: .82rem; cursor: pointer; color: #64748b; display: flex; align-items: center; gap: 5px; transition: all .15s; }
        .pt-views button:not(:last-child) { border-right: 1.5px solid #e2e8f0; }
        .pt-views button.active { background: #0f766e; color: #fff; }
        .pt-views button:hover:not(.active) { background: #f1f5f9; }

        /* ─── Section ─── */
        .level-section { margin-bottom: 40px; scroll-margin-top: 80px; }
        .level-header {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 20px; padding-bottom: 14px;
            border-bottom: 3px solid #f1f5f9; position: relative;
        }
        .level-header::after {
            content: ''; position: absolute; bottom: -3px; left: 0;
            width: 80px; height: 3px; border-radius: 2px;
        }
        .level-icon {
            width: 48px; height: 48px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }
        .level-info h4 { font-size: 1.15rem; font-weight: 800; color: #1e293b; margin: 0 0 2px; }
        .level-info p { font-size: .82rem; color: #94a3b8; margin: 0; }
        .level-count {
            margin-left: auto; font-size: .78rem; font-weight: 600;
            background: #f1f5f9; padding: 4px 14px; border-radius: 20px; color: #64748b;
        }

        /* ─── Cards grid ─── */
        .exam-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; }
        .exam-card {
            background: #fff; border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            border: 1.5px solid #f1f5f9;
            transition: all .25s; overflow: hidden;
            display: flex; flex-direction: column;
        }
        .exam-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.1); border-color: #e2e8f0; }

        .card-accent { height: 4px; }
        .card-body-inner { padding: 20px 20px 12px; flex: 1; }
        .card-badges { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 10px; }
        .badge-free { background: #d1fae5; color: #065f46; font-size: .72rem; font-weight: 700; padding: 3px 10px; border-radius: 12px; }
        .badge-cat { font-size: .72rem; font-weight: 600; padding: 3px 10px; border-radius: 12px; }
        .badge-sub { font-size: .72rem; font-weight: 500; padding: 3px 10px; border-radius: 12px; background: #eff6ff; color: #1d4ed8; }

        .card-title-text { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 6px; line-height: 1.4; }
        .card-desc { font-size: .82rem; color: #94a3b8; margin: 0 0 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        .card-meta { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; padding: 12px 20px; background: #fafbfd; border-top: 1px solid #f1f5f9; }
        .meta-item { text-align: center; }
        .meta-item .m-icon { font-size: .7rem; margin-bottom: 2px; display: block; }
        .meta-item .m-val { font-size: .9rem; font-weight: 700; color: #1e293b; display: block; }
        .meta-item .m-lbl { font-size: .68rem; color: #94a3b8; }

        .card-action { padding: 12px 20px 16px; }
        .btn-start {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 10px; border-radius: 10px;
            font-size: .9rem; font-weight: 700; color: #fff;
            text-decoration: none; transition: all .2s; border: none;
        }
        .btn-start:hover { filter: brightness(1.08); transform: scale(1.02); color: #fff; text-decoration: none; }

        /* ─── Table view ─── */
        .pt-table-wrap { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.07); border: 1.5px solid #f1f5f9; overflow: hidden; margin-bottom: 40px; }
        .pt-table { margin: 0; width: 100%; }
        .pt-table thead th { background: #f8fafc; font-size: .75rem; font-weight: 700; color: #64748b; text-transform: uppercase; padding: 12px 14px; border-bottom: 2px solid #f1f5f9; white-space: nowrap; }
        .pt-table tbody td { padding: 11px 14px; vertical-align: middle; font-size: .85rem; border-bottom: 1px solid #f8fafc; }
        .pt-table tbody tr:hover { background: #fafbfd; }
        .pt-table .t-name { font-weight: 600; color: #1e293b; }
        .pt-table .t-desc { font-size: .75rem; color: #94a3b8; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; max-width: 220px; }
        .pt-table .t-badge { font-size: .7rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
        .pt-table .act-btn { width: 34px; height: 34px; border-radius: 8px; border: 1.5px solid #e2e8f0; background: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; color: #64748b; text-decoration: none; transition: all .15s; }
        .pt-table .act-btn:hover { background: #f0fdf4; border-color: #6ee7b7; color: #0f766e; text-decoration: none; }

        .pt-table .lv-row td { background: #f1f5f9; padding: 10px 14px; cursor: default; }
        .pt-table .lv-row:hover td { background: #f1f5f9; }
        .pt-table .lv-label { display: flex; align-items: center; gap: 8px; font-size: .82rem; font-weight: 700; color: #1e293b; }
        .pt-table .lv-icon { width: 24px; height: 24px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: .6rem; color: #fff; flex-shrink: 0; }

        /* ─── Empty ─── */
        .empty-state {
            text-align: center; padding: 70px 20px; background: #fff;
            border-radius: 14px; border: 2px dashed #e2e8f0; margin-top: 20px;
        }
        .empty-state .icon { font-size: 56px; color: #d1d5db; margin-bottom: 16px; }
        .empty-state h5 { color: #64748b; font-weight: 700; margin: 0 0 8px; }
        .empty-state p { color: #94a3b8; font-size: .9rem; margin: 0; }

        /* ─── Footer ─── */
        .pt-footer { background: linear-gradient(180deg,#0f172a 0%,#1a1a2e 100%); color: rgba(255,255,255,.6); padding: 0; margin-top: 50px; }
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
        @media (max-width:575px) { .ft-stat { padding: 7px 12px; } .ft-stat .fsv { font-size: 1.1rem; } }

        /* ─── Level colors ─── */
        .lv-tri  { --lv: #16a34a; --lv-light: #dcfce7; --lv-text: #166534; }
        .lv-tho  { --lv: #2563eb; --lv-light: #dbeafe; --lv-text: #1e40af; }
        .lv-ek   { --lv: #7c3aed; --lv-light: #ede9fe; --lv-text: #5b21b6; }
        .lv-none { --lv: #64748b; --lv-light: #f1f5f9; --lv-text: #475569; }

        /* ─── Category badges ─── */
        .cat-กระทู้     { background: #fef3c7; color: #92400e; }
        .cat-ธรรมะ     { background: #dcfce7; color: #166534; }
        .cat-พุทธะ     { background: #dbeafe; color: #1e40af; }
        .cat-วินัย      { background: #ede9fe; color: #5b21b6; }
        .cat-ศาสนพิธี  { background: #fee2e2; color: #991b1b; }

        /* ─── Guide button ─── */
        .btn-guide {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,.22); backdrop-filter: blur(4px);
            border: 1.5px solid rgba(255,255,255,.35); border-radius: 25px;
            padding: 9px 22px; color: #fff; font-weight: 600; font-size: .88rem;
            text-decoration: none; cursor: pointer; transition: all .2s; margin-top: 18px;
        }
        .btn-guide:hover { background: rgba(255,255,255,.35); color: #fff; text-decoration: none; transform: translateY(-1px); }

        /* ─── Guide modal ─── */
        .guide-overlay {
            position: fixed; inset: 0; background: rgba(15,23,42,.6); backdrop-filter: blur(4px);
            z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px;
        }
        .guide-overlay.active { display: flex; }
        .guide-box {
            background: #fff; border-radius: 18px; max-width: 680px; width: 100%;
            max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,.2);
            animation: guideIn .3s ease;
        }
        @keyframes guideIn { from { opacity: 0; transform: scale(.92) translateY(20px); } }

        .guide-header {
            padding: 24px 28px 16px; display: flex; align-items: center; justify-content: space-between;
            border-bottom: 2px solid #f1f5f9; position: sticky; top: 0; background: #fff;
            border-radius: 18px 18px 0 0; z-index: 1;
        }
        .guide-header h5 { margin: 0; font-size: 1.15rem; font-weight: 800; color: #1e293b; }
        .guide-close {
            width: 36px; height: 36px; border-radius: 50%; border: none;
            background: #f1f5f9; color: #64748b; font-size: 1rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center; transition: all .15s;
        }
        .guide-close:hover { background: #e2e8f0; color: #1e293b; }
        .guide-body { padding: 24px 28px 28px; }

        .guide-steps { display: flex; flex-direction: column; gap: 0; }
        .guide-step { display: flex; gap: 18px; position: relative; padding-bottom: 28px; }
        .guide-step:last-child { padding-bottom: 0; }
        .guide-step:last-child .step-line { display: none; }
        .step-indicator { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
        .step-num { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; color: #fff; position: relative; z-index: 1; box-shadow: 0 4px 12px rgba(0,0,0,.15); }
        .step-line { width: 3px; flex: 1; border-radius: 2px; margin-top: 6px; min-height: 20px; }
        .step-content { flex: 1; padding-top: 6px; }
        .step-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 6px; }
        .step-desc { font-size: .88rem; color: #64748b; margin: 0 0 10px; line-height: 1.6; }
        .step-preview { background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; font-size: .82rem; color: #475569; display: flex; align-items: center; gap: 10px; }
        .step-preview .sp-icon { font-size: 1.3rem; flex-shrink: 0; }
        .step-1 .step-num { background: linear-gradient(135deg,#16a34a,#22c55e); }
        .step-1 .step-line { background: linear-gradient(to bottom,#22c55e,#3b82f6); }
        .step-2 .step-num { background: linear-gradient(135deg,#2563eb,#3b82f6); }
        .step-2 .step-line { background: linear-gradient(to bottom,#3b82f6,#f59e0b); }
        .step-3 .step-num { background: linear-gradient(135deg,#f59e0b,#fbbf24); }
        .step-3 .step-line { background: linear-gradient(to bottom,#fbbf24,#7c3aed); }
        .step-4 .step-num { background: linear-gradient(135deg,#7c3aed,#8b5cf6); }
        .guide-tips { margin-top: 20px; padding: 16px; background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; font-size: .85rem; color: #166534; }
        .guide-tips strong { display: block; margin-bottom: 6px; font-size: .9rem; }
        .guide-tips ul { margin: 0; padding-left: 18px; }
        .guide-tips li { margin-bottom: 4px; }

        /* ─── Toggle hide ─── */
        .pt-content.hide { display: none; }

        @media (max-width: 576px) {
            .pt-hero { padding: 40px 0 30px; }
            .pt-hero h2 { font-size: 1.4rem; }
            .exam-grid { grid-template-columns: 1fr; }
            .level-count { display: none; }
            .guide-box { border-radius: 14px; }
            .guide-header, .guide-body { padding-left: 20px; padding-right: 20px; }
            .step-num { width: 38px; height: 38px; font-size: .9rem; }
            .guide-step { gap: 14px; }
        }
    </style>
</head>
<body>

@php
    $levelStyles = [
        'ธ.ศ.ตรี' => ['class' => 'lv-tri', 'icon' => 'fa-seedling',   'gradient' => 'linear-gradient(135deg,#16a34a,#22c55e)'],
        'ธ.ศ.โท'  => ['class' => 'lv-tho', 'icon' => 'fa-book-reader', 'gradient' => 'linear-gradient(135deg,#2563eb,#3b82f6)'],
        'ธ.ศ.เอก' => ['class' => 'lv-ek',  'icon' => 'fa-crown',       'gradient' => 'linear-gradient(135deg,#7c3aed,#8b5cf6)'],
        'ทั่วไป'    => ['class' => 'lv-none','icon' => 'fa-folder-open', 'gradient' => 'linear-gradient(135deg,#64748b,#94a3b8)'],
    ];

    $catColors = [
        'กระทู้'     => '#e67e22',
        'ธรรมะ'     => '#16a34a',
        'พุทธะ'     => '#3b82f6',
        'วินัย'      => '#7c3aed',
        'ศาสนพิธี'  => '#dc2626',
    ];
@endphp

<!-- Navbar -->
<nav class="pt-nav">
    <div class="container inner">
        <a href="{{ route('landing') }}" class="logo">
            <div class="logo-icon"><i class="fas fa-book-open"></i></div>
            <span class="logo-text">วัดสารอด|สำนักเรียนวัดสารอด</span>
        </a>
        <div class="d-flex align-items-center" style="gap:10px;">
            <span class="d-none d-md-inline" style="color:#888;font-size:.9rem;">
                <i class="fas fa-pencil-alt mr-1 text-success"></i>Pre-Test ฟรี
            </span>
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt mr-1"></i>เข้าสู่ระบบ
            </a>
        </div>
    </div>
</nav>

<!-- Hero -->
<div class="pt-hero">
    <div class="container">
        <h2><i class="fas fa-pencil-alt mr-2"></i>เปิดประตูแห่งการฝึกฝนปัญญา </h2>
        <p>ทดลองทำแบบทดสอบ|ไม่ต้องสมัครสมาชิก</p>
        <a href="{{ route('pretest.guide') }}" class="btn-guide">
            <i class="fas fa-book-reader"></i> คู่มือการสอบ
        </a>
        <a href="{{ route('pretest.stats') }}" class="btn-guide" style="margin-left:8px;">
            <i class="fas fa-chart-bar"></i> สถิติการสอบ
        </a>

        <div class="pt-stats">
            <div class="pt-stat">
                <span class="val">{{ $stats['total'] }}</span>
                <span class="lbl">ชุดข้อสอบ</span>
            </div>
            <div class="pt-stat">
                <span class="val">{{ $stats['classrooms'] }}</span>
                <span class="lbl">ระดับชั้น</span>
            </div>
            <div class="pt-stat">
                <span class="val">{{ $stats['questions'] }}</span>
                <span class="lbl">ข้อสอบรวม</span>
            </div>
            <div class="pt-stat">
                <span class="val">{{ number_format($stats['views']) }}</span>
                <span class="lbl"><i class="fas fa-eye" style="font-size:.65rem;"></i> ผู้เข้าชม</span>
            </div>
            <div class="pt-stat">
                <span class="val">{{ number_format($stats['attempts']) }}</span>
                <span class="lbl"><i class="fas fa-pencil-alt" style="font-size:.65rem;"></i> ผู้ทำข้อสอบ</span>
            </div>
            <div class="pt-stat">
                <span class="val">{{ number_format($stats['passed']) }}</span>
                <span class="lbl"><i class="fas fa-check-circle" style="font-size:.65rem;"></i> ผ่านเกณฑ์</span>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">

    @if($grouped->isEmpty())
        <div class="empty-state">
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <h5>ยังไม่มีแบบทดสอบฟรีในขณะนี้</h5>
            <p>กรุณากลับมาใหม่ภายหลัง</p>
        </div>
    @else

        {{-- Top bar: level nav + view toggle --}}
        <div class="pt-topbar">
            <div class="pt-topbar-left">
                @if($grouped->count() > 1)
                <div class="level-nav" id="levelNav">
                    @foreach($grouped as $levelName => $group)
                        @php $ls = $levelStyles[$levelName] ?? $levelStyles['ทั่วไป']; @endphp
                        <a href="#{{ $group['slug'] }}" class="js-level-link">
                            <span class="nav-icon" style="background:{{ $ls['gradient'] }};"><i class="fas {{ $ls['icon'] }}"></i></span>
                            {{ $levelName }}
                            <span class="cnt">{{ $group['items']->count() }} ชุด</span>
                        </a>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="pt-views">
                <button type="button" class="active" id="btnGrid" onclick="ptToggleView('grid')">
                    <i class="fas fa-th-large"></i> กริด
                </button>
                <button type="button" id="btnTable" onclick="ptToggleView('table')">
                    <i class="fas fa-list"></i> ตาราง
                </button>
            </div>
        </div>

        {{-- ════ GRID VIEW ════ --}}
        <div class="pt-content" id="ptViewGrid">
            @foreach($grouped as $levelName => $group)
                @php $ls = $levelStyles[$levelName] ?? $levelStyles['ทั่วไป']; @endphp
                <div class="level-section {{ $ls['class'] }}" id="{{ $group['slug'] }}">
                    <div class="level-header">
                        <div class="level-icon" style="background:{{ $ls['gradient'] }};">
                            <i class="fas {{ $ls['icon'] }}"></i>
                        </div>
                        <div class="level-info">
                            <h4>{{ $levelName }}</h4>
                            <p>{{ $group['description'] ?? '' }}</p>
                        </div>
                        <span class="level-count">{{ $group['items']->count() }} ชุดข้อสอบ</span>
                        <style>.level-section.{{ $ls['class'] }} .level-header::after { background: {{ $ls['gradient'] }}; }</style>
                    </div>

                    <div class="exam-grid">
                        @foreach($group['items'] as $es)
                            @php
                                $cat = $es->subject->category ?? null;
                                $btnGrad = $ls['gradient'];
                            @endphp
                            <div class="exam-card">
                                <div class="card-accent" style="background:{{ $btnGrad }};"></div>
                                <div class="card-body-inner">
                                    <div class="card-badges">
                                        <span class="badge-free"><i class="fas fa-check-circle mr-1" style="font-size:.6rem;"></i>ฟรี</span>
                                        @if($cat)
                                            <span class="badge-cat cat-{{ $cat }}">{{ $cat }}</span>
                                        @endif
                                        @if($es->subject)
                                            <span class="badge-sub">{{ $es->subject->name }}</span>
                                        @endif
                                    </div>
                                    <h6 class="card-title-text">{{ $es->name }}</h6>
                                    @if($es->description)
                                        <p class="card-desc">{{ $es->description }}</p>
                                    @endif
                                </div>

                                <div class="card-meta">
                                    <div class="meta-item">
                                        <span class="m-icon" style="color:var(--lv);"><i class="fas fa-question-circle"></i></span>
                                        <span class="m-val">{{ $es->total_questions }}</span>
                                        <span class="m-lbl">ข้อ</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="m-icon" style="color:var(--lv);"><i class="fas fa-clock"></i></span>
                                        <span class="m-val">{{ $es->time_limit ? $es->time_limit : '∞' }}</span>
                                        <span class="m-lbl">นาที</span>
                                    </div>
                                    <div class="meta-item">
                                        <span class="m-icon" style="color:var(--lv);"><i class="fas fa-percent"></i></span>
                                        <span class="m-val">{{ $es->pass_percentage }}%</span>
                                        <span class="m-lbl">เกณฑ์ผ่าน</span>
                                    </div>
                                </div>

                                @if($es->view_count > 0 || $es->attempt_count > 0)
                                <div style="display:flex;gap:12px;padding:0 20px 10px;font-size:.75rem;color:#94a3b8;">
                                    @if($es->view_count > 0)
                                    <span><i class="fas fa-eye mr-1"></i>{{ number_format($es->view_count) }} ผู้ชม</span>
                                    @endif
                                    @if($es->attempt_count > 0)
                                    <span><i class="fas fa-pencil-alt mr-1"></i>{{ number_format($es->attempt_count) }} ทำแล้ว</span>
                                    @endif
                                    @if($es->pass_count > 0)
                                    <span style="color:#16a34a;"><i class="fas fa-check mr-1"></i>{{ number_format($es->pass_count) }} ผ่าน</span>
                                    @endif
                                </div>
                                @endif

                                <div class="card-action">
                                    <a href="{{ route('pretest.show', $es) }}" class="btn-start" style="background:{{ $btnGrad }};">
                                        <i class="fas fa-play-circle"></i> เริ่มทำเลย
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ════ TABLE VIEW ════ --}}
        <div class="pt-content hide" id="ptViewTable">
            <div class="pt-table-wrap">
                <div class="table-responsive">
                <table class="pt-table table mb-0">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>ชื่อชุดข้อสอบ</th>
                            <th>หมวด</th>
                            <th>จำนวนข้อ</th>
                            <th>เวลา</th>
                            <th>เกณฑ์ผ่าน</th>
                            <th>ทำแล้ว / ผ่าน</th>
                            <th style="width:56px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $rowNum = 0; @endphp
                    @foreach($grouped as $levelName => $group)
                        @php $ls = $levelStyles[$levelName] ?? $levelStyles['ทั่วไป']; @endphp
                        <tr class="lv-row">
                            <td colspan="8">
                                <div class="lv-label">
                                    <span class="lv-icon" style="background:{{ $ls['gradient'] }};">
                                        <i class="fas {{ $ls['icon'] }}"></i>
                                    </span>
                                    {{ $levelName }}
                                    <span style="font-weight:400;color:#94a3b8;font-size:.78rem;">({{ $group['items']->count() }} ชุด)</span>
                                </div>
                            </td>
                        </tr>
                        @foreach($group['items'] as $es)
                        @php
                            $rowNum++;
                            $cat = $es->subject->category ?? null;
                            $passRate = $es->attempt_count > 0 ? round($es->pass_count / $es->attempt_count * 100) : null;
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $rowNum }}</td>
                            <td>
                                <div class="t-name">{{ $es->name }}</div>
                                @if($es->description)
                                    <div class="t-desc">{{ $es->description }}</div>
                                @endif
                            </td>
                            <td>
                                @if($cat)
                                    <span class="t-badge cat-{{ $cat }}">{{ $cat }}</span>
                                @elseif($es->subject)
                                    <span class="t-badge badge-sub">{{ $es->subject->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $es->total_questions }} ข้อ</td>
                            <td>{{ $es->time_limit ? $es->time_limit.' นาที' : 'ไม่จำกัด' }}</td>
                            <td>{{ $es->pass_percentage }}%</td>
                            <td>
                                @if($es->attempt_count > 0)
                                    <span style="font-size:.82rem;">
                                        {{ number_format($es->attempt_count) }}
                                        @if($passRate !== null)
                                            <span style="color:{{ $passRate >= 50 ? '#16a34a' : '#94a3b8' }};font-size:.75rem;">
                                                / {{ number_format($es->pass_count) }} ({{ $passRate }}%)
                                            </span>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size:.8rem;">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pretest.show', $es) }}" class="act-btn" title="เริ่มทำ">
                                    <i class="fas fa-play"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>

    @endif

    <div class="text-center mt-4 mb-2 text-muted small">
        ต้องการบันทึกผลและรับใบประกาศ?
        <a href="{{ route('login') }}" style="color:#16a085;font-weight:600;">เข้าสู่ระบบ</a>
    </div>
</div>

<!-- Footer -->
<footer class="pt-footer">

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
                    <div class="ft-brand"><i class="fas fa-book-open mr-2" style="color:#1abc9c;"></i>ลานธรรมศึกษาเพื่อสันติสุข</div>
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
                    <div class="ft-col-title">Pre-Test</div>
                    @foreach($grouped->take(4) as $lvName => $grp)
                    <a href="#{{ $grp['slug'] }}" class="ft-link">{{ $lvName }} ({{ $grp['items']->count() }} ชุด)</a>
                    @endforeach
                    <a href="{{ route('pretest.stats') }}" class="ft-link">สถิติการสอบ</a>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="ft-col-title">เริ่มต้นใช้งาน</div>
                    <a href="{{ route('pretest.index') }}" class="ft-link">
                        <i class="fas fa-play-circle mr-1" style="color:#1abc9c;font-size:.68rem;"></i>ทดลองฟรี ไม่ต้องสมัคร
                    </a>
                    <a href="{{ route('register') }}" class="ft-link">
                        <i class="fas fa-user-plus mr-1" style="color:#3b82f6;font-size:.68rem;"></i>สมัครนักเรียน
                    </a>
                    <a href="{{ route('login') }}" class="ft-link">
                        <i class="fas fa-chalkboard-teacher mr-1" style="color:#f59e0b;font-size:.68rem;"></i>เข้าสู่ระบบครู
                    </a>
                    <a href="{{ route('pretest.guide') }}" class="ft-link">
                        <i class="fas fa-book-reader mr-1" style="color:#a78bfa;font-size:.68rem;"></i>คู่มือการสอบ
                    </a>
                </div>
            </div>

            {{-- Sponsor --}}
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

    {{-- Bottom bar --}}
    <div class="ft-bottom">
        &copy; {{ date('Y') }} ลานธรรมศึกษาเพื่อสันติสุข &mdash; สงวนลิขสิทธิ์
        &nbsp;·&nbsp; พัฒนาโดยทีมงานสำนักเรียนวัดสารอด
    </div>

</footer>

<!-- Guide Modal -->
<div class="guide-overlay" id="guideModal" onclick="if(event.target===this)this.classList.remove('active')">
    <div class="guide-box">
        <div class="guide-header">
            <h5><i class="fas fa-info-circle mr-2" style="color:#16a34a;"></i>วิธีใช้งาน Pre-Test</h5>
            <button class="guide-close" onclick="document.getElementById('guideModal').classList.remove('active')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="guide-body">
            <div class="guide-steps">

                <div class="guide-step step-1">
                    <div class="step-indicator">
                        <div class="step-num">1</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step-content">
                        <h6 class="step-title"><i class="fas fa-hand-pointer mr-1" style="color:#16a34a;"></i>เลือกชุดข้อสอบ</h6>
                        <p class="step-desc">เลือกระดับชั้นที่ต้องการ (ธ.ศ.ตรี, ธ.ศ.โท, ธ.ศ.เอก) แล้วกดปุ่ม <strong>"เริ่มทำเลย"</strong> ที่ชุดข้อสอบที่สนใจ</p>
                        <div class="step-preview">
                            <span class="sp-icon">📋</span>
                            <span>แต่ละชุดจะแสดง: จำนวนข้อ, เวลาที่กำหนด, เกณฑ์ผ่าน</span>
                        </div>
                    </div>
                </div>

                <div class="guide-step step-2">
                    <div class="step-indicator">
                        <div class="step-num">2</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step-content">
                        <h6 class="step-title"><i class="fas fa-info-circle mr-1" style="color:#2563eb;"></i>ตรวจสอบรายละเอียด</h6>
                        <p class="step-desc">ระบบจะแสดงรายละเอียดชุดข้อสอบ — จำนวนข้อ, เวลาสอบ, เกณฑ์ผ่าน ตรวจสอบให้เรียบร้อยแล้วกดปุ่ม <strong>"เริ่มทำแบบทดสอบ"</strong></p>
                        <div class="step-preview">
                            <span class="sp-icon">💡</span>
                            <span>Pre-Test ฟรีไม่บันทึกผลในระบบ — เข้าสู่ระบบก่อนเพื่อบันทึกและรับใบประกาศ</span>
                        </div>
                    </div>
                </div>

                <div class="guide-step step-3">
                    <div class="step-indicator">
                        <div class="step-num">3</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step-content">
                        <h6 class="step-title"><i class="fas fa-pencil-alt mr-1" style="color:#f59e0b;"></i>ทำแบบทดสอบ</h6>
                        <p class="step-desc">ตอบคำถามทีละข้อ — <strong>ปรนัย</strong> เลือกตัวเลือก ก-ง, <strong>อัตนัย</strong> พิมพ์คำตอบในช่องที่กำหนด เมื่อตอบครบทุกข้อแล้วกดปุ่ม <strong>"ส่งคำตอบ"</strong></p>
                        <div class="step-preview">
                            <span class="sp-icon">⏱️</span>
                            <span>หากมีเวลาจำกัด — ตัวจับเวลาจะแสดงมุมบนขวา ครบเวลาระบบส่งอัตโนมัติ</span>
                        </div>
                    </div>
                </div>

                <div class="guide-step step-4">
                    <div class="step-indicator">
                        <div class="step-num">4</div>
                    </div>
                    <div class="step-content">
                        <h6 class="step-title"><i class="fas fa-chart-bar mr-1" style="color:#7c3aed;"></i>ดูผลลัพธ์</h6>
                        <p class="step-desc">ระบบแสดงผลทันที — เปอร์เซ็นต์คะแนน, ผ่าน/ไม่ผ่าน พร้อมเฉลยทุกข้อเพื่อทบทวน สามารถกด <strong>"ทำอีกครั้ง"</strong> ได้ไม่จำกัด</p>
                        <div class="step-preview">
                            <span class="sp-icon">🏆</span>
                            <span>อัตนัย — ระบบเทียบข้อความอัตโนมัติ ตรงกัน ≥80% = ได้เต็ม, ≥50% = ได้ครึ่ง</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="guide-tips">
                <strong><i class="fas fa-lightbulb mr-1"></i>เคล็ดลับ</strong>
                <ul>
                    <li>ทำได้ไม่จำกัดจำนวนครั้ง ทำซ้ำเพื่อฝึกฝนได้เลย</li>
                    <li>อ่านคำถามให้เข้าใจก่อนเลือกตอบ</li>
                    <li>ข้อไหนไม่แน่ใจ ให้ตอบไปก่อน — ไม่หักคะแนน</li>
                    <li>ดูเฉลยหลังส่ง เพื่อทบทวนและจดจำ</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function ptToggleView(mode) {
    var grid    = document.getElementById('ptViewGrid');
    var table   = document.getElementById('ptViewTable');
    var btnG    = document.getElementById('btnGrid');
    var btnT    = document.getElementById('btnTable');
    var levelNav = document.getElementById('levelNav');

    if (mode === 'table') {
        grid.classList.add('hide');
        table.classList.remove('hide');
        btnG.classList.remove('active');
        btnT.classList.add('active');
        if (levelNav) levelNav.style.display = 'none';
    } else {
        table.classList.add('hide');
        grid.classList.remove('hide');
        btnT.classList.remove('active');
        btnG.classList.add('active');
        if (levelNav) levelNav.style.display = '';
    }

    try { localStorage.setItem('pt_view', mode); } catch(e) {}
}

(function () {
    try {
        var saved = localStorage.getItem('pt_view');
        if (saved === 'table') ptToggleView('table');
    } catch(e) {}
})();

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.getElementById('guideModal').classList.remove('active');
});
</script>

</body>
</html>
