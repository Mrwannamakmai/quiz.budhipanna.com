@extends('layouts.admin')
@section('title', $session->exists ? 'แก้ไขรอบสอบ' : 'สร้างรอบสอบ')
@section('page-title', $session->exists ? 'แก้ไขรอบสอบ' : 'สร้างรอบสอบ')

@section('content')
<div class="card" style="max-width:900px;">
    <div class="card-body">
        <form method="POST" action="{{ $session->exists ? route('admin.exam_sessions.update', $session) : route('admin.exam_sessions.store') }}">
            @csrf
            @if($session->exists) @method('PUT') @endif
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="academic_year">ปีการศึกษา / ชื่อรอบสอบ</label>
                    <input id="academic_year" name="academic_year" class="form-control" maxlength="20" required value="{{ old('academic_year', $session->academic_year) }}" placeholder="เช่น 2569">
                </div>
                <div class="form-group col-md-3">
                    <label for="exam_date">วันสอบ</label>
                    <input id="exam_date" name="exam_date" type="date" class="form-control" value="{{ old('exam_date', $session->exam_date ? $session->exam_date->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="status">สถานะรอบสอบ</label>
                    <select id="status" name="status" class="form-control" required>
                        @foreach(['draft' => 'ฉบับร่าง', 'open' => 'เปิดรับสมัคร', 'closed' => 'ปิดรับสมัคร', 'completed' => 'เสร็จสิ้น'] as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $session->status ?: 'draft') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="announcement">ประกาศรอบสอบ</label>
                <textarea id="announcement" name="announcement" rows="4" maxlength="10000" class="form-control">{{ old('announcement', $session->announcement) }}</textarea>
            </div>
            <div class="form-group">
                <label for="details">รายละเอียดรอบสอบ</label>
                <textarea id="details" name="details" rows="4" maxlength="10000" class="form-control">{{ old('details', $session->details) }}</textarea>
            </div>
            <div class="form-group">
                <label for="schedule">กำหนดการ</label>
                <textarea id="schedule" name="schedule" rows="6" maxlength="10000" class="form-control" placeholder="ระบุเวลาและกิจกรรมแต่ละช่วง">{{ old('schedule', $session->schedule) }}</textarea>
            </div>
            <div class="form-group">
                <label for="notebooklm_url">ลิงก์ที่แชร์จาก NotebookLM</label>
                <input id="notebooklm_url" name="notebooklm_url" type="url" maxlength="1000" class="form-control"
                       value="{{ old('notebooklm_url', $session->notebooklm_url) }}"
                       placeholder="https://notebooklm.google.com/notebook/...">
                <small class="form-text text-muted">ใส่ลิงก์แชร์จาก NotebookLM ที่ต้องการแสดงในรายละเอียดรอบสอบ</small>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" id="is_published" name="is_published" value="1" class="form-check-input" {{ old('is_published', $session->is_published) ? 'checked' : '' }}>
                <label for="is_published" class="form-check-label">เผยแพร่ประกาศให้ผู้สมัครเห็น</label>
            </div>

            <hr>
            <h6 class="font-weight-bold"><i class="fas fa-clock mr-1"></i>ช่วงเวลารับสมัครสาธารณะ</h6>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="registration_mode">โหมดรับสมัคร</label>
                    <select id="registration_mode" name="registration_mode" class="form-control">
                        @foreach(['auto' => 'อัตโนมัติ (ตามวันที่)', 'force_open' => 'เปิดเสมอ', 'force_closed' => 'ปิดเสมอ'] as $value => $label)
                            <option value="{{ $value }}" {{ old('registration_mode', $session->registration_mode ?: 'auto') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="registration_start_date">วันเริ่มรับสมัคร</label>
                    <input id="registration_start_date" name="registration_start_date" type="date" class="form-control" value="{{ old('registration_start_date', $session->registration_start_date ? $session->registration_start_date->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="registration_end_date">วันสิ้นสุดรับสมัคร</label>
                    <input id="registration_end_date" name="registration_end_date" type="date" class="form-control" value="{{ old('registration_end_date', $session->registration_end_date ? $session->registration_end_date->format('Y-m-d') : '') }}">
                </div>
            </div>
            <small class="form-text text-muted mb-3">ตั้งค่าช่วงเวลาสำหรับหน้าสมัครสอบสาธารณะ (ไม่ต้อง login) — "อัตโนมัติ" จะเปิด/ปิดตามวันที่กำหนด</small>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>บันทึก</button>
            <a href="{{ route('admin.exam_sessions.index') }}" class="btn btn-light">ยกเลิก</a>
        </form>
    </div>
</div>
@endsection
