@extends('layouts.student')
@section('title','กำลังสอบ')

@section('content')
<div class="container py-3" style="max-width:800px;">
    <!-- Header bar -->
    <div class="card mb-3 bg-dark text-white">
        <div class="card-body py-2 d-flex justify-content-between align-items-center">
            <span><i class="fas fa-file-alt mr-2"></i><strong>{{ $attempt->examSet->name }}</strong></span>
            @if($attempt->examSet->time_limit)
            <span id="timer" class="font-weight-bold text-warning">
                <i class="fas fa-clock mr-1"></i><span id="timerDisplay">--:--</span>
            </span>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('student.exams.submit', $attempt) }}" id="examForm">
        @csrf

        @foreach($questions as $i => $q)
        <div class="card mb-3" id="q{{ $i+1 }}">
            <div class="card-header py-2">
                <small class="text-muted">ข้อ {{ $i+1 }} / {{ count($questions) }}</small>
                <span class="float-right badge badge-{{ $q->type === 'multiple_choice' ? 'primary' : 'warning text-dark' }}">
                    {{ $q->type === 'multiple_choice' ? 'ปรนัย' : 'อัตนัย' }} | {{ $q->points }} คะแนน
                </span>
            </div>
            <div class="card-body">
                <p class="font-weight-bold mb-3">{{ $q->question_text }}</p>

                @if($q->type === 'multiple_choice')
                    <input type="hidden" name="question_ids[]" value="{{ $q->id }}">
                    @foreach($q->options as $key => $text)
                    @if($text)
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" class="custom-control-input"
                               id="q{{ $q->id }}_{{ $key }}"
                               name="answers[{{ $q->id }}]" value="{{ $key }}">
                        <label class="custom-control-label" for="q{{ $q->id }}_{{ $key }}">
                            {{ ['a'=>'ก','b'=>'ข','c'=>'ค','d'=>'ง'][$key] ?? $key }}. {{ $text }}
                        </label>
                    </div>
                    @endif
                    @endforeach
                @else
                    <input type="hidden" name="question_ids[]" value="{{ $q->id }}">
                    <textarea name="answers[{{ $q->id }}]" class="form-control" rows="4"
                              placeholder="พิมพ์คำตอบที่นี่..."></textarea>
                @endif
            </div>
        </div>
        @endforeach

        <div class="text-center mb-4">
            <button type="submit" class="btn btn-success btn-lg px-5"
                    onclick="return confirm('ยืนยันส่งข้อสอบ? คุณจะไม่สามารถแก้ไขได้อีก')">
                <i class="fas fa-paper-plane mr-2"></i>ส่งข้อสอบ
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
(function() {
    var limitMinutes = {{ $attempt->examSet->time_limit ?? 0 }};
    if (!limitMinutes) return;

    var startedAt = new Date('{{ $attempt->started_at->toISOString() }}');
    var deadline  = new Date(startedAt.getTime() + limitMinutes * 60000);

    function tick() {
        var now  = new Date();
        var diff = Math.max(0, Math.floor((deadline - now) / 1000));
        var m    = Math.floor(diff / 60);
        var s    = diff % 60;

        var display = document.getElementById('timerDisplay');
        if (display) {
            display.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        }
        if (diff <= 60) {
            var timer = document.getElementById('timer');
            if (timer) { timer.classList.remove('text-warning'); timer.classList.add('text-danger'); }
        }
        if (diff === 0) {
            document.getElementById('examForm').submit();
        }
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
@endsection
