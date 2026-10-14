@extends('layouts.teacher')
@section('title','แก้ไขคำถาม')
@section('page-title','แก้ไขคำถาม')

@section('content')
<div class="card" style="max-width:750px;">
    <div class="card-header">แก้ไขคำถามใน: <strong>{{ $examSet->name }}</strong></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.exam_sets.questions.update', [$examSet, $question]) }}">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>ประเภทคำถาม <span class="text-danger">*</span></label>
                    <select name="type" id="typeSelect" class="form-control" required>
                        <option value="multiple_choice" {{ old('type',$question->type)==='multiple_choice'?'selected':'' }}>ปรนัย (Multiple Choice)</option>
                        <option value="essay" {{ old('type',$question->type)==='essay'?'selected':'' }}>อัตนัย (Essay)</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>ด้านพัฒนา (4 ภาพ)</label>
                    <select name="dimension" class="form-control">
                        <option value="">-- ไม่ระบุ --</option>
                        <option value="kaya"    {{ old('dimension',$question->dimension)==='kaya'   ?'selected':'' }}>🟠 ภายภาพ</option>
                        <option value="conduct" {{ old('dimension',$question->dimension)==='conduct'?'selected':'' }}>🔵 พฤติภาพ</option>
                        <option value="citta"   {{ old('dimension',$question->dimension)==='citta'  ?'selected':'' }}>🟣 จิตภาพ</option>
                        <option value="panna"   {{ old('dimension',$question->dimension)==='panna'  ?'selected':'' }}>🟢 ปัญญาภาพ</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>คำถาม <span class="text-danger">*</span></label>
                <textarea name="question_text" class="form-control @error('question_text') is-invalid @enderror"
                          rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
                @error('question_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div id="essayAnswer" style="display:none;">
                <div class="form-group">
                    <label>เฉลยอัตนัย <span class="text-danger">*</span></label>
                    <textarea name="essay_answer" class="form-control" rows="4" placeholder="พิมพ์เฉลยคำตอบอัตนัย เพื่อใช้ตรวจอัตโนมัติ">{{ old('essay_answer', $question->essay_answer) }}</textarea>
                    <small class="form-text text-muted"><i class="fas fa-info-circle mr-1"></i>ระบบจะนำไปเปรียบเทียบกับคำตอบของผู้สอบ เพื่อให้คะแนนอัตโนมัติ</small>
                </div>
            </div>

            <div id="mcOptions">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>ตัวเลือก ก</label>
                        <input type="text" name="option_a" class="form-control" value="{{ old('option_a', $question->option_a) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>ตัวเลือก ข</label>
                        <input type="text" name="option_b" class="form-control" value="{{ old('option_b', $question->option_b) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>ตัวเลือก ค</label>
                        <input type="text" name="option_c" class="form-control" value="{{ old('option_c', $question->option_c) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>ตัวเลือก ง</label>
                        <input type="text" name="option_d" class="form-control" value="{{ old('option_d', $question->option_d) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>เฉลย</label>
                    <select name="correct_answer" class="form-control">
                        <option value="a" {{ old('correct_answer',$question->correct_answer)==='a'?'selected':'' }}>ก</option>
                        <option value="b" {{ old('correct_answer',$question->correct_answer)==='b'?'selected':'' }}>ข</option>
                        <option value="c" {{ old('correct_answer',$question->correct_answer)==='c'?'selected':'' }}>ค</option>
                        <option value="d" {{ old('correct_answer',$question->correct_answer)==='d'?'selected':'' }}>ง</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>คะแนน <span class="text-danger">*</span></label>
                <input type="number" name="points" class="form-control" style="max-width:120px;"
                       value="{{ old('points', $question->points) }}" min="1" required>
            </div>
            <div class="form-group">
                <label>ลำดับ</label>
                <input type="number" name="sort_order" class="form-control" style="max-width:120px;"
                       value="{{ old('sort_order', $question->sort_order) }}" min="0">
            </div>

            <div class="d-flex">
                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save mr-1"></i>บันทึก</button>
                <a href="{{ route('teacher.exam_sets.questions.index', $examSet) }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
var typeSelect  = document.getElementById('typeSelect');
var mcOptions   = document.getElementById('mcOptions');
var essayAnswer = document.getElementById('essayAnswer');
function toggleType() {
    var isMC = typeSelect.value === 'multiple_choice';
    mcOptions.style.display   = isMC ? 'block' : 'none';
    essayAnswer.style.display = isMC ? 'none'  : 'block';
}
typeSelect.addEventListener('change', toggleType);
toggleType();
</script>
@endsection
@endsection
