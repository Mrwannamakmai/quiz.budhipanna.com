<?php

namespace App\Modules\Attendance\Http\Controllers;

use App\Classroom;
use App\Modules\Attendance\Models\AttendanceRecord;
use App\Modules\Attendance\Models\AttendanceSession;
use App\Modules\Attendance\Support\Attendance;
use App\Support\Settings\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceReportController extends BaseController
{
    public function index(Request $request)
    {
        $data = $this->build($request);
        return view('attendance::teacher.report', $data);
    }

    public function export(Request $request)
    {
        $d = $this->build($request);
        abort_unless($d['classroom'], 404);

        $filename = 'attendance_' . $d['classroom']->id . '_' . $d['from'] . '_' . $d['to'] . '.csv';

        return response()->streamDownload(function () use ($d) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM ให้ Excel อ่านภาษาไทยได้
            fputcsv($out, [Settings::get('general.school_name') . ' — รายงานเวลาเรียน ' . $d['classroom']->name . ' (' . $d['from'] . ' ถึง ' . $d['to'] . ')']);

            $head = ['ลำดับ', 'ชื่อ-สกุล'];
            foreach ($d['sessions'] as $s) {
                $head[] = $s->session_date->format('d/m') . ($s->start_time ? ' ' . substr($s->start_time, 0, 5) : '') . ' ' . $s->label();
            }
            $head = array_merge($head, ['มา', 'สาย', 'ลา', 'ขาด', 'ร้อยละ']);
            fputcsv($out, $head);

            foreach ($d['students'] as $i => $st) {
                $row = [$i + 1, $st->name];
                foreach ($d['sessions'] as $s) {
                    $status = $d['matrix'][$st->id][$s->id] ?? null;
                    $row[] = $status ? Attendance::status($status)['label'] : '';
                }
                $sum = $d['summaries'][$st->id];
                $row = array_merge($row, [$sum['present'], $sum['late'], $sum['leave'], $sum['absent'], $sum['percent'] === null ? '' : $sum['percent']]);
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    protected function build(Request $request)
    {
        $user       = Auth::user();
        $classrooms = Attendance::classroomsFor($user);
        $allowed    = $classrooms->pluck('id')->map(function ($id) { return (int) $id; })->all();

        $classroomId = (int) $request->query('classroom_id', $allowed[0] ?? 0);
        if (!in_array($classroomId, $allowed, true)) {
            $classroomId = $allowed[0] ?? 0;
        }
        $now  = Attendance::now();
        $from = $this->date($request->query('from'), $now->copy()->startOfMonth()->format('Y-m-d'));
        $to   = $this->date($request->query('to'), $now->format('Y-m-d'));
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }
        $subjectId = (int) $request->query('subject_id') ?: null;

        $classroom = $classroomId ? Classroom::find($classroomId) : null;
        $sessions  = collect();
        $students  = collect();
        $matrix    = [];
        $summaries = [];

        if ($classroom) {
            $q = AttendanceSession::with('subject')
                ->where('classroom_id', $classroomId)
                ->whereBetween('session_date', [$from, $to])
                ->orderBy('session_date')->orderBy('start_time')->orderBy('id');
            if ($subjectId) $q->where('subject_id', $subjectId);
            $sessions = $q->get();
            $students = Attendance::studentsOf($classroomId);

            $records = AttendanceRecord::whereIn('attendance_session_id', $sessions->pluck('id')->all() ?: [0])->get();
            foreach ($records as $r) {
                $matrix[$r->user_id][$r->attendance_session_id] = $r->status;
            }
            foreach ($students as $st) {
                $summaries[$st->id] = Attendance::summarize($matrix[$st->id] ?? []);
            }
        }

        $subjects = Attendance::subjectsFor($user);

        return [
            'classrooms' => $classrooms,
            'classroom'  => $classroom,
            'subjects'   => $subjects,
            'subjectId'  => $subjectId,
            'from'       => $from,
            'to'         => $to,
            'sessions'   => $sessions,
            'students'   => $students,
            'matrix'     => $matrix,
            'summaries'  => $summaries,
            'minPercent' => Attendance::minPercent(),
            'statuses'   => Attendance::statuses(),
        ];
    }

    protected function date($value, $fallback)
    {
        return is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) && strtotime($value) ? $value : $fallback;
    }
}
