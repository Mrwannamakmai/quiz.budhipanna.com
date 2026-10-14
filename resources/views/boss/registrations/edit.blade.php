@extends('layouts.boss')
@section('title','แก้ไขข้อมูลผู้สมัครสอบ')
@section('page-title','แก้ไขผู้สมัครสอบ')

@section('content')
<div class="card mb-4" style="max-width: 800px; margin: auto;">
    <div class="card-header">
        <i class="fas fa-edit mr-1"></i> แก้ไขข้อมูล: <strong>{{ $registration->first_name }} {{ $registration->last_name }}</strong>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('boss.registrations.update', $registration) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>คำนำหน้า <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $registration->title) }}" required>
                    @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>ชื่อ <span class="text-danger">*</span></label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $registration->first_name) }}" required>
                    @error('first_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-5 form-group">
                    <label>นามสกุล <span class="text-danger">*</span></label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $registration->last_name) }}" required>
                    @error('last_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>เลขประจำตัวประชาชน <span class="text-danger">*</span></label>
                    <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id', $registration->national_id) }}" maxlength="13" required>
                    @error('national_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $registration->phone) }}">
                    @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>ชั้นที่สมัครสอบ <span class="text-danger">*</span></label>
                    <select name="exam_tier" class="form-control @error('exam_tier') is-invalid @enderror" required>
                        @foreach(\App\ExamRegistration::TIER_LABELS as $val => $label)
                            <option value="{{ $val }}" {{ old('exam_tier', $registration->exam_tier) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('exam_tier')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>ระดับการศึกษาปัจจุบัน</label>
                    <input type="text" name="education_level" class="form-control @error('education_level') is-invalid @enderror" value="{{ old('education_level', $registration->education_level) }}">
                    @error('education_level')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-group">
                <label>สำนักเรียน</label>
                <input type="text" name="office_name" class="form-control @error('office_name') is-invalid @enderror" value="{{ old('office_name', $registration->office_name) }}">
                @error('office_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>สถานะการสมัคร <span class="text-danger">*</span></label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                        @foreach(\App\ExamRegistration::STATUSES as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $registration->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-6 form-group">
                    <label>หมายเหตุ</label>
                    <input type="text" name="remarks" class="form-control @error('remarks') is-invalid @enderror" value="{{ old('remarks', $registration->remarks) }}">
                    @error('remarks')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="mt-4 border-top pt-3 text-right">
                <a href="{{ route('boss.registrations.index') }}" class="btn btn-secondary mr-2">ยกเลิก</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>
@endsection
