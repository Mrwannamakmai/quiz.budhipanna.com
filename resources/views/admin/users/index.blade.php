@extends('layouts.admin')
@section('title','ผู้ใช้งาน')
@section('page-title','จัดการผู้ใช้งาน')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="form-inline flex-wrap gap-2">
            <input type="text" name="search" class="form-control form-control-sm mr-2 mb-2" value="{{ request('search') }}" placeholder="ค้นหาชื่อ / อีเมล">
            <select name="role" class="form-control form-control-sm mr-2 mb-2">
                <option value="">ทุก Role</option>
                <option value="superadmin" {{ request('role')=='superadmin'?'selected':'' }}>SuperAdmin</option>
                <option value="teacher"    {{ request('role')=='teacher'?'selected':'' }}>ครู</option>
                <option value="student"    {{ request('role')=='student'?'selected':'' }}>นักเรียน</option>
            </select>
            <select name="classroom_id" class="form-control form-control-sm mr-2 mb-2">
                <option value="">ทุกห้องเรียน</option>
                @foreach($classrooms as $cl)
                    <option value="{{ $cl->id }}" {{ request('classroom_id')==$cl->id?'selected':'' }}>{{ $cl->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-secondary mr-2 mb-2"><i class="fas fa-search mr-1"></i>ค้นหา</button>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary mb-2"><i class="fas fa-plus mr-1"></i>เพิ่มผู้ใช้</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr><th>#</th><th>ชื่อ</th><th>อีเมล</th><th>Role</th><th>ห้องเรียน</th><th>จัดการ</th></tr>
            </thead>
            <tbody>
            @forelse($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td><strong>{{ $u->name }}</strong></td>
                    <td>{{ $u->email ?? '-' }}</td>
                    <td>
                        @if($u->role === 'superadmin')
                            <span class="badge badge-danger">SuperAdmin</span>
                        @elseif($u->role === 'teacher')
                            <span class="badge badge-primary">ครู</span>
                        @else
                            <span class="badge badge-success">นักเรียน</span>
                        @endif
                    </td>
                    <td>{{ $u->classroom->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary" title="แก้ไขข้อมูล">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($u->isSuperAdmin())
                            <span class="badge badge-light border text-muted px-2 py-1 ml-1" title="บัญชี SuperAdmin ได้รับการป้องกันสูงสุด ไม่สามารถลบได้">
                                <i class="fas fa-shield-alt text-danger"></i>
                            </span>
                        @elseif((int) $u->id !== (int) auth()->id())
                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('ยืนยันการลบผู้ใช้ {{ $u->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger ml-1" title="ลบผู้ใช้"><i class="fas fa-trash"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">ไม่พบข้อมูล</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    <div class="card-footer">{{ $users->appends(request()->query())->links() }}</div>
</div>
@endsection
