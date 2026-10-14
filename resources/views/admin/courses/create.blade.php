@extends('layouts.admin')
@section('title','เพิ่มวิชา')
@section('page-title','เพิ่มวิชาใหม่')

@section('content')
<div class="card" style="max-width:580px;">
    <div class="card-header">ข้อมูลวิชา</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.courses.store') }}">
            @csrf
            <div class="form-group">
                <label>ชื่อวิชา <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>คำอธิบาย</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>ห้องเรียน <span class="text-danger">*</span></label>
                    <select name="classroom_id" class="form-control @error('classroom_id') is-invalid @enderror" required>
                        <option value="">-- เลือกห้องเรียน --</option>
                        @foreach($classrooms as $cl)
                            <option value="{{ $cl->id }}" {{ old('classroom_id')==$cl->id?'selected':'' }}>{{ $cl->name }}</option>
                        @endforeach
                    </select>
                    @error('classroom_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group col-md-6">
                    <label>ครูผู้สอน <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">-- เลือกครู --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ old('user_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="custom-control custom-switch mb-3">
                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}>
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
