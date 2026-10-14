@extends(auth()->user()->isStudent() ? 'layouts.student' : 'layouts.teacher')
@section('title', 'แก้ไขข้อมูลผู้สมัครสอบ')
@section('page-title', 'แก้ไขข้อมูลผู้สมัครสอบ')

@section('content')
<style>
/* ─── Modern Edit Form Design System ─── */
:root {
    --er-primary: #2563eb;
    --er-primary-hover: #1d4ed8;
    --er-primary-soft: #eff6ff;
    --er-success: #10b981;
    --er-warning: #f59e0b;
    --er-danger: #ef4444;
    --er-slate-50: #f8fafc;
    --er-slate-100: #f1f5f9;
    --er-slate-200: #e2e8f0;
    --er-slate-300: #cbd5e1;
    --er-slate-600: #475569;
    --er-slate-700: #334155;
    --er-slate-800: #1e293b;
    --er-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
}

.er-edit-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Header Banner */
.er-banner {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--er-slate-200);
    box-shadow: var(--er-shadow);
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.er-banner-title {
    display: flex;
    align-items: center;
    gap: 14px;
}

.er-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: var(--er-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

.er-banner-title h5 {
    margin: 0;
    font-weight: 700;
    color: var(--er-slate-800);
    font-size: 1.2rem;
    line-height: 1.3;
}

.er-banner-title p {
    margin: 3px 0 0 0;
    font-size: 0.875rem;
    color: #64748b;
}

/* Section Cards */
.er-section-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--er-slate-200);
    box-shadow: var(--er-shadow);
    margin-bottom: 24px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.er-section-header {
    background: #f8fafc;
    padding: 14px 22px;
    border-bottom: 1.5px solid var(--er-slate-200);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--er-slate-800);
}

.er-section-header.blue {
    border-left: 4px solid var(--er-primary);
}

.er-section-header.emerald {
    border-left: 4px solid var(--er-success);
}

.er-section-header.purple {
    border-left: 4px solid #8b5cf6;
}

.er-section-header.amber {
    border-left: 4px solid var(--er-warning);
}

.er-section-body {
    padding: 22px;
}

/* Form Controls */
.er-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--er-slate-700);
    margin-bottom: 6px;
}

.er-label .req {
    color: var(--er-danger);
    margin-left: 2px;
}

.er-input {
    width: 100%;
    padding: 9px 13px;
    font-size: 0.925rem;
    color: var(--er-slate-800);
    background-color: #ffffff;
    border: 1.5px solid var(--er-slate-300);
    border-radius: 8px;
    transition: all 0.2s ease;
    outline: none;
}

.er-input:focus {
    border-color: var(--er-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    background-color: #ffffff;
}

.er-input::placeholder {
    color: #94a3b8;
    font-size: 0.875rem;
}

select.er-input {
    cursor: pointer;
}

/* Action Buttons */
.btn-er-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 10px;
    padding: 10px 24px;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.btn-er-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
}

.btn-er-ghost {
    background: #ffffff;
    color: var(--er-slate-700) !important;
    border: 1.5px solid var(--er-slate-300);
    border-radius: 10px;
    padding: 9px 18px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
}

.btn-er-ghost:hover {
    background: var(--er-slate-50);
    border-color: var(--er-slate-600);
    color: var(--er-slate-800) !important;
}

.er-footer-bar {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--er-slate-200);
    box-shadow: var(--er-shadow);
    padding: 18px 24px;
    margin-top: 10px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.helper-text {
    font-size: 0.775rem;
    color: #64748b;
    margin-top: 4px;
}
</style>

