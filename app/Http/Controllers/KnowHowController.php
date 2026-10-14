<?php

namespace App\Http\Controllers;

use App\Article;
use App\Subject;
use Illuminate\Http\Request;

class KnowHowController extends Controller
{
    private function sharedStats(): array
    {
        return [
            'visitorStats' => [
                'total'     => \App\VisitorLog::count(),
                'unique'    => \App\VisitorLog::distinct('ip_address')->count('ip_address'),
                'returning' => \App\VisitorLog::select('ip_address')
                                ->groupBy('ip_address')->havingRaw('COUNT(*) > 1')->get()->count(),
                'today'     => \App\VisitorLog::whereDate('visited_at', today())->count(),
            ],
            'siteStats' => [
                'students'     => \App\User::where('role', 'student')->count(),
                'exam_sets'    => \App\ExamSet::where('is_active', true)->count(),
                'attempts'     => \App\ExamAttempt::whereNotNull('submitted_at')->count(),
                'passed'       => \App\ExamAttempt::whereNotNull('submitted_at')->where('passed', true)->count(),
                'certificates' => \App\Certificate::count(),
                'pretest'      => \App\ExamSet::where('is_active', true)->where('is_public', true)->count(),
            ],
        ];
    }

    public function index(Request $request)
    {
        $query = Article::published()->with(['subject', 'creator']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('tag')) {
            $query->where('tags', 'like', '%' . $request->tag . '%');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest()->paginate(12)->appends($request->query());
        $subjects = Subject::has('articles')->orderBy('name')->get(['id', 'name']);

        // Popular tags across all published articles
        $allTags = Article::published()->whereNotNull('tags')->pluck('tags')
            ->flatMap(fn($t) => array_filter(array_map('trim', explode(',', $t))))
            ->countBy()->sortByDesc(fn($v) => $v)->take(15);

        return view('knowhow.index', array_merge(compact('articles', 'subjects', 'allTags'), $this->sharedStats()));
    }

    public function show(Article $article)
    {
        abort_unless($article->is_published, 404);

        $article->increment('view_count');
        $article->load(['subject', 'creator']);

        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($article) {
                if ($article->subject_id) {
                    $q->where('subject_id', $article->subject_id);
                }
            })
            ->latest()->limit(4)->get();

        return view('knowhow.show', array_merge(compact('article', 'related'), $this->sharedStats()));
    }
}
