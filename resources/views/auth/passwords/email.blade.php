<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน – V-Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #16a085 0%, #2c3e50 100%); min-height: 100vh; display:flex; align-items:center; justify-content:center; font-family:'Sarabun','Segoe UI',sans-serif; }
        .login-card { width: 100%; max-width: 420px; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,.25); }
        .login-header { background: #2c3e50; color: #fff; border-radius: 16px 16px 0 0; padding: 32px; text-align: center; }
        .login-header .icon { font-size: 3rem; color: #f1c40f; margin-bottom: 8px; }
        .login-body { background: #fff; border-radius: 0 0 16px 16px; padding: 32px; }
        .btn-submit { background: #16a085; color: #fff; width: 100%; border-radius: 8px; padding: 12px; font-size: 1rem; }
        .btn-submit:hover { background: #1abc9c; color: #fff; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <div class="icon"><i class="fas fa-key"></i></div>
        <h4 class="mb-1 font-weight-bold">ลืมรหัสผ่าน</h4>
        <small class="opacity-75">กรอกอีเมลเพื่อรับลิงก์รีเซ็ตรหัสผ่าน</small>
    </div>
    <div class="login-body">
        @if(session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-1"></i>
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label><i class="fas fa-envelope mr-1 text-muted"></i>อีเมล</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus placeholder="กรอกอีเมลที่ลงทะเบียนไว้">
            </div>
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-paper-plane mr-1"></i> ส่งลิงก์รีเซ็ตรหัสผ่าน
            </button>
        </form>
        <hr>
        <p class="text-center mb-0 small">
            <a href="{{ route('login') }}"><i class="fas fa-arrow-left mr-1"></i>กลับไปหน้าเข้าสู่ระบบ</a>
        </p>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
