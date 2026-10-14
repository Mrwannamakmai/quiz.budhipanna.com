<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamRegistration extends Model
{
    const STATUSES = [
        'pending'    => 'รออนุมัติ',
        'registered' => 'รออนุมัติ',
        'checked'    => 'รออนุมัติ',
        'approved'   => 'อนุมัติแล้ว',
        'eligible'   => 'อนุมัติแล้ว',
        'rejected'   => 'ไม่อนุมัติ',
        'incomplete' => 'ตามเอกสาร',
        'cancelled'  => 'ยกเลิก',
    ];

    const STATUS_OPTIONS = [
        'pending'    => 'รออนุมัติ',
        'approved'   => 'อนุมัติแล้ว',
        'rejected'   => 'ไม่อนุมัติ',
        'incomplete' => 'ตามเอกสาร',
        'cancelled'  => 'ยกเลิก',
    ];

    protected $fillable = [
        'reference_code',
        'exam_session_id',
        'classroom_id',
        'created_by',
        'exam_center_id',
        'national_id',
        'title',
        'first_name',
        'last_name',
        'birth_date',
        'birth_day',
        'birth_month',
        'birth_year_be',
        'age',
        'phone',
        'line_id',
        'house_number',
        'sub_district',
        'district',
        'province',
        'postal_code',
        'education_class',
        'education_level',
        'exam_tier',
        'has_previous_cert',
        'previous_level',
        'previous_period',
        'previous_certificate_no',
        'previous_year',
        'previous_province',
        'office_name',
        'office_sub_district',
        'office_district',
        'office_province',
        'office_postal_code',
        'referral_sources',
        'registration_channel',
        'remarks',
        'status',
        'follow_up_status',
        'follow_up_note',
    ];

    const TIER_LABELS = [
        'tree' => 'ธ.ศ.ตรี',
        'tho'  => 'ธ.ศ.โท',
        'ek'   => 'ธ.ศ.เอก',
    ];

    protected $casts = [
        'birth_date'        => 'date',
        'has_previous_cert' => 'boolean',
    ];

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function examCenter()
    {
        return $this->belongsTo(ExamCenter::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
