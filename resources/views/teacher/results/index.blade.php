@extends('layouts.teacher')
@section('title','สถิติผลสอบนักเรียน')
@section('page-title','สถิติผลสอบนักเรียน')

@section('content')
<style>
/* ─── Stats Cards ─── */
.rs-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:14px; margin-bottom:24px; }
.rs-stat { background:#fff; border-radius:12px; padding:18px 16px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1.5px solid #f1f5f9; transition:transform .15s,box-shadow .15s; }
.rs-stat:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.1); }
.rs-stat .rs-val { font-size:1.7rem; font-weight:800; display:block; line-height:1.2; }
.rs-stat .rs-lbl { font-size:.73rem; color:#94a3b8; margin-top:4px; font-weight:500; }
.rs-stat.c-blue .rs-val { color:#3b82f6; }
.rs-stat.c-green .rs-val { color:#16a34a; }
.rs-stat.c-red .rs-val { color:#ef4444; }
.rs-stat.c-purple .rs-val { color:#7c3aed; }
.rs-stat.c-amber .rs-val { color:#f59e0b; }
.rs-stat.c-teal .rs-val { color:#0d9488; }

/* ─── Section ─── */
.rs-section { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; margin-bottom:20px; overflow:hidden; }
.rs-section-head { padding:16px 20px; border-bottom:1.5px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; }
.rs-section-head h6 { margin:0; font-size:.92rem; font-weight:700; color:#1e293b; }

/* ─── Chart ─── */
.rs-chart-wrap { padding:20px; }
.rs-bar-chart { display:flex; align-items:flex-end; gap:8px; height:180px; padding:0 4px; }
.rs-bar-col { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; height:100%; }
.rs-bar { width:100%; min-width:20px; border-radius:6px 6px 0 0; transition:height .5s ease; position:relative; }
.rs-bar.passed { background:linear-gradient(180deg,#22c55e,#16a34a); }
.rs-bar.failed { background:linear-gradient(180deg,#f87171,#ef4444); }
.rs-bar-val { font-size:.7rem; font-weight:700; color:#1e293b; margin-bottom:4px; }
.rs-bar-lbl { font-size:.65rem; color:#94a3b8; margin-top:6px; text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%; }

/* ─── Distribution Chart ─── */
.rs-dist { display:flex; align-items:flex-end; gap:6px; height:140px; padding:0 4px; }
.rs-dist-col { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; height:100%; }
.rs-dist-bar { width:100%; border-radius:4px 4px 0 0; background:linear-gradient(180deg,#818cf8,#6366f1); transition:height .5s ease; min-height:2px; }
.rs-dist-val { font-size:.65rem; font-weight:700; color:#475569; margin-bottom:3px; }
.rs-dist-lbl { font-size:.6rem; color:#94a3b8; margin-top:4px; }

/* ─── Per Exam Set Table ─── */
.rs-es-table { width:100%; border-collapse:collapse; }
.rs-es-table thead th { padding:10px 14px; font-size:.75rem; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:2px solid #e2e8f0; text-align:left; white-space:nowrap; background:#f8fafc; }
.rs-es-table tbody td { padding:10px 14px; font-size:.83rem; color:#1e293b; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
.rs-es-table tbody tr:hover { background:#f8fafc; }

.rs-progress { height:8px; background:#e2e8f0; border-radius:4px; overflow:hidden; min-width:80px; }
.rs-progress-fill { height:100%; border-radius:4px; transition:width .5s ease; }
.rs-progress-fill.high { background:linear-gradient(90deg,#22c55e,#16a34a); }
.rs-progress-fill.mid  { background:linear-gradient(90deg,#fbbf24,#f59e0b); }
.rs-progress-fill.low  { background:linear-gradient(90deg,#f87171,#ef4444); }

.rs-rate { font-weight:700; font-size:.82rem; }
.rs-rate.high { color:#16a34a; }
.rs-rate.mid  { color:#f59e0b; }
.rs-rate.low  { color:#ef4444; }

/* ─── Filter ─── */
.rs-filter { background:#fff; border-radius:12px; padding:14px 20px; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1.5px solid #f1f5f9; margin-bottom:18px; }
.rs-filter form { display:flex; gap:10px; flex-wrap:wrap; align-items:end; }
.rs-filter .fg { display:flex; flex-direction:column; gap:3px; }
.rs-filter label { font-size:.72rem; font-weight:600; color:#64748b; text-transform:uppercase; }
.rs-filter select,
.rs-filter input[type=text] { border:1.5px solid #e2e8f0; border-radius:8px; padding:7px 12px; font-size:.82rem; background:#f8fafc; min-width:140px; }
.rs-filter select:focus,
.rs-filter input:focus { outline:none; border-color:#3b82f6; }
.rs-btn-search { background:#3b82f6; color:#fff; border:none; border-radius:8px; padding:8px 16px; font-size:.82rem; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:5px; }
.rs-btn-search:hover { background:#2563eb; }
.rs-btn-reset { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:8px; padding:8px 16px; font-size:.82rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; }
.rs-btn-reset:hover { background:#f1f5f9; color:#334155; text-decoration:none; }

/* ─── Results Table ─── */
.rs-table { width:100%; border-collapse:collapse; }
.rs-table thead th { padding:10px 14px; font-size:.75rem; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:2px solid #e2e8f0; text-align:left; white-space:nowrap; background:#f8fafc; }
.rs-table tbody td { padding:10px 14px; font-size:.83rem; color:#1e293b; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
.rs-table tbody tr:hover { background:#fafbfd; }

.badge-pass { font-size:.72rem; font-weight:600; padding:3px 10px; border-radius:20px; background:#dcfce7; color:#15803d; }
.badge-fail { font-size:.72rem; font-weight:600; padding:3px 10px; border-radius:20px; background:#fef2f2; color:#dc2626; }
.badge-pending { font-size:.72rem; font-weight:600; padding:3px 10px; border-radius:20px; background:#fef3c7; color:#b45309; }

.rs-view-btn { width:32px; height:32px; border-radius:7px; border:1.5px solid #e2e8f0; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:13px; color:#64748b; text-decoration:none; transition:all .15s; }
.rs-view-btn:hover { background:#eff6ff; border-color:#93c5fd; color:#3b82f6; text-decoration:none; }

.rs-score-bar { display:inline-flex; align-items:center; gap:8px; }
.rs-score-mini { width:60px; height:6px; background:#e2e8f0; border-radius:3px; overflow:hidden; }
.rs-score-mini-fill { height:100%; border-radius:3px; }

/* ─── Donut ─── */
.rs-donut-wrap { display:flex; align-items:center; justify-content:center; gap:30px; padding:20px; }
.rs-donut { position:relative; width:160px; height:160px; }
.rs-donut svg { transform:rotate(-90deg); }
.rs-donut-center { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; }
.rs-donut-pct { font-size:1.6rem; font-weight:800; color:#1e293b; }
.rs-donut-sub { font-size:.72rem; color:#94a3b8; }
.rs-donut-legend { display:flex; flex-direction:column; gap:10px; }
.rs-legend-item { display:flex; align-items:center; gap:8px; font-size:.82rem; color:#475569; }
.rs-legend-dot { width:12px; height:12px; border-radius:3px; flex-shrink:0; }

/* ─── Tabs ─── */
.rs-tabs { display:flex; gap:4px; padding:0 20px; border-bottom:1.5px solid #f1f5f9; }
.rs-tab { padding:10px 16px; font-size:.82rem; font-weight:600; color:#94a3b8; cursor:pointer; border-bottom:2px solid transparent; transition:all .15s; background:none; border-top:none; border-left:none; border-right:none; }
.rs-tab:hover { color:#64748b; }
.rs-tab.active { color:#3b82f6; border-bottom-color:#3b82f6; }
.rs-tab-content { display:none; }
.rs-tab-content.active { display:block; }

/* ─── Empty ─── */
.rs-empty { text-align:center; padding:50px 20px; color:#94a3b8; }
.rs-empty i { font-size:40px; margin-bottom:10px; display:block; color:#cbd5e1; }
</style>

{{-- Stats Cards --}}
<div class="rs-stats">
    <div class="rs-stat c-blue">
        <span class="rs-val">{{ $stats['total_attempts'] }}</span>
        <span class="rs-lbl">การสอบทั้งหมด</span>
    </div>
    <div class="rs-stat c-purple">
        <span class="rs-val">{{ $stats['total_students'] }}</span>
        <span class="rs-lbl">นักเรียนที่สอบ</span>
    </div>
    <div class="rs-stat c-green">
        <span class="rs-val">{{ $stats['passed'] }}</span>
        <span class="rs-lbl">ผ่าน</span>
    </div>
    <div class="rs-stat c-red">
        <span class="rs-val">{{ $stats['failed'] }}</span>
        <span class="rs-lbl">ไม่ผ่าน</span>
    </div>
    <div class="rs-stat c-teal">
        <span class="rs-val">{{ $stats['avg_score'] }}%</span>
        <span class="rs-lbl">คะแนนเฉลี่ย</span>
    </div>
    <div class="rs-stat c-amber">
        <span class="rs-val">{{ $stats['pending_essay'] }}</span>
        <span class="rs-lbl">รอตรวจอัตนัย</span>
    </div>
</div>

{{-- Charts row --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:20px;">
    {{-- Pass/Fail Donut --}}
    <div class="rs-section">
        <div class="rs-section-head">
            <h6><i class="fas fa-chart-pie mr-2" style="color:#6366f1;"></i>อัตราผ่าน/ไม่ผ่าน</h6>
        </div>
        <div class="rs-donut-wrap">
            @php
                $passRate = $stats['total_attempts'] > 0 ? round($stats['passed'] / $stats['total_attempts'] * 100, 1) : 0;
                $circumference = 2 * 3.14159 * 62;
                $passLen = $circumference * $passRate / 100;
                $failLen = $circumference - $passLen;
            @endphp
            <div class="rs-donut">
                <svg width="160" height="160" viewBox="0 0 160 160">
                    <circle cx="80" cy="80" r="62" fill="none" stroke="#fee2e2" stroke-width="18"/>
                    <circle cx="80" cy="80" r="62" fill="none" stroke="#22c55e" stroke-width="18"
                            stroke-dasharray="{{ $passLen }} {{ $failLen }}"
                            stroke-linecap="round"/>
                </svg>
                <div class="rs-donut-center">
                    <span class="rs-donut-pct">{{ $passRate }}%</span>
                    <span class="rs-donut-sub">อัตราผ่าน</span>
                </div>
            </div>
            <div class="rs-donut-legend">
                <div class="rs-legend-item">
                    <span class="rs-legend-dot" style="background:#22c55e;"></span>
                    ผ่าน {{ $stats['passed'] }} ครั้ง
                </div>
                <div class="rs-legend-item">
                    <span class="rs-legend-dot" style="background:#fee2e2;"></span>
                    ไม่ผ่าน {{ $stats['failed'] }} ครั้ง
                </div>
                <div class="rs-legend-item" style="color:#94a3b8; font-size:.75rem;">
                    จากทั้งหมด {{ $stats['total_attempts'] }} ครั้ง
                </div>
            </div>
        </div>
    </div>

    {{-- Score Distribution --}}
    <div class="rs-section">
        <div class="rs-section-head">
            <h6><i class="fas fa-chart-bar mr-2" style="color:#6366f1;"></i>การกระจายคะแนน (%)</h6>
        </div>
        <div class="rs-chart-wrap">
            @php $maxDist = $distribution->max('count') ?: 1; @endphp
            <div class="rs-dist">
                @foreach($distribution as $d)
                <div class="rs-dist-col">
                    <span class="rs-dist-val">{{ $d['count'] }}</span>
                    <div class="rs-dist-bar" style="height:{{ max(2, $d['count'] / $maxDist * 100) }}%;"></div>
                    <span class="rs-dist-lbl">{{ $d['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Per Exam Set Stats --}}
<div class="rs-section" style="margin-bottom:20px;">
    <div class="rs-section-head">
        <h6><i class="fas fa-list-alt mr-2" style="color:#3b82f6;"></i>สถิติแต่ละชุดข้อสอบ</h6>
    </div>
    @if($examSetStats->isEmpty())
        <div class="rs-empty"><i class="fas fa-inbox"></i>ยังไม่มีข้อมูล</div>
    @else
    <div class="table-responsive">
    <table class="rs-es-table">
        <thead>
            <tr>
                <th>ชุดข้อสอบ</th>
                <th>หมวดวิชา</th>
                <th style="text-align:center;">สอบทั้งหมด</th>
                <th style="text-align:center;">ผ่าน</th>
                <th style="text-align:center;">ไม่ผ่าน</th>
                <th>อัตราผ่าน</th>
                <th style="text-align:center;">คะแนนเฉลี่ย</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($examSetStats as $es)
            @php
                $rateClass = $es['pass_rate'] >= 70 ? 'high' : ($es['pass_rate'] >= 40 ? 'mid' : 'low');
            @endphp
            <tr>
                <td style="font-weight:600;">{{ $es['name'] }}</td>
                <td style="color:#64748b;">{{ $es['subject'] }}</td>
                <td style="text-align:center; font-weight:600;">{{ $es['total'] }}</td>
                <td style="text-align:center; color:#16a34a; font-weight:600;">{{ $es['passed'] }}</td>
                <td style="text-align:center; color:#ef4444; font-weight:600;">{{ $es['failed'] }}</td>
                <td>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div class="rs-progress">
                            <div class="rs-progress-fill {{ $rateClass }}" style="width:{{ $es['pass_rate'] }}%;"></div>
                        </div>
                        <span class="rs-rate {{ $rateClass }}">{{ $es['pass_rate'] }}%</span>
                    </div>
                </td>
                <td style="text-align:center; font-weight:600;">{{ $es['avg_score'] }}%</td>
                <td>
                    <a href="{{ route('teacher.results.item_analysis', $es['id']) }}"
                       class="rs-view-btn" title="วิเคราะห์ข้อสอบ (Item Analysis)"
                       style="width:auto; padding:0 10px; gap:4px; font-size:.72rem; white-space:nowrap;">
                        <i class="fas fa-microscope"></i> วิเคราะห์
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    @endif
</div>

{{-- Filter + Attempts Table --}}
<div class="rs-section">
    <div class="rs-section-head">
        <h6><i class="fas fa-table mr-2" style="color:#3b82f6;"></i>รายการผลสอบ</h6>
        <a href="{{ route('teacher.results.export', request()->query()) }}"
           class="rs-btn-search" style="font-size:.78rem; padding:6px 14px; text-decoration:none;">
            <i class="fas fa-file-csv mr-1"></i>Export CSV
        </a>
    </div>

    <div class="rs-filter" style="border:none; box-shadow:none; border-radius:0; border-bottom:1.5px solid #f1f5f9; margin-bottom:0;">
        <form method="GET" action="{{ route('teacher.results.index') }}">
            <div class="fg">
                <label>นักเรียน</label>
                <select name="user_id" style="min-width:180px;">
                    <option value="">-- ทุกคน --</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ request('user_id')==$st->id?'selected':'' }}>{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label>ชุดข้อสอบ</label>
                <select name="exam_set_id">
                    <option value="">-- ทั้งหมด --</option>
                    @foreach($examSets as $es)
                        <option value="{{ $es->id }}" {{ request('exam_set_id')==$es->id?'selected':'' }}>{{ $es->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label>ผลสอบ</label>
                <select name="passed">
                    <option value="">-- ทั้งหมด --</option>
                    <option value="1" {{ request('passed')==='1'?'selected':'' }}>ผ่าน</option>
                    <option value="0" {{ request('passed')==='0'?'selected':'' }}>ไม่ผ่าน</option>
                </select>
            </div>
            <div class="fg">
                <label>ค้นหาชื่อ</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="พิมพ์ชื่อ...">
            </div>
            <div class="fg" style="justify-content:flex-end;">
                <label>&nbsp;</label>
                <div style="display:flex;gap:6px;">
                    <button type="submit" class="rs-btn-search"><i class="fas fa-search mr-1"></i>ค้นหา</button>
                    <a href="{{ route('teacher.results.index') }}" class="rs-btn-reset"><i class="fas fa-redo mr-1"></i>ล้าง</a>
                </div>
            </div>
        </form>
    </div>

    @if($attempts->isEmpty())
        <div class="rs-empty"><i class="fas fa-inbox"></i>ยังไม่มีผลการสอบ</div>
    @else
    <div class="table-responsive">
    <table class="rs-table">
        <thead>
            <tr>
                <th>#</th>
                <th>นักเรียน</th>
                <th>ชุดข้อสอบ</th>
                <th>คะแนน</th>
                <th>ผล</th>
                <th>เวลาที่ส่ง</th>
                <th style="width:50px;"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($attempts as $i => $attempt)
            @php
                $pct = $attempt->total_points > 0 ? round($attempt->score / $attempt->total_points * 100, 1) : 0;
                $barColor = $attempt->passed ? '#22c55e' : '#ef4444';
            @endphp
            <tr>
                <td style="color:#94a3b8;">{{ $attempts->firstItem() + $i }}</td>
                <td style="font-weight:600;">{{ $attempt->user->name ?? '-' }}</td>
                <td style="color:#475569;">{{ $attempt->examSet->name ?? '-' }}</td>
                <td>
                    <div class="rs-score-bar">
                        <span style="font-weight:600;">{{ $attempt->score }}/{{ $attempt->total_points }}</span>
                        <div class="rs-score-mini">
                            <div class="rs-score-mini-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                        </div>
                        <span style="font-size:.75rem; color:#94a3b8;">{{ $pct }}%</span>
                    </div>
                </td>
                <td>
                    @if($attempt->passed)
                        <span class="badge-pass"><i class="fas fa-check mr-1"></i>ผ่าน</span>
                    @else
                        <span class="badge-fail"><i class="fas fa-times mr-1"></i>ไม่ผ่าน</span>
                    @endif
                </td>
                <td style="color:#64748b; font-size:.82rem;">{{ $attempt->submitted_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('teacher.results.show', $attempt) }}" class="rs-view-btn" title="ดูรายละเอียด">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <div style="padding:14px 20px; border-top:1.5px solid #f1f5f9;">
        {{ $attempts->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Animate bars on load
    document.querySelectorAll('.rs-dist-bar, .rs-progress-fill, .rs-score-mini-fill').forEach(function (el) {
        var w = el.style.width || el.style.height;
        el.style.width = '0';
        el.style.height = el.style.height || '';
        setTimeout(function () { el.style.width = w; }, 100);
    });
});
</script>
@endsection
@endsection
