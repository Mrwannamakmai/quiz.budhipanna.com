@extends('layouts.boss')
@section('title','ข้อมูลผู้สมัครสอบ')
@section('page-title','ข้อมูลผู้สมัครสอบ: ' . $registration->first_name)

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-user-circle mr-1"></i> รายละเอียดผู้สมัคร</span>
        <a href="{{ route('boss.registrations.index') }}" class="btn btn-sm btn-secondary">กลับไปหน้าแรก</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <h5 class="text-primary border-bottom pb-2">ข้อมูลส่วนตัว</h5>
                <p><strong>รหัสอ้างอิง:</strong> <code>{{ $registration->reference_code }}</code></p>
                <p><strong>ชื่อ-นามสกุล:</strong> {{ $registration->title }} {{ $registration->first_name }} {{ $registration->last_name }}</p>
                <p><strong>เลขประจำตัวประชาชน:</strong> {{ $registration->national_id }}</p>
                <p><strong>เบอร์โทรศัพท์:</strong> {{ $registration->phone ?: '-' }}</p>
                <p><strong>ระดับการศึกษาปัจจุบัน:</strong> {{ $registration->education_level ?: '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <h5 class="text-primary border-bottom pb-2">ข้อมูลการสมัครสอบ</h5>
                <p><strong>ชั้นที่สมัครสอบ:</strong> {{ \App\ExamRegistration::TIER_LABELS[$registration->exam_tier] ?? $registration->exam_tier }}</p>
                <p><strong>สำนักเรียน:</strong> {{ $registration->office_name ?: '-' }}</p>
                <p>
                    <strong>สถานะ:</strong>
                    @php $statusColors = ['registered'=>'warning','checked'=>'info','eligible'=>'success','absent'=>'secondary']; @endphp
                    <span class="badge badge-{{ $statusColors[$registration->status] ?? 'secondary' }}">{{ \App\ExamRegistration::STATUSES[$registration->status] ?? $registration->status }}</span>
                </p>
                <p><strong>วันที่สมัคร:</strong> {{ $registration->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>หมายเหตุ:</strong> {{ $registration->remarks ?: '-' }}</p>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('boss.registrations.edit', $registration) }}" class="btn btn-warning"><i class="fas fa-edit"></i> แก้ไขข้อมูล</a>
        </div>
    </div>
</div>
@endsection
