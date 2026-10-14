<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โจทย์ประจำวัน — ทำข้อสอบ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; }
        .play-header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff; padding: 14px 0;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 12px rgba(217,119,6,.3);
        }
        .timer-box {
            background: rgba(255,255,255,.2); border-radius: 10px;
            padding: 4px 14px; font-weight: 800; font-size: 1.1rem;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .timer-box.warn { background: rgba(239,68,68,.3); animation: pulse .8s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.7} }
        .progress-bar-custom {
            height: 6px; background: rgba(255,255,255,.25); border-radius: 3px; overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%; background: #fff; border-radius: 3px;
            transition: width .3s;
        }
        .q-card {
            background: #fff; border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            margin-bottom: 20px; overflow: hidden;
        }
        .q-head {
            padding: 16px 22px 12px;
            border-bottom: 1.5px solid #f1f5f9;
            display: flex; align-items: flex-start; gap: 14px;
        }
        .q-num {
            width: 34px; height: 34px; border-radius: 50%;
            background: #fef3c7; color: #92400e;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: .9rem; flex-shrink: 0;
        }
        .q-text { flex: 1; color: #1e293b; font-size: .95rem; line-height: 1.65; padding-top: 4px; }
        .q-opts { padding: 14px 22px 18px; display: flex; flex-direction: column; gap: 10px; }
        .opt-label {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 10px 16px; border-radius: 10px; cursor: pointer;
            border: 1.5px solid #e2e8f0; transition: all .15s;
        }
        .opt-label:hover { border-color: #f59e0b; background: #fffbeb; }
        .opt-label input[type=radio] { display: none; }
        .opt-label input[type=radio]:checked ~ .opt-circle { background: #f59e0b; border-color: #f59e0b; color: #fff; }
        .opt-label:has(input:checked) { border-color: #f59e0b; background: #fffbeb; }
        .opt-circle {
            width: 28px; height: 28px; border-radius: 50%;
            border: 2px solid #e2e8f0; background: #f8fafc;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .8rem; color: #64748b;
            flex-shrink: 0; transition: all .15s;
        }
        .opt-text { flex: 1; font-size: .9rem; color: #334155; padding-top: 3px; line-height: 1.5; }
        .btn-submit {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff; border: none; border-radius: 30px;
            padding: 14px 50px; font-size: 1.05rem; font-weight: 800;
            cursor: pointer; box-shadow: 0 4px 16px rgba(217,119,6,.35);
            transition: transform .15s;
        }
        .btn-submit:hover { transform: translateY(-2px); }
        .answered-count { font-size: .82rem; color: #6b7280; }
    </style>
</head>
<body>

@php
    $thaiMonths = ['','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
    $m = intval(date('n'));
    $thaiDate = date('j') . ' ' . $thaiMonths[$m] . ' ' . (date('Y') + 543);
    $thaiOpts = ['a'=>'ก','b'=>'ข','c'=>'ค','d'=>'ง'];
    $total = $questions->count();
@endphp

{{-- Sticky header --}}
<div class="play-header">
    <div class="container d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:1.2rem;">🔥</span>
            <div>
                <div style="font-weight:800;font-size:1rem;">โจทย์ประจำวัน</div>
                <div style="font-size:.75rem;opacity:.85;">{{ $thaiDate }}</div>
            </div>
        </div>
        <div class="timer-box" id="timer-box">
            <i class="fas fa-clock"></i>
            <span id="timer-display">10:00</span>
        </div>
    </div>
    <div class="container mt-2">
        <div class="d-flex align-items-center justify-content-between mb-1" style="font-size:.78rem;opacity:.9;">
            <span id="answered-label">ตอบแล้ว 0 / {{ $total }} ข้อ</span>
            <span id="pct-label">0%</span>
        </div>
        <div class="progress-bar-custom">
            <div class="progress-bar-fill" id="progress-fill" style="width:0%"></div>
        </div>
    </div>
</div>

<div class="container py-4" style="max-width:700px;">

    <form id="quiz-form" action="{{ route('daily.submit') }}" method="POST">
        @csrf

        @foreach($questions as $i => $q)
        <div class="q-card" data-qnum="{{ $i + 1 }}">
            <div class="q-head">
                <div class="q-num">{{ $i + 1 }}</div>
                <div class="q-text">{{ $q->question_text }}</div>
            </div>
            <div class="q-opts">
                @foreach(['a','b','c','d'] as $key)
                @if($q->{'option_'.$key})
                <label class="opt-label">
                    <input type="radio" name="answer_{{ $q->id }}" value="{{ $key }}"
                           onchange="onAnswer(this)">
                    <div class="opt-circle">{{ $thaiOpts[$key] }}</div>
                    <div class="opt-text">{{ $q->{'option_'.$key} }}</div>
                </label>
                @endif
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="text-center py-3">
            <div class="answered-count mb-3" id="bottom-answered">ตอบแล้ว 0 / {{ $total }} ข้อ</div>
            <button type="button" class="btn-submit" onclick="confirmSubmit()">
                <i class="fas fa-paper-plane mr-2"></i>ส่งคำตอบ
            </button>
        </div>
    </form>
</div>

{{-- Confirm modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-body text-center py-4">
                <div style="font-size:2.5rem;margin-bottom:10px;">📝</div>
                <h5 style="font-weight:800;color:#1e293b;margin-bottom:6px;">ยืนยันส่งคำตอบ?</h5>
                <p style="color:#64748b;font-size:.9rem;" id="modal-msg">ตอบแล้ว 0 / {{ $total }} ข้อ</p>
                <p style="color:#ef4444;font-size:.82rem;" id="modal-warn" style="display:none;"></p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4" style="gap:10px;">
                <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn px-4" id="btn-confirm-submit"
                        style="background:#f59e0b;color:#fff;font-weight:700;border-radius:20px;">
                    <i class="fas fa-check mr-1"></i>ส่งเลย
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
var total    = {{ $total }};
var answered = 0;
var timerSec = 600; // 10 minutes
var timerInterval;
var submitted = false;

function onAnswer(input) {
    // Recount answered
    answered = 0;
    document.querySelectorAll('.q-card').forEach(function(card) {
        if (card.querySelector('input[type=radio]:checked')) answered++;
    });
    document.getElementById('answered-label').textContent = 'ตอบแล้ว ' + answered + ' / ' + total + ' ข้อ';
    document.getElementById('bottom-answered').textContent = 'ตอบแล้ว ' + answered + ' / ' + total + ' ข้อ';
    var pct = Math.round(answered / total * 100);
    document.getElementById('pct-label').textContent = pct + '%';
    document.getElementById('progress-fill').style.width = pct + '%';
}

function confirmSubmit() {
    document.getElementById('modal-msg').textContent = 'ตอบแล้ว ' + answered + ' / ' + total + ' ข้อ';
    var warn = document.getElementById('modal-warn');
    if (answered < total) {
        warn.textContent = 'ยังมี ' + (total - answered) + ' ข้อที่ยังไม่ได้ตอบ';
        warn.style.display = 'block';
    } else {
        warn.style.display = 'none';
    }
    $('#confirmModal').modal('show');
}

document.getElementById('btn-confirm-submit').addEventListener('click', function() {
    if (!submitted) {
        submitted = true;
        clearInterval(timerInterval);
        document.getElementById('quiz-form').submit();
    }
});

// Timer
function updateTimer() {
    if (timerSec <= 0) {
        clearInterval(timerInterval);
        if (!submitted) {
            submitted = true;
            document.getElementById('quiz-form').submit();
        }
        return;
    }
    timerSec--;
    var m = Math.floor(timerSec / 60);
    var s = timerSec % 60;
    document.getElementById('timer-display').textContent =
        String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    if (timerSec <= 60) {
        document.getElementById('timer-box').classList.add('warn');
    }
}
timerInterval = setInterval(updateTimer, 1000);

// Warn before leave
window.addEventListener('beforeunload', function(e) {
    if (!submitted) {
        e.preventDefault();
        e.returnValue = '';
    }
});
</script>
</body>
</html>
