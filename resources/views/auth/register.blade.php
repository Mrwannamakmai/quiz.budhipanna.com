<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก – V-Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #16a085 0%, #2c3e50 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: 'Sarabun', 'Segoe UI', sans-serif; padding: 20px 0;
        }
        .reg-card { width: 100%; max-width: 480px; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,.25); }
        .reg-header {
            background: #2c3e50; color: #fff; border-radius: 16px 16px 0 0;
            padding: 28px 32px; text-align: center;
        }
        .reg-header .icon { font-size: 2.6rem; color: #1abc9c; margin-bottom: 6px; }
        .reg-body { background: #fff; border-radius: 0 0 16px 16px; padding: 28px 32px; }
        .btn-register {
            background: #16a085; color: #fff; width: 100%; border-radius: 8px;
            padding: 12px; font-size: 1rem; font-weight: 600;
        }
        .btn-register:hover { background: #1abc9c; color: #fff; }
        .form-group label { font-weight: 600; font-size: .9rem; color: #333; }
        .classroom-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
        .classroom-opt {
            position: relative; cursor: pointer;
        }
        .classroom-opt input { position: absolute; opacity: 0; pointer-events: none; }
        .classroom-opt .cl-label {
            display: flex; flex-direction: column; align-items: center; gap: 4px;
            padding: 12px 8px; border-radius: 10px; border: 2px solid #e2e8f0;
            text-align: center; transition: all .2s; font-size: .85rem; font-weight: 600;
            color: #64748b; background: #f8fafc;
        }
        .classroom-opt .cl-label .cl-icon { font-size: 1.2rem; }
        .classroom-opt input:checked + .cl-label {
            border-color: #16a085; background: #f0fdfa; color: #0f766e;
            box-shadow: 0 0 0 3px rgba(22,160,133,.15);
        }
        .classroom-opt:hover .cl-label { border-color: #94a3b8; }
        .cl-tri .cl-icon { color: #16a34a; }
        .cl-tho .cl-icon { color: #2563eb; }
        .cl-ek .cl-icon { color: #7c3aed; }
        .pwd-wrap { position: relative; }
        .pwd-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #94a3b8; cursor: pointer; font-size: .9rem;
        }
        .pwd-toggle:hover { color: #64748b; }
        .strength-bar { height: 4px; border-radius: 2px; background: #e2e8f0; margin-top: 6px; overflow: hidden; }
        .strength-fill { height: 100%; border-radius: 2px; transition: width .3s, background .3s; width: 0; }
        .strength-text { font-size: .75rem; margin-top: 3px; color: #94a3b8; }
    </style>
</head>
<body>
<div class="reg-card">
    <div class="reg-header">
        <div class="icon"><i class="fas fa-user-plus"></i></div>
        <h4 class="mb-1 font-weight-bold">สมัครสมาชิก</h4>
        <small style="opacity:.8;">สร้างบัญชีเพื่อบันทึกผลสอบและรับใบประกาศ</small>
    </div>
    <div class="reg-body">
        @if($errors->any())
            <div class="alert alert-danger py-2" style="font-size:.88rem;">
                <i class="fas fa-exclamation-circle mr-1"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="regForm">
            @csrf

            {{-- ชื่อ --}}
            <div class="form-group">
                <label><i class="fas fa-user mr-1 text-muted"></i>ชื่อ-นามสกุล</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required autofocus placeholder="กรอกชื่อ-นามสกุล">
            </div>

            {{-- อีเมล --}}
            <div class="form-group">
                <label><i class="fas fa-envelope mr-1 text-muted"></i>อีเมล</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required placeholder="กรอกอีเมล">
            </div>

            {{-- ระดับชั้น --}}
            <div class="form-group">
                <label><i class="fas fa-graduation-cap mr-1 text-muted"></i>ระดับชั้น</label>
                <div class="classroom-grid">
                    @foreach(\App\Classroom::active()->get() as $cl)
                    @php
                        $icons = ['ธ.ศ.ตรี' => 'fa-seedling', 'ธ.ศ.โท' => 'fa-book-reader', 'ธ.ศ.เอก' => 'fa-crown'];
                        $cls   = ['ธ.ศ.ตรี' => 'cl-tri', 'ธ.ศ.โท' => 'cl-tho', 'ธ.ศ.เอก' => 'cl-ek'];
                    @endphp
                    <label class="classroom-opt {{ $cls[$cl->name] ?? '' }}">
                        <input type="radio" name="classroom_id" value="{{ $cl->id }}"
                               {{ old('classroom_id') == $cl->id ? 'checked' : '' }}>
                        <span class="cl-label">
                            <i class="fas {{ $icons[$cl->name] ?? 'fa-folder' }} cl-icon"></i>
                            {{ $cl->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('classroom_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- รหัสผ่าน --}}
            <div class="form-group">
                <label><i class="fas fa-lock mr-1 text-muted"></i>รหัสผ่าน</label>
                <div class="pwd-wrap">
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required placeholder="อย่างน้อย 8 ตัวอักษร" minlength="8">
                    <button type="button" class="pwd-toggle" onclick="togglePwd('password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                <div class="strength-text" id="strengthText"></div>
            </div>

            {{-- ยืนยันรหัสผ่าน --}}
            <div class="form-group">
                <label><i class="fas fa-lock mr-1 text-muted"></i>ยืนยันรหัสผ่าน</label>
                <div class="pwd-wrap">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" required placeholder="กรอกรหัสผ่านอีกครั้ง" minlength="8">
                    <button type="button" class="pwd-toggle" onclick="togglePwd('password_confirmation', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-register">
                <i class="fas fa-user-plus mr-1"></i> สมัครสมาชิก
            </button>
        </form>

        <hr>
        <p class="text-center mb-0 small">
            มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}" style="color:#16a085;font-weight:600;">เข้าสู่ระบบ</a>
        </p>
    </div>
</div>

<script>
function togglePwd(id, btn) {
    var inp = document.getElementById(id);
    var icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

document.getElementById('password').addEventListener('input', function() {
    var val = this.value;
    var score = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    var fill = document.getElementById('strengthFill');
    var text = document.getElementById('strengthText');
    var levels = [
        { w: '0%',   c: '#e2e8f0', t: '' },
        { w: '20%',  c: '#ef4444', t: 'อ่อนมาก' },
        { w: '40%',  c: '#f97316', t: 'อ่อน' },
        { w: '60%',  c: '#eab308', t: 'ปานกลาง' },
        { w: '80%',  c: '#22c55e', t: 'แข็งแรง' },
        { w: '100%', c: '#16a34a', t: 'แข็งแรงมาก' },
    ];
    var lv = val.length === 0 ? 0 : score;
    fill.style.width = levels[lv].w;
    fill.style.background = levels[lv].c;
    text.textContent = levels[lv].t;
    text.style.color = levels[lv].c;
});
</script>
</body>
</html>
