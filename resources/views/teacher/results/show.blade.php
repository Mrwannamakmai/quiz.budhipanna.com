@extends('layouts.teacher')
@section('title','รายละเอียดผลสอบ')
@section('page-title','รายละเอียดผลสอบ')

@section('content')
<style>
/* ─── Rubric grading ─── */
.rubric-card { background:#fffbeb; border:1.5px solid #fde68a; border-radius:10px; padding:14px; margin-top:10px; }
.rubric-title { font-size:.78rem; font-weight:700; color:#92400e; margin-bottom:10px; display:flex; align-items:center; gap:6px; }
.rubric-row { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px solid #fef3c7; }
.rubric-row:last-of-type { border-bottom:none; }
.rubric-name { flex:1; }
.rubric-name .rn-title { font-size:.82rem; font-weight:600; color:#1e293b; }
.rubric-name .rn-desc { font-size:.72rem; color:#94a3b8; }
.rubric-input { display:flex; align-items:center; gap:4px; }
.rubric-input input { width:65px; border:1.5px solid #e2e8f0; border-radius:6px; padding:5px 8px; font-size:.82rem; text-align:center; }
.rubric-input input:focus { outline:none; border-color:#3b82f6; }
.rubric-max { font-size:.78rem; color:#94a3b8; }
.rubric-comment { width:100%; border:1.5px solid #e2e8f0; border-radius:6px; padding:5px 8px; font-size:.78rem; margin-top:4px; }
.rubric-comment:focus { outline:none; border-color:#3b82f6; }
.rubric-total { display:flex; justify-content:space-between; align-items:center; padding:10px 0 0; margin-top:8px; border-top:2px solid #fde68a; }
.rubric-total .rt-label { font-size:.82rem; font-weight:600; color:#92400e; }
.rubric-total .rt-val { font-size:1rem; font-weight:700; color:#1e293b; }

.grader-comment-box { margin-top:10px; }
.grader-comment-box label { font-size:.78rem; font-weight:600; color:#64748b; display:block; margin-bottom:4px; }
.grader-comment-box textarea { width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:.82rem; }
.grader-comment-box textarea:focus { outline:none; border-color:#3b82f6; }

.graded-rubric { background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:10px; padding:14px; margin-top:10px; }
.graded-rubric .gr-title { font-size:.78rem; font-weight:700; color:#15803d; margin-bottom:8px; }
.gr-row { display:flex; justify-content:space-between; align-items:center; padding:5px 0; border-bottom:1px solid #dcfce7; font-size:.82rem; }
.gr-row:last-child { border-bottom:none; }
.gr-name { color:#1e293b; font-weight:500; }
.gr-score { font-weight:600; }
.gr-comment { font-size:.72rem; color:#64748b; font-style:italic; margin-top:2px; }
.gr-total { display:flex; justify-content:space-between; padding:8px 0 0; margin-top:6px; border-top:2px solid #bbf7d0; font-weight:700; }

.grader-note { background:#f8fafc; border-radius:8px; padding:10px; margin-top:8px; font-size:.8rem; color:#64748b; }
.grader-note strong { color:#334155; }
</style>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">ข้อมูลการสอบ</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">นักเรียน</td><td><strong>{{ $attempt->user->name ?? '-' }}</strong></td></tr>
                    <tr><td class="text-muted">ชุดข้อสอบ</td><td>{{ $attempt->examSet->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">คะแนน</td><td>{{ $attempt->score }} / {{ $attempt->total_points }}</td></tr>
                    <tr><td class="text-muted">เปอร์เซ็นต์</td><td>{{ $attempt->total_points > 0 ? number_format($attempt->score / $attempt->total_points * 100, 1) : 0 }}%</td></tr>
                    <tr><td class="text-muted">ผล</td>
                        <td>
                            @if($attempt->passed)
                                <span class="badge badge-success">ผ่าน</span>
                            @else
                                <span class="badge badge-danger">ไม่ผ่าน</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted">ส่งเมื่อ</td><td>{{ $attempt->submitted_at ? $attempt->submitted_at->format('d/m/Y H:i') : '-' }}</td></tr>
                </table>
            </div>
        </div>

        @if($attempt->passed && !$attempt->certificate)
        <div class="card mb-3 border-success">
            <div class="card-body text-center">
                <p class="text-success mb-2"><i class="fas fa-certificate fa-2x"></i></p>
                <p class="mb-2">นักเรียนผ่านเกณฑ์แล้ว ออกใบประกาศได้</p>
                <form method="POST" action="{{ route('teacher.results.certificate', $attempt) }}">
                    @csrf
                    <button class="btn btn-success btn-sm">
                        <i class="fas fa-certificate mr-1"></i>ออกใบประกาศ
                    </button>
                </form>
            </div>
        </div>
        @elseif($attempt->certificate)
        <div class="card mb-3 border-info">
            <div class="card-body text-center">
                <p class="text-info mb-1"><i class="fas fa-check-circle fa-2x"></i></p>
                <small>ออกใบประกาศแล้ว: <code>{{ $attempt->certificate->certificate_no }}</code></small>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">คำตอบทั้งหมด</div>
            <div class="card-body p-0">
                @foreach($attempt->studentAnswers as $i => $ans)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <strong>ข้อ {{ $i+1 }}.</strong>
                        <span>
                            @if($ans->question->type === 'multiple_choice')
                                @if($ans->is_correct)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> ถูก ({{ $ans->points_earned }}/{{ $ans->question->points }})</span>
                                @else
                                    <span class="text-danger"><i class="fas fa-times-circle"></i> ผิด (0/{{ $ans->question->points }})</span>
                                @endif
                            @else
                                <span class="badge badge-warning text-dark">อัตนัย — {{ $ans->points_earned ?? '?' }}/{{ $ans->question->points }} คะแนน</span>
                            @endif
                        </span>
                    </div>
                    <p class="mb-1 mt-1">{{ $ans->question->question_text }}</p>

                    @if($ans->question->type === 'multiple_choice')
                        <small class="text-muted">
                            คำตอบ: <strong>{{ strtoupper($ans->answer) }}</strong>
                            | เฉลย: <strong class="text-success">{{ strtoupper($ans->question->correct_answer) }}</strong>
                        </small>
                    @else
                        {{-- Essay answer --}}
                        <div class="bg-light p-2 rounded mt-1 mb-2">
                            <small>{{ $ans->answer ?? $ans->essay_answer ?? '(ไม่มีคำตอบ)' }}</small>
                        </div>

                        @if(!$ans->is_graded)
                            @php $criteria = $ans->question->essayCriteria; @endphp

                            @if($criteria->isNotEmpty())
                                {{-- Rubric-based grading --}}
                                <form method="POST" action="{{ route('teacher.results.grade', $ans) }}">
                                    @csrf
                                    <div class="rubric-card">
                                        <div class="rubric-title">
                                            <i class="fas fa-clipboard-check"></i> ให้คะแนนตามเกณฑ์ ({{ $criteria->count() }} เกณฑ์)
                                        </div>

                                        @foreach($criteria as $c)
                                        <div class="rubric-row">
                                            <div class="rubric-name">
                                                <div class="rn-title">{{ $c->name }}</div>
                                                @if($c->description)
                                                    <div class="rn-desc">{{ $c->description }}</div>
                                                @endif
                                            </div>
                                            <div class="rubric-input">
                                                <input type="number" name="criterion_scores[{{ $c->id }}][points]"
                                                       min="0" max="{{ $c->max_points }}" step="0.5" value="0"
                                                       required class="rubric-score-input"
                                                       data-max="{{ $c->max_points }}"
                                                       onchange="updateRubricTotal(this)">
                                                <span class="rubric-max">/ {{ number_format($c->max_points, 1) }}</span>
                                            </div>
                                        </div>
                                        <input type="text" name="criterion_scores[{{ $c->id }}][comment]"
                                               class="rubric-comment" placeholder="ความเห็นเกณฑ์นี้ (ถ้ามี)">
                                        @endforeach

                                        <div class="rubric-total">
                                            <span class="rt-label">คะแนนรวม:</span>
                                            <span class="rt-val"><span class="rubric-total-display">0</span> / {{ $ans->question->points }}</span>
                                        </div>
                                    </div>

                                    <div class="grader-comment-box">
                                        <label><i class="fas fa-comment mr-1"></i>ความเห็นรวม (ถ้ามี)</label>
                                        <textarea name="grader_comment" rows="2" placeholder="ความเห็นเพิ่มเติมสำหรับนักเรียน..."></textarea>
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save mr-1"></i>บันทึกคะแนน
                                        </button>
                                    </div>
                                </form>
                            @else
                                {{-- Simple grading (no criteria) --}}
                                <form method="POST" action="{{ route('teacher.results.grade', $ans) }}"
                                      class="form-inline mt-1">
                                    @csrf
                                    <label class="mr-2 text-muted">ให้คะแนน:</label>
                                    <input type="number" name="points_earned" class="form-control form-control-sm mr-2"
                                           style="width:80px;" min="0" max="{{ $ans->question->points }}"
                                           value="{{ $ans->points_earned ?? 0 }}" step="0.5" required>
                                    <span class="mr-2 text-muted">/ {{ $ans->question->points }}</span>
                                    <input type="text" name="grader_comment" class="form-control form-control-sm mr-2"
                                           style="width:200px;" placeholder="ความเห็น (ถ้ามี)">
                                    <button class="btn btn-sm btn-primary">บันทึก</button>
                                </form>
                            @endif
                        @else
                            {{-- Already graded --}}
                            @php
                                $criterionScores = $ans->criterionScores;
                                $hasCriterionScores = $criterionScores && $criterionScores->isNotEmpty();
                            @endphp

                            @if($hasCriterionScores)
                                <div class="graded-rubric">
                                    <div class="gr-title"><i class="fas fa-check-circle mr-1"></i>ตรวจแล้ว — คะแนนตามเกณฑ์</div>
                                    @foreach($criterionScores as $cs)
                                    <div>
                                        <div class="gr-row">
                                            <span class="gr-name">{{ $cs->criterion->name ?? '-' }}</span>
                                            <span class="gr-score">{{ number_format($cs->points_earned, 1) }} / {{ number_format($cs->criterion->max_points ?? 0, 1) }}</span>
                                        </div>
                                        @if($cs->comment)
                                            <div class="gr-comment">{{ $cs->comment }}</div>
                                        @endif
                                    </div>
                                    @endforeach
                                    <div class="gr-total">
                                        <span>รวม</span>
                                        <span>{{ number_format($ans->points_earned, 1) }} / {{ $ans->question->points }}</span>
                                    </div>
                                </div>
                            @else
                                <small class="text-success"><i class="fas fa-check"></i> ตรวจแล้ว: {{ $ans->points_earned }} คะแนน</small>
                            @endif

                            @if($ans->grader_comment)
                                <div class="grader-note">
                                    <strong><i class="fas fa-comment mr-1"></i>ความเห็นจากผู้ตรวจ:</strong>
                                    {{ $ans->grader_comment }}
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('teacher.results.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i>กลับ
    </a>
</div>

@section('scripts')
<script>
function updateRubricTotal(input) {
    var form = input.closest('form');
    var inputs = form.querySelectorAll('.rubric-score-input');
    var total = 0;
    inputs.forEach(function(inp) {
        var val = parseFloat(inp.value) || 0;
        var max = parseFloat(inp.getAttribute('data-max')) || 0;
        if (val > max) { inp.value = max; val = max; }
        total += val;
    });
    var display = form.querySelector('.rubric-total-display');
    if (display) display.textContent = total.toFixed(1);
}
</script>
@endsection
@endsection
