<?php

namespace App\Http\Controllers\Teacher;

use App\Course;
use App\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /** ID ของ courses ที่ user นี้มีสิทธิ์จัดการ */
    private function allowedCourseIds()
    {
        if (Auth::user()->isSuperAdmin()) {
            return Course::pluck('id');
        }
        return Course::where('user_id', Auth::id())->pluck('id');
    }

    public function index()
    {
        $subjects = Subject::whereIn('course_id', $this->allowedCourseIds())
            ->with('course', 'examSets')
            ->withCount(['questions', 'examSets'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categories = ['กระทู้', 'ธรรมะ', 'พุทธะ', 'วินัย', 'ศาสนพิธี'];
        $grouped = $subjects->groupBy(function ($s) {
            return $s->category ?? 'ไม่ระบุหมวด';
        });

        $stats = [
            'total'      => $subjects->count(),
            'questions'  => $subjects->sum('questions_count'),
            'exam_sets'  => $subjects->sum('exam_sets_count'),
        ];

        return view('teacher.subjects.index', compact('grouped', 'categories', 'stats'));
    }

    public function create()
    {
        $courses = Auth::user()->isSuperAdmin()
            ? Course::orderBy('name')->get()
            : Course::where('user_id', Auth::id())->orderBy('name')->get();

        return view('teacher.subjects.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'category'    => 'nullable|in:กระทู้,ธรรมะ,พุทธะ,วินัย,ศาสนพิธี',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        abort_unless(in_array($data['course_id'], $this->allowedCourseIds()->toArray()), 403);

        Subject::create($data);
        return redirect()->route('teacher.subjects.index')->with('success', 'สร้างหมวดวิชาสำเร็จ');
    }

    public function edit(Subject $subject)
    {
        abort_unless(in_array($subject->course_id, $this->allowedCourseIds()->toArray()), 403);

        $courses = Auth::user()->isSuperAdmin()
            ? Course::orderBy('name')->get()
            : Course::where('user_id', Auth::id())->orderBy('name')->get();

        return view('teacher.subjects.edit', compact('subject', 'courses'));
    }

    public function update(Request $request, Subject $subject)
    {
        abort_unless(in_array($subject->course_id, $this->allowedCourseIds()->toArray()), 403);

        $data = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'category'    => 'nullable|in:กระทู้,ธรรมะ,พุทธะ,วินัย,ศาสนพิธี',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subject->update($data);
        return redirect()->route('teacher.subjects.index')->with('success', 'แก้ไขหมวดวิชาสำเร็จ');
    }

    public function destroy(Subject $subject)
    {
        abort_unless(in_array($subject->course_id, $this->allowedCourseIds()->toArray()), 403);
        $subject->delete();
        return redirect()->route('teacher.subjects.index')->with('success', 'ลบหมวดวิชาสำเร็จ');
    }
}
