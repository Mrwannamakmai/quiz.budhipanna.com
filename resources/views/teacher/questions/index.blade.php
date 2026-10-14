@extends('layouts.teacher')
@section('title','คำถาม')
@section('page-title','จัดการคำถาม')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0">คำถามใน: <strong>{{ $examSet->name }}</strong></h5>
        <small class="text-muted">{{ $questions->total() }} ข้อ</small>
    </div>
    <div>
        <a href="{{ route('teacher.exam_sets.questions.create', $examSet) }}" class="btn btn-primary btn-sm mr-1">
            <i class="fas fa-plus mr-1"></i> เพิ่มคำถาม
        </a>
        <a href="{{ route('teacher.exam_sets.questions.import_form', $examSet) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-file-import mr-1"></i> นำเข้า JSON
        </a>
        <a href="{{ route('teacher.exam_sets.index') }}" class="btn btn-outline-secondary btn-sm ml-1">
            <i class="fas fa-arrow-left mr-1"></i> กลับ
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0" id="questionTable">
            <thead class="thead-light">
                <tr>
                    <th style="width:40px;">#</th>
                    <th>คำถาม</th>
                    <th>ประเภท</th>
                    <th>คะแนน</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
            @forelse($questions as $i => $q)
                <tr data-id="{{ $q->id }}">
                    <td class="text-muted">{{ $questions->firstItem() + $i }}</td>
                    <td>
                        <div>{{ Str::limit($q->question_text, 80) }}</div>
                        @if($q->type === 'multiple_choice')
                            <small class="text-muted">
                                ก. {{ Str::limit($q->option_a, 30) }} &nbsp;
                                ข. {{ Str::limit($q->option_b, 30) }}
                            </small>
                        @endif
                    </td>
                    <td>
                        @if($q->type === 'multiple_choice')
                            <span class="badge badge-primary">ปรนัย</span>
                        @else
                            <span class="badge badge-warning text-dark">อัตนัย</span>
                        @endif
                    </td>
                    <td>{{ $q->points }} คะแนน</td>
                    <td class="text-nowrap">
                        <a href="{{ route('teacher.exam_sets.questions.edit', [$examSet, $q]) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('teacher.exam_sets.questions.destroy', [$examSet, $q]) }}"
                              method="POST" class="d-inline" onsubmit="return confirm('ลบคำถามนี้?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีคำถาม</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    <div class="card-footer">{{ $questions->links() }}</div>
</div>
@endsection
