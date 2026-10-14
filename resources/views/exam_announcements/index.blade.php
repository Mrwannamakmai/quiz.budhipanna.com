<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ประกาศรอบสอบธรรมศึกษา</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark" style="background:#16a085;">
    <a class="navbar-brand font-weight-bold" href="{{ route('landing') }}">V-Quiz</a>
    <div>
        @auth
            <a class="btn btn-light btn-sm" href="{{ route('exam_registration.index') }}">สมัครสอบ</a>
        @else
            <a class="btn btn-light btn-sm" href="{{ route('login') }}">เข้าสู่ระบบเพื่อสมัครสอบ</a>
        @endauth
    </div>
</nav>
<main class="container py-4" style="max-width:900px;">
    <h1 class="h3 mb-4"><i class="fas fa-bullhorn text-success mr-2"></i>ประกาศรอบสอบธรรมศึกษา</h1>
    @forelse($sessions as $session)
        <article class="card mb-4">
            <div class="card-header d-flex justify-content-between flex-wrap">
                <strong>ปีการศึกษา {{ $session->academic_year }}</strong>
                <span>
                    @if($session->exam_date) วันสอบ {{ $session->exam_date->format('d/m/Y') }} @endif
                    — {{ ['draft' => 'ฉบับร่าง', 'open' => 'เปิดรับสมัคร', 'closed' => 'ปิดรับสมัคร', 'completed' => 'เสร็จสิ้น'][$session->status] }}
                </span>
            </div>
            <div class="card-body">
                @if($session->announcement)
                    <h2 class="h5">ประกาศ</h2>
                    <p style="white-space:pre-line;">{{ $session->announcement }}</p>
                @endif
                @if($session->details)
                    <h2 class="h5">รายละเอียดรอบสอบ</h2>
                    <p style="white-space:pre-line;">{{ $session->details }}</p>
                @endif
                @if($session->schedule)
                    <h2 class="h5">กำหนดการ</h2>
                    <p style="white-space:pre-line;">{{ $session->schedule }}</p>
                @endif
                @if($session->notebooklm_url)
                    <div class="mt-4">
                        <h2 class="h5"><i class="fas fa-brain text-primary mr-1"></i>ข้อมูลเพิ่มเติมจาก NotebookLM</h2>
                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                            <iframe class="embed-responsive-item" src="{{ $session->notebooklm_url }}"
                                    title="ข้อมูลรอบสอบจาก NotebookLM" allow="fullscreen" allowfullscreen></iframe>
                        </div>
                        <a href="{{ $session->notebooklm_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt mr-1"></i>เปิด NotebookLM ในแท็บใหม่
                        </a>
                    </div>
                @endif
                @if($session->status === 'open')
                    <a href="{{ route('exam_registration.index', ['session_id' => $session->id]) }}" class="btn btn-success">
                        <i class="fas fa-id-card mr-1"></i>สมัครสอบรอบนี้
                    </a>
                @endif
            </div>
        </article>
    @empty
        <div class="alert alert-info">ยังไม่มีประกาศรอบสอบ</div>
    @endforelse
</main>
</body>
</html>
