<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลการทำแบบทดสอบ — {{ $examSet->name ?? 'ธรรมศึกษา Quiz' }}</title>
    {{-- OG / Social Meta --}}
    @if(!empty($certificate))
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $certificate->recipient_name }} — ผ่านการทดสอบธรรมศึกษา!">
    <meta property="og:description" content="สอบผ่าน {{ $examSet->name ?? '' }} คะแนน {{ $scorePercent }}% · เลขที่ {{ $certificate->certificate_no }}">
    <meta property="og:url"         content="{{ route('certificates.verify', $certificate->certificate_no) }}">
    <meta name="twitter:card"       content="summary">
    <meta name="twitter:title"      content="{{ $certificate->recipient_name }} ผ่านแบบทดสอบธรรมศึกษา">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; }
        .fas,.far,.fal { font-family: "Font Awesome 5 Free" !important; }
        .fab { font-family: "Font Awesome 5 Brands" !important; }
    </style>
</head>
<body>
<nav style="background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:10px 0;position:sticky;top:0;z-index:999;">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('landing') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#16a085,#1abc9c);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;">
                <i class="fas fa-book-open"></i>
            </div>
            <span style="font-size:1.1rem;font-weight:700;color:#16a085;">ธรรมศึกษา<span style="color:#555;font-weight:400;">Quiz</span></span>
        </a>
        <div class="d-flex align-items-center" style="gap:10px;">
            <a href="{{ route('pretest.index') }}" style="color:#555;font-size:.9rem;text-decoration:none;">
                <i class="fas fa-arrow-left mr-1"></i>รายการแบบทดสอบ
            </a>
            <a href="{{ route('login') }}" style="background:#16a085;color:#fff;border-radius:20px;padding:7px 20px;font-weight:600;text-decoration:none;font-size:.9rem;">
                <i class="fas fa-sign-in-alt mr-1"></i>เข้าสู่ระบบ
            </a>
        </div>
    </div>
</nav>

