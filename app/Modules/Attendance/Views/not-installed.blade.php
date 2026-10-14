@extends(auth()->user()->isStudent() ? 'layouts.student' : (auth()->user()->isSuperAdmin() ? 'layouts.admin' : 'layouts.teacher'))
@section('title', 'ยังไม่ได้ติดตั้งโมดูลเช็คชื่อ')
@section('content')
<div style="max-width:640px;margin:40px auto;background:#fff;border-radius:12px;padding:28px;border:1.5px solid #f1f5f9;box-shadow:0 1px 6px rgba(0,0,0,.06);">
    <h5 class="font-weight-bold"><i class="fas fa-tools mr-1" style="color:#d97706;"></i> โมดูลเช็คชื่อยังไม่พร้อมใช้งาน</h5>
    <p class="mb-2" style="color:#475569;">ยังไม่ได้สร้างตารางในฐานข้อมูล</p>
    @if(!auth()->user()->isStudent())
    <ol style="color:#475569;font-size:.9rem;">
        <li>เข้า phpMyAdmin เลือกฐานข้อมูลของระบบ</li>
        <li>Import ไฟล์ <code>myapp/database/install_modules_2026_10_01.sql</code></li>
        <li>หรือถ้ามี SSH: <code>php artisan migrate</code></li>
    </ol>
    @else
    <p class="mb-0" style="color:#475569;">กรุณาแจ้งครูหรือผู้ดูแลระบบ</p>
    @endif
</div>
@endsection
