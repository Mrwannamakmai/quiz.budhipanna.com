@extends('layouts.student')
@section('title','ผลการสอบ')

@section('content')
<div class="container py-4" style="max-width:750px;">
    <!-- Result summary card -->
    <div class="card mb-4 text-center shadow-sm
        {{ $attempt->is_passed ? 'border-success' : 'border-danger' }}">
        <div class="card-body py-4">
            @if($attempt->is_passed)
                <div class="text-success mb-2"><i class="fas fa-check-circle fa-3x"></i></div>
                <h4 class="text-success">ผ่านแล้ว!</h4>
            @else
                <div class="text-danger mb-2"><i class="fas fa-times-circle fa-3x"></i></div>
                <h4 class="text-danger">ยังไม่ผ่าน</h4>
            @endif
            <div class="display-4 font-weight-bold my-2">{{ number_format($attempt->score_percentage, 1) }}%</div>
            <p class="text-muted">คะแนน {{ $attempt->score }} / {{ $attempt->total_points }}</p>
            <p class="text-muted small">เกณฑ์ผ่าน {{ $attempt->examSet->pass_percentage }}%</p>
        </div>
    </div>

    @if($attempt->certificate)
    <div class="alert alert-success d-flex align-items-center">
        <i class="fas fa-certificate fa-2x mr-3 text-warning"></i>
        <div>
            <strong>ได้รับใบประกาศแล้ว!</strong>
            <div><code>{{ $attempt->certificate->certificate_no }}</code></div>
        </div>
        <a href="{{ route('student.certificates.show', $attempt->certificate) }}"
           class="btn btn-warning btn-sm ml-auto">ดูใบประกาศ</a>
    </div>
    @endif

    @if($hasDimData)
    <!-- Dimension Analysis -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex align-items-center">
            <i class="fas fa-chart-pie mr-2 text-primary"></i>
            <strong>วิเคราะห์พฤติกรรมรายบุคคล (4 ภาพ)</strong>
        </div>
        <div class="card-body">
            @php
                $descriptions = [
                    'kaya' => [
                        [80, 'ดีมาก', 'มีความรู้ความเข้าใจเรื่องลักษณะทางกายภาพ สถานที่ และเหตุการณ์ภายนอกได้อย่างถูกต้องและครบถ้วน', 'success'],
                        [60, 'ดี',    'มีความเข้าใจเรื่องทางกายภาพได้เป็นอย่างดี แต่ยังมีบางส่วนที่ควรทบทวนเพิ่มเติม', 'info'],
                        [40, 'พอใช้', 'เข้าใจเรื่องทางกายภาพได้บางส่วน ควรศึกษาเพิ่มเติมเกี่ยวกับสถานที่และเหตุการณ์ต่างๆ', 'warning'],
                        [0,  'ต้องปรับปรุง', 'ยังขาดความเข้าใจในด้านกายภาพ ควรทบทวนเรื่องสถานที่สำคัญและลักษณะภายนอกให้มากขึ้น', 'danger'],
                    ],
                    'conduct' => [
                        [80, 'ดีมาก', 'เข้าใจหลักพฤติกรรมและจริยธรรมได้อย่างลึกซึ้ง สามารถประยุกต์ใช้ในชีวิตประจำวันได้', 'success'],
                        [60, 'ดี',    'มีความเข้าใจในหลักพฤติกรรมดี ควรเสริมสร้างความเข้าใจในเรื่องจริยธรรมให้ลึกยิ่งขึ้น', 'info'],
                        [40, 'พอใช้', 'เข้าใจหลักพฤติกรรมได้บ้าง ควรศึกษาเรื่องการปฏิบัติตนและศีลธรรมเพิ่มเติม', 'warning'],
                        [0,  'ต้องปรับปรุง', 'ยังขาดความเข้าใจในด้านพฤติกรรม ควรเรียนรู้หลักการดำเนินชีวิตที่ถูกต้องให้มากขึ้น', 'danger'],
                    ],
                    'citta' => [
                        [80, 'ดีมาก', 'มีความเข้าใจในด้านจิตใจและจิตวิทยาได้อย่างลึกซึ้ง รับรู้และเข้าใจสภาวะจิตได้ดีเยี่ยม', 'success'],
                        [60, 'ดี',    'เข้าใจด้านจิตใจได้ดี ควรฝึกฝนการสังเกตสภาวะจิตและอารมณ์ให้ลึกยิ่งขึ้น', 'info'],
                        [40, 'พอใช้', 'มีความเข้าใจจิตใจเบื้องต้น ควรศึกษาเรื่องสภาวะจิตและการพัฒนาจิตเพิ่มเติม', 'warning'],
                        [0,  'ต้องปรับปรุง', 'ยังขาดความเข้าใจด้านจิตใจ ควรทบทวนเรื่องสภาวะจิต สมาธิ และการพัฒนาจิตใจ', 'danger'],
                    ],
                    'panna' => [
                        [80, 'ดีมาก', 'มีปัญญาและความเข้าใจในหลักธรรมอย่างลึกซึ้ง สามารถคิดวิเคราะห์และประยุกต์ใช้ความรู้ได้ดีเยี่ยม', 'success'],
                        [60, 'ดี',    'มีปัญญาและความเข้าใจในหลักธรรมได้ดี ควรเสริมสร้างการคิดวิเคราะห์เพิ่มเติม', 'info'],
                        [40, 'พอใช้', 'มีความเข้าใจด้านปัญญาบ้าง ควรฝึกฝนการคิดวิเคราะห์และศึกษาหลักธรรมให้ลึกขึ้น', 'warning'],
                        [0,  'ต้องปรับปรุง', 'ยังขาดความเข้าใจด้านปัญญา ควรทบทวนหลักธรรมและฝึกการคิดวิเคราะห์อย่างสม่ำเสมอ', 'danger'],
                    ],
                ];
            @endphp

            <div class="row">
            @foreach($dims as $key => $dim)
            @php
                $d     = $dimAnalysis[$key];
                $pct   = $d['points'] > 0 ? round($d['earned'] / $d['points'] * 100) : 0;
                $descs = $descriptions[$key];
                $row   = collect($descs)->first(fn($r) => $pct >= $r[0]);
                [$minPct, $grade, $text, $color] = $row;
                $bar = ['success'=>'#27ae60','info'=>'#2980b9','warning'=>'#e67e22','danger'=>'#e74c3c'][$color];
            @endphp
            <div class="col-md-6 mb-3">
                <div class="border rounded p-3 h-100" style="border-left:4px solid {{ $dim['color'] }} !important;">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                             style="width:36px;height:36px;background:{{ $dim['color'] }}20;">
                            <i class="fas {{ $dim['icon'] }}" style="color:{{ $dim['color'] }};font-size:.95rem;"></i>
                        </div>
                        <div>
                            <strong style="color:{{ $dim['color'] }};">{{ $dim['label'] }}</strong>
                            @if($d['total'] > 0)
                                <small class="text-muted d-block">{{ $d['correct'] }}/{{ $d['total'] }} ข้อ</small>
                            @else
                                <small class="text-muted d-block">ไม่มีข้อสอบในด้านนี้</small>
                            @endif
                        </div>
                        <span class="ml-auto badge badge-{{ $color }}" style="font-size:.85rem;">{{ $grade }}</span>
                    </div>

                    @if($d['total'] > 0)
                    <div class="progress mb-2" style="height:10px;border-radius:5px;">
                        <div class="progress-bar" role="progressbar"
                             style="width:{{ $pct }}%;background:{{ $bar }};border-radius:5px;"
                             aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">{{ $pct }}%</small>
                        <small class="text-muted">{{ $d['earned'] }}/{{ $d['points'] }} คะแนน</small>
                    </div>
                    <p class="text-muted mb-0" style="font-size:.82rem;line-height:1.5;">{{ $text }}</p>
                    @else
                    <p class="text-muted mb-0" style="font-size:.82rem;">ยังไม่มีข้อสอบที่ระบุด้านนี้</p>
                    @endif
                </div>
            </div>
            @endforeach
            </div>

            {{-- Summary radar-style score bar --}}
            @php
                $allTagged = collect($dimAnalysis)->sum('total');
                $allCorrect = collect($dimAnalysis)->sum('correct');
            @endphp
            @if($allTagged > 0)
            <div class="mt-2 p-3 rounded" style="background:#f8f9fa;border:1px solid #dee2e6;">
                <small class="text-muted">
                    <i class="fas fa-info-circle mr-1"></i>
                    วิเคราะห์จากข้อสอบที่ระบุด้านทั้งหมด <strong>{{ $allTagged }}</strong> ข้อ
                    (ตอบถูก <strong class="text-success">{{ $allCorrect }}</strong> ข้อ)
                </small>
            </div>
            @endif
        </div>
    </div>
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
    .rv-num.pending { background:#6366f1; }

    .rv-q-text { flex:1; font-size:.92rem; color:#1e293b; line-height:1.6; }
    .rv-status { flex-shrink:0; font-size:.78rem; font-weight:700; padding:4px 12px; border-radius:20px; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; }
    .rv-status.correct { background:#dcfce7; color:#15803d; }
    .rv-status.partial { background:#fef3c7; color:#92400e; }
    .rv-status.wrong { background:#fee2e2; color:#991b1b; }
    .rv-status.skip { background:#f1f5f9; color:#64748b; }
    .rv-status.pending { background:#eef2ff; color:#4338ca; }

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
    </style>

    <div style="border-radius:14px; box-shadow:0 1px 6px rgba(0,0,0,.08); overflow:hidden; border:1.5px solid #f1f5f9;">
        <div class="rv-header">
            <i class="fas fa-clipboard-list"></i>
            <h6>รายละเอียดคำตอบ</h6>
            <span class="rv-summary">{{ $attempt->answers->count() }} ข้อ</span>
        </div>

        @foreach($attempt->answers as $i => $ans)
        @php
            $q = $ans->question;
            $isMC = $q->type === 'multiple_choice';

            if ($isMC) {
                $state = $ans->is_correct ? 'correct' : 'wrong';
            } else {
                if (!$ans->is_graded) {
                    $state = 'pending';
                } elseif ($ans->points_earned >= $q->points) {
                    $state = 'correct';
                } elseif ($ans->points_earned > 0) {
                    $state = 'partial';
                } else {
                    $state = 'wrong';
                }
            }
            if (!$ans->answer && $ans->answer !== '0') $state = 'skip';

            $thaiOpts = ['a'=>'ก','b'=>'ข','c'=>'ค','d'=>'ง'];
            $opts = $q->options ?? [];
            $userKey = $ans->answer ? strtolower($ans->answer) : null;
            $correctKey = $q->correct_answer ? strtolower($q->correct_answer) : null;
        @endphp
        <div class="rv-item">
            <div class="rv-q-top">
                <div class="rv-num {{ $state }}">{{ $i + 1 }}</div>
                <div class="rv-q-text">{{ $q->question_text }}</div>
                <div class="rv-status {{ $state }}">
                    @if($state === 'correct')
                        <i class="fas fa-check-circle"></i> ถูก {{ $ans->points_earned }}/{{ $q->points }}
                    @elseif($state === 'partial')
                        <i class="fas fa-adjust"></i> บางส่วน {{ $ans->points_earned }}/{{ $q->points }}
                    @elseif($state === 'wrong')
                        <i class="fas fa-times-circle"></i> ผิด 0/{{ $q->points }}
                    @elseif($state === 'pending')
                        <i class="fas fa-clock"></i> รอตรวจ
                    @else
                        <i class="fas fa-minus-circle"></i> ไม่ตอบ 0/{{ $q->points }}
                    @endif
                </div>
            </div>

            @if($isMC)
            <div class="rv-answers">
                <div class="rv-ans-box rv-ans-user {{ $state }}">
                    <div class="rv-icon">
                        @if($state === 'correct') <i class="fas fa-check"></i>
                        @elseif($state === 'wrong') <i class="fas fa-times"></i>
                        @else <i class="fas fa-minus"></i>
                        @endif
                    </div>
                    <div>
                        <div class="rv-ans-label">คำตอบของคุณ</div>
                        <span class="rv-ans-text">
                            @if($userKey)
                                {{ $thaiOpts[$userKey] ?? strtoupper($userKey) }}.
                                <span class="rv-ans-detail">{{ $opts[$userKey] ?? '' }}</span>
                            @else
                                ไม่ได้ตอบ
                            @endif
                        </span>
                    </div>
                </div>
                @if(!$ans->is_correct && $correctKey)
                <div class="rv-ans-box rv-ans-key">
                    <div class="rv-icon"><i class="fas fa-lightbulb"></i></div>
                    <div>
                        <div class="rv-ans-label">เฉลย</div>
                        <span class="rv-ans-text">
                            {{ $thaiOpts[$correctKey] ?? strtoupper($correctKey) }}.
                            <span class="rv-ans-detail">{{ $opts[$correctKey] ?? '' }}</span>
                        </span>
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="rv-answers">
                <div>
                    <div class="rv-essay-label">
                        <i class="fas fa-pen-alt" style="color:{{ $state === 'skip' ? '#94a3b8' : ($state === 'pending' ? '#6366f1' : ($state === 'correct' ? '#16a34a' : '#ef4444')) }};"></i>
                        <span>คำตอบของคุณ</span>
                    </div>
                    <div class="rv-essay-box rv-essay-user">
                        {{ $ans->answer ?: '(ไม่มีคำตอบ)' }}
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
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-3 d-flex">
        <a href="{{ route('student.exams.show', $attempt->examSet) }}" class="btn btn-outline-secondary mr-2">
            <i class="fas fa-arrow-left mr-1"></i>กลับ
        </a>
        <a href="{{ route('student.exams.index') }}" class="btn btn-outline-primary">
            ชุดข้อสอบทั้งหมด
        </a>
    </div>
</div>
@endsection
