<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กำลังทำ — {{ $examSet->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; }
        .fas,.far,.fal { font-family: "Font Awesome 5 Free" !important; }
        .fab { font-family: "Font Awesome 5 Brands" !important; }
        .q-card { border-left: 4px solid #16a085; }
    </style>
</head>
<body>

<!-- Top bar -->
<nav style="background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.08);padding:10px 0;position:sticky;top:0;z-index:999;">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('landing') }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#16a085,#1abc9c);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;">
                <i class="fas fa-book-open"></i>
            </div>
            <span style="font-size:1.1rem;font-weight:700;color:#16a085;">V-Quiz</span>
        </a>
        <div class="d-flex align-items-center" style="gap:14px;">
            <span style="color:#333;font-weight:600;font-size:.9rem;" class="d-none d-md-inline">
                <i class="fas fa-pencil-alt mr-1 text-success"></i>{{ $examSet->name }}
            </span>
            @if($examSet->time_limit)
            <span style="background:#fff3cd;color:#856404;border-radius:20px;padding:6px 16px;font-weight:700;font-size:.95rem;">
                <i class="fas fa-clock mr-1"></i><span id="timerDisplay">--:--</span>
            </span>
            @endif
        </div>
    </div>
</nav>

<div class="container py-4" style="max-width:800px;">
    <form method="POST" action="{{ route('pretest.submit', $examSet) }}" id="pretestForm">
        @csrf

        @foreach($questions as $i => $q)
        <div class="card mb-3 q-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <strong class="text-muted">ข้อ {{ $i + 1 }} / {{ count($questions) }}</strong>
                    <span class="badge badge-{{ $q->type === 'multiple_choice' ? 'primary' : 'warning text-dark' }}">
                        {{ $q->type === 'multiple_choice' ? 'ปรนัย' : 'อัตนัย' }} | {{ $q->points }} คะแนน
                    </span>
                </div>
                <p class="font-weight-bold mb-3">{{ $q->question_text }}</p>

                @if($q->type === 'multiple_choice')
                    @foreach($q->options as $key => $text)
                    @if($text)
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" class="custom-control-input"
                               id="q{{ $q->id }}_{{ $key }}"
                               name="answers[{{ $q->id }}]" value="{{ $key }}">
                        <label class="custom-control-label" for="q{{ $q->id }}_{{ $key }}">
                            {{ ['a'=>'ก','b'=>'ข','c'=>'ค','d'=>'ง'][$key] }}. {{ $text }}
                        </label>
                    </div>
                    @endif
                    @endforeach
                @else
                    <textarea name="answers[{{ $q->id }}]" class="form-control"
                              rows="3" placeholder="พิมพ์คำตอบที่นี่..."></textarea>
                @endif
            </div>
        </div>
        @endforeach

        <div class="text-center my-4">
            <button type="submit" class="btn btn-success btn-lg px-5"
                    onclick="return confirm('ยืนยันส่งคำตอบ?')">
                <i class="fas fa-paper-plane mr-2"></i>ส่งคำตอบ
            </button>
        </div>
    </form>
</div>

<!-- Footer -->
<footer style="background:#1a1a2e;color:#bdc3c7;padding:28px 0 14px;font-size:.82rem;margin-top:20px;">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center mb-3" style="gap:8px;">
            <span style="background:#16a085;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Quiz</span>
            <span style="background:#2980b9;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Kru.One</span>
            <span style="background:#8e44ad;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">ALevel</span>
            <span style="background:#c0392b;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Pali</span>
            <span style="background:#d35400;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Tutor</span>
        </div>
        <div class="text-center mb-2">
            <i class="fas fa-hands-helping mr-1" style="color:#1abc9c;"></i>
            <strong style="color:#ecf0f1;">สนับสนุนโดย</strong> วัดสารอด | สำนักเรียนวัดสารอด เขตราษฎร์บูรณะ กรุงเทพฯ
        </div>
        <div class="text-center mb-3">
            <i class="fas fa-school mr-1" style="color:#1abc9c;"></i>
            สำนักเรียนวัดสารอด เขตราษฏร์บูรณะ กรุงเทพฯ
            &nbsp;|&nbsp; พระปริยัติธรรม &nbsp;|&nbsp; แผนกบาลี &nbsp;|&nbsp; แผนกธรรม
        </div>
        <hr style="border-color:#2c3e50;margin:8px 0 10px;">
        <div class="text-center" style="color:#636e72;font-size:.75rem;">
            &copy; {{ date('Y') }} V-Quiz &mdash; สงวนลิขสิทธิ์
        </div>
    </div>
</footer>

@if($examSet->time_limit)
<script>
(function() {
    var seconds = {{ $examSet->time_limit * 60 }};
    var display = document.getElementById('timerDisplay');
    function tick() {
        var m = Math.floor(seconds / 60);
        var s = seconds % 60;
        display.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        if (seconds <= 60) display.style.color = '#e74c3c';
        if (seconds <= 0) { document.getElementById('pretestForm').submit(); return; }
        seconds--;
    }
    tick();
    setInterval(tick, 1000);
})();
</script>
@endif
</body>
</html>
