<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สมัครสอบสำเร็จ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark" style="background:#16a085;">
    <a class="navbar-brand font-weight-bold" href="{{ route('landing') }}">ธรรมศึกษา|สำนักเรียนวัดสารอด</a>
</nav>
<main class="container py-5" style="max-width:600px;">
    <div class="card text-center shadow-sm">
        <div class="card-body py-5">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h1 class="h4">ลงทะเบียนสมัครสอบสำเร็จ</h1>
            <p class="text-muted">กรุณาเก็บหมายเลขอ้างอิงนี้ไว้สำหรับติดตามสถานะ</p>
            <div class="h3 font-weight-bold text-success py-2 px-3 d-inline-block border rounded">
                {{ $registration->reference_code }}
            </div>
            <p class="mt-3 mb-0">ชื่อ-สกุล: {{ $registration->fullName() }}</p>
            <p class="text-muted">สถานะ: {{ $registration->statusLabel() }}</p>
            <a href="{{ route('exam_registration.public.create') }}" class="btn btn-outline-success mt-3">
                <i class="fas fa-arrow-left mr-1"></i>กลับหน้าสมัครสอบ
            </a>
        </div>
    </div>
</main>
</body>
</html>
