<?php

namespace App\Http\Controllers\Admin;

use App\ExamSession;
use App\ExamRegistration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    public function index()
    {
        $sessions = ExamSession::withCount('registrations')
            ->orderBy('academic_year', 'desc')
            ->orderBy('exam_date')
            ->get();

        return view('admin.exam_sessions.index', compact('sessions'));
    }

    public function registrations(ExamSession $examSession)
    {
        $registrations = ExamRegistration::with('examCenter')
            ->where('exam_session_id', $examSession->id)
            ->orderBy('exam_tier')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(50);

        return view('admin.exam_sessions.registrations', [
            'session' => $examSession,
            'registrations' => $registrations,
        ]);
    }

    public function updateRegistrationStatus(Request $request, ExamRegistration $registration)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(ExamRegistration::STATUSES)),
        ]);

        $registration->update(['status' => $data['status']]);

        return redirect()->route('admin.exam_sessions.registrations', $registration->exam_session_id)
            ->with('success', 'ปรับปรุงสถานะผู้สมัครเรียบร้อยแล้ว');
    }

    public function create()
    {
        $session = new ExamSession();

        return view('admin.exam_sessions.form', compact('session'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        ExamSession::create($data);

        return redirect()->route('admin.exam_sessions.index')->with('success', 'สร้างรอบสอบเรียบร้อยแล้ว');
    }

    public function edit(ExamSession $examSession)
    {
        $session = $examSession;

        return view('admin.exam_sessions.form', compact('session'));
    }

    public function update(Request $request, ExamSession $examSession)
    {
        $examSession->update($this->validatedData($request));

        return redirect()->route('admin.exam_sessions.index')->with('success', 'บันทึกรอบสอบเรียบร้อยแล้ว');
    }

    public function exportRegistrations(Request $request, ExamSession $examSession)
    {
        $registrations = ExamRegistration::with('examCenter')
            ->where('exam_session_id', $examSession->id)
            ->orderBy('exam_tier')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $format = $request->query('format', 'excel');

        if ($format === 'pdf') {
            return view('admin.exam_sessions.registrations_print', [
                'session' => $examSession,
                'registrations' => $registrations,
            ]);
        }

        // Excel (CSV with BOM for Thai)
        $tierLabels = ['tree' => 'ธรรมศึกษาชั้นตรี', 'tho' => 'ธรรมศึกษาชั้นโท', 'ek' => 'ธรรมศึกษาชั้นเอก'];
        $filename = 'registrations_' . $examSession->academic_year . '_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($registrations, $tierLabels) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ลำดับ', 'รหัสอ้างอิง', 'คำนำหน้า', 'ชื่อ', 'นามสกุล',
                'เลขประจำตัวประชาชน', 'อายุ', 'วันเกิด', 'เดือนเกิด', 'ปีเกิด (พ.ศ.)',
                'เบอร์โทร', 'LINE ID',
                'บ้านเลขที่', 'ตำบล/แขวง', 'อำเภอ/เขต', 'จังหวัด', 'รหัสไปรษณีย์',
                'ระดับการศึกษา', 'ชั้นเรียน',
                'ชั้นที่สมัครสอบ',
                'เคยสอบผ่าน', 'ชั้นเดิม', 'ช่วงชั้นเดิม', 'เลขที่ประกาศนียบัตร', 'ปีที่สอบได้', 'จังหวัดที่สอบ',
                'สำนักเรียน', 'ตำบล (สำนักเรียน)', 'อำเภอ (สำนักเรียน)', 'จังหวัด (สำนักเรียน)', 'รหัสไปรษณีย์ (สำนักเรียน)',
                'สนามสอบ', 'ช่องทางสมัคร', 'สถานะ', 'วันที่สมัคร',
            ]);

            foreach ($registrations as $i => $r) {
                fputcsv($out, [
                    $i + 1,
                    $r->reference_code,
                    $r->title,
                    $r->first_name,
                    $r->last_name,
                    "'" . $r->national_id,
                    $r->age,
                    $r->birth_day,
                    $r->birth_month,
                    $r->birth_year_be,
                    "'" . $r->phone,
                    $r->line_id,
                    $r->house_number,
                    $r->sub_district,
                    $r->district,
                    $r->province,
                    "'" . $r->postal_code,
                    $r->education_level,
                    $r->education_class,
                    $tierLabels[$r->exam_tier] ?? $r->exam_tier,
                    $r->has_previous_cert ? 'ใช่' : 'ไม่',
                    $r->previous_level,
                    $r->previous_period,
                    $r->previous_certificate_no,
                    $r->previous_year,
                    $r->previous_province,
                    $r->office_name,
                    $r->office_sub_district,
                    $r->office_district,
                    $r->office_province,
                    "'" . $r->office_postal_code,
                    $r->examCenter->name ?? '',
                    $r->registration_channel === 'public' ? 'สมัครเอง' : 'ครูสมัครให้',
                    ExamRegistration::STATUSES[$r->status] ?? $r->status,
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function validatedData(Request $request)
    {
        $data = $request->validate([
            'academic_year' => 'required|string|max:20',
            'exam_date' => 'nullable|date',
            'status' => 'required|in:draft,open,closed,completed',
            'details' => 'nullable|string|max:10000',
            'schedule' => 'nullable|string|max:10000',
            'announcement' => 'nullable|required_if:is_published,1|string|max:10000',
            'notebooklm_url' => ['nullable', 'url', 'max:1000', 'regex~^https://notebooklm\.google\.com/~i'],
            'is_published' => 'nullable|boolean',
            'registration_mode' => 'nullable|in:auto,force_open,force_closed',
            'registration_start_date' => 'nullable|date',
            'registration_end_date' => 'nullable|date|after_or_equal:registration_start_date',
        ]);

        $data['is_published'] = $request->has('is_published');
        $data['registration_mode'] = $data['registration_mode'] ?? 'auto';

        return $data;
    }
}
