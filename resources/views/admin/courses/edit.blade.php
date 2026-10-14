@extends('layouts.admin')
@section('title','แก้ไขวิชา')
@section('page-title','แก้ไขวิชา')

@section('content')
<div class="card" style="max-width:580px;">
    <div class="card-header">แก้ไข: <strong>{{ $course->name }}</strong></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.courses.update', $course) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>ชื่อวิชา <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $course->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>คำอธิบาย</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $course->description) }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>ห้องเรียน <span class="text-danger">*</span></label>
                    <select name="classroom_id" class="form-control" required>
                        <option value="">-- เลือก --</option>
                        @foreach($classrooms as $cl)
                            <option value="{{ $cl->id }}" {{ old('classroom_id',$course->classroom_id)==$cl->id?'selected':'' }}>{{ $cl->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>ครูผู้สอน <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- เลือก --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ old('user_id',$course->user_id)==$t->id?'selected':'' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="custom-control custom-switch mb-3">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                       {{ old('is_active',$course->is_active) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save mr-1"></i>บันทึก</button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
