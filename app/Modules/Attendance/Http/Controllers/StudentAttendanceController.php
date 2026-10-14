<?php

namespace App\Modules\Attendance\Http\Controllers;

use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Modules\Attendance\Support\Attendance;
use App\Support\Settings\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends BaseController
{
    public function index()
    {
        $user = Auth::user();

        $records = AttendanceRecord::with(['session.subject', 'session.classroom'])
            ->where('user_id', $user->id)
            ->join('attendance_sessions', 'attendance_sessions.id', '=', 'attendance_records.attendance_session_id')
            ->orderBy('attendance_sessions.session_date', 'desc')
            ->orderBy('attendance_sessions.start_time', 'desc')
            ->select('attendance_records.*')
            ->limit(200)
            ->get();

        // คาบของห้องตัวเองที่ยังไม่มีบันทึก (นับเป็น "ยังไม่เช็ค" ไม่นำมาคิด %)
        $summary = Attendance::summarize($records->pluck('status'));

        return view('attendance::student.index', [
            'user'        => $user,
            'records'     => $records,
            'summary'     => $summary,
            'minPercent'  => Attendance::minPercent(),
            'selfCheckin' => (bool) Settings::get('attendance.self_checkin', true),
        ]);
    }

    public function checkIn(Request $request)
    {
        abort_unless(Settings::get('attendance.self_checkin', true), 403);
        $user = Auth::user();

        $request->validate(['code' => 'required|string|max:12'], ['code.required' => 'กรุณากรอกรหัสเช็คชื่อ']);
        $code = strtoupper(preg_replace('/\s+/', '', $request->input('code')));

        $session = AttendanceSession::where('checkin_code', $code)
            ->where('checkin_closes_at', '>', now())
            ->where('classroom_id', $user->classroom_id)
            ->first();

        if (!$session) {
            return back()->withErrors(['code' => 'รหัสไม่ถูกต้อง หมดเวลาแล้ว หรือไม่ใช่คาบของห้องคุณ'])->withInput();
        }

        $record = AttendanceRecord::where('attendance_session_id', $session->id)->where('user_id', $user->id)->first();
        if ($record) {
            return redirect()->route('attendance.student.index')
                ->with('success', 'คุณเช็คชื่อคาบนี้แล้ว (' . Attendance::status($record->status)['label'] . ')');
        }

        $status = Attendance::selfStatus($session);
        AttendanceRecord::create([
            'attendance_session_id' => $session->id,
            'user_id'               => $user->id,
            'status'                => $status,
            'method'                => 'self',
            'checked_in_at'         => now(),
        ]);

        return redirect()->route('attendance.student.index')
            ->with('success', 'เช็คชื่อสำเร็จ: ' . $session->label() . ' — ' . Attendance::status($status)['label']);
    }
}