<div class="er-edit-container">
    {{-- Header Banner --}}
    <div class="er-banner">
        <div class="er-banner-title">
            <div class="er-icon-box">
                <i class="fas fa-user-edit"></i>
            </div>
            <div>
                <h5>แก้ไขข้อมูลผู้สมัคร: <span class="text-primary">{{ $registration->title }}{{ $registration->first_name }} {{ $registration->last_name }}</span></h5>
                <p>
                    <i class="fas fa-id-card-alt mr-1"></i> ปชช. {{ $registration->national_id }}
                    @if(optional($registration->examSession)->academic_year)
                        · <i class="far fa-calendar-alt mr-1"></i> รอบสอบปีการศึกษา {{ $registration->examSession->academic_year }}
                    @endif
                    @if($registration->reference_code)
                        · <i class="fas fa-hashtag mr-1"></i> รหัส {{ $registration->reference_code }}
                    @endif
                </p>
            </div>
        </div>
        <div class="d-flex align-items-center" style="gap: 10px;">
            <a href="{{ route('exam_registration.show', $registration->id) }}" class="btn-er-ghost" title="ดูรายละเอียดผู้สมัคร">
                <i class="fas fa-eye text-primary"></i> ดูรายละเอียด
            </a>
            <a href="{{ auth()->user()->isStudent() ? route('exam_registration.index') : route('exam_registration.list') }}" class="btn-er-ghost" title="กลับ">
                <i class="fas fa-arrow-left"></i> {{ auth()->user()->isStudent() ? 'กลับหน้าสมัครสอบ' : 'กลับสู่รายชื่อ' }}
            </a>
        </div>
    </div>

    {{-- Main Edit Form --}}
    <form action="{{ route('exam_registration.update', $registration->id) }}" method="POST" id="editRegistrationForm">
        @csrf
        @method('PUT')

        {{-- Section 1: ข้อมูลส่วนตัว --}}
        <div class="er-section-card">
            <div class="er-section-header blue">
                <i class="fas fa-user text-primary"></i> ๑. ข้อมูลส่วนตัวผู้สมัคร
            </div>
            <div class="er-section-body">
                @php
                    $stdTitles = ['เด็กชาย', 'เด็กหญิง', 'นาย', 'นางสาว', 'นาง', 'สามเณร', 'พระ', 'พระภิกษุ', 'พระครู', 'พระมหา', 'แม่ชี'];
                    $currentTitle = old('title', $registration->title);
                    $isOtherTitle = !in_array($currentTitle, $stdTitles) && !empty($currentTitle);
                @endphp
                <div class="form-row">
                    <div class="form-group col-md-3 col-sm-6">
                        <label class="er-label">คำนำหน้าชื่อ <span class="req">*</span></label>
                        <select id="titleSelect" class="er-input" onchange="handleTitleChange(this)">
                            <option value="เด็กชาย" {{ $currentTitle == 'เด็กชาย' ? 'selected' : '' }}>เด็กชาย (ด.ช.)</option>
                            <option value="เด็กหญิง" {{ $currentTitle == 'เด็กหญิง' ? 'selected' : '' }}>เด็กหญิง (ด.ญ.)</option>
                            <option value="นาย" {{ $currentTitle == 'นาย' ? 'selected' : '' }}>นาย</option>
                            <option value="นางสาว" {{ $currentTitle == 'นางสาว' ? 'selected' : '' }}>นางสาว (น.ส.)</option>
                            <option value="นาง" {{ $currentTitle == 'นาง' ? 'selected' : '' }}>นาง</option>
                            <option value="สามเณร" {{ $currentTitle == 'สามเณร' ? 'selected' : '' }}>สามเณร</option>
                            <option value="พระ" {{ in_array($currentTitle, ['พระ', 'พระภิกษุ']) ? 'selected' : '' }}>พระ / พระภิกษุ</option>
                            <option value="พระครู" {{ $currentTitle == 'พระครู' ? 'selected' : '' }}>พระครู</option>
                            <option value="พระมหา" {{ $currentTitle == 'พระมหา' ? 'selected' : '' }}>พระมหา</option>
                            <option value="แม่ชี" {{ $currentTitle == 'แม่ชี' ? 'selected' : '' }}>แม่ชี</option>
                            <option value="_other" {{ $isOtherTitle ? 'selected' : '' }}>✏️ อื่นๆ (ระบุยศ / ตำแหน่ง)</option>
                        </select>
                        <div id="otherTitleWrapper" class="mt-2 {{ $isOtherTitle ? '' : 'd-none' }}">
                            <input type="text" id="titleOtherInput" class="er-input" 
                                   value="{{ $isOtherTitle ? $currentTitle : '' }}" 
                                   placeholder="ระบุยศ/ตำแหน่ง เช่น ร.ต.อ., ผศ.ดร.">
                        </div>
                        <input type="hidden" name="title" id="titleFinalInput" value="{{ $currentTitle }}" required>
                    </div>

                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label">ชื่อจริง <span class="req">*</span></label>
                        <input type="text" name="first_name" class="er-input" value="{{ old('first_name', $registration->first_name) }}" placeholder="ชื่อจริง (ไม่ต้องใส่คำนำหน้า)" required>
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label class="er-label">นามสกุล <span class="req">*</span></label>
                        <input type="text" name="last_name" class="er-input" value="{{ old('last_name', $registration->last_name) }}" placeholder="นามสกุล" required>
                    </div>

                    <div class="form-group col-md-3 col-sm-6">
                        <label class="er-label"><i class="far fa-id-card text-muted mr-1"></i> เลข ปชช. (13 หลัก) <span class="req">*</span></label>
                        <input type="text" name="national_id" class="er-input" value="{{ old('national_id', $registration->national_id) }}" pattern="[0-9]{13}" maxlength="13" placeholder="ตัวเลข 13 หลัก" required>
                    </div>
                    <div class="form-group col-md-3 col-sm-6">
                        <label class="er-label"><i class="far fa-calendar-alt text-muted mr-1"></i> วันเกิด <span class="req">*</span></label>
                        <input type="date" name="birth_date" class="er-input" value="{{ old('birth_date', $registration->birth_date ? $registration->birth_date->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="form-group col-md-3 col-sm-6">
                        <label class="er-label"><i class="fas fa-phone-alt text-muted mr-1"></i> เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" class="er-input" value="{{ old('phone', $registration->phone) }}" placeholder="08xxxxxxxx">
                    </div>
                    <div class="form-group col-md-3 col-sm-6">
                        <label class="er-label"><i class="fab fa-line text-success mr-1"></i> LINE ID</label>
                        <input type="text" name="line_id" class="er-input" value="{{ old('line_id', $registration->line_id) }}" placeholder="ID ไลน์">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: ข้อมูลที่อยู่ 77 จังหวัด & สังกัด --}}
        <div class="er-section-card">
            <div class="er-section-header emerald">
                <i class="fas fa-map-marked-alt text-success"></i> ๒. ข้อมูลที่อยู่ (ค้นหาอัตโนมัติ ๗๗ จังหวัด) & สังกัด
            </div>
            <div class="er-section-body">
                <div class="form-row">
                    <div class="form-group col-md-4 col-sm-12">
                        <label class="er-label">บ้านเลขที่ / ถนน / ซอย</label>
                        <input type="text" name="house_number" class="er-input" value="{{ old('house_number', $registration->house_number) }}" placeholder="เช่น 123/45 หมู่ 6 ถ.สุขุมวิท">
                    </div>

                    {{-- จังหวัด (77 จังหวัด) --}}
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label"><i class="fas fa-city text-primary mr-1"></i> จังหวัด (77 จังหวัด)</label>
                        <input type="text" name="province" id="addr_province" class="er-input" list="dl_province" 
                               value="{{ old('province', $registration->province) }}" 
                               placeholder="พิมพ์หรือเลือกจังหวัด..." autocomplete="off">
                        <datalist id="dl_province"></datalist>
                        <div class="helper-text">เลือกจังหวัดเพื่อกรองอำเภออัตโนมัติ</div>
                    </div>

                    {{-- อำเภอ/เขต --}}
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label"><i class="fas fa-landmark text-primary mr-1"></i> อำเภอ / เขต</label>
                        <input type="text" name="district" id="addr_district" class="er-input" list="dl_district" 
                               value="{{ old('district', $registration->district) }}" 
                               placeholder="พิมพ์หรือเลือกอำเภอ/เขต..." autocomplete="off">
                        <datalist id="dl_district"></datalist>
                        <div class="helper-text">กรองตามจังหวัดที่เลือก</div>
                    </div>

                    {{-- ตำบล/แขวง --}}
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label"><i class="fas fa-map-pin text-primary mr-1"></i> ตำบล / แขวง</label>
                        <input type="text" name="sub_district" id="addr_sub_district" class="er-input" list="dl_sub_district" 
                               value="{{ old('sub_district', $registration->sub_district) }}" 
                               placeholder="พิมพ์หรือเลือกตำบล/แขวง..." autocomplete="off">
                        <datalist id="dl_sub_district"></datalist>
                        <div class="helper-text">เลือกเพื่อดึงรหัสไปรษณีย์อัตโนมัติ</div>
                    </div>

                    {{-- รหัสไปรษณีย์ --}}
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label"><i class="fas fa-mail-bulk text-primary mr-1"></i> รหัสไปรษณีย์</label>
                        <input type="text" name="postal_code" id="addr_postal_code" class="er-input" 
                               value="{{ old('postal_code', $registration->postal_code) }}" 
                               placeholder="5 หลัก" maxlength="5">
                        <div class="helper-text">เติมอัตโนมัติเมื่อเลือกตำบล</div>
                    </div>

                    {{-- สังกัด / โรงเรียน --}}
                    <div class="form-group col-md-4 col-sm-12">
                        <label class="er-label"><i class="fas fa-building text-muted mr-1"></i> โรงเรียน / หน่วยงาน / สังกัด</label>
                        <input type="text" name="office_name" class="er-input" value="{{ old('office_name', $registration->office_name) }}" placeholder="ชื่อโรงเรียนหรือสังกัด">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: วุฒิการศึกษา & ประวัติการสอบเดิม --}}
        <div class="er-section-card">
            <div class="er-section-header purple">
                <i class="fas fa-graduation-cap text-primary"></i> ๓. วุฒิการศึกษา & ประวัติการสอบเดิม
            </div>
            <div class="er-section-body">
                <div class="form-row">
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label">ระดับการศึกษา</label>
                        <select name="education_level" class="er-input">
                            <option value="">-- เลือกระดับการศึกษา --</option>
                            @php
                                $edLevels = ['ประถมศึกษา', 'มัธยมศึกษาตอนต้น', 'มัธยมศึกษาตอนปลาย', 'ปวช./ปวส.', 'ปริญญาตรี', 'ปริญญาโท/เอก', 'ประชาชนทั่วไป'];
                                $currEdLevel = old('education_level', $registration->education_level);
                            @endphp
                            @foreach($edLevels as $lvl)
                                <option value="{{ $lvl }}" {{ $currEdLevel === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label">ชั้นเรียนปัจจุบัน <span class="req">*</span></label>
                        <input type="text" name="education_class" class="er-input" value="{{ old('education_class', $registration->education_class) }}" placeholder="เช่น ป.๖, ม.๑, ม.๓" required>
                    </div>
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label">เลขประกาศนียบัตรเดิมที่ผ่านแล้ว</label>
                        <input type="text" name="previous_certificate_no" class="er-input" value="{{ old('previous_certificate_no', $registration->previous_certificate_no) }}" placeholder="ระบุเลขที่เดิม (ถ้ามี)">
                    </div>
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label">จังหวัดที่สอบผ่านเดิม</label>
                        <input type="text" name="previous_province" class="er-input" list="dl_province" value="{{ old('previous_province', $registration->previous_province) }}" placeholder="เลือกหรือพิมพ์จังหวัด">
                    </div>
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label">พ.ศ. ที่สอบผ่านเดิม</label>
                        <input type="text" name="previous_year" class="er-input" value="{{ old('previous_year', $registration->previous_year) }}" placeholder="เช่น 2568" maxlength="4">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 4: ข้อมูลการสมัครสอบ --}}
        <div class="er-section-card">
            <div class="er-section-header amber">
                <i class="fas fa-file-signature text-warning"></i> ๔. ข้อมูลการสมัครสอบ & สนามสอบ
            </div>
            <div class="er-section-body">
                <div class="form-row">
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label"><i class="fas fa-award text-muted mr-1"></i> ชั้นที่สมัครสอบ <span class="req">*</span></label>
                        <select name="exam_tier" class="er-input" required>
                            <option value="tree" {{ old('exam_tier', $registration->exam_tier) === 'tree' ? 'selected' : '' }}>ธรรมศึกษาชั้นตรี</option>
                            <option value="tho" {{ old('exam_tier', $registration->exam_tier) === 'tho' ? 'selected' : '' }}>ธรรมศึกษาชั้นโท</option>
                            <option value="ek" {{ old('exam_tier', $registration->exam_tier) === 'ek' ? 'selected' : '' }}>ธรรมศึกษาชั้นเอก</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label"><i class="fas fa-map-pin text-muted mr-1"></i> สนามสอบ</label>
                        <select name="exam_center_id" class="er-input">
                            <option value="">-- ยังไม่ระบุสนามสอบ --</option>
                            @foreach($centers as $center)
                                <option value="{{ $center->id }}" {{ old('exam_center_id', $registration->exam_center_id) == $center->id ? 'selected' : '' }}>
                                    {{ $center->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label"><i class="fas fa-check-circle text-muted mr-1"></i> สถานะการสมัคร <span class="req">*</span></label>
                        @php
                            $curStatus = old('status', $registration->status);
                            if ($curStatus === 'registered' || $curStatus === 'checked') $curStatus = 'pending';
                            if ($curStatus === 'eligible') $curStatus = 'approved';
                        @endphp
                        @if(auth()->user()->isStudent())
                            <div class="mt-2">
                                @php
                                    $sLabels = ['pending'=>'⏳ รออนุมัติ','approved'=>'✅ อนุมัติแล้ว','rejected'=>'❌ ไม่อนุมัติ','incomplete'=>'📄 ตามเอกสาร','cancelled'=>'🚫 ยกเลิก'];
                                    $sColors = ['pending'=>'badge-warning','approved'=>'badge-success','rejected'=>'badge-danger','incomplete'=>'badge-info','cancelled'=>'badge-secondary'];
                                @endphp
                                <span class="badge {{ $sColors[$curStatus] ?? 'badge-secondary' }} px-3 py-2" style="font-size: 0.9rem;">
                                    {{ $sLabels[$curStatus] ?? $curStatus }}
                                </span>
                                <div class="helper-text">สถานะการสมัครตรวจสอบโดยเจ้าหน้าที่</div>
                            </div>
                        @else
                            <select name="status" class="er-input" required>
                                <option value="pending" {{ $curStatus === 'pending' ? 'selected' : '' }}>⏳ รออนุมัติ</option>
                                <option value="approved" {{ $curStatus === 'approved' ? 'selected' : '' }}>✅ อนุมัติแล้ว</option>
                                <option value="incomplete" {{ $curStatus === 'incomplete' ? 'selected' : '' }}>📄 ตามเอกสาร</option>
                                <option value="rejected" {{ $curStatus === 'rejected' ? 'selected' : '' }}>❌ ไม่อนุมัติ</option>
                                <option value="cancelled" {{ $curStatus === 'cancelled' ? 'selected' : '' }}>🚫 ยกเลิก</option>
                            </select>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label class="er-label">หมายเหตุทั่วไป</label>
                        <textarea name="remarks" class="er-input" rows="2" placeholder="ข้อความหมายเหตุเพิ่มเติม...">{{ old('remarks', $registration->remarks) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 5: การติดตาม & บันทึกการติดต่อ (สำหรับครูและผู้ดูแลระบบ) --}}
        @if(!auth()->user()->isStudent())
        <div class="er-section-card">
            <div class="er-section-header blue">
                <i class="fas fa-headset text-primary"></i> ๕. การติดตาม & บันทึกผลการติดต่อ
            </div>
            <div class="er-section-body">
                <div class="form-row">
                    <div class="form-group col-md-4 col-sm-6">
                        <label class="er-label">สถานะการติดตามล่าสุด</label>
                        @php
                            $curFu = old('follow_up_status', $registration->follow_up_status);
                        @endphp
                        <select name="follow_up_status" class="er-input">
                            <option value="">-- ยังไม่ติดตาม --</option>
                            <option value="โทร" {{ $curFu === 'โทร' ? 'selected' : '' }}>📱 โทรศัพท์</option>
                            <option value="ไลน์" {{ $curFu === 'ไลน์' ? 'selected' : '' }}>💬 ไลน์ (LINE)</option>
                            <option value="อีเมล" {{ $curFu === 'อีเมล' ? 'selected' : '' }}>✉️ อีเมล</option>
                            <option value="ติดต่อไม่ได้" {{ $curFu === 'ติดต่อไม่ได้' ? 'selected' : '' }}>❌ ติดต่อไม่ได้</option>
                            <option value="ไม่มีผู้รับสาย" {{ $curFu === 'ไม่มีผู้รับสาย' ? 'selected' : '' }}>📵 ไม่มีผู้รับสาย</option>
                            <option value="เรียบร้อยแล้ว" {{ $curFu === 'เรียบร้อยแล้ว' ? 'selected' : '' }}>✅ ติดต่อเรียบร้อยแล้ว</option>
                        </select>
                    </div>
                    <div class="form-group col-md-8 col-sm-6">
                        <label class="er-label">บันทึกการติดตาม / นัดหมายเอกสาร</label>
                        <input type="text" name="follow_up_note" class="er-input" value="{{ old('follow_up_note', $registration->follow_up_note) }}" placeholder="เช่น โทรแจ้งให้นำสำเนาบัตรประชาชนมาส่งเพิ่ม วันที่ 15 นี้">
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Footer Actions --}}
        <div class="er-footer-bar">
            <div class="text-muted small">
                <i class="far fa-clock mr-1 text-primary"></i> แก้ไขล่าสุด: {{ $registration->updated_at ? $registration->updated_at->format('d/m/Y H:i น.') : '-' }}
            </div>
            <div class="d-flex align-items-center" style="gap: 12px;">
                <a href="{{ route('exam_registration.list') }}" class="btn-er-ghost">
                    <i class="fas fa-times"></i> ยกเลิก
                </a>
                <button type="submit" class="btn-er-primary">
                    <i class="fas fa-save"></i> บันทึกข้อมูลผู้สมัคร
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
// ── 1. Title Selection & Other (Rank/Position) Handler ──
function handleTitleChange(selectEl) {
    var otherWrapper = document.getElementById('otherTitleWrapper');
    var otherInput = document.getElementById('titleOtherInput');
    var finalInput = document.getElementById('titleFinalInput');

    if (selectEl.value === '_other') {
        otherWrapper.classList.remove('d-none');
        otherInput.focus();
        finalInput.value = otherInput.value.trim();
    } else {
        otherWrapper.classList.add('d-none');
        finalInput.value = selectEl.value;
    }
}

