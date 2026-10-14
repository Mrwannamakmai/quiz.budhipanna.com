<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โจทย์ประจำวัน — ธรรมศึกษา Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; }
        .daily-hero {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
            color: #fff; padding: 48px 0 36px; text-align: center;
        }
        .daily-hero h1 { font-size: 2rem; font-weight: 800; margin-bottom: 6px; }
        .daily-hero .subtitle { font-size: 1rem; opacity: .9; }
        .daily-date-badge {
            display: inline-block; background: rgba(255,255,255,.2);
            border-radius: 20px; padding: 6px 18px; font-size: .9rem; margin-top: 10px;
        }
        .streak-card {
            background: #fff; border-radius: 16px; padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,.08); text-align: center;
        }
        .streak-flame { font-size: 3rem; line-height: 1; }
        .streak-count { font-size: 3rem; font-weight: 800; color: #d97706; line-height: 1.1; }
        .streak-label { font-size: .85rem; color: #92400e; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .streak-sub { font-size: .82rem; color: #6b7280; margin-top: 4px; }
        .info-card {
            background: #fff; border-radius: 16px; padding: 22px;
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
        }
        .info-row { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .info-row:last-child { border-bottom: none; }
        .info-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; }
        .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; margin-top: 12px; }
        .cal-day { text-align: center; }
        .cal-dot {
            width: 32px; height: 32px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .7rem; font-weight: 700; margin: 0 auto 3px;
        }
        .cal-dot.done-pass { background: #d1fae5; color: #065f46; }
        .cal-dot.done-fail { background: #fee2e2; color: #991b1b; }
        .cal-dot.today-done { background: #d97706; color: #fff; box-shadow: 0 0 0 3px #fde68a; }
        .cal-dot.today-pending { background: #fef3c7; color: #92400e; border: 2px dashed #f59e0b; }
        .cal-dot.miss { background: #f1f5f9; color: #94a3b8; }
        .cal-day-label { font-size: .68rem; color: #9ca3af; }
        .btn-start {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff; border: none; border-radius: 30px;
            padding: 14px 40px; font-size: 1.1rem; font-weight: 800;
            cursor: pointer; width: 100%; max-width: 320px;
            box-shadow: 0 4px 16px rgba(217,119,6,.35);
            transition: transform .15s, box-shadow .15s;
        }
        .btn-start:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(217,119,6,.45); text-decoration: none; color: #fff; }
        .btn-result {
            background: #fff; color: #d97706; border: 2px solid #f59e0b;
            border-radius: 30px; padding: 12px 36px; font-size: 1rem; font-weight: 700;
            cursor: pointer; width: 100%; max-width: 320px; display: block;
            text-align: center; text-decoration: none;
        }
        .btn-result:hover { background: #fffbeb; color: #d97706; text-decoration: none; }
        .completed-badge {
            background: #d1fae5; color: #065f46; border-radius: 20px;
            padding: 8px 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav style="background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:10px 0;position:sticky;top:0;z-index:999;">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('landing') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;">
                <i class="fas fa-fire"></i>
            </div>
            <span style="font-size:1.1rem;font-weight:700;color:#d97706;">Daily<span style="color:#555;font-weight:400;"> Challenge</span></span>
        </a>
        <div class="d-flex align-items-center" style="gap:10px;">
            @auth
            <span style="font-size:.88rem;color:#555;">{{ Auth::user()->name }}</span>
            @endauth
            <a href="{{ route('student.dashboard') }}" style="color:#555;font-size:.85rem;text-decoration:none;">
                <i class="fas fa-home mr-1"></i>หน้าหลัก
            </a>
        </div>
    </div>
</nav>

{{-- Hero --}}
<div class="daily-hero">
    @php
        $thaiMonths = ['','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
        $m = intval(date('n'));
        $thaiDate = date('j') . ' ' . $thaiMonths[$m] . ' ' . (date('Y') + 543);
        $thaiDays = ['อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์'];
        $dayName  = $thaiDays[date('w')];
    @endphp
    <div class="container">
        <div style="font-size:3rem;margin-bottom:8px;">🔥</div>
        <h1>โจทย์ประจำวัน</h1>
        <p class="subtitle">ฝึกทำข้อสอบทุกวัน สะสม Streak รักษาความต่อเนื่อง</p>
        <div class="daily-date-badge">
            <i class="fas fa-calendar-day mr-1"></i>
            วัน{{ $dayName }} ที่ {{ $thaiDate }}
        </div>
    </div>
</div>

<div class="container py-4" style="max-width:700px;">

    @if(session('error'))
    <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Streak card --}}
        <div class="col-md-4 mb-3">
            <div class="streak-card h-100">
                <div class="streak-flame">🔥</div>
                <div class="streak-count">{{ $user->daily_streak ?? 0 }}</div>
                <div class="streak-label">วันติดต่อกัน</div>
                <div class="streak-sub">สถิติสูงสุด {{ $user->longest_streak ?? 0 }} วัน</div>
            </div>
        </div>

        {{-- Info + action --}}
        <div class="col-md-8 mb-3">
            <div class="info-card">
                <h6 style="font-weight:800;color:#1e293b;margin-bottom:14px;">
                    <i class="fas fa-bolt mr-1" style="color:#f59e0b;"></i>วันนี้
                </h6>

                <div class="info-row">
                    <div class="info-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-question-circle"></i></div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">10 ข้อ</div>
                        <div style="font-size:.78rem;color:#6b7280;">ปรนัย 4 ตัวเลือก</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="background:#dbeafe;color:#2563eb;"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">ผ่านเมื่อได้ ≥ 70%</div>
                        <div style="font-size:.78rem;color:#6b7280;">7 ข้อขึ้นไป</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-icon" style="background:#dcfce7;color:#16a34a;"><i class="fas fa-fire-alt"></i></div>
                    <div>
                        <div style="font-weight:700;color:#1e293b;">Streak ไม่ขาด</div>
                        <div style="font-size:.78rem;color:#6b7280;">ทำทุกวันเพื่อรักษา streak</div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    @if($attempt)
                        <div class="completed-badge mb-3">
                            <i class="fas fa-check-circle"></i>
                            ทำแล้ววันนี้ — {{ $attempt->score }}/{{ $attempt->total }} คะแนน
                            ({{ $attempt->total > 0 ? round($attempt->score/$attempt->total*100) : 0 }}%)
                        </div>
                        <a href="{{ route('daily.result') }}" class="btn-result">
                            <i class="fas fa-chart-bar mr-1"></i>ดูผลและเฉลย
                        </a>
                    @else
                        <a href="{{ route('daily.play') }}" class="btn-start">
                            <i class="fas fa-play mr-1"></i>เริ่มทำเลย!
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 7-day calendar --}}
    <div class="info-card mb-4">
        <h6 style="font-weight:800;color:#1e293b;margin-bottom:14px;">
            <i class="fas fa-calendar-week mr-1" style="color:#f59e0b;"></i>7 วันที่ผ่านมา
        </h6>
        <div class="cal-grid">
            @for($d = 6; $d >= 0; $d--)
            @php
                $date    = today()->subDays($d)->toDateString();
                $dayAttempt = $history->get($date);
                $isToday = ($date === $today);
                $label   = $thaiDays[today()->subDays($d)->dayOfWeek];
                $shortLabel = mb_substr($label, 0, 2);
                if ($isToday && $attempt) {
                    $dotClass = 'today-done';
                    $icon = '✓';
                } elseif ($isToday) {
                    $dotClass = 'today-pending';
                    $icon = '?';
                } elseif ($dayAttempt) {
                    $dotClass = $dayAttempt->passed ? 'done-pass' : 'done-fail';
                    $icon = $dayAttempt->passed ? '✓' : '✗';
                } else {
                    $dotClass = 'miss';
                    $icon = '-';
                }
            @endphp
            <div class="cal-day">
                <div class="cal-dot {{ $dotClass }}">{{ $icon }}</div>
                <div class="cal-day-label">{{ $shortLabel }}</div>
            </div>
            @endfor
        </div>
        <div style="display:flex;gap:16px;margin-top:12px;flex-wrap:wrap;">
            <span style="font-size:.75rem;color:#6b7280;display:flex;align-items:center;gap:4px;">
                <span style="width:12px;height:12px;background:#d1fae5;border-radius:50%;display:inline-block;"></span>ผ่าน
            </span>
            <span style="font-size:.75rem;color:#6b7280;display:flex;align-items:center;gap:4px;">
                <span style="width:12px;height:12px;background:#fee2e2;border-radius:50%;display:inline-block;"></span>ไม่ผ่าน
            </span>
            <span style="font-size:.75rem;color:#6b7280;display:flex;align-items:center;gap:4px;">
                <span style="width:12px;height:12px;background:#f1f5f9;border-radius:50%;display:inline-block;"></span>ไม่ได้ทำ
            </span>
        </div>
    </div>

    {{-- All-time stats --}}
    @php
        $allAttempts = \App\DailyAttempt::where('user_id', $user->id)->get();
        $totalDone   = $allAttempts->count();
        $totalPass   = $allAttempts->where('passed', true)->count();
    @endphp
    @if($totalDone > 0)
    <div class="info-card mb-4">
        <h6 style="font-weight:800;color:#1e293b;margin-bottom:14px;">
            <i class="fas fa-trophy mr-1" style="color:#f59e0b;"></i>สถิติรวม
        </h6>
        <div class="row text-center">
            <div class="col-4">
                <div style="font-size:1.6rem;font-weight:800;color:#d97706;">{{ $totalDone }}</div>
                <div style="font-size:.78rem;color:#6b7280;">วันที่ทำ</div>
            </div>
            <div class="col-4">
                <div style="font-size:1.6rem;font-weight:800;color:#16a34a;">{{ $totalPass }}</div>
                <div style="font-size:.78rem;color:#6b7280;">วันที่ผ่าน</div>
            </div>
            <div class="col-4">
                <div style="font-size:1.6rem;font-weight:800;color:#2563eb;">{{ $totalDone > 0 ? round($totalPass/$totalDone*100) : 0 }}%</div>
                <div style="font-size:.78rem;color:#6b7280;">อัตราผ่าน</div>
            </div>
        </div>
    </div>
    @endif

</div>

<footer style="background:#1a1a2e;color:#bdc3c7;padding:18px 0;font-size:.8rem;text-align:center;margin-top:20px;">
    &copy; {{ date('Y') }} ระบบข้อสอบธรรมศึกษา
</footer>

</body>
</html>
