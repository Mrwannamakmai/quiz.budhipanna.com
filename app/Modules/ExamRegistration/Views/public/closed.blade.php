<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สมัครสอบธรรมศึกษา</title>
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
            <i class="fas fa-door-closed fa-3x text-muted mb-3"></i>
            <h1 class="h4">ปิดรับสมัคร</h1>
            <p class="text-muted mb-0">{{ $message }}</p>
        </div>
    </div>
</main>
</body>
</html>
