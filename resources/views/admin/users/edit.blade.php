@extends('layouts.admin')
@section('title','แก้ไขผู้ใช้')
@section('page-title','แก้ไขผู้ใช้')

@section('content')
<div class="card" style="max-width:580px;">
    <div class="card-header">แก้ไขข้อมูล: <strong>{{ $user->name }}</strong></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>อีเมล <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>รหัสผ่านใหม่ <small class="text-muted">(เว้นว่างหากไม่เปลี่ยน)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group col-md-6">
                    <label>ยืนยันรหัสผ่าน</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>บทบาท</label>
                    <select name="role" class="form-control" id="roleSelectEdit">
                        <option value="student"    {{ old('role',$user->role)=='student'?'selected':'' }}>นักเรียน</option>
                        <option value="teacher"    {{ old('role',$user->role)=='teacher'?'selected':'' }}>ครูผู้สอน</option>
                        <option value="superadmin" {{ old('role',$user->role)=='superadmin'?'selected':'' }}>SuperAdmin</option>
                    </select>
                </div>
                <div class="form-group col-md-6" id="classroomGroupEdit">
                    <label>ห้องเรียน</label>
                    <select name="classroom_id" class="form-control">
                        <option value="">-- ไม่ระบุ --</option>
                        @foreach($classrooms as $cl)
                            <option value="{{ $cl->id }}" {{ old('classroom_id',$user->classroom_id)==$cl->id?'selected':'' }}>{{ $cl->name }}</option>
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
document.getElementById('roleSelectEdit').addEventListener('change', function(){
    document.getElementById('classroomGroupEdit').style.display = this.value === 'student' ? '' : 'none';
});
document.getElementById('roleSelectEdit').dispatchEvent(new Event('change'));
</script>
@endsection
