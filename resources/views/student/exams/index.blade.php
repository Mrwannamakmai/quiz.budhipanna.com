@extends('layouts.student')
@section('title','ชุดข้อสอบ')

@section('content')
<style>
/* ─── Stats ─── */
.se-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:12px; margin-bottom:20px; }
.se-stat { background:#fff; border-radius:12px; padding:16px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1.5px solid #f1f5f9; }
.se-stat .val { font-size:1.5rem; font-weight:700; display:block; }
.se-stat .lbl { font-size:.75rem; color:#94a3b8; }
.se-stat.total .val { color:#1e293b; }
.se-stat.subject .val { color:#3b82f6; }
.se-stat.passed .val { color:#16a34a; }

/* ─── Top bar ─── */
.se-topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px; }
.se-topbar h5 { margin:0; font-size:1rem; font-weight:700; color:#1e293b; }
.se-views { display:flex; gap:0; border:1.5px solid #e2e8f0; border-radius:8px; overflow:hidden; }
.se-views button { border:none; background:#fff; padding:8px 14px; font-size:.82rem; cursor:pointer; color:#64748b; display:flex; align-items:center; gap:5px; transition:all .15s; }
.se-views button:not(:last-child) { border-right:1.5px solid #e2e8f0; }
.se-views button.active { background:#1e40af; color:#fff; }
.se-views button:hover:not(.active) { background:#f1f5f9; }

/* ─── Subject group ─── */
.se-group { margin-bottom:24px; }
.se-group-head { display:flex; align-items:center; gap:10px; margin-bottom:14px; padding-bottom:10px; border-bottom:2px solid #f1f5f9; }
.se-group-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.9rem; color:#fff; flex-shrink:0; }
.se-group-head h6 { margin:0; font-size:.95rem; font-weight:700; color:#1e293b; }
.se-group-head .se-group-count { font-size:.78rem; color:#94a3b8; font-weight:400; margin-left:4px; }

/* ─── Grid cards ─── */
.se-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:14px; }
.se-card { background:#fff; border-radius:12px; border:1.5px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; display:flex; flex-direction:column; transition:all .2s; }
.se-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.1); transform:translateY(-2px); }
.se-card-top { padding:18px 18px 0; flex:1; }
.se-card-name { font-size:.95rem; font-weight:700; color:#1e293b; margin:0 0 6px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.se-card-desc { font-size:.8rem; color:#94a3b8; margin:0 0 12px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.se-card-metas { display:flex; flex-wrap:wrap; gap:8px 14px; margin-bottom:14px; }
.se-card-meta { font-size:.78rem; color:#64748b; display:flex; align-items:center; gap:5px; }
.se-card-meta i { color:#94a3b8; width:14px; text-align:center; }
.se-card-foot { padding:12px 18px; border-top:1.5px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; background:#fafbfc; }
.se-card-status { font-size:.72rem; font-weight:700; padding:3px 10px; border-radius:20px; display:inline-flex; align-items:center; gap:4px; }
.se-card-status.done { background:#dcfce7; color:#15803d; }
.se-card-status.tried { background:#fef3c7; color:#92400e; }
.se-card-status.new { background:#eff6ff; color:#1d4ed8; }
.se-card-btn { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:7px 16px; font-size:.82rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:background .15s; }
.se-card-btn:hover { background:#1d3a9a; color:#fff; text-decoration:none; }

/* ─── Table ─── */
.se-table-wrap { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; overflow:hidden; }
.se-table { margin:0; width:100%; }
.se-table thead th { background:#f8fafc; font-size:.75rem; font-weight:700; color:#64748b; text-transform:uppercase; padding:12px 14px; border-bottom:2px solid #f1f5f9; white-space:nowrap; }
.se-table tbody td { padding:12px 14px; vertical-align:middle; font-size:.85rem; border-bottom:1px solid #f8fafc; }
.se-table tbody tr:hover { background:#fafbfd; }
.se-table .t-name { font-weight:600; color:#1e293b; }
.se-table .t-desc { font-size:.75rem; color:#94a3b8; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; max-width:220px; }
.se-table .t-badge { font-size:.72rem; font-weight:600; padding:3px 10px; border-radius:20px; }
.se-table .act-btn { width:32px; height:32px; border-radius:7px; border:1.5px solid #e2e8f0; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:13px; color:#64748b; text-decoration:none; transition:all .15s; }
.se-table .act-btn:hover { background:#eff6ff; border-color:#93c5fd; color:#1d4ed8; text-decoration:none; }

/* ─── Subject header row ─── */
.se-table .subj-row td { background:#f1f5f9; padding:10px 14px; cursor:default; }
.se-table .subj-row:hover td { background:#f1f5f9; }
.se-table .subj-label { display:flex; align-items:center; gap:8px; font-size:.82rem; font-weight:700; color:#1e293b; }
.se-table .subj-icon { width:24px; height:24px; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:.65rem; color:#fff; flex-shrink:0; }

/* ─── Empty ─── */
.se-empty { text-align:center; padding:60px 20px; background:#fff; border-radius:14px; border:2px dashed #e2e8f0; }
.se-empty i { font-size:48px; color:#cbd5e1; margin-bottom:12px; display:block; }
.se-empty h6 { color:#64748b; margin:0 0 6px; }
.se-empty p { color:#94a3b8; font-size:.85rem; margin:0; }

/* ─── Toggle ─── */
.se-content.hide { display:none; }
</style>

@php
    $subjectColors = ['#3b82f6','#16a34a','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1','#06b6d4'];
    $subjectIcons  = ['fa-book','fa-dharmachakra','fa-praying-hands','fa-balance-scale','fa-landmark','fa-pen-fancy','fa-scroll','fa-graduation-cap','fa-feather-alt','fa-star'];
@endphp

{{-- Stats --}}
<div class="se-stats">
    <div class="se-stat total">
        <span class="val">{{ $stats['total'] }}</span>
        <span class="lbl">ชุดข้อสอบทั้งหมด</span>
    </div>
    <div class="se-stat subject">
        <span class="val">{{ $stats['subjects'] }}</span>
        <span class="lbl">รายวิชา</span>
    </div>
    <div class="se-stat passed">
        <span class="val">{{ $stats['passed'] }}</span>
        <span class="lbl">สอบผ่านแล้ว</span>
    </div>
</div>

{{-- Top bar --}}
<div class="se-topbar">
    <h5><i class="fas fa-layer-group mr-2" style="color:#3b82f6;"></i>ชุดข้อสอบ</h5>
    <div class="se-views">
        <button type="button" class="active" id="btnGrid" onclick="toggleView('grid')">
            <i class="fas fa-th-large"></i> กริด
        </button>
        <button type="button" id="btnTable" onclick="toggleView('table')">
            <i class="fas fa-list"></i> ตาราง
        </button>
    </div>
</div>

@if($examSets->isEmpty())
<div class="se-empty">
    <i class="fas fa-file-alt"></i>
    <h6>ยังไม่มีชุดข้อสอบ</h6>
    <p>รอครูเปิดชุดข้อสอบสำหรับชั้นเรียนของคุณ</p>
</div>
@else

{{-- ============ GRID VIEW ============ --}}
<div class="se-content" id="viewGrid">
@foreach($grouped as $subjectName => $items)
@php $idx = $loop->index % count($subjectColors); @endphp
<div class="se-group">
    <div class="se-group-head">
        <div class="se-group-icon" style="background:{{ $subjectColors[$idx] }};">
            <i class="fas {{ $subjectIcons[$idx] }}"></i>
        </div>
        <h6>{{ $subjectName }} <span class="se-group-count">({{ $items->count() }} ชุด)</span></h6>
    </div>
    <div class="se-grid">
        @foreach($items as $es)
        @php
            $attempts = $attemptMap[$es->id] ?? collect();
            $bestAttempt = $attempts->sortByDesc('score')->first();
            $hasPassed = $attempts->contains('passed', true);
            $attCount = $attempts->count();
        @endphp
        <div class="se-card">
            <div class="se-card-top">
                <h6 class="se-card-name">{{ $es->name }}</h6>
                @if($es->description)
                    <p class="se-card-desc">{{ $es->description }}</p>
                @endif
                <div class="se-card-metas">
                    <span class="se-card-meta"><i class="fas fa-question-circle"></i>{{ $es->total_questions }} ข้อ</span>
                    <span class="se-card-meta"><i class="fas fa-clock"></i>{{ $es->time_limit ? $es->time_limit.' นาที' : 'ไม่จำกัด' }}</span>
                    <span class="se-card-meta"><i class="fas fa-percent"></i>ผ่าน {{ $es->pass_percentage }}%</span>
                    <span class="se-card-meta"><i class="fas fa-redo"></i>สอบได้ {{ $es->max_attempts }} ครั้ง</span>
                    @if($bestAttempt)
                    <span class="se-card-meta">
                        <i class="fas fa-trophy" style="color:#f59e0b;"></i>
                        สูงสุด {{ $bestAttempt->total_points > 0 ? round($bestAttempt->score / $bestAttempt->total_points * 100, 1) : 0 }}%
                    </span>
                    @endif
                </div>
            </div>
            <div class="se-card-foot">
                @if($hasPassed)
                    <span class="se-card-status done"><i class="fas fa-check-circle"></i> สอบผ่านแล้ว</span>
                @elseif($attCount > 0)
                    <span class="se-card-status tried"><i class="fas fa-clock"></i> สอบแล้ว {{ $attCount }} ครั้ง</span>
                @else
                    <span class="se-card-status new"><i class="fas fa-sparkles"></i> ยังไม่เคยสอบ</span>
                @endif
                <a href="{{ route('student.exams.show', $es) }}" class="se-card-btn">
                    <i class="fas fa-arrow-right"></i> เข้าสอบ
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
</div>

{{-- ============ TABLE VIEW ============ --}}
<div class="se-content hide" id="viewTable">
<div class="se-table-wrap">
    <div class="table-responsive">
    <table class="se-table table mb-0">
        <thead>
            <tr>
                <th style="width:40px;">#</th>
                <th>ชื่อชุดข้อสอบ</th>
                <th>จำนวนข้อ</th>
                <th>เวลา</th>
                <th>เกณฑ์ผ่าน</th>
                <th>สถานะ</th>
                <th>คะแนนสูงสุด</th>
                <th style="width:60px;">จัดการ</th>
            </tr>
        </thead>
        <tbody>
        @php $rowNum = 0; @endphp
        @foreach($grouped as $subjectName => $items)
        @php $idx = $loop->index % count($subjectColors); @endphp
        <tr class="subj-row">
            <td colspan="8">
                <div class="subj-label">
                    <span class="subj-icon" style="background:{{ $subjectColors[$idx] }};">
                        <i class="fas {{ $subjectIcons[$idx] }}"></i>
                    </span>
                    {{ $subjectName }} ({{ $items->count() }})
                </div>
            </td>
        </tr>
        @foreach($items as $es)
        @php
            $rowNum++;
            $attempts = $attemptMap[$es->id] ?? collect();
            $bestAttempt = $attempts->sortByDesc('score')->first();
            $hasPassed = $attempts->contains('passed', true);
            $attCount = $attempts->count();
            $bestPct = $bestAttempt && $bestAttempt->total_points > 0
                ? round($bestAttempt->score / $bestAttempt->total_points * 100, 1)
                : null;
        @endphp
        <tr>
            <td class="text-muted">{{ $rowNum }}</td>
            <td>
                <div class="t-name">{{ $es->name }}</div>
                @if($es->description)
                    <div class="t-desc">{{ $es->description }}</div>
                @endif
            </td>
            <td>{{ $es->total_questions }} ข้อ</td>
            <td>{{ $es->time_limit ? $es->time_limit.' นาที' : 'ไม่จำกัด' }}</td>
            <td>{{ $es->pass_percentage }}%</td>
            <td>
                @if($hasPassed)
                    <span class="t-badge" style="background:#dcfce7;color:#15803d;">
                        <i class="fas fa-check-circle mr-1"></i>ผ่าน
                    </span>
                @elseif($attCount > 0)
                    <span class="t-badge" style="background:#fef3c7;color:#92400e;">
                        <i class="fas fa-clock mr-1"></i>{{ $attCount }} ครั้ง
                    </span>
                @else
                    <span class="t-badge" style="background:#eff6ff;color:#1d4ed8;">
                        <i class="fas fa-minus-circle mr-1"></i>ยังไม่สอบ
                    </span>
                @endif
            </td>
            <td>
                @if($bestPct !== null)
                    <span style="font-weight:700;color:{{ $bestPct >= $es->pass_percentage ? '#16a34a' : '#ef4444' }};">
                        {{ $bestPct }}%
                    </span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <a href="{{ route('student.exams.show', $es) }}" class="act-btn" title="ดูรายละเอียด">
                    <i class="fas fa-arrow-right"></i>
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

<script>
function toggleView(mode) {
    var grid = document.getElementById('viewGrid');
    var table = document.getElementById('viewTable');
    var btnGrid = document.getElementById('btnGrid');
    var btnTable = document.getElementById('btnTable');

    if (mode === 'table') {
        grid.classList.add('hide');
        table.classList.remove('hide');
        btnGrid.classList.remove('active');
        btnTable.classList.add('active');
    } else {
        table.classList.add('hide');
        grid.classList.remove('hide');
        btnTable.classList.remove('active');
        btnGrid.classList.add('active');
    }

    try { localStorage.setItem('se_view', mode); } catch(e) {}
}

(function () {
    try {
        var saved = localStorage.getItem('se_view');
        if (saved === 'table') toggleView('table');
    } catch(e) {}
})();
</script>
@endsection
