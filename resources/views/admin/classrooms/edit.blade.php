@extends('layouts.admin')
@section('title','แก้ไขห้องเรียน')
@section('page-title','แก้ไขห้องเรียน')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header">แก้ไขข้อมูลห้องเรียน: <strong>{{ $classroom->name }}</strong></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.classrooms.update', $classroom) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>ชื่อห้องเรียน <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $classroom->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>คำอธิบาย</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $classroom->description) }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>ลำดับการแสดง</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $classroom->sort_order) }}" min="0">
                </div>
                <div class="form-group col-md-6 d-flex align-items-end">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $classroom->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">เปิดใช้งาน</label>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save mr-1"></i>บันทึก</button>
                <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection
