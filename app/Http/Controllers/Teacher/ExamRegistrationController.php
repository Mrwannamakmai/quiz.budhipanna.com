<?php

namespace App\Http\Controllers\Teacher;

use App\ExamCenter;
use App\ExamRegistration;
use App\ExamSession;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $sessionQuery = ExamSession::orderBy('academic_year', 'desc');
        if (Auth::user()->isStudent()) {
            $sessionQuery->where('is_published', true);
        }
        $sessions = $sessionQuery->get();
        $session = $request->filled('session_id')
            ? $sessions->firstWhere('id', (int) $request->session_id)
            : $sessions->firstWhere('status', 'open');
        $centers = ExamCenter::orderBy('name')->get();

        return view('teacher.exam_registration.index', [
            'sessions' => $sessions,
            'session' => $session,
            'centers' => $centers,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_session_id' => 'required|exists:exam_sessions,id',
            'registrants' => 'required|array|min:1|max:' . (Auth::user()->isStudent() ? 1 : 100),
            'registrants.*.national_id' => ['required', 'distinct', 'regex:/^[0-9]{13}$/'],
            'registrants.*.title' => 'required|string|max:50',
            'registrants.*.first_name' => 'required|string|max:255',
            'registrants.*.last_name' => 'required|string|max:255',
            'registrants.*.birth_date' => 'required|date|before:today',
            'registrants.*.education_class' => 'required|string|max:50',
            'registrants.*.exam_tier' => 'required|in:tree,tho,ek',
            'registrants.*.previous_certificate_no' => 'nullable|string|max:100',
            'registrants.*.exam_center_id' => 'nullable|exists:exam_centers,id',
            'registrants.*.remarks' => 'nullable|string|max:1000',
        ]);

        $session = ExamSession::findOrFail($data['exam_session_id']);
        if ($session->status !== 'open' || (Auth::user()->isStudent() && !$session->is_published)) {
            return redirect()->back()->withInput()->withErrors([
                'exam_session_id' => 'รอบสอบนี้ไม่ได้เปิดรับสมัคร',
            ]);
        }

        $classroomId = Auth::user()->classroom_id;
        foreach ($data['registrants'] as $index => $registrant) {
            if (ExamRegistration::where('exam_session_id', $session->id)
                ->where('national_id', $registrant['national_id'])->exists()) {
                return redirect()->back()->withInput()->withErrors([
                    'registrants.' . $index . '.national_id' => 'เลขประจำตัวประชาชนนี้ลงทะเบียนในรอบสอบนี้แล้ว',
                ]);
            }
        }

        DB::transaction(function () use ($data, $session, $classroomId) {
            foreach ($data['registrants'] as $registrant) {
                ExamRegistration::create([
                    'exam_session_id' => $session->id,
                    'classroom_id' => $classroomId,
                    'created_by' => Auth::id(),
                    'exam_center_id' => isset($registrant['exam_center_id']) ? $registrant['exam_center_id'] : null,
                    'national_id' => $registrant['national_id'],
                    'title' => $registrant['title'],
                    'first_name' => $registrant['first_name'],
                    'last_name' => $registrant['last_name'],
                    'birth_date' => $registrant['birth_date'],
                    'education_class' => $registrant['education_class'],
                    'exam_tier' => $registrant['exam_tier'],
                    'previous_certificate_no' => isset($registrant['previous_certificate_no']) ? $registrant['previous_certificate_no'] : null,
                    'remarks' => isset($registrant['remarks']) ? $registrant['remarks'] : null,
                ]);
            }
        });

        return redirect()->route('exam_registration.index', [
            'session_id' => $session->id,
        ])->with('success', 'ลงทะเบียนสอบสำเร็จ ' . count($data['registrants']) . ' คน');
    }

    public function list(Request $request)
    {
        $sessions = ExamSession::orderBy('academic_year', 'desc')->get();
        $session = $request->filled('session_id')
            ? $sessions->firstWhere('id', (int) $request->session_id)
            : $sessions->firstWhere('status', 'open') ?? $sessions->first();

        $query = ExamRegistration::with('examCenter')
            ->where('exam_session_id', $session ? $session->id : 0);

        if (Auth::user()->isTeacher()) {
            // ครูเห็นที่ตัวเองสร้าง หรือตาม classroom
            $query->where(function($q) {
                $q->where('created_by', Auth::id())
                  ->orWhere('classroom_id', Auth::user()->classroom_id);
            });
        } elseif (Auth::user()->isStudent()) {
            $query->where('created_by', Auth::id());
        }

        if ($request->filled('tier')) {
            $query->where('exam_tier', $request->tier);
        }
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['pending', 'registered', 'checked']);
            } elseif ($request->status === 'approved') {
                $query->whereIn('status', ['approved', 'eligible']);
            } else {
                $query->where('status', $request->status);
            }
        }
        if ($request->filled('follow_up_status')) {
            if ($request->follow_up_status === 'none') {
                $query->whereNull('follow_up_status');
            } else {
                $query->where('follow_up_status', $request->follow_up_status);
            }
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                  ->orWhere('last_name', 'like', "%{$keyword}%")
                  ->orWhere('national_id', 'like', "%{$keyword}%");
            });
        }

        $registrations = $query->orderBy('exam_tier')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(50)
            ->appends($request->all());

        return view('teacher.exam_registration.list', compact('sessions', 'session', 'registrations'));
    }

    public function export(Request $request)
    {
        $session = ExamSession::findOrFail($request->session_id);

        $query = ExamRegistration::with('examCenter')
            ->where('exam_session_id', $session->id);

        if (Auth::user()->isTeacher()) {
            $query->where(function($q) {
                $q->where('created_by', Auth::id())
                  ->orWhere('classroom_id', Auth::user()->classroom_id);
            });
        } elseif (Auth::user()->isStudent()) {
            $query->where('created_by', Auth::id());
        }

        if ($request->filled('tier')) {
            $query->where('exam_tier', $request->tier);
        }
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->whereIn('status', ['pending', 'registered', 'checked']);
            } elseif ($request->status === 'approved') {
                $query->whereIn('status', ['approved', 'eligible']);
            } else {
                $query->where('status', $request->status);
            }
        }
        if ($request->filled('follow_up_status')) {
            if ($request->follow_up_status === 'none') {
                $query->whereNull('follow_up_status');
            } else {
                $query->where('follow_up_status', $request->follow_up_status);
            }
        }
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('first_name', 'like', "%{$keyword}%")
                  ->orWhere('last_name', 'like', "%{$keyword}%")
                  ->orWhere('national_id', 'like', "%{$keyword}%");
            });
        }

        $registrations = $query->orderBy('exam_tier')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $format = $request->query('format', 'excel');

        if ($format === 'pdf') {
            return view('teacher.exam_registration.print', compact('session', 'registrations'));
        }

        // Excel (CSV with BOM for Thai)
        $tierLabels = ['tree' => 'ธรรมศึกษาชั้นตรี', 'tho' => 'ธรรมศึกษาชั้นโท', 'ek' => 'ธรรมศึกษาชั้นเอก'];
        $filename = 'registrations_' . $session->academic_year . '_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($registrations, $tierLabels) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($out, [
                'ลำดับ', 'คำนำหน้า', 'ชื่อ', 'นามสกุล', 'เลขประจำตัวประชาชน',
                'ชั้นที่สมัครสอบ', 'ระดับการศึกษา', 'ชั้นเรียน',
                'สนามสอบ', 'สถานะ', 'การติดตาม', 'หมายเหตุติดตาม', 'วันที่สมัคร'
            ]);

            foreach ($registrations as $i => $r) {
                fputcsv($out, [
                    $i + 1,
                    $r->title,
                    $r->first_name,
                    $r->last_name,
                    "'" . $r->national_id,
                    $tierLabels[$r->exam_tier] ?? $r->exam_tier,
                    $r->education_level,
                    $r->education_class,
                    $r->examCenter->name ?? '',
                    ExamRegistration::STATUSES[$r->status] ?? $r->status,
                    $r->follow_up_status,
                    $r->follow_up_note,
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateFollowUp(Request $request, ExamRegistration $registration)
    {
        // Auth check: allow superadmin and teachers
        if (Auth::user()->isStudent() && $registration->created_by != Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'follow_up_status' => 'nullable|string|max:50',
            'follow_up_note' => 'nullable|string|max:1000',
        ]);

        $registration->follow_up_status = $data['follow_up_status'] ?? null;
        $registration->follow_up_note = $data['follow_up_note'] ?? null;
        $registration->save();

        return redirect()->back()->with('success', 'บันทึกการติดตามเรียบร้อยแล้ว');
    }

    public function show(ExamRegistration $registration)
    {
        if (Auth::user()->isTeacher() && $registration->created_by != Auth::id() && $registration->classroom_id != Auth::user()->classroom_id) {
            abort(403);
        }
        return view('teacher.exam_registration.show', compact('registration'));
    }

    public function edit(ExamRegistration $registration)
    {
        if (Auth::user()->isTeacher() && $registration->created_by != Auth::id() && $registration->classroom_id != Auth::user()->classroom_id) {
            abort(403);
        }
        $centers = ExamCenter::orderBy('name')->get();
        return view('teacher.exam_registration.edit', compact('registration', 'centers'));
    }

    public function update(Request $request, ExamRegistration $registration)
    {
        if (Auth::user()->isTeacher() && $registration->created_by != Auth::id() && $registration->classroom_id != Auth::user()->classroom_id) {
            abort(403);
        }

        $data = $request->validate([
            'national_id' => ['required', 'regex:/^[0-9]{13}$/'],
            'title' => 'required|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'required|date|before:today',
            'education_class' => 'required|string|max:50',
            'exam_tier' => 'required|in:tree,tho,ek',
            'previous_certificate_no' => 'nullable|string|max:100',
            'exam_center_id' => 'nullable|exists:exam_centers,id',
            'remarks' => 'nullable|string|max:1000',
            'status' => 'required|string|max:50',
        ]);

        $registration->update($data);

        return redirect()->route('exam_registration.list')->with('success', 'บันทึกข้อมูลผู้สมัครเรียบร้อยแล้ว');
    }

    public function destroy(ExamRegistration $registration)
    {
        if (Auth::user()->isTeacher() && $registration->created_by != Auth::id() && $registration->classroom_id != Auth::user()->classroom_id) {
            abort(403);
        }
        $registration->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลผู้สมัครเรียบร้อยแล้ว');
    }
}
