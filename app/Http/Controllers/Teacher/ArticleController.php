<?php

namespace App\Http\Controllers\Teacher;

use App\Article;
use App\Subject;
use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    private function teacherSubjectIds()
    {
        if (Auth::user()->isSuperAdmin()) {
            return Subject::pluck('id');
        }
        $courseIds = Course::where('user_id', Auth::id())->pluck('id');
        return Subject::whereIn('course_id', $courseIds)->pluck('id');
    }

    public function index(Request $request)
    {
        $query = Article::with(['subject', 'creator']);

        if (!Auth::user()->isSuperAdmin()) {
            $subjectIds = $this->teacherSubjectIds();
            $query->where(function ($q) use ($subjectIds) {
                $q->whereIn('subject_id', $subjectIds)
                  ->orWhere('created_by', Auth::id());
            });
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('published')) {
            $query->where('is_published', $request->published === '1');
        }

        $articles = $query->latest()->paginate(20)->appends($request->query());
        return view('teacher.articles.index', compact('articles'));
    }

    public function create()
    {
        $subjects = Subject::whereIn('id', $this->teacherSubjectIds())->orderBy('name')->get();
        return view('teacher.articles.form', compact('subjects'));
    }

    private function parseNotebooklm(array $data): ?array
    {
        $items = array_values(array_filter(
            $data['notebooklm'] ?? [],
            fn($row) => !empty(trim($row['url'] ?? ''))
        ));
        if (empty($items)) return null;
        return array_map(fn($row) => [
            'url'   => trim($row['url']),
            'label' => trim($row['label'] ?? ''),
        ], $items);
    }

    private function mediaRules(): array
    {
        return [
            'image_url'   => 'nullable|url|max:500',
            'youtube_url' => 'nullable|url|max:500',
            'canva_url'   => 'nullable|url|max:500',
            'ebook_url'   => 'nullable|url|max:500',
            'file_url'              => 'nullable|url|max:500',
            'file_name'             => 'nullable|string|max:255',
            'notebooklm'            => 'nullable|array|max:10',
            'notebooklm.*.url'      => 'nullable|url|max:1000',
            'notebooklm.*.label'    => 'nullable|string|max:100',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate(array_merge([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'subject_id' => 'nullable|exists:subjects,id',
            'tags'       => 'nullable|string|max:500',
        ], $this->mediaRules()));

        Article::create([
            'title'        => $data['title'],
            'slug'         => Article::generateSlug($data['title']),
            'body'         => $data['body'],
            'subject_id'   => $data['subject_id'] ?? null,
            'tags'         => $data['tags'] ?? null,
            'is_published' => $request->has('is_published'),
            'created_by'   => Auth::id(),
            'image_url'    => $data['image_url'] ?? null,
            'youtube_url'  => $data['youtube_url'] ?? null,
            'canva_url'    => $data['canva_url'] ?? null,
            'ebook_url'    => $data['ebook_url'] ?? null,
            'file_url'          => $data['file_url'] ?? null,
            'file_name'         => $data['file_name'] ?? null,
            'notebooklm_items'  => $this->parseNotebooklm($data),
        ]);

        return redirect()->route('teacher.articles.index')
            ->with('success', 'เพิ่มบทความสำเร็จ');
    }

    public function edit(Article $article)
    {
        $subjects = Subject::whereIn('id', $this->teacherSubjectIds())->orderBy('name')->get();
        return view('teacher.articles.form', compact('article', 'subjects'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate(array_merge([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'subject_id' => 'nullable|exists:subjects,id',
            'tags'       => 'nullable|string|max:500',
        ], $this->mediaRules()));

        $article->update([
            'title'        => $data['title'],
            'body'         => $data['body'],
            'subject_id'   => $data['subject_id'] ?? null,
            'tags'         => $data['tags'] ?? null,
            'is_published' => $request->has('is_published'),
            'image_url'    => $data['image_url'] ?? null,
            'youtube_url'  => $data['youtube_url'] ?? null,
            'canva_url'    => $data['canva_url'] ?? null,
            'ebook_url'    => $data['ebook_url'] ?? null,
            'file_url'          => $data['file_url'] ?? null,
            'file_name'         => $data['file_name'] ?? null,
            'notebooklm_items'  => $this->parseNotebooklm($data),
        ]);

        return redirect()->route('teacher.articles.index')
            ->with('success', 'แก้ไขบทความสำเร็จ');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return back()->with('success', 'ลบบทความแล้ว');
    }

    public function toggle(Article $article)
    {
        $article->update(['is_published' => !$article->is_published]);
        return back()->with('success', $article->is_published ? 'เผยแพร่แล้ว' : 'ซ่อนบทความแล้ว');
    }
}
