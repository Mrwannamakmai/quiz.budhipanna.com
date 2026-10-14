@extends(auth()->user()->isStudent() ? 'layouts.student' : 'layouts.teacher')
@section('title', 'ลงทะเบียนสอบธรรมศึกษา')
@section('page-title', 'ลงทะเบียนสอบธรรมศึกษา')

@section('content')
<style>
/* ─── Modern Theme Variables & Base Styling ─── */
:root {
    --er-primary: #2563eb;
    --er-primary-hover: #1d4ed8;
    --er-primary-soft: #eff6ff;
    --er-success: #10b981;
    --er-success-soft: #ecfdf5;
    --er-warning: #f59e0b;
    --er-warning-soft: #fffbeb;
    --er-danger: #ef4444;
    --er-danger-soft: #fef2f2;
    --er-dark: #0f172a;
    --er-gray-50: #f8fafc;
    --er-gray-100: #f1f5f9;
    --er-gray-200: #e2e8f0;
    --er-gray-400: #94a3b8;
    --er-gray-600: #475569;
    --er-gray-700: #334155;
    --er-gray-900: #1e293b;
    --er-card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
    --er-hover-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.08), 0 4px 10px -2px rgba(0, 0, 0, 0.04);
}

.er-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Card Container */
.er-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: var(--er-card-shadow);
    margin-bottom: 24px;
    overflow: hidden;
    transition: all 0.25s ease;
}

.er-card-header {
    padding: 20px 24px;
    background: #ffffff;
    border-bottom: 1px solid var(--er-gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.er-card-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.er-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: var(--er-primary);
    flex-shrink: 0;
}

.er-icon-box.green {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    color: var(--er-success);
}

.er-card-title h5 {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--er-gray-900);
    margin: 0 0 2px 0;
    line-height: 1.3;
}

.er-card-title p {
    font-size: 0.875rem;
    color: var(--er-gray-400);
    margin: 0;
}

.er-card-body {
    padding: 24px;
}

/* Session Selection Banner */
.er-session-picker {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    padding: 16px 20px;
    border: 1px solid var(--er-gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}

.er-select-custom {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--er-gray-900);
    background-color: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 9px 16px;
    min-width: 320px;
    cursor: pointer;
    transition: all 0.2s;
    outline: none;
}

.er-select-custom:focus {
    border-color: var(--er-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

/* Status Badges */
.er-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.825rem;
    font-weight: 600;
}

.er-status-open {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.er-status-open .dot {
    width: 8px;
    height: 8px;
    background: #22c55e;
    border-radius: 50%;
    animation: pulse-dot 2s infinite;
}

.er-status-closed {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.er-status-draft {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

@keyframes pulse-dot {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}

/* Session Info Grid */
.er-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
}

.er-info-box {
    background: #ffffff;
    border: 1px solid var(--er-gray-200);
    border-radius: 12px;
    padding: 16px 18px;
    position: relative;
    border-left: 4px solid var(--er-primary);
    transition: all 0.2s;
}

.er-info-box:hover {
    border-color: #cbd5e1;
    border-left-color: var(--er-primary);
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.er-info-box.schedule {
    border-left-color: var(--er-warning);
}

.er-info-box.announcement {
    border-left-color: #8b5cf6;
}

.er-info-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--er-gray-700);
    margin-bottom: 8px;
}

.er-info-content {
    font-size: 0.925rem;
    color: var(--er-gray-600);
    line-height: 1.6;
    white-space: pre-line;
}

/* NotebookLM Box */
.er-ai-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #e0e7ff 100%);
    border: 1px solid #c7d2fe;
    border-radius: 14px;
    padding: 18px;
    margin-top: 16px;
}

/* Modern Form Controls */
.er-form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--er-gray-700);
    margin-bottom: 6px;
}

.er-form-label .req {
    color: var(--er-danger);
    margin-left: 2px;
}

.er-input {
    width: 100%;
    padding: 9px 13px;
    font-size: 0.925rem;
    color: var(--er-gray-900);
    background-color: #ffffff;
    border: 1.5px solid #cbd5e1;
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
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 14px 10px;
    padding-right: 36px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

/* Registrant Row / Item Card */
.registrant-row {
    background: #fafafa;
    border: 1.5px solid var(--er-gray-200);
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 18px;
    position: relative;
    transition: all 0.25s ease;
}

.registrant-row:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 6px 16px rgba(0,0,0,0.04);
}

