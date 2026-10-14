@extends('layouts.teacher')
@section('title','เพิ่มหมวดวิชา')
@section('page-title','เพิ่มหมวดวิชาใหม่')

@section('content')
<style>
.sc-wrap { max-width:620px; }

/* ─── Card ─── */
.sc-card { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); overflow:hidden; margin-bottom:18px; }
.sc-card-header { display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid #f0f4f8; }
.sc-card-header .hicon { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
.sc-card-header h6 { margin:0; font-size:.9rem; font-weight:600; color:#1e293b; }
.sc-card-body { padding:20px; }

/* ─── Field ─── */
.field-lbl { font-size:.75rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.05em; display:block; margin-bottom:7px; }
.field-lbl .req { color:#ef4444; }
.sc-input { border:1.5px solid #e2e8f0; border-radius:8px; padding:9px 13px; font-size:.9rem; width:100%; background:#fafbfc; color:#1e293b; transition:border-color .15s,box-shadow .15s; }
.sc-input:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.12); background:#fff; }
.sc-input.is-invalid { border-color:#ef4444; }
.invalid-feedback { font-size:.78rem; color:#ef4444; margin-top:4px; }

/* ─── Category cards ─── */
.cat-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:4px; }
.cat-card { border:1.5px solid #e2e8f0; border-radius:10px; padding:12px 10px; text-align:center; cursor:pointer; transition:all .15s; background:#fafbfc; position:relative; }
.cat-card:hover { border-color:#94a3b8; background:#f8fafc; }
.cat-card.selected { border-color:var(--cat-color); background:var(--cat-bg); box-shadow:0 0 0 3px color-mix(in srgb, var(--cat-color) 15%, transparent); }
.cat-card input[type=radio] { position:absolute; opacity:0; width:0; height:0; }
.cat-icon { font-size:24px; margin-bottom:6px; display:block; }
.cat-name { font-size:.82rem; font-weight:600; color:#334155; }
.cat-card.selected .cat-name { color:var(--cat-color); }
.cat-check { position:absolute; top:7px; right:7px; width:18px; height:18px; border-radius:50%; background:var(--cat-color); color:#fff; font-size:10px; display:none; align-items:center; justify-content:center; }
.cat-card.selected .cat-check { display:flex; }

/* none option */
.cat-none { border:1.5px dashed #e2e8f0; border-radius:10px; padding:10px; text-align:center; cursor:pointer; transition:all .15s; background:transparent; position:relative; margin-top:0; }
.cat-none:hover { border-color:#94a3b8; background:#f8fafc; }
.cat-none.selected { border-color:#94a3b8; border-style:solid; background:#f1f5f9; }
.cat-none input[type=radio] { position:absolute; opacity:0; width:0; height:0; }
.cat-none-text { font-size:.8rem; color:#94a3b8; font-weight:500; }
.cat-none.selected .cat-none-text { color:#475569; }

/* ─── Submit bar ─── */
.sc-bar { display:flex; gap:10px; padding:16px 20px; background:#f8fafc; border-top:1px solid #f0f4f8; }
.btn-save { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:10px 24px; font-size:.9rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:7px; transition:background .15s; }
.btn-save:hover { background:#1d3a9a; color:#fff; }
.btn-cancel { color:#64748b; background:#fff; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 20px; font-size:.9rem; text-decoration:none; display:flex; align-items:center; gap:7px; transition:all .15s; }
.btn-cancel:hover { background:#f1f5f9; color:#334155; text-decoration:none; }
</style>

<div class="sc-wrap">
<form method="POST" action="{{ route('teacher.subjects.store') }}">
@csrf

{{-- ── หมวดหลัก ── --}}
<div class="sc-card">
    <div class="sc-card-header">
        <div class="hicon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-tags"></i></div>
        <h6>หมวดหลัก <span style="font-weight:400;color:#94a3b8;font-size:.8rem;">(ระบุทีหลังได้)</span></h6>
    </div>
    <div class="sc-card-body">
        <div class="cat-grid">
            @php
            $cats = [
                ['กระทู้',   '#d97706', '#fef3c7', '📜'],
                ['ธรรมะ',    '#059669', '#d1fae5', '🙏'],
                ['พุทธะ',    '#ea580c', '#ffedd5', '☸️'],
                ['วินัย',    '#2563eb', '#dbeafe', '📋'],
                ['ศาสนพิธี', '#7c3aed', '#ede9fe', '🎋'],
                ['ภาษาบาลี', '#7c3aed', '#ede9fe', '📜'],
            ];
            @endphp
            @foreach($cats as [$cat, $color, $bg, $emoji])
            <label class="cat-card {{ old('category') == $cat ? 'selected' : '' }}"
                   style="--cat-color:{{ $color }};--cat-bg:{{ $bg }};">
                <input type="radio" name="category" value="{{ $cat }}"
                       {{ old('category') == $cat ? 'checked' : '' }}
                       onchange="selectCat(this)">
                <span class="cat-icon">{{ $emoji }}</span>
                <div class="cat-name">{{ $cat }}</div>
                <div class="cat-check"><i class="fas fa-check"></i></div>
            </label>
            @endforeach
        </div>
        <label class="cat-none {{ old('category') == '' ? 'selected' : '' }}" id="cat-none-lbl">
            <input type="radio" name="category" value=""
                   {{ old('category') == '' ? 'checked' : '' }}
                   onchange="selectCat(this)" id="cat-none-input">
            <span class="cat-none-text"><i class="fas fa-minus-circle mr-1"></i>ไม่ระบุหมวดหลัก</span>
        </label>
    </div>
</div>

{{-- ── ข้อมูลหมวดวิชา ── --}}
<div class="sc-card">
    <div class="sc-card-header">
        <div class="hicon" style="background:#eff6ff;color:#3b82f6;"><i class="fas fa-layer-group"></i></div>
        <h6>ข้อมูลหมวดวิชา</h6>
    </div>
    <div class="sc-card-body">
        <div class="mb-3">
            <label class="field-lbl">ชื่อหมวดวิชา <span class="req">*</span></label>
            <input type="text" name="name"
                   class="sc-input @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" required
                   placeholder="เช่น ธรรมะชั้นตรี หมวด ๑">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="field-lbl">วิชา / คอร์ส <span class="req">*</span></label>
            <select name="course_id"
                    class="sc-input @error('course_id') is-invalid @enderror" required>
                <option value="">— เลือกคอร์ส —</option>
                @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ old('course_id')==$course->id?'selected':'' }}>
                    {{ $course->name }}
                </option>
                @endforeach
            </select>
            @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="field-lbl">คำอธิบาย</label>
            <textarea name="description" class="sc-input" rows="3"
                      placeholder="รายละเอียดเพิ่มเติม (ถ้ามี)">{{ old('description') }}</textarea>
        </div>
    </div>
    <div class="sc-bar">
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> บันทึก
        </button>
        <a href="{{ route('teacher.subjects.index') }}" class="btn-cancel">
            <i class="fas fa-times"></i> ยกเลิก
        </a>
    </div>
</div>

</form>
</div>
@endsection

@section('scripts')
<script>
function selectCat(radio) {
    document.querySelectorAll('.cat-card, .cat-none').forEach(function(el) {
        el.classList.remove('selected');
    });
    var lbl = radio.closest('.cat-card, .cat-none');
    if (lbl) lbl.classList.add('selected');
}
</script>
@endsection
