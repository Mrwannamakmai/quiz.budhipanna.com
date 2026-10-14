@extends('layouts.boss')
@section('title','จัดการผู้สมัครสอบ')
@section('page-title','รายชื่อผู้ลงทะเบียนสมัครสอบ')

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users mr-1"></i> ผู้ลงทะเบียนทั้งหมด</span>
    </div>
    <div class="card-body border-bottom bg-light">
        <form method="GET" action="{{ route('boss.registrations.index') }}" class="form-inline">
            <input type="text" name="search" class="form-control mr-2 mb-2" placeholder="ค้นหาชื่อ, เลขบัตร, รหัสอ้างอิง" value="{{ request('search') }}">
            <select name="status" class="form-control mr-2 mb-2">
                <option value="">-- ทุกสถานะ --</option>
                @foreach(\App\ExamRegistration::STATUSES as $status => $label)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary mb-2"><i class="fas fa-search"></i> ค้นหา</button>
            <a href="{{ route('boss.registrations.index') }}" class="btn btn-secondary mb-2 ml-2">ล้าง</a>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>รหัสอ้างอิง</th>
                        <th>ชื่อ-สกุล</th>
                        <th>ชั้น</th>
                        <th>สำนักเรียน</th>
                        <th>สถานะ</th>
                        <th>วันที่สมัคร</th>
                        <th class="text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($registrations as $reg)
                    @php
                        $statusColors = ['registered'=>'warning','checked'=>'info','eligible'=>'success','absent'=>'secondary'];
                    @endphp
                    <tr>
                        <td><code>{{ $reg->reference_code }}</code></td>
                        <td>{{ $reg->title }} {{ $reg->first_name }} {{ $reg->last_name }}</td>
                        <td>{{ \App\ExamRegistration::TIER_LABELS[$reg->exam_tier] ?? $reg->exam_tier }}</td>
                        <td>{{ $reg->office_name ?: '-' }}</td>
                        <td><span class="badge badge-{{ $statusColors[$reg->status] ?? 'secondary' }}">{{ \App\ExamRegistration::STATUSES[$reg->status] ?? $reg->status }}</span></td>
                        <td>{{ $reg->created_at ? $reg->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('boss.registrations.show', $reg) }}" class="btn btn-sm btn-info" title="ดูข้อมูล"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('boss.registrations.edit', $reg) }}" class="btn btn-sm btn-warning" title="แก้ไข"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4">ไม่พบข้อมูล</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($registrations->hasPages())
    <div class="card-footer">
        {{ $registrations->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
