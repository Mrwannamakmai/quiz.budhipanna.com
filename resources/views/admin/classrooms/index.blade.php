@extends('layouts.admin')
@section('title','ห้องเรียน')
@section('page-title','จัดการห้องเรียน')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">ห้องเรียนทั้งหมด</h5>
    <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus mr-1"></i> เพิ่มห้องเรียน
    </a>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr><th>#</th><th>ชื่อ</th><th>คำอธิบาย</th><th>ลำดับ</th><th>สถานะ</th><th>จัดการ</th></tr>
            </thead>
            <tbody>
            @forelse($classrooms as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td><strong>{{ $c->name }}</strong></td>
                    <td>{{ $c->description ?? '-' }}</td>
                    <td>{{ $c->sort_order }}</td>
                    <td>
                        @if($c->is_active)
                            <span class="badge badge-success">เปิด</span>
                        @else
                            <span class="badge badge-secondary">ปิด</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.classrooms.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.classrooms.destroy', $c) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('ยืนยันการลบ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">ยังไม่มีห้องเรียน</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
