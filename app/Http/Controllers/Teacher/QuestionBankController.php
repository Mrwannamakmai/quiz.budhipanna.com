<?php

namespace App\Http\Controllers\Teacher;

use App\Course;
use App\ExamSet;
use App\Question;
use App\Subject;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionBankController extends Controller
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

    /** superadmin ใช้ได้ทุกหมวด / ครูใช้ได้เฉพาะหมวดของวิชาตัวเอง */
    private function canUseSubject($subjectId)
    {
        return Auth::user()->isSuperAdmin() || $this->allowedSubjectIds()->contains($subjectId);
    }

    /** ข้อสอบที่ไม่ระบุหมวด (subject_id ว่าง) ถือเป็นข้อสอบกลาง — ครูทุกคนจัดการได้ เหมือนหน้าแก้ไข */
    private function canUseQuestion(Question $question)
    {
        return Auth::user()->isSuperAdmin()
            || $question->subject_id === null
            || $this->allowedSubjectIds()->contains($question->subject_id);
    }

    public function index(Request $request)
    {
        $subjectIds = $this->allowedSubjectIds();
        $subjects   = Subject::whereIn('id', $subjectIds)->with('course')->orderBy('name')->get();

        $scopeFilter = function ($q) use ($subjectIds) {
            $q->whereIn('subject_id', $subjectIds)->orWhereNull('subject_id');
        };

        $query = Question::where($scopeFilter)
                         ->with(['subject.course', 'examSets', 'essayCriteria']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('category')) {
            $query->whereHas('subject', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('search')) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->orderBy('updated_at', 'desc')->paginate(20)->appends($request->query());

        $stats = [
            'total'    => Question::where($scopeFilter)->count(),
            'active'   => Question::where($scopeFilter)->where('is_active', true)->count(),
            'inactive' => Question::where($scopeFilter)->where('is_active', false)->count(),
            'mc'       => Question::where($scopeFilter)->where('type', 'multiple_choice')->count(),
            'essay'    => Question::where($scopeFilter)->where('type', 'essay')->count(),
        ];

        $categories = ['กระทู้', 'ธรรมะ', 'พุทธะ', 'วินัย', 'ศาสนพิธี'];

        $examSets = ExamSet::where(function ($q) use ($subjectIds) {
                $q->whereIn('subject_id', $subjectIds)->orWhereNull('subject_id');
            })
            ->with('subject')
            ->orderBy('name')
            ->get();

        $groupedExamSets = $examSets->groupBy(function ($es) {
            return $es->subject->category ?? 'ไม่ระบุหมวด';
        });

        return view('teacher.question_bank.index', compact('questions', 'subjects', 'stats', 'categories', 'examSets', 'groupedExamSets'));
    }

    public function create()
    {
        $subjectIds = $this->allowedSubjectIds();
        $subjects   = Subject::whereIn('id', $subjectIds)->with('course')->orderBy('name')->get();
        $categories = ['กระทู้', 'ธรรมะ', 'พุทธะ', 'วินัย', 'ศาสนพิธี'];

        return view('teacher.question_bank.create', compact('subjects', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id'     => 'required|exists:subjects,id',
            'type'           => 'required|in:multiple_choice,essay',
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

        abort_unless($this->canUseSubject($data['subject_id']), 403);

        $data['is_active'] = $request->has('is_active');
        Question::create($data);

        if ($request->has('add_more')) {
            return redirect()->route('teacher.question_bank.create')
                ->with('success', 'เพิ่มข้อสอบในคลังสำเร็จ เพิ่มต่อได้เลย');
        }

        return redirect()->route('teacher.question_bank.index')
            ->with('success', 'เพิ่มข้อสอบในคลังสำเร็จ');
    }

    public function edit(Question $question)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort_unless(
                $question->subject_id === null || $this->allowedSubjectIds()->contains($question->subject_id),
                403
            );
        }

        $subjectIds = $this->allowedSubjectIds();
        $subjects   = Subject::whereIn('id', $subjectIds)->with('course')->orderBy('name')->get();
        $categories = ['กระทู้', 'ธรรมะ', 'พุทธะ', 'วินัย', 'ศาสนพิธี'];

        $examSets = $question->examSets()->get();

        return view('teacher.question_bank.edit', compact('question', 'subjects', 'categories', 'examSets'));
    }

    public function update(Request $request, Question $question)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort_unless(
                $question->subject_id === null || $this->allowedSubjectIds()->contains($question->subject_id),
                403
            );
        }

        $data = $request->validate([
            'subject_id'     => 'required|exists:subjects,id',
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

        if (!Auth::user()->isSuperAdmin()) {
            abort_unless($this->canUseSubject($data['subject_id']), 403);
        }

        $data['is_active'] = $request->has('is_active');
        $question->update($data);

        return redirect()->route('teacher.question_bank.index')
            ->with('success', 'แก้ไขข้อสอบในคลังสำเร็จ');
    }

    public function destroy(Question $question)
    {
        abort_unless($this->canUseQuestion($question), 403);

        if ($question->examSets()->count() > 0 || $question->studentAnswers()->count() > 0) {
            return back()->withErrors(['error' => 'ไม่สามารถลบได้ เนื่องจากข้อสอบนี้ถูกใช้งานอยู่ในชุดข้อสอบหรือมีคำตอบนักเรียนแล้ว']);
        }

        $question->delete();
        return redirect()->route('teacher.question_bank.index')
            ->with('success', 'ลบข้อสอบออกจากคลังสำเร็จ');
    }

    public function toggleActive(Question $question)
    {
        abort_unless($this->canUseQuestion($question), 403);

        $question->update(['is_active' => !$question->is_active]);
        $status = $question->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน';

        return back()->with('success', "เปลี่ยนสถานะข้อสอบเป็น {$status} สำเร็จ");
    }

    public function assignForm(Question $question)
    {
        abort_unless($this->canUseQuestion($question), 403);

        $subjectIds = $this->allowedSubjectIds();
        $examSets   = ExamSet::whereIn('subject_id', $subjectIds)
            ->with('subject')
            ->withCount('directQuestions')
            ->orderBy('name')
            ->get();
        $assigned   = $question->examSets()->pluck('exam_sets.id')->toArray();

        $grouped = $examSets->groupBy(function ($es) {
            return $es->subject->category ?? 'ไม่ระบุหมวด';
        });

        return view('teacher.question_bank.assign', compact('question', 'examSets', 'grouped', 'assigned'));
    }

    public function assign(Request $request, Question $question)
    {
        abort_unless($this->canUseQuestion($question), 403);

        $request->validate([
            'exam_set_ids'   => 'nullable|array',
            'exam_set_ids.*' => 'exists:exam_sets,id',
        ]);

        $examSetIds = $request->input('exam_set_ids', []);
        $question->examSets()->sync($examSetIds);

        return redirect()->route('teacher.question_bank.index')
            ->with('success', 'เชื่อมโยงข้อสอบกับชุดข้อสอบสำเร็จ');
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'question_ids'   => 'required|array|min:1',
            'question_ids.*' => 'exists:questions,id',
            'exam_set_ids'   => 'required|array|min:1',
            'exam_set_ids.*' => 'exists:exam_sets,id',
        ]);

        $allowedIds  = $this->allowedSubjectIds();
        $questionIds = $request->input('question_ids');
        $examSetIds  = $request->input('exam_set_ids');

        $questions = Question::whereIn('id', $questionIds)
            ->where(function ($q) use ($allowedIds) {
                $q->whereIn('subject_id', $allowedIds)->orWhereNull('subject_id');
            })
            ->get();

        $count = 0;
        foreach ($questions as $question) {
            $question->examSets()->syncWithoutDetaching($examSetIds);
            $count++;
        }

        return redirect()->route('teacher.question_bank.index')
            ->with('success', "เชื่อมโยง {$count} ข้อสอบเข้าชุดข้อสอบสำเร็จ");
    }

    public function importForm()
    {
        $subjectIds = $this->allowedSubjectIds();
        $subjects   = Subject::whereIn('id', $subjectIds)->with('course')->orderBy('name')->get();
        $categories = ['กระทู้', 'ธรรมะ', 'พุทธะ', 'วินัย', 'ศาสนพิธี'];

        return view('teacher.question_bank.import', compact('subjects', 'categories'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'txt_file'   => 'required|file|mimes:txt|max:2048',
        ]);

        if ($request->subject_id) {
            abort_unless($this->canUseSubject($request->subject_id), 403);
        }

        $content = file_get_contents($request->file('txt_file')->getRealPath());
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');

        $blocks = preg_split('/\n\s*---\s*\n|\n\s*\n/', trim($content));
        $blocks = array_values(array_filter($blocks, function ($b) { return trim($b) !== ''; }));

        if (count($blocks) > 50) {
            return back()->withErrors(['txt_file' => 'ไฟล์มีข้อสอบ ' . count($blocks) . ' ข้อ (สูงสุด 50 ข้อต่อไฟล์)'])->withInput();
        }

        $inserted = 0;
        $errors   = [];

        foreach ($blocks as $idx => $block) {
            $block = trim($block);
            if ($block === '') continue;

            $num  = $idx + 1;
            $data = $this->parseTxtBlock($block);

            if (!$data || empty($data['question_text'])) {
                $errors[] = "ข้อ {$num}: ไม่พบคำถาม";
                continue;
            }

            $data['subject_id'] = $request->subject_id ?: null;
            $data['is_active']  = true;

            if ($data['type'] === 'multiple_choice' && empty($data['correct_answer'])) {
                $errors[] = "ข้อ {$num}: ปรนัยต้องระบุเฉลย";
                continue;
            }

            Question::create($data);
            $inserted++;
        }

        $msg = "นำเข้าสำเร็จ {$inserted} ข้อ";
        if (count($errors) > 0) {
            $msg .= ' (ข้ามไป ' . count($errors) . ' ข้อ)';
            return redirect()->route('teacher.question_bank.index')
                ->with('success', $msg)
                ->with('import_errors', $errors);
        }

        return redirect()->route('teacher.question_bank.index')
            ->with('success', $msg);
    }

    private function parseTxtBlock($block)
    {
        $lines = preg_split('/\r?\n/', $block);
        $data  = [
            'type'           => 'multiple_choice',
            'question_text'  => '',
            'option_a'       => null,
            'option_b'       => null,
            'option_c'       => null,
            'option_d'       => null,
            'correct_answer' => null,
            'essay_answer'   => null,
            'points'         => 1,
            'sort_order'     => 0,
        ];

        $currentKey = null;
        $multiLine  = '';

        foreach ($lines as $line) {
            if (preg_match('/^(คำถาม|ประเภท|ก\.|ข\.|ค\.|ง\.|เฉลย|คะแนน|ลำดับ|เฉลยอัตนัย)\s*[:：]\s*(.*)$/u', $line, $m)) {
                if ($currentKey === 'question_text' && $multiLine !== '') {
                    $data['question_text'] = trim($multiLine);
                    $multiLine = '';
                } elseif ($currentKey === 'essay_answer' && $multiLine !== '') {
                    $data['essay_answer'] = trim($multiLine);
                    $multiLine = '';
                }

                $key = trim($m[1]);
                $val = trim($m[2]);

                switch ($key) {
                    case 'คำถาม':
                        $currentKey = 'question_text';
                        $multiLine  = $val;
                        break;
                    case 'ประเภท':
                        $v = mb_strtolower($val);
                        if (in_array($v, ['อัตนัย', 'essay'])) {
                            $data['type'] = 'essay';
                        } else {
                            $data['type'] = 'multiple_choice';
                        }
                        $currentKey = null;
                        break;
                    case 'ก.':
                        $data['option_a'] = $val;
                        $currentKey = null;
                        break;
                    case 'ข.':
                        $data['option_b'] = $val;
                        $currentKey = null;
                        break;
                    case 'ค.':
                        $data['option_c'] = $val;
                        $currentKey = null;
                        break;
                    case 'ง.':
                        $data['option_d'] = $val;
                        $currentKey = null;
                        break;
                    case 'เฉลย':
                        $ans = mb_strtolower(trim($val));
                        $map = ['ก' => 'a', 'ข' => 'b', 'ค' => 'c', 'ง' => 'd',
                                'a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd',
                                '1' => 'a', '2' => 'b', '3' => 'c', '4' => 'd'];
                        $data['correct_answer'] = $map[$ans] ?? null;
                        $currentKey = null;
                        break;
                    case 'เฉลยอัตนัย':
                        $currentKey = 'essay_answer';
                        $multiLine  = $val;
                        break;
                    case 'คะแนน':
                        $data['points'] = max(1, intval($val));
                        $currentKey = null;
                        break;
                    case 'ลำดับ':
                        $data['sort_order'] = intval($val);
                        $currentKey = null;
                        break;
                }
            } else {
                if ($currentKey === 'question_text' || $currentKey === 'essay_answer') {
                    $multiLine .= "\n" . $line;
                }
            }
        }

        if ($currentKey === 'question_text' && $multiLine !== '') {
            $data['question_text'] = trim($multiLine);
        }
        if ($currentKey === 'essay_answer' && $multiLine !== '') {
            $data['essay_answer'] = trim($multiLine);
        }

        if ($data['type'] === 'essay' && $data['essay_answer'] === null) {
            $data['correct_answer'] = null;
        }

        return $data;
    }
}
