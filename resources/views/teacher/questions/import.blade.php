@extends('layouts.teacher')
@section('title','นำเข้าคำถาม')
@section('page-title','นำเข้าคำถามจาก JSON')

@section('content')
<div class="card" style="max-width:700px;">
    <div class="card-header">นำเข้าคำถามใน: <strong>{{ $examSet->name }}</strong></div>
    <div class="card-body">
        @if(session('import_result'))
            <div class="alert alert-info">{{ session('import_result') }}</div>
        @endif

        <p class="text-muted">วางข้อมูล JSON ตามรูปแบบด้านล่าง แต่ละข้อเป็น object ใน array</p>
        <pre class="bg-light p-3 rounded" style="font-size:0.8rem;">[
  {
    "type": "multiple_choice",
    "question_text": "คำถาม...",
    "option_a": "ก. ...",
    "option_b": "ข. ...",
    "option_c": "ค. ...",
    "option_d": "ง. ...",
    "correct_answer": "a",
    "points": 1
  },
  {
    "type": "essay",
    "question_text": "คำถามอัตนัย...",
    "points": 5
  }
]</pre>

        <form method="POST" action="{{ route('teacher.exam_sets.questions.import', $examSet) }}">
            @csrf
            <div class="form-group">
                <label>JSON Data <span class="text-danger">*</span></label>
                <textarea name="json_data" class="form-control @error('json_data') is-invalid @enderror"
                          rows="10" required placeholder="วาง JSON ที่นี่...">{{ old('json_data') }}</textarea>
                @error('json_data')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-file-import mr-1"></i>นำเข้า</button>
                <a href="{{ route('teacher.exam_sets.questions.index', $examSet) }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
