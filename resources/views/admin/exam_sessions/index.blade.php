@extends('layouts.admin')
@section('title', 'รอบสอบธรรมศึกษา')
@section('page-title', 'รอบสอบธรรมศึกษา')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">จัดการรอบสอบ กำหนดการ และประกาศที่แสดงให้ผู้สมัครเห็น</p>
    <a href="{{ route('admin.exam_sessions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i>สร้างรอบสอบ
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ปีการศึกษา</th>
                    <th>วันสอบ</th>
                    <th>สถานะรับสมัคร</th>
                    <th>ประกาศ</th>
                    <th>ผู้สมัคร</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    <tr>
                        <td>{{ $session->academic_year }}</td>
                        <td>{{ $session->exam_date ? $session->exam_date->format('d/m/Y') : '—' }}</td>
                        <td>{{ ['draft' => 'ฉบับร่าง', 'open' => 'เปิดรับสมัคร', 'closed' => 'ปิดรับสมัคร', 'completed' => 'เสร็จสิ้น'][$session->status] }}</td>
                        <td>
                            @if($session->is_published)
                                <span class="badge badge-success">เผยแพร่</span>
                            @else
                                <span class="badge badge-secondary">ฉบับร่าง</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.exam_sessions.registrations', $session) }}">
                                {{ $session->registrations_count }} รายชื่อ
                            </a>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.exam_sessions.registrations', $session) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-users mr-1"></i>รายชื่อ
                            </a>
                            <a href="{{ route('admin.exam_sessions.edit', $session) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit mr-1"></i>แก้ไข
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">ยังไม่มีรอบสอบ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
