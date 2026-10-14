<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลโจทย์ประจำวัน</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; }
        .result-hero {
            text-align: center; padding: 36px 0 28px;
        }
        .score-circle {
            width: 130px; height: 130px; border-radius: 50%;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-weight: 800;
            box-shadow: 0 6px 24px rgba(0,0,0,.12);
        }
        .score-circle.pass { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; }
        .score-circle.fail { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b; }
        .score-pct { font-size: 2.2rem; line-height: 1; }
        .score-label { font-size: .78rem; text-transform: uppercase; letter-spacing: 1px; }
        .streak-card {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border: 2px solid #fbbf24; border-radius: 16px; padding: 20px;
            text-align: center;
        }
        .streak-number { font-size: 2.8rem; font-weight: 800; color: #d97706; line-height: 1; }
        .info-card { background:#fff; border-radius:16px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,.06); }
        .rv-item { padding: 16px 0; border-bottom: 1.5px solid #f1f5f9; }
        .rv-item:last-child { border-bottom: none; }
        .rv-num { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; color:#fff; flex-shrink:0; }
        .rv-num.c { background:#16a34a; }
        .rv-num.w { background:#ef4444; }
        .rv-num.s { background:#94a3b8; }
        .opt-row { display:flex; align-items:center; gap:8px; padding:6px 10px; border-radius:8px; font-size:.85rem; margin-top:6px; }
        .opt-row.user-c { background:#dcfce7; color:#065f46; }
        .opt-row.user-w { background:#fee2e2; color:#991b1b; }
        .opt-row.user-s { background:#f1f5f9; color:#64748b; }
        .opt-row.key    { background:#eff6ff; color:#1e40af; margin-top:4px; }
        .btn-again {
            background: linear-gradient(135deg,#f59e0b,#d97706); color:#fff; border:none;
            border-radius:30px; padding:12px 36px; font-weight:700; font-size:1rem; cursor:pointer;
            box-shadow:0 4px 16px rgba(217,119,6,.3); text-decoration:none; display:inline-block;
        }
        .btn-again:hover { color:#fff; text-decoration:none; transform:translateY(-1px); }
        .streak-anim { animation: bounceIn .6s ease; }
        @keyframes bounceIn {
            0%   { transform: scale(.3); opacity: 0; }
            50%  { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav style="background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:10px 0;">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('daily.index') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;">
                <i class="fas fa-fire"></i>
            </div>
            <span style="font-size:1rem;font-weight:700;color:#d97706;">Daily Challenge</span>
        </a>
        <a href="{{ route('student.dashboard') }}" style="color:#555;font-size:.85rem;text-decoration:none;">
            <i class="fas fa-home mr-1"></i>หน้าหลัก
        </a>
    </div>
</nav>

@php
    $thaiMonths = ['','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
    $m = intval(date('n'));
    $thaiDate  = date('j') . ' ' . $thaiMonths[$m] . ' ' . (date('Y') + 543);
    $thaiOpts  = ['a'=>'ก','b'=>'ข','c'=>'ค','d'=>'ง'];
    $passed    = $resultData['passed'];
    $score     = $resultData['score'];
    $total     = $resultData['total'];
    $streak    = $resultData['streak'];
    $longest   = $resultData['longest'];
    $answers   = $resultData['answers'];
    $correctCnt= collect($answers)->where('correct', true)->count();
    $wrongCnt  = collect($answers)->where('correct', false)->filter(function($a){ return trim($a['user_answer'] ?? '') !== ''; })->count();
    $skipCnt   = collect($answers)->filter(function($a){ return trim($a['user_answer'] ?? '') === ''; })->count();
@endphp

<div class="container py-4" style="max-width:680px;">

    {{-- Score card --}}
    <div class="result-hero">
        <div class="score-circle {{ $passed ? 'pass' : 'fail' }}">
            <div class="score-pct">{{ $scorePct }}%</div>
            <div class="score-label">{{ $passed ? 'ผ่าน' : 'ไม่ผ่าน' }}</div>
        </div>
        <h5 style="font-weight:800;color:#1e293b;margin-bottom:4px;">
            {{ $passed ? '🎉 ยอดเยี่ยม! ผ่านแล้ว!' : '💪 พยายามต่อไปนะ' }}
        </h5>
        <p style="color:#64748b;font-size:.88rem;">{{ $thaiDate }} &middot; {{ $score }}/{{ $total }} คะแนน</p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <span style="font-size:.82rem;color:#16a34a;font-weight:700;"><i class="fas fa-check-circle mr-1"></i>ถูก {{ $correctCnt }}</span>
            <span style="font-size:.82rem;color:#ef4444;font-weight:700;"><i class="fas fa-times-circle mr-1"></i>ผิด {{ $wrongCnt }}</span>
            @if($skipCnt > 0)
            <span style="font-size:.82rem;color:#94a3b8;font-weight:700;"><i class="fas fa-minus-circle mr-1"></i>ไม่ตอบ {{ $skipCnt }}</span>
            @endif
        </div>
    </div>

    {{-- Streak card --}}
    <div class="streak-card mb-4">
        <div class="streak-anim">
            <div style="font-size:2.5rem;">🔥</div>
            <div class="streak-number">{{ $streak }}</div>
            <div style="font-weight:700;color:#92400e;margin-bottom:2px;">วันติดต่อกัน!</div>
            @if($streak === $longest && $longest > 1)
            <div style="font-size:.82rem;color:#a16207;background:rgba(255,255,255,.6);border-radius:10px;padding:4px 12px;display:inline-block;margin-top:6px;">
                <i class="fas fa-trophy mr-1" style="color:#f59e0b;"></i>สถิติใหม่!
            </div>
            @else
            <div style="font-size:.82rem;color:#a16207;">สูงสุด {{ $longest }} วัน</div>
            @endif
        </div>
    </div>

    {{-- Answer breakdown --}}
    <div class="info-card mb-4">
        <h6 style="font-weight:800;color:#1e293b;margin-bottom:14px;">
            <i class="fas fa-clipboard-list mr-1" style="color:#f59e0b;"></i>เฉลยคำตอบ
        </h6>

        @foreach($answers as $qId => $ans)
        @php
            $isCorrect = $ans['correct'];
            $isSkip    = trim($ans['user_answer'] ?? '') === '';
            $state     = $isCorrect ? 'c' : ($isSkip ? 's' : 'w');
        @endphp
        <div class="rv-item">
            <div style="display:flex;align-items:flex-start;gap:10px;">
                <div class="rv-num {{ $state }}">
                    @if($state === 'c') <i class="fas fa-check"></i>
                    @elseif($state === 'w') <i class="fas fa-times"></i>
                    @else <i class="fas fa-minus"></i>
                    @endif
                </div>
                <div style="flex:1;font-size:.9rem;color:#1e293b;line-height:1.6;">
                    {{ $ans['question_text'] ?? '' }}
                </div>
                <div style="font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:12px;flex-shrink:0;
                    {{ $isCorrect ? 'background:#dcfce7;color:#16a34a;' : ($isSkip ? 'background:#f1f5f9;color:#64748b;' : 'background:#fee2e2;color:#991b1b;') }}">
                    {{ $ans['points'] }} คะแนน
                </div>
            </div>
            <div style="margin-left:38px;margin-top:8px;">
                {{-- คำตอบของคุณ --}}
                <div class="opt-row {{ $isCorrect ? 'user-c' : ($isSkip ? 'user-s' : 'user-w') }}">
                    <i class="fas {{ $isCorrect ? 'fa-check' : ($isSkip ? 'fa-minus' : 'fa-times') }}"></i>
                    <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;opacity:.7;min-width:70px;">คำตอบคุณ</span>
                    <span style="font-weight:600;">
                        @if(!$isSkip)
                            {{ $thaiOpts[$ans['user_answer']] ?? $ans['user_answer'] }}.
                            {{ $ans['option_' . ($ans['user_answer'] ?? '')] ?? '' }}
                        @else
                            ไม่ได้ตอบ
                        @endif
                    </span>
                </div>
                {{-- เฉลย (แสดงเมื่อผิด/ไม่ตอบ) --}}
                @if(!$isCorrect)
                <div class="opt-row key">
                    <i class="fas fa-lightbulb"></i>
                    <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;opacity:.7;min-width:70px;">เฉลย</span>
                    <span style="font-weight:600;">
                        {{ $thaiOpts[$ans['correct_answer']] ?? $ans['correct_answer'] }}.
                        {{ $ans['option_' . ($ans['correct_answer'] ?? '')] ?? '' }}
                    </span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- CTAs --}}
    <div class="text-center py-2 mb-4">
        <p style="color:#64748b;font-size:.88rem;margin-bottom:16px;">
            <i class="fas fa-calendar-day mr-1" style="color:#f59e0b;"></i>
            กลับมาพรุ่งนี้เพื่อรักษา streak!
        </p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('daily.index') }}" class="btn-again">
                <i class="fas fa-fire mr-1"></i>หน้า Daily Challenge
            </a>
            <a href="{{ route('pretest.index') }}" style="background:#fff;color:#555;border:2px solid #e2e8f0;border-radius:30px;padding:12px 28px;font-weight:700;font-size:.9rem;text-decoration:none;">
                <i class="fas fa-list mr-1"></i>รายการข้อสอบ
            </a>
        </div>
    </div>

</div>

<footer style="background:#1a1a2e;color:#bdc3c7;padding:18px 0;font-size:.8rem;text-align:center;">
    &copy; {{ date('Y') }} ระบบข้อสอบธรรมศึกษา
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
