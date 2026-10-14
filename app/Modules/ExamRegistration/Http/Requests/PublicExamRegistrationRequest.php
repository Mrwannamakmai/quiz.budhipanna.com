<?php

namespace App\Modules\ExamRegistration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicExamRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        // หน้าสมัครสอบสาธารณะ ไม่ต้อง login
        return true;
    }

    public function rules()
    {
        return [
            'title'       => 'required|string|max:50',
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'age'         => 'nullable|integer|min:0|max:150',
            'dob_day'     => 'nullable|integer|min:1|max:31',
            'dob_month'   => 'nullable|integer|min:1|max:12',
            'dob_year_be' => 'nullable|integer|min:2400|max:2700',

            'citizen_id'  => 'required|digits:13|unique:srd_exam_registrations,citizen_id',

            'address_no'   => 'nullable|string|max:100',
            'moo'          => 'nullable|string|max:50',
            'subdistrict'  => 'nullable|string|max:100',
            'district'     => 'nullable|string|max:100',
            'province'     => 'nullable|string|max:100',
            'postal_code'  => 'nullable|string|max:10',

            'education_level' => 'required|string|max:100',

            'previous_certificate_no'  => 'nullable|string|max:100',
            'previous_level'           => 'nullable|string|max:50',
            'previous_range'           => 'nullable|string|max:50',
            'previous_passed_year_be'  => 'nullable|integer|min:2400|max:2700',
            'previous_province'       => 'nullable|string|max:100',

            'target_level' => 'required|string|max:50',
            'target_range' => 'required|string|max:50',

            'office_name'          => 'nullable|string|max:255',
            'office_subdistrict'   => 'nullable|string|max:100',
            'office_district'      => 'nullable|string|max:100',
            'office_province'      => 'nullable|string|max:100',
            'office_postal_code'   => 'nullable|string|max:10',

            'phone'   => 'nullable|string|max:20',
            'line_id' => 'nullable|string|max:100',

            'referral_sources'   => 'nullable|array',
            'referral_sources.*' => 'nullable|string|max:100',

            'exam_session_id' => 'nullable|exists:srd_exam_sessions,id',
            'exam_center_id'  => 'nullable|exists:srd_exam_centers,id',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required'  => 'กรุณากรอกชื่อ',
            'last_name.required'   => 'กรุณากรอกนามสกุล',
            'citizen_id.required'  => 'กรุณากรอกเลขประจำตัวประชาชน',
            'citizen_id.digits'    => 'เลขประจำตัวประชาชนต้องมี 13 หลัก',
            'citizen_id.unique'    => 'เลขประจำตัวประชาชนนี้ลงทะเบียนไว้แล้ว',
            'education_level.required' => 'กรุณาเลือกระดับการศึกษา',
            'target_level.required'    => 'กรุณาเลือกชั้นที่สมัครสอบ',
            'target_range.required'    => 'กรุณาเลือกช่วงชั้นที่สมัครสอบ',
        ];
    }
}
