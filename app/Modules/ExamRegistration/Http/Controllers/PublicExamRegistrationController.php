<?php

namespace App\Modules\ExamRegistration\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ExamRegistration\Http\Requests\PublicExamRegistrationRequest;
use App\Modules\ExamRegistration\Models\SrdExamCenter;
use App\Modules\ExamRegistration\Models\SrdExamRegistration;
use App\Modules\ExamRegistration\Models\SrdExamSession;
use App\Support\Settings\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicExamRegistrationController extends Controller
{
    /**
     * ตรวจสอบว่าช่วงเวลารับสมัครเปิดอยู่หรือไม่ ตามค่าตั้งค่า exam_registration.registration_mode
     * โหมด: auto (เทียบกับวันเวลาที่ตั้งไว้), force_open (เปิดเสมอ), force_closed (ปิดเสมอ)
     */
    protected function registrationStatus()
    {
        $mode = Settings::get('exam_registration.registration_mode', 'auto');

        if ($mode === 'force_open') {
            return ['open' => true, 'message' => null];
        }

        if ($mode === 'force_closed') {
            return ['open' => false, 'message' => 'ขณะนี้ปิดรับสมัครชั่วคราว'];
        }

        // auto
        $start = Settings::get('exam_registration.registration_start');
        $end = Settings::get('exam_registration.registration_end');
        $now = Carbon::now();

        try {
            if ($start && $now->lt(Carbon::parse($start))) {
                return ['open' => false, 'message' => 'ยังไม่เปิดรับสมัคร กรุณากลับมาใหม่ในวันที่ ' . Carbon::parse($start)->format('d/m/Y H:i')];
            }
            if ($end && $now->gt(Carbon::parse($end))) {
                return ['open' => false, 'message' => 'ปิดรับสมัครแล้วเมื่อวันที่ ' . Carbon::parse($end)->format('d/m/Y H:i')];
            }
        } catch (\Throwable $e) {
            // ค่าวันที่ไม่ถูกต้อง — ถือว่าไม่จำกัดช่วงเวลา
        }

        return ['open' => true, 'message' => null];
    }

    public function create()
    {
        if (!Settings::moduleEnabled('exam_registration')) {
            return view('examregistration::public.closed', [
                'message' => 'ขออภัย ระบบลงทะเบียนสอบธรรมศึกษาปิดให้บริการขณะนี้',
            ]);
        }

        $status = $this->registrationStatus();
        if (!$status['open']) {
            return view('examregistration::public.closed', [
                'message' => $status['message'] ?? 'ปิดรับสมัคร',
            ]);
        }

        $sessions = SrdExamSession::where('is_published', true)->orderByDesc('exam_date')->get();
        $centers = SrdExamCenter::where('is_active', true)->orderBy('name')->get();

        return view('examregistration::public.create', [
            'sessions' => $sessions,
            'centers' => $centers,
        ]);
    }

    public function store(PublicExamRegistrationRequest $request)
    {
        if (!Settings::moduleEnabled('exam_registration')) {
            abort(404);
        }

        $status = $this->registrationStatus();
        if (!$status['open']) {
            return redirect()->route('exam_registration.public.create')
                ->withErrors(['registration' => $status['message'] ?? 'ปิดรับสมัคร']);
        }

        $data = $request->validated();

        $referral = $request->input('referral_sources', []);
        $data['referral_sources'] = is_array($referral) ? implode(',', array_filter($referral)) : $referral;

        $data['status'] = 'PENDING';
        $data['follow_up_status'] = 'FOLLOW_PENDING';
        $data['submitted_date'] = now();
        $data['reference_code'] = $this->generateReferenceCode();

        $registration = SrdExamRegistration::create($data);

        return redirect()->route('exam_registration.public.success', ['referenceCode' => $registration->reference_code]);
    }

    public function success($referenceCode)
    {
        $registration = SrdExamRegistration::where('reference_code', $referenceCode)->firstOrFail();

        return view('examregistration::public.success', ['registration' => $registration]);
    }

    protected function generateReferenceCode()
    {
        do {
            $code = 'REG-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (SrdExamRegistration::where('reference_code', $code)->exists());

        return $code;
    }

    /**
     * ข้อมูลอำเภอ/เขต ตามรหัสจังหวัด อ่านจาก public/data/thai-address.json
     * (หน้าสมัครใช้ JS fetch ไฟล์ทั้งหมดครั้งเดียวเป็นหลัก endpoint นี้มีไว้สำรอง/เรียกใช้แบบ server-side)
     */
    public function districts(Request $request)
    {
        $provinceCode = $request->query('province_code');
        $data = $this->loadAddressData();

        $province = collect($data)->firstWhere('code', (int) $provinceCode);
        $districts = $province ? array_map(function ($d) {
            return ['code' => $d['code'], 'name_th' => $d['name_th']];
        }, $province['districts']) : [];

        return response()->json($districts);
    }

    public function subdistricts(Request $request)
    {
        $provinceCode = $request->query('province_code');
        $districtCode = $request->query('district_code');
        $data = $this->loadAddressData();

        $province = collect($data)->firstWhere('code', (int) $provinceCode);
        $district = $province ? collect($province['districts'])->firstWhere('code', (int) $districtCode) : null;

        return response()->json($district ? $district['subdistricts'] : []);
    }

    protected function loadAddressData()
    {
        static $cache = null;
        if ($cache === null) {
            $path = public_path('data/thai-address.json');
            $cache = file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        }
        return $cache;
    }
}
