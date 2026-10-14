<?php

namespace App\Modules\ExamRegistration\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SrdExamRegistration extends Model
{
    const STATUSES = [
        'PENDING'  => 'รอตรวจสอบ',
        'APPROVED' => 'อนุมัติแล้ว',
        'REJECTED' => 'ไม่อนุมัติ',
    ];

    const FOLLOW_UP_STATUSES = [
        'FOLLOW_PENDING'     => 'รอติดตาม',
        'FOLLOW_IN_PROGRESS' => 'กำลังติดตาม',
        'FOLLOW_SUCCESS'     => 'ติดตามสำเร็จ',
        'FOLLOW_CANCELLED'   => 'ยกเลิกการติดตาม',
    ];

    protected $table = 'srd_exam_registrations';

    protected $fillable = [
        'reference_code',
        'academic_year',
        'title',
        'first_name',
        'last_name',
        'age',
        'dob_day',
        'dob_month',
        'dob_year_be',
        'citizen_id',
        'address_no',
        'moo',
        'subdistrict',
        'district',
        'province',
        'postal_code',
        'education_level',
        'previous_certificate_no',
        'previous_level',
        'previous_range',
        'previous_passed_year_be',
        'previous_province',
        'target_level',
        'target_range',
        'office_name',
        'office_subdistrict',
        'office_district',
        'office_province',
        'office_postal_code',
        'status',
        'approved_by',
        'approved_date',
        'submitted_date',
        'referral_sources',
        'follow_up_status',
        'phone',
        'line_id',
        'exam_session_id',
        'exam_center_id',
        'created_by',
    ];

    protected $casts = [
        'approved_date'   => 'datetime',
        'submitted_date'  => 'datetime',
    ];

    public function examSession()
    {
        return $this->belongsTo(SrdExamSession::class, 'exam_session_id');
    }

    public function examCenter()
    {
        return $this->belongsTo(SrdExamCenter::class, 'exam_center_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function followUps()
    {
        return $this->hasMany(SrdExamFollowUp::class, 'exam_registration_id')->orderByDesc('followed_at');
    }

    public function statusLabel()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function followUpStatusLabel()
    {
        return $this->follow_up_status ? (self::FOLLOW_UP_STATUSES[$this->follow_up_status] ?? $this->follow_up_status) : '-';
    }

    public function fullName()
    {
        return trim(($this->title ? $this->title . ' ' : '') . $this->first_name . ' ' . $this->last_name);
    }

    /** referral_sources เก็บเป็น string คั่นด้วย comma — แปลงกลับเป็น array */
    public function referralSourcesArray()
    {
        if (!$this->referral_sources) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->referral_sources))));
    }
}
