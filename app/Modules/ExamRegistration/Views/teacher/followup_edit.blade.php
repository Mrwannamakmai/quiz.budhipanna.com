@extends('layouts.teacher')
@section('title', 'บันทึกการติดตาม')
@section('page-title', 'บันทึกการติดตามผู้สมัคร')

@section('content')
<style>.er-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:18px}</style>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('teacher.exam_registration.followup.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left mr-1"></i>กลับ</a>

<div class="er-card">
    <h5>{{ $registration->fullName() }}</h5>
    <div class="text-muted mb-3">โทรศัพท์: {{ $registration->phone ?: '-' }} &nbsp;|&nbsp; LINE ID: {{ $registration->line_id ?: '-' }}</div>

    <form method="POST" action="{{ route('teacher.exam_registration.followup.update', $registration->id) }}">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>วันเวลาที่ติดตาม</label>
                <input type="datetime-local" name="followed_at" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label>ช่องทาง</label>
                <select name="channel" class="form-control">
                    <option value="โทรศัพท์">โทรศัพท์</option>
                    <option value="LINE">LINE</option>
                    <option value="SMS">SMS</option>
                    <option value="อื่น ๆ">อื่น ๆ</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>สถานะการติดตาม</label>
                <select name="follow_up_status" class="form-control">
                    @foreach ($followUpStatuses as $key => $label)
                        <option value="{{ $key }}" {{ $registration->follow_up_status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>ผลการติดตาม</label>
            <input type="text" name="result" class="form-control" placeholder="เช่น ยืนยันเข้าสอบ, ขอยกเลิก">
        </div>
        <div class="form-group">
            <label>หมายเหตุ</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>
        <button class="btn btn-primary"><i class="fas fa-save mr-1"></i>บันทึก</button>
    </form>

    @if ($registration->followUps->isNotEmpty())
        <hr>
        <h6 class="font-weight-bold">ประวัติการติดตาม</h6>
        <table class="table table-sm">
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
</div>
@endsection
