@extends('layouts.teacher')
@section('title', 'รายงานผู้สมัครสอบธรรมศึกษา')
@section('page-title', 'รายงานผู้สมัครสอบธรรมศึกษา')

@section('content')
<style>
.er-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:18px}
.er-card h6{font-weight:700;color:#1e293b;border-bottom:2px solid #16a085;padding-bottom:8px;margin-bottom:12px}
</style>

<a href="{{ route('teacher.exam_registration.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left mr-1"></i>กลับ</a>

@php
    $reports = [
        'ตามชั้นที่สมัครสอบ (ตรี/โท/เอก)' => $byTargetLevel,
        'ตามช่วงชั้น (ประถม/มัธยม/อุดมศึกษา)' => $byTargetRange,
        'ตามสถานะใบสมัคร' => $byStatus,
        'ตามระดับการศึกษา' => $byEducationLevel,
        'ตามจังหวัด' => $byProvince,
        'ตามช่องทางที่ทราบข่าว' => $byReferralSource,
    ];
@endphp

<div class="row">
    @foreach ($reports as $title => $report)
        <div class="col-md-6">
            <div class="er-card">
                <h6>{{ $title }}</h6>
                <table class="table table-sm mb-0">
                    <tbody>
                        @forelse ($report['rows'] as $row)
                            <tr><td>{{ $row->label }}</td><td class="text-right">{{ $row->total }}</td></tr>
                        @empty
                            <tr><td colspan="2" class="text-muted text-center">ไม่มีข้อมูล</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold"><td>รวม</td><td class="text-right">{{ $report['total'] }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
