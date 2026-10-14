<?php

namespace App\Http\Controllers;

use App\ExamRegistration;
use App\ExamSession;
use App\StudentPassedExam;
use Illuminate\Http\Request;

class ExamAnnouncementController extends Controller
{
    public function index()
    {
        $sessions = ExamSession::where('is_published', true)
            ->orderBy('academic_year', 'desc')
            ->orderBy('exam_date')
            ->get();

        $examRegistrationOpen = $sessions->contains('status', 'open');

        return view('exam_announcements.index', compact('sessions', 'examRegistrationOpen'));
    }

    public function liveRegistrations()
    {
        $sessions = ExamSession::where('is_published', true)
            ->withCount(['registrations' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }])
            ->orderBy('academic_year', 'desc')
            ->orderBy('exam_date')
            ->get(['id', 'academic_year', 'exam_date', 'status']);

        $data = $sessions->map(function ($session) {
            $registrations = ExamRegistration::where('exam_session_id', $session->id)
                ->where('status', '!=', 'cancelled')
                ->orderBy('first_name', 'asc')
                ->orderBy('last_name', 'asc')
                ->limit(100)
                ->get(['title', 'first_name', 'last_name', 'exam_tier', 'status', 'follow_up_status'])
                ->map(function ($registration) {
                    $fuIcons = [
                        'โทร' => '📱 โทร',
                        'ไลน์' => '💬 ไลน์',
                        'อีเมล' => '✉️ อีเมล',
                        'ติดต่อไม่ได้' => '❌ ติดต่อไม่ได้',
                        'ไม่มีผู้รับสาย' => '📵 ไม่มีผู้รับสาย',
                    ];
                    return [
                        'first_name' => $registration->first_name,
                        'last_name' => $registration->last_name,
                        'name' => trim($registration->title . $registration->first_name . ' ' . $registration->last_name),
                        'tier' => [
                            'tree' => 'ธรรมศึกษาชั้นตรี',
                            'tho' => 'ธรรมศึกษาชั้นโท',
                            'ek' => 'ธรรมศึกษาชั้นเอก',
                        ][$registration->exam_tier] ?? $registration->exam_tier,
                        'status' => ExamRegistration::STATUSES[$registration->status] ?? $registration->status,
                        'follow_up' => $fuIcons[$registration->follow_up_status] ?? ($registration->follow_up_status ?: 'ยังไม่ติดตาม'),
                    ];
                });

            return [
                'id' => $session->id,
                'academic_year' => $session->academic_year,
                'exam_date' => $session->exam_date ? $session->exam_date->format('d/m/Y') : null,
                'status' => [
                    'draft' => 'ฉบับร่าง',
                    'open' => 'เปิดรับสมัคร',
                    'closed' => 'ปิดรับสมัคร',
                    'completed' => 'เสร็จสิ้น',
                ][$session->status] ?? $session->status,
                'registrations_count' => $session->registrations_count,
                'registrations' => $registrations,
            ];
        });

        return response()->json(['sessions' => $data])
            ->header('Cache-Control', 'no-store, private');
    }

    public function searchPassed(Request $request)
    {
        $q = trim($request->get('q', ''));
        $courseType = $request->get('course_type');
        $examLevel = $request->get('exam_level');
        $year = $request->get('year');
        $seatNo = trim($request->get('seat_no', ''));

        if ($q === '' && !$courseType && !$examLevel && !$year && $seatNo === '') {
            return response()->json(['results' => [], 'total' => 0]);
        }

        $query = StudentPassedExam::query();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('pali_title', 'like', "%{$q}%");
            });
        }

        if ($seatNo !== '') {
            $query->where('exam_seat_no', 'like', "%{$seatNo}%");
        }

        if ($courseType) {
            $query->where('course_type', $courseType);
        }

        if ($examLevel) {
            $query->where('exam_level', $examLevel);
        }

        if ($year) {
            $query->where('academic_year_be', $year);
        }

        $total = $query->count();

        $results = $query->orderBy('exam_level')
            ->orderBy('first_name')
            ->limit(50)
            ->get()
            ->map(function ($r) {
                return [
                    'seat_no' => $r->exam_seat_no,
                    'name' => $r->fullName(),
                    'temple' => $r->temple_name,
                    'district' => $r->district,
                    'province' => $r->province,
                    'course_type' => $r->course_type,
                    'exam_level' => $r->exam_level,
                    'year' => $r->academic_year_be,
                    'status' => $r->result_status,
                ];
            });

        return response()->json(['results' => $results, 'total' => $total]);
    }
}
