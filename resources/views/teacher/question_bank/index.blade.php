@extends('layouts.teacher')
@section('title','คลังข้อสอบ')
@section('page-title','คลังข้อสอบ')

@section('content')
<style>
/* ─── Stats ─── */
.qb-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:12px; margin-bottom:20px; }
.qb-stat { background:#fff; border-radius:10px; padding:16px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1.5px solid #f1f5f9; }
.qb-stat .val { font-size:1.5rem; font-weight:700; color:#1e293b; display:block; }
.qb-stat .lbl { font-size:.75rem; color:#94a3b8; }
.qb-stat.active .val { color:#16a34a; }
.qb-stat.inactive .val { color:#ef4444; }
.qb-stat.mc .val { color:#3b82f6; }
.qb-stat.essay .val { color:#f59e0b; }

/* ─── Top bar ─── */
.qb-topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px; }
.qb-topbar h5 { margin:0; font-size:1rem; font-weight:700; color:#1e293b; }
.btn-new { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:7px; transition:background .15s; }
.btn-new:hover { background:#1d3a9a; color:#fff; text-decoration:none; }

/* ─── Filter bar ─── */
.qb-filters { background:#fff; border-radius:10px; padding:14px 18px; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1.5px solid #f1f5f9; margin-bottom:16px; }
.qb-filters form { display:flex; gap:10px; flex-wrap:wrap; align-items:end; }
.qb-filters .fg { display:flex; flex-direction:column; gap:3px; }
.qb-filters label { font-size:.72rem; font-weight:600; color:#64748b; text-transform:uppercase; }
.qb-filters select, .qb-filters input[type=text] { border:1.5px solid #e2e8f0; border-radius:7px; padding:6px 10px; font-size:.82rem; background:#f8fafc; min-width:140px; }
.qb-filters select:focus, .qb-filters input:focus { outline:none; border-color:#3b82f6; }
.btn-filter { background:#3b82f6; color:#fff; border:none; border-radius:7px; padding:7px 14px; font-size:.82rem; cursor:pointer; font-weight:600; }
.btn-filter:hover { background:#2563eb; }
.btn-reset { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:7px; padding:7px 14px; font-size:.82rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; }
.btn-reset:hover { background:#f1f5f9; color:#334155; text-decoration:none; }

/* ─── Bulk action bar ─── */
.bulk-bar { display:none; background:#1e40af; color:#fff; border-radius:10px; padding:12px 18px; margin-bottom:14px; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; box-shadow:0 2px 8px rgba(30,64,175,.3); animation:slideDown .2s ease; }
.bulk-bar.show { display:flex; }
@keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
.bulk-bar .bulk-info { display:flex; align-items:center; gap:10px; font-size:.88rem; font-weight:600; }
.bulk-bar .bulk-count { background:rgba(255,255,255,.2); padding:3px 12px; border-radius:16px; font-size:.82rem; }
.bulk-bar .bulk-actions { display:flex; gap:8px; }
.btn-bulk { background:rgba(255,255,255,.15); color:#fff; border:1.5px solid rgba(255,255,255,.3); border-radius:8px; padding:8px 16px; font-size:.82rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:all .15s; }
.btn-bulk:hover { background:rgba(255,255,255,.25); }
.btn-bulk.primary { background:#fff; color:#1e40af; border-color:#fff; }
.btn-bulk.primary:hover { background:#f0f0f0; }
.btn-deselect { background:transparent; color:rgba(255,255,255,.8); border:none; cursor:pointer; font-size:.8rem; padding:4px 8px; }
.btn-deselect:hover { color:#fff; }

/* ─── Table ─── */
.qb-table { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; overflow:hidden; }
.qb-table table { margin:0; }
.qb-table thead th { background:#f8fafc; font-size:.78rem; font-weight:600; color:#64748b; text-transform:uppercase; border-bottom:2px solid #f1f5f9; padding:12px 14px; white-space:nowrap; }
.qb-table tbody td { padding:12px 14px; vertical-align:middle; font-size:.85rem; border-bottom:1px solid #f8fafc; }
.qb-table tbody tr:hover { background:#fafbfd; }
.qb-table tbody tr.is-selected { background:#eff6ff; }

.qb-table input[type=checkbox] { width:18px; height:18px; accent-color:#3b82f6; cursor:pointer; }

.q-text { color:#1e293b; font-weight:500; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; max-width:320px; }
.q-subject { font-size:.72rem; color:#94a3b8; margin-top:3px; }

.badge-type { font-size:.72rem; font-weight:600; padding:3px 10px; border-radius:20px; }
.badge-mc { background:#eff6ff; color:#1d4ed8; }
.badge-es { background:#fffbeb; color:#b45309; }

.badge-status { font-size:.72rem; font-weight:600; padding:3px 10px; border-radius:20px; }
.badge-on  { background:#dcfce7; color:#15803d; }
.badge-off { background:#f1f5f9; color:#64748b; }

.badge-cat { font-size:.68rem; font-weight:500; padding:2px 8px; border-radius:12px; background:#fdf4ff; color:#7e22ce; }
.badge-link { font-size:.68rem; font-weight:500; padding:2px 8px; border-radius:12px; background:#ecfdf5; color:#059669; }

/* ─── Actions ─── */
.ec-actions { display:flex; gap:5px; }
.act-btn { width:32px; height:32px; border-radius:7px; border:1.5px solid #e2e8f0; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:13px; color:#64748b; text-decoration:none; cursor:pointer; transition:all .15s; }
.act-btn:hover { background:#f1f5f9; border-color:#94a3b8; color:#334155; text-decoration:none; }
.act-btn.danger:hover { background:#fef2f2; border-color:#fca5a5; color:#dc2626; }
.act-btn.edit:hover { background:#fefce8; border-color:#fde68a; color:#ca8a04; }
.act-btn.link:hover { background:#ecfdf5; border-color:#6ee7b7; color:#059669; }

/* ─── Toggle ─── */
.tgl { position:relative; display:inline-block; width:36px; height:20px; }
.tgl input { opacity:0; width:0; height:0; }
.tgl-sld { position:absolute; inset:0; background:#cbd5e1; border-radius:20px; cursor:pointer; transition:.2s; }
.tgl-sld:before { position:absolute; content:""; height:14px; width:14px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.2s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
.tgl input:checked+.tgl-sld { background:#16a34a; }
.tgl input:checked+.tgl-sld:before { transform:translateX(16px); }

/* ─── Empty ─── */
.empty-box { text-align:center; padding:60px 20px; background:#fff; border-radius:12px; border:2px dashed #e2e8f0; }
.empty-icon { font-size:48px; margin-bottom:12px; }
.empty-box h6 { color:#64748b; margin:0 0 6px; }
.empty-box p { color:#94a3b8; font-size:.85rem; margin:0 0 16px; }

/* ─── Modal ─── */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1050; align-items:center; justify-content:center; }
.modal-overlay.show { display:flex; }
.modal-box { background:#fff; border-radius:14px; box-shadow:0 10px 40px rgba(0,0,0,.2); width:720px; max-width:95vw; max-height:85vh; display:flex; flex-direction:column; animation:modalIn .2s ease; }
@keyframes modalIn { from { opacity:0; transform:scale(.95) translateY(10px); } to { opacity:1; transform:scale(1) translateY(0); } }
.modal-head { padding:18px 22px; border-bottom:1.5px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
.modal-head h6 { margin:0; font-size:.95rem; font-weight:700; color:#1e293b; }
.modal-close { background:none; border:none; font-size:1.2rem; color:#94a3b8; cursor:pointer; padding:4px 8px; border-radius:6px; }
.modal-close:hover { background:#f1f5f9; color:#334155; }
.modal-box > form { display:flex; flex-direction:column; flex:1; min-height:0; overflow:hidden; }
.modal-body { padding:0; overflow-y:auto; flex:1; min-height:0; }
.modal-foot { padding:14px 22px; border-top:1.5px solid #f1f5f9; display:flex; gap:10px; justify-content:flex-end; flex-shrink:0; background:#fff; border-radius:0 0 14px 14px; }

/* ─── Modal info bar ─── */
.m-info-bar { display:flex; align-items:center; justify-content:space-between; padding:12px 22px; background:#f8fafc; border-bottom:1.5px solid #f1f5f9; flex-shrink:0; }
.m-info-bar span { font-size:.82rem; color:#64748b; }
.m-sel-count { font-size:.82rem; color:#3b82f6; font-weight:700; }

/* ─── Modal table ─── */
.m-table { width:100%; border-collapse:collapse; }
.m-table thead { position:sticky; top:0; z-index:2; background:#f8fafc; }
.m-table thead th { padding:10px 14px; font-size:.75rem; font-weight:700; color:#64748b; text-transform:uppercase; border-bottom:2px solid #e2e8f0; white-space:nowrap; text-align:left; }
.m-table thead th:first-child { width:42px; text-align:center; }
.m-table tbody td { padding:10px 14px; font-size:.82rem; color:#1e293b; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
.m-table tbody td:first-child { text-align:center; }
.m-table tbody tr { cursor:pointer; transition:background .12s; }
.m-table tbody tr:hover { background:#eff6ff; }
.m-table tbody tr.is-checked { background:#eff6ff; }
.m-table input[type=checkbox] { width:16px; height:16px; accent-color:#3b82f6; cursor:pointer; }

.m-cat-row td { background:#f1f5f9 !important; padding:8px 14px !important; cursor:default !important; }
.m-cat-row:hover { background:#f1f5f9 !important; }
.m-cat-label { display:flex; align-items:center; gap:8px; font-size:.8rem; font-weight:700; color:#1e293b; }
.m-cat-icon { width:22px; height:22px; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:.65rem; color:#fff; flex-shrink:0; }

.m-es-name { font-weight:600; }
.m-es-sub { font-size:.72rem; color:#94a3b8; }
.m-es-badge { font-size:.68rem; font-weight:600; padding:2px 10px; border-radius:12px; display:inline-block; }

.btn-modal-save { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:10px 22px; font-size:.85rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:all .15s; }
.btn-modal-save:hover { background:#1d3a9a; }
.btn-modal-save:disabled { background:#94a3b8; cursor:not-allowed; }
.btn-modal-cancel { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 22px; font-size:.85rem; cursor:pointer; transition:all .15s; }
.btn-modal-cancel:hover { background:#f1f5f9; color:#334155; }
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

{{-- Import errors --}}
@if(session('import_errors'))
<div class="alert alert-warning alert-dismissible fade show" style="border-radius:10px;">
    <strong><i class="fas fa-exclamation-triangle mr-1"></i>รายการที่ข้ามไป:</strong>
    <ul class="mb-0 mt-1" style="font-size:.85rem;">
        @foreach(session('import_errors') as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

{{-- Stats --}}
<div class="qb-stats">
    <div class="qb-stat">
        <span class="val">{{ $stats['total'] }}</span>
        <span class="lbl">ข้อสอบทั้งหมด</span>
    </div>
    <div class="qb-stat active">
        <span class="val">{{ $stats['active'] }}</span>
        <span class="lbl">เปิดใช้งาน</span>
    </div>
    <div class="qb-stat inactive">
        <span class="val">{{ $stats['inactive'] }}</span>
        <span class="lbl">ปิดใช้งาน</span>
    </div>
    <div class="qb-stat mc">
        <span class="val">{{ $stats['mc'] }}</span>
        <span class="lbl">ปรนัย</span>
    </div>
    <div class="qb-stat essay">
        <span class="val">{{ $stats['essay'] }}</span>
        <span class="lbl">อัตนัย</span>
    </div>
</div>

{{-- Top bar --}}
<div class="qb-topbar">
    <h5><i class="fas fa-database mr-2" style="color:#3b82f6;"></i>คลังข้อสอบ
        <span style="font-size:.8rem;font-weight:400;color:#94a3b8;margin-left:6px;">({{ $questions->total() }} ข้อ)</span>
    </h5>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('teacher.question_bank.import_form') }}" class="btn-new" style="background:#059669;">
            <i class="fas fa-file-import"></i> นำเข้าจากไฟล์
        </a>
        <a href="{{ route('teacher.question_bank.create') }}" class="btn-new">
            <i class="fas fa-plus"></i> เพิ่มข้อสอบ
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="qb-filters">
    <form method="GET" action="{{ route('teacher.question_bank.index') }}">
        <div class="fg">
            <label>ค้นหา</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="พิมพ์คำถาม...">
        </div>
        <div class="fg">
            <label>หมวดวิชา</label>
            <select name="subject_id">
                <option value="">-- ทั้งหมด --</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id')==$s->id?'selected':'' }}>
                        {{ $s->name }} ({{ $s->course->name ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="fg">
            <label>หมวดหมู่</label>
            <select name="category">
                <option value="">-- ทั้งหมด --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="fg">
            <label>ประเภท</label>
            <select name="type">
                <option value="">-- ทั้งหมด --</option>
                <option value="multiple_choice" {{ request('type')==='multiple_choice'?'selected':'' }}>ปรนัย</option>
                <option value="essay" {{ request('type')==='essay'?'selected':'' }}>อัตนัย</option>
            </select>
        </div>
        <div class="fg">
            <label>สถานะ</label>
            <select name="status">
                <option value="">-- ทั้งหมด --</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>เปิดใช้งาน</option>
                <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>ปิดใช้งาน</option>
            </select>
        </div>
        <div class="fg" style="justify-content:flex-end;">
            <label>&nbsp;</label>
            <div style="display:flex;gap:6px;">
                <button type="submit" class="btn-filter"><i class="fas fa-search mr-1"></i>กรอง</button>
                <a href="{{ route('teacher.question_bank.index') }}" class="btn-reset"><i class="fas fa-redo mr-1"></i>ล้าง</a>
            </div>
        </div>
    </form>
</div>

{{-- Bulk action bar --}}
<div class="bulk-bar" id="bulkBar">
    <div class="bulk-info">
        <i class="fas fa-check-double"></i>
        <span>เลือกแล้ว <span class="bulk-count" id="bulkCount">0</span> ข้อ</span>
        <button type="button" class="btn-deselect" id="btnDeselectAll"><i class="fas fa-times mr-1"></i>ยกเลิกทั้งหมด</button>
    </div>
    <div class="bulk-actions">
        <button type="button" class="btn-bulk primary" id="btnBulkAssign">
            <i class="fas fa-link"></i> เชื่อมโยงชุดข้อสอบ
        </button>
    </div>
</div>

{{-- Table --}}
@if($questions->isEmpty())
<div class="empty-box">
    <div class="empty-icon"><i class="fas fa-database" style="color:#cbd5e1;"></i></div>
    <h6>ยังไม่มีข้อสอบในคลัง</h6>
    <p>เริ่มเพิ่มข้อสอบเข้าคลังเพื่อใช้ร่วมกับชุดข้อสอบต่างๆ</p>
    <a href="{{ route('teacher.question_bank.create') }}" class="btn-new" style="display:inline-flex;">
        <i class="fas fa-plus"></i> เพิ่มข้อสอบ
    </a>
</div>
@else
<div class="qb-table">
    <div class="table-responsive">
    <table class="table mb-0">
        <thead>
            <tr>
                <th style="width:40px;" class="text-center">
                    <input type="checkbox" id="chkAll" title="เลือกทั้งหมดในหน้านี้">
                </th>
                <th style="width:40px;">#</th>
                <th>คำถาม</th>
                <th>หมวดวิชา</th>
                <th>ประเภท</th>
                <th>คะแนน</th>
                <th>ชุดข้อสอบ</th>
                <th>สถานะ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
        @foreach($questions as $i => $q)
            <tr data-qid="{{ $q->id }}">
                <td class="text-center">
                    <input type="checkbox" class="q-chk" value="{{ $q->id }}">
                </td>
                <td class="text-muted">{{ $questions->firstItem() + $i }}</td>
                <td>
                    <div class="q-text">{{ Str::limit($q->question_text, 80) }}</div>
                    @if($q->type === 'multiple_choice' && $q->option_a)
                        <small class="text-muted">
                            ก. {{ Str::limit($q->option_a, 25) }}
                            ข. {{ Str::limit($q->option_b, 25) }}
                        </small>
                    @endif
                </td>
                <td>
                    @if($q->subject)
                        <div style="font-size:.82rem;font-weight:500;">{{ $q->subject->name }}</div>
                        @if($q->subject->category)
                            <span class="badge-cat">{{ $q->subject->category }}</span>
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if($q->type === 'multiple_choice')
                        <span class="badge-type badge-mc">ปรนัย</span>
                    @else
                        <span class="badge-type badge-es">อัตนัย</span>
                    @endif
                </td>
                <td style="font-weight:600;">{{ $q->points }}</td>
                <td>
                    @php $esCount = $q->examSets->count(); @endphp
                    @if($esCount > 0)
                        <span class="badge-link"><i class="fas fa-link mr-1"></i>{{ $esCount }} ชุด</span>
                    @else
                        <span class="text-muted" style="font-size:.78rem;">ยังไม่ได้เชื่อมโยง</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('teacher.question_bank.toggle', $q) }}" method="POST">
                        @csrf
                        <label class="tgl" title="{{ $q->is_active ? 'คลิกเพื่อปิด' : 'คลิกเพื่อเปิด' }}">
                            <input type="checkbox" {{ $q->is_active ? 'checked' : '' }}
                                   onchange="this.closest('form').submit()">
                            <span class="tgl-sld"></span>
                        </label>
                    </form>
                </td>
                <td>
                    <div class="ec-actions">
                        @if($q->type === 'essay')
                        <a href="{{ route('teacher.question_bank.criteria.index', $q) }}"
                           class="act-btn" title="เกณฑ์การให้คะแนน" style="{{ $q->essayCriteria->count() > 0 ? 'color:#16a34a;border-color:#bbf7d0;' : '' }}">
                            <i class="fas fa-clipboard-check"></i>
                        </a>
                        @endif
                        <a href="{{ route('teacher.question_bank.assign_form', $q) }}"
                           class="act-btn link" title="เชื่อมโยงชุดข้อสอบ">
                            <i class="fas fa-link"></i>
                        </a>
                        <a href="{{ route('teacher.question_bank.edit', $q) }}"
                           class="act-btn edit" title="แก้ไข">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('teacher.question_bank.destroy', $q) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('ลบข้อสอบนี้จากคลัง?')">
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
    <div class="card-footer">{{ $questions->links() }}</div>
</div>
@endif

{{-- Bulk assign modal --}}
<div class="modal-overlay" id="bulkModal">
    <div class="modal-box">
        <div class="modal-head">
            <h6><i class="fas fa-link mr-2" style="color:#3b82f6;"></i>เชื่อมโยงข้อสอบเข้าชุดข้อสอบ</h6>
            <button type="button" class="modal-close" id="modalClose">&times;</button>
        </div>
        <form method="POST" action="{{ route('teacher.question_bank.bulk_assign') }}" id="bulkForm">
            @csrf
            <div id="bulkQuestionInputs"></div>

            <div class="m-info-bar">
                <span><i class="fas fa-info-circle mr-1"></i>ข้อสอบที่เลือก: <strong id="modalQCount">0</strong> ข้อ</span>
                <span class="m-sel-count" id="modalEsCount">เลือกชุดข้อสอบ: 0</span>
            </div>

            <div class="modal-body">
                @if($groupedExamSets->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle mr-1"></i>ยังไม่มีชุดข้อสอบ
                    </div>
                @else
                <table class="m-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="modalChkAll" title="เลือกทั้งหมด"></th>
                            <th>ชื่อชุดข้อสอบ</th>
                            <th>หมวดวิชา</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($groupedExamSets as $catName => $catEs)
                        <tr class="m-cat-row">
                            <td colspan="4">
                                <div class="m-cat-label">
                                    <span class="m-cat-icon" style="background:{{ $catColors[$catName] ?? '#94a3b8' }};">
                                        <i class="fas {{ $catIcons[$catName] ?? 'fa-folder' }}"></i>
                                    </span>
                                    {{ $catName }} ({{ $catEs->count() }})
                                </div>
                            </td>
                        </tr>
                        @foreach($catEs as $es)
                        <tr onclick="var c=this.querySelector('input');c.checked=!c.checked;c.dispatchEvent(new Event('change',{bubbles:true}));">
                            <td onclick="event.stopPropagation();">
                                <input type="checkbox" name="exam_set_ids[]" value="{{ $es->id }}" class="m-es-chk">
                            </td>
                            <td><span class="m-es-name">{{ $es->name }}</span></td>
                            <td><span class="m-es-sub">{{ $es->subject->name ?? '-' }}</span></td>
                            <td>
                                <span class="m-es-badge {{ $es->is_active ? 'badge-on' : 'badge-off' }}">
                                    {{ $es->is_active ? 'เปิด' : 'ปิด' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>

            <div class="modal-foot">
                <button type="button" class="btn-modal-cancel" id="modalCancel">ยกเลิก</button>
                <button type="submit" class="btn-modal-save" id="modalSubmit" disabled>
                    <i class="fas fa-save"></i> บันทึกการเชื่อมโยง
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
(function () {
    var chkAll       = document.getElementById('chkAll');
    var bulkBar      = document.getElementById('bulkBar');
    var bulkCount    = document.getElementById('bulkCount');
    var btnDeselect  = document.getElementById('btnDeselectAll');
    var btnBulkAssign = document.getElementById('btnBulkAssign');
    var modal        = document.getElementById('bulkModal');
    var modalClose   = document.getElementById('modalClose');
    var modalCancel  = document.getElementById('modalCancel');
    var modalQCount  = document.getElementById('modalQCount');
    var modalEsCount = document.getElementById('modalEsCount');
    var modalChkAll  = document.getElementById('modalChkAll');
    var bulkInputs   = document.getElementById('bulkQuestionInputs');
    var bulkForm     = document.getElementById('bulkForm');
    var modalSubmit  = document.getElementById('modalSubmit');

    function getChecked() {
        return document.querySelectorAll('.q-chk:checked');
    }
    function getAllChk() {
        return document.querySelectorAll('.q-chk');
    }

    function updateBulkBar() {
        var n = getChecked().length;
        bulkCount.textContent = n;
        if (n > 0) {
            bulkBar.classList.add('show');
        } else {
            bulkBar.classList.remove('show');
        }
        // update header checkbox
        var all = getAllChk();
        chkAll.checked = all.length > 0 && n === all.length;
        // highlight rows
        all.forEach(function (c) {
            var tr = c.closest('tr');
            if (c.checked) tr.classList.add('is-selected');
            else tr.classList.remove('is-selected');
        });
    }

    // row checkboxes
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('q-chk')) {
            updateBulkBar();
        }
    });

    // header checkbox
    chkAll.addEventListener('change', function () {
        var checked = this.checked;
        getAllChk().forEach(function (c) { c.checked = checked; });
        updateBulkBar();
    });

    // deselect all
    btnDeselect.addEventListener('click', function () {
        getAllChk().forEach(function (c) { c.checked = false; });
        updateBulkBar();
    });

    // open modal
    btnBulkAssign.addEventListener('click', function () {
        var checked = getChecked();
        if (checked.length === 0) return;

        // populate hidden inputs
        bulkInputs.innerHTML = '';
        checked.forEach(function (c) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'question_ids[]';
            input.value = c.value;
            bulkInputs.appendChild(input);
        });
        modalQCount.textContent = checked.length;

        // reset exam set checkboxes
        document.querySelectorAll('.m-es-chk').forEach(function (c) { c.checked = false; });
        modalChkAll.checked = false;
        updateModalEsCount();

        modal.classList.add('show');
    });

    // close modal
    function closeModal() { modal.classList.remove('show'); }
    modalClose.addEventListener('click', closeModal);
    modalCancel.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // modal exam set checkboxes
    function updateModalEsCount() {
        var n = document.querySelectorAll('.m-es-chk:checked').length;
        modalEsCount.textContent = 'เลือกชุดข้อสอบ: ' + n;
        modalSubmit.disabled = n === 0;
        // highlight checked rows
        document.querySelectorAll('.m-es-chk').forEach(function (c) {
            var tr = c.closest('tr');
            if (c.checked) tr.classList.add('is-checked');
            else tr.classList.remove('is-checked');
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('m-es-chk')) {
            updateModalEsCount();
            var all = document.querySelectorAll('.m-es-chk');
            var checked = document.querySelectorAll('.m-es-chk:checked');
            modalChkAll.checked = all.length > 0 && checked.length === all.length;
        }
    });

    modalChkAll.addEventListener('change', function () {
        var checked = this.checked;
        document.querySelectorAll('.m-es-chk').forEach(function (c) { c.checked = checked; });
        updateModalEsCount();
    });

    // form submit validation
    bulkForm.addEventListener('submit', function (e) {
        var esChecked = document.querySelectorAll('.m-es-chk:checked').length;
        if (esChecked === 0) {
            e.preventDefault();
            alert('กรุณาเลือกชุดข้อสอบอย่างน้อย 1 ชุด');
        }
    });
})();
</script>
@endsection
@endsection
