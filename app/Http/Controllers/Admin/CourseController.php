<?php

namespace App\Http\Controllers\Admin;

use App\Classroom;
use App\Course;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['classroom', 'teacher'])->latest()->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $classrooms = Classroom::active()->get();
        $teachers   = User::teachers()->get();
        return view('admin.courses.create', compact('classrooms', 'teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id'      => 'required|exists:users,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'is_active'    => 'boolean',
        ]);

        Course::create($data);
        return redirect()->route('admin.courses.index')->with('success', 'สร้างวิชาสำเร็จ');
    }

    public function edit(Course $course)
    {
        $classrooms = Classroom::active()->get();
        $teachers   = User::teachers()->get();
        return view('admin.courses.edit', compact('course', 'classrooms', 'teachers'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id'      => 'required|exists:users,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'is_active'    => 'boolean',
        ]);

        $course->update($data);
        return redirect()->route('admin.courses.index')->with('success', 'แก้ไขวิชาสำเร็จ');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'ลบวิชาสำเร็จ');
    }
}
