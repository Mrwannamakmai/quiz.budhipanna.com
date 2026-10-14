<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>สมัครสอบธรรมศึกษา – วัดสารอด</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary: #16a085;
            --primary-dark: #0e7c67;
            --secondary: #2c3e50;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --light-bg: #f8f9fa;
        }
        * { box-sizing: border-box; }
        body {
            background: #f0f2f5;
            min-height: 100vh;
            font-family: 'Sarabun', 'Segoe UI', sans-serif;
            margin: 0; padding: 0;
        }
        .reg-container {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .reg-header {
            background: var(--secondary);
            color: #fff;
            padding: 20px 28px;
            text-align: center;
        }
        .reg-header h4 { font-weight: 700; margin-bottom: 4px; font-size: 1.2rem; }
        .reg-header .subtitle { font-size: .85rem; opacity: .85; }
        .reg-header .brand { font-size: .75rem; opacity: .6; letter-spacing: 1px; margin-bottom: 6px; }

        /* Alerts */
        .alert-notice {
            background: #fff8e1; border: 1px solid #ffd54f; color: #795548;
            border-radius: 8px; padding: 12px 16px; font-size: .88rem; margin-bottom: 12px;
        }
        .alert-notice i { color: var(--warning); margin-right: 6px; }
        .alert-period {
            background: #e8f5e9; border: 1px solid #a5d6a7; color: #2e7d32;
            border-radius: 8px; padding: 10px 16px; font-size: .85rem; margin-bottom: 16px;
        }
        .alert-period i { margin-right: 6px; }

        /* Card body — centered content */
        .reg-body {
            background: #fff;
            padding: 28px 5% 32px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Stepper */
        .stepper { margin-bottom: 24px; }
        .stepper-info {
            font-size: .85rem; color: #666; margin-bottom: 8px;
        }
        .stepper-info strong { color: var(--secondary); }
        .progress { height: 6px; border-radius: 3px; background: #e9ecef; margin-bottom: 12px; }
        .progress-bar { background: var(--primary); transition: width .4s ease; border-radius: 3px; }
        .step-labels {
            display: flex; justify-content: space-between; gap: 4px;
        }
        .step-label {
            flex: 1; text-align: center; font-size: .75rem; color: #aaa;
            padding: 6px 4px; border-radius: 6px; cursor: default;
            transition: all .2s; position: relative;
        }
        .step-label.active { color: var(--primary); font-weight: 700; background: #f0fdfa; }
        .step-label.completed {
            color: var(--success); cursor: pointer; font-weight: 600;
        }
        .step-label.completed:hover { background: #f0fff4; }
        .step-label .step-num {
            display: inline-flex; align-items: center; justify-content: center;
            width: 22px; height: 22px; border-radius: 50%;
            background: #e9ecef; color: #999; font-size: .72rem; font-weight: 700;
            margin-bottom: 2px;
        }
        .step-label.active .step-num { background: var(--primary); color: #fff; }
        .step-label.completed .step-num { background: var(--success); color: #fff; }

        /* Form sections */
        .form-section { display: none; }
        .form-section.active { display: block; }
        .section-title {
            font-size: 1.05rem; font-weight: 700; color: var(--secondary);
            margin-bottom: 4px; padding-bottom: 8px; border-bottom: 2px solid #e9ecef;
        }
        .section-title i { color: var(--primary); margin-right: 6px; }
        .section-desc { font-size: .82rem; color: #888; margin-bottom: 16px; }

        /* Form fields */
        .form-group label { font-weight: 600; font-size: .88rem; color: #333; }
        .form-group label .req { color: var(--danger); }
        .form-control { font-size: .9rem; border-radius: 6px; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 .15rem rgba(22,160,133,.2); }
        .field-error { color: var(--danger); font-size: .8rem; margin-top: 3px; display: none; }
        .field-error.show { display: block; }
        .form-control.is-invalid { border-color: var(--danger); }

        .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0 12px; }

        /* Checkbox group */
        .check-group { display: flex; flex-wrap: wrap; gap: 8px; }
        .check-group label {
            font-weight: 400; font-size: .85rem; cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0;
            transition: all .2s; background: #f8fafc;
        }
        .check-group label:hover { border-color: #94a3b8; }
        .check-group input:checked + span { color: var(--primary); font-weight: 600; }
        .check-group label:has(input:checked) {
            border-color: var(--primary); background: #f0fdfa;
        }

        /* Tier/Education selection */
        .tier-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; }
        .tier-option { position: relative; cursor: pointer; }
        .tier-option input { position: absolute; opacity: 0; pointer-events: none; }
        .tier-card {
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            padding: 24px 16px; border-radius: 14px; border: 2px solid #e2e8f0;
            text-align: center; transition: all .25s; background: #f8fafc;
        }
        .tier-card .tier-icon { font-size: 2.2rem; }
        .tier-card .tier-name { font-size: 1rem; font-weight: 700; color: #334155; }
        .tier-card .tier-desc { font-size: .75rem; color: #94a3b8; }
        .tier-option:hover .tier-card { border-color: #94a3b8; transform: translateY(-2px); }
        .tier-option input:checked + .tier-card {
            border-color: var(--primary); background: #f0fdfa; box-shadow: 0 0 0 3px rgba(22,160,133,.15);
        }
        .tier-option.tri .tier-icon { color: #16a34a; }
        .tier-option.tho .tier-icon { color: #2563eb; }
        .tier-option.ek .tier-icon { color: #7c3aed; }
        .edu-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .edu-option { position: relative; cursor: pointer; }
        .edu-option input { position: absolute; opacity: 0; pointer-events: none; }
        .edu-card {
            display: flex; flex-direction: column; align-items: center; gap: 6px;
            padding: 18px 12px; border-radius: 12px; border: 2px solid #e2e8f0;
            text-align: center; transition: all .25s; background: #f8fafc;
        }
        .edu-card .edu-icon { font-size: 1.6rem; color: #64748b; }
        .edu-card .edu-name { font-size: .9rem; font-weight: 600; color: #334155; }
        .edu-option:hover .edu-card { border-color: #94a3b8; }
        .edu-option input:checked + .edu-card {
            border-color: var(--primary); background: #f0fdfa; box-shadow: 0 0 0 3px rgba(22,160,133,.15);
        }
        @media (max-width: 576px) {
            .tier-grid { grid-template-columns: 1fr; }
            .edu-grid { grid-template-columns: 1fr; }
        }

        /* Review card */
        .review-card {
            background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 10px;
            padding: 20px; margin-bottom: 16px;
        }
        .review-card h6 { font-weight: 700; color: var(--secondary); margin-bottom: 12px; }
        .review-row { display: flex; padding: 5px 0; font-size: .88rem; border-bottom: 1px solid #eee; }
        .review-row:last-child { border-bottom: none; }
        .review-label { width: 140px; color: #666; flex-shrink: 0; }
        .review-value { font-weight: 600; color: #333; }

        /* Buttons */
        .btn-nav { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid #eee; }
        .btn-back {
            background: #e9ecef; color: #555; border: none; border-radius: 8px;
            padding: 10px 24px; font-weight: 600; font-size: .9rem; cursor: pointer;
        }
        .btn-back:hover { background: #dee2e6; }
        .btn-next, .btn-submit {
            background: var(--primary); color: #fff; border: none; border-radius: 8px;
            padding: 10px 28px; font-weight: 600; font-size: .9rem; cursor: pointer;
        }
        .btn-next:hover, .btn-submit:hover { background: var(--primary-dark); }
        .btn-submit { background: var(--success); }
        .btn-submit:hover { background: #219a52; }
        .btn-next:disabled, .btn-submit:disabled { opacity: .6; cursor: not-allowed; }

        /* Result messages */
        .result-msg {
            padding: 16px 20px; border-radius: 10px; margin-bottom: 16px;
            font-size: .9rem; display: none;
        }
        .result-msg.success {
            background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; display: block;
        }
        .result-msg.error {
            background: #fde2e2; border: 1px solid #f5c6cb; color: #721c24; display: block;
        }
        .result-msg .ref-code {
            font-size: 1.1rem; font-weight: 700; color: var(--success);
            background: #fff; padding: 6px 14px; border-radius: 6px;
            display: inline-block; margin-top: 6px; letter-spacing: .5px;
        }
        .line-invite {
            background: #fff; border: 2px solid #06c755; border-radius: 14px;
            padding: 24px; text-align: center; margin-top: 18px;
        }
        .line-invite-header {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 1.05rem; font-weight: 700; color: #06c755; margin-bottom: 14px;
        }
        .line-invite-header i { font-size: 1.3rem; }
        .line-invite img { border-radius: 10px; border: 3px solid #e8f5e9; }
        .line-invite-group { font-weight: 700; font-size: .95rem; color: #333; margin-top: 12px; }
        .line-invite-desc { font-size: .82rem; color: #666; margin-top: 4px; }
        .line-invite-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: #06c755; color: #fff; border: none; border-radius: 8px;
            padding: 10px 24px; font-size: .9rem; font-weight: 600;
            text-decoration: none; margin-top: 14px; transition: background .2s;
        }
        .line-invite-btn:hover { background: #05b04c; color: #fff; text-decoration: none; }

        /* Receipt & Action Buttons */
        .receipt-actions {
            display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-top: 18px;
        }
        .receipt-actions .rbtn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 8px; font-size: .88rem; font-weight: 600;
            border: none; cursor: pointer; text-decoration: none; transition: all .2s;
        }
        .rbtn-print { background: #2563eb; color: #fff; }
        .rbtn-print:hover { background: #1d4ed8; color: #fff; }
        .rbtn-pdf { background: #dc2626; color: #fff; }
        .rbtn-pdf:hover { background: #b91c1c; color: #fff; }
        .rbtn-jpg { background: #f59e0b; color: #fff; }
        .rbtn-jpg:hover { background: #d97706; color: #fff; }
        .rbtn-home { background: #6b7280; color: #fff; }
        .rbtn-home:hover { background: #4b5563; color: #fff; text-decoration: none; }

        .receipt-card {
            background: #fff; border: 2px solid #e2e8f0; border-radius: 14px;
            padding: 28px 24px; margin-top: 18px;
        }
        .receipt-card-header {
            text-align: center; border-bottom: 2px solid #16a085; padding-bottom: 16px; margin-bottom: 16px;
        }
        .receipt-card-header img { height: 60px; margin: 0 8px; }
        .receipt-card-header h5 { margin: 10px 0 2px; font-weight: 700; color: #2c3e50; }
        .receipt-card-header small { color: #666; }
        .receipt-row { display: flex; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: .9rem; }
        .receipt-row:last-child { border-bottom: none; }
        .receipt-label { color: #64748b; width: 160px; flex-shrink: 0; }
        .receipt-value { font-weight: 600; color: #1e293b; flex: 1; }
        .receipt-footer { text-align: center; margin-top: 16px; padding-top: 14px; border-top: 2px solid #e2e8f0; font-size: .78rem; color: #94a3b8; }

        /* Guide button */
        .guide-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.18); color: #fff; border: 1.5px solid rgba(255,255,255,.4);
            border-radius: 8px; padding: 7px 18px; font-size: .82rem; font-weight: 600;
            cursor: pointer; transition: all .2s; margin-top: 10px;
            font-family: 'Sarabun', sans-serif;
        }
        .guide-btn:hover { background: rgba(255,255,255,.3); border-color: #fff; }
        .guide-btn i { font-size: .95rem; }

        /* Guide modal overlay */
        .guide-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.55);
            z-index: 9999; display: none; align-items: center; justify-content: center;
            padding: 20px;
        }
        .guide-overlay.show { display: flex; }
        .guide-modal {
            background: #fff; border-radius: 16px; width: 100%; max-width: 780px;
            max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,.3);
            position: relative;
        }
        .guide-modal-header {
            background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%);
            color: #fff; padding: 24px 28px; border-radius: 16px 16px 0 0;
            position: sticky; top: 0; z-index: 1;
        }
        .guide-modal-header h5 { font-weight: 700; margin: 0 0 4px; font-size: 1.15rem; }
        .guide-modal-header p { margin: 0; font-size: .82rem; opacity: .85; }
        .guide-close {
            position: absolute; top: 16px; right: 20px;
            background: rgba(255,255,255,.2); border: none; color: #fff;
            width: 32px; height: 32px; border-radius: 50%; font-size: 1.1rem;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .2s;
        }
        .guide-close:hover { background: rgba(255,255,255,.4); }
        .guide-modal-body { padding: 28px; }

        .guide-step {
            display: flex; gap: 16px; margin-bottom: 24px;
            padding-bottom: 24px; border-bottom: 1px solid #f1f5f9;
        }
        .guide-step:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .guide-step-num {
            flex-shrink: 0; width: 44px; height: 44px; border-radius: 50%;
            background: #f0fdfa; color: #16a085; font-weight: 800; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #a7f3d0;
        }
        .guide-step-content { flex: 1; }
        .guide-step-title { font-weight: 700; color: #1e293b; font-size: 1rem; margin-bottom: 6px; }
        .guide-step-title i { color: #16a085; margin-right: 6px; font-size: .9rem; }
        .guide-step-desc { font-size: .88rem; color: #475569; line-height: 1.65; }
        .guide-step-desc ul { margin: 8px 0 0; padding-left: 20px; }
        .guide-step-desc li { margin-bottom: 4px; }
        .guide-step-desc .guide-tag {
            display: inline-block; background: #fef3c7; color: #92400e;
            font-size: .75rem; font-weight: 600; padding: 2px 8px; border-radius: 4px;
        }
        .guide-step-desc .guide-req {
            display: inline-block; background: #fee2e2; color: #991b1b;
            font-size: .72rem; font-weight: 600; padding: 1px 6px; border-radius: 4px;
        }

        .guide-tips {
            background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px;
            padding: 18px 20px; margin-top: 8px;
        }
        .guide-tips-title {
            font-weight: 700; color: #1e40af; font-size: .9rem; margin-bottom: 8px;
        }
        .guide-tips-title i { margin-right: 6px; }
        .guide-tips ul { margin: 0; padding-left: 18px; font-size: .85rem; color: #334155; }
        .guide-tips li { margin-bottom: 5px; }

        .guide-contact {
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;
            padding: 18px 20px; margin-top: 20px; text-align: center;
        }
        .guide-contact-title { font-weight: 700; color: #334155; font-size: .9rem; margin-bottom: 6px; }
        .guide-contact p { font-size: .82rem; color: #64748b; margin: 3px 0; }

        @media (max-width: 576px) {
            .guide-modal-body { padding: 18px 16px; }
            .guide-step { flex-direction: column; gap: 8px; }
            .guide-step-num { width: 36px; height: 36px; font-size: .95rem; }
        }

        @media print {
            body { background: #fff !important; }
            .reg-hero, .reg-header, .alert-notice, .alert-period, .receipt-actions,
            .line-invite, .reg-footer, .guide-overlay, .guide-btn { display: none !important; }
            .reg-body { padding: 0 !important; }
            .receipt-card { border: 1px solid #ccc; box-shadow: none; }
        }

        /* Banner (closed states) */
        .closed-banner {
            background: #fff; border-radius: 0; padding: 48px 32px;
            text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,.08);
            max-width: 600px; margin: 60px auto;
            border-radius: 16px;
        }
        .closed-banner .icon { font-size: 3rem; margin-bottom: 12px; }
        .closed-banner.warn .icon { color: var(--warning); }
        .closed-banner.error .icon { color: var(--danger); }
        .closed-banner h5 { font-weight: 700; color: var(--secondary); }
        .closed-banner p { color: #666; font-size: .9rem; }

        /* Hero banner — full-width blurred background */
        .reg-hero {
            position: relative;
            overflow: hidden;
            padding: 48px 20px 36px;
            text-align: center;
            color: #fff;
        }
        .reg-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: url('{{ asset("images/หลวงพ่อรอด.jpg") }}') center/cover no-repeat;
            filter: blur(6px) brightness(.42);
            transform: scale(1.12);
            z-index: 0;
        }
        .reg-hero > * { position: relative; z-index: 1; }
        .reg-hero-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 24px;
            margin-bottom: 18px;
        }
        .reg-hero-logos img {
            width: 90px; height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,.65);
            box-shadow: 0 4px 20px rgba(0,0,0,.35);
        }
        .reg-hero-title {
            font-size: 1.6rem;
            font-weight: 800;
            text-shadow: 0 2px 10px rgba(0,0,0,.5);
            margin: 0;
            letter-spacing: .5px;
        }
        .reg-hero-subtitle {
            font-size: .95rem;
            opacity: .85;
            margin-top: 6px;
            text-shadow: 0 1px 6px rgba(0,0,0,.4);
        }

        /* Footer */
        .reg-footer {
            text-align: center; color: #999;
            font-size: .75rem; margin-top: 16px; padding: 16px;
            background: #e8eaed;
        }
        .reg-footer a { color: #777; }

        /* Conditional fields */
        .conditional-fields { display: none; }
        .conditional-fields.show { display: block; }

        /* Other text input for referral */
        #referralOtherWrap { display: none; margin-top: 8px; }
        #referralOtherWrap.show { display: block; }

        @media (min-width: 769px) {
            .reg-hero { padding: 56px 40px 44px; }
            .reg-hero-logos img { width: 110px; height: 110px; border-width: 4px; }
            .reg-hero-title { font-size: 2rem; }
            .reg-hero-subtitle { font-size: 1.05rem; }
            .reg-body { padding: 36px 48px 40px; }
        }
        @media (max-width: 768px) {
            .reg-body { padding: 24px 20px 28px; }
            .reg-header { padding: 16px 20px; }
        }
        @media (max-width: 576px) {
            .reg-hero { padding: 28px 16px 22px; }
            .reg-hero-logos { gap: 14px; }
            .reg-hero-logos img { width: 60px; height: 60px; }
            .reg-hero-title { font-size: 1.15rem; }
            .reg-hero-subtitle { font-size: .8rem; }
            .reg-body { padding: 18px 16px 24px; }
            .reg-header { padding: 14px 16px; }
            .reg-header h4 { font-size: 1.05rem; }
            .form-row-2 { grid-template-columns: 1fr; }
            .form-row-3 { grid-template-columns: 1fr 1fr 1fr; }
            .step-label { font-size: .68rem; padding: 4px 2px; }
            .review-row { flex-direction: column; }
            .review-label { width: auto; margin-bottom: 2px; }
        }
    </style>
</head>
<body>

<div class="reg-container">
    @if($periodStatus['status'] !== 'open')
        {{-- Closed/Not Started/Expired Banner --}}
        <div class="closed-banner {{ in_array($periodStatus['status'], ['not_started']) ? 'warn' : 'error' }}">
            <div class="icon">
                @if($periodStatus['status'] === 'not_started')
                    <i class="fas fa-clock"></i>
                @else
                    <i class="fas fa-times-circle"></i>
                @endif
            </div>
            <h5>{{ $periodStatus['message'] }}</h5>
            <p>หากมีข้อสงสัย กรุณาติดต่อสำนักเรียนของท่าน</p>
            <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-sm mt-3">
                <i class="fas fa-home mr-1"></i> กลับหน้าหลัก
            </a>
        </div>
    @else
        {{-- Hero Banner --}}
        <div class="reg-hero">
            <div class="reg-hero-logos">
                <img src="{{ asset('images/โลโก้วัดสารอด.jpg') }}" alt="โลโก้วัดสารอด">
                <img src="{{ asset('images/โลโก้สำนักเรียนวัดสารอด.jpg') }}" alt="โลโก้สำนักเรียนวัดสารอด">
            </div>
            <h2 class="reg-hero-title">ระบบสมัครเรียนและสอบ</h2>
            <div class="reg-hero-subtitle">สำนักเรียนวัดสารอด &middot; ธรรมศึกษา</div>
        </div>

        {{-- Registration Form --}}
        <div class="reg-header">
            <div class="brand">SRD APP &middot; DHAMMA EDUCATION</div>
            <h4><i class="fas fa-clipboard-list mr-2"></i>สมัครสอบธรรมศึกษา</h4>
            <div class="subtitle">กรอกใบสมัครให้ครบถ้วน ระบบจะบันทึกข้อมูลโดยอัตโนมัติ</div>
            <button type="button" class="guide-btn" onclick="openGuide()">
                <i class="fas fa-question-circle"></i> คำแนะนำ
            </button>
        </div>

        <div class="reg-body">
            {{-- Notices --}}
            <div class="alert-notice">
                <i class="fas fa-exclamation-triangle"></i>
                กรุณาตรวจสอบเลขประจำตัวประชาชนและข้อมูลสถานศึกษาให้ถูกต้องก่อนส่งใบสมัคร
            </div>

            @if(!empty($periodStatus['message']))
            <div class="alert-period">
                <i class="fas fa-calendar-check"></i>
                {{ $periodStatus['message'] }}
            </div>
            @endif

            {{-- Result messages --}}
            <div id="resultMsg" role="status" aria-live="polite"></div>

            <div id="formSection">
            {{-- Stepper --}}
            <div class="stepper">
                <div class="stepper-info">
                    ขั้นตอน <strong id="stepCurrent">1</strong> จาก <strong>5</strong>
                    &mdash; <span id="stepTitle">เลือกระดับชั้น</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" id="progressBar" style="width:20%"></div>
                </div>
                <div class="step-labels">
                    <div class="step-label active" data-step="0" onclick="goToStep(0)">
                        <div class="step-num">1</div><br>ระดับชั้น
                    </div>
                    <div class="step-label" data-step="1" onclick="goToStep(1)">
                        <div class="step-num">2</div><br>ผู้สมัคร
                    </div>
                    <div class="step-label" data-step="2" onclick="goToStep(2)">
                        <div class="step-num">3</div><br>ที่อยู่
                    </div>
                    <div class="step-label" data-step="3" onclick="goToStep(3)">
                        <div class="step-num">4</div><br>ประวัติ
                    </div>
                    <div class="step-label" data-step="4" onclick="goToStep(4)">
                        <div class="step-num">5</div><br>สมัครสอบ
                    </div>
                </div>
            </div>

            {{-- FORM --}}
            <form id="regForm" novalidate>

                {{-- ===== STEP 1: เลือกระดับชั้น ===== --}}
                <div class="form-section active" data-step="0">
                    <div class="section-title"><i class="fas fa-graduation-cap"></i> เลือกระดับชั้นที่ต้องการสมัครสอบ</div>
                    <div class="section-desc">กรุณาเลือกชั้นธรรมศึกษาและระดับชั้นการศึกษา</div>

                    <div class="form-group">
                        <label>ชั้นธรรมศึกษาที่สมัคร <span class="req">*</span></label>
                        <div class="tier-grid">
                            <label class="tier-option tri">
                                <input type="radio" name="target_level" value="ตรี" required>
                                <div class="tier-card">
                                    <i class="fas fa-seedling tier-icon"></i>
                                    <div class="tier-name">ธ.ศ.ตรี</div>
                                    <div class="tier-desc">ชั้นตรี</div>
                                </div>
                            </label>
                            <label class="tier-option tho">
                                <input type="radio" name="target_level" value="โท">
                                <div class="tier-card">
                                    <i class="fas fa-book-reader tier-icon"></i>
                                    <div class="tier-name">ธ.ศ.โท</div>
                                    <div class="tier-desc">ชั้นโท</div>
                                </div>
                            </label>
                            <label class="tier-option ek">
                                <input type="radio" name="target_level" value="เอก">
                                <div class="tier-card">
                                    <i class="fas fa-crown tier-icon"></i>
                                    <div class="tier-name">ธ.ศ.เอก</div>
                                    <div class="tier-desc">ชั้นเอก</div>
                                </div>
                            </label>
                        </div>
                        <div class="field-error" data-field="target_level"></div>
                    </div>

                    <div class="form-group">
                        <label>ระดับชั้นการศึกษา <span class="req">*</span></label>
                        <div class="edu-grid">
                            <label class="edu-option">
                                <input type="radio" name="education_class" value="ประถม" required>
                                <div class="edu-card">
                                    <i class="fas fa-child edu-icon"></i>
                                    <div class="edu-name">ประถม</div>
                                </div>
                            </label>
                            <label class="edu-option">
                                <input type="radio" name="education_class" value="มัธยม">
                                <div class="edu-card">
                                    <i class="fas fa-user-graduate edu-icon"></i>
                                    <div class="edu-name">มัธยม</div>
                                </div>
                            </label>
                            <label class="edu-option">
                                <input type="radio" name="education_class" value="อุดม">
                                <div class="edu-card">
                                    <i class="fas fa-university edu-icon"></i>
                                    <div class="edu-name">อุดม</div>
                                </div>
                            </label>
                        </div>
                        <div class="field-error" data-field="education_class"></div>
                    </div>
                </div>

                {{-- ===== STEP 2: ข้อมูลผู้สมัคร ===== --}}
                <div class="form-section" data-step="1">
                    <div class="section-title"><i class="fas fa-user"></i> ข้อมูลผู้สมัคร</div>
                    <div class="section-desc">กรอกข้อมูลส่วนตัวของผู้สมัครสอบ</div>

                    <div class="form-group">
                        <label>ปีการศึกษา (พ.ศ.) <span class="req">*</span></label>
                        <input type="text" name="academic_year" class="form-control"
                               value="{{ date('Y') + 543 }}" required>
                        <div class="field-error" data-field="academic_year"></div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>คำนำหน้าชื่อ <span class="req">*</span></label>
                            <select name="title" class="form-control" required>
                                <option value="">-- เลือก --</option>
                                <option value="เด็กชาย">เด็กชาย</option>
                                <option value="เด็กหญิง">เด็กหญิง</option>
                                <option value="นาย">นาย</option>
                                <option value="นาง">นาง</option>
                                <option value="นางสาว">นางสาว</option>
                            </select>
                            <div class="field-error" data-field="title"></div>
                        </div>
                        <div class="form-group">
                            <label>อายุ (ปี) <span class="req">*</span></label>
                            <input type="number" name="age" class="form-control" min="1" max="120" required placeholder="เช่น 15">
                            <div class="field-error" data-field="age"></div>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>ชื่อ <span class="req">*</span></label>
                            <input type="text" name="first_name" class="form-control" required placeholder="กรอกชื่อ">
                            <div class="field-error" data-field="first_name"></div>
                        </div>
                        <div class="form-group">
                            <label>นามสกุล <span class="req">*</span></label>
                            <input type="text" name="last_name" class="form-control" required placeholder="กรอกนามสกุล">
                            <div class="field-error" data-field="last_name"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>เลขประจำตัวประชาชน <span class="req">*</span></label>
                        <input type="text" name="national_id" class="form-control" required
                               placeholder="X-XXXX-XXXXX-XX-X" maxlength="17"
                               oninput="formatCitizenId(this)">
                        <div class="field-error" data-field="national_id"></div>
                    </div>

                    <div class="form-row-3">
                        <div class="form-group">
                            <label>วันเกิด <span class="req">*</span></label>
                            <input type="number" name="birth_day" class="form-control" min="1" max="31" required placeholder="วัน">
                            <div class="field-error" data-field="birth_day"></div>
                        </div>
                        <div class="form-group">
                            <label>เดือนเกิด <span class="req">*</span></label>
                            <select name="birth_month" class="form-control" required>
                                <option value="">-- เดือน --</option>
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
                            <div class="field-error" data-field="birth_month"></div>
                        </div>
                        <div class="form-group">
                            <label>ปีเกิด (พ.ศ.) <span class="req">*</span></label>
                            <input type="number" name="birth_year_be" class="form-control" required placeholder="เช่น 2553">
                            <div class="field-error" data-field="birth_year_be"></div>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>เบอร์โทรศัพท์ <span class="req">*</span></label>
                            <input type="tel" name="phone" class="form-control" required placeholder="0XXXXXXXXX">
                            <div class="field-error" data-field="phone"></div>
                        </div>
                        <div class="form-group">
                            <label>LINE ID</label>
                            <input type="text" name="line_id" class="form-control" placeholder="(ไม่บังคับ)">
                            <div class="field-error" data-field="line_id"></div>
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 3: ที่อยู่ ===== --}}
                <div class="form-section" data-step="2">
                    <div class="section-title"><i class="fas fa-map-marker-alt"></i> ที่อยู่</div>
                    <div class="section-desc">กรอกที่อยู่ปัจจุบัน</div>

                    <div class="form-group">
                        <label>บ้านเลขที่ / ที่อยู่ <span class="req">*</span></label>
                        <input type="text" name="house_number" class="form-control" required placeholder="เช่น 123/4 หมู่ 5">
                        <div class="field-error" data-field="house_number"></div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>จังหวัด <span class="req">*</span></label>
                            <input type="text" name="province" class="form-control addr-input" list="dl_province" data-target="district" required placeholder="พิมพ์ชื่อจังหวัด" autocomplete="off">
                            <datalist id="dl_province"></datalist>
                            <div class="field-error" data-field="province"></div>
                        </div>
                        <div class="form-group">
                            <label>อำเภอ/เขต <span class="req">*</span></label>
                            <input type="text" name="district" class="form-control addr-input" list="dl_district" data-target="sub_district" required disabled placeholder="พิมพ์ชื่ออำเภอ" autocomplete="off">
                            <datalist id="dl_district"></datalist>
                            <div class="field-error" data-field="district"></div>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>ตำบล/แขวง <span class="req">*</span></label>
                            <input type="text" name="sub_district" class="form-control addr-input" list="dl_sub_district" data-target="postal_code" required disabled placeholder="พิมพ์ชื่อตำบล" autocomplete="off">
                            <datalist id="dl_sub_district"></datalist>
                            <div class="field-error" data-field="sub_district"></div>
                        </div>
                        <div class="form-group">
                            <label>รหัสไปรษณีย์ <span class="req">*</span></label>
                            <input type="text" name="postal_code" class="form-control addr-input" list="dl_postal_code" required disabled placeholder="รหัสไปรษณีย์" autocomplete="off">
                            <datalist id="dl_postal_code"></datalist>
                            <div class="field-error" data-field="postal_code"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>ระดับการศึกษาปัจจุบัน <span class="req">*</span></label>
                        <select name="education_level" class="form-control" required>
                            <option value="">-- เลือก --</option>
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
                        <div class="field-error" data-field="education_level"></div>
                    </div>
                </div>

                {{-- ===== STEP 4: ประวัติธรรมศึกษาเดิม ===== --}}
                <div class="form-section" data-step="3">
                    <div class="section-title"><i class="fas fa-history"></i> ประวัติธรรมศึกษาเดิม</div>
                    <div class="section-desc">หากเคยสอบผ่านธรรมศึกษามาก่อน กรุณาระบุข้อมูล</div>

                    <div class="form-group">
                        <label style="cursor:pointer;">
                            <input type="checkbox" id="hasPreviousCert" name="has_previous_cert_check" value="1"
                                   onchange="togglePrevCert(this)">
                            <strong>เคยสอบได้ธรรมศึกษามาก่อน</strong>
                        </label>
                    </div>

                    <div class="conditional-fields" id="prevCertFields">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>ชั้นเดิมที่สอบได้ <span class="req">*</span></label>
                                <select name="previous_level" class="form-control" onchange="onPrevLevelChange(this)">
                                    <option value="">-- เลือก --</option>
                                    <option value="ตรี">ธ.ศ.ตรี</option>
                                    <option value="โท">ธ.ศ.โท</option>
                                    <option value="เอก">ธ.ศ.เอก</option>
                                </select>
                                <div class="field-error" data-field="previous_level"></div>
                            </div>
                            <div class="form-group">
                                <label>ช่วงชั้นเดิม</label>
                                <input type="text" name="previous_period" class="form-control" placeholder="เช่น ธรรมศึกษาชั้นตรี">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label>เลขที่ประกาศนียบัตรเดิม</label>
                                <input type="text" name="previous_certificate_no" class="form-control" placeholder="(ถ้ามี)">
                            </div>
                            <div class="form-group">
                                <label>ปีที่สอบได้ (พ.ศ.)</label>
                                <input type="text" name="previous_year" class="form-control" placeholder="เช่น 2567">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>จังหวัดที่สอบได้เดิม</label>
                            <input type="text" name="previous_province" class="form-control" placeholder="เช่น กรุงเทพมหานคร">
                        </div>
                    </div>
                </div>

                {{-- ===== STEP 5: สำนักเรียนและยืนยัน ===== --}}
                <div class="form-section" data-step="4">
                    <div class="section-title"><i class="fas fa-school"></i> สำนักเรียนและยืนยัน</div>
                    <div class="section-desc">ระบุสำนักเรียนและตรวจทานข้อมูลก่อนส่งใบสมัคร</div>

                    <div class="form-group">
                        <label>สำนักเรียน/โรงเรียน <span class="req">*</span></label>
                        <input type="text" name="office_name" class="form-control" required placeholder="เช่น วัดสารอด">
                        <div class="field-error" data-field="office_name"></div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>จังหวัดสำนักเรียน</label>
                            <input type="text" name="office_province" class="form-control addr-input" list="dl_office_province" data-target="office_district" placeholder="พิมพ์ชื่อจังหวัด" autocomplete="off">
                            <datalist id="dl_office_province"></datalist>
                        </div>
                        <div class="form-group">
                            <label>อำเภอ/เขต สำนักเรียน</label>
                            <input type="text" name="office_district" class="form-control addr-input" list="dl_office_district" data-target="office_sub_district" disabled placeholder="พิมพ์ชื่ออำเภอ" autocomplete="off">
                            <datalist id="dl_office_district"></datalist>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>ตำบล/แขวง สำนักเรียน</label>
                            <input type="text" name="office_sub_district" class="form-control addr-input" list="dl_office_sub_district" data-target="office_postal_code" disabled placeholder="พิมพ์ชื่อตำบล" autocomplete="off">
                            <datalist id="dl_office_sub_district"></datalist>
                        </div>
                        <div class="form-group">
                            <label>รหัสไปรษณีย์ สำนักเรียน</label>
                            <input type="text" name="office_postal_code" class="form-control addr-input" list="dl_office_postal_code" disabled placeholder="รหัสไปรษณีย์" autocomplete="off">
                            <datalist id="dl_office_postal_code"></datalist>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>ช่องทางที่ได้รู้จัก</label>
                        <div class="check-group">
                            @foreach(['เว็บไซต์','เพจวัด','เพื่อนแนะนำ','กูเกิล','เฟซบุ๊ก','ยูทูป','ป้ายโฆษณา','ทวิตเตอร์','Discord'] as $src)
                            <label>
                                <input type="checkbox" name="referral_sources[]" value="{{ $src }}">
                                <span>{{ $src }}</span>
                            </label>
                            @endforeach
                            <label>
                                <input type="checkbox" name="referral_sources[]" value="อื่นๆ"
                                       onchange="toggleReferralOther(this)">
                                <span>อื่นๆ</span>
                            </label>
                        </div>
                        <div id="referralOtherWrap">
                            <input type="text" name="referral_other" class="form-control mt-2" placeholder="ระบุช่องทางอื่นๆ">
                        </div>
                    </div>

                    <hr>

                    {{-- Review card --}}
                    <div class="review-card" id="reviewCard">
                        <h6><i class="fas fa-clipboard-check mr-1"></i> ตรวจทานก่อนส่ง</h6>
                        <div class="review-row">
                            <div class="review-label">ชื่อ-สกุล</div>
                            <div class="review-value" id="rvName">-</div>
                        </div>
                        <div class="review-row">
                            <div class="review-label">ชั้นธรรมศึกษา</div>
                            <div class="review-value" id="rvLevel">-</div>
                        </div>
                        <div class="review-row">
                            <div class="review-label">ปีการศึกษา</div>
                            <div class="review-value" id="rvYear">-</div>
                        </div>
                        <div class="review-row">
                            <div class="review-label">เลขบัตรประชาชน</div>
                            <div class="review-value" id="rvNid">-</div>
                        </div>
                        <div class="review-row">
                            <div class="review-label">เบอร์โทร</div>
                            <div class="review-value" id="rvPhone">-</div>
                        </div>
                        <div class="review-row">
                            <div class="review-label">LINE ID</div>
                            <div class="review-value" id="rvLine">-</div>
                        </div>
                        <div class="review-row">
                            <div class="review-label">สำนักเรียน</div>
                            <div class="review-value" id="rvOffice">-</div>
                        </div>
                    </div>
                </div>

            </form>

            {{-- Navigation buttons --}}
            <div class="btn-nav">
                <button type="button" class="btn-back" id="btnBack" onclick="prevStep()" style="visibility:hidden;">
                    <i class="fas fa-arrow-left mr-1"></i> ย้อนกลับ
                </button>
                <button type="button" class="btn-next" id="btnNext" onclick="nextStep()">
                    ถัดไป <i class="fas fa-arrow-right ml-1"></i>
                </button>
                <button type="button" class="btn-submit" id="btnSubmit" onclick="submitForm()" style="display:none;">
                    <i class="fas fa-paper-plane mr-1"></i> ยืนยันและส่งใบสมัคร
                </button>
            </div>

            <div class="text-center mt-3" style="font-size:.78rem;color:#999;">
                <i class="fas fa-database mr-1"></i> ข้อมูลจะถูกบันทึกเข้าสู่ระบบโดยอัตโนมัติ
            </div>
            </div>{{-- /formSection --}}
        </div>
    @endif
</div>

{{-- ===== Guide Modal ===== --}}
<div class="guide-overlay" id="guideOverlay" onclick="if(event.target===this)closeGuide()">
    <div class="guide-modal">
        <div class="guide-modal-header">
            <h5><i class="fas fa-book-open mr-2"></i>คู่มือแนะนำการสมัครเรียนและสอบธรรมศึกษา</h5>
            <p>สำนักเรียนวัดสารอด &middot; ระบบรับสมัครออนไลน์</p>
            <button type="button" class="guide-close" onclick="closeGuide()" aria-label="ปิด">&times;</button>
        </div>
        <div class="guide-modal-body">

            {{-- ข้อมูลเบื้องต้น --}}
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:18px 20px;margin-bottom:24px;">
                <div style="font-weight:700;color:#166534;font-size:.95rem;margin-bottom:6px;">
                    <i class="fas fa-info-circle mr-1"></i> ธรรมศึกษาคืออะไร?
                </div>
                <p style="font-size:.85rem;color:#334155;margin:0;line-height:1.7;">
                    ธรรมศึกษา คือ หลักสูตรการศึกษาพระปริยัติธรรมแผนกธรรมสำหรับฆราวาส (ผู้ที่มิใช่พระภิกษุสามเณร)
                    จัดโดยสำนักงานแม่กองธรรมสนามหลวง แบ่งเป็น 3 ชั้น ได้แก่ <strong>ธรรมศึกษาชั้นตรี ชั้นโท และชั้นเอก</strong>
                    ผู้สอบผ่านจะได้รับประกาศนียบัตรรับรองจากสำนักงานแม่กองธรรมสนามหลวง
                </p>
            </div>

            {{-- คุณสมบัติผู้สมัคร --}}
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:18px 20px;margin-bottom:24px;">
                <div style="font-weight:700;color:#1e40af;font-size:.95rem;margin-bottom:8px;">
                    <i class="fas fa-user-check mr-1"></i> คุณสมบัติผู้สมัคร
                </div>
                <ul style="font-size:.85rem;color:#334155;margin:0;padding-left:18px;line-height:1.8;">
                    <li>เป็นนักเรียน นักศึกษา หรือประชาชนทั่วไป ไม่จำกัดอายุ</li>
                    <li>ผู้สมัคร<strong>ครั้งแรก</strong>ต้องสมัครสอบ<strong>ชั้นตรี</strong>เท่านั้น</li>
                    <li>ผู้สมัครชั้นโท ต้องสอบผ่านชั้นตรีมาก่อน</li>
                    <li>ผู้สมัครชั้นเอก ต้องสอบผ่านชั้นโทมาก่อน</li>
                    <li>ต้องมีเลขประจำตัวประชาชน 13 หลัก</li>
                </ul>
            </div>

            {{-- เอกสาร/ข้อมูลที่ต้องเตรียม --}}
            <div style="background:#fefce8;border:1px solid #fde68a;border-radius:12px;padding:18px 20px;margin-bottom:28px;">
                <div style="font-weight:700;color:#92400e;font-size:.95rem;margin-bottom:8px;">
                    <i class="fas fa-clipboard-list mr-1"></i> ข้อมูลที่ต้องเตรียม
                </div>
                <ul style="font-size:.85rem;color:#334155;margin:0;padding-left:18px;line-height:1.8;">
                    <li>เลขประจำตัวประชาชน 13 หลัก</li>
                    <li>ชื่อ-นามสกุล, วันเดือนปีเกิด, อายุ</li>
                    <li>เบอร์โทรศัพท์ที่ติดต่อได้</li>
                    <li>ที่อยู่ปัจจุบัน (จังหวัด อำเภอ ตำบล รหัสไปรษณีย์)</li>
                    <li>ระดับการศึกษาปัจจุบัน</li>
                    <li>ชื่อสำนักเรียน/โรงเรียนที่สังกัด</li>
                    <li>หากเคยสอบผ่านมาก่อน — เลขที่ประกาศนียบัตร, ปี พ.ศ., จังหวัดที่สอบ</li>
                </ul>
            </div>

            <h6 style="font-weight:700;color:#1e293b;font-size:1.05rem;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #e2e8f0;">
                <i class="fas fa-shoe-prints mr-2 text-success"></i>ขั้นตอนการสมัคร (5 ขั้นตอน)
            </h6>

            {{-- Step 1 --}}
            <div class="guide-step">
                <div class="guide-step-num">1</div>
                <div class="guide-step-content">
                    <div class="guide-step-title"><i class="fas fa-graduation-cap"></i> เลือกระดับชั้นที่ต้องการสมัครสอบ</div>
                    <div class="guide-step-desc">
                        เลือก 2 รายการ:
                        <ul>
                            <li><strong>ชั้นธรรมศึกษา</strong> — เลือก ธ.ศ.ตรี, ธ.ศ.โท หรือ ธ.ศ.เอก <span class="guide-req">จำเป็น</span></li>
                            <li><strong>ระดับชั้นการศึกษา</strong> — เลือก ประถม, มัธยม หรือ อุดม <span class="guide-req">จำเป็น</span></li>
                        </ul>
                        <span class="guide-tag"><i class="fas fa-lightbulb mr-1"></i> ผู้สมัครครั้งแรกต้องเลือกชั้นตรี</span>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="guide-step">
                <div class="guide-step-num">2</div>
                <div class="guide-step-content">
                    <div class="guide-step-title"><i class="fas fa-user"></i> ข้อมูลผู้สมัคร</div>
                    <div class="guide-step-desc">
                        กรอกข้อมูลส่วนตัว:
                        <ul>
                            <li><strong>ปีการศึกษา</strong> — ระบบกรอกให้อัตโนมัติ สามารถแก้ไขได้</li>
                            <li><strong>คำนำหน้าชื่อ</strong> — เด็กชาย, เด็กหญิง, นาย, นาง, นางสาว <span class="guide-req">จำเป็น</span></li>
                            <li><strong>ชื่อ-นามสกุล</strong> — กรอกเป็นภาษาไทย <span class="guide-req">จำเป็น</span></li>
                            <li><strong>เลขประจำตัวประชาชน</strong> — 13 หลัก ระบบจัดรูปแบบให้อัตโนมัติ <span class="guide-req">จำเป็น</span></li>
                            <li><strong>วันเดือนปีเกิด</strong> — ระบุวัน เดือน ปี พ.ศ. <span class="guide-req">จำเป็น</span></li>
                            <li><strong>อายุ</strong> — ระบุเป็นปี <span class="guide-req">จำเป็น</span></li>
                            <li><strong>เบอร์โทรศัพท์</strong> — ขึ้นต้นด้วย 0 ตามด้วยตัวเลข 8-9 หลัก <span class="guide-req">จำเป็น</span></li>
                            <li><strong>LINE ID</strong> — ไม่บังคับ แต่สะดวกในการติดต่อ</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="guide-step">
                <div class="guide-step-num">3</div>
                <div class="guide-step-content">
                    <div class="guide-step-title"><i class="fas fa-map-marker-alt"></i> ที่อยู่และการศึกษา</div>
                    <div class="guide-step-desc">
                        กรอกที่อยู่ปัจจุบัน:
                        <ul>
                            <li><strong>บ้านเลขที่/ที่อยู่</strong> — เช่น 123/4 หมู่ 5 <span class="guide-req">จำเป็น</span></li>
                            <li><strong>จังหวัด</strong> — พิมพ์ชื่อแล้วเลือกจากรายการ ระบบจะแสดงอำเภอ ตำบล และรหัสไปรษณีย์ให้อัตโนมัติ <span class="guide-req">จำเป็น</span></li>
                            <li><strong>อำเภอ/เขต, ตำบล/แขวง, รหัสไปรษณีย์</strong> — เลือกจากรายการที่ระบบแสดง <span class="guide-req">จำเป็น</span></li>
                            <li><strong>ระดับการศึกษาปัจจุบัน</strong> — เลือกชั้นเรียนปัจจุบัน เช่น ป.4, ม.1, ปริญญาตรี <span class="guide-req">จำเป็น</span></li>
                        </ul>
                        <span class="guide-tag"><i class="fas fa-magic mr-1"></i> เลือกจังหวัดแล้ว ช่องถัดไปจะแสดงตัวเลือกอัตโนมัติ</span>
                    </div>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="guide-step">
                <div class="guide-step-num">4</div>
                <div class="guide-step-content">
                    <div class="guide-step-title"><i class="fas fa-history"></i> ประวัติธรรมศึกษาเดิม</div>
                    <div class="guide-step-desc">
                        <ul>
                            <li>หากเคยสอบผ่านธรรมศึกษามาก่อน ให้ <strong>ติ๊กเครื่องหมาย</strong> "เคยสอบได้ธรรมศึกษามาก่อน"</li>
                            <li>จากนั้นกรอก: <strong>ชั้นเดิมที่สอบได้</strong> (ตรี/โท/เอก), เลขที่ประกาศนียบัตร, ปี พ.ศ. ที่สอบได้, จังหวัดที่สอบ</li>
                            <li>หาก<strong>ไม่เคยสอบ</strong>มาก่อน ไม่ต้องกรอกอะไร กดถัดไปได้เลย</li>
                        </ul>
                        <span class="guide-tag"><i class="fas fa-lightbulb mr-1"></i> ระบบจะแนะนำชั้นที่ควรสมัครให้อัตโนมัติ เช่น เคยสอบได้ตรี → แนะนำสมัครโท</span>
                    </div>
                </div>
            </div>

            {{-- Step 5 --}}
            <div class="guide-step">
                <div class="guide-step-num">5</div>
                <div class="guide-step-content">
                    <div class="guide-step-title"><i class="fas fa-school"></i> สำนักเรียนและยืนยัน</div>
                    <div class="guide-step-desc">
                        <ul>
                            <li><strong>สำนักเรียน/โรงเรียน</strong> — กรอกชื่อสถานศึกษาที่สังกัด เช่น วัดสารอด <span class="guide-req">จำเป็น</span></li>
                            <li><strong>จังหวัด, อำเภอ, ตำบล, รหัสไปรษณีย์ สำนักเรียน</strong> — เลือกจากรายการ (ไม่บังคับ)</li>
                            <li><strong>ช่องทางที่ได้รู้จัก</strong> — เลือกได้หลายช่องทาง (ไม่บังคับ)</li>
                            <li><strong>ตรวจทานข้อมูล</strong> — ตรวจสอบความถูกต้องจากกรอบ "ตรวจทานก่อนส่ง"</li>
                            <li>กดปุ่ม <strong>"ยืนยันและส่งใบสมัคร"</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- หลังสมัครสำเร็จ --}}
            <div style="background:#ecfdf5;border:2px solid #86efac;border-radius:12px;padding:20px;margin-top:28px;">
                <div style="font-weight:700;color:#166534;font-size:1rem;margin-bottom:10px;">
                    <i class="fas fa-check-circle mr-1"></i> เมื่อสมัครสำเร็จ
                </div>
                <ul style="font-size:.85rem;color:#334155;margin:0;padding-left:18px;line-height:1.8;">
                    <li>ระบบจะแสดง<strong>รหัสอ้างอิง</strong> — กรุณาจดบันทึกไว้เพื่อตรวจสอบสถานะ</li>
                    <li>สามารถ<strong>ปริ้นใบสมัคร</strong>, <strong>ดาวน์โหลด PDF</strong> หรือ <strong>ดาวน์โหลด JPG</strong> ได้ทันที</li>
                    <li>สแกน QR Code หรือกดปุ่มเพื่อเข้ากลุ่มไลน์ <strong>"ธรรมศึกษา|วัดสารอด"</strong> สำหรับติดตามข่าวสาร</li>
                </ul>
            </div>

            {{-- เคล็ดลับ --}}
            <div class="guide-tips" style="margin-top:20px;">
                <div class="guide-tips-title"><i class="fas fa-star"></i> เคล็ดลับการกรอกฟอร์ม</div>
                <ul>
                    <li>สามารถกดที่<strong>หมายเลขขั้นตอน</strong>ด้านบนเพื่อย้อนกลับไปแก้ไขข้อมูลในขั้นตอนก่อนหน้าได้</li>
                    <li>ช่องที่มีเครื่องหมาย <span style="color:#e74c3c;font-weight:700;">*</span> คือช่องที่<strong>ต้องกรอก</strong></li>
                    <li>ระบบจะตรวจสอบความถูกต้องของข้อมูลทุกขั้นตอนก่อนไปขั้นถัดไป</li>
                    <li>เลขบัตรประชาชนจะถูกจัดรูปแบบ X-XXXX-XXXXX-XX-X อัตโนมัติ</li>
                    <li>ที่อยู่จังหวัด อำเภอ ตำบล — พิมพ์ตัวอักษรแรกแล้วเลือกจากรายการที่แสดง</li>
                    <li>หากมีปัญหา สามารถรีเฟรชหน้าเว็บแล้วกรอกใหม่ได้</li>
                </ul>
            </div>

            {{-- ติดต่อ --}}
            <div class="guide-contact">
                <div class="guide-contact-title"><i class="fas fa-headset mr-1"></i> ต้องการความช่วยเหลือ?</div>
                <p>ติดต่อสำนักเรียนวัดสารอด</p>
                <p><i class="fab fa-line mr-1" style="color:#06c755;"></i> เข้ากลุ่มไลน์ "ธรรมศึกษา|วัดสารอด" เพื่อสอบถามข้อมูลเพิ่มเติม</p>
            </div>

            {{-- ปุ่มปิด --}}
            <div style="text-align:center;margin-top:24px;">
                <button type="button" onclick="closeGuide()" style="background:#16a085;color:#fff;border:none;border-radius:10px;padding:12px 40px;font-size:.95rem;font-weight:700;cursor:pointer;font-family:'Sarabun',sans-serif;">
                    <i class="fas fa-times mr-1"></i> ปิดคำแนะนำ
                </button>
            </div>
        </div>
    </div>
</div>

<div class="reg-footer">
    สำนักงานแม่กองธรรมสนามหลวง &middot; ระบบรับสมัครออนไลน์<br>
    &copy; {{ date('Y') + 543 }} วัดสารอด &middot;
    <a href="{{ route('landing') }}">กลับหน้าหลัก</a>
    <br>
    <div style="display:inline-flex;background:#e5e5ea;border-radius:10px;overflow:hidden;margin-top:12px;">
        <a href="{{ route('public_exam_registration') }}" style="padding:8px 18px;font-size:.78rem;font-weight:600;text-decoration:none;background:var(--primary);color:#fff;border-radius:10px;"><i class="fas fa-desktop"></i> PC</a>
        <a href="{{ route('public_exam_registration') }}?theme=mobile" style="padding:8px 18px;font-size:.78rem;font-weight:600;text-decoration:none;color:#8e8e93;"><i class="fas fa-mobile-alt"></i> Mobile</a>
    </div>
</div>

@if($periodStatus['status'] === 'open')
<script>
(function() {
    var currentStep = 0;
    var maxReached = 0;
    var stepTitles = ['เลือกระดับชั้น', 'ข้อมูลผู้สมัคร', 'ที่อยู่และการศึกษา', 'ประวัติธรรมศึกษาเดิม', 'สำนักเรียนและยืนยัน'];
    var submitting = false;

    // ── Citizen ID formatter ──
    window.formatCitizenId = function(el) {
        var raw = el.value.replace(/\D/g, '').substring(0, 13);
        var parts = [];
        if (raw.length > 0) parts.push(raw.substring(0, 1));
        if (raw.length > 1) parts.push(raw.substring(1, 5));
        if (raw.length > 5) parts.push(raw.substring(5, 10));
        if (raw.length > 10) parts.push(raw.substring(10, 12));
        if (raw.length > 12) parts.push(raw.substring(12, 13));
        el.value = parts.join('-');
    };

    // ── Step navigation ──
    function updateStepper() {
        document.getElementById('stepCurrent').textContent = currentStep + 1;
        document.getElementById('stepTitle').textContent = stepTitles[currentStep];
        document.getElementById('progressBar').style.width = ((currentStep + 1) * 20) + '%';

        document.querySelectorAll('.form-section').forEach(function(s) {
            s.classList.toggle('active', parseInt(s.dataset.step) === currentStep);
        });

        document.querySelectorAll('.step-label').forEach(function(l) {
            var s = parseInt(l.dataset.step);
            l.classList.remove('active', 'completed');
            if (s === currentStep) l.classList.add('active');
            else if (s < maxReached + 1 && s < currentStep) l.classList.add('completed');
            else if (s <= maxReached && s !== currentStep) l.classList.add('completed');
        });

        document.getElementById('btnBack').style.visibility = currentStep > 0 ? 'visible' : 'hidden';
        document.getElementById('btnNext').style.display = currentStep < 4 ? 'inline-block' : 'none';
        document.getElementById('btnSubmit').style.display = currentStep === 4 ? 'inline-block' : 'none';

        if (currentStep === 4) updateReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    window.goToStep = function(step) {
        if (step <= maxReached && step < currentStep) {
            currentStep = step;
            updateStepper();
        }
    };

    window.nextStep = function() {
        if (!validateStep(currentStep)) return;
        if (currentStep < 4) {
            currentStep++;
            if (currentStep > maxReached) maxReached = currentStep;
            updateStepper();
        }
    };

    window.prevStep = function() {
        if (currentStep > 0) {
            currentStep--;
            updateStepper();
        }
    };

    // ── Validation ──
    function clearErrors() {
        document.querySelectorAll('.field-error').forEach(function(e) {
            e.classList.remove('show');
            e.textContent = '';
        });
        document.querySelectorAll('.form-control.is-invalid').forEach(function(e) {
            e.classList.remove('is-invalid');
        });
    }

    function showFieldError(name, msg) {
        var el = document.querySelector('.field-error[data-field="' + name + '"]');
        if (el) {
            el.textContent = msg;
            el.classList.add('show');
        }
        var inp = document.querySelector('[name="' + name + '"]');
        if (inp) inp.classList.add('is-invalid');
    }

    function getVal(name) {
        var el = document.querySelector('[name="' + name + '"]');
        return el ? el.value.trim() : '';
    }

    function validateStep(step) {
        clearErrors();
        var valid = true;

        function check(name, msg) {
            if (!getVal(name)) {
                showFieldError(name, msg);
                valid = false;
            }
        }

        if (step === 0) {
            var tierChecked = document.querySelector('input[name="target_level"]:checked');
            if (!tierChecked) { showFieldError('target_level', 'กรุณาเลือกชั้นธรรมศึกษา'); valid = false; }
            var eduChecked = document.querySelector('input[name="education_class"]:checked');
            if (!eduChecked) { showFieldError('education_class', 'กรุณาเลือกระดับชั้นการศึกษา'); valid = false; }
        }

        if (step === 1) {
            check('academic_year', 'กรุณากรอกปีการศึกษา');
            check('title', 'กรุณาเลือกคำนำหน้าชื่อ');
            check('first_name', 'กรุณากรอกชื่อ');
            check('last_name', 'กรุณากรอกนามสกุล');

            var nid = getVal('national_id').replace(/\D/g, '');
            if (!nid) { showFieldError('national_id', 'กรุณากรอกเลขประจำตัวประชาชน'); valid = false; }
            else if (nid.length !== 13) { showFieldError('national_id', 'เลขประจำตัวประชาชนต้องเป็นตัวเลข 13 หลัก'); valid = false; }

            var age = parseInt(getVal('age'));
            if (!getVal('age')) { showFieldError('age', 'กรุณากรอกอายุ'); valid = false; }
            else if (age < 1 || age > 120) { showFieldError('age', 'อายุต้องอยู่ระหว่าง 1-120'); valid = false; }

            check('birth_day', 'กรุณากรอกวันเกิด');
            check('birth_month', 'กรุณาเลือกเดือนเกิด');
            check('birth_year_be', 'กรุณากรอกปีเกิด (พ.ศ.)');

            if (getVal('birth_day') && getVal('birth_month') && getVal('birth_year_be')) {
                var d = parseInt(getVal('birth_day'));
                var m = parseInt(getVal('birth_month'));
                var y = parseInt(getVal('birth_year_be')) - 543;
                var testDate = new Date(y, m - 1, d);
                if (testDate.getDate() !== d || testDate.getMonth() !== m - 1) {
                    showFieldError('birth_day', 'วันเกิดไม่ถูกต้อง');
                    valid = false;
                }
            }

            var phone = getVal('phone');
            if (!phone) { showFieldError('phone', 'กรุณากรอกเบอร์โทรศัพท์'); valid = false; }
            else if (!/^0\d{8,9}$/.test(phone)) { showFieldError('phone', 'เบอร์โทรศัพท์ไม่ถูกต้อง (เช่น 0812345678)'); valid = false; }
        }

        if (step === 2) {
            check('house_number', 'กรุณากรอกบ้านเลขที่');
            check('province', 'กรุณาเลือกจังหวัด');
            check('district', 'กรุณาเลือกอำเภอ/เขต');
            check('sub_district', 'กรุณาเลือกตำบล/แขวง');
            var pc = getVal('postal_code');
            if (!pc) { showFieldError('postal_code', 'กรุณาเลือกรหัสไปรษณีย์'); valid = false; }
            else if (!/^\d{5}$/.test(pc)) { showFieldError('postal_code', 'รหัสไปรษณีย์ต้องเป็น 5 หลัก'); valid = false; }
            check('education_level', 'กรุณาเลือกระดับการศึกษา');
        }

        if (step === 3) {
            var hasPrev = document.getElementById('hasPreviousCert').checked;
            if (hasPrev && !getVal('previous_level')) {
                showFieldError('previous_level', 'กรุณาเลือกชั้นเดิมที่สอบได้');
                valid = false;
            }
        }

        if (step === 4) {
            check('office_name', 'กรุณากรอกชื่อสำนักเรียน/โรงเรียน');

            var hasPrev = document.getElementById('hasPreviousCert').checked;
            var tierRadio = document.querySelector('input[name="target_level"]:checked');
            var target = tierRadio ? tierRadio.value : '';
            if (!hasPrev && target && target !== 'ตรี') {
                showFieldError('target_level', 'ผู้สมัครครั้งแรกสามารถสมัครได้เฉพาะชั้นตรีเท่านั้น');
                valid = false;
            }
            if (hasPrev && target) {
                var prev = getVal('previous_level');
                if (target === 'โท' && prev !== 'ตรี') {
                    showFieldError('target_level', 'สมัครชั้นโทต้องสอบผ่านชั้นตรีมาก่อน');
                    valid = false;
                }
                if (target === 'เอก' && prev !== 'โท') {
                    showFieldError('target_level', 'สมัครชั้นเอกต้องสอบผ่านชั้นโทมาก่อน');
                    valid = false;
                }
            }
        }

        return valid;
    }

    // ── Previous cert toggle ──
    window.togglePrevCert = function(cb) {
        var fields = document.getElementById('prevCertFields');
        fields.classList.toggle('show', cb.checked);
        if (!cb.checked) {
            document.querySelector('[name="previous_level"]').value = '';
            var triRadio = document.querySelector('input[name="target_level"][value="ตรี"]');
            if (triRadio) triRadio.checked = true;
        }
    };

    window.onPrevLevelChange = function(sel) {
        var map = { 'ตรี': 'โท', 'โท': 'เอก' };
        var suggest = map[sel.value] || '';
        if (suggest) {
            var radio = document.querySelector('input[name="target_level"][value="' + suggest + '"]');
            if (radio) radio.checked = true;
        }
    };

    // ── Referral "other" toggle ──
    window.toggleReferralOther = function(cb) {
        document.getElementById('referralOtherWrap').classList.toggle('show', cb.checked);
    };

    // ── Review ──
    function updateReview() {
        var title = getVal('title');
        var fn = getVal('first_name');
        var ln = getVal('last_name');
        document.getElementById('rvName').textContent = title + ' ' + fn + ' ' + ln;
        var tierRadio = document.querySelector('input[name="target_level"]:checked');
        document.getElementById('rvLevel').textContent = 'ธ.ศ.' + (tierRadio ? tierRadio.value : '-');
        document.getElementById('rvYear').textContent = getVal('academic_year');

        var nid = getVal('national_id').replace(/\D/g, '');
        var masked = nid.length >= 4 ? 'x-xxxx-xxxxx-xx-' + nid.substring(9) : nid;
        document.getElementById('rvNid').textContent = masked;

        document.getElementById('rvPhone').textContent = getVal('phone') || '-';
        document.getElementById('rvLine').textContent = getVal('line_id') || '-';
        document.getElementById('rvOffice').textContent = getVal('office_name') || '-';
    }

    // ── Submit ──
    window.submitForm = function() {
        if (submitting) return;
        if (!validateStep(4)) return;

        submitting = true;
        var btnSubmit = document.getElementById('btnSubmit');
        var btnBack = document.getElementById('btnBack');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังตรวจสอบและบันทึกใบสมัคร...';
        btnBack.disabled = true;

        var formData = {};
        var form = document.getElementById('regForm');
        var inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(function(inp) {
            if (!inp.name) return;
            if (inp.type === 'checkbox') {
                if (inp.name === 'has_previous_cert_check') return;
                if (inp.name === 'referral_sources[]') {
                    if (!formData.referral_sources) formData.referral_sources = [];
                    if (inp.checked) {
                        var val = inp.value;
                        if (val === 'อื่นๆ') {
                            var other = getVal('referral_other');
                            if (other) formData.referral_sources.push('อื่นๆ: ' + other);
                            else formData.referral_sources.push('อื่นๆ');
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

        formData.has_previous_cert = document.getElementById('hasPreviousCert').checked ? 1 : 0;
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
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> ยืนยันและส่งใบสมัคร';
            btnBack.disabled = false;

            var msgDiv = document.getElementById('resultMsg');

            if (result.ok && result.data.success) {
                var titles = {'นาย':'นาย','นาง':'นาง','นางสาว':'นางสาว','เด็กชาย':'ด.ช.','เด็กหญิง':'ด.ญ.','พระ':'พระ','สามเณร':'สามเณร','แม่ชี':'แม่ชี'};
                var t = formData.title || '';
                var fullName = (titles[t] || t) + ' ' + (formData.first_name || '') + ' ' + (formData.last_name || '');
                var tierMap = {'ตรี':'ธ.ศ.ตรี','โท':'ธ.ศ.โท','เอก':'ธ.ศ.เอก'};
                var levelText = tierMap[formData.target_level] || formData.target_level || '-';
                var refCode = result.data.reference_code;

                msgDiv.innerHTML = '<div class="result-msg success">' +
                    '<i class="fas fa-check-circle mr-1"></i> <strong>' + result.data.message + '</strong><br>' +
                    'รหัสอ้างอิงของท่าน: <span class="ref-code">' + refCode + '</span><br>' +
                    '<small>กรุณาจดรหัสอ้างอิงไว้เพื่อใช้ตรวจสอบสถานะ</small>' +
                    '</div>' +
                    '<div class="receipt-actions">' +
                        '<button class="rbtn rbtn-print" onclick="printReceipt()"><i class="fas fa-print"></i> ปริ้นใบสมัคร</button>' +
                        '<button class="rbtn rbtn-pdf" onclick="downloadPDF()"><i class="fas fa-file-pdf"></i> ดาวน์โหลด PDF</button>' +
                        '<button class="rbtn rbtn-jpg" onclick="downloadJPG()"><i class="fas fa-file-image"></i> ดาวน์โหลด JPG</button>' +
                        '<a href="{{ route("landing") }}" class="rbtn rbtn-home"><i class="fas fa-home"></i> กลับหน้าหลัก</a>' +
                    '</div>' +
                    '<div id="receiptCard" class="receipt-card">' +
                        '<div class="receipt-card-header">' +
                            '<img src="{{ asset("images/โลโก้วัดสารอด.jpg") }}" alt="โลโก้วัดสารอด">' +
                            '<img src="{{ asset("images/โลโก้สำนักเรียนวัดสารอด.jpg") }}" alt="โลโก้สำนักเรียน">' +
                            '<h5>ใบสมัครสอบธรรมศึกษา</h5>' +
                            '<small>สำนักเรียนวัดสารอด &middot; รหัสอ้างอิง: ' + refCode + '</small>' +
                        '</div>' +
                        '<div class="receipt-row"><div class="receipt-label">ชื่อ-สกุล</div><div class="receipt-value">' + fullName + '</div></div>' +
                        '<div class="receipt-row"><div class="receipt-label">ชั้นธรรมศึกษา</div><div class="receipt-value">' + levelText + '</div></div>' +
                        '<div class="receipt-row"><div class="receipt-label">ปีการศึกษา (พ.ศ.)</div><div class="receipt-value">' + (formData.academic_year || '-') + '</div></div>' +
                        '<div class="receipt-row"><div class="receipt-label">เลขบัตรประชาชน</div><div class="receipt-value">' + (formData.national_id || '-') + '</div></div>' +
                        '<div class="receipt-row"><div class="receipt-label">เบอร์โทรศัพท์</div><div class="receipt-value">' + (formData.phone || '-') + '</div></div>' +
                        '<div class="receipt-row"><div class="receipt-label">LINE ID</div><div class="receipt-value">' + (formData.line_id || '-') + '</div></div>' +
                        '<div class="receipt-row"><div class="receipt-label">สำนักเรียน</div><div class="receipt-value">' + (formData.office_name || '-') + '</div></div>' +
                        '<div class="receipt-row"><div class="receipt-label">วันที่สมัคร</div><div class="receipt-value">' + new Date().toLocaleDateString('th-TH', {year:'numeric',month:'long',day:'numeric'}) + '</div></div>' +
                        '<div class="receipt-footer"><i class="fas fa-stamp mr-1"></i> เอกสารออกโดยระบบรับสมัครออนไลน์ วัดสารอด</div>' +
                    '</div>' +
                    '<div class="line-invite">' +
                        '<div class="line-invite-header"><i class="fab fa-line"></i> เข้ากลุ่มไลน์เพื่อติดตามข่าวสาร</div><br>' +
                        '<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https%3A%2F%2Fline.me%2FR%2Fti%2Fg%2FsErRNVzd8k" alt="LINE QR Code" width="180" height="180"><br>' +
                        '<div class="line-invite-group">ธรรมศึกษา|วัดสารอด</div>' +
                        '<div class="line-invite-desc">สแกน QR Code หรือกดปุ่มด้านล่างเพื่อเข้ากลุ่ม</div>' +
                        '<a href="https://line.me/R/ti/g/sErRNVzd8k" target="_blank" class="line-invite-btn"><i class="fab fa-line"></i> เข้ากลุ่มไลน์</a>' +
                    '</div>';

                document.getElementById('formSection').style.display = 'none';

                form.reset();
                document.getElementById('prevCertFields').classList.remove('show');
                document.getElementById('referralOtherWrap').classList.remove('show');
                document.querySelector('[name="academic_year"]').value = '{{ date("Y") + 543 }}';
                currentStep = 0;
                maxReached = 0;
                updateStepper();
                resetAddressDropdowns();
            } else {
                var errMsg = result.data.message || 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
                if (result.data.errors) {
                    clearErrors();
                    var firstErrorStep = null;
                    Object.keys(result.data.errors).forEach(function(key) {
                        var msgs = result.data.errors[key];
                        showFieldError(key, Array.isArray(msgs) ? msgs[0] : msgs);
                        var step = getFieldStep(key);
                        if (firstErrorStep === null || step < firstErrorStep) firstErrorStep = step;
                    });
                    if (firstErrorStep !== null && firstErrorStep !== currentStep) {
                        currentStep = firstErrorStep;
                        updateStepper();
                    }
                    errMsg = 'กรุณาตรวจสอบข้อมูลที่กรอก';
                }
                msgDiv.innerHTML = '<div class="result-msg error">' +
                    '<i class="fas fa-exclamation-circle mr-1"></i> ' + errMsg +
                    '</div>';
            }
            msgDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        })
        .catch(function(err) {
            submitting = false;
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> ยืนยันและส่งใบสมัคร';
            btnBack.disabled = false;
            var msgDiv = document.getElementById('resultMsg');
            msgDiv.innerHTML = '<div class="result-msg error">' +
                '<i class="fas fa-exclamation-circle mr-1"></i> บันทึกใบสมัครไม่สำเร็จ กรุณาตรวจสอบการเชื่อมต่อและลองใหม่อีกครั้ง' +
                '</div>';
        });
    };

    function getFieldStep(name) {
        var step0 = ['target_level','education_class'];
        var step1 = ['academic_year','title','first_name','last_name','national_id','age','birth_day','birth_month','birth_year_be','phone','line_id'];
        var step2 = ['house_number','province','district','sub_district','postal_code','education_level'];
        var step3 = ['has_previous_cert','previous_level','previous_period','previous_certificate_no','previous_year','previous_province'];
        if (step0.indexOf(name) !== -1) return 0;
        if (step1.indexOf(name) !== -1) return 1;
        if (step2.indexOf(name) !== -1) return 2;
        if (step3.indexOf(name) !== -1) return 3;
        return 4;
    }

    // ── Address autocomplete cascading ──
    var addrValidOptions = {};

    function populateDatalist(datalistId, items) {
        var dl = document.getElementById(datalistId);
        dl.innerHTML = '';
        items.forEach(function(item) {
            var opt = document.createElement('option');
            opt.value = item;
            dl.appendChild(opt);
        });
        addrValidOptions[datalistId] = items;
    }

    function loadAddressOptions(type, params, targetInput, datalistId) {
        var qs = 'type=' + type;
        Object.keys(params).forEach(function(k) { qs += '&' + k + '=' + encodeURIComponent(params[k]); });
        fetch('{{ route("api.thai_addresses") }}?' + qs)
            .then(function(r) { return r.json(); })
            .then(function(items) {
                populateDatalist(datalistId, items);
                targetInput.disabled = false;
                if (items.length === 1) {
                    targetInput.value = items[0];
                    targetInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
    }

    function resetAddressDropdowns() {
        ['district','sub_district','postal_code'].forEach(function(name) {
            var inp = document.querySelector('[name="' + name + '"]');
            if (inp) {
                inp.value = '';
                inp.disabled = true;
                var dl = document.getElementById('dl_' + name);
                if (dl) dl.innerHTML = '';
            }
        });
        ['office_district','office_sub_district','office_postal_code'].forEach(function(name) {
            var inp = document.querySelector('[name="' + name + '"]');
            if (inp) {
                inp.value = '';
                inp.disabled = true;
                var dl = document.getElementById('dl_' + name);
                if (dl) dl.innerHTML = '';
            }
        });
    }

    function isValidOption(datalistId, value) {
        var opts = addrValidOptions[datalistId];
        return opts && opts.indexOf(value) !== -1;
    }

    function initAddressDropdowns() {
        fetch('{{ route("api.thai_addresses") }}?type=provinces')
            .then(function(r) { return r.json(); })
            .then(function(provinces) {
                populateDatalist('dl_province', provinces);
                populateDatalist('dl_office_province', provinces);
            });

        setupCascade('province', 'dl_province', 'district', 'dl_district', 'districts', 'province');
        setupCascade('district', 'dl_district', 'sub_district', 'dl_sub_district', 'sub_districts', 'district', 'province');
        setupCascadePostal('sub_district', 'dl_sub_district', 'postal_code', 'dl_postal_code', 'province', 'district');

        setupCascade('office_province', 'dl_office_province', 'office_district', 'dl_office_district', 'districts', 'province');
        setupCascade('office_district', 'dl_office_district', 'office_sub_district', 'dl_office_sub_district', 'sub_districts', 'district', 'office_province');
        setupCascadePostal('office_sub_district', 'dl_office_sub_district', 'office_postal_code', 'dl_office_postal_code', 'office_province', 'office_district');
    }

    function setupCascade(sourceName, sourceDlId, targetName, targetDlId, apiType, paramKey, parentName) {
        var source = document.querySelector('[name="' + sourceName + '"]');
        var target = document.querySelector('[name="' + targetName + '"]');
        var debounceTimer;

        source.addEventListener('input', function() {
            var val = this.value.trim();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                if (!isValidOption(sourceDlId, val)) {
                    target.value = '';
                    target.disabled = true;
                    document.getElementById(targetDlId).innerHTML = '';
                    var nextTarget = target.getAttribute('data-target');
                    if (nextTarget) resetDownstream(nextTarget);
                    return;
                }
                target.value = '';
                var nextTarget = target.getAttribute('data-target');
                if (nextTarget) resetDownstream(nextTarget);

                var params = {};
                params[paramKey] = val;
                if (parentName) {
                    params.province = document.querySelector('[name="' + parentName + '"]').value;
                }
                loadAddressOptions(apiType, params, target, targetDlId);
            }, 150);
        });
    }

    function setupCascadePostal(sourceName, sourceDlId, targetName, targetDlId, provName, distName) {
        var source = document.querySelector('[name="' + sourceName + '"]');
        var target = document.querySelector('[name="' + targetName + '"]');
        var debounceTimer;

        source.addEventListener('input', function() {
            var val = this.value.trim();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                if (!isValidOption(sourceDlId, val)) {
                    target.value = '';
                    target.disabled = true;
                    document.getElementById(targetDlId).innerHTML = '';
                    return;
                }
                target.value = '';
                var prov = document.querySelector('[name="' + provName + '"]').value;
                var dist = document.querySelector('[name="' + distName + '"]').value;
                loadAddressOptions('postal_codes', {
                    province: prov,
                    district: dist,
                    sub_district: val
                }, target, targetDlId);
            }, 150);
        });
    }

    function resetDownstream(name) {
        var el = document.querySelector('[name="' + name + '"]');
        if (el) {
            el.value = '';
            el.disabled = true;
            var dlId = 'dl_' + name;
            var dl = document.getElementById(dlId);
            if (dl) dl.innerHTML = '';
            var next = el.getAttribute('data-target');
            if (next) resetDownstream(next);
        }
    }

    // Init
    initAddressDropdowns();
    updateStepper();
})();

function printReceipt() {
    window.print();
}

function downloadPDF() {
    var el = document.getElementById('receiptCard');
    html2canvas(el, { scale: 2, useCORS: true, backgroundColor: '#fff' }).then(function(canvas) {
        var imgData = canvas.toDataURL('image/jpeg', 0.95);
        var pdf = new jspdf.jsPDF('p', 'mm', 'a4');
        var w = pdf.internal.pageSize.getWidth() - 20;
        var h = (canvas.height * w) / canvas.width;
        pdf.addImage(imgData, 'JPEG', 10, 10, w, h);
        pdf.save('ใบสมัครสอบธรรมศึกษา.pdf');
    });
}

function downloadJPG() {
    var el = document.getElementById('receiptCard');
    html2canvas(el, { scale: 2, useCORS: true, backgroundColor: '#fff' }).then(function(canvas) {
        var link = document.createElement('a');
        link.download = 'ใบสมัครสอบธรรมศึกษา.jpg';
        link.href = canvas.toDataURL('image/jpeg', 0.95);
        link.click();
    });
}

function openGuide() {
    document.getElementById('guideOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeGuide() {
    document.getElementById('guideOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeGuide();
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@endif

</body>
</html>
