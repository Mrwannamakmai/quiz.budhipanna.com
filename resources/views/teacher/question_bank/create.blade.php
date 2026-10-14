@extends('layouts.teacher')
@section('title','เพิ่มข้อสอบในคลัง')
@section('page-title','เพิ่มข้อสอบใหม่ในคลัง')

@section('content')
<style>
.qb-form { display:grid; grid-template-columns:1fr 320px; gap:20px; }
@media(max-width:768px) { .qb-form { grid-template-columns:1fr; } }
.qb-card { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; padding:20px; }
.qb-card h6 { font-size:.85rem; font-weight:700; color:#1e293b; margin:0 0 16px; padding-bottom:10px; border-bottom:1.5px solid #f1f5f9; }
.qb-card h6 i { color:#3b82f6; margin-right:8px; }

.fg { margin-bottom:14px; }
.fg label { font-size:.8rem; font-weight:600; color:#334155; margin-bottom:4px; display:block; }
.fg .req { color:#ef4444; }
.fg select, .fg input, .fg textarea { width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:9px 12px; font-size:.85rem; background:#f8fafc; transition:border-color .15s; }
.fg select:focus, .fg input:focus, .fg textarea:focus { outline:none; border-color:#3b82f6; background:#fff; }
.fg .help { font-size:.72rem; color:#94a3b8; margin-top:3px; }

.cat-pills { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:14px; }
.cat-pill { padding:6px 14px; border-radius:20px; font-size:.78rem; font-weight:500; cursor:pointer; border:1.5px solid #e2e8f0; background:#f8fafc; color:#64748b; transition:all .15s; }
.cat-pill:hover { border-color:#3b82f6; color:#3b82f6; }
.cat-pill.active { background:#eff6ff; border-color:#3b82f6; color:#1d4ed8; font-weight:600; }

.tgl-row { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f1f5f9; }
.tgl-row:last-child { border-bottom:none; }
.tgl-info .tgl-label { font-size:.82rem; font-weight:600; color:#334155; }
.tgl-info .tgl-desc { font-size:.72rem; color:#94a3b8; }
.tgl { position:relative; display:inline-block; width:40px; height:22px; }
.tgl input { opacity:0; width:0; height:0; }
.tgl-sld { position:absolute; inset:0; background:#cbd5e1; border-radius:22px; cursor:pointer; transition:.2s; }
.tgl-sld:before { position:absolute; content:""; height:16px; width:16px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.2s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
.tgl input:checked+.tgl-sld { background:#16a34a; }
.tgl input:checked+.tgl-sld:before { transform:translateX(18px); }

.btn-save { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:10px 22px; font-size:.85rem; font-weight:600; cursor:pointer; transition:background .15s; }
.btn-save:hover { background:#1d3a9a; }
.btn-more { background:transparent; color:#1e40af; border:1.5px solid #1e40af; border-radius:8px; padding:10px 22px; font-size:.85rem; font-weight:600; cursor:pointer; transition:all .15s; }
.btn-more:hover { background:#eff6ff; }
.btn-cancel { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 22px; font-size:.85rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; }
.btn-cancel:hover { background:#f1f5f9; color:#334155; text-decoration:none; }
</style>

<form method="POST" action="{{ route('teacher.question_bank.store') }}">
@csrf
<div class="qb-form">

{{-- Left column: Question --}}
<div>
    <div class="qb-card">
        <h6><i class="fas fa-edit"></i>รายละเอียดข้อสอบ</h6>

        <div class="fg">
            <label>หมวดวิชา <span class="req">*</span></label>
            <div class="cat-pills" id="catPills">
                <span class="cat-pill active" data-cat="">ทั้งหมด</span>
                @foreach($categories as $cat)
                    <span class="cat-pill" data-cat="{{ $cat }}">{{ $cat }}</span>
                @endforeach
            </div>
            <select name="subject_id" id="subjectSelect" required>
                <option value="">-- เลือกหมวดวิชา --</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}"
                            data-cat="{{ $s->category }}"
                            {{ old('subject_id')==$s->id?'selected':'' }}>
                        {{ $s->name }} ({{ $s->course->name ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="fg">
            <label>ประเภทคำถาม <span class="req">*</span></label>
            <select name="type" id="typeSelect" required>
                <option value="multiple_choice" {{ old('type','multiple_choice')==='multiple_choice'?'selected':'' }}>ปรนัย (Multiple Choice)</option>
                <option value="essay" {{ old('type')==='essay'?'selected':'' }}>อัตนัย (Essay)</option>
            </select>
        </div>

        <div class="fg">
            <label>คำถาม <span class="req">*</span></label>
            <textarea name="question_text" rows="4" required>{{ old('question_text') }}</textarea>
        </div>

        <div id="essayAnswer" style="display:none;">
            <div class="fg">
                <label>เฉลยอัตนัย <span class="req">*</span></label>
                <textarea name="essay_answer" rows="4" placeholder="พิมพ์เฉลยคำตอบอัตนัย เพื่อใช้ตรวจอัตโนมัติ">{{ old('essay_answer') }}</textarea>
                <div class="help"><i class="fas fa-info-circle mr-1"></i>ระบบจะนำไปเปรียบเทียบกับคำตอบของผู้สอบ เพื่อให้คะแนนอัตโนมัติ</div>
            </div>
        </div>

        <div id="mcOptions">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="fg">
                    <label>ตัวเลือก ก <span class="req">*</span></label>
                    <input type="text" name="option_a" value="{{ old('option_a') }}">
                </div>
                <div class="fg">
                    <label>ตัวเลือก ข <span class="req">*</span></label>
                    <input type="text" name="option_b" value="{{ old('option_b') }}">
                </div>
                <div class="fg">
                    <label>ตัวเลือก ค</label>
                    <input type="text" name="option_c" value="{{ old('option_c') }}">
                </div>
                <div class="fg">
                    <label>ตัวเลือก ง</label>
                    <input type="text" name="option_d" value="{{ old('option_d') }}">
                </div>
            </div>
            <div class="fg">
                <label>เฉลย <span class="req">*</span></label>
                <select name="correct_answer" style="max-width:200px;">
                    <option value="a" {{ old('correct_answer')==='a'?'selected':'' }}>ก</option>
                    <option value="b" {{ old('correct_answer')==='b'?'selected':'' }}>ข</option>
                    <option value="c" {{ old('correct_answer')==='c'?'selected':'' }}>ค</option>
                    <option value="d" {{ old('correct_answer')==='d'?'selected':'' }}>ง</option>
                </select>
            </div>
        </div>
    </div>
</div>

{{-- Right column: Settings --}}
<div>
    <div class="qb-card">
        <h6><i class="fas fa-cog"></i>ตั้งค่า</h6>

        <div class="fg">
            <label>คะแนน <span class="req">*</span></label>
            <input type="number" name="points" value="{{ old('points', 1) }}" min="1" required style="max-width:120px;">
        </div>

        <div class="fg">
            <label>ลำดับ</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="max-width:120px;">
            <div class="help">ใช้สำหรับจัดเรียงลำดับข้อสอบ</div>
        </div>

        <div class="tgl-row">
            <div class="tgl-info">
                <div class="tgl-label">เปิดใช้งาน</div>
                <div class="tgl-desc">เปิดเพื่อให้ข้อสอบพร้อมใช้งาน</div>
            </div>
            <label class="tgl">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                <span class="tgl-sld"></span>
            </label>
        </div>
    </div>

    <div class="qb-card mt-3">
        <div style="display:flex;flex-direction:column;gap:8px;">
            <button type="submit" class="btn-save"><i class="fas fa-save mr-1"></i>บันทึก</button>
            <button type="submit" name="add_more" value="1" class="btn-more"><i class="fas fa-plus mr-1"></i>บันทึกและเพิ่มต่อ</button>
            <a href="{{ route('teacher.question_bank.index') }}" class="btn-cancel"><i class="fas fa-arrow-left mr-1"></i>ยกเลิก</a>
        </div>
    </div>
</div>

</div>
</form>

@section('scripts')
<script>
// Toggle MC options
var typeSelect   = document.getElementById('typeSelect');
var mcOptions    = document.getElementById('mcOptions');
var essayAnswer  = document.getElementById('essayAnswer');
function toggleType() {
    var isMC = typeSelect.value === 'multiple_choice';
    mcOptions.style.display   = isMC ? 'block' : 'none';
    essayAnswer.style.display = isMC ? 'none'  : 'block';
}
typeSelect.addEventListener('change', toggleType);
toggleType();

// Category pills filter
var pills  = document.querySelectorAll('#catPills .cat-pill');
var select = document.getElementById('subjectSelect');
var opts   = select.querySelectorAll('option[data-cat]');

pills.forEach(function(pill) {
    pill.addEventListener('click', function() {
        pills.forEach(function(p) { p.classList.remove('active'); });
        this.classList.add('active');
        var cat = this.getAttribute('data-cat');
        opts.forEach(function(opt) {
            if (!cat || opt.getAttribute('data-cat') === cat) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });
        select.value = '';
    });
});
</script>
@endsection
@endsection
