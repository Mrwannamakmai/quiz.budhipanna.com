<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#16a085">
    <title>สมัครสอบธรรมศึกษา – วัดสารอด</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary: #16a085;
            --primary-dark: #0e7c67;
            --primary-light: #e8f8f5;
            --secondary: #2c3e50;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --bg: #f2f2f7;
            --card: #fff;
            --card-border: rgba(0,0,0,.06);
            --text: #1c1c1e;
            --text-secondary: #8e8e93;
            --separator: rgba(0,0,0,.08);
            --radius: 14px;
            --safe-bottom: env(safe-area-inset-bottom, 0px);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            font-family: 'Sarabun', -apple-system, 'SF Pro Text', 'Segoe UI', sans-serif;
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            padding-bottom: calc(70px + var(--safe-bottom));
            overflow-x: hidden;
        }

        /* ── Status Bar ── */
        .app-statusbar {
            height: env(safe-area-inset-top, 0px);
            background: var(--primary);
        }

        /* ── Navigation Bar (iOS style) ── */
        .app-navbar {
            background: var(--primary);
            color: #fff;
            padding: 12px 16px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,.12);
        }
        .app-navbar-back {
            background: none; border: none; color: #fff;
            font-size: 1rem; cursor: pointer;
            display: flex; align-items: center; gap: 4px;
            padding: 6px 4px;
        }
        .app-navbar-title {
            font-size: 1.05rem; font-weight: 700;
            text-align: center; flex: 1;
        }
        .app-navbar-action {
            width: 40px; text-align: right;
            font-size: .85rem; color: rgba(255,255,255,.7);
        }

        /* ── Hero (App Header) ── */
        .app-hero {
            position: relative;
            overflow: hidden;
            padding: 32px 20px 28px;
            text-align: center;
            color: #fff;
        }
        .app-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: url('{{ asset("images/หลวงพ่อรอด.jpg") }}') center/cover no-repeat;
            filter: blur(8px) brightness(.38);
            transform: scale(1.15);
            z-index: 0;
        }
        .app-hero > * { position: relative; z-index: 1; }
        .app-hero-logos {
            display: flex;
            justify-content: center; align-items: center;
            gap: 16px; margin-bottom: 14px;
        }
        .app-hero-logos img {
            width: 68px; height: 68px;
            border-radius: 18px;
            object-fit: cover;
            border: 2.5px solid rgba(255,255,255,.5);
            box-shadow: 0 4px 16px rgba(0,0,0,.3);
        }
        .app-hero h2 {
            font-size: 1.25rem; font-weight: 800;
            text-shadow: 0 2px 8px rgba(0,0,0,.4);
            margin-bottom: 4px;
        }
        .app-hero .app-hero-sub {
            font-size: .82rem; opacity: .8;
            text-shadow: 0 1px 4px rgba(0,0,0,.3);
        }

        /* ── Content Area ── */
        .app-content {
            padding: 0 16px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ── iOS-style Card ── */
        .app-card {
            background: var(--card);
            border-radius: var(--radius);
            margin: 16px 0;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .app-card-header {
            padding: 14px 18px 10px;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .app-card-body { padding: 0; }

        /* ── iOS-style Form Rows ── */
        .app-form-row {
            display: flex;
            align-items: center;
            padding: 12px 18px;
            border-bottom: 1px solid var(--separator);
            gap: 12px;
            min-height: 50px;
        }
        .app-form-row:last-child { border-bottom: none; }
        .app-form-row .row-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; color: #fff; flex-shrink: 0;
        }
        .app-form-row .row-icon.green { background: var(--success); }
        .app-form-row .row-icon.blue { background: #3478f6; }
        .app-form-row .row-icon.orange { background: #ff9500; }
        .app-form-row .row-icon.red { background: var(--danger); }
        .app-form-row .row-icon.purple { background: #af52de; }
        .app-form-row .row-icon.teal { background: var(--primary); }
        .app-form-row .row-label {
            font-size: .88rem; font-weight: 500;
            color: var(--text);
            min-width: 90px; flex-shrink: 0;
        }
        .app-form-row .row-label .req { color: var(--danger); font-size: .75rem; }
        .app-form-row .row-input { flex: 1; min-width: 0; }
        .app-form-row input,
        .app-form-row select {
            width: 100%;
            border: none;
            background: transparent;
            font-size: .9rem;
            font-family: inherit;
            color: var(--text);
            text-align: right;
            padding: 4px 0;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }
        .app-form-row select {
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='6'%3E%3Cpath d='M0 0l4 6 4-6z' fill='%23999'/%3E%3C/svg%3E") right center/8px no-repeat;
            padding-right: 14px;
        }
        .app-form-row input::placeholder { color: #c7c7cc; }
        .app-form-row input:disabled,
        .app-form-row select:disabled { color: #c7c7cc; }
        .app-form-row.invalid { background: #fff5f5; }
        .app-form-row.invalid input,
        .app-form-row.invalid select { color: var(--danger); }
        .app-form-error {
            padding: 0 18px 8px 60px;
            font-size: .75rem;
            color: var(--danger);
            display: none;
        }
        .app-form-error.show { display: block; }

        /* ── Full-width form row (stacked) ── */
        .app-form-row.stacked {
            flex-direction: column;
            align-items: stretch;
            gap: 6px;
        }
        .app-form-row.stacked .row-top {
            display: flex; align-items: center; gap: 12px;
        }
        .app-form-row.stacked input,
        .app-form-row.stacked select { text-align: left; }

        /* ── Checkbox row ── */
        .app-form-row.checkbox-row {
            cursor: pointer;
        }
        .app-form-row.checkbox-row input[type="checkbox"] {
            width: auto; flex: none;
            width: 22px; height: 22px;
            accent-color: var(--primary);
        }

        /* ── Step Indicator (pill style) ── */
        .app-steps {
            display: flex;
            justify-content: center;
            gap: 8px;
            padding: 18px 16px 4px;
        }
        .app-step-pill {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            background: #e5e5ea;
            color: #8e8e93;
            cursor: default;
            transition: all .3s ease;
            white-space: nowrap;
        }
        .app-step-pill .pill-num {
            width: 20px; height: 20px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .68rem; font-weight: 700;
            background: #c7c7cc; color: #fff;
        }
        .app-step-pill.active {
            background: var(--primary-light);
            color: var(--primary);
        }
        .app-step-pill.active .pill-num {
            background: var(--primary); color: #fff;
        }
        .app-step-pill.completed {
            background: #e8f5e9;
            color: var(--success);
            cursor: pointer;
        }
        .app-step-pill.completed .pill-num {
            background: var(--success); color: #fff;
        }

        /* ── Form Sections ── */
        .app-form-section { display: none; }
        .app-form-section.active { display: block; animation: slideIn .3s ease; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* ── Notice banner ── */
        .app-notice {
            margin: 16px 0 0;
            padding: 12px 16px;
            border-radius: var(--radius);
            font-size: .82rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .app-notice.warn {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .app-notice.info {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .app-notice i { margin-top: 2px; flex-shrink: 0; }

        /* ── Result messages ── */
        .app-result {
            margin: 16px 0;
            padding: 20px 18px;
            border-radius: var(--radius);
            font-size: .88rem;
            display: none;
        }
        .app-result.success {
            background: #ecfdf5; border: 1px solid #6ee7b7;
            color: #065f46; display: block;
        }
        .app-result.error {
            background: #fef2f2; border: 1px solid #fca5a5;
            color: #991b1b; display: block;
        }
        .app-result .ref-code {
            display: inline-block;
            background: var(--primary); color: #fff;
            padding: 6px 16px; border-radius: 8px;
            font-size: 1.05rem; font-weight: 700;
            margin-top: 8px; letter-spacing: .5px;
        }
        .m-line-invite {
            background: var(--card); border: 2px solid #06c755; border-radius: 14px;
            padding: 20px 16px; text-align: center; margin-top: 16px;
        }
        .m-line-invite-header {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: .95rem; font-weight: 700; color: #06c755; margin-bottom: 12px;
        }
        .m-line-invite-header i { font-size: 1.2rem; }
        .m-line-invite img { border-radius: 10px; border: 3px solid #e8f5e9; }
        .m-line-invite-group { font-weight: 700; font-size: .9rem; color: var(--text); margin-top: 10px; }
        .m-line-invite-desc { font-size: .78rem; color: var(--text-secondary); margin-top: 4px; }
        .m-line-invite-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: #06c755; color: #fff; border: none; border-radius: 10px;
            padding: 10px 22px; font-size: .88rem; font-weight: 600;
            text-decoration: none; margin-top: 12px;
        }

        /* ── Receipt ── */
        .m-receipt-actions {
            display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;
            padding: 16px; margin-top: 8px;
        }
        .m-receipt-actions .m-rbtn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 10px 16px; border-radius: 10px; font-size: .82rem; font-weight: 600;
            border: none; cursor: pointer; text-decoration: none; color: #fff;
        }
        .m-rbtn-print { background: #2563eb; }
        .m-rbtn-pdf { background: #dc2626; }
        .m-rbtn-jpg { background: #f59e0b; }
        .m-rbtn-home { background: #6b7280; }
        .m-receipt-card {
            background: var(--card); border: 2px solid var(--separator); border-radius: 14px;
            padding: 20px 16px; margin: 0 16px 16px;
        }
        .m-receipt-header {
            text-align: center; border-bottom: 2px solid var(--primary); padding-bottom: 14px; margin-bottom: 12px;
        }
        .m-receipt-header img { height: 50px; margin: 0 6px; }
        .m-receipt-header h6 { margin: 8px 0 2px; font-weight: 700; color: var(--text); }
        .m-receipt-header small { color: var(--text-secondary); font-size: .78rem; }
        .m-receipt-row { display: flex; padding: 7px 0; border-bottom: 1px solid var(--separator); font-size: .82rem; }
        .m-receipt-row:last-child { border-bottom: none; }
        .m-receipt-label { color: var(--text-secondary); width: 110px; flex-shrink: 0; }
        .m-receipt-value { font-weight: 600; color: var(--text); flex: 1; text-align: right; }
        .m-receipt-footer { text-align: center; margin-top: 12px; padding-top: 12px; border-top: 2px solid var(--separator); font-size: .72rem; color: var(--text-secondary); }

        /* ── Guide Modal ── */
        .m-guide-btn {
            background: rgba(255,255,255,.2); border: none; color: #fff;
            width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
        }
        .m-guide-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.55);
            z-index: 9999; display: none;
        }
        .m-guide-overlay.show { display: block; }
        .m-guide-modal {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: #fff; border-radius: 20px 20px 0 0;
            max-height: 92vh; overflow-y: auto; z-index: 10000;
            box-shadow: 0 -10px 40px rgba(0,0,0,.2);
            transform: translateY(100%); transition: transform .35s ease;
            padding-bottom: calc(20px + var(--safe-bottom));
        }
        .m-guide-overlay.show .m-guide-modal { transform: translateY(0); }
        .m-guide-handle {
            text-align: center; padding: 12px 0 4px; position: sticky; top: 0;
            background: #fff; border-radius: 20px 20px 0 0; z-index: 1;
        }
        .m-guide-handle::before {
            content: ''; display: block; width: 40px; height: 5px;
            background: #d1d5db; border-radius: 3px; margin: 0 auto 8px;
        }
        .m-guide-header {
            padding: 0 20px 16px; border-bottom: 1px solid var(--separator);
        }
        .m-guide-header h5 { font-weight: 700; font-size: 1.05rem; color: var(--secondary); margin: 0 0 4px; }
        .m-guide-header p { font-size: .78rem; color: var(--text-secondary); margin: 0; }
        .m-guide-body { padding: 20px; }

        .mg-info-card {
            border-radius: 12px; padding: 16px; margin-bottom: 16px; font-size: .83rem; line-height: 1.7;
        }
        .mg-info-card b { display: block; font-size: .88rem; margin-bottom: 4px; }
        .mg-info-card ul { margin: 6px 0 0; padding-left: 16px; }
        .mg-info-card li { margin-bottom: 3px; }

        .mg-step {
            display: flex; gap: 12px; padding: 14px 0;
            border-bottom: 1px solid var(--separator);
        }
        .mg-step:last-of-type { border-bottom: none; }
        .mg-step-num {
            flex-shrink: 0; width: 32px; height: 32px; border-radius: 50%;
            background: var(--primary-light); color: var(--primary); font-weight: 800;
            font-size: .9rem; display: flex; align-items: center; justify-content: center;
        }
        .mg-step-content { flex: 1; }
        .mg-step-title { font-weight: 700; font-size: .9rem; color: var(--text); margin-bottom: 4px; }
        .mg-step-title i { color: var(--primary); margin-right: 4px; font-size: .82rem; }
        .mg-step-desc { font-size: .82rem; color: #475569; line-height: 1.6; }
        .mg-step-desc ul { margin: 4px 0 0; padding-left: 14px; }
        .mg-step-desc li { margin-bottom: 2px; }
        .mg-tag {
            display: inline-block; background: #fef3c7; color: #92400e;
            font-size: .7rem; font-weight: 600; padding: 1px 6px; border-radius: 4px;
        }
        .mg-req {
            display: inline-block; background: #fee2e2; color: #991b1b;
            font-size: .68rem; font-weight: 600; padding: 1px 5px; border-radius: 3px;
        }

        @media print {
            body { background: #fff !important; }
            .app-statusbar, .app-navbar, .app-hero, .app-notice,
            .m-receipt-actions, .m-line-invite, .app-footer, .m-guide-overlay { display: none !important; }
            .m-receipt-card { border: 1px solid #ccc; }
        }

        /* ── Review Card ── */
        .app-review {
            background: var(--card);
            border-radius: var(--radius);
            margin: 16px 0;
            overflow: hidden;
        }
        .app-review-title {
            padding: 14px 18px;
            font-weight: 700;
            font-size: .92rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            display: flex; align-items: center; gap: 8px;
        }
        .app-review-row {
            display: flex;
            padding: 11px 18px;
            border-bottom: 1px solid var(--separator);
            font-size: .85rem;
        }
        .app-review-row:last-child { border-bottom: none; }
        .app-review-label { color: var(--text-secondary); width: 110px; flex-shrink: 0; }
        .app-review-value { font-weight: 600; color: var(--text); flex: 1; text-align: right; }

        /* ── Check group (tags) ── */
        .app-tags {
            display: flex; flex-wrap: wrap; gap: 8px;
            padding: 12px 18px;
        }
        .app-tag {
            font-size: .8rem; padding: 6px 14px;
            border-radius: 20px;
            border: 1.5px solid #e5e5ea;
            background: #fff;
            cursor: pointer;
            font-weight: 500;
            transition: all .2s;
            display: flex; align-items: center; gap: 4px;
        }
        .app-tag input { display: none; }
        .app-tag.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }
        #mReferralOtherWrap { display: none; padding: 0 18px 12px; }
        #mReferralOtherWrap.show { display: block; }
        #mReferralOtherWrap input {
            width: 100%;
            border: 1px solid #e5e5ea;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .88rem;
            font-family: inherit;
            outline: none;
        }
        #mReferralOtherWrap input:focus { border-color: var(--primary); }

        /* ── Conditional fields ── */
        .app-conditional { display: none; }
        .app-conditional.show { display: block; }

        /* ── Bottom Action Bar ── */
        .app-bottom-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--separator);
            padding: 10px 16px calc(10px + var(--safe-bottom));
            z-index: 100;
            display: flex; gap: 10px;
        }
        .app-btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 14px;
            border: none;
            font-size: .95rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .15s;
        }
        .app-btn:active { transform: scale(.97); }
        .app-btn.secondary {
            background: #e5e5ea; color: var(--text);
            flex: .45;
        }
        .app-btn.primary {
            background: var(--primary); color: #fff;
        }
        .app-btn.success-btn {
            background: var(--success); color: #fff;
        }
        .app-btn:disabled { opacity: .5; cursor: not-allowed; }

        /* ── Closed Banner ── */
        .app-closed {
            text-align: center;
            padding: 60px 24px;
        }
        .app-closed-icon {
            width: 80px; height: 80px;
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem; margin: 0 auto 20px;
        }
        .app-closed-icon.warn { background: #fffbeb; color: var(--warning); }
        .app-closed-icon.error { background: #fef2f2; color: var(--danger); }
        .app-closed h3 { font-size: 1.1rem; font-weight: 700; color: var(--secondary); margin-bottom: 8px; }
        .app-closed p { color: var(--text-secondary); font-size: .88rem; }
        .app-closed .app-btn { display: inline-flex; margin-top: 20px; flex: none; padding: 12px 28px; }

        /* ── Footer / Theme Toggle ── */
        .app-footer {
            text-align: center;
            padding: 20px 16px 100px;
            color: var(--text-secondary);
            font-size: .75rem;
        }
        .theme-toggle {
            display: inline-flex;
            background: #e5e5ea;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 12px;
        }
        .theme-toggle a {
            padding: 8px 18px;
            font-size: .78rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--text-secondary);
            transition: all .2s;
        }
        .theme-toggle a.active {
            background: var(--primary);
            color: #fff;
            border-radius: 10px;
        }

        /* ── Responsive ── */
        @media (min-width: 768px) {
            .app-hero-logos img { width: 80px; height: 80px; border-radius: 22px; }
            .app-hero h2 { font-size: 1.5rem; }
            .app-hero { padding: 40px 20px 36px; }
            .app-form-row .row-label { min-width: 130px; }
        }
    </style>
</head>
<body>

{{-- Status Bar spacer --}}
<div class="app-statusbar"></div>

{{-- Navigation Bar --}}
<nav class="app-navbar">
    <a href="{{ route('landing') }}" class="app-navbar-back">
        <i class="fas fa-chevron-left"></i> หน้าหลัก
    </a>
    <div class="app-navbar-title">สมัครสอบ</div>
    <div class="app-navbar-action" style="display:flex;align-items:center;gap:8px;width:auto;">
        <span id="mStepIndicator">1/4</span>
        <button type="button" class="m-guide-btn" onclick="mOpenGuide()" aria-label="คำแนะนำ">
            <i class="fas fa-question-circle"></i>
        </button>
    </div>
</nav>

@if($periodStatus['status'] !== 'open')
    {{-- Closed State --}}
    <div class="app-content">
        <div class="app-closed">
            <div class="app-closed-icon {{ $periodStatus['status'] === 'not_started' ? 'warn' : 'error' }}">
                <i class="fas {{ $periodStatus['status'] === 'not_started' ? 'fa-clock' : 'fa-times-circle' }}"></i>
            </div>
            <h3>{{ $periodStatus['message'] }}</h3>
            <p>หากมีข้อสงสัย กรุณาติดต่อสำนักเรียนของท่าน</p>
            <a href="{{ route('landing') }}" class="app-btn secondary" style="flex:none;">
                <i class="fas fa-home"></i> กลับหน้าหลัก
            </a>
        </div>
    </div>
@else

{{-- Hero --}}
<div class="app-hero">
    <div class="app-hero-logos">
        <img src="{{ asset('images/โลโก้วัดสารอด.jpg') }}" alt="โลโก้วัดสารอด">
        <img src="{{ asset('images/โลโก้สำนักเรียนวัดสารอด.jpg') }}" alt="โลโก้สำนักเรียนวัดสารอด">
    </div>
    <h2>ระบบสมัครเรียนและสอบ</h2>
    <div class="app-hero-sub">สำนักเรียนวัดสารอด &middot; ธรรมศึกษา</div>
</div>

{{-- Result (outside formSection so it stays visible) --}}
<div class="app-content" id="mResultArea" style="display:none;">
    <div id="mResultMsg" role="status" aria-live="polite"></div>
</div>

<div id="mFormSection">
{{-- Step Pills --}}
<div class="app-steps">
    <div class="app-step-pill active" data-step="0" onclick="mGoToStep(0)">
        <div class="pill-num">1</div> ผู้สมัคร
    </div>
    <div class="app-step-pill" data-step="1" onclick="mGoToStep(1)">
        <div class="pill-num">2</div> ที่อยู่
    </div>
    <div class="app-step-pill" data-step="2" onclick="mGoToStep(2)">
        <div class="pill-num">3</div> ประวัติ
    </div>
    <div class="app-step-pill" data-step="3" onclick="mGoToStep(3)">
        <div class="pill-num">4</div> สมัคร
    </div>
</div>

<div class="app-content">
    {{-- Notices --}}
    <div class="app-notice warn">
        <i class="fas fa-exclamation-triangle"></i>
        <span>กรุณาตรวจสอบเลขประจำตัวประชาชนและข้อมูลสถานศึกษาให้ถูกต้องก่อนส่งใบสมัคร</span>
    </div>

    @if(!empty($periodStatus['message']))
    <div class="app-notice info">
        <i class="fas fa-calendar-check"></i>
        <span>{{ $periodStatus['message'] }}</span>
    </div>
    @endif

    {{-- Result moved outside mFormSection --}}

    {{-- FORM --}}
    <form id="mRegForm" novalidate>

        {{-- ===== STEP 1 ===== --}}
        <div class="app-form-section active" data-step="0">
            <div class="app-card">
                <div class="app-card-header"><i class="fas fa-user mr-1"></i> ข้อมูลผู้สมัคร</div>
                <div class="app-card-body">
                    <div class="app-form-row">
                        <div class="row-icon teal"><i class="fas fa-calendar"></i></div>
                        <div class="row-label">ปีการศึกษา <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="academic_year" value="{{ date('Y') + 543 }}" required>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="academic_year"></div>

                    <div class="app-form-row">
                        <div class="row-icon blue"><i class="fas fa-user-tag"></i></div>
                        <div class="row-label">คำนำหน้า <span class="req">*</span></div>
                        <div class="row-input">
                            <select name="title" required>
                                <option value="">เลือก</option>
                                <option value="เด็กชาย">เด็กชาย</option>
                                <option value="เด็กหญิง">เด็กหญิง</option>
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                            </select>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="title"></div>

                    <div class="app-form-row">
                        <div class="row-icon green"><i class="fas fa-id-badge"></i></div>
                        <div class="row-label">ชื่อ <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="first_name" required placeholder="กรอกชื่อ">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="first_name"></div>

                    <div class="app-form-row">
                        <div class="row-icon green"><i class="fas fa-id-badge"></i></div>
                        <div class="row-label">นามสกุล <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="last_name" required placeholder="กรอกนามสกุล">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="last_name"></div>

                    <div class="app-form-row">
                        <div class="row-icon orange"><i class="fas fa-birthday-cake"></i></div>
                        <div class="row-label">อายุ <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="number" name="age" min="1" max="120" required placeholder="ปี">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="age"></div>

                    <div class="app-form-row">
                        <div class="row-icon red"><i class="fas fa-id-card"></i></div>
                        <div class="row-label">บัตร ปชช. <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="national_id" required placeholder="X-XXXX-XXXXX-XX-X" maxlength="17" oninput="mFormatCitizenId(this)">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="national_id"></div>
                </div>
            </div>

            <div class="app-card">
                <div class="app-card-header"><i class="fas fa-calendar-alt mr-1"></i> วัน/เดือน/ปีเกิด</div>
                <div class="app-card-body">
                    <div class="app-form-row">
                        <div class="row-icon purple"><i class="fas fa-calendar-day"></i></div>
                        <div class="row-label">วันเกิด <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="number" name="birth_day" min="1" max="31" required placeholder="วัน">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="birth_day"></div>

                    <div class="app-form-row">
                        <div class="row-icon purple"><i class="fas fa-calendar-week"></i></div>
                        <div class="row-label">เดือนเกิด <span class="req">*</span></div>
                        <div class="row-input">
                            <select name="birth_month" required>
                                <option value="">เดือน</option>
                                <option value="1">มกราคม</option>
                                <option value="2">กุมภาพันธ์</option>
                                <option value="3">มีนาคม</option>
                                <option value="4">เมษายน</option>
                                <option value="5">พฤษภาคม</option>
                                <option value="6">มิถุนายน</option>
                                <option value="7">กรกฎาคม</option>
                                <option value="8">สิงหาคม</option>
                                <option value="9">กันยายน</option>
                                <option value="10">ตุลาคม</option>
                                <option value="11">พฤศจิกายน</option>
                                <option value="12">ธันวาคม</option>
                            </select>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="birth_month"></div>

                    <div class="app-form-row">
                        <div class="row-icon purple"><i class="fas fa-calendar"></i></div>
                        <div class="row-label">ปีเกิด (พ.ศ.) <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="number" name="birth_year_be" required placeholder="เช่น 2553">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="birth_year_be"></div>
                </div>
            </div>

            <div class="app-card">
                <div class="app-card-header"><i class="fas fa-phone-alt mr-1"></i> ช่องทางติดต่อ</div>
                <div class="app-card-body">
                    <div class="app-form-row">
                        <div class="row-icon green"><i class="fas fa-phone"></i></div>
                        <div class="row-label">โทรศัพท์ <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="tel" name="phone" required placeholder="0XXXXXXXXX">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="phone"></div>

                    <div class="app-form-row">
                        <div class="row-icon green"><i class="fab fa-line"></i></div>
                        <div class="row-label">LINE ID</div>
                        <div class="row-input">
                            <input type="text" name="line_id" placeholder="ไม่บังคับ">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="line_id"></div>
                </div>
            </div>
        </div>

        {{-- ===== STEP 2 ===== --}}
        <div class="app-form-section" data-step="1">
            <div class="app-card">
                <div class="app-card-header"><i class="fas fa-map-marker-alt mr-1"></i> ที่อยู่ปัจจุบัน</div>
                <div class="app-card-body">
                    <div class="app-form-row">
                        <div class="row-icon orange"><i class="fas fa-home"></i></div>
                        <div class="row-label">บ้านเลขที่ <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="house_number" required placeholder="เช่น 123/4 หมู่ 5">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="house_number"></div>

                    <div class="app-form-row">
                        <div class="row-icon teal"><i class="fas fa-city"></i></div>
                        <div class="row-label">จังหวัด <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="province" class="addr-input" list="mdl_province" data-target="district" required placeholder="พิมพ์จังหวัด" autocomplete="off">
                            <datalist id="mdl_province"></datalist>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="province"></div>

                    <div class="app-form-row">
                        <div class="row-icon teal"><i class="fas fa-building"></i></div>
                        <div class="row-label">อำเภอ <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="district" class="addr-input" list="mdl_district" data-target="sub_district" required disabled placeholder="เลือกจังหวัดก่อน" autocomplete="off">
                            <datalist id="mdl_district"></datalist>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="district"></div>

                    <div class="app-form-row">
                        <div class="row-icon teal"><i class="fas fa-map-pin"></i></div>
                        <div class="row-label">ตำบล <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="sub_district" class="addr-input" list="mdl_sub_district" data-target="postal_code" required disabled placeholder="เลือกอำเภอก่อน" autocomplete="off">
                            <datalist id="mdl_sub_district"></datalist>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="sub_district"></div>

                    <div class="app-form-row">
                        <div class="row-icon blue"><i class="fas fa-mail-bulk"></i></div>
                        <div class="row-label">ไปรษณีย์ <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="postal_code" class="addr-input" list="mdl_postal_code" required disabled placeholder="รหัสไปรษณีย์" autocomplete="off">
                            <datalist id="mdl_postal_code"></datalist>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="postal_code"></div>
                </div>
            </div>

            <div class="app-card">
                <div class="app-card-header"><i class="fas fa-graduation-cap mr-1"></i> การศึกษา</div>
                <div class="app-card-body">
                    <div class="app-form-row">
                        <div class="row-icon blue"><i class="fas fa-school"></i></div>
                        <div class="row-label">ระดับ <span class="req">*</span></div>
                        <div class="row-input">
                            <select name="education_level" required>
                                <option value="">เลือก</option>
                                <option value="ป.4">ป.4</option>
                                <option value="ป.5">ป.5</option>
                                <option value="ป.6">ป.6</option>
                                <option value="ม.1">ม.1</option>
                                <option value="ม.2">ม.2</option>
                                <option value="ม.3">ม.3</option>
                                <option value="ม.4">ม.4</option>
                                <option value="ม.5">ม.5</option>
                                <option value="ม.6">ม.6</option>
                                <option value="ปริญญาตรี">ปริญญาตรี</option>
                                <option value="ปริญญาโท">ปริญญาโท</option>
                                <option value="ปริญญาเอก">ปริญญาเอก</option>
                                <option value="ประชาชนทั่วไป">ประชาชนทั่วไป</option>
                            </select>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="education_level"></div>
                </div>
            </div>
        </div>

        {{-- ===== STEP 3 ===== --}}
        <div class="app-form-section" data-step="2">
            <div class="app-card">
                <div class="app-card-header"><i class="fas fa-history mr-1"></i> ประวัติธรรมศึกษาเดิม</div>
                <div class="app-card-body">
                    <div class="app-form-row checkbox-row" onclick="mTogglePrevCert()">
                        <div class="row-icon orange"><i class="fas fa-award"></i></div>
                        <div class="row-label" style="flex:1;">เคยสอบได้ธรรมศึกษา</div>
                        <input type="checkbox" id="mHasPreviousCert" name="has_previous_cert_check" value="1">
                    </div>
                </div>
            </div>

            <div class="app-conditional" id="mPrevCertFields">
                <div class="app-card">
                    <div class="app-card-header">รายละเอียดธรรมศึกษาเดิม</div>
                    <div class="app-card-body">
                        <div class="app-form-row">
                            <div class="row-icon orange"><i class="fas fa-layer-group"></i></div>
                            <div class="row-label">ชั้นเดิม <span class="req">*</span></div>
                            <div class="row-input">
                                <select name="previous_level" onchange="mOnPrevLevelChange(this)">
                                    <option value="">เลือก</option>
                                    <option value="ตรี">ธ.ศ.ตรี</option>
                                    <option value="โท">ธ.ศ.โท</option>
                                    <option value="เอก">ธ.ศ.เอก</option>
                                </select>
                            </div>
                        </div>
                        <div class="app-form-error" data-field="previous_level"></div>

                        <div class="app-form-row">
                            <div class="row-icon orange"><i class="fas fa-layer-group"></i></div>
                            <div class="row-label">ช่วงชั้น</div>
                            <div class="row-input">
                                <input type="text" name="previous_period" placeholder="เช่น ธรรมศึกษาชั้นตรี">
                            </div>
                        </div>

                        <div class="app-form-row">
                            <div class="row-icon blue"><i class="fas fa-certificate"></i></div>
                            <div class="row-label">เลขที่ ปณ.</div>
                            <div class="row-input">
                                <input type="text" name="previous_certificate_no" placeholder="ถ้ามี">
                            </div>
                        </div>

                        <div class="app-form-row">
                            <div class="row-icon purple"><i class="fas fa-calendar"></i></div>
                            <div class="row-label">ปีสอบได้</div>
                            <div class="row-input">
                                <input type="text" name="previous_year" placeholder="พ.ศ.">
                            </div>
                        </div>

                        <div class="app-form-row">
                            <div class="row-icon teal"><i class="fas fa-map"></i></div>
                            <div class="row-label">จังหวัด</div>
                            <div class="row-input">
                                <input type="text" name="previous_province" placeholder="จังหวัดที่สอบ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== STEP 4 ===== --}}
        <div class="app-form-section" data-step="3">
            <div class="app-card">
                <div class="app-card-header"><i class="fas fa-graduation-cap mr-1"></i> ชั้นสอบและสำนักเรียน</div>
                <div class="app-card-body">
                    <div class="app-form-row">
                        <div class="row-icon teal"><i class="fas fa-dharmachakra"></i></div>
                        <div class="row-label">ชั้นสมัคร <span class="req">*</span></div>
                        <div class="row-input">
                            <select name="target_level" id="mTargetLevel" required>
                                <option value="">เลือก</option>
                                <option value="ตรี">ธ.ศ.ตรี</option>
                                <option value="โท">ธ.ศ.โท</option>
                                <option value="เอก">ธ.ศ.เอก</option>
                            </select>
                        </div>
                    </div>
                    <div class="app-form-error" data-field="target_level"></div>

                    <div class="app-form-row">
                        <div class="row-icon green"><i class="fas fa-university"></i></div>
                        <div class="row-label">สำนักเรียน <span class="req">*</span></div>
                        <div class="row-input">
                            <input type="text" name="office_name" required placeholder="เช่น วัดสารอด">
                        </div>
                    </div>
                    <div class="app-form-error" data-field="office_name"></div>

                    <div class="app-form-row">
                        <div class="row-icon teal"><i class="fas fa-city"></i></div>
                        <div class="row-label">จ.สำนักเรียน</div>
                        <div class="row-input">
                            <input type="text" name="office_province" class="addr-input" list="mdl_office_province" data-target="office_district" placeholder="จังหวัด" autocomplete="off">
                            <datalist id="mdl_office_province"></datalist>
                        </div>
                    </div>

                    <div class="app-form-row">
                        <div class="row-icon teal"><i class="fas fa-building"></i></div>
                        <div class="row-label">อ.สำนักเรียน</div>
                        <div class="row-input">
                            <input type="text" name="office_district" class="addr-input" list="mdl_office_district" data-target="office_sub_district" disabled placeholder="อำเภอ" autocomplete="off">
                            <datalist id="mdl_office_district"></datalist>
                        </div>
                    </div>

                    <div class="app-form-row">
                        <div class="row-icon teal"><i class="fas fa-map-pin"></i></div>
                        <div class="row-label">ต.สำนักเรียน</div>
                        <div class="row-input">
                            <input type="text" name="office_sub_district" class="addr-input" list="mdl_office_sub_district" data-target="office_postal_code" disabled placeholder="ตำบล" autocomplete="off">
                            <datalist id="mdl_office_sub_district"></datalist>
                        </div>
                    </div>

                    <div class="app-form-row">
                        <div class="row-icon blue"><i class="fas fa-mail-bulk"></i></div>
                        <div class="row-label">ปณ.สำนักเรียน</div>
                        <div class="row-input">
                            <input type="text" name="office_postal_code" class="addr-input" list="mdl_office_postal_code" disabled placeholder="รหัสไปรษณีย์" autocomplete="off">
                            <datalist id="mdl_office_postal_code"></datalist>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-card">
                <div class="app-card-header"><i class="fas fa-share-alt mr-1"></i> ช่องทางที่รู้จัก</div>
                <div class="app-tags">
                    @foreach(['เว็บไซต์','เพจวัด','เพื่อนแนะนำ','กูเกิล','เฟซบุ๊ก','ยูทูป','ป้ายโฆษณา','ทวิตเตอร์','Discord'] as $src)
                    <label class="app-tag" onclick="this.classList.toggle('selected')">
                        <input type="checkbox" name="referral_sources[]" value="{{ $src }}">
                        {{ $src }}
                    </label>
                    @endforeach
                    <label class="app-tag" onclick="this.classList.toggle('selected'); mToggleReferralOther()">
                        <input type="checkbox" name="referral_sources[]" value="อื่นๆ">
                        อื่นๆ
                    </label>
                </div>
                <div id="mReferralOtherWrap">
                    <input type="text" name="referral_other" placeholder="ระบุช่องทางอื่นๆ">
                </div>
            </div>

            {{-- Review --}}
            <div class="app-review">
                <div class="app-review-title">
                    <i class="fas fa-clipboard-check"></i> ตรวจทานก่อนส่ง
                </div>
                <div class="app-review-row"><div class="app-review-label">ชื่อ-สกุล</div><div class="app-review-value" id="mRvName">-</div></div>
                <div class="app-review-row"><div class="app-review-label">ชั้นธรรมศึกษา</div><div class="app-review-value" id="mRvLevel">-</div></div>
                <div class="app-review-row"><div class="app-review-label">ปีการศึกษา</div><div class="app-review-value" id="mRvYear">-</div></div>
                <div class="app-review-row"><div class="app-review-label">เลขบัตร ปชช.</div><div class="app-review-value" id="mRvNid">-</div></div>
                <div class="app-review-row"><div class="app-review-label">เบอร์โทร</div><div class="app-review-value" id="mRvPhone">-</div></div>
                <div class="app-review-row"><div class="app-review-label">LINE ID</div><div class="app-review-value" id="mRvLine">-</div></div>
                <div class="app-review-row"><div class="app-review-label">สำนักเรียน</div><div class="app-review-value" id="mRvOffice">-</div></div>
            </div>
        </div>

    </form>
</div>

{{-- Bottom Action Bar --}}
<div class="app-bottom-bar">
    <button type="button" class="app-btn secondary" id="mBtnBack" onclick="mPrevStep()" style="display:none;">
        <i class="fas fa-chevron-left"></i> ย้อนกลับ
    </button>
    <button type="button" class="app-btn primary" id="mBtnNext" onclick="mNextStep()">
        ถัดไป <i class="fas fa-chevron-right"></i>
    </button>
    <button type="button" class="app-btn success-btn" id="mBtnSubmit" onclick="mSubmitForm()" style="display:none;">
        <i class="fas fa-paper-plane"></i> ส่งใบสมัคร
    </button>
</div>
</div>{{-- /mFormSection --}}

@endif

{{-- ===== Guide Modal (Mobile Bottom Sheet) ===== --}}
<div class="m-guide-overlay" id="mGuideOverlay" onclick="if(event.target===this)mCloseGuide()">
    <div class="m-guide-modal">
        <div class="m-guide-handle" onclick="mCloseGuide()"></div>
        <div class="m-guide-header">
            <h5><i class="fas fa-book-open mr-1"></i> คู่มือการสมัครสอบธรรมศึกษา</h5>
            <p>สำนักเรียนวัดสารอด &middot; ระบบรับสมัครออนไลน์</p>
        </div>
        <div class="m-guide-body">

            <div class="mg-info-card" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                <b style="color:#166534;"><i class="fas fa-info-circle mr-1"></i> ธรรมศึกษาคืออะไร?</b>
                <span style="color:#334155;">ธรรมศึกษา คือ หลักสูตรการศึกษาพระปริยัติธรรมสำหรับฆราวาส จัดโดยสำนักงานแม่กองธรรมสนามหลวง แบ่งเป็น 3 ชั้น: <strong>ตรี, โท, เอก</strong> ผู้สอบผ่านจะได้รับประกาศนียบัตรรับรอง</span>
            </div>

            <div class="mg-info-card" style="background:#eff6ff;border:1px solid #bfdbfe;">
                <b style="color:#1e40af;"><i class="fas fa-user-check mr-1"></i> คุณสมบัติผู้สมัคร</b>
                <ul style="color:#334155;">
                    <li>เป็นนักเรียน นักศึกษา หรือประชาชนทั่วไป ไม่จำกัดอายุ</li>
                    <li>ผู้สมัคร<strong>ครั้งแรก</strong>ต้องสมัครสอบ<strong>ชั้นตรี</strong>เท่านั้น</li>
                    <li>สมัครชั้นโท ต้องสอบผ่านชั้นตรีมาก่อน</li>
                    <li>สมัครชั้นเอก ต้องสอบผ่านชั้นโทมาก่อน</li>
                </ul>
            </div>

            <div class="mg-info-card" style="background:#fefce8;border:1px solid #fde68a;">
                <b style="color:#92400e;"><i class="fas fa-clipboard-list mr-1"></i> ข้อมูลที่ต้องเตรียม</b>
                <ul style="color:#334155;">
                    <li>เลขประจำตัวประชาชน 13 หลัก</li>
                    <li>ชื่อ-นามสกุล, วันเดือนปีเกิด, อายุ</li>
                    <li>เบอร์โทรศัพท์ที่ติดต่อได้</li>
                    <li>ที่อยู่ปัจจุบัน (จังหวัด อำเภอ ตำบล)</li>
                    <li>ชื่อสำนักเรียน/โรงเรียนที่สังกัด</li>
                    <li>ประกาศนียบัตรเดิม (ถ้ามี)</li>
                </ul>
            </div>

            <div style="font-weight:700;font-size:.95rem;color:var(--text);margin:20px 0 12px;padding-bottom:8px;border-bottom:2px solid var(--separator);">
                <i class="fas fa-shoe-prints mr-1" style="color:var(--primary);"></i> ขั้นตอนการสมัคร (5 ขั้นตอน)
            </div>

            <div class="mg-step">
                <div class="mg-step-num">1</div>
                <div class="mg-step-content">
                    <div class="mg-step-title"><i class="fas fa-graduation-cap"></i> เลือกระดับชั้น</div>
                    <div class="mg-step-desc">
                        <ul>
                            <li><strong>ชั้นธรรมศึกษา</strong> — ตรี/โท/เอก <span class="mg-req">จำเป็น</span></li>
                            <li><strong>ระดับชั้นการศึกษา</strong> — ประถม/มัธยม/อุดม <span class="mg-req">จำเป็น</span></li>
                        </ul>
                        <span class="mg-tag"><i class="fas fa-lightbulb mr-1"></i> สมัครครั้งแรกต้องเลือกชั้นตรี</span>
                    </div>
                </div>
            </div>

            <div class="mg-step">
                <div class="mg-step-num">2</div>
                <div class="mg-step-content">
                    <div class="mg-step-title"><i class="fas fa-user"></i> ข้อมูลผู้สมัคร</div>
                    <div class="mg-step-desc">
                        กรอกข้อมูลส่วนตัว:
                        <ul>
                            <li>ปีการศึกษา, คำนำหน้าชื่อ, ชื่อ-นามสกุล</li>
                            <li>เลขบัตรประชาชน 13 หลัก</li>
                            <li>วันเดือนปีเกิด, อายุ</li>
                            <li>เบอร์โทรศัพท์ <span class="mg-req">จำเป็น</span></li>
                            <li>LINE ID (ไม่บังคับ)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mg-step">
                <div class="mg-step-num">3</div>
                <div class="mg-step-content">
                    <div class="mg-step-title"><i class="fas fa-map-marker-alt"></i> ที่อยู่และการศึกษา</div>
                    <div class="mg-step-desc">
                        <ul>
                            <li>บ้านเลขที่, จังหวัด, อำเภอ, ตำบล, รหัสไปรษณีย์</li>
                            <li>ระดับการศึกษาปัจจุบัน</li>
                        </ul>
                        <span class="mg-tag"><i class="fas fa-magic mr-1"></i> เลือกจังหวัดแล้ว ช่องถัดไปแสดงอัตโนมัติ</span>
                    </div>
                </div>
            </div>

            <div class="mg-step">
                <div class="mg-step-num">4</div>
                <div class="mg-step-content">
                    <div class="mg-step-title"><i class="fas fa-history"></i> ประวัติธรรมศึกษาเดิม</div>
                    <div class="mg-step-desc">
                        <ul>
                            <li>เคยสอบผ่านมาก่อน → ติ๊กเครื่องหมาย แล้วกรอกข้อมูล</li>
                            <li>ไม่เคยสอบ → ข้ามไปขั้นตอนถัดไปได้เลย</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mg-step">
                <div class="mg-step-num">5</div>
                <div class="mg-step-content">
                    <div class="mg-step-title"><i class="fas fa-school"></i> สำนักเรียนและยืนยัน</div>
                    <div class="mg-step-desc">
                        <ul>
                            <li>กรอกชื่อสำนักเรียน/โรงเรียน <span class="mg-req">จำเป็น</span></li>
                            <li>ที่อยู่สำนักเรียน (ไม่บังคับ)</li>
                            <li>ตรวจทานข้อมูล แล้วกด<strong>"ยืนยันและส่งใบสมัคร"</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mg-info-card" style="background:#ecfdf5;border:2px solid #86efac;margin-top:16px;">
                <b style="color:#166534;"><i class="fas fa-check-circle mr-1"></i> เมื่อสมัครสำเร็จ</b>
                <ul style="color:#334155;">
                    <li>จดรหัสอ้างอิงไว้ตรวจสอบสถานะ</li>
                    <li>ปริ้นใบสมัคร / ดาวน์โหลด PDF, JPG ได้</li>
                    <li>เข้ากลุ่มไลน์ "ธรรมศึกษา|วัดสารอด" เพื่อติดตามข่าว</li>
                </ul>
            </div>

            <div class="mg-info-card" style="background:#eff6ff;border:1px solid #bfdbfe;margin-top:12px;">
                <b style="color:#1e40af;"><i class="fas fa-star mr-1"></i> เคล็ดลับ</b>
                <ul style="color:#334155;">
                    <li>กดหมายเลขขั้นตอนด้านบนเพื่อย้อนแก้ไขได้</li>
                    <li>ช่องที่มี <span style="color:#e74c3c;font-weight:700;">*</span> ต้องกรอก</li>
                    <li>เลขบัตรประชาชนจัดรูปแบบอัตโนมัติ</li>
                    <li>ที่อยู่ — พิมพ์ตัวอักษรแรกแล้วเลือกจากรายการ</li>
                </ul>
            </div>

            <div style="text-align:center;margin-top:16px;padding:14px;background:var(--bg);border-radius:12px;">
                <div style="font-weight:700;color:var(--text);font-size:.85rem;"><i class="fas fa-headset mr-1"></i> ต้องการความช่วยเหลือ?</div>
                <p style="font-size:.78rem;color:var(--text-secondary);margin:4px 0;">ติดต่อสำนักเรียนวัดสารอด หรือเข้ากลุ่มไลน์</p>
            </div>

            <div style="text-align:center;margin-top:18px;">
                <button type="button" onclick="mCloseGuide()" style="background:var(--primary);color:#fff;border:none;border-radius:12px;padding:14px 0;width:100%;font-size:.95rem;font-weight:700;cursor:pointer;font-family:'Sarabun',sans-serif;">
                    <i class="fas fa-times mr-1"></i> ปิดคำแนะนำ
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Footer + Theme Toggle --}}
<div class="app-footer">
    สำนักงานแม่กองธรรมสนามหลวง &middot; ระบบรับสมัครออนไลน์<br>
    &copy; {{ date('Y') + 543 }} วัดสารอด &middot;
    <a href="{{ route('landing') }}" style="color:var(--primary);">กลับหน้าหลัก</a>
    <br>
    <div class="theme-toggle">
        <a href="{{ route('public_exam_registration') }}"><i class="fas fa-desktop"></i> PC</a>
        <a href="{{ route('public_exam_registration') }}?theme=mobile" class="active"><i class="fas fa-mobile-alt"></i> Mobile</a>
    </div>
</div>

@if($periodStatus['status'] === 'open')
<script>
(function() {
    var currentStep = 0;
    var maxReached = 0;
    var submitting = false;

    window.mFormatCitizenId = function(el) {
        var raw = el.value.replace(/\D/g, '').substring(0, 13);
        var parts = [];
        if (raw.length > 0) parts.push(raw.substring(0, 1));
        if (raw.length > 1) parts.push(raw.substring(1, 5));
        if (raw.length > 5) parts.push(raw.substring(5, 10));
        if (raw.length > 10) parts.push(raw.substring(10, 12));
        if (raw.length > 12) parts.push(raw.substring(12, 13));
        el.value = parts.join('-');
    };

    function updateUI() {
        document.getElementById('mStepIndicator').textContent = (currentStep + 1) + '/4';

        document.querySelectorAll('.app-form-section').forEach(function(s) {
            s.classList.toggle('active', parseInt(s.dataset.step) === currentStep);
        });
        document.querySelectorAll('.app-step-pill').forEach(function(p) {
            var s = parseInt(p.dataset.step);
            p.classList.remove('active', 'completed');
            if (s === currentStep) p.classList.add('active');
            else if (s <= maxReached && s < currentStep) p.classList.add('completed');
        });

        document.getElementById('mBtnBack').style.display = currentStep > 0 ? 'flex' : 'none';
        document.getElementById('mBtnNext').style.display = currentStep < 3 ? 'flex' : 'none';
        document.getElementById('mBtnSubmit').style.display = currentStep === 3 ? 'flex' : 'none';

        if (currentStep === 3) mUpdateReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    window.mGoToStep = function(step) {
        if (step <= maxReached && step < currentStep) {
            currentStep = step;
            updateUI();
        }
    };

    window.mNextStep = function() {
        if (!mValidateStep(currentStep)) return;
        if (currentStep < 3) {
            currentStep++;
            if (currentStep > maxReached) maxReached = currentStep;
            updateUI();
        }
    };

    window.mPrevStep = function() {
        if (currentStep > 0) { currentStep--; updateUI(); }
    };

    function clearErrors() {
        document.querySelectorAll('.app-form-error').forEach(function(e) { e.classList.remove('show'); e.textContent = ''; });
        document.querySelectorAll('.app-form-row.invalid').forEach(function(r) { r.classList.remove('invalid'); });
    }

    function showErr(name, msg) {
        var el = document.querySelector('.app-form-error[data-field="' + name + '"]');
        if (el) { el.textContent = msg; el.classList.add('show'); }
        var inp = document.querySelector('[name="' + name + '"]');
        if (inp) { var row = inp.closest('.app-form-row'); if (row) row.classList.add('invalid'); }
    }

    function gv(name) {
        var el = document.querySelector('#mRegForm [name="' + name + '"]');
        return el ? el.value.trim() : '';
    }

    function mValidateStep(step) {
        clearErrors();
        var valid = true;
        function chk(n, m) { if (!gv(n)) { showErr(n, m); valid = false; } }

        if (step === 0) {
            chk('academic_year', 'กรุณากรอกปีการศึกษา');
            chk('title', 'กรุณาเลือกคำนำหน้า');
            chk('first_name', 'กรุณากรอกชื่อ');
            chk('last_name', 'กรุณากรอกนามสกุล');
            var nid = gv('national_id').replace(/\D/g, '');
            if (!nid) { showErr('national_id', 'กรุณากรอกเลขบัตร ปชช.'); valid = false; }
            else if (nid.length !== 13) { showErr('national_id', 'ต้องเป็น 13 หลัก'); valid = false; }
            var age = parseInt(gv('age'));
            if (!gv('age')) { showErr('age', 'กรุณากรอกอายุ'); valid = false; }
            else if (age < 1 || age > 120) { showErr('age', 'อายุ 1-120'); valid = false; }
            chk('birth_day', 'กรุณากรอกวันเกิด');
            chk('birth_month', 'กรุณาเลือกเดือน');
            chk('birth_year_be', 'กรุณากรอกปีเกิด');
            if (gv('birth_day') && gv('birth_month') && gv('birth_year_be')) {
                var d=parseInt(gv('birth_day')), m=parseInt(gv('birth_month')), y=parseInt(gv('birth_year_be'))-543;
                var td = new Date(y, m-1, d);
                if (td.getDate()!==d || td.getMonth()!==m-1) { showErr('birth_day','วันเกิดไม่ถูกต้อง'); valid=false; }
            }
            var ph = gv('phone');
            if (!ph) { showErr('phone', 'กรุณากรอกเบอร์โทร'); valid = false; }
            else if (!/^0\d{8,9}$/.test(ph)) { showErr('phone', 'เบอร์ไม่ถูกต้อง'); valid = false; }
        }
        if (step === 1) {
            chk('house_number', 'กรุณากรอกที่อยู่');
            chk('province', 'กรุณาเลือกจังหวัด');
            chk('district', 'กรุณาเลือกอำเภอ');
            chk('sub_district', 'กรุณาเลือกตำบล');
            var pc = gv('postal_code');
            if (!pc) { showErr('postal_code', 'กรุณากรอกรหัสไปรษณีย์'); valid = false; }
            else if (!/^\d{5}$/.test(pc)) { showErr('postal_code', 'ต้องเป็น 5 หลัก'); valid = false; }
            chk('education_level', 'กรุณาเลือกระดับการศึกษา');
        }
        if (step === 2) {
            if (document.getElementById('mHasPreviousCert').checked && !gv('previous_level')) {
                showErr('previous_level', 'กรุณาเลือกชั้นเดิม'); valid = false;
            }
        }
        if (step === 3) {
            chk('target_level', 'กรุณาเลือกชั้นสมัคร');
            chk('office_name', 'กรุณากรอกสำนักเรียน');
            var hasPrev = document.getElementById('mHasPreviousCert').checked;
            var target = gv('target_level');
            if (!hasPrev && target && target !== 'ตรี') {
                showErr('target_level', 'ผู้สมัครครั้งแรกได้เฉพาะชั้นตรี'); valid = false;
            }
            if (hasPrev && target) {
                var prev = gv('previous_level');
                if (target==='โท'&&prev!=='ตรี') { showErr('target_level','ต้องสอบผ่านชั้นตรีก่อน'); valid=false; }
                if (target==='เอก'&&prev!=='โท') { showErr('target_level','ต้องสอบผ่านชั้นโทก่อน'); valid=false; }
            }
        }
        return valid;
    }

    window.mTogglePrevCert = function() {
        var cb = document.getElementById('mHasPreviousCert');
        cb.checked = !cb.checked;
        document.getElementById('mPrevCertFields').classList.toggle('show', cb.checked);
        if (!cb.checked) {
            document.querySelector('#mRegForm [name="previous_level"]').value = '';
            document.getElementById('mTargetLevel').value = 'ตรี';
        }
    };

    window.mOnPrevLevelChange = function(sel) {
        var map = {'ตรี':'โท','โท':'เอก'};
        if (map[sel.value]) document.getElementById('mTargetLevel').value = map[sel.value];
    };

    window.mToggleReferralOther = function() {
        var cb = document.querySelector('[name="referral_sources[]"][value="อื่นๆ"]');
        document.getElementById('mReferralOtherWrap').classList.toggle('show', cb.checked);
    };

    function mUpdateReview() {
        document.getElementById('mRvName').textContent = gv('title') + ' ' + gv('first_name') + ' ' + gv('last_name');
        document.getElementById('mRvLevel').textContent = 'ธ.ศ.' + gv('target_level');
        document.getElementById('mRvYear').textContent = gv('academic_year');
        var nid = gv('national_id').replace(/\D/g, '');
        document.getElementById('mRvNid').textContent = nid.length >= 4 ? 'x-xxxx-xxxxx-xx-' + nid.substring(9) : nid;
        document.getElementById('mRvPhone').textContent = gv('phone') || '-';
        document.getElementById('mRvLine').textContent = gv('line_id') || '-';
        document.getElementById('mRvOffice').textContent = gv('office_name') || '-';
    }

    window.mSubmitForm = function() {
        if (submitting) return;
        if (!mValidateStep(3)) return;
        submitting = true;
        var btn = document.getElementById('mBtnSubmit');
        var btnBack = document.getElementById('mBtnBack');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';
        btnBack.disabled = true;

        var formData = {};
        document.querySelectorAll('#mRegForm input, #mRegForm select').forEach(function(inp) {
            if (!inp.name) return;
            if (inp.type === 'checkbox') {
                if (inp.name === 'has_previous_cert_check') return;
                if (inp.name === 'referral_sources[]') {
                    if (!formData.referral_sources) formData.referral_sources = [];
                    if (inp.checked) {
                        var val = inp.value;
                        if (val === 'อื่นๆ') {
                            var other = gv('referral_other');
                            formData.referral_sources.push(other ? 'อื่นๆ: ' + other : 'อื่นๆ');
                        } else {
                            formData.referral_sources.push(val);
                        }
                    }
                    return;
                }
            }
            if (inp.type === 'radio' && !inp.checked) return;
            formData[inp.name.replace('[]', '')] = inp.value;
        });
        formData.has_previous_cert = document.getElementById('mHasPreviousCert').checked ? 1 : 0;
        formData.national_id = (formData.national_id || '').replace(/\D/g, '');

        fetch('{{ route("public_exam_registration.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(function(res) { return res.json().then(function(data) { return { ok: res.ok, data: data }; }); })
        .then(function(result) {
            submitting = false;
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> ส่งใบสมัคร';
            btnBack.disabled = false;
            var msg = document.getElementById('mResultMsg');
            if (result.ok && result.data.success) {
                var titles = {'นาย':'นาย','นาง':'นาง','นางสาว':'นางสาว','เด็กชาย':'ด.ช.','เด็กหญิง':'ด.ญ.','พระ':'พระ','สามเณร':'สามเณร','แม่ชี':'แม่ชี'};
                var t = formData.title || '';
                var fullName = (titles[t] || t) + ' ' + (formData.first_name || '') + ' ' + (formData.last_name || '');
                var levelMap = {};
                document.querySelectorAll('#mRegForm [name="exam_level"]').forEach(function(o) { levelMap[o.value] = o.textContent || o.innerText; });
                var levelText = levelMap[formData.exam_level] || formData.exam_level || '-';
                var refCode = result.data.reference_code;

                msg.innerHTML = '<div class="app-result success"><i class="fas fa-check-circle"></i> <strong>' + result.data.message + '</strong><br>รหัสอ้างอิง: <span class="ref-code">' + refCode + '</span><br><small>กรุณาจดรหัสไว้ตรวจสอบสถานะ</small></div>' +
                    '<div class="m-receipt-actions">' +
                        '<button class="m-rbtn m-rbtn-print" onclick="mPrintReceipt()"><i class="fas fa-print"></i> ปริ้น</button>' +
                        '<button class="m-rbtn m-rbtn-pdf" onclick="mDownloadPDF()"><i class="fas fa-file-pdf"></i> PDF</button>' +
                        '<button class="m-rbtn m-rbtn-jpg" onclick="mDownloadJPG()"><i class="fas fa-file-image"></i> JPG</button>' +
                        '<a href="{{ route("landing") }}" class="m-rbtn m-rbtn-home"><i class="fas fa-home"></i> หน้าหลัก</a>' +
                    '</div>' +
                    '<div id="mReceiptCard" class="m-receipt-card">' +
                        '<div class="m-receipt-header">' +
                            '<img src="{{ asset("images/โลโก้วัดสารอด.jpg") }}" alt="โลโก้วัดสารอด">' +
                            '<img src="{{ asset("images/โลโก้สำนักเรียนวัดสารอด.jpg") }}" alt="โลโก้สำนักเรียน">' +
                            '<h6>ใบสมัครสอบธรรมศึกษา</h6>' +
                            '<small>สำนักเรียนวัดสารอด &middot; รหัส: ' + refCode + '</small>' +
                        '</div>' +
                        '<div class="m-receipt-row"><div class="m-receipt-label">ชื่อ-สกุล</div><div class="m-receipt-value">' + fullName + '</div></div>' +
                        '<div class="m-receipt-row"><div class="m-receipt-label">ชั้นธรรมศึกษา</div><div class="m-receipt-value">' + levelText + '</div></div>' +
                        '<div class="m-receipt-row"><div class="m-receipt-label">ปีการศึกษา</div><div class="m-receipt-value">' + (formData.academic_year || '-') + '</div></div>' +
                        '<div class="m-receipt-row"><div class="m-receipt-label">เลขบัตร ปชช.</div><div class="m-receipt-value">' + (formData.national_id || '-') + '</div></div>' +
                        '<div class="m-receipt-row"><div class="m-receipt-label">เบอร์โทร</div><div class="m-receipt-value">' + (formData.phone || '-') + '</div></div>' +
                        '<div class="m-receipt-row"><div class="m-receipt-label">LINE ID</div><div class="m-receipt-value">' + (formData.line_id || '-') + '</div></div>' +
                        '<div class="m-receipt-row"><div class="m-receipt-label">สำนักเรียน</div><div class="m-receipt-value">' + (formData.office_name || '-') + '</div></div>' +
                        '<div class="m-receipt-row"><div class="m-receipt-label">วันที่สมัคร</div><div class="m-receipt-value">' + new Date().toLocaleDateString('th-TH', {year:'numeric',month:'long',day:'numeric'}) + '</div></div>' +
                        '<div class="m-receipt-footer"><i class="fas fa-stamp"></i> เอกสารออกโดยระบบรับสมัครออนไลน์ วัดสารอด</div>' +
                    '</div>' +
                    '<div class="m-line-invite">' +
                        '<div class="m-line-invite-header"><i class="fab fa-line"></i> เข้ากลุ่มไลน์เพื่อติดตามข่าวสาร</div><br>' +
                        '<img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=https%3A%2F%2Fline.me%2FR%2Fti%2Fg%2FsErRNVzd8k" alt="LINE QR Code" width="160" height="160"><br>' +
                        '<div class="m-line-invite-group">ธรรมศึกษา|วัดสารอด</div>' +
                        '<div class="m-line-invite-desc">สแกน QR Code หรือกดปุ่มด้านล่าง</div>' +
                        '<a href="https://line.me/R/ti/g/sErRNVzd8k" target="_blank" class="m-line-invite-btn"><i class="fab fa-line"></i> เข้ากลุ่มไลน์</a>' +
                    '</div>';

                document.getElementById('mFormSection').style.display = 'none';
                document.getElementById('mResultArea').style.display = 'block';
                document.getElementById('mRegForm').reset();
                document.getElementById('mPrevCertFields').classList.remove('show');
                document.getElementById('mReferralOtherWrap').classList.remove('show');
                document.querySelectorAll('.app-tag.selected').forEach(function(t) { t.classList.remove('selected'); });
                document.querySelector('#mRegForm [name="academic_year"]').value = '{{ date("Y") + 543 }}';
                currentStep = 0; maxReached = 0;
                mResetAddressDropdowns();
                updateUI();
            } else {
                var errMsg = result.data.message || 'เกิดข้อผิดพลาด';
                if (result.data.errors) {
                    clearErrors();
                    var firstStep = null;
                    Object.keys(result.data.errors).forEach(function(key) {
                        showErr(key, Array.isArray(result.data.errors[key]) ? result.data.errors[key][0] : result.data.errors[key]);
                        var s = mGetFieldStep(key);
                        if (firstStep === null || s < firstStep) firstStep = s;
                    });
                    if (firstStep !== null && firstStep !== currentStep) { currentStep = firstStep; updateUI(); }
                    errMsg = 'กรุณาตรวจสอบข้อมูล';
                }
                msg.innerHTML = '<div class="app-result error"><i class="fas fa-exclamation-circle"></i> ' + errMsg + '</div>';
                document.getElementById('mResultArea').style.display = 'block';
            }
            msg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .catch(function() {
            submitting = false;
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> ส่งใบสมัคร';
            btnBack.disabled = false;
            document.getElementById('mResultMsg').innerHTML = '<div class="app-result error"><i class="fas fa-exclamation-circle"></i> บันทึกไม่สำเร็จ กรุณาลองใหม่</div>';
            document.getElementById('mResultArea').style.display = 'block';
        });
    };

    function mGetFieldStep(name) {
        var s0 = ['academic_year','title','first_name','last_name','national_id','age','birth_day','birth_month','birth_year_be','phone','line_id'];
        var s1 = ['house_number','province','district','sub_district','postal_code','education_level'];
        var s2 = ['has_previous_cert','previous_level','previous_period','previous_certificate_no','previous_year','previous_province'];
        if (s0.indexOf(name)!==-1) return 0;
        if (s1.indexOf(name)!==-1) return 1;
        if (s2.indexOf(name)!==-1) return 2;
        return 3;
    }

    // ── Address autocomplete ──
    var addrValid = {};

    function populateDL(id, items) {
        var dl = document.getElementById(id);
        dl.innerHTML = '';
        items.forEach(function(i) { var o = document.createElement('option'); o.value = i; dl.appendChild(o); });
        addrValid[id] = items;
    }

    function loadAddr(type, params, target, dlId) {
        var qs = 'type=' + type;
        Object.keys(params).forEach(function(k) { qs += '&' + k + '=' + encodeURIComponent(params[k]); });
        fetch('{{ route("api.thai_addresses") }}?' + qs)
            .then(function(r) { return r.json(); })
            .then(function(items) {
                populateDL(dlId, items);
                target.disabled = false;
                if (items.length === 1) { target.value = items[0]; target.dispatchEvent(new Event('input', { bubbles: true })); }
            });
    }

    function mResetAddressDropdowns() {
        ['district','sub_district','postal_code','office_district','office_sub_district','office_postal_code'].forEach(function(n) {
            var inp = document.querySelector('#mRegForm [name="' + n + '"]');
            if (inp) { inp.value = ''; inp.disabled = true; }
            var dl = document.getElementById('mdl_' + n);
            if (dl) dl.innerHTML = '';
        });
    }

    function isValid(dlId, val) { return addrValid[dlId] && addrValid[dlId].indexOf(val) !== -1; }

    function setupCasc(srcN, srcDl, tgtN, tgtDl, apiT, paramK, parentN) {
        var src = document.querySelector('#mRegForm [name="' + srcN + '"]');
        var tgt = document.querySelector('#mRegForm [name="' + tgtN + '"]');
        var timer;
        src.addEventListener('input', function() {
            var v = this.value.trim();
            clearTimeout(timer);
            timer = setTimeout(function() {
                if (!isValid(srcDl, v)) {
                    tgt.value = ''; tgt.disabled = true;
                    document.getElementById(tgtDl).innerHTML = '';
                    var nx = tgt.getAttribute('data-target');
                    if (nx) resetDown(nx);
                    return;
                }
                tgt.value = '';
                var nx = tgt.getAttribute('data-target');
                if (nx) resetDown(nx);
                var p = {}; p[paramK] = v;
                if (parentN) p.province = document.querySelector('#mRegForm [name="' + parentN + '"]').value;
                loadAddr(apiT, p, tgt, tgtDl);
            }, 150);
        });
    }

    function setupCascPostal(srcN, srcDl, tgtN, tgtDl, provN, distN) {
        var src = document.querySelector('#mRegForm [name="' + srcN + '"]');
        var tgt = document.querySelector('#mRegForm [name="' + tgtN + '"]');
        var timer;
        src.addEventListener('input', function() {
            var v = this.value.trim();
            clearTimeout(timer);
            timer = setTimeout(function() {
                if (!isValid(srcDl, v)) { tgt.value = ''; tgt.disabled = true; document.getElementById(tgtDl).innerHTML = ''; return; }
                tgt.value = '';
                loadAddr('postal_codes', {
                    province: document.querySelector('#mRegForm [name="' + provN + '"]').value,
                    district: document.querySelector('#mRegForm [name="' + distN + '"]').value,
                    sub_district: v
                }, tgt, tgtDl);
            }, 150);
        });
    }

    function resetDown(name) {
        var el = document.querySelector('#mRegForm [name="' + name + '"]');
        if (el) {
            el.value = ''; el.disabled = true;
            var dl = document.getElementById('mdl_' + name);
            if (dl) dl.innerHTML = '';
            var nx = el.getAttribute('data-target');
            if (nx) resetDown(nx);
        }
    }

    function initAddr() {
        fetch('{{ route("api.thai_addresses") }}?type=provinces')
            .then(function(r) { return r.json(); })
            .then(function(p) { populateDL('mdl_province', p); populateDL('mdl_office_province', p); });

        setupCasc('province','mdl_province','district','mdl_district','districts','province');
        setupCasc('district','mdl_district','sub_district','mdl_sub_district','sub_districts','district','province');
        setupCascPostal('sub_district','mdl_sub_district','postal_code','mdl_postal_code','province','district');

        setupCasc('office_province','mdl_office_province','office_district','mdl_office_district','districts','province');
        setupCasc('office_district','mdl_office_district','office_sub_district','mdl_office_sub_district','sub_districts','district','office_province');
        setupCascPostal('office_sub_district','mdl_office_sub_district','office_postal_code','mdl_office_postal_code','office_province','office_district');
    }

    initAddr();
    updateUI();

    window.mPrintReceipt = function() { window.print(); };

    window.mDownloadPDF = function() {
        var el = document.getElementById('mReceiptCard');
        html2canvas(el, { scale: 2, useCORS: true, backgroundColor: '#fff' }).then(function(canvas) {
            var imgData = canvas.toDataURL('image/jpeg', 0.95);
            var pdf = new jspdf.jsPDF('p', 'mm', 'a4');
            var w = pdf.internal.pageSize.getWidth() - 20;
            var h = (canvas.height * w) / canvas.width;
            pdf.addImage(imgData, 'JPEG', 10, 10, w, h);
            pdf.save('ใบสมัครสอบธรรมศึกษา.pdf');
        });
    };

    window.mDownloadJPG = function() {
        var el = document.getElementById('mReceiptCard');
        html2canvas(el, { scale: 2, useCORS: true, backgroundColor: '#fff' }).then(function(canvas) {
            var link = document.createElement('a');
            link.download = 'ใบสมัครสอบธรรมศึกษา.jpg';
            link.href = canvas.toDataURL('image/jpeg', 0.95);
            link.click();
        });
    };
    window.mOpenGuide = function() {
        document.getElementById('mGuideOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    window.mCloseGuide = function() {
        document.getElementById('mGuideOverlay').classList.remove('show');
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') window.mCloseGuide();
    });
})();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endif

</body>
</html>