.registrant-row-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 14px;
    margin-bottom: 16px;
    border-bottom: 1px solid var(--er-gray-200);
}

.registrant-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--er-primary);
    background: var(--er-primary-soft);
    padding: 5px 14px;
    border-radius: 20px;
}

.btn-remove-row {
    background: transparent;
    color: var(--er-danger);
    border: 1px solid #fecaca;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 0.825rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-remove-row:hover {
    background: var(--er-danger-soft);
    border-color: var(--er-danger);
    color: #dc2626;
}

/* Action Buttons */
.btn-er-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 10px;
    padding: 10px 22px;
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
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
}

.btn-er-outline {
    background: #ffffff;
    color: var(--er-primary) !important;
    border: 1.5px solid #bfdbfe;
    border-radius: 10px;
    padding: 9px 18px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.btn-er-outline:hover {
    background: var(--er-primary-soft);
    border-color: var(--er-primary);
    transform: translateY(-1px);
}

.btn-er-info {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 10px;
    padding: 9px 18px;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2);
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
}

.btn-er-info:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(2, 132, 199, 0.3);
}

.er-footer-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--er-gray-200);
}

.er-counter-pill {
    background: var(--er-gray-100);
    color: var(--er-gray-700);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .er-card-header {
        padding: 16px;
    }
    .er-card-body {
        padding: 16px;
    }
    .er-select-custom {
        min-width: 100%;
    }
    .er-session-picker {
        flex-direction: column;
        align-items: stretch;
    }
    .registrant-row {
        padding: 14px;
    }
}
</style>

