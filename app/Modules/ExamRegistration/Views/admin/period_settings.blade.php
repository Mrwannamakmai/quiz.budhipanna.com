@extends('layouts.admin')
@section('title', 'ตั้งค่ารับสมัครสอบธรรมศึกษา')
@section('page-title', 'ตั้งค่าช่วงเวลารับสมัคร / QR Code')

@section('content')
<style>.er-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:18px}</style>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<a href="{{ route('teacher.exam_registration.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left mr-1"></i>กลับ</a>

<div class="row">
    <div class="col-md-7">
        <div class="er-card">
            <h5 class="mb-3">ช่วงเวลารับสมัคร</h5>
            <form method="POST" action="{{ route('teacher.exam_registration.settings.update') }}">
                @csrf
                <div class="form-group">
                    <label>โหมด</label>
                    <select name="registration_mode" class="form-control">
                        <option value="auto" {{ $mode === 'auto' ? 'selected' : '' }}>อัตโนมัติ (ตามวันเวลาที่กำหนด)</option>
                        <option value="force_open" {{ $mode === 'force_open' ? 'selected' : '' }}>เปิดรับสมัครตลอด (บังคับเปิด)</option>
                        <option value="force_closed" {{ $mode === 'force_closed' ? 'selected' : '' }}>ปิดรับสมัครตลอด (บังคับปิด)</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>วันเวลาเริ่มรับสมัคร</label>
                        <input type="datetime-local" name="registration_start" class="form-control" value="{{ $start ? \Carbon\Carbon::parse($start)->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>วันเวลาสิ้นสุดรับสมัคร</label>
                        <input type="datetime-local" name="registration_end" class="form-control" value="{{ $end ? \Carbon\Carbon::parse($end)->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>หัวข้อบน QR Code</label>
                    <input type="text" name="qr_title" id="qrTitle" class="form-control" value="{{ $qrTitle }}">
                </div>
                <div class="form-group">
                    <label>ข้อความรองบน QR Code</label>
                    <input type="text" name="qr_subtitle" id="qrSubtitle" class="form-control" value="{{ $qrSubtitle }}">
                </div>
                <button class="btn btn-primary"><i class="fas fa-save mr-1"></i>บันทึก</button>
            </form>
        </div>
    </div>

    <div class="col-md-5">
        <div class="er-card text-center">
            <h5 class="mb-3">QR Code หน้าสมัครสอบ</h5>
            <div class="text-muted small mb-2" style="word-break:break-all;">{{ $publicUrl }}</div>
            <canvas id="qrCanvas" width="320" height="380" class="border rounded mb-2"></canvas><br>
            <button type="button" class="btn btn-outline-success btn-sm" id="downloadQr"><i class="fas fa-download mr-1"></i>ดาวน์โหลด PNG</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="fas fa-print mr-1"></i>พิมพ์</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
(function () {
    var url = @json($publicUrl);

    // สร้าง QR ด้วยไลบรารี qrcodejs ในกล่องที่ซ่อนไว้ แล้ววาดลง canvas หลักพร้อมหัวข้อ/ข้อความรอง
    var hidden = document.createElement('div');
    hidden.style.display = 'none';
    document.body.appendChild(hidden);
    new QRCode(hidden, { text: url, width: 260, height: 260, correctLevel: QRCode.CorrectLevel.M });

    function draw() {
        var qrImg = hidden.querySelector('img') || hidden.querySelector('canvas');
        if (!qrImg || (qrImg.tagName === 'IMG' && !qrImg.complete)) {
            setTimeout(draw, 150);
            return;
        }
        var canvas = document.getElementById('qrCanvas');
        var ctx = canvas.getContext('2d');
        ctx.fillStyle = '#fff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = '#1e293b';
        ctx.font = 'bold 18px Sarabun, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(document.getElementById('qrTitle').value || '', canvas.width / 2, 28);

        ctx.font = '14px Sarabun, sans-serif';
        ctx.fillStyle = '#475569';
        ctx.fillText(document.getElementById('qrSubtitle').value || '', canvas.width / 2, 50);

        var qx = (canvas.width - 260) / 2;
        ctx.drawImage(qrImg, qx, 65, 260, 260);

        ctx.font = '12px Sarabun, sans-serif';
        ctx.fillStyle = '#64748b';
        ctx.fillText('สแกนเพื่อสมัครสอบธรรมศึกษา', canvas.width / 2, 350);
    }
    draw();

    document.getElementById('downloadQr').addEventListener('click', function () {
        var canvas = document.getElementById('qrCanvas');
        var link = document.createElement('a');
        link.download = 'exam-registration-qr.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });

    document.getElementById('qrTitle').addEventListener('input', draw);
    document.getElementById('qrSubtitle').addEventListener('input', draw);
})();
</script>
@endsection
