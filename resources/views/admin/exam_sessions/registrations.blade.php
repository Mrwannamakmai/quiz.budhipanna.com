@extends('layouts.admin')
@section('title', 'รายชื่อผู้ลงทะเบียน')
@section('page-title', 'รายชื่อผู้ลงทะเบียน')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
    <div>
        <h5 class="mb-1">รอบสอบปีการศึกษา {{ $session->academic_year }}</h5>
        <div class="text-muted">
            @if($session->exam_date) วันสอบ {{ $session->exam_date->format('d/m/Y') }} · @endif
            ผู้ลงทะเบียนทั้งหมด {{ $registrations->total() }} คน
        </div>
    </div>
    <div>
        <a href="{{ route('admin.exam_sessions.export', [$session, 'format' => 'excel']) }}" class="btn btn-success mr-1">
            <i class="fas fa-file-excel mr-1"></i>Export Excel
        </a>
        <a href="{{ route('admin.exam_sessions.export', [$session, 'format' => 'pdf']) }}" class="btn btn-danger mr-1" target="_blank">
            <i class="fas fa-file-pdf mr-1"></i>Export PDF
        </a>
        <a href="{{ route('admin.exam_sessions.index') }}" class="btn btn-light">
            <i class="fas fa-arrow-left mr-1"></i>กลับไปรอบสอบ
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>เลขประจำตัวประชาชน</th>
                    <th>ชั้นเรียนปัจจุบัน</th>
                    <th>ชั้นที่สมัครสอบ</th>
                    <th>เลขประกาศนียบัตรชั้นเดิม</th>
                    <th>สนามสอบ</th>
                    <th>สถานะ</th>
                    <th>วันที่ลงทะเบียน</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $index => $registration)
                    <tr>
                        <td>{{ $registrations->firstItem() + $index }}</td>
                        <td>{{ $registration->title }}{{ $registration->first_name }} {{ $registration->last_name }}</td>
                        <td>{{ $registration->national_id }}</td>
                        <td>{{ $registration->education_class }}</td>
                        <td>{{ ['tree' => 'ธรรมศึกษาชั้นตรี', 'tho' => 'ธรรมศึกษาชั้นโท', 'ek' => 'ธรรมศึกษาชั้นเอก'][$registration->exam_tier] ?? $registration->exam_tier }}</td>
                        <td>{{ $registration->previous_certificate_no ?: '—' }}</td>
                        <td>{{ $registration->examCenter->name ?? '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.exam_sessions.registrations.status', $registration) }}" class="d-flex align-items-center">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-control form-control-sm mr-1" aria-label="สถานะผู้สมัคร">
                                    @foreach(\App\ExamRegistration::STATUSES as $status => $label)
                                        <option value="{{ $status }}" {{ $registration->status === $status ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary">บันทึก</button>
                            </form>
                        </td>
                        <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">ยังไม่มีผู้ลงทะเบียนในรอบสอบนี้</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($registrations->hasPages())
        <div class="card-footer">{{ $registrations->links() }}</div>
    @endif
</div>
@endsection
