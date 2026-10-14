@extends('layouts.admin')
@section('title','วิชา / หลักสูตร')
@section('page-title','จัดการวิชา / หลักสูตร')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5 class="mb-0">วิชาทั้งหมด</h5>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i>เพิ่มวิชา</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr><th>#</th><th>ชื่อวิชา</th><th>ห้องเรียน</th><th>ครูผู้สอน</th><th>สถานะ</th><th>จัดการ</th></tr>
            </thead>
            <tbody>
            @forelse($courses as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td><strong>{{ $c->name }}</strong><br><small class="text-muted">{{ $c->description }}</small></td>
                    <td>{{ $c->classroom->name ?? '-' }}</td>
                    <td>{{ $c->teacher->name ?? '-' }}</td>
                    <td>
                        @if($c->is_active)<span class="badge badge-success">เปิด</span>
                        @else<span class="badge badge-secondary">ปิด</span>@endif
                    </td>
                    <td>
                        <a href="{{ route('admin.courses.edit', $c) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.courses.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">ยังไม่มีวิชา</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $courses->links() }}</div>
</div>
@endsection
