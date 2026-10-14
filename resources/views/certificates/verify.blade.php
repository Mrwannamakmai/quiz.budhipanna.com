<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $certificate ? 'ใบประกาศ — '.$certificate->recipient_name : 'ตรวจสอบใบประกาศ' }} — ธรรมศึกษา Quiz</title>
    {{-- OG / Social Meta --}}
    @if($certificate)
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $certificate->recipient_name }} — ได้รับใบประกาศนียบัตรธรรมศึกษา">
    <meta property="og:description" content="ผ่านการทดสอบ {{ $certificate->examSet->name ?? '' }} คะแนน {{ $certificate->score }} · เลขที่ {{ $certificate->certificate_no }}">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta name="twitter:card"       content="summary">
    <meta name="twitter:title"      content="{{ $certificate->recipient_name }} ได้ใบประกาศนียบัตร">
    <meta name="twitter:description" content="ผ่านแบบทดสอบ {{ $certificate->examSet->name ?? '' }} · ธรรมศึกษา Quiz">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #134e4a 50%, #1a1a2e 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* ── Navbar ── */
        .vf-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(15,118,110,.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,.1);
            padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .vf-nav-logo { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .vf-nav-logo-icon {
            width: 34px; height: 34px; border-radius: 9px;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
            color: #a7f3d0; font-size: .9rem;
        }
        .vf-nav-logo span { font-size: 1rem; font-weight: 800; color: #fff; }
        .vf-nav-logo small { color: rgba(255,255,255,.6); font-weight: 400; }
        .vf-nav-back {
            color: rgba(255,255,255,.75); font-size: .82rem; text-decoration: none;
            display: flex; align-items: center; gap: 6px;
            transition: color .2s;
        }
        .vf-nav-back:hover { color: #fff; text-decoration: none; }

        /* ── Wrapper ── */
        .vf-wrap { width: 100%; max-width: 520px; margin-top: 64px; }

        /* ── Header badge ── */
        .vf-header {
            text-align: center; margin-bottom: 24px;
        }
        .vf-header-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 30px; padding: 6px 18px;
            color: #a7f3d0; font-size: .78rem; font-weight: 700;
            letter-spacing: .5px; margin-bottom: 12px;
        }
        .vf-header h4 { color: #fff; font-weight: 800; font-size: 1.3rem; margin-bottom: 4px; }
        .vf-header p  { color: rgba(255,255,255,.55); font-size: .82rem; }

        /* ── Valid card ── */
        .cert-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,.35), 0 4px 16px rgba(0,0,0,.2);
        }

        /* Gold header stripe */
        .cert-header {
            background: linear-gradient(135deg, #d97706, #f59e0b, #fcd34d);
            padding: 28px 32px 24px;
            text-align: center; position: relative; overflow: hidden;
        }
        .cert-header::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .cert-seal {
            width: 72px; height: 72px; border-radius: 50%;
            background: rgba(255,255,255,.25);
            border: 3px solid rgba(255,255,255,.5);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px; position: relative; z-index: 1;
            box-shadow: 0 4px 16px rgba(0,0,0,.15);
        }
        .cert-seal i { font-size: 1.8rem; color: #fff; }
        .cert-valid-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.25); border: 1.5px solid rgba(255,255,255,.4);
            border-radius: 20px; padding: 5px 16px;
            color: #fff; font-size: .78rem; font-weight: 800;
            letter-spacing: .4px; position: relative; z-index: 1;
        }
        .cert-valid-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #fff;
            box-shadow: 0 0 0 2px rgba(255,255,255,.4);
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 2px rgba(255,255,255,.4); }
            50%       { box-shadow: 0 0 0 5px rgba(255,255,255,.1); }
        }

        /* Body */
        .cert-body { padding: 28px 32px; }

        .cert-name-block {
            text-align: center; margin-bottom: 22px;
            padding-bottom: 20px; border-bottom: 1.5px dashed #e2e8f0;
        }
        .cert-name-label { font-size: .7rem; font-weight: 700; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px; }
        .cert-name { font-size: 1.5rem; font-weight: 800; color: #0f172a; line-height: 1.2; }

        .cert-rows { display: flex; flex-direction: column; gap: 14px; margin-bottom: 22px; }
        .cert-row { display: flex; align-items: flex-start; gap: 12px; }
        .cert-row-icon {
            width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: .8rem;
        }
        .cert-row-text {}
        .cert-row-label { font-size: .68rem; font-weight: 700; color: #94a3b8; letter-spacing: .5px; text-transform: uppercase; }
        .cert-row-value { font-size: .95rem; font-weight: 700; color: #1e293b; }

        .cert-no-box {
            background: #f8fafc; border: 1.5px solid #e2e8f0;
            border-radius: 10px; padding: 12px 16px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 10px; margin-bottom: 18px;
        }
        .cert-no-label { font-size: .68rem; font-weight: 700; color: #94a3b8; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 2px; }
        .cert-no-value { font-size: .9rem; font-weight: 800; color: #0f766e; font-family: monospace; letter-spacing: 1px; }
        .cert-no-copy {
            width: 32px; height: 32px; border-radius: 8px;
            background: #e8f5f2; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #0f766e; font-size: .75rem; transition: background .2s;
        }
        .cert-no-copy:hover { background: #ccece7; }

        .cert-footer-bar {
            background: #f0fdf4; border-radius: 10px;
            padding: 10px 14px;
            display: flex; align-items: center; gap: 8px;
            font-size: .75rem; color: #065f46;
        }

        /* ── Score ring ── */
        .score-ring-wrap {
            display: flex; flex-direction: column; align-items: center;
        }
        .score-ring {
            width: 64px; height: 64px; position: relative; margin-bottom: 4px;
        }
        .score-ring svg { transform: rotate(-90deg); }
        .score-ring circle { fill: none; }
        .score-ring-val {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; font-weight: 800; color: #0f766e;
        }
        .score-ring-label { font-size: .65rem; color: #94a3b8; font-weight: 600; }

        /* ── Invalid card ── */
        .invalid-card {
            background: #fff; border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,.35);
        }
        .invalid-header {
            background: linear-gradient(135deg, #7f1d1d, #dc2626);
            padding: 28px 32px 24px; text-align: center;
        }
        .invalid-seal {
            width: 72px; height: 72px; border-radius: 50%;
            background: rgba(255,255,255,.15);
            border: 3px solid rgba(255,255,255,.3);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
        }
        .invalid-seal i { font-size: 1.8rem; color: #fff; }
        .invalid-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.2);
            border: 1.5px solid rgba(255,255,255,.3);
            border-radius: 20px; padding: 5px 16px;
            color: #fff; font-size: .78rem; font-weight: 800;
        }
        .invalid-body { padding: 28px 32px; text-align: center; }
        .invalid-body h5 { font-size: 1.1rem; font-weight: 800; color: #7f1d1d; margin-bottom: 8px; }
        .invalid-body p  { color: #64748b; font-size: .85rem; line-height: 1.6; }
        .invalid-code {
            display: inline-block; background: #fef2f2; color: #991b1b;
            border: 1px solid #fecaca; border-radius: 8px;
            padding: 6px 14px; font-family: monospace;
            font-size: .85rem; font-weight: 700; margin: 10px 0 16px;
        }
        .invalid-hint {
            background: #fef9c3; border: 1px solid #fde68a; border-radius: 10px;
            padding: 10px 14px; font-size: .78rem; color: #854d0e;
            display: flex; align-items: flex-start; gap: 8px; text-align: left;
        }

        /* ── Bottom timestamp ── */
        .vf-bottom {
            text-align: center; margin-top: 18px;
            color: rgba(255,255,255,.4); font-size: .73rem;
        }
        .vf-bottom a { color: rgba(255,255,255,.55); text-decoration: none; }
        .vf-bottom a:hover { color: #a7f3d0; }

        /* ── QR + Share ── */
        .share-section { border-top: 1.5px dashed #e2e8f0; margin-top: 18px; padding-top: 18px; }
        .share-label { font-size: .68rem; font-weight: 700; color: #94a3b8; letter-spacing: .6px; text-transform: uppercase; margin-bottom: 12px; text-align: center; }
        .share-row { display: flex; align-items: center; justify-content: center; gap: 10px; flex-wrap: wrap; }
        .share-btn { display: inline-flex; align-items: center; gap: 7px; border-radius: 22px; padding: 8px 18px; font-size: .82rem; font-weight: 700; text-decoration: none; cursor: pointer; border: none; font-family: 'Sarabun', sans-serif; transition: opacity .15s; }
        .share-btn:hover { opacity: .88; text-decoration: none; }
        .share-btn-copy { background: #f1f5f9; color: #475569; }
        .share-btn-line  { background: #06c755; color: #fff; }
        .share-btn-fb    { background: #1877f2; color: #fff; }
        .share-btn-print { background: #fef3c7; color: #92400e; }
        #qr-box { display: flex; justify-content: center; margin-bottom: 14px; }
        #qr-box canvas, #qr-box img { border-radius: 10px; border: 3px solid #f0fdf4; padding: 4px; background: #fff; }

        @media (max-width: 480px) {
            .cert-body, .invalid-body { padding: 20px; }
            .cert-header, .invalid-header { padding: 22px 20px 20px; }
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="vf-nav">
    <a href="{{ route('landing') }}" class="vf-nav-logo">
        <div class="vf-nav-logo-icon"><i class="fas fa-book-open"></i></div>
        <span>ธรรมศึกษา<small>Quiz</small></span>
    </a>
    <a href="{{ route('landing') }}" class="vf-nav-back">
        <i class="fas fa-arrow-left"></i> กลับหน้าแรก
    </a>
</nav>

<div class="vf-wrap">

    {{-- Header --}}
    <div class="vf-header">
        <div class="vf-header-badge">
            <i class="fas fa-shield-alt"></i> ระบบตรวจสอบใบประกาศ
        </div>
        <h4>Certificate Verification</h4>
        <p>ตรวจสอบความถูกต้องและน่าเชื่อถือของใบประกาศนียบัตร</p>
    </div>

    @if($certificate)
    {{-- ── Valid ── --}}
    <div class="cert-card">
        {{-- Gold header --}}
        <div class="cert-header">
            <div class="cert-seal"><i class="fas fa-award"></i></div>
            <div class="cert-valid-badge">
                <div class="cert-valid-dot"></div>
                ใบประกาศถูกต้องและน่าเชื่อถือ
            </div>
        </div>

        <div class="cert-body">
            {{-- Recipient --}}
            <div class="cert-name-block">
                <div class="cert-name-label"><i class="fas fa-user mr-1"></i>ผู้รับใบประกาศ</div>
                <div class="cert-name">{{ $certificate->recipient_name }}</div>
            </div>

            {{-- Cert No --}}
            <div class="cert-no-box">
                <div>
                    <div class="cert-no-label">เลขที่ใบประกาศ</div>
                    <div class="cert-no-value" id="cert-no-text">{{ $certificate->certificate_no }}</div>
                </div>
                <button class="cert-no-copy" onclick="copyCertNo()" title="คัดลอก">
                    <i class="fas fa-copy" id="copy-icon"></i>
                </button>
            </div>

            {{-- Details --}}
            <div class="cert-rows">
                <div class="cert-row">
                    <div class="cert-row-icon" style="background:#eff6ff;color:#2563eb;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="cert-row-text">
                        <div class="cert-row-label">ชุดข้อสอบ</div>
                        <div class="cert-row-value">{{ $certificate->examSet->name ?? '-' }}</div>
                    </div>
                </div>

                <div class="cert-row">
                    <div class="cert-row-icon" style="background:#f0fdf4;color:#16a34a;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="cert-row-text" style="flex:1;">
                        <div class="cert-row-label">คะแนน</div>
                        @php
                            $scoreNum = (float) $certificate->score;
                        @endphp
                        <div class="cert-row-value" style="color:#16a34a;">
                            {{ $scoreNum > 0 && $scoreNum <= 1 ? round($scoreNum * 100, 1).'%' : $scoreNum }}
                        </div>
                    </div>
                </div>

                <div class="cert-row">
                    <div class="cert-row-icon" style="background:#fef9c3;color:#d97706;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="cert-row-text">
                        <div class="cert-row-label">วันที่ออกใบประกาศ</div>
                        <div class="cert-row-value">
                            {{ $certificate->issued_at ? $certificate->issued_at->format('d/m/Y') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="cert-row">
                    <div class="cert-row-icon" style="background:#f5f3ff;color:#7c3aed;">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="cert-row-text">
                        <div class="cert-row-label">ประเภท</div>
                        <div class="cert-row-value">
                            @if($certificate->issued_type === 'auto')
                                <span style="display:inline-flex;align-items:center;gap:5px;background:#e0f2fe;color:#0369a1;border-radius:12px;padding:3px 10px;font-size:.78rem;font-weight:700;">
                                    <i class="fas fa-robot" style="font-size:.7rem;"></i> ระบบออกให้อัตโนมัติ
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:5px;background:#fef9c3;color:#854d0e;border-radius:12px;padding:3px 10px;font-size:.78rem;font-weight:700;">
                                    <i class="fas fa-chalkboard-teacher" style="font-size:.7rem;"></i> ครูออกให้
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer bar --}}
            <div class="cert-footer-bar">
                <i class="fas fa-check-circle" style="color:#16a34a;font-size:1rem;flex-shrink:0;"></i>
                <span>ข้อมูลนี้ได้รับการยืนยันจากระบบข้อสอบธรรมศึกษา ณ <strong>{{ now()->format('d/m/Y H:i') }}</strong></span>
            </div>

            {{-- QR + Share --}}
            <div class="share-section">
                <div class="share-label"><i class="fas fa-share-alt mr-1"></i>แชร์ใบประกาศ</div>
                <div id="qr-box"></div>
                <div class="share-row">
                    <button class="share-btn share-btn-copy" onclick="copyVerifyLink(this)">
                        <i class="fas fa-copy"></i><span>คัดลอกลิงก์</span>
                    </button>
                    <a class="share-btn share-btn-line" id="share-line" href="#" target="_blank" rel="noopener">
                        <i class="fas fa-comment-dots"></i>แชร์ Line
                    </a>
                    <a class="share-btn share-btn-fb" id="share-fb" href="#" target="_blank" rel="noopener">
                        <i class="fab fa-facebook-f"></i>Facebook
                    </a>
                    <button class="share-btn share-btn-print" onclick="window.print()">
                        <i class="fas fa-print"></i>พิมพ์
                    </button>
                </div>
            </div>
        </div>
    </div>

    @else
    {{-- ── Invalid ── --}}
    <div class="invalid-card">
        <div class="invalid-header">
            <div class="invalid-seal"><i class="fas fa-times-circle"></i></div>
            <div class="invalid-tag"><i class="fas fa-exclamation-triangle mr-1"></i>ไม่พบใบประกาศ</div>
        </div>
        <div class="invalid-body">
            <h5>ไม่พบใบประกาศนี้ในระบบ</h5>
            <p>ไม่มีใบประกาศที่ตรงกับเลขที่</p>
            <div class="invalid-code">{{ request()->route('certificateNo') }}</div>
            <div class="invalid-hint">
                <i class="fas fa-lightbulb" style="color:#d97706;flex-shrink:0;margin-top:2px;"></i>
                <span>ตรวจสอบว่าเลขที่ถูกต้องครบถ้วน เลขที่ใบประกาศอยู่บนใบประกาศหรือที่หน้าผลการสอบของคุณ</span>
            </div>
        </div>
    </div>
    @endif

    <div class="vf-bottom">
        ตรวจสอบ ณ {{ now()->format('d/m/Y H:i:s') }} &nbsp;·&nbsp;
        <a href="{{ route('pretest.index') }}">ทำข้อสอบฟรี</a>
        &nbsp;·&nbsp;
        <a href="{{ route('landing') }}">หน้าแรก</a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
var verifyUrl = '{{ url()->current() }}';

function copyCertNo() {
    var text = document.getElementById('cert-no-text').innerText;
    navigator.clipboard.writeText(text).then(function() {
        var icon = document.getElementById('copy-icon');
        icon.className = 'fas fa-check';
        setTimeout(function() { icon.className = 'fas fa-copy'; }, 1800);
    });
}

function copyVerifyLink(btn) {
    navigator.clipboard.writeText(verifyUrl).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i><span>คัดลอกแล้ว!</span>';
        btn.style.background = '#d1fae5';
        btn.style.color = '#065f46';
        setTimeout(function() {
            btn.innerHTML = orig;
            btn.style.background = '';
            btn.style.color = '';
        }, 2000);
    }).catch(function() {
        prompt('คัดลอก URL:', verifyUrl);
    });
}

// Set share links
@if($certificate)
var shareText = encodeURIComponent('{{ $certificate->recipient_name }} ได้รับใบประกาศนียบัตรธรรมศึกษา · ตรวจสอบได้ที่ ' + verifyUrl);
document.getElementById('share-line').href = 'https://social-plugins.line.me/lineit/share?url=' + encodeURIComponent(verifyUrl) + '&text=' + shareText;
document.getElementById('share-fb').href   = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(verifyUrl);

// QR Code
if (typeof QRCode !== 'undefined') {
    new QRCode(document.getElementById('qr-box'), {
        text: verifyUrl,
        width: 128, height: 128,
        colorDark: '#0f766e', colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
    });
}
@endif
</script>
</body>
</html>
