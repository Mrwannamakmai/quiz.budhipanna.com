@extends('layouts.teacher')
@section('title', 'รายละเอียดผู้สมัครสอบ')
@section('page-title', 'รายละเอียดผู้สมัครสอบ')

@section('content')
<style>
.er-show-container {
    max-width: 1000px;
    margin: 0 auto;
}

.er-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
    margin-bottom: 24px;
    overflow: hidden;
}

.er-banner {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.er-section-header {
    background: #f8fafc;
    padding: 14px 22px;
    border-bottom: 1.5px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    color: #1e293b;
}

.er-section-header.blue { border-left: 4px solid #2563eb; }
.er-section-header.emerald { border-left: 4px solid #10b981; }
.er-section-header.purple { border-left: 4px solid #8b5cf6; }
.er-section-header.amber { border-left: 4px solid #f59e0b; }

.er-section-body {
    padding: 22px;
}

.er-label-muted {
    font-size: 0.8rem;
    color: #64748b;
    margin-bottom: 4px;
    font-weight: 600;
}

.er-val {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

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
    text-decoration: none !important;
}

.btn-er-ghost {
    background: #ffffff;
    color: #334155 !important;
    border: 1.5px solid #cbd5e1;
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
    background: #f8fafc;
    border-color: #475569;
}
</style>

<div class="er-show-container">
    {{-- Top Banner --}}
    <div class="er-banner">
        <div class="d-flex align-items-center" style="gap: 14px;">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:1.35rem;flex-shrink:0;">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <h5 class="m-0 font-weight-bold text-dark">
                    {{ $registration->title }}{{ $registration->first_name }} {{ $registration->last_name }}
                </h5>
                <div class="text-muted small mt-1">
                    <i class="fas fa-id-card-alt mr-1"></i> ปชช. {{ $registration->national_id }}
                    @if(optional($registration->examSession)->academic_year)
                        · รอบสอบปีการศึกษา {{ $registration->examSession->academic_year }}
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center" style="gap: 10px;">
            <a href="{{ route('exam_registration.list') }}" class="btn-er-ghost">
                <i class="fas fa-arrow-left"></i> กลับสู่รายชื่อ
            </a>
            <a href="{{ route('exam_registration.edit', $registration->id) }}" class="btn-er-primary">
                <i class="fas fa-edit"></i> แก้ไขข้อมูล
            </a>
        </div>
    </div>

    {{-- Section 1: ข้อมูลส่วนตัว --}}
    <div class="er-card">
        <div class="er-section-header blue">
            <i class="fas fa-user text-primary"></i> ๑. ข้อมูลส่วนตัวผู้สมัคร
        </div>
        <div class="er-section-body">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="er-label-muted">ชื่อ - นามสกุล</div>
                    <div class="er-val text-primary">{{ $registration->title }}{{ $registration->first_name }} {{ $registration->last_name }}</div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="er-label-muted">เลขประจำตัวประชาชน</div>
                    <div class="er-val">{{ $registration->national_id }}</div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="er-label-muted">วันเกิด</div>
                    <div class="er-val">{{ $registration->birth_date ? $registration->birth_date->format('d/m/Y') : '-' }}</div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="er-label-muted">เบอร์โทรศัพท์</div>
                    <div class="er-val">{{ $registration->phone ?: '-' }}</div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="er-label-muted">LINE ID</div>
                    <div class="er-val">{{ $registration->line_id ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 2: ข้อมูลที่อยู่ & สังกัด --}}
    <div class="er-card">
        <div class="er-section-header emerald">
            <i class="fas fa-map-marker-alt text-success"></i> ๒. ข้อมูลที่อยู่ & สังกัด
        </div>
        <div class="er-section-body">
            <div class="row">
                <div class="col-md-8 col-sm-12 mb-3">
                    <div class="er-label-muted">ที่อยู่ / บ้านเลขที่</div>
                    <div class="er-val">
                        {{ implode(' ', array_filter([$registration->house_number, $registration->sub_district ? 'ต.'.$registration->sub_district : '', $registration->district ? 'อ.'.$registration->district : '', $registration->province ? 'จ.'.$registration->province : '', $registration->postal_code])) ?: '-' }}
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 mb-3">
                    <div class="er-label-muted">โรงเรียน / สังกัด / หน่วยงาน</div>
                    <div class="er-val">{{ $registration->office_name ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: วุฒิการศึกษา & ประวัติเดิม --}}
    <div class="er-card">
        <div class="er-section-header purple">
            <i class="fas fa-graduation-cap text-primary"></i> ๓. วุฒิการศึกษา & ประวัติการสอบเดิม
        </div>
        <div class="er-section-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">ระดับการศึกษา</div>
                    <div class="er-val">{{ $registration->education_level ?: '-' }}</div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">ชั้นเรียนปัจจุบัน</div>
                    <div class="er-val">{{ $registration->education_class ?: '-' }}</div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">เลขประกาศนียบัตรเดิม</div>
                    <div class="er-val">{{ $registration->previous_certificate_no ?: '-' }}</div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">จังหวัดที่สอบผ่านเดิม</div>
                    <div class="er-val">{{ $registration->previous_province ?: '-' }}</div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">พ.ศ. ที่สอบผ่านเดิม</div>
                    <div class="er-val">{{ $registration->previous_year ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 4: ข้อมูลการสมัครสอบ --}}
    <div class="er-card">
        <div class="er-section-header amber">
            <i class="fas fa-file-signature text-warning"></i> ๔. ข้อมูลการสมัครสอบ & สนามสอบ
        </div>
        <div class="er-section-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">ชั้นที่สมัครสอบ</div>
                    <div class="er-val text-primary">{{ \App\ExamRegistration::TIER_LABELS[$registration->exam_tier] ?? $registration->exam_tier }}</div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">สนามสอบ</div>
                    <div class="er-val">{{ $registration->examCenter->name ?? 'ยังไม่ระบุสนามสอบ' }}</div>
                </div>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">สถานะการสมัคร</div>
                    <div>
                        @php
                            $sKey = $registration->status;
                            if ($sKey === 'registered' || $sKey === 'checked') $sKey = 'pending';
                            if ($sKey === 'eligible') $sKey = 'approved';
                            $sLabels = ['pending'=>'⏳ รออนุมัติ','approved'=>'✅ อนุมัติแล้ว','rejected'=>'❌ ไม่อนุมัติ','incomplete'=>'📄 ตามเอกสาร','cancelled'=>'🚫 ยกเลิก'];
                            $sColors = ['pending'=>'badge-warning','approved'=>'badge-success','rejected'=>'badge-danger','incomplete'=>'badge-info','cancelled'=>'badge-secondary'];
                        @endphp
                        <span class="badge {{ $sColors[$sKey] ?? 'badge-secondary' }} px-2 py-1" style="font-size: 0.9rem;">
                            {{ $sLabels[$sKey] ?? (\App\ExamRegistration::STATUSES[$registration->status] ?? $registration->status) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="er-label-muted">หมายเหตุ</div>
                    <div class="text-dark">{{ $registration->remarks ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 5: การติดตาม & บันทึกการติดต่อ --}}
    <div class="er-card">
        <div class="er-section-header blue">
            <i class="fas fa-headset text-primary"></i> ๕. การติดตาม & บันทึกการติดต่อ
        </div>
        <div class="er-section-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="er-label-muted">ผลการติดต่อล่าสุด</div>
                    <div class="mt-1">
                        @if($registration->follow_up_status)
                            @php
                                $fuIcons = ['โทร'=>'📱','ไลน์'=>'💬','อีเมล'=>'✉️','ติดต่อไม่ได้'=>'❌','ไม่มีผู้รับสาย'=>'📵','เรียบร้อยแล้ว'=>'✅'];
                                $fuBadgeClasses = ['โทร'=>'badge-info','ไลน์'=>'badge-success','อีเมล'=>'badge-primary','ติดต่อไม่ได้'=>'badge-danger','ไม่มีผู้รับสาย'=>'badge-warning','เรียบร้อยแล้ว'=>'badge-success'];
                                $icon = $fuIcons[$registration->follow_up_status] ?? '📌';
                                $badgeClass = $fuBadgeClasses[$registration->follow_up_status] ?? 'badge-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }} px-2 py-1 font-weight-normal" style="font-size: 0.925rem;">
                                {{ $icon }} {{ $registration->follow_up_status }}
                            </span>
                        @else
                            <span class="text-muted font-italic">ยังไม่มีการบันทึกการติดตาม</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-5 col-sm-6 mb-3">
                    <div class="er-label-muted">บันทึกการติดตาม / นัดหมายเอกสาร</div>
                    <div class="er-val">{{ $registration->follow_up_note ?: '-' }}</div>
                </div>
                <div class="col-md-3 col-sm-12 mb-3">
                    <div class="er-label-muted">ปรับปรุงข้อมูลเมื่อ</div>
                    <div class="text-muted" style="font-size: 0.875rem;">
                        {{ $registration->updated_at ? $registration->updated_at->format('d/m/Y H:i น.') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
