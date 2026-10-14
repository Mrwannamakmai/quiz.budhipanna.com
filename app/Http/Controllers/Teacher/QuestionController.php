<?php

namespace App\Http\Controllers\Teacher;

use App\Course;
use App\ExamSet;
use App\Question;
use App\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    private function teacherExamSetIds()
    {
        if (Auth::user()->isSuperAdmin()) {
            return ExamSet::pluck('id');
        }
        $courseIds  = Course::where('user_id', Auth::id())->pluck('id');
        $subjectIds = Subject::whereIn('course_id', $courseIds)->pluck('id');
        return ExamSet::whereIn('subject_id', $subjectIds)->pluck('id');
    }

    public function index(ExamSet $examSet)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);
        $questions = Question::where('exam_set_id', $examSet->id)
            ->orderBy('sort_order')
            ->paginate(30);
        return view('teacher.questions.index', compact('examSet', 'questions'));
    }

    public function create(ExamSet $examSet)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);
        return view('teacher.questions.create', compact('examSet'));
    }

    public function store(Request $request, ExamSet $examSet)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);

        $data = $request->validate([
            'type'           => 'required|in:multiple_choice,essay',
            'dimension'      => 'nullable|in:kaya,conduct,citta,panna',
            'question_text'  => 'required|string',
            'option_a'       => 'nullable|string|max:500',
            'option_b'       => 'nullable|string|max:500',
            'option_c'       => 'nullable|string|max:500',
            'option_d'       => 'nullable|string|max:500',
            'correct_answer' => 'nullable|in:a,b,c,d',
            'essay_answer'   => 'nullable|string',
            'points'         => 'required|integer|min:1',
            'sort_order'     => 'nullable|integer',
        ]);

        $data['exam_set_id'] = $examSet->id;
        Question::create($data);

        if ($request->has('add_more')) {
            return redirect()->route('teacher.exam_sets.questions.create', $examSet)
                ->with('success', 'เพิ่มคำถามสำเร็จ เพิ่มต่อได้เลย');
        }

        return redirect()->route('teacher.exam_sets.questions.index', $examSet)
            ->with('success', 'เพิ่มคำถามสำเร็จ');
    }

    public function edit(ExamSet $examSet, Question $question)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);
        abort_unless($question->exam_set_id == $examSet->id, 403);
        return view('teacher.questions.edit', compact('examSet', 'question'));
    }

    public function update(Request $request, ExamSet $examSet, Question $question)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);
        abort_unless($question->exam_set_id == $examSet->id, 403);

        $data = $request->validate([
            'type'           => 'required|in:multiple_choice,essay',
            'dimension'      => 'nullable|in:kaya,conduct,citta,panna',
            'question_text'  => 'required|string',
            'option_a'       => 'nullable|string|max:500',
            'option_b'       => 'nullable|string|max:500',
            'option_c'       => 'nullable|string|max:500',
            'option_d'       => 'nullable|string|max:500',
            'correct_answer' => 'nullable|in:a,b,c,d',
            'essay_answer'   => 'nullable|string',
            'points'         => 'required|integer|min:1',
            'sort_order'     => 'nullable|integer',
        ]);

        $question->update($data);
        return redirect()->route('teacher.exam_sets.questions.index', $examSet)
            ->with('success', 'แก้ไขคำถามสำเร็จ');
    }

    public function destroy(ExamSet $examSet, Question $question)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);
        abort_unless($question->exam_set_id == $examSet->id, 403);
        $question->delete();
        return redirect()->route('teacher.exam_sets.questions.index', $examSet)
            ->with('success', 'ลบคำถามสำเร็จ');
    }

    public function reorder(Request $request, ExamSet $examSet)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

        foreach ($request->ids as $order => $id) {
            Question::where('id', $id)->where('exam_set_id', $examSet->id)
                ->update(['sort_order' => $order]);
        }
        return response()->json(['success' => true]);
    }

    public function importForm(ExamSet $examSet)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);
        return view('teacher.questions.import', compact('examSet'));
    }

    public function import(Request $request, ExamSet $examSet)
    {
        abort_unless(in_array($examSet->id, $this->teacherExamSetIds()->toArray()), 403);
        $request->validate(['json_data' => 'required|string']);

        $rows = json_decode($request->json_data, true);

        if (!is_array($rows)) {
            return back()->withErrors(['json_data' => 'รูปแบบ JSON ไม่ถูกต้อง (ต้องเป็น array)'])
                         ->withInput();
        }

        $inserted = 0;
        foreach ($rows as $row) {
            Question::create(array_merge($row, ['exam_set_id' => $examSet->id]));
            $inserted++;
        }

        return redirect()->route('teacher.exam_sets.questions.index', $examSet)
            ->with('success', "นำเข้าคำถาม {$inserted} ข้อสำเร็จ");
    }
}