document.getElementById('titleOtherInput').addEventListener('input', function () {
    var selectEl = document.getElementById('titleSelect');
    var finalInput = document.getElementById('titleFinalInput');
    if (selectEl.value === '_other') {
        finalInput.value = this.value.trim();
    }
});

// Form submit validation for title
document.getElementById('editRegistrationForm').addEventListener('submit', function (e) {
    var selectEl = document.getElementById('titleSelect');
    var otherInput = document.getElementById('titleOtherInput');
    var finalInput = document.getElementById('titleFinalInput');

    if (selectEl.value === '_other') {
        if (!otherInput.value.trim()) {
            e.preventDefault();
            alert('กรุณาระบุคำนำหน้าชื่อ / ยศ / ตำแหน่ง ในช่องข้อความ');
            otherInput.focus();
            return false;
        }
        finalInput.value = otherInput.value.trim();
    } else {
        finalInput.value = selectEl.value;
    }
});

// ── 2. 77 Thai Provinces & Address Auto-Complete Cascading ──
(function () {
    var apiEndpoint = '{{ route("api.thai_addresses") }}';
    var provInput = document.getElementById('addr_province');
    var distInput = document.getElementById('addr_district');
    var subDistInput = document.getElementById('addr_sub_district');
    var postalInput = document.getElementById('addr_postal_code');

    var dlProv = document.getElementById('dl_province');
    var dlDist = document.getElementById('dl_district');
    var dlSubDist = document.getElementById('dl_sub_district');

    function populateDatalist(datalistEl, items) {
        if (!datalistEl) return;
        datalistEl.innerHTML = '';
        if (Array.isArray(items)) {
            items.forEach(function (item) {
                var opt = document.createElement('option');
                opt.value = item;
                datalistEl.appendChild(opt);
            });
        }
    }

    // Load 77 Provinces on startup
    fetch(apiEndpoint + '?type=provinces')
        .then(function (res) { return res.json(); })
        .then(function (provinces) {
            populateDatalist(dlProv, provinces);
        })
        .catch(function (err) {
            console.warn('Address API error:', err);
        });

    // When Province changes -> load Districts
    provInput.addEventListener('change', function () {
        var prov = this.value.trim();
        if (!prov) {
            populateDatalist(dlDist, []);
            populateDatalist(dlSubDist, []);
            return;
        }
        fetch(apiEndpoint + '?type=districts&province=' + encodeURIComponent(prov))
            .then(function (res) { return res.json(); })
            .then(function (districts) {
                populateDatalist(dlDist, districts);
            });
    });

    // If province already filled on page load -> pre-populate districts
    if (provInput.value.trim()) {
        fetch(apiEndpoint + '?type=districts&province=' + encodeURIComponent(provInput.value.trim()))
            .then(function (res) { return res.json(); })
            .then(function (districts) {
                populateDatalist(dlDist, districts);
            });
    }

    // When District changes -> load Sub-Districts
    distInput.addEventListener('change', function () {
        var prov = provInput.value.trim();
        var dist = this.value.trim();
        if (!prov || !dist) {
            populateDatalist(dlSubDist, []);
            return;
        }
        fetch(apiEndpoint + '?type=sub_districts&province=' + encodeURIComponent(prov) + '&district=' + encodeURIComponent(dist))
            .then(function (res) { return res.json(); })
            .then(function (subDistricts) {
                populateDatalist(dlSubDist, subDistricts);
            });
    });

    // If district already filled on page load -> pre-populate subdistricts
    if (provInput.value.trim() && distInput.value.trim()) {
        fetch(apiEndpoint + '?type=sub_districts&province=' + encodeURIComponent(provInput.value.trim()) + '&district=' + encodeURIComponent(distInput.value.trim()))
            .then(function (res) { return res.json(); })
            .then(function (subDistricts) {
                populateDatalist(dlSubDist, subDistricts);
            });
    }

    // When Sub-District changes -> auto-fetch Zipcode
    subDistInput.addEventListener('change', function () {
        var prov = provInput.value.trim();
        var dist = distInput.value.trim();
        var subDist = this.value.trim();
        if (!prov || !dist || !subDist) return;

        fetch(apiEndpoint + '?type=postal_code&province=' + encodeURIComponent(prov) + '&district=' + encodeURIComponent(dist) + '&sub_district=' + encodeURIComponent(subDist))
            .then(function (res) { return res.json(); })
            .then(function (zipcodes) {
                if (Array.isArray(zipcodes) && zipcodes.length > 0) {
                    postalInput.value = zipcodes[0];
                }
            });
    });
})();
</script>
@endsection
