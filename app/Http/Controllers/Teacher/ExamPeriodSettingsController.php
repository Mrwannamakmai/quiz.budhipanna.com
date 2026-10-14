<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Support\Settings\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ตั้งค่าช่วงเวลารับสมัคร + ข้อความบน QR Code (เฉพาะ superadmin)
 * บันทึกค่าผ่าน Settings::set() คีย์เดียวกับที่ ExamRegistrationServiceProvider ลงทะเบียนไว้
 * เพื่อให้ตรงกับหน้า แอดมิน > ตั้งค่าระบบ เสมอ — ภาพ QR Code สร้างฝั่ง client ด้วย JS (ไม่เพิ่ม composer package)
 */
class ExamPeriodSettingsController extends Controller
{
    public function edit()
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        return view('examregistration::admin.period_settings', [
            'mode' => Settings::get('exam_registration.registration_mode', 'auto'),
            'start' => Settings::get('exam_registration.registration_start'),
            'end' => Settings::get('exam_registration.registration_end'),
            'qrTitle' => Settings::get('exam_registration.qr_title', 'สมัครเรียนธรรมศึกษา'),
            'qrSubtitle' => Settings::get('exam_registration.qr_subtitle', 'สำนักเรียนวัดสารอด'),
            'publicUrl' => route('exam_registration.public.create'),
        ]);
    }

    public function update(Request $request)
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        $data = $request->validate([
            'registration_mode'  => 'required|in:auto,force_open,force_closed',
            'registration_start' => 'nullable|date',
            'registration_end'   => 'nullable|date',
            'qr_title'           => 'nullable|string|max:255',
            'qr_subtitle'        => 'nullable|string|max:255',
        ]);

        Settings::set('exam_registration.registration_mode', $data['registration_mode']);
        Settings::set('exam_registration.registration_start', $data['registration_start'] ?? null);
        Settings::set('exam_registration.registration_end', $data['registration_end'] ?? null);
        Settings::set('exam_registration.qr_title', $data['qr_title'] ?? '');
        Settings::set('exam_registration.qr_subtitle', $data['qr_subtitle'] ?? '');

        return redirect()->route('teacher.exam_registration.settings.period')
            ->with('success', 'บันทึกการตั้งค่าแล้ว');
    }
}
