@extends('layouts.teacher')
@section('title','แก้ไขหมวดวิชา')
@section('page-title','แก้ไขหมวดวิชา')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">แก้ไข: <strong>{{ $subject->name }}</strong></div>
    <div class="card-body">
        <form method="POST" action="{{ route('teacher.subjects.update', $subject) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>หมวดหลัก</label>
                <select name="category" class="form-control">
                    <option value="">— ไม่ระบุ —</option>
                    @foreach(['กระทู้','ธรรมะ','พุทธะ','วินัย','ศาสนพิธี','ภาษาบาลี'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $subject->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>ชื่อหมวดวิชา <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $subject->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>วิชา/คอร์ส <span class="text-danger">*</span></label>
                <select name="course_id" class="form-control @error('course_id') is-invalid @enderror" required>
                    <option value="">-- เลือกวิชา --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id',$subject->course_id)==$course->id?'selected':'' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>คำอธิบาย</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $subject->description) }}</textarea>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save mr-1"></i>บันทึก</button>
                <a href="{{ route('teacher.subjects.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
