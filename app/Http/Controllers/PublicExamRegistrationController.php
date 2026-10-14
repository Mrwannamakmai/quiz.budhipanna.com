<?php

namespace App\Http\Controllers;

use App\ExamRegistration;
use App\ExamSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicExamRegistrationController extends Controller
{
    public function index()
    {
        $session = $this->getOpenSession();
        $periodStatus = $this->checkPeriod($session);

        $view = request('theme') === 'mobile'
            ? 'public_exam_registration_mobile'
            : 'public_exam_registration';

        return view($view, compact('session', 'periodStatus'));
    }

    public function store(Request $request)
    {
        $session = $this->getOpenSession();
        $periodStatus = $this->checkPeriod($session);

        if ($periodStatus['status'] !== 'open') {
            return response()->json([
                'success' => false,
                'message' => $periodStatus['message'],
            ], 422);
        }

        $rules = [
            'academic_year'    => 'required|string|max:10',
            'title'            => 'required|in:เด็กชาย,เด็กหญิง,นาย,นาง,นางสาว',
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'national_id'      => 'required|regex:/^[0-9]{13}$/',
            'age'              => 'required|integer|min:1|max:120',
            'birth_day'        => 'required|integer|min:1|max:31',
            'birth_month'      => 'required|integer|min:1|max:12',
            'birth_year_be'    => 'required|integer|min:2400|max:2600',
            'phone'            => 'required|regex:/^0[0-9]{8,9}$/',
            'line_id'          => 'nullable|string|max:100',
            'house_number'     => 'required|string|max:100',
            'sub_district'     => 'required|string|max:100',
            'district'         => 'required|string|max:100',
            'province'         => 'required|string|max:100',
            'postal_code'      => 'required|regex:/^[0-9]{5}$/',
            'education_level'  => 'required|string|max:50',
            'has_previous_cert'   => 'required|boolean',
            'previous_level'      => 'required_if:has_previous_cert,1|nullable|in:ตรี,โท,เอก',
            'previous_period'     => 'nullable|string|max:50',
            'previous_certificate_no' => 'nullable|string|max:100',
            'previous_year'       => 'nullable|string|max:10',
            'previous_province'   => 'nullable|string|max:100',
            'target_level'        => 'required|in:ตรี,โท,เอก',
            'office_name'         => 'required|string|max:255',
            'office_province'     => 'nullable|string|max:100',
            'office_district'     => 'nullable|string|max:100',
            'office_sub_district' => 'nullable|string|max:100',
            'office_postal_code'  => 'nullable|regex:/^[0-9]{5}$/',
            'referral_sources'    => 'nullable|array',
            'referral_sources.*'  => 'string|max:100',
        ];

        $messages = [
            'national_id.required' => 'กรุณากรอกเลขประจำตัวประชาชน',
            'national_id.regex'    => 'เลขประจำตัวประชาชนต้องเป็นตัวเลข 13 หลัก',
            'title.required'       => 'กรุณาเลือกคำนำหน้าชื่อ',
            'first_name.required'  => 'กรุณากรอกชื่อ',
            'last_name.required'   => 'กรุณากรอกนามสกุล',
            'age.required'         => 'กรุณากรอกอายุ',
            'age.min'              => 'อายุต้องมากกว่า 0',
            'age.max'              => 'อายุต้องไม่เกิน 120 ปี',
            'birth_day.required'   => 'กรุณากรอกวันเกิด',
            'birth_month.required' => 'กรุณาเลือกเดือนเกิด',
            'birth_year_be.required' => 'กรุณากรอกปีเกิด (พ.ศ.)',
            'phone.required'       => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.regex'          => 'เบอร์โทรศัพท์ไม่ถูกต้อง (ต้องขึ้นต้นด้วย 0 ตามด้วยตัวเลข 8-9 หลัก)',
            'house_number.required' => 'กรุณากรอกบ้านเลขที่',
            'province.required'    => 'กรุณาเลือกจังหวัด',
            'district.required'    => 'กรุณาเลือกอำเภอ/เขต',
            'sub_district.required' => 'กรุณาเลือกตำบล/แขวง',
            'postal_code.required' => 'กรุณากรอกรหัสไปรษณีย์',
            'postal_code.regex'    => 'รหัสไปรษณีย์ต้องเป็นตัวเลข 5 หลัก',
            'education_level.required' => 'กรุณาเลือกระดับการศึกษา',
            'previous_level.required_if' => 'กรุณาเลือกชั้นเดิมที่สอบได้',
            'target_level.required' => 'กรุณาเลือกชั้นธรรมศึกษาที่สมัคร',
            'office_name.required'  => 'กรุณากรอกชื่อสำนักเรียน/โรงเรียน',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()->toArray(),
            ], 422);
        }

        $data = $validator->validated();

        // Validate birth date
        $birthYearCE = $data['birth_year_be'] - 543;
        if (!checkdate($data['birth_month'], $data['birth_day'], $birthYearCE)) {
            return response()->json([
                'success' => false,
                'errors'  => ['birth_day' => ['วันเกิดไม่ถูกต้อง']],
            ], 422);
        }

        // Validate target level vs previous cert
        $hasPrev = (bool) $data['has_previous_cert'];
        $targetLevel = $data['target_level'];

        if (!$hasPrev && $targetLevel !== 'ตรี') {
            return response()->json([
                'success' => false,
                'errors'  => ['target_level' => ['ผู้สมัครครั้งแรกสามารถสมัครได้เฉพาะชั้นตรีเท่านั้น']],
            ], 422);
        }

        if ($hasPrev) {
            $prevLevel = $data['previous_level'] ?? null;
            if ($targetLevel === 'โท' && $prevLevel !== 'ตรี') {
                return response()->json([
                    'success' => false,
                    'errors'  => ['target_level' => ['สมัครชั้นโทต้องสอบผ่านชั้นตรีมาก่อน']],
                ], 422);
            }
            if ($targetLevel === 'เอก' && $prevLevel !== 'โท') {
                return response()->json([
                    'success' => false,
                    'errors'  => ['target_level' => ['สมัครชั้นเอกต้องสอบผ่านชั้นโทมาก่อน']],
                ], 422);
            }
        }

        // Check duplicate national_id for this session
        $nationalId = preg_replace('/\D/', '', $data['national_id']);
        $existing = ExamRegistration::where('exam_session_id', $session->id)
            ->where('national_id', $nationalId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'เลขประจำตัวประชาชนนี้เคยสมัครแล้ว'
                    . ($existing->reference_code ? ' (รหัสอ้างอิง: ' . $existing->reference_code . ')' : '')
                    . ' สถานะ: ' . (ExamRegistration::STATUSES[$existing->status] ?? $existing->status),
            ], 422);
        }

        // Map exam_tier
        $tierMap = ['ตรี' => 'tree', 'โท' => 'tho', 'เอก' => 'ek'];

        $referralSources = $data['referral_sources'] ?? [];
        $referralText = !empty($referralSources) ? implode(', ', $referralSources) : null;

        $birthDate = Carbon::createFromDate($birthYearCE, $data['birth_month'], $data['birth_day']);
        $referenceCode = 'REG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));

        $registration = ExamRegistration::create([
            'reference_code'     => $referenceCode,
            'exam_session_id'    => $session->id,
            'national_id'        => $nationalId,
            'title'              => $data['title'],
            'first_name'         => $data['first_name'],
            'last_name'          => $data['last_name'],
            'birth_date'         => $birthDate,
            'birth_day'          => $data['birth_day'],
            'birth_month'        => $data['birth_month'],
            'birth_year_be'      => $data['birth_year_be'],
            'age'                => $data['age'],
            'phone'              => $data['phone'],
            'line_id'            => $data['line_id'] ?? null,
            'house_number'       => $data['house_number'],
            'sub_district'       => $data['sub_district'],
            'district'           => $data['district'],
            'province'           => $data['province'],
            'postal_code'        => $data['postal_code'],
            'education_class'    => $data['education_level'],
            'education_level'    => $data['education_level'],
            'has_previous_cert'  => $hasPrev,
            'previous_level'     => $data['previous_level'] ?? null,
            'previous_period'    => $data['previous_period'] ?? null,
            'previous_certificate_no' => $data['previous_certificate_no'] ?? null,
            'previous_year'      => $data['previous_year'] ?? null,
            'previous_province'  => $data['previous_province'] ?? null,
            'exam_tier'          => $tierMap[$targetLevel] ?? 'tree',
            'office_name'        => $data['office_name'],
            'office_province'    => $data['office_province'] ?? null,
            'office_district'    => $data['office_district'] ?? null,
            'office_sub_district' => $data['office_sub_district'] ?? null,
            'office_postal_code' => $data['office_postal_code'] ?? null,
            'referral_sources'   => $referralText,
            'registration_channel' => 'public',
            'status'             => 'registered',
        ]);

        return response()->json([
            'success'        => true,
            'message'        => 'ส่งใบสมัครเรียบร้อยแล้ว',
            'reference_code' => $referenceCode,
        ]);
    }

    public function addresses(Request $request)
    {
        $path = resource_path('data/thai-addresses.json');
        if (!file_exists($path)) {
            return response()->json([]);
        }

        $data = json_decode(file_get_contents($path), true);

        $type = $request->query('type');
        $province = $request->query('province');
        $district = $request->query('district');
        $subDistrict = $request->query('sub_district');

        if ($type === 'provinces') {
            $provinces = collect($data)->pluck('province')->unique()->sort()->values();
            return response()->json($provinces);
        }

        if ($type === 'districts' && $province) {
            $districts = collect($data)
                ->where('province', $province)
                ->pluck('amphoe')->unique()->sort()->values();
            return response()->json($districts);
        }

        if ($type === 'sub_districts' && $province && $district) {
            $subs = collect($data)
                ->where('province', $province)
                ->where('amphoe', $district)
                ->pluck('district')->unique()->sort()->values();
            return response()->json($subs);
        }

        if ($type === 'postal_codes' && $province && $district && $subDistrict) {
            $codes = collect($data)
                ->where('province', $province)
                ->where('amphoe', $district)
                ->where('district', $subDistrict)
                ->pluck('zipcode')->unique()->sort()->values();
            return response()->json($codes);
        }

        return response()->json([]);
    }

    private function getOpenSession()
    {
        return ExamSession::where('is_published', true)
            ->whereIn('status', ['open', 'draft'])
            ->orderByDesc('id')
            ->first();
    }

    private function checkPeriod($session)
    {
        if (!$session) {
            return ['status' => 'closed', 'message' => 'ยังไม่มีรอบสอบที่เปิดรับสมัคร'];
        }

        $mode = $session->registration_mode ?? 'auto';

        if ($mode === 'force_closed') {
            return ['status' => 'closed', 'message' => 'ปิดรับสมัครชั่วคราว'];
        }

        if ($mode === 'force_open') {
            return [
                'status'  => 'open',
                'message' => null,
                'session' => $session,
            ];
        }

        // auto mode
        $now = Carbon::today();
        $start = $session->registration_start_date ? Carbon::parse($session->registration_start_date) : null;
        $end = $session->registration_end_date ? Carbon::parse($session->registration_end_date) : null;

        if ($start && $now->lt($start)) {
            return [
                'status'  => 'not_started',
                'message' => 'ยังไม่เปิดรับสมัคร — เปิดรับสมัครวันที่ ' . $this->thaiDate($start),
            ];
        }

        if ($end && $now->gt($end)) {
            return ['status' => 'expired', 'message' => 'หมดเวลารับสมัครแล้ว'];
        }

        $info = $end ? 'เปิดรับสมัครถึงวันที่ ' . $this->thaiDate($end) : null;

        return [
            'status'  => 'open',
            'message' => $info,
            'session' => $session,
        ];
    }

    private function thaiDate(Carbon $date)
    {
        $months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม',
        ];

        return $date->day . ' ' . $months[$date->month] . ' ' . ($date->year + 543);
    }
}
