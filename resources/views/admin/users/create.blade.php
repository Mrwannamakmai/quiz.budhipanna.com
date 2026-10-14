@extends('layouts.admin')
@section('title','เพิ่มผู้ใช้')
@section('page-title','เพิ่มผู้ใช้ใหม่')

@section('content')
<div class="card" style="max-width:580px;">
    <div class="card-header">ข้อมูลผู้ใช้</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="form-group">
                <label>ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>อีเมล <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>รหัสผ่าน <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group col-md-6">
                    <label>ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>บทบาท <span class="text-danger">*</span></label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required id="roleSelect">
                        <option value="student"    {{ old('role')=='student'?'selected':'' }}>นักเรียน</option>
                        <option value="teacher"    {{ old('role')=='teacher'?'selected':'' }}>ครูผู้สอน</option>
                        <option value="superadmin" {{ old('role')=='superadmin'?'selected':'' }}>SuperAdmin</option>
                    </select>
                </div>
                <div class="form-group col-md-6" id="classroomGroup">
                    <label>ห้องเรียน</label>
                    <select name="classroom_id" class="form-control">
                        <option value="">-- ไม่ระบุ --</option>
                        @foreach($classrooms as $cl)
                            <option value="{{ $cl->id }}" {{ old('classroom_id')==$cl->id?'selected':'' }}>{{ $cl->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save mr-1"></i>บันทึก</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('roleSelect').addEventListener('change', function(){
    document.getElementById('classroomGroup').style.display = this.value === 'student' ? '' : 'none';
});
document.getElementById('roleSelect').dispatchEvent(new Event('change'));
</script>
@endsection
