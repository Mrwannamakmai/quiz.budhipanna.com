@extends('layouts.teacher')
@section('title', 'รายละเอียดผู้สมัคร')
@section('page-title', 'รายละเอียดผู้สมัครสอบธรรมศึกษา')

@section('content')
<style>.er-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:18px}
dt{color:#64748b;font-weight:600}</style>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('teacher.exam_registration.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left mr-1"></i>กลับ</a>

<div class="er-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
        <div>
            <h4 class="mb-0">{{ $registration->fullName() }}</h4>
            <div class="text-muted">เลขอ้างอิง: {{ $registration->reference_code }}</div>
        </div>
        <span class="badge badge-pill badge-{{ strtolower($registration->status) }} p-2">{{ $registration->statusLabel() }}</span>
    </div>

    @if ($canManage && $registration->status === 'PENDING')
    <div class="mb-3">
        <form method="POST" action="{{ route('teacher.exam_registration.approve', $registration->id) }}" class="d-inline">
            @csrf
            <button class="btn btn-success" onclick="return confirm('อนุมัติผู้สมัครนี้?')"><i class="fas fa-check mr-1"></i>อนุมัติ</button>
        </form>
        <form method="POST" action="{{ route('teacher.exam_registration.reject', $registration->id) }}" class="d-inline">
            @csrf
            <button class="btn btn-danger" onclick="return confirm('ไม่อนุมัติผู้สมัครนี้?')"><i class="fas fa-times mr-1"></i>ไม่อนุมัติ</button>
        </form>
    </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <dl class="row">
                <dt class="col-5">เลขประจำตัวประชาชน</dt><dd class="col-7">{{ $registration->citizen_id }}</dd>
                <dt class="col-5">วันเกิด</dt><dd class="col-7">{{ $registration->dob_day }}/{{ $registration->dob_month }}/{{ $registration->dob_year_be }} (พ.ศ.)</dd>
                <dt class="col-5">โทรศัพท์</dt><dd class="col-7">{{ $registration->phone ?: '-' }}</dd>
                <dt class="col-5">LINE ID</dt><dd class="col-7">{{ $registration->line_id ?: '-' }}</dd>
                <dt class="col-5">ที่อยู่</dt><dd class="col-7">{{ $registration->address_no }} หมู่ {{ $registration->moo }} ต.{{ $registration->subdistrict }} อ.{{ $registration->district }} จ.{{ $registration->province }} {{ $registration->postal_code }}</dd>
            </dl>
        </div>
        <div class="col-md-6">
            <dl class="row">
                <dt class="col-5">ระดับการศึกษา</dt><dd class="col-7">{{ $registration->education_level }}</dd>
                <dt class="col-5">สมัครสอบชั้น</dt><dd class="col-7">{{ $registration->target_level }} / {{ $registration->target_range }}</dd>
                <dt class="col-5">เคยสอบได้ชั้น</dt><dd class="col-7">{{ $registration->previous_level ?: '-' }} ({{ $registration->previous_range ?: '-' }}) ปี {{ $registration->previous_passed_year_be ?: '-' }}</dd>
                <dt class="col-5">รอบสอบ</dt><dd class="col-7">{{ optional($registration->examSession)->name ?: '-' }}</dd>
                <dt class="col-5">สถานที่สอบ</dt><dd class="col-7">{{ optional($registration->examCenter)->name ?: '-' }}</dd>
                <dt class="col-5">สถานศึกษา/สำนักเรียน</dt><dd class="col-7">{{ $registration->office_name ?: '-' }}</dd>
                <dt class="col-5">แหล่งที่มาของข่าว</dt><dd class="col-7">{{ implode(', ', $registration->referralSourcesArray()) ?: '-' }}</dd>
            </dl>
        </div>
    </div>

    @if ($registration->status === 'APPROVED')
    <hr>
    <h6 class="font-weight-bold">สถานะติดตาม: {{ $registration->followUpStatusLabel() }}</h6>
    <a href="{{ route('teacher.exam_registration.followup.edit', $registration->id) }}" class="btn btn-sm btn-outline-primary">บันทึกการติดตาม</a>
    @if ($registration->followUps->isNotEmpty())
        <table class="table table-sm mt-3">
            <thead><tr><th>วันที่</th><th>ช่องทาง</th><th>ผล</th><th>หมายเหตุ</th><th>ผู้บันทึก</th></tr></thead>
            <tbody>
                @foreach ($registration->followUps as $f)
                    <tr>
                        <td>{{ optional($f->followed_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $f->channel }}</td>
                        <td>{{ $f->result }}</td>
                        <td>{{ $f->notes }}</td>
                        <td>{{ optional($f->followedBy)->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @endif
</div>
@endsection
