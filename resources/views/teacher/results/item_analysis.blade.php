@extends('layouts.teacher')
@section('title','วิเคราะห์ข้อสอบ — '.$examSet->name)
@section('page-title','วิเคราะห์ข้อสอบ (Item Analysis)')

@section('content')
<style>
/* ── Layout ── */
.ia-back { display:inline-flex; align-items:center; gap:6px; font-size:.83rem; color:#64748b; text-decoration:none; margin-bottom:18px; padding:6px 12px; border:1.5px solid #e2e8f0; border-radius:8px; background:#fff; transition:all .15s; }
.ia-back:hover { background:#f1f5f9; color:#1e293b; text-decoration:none; }

.ia-card { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; margin-bottom:20px; overflow:hidden; }
.ia-card-head { padding:14px 20px; border-bottom:1.5px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; gap:8px; flex-wrap:wrap; }
.ia-card-head h6 { margin:0; font-size:.92rem; font-weight:700; color:#1e293b; }
.ia-card-body { padding:20px; }

/* ── Summary stats ── */
.ia-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(130px,1fr)); gap:12px; }
.ia-stat { border-radius:10px; padding:14px 12px; text-align:center; border:1.5px solid; }
.ia-stat .sv { font-size:1.5rem; font-weight:800; display:block; line-height:1.2; }
.ia-stat .sl { font-size:.72rem; margin-top:3px; font-weight:500; }
.ia-stat.blue  { border-color:#bfdbfe; background:#eff6ff; }   .ia-stat.blue  .sv { color:#2563eb; } .ia-stat.blue  .sl { color:#3b82f6; }
.ia-stat.green { border-color:#bbf7d0; background:#f0fdf4; }   .ia-stat.green .sv { color:#16a34a; } .ia-stat.green .sl { color:#22c55e; }
.ia-stat.amber { border-color:#fde68a; background:#fffbeb; }   .ia-stat.amber .sv { color:#d97706; } .ia-stat.amber .sl { color:#f59e0b; }
.ia-stat.red   { border-color:#fecaca; background:#fef2f2; }   .ia-stat.red   .sv { color:#dc2626; } .ia-stat.red   .sl { color:#ef4444; }
.ia-stat.purple{ border-color:#ddd6fe; background:#f5f3ff; }   .ia-stat.purple .sv { color:#7c3aed; }.ia-stat.purple .sl { color:#8b5cf6; }
.ia-stat.teal  { border-color:#99f6e4; background:#f0fdfa; }   .ia-stat.teal  .sv { color:#0d9488; } .ia-stat.teal  .sl { color:#14b8a6; }

/* ── Legend row ── */
.ia-legend { display:flex; gap:16px; flex-wrap:wrap; font-size:.78rem; color:#64748b; }
.ia-dot { width:10px; height:10px; border-radius:3px; display:inline-block; margin-right:4px; vertical-align:middle; }

/* ── Table ── */
.ia-table { width:100%; border-collapse:collapse; font-size:.82rem; }
.ia-table thead th { padding:9px 12px; font-size:.72rem; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:2px solid #e2e8f0; background:#f8fafc; white-space:nowrap; }
.ia-table tbody td { padding:10px 12px; border-bottom:1px solid #f1f5f9; vertical-align:top; }
.ia-table tbody tr:hover { background:#fafbff; }
.ia-table .q-no { width:36px; height:36px; border-radius:8px; background:#f1f5f9; display:inline-flex; align-items:center; justify-content:center; font-weight:700; color:#475569; font-size:.82rem; flex-shrink:0; }

/* ── P-bar (Difficulty) ── */
.p-bar-wrap { display:flex; align-items:center; gap:8px; }
.p-bar { flex:1; height:10px; background:#e2e8f0; border-radius:5px; overflow:hidden; min-width:70px; }
.p-bar-fill { height:100%; border-radius:5px; transition:width .5s ease; }
.p-bar-fill.easy   { background:linear-gradient(90deg,#4ade80,#22c55e); }
.p-bar-fill.medium { background:linear-gradient(90deg,#fbbf24,#f59e0b); }
.p-bar-fill.hard   { background:linear-gradient(90deg,#f87171,#ef4444); }
.p-val { font-size:.82rem; font-weight:700; min-width:38px; text-align:right; }
.p-val.easy   { color:#16a34a; }
.p-val.medium { color:#d97706; }
.p-val.hard   { color:#dc2626; }

/* ── Difficulty badge ── */
.diff-badge { font-size:.7rem; font-weight:600; padding:2px 9px; border-radius:20px; white-space:nowrap; }
.diff-badge.easy   { background:#dcfce7; color:#15803d; }
.diff-badge.medium { background:#fef3c7; color:#b45309; }
.diff-badge.hard   { background:#fef2f2; color:#dc2626; }

/* ── D badge (Discrimination) ── */
.d-badge { font-size:.7rem; font-weight:600; padding:2px 9px; border-radius:20px; white-space:nowrap; }
.d-badge.excellent { background:#ede9fe; color:#6d28d9; }
.d-badge.good      { background:#dbeafe; color:#1d4ed8; }
.d-badge.fair      { background:#fef3c7; color:#b45309; }
.d-badge.poor      { background:#fef2f2; color:#dc2626; }

/* ── Distractor mini bars ── */
.dist-row { display:flex; align-items:center; gap:5px; margin-bottom:3px; font-size:.75rem; }
.dist-row:last-child { margin-bottom:0; }
.dist-opt { width:16px; height:16px; border-radius:4px; background:#e2e8f0; font-size:.68rem; font-weight:700; display:inline-flex; align-items:center; justify-content:center; color:#475569; flex-shrink:0; }
.dist-opt.correct-opt { background:#22c55e; color:#fff; }
.dist-bar-wrap { flex:1; height:8px; background:#f1f5f9; border-radius:4px; overflow:hidden; min-width:50px; }
.dist-bar-fill { height:100%; border-radius:4px; transition:width .5s ease; }
.dist-bar-fill.correct-bar { background:#22c55e; }
.dist-bar-fill.wrong-bar   { background:#94a3b8; }
.dist-pct { min-width:36px; text-align:right; color:#475569; font-weight:500; }

/* ── Dimension badge ── */
.dim-badge { font-size:.68rem; padding:2px 7px; border-radius:12px; color:#fff; font-weight:600; white-space:nowrap; }

/* ── Highlight rows ── */
tr.row-hard  { background:#fff8f8; }
tr.row-poor  { background:#fff8f8; }
tr.row-hard.row-poor { background:#fff0f0; }
</style>

{{-- Back button --}}
<a href="{{ route('teacher.results.index') }}" class="ia-back">
    <i class="fas fa-arrow-left"></i>กลับหน้าสถิติ
</a>

{{-- Exam set info --}}
<div class="ia-card">
    <div class="ia-card-head">
        <div>
            <h6><i class="fas fa-microscope mr-2" style="color:#7c3aed;"></i>{{ $examSet->name }}</h6>
            <div style="font-size:.78rem; color:#94a3b8; margin-top:3px;">
                {{ $examSet->subject->name ?? '-' }}
                @if($examSet->questions_count ?? false) · {{ $examSet->questions_count }} ข้อ @endif
                · ผู้เข้าสอบ {{ $totalAttempts }} คน
            </div>
        </div>
        <div style="font-size:.78rem; color:#94a3b8;">
            <i class="fas fa-info-circle mr-1"></i>คำนวณจากข้อสอบที่ส่งแล้วทั้งหมด
        </div>
    </div>
</div>

@if($totalAttempts === 0)
{{-- No data --}}
<div class="ia-card">
    <div class="ia-card-body text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <p class="text-muted">ยังไม่มีการสอบในชุดข้อสอบนี้ — ไม่สามารถวิเคราะห์ได้</p>
    </div>
</div>

@elseif($items->isEmpty())
<div class="ia-card">
    <div class="ia-card-body text-center py-5">
        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
        <p class="text-muted">ไม่พบข้อมูลคำตอบสำหรับชุดข้อสอบนี้</p>
    </div>
</div>

@else
{{-- Summary cards --}}
<div class="ia-card">
    <div class="ia-card-head">
        <h6><i class="fas fa-chart-bar mr-2" style="color:#3b82f6;"></i>สรุปภาพรวม</h6>
        <div class="ia-legend">
            <span><span class="ia-dot" style="background:#22c55e;"></span>ง่าย (P≥80%)</span>
            <span><span class="ia-dot" style="background:#f59e0b;"></span>ปานกลาง (P 40-79%)</span>
            <span><span class="ia-dot" style="background:#ef4444;"></span>ยาก (P&lt;40%)</span>
            <span style="margin-left:8px;"><span class="ia-dot" style="background:#8b5cf6;"></span>จำแนกดีมาก (D≥40%)</span>
            <span><span class="ia-dot" style="background:#3b82f6;"></span>ดี (D 30-39%)</span>
            <span><span class="ia-dot" style="background:#f59e0b;"></span>พอใช้ (D 20-29%)</span>
            <span><span class="ia-dot" style="background:#ef4444;"></span>ควรปรับปรุง (D&lt;20%)</span>
        </div>
    </div>
    <div class="ia-card-body">
        <div class="ia-stats">
            <div class="ia-stat blue">
                <span class="sv">{{ $items->count() }}</span>
                <span class="sl">ข้อที่วิเคราะห์</span>
            </div>
            <div class="ia-stat blue">
                <span class="sv">{{ $summary['avg_p'] }}%</span>
                <span class="sl">ความยากเฉลี่ย (P̄)</span>
            </div>
            <div class="ia-stat purple">
                <span class="sv">{{ $summary['avg_d'] }}%</span>
                <span class="sl">อำนาจจำแนกเฉลี่ย (D̄)</span>
            </div>
            <div class="ia-stat green">
                <span class="sv">{{ $summary['easy_count'] }}</span>
                <span class="sl">ข้อง่าย</span>
            </div>
            <div class="ia-stat amber">
                <span class="sv">{{ $summary['medium_count'] }}</span>
                <span class="sl">ข้อปานกลาง</span>
            </div>
            <div class="ia-stat red">
                <span class="sv">{{ $summary['hard_count'] }}</span>
                <span class="sl">ข้อยาก</span>
            </div>
            <div class="ia-stat purple">
                <span class="sv">{{ $summary['excellent_d'] + $summary['good_d'] }}</span>
                <span class="sl">จำแนกดี-ดีมาก</span>
            </div>
            <div class="ia-stat red">
                <span class="sv">{{ $summary['poor_d'] }}</span>
                <span class="sl">ควรปรับปรุง</span>
            </div>
        </div>
    </div>
</div>

{{-- Per-question table --}}
<div class="ia-card">
    <div class="ia-card-head">
        <h6><i class="fas fa-list-ol mr-2" style="color:#0d9488;"></i>รายละเอียดแต่ละข้อ</h6>
        <div style="font-size:.75rem; color:#94a3b8;">
            <i class="fas fa-users mr-1"></i>กลุ่มสูง-ต่ำ 27% (n={{ max(1, round($totalAttempts * 0.27)) }} คน/กลุ่ม)
        </div>
    </div>
    <div class="table-responsive">
    <table class="ia-table">
        <thead>
            <tr>
                <th style="width:40px;">ข้อ</th>
                <th>คำถาม</th>
                <th style="min-width:160px;">ความยาก (P)</th>
                <th style="min-width:100px;">ระดับ</th>
                <th style="min-width:100px;">จำแนก (D)</th>
                <th style="min-width:90px;">ระดับ</th>
                <th style="min-width:140px;">การกระจายคำตอบ</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $i => $item)
        @php
            $rowClass = '';
            if ($item['difficulty'] === 'hard' || $item['discrim'] === 'poor') {
                $rowClass = 'row-hard';
            }
        @endphp
        <tr class="{{ $rowClass }}">
            {{-- ข้อที่ --}}
            <td style="text-align:center;">
                <div class="q-no">{{ $i + 1 }}</div>
            </td>

            {{-- คำถาม --}}
            <td>
                <div style="font-size:.8rem; color:#334155; line-height:1.5; max-width:260px;">
                    {!! nl2br(e(mb_substr($item['question']->question_text, 0, 100))) !!}{{ mb_strlen($item['question']->question_text) > 100 ? '…' : '' }}
                </div>
                @if($item['question']->dimension)
                @php $dim = \App\Question::$dimensions[$item['question']->dimension] ?? null; @endphp
                @if($dim)
                <span class="dim-badge mt-1 d-inline-block" style="background:{{ $dim['color'] }};">
                    <i class="fas {{ $dim['icon'] }} mr-1" style="font-size:.6rem;"></i>{{ $dim['short'] }}
                </span>
                @endif
                @endif
                <div style="font-size:.72rem; color:#94a3b8; margin-top:3px;">
                    ตอบ {{ $item['total'] }} ครั้ง · ถูก {{ $item['correct'] }}
                </div>
            </td>

            {{-- ความยาก P --}}
            <td>
                <div class="p-bar-wrap">
                    <div class="p-bar">
                        <div class="p-bar-fill {{ $item['difficulty'] }}" style="width:{{ $item['p_pct'] }}%;"></div>
                    </div>
                    <span class="p-val {{ $item['difficulty'] }}">{{ $item['p_pct'] }}%</span>
                </div>
            </td>

            {{-- ระดับความยาก --}}
            <td>
                <span class="diff-badge {{ $item['difficulty'] }}">
                    @if($item['difficulty']==='easy') ง่าย
                    @elseif($item['difficulty']==='medium') ปานกลาง
                    @else ยาก @endif
                </span>
            </td>

            {{-- อำนาจจำแนก D --}}
            <td>
                @php
                    $dColor = $item['discrim'] === 'excellent' ? '#7c3aed'
                            : ($item['discrim'] === 'good'      ? '#2563eb'
                            : ($item['discrim'] === 'fair'      ? '#d97706' : '#dc2626'));
                    $dAbs = max(0, $item['d_pct']);
                @endphp
                <div style="display:flex; align-items:center; gap:6px;">
                    <div style="flex:1; height:10px; background:#e2e8f0; border-radius:5px; overflow:hidden; min-width:55px;">
                        <div style="width:{{ $dAbs }}%; height:100%; background:{{ $dColor }}; border-radius:5px; transition:width .5s;"></div>
                    </div>
                    <span style="font-size:.82rem; font-weight:700; color:{{ $dColor }}; min-width:38px; text-align:right;">
                        {{ $item['d_pct'] }}%
                    </span>
                </div>
            </td>

            {{-- ระดับอำนาจจำแนก --}}
            <td>
                <span class="d-badge {{ $item['discrim'] }}">
                    @if($item['discrim']==='excellent') ดีมาก
                    @elseif($item['discrim']==='good')  ดี
                    @elseif($item['discrim']==='fair')  พอใช้
                    @else ควรปรับปรุง @endif
                </span>
            </td>

            {{-- Distractor --}}
            <td>
                @if($item['distractors'])
                @foreach($item['distractors'] as $opt => $data)
                @php
                    $isCorrect = strtolower($item['question']->correct_answer ?? '') === $opt;
                @endphp
                <div class="dist-row">
                    <span class="dist-opt {{ $isCorrect ? 'correct-opt' : '' }}">{{ strtoupper($opt) }}</span>
                    <div class="dist-bar-wrap">
                        <div class="dist-bar-fill {{ $isCorrect ? 'correct-bar' : 'wrong-bar' }}" style="width:{{ $data['pct'] }}%;"></div>
                    </div>
                    <span class="dist-pct">{{ $data['pct'] }}%</span>
                </div>
                @endforeach
                @else
                <span style="font-size:.75rem; color:#94a3b8;">อัตนัย</span>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>

{{-- Interpretation guide --}}
<div class="ia-card">
    <div class="ia-card-head">
        <h6><i class="fas fa-book-open mr-2" style="color:#0d9488;"></i>คู่มือการแปลผล</h6>
    </div>
    <div class="ia-card-body">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
            <div>
                <div style="font-size:.82rem; font-weight:700; color:#1e293b; margin-bottom:8px;">
                    <i class="fas fa-tachometer-alt mr-1" style="color:#f59e0b;"></i>ค่าความยาก (P — Difficulty Index)
                </div>
                <table style="width:100%; font-size:.78rem; border-collapse:collapse;">
                    <thead><tr style="background:#f8fafc;">
                        <th style="padding:6px 10px; border:1px solid #e2e8f0; text-align:left;">ช่วงค่า P</th>
                        <th style="padding:6px 10px; border:1px solid #e2e8f0; text-align:left;">ระดับ</th>
                        <th style="padding:6px 10px; border:1px solid #e2e8f0; text-align:left;">ข้อแนะนำ</th>
                    </tr></thead>
                    <tbody>
                        <tr><td style="padding:6px 10px; border:1px solid #e2e8f0;">80–100%</td><td style="padding:6px 10px; border:1px solid #e2e8f0;"><span class="diff-badge easy">ง่าย</span></td><td style="padding:6px 10px; border:1px solid #e2e8f0; color:#64748b;">พิจารณาเพิ่มความยาก</td></tr>
                        <tr><td style="padding:6px 10px; border:1px solid #e2e8f0;">40–79%</td><td style="padding:6px 10px; border:1px solid #e2e8f0;"><span class="diff-badge medium">ปานกลาง</span></td><td style="padding:6px 10px; border:1px solid #e2e8f0; color:#64748b;">เหมาะสม</td></tr>
                        <tr><td style="padding:6px 10px; border:1px solid #e2e8f0;">0–39%</td><td style="padding:6px 10px; border:1px solid #e2e8f0;"><span class="diff-badge hard">ยาก</span></td><td style="padding:6px 10px; border:1px solid #e2e8f0; color:#64748b;">พิจารณาปรับหรือแก้ไขข้อ</td></tr>
                    </tbody>
                </table>
            </div>
            <div>
                <div style="font-size:.82rem; font-weight:700; color:#1e293b; margin-bottom:8px;">
                    <i class="fas fa-sort-amount-up mr-1" style="color:#8b5cf6;"></i>อำนาจจำแนก (D — Discrimination Index)
                </div>
                <table style="width:100%; font-size:.78rem; border-collapse:collapse;">
                    <thead><tr style="background:#f8fafc;">
                        <th style="padding:6px 10px; border:1px solid #e2e8f0; text-align:left;">ช่วงค่า D</th>
                        <th style="padding:6px 10px; border:1px solid #e2e8f0; text-align:left;">ระดับ</th>
                        <th style="padding:6px 10px; border:1px solid #e2e8f0; text-align:left;">ข้อแนะนำ</th>
                    </tr></thead>
                    <tbody>
                        <tr><td style="padding:6px 10px; border:1px solid #e2e8f0;">≥ 40%</td><td style="padding:6px 10px; border:1px solid #e2e8f0;"><span class="d-badge excellent">ดีมาก</span></td><td style="padding:6px 10px; border:1px solid #e2e8f0; color:#64748b;">คงไว้</td></tr>
                        <tr><td style="padding:6px 10px; border:1px solid #e2e8f0;">30–39%</td><td style="padding:6px 10px; border:1px solid #e2e8f0;"><span class="d-badge good">ดี</span></td><td style="padding:6px 10px; border:1px solid #e2e8f0; color:#64748b;">ดีและใช้ได้</td></tr>
                        <tr><td style="padding:6px 10px; border:1px solid #e2e8f0;">20–29%</td><td style="padding:6px 10px; border:1px solid #e2e8f0;"><span class="d-badge fair">พอใช้</span></td><td style="padding:6px 10px; border:1px solid #e2e8f0; color:#64748b;">ควรปรับปรุงตัวลวง</td></tr>
                        <tr><td style="padding:6px 10px; border:1px solid #e2e8f0;">< 20%</td><td style="padding:6px 10px; border:1px solid #e2e8f0;"><span class="d-badge poor">ควรปรับปรุง</span></td><td style="padding:6px 10px; border:1px solid #e2e8f0; color:#64748b;">ควรแก้ไขหรือตัดออก</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin-top:14px; font-size:.75rem; color:#94a3b8; border-top:1px solid #f1f5f9; padding-top:10px;">
            <i class="fas fa-info-circle mr-1"></i>
            D คำนวณจาก P(กลุ่มสูง) − P(กลุ่มต่ำ) โดยใช้กลุ่มสูงสุดและต่ำสุด 27% ของผู้สอบเรียงตามคะแนนรวม
        </div>
    </div>
</div>
@endif

@endsection
