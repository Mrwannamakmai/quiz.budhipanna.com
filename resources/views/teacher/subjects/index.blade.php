@extends('layouts.teacher')
@section('title','หมวดวิชา')
@section('page-title','จัดการหมวดวิชา')

@section('content')
<style>
/* ─── Stats ─── */
.sb-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:12px; margin-bottom:20px; }
.sb-stat { background:#fff; border-radius:10px; padding:16px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1.5px solid #f1f5f9; }
.sb-stat .val { font-size:1.5rem; font-weight:700; color:#1e293b; display:block; }
.sb-stat .lbl { font-size:.75rem; color:#94a3b8; }
.sb-stat.s-sub .val { color:#7c3aed; }
.sb-stat.s-q .val   { color:#3b82f6; }
.sb-stat.s-es .val  { color:#16a34a; }

/* ─── Top bar ─── */
.sb-topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px; }
.sb-topbar h5 { margin:0; font-size:1rem; font-weight:700; color:#1e293b; }
.btn-new { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:background .15s; }
.btn-new:hover { background:#1d3a9a; color:#fff; text-decoration:none; }

/* ─── Category pills ─── */
.cat-nav { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
.cat-nav a { padding:7px 16px; border-radius:20px; font-size:.82rem; font-weight:500; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; text-decoration:none; transition:all .15s; }
.cat-nav a:hover { border-color:#7c3aed; color:#7c3aed; text-decoration:none; }
.cat-nav a.active { background:#f3e8ff; border-color:#7c3aed; color:#6d28d9; font-weight:600; }

/* ─── Category Group ─── */
.cat-group { margin-bottom:24px; }
.cat-header { display:flex; align-items:center; gap:10px; margin-bottom:12px; padding-bottom:8px; border-bottom:2px solid #f1f5f9; }
.cat-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.85rem; color:#fff; }
.cat-title { font-size:.95rem; font-weight:700; color:#1e293b; margin:0; }
.cat-count { font-size:.75rem; color:#94a3b8; font-weight:400; }

/* ─── Table ─── */
.sb-table { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; overflow-x:auto; }
.sb-table table { margin:0; }
.sb-table thead th { background:#f8fafc; font-size:.78rem; font-weight:600; color:#64748b; text-transform:uppercase; border-bottom:2px solid #f1f5f9; padding:10px 14px; white-space:nowrap; }
.sb-table tbody td { padding:10px 14px; vertical-align:middle; font-size:.85rem; border-bottom:1px solid #f8fafc; }
.sb-table tbody tr:hover { background:#fafbfd; }

.sb-name { color:#1e293b; font-weight:600; }
.sb-desc { font-size:.72rem; color:#94a3b8; margin-top:2px; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; max-width:250px; }

.badge-course { font-size:.72rem; font-weight:500; padding:2px 10px; border-radius:12px; background:#eff6ff; color:#1d4ed8; }
.badge-num { font-size:.78rem; font-weight:700; padding:3px 10px; border-radius:20px; }
.badge-q  { background:#eff6ff; color:#1d4ed8; }
.badge-es { background:#ecfdf5; color:#059669; }

/* ─── Actions ─── */
.ec-actions { display:flex; gap:5px; }
.act-btn { width:32px; height:32px; border-radius:7px; border:1.5px solid #e2e8f0; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:13px; color:#64748b; text-decoration:none; cursor:pointer; transition:all .15s; }
.act-btn:hover { background:#f1f5f9; border-color:#94a3b8; color:#334155; text-decoration:none; }
.act-btn.danger:hover { background:#fef2f2; border-color:#fca5a5; color:#dc2626; }
.act-btn.edit:hover   { background:#fefce8; border-color:#fde68a; color:#ca8a04; }

/* ─── Empty ─── */
.empty-box { text-align:center; padding:60px 20px; background:#fff; border-radius:12px; border:2px dashed #e2e8f0; }
.empty-icon { font-size:48px; margin-bottom:12px; color:#cbd5e1; }
.empty-box h6 { color:#64748b; margin:0 0 6px; }
.empty-box p { color:#94a3b8; font-size:.85rem; margin:0 0 16px; }
</style>

@php
    $catColors = [
        'กระทู้'     => '#e67e22',
        'ธรรมะ'     => '#16a34a',
        'พุทธะ'     => '#3b82f6',
        'วินัย'      => '#7c3aed',
        'ศาสนพิธี'  => '#dc2626',
        'ภาษาบาลี'  => '#7c3aed',
        'ไม่ระบุหมวด' => '#94a3b8',
    ];
    $catIcons = [
        'กระทู้'     => 'fa-pen-fancy',
        'ธรรมะ'     => 'fa-dharmachakra',
        'พุทธะ'     => 'fa-praying-hands',
        'วินัย'      => 'fa-balance-scale',
        'ศาสนพิธี'  => 'fa-landmark',
        'ภาษาบาลี'  => 'fa-book',
        'ไม่ระบุหมวด' => 'fa-folder',
    ];
@endphp

{{-- Stats --}}
<div class="sb-stats">
    <div class="sb-stat s-sub">
        <span class="val">{{ $stats['total'] }}</span>
        <span class="lbl">หมวดวิชาทั้งหมด</span>
    </div>
    <div class="sb-stat s-q">
        <span class="val">{{ $stats['questions'] }}</span>
        <span class="lbl">ข้อสอบทั้งหมด</span>
    </div>
    <div class="sb-stat s-es">
        <span class="val">{{ $stats['exam_sets'] }}</span>
        <span class="lbl">ชุดข้อสอบ</span>
    </div>
</div>

{{-- Top bar --}}
<div class="sb-topbar">
    <h5><i class="fas fa-layer-group mr-2" style="color:#7c3aed;"></i>หมวดวิชา
        <span style="font-size:.8rem;font-weight:400;color:#94a3b8;margin-left:6px;">({{ $stats['total'] }} หมวด)</span>
    </h5>
    <a href="{{ route('teacher.subjects.create') }}" class="btn-new">
        <i class="fas fa-plus"></i> เพิ่มหมวดวิชา
    </a>
</div>

{{-- Category quick nav --}}
@if($grouped->count() > 1)
<div class="cat-nav">
    @foreach($grouped->keys() as $catName)
        <a href="#cat-{{ Str::slug($catName, '_') }}" class="active">
            <i class="fas {{ $catIcons[$catName] ?? 'fa-folder' }} mr-1" style="font-size:.75rem;"></i>{{ $catName }}
            <span style="font-size:.7rem;opacity:.7;">({{ $grouped[$catName]->count() }})</span>
        </a>
    @endforeach
</div>
@endif

@if($grouped->isEmpty())
<div class="empty-box">
    <div class="empty-icon"><i class="fas fa-layer-group"></i></div>
    <h6>ยังไม่มีหมวดวิชา</h6>
    <p>เริ่มสร้างหมวดวิชาแรกของคุณได้เลย</p>
    <a href="{{ route('teacher.subjects.create') }}" class="btn-new" style="display:inline-flex;">
        <i class="fas fa-plus"></i> เพิ่มหมวดวิชา
    </a>
</div>
@else
    @foreach($grouped as $catName => $subjects)
    <div class="cat-group" id="cat-{{ Str::slug($catName, '_') }}">
        <div class="cat-header">
            <div class="cat-icon" style="background:{{ $catColors[$catName] ?? '#94a3b8' }};">
                <i class="fas {{ $catIcons[$catName] ?? 'fa-folder' }}"></i>
            </div>
            <h6 class="cat-title">{{ $catName }} <span class="cat-count">({{ $subjects->count() }} หมวด)</span></h6>
        </div>

        <div class="sb-table">
        <table class="table table-borderless mb-0">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>ชื่อหมวดวิชา</th>
                    <th style="width:140px;">วิชา/คอร์ส</th>
                    <th style="width:80px;" class="text-center">ข้อสอบ</th>
                    <th style="width:80px;" class="text-center">ชุดข้อสอบ</th>
                    <th style="width:100px;" class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @foreach($subjects as $subject)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                        <span class="sb-name">{{ $subject->name }}</span>
                        @if($subject->description)
                            <div class="sb-desc">{{ $subject->description }}</div>
                        @endif
                    </td>
                    <td>
                        @if($subject->course)
                            <span class="badge-course">{{ $subject->course->name }}</span>
                        @else
                            <span class="text-muted" style="font-size:.78rem;">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge-num badge-q">{{ $subject->questions_count }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge-num badge-es">{{ $subject->exam_sets_count }}</span>
                    </td>
                    <td class="text-center">
                        <div class="ec-actions justify-content-center">
                            <a href="{{ route('teacher.subjects.edit', $subject) }}"
                               class="act-btn edit" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('teacher.subjects.destroy', $subject) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('ลบ «{{ addslashes($subject->name) }}» ?')">
                                @csrf @method('DELETE')
                                <button class="act-btn danger" type="submit" title="ลบ">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endforeach
@endif
@endsection