<div class="container py-4" style="max-width:750px;">

    <!-- Score card -->
    <div class="card border-0 shadow-sm mb-4 text-center
        {{ $passed ? 'border-top border-success' : 'border-top border-danger' }}" style="border-top-width:4px!important;">
        <div class="card-body py-4">
            @if($passed)
                <div class="text-success mb-2"><i class="fas fa-check-circle fa-3x"></i></div>
                <h4 class="text-success">ผ่าน!</h4>
            @else
                <div class="text-danger mb-2"><i class="fas fa-times-circle fa-3x"></i></div>
                <h4 class="text-danger">ยังไม่ผ่าน</h4>
            @endif
            <div class="display-4 font-weight-bold my-2">{{ $scorePercent }}%</div>
            <p class="text-muted mb-1">คะแนน {{ $earnedPoints }} / {{ $totalPoints }}</p>
            <p class="text-muted small">เกณฑ์ผ่าน {{ $examSet->pass_percentage }}%</p>
        </div>
    </div>

    @if($passed)
        @if(!empty($certificate))
        @php $verifyUrl = route('certificates.verify', $certificate->certificate_no); @endphp
        {{-- ใบประกาศ --}}
        <div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:2px solid #fbbf24;border-radius:16px;padding:24px;text-align:center;margin-bottom:16px;">
            <div style="font-size:2.2rem;margin-bottom:6px;">🏆</div>
            <h5 style="color:#92400e;font-weight:800;margin-bottom:4px;">ได้รับใบประกาศนียบัตร!</h5>
            <p style="color:#a16207;font-size:.82rem;margin-bottom:14px;">เลขที่ <code style="background:#fde68a;padding:2px 8px;border-radius:5px;">{{ $certificate->certificate_no }}</code></p>
            <div style="background:rgba(255,255,255,.75);border-radius:10px;padding:14px 20px;margin-bottom:16px;display:inline-block;min-width:260px;">
                <div style="font-size:1.15rem;font-weight:800;color:#1e293b;margin-bottom:2px;">{{ $certificate->recipient_name }}</div>
                <div style="font-size:.82rem;color:#64748b;">{{ $examSet->name }}</div>
                <div style="font-size:.88rem;color:#059669;font-weight:700;margin-top:6px;"><i class="fas fa-star mr-1"></i>คะแนน {{ $scorePercent }}%</div>
            </div>

            {{-- QR Code --}}
            <div style="display:flex;justify-content:center;margin-bottom:14px;">
                <div id="result-qr" style="background:#fff;padding:8px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.12);display:inline-block;"></div>
            </div>

            {{-- Share Buttons --}}
            <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;margin-bottom:14px;">
                <button id="res-copy-btn" onclick="copyResultLink(this)"
                        style="background:#fff;color:#0f766e;border:2px solid #0d9488;border-radius:22px;padding:8px 18px;font-weight:700;font-size:.82rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-copy"></i>คัดลอกลิงก์
                </button>
                <a id="res-line-btn" href="#" target="_blank" rel="noopener"
                   style="background:#06c755;color:#fff;border:none;border-radius:22px;padding:8px 18px;font-weight:700;font-size:.82rem;text-decoration:none;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-comment-dots"></i>แชร์ Line
                </a>
                <a id="res-fb-btn" href="#" target="_blank" rel="noopener"
                   style="background:#1877f2;color:#fff;border:none;border-radius:22px;padding:8px 18px;font-weight:700;font-size:.82rem;text-decoration:none;display:flex;align-items:center;gap:6px;">
                    <i class="fab fa-facebook-f"></i>Facebook
                </a>
            </div>

            <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                <a href="{{ $verifyUrl }}"
                   target="_blank"
                   style="background:#fff;color:#92400e;border:2px solid #f59e0b;border-radius:22px;padding:8px 22px;font-weight:700;font-size:.85rem;text-decoration:none;">
                    <i class="fas fa-search mr-1"></i>ดู/ตรวจสอบ
                </a>
                <button onclick="window.print()"
                        style="background:#92400e;color:#fff;border:none;border-radius:22px;padding:8px 22px;font-weight:700;font-size:.85rem;cursor:pointer;">
                    <i class="fas fa-print mr-1"></i>พิมพ์
                </button>
            </div>
        </div>
        @else
        <div class="alert alert-success text-center">
            <i class="fas fa-certificate mr-1 text-warning"></i>
            คุณผ่านแล้ว! <a href="{{ route('login') }}" class="font-weight-bold">เข้าสู่ระบบ</a>
            เพื่อรับใบประกาศนียบัตร
        </div>
        @endif
    @endif

    <!-- Answer breakdown -->
    <style>
    .rv-header { background:#fff; border-radius:14px 14px 0 0; padding:18px 22px; border-bottom:2px solid #f1f5f9; display:flex; align-items:center; gap:10px; }
    .rv-header i { color:#3b82f6; font-size:1.1rem; }
    .rv-header h6 { margin:0; font-size:1rem; font-weight:700; color:#1e293b; }
    .rv-header .rv-summary { margin-left:auto; font-size:.82rem; color:#64748b; }

    .rv-item { background:#fff; padding:20px 22px; border-bottom:1.5px solid #f1f5f9; }
    .rv-item:last-child { border-bottom:none; border-radius:0 0 14px 14px; }

    .rv-q-top { display:flex; align-items:flex-start; gap:12px; margin-bottom:12px; }
    .rv-num { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; color:#fff; flex-shrink:0; }
    .rv-num.correct { background:#16a34a; }
    .rv-num.partial { background:#f59e0b; }
    .rv-num.wrong { background:#ef4444; }
    .rv-num.skip { background:#94a3b8; }

    .rv-q-text { flex:1; font-size:.92rem; color:#1e293b; line-height:1.6; }
    .rv-status { flex-shrink:0; font-size:.78rem; font-weight:700; padding:4px 12px; border-radius:20px; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; }
    .rv-status.correct { background:#dcfce7; color:#15803d; }
    .rv-status.partial { background:#fef3c7; color:#92400e; }
    .rv-status.wrong { background:#fee2e2; color:#991b1b; }
    .rv-status.skip { background:#f1f5f9; color:#64748b; }

    .rv-answers { display:flex; flex-direction:column; gap:8px; margin-left:44px; }
    .rv-ans-box { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; font-size:.88rem; }
    .rv-ans-box .rv-icon { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.75rem; flex-shrink:0; }

    .rv-ans-user.correct { background:#dcfce7; border:1.5px solid #bbf7d0; }
    .rv-ans-user.correct .rv-icon { background:#16a34a; color:#fff; }
    .rv-ans-user.wrong { background:#fee2e2; border:1.5px solid #fecaca; }
    .rv-ans-user.wrong .rv-icon { background:#ef4444; color:#fff; }
    .rv-ans-user.partial { background:#fef3c7; border:1.5px solid #fde68a; }
    .rv-ans-user.partial .rv-icon { background:#f59e0b; color:#fff; }
    .rv-ans-user.skip { background:#f1f5f9; border:1.5px solid #e2e8f0; }
    .rv-ans-user.skip .rv-icon { background:#94a3b8; color:#fff; }

    .rv-ans-key { background:#eff6ff; border:1.5px solid #bfdbfe; }
    .rv-ans-key .rv-icon { background:#2563eb; color:#fff; }

    .rv-ans-label { font-size:.72rem; font-weight:700; text-transform:uppercase; color:#64748b; letter-spacing:.3px; }
    .rv-ans-text { font-weight:600; color:#1e293b; }
    .rv-ans-detail { color:#64748b; font-size:.82rem; margin-left:4px; }

    .rv-essay-box { border-radius:10px; padding:14px 16px; font-size:.88rem; line-height:1.6; }
    .rv-essay-user { background:#f8fafc; border:1.5px solid #e2e8f0; color:#334155; }
    .rv-essay-key { background:#eff6ff; border:1.5px solid #bfdbfe; color:#1e40af; }
    .rv-essay-label { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
    .rv-essay-label i { font-size:.85rem; }
    .rv-essay-label span { font-size:.78rem; font-weight:700; text-transform:uppercase; color:#64748b; letter-spacing:.3px; }

    .rv-sim { display:flex; align-items:center; gap:10px; margin-top:6px; padding:8px 14px; background:#f8fafc; border-radius:8px; border:1px solid #e2e8f0; }
    .rv-sim-bar { flex:1; height:8px; background:#e2e8f0; border-radius:8px; overflow:hidden; max-width:160px; }
    .rv-sim-fill { height:100%; border-radius:8px; transition:width .3s; }
    .rv-sim-text { font-size:.78rem; font-weight:700; }
    </style>

    <div style="border-radius:14px; box-shadow:0 1px 6px rgba(0,0,0,.08); overflow:hidden; border:1.5px solid #f1f5f9;">
        <div class="rv-header">
            <i class="fas fa-clipboard-list"></i>
            <h6>เฉลยคำตอบ</h6>
            <span class="rv-summary">
                <i class="fas fa-check-circle text-success mr-1"></i>ถูก {{ collect($results)->where('correct', true)->where('points_earned', '>', 0)->count() }}
                &nbsp;
                <i class="fas fa-times-circle text-danger mr-1"></i>ผิด {{ collect($results)->filter(fn($r) => !$r['correct'] && trim($r['user_answer'] ?? '') !== '')->count() }}
            </span>
        </div>

        @foreach($results as $i => $r)
        @php
            $q = $r['question'];
            $isFullCorrect = $r['correct'] === true && $r['points_earned'] == $q->points;
            $isPartial = $r['correct'] === true && $r['points_earned'] < $q->points;
            $isWrong = $r['correct'] === false && trim($r['user_answer'] ?? '') !== '';
            $isSkip = trim($r['user_answer'] ?? '') === '';
            $state = $isFullCorrect ? 'correct' : ($isPartial ? 'partial' : ($isWrong ? 'wrong' : 'skip'));

            $thaiOpts = ['a'=>'ก','b'=>'ข','c'=>'ค','d'=>'ง'];
        @endphp
        <div class="rv-item">
            <div class="rv-q-top">
                <div class="rv-num {{ $state }}">{{ $i + 1 }}</div>
                <div class="rv-q-text">{{ $q->question_text }}</div>
                <div class="rv-status {{ $state }}">
                    @if($state === 'correct')
                        <i class="fas fa-check-circle"></i> ถูก {{ $r['points_earned'] }}/{{ $q->points }}
                    @elseif($state === 'partial')
                        <i class="fas fa-adjust"></i> บางส่วน {{ $r['points_earned'] }}/{{ $q->points }}
                    @elseif($state === 'wrong')
                        <i class="fas fa-times-circle"></i> ผิด 0/{{ $q->points }}
                    @else
                        <i class="fas fa-minus-circle"></i> ไม่ตอบ 0/{{ $q->points }}
                    @endif
                </div>
            </div>

            @if($q->type === 'multiple_choice')
            <div class="rv-answers">
                {{-- คำตอบของคุณ --}}
                <div class="rv-ans-box rv-ans-user {{ $state }}">
                    <div class="rv-icon">
                        @if($state === 'correct') <i class="fas fa-check"></i>
                        @elseif($state === 'wrong') <i class="fas fa-times"></i>
                        @elseif($isSkip) <i class="fas fa-minus"></i>
                        @endif
                    </div>
                    <div>
                        <div class="rv-ans-label">คำตอบของคุณ</div>
                        <span class="rv-ans-text">
                            @if($r['user_answer'])
                                {{ $thaiOpts[$r['user_answer']] ?? '-' }}.
                                <span class="rv-ans-detail">{{ $q->{'option_'.$r['user_answer']} ?? '' }}</span>
                            @else
                                ไม่ได้ตอบ
                            @endif
                        </span>
                    </div>
                </div>

                {{-- เฉลย (แสดงเมื่อตอบผิดหรือไม่ได้ตอบ) --}}
                @if(!$isFullCorrect)
                <div class="rv-ans-box rv-ans-key">
                    <div class="rv-icon"><i class="fas fa-lightbulb"></i></div>
                    <div>
                        <div class="rv-ans-label">เฉลย</div>
                        <span class="rv-ans-text">
                            {{ $thaiOpts[$q->correct_answer] ?? '-' }}.
                            <span class="rv-ans-detail">{{ $q->{'option_'.$q->correct_answer} }}</span>
                        </span>
                    </div>
                </div>
                @endif
            </div>
            @else
            {{-- อัตนัย --}}
            <div class="rv-answers">
                <div>
                    <div class="rv-essay-label">
                        <i class="fas fa-pen-alt" style="color:{{ $state === 'skip' ? '#94a3b8' : ($state === 'correct' ? '#16a34a' : ($state === 'partial' ? '#f59e0b' : '#ef4444')) }};"></i>
                        <span>คำตอบของคุณ</span>
                    </div>
                    <div class="rv-essay-box rv-essay-user">
                        {{ $r['user_answer'] ?: '(ไม่ได้ตอบ)' }}
                    </div>
                </div>
                @if($q->essay_answer)
                <div>
                    <div class="rv-essay-label">
                        <i class="fas fa-lightbulb" style="color:#2563eb;"></i>
                        <span>เฉลย</span>
                    </div>
                    <div class="rv-essay-box rv-essay-key">
                        {{ $q->essay_answer }}
                    </div>
                </div>
                    @if(isset($r['similarity']) && $r['similarity'] > 0)
                    @php
                        $simColor = $r['similarity'] >= 80 ? '#16a34a' : ($r['similarity'] >= 50 ? '#f59e0b' : '#ef4444');
                        $simLabel = $r['similarity'] >= 80 ? 'ได้เต็ม' : ($r['similarity'] >= 50 ? 'ได้บางส่วน' : 'ไม่ผ่าน');
                    @endphp
                    <div class="rv-sim">
                        <i class="fas fa-chart-bar" style="color:{{ $simColor }};font-size:.85rem;"></i>
                        <span style="font-size:.8rem;color:#64748b;">ความตรงกัน</span>
                        <div class="rv-sim-bar">
                            <div class="rv-sim-fill" style="width:{{ $r['similarity'] }}%;background:{{ $simColor }};"></div>
                        </div>
                        <span class="rv-sim-text" style="color:{{ $simColor }};">{{ $r['similarity'] }}% — {{ $simLabel }}</span>
                    </div>
                    @endif
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('pretest.show', $examSet) }}" class="btn btn-outline-success mr-2">
            <i class="fas fa-redo mr-1"></i>ทำอีกครั้ง
        </a>
        <a href="{{ route('pretest.index') }}" class="btn btn-outline-secondary mr-2">
            รายการแบบทดสอบ
        </a>
        <a href="{{ route('login') }}" class="btn btn-success">
            <i class="fas fa-sign-in-alt mr-1"></i>เข้าสู่ระบบ
        </a>
    </div>
</div>

@if(!empty($certificate))
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
(function() {
    var verifyUrl = '{{ $verifyUrl }}';

    if (typeof QRCode !== 'undefined') {
        new QRCode(document.getElementById('result-qr'), {
            text: verifyUrl,
            width: 112, height: 112,
            colorDark: '#92400e', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    }

    var shareText = encodeURIComponent('{{ $certificate->recipient_name }} ผ่านแบบทดสอบธรรมศึกษา {{ $examSet->name ?? "" }} คะแนน {{ $scorePercent }}% ');
    document.getElementById('res-line-btn').href = 'https://social-plugins.line.me/lineit/share?url=' + encodeURIComponent(verifyUrl) + '&text=' + shareText;
    document.getElementById('res-fb-btn').href   = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(verifyUrl);
})();

function copyResultLink(btn) {
    var verifyUrl = '{{ $verifyUrl }}';
    navigator.clipboard.writeText(verifyUrl).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>คัดลอกแล้ว!';
        btn.style.background = '#d1fae5';
        btn.style.color = '#065f46';
        btn.style.borderColor = '#6ee7b7';
        setTimeout(function() {
            btn.innerHTML = orig;
            btn.style.background = '';
            btn.style.color = '';
            btn.style.borderColor = '';
        }, 2000);
    }).catch(function() {
        prompt('คัดลอก URL:', verifyUrl);
    });
}
</script>
@endif

<!-- Footer -->
<footer style="background:#1a1a2e;color:#bdc3c7;padding:28px 0 14px;font-size:.82rem;margin-top:40px;">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center mb-3" style="gap:8px;">
            <span style="background:#16a085;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Quiz</span>
            <span style="background:#2980b9;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Kru.One</span>
            <span style="background:#8e44ad;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">ALevel</span>
            <span style="background:#c0392b;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Pali</span>
            <span style="background:#d35400;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Tutor</span>
        </div>
        <div class="text-center mb-2">
            <i class="fas fa-hands-helping mr-1" style="color:#1abc9c;"></i>
            <strong style="color:#ecf0f1;">สนับสนุนโดย</strong> วัดสารอด | สำนักเรียนวัดสารอด เขตราษฎร์บูรณะ กรุงเทพฯ
        </div>
        <div class="text-center mb-3">
            <i class="fas fa-school mr-1" style="color:#1abc9c;"></i>
            สำนักเรียนวัดสารอด เขตราษฏร์บูรณะ กรุงเทพฯ
            &nbsp;|&nbsp; พระปริยัติธรรม &nbsp;|&nbsp; แผนกบาลี &nbsp;|&nbsp; แผนกธรรม
        </div>
        <hr style="border-color:#2c3e50;margin:8px 0 10px;">
        <div class="text-center" style="color:#636e72;font-size:.75rem;">
            &copy; {{ date('Y') }} ระบบข้อสอบธรรมศึกษา &mdash; สงวนลิขสิทธิ์
        </div>
    </div>
</footer>
</body>
</html>