<div class="er-container">
    {{-- Card 1: Session Overview & Picker --}}
    <div class="er-card">
        <div class="er-card-header">
            <div class="er-card-title">
                <div class="er-icon-box">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h5>รอบสมัครสอบธรรมศึกษา</h5>
                    <p>ตรวจสอบข้อมูลรอบการเปิดรับสมัครและกำหนดการสอบ</p>
                </div>
            </div>
            @if($session)
                <div>
                    @if($session->status === 'open')
                        <span class="er-status-pill er-status-open">
                            <span class="dot"></span> กำลังเปิดรับสมัคร
                        </span>
                    @elseif($session->status === 'closed')
                        <span class="er-status-pill er-status-closed">
                            <i class="fas fa-lock"></i> ปิดรับสมัครแล้ว
                        </span>
                    @else
                        <span class="er-status-pill er-status-draft">
                            <i class="fas fa-clock"></i> {{ ['draft'=>'ฉบับร่าง','completed'=>'เสร็จสิ้น'][$session->status] ?? $session->status }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <div class="er-card-body">
            @if($sessions->isEmpty())
                <div class="alert alert-info d-flex align-items-center mb-0" style="border-radius: 12px;">
                    <i class="fas fa-info-circle fa-lg mr-3 text-info"></i>
                    <div>
                        <strong>ขณะนี้ยังไม่มีรอบสอบในระบบ</strong>
                        <div class="text-muted small">กรุณารอเจ้าหน้าที่เปิดรอบสมัครสอบ</div>
                    </div>
                </div>
            @else
                <div class="er-session-picker">
                    <form method="GET" action="{{ route('exam_registration.index') }}" class="d-flex align-items-center flex-wrap" style="gap: 12px; margin: 0; width: 100%;">
                        <div class="d-flex align-items-center flex-grow-1 flex-wrap" style="gap: 12px;">
                            <label for="session_id" class="m-0 font-weight-bold text-secondary" style="font-size: 0.9rem; white-space: nowrap;">
                                <i class="fas fa-filter text-primary mr-1"></i> เลือกรอบสอบ:
                            </label>
                            <select id="session_id" name="session_id" class="er-select-custom flex-grow-1" onchange="this.form.submit()">
                                @foreach($sessions as $examSession)
                                    <option value="{{ $examSession->id }}" {{ $session && $session->id === $examSession->id ? 'selected' : '' }}>
                                        ปีการศึกษา {{ $examSession->academic_year }} — [{{ ['draft'=>'ฉบับร่าง','open'=>'เปิดรับสมัคร','closed'=>'ปิดรับสมัคร','completed'=>'เสร็จสิ้น'][$examSession->status] ?? $examSession->status }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($session && $session->exam_date)
                            <div class="d-flex align-items-center text-primary font-weight-bold ml-auto" style="font-size: 0.95rem; background: #ffffff; padding: 7px 16px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                <i class="fas fa-calendar-day mr-2 text-danger"></i>
                                <span>วันสอบ: {{ $session->exam_date->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </form>
                </div>
            @endif

            @if ($session && ($session->announcement || $session->details || $session->schedule || $session->notebooklm_url))
                <div class="er-info-grid">
                    @if ($session->announcement)
                        <div class="er-info-box announcement">
                            <div class="er-info-label" style="color: #7c3aed;">
                                <i class="fas fa-bullhorn"></i> ข้อมูลรอบสอบ / ประกาศ
                            </div>
                            <div class="er-info-content">{{ $session->announcement }}</div>
                        </div>
                    @endif

                    @if ($session->details)
                        <div class="er-info-box">
                            <div class="er-info-label" style="color: var(--er-primary);">
                                <i class="fas fa-align-left"></i> รายละเอียดการสมัคร
                            </div>
                            <div class="er-info-content">{{ $session->details }}</div>
                        </div>
                    @endif

                    @if ($session->schedule)
                        <div class="er-info-box schedule">
                            <div class="er-info-label" style="color: #d97706;">
                                <i class="fas fa-clock"></i> กำหนดการสอบ
                            </div>
                            <div class="er-info-content">{{ $session->schedule }}</div>
                        </div>
                    @endif
                </div>

                @if ($session->notebooklm_url)
                    <div class="er-ai-box">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap" style="gap: 10px;">
                            <h6 class="font-weight-bold m-0 text-dark d-flex align-items-center">
                                <i class="fas fa-brain text-primary mr-2" style="font-size: 1.1rem;"></i> ข้อมูลสรุปอัจฉริยะ (NotebookLM)
                            </h6>
                            <a href="{{ $session->notebooklm_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 600;">
                                <i class="fas fa-external-link-alt mr-1"></i> เปิดดูในแท็บใหม่
                            </a>
                        </div>
                        <div class="embed-responsive embed-responsive-16by9" style="border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border: 1px solid #cbd5e1;">
                            <iframe class="embed-responsive-item" src="{{ $session->notebooklm_url }}"
                                    title="ข้อมูลรอบสอบจาก NotebookLM" allow="fullscreen" allowfullscreen></iframe>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Card 2: Registration Form --}}
    @if($session)
        @if($session->status === 'open')
            <div class="er-card">
                <div class="er-card-header">
                    <div class="er-card-title">
                        <div class="er-icon-box green">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h5>{{ auth()->user()->isStudent() ? 'ฟอร์มสมัครสอบธรรมศึกษา' : 'ลงทะเบียนผู้สมัครสอบ (แบบกลุ่ม)' }}</h5>
                            <p>
                                {{ auth()->user()->isStudent() ? 'กรอกข้อมูลส่วนตัวของท่านให้ถูกต้องครบถ้วนเพื่อลงทะเบียนสอบ' : 'กรอกข้อมูลผู้เรียนเพื่อลงทะเบียนสอบ สามารถเพิ่มได้สูงสุด 100 คนต่อครั้ง' }}
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                        <a href="{{ route('exam_registration.list') }}" class="btn-er-info">
                            <i class="fas fa-th-list"></i> ดูรายชื่อผู้สมัคร
                        </a>
                        @if (!auth()->user()->isStudent())
                            <button type="button" class="btn-er-outline" id="addRegistrant">
                                <i class="fas fa-plus"></i> เพิ่มผู้สมัครอีก 1 คน
                            </button>
                        @endif
                    </div>
                </div>

                <div class="er-card-body">
                    <form method="POST" action="{{ route('exam_registration.store') }}" id="registrationForm">
                        @csrf
                        <input type="hidden" name="exam_session_id" value="{{ $session->id }}">

                        <div id="registrants">
                            {{-- Row 1: First Registrant --}}
                            <div class="registrant-row" data-index="0">
                                <div class="registrant-row-header">
                                    <div class="registrant-badge">
                                        <i class="fas fa-user-graduate"></i>
                                        <span>ผู้ลงทะเบียนคนที่ <span class="row-number">1</span></span>
                                    </div>
                                    @if (!auth()->user()->isStudent())
                                        <button type="button" class="btn-remove-row d-none" onclick="removeRegistrantRow(this)">
                                            <i class="fas fa-trash-alt"></i> ลบคนนี้
                                        </button>
                                    @endif
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-2 col-sm-4">
                                        <label class="er-form-label">คำนำหน้า <span class="req">*</span></label>
                                        <input name="registrants[0][title]" class="er-input" placeholder="ด.ช. / ด.ญ. / นาย / น.ส." required>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-8">
                                        <label class="er-form-label">ชื่อ <span class="req">*</span></label>
                                        <input name="registrants[0][first_name]" class="er-input" placeholder="ชื่อจริง (ไม่ต้องใส่คำนำหน้า)" required>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label class="er-form-label">นามสกุล <span class="req">*</span></label>
                                        <input name="registrants[0][last_name]" class="er-input" placeholder="นามสกุล" required>
                                    </div>

                                    <div class="form-group col-md-3 col-sm-6">
                                        <label class="er-form-label"><i class="far fa-id-card text-muted mr-1"></i> เลขประจำตัวประชาชน <span class="req">*</span></label>
                                        <input name="registrants[0][national_id]" class="er-input national-id-input" inputmode="numeric" maxlength="13" pattern="[0-9]{13}" placeholder="เลข 13 หลัก (เฉพาะตัวเลข)" required>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6">
                                        <label class="er-form-label"><i class="far fa-calendar-alt text-muted mr-1"></i> วันเกิด <span class="req">*</span></label>
                                        <input type="date" name="registrants[0][birth_date]" class="er-input" required>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6">
                                        <label class="er-form-label"><i class="fas fa-graduation-cap text-muted mr-1"></i> การศึกษาปัจจุบัน <span class="req">*</span></label>
                                        <input name="registrants[0][education_class]" class="er-input" placeholder="เช่น ป.๖, ม.๑, ม.๓" required>
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6">
                                        <label class="er-form-label"><i class="fas fa-award text-muted mr-1"></i> ระดับชั้นที่สอบ <span class="req">*</span></label>
                                        <select name="registrants[0][exam_tier]" class="er-input" required>
                                            <option value="">-- เลือกระดับชั้น --</option>
                                            <option value="tree">ธรรมศึกษาชั้นตรี</option>
                                            <option value="tho">ธรรมศึกษาชั้นโท</option>
                                            <option value="ek">ธรรมศึกษาชั้นเอก</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4 col-sm-6">
                                        <label class="er-form-label">เลขประกาศนียบัตรเดิมที่ผ่านแล้ว</label>
                                        <input name="registrants[0][previous_certificate_no]" class="er-input" maxlength="100" placeholder="ระบุเลขที่เดิม (ถ้ามี)">
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label class="er-form-label"><i class="fas fa-map-marker-alt text-muted mr-1"></i> สนามสอบ</label>
                                        <select name="registrants[0][exam_center_id]" class="er-input">
                                            <option value="">ยังไม่ระบุสนามสอบ</option>
                                            @foreach($centers as $center)
                                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-12">
                                        <label class="er-form-label">หมายเหตุ</label>
                                        <input name="registrants[0][remarks]" class="er-input" placeholder="ข้อความเพิ่มเติม (ถ้ามี)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="er-footer-actions">
                            <div class="d-flex align-items-center" style="gap: 12px;">
                                <span class="er-counter-pill" id="totalCountBadge">
                                    <i class="fas fa-users text-primary"></i> จำนวนผู้สมัครในฟอร์ม: <strong id="totalCountText" class="ml-1 text-primary">1</strong> คน
                                </span>
                                @if (!auth()->user()->isStudent())
                                    <button type="button" class="btn-er-outline" id="addRegistrantBottom">
                                        <i class="fas fa-plus"></i> เพิ่มผู้สมัคร
                                    </button>
                                @endif
                            </div>
                            <button type="submit" class="btn-er-primary" id="btnSubmitForm">
                                <i class="fas fa-check-circle"></i> ยืนยันและบันทึกข้อมูลลงทะเบียน
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="er-card">
                <div class="er-card-body text-center py-5">
                    <div class="mb-3 text-warning">
                        <i class="fas fa-exclamation-circle fa-3x"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-2">รอบสอบนี้ยังไม่เปิดรับสมัคร หรือปิดรับสมัครแล้ว</h5>
                    <p class="text-muted mb-4">สถานะรอบสอบปัจจุบันคือ "{{ ['draft'=>'ฉบับร่าง','closed'=>'ปิดรับสมัคร','completed'=>'เสร็จสิ้น'][$session->status] ?? $session->status }}" ท่านสามารถดูรายชื่อผู้สมัครเดิมได้</p>
                    <a href="{{ route('exam_registration.list') }}" class="btn-er-info">
                        <i class="fas fa-list mr-1"></i> ดูรายชื่อผู้สมัครสอบ
                    </a>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection

@section('scripts')
<script>
function updateRowNumbersAndButtons() {
    var rows = document.querySelectorAll('#registrants .registrant-row');
    var count = rows.length;
    var countText = document.getElementById('totalCountText');
    if (countText) {
        countText.textContent = count;
    }

    rows.forEach(function (row, idx) {
        row.setAttribute('data-index', idx);
        var numEl = row.querySelector('.row-number');
        if (numEl) {
            numEl.textContent = idx + 1;
        }

        // Update all field names in this row
        row.querySelectorAll('input, select').forEach(function (field) {
            if (field.name) {
                field.name = field.name.replace(/registrants\[\d+\]/, 'registrants[' + idx + ']');
            }
        });

        // Show/hide remove button based on total count
        var removeBtn = row.querySelector('.btn-remove-row');
        if (removeBtn) {
            if (count > 1) {
                removeBtn.classList.remove('d-none');
            } else {
                removeBtn.classList.add('d-none');
            }
        }
    });
}

function removeRegistrantRow(button) {
    var row = button.closest('.registrant-row');
    if (row && document.querySelectorAll('#registrants .registrant-row').length > 1) {
        row.style.opacity = '0';
        row.style.transform = 'translateY(-10px)';
        setTimeout(function () {
            row.remove();
            updateRowNumbersAndButtons();
        }, 200);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('registrants');
    var addBtnTop = document.getElementById('addRegistrant');
    var addBtnBottom = document.getElementById('addRegistrantBottom');

    function addNewRegistrant() {
        if (!container) return;
        var rows = container.querySelectorAll('.registrant-row');
        if (rows.length >= 100) {
            alert('สามารถเพิ่มได้สูงสุด 100 คนต่อครั้ง');
            return;
        }

        var firstRow = rows[0];
        var clone = firstRow.cloneNode(true);
        var newIndex = rows.length;

        // Clear values in clone
        clone.querySelectorAll('input').forEach(function (input) {
            input.value = '';
        });
        clone.querySelectorAll('select').forEach(function (select) {
            select.selectedIndex = 0;
        });

        clone.style.opacity = '0';
        clone.style.transform = 'translateY(10px)';
        container.appendChild(clone);

        updateRowNumbersAndButtons();

        setTimeout(function () {
            clone.style.transition = 'all 0.3s ease';
            clone.style.opacity = '1';
            clone.style.transform = 'translateY(0)';
            clone.scrollIntoView({ behavior: 'smooth', block: 'center' });
            var firstInput = clone.querySelector('input');
            if (firstInput) firstInput.focus();
        }, 50);
    }

    if (addBtnTop) {
        addBtnTop.addEventListener('click', addNewRegistrant);
    }
    if (addBtnBottom) {
        addBtnBottom.addEventListener('click', addNewRegistrant);
    }

    // National ID format helper: only numeric
    document.addEventListener('input', function (e) {
        if (e.target && e.target.classList.contains('national-id-input')) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 13);
        }
    });

    updateRowNumbersAndButtons();
});
</script>
@endsection
