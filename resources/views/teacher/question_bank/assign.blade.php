@extends('layouts.teacher')
@section('title','เชื่อมโยงชุดข้อสอบ')
@section('page-title','เชื่อมโยงข้อสอบกับชุดข้อสอบ')

@section('content')
<style>
/* ─── Question preview ─── */
.q-card { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; margin-bottom:20px; }
.q-head { padding:16px 20px; border-bottom:1.5px solid #f1f5f9; display:flex; align-items:center; gap:10px; }
.q-head-icon { width:36px; height:36px; border-radius:8px; background:#eff6ff; display:flex; align-items:center; justify-content:center; color:#3b82f6; font-size:.9rem; }
.q-head h6 { margin:0; font-size:.9rem; font-weight:700; color:#1e293b; }
.q-head .q-meta { font-size:.75rem; color:#94a3b8; font-weight:400; }
.q-body { padding:16px 20px; }
.q-text { font-size:.88rem; color:#1e293b; line-height:1.6; background:#f8fafc; border-radius:8px; padding:12px 16px; border:1px solid #e2e8f0; }
.q-info-row { display:flex; gap:10px; margin-top:10px; flex-wrap:wrap; }
.q-badge { font-size:.72rem; font-weight:500; padding:3px 10px; border-radius:12px; }
.q-badge.type-mc { background:#dbeafe; color:#1d4ed8; }
.q-badge.type-es { background:#fef3c7; color:#b45309; }
.q-badge.pts { background:#ecfdf5; color:#059669; }
.q-badge.subj { background:#f3e8ff; color:#7c3aed; }

/* ─── Toolbar ─── */
.asg-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:10px; }
.asg-toolbar h5 { margin:0; font-size:.95rem; font-weight:700; color:#1e293b; }
.asg-toolbar .sel-count { font-size:.8rem; color:#3b82f6; font-weight:600; margin-left:8px; }
.asg-actions { display:flex; gap:8px; }
.btn-save { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:9px 20px; font-size:.85rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:7px; transition:background .15s; }
.btn-save:hover { background:#1d3a9a; }
.btn-cancel { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:8px; padding:9px 20px; font-size:.85rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:all .15s; }
.btn-cancel:hover { background:#f1f5f9; color:#334155; text-decoration:none; }

/* ─── Category nav ─── */
.cat-nav { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:18px; }
.cat-nav a { padding:7px 16px; border-radius:20px; font-size:.82rem; font-weight:500; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; text-decoration:none; transition:all .15s; }
.cat-nav a:hover { border-color:#3b82f6; color:#3b82f6; text-decoration:none; }
.cat-nav a .cnt { font-size:.7rem; opacity:.7; }

/* ─── Category group ─── */
.cat-group { margin-bottom:22px; }
.cat-header { display:flex; align-items:center; gap:10px; margin-bottom:10px; padding-bottom:8px; border-bottom:2px solid #f1f5f9; }
.cat-icon { width:30px; height:30px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:.8rem; color:#fff; }
.cat-title { font-size:.9rem; font-weight:700; color:#1e293b; margin:0; }
.cat-count { font-size:.75rem; color:#94a3b8; font-weight:400; }

/* ─── Table ─── */
.asg-table { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; overflow-x:auto; }
.asg-table table { margin:0; }
.asg-table thead th { background:#f8fafc; font-size:.78rem; font-weight:600; color:#64748b; text-transform:uppercase; border-bottom:2px solid #f1f5f9; padding:10px 14px; white-space:nowrap; }
.asg-table tbody td { padding:10px 14px; vertical-align:middle; font-size:.85rem; border-bottom:1px solid #f8fafc; }
.asg-table tbody tr:hover { background:#f0f7ff; }
.asg-table tbody tr.is-checked { background:#eff6ff; }

.asg-table input[type=checkbox] { width:18px; height:18px; accent-color:#3b82f6; cursor:pointer; }
.asg-table .chk-all { cursor:pointer; }

.es-name { color:#1e293b; font-weight:600; display:block; }
.es-desc { font-size:.72rem; color:#94a3b8; margin-top:2px; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; max-width:220px; }

.badge-sub { font-size:.72rem; font-weight:500; padding:2px 10px; border-radius:12px; background:#eff6ff; color:#1d4ed8; }
.badge-on { font-size:.68rem; font-weight:600; padding:2px 8px; border-radius:12px; background:#dcfce7; color:#15803d; }
.badge-off { font-size:.68rem; font-weight:600; padding:2px 8px; border-radius:12px; background:#f1f5f9; color:#64748b; }
.badge-assigned { font-size:.68rem; font-weight:600; padding:2px 8px; border-radius:12px; background:#dbeafe; color:#1d4ed8; }

.info-val { font-weight:700; color:#1e293b; }

/* ─── Empty ─── */
.empty-box { text-align:center; padding:50px 20px; background:#fff; border-radius:12px; border:2px dashed #e2e8f0; }
.empty-icon { font-size:42px; margin-bottom:10px; color:#cbd5e1; }
.empty-box h6 { color:#64748b; margin:0 0 4px; }
.empty-box p { color:#94a3b8; font-size:.85rem; margin:0; }
</style>

@php
    $catColors = [
        'กระทู้'     => '#e67e22',
        'ธรรมะ'     => '#16a34a',
        'พุทธะ'     => '#3b82f6',
        'วินัย'      => '#7c3aed',
        'ศาสนพิธี'  => '#dc2626',
        'ไม่ระบุหมวด' => '#94a3b8',
    ];
    $catIcons = [
        'กระทู้'     => 'fa-pen-fancy',
        'ธรรมะ'     => 'fa-dharmachakra',
        'พุทธะ'     => 'fa-praying-hands',
        'วินัย'      => 'fa-balance-scale',
        'ศาสนพิธี'  => 'fa-landmark',
        'ไม่ระบุหมวด' => 'fa-folder',
    ];
    $assignedCount = count($assigned);
@endphp

{{-- Question preview --}}
<div class="q-card">
    <div class="q-head">
        <div class="q-head-icon"><i class="fas fa-question"></i></div>
        <div>
            <h6>ข้อสอบที่จะเชื่อมโยง <span class="q-meta">#{{ $question->id }}</span></h6>
        </div>
    </div>
    <div class="q-body">
        <div class="q-text">{{ $question->question_text }}</div>
        <div class="q-info-row">
            <span class="q-badge {{ $question->type === 'essay' ? 'type-es' : 'type-mc' }}">
                <i class="fas {{ $question->type === 'essay' ? 'fa-pen-alt' : 'fa-list-ul' }} mr-1" style="font-size:.6rem;"></i>{{ $question->type === 'essay' ? 'อัตนัย' : 'ปรนัย' }}
            </span>
            <span class="q-badge pts"><i class="fas fa-star mr-1" style="font-size:.6rem;"></i>{{ $question->points }} คะแนน</span>
            @if($question->subject)
                <span class="q-badge subj"><i class="fas fa-layer-group mr-1" style="font-size:.6rem;"></i>{{ $question->subject->name }}</span>
            @endif
        </div>
    </div>
</div>

<form method="POST" action="{{ route('teacher.question_bank.assign', $question) }}" id="assignForm">
@csrf

{{-- Toolbar --}}
<div class="asg-toolbar">
    <h5>
        <i class="fas fa-link mr-2" style="color:#3b82f6;"></i>เลือกชุดข้อสอบ
        <span class="sel-count" id="selCount">(เลือกแล้ว {{ $assignedCount }} ชุด)</span>
    </h5>
    <div class="asg-actions">
        <button type="submit" class="btn-save"><i class="fas fa-save"></i> บันทึก</button>
        <a href="{{ route('teacher.question_bank.index') }}" class="btn-cancel"><i class="fas fa-arrow-left"></i> กลับ</a>
    </div>
</div>

{{-- Category quick nav --}}
@if($grouped->count() > 1)
<div class="cat-nav">
    @foreach($grouped->keys() as $catName)
        <a href="#asg-cat-{{ Str::slug($catName, '_') }}">
            <i class="fas {{ $catIcons[$catName] ?? 'fa-folder' }} mr-1" style="font-size:.75rem;color:{{ $catColors[$catName] ?? '#94a3b8' }};"></i>{{ $catName }}
            <span class="cnt">({{ $grouped[$catName]->count() }})</span>
        </a>
    @endforeach
</div>
@endif

@if($grouped->isEmpty())
<div class="empty-box">
    <div class="empty-icon"><i class="fas fa-file-alt"></i></div>
    <h6>ยังไม่มีชุดข้อสอบ</h6>
    <p>สร้างชุดข้อสอบก่อนจึงจะเชื่อมโยงได้</p>
</div>
@else
    @foreach($grouped as $catName => $catExamSets)
    <div class="cat-group" id="asg-cat-{{ Str::slug($catName, '_') }}">
        <div class="cat-header">
            <div class="cat-icon" style="background:{{ $catColors[$catName] ?? '#94a3b8' }};">
                <i class="fas {{ $catIcons[$catName] ?? 'fa-folder' }}"></i>
            </div>
            <h6 class="cat-title">{{ $catName }} <span class="cat-count">({{ $catExamSets->count() }} ชุด)</span></h6>
        </div>

        <div class="asg-table">
        <table class="table table-borderless mb-0">
            <thead>
                <tr>
                    <th style="width:40px;" class="text-center">
                        <input type="checkbox" class="chk-all" data-group="{{ Str::slug($catName, '_') }}" title="เลือกทั้งหมดในกลุ่มนี้">
                    </th>
                    <th style="width:40px;">#</th>
                    <th>ชื่อชุดข้อสอบ</th>
                    <th style="width:130px;">หมวดวิชา</th>
                    <th style="width:70px;" class="text-center">ข้อสอบ</th>
                    <th style="width:70px;" class="text-center">สถานะ</th>
                    <th style="width:90px;" class="text-center">เชื่อมโยง</th>
                </tr>
            </thead>
            <tbody>
            @foreach($catExamSets as $es)
                @php $isAssigned = in_array($es->id, $assigned); @endphp
                <tr class="{{ $isAssigned ? 'is-checked' : '' }}" data-group="{{ Str::slug($catName, '_') }}">
                    <td class="text-center">
                        <input type="checkbox" name="exam_set_ids[]" value="{{ $es->id }}"
                               class="es-chk" data-group="{{ Str::slug($catName, '_') }}"
                               {{ $isAssigned ? 'checked' : '' }}>
                    </td>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                        <span class="es-name">{{ $es->name }}</span>
                        @if($es->description)
                            <span class="es-desc">{{ $es->description }}</span>
                        @endif
                    </td>
                    <td>
                        @if($es->subject)
                            <span class="badge-sub">{{ $es->subject->name }}</span>
                        @else
                            <span class="text-muted" style="font-size:.78rem;">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="info-val">{{ $es->direct_questions_count }}</span>
                    </td>
                    <td class="text-center">
                        <span class="{{ $es->is_active ? 'badge-on' : 'badge-off' }}">
                            {{ $es->is_active ? 'เปิด' : 'ปิด' }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($isAssigned)
                            <span class="badge-assigned"><i class="fas fa-check mr-1" style="font-size:.55rem;"></i>เชื่อมแล้ว</span>
                        @else
                            <span class="text-muted" style="font-size:.75rem;">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endforeach
@endif

{{-- Bottom save bar --}}
@if($grouped->isNotEmpty())
<div class="asg-toolbar" style="margin-top:16px;">
    <span class="sel-count" id="selCount2">(เลือกแล้ว {{ $assignedCount }} ชุด)</span>
    <div class="asg-actions">
        <button type="submit" class="btn-save"><i class="fas fa-save"></i> บันทึกการเชื่อมโยง</button>
        <a href="{{ route('teacher.question_bank.index') }}" class="btn-cancel"><i class="fas fa-arrow-left"></i> กลับ</a>
    </div>
</div>
@endif
</form>

@section('scripts')
<script>
(function () {
    var checkboxes = document.querySelectorAll('.es-chk');
    var selCount   = document.getElementById('selCount');
    var selCount2  = document.getElementById('selCount2');

    function updateCount() {
        var n = document.querySelectorAll('.es-chk:checked').length;
        var txt = '(เลือกแล้ว ' + n + ' ชุด)';
        if (selCount)  selCount.textContent  = txt;
        if (selCount2) selCount2.textContent = txt;
    }

    function updateRowHighlight(chk) {
        var tr = chk.closest('tr');
        if (tr) {
            if (chk.checked) { tr.classList.add('is-checked'); }
            else { tr.classList.remove('is-checked'); }
        }
    }

    checkboxes.forEach(function (chk) {
        chk.addEventListener('change', function () {
            updateRowHighlight(this);
            updateCount();
            updateGroupAll(this.getAttribute('data-group'));
        });
    });

    document.querySelectorAll('.chk-all').forEach(function (allChk) {
        allChk.addEventListener('change', function () {
            var group = this.getAttribute('data-group');
            var boxes = document.querySelectorAll('.es-chk[data-group="' + group + '"]');
            var checked = this.checked;
            boxes.forEach(function (b) {
                b.checked = checked;
                updateRowHighlight(b);
            });
            updateCount();
        });
    });

    function updateGroupAll(group) {
        var boxes   = document.querySelectorAll('.es-chk[data-group="' + group + '"]');
        var allChk  = document.querySelector('.chk-all[data-group="' + group + '"]');
        if (!allChk) return;
        var allChecked = true;
        boxes.forEach(function (b) { if (!b.checked) allChecked = false; });
        allChk.checked = allChecked;
    }

    document.querySelectorAll('.chk-all').forEach(function (allChk) {
        updateGroupAll(allChk.getAttribute('data-group'));
    });
})();
</script>
@endsection
@endsection
