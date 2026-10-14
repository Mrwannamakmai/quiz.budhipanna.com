<?php

namespace App\Modules\Attendance\Http\Controllers;

use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Modules\Attendance\Support\Attendance;
use App\Support\Settings\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceController extends BaseController
{
    public function index(Request $request)
    {
        $user       = Auth::user();
        $classrooms = Attendance::classroomsFor($user);
        $allowedIds = $classrooms->pluck('id')->map(function ($id) { return (int) $id; })->all();

        $classroomId = (int) $request->query('classroom_id');
        $month       = $request->query('month', Attendance::now()->format('Y-m'));
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = Attendance::now()->format('Y-m');
        }
        $from = $month . '-01';
        $to   = date('Y-m-t', strtotime($from));

        $query = AttendanceSession::with(['classroom', 'subject'])
            ->whereIn('classroom_id', $allowedIds ?: [0])
            ->whereBetween('session_date', [$from, $to])
            ->orderBy('session_date', 'desc')->orderBy('start_time', 'desc')->orderBy('id', 'desc');
        if ($classroomId && in_array($classroomId, $allowedIds, true)) {
            $query->where('classroom_id', $classroomId);
        }
        $sessions = $query->get();

        // นับสถานะต่อคาบ
        $counts = AttendanceRecord::select('attendance_session_id', 'status', DB::raw('COUNT(*) as c'))
            ->whereIn('attendance_session_id', $sessions->pluck('id')->all() ?: [0])
            ->groupBy('attendance_session_id', 'status')->get()
            ->groupBy('attendance_session_id');
        $studentCounts = \App\User::where('role', 'student')
            ->whereIn('classroom_id', $sessions->pluck('classroom_id')->unique()->all() ?: [0])
            ->select('classroom_id', DB::raw('COUNT(*) as c'))->groupBy('classroom_id')
            ->pluck('c', 'classroom_id');

        return view('attendance::teacher.index', compact(
            'sessions', 'classrooms', 'classroomId', 'month', 'counts', 'studentCounts'
        ));
    }

    public function create(Request $request)
    {
        $user       = Auth::user();
        $classrooms = Attendance::classroomsFor($user);
        $subjects   = Attendance::subjectsFor($user);
        $now        = Attendance::now();

        return view('attendance::teacher.create', [
            'classrooms'  => $classrooms,
            'subjects'    => $subjects,
            'classroomId' => (int) $request->query('classroom_id'),
            'today'       => $now->format('Y-m-d'),
            'nowTime'     => $now->format('H:i'),
        ]);
    }

    public function store(Request $request)
    {
        $user    = Auth::user();
        $allowed = Attendance::classroomIdsFor($user);

        $data = $request->validate([
            'classroom_id' => 'required|integer|in:' . implode(',', $allowed ?: [0]),
            'subject_id'   => 'nullable|integer|exists:subjects,id',
            'session_date' => 'required|date_format:Y-m-d',
            'start_time'   => 'nullable|date_format:H:i',
            'title'        => 'nullable|string|max:150',
            'note'         => 'nullable|string|max:1000',
        ], [
            'classroom_id.in' => 'คุณไม่มีสิทธิ์เช็คชื่อห้องนี้',
        ]);
        $data['created_by'] = $user->id;

        $session = AttendanceSession::create($data);

        if ($request->input('open_checkin') && Settings::get('attendance.self_checkin', true)) {
            $this->openFor($session);
        }

        return redirect()->route('attendance.teacher.sessions.show', $session)
            ->with('success', 'สร้างคาบเรียนแล้ว เริ่มเช็คชื่อได้เลย');
    }

    public function show(AttendanceSession $session)
    {
        abort_unless(Attendance::canManage(Auth::user(), $session), 403);
        $session->load(['classroom', 'subject', 'creator']);

        $students = Attendance::studentsOf($session->classroom_id);
        $records  = $session->records()->get()->keyBy('user_id');
        $summary  = Attendance::summarize($records->pluck('status'));

        return view('attendance::teacher.show', [
            'session'       => $session,
            'students'      => $students,
            'records'       => $records,
            'summary'       => $summary,
            'statuses'      => Attendance::statuses(),
            'defaultStatus' => Settings::get('attendance.default_status', 'present'),
            'selfCheckin'   => (bool) Settings::get('attendance.self_checkin', true),
        ]);
    }

    public function saveMarks(Request $request, AttendanceSession $session)
    {
        $user = Auth::user();
        abort_unless(Attendance::canManage($user, $session), 403);

        $request->validate([
            'status'   => 'nullable|array',
            'status.*' => 'nullable|in:' . implode(',', array_keys(Attendance::statuses())),
            'note'     => 'nullable|array',
            'note.*'   => 'nullable|string|max:255',
        ], [
            'status.*.in' => 'สถานะการเช็คชื่อไม่ถูกต้อง',
            'note.*.max'  => 'หมายเหตุยาวเกิน 255 ตัวอักษร',
        ]);

        $studentIds = Attendance::studentsOf($session->classroom_id)->pluck('id')->map(function ($id) { return (int) $id; })->all();
        $statuses   = (array) $request->input('status', []);
        $notes      = (array) $request->input('note', []);
        $existing   = $session->records()->get()->keyBy('user_id');

        DB::transaction(function () use ($studentIds, $statuses, $notes, $existing, $session, $user) {
            foreach ($studentIds as $sid) {
                $status = $statuses[$sid] ?? null;
                $note   = isset($notes[$sid]) && trim($notes[$sid]) !== '' ? trim($notes[$sid]) : null;
                $record = $existing->get($sid);

                if (!$status) {
                    // ไม่ได้เลือกสถานะ: ถ้าเคยมีที่ครูบันทึกไว้ให้ลบ, ของที่นักเรียนเช็คเองเก็บไว้
                    if ($record && $record->method === 'teacher') {
                        $record->delete();
                    }
                    continue;
                }

                if ($record) {
                    if ($record->status !== $status || $record->note !== $note) {
                        $record->update(['status' => $status, 'note' => $note, 'recorded_by' => $user->id]);
                    }
                } else {
                    AttendanceRecord::create([
                        'attendance_session_id' => $session->id,
                        'user_id'               => $sid,
                        'status'                => $status,
                        'method'                => 'teacher',
                        'note'                  => $note,
                        'recorded_by'           => $user->id,
                    ]);
                }
            }
        });

        return redirect()->route('attendance.teacher.sessions.show', $session)->with('success', 'บันทึกการเช็คชื่อเรียบร้อย');
    }

    public function destroy(AttendanceSession $session)
    {
        abort_unless(Attendance::canManage(Auth::user(), $session), 403);
        $session->records()->delete();
        $session->delete();

        return redirect()->route('attendance.teacher.index')->with('success', 'ลบคาบเรียนแล้ว');
    }

    public function openCheckin(AttendanceSession $session)
    {
        abort_unless(Attendance::canManage(Auth::user(), $session), 403);
        abort_unless(Settings::get('attendance.self_checkin', true), 403, 'ปิดการเช็คชื่อด้วยตนเองไว้ในหน้าตั้งค่า');
        $this->openFor($session);

        return back()->with('success', 'เปิดรับรหัสเช็คชื่อแล้ว: ' . $session->checkin_code);
    }

    public function closeCheckin(AttendanceSession $session)
    {
        abort_unless(Attendance::canManage(Auth::user(), $session), 403);
        $session->update(['checkin_closes_at' => now()]);

        return back()->with('success', 'ปิดรับรหัสเช็คชื่อแล้ว');
    }

    protected function openFor(AttendanceSession $session)
    {
        $minutes = max(1, (int) Settings::get('attendance.checkin_window', 15));
        $session->update([
            'checkin_code'      => Attendance::generateCode(),
            'checkin_opened_at' => $session->checkin_opened_at ?: now(),
            'checkin_closes_at' => now()->addMinutes($minutes),
        ]);
    }
}
