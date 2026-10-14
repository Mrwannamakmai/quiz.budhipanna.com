<?php

namespace App\Http\Controllers\Teacher;

use App\Course;
use App\EssayCriterion;
use App\Question;
use App\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EssayCriteriaController extends Controller
{
    private function allowedSubjectIds()
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return Subject::pluck('id');
        }
        $courseIds = Course::where('user_id', $user->id)->pluck('id');
        return Subject::whereIn('course_id', $courseIds)->pluck('id');
    }

    private function authorizeQuestion(Question $question)
    {
        abort_unless(
            Auth::user()->isSuperAdmin()
                || $question->subject_id === null
                || $this->allowedSubjectIds()->contains($question->subject_id),
            403
        );
        abort_unless($question->type === 'essay', 403);
    }

    public function index(Question $question)
    {
        $this->authorizeQuestion($question);

        $criteria = $question->essayCriteria()->get();
        $totalMaxPoints = $criteria->sum('max_points');

        return view('teacher.essay_criteria.index', compact('question', 'criteria', 'totalMaxPoints'));
    }

    public function store(Request $request, Question $question)
    {
        $this->authorizeQuestion($question);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_points'  => 'required|numeric|min:0.5',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data['question_id'] = $question->id;
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = $question->essayCriteria()->count();
        }

        EssayCriterion::create($data);

        return back()->with('success', 'เพิ่มเกณฑ์การให้คะแนนสำเร็จ');
    }

    public function update(Request $request, Question $question, EssayCriterion $criterion)
    {
        $this->authorizeQuestion($question);
        abort_unless((int) $criterion->question_id === (int) $question->id, 403);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_points'  => 'required|numeric|min:0.5',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $criterion->update($data);

        return back()->with('success', 'แก้ไขเกณฑ์การให้คะแนนสำเร็จ');
    }

    public function destroy(Question $question, EssayCriterion $criterion)
    {
        $this->authorizeQuestion($question);
        abort_unless((int) $criterion->question_id === (int) $question->id, 403);

        if ($criterion->scores()->count() > 0) {
            return back()->withErrors(['error' => 'ไม่สามารถลบเกณฑ์นี้ได้ เนื่องจากมีการให้คะแนนไปแล้ว']);
        }

        $criterion->delete();

        return back()->with('success', 'ลบเกณฑ์การให้คะแนนสำเร็จ');
    }

    public function bulkStore(Request $request, Question $question)
    {
        $this->authorizeQuestion($question);

        $data = $request->validate([
            'criteria'              => 'required|array|min:1',
            'criteria.*.name'       => 'required|string|max:255',
            'criteria.*.description'=> 'nullable|string',
            'criteria.*.max_points' => 'required|numeric|min:0.5',
        ]);

        $existingCount = $question->essayCriteria()->count();
        foreach ($data['criteria'] as $i => $item) {
            EssayCriterion::create([
                'question_id' => $question->id,
                'name'        => $item['name'],
                'description' => $item['description'] ?? null,
                'max_points'  => $item['max_points'],
                'sort_order'  => $existingCount + $i,
            ]);
        }

        return back()->with('success', 'เพิ่มเกณฑ์การให้คะแนน ' . count($data['criteria']) . ' รายการสำเร็จ');
    }
}
