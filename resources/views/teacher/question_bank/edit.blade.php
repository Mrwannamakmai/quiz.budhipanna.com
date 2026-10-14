@extends('layouts.teacher')
@section('title','แก้ไขข้อสอบ')
@section('page-title','แก้ไขข้อสอบในคลัง')

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

.btn-save { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:10px 22px; font-size:.85rem; font-weight:600; cursor:pointer; transition:background .15s; width:100%; }
.btn-save:hover { background:#1d3a9a; }
.btn-cancel { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 22px; font-size:.85rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; width:100%; }
.btn-cancel:hover { background:#f1f5f9; color:#334155; text-decoration:none; }

.linked-sets { list-style:none; padding:0; margin:0; }
.linked-sets li { padding:8px 0; border-bottom:1px solid #f1f5f9; font-size:.82rem; display:flex; align-items:center; gap:8px; }
.linked-sets li:last-child { border-bottom:none; }
.linked-sets .dot { width:6px; height:6px; border-radius:50%; display:inline-block; }
.linked-sets .dot.on { background:#16a34a; }
.linked-sets .dot.off { background:#cbd5e1; }
</style>

<form method="POST" action="{{ route('teacher.question_bank.update', $question) }}">
@csrf @method('PUT')
<div class="qb-form">

{{-- Left column --}}
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
                            {{ old('subject_id', $question->subject_id)==$s->id?'selected':'' }}>
                        {{ $s->name }} ({{ $s->course->name ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="fg">
            <label>มิติ (Dimension)</label>
            <select name="dimension">
                <option value="">-- ไม่ระบุ --</option>
                @foreach(\App\Question::$dimensions as $key => $dim)
                <option value="{{ $key }}" {{ old('dimension', $question->dimension) === $key ? 'selected' : '' }}>
                    {{ $dim['short'] }} — {{ $dim['label'] }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="fg">
            <label>ประเภทคำถาม <span class="req">*</span></label>
            <select name="type" id="typeSelect" required>
                <option value="multiple_choice" {{ old('type', $question->type)==='multiple_choice'?'selected':'' }}>ปรนัย (Multiple Choice)</option>
                <option value="essay" {{ old('type', $question->type)==='essay'?'selected':'' }}>อัตนัย (Essay)</option>
            </select>
        </div>

        <div class="fg">
            <label>คำถาม <span class="req">*</span></label>
            <textarea name="question_text" rows="4" required>{{ old('question_text', $question->question_text) }}</textarea>
        </div>

        <div id="essayAnswer" style="display:none;">
            <div class="fg">
                <label>เฉลยอัตนัย <span class="req">*</span></label>
                <textarea name="essay_answer" rows="4" placeholder="พิมพ์เฉลยคำตอบอัตนัย เพื่อใช้ตรวจอัตโนมัติ">{{ old('essay_answer', $question->essay_answer) }}</textarea>
                <div class="help"><i class="fas fa-info-circle mr-1"></i>ระบบจะนำไปเปรียบเทียบกับคำตอบของผู้สอบ เพื่อให้คะแนนอัตโนมัติ</div>
            </div>
        </div>

        <div id="mcOptions">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="fg">
                    <label>ตัวเลือก ก <span class="req">*</span></label>
                    <input type="text" name="option_a" value="{{ old('option_a', $question->option_a) }}">
                </div>
                <div class="fg">
                    <label>ตัวเลือก ข <span class="req">*</span></label>
                    <input type="text" name="option_b" value="{{ old('option_b', $question->option_b) }}">
                </div>
                <div class="fg">
                    <label>ตัวเลือก ค</label>
                    <input type="text" name="option_c" value="{{ old('option_c', $question->option_c) }}">
                </div>
                <div class="fg">
                    <label>ตัวเลือก ง</label>
                    <input type="text" name="option_d" value="{{ old('option_d', $question->option_d) }}">
                </div>
            </div>
            <div class="fg">
                <label>เฉลย <span class="req">*</span></label>
                <select name="correct_answer" style="max-width:200px;">
                    <option value="a" {{ old('correct_answer', $question->correct_answer)==='a'?'selected':'' }}>ก</option>
                    <option value="b" {{ old('correct_answer', $question->correct_answer)==='b'?'selected':'' }}>ข</option>
                    <option value="c" {{ old('correct_answer', $question->correct_answer)==='c'?'selected':'' }}>ค</option>
                    <option value="d" {{ old('correct_answer', $question->correct_answer)==='d'?'selected':'' }}>ง</option>
                </select>
            </div>
        </div>
    </div>
</div>

{{-- Right column --}}
<div>
    <div class="qb-card">
        <h6><i class="fas fa-cog"></i>ตั้งค่า</h6>

        <div class="fg">
            <label>คะแนน <span class="req">*</span></label>
            <input type="number" name="points" value="{{ old('points', $question->points) }}" min="1" required style="max-width:120px;">
        </div>

        <div class="fg">
            <label>ลำดับ</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $question->sort_order) }}" min="0" style="max-width:120px;">
        </div>

        <div class="tgl-row">
            <div class="tgl-info">
                <div class="tgl-label">เปิดใช้งาน</div>
                <div class="tgl-desc">เปิดเพื่อให้ข้อสอบพร้อมใช้งาน</div>
            </div>
            <label class="tgl">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
                <span class="tgl-sld"></span>
            </label>
        </div>
    </div>

    @if($question->type === 'essay')
    <div class="qb-card mt-3">
        <h6><i class="fas fa-clipboard-check"></i>เกณฑ์การให้คะแนน</h6>
        @php $criteriaCount = $question->essayCriteria()->count(); @endphp
        @if($criteriaCount > 0)
            <div style="font-size:.82rem;color:#1e293b;margin-bottom:8px;">
                <i class="fas fa-check-circle text-success mr-1"></i>ตั้งค่าแล้ว {{ $criteriaCount }} เกณฑ์
                <span class="text-muted">({{ number_format($question->essayCriteria()->sum('max_points'), 1) }} คะแนน)</span>
            </div>
        @else
            <div style="font-size:.82rem;color:#94a3b8;margin-bottom:8px;">
                <i class="fas fa-info-circle mr-1"></i>ยังไม่ได้ตั้งเกณฑ์ — จะใช้การให้คะแนนแบบรวม
            </div>
        @endif
        <a href="{{ route('teacher.question_bank.criteria.index', $question) }}"
           style="font-size:.82rem;color:#3b82f6;font-weight:600;text-decoration:none;">
            <i class="fas fa-cog mr-1"></i>จัดการเกณฑ์การให้คะแนน
        </a>
    </div>
    @endif

    @if($examSets->isNotEmpty())
    <div class="qb-card mt-3">
        <h6><i class="fas fa-link"></i>ชุดข้อสอบที่เชื่อมโยง</h6>
        <ul class="linked-sets">
            @foreach($examSets as $es)
            <li>
                <span class="dot {{ $es->is_active ? 'on' : 'off' }}"></span>
                {{ $es->name }}
                @if($es->subject)
                    <small class="text-muted">({{ $es->subject->name }})</small>
                @endif
            </li>
            @endforeach
        </ul>
        <div style="margin-top:10px;">
            <a href="{{ route('teacher.question_bank.assign_form', $question) }}" style="font-size:.8rem;color:#3b82f6;">
                <i class="fas fa-cog mr-1"></i>จัดการการเชื่อมโยง
            </a>
        </div>
    </div>
    @endif

    <div class="qb-card mt-3">
        <div style="display:flex;flex-direction:column;gap:8px;">
            <button type="submit" class="btn-save"><i class="fas fa-save mr-1"></i>บันทึกการแก้ไข</button>
            <a href="{{ route('teacher.question_bank.index') }}" class="btn-cancel"><i class="fas fa-arrow-left mr-1"></i>ยกเลิก</a>
        </div>
    </div>
</div>

</div>
</form>

@section('scripts')
<script>
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
    });
});
</script>
@endsection
@endsection
