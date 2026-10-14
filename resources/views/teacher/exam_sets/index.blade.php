@extends('layouts.teacher')
@section('title','ชุดข้อสอบ')
@section('page-title','จัดการชุดข้อสอบ')

@section('content')
<style>
/* ─── Stats ─── */
.es-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(130px,1fr)); gap:12px; margin-bottom:20px; }
.es-stat { background:#fff; border-radius:10px; padding:16px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1.5px solid #f1f5f9; }
.es-stat .val { font-size:1.5rem; font-weight:700; color:#1e293b; display:block; }
.es-stat .lbl { font-size:.75rem; color:#94a3b8; }
.es-stat.s-active .val { color:#16a34a; }
.es-stat.s-inactive .val { color:#ef4444; }
.es-stat.s-public .val { color:#0891b2; }

/* ─── Top bar ─── */
.es-topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px; }
.es-topbar h5 { margin:0; font-size:1rem; font-weight:700; color:#1e293b; }
.btn-new { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:background .15s; }
.btn-new:hover { background:#1d3a9a; color:#fff; text-decoration:none; }

/* ─── Category nav ─── */
.cat-nav { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
.cat-nav a { padding:7px 16px; border-radius:20px; font-size:.82rem; font-weight:500; border:1.5px solid #e2e8f0; background:#fff; color:#64748b; text-decoration:none; transition:all .15s; }
.cat-nav a:hover { border-color:#3b82f6; color:#3b82f6; text-decoration:none; }
.cat-nav a .cnt { font-size:.7rem; opacity:.7; }

/* ─── Category group ─── */
.cat-group { margin-bottom:24px; }
.cat-header { display:flex; align-items:center; gap:10px; margin-bottom:12px; padding-bottom:8px; border-bottom:2px solid #f1f5f9; }
.cat-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.85rem; color:#fff; }
.cat-title { font-size:.95rem; font-weight:700; color:#1e293b; margin:0; }
.cat-count { font-size:.75rem; color:#94a3b8; font-weight:400; }

/* ─── Table ─── */
.es-table { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; overflow-x:auto; }
.es-table table { margin:0; }
.es-table thead th { background:#f8fafc; font-size:.78rem; font-weight:600; color:#64748b; text-transform:uppercase; border-bottom:2px solid #f1f5f9; padding:10px 14px; white-space:nowrap; }
.es-table tbody td { padding:10px 14px; vertical-align:middle; font-size:.85rem; border-bottom:1px solid #f8fafc; }
.es-table tbody tr:hover { background:#fafbfd; }

.es-name { color:#1e293b; font-weight:600; display:block; }
.es-desc { font-size:.72rem; color:#94a3b8; margin-top:2px; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; max-width:250px; }
.es-badges { display:flex; gap:4px; flex-wrap:wrap; margin-top:4px; }

.badge-pub { background:#d1fae5; color:#065f46; font-size:.68rem; font-weight:600; padding:2px 8px; border-radius:12px; display:inline-block; }
.badge-sub { font-size:.72rem; font-weight:500; padding:2px 8px; border-radius:12px; background:#eff6ff; color:#1d4ed8; }
.badge-cls { font-size:.72rem; font-weight:500; padding:2px 8px; border-radius:12px; background:#fdf4ff; color:#7e22ce; }

.info-val { font-weight:700; color:#1e293b; }
.info-lbl { font-size:.7rem; color:#94a3b8; }

/* ─── Actions ─── */
.ec-actions { display:flex; gap:5px; }
.act-btn { width:32px; height:32px; border-radius:7px; border:1.5px solid #e2e8f0; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:13px; color:#64748b; text-decoration:none; cursor:pointer; transition:all .15s; }
.act-btn:hover { background:#f1f5f9; border-color:#94a3b8; color:#334155; text-decoration:none; }
.act-btn.danger:hover { background:#fef2f2; border-color:#fca5a5; color:#dc2626; }
.act-btn.edit:hover   { background:#fefce8; border-color:#fde68a; color:#ca8a04; }
.act-btn.info:hover   { background:#eff6ff; border-color:#93c5fd; color:#2563eb; }

/* ─── Toggle ─── */
.tgl { position:relative; display:inline-block; width:36px; height:20px; }
.tgl input { opacity:0; width:0; height:0; }
.tgl-sld { position:absolute; inset:0; background:#cbd5e1; border-radius:20px; cursor:pointer; transition:.2s; }
.tgl-sld:before { position:absolute; content:""; height:14px; width:14px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.2s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
.tgl input:checked+.tgl-sld { background:#16a34a; }
.tgl input:checked+.tgl-sld:before { transform:translateX(16px); }

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
@endphp

{{-- Stats --}}
<div class="es-stats">
    <div class="es-stat">
        <span class="val">{{ $stats['total'] }}</span>
        <span class="lbl">ชุดข้อสอบทั้งหมด</span>
    </div>
    <div class="es-stat s-active">
        <span class="val">{{ $stats['active'] }}</span>
        <span class="lbl">เปิดใช้งาน</span>
    </div>
    <div class="es-stat s-inactive">
        <span class="val">{{ $stats['inactive'] }}</span>
        <span class="lbl">ปิดใช้งาน</span>
    </div>
    <div class="es-stat s-public">
        <span class="val">{{ $stats['public'] }}</span>
        <span class="lbl">Pre-Test ฟรี</span>
    </div>
</div>

{{-- Top bar --}}
<div class="es-topbar">
    <h5><i class="fas fa-file-alt mr-2" style="color:#3b82f6;"></i>ชุดข้อสอบ
        <span style="font-size:.8rem;font-weight:400;color:#94a3b8;margin-left:6px;">({{ $stats['total'] }} ชุด)</span>
    </h5>
    <a href="{{ route('teacher.exam_sets.create') }}" class="btn-new">
        <i class="fas fa-plus"></i> สร้างชุดข้อสอบ
    </a>
</div>

{{-- Category quick nav --}}
@if($grouped->count() > 1)
<div class="cat-nav">
    @foreach($grouped->keys() as $catName)
        <a href="#es-cat-{{ Str::slug($catName, '_') }}">
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
    <p>เริ่มสร้างชุดข้อสอบชุดแรกของคุณได้เลย</p>
    <a href="{{ route('teacher.exam_sets.create') }}" class="btn-new" style="display:inline-flex;">
        <i class="fas fa-plus"></i> สร้างชุดข้อสอบ
    </a>
</div>
@else
    @foreach($grouped as $catName => $examSets)
    <div class="cat-group" id="es-cat-{{ Str::slug($catName, '_') }}">
        <div class="cat-header">
            <div class="cat-icon" style="background:{{ $catColors[$catName] ?? '#94a3b8' }};">
                <i class="fas {{ $catIcons[$catName] ?? 'fa-folder' }}"></i>
            </div>
            <h6 class="cat-title">{{ $catName }} <span class="cat-count">({{ $examSets->count() }} ชุด)</span></h6>
        </div>

        <div class="es-table">
        <table class="table table-borderless mb-0">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>ชื่อชุดข้อสอบ</th>
                    <th style="width:130px;">หมวด / ห้อง</th>
                    <th style="width:70px;" class="text-center">ข้อสอบ</th>
                    <th style="width:65px;" class="text-center">เวลา</th>
                    <th style="width:65px;" class="text-center">เกณฑ์</th>
                    <th style="width:55px;" class="text-center">ครั้ง</th>
                    <th style="width:70px;" class="text-center">สถานะ</th>
                    <th style="width:130px;" class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @foreach($examSets as $es)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td>
                        <span class="es-name">{{ $es->name }}</span>
                        @if($es->description)
                            <span class="es-desc">{{ $es->description }}</span>
                        @endif
                        <div class="es-badges">
                            @if($es->is_public)
                                <span class="badge-pub"><i class="fas fa-globe-asia mr-1" style="font-size:.6rem;"></i>Pre-Test</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($es->subject)
                            <span class="badge-sub"><i class="fas fa-layer-group mr-1" style="font-size:.6rem;"></i>{{ $es->subject->name }}</span>
                        @else
                            <span class="text-muted" style="font-size:.78rem;">-</span>
                        @endif
                        @if($es->classroom)
                            <span class="badge-cls" style="display:inline-block;margin-top:3px;"><i class="fas fa-door-open mr-1" style="font-size:.6rem;"></i>{{ $es->classroom->name }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="info-val">{{ $es->questions_count ?: $es->direct_questions_count }}</span>
                    </td>
                    <td class="text-center">
                        <span class="info-val">{{ $es->time_limit ?? '∞' }}</span>
                        <span class="info-lbl d-block">นาที</span>
                    </td>
                    <td class="text-center">
                        <span class="info-val">{{ $es->pass_percentage }}%</span>
                    </td>
                    <td class="text-center">
                        <span class="info-val">{{ $es->max_attempts }}</span>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('teacher.exam_sets.toggle', $es) }}" method="POST" class="d-inline">
                            @csrf
                            <label class="tgl" title="{{ $es->is_active ? 'คลิกเพื่อปิด' : 'คลิกเพื่อเปิด' }}">
                                <input type="checkbox" {{ $es->is_active ? 'checked' : '' }}
                                       onchange="this.closest('form').submit()">
                                <span class="tgl-sld"></span>
                            </label>
                        </form>
                    </td>
                    <td class="text-center">
                        <div class="ec-actions justify-content-center">
                            <a href="{{ route('teacher.exam_sets.questions.index', $es) }}"
                               class="act-btn info" title="จัดการข้อสอบ">
                                <i class="fas fa-question-circle"></i>
                            </a>
                            <a href="{{ route('teacher.exam_sets.edit', $es) }}"
                               class="act-btn edit" title="แก้ไข">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('teacher.exam_sets.destroy', $es) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('ลบ «{{ addslashes($es->name) }}» ?')">
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
