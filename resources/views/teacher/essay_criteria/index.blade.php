@extends('layouts.teacher')
@section('title','เกณฑ์การให้คะแนน')
@section('page-title','เกณฑ์การตรวจและให้คะแนนอัตนัย')

@section('content')
<style>
.ec-top { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:20px; flex-wrap:wrap; }
.ec-top h5 { margin:0; font-size:1rem; font-weight:700; color:#1e293b; }
.ec-top .sub { font-size:.8rem; color:#94a3b8; margin-top:4px; }

.ec-card { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; overflow:hidden; }

/* ─── Question preview ─── */
.q-preview { background:linear-gradient(135deg,#f0f9ff,#eff6ff); border-radius:12px; padding:18px 20px; margin-bottom:20px; border:1.5px solid #bfdbfe; }
.q-preview .q-label { font-size:.7rem; font-weight:600; color:#3b82f6; text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px; }
.q-preview .q-text { font-size:.9rem; color:#1e293b; font-weight:500; line-height:1.5; }
.q-preview .q-meta { display:flex; gap:12px; margin-top:10px; }
.q-preview .q-meta span { font-size:.75rem; color:#64748b; background:#fff; padding:3px 10px; border-radius:12px; }

/* ─── Stats row ─── */
.ec-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:20px; }
.ec-stat { background:#fff; border-radius:10px; padding:14px; text-align:center; box-shadow:0 1px 4px rgba(0,0,0,.06); border:1.5px solid #f1f5f9; }
.ec-stat .val { font-size:1.3rem; font-weight:700; display:block; }
.ec-stat .lbl { font-size:.72rem; color:#94a3b8; }
.ec-stat.total .val { color:#1e293b; }
.ec-stat.criteria .val { color:#3b82f6; }
.ec-stat.diff .val { color:#f59e0b; }
.ec-stat.match .val { color:#16a34a; }

/* ─── Criteria list ─── */
.cr-list { list-style:none; padding:0; margin:0; }
.cr-item { display:flex; align-items:flex-start; gap:14px; padding:16px 20px; border-bottom:1px solid #f1f5f9; transition:background .15s; }
.cr-item:last-child { border-bottom:none; }
.cr-item:hover { background:#fafbfd; }
.cr-num { width:28px; height:28px; border-radius:8px; background:#eff6ff; color:#3b82f6; font-weight:700; font-size:.8rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px; }
.cr-body { flex:1; min-width:0; }
.cr-name { font-size:.88rem; font-weight:600; color:#1e293b; }
.cr-desc { font-size:.78rem; color:#94a3b8; margin-top:3px; line-height:1.4; }
.cr-pts { font-size:.82rem; font-weight:700; color:#f59e0b; white-space:nowrap; background:#fffbeb; padding:4px 10px; border-radius:8px; flex-shrink:0; }
.cr-actions { display:flex; gap:5px; flex-shrink:0; }
.cr-btn { width:30px; height:30px; border-radius:7px; border:1.5px solid #e2e8f0; background:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:12px; color:#64748b; cursor:pointer; transition:all .15s; }
.cr-btn:hover { background:#f1f5f9; border-color:#94a3b8; color:#334155; }
.cr-btn.danger:hover { background:#fef2f2; border-color:#fca5a5; color:#dc2626; }

/* ─── Add form ─── */
.add-card { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; padding:20px; }
.add-card h6 { font-size:.85rem; font-weight:700; color:#1e293b; margin:0 0 16px; padding-bottom:10px; border-bottom:1.5px solid #f1f5f9; }
.add-card h6 i { color:#16a34a; margin-right:8px; }
.fg { margin-bottom:12px; }
.fg label { font-size:.78rem; font-weight:600; color:#334155; margin-bottom:3px; display:block; }
.fg .req { color:#ef4444; }
.fg input, .fg textarea { width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:8px 12px; font-size:.85rem; background:#f8fafc; transition:border-color .15s; }
.fg input:focus, .fg textarea:focus { outline:none; border-color:#3b82f6; background:#fff; }
.btn-add { background:#16a34a; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:.85rem; font-weight:600; cursor:pointer; width:100%; transition:background .15s; }
.btn-add:hover { background:#15803d; }

/* ─── Edit modal ─── */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:1000; align-items:center; justify-content:center; }
.modal-overlay.active { display:flex; }
.modal-box { background:#fff; border-radius:14px; padding:24px; width:90%; max-width:460px; box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-box h6 { font-size:.9rem; font-weight:700; color:#1e293b; margin:0 0 16px; }
.modal-actions { display:flex; gap:8px; margin-top:16px; }
.btn-modal-save { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:9px 18px; font-size:.85rem; font-weight:600; cursor:pointer; flex:1; }
.btn-modal-cancel { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:8px; padding:9px 18px; font-size:.85rem; cursor:pointer; flex:1; }

/* ─── Template presets ─── */
.preset-bar { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px; }
.preset-btn { padding:6px 14px; border-radius:20px; font-size:.76rem; font-weight:500; cursor:pointer; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .15s; text-decoration:none; }
.preset-btn:hover { border-color:#3b82f6; color:#3b82f6; background:#eff6ff; text-decoration:none; }

/* ─── Empty ─── */
.empty-box { text-align:center; padding:40px 20px; }
.empty-box i { font-size:40px; color:#e2e8f0; margin-bottom:10px; }
.empty-box p { color:#94a3b8; font-size:.85rem; margin:0; }

.btn-back { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:8px; padding:8px 16px; font-size:.85rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
.btn-back:hover { background:#f1f5f9; color:#334155; text-decoration:none; }

@media(max-width:768px) {
    .ec-grid { grid-template-columns:1fr !important; }
    .ec-stats { grid-template-columns:repeat(2,1fr); }
}
</style>

{{-- Question preview --}}
<div class="q-preview">
    <div class="q-label"><i class="fas fa-question-circle mr-1"></i>ข้อสอบอัตนัย</div>
    <div class="q-text">{{ $question->question_text }}</div>
    <div class="q-meta">
        <span><i class="fas fa-star mr-1"></i>{{ $question->points }} คะแนนเต็ม</span>
        @if($question->subject)
            <span><i class="fas fa-layer-group mr-1"></i>{{ $question->subject->name }}</span>
        @endif
    </div>
</div>

{{-- Stats --}}
<div class="ec-stats">
    <div class="ec-stat criteria">
        <span class="val">{{ $criteria->count() }}</span>
        <span class="lbl">เกณฑ์ทั้งหมด</span>
    </div>
    <div class="ec-stat total">
        <span class="val">{{ number_format($totalMaxPoints, 1) }}</span>
        <span class="lbl">คะแนนรวมเกณฑ์</span>
    </div>
    <div class="ec-stat {{ abs($totalMaxPoints - $question->points) < 0.01 ? 'match' : 'diff' }}">
        <span class="val">{{ number_format($question->points - $totalMaxPoints, 1) }}</span>
        <span class="lbl">ส่วนต่างจากคะแนนเต็ม</span>
    </div>
</div>

@if(abs($totalMaxPoints - $question->points) > 0.01 && $criteria->count() > 0)
<div class="alert alert-warning" style="font-size:.85rem;">
    <i class="fas fa-exclamation-triangle mr-1"></i>
    คะแนนรวมเกณฑ์ ({{ number_format($totalMaxPoints, 1) }}) ไม่ตรงกับคะแนนเต็มข้อสอบ ({{ $question->points }}) — ควรปรับให้ตรงกัน
</div>
@endif

<div class="ec-grid" style="display:grid;grid-template-columns:1fr 340px;gap:20px;">

{{-- Left: Criteria list --}}
<div>
    <div class="ec-card">
        <div style="padding:16px 20px;border-bottom:1.5px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
            <h6 style="margin:0;font-size:.88rem;font-weight:700;color:#1e293b;">
                <i class="fas fa-list-ol mr-2" style="color:#3b82f6;"></i>รายการเกณฑ์การให้คะแนน
            </h6>
        </div>

        @if($criteria->isEmpty())
        <div class="empty-box">
            <i class="fas fa-clipboard-list"></i>
            <p>ยังไม่มีเกณฑ์การให้คะแนน<br>เพิ่มเกณฑ์จากฟอร์มด้านขวา หรือใช้เทมเพลตด้านล่าง</p>
        </div>
        @else
        <ul class="cr-list">
            @foreach($criteria as $i => $c)
            <li class="cr-item">
                <span class="cr-num">{{ $i + 1 }}</span>
                <div class="cr-body">
                    <div class="cr-name">{{ $c->name }}</div>
                    @if($c->description)
                        <div class="cr-desc">{{ $c->description }}</div>
                    @endif
                </div>
                <span class="cr-pts">{{ number_format($c->max_points, 1) }} คะแนน</span>
                <div class="cr-actions">
                    <button class="cr-btn" title="แก้ไข"
                            onclick="openEditModal({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ addslashes($c->description) }}', {{ $c->max_points }}, {{ $c->sort_order }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('teacher.question_bank.criteria.destroy', [$question, $c]) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('ลบเกณฑ์ «{{ addslashes($c->name) }}» ?')">
                        @csrf @method('DELETE')
                        <button class="cr-btn danger" type="submit" title="ลบ">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>

    <div class="mt-3">
        <a href="{{ route('teacher.question_bank.edit', $question) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> กลับไปแก้ไขข้อสอบ
        </a>
    </div>
</div>

{{-- Right: Add form --}}
<div>
    {{-- Preset templates --}}
    @if($criteria->isEmpty())
    <div class="add-card mb-3">
        <h6><i class="fas fa-magic"></i>เทมเพลตเกณฑ์สำเร็จรูป</h6>
        <div class="preset-bar">
            <form method="POST" action="{{ route('teacher.question_bank.criteria.bulk', $question) }}" class="d-inline">
                @csrf
                <input type="hidden" name="criteria[0][name]" value="เนื้อหาถูกต้อง">
                <input type="hidden" name="criteria[0][description]" value="ตอบตรงประเด็น เนื้อหาถูกต้องครบถ้วน">
                <input type="hidden" name="criteria[0][max_points]" value="{{ number_format($question->points * 0.4, 1) }}">
                <input type="hidden" name="criteria[1][name]" value="การอธิบายและเหตุผล">
                <input type="hidden" name="criteria[1][description]" value="มีการอธิบายอย่างชัดเจน มีเหตุผลประกอบ">
                <input type="hidden" name="criteria[1][max_points]" value="{{ number_format($question->points * 0.3, 1) }}">
                <input type="hidden" name="criteria[2][name]" value="การใช้ภาษาและการเรียบเรียง">
                <input type="hidden" name="criteria[2][description]" value="ใช้ภาษาถูกต้อง เรียบเรียงเป็นระเบียบ อ่านง่าย">
                <input type="hidden" name="criteria[2][max_points]" value="{{ number_format($question->points * 0.2, 1) }}">
                <input type="hidden" name="criteria[3][name]" value="ความคิดสร้างสรรค์">
                <input type="hidden" name="criteria[3][description]" value="มีมุมมองที่น่าสนใจ หรือนำไปประยุกต์ใช้ได้">
                <input type="hidden" name="criteria[3][max_points]" value="{{ number_format($question->points * 0.1, 1) }}">
                <button type="submit" class="preset-btn"><i class="fas fa-file-alt mr-1"></i>ทั่วไป (4 เกณฑ์)</button>
            </form>
            <form method="POST" action="{{ route('teacher.question_bank.criteria.bulk', $question) }}" class="d-inline">
                @csrf
                <input type="hidden" name="criteria[0][name]" value="ความรู้ความเข้าใจ">
                <input type="hidden" name="criteria[0][description]" value="แสดงความเข้าใจในหลักธรรมได้ถูกต้อง">
                <input type="hidden" name="criteria[0][max_points]" value="{{ number_format($question->points * 0.5, 1) }}">
                <input type="hidden" name="criteria[1][name]" value="การอ้างอิงหลักฐาน">
                <input type="hidden" name="criteria[1][description]" value="อ้างพระไตรปิฎก คัมภีร์ หรือแหล่งข้อมูลที่เกี่ยวข้อง">
                <input type="hidden" name="criteria[1][max_points]" value="{{ number_format($question->points * 0.3, 1) }}">
                <input type="hidden" name="criteria[2][name]" value="การประยุกต์ใช้">
                <input type="hidden" name="criteria[2][description]" value="สามารถนำหลักธรรมไปประยุกต์ใช้ในชีวิตจริงได้">
                <input type="hidden" name="criteria[2][max_points]" value="{{ number_format($question->points * 0.2, 1) }}">
                <button type="submit" class="preset-btn"><i class="fas fa-dharmachakra mr-1"></i>พุทธศึกษา (3 เกณฑ์)</button>
            </form>
        </div>
    </div>
    @endif

    {{-- Manual add form --}}
    <div class="add-card">
        <h6><i class="fas fa-plus-circle"></i>เพิ่มเกณฑ์ใหม่</h6>
        <form method="POST" action="{{ route('teacher.question_bank.criteria.store', $question) }}">
            @csrf
            <div class="fg">
                <label>ชื่อเกณฑ์ <span class="req">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="เช่น เนื้อหาถูกต้อง">
            </div>
            <div class="fg">
                <label>คำอธิบาย</label>
                <textarea name="description" rows="2" placeholder="อธิบายรายละเอียดเกณฑ์...">{{ old('description') }}</textarea>
            </div>
            <div class="fg">
                <label>คะแนนเต็ม <span class="req">*</span></label>
                <input type="number" name="max_points" value="{{ old('max_points', 1) }}" min="0.5" step="0.5" required style="max-width:120px;">
            </div>
            <div class="fg">
                <label>ลำดับ</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $criteria->count()) }}" min="0" style="max-width:120px;">
            </div>
            <button type="submit" class="btn-add"><i class="fas fa-plus mr-1"></i>เพิ่มเกณฑ์</button>
        </form>
    </div>
</div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <h6><i class="fas fa-edit mr-2" style="color:#3b82f6;"></i>แก้ไขเกณฑ์</h6>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="fg">
                <label>ชื่อเกณฑ์ <span class="req">*</span></label>
                <input type="text" name="name" id="editName" required>
            </div>
            <div class="fg">
                <label>คำอธิบาย</label>
                <textarea name="description" id="editDesc" rows="2"></textarea>
            </div>
            <div class="fg">
                <label>คะแนนเต็ม <span class="req">*</span></label>
                <input type="number" name="max_points" id="editPoints" min="0.5" step="0.5" required style="max-width:120px;">
            </div>
            <div class="fg">
                <label>ลำดับ</label>
                <input type="number" name="sort_order" id="editOrder" min="0" style="max-width:120px;">
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-modal-save">บันทึก</button>
                <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">ยกเลิก</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
function openEditModal(id, name, desc, points, order) {
    document.getElementById('editForm').action =
        '{{ route("teacher.question_bank.criteria.update", [$question, "__ID__"]) }}'.replace('__ID__', id);
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editPoints').value = points;
    document.getElementById('editOrder').value = order;
    document.getElementById('editModal').classList.add('active');
}
function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endsection
@endsection
