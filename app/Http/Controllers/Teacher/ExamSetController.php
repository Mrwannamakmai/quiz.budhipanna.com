<?php

namespace App\Http\Controllers\Teacher;

use App\Classroom;
use App\Course;
use App\ExamSet;
use App\Subject;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamSetController extends Controller
{
    /** ID ของ courses ที่ user นี้มีสิทธิ์จัดการ */
    private function allowedCourseIds()
    {
        if (Auth::user()->isSuperAdmin()) {
            return Course::pluck('id');
        }
        return Course::where('user_id', Auth::id())->pluck('id');
    }

    private function teacherSubjectIds()
    {
        return Subject::whereIn('course_id', $this->allowedCourseIds())->pluck('id');
    }

    private function canManage(ExamSet $examSet): bool
    {
        if (is_null($examSet->subject_id)) return true;
        return in_array($examSet->subject_id, $this->teacherSubjectIds()->toArray());
    }

    public function index()
    {
        $examSets = ExamSet::where(function ($q) {
                $q->whereIn('subject_id', $this->teacherSubjectIds())
                  ->orWhereNull('subject_id');
            })
            ->with(['subject.course', 'classroom'])
            ->withCount(['questions', 'directQuestions', 'attempts'])
            ->latest()
            ->get();

        $categories = ['กระทู้', 'ธรรมะ', 'พุทธะ', 'วินัย', 'ศาสนพิธี'];

        $grouped = $examSets->groupBy(function ($es) {
            return $es->subject->category ?? 'ไม่ระบุหมวด';
        });

        $stats = [
            'total'    => $examSets->count(),
            'active'   => $examSets->where('is_active', true)->count(),
            'inactive' => $examSets->where('is_active', false)->count(),
            'public'   => $examSets->where('is_public', true)->count(),
        ];

        return view('teacher.exam_sets.index', compact('grouped', 'categories', 'stats'));
    }

    public function create()
    {
        $subjects   = Subject::whereIn('course_id', $this->allowedCourseIds())
                        ->orderBy('sort_order')->orderBy('name')->get();
        $classrooms = Classroom::active()->get();
        return view('teacher.exam_sets.create', compact('subjects', 'classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id'       => 'nullable|exists:subjects,id',
            'classroom_id'     => 'nullable|exists:classrooms,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'time_limit'       => 'nullable|integer|min:1',
            'pass_percentage'  => 'required|integer|min:1|max:100',
            'max_attempts'     => 'required|integer|min:1',
            'pool_size'        => 'nullable|integer|min:1',
            'available_from'   => 'nullable|date',
            'available_until'  => 'nullable|date|after_or_equal:available_from',
        ]);

        $data['shuffle_questions'] = (bool)$request->input('shuffle_questions', 0);
        $data['pool_enabled']      = (bool)$request->input('pool_enabled', 0);
        $data['is_active']         = (bool)$request->input('is_active', 0);
        $data['is_public']         = (bool)$request->input('is_public', 0);
        $data['available_from']    = !empty($data['available_from'])  ? Carbon::parse($data['available_from'])  : null;
        $data['available_until']   = !empty($data['available_until']) ? Carbon::parse($data['available_until']) : null;

        ExamSet::create($data);
        return redirect()->route('teacher.exam_sets.index')->with('success', 'สร้างชุดข้อสอบสำเร็จ');
    }

    public function edit(ExamSet $examSet)
    {
        abort_unless($this->canManage($examSet), 403);
        $subjects   = Subject::whereIn('course_id', $this->allowedCourseIds())
                        ->orderBy('sort_order')->orderBy('name')->get();
        $classrooms = Classroom::active()->get();
        return view('teacher.exam_sets.edit', compact('examSet', 'subjects', 'classrooms'));
    }

    public function update(Request $request, ExamSet $examSet)
    {
        abort_unless($this->canManage($examSet), 403);

        $data = $request->validate([
            'subject_id'      => 'nullable|exists:subjects,id',
            'classroom_id'    => 'nullable|exists:classrooms,id',
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'time_limit'      => 'nullable|integer|min:1',
            'pass_percentage' => 'required|integer|min:1|max:100',
            'max_attempts'    => 'required|integer|min:1',
            'pool_size'       => 'nullable|integer|min:1',
            'available_from'  => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
        ]);

        $data['shuffle_questions'] = (bool)$request->input('shuffle_questions', 0);
        $data['pool_enabled']      = (bool)$request->input('pool_enabled', 0);
        $data['is_active']         = (bool)$request->input('is_active', 0);
        $data['is_public']         = (bool)$request->input('is_public', 0);
        $data['available_from']    = !empty($data['available_from'])  ? Carbon::parse($data['available_from'])  : null;
        $data['available_until']   = !empty($data['available_until']) ? Carbon::parse($data['available_until']) : null;

        $examSet->update($data);
        return redirect()->route('teacher.exam_sets.index')->with('success', 'แก้ไขชุดข้อสอบสำเร็จ');
    }

    public function destroy(ExamSet $examSet)
    {
        abort_unless($this->canManage($examSet), 403);
        $examSet->delete();
        return redirect()->route('teacher.exam_sets.index')->with('success', 'ลบชุดข้อสอบสำเร็จ');
    }

    public function toggleActive(ExamSet $examSet)
    {
        abort_unless($this->canManage($examSet), 403);
        $examSet->update(['is_active' => !$examSet->is_active]);
        return back()->with('success', 'เปลี่ยนสถานะสำเร็จ');
    }
}
