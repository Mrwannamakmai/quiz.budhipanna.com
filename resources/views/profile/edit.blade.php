@extends(auth()->user()->isSuperAdmin() ? 'layouts.admin' : (auth()->user()->isTeacher() ? 'layouts.teacher' : 'layouts.student'))

@section('title', 'แก้ไขโปรไฟล์')
@section('page-title', 'ข้อมูลส่วนตัว')

@section('content')
<style>
.profile-container {
    max-width: 800px;
    margin: 0 auto;
}

.profile-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
    margin-bottom: 24px;
    overflow: hidden;
}

.profile-hero {
    background: linear-gradient(135deg, #1e3a4a 0%, #2c5364 100%);
    padding: 30px 24px;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.profile-avatar-circle {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: 3px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    flex-shrink: 0;
}

.profile-hero-info h4 {
    margin: 0 0 4px 0;
    font-weight: 700;
    font-size: 1.35rem;
    color: #ffffff;
}

.profile-hero-info p {
    margin: 0;
    opacity: 0.85;
    font-size: 0.9rem;
}

.profile-role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #ffffff;
    margin-top: 6px;
}

.profile-body {
    padding: 28px 24px;
}

.profile-form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
}

.profile-input {
    width: 100%;
    padding: 10px 16px;
    font-size: 0.95rem;
    color: #1e293b;
    background-color: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    transition: all 0.2s ease;
    outline: none;
}

.profile-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

.btn-profile-submit {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 10px;
    padding: 11px 24px;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.btn-profile-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
}

.profile-meta-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-top: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
</style>

<div class="profile-container">
    <div class="profile-card">
        {{-- Profile Hero Banner --}}
        <div class="profile-hero">
            <div class="profile-avatar-circle">
                @php
                    $initial = mb_substr(auth()->user()->name ?: 'U', 0, 1, 'UTF-8');
                @endphp
                {{ $initial }}
            </div>
            <div class="profile-hero-info">
                <h4>{{ auth()->user()->name }}</h4>
                <p><i class="fas fa-envelope mr-1 opacity-75"></i> {{ auth()->user()->email }}</p>
                <div class="profile-role-badge">
                    @if(auth()->user()->isSuperAdmin())
                        <i class="fas fa-shield-alt"></i> Super Admin
                    @elseif(auth()->user()->isTeacher())
                        <i class="fas fa-chalkboard-teacher"></i> ครู / ผู้สอน
                    @else
                        <i class="fas fa-user-graduate"></i> นักเรียน
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="profile-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group mb-4">
                    <label class="profile-form-label" for="name">
                        <i class="fas fa-user text-primary"></i> ชื่อ - นามสกุล ที่แสดง
                    </label>
                    <input id="name" name="name" type="text" class="profile-input" 
                           value="{{ old('name', auth()->user()->name) }}" 
                           placeholder="กรอกชื่อและนามสกุลจริง" required maxlength="255">
                    <small class="form-text text-muted mt-2">
                        ชื่อนี้จะใช้แสดงในระบบ รายชื่อผู้สอน หรือในผลการสอบของนักเรียน
                    </small>
                </div>

                <div class="form-group mb-4">
                    <label class="profile-form-label" for="email">
                        <i class="fas fa-envelope text-primary"></i> ที่อยู่อีเมล (ใช้สำหรับเข้าสู่ระบบ)
                    </label>
                    <input id="email" name="email" type="email" class="profile-input" 
                           value="{{ old('email', auth()->user()->email) }}" 
                           placeholder="example@domain.com" required maxlength="255">
                    <small class="form-text text-muted mt-2">
                        อีเมลสำหรับรับการแจ้งเตือนและการเข้าใช้งานระบบ
                    </small>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap pt-3 border-top" style="gap: 12px;">
                    <div class="text-muted" style="font-size: 0.85rem;">
                        <i class="fas fa-shield-alt mr-1 text-success"></i> ข้อมูลของคุณจะได้รับการบันทึกอย่างปลอดภัย
                    </div>
                    <button type="submit" class="btn-profile-submit">
                        <i class="fas fa-save"></i> บันทึกข้อมูลโปรไฟล์
                    </button>
                </div>
            </form>

            <div class="profile-meta-box">
                <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                    <i class="fas fa-calendar-check mr-2 text-primary"></i>
                    <span>วันที่สร้างบัญชี: {{ auth()->user()->created_at ? auth()->user()->created_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="d-flex align-items-center text-muted" style="font-size: 0.85rem;">
                    <i class="fas fa-id-badge mr-2 text-primary"></i>
                    <span>รหัสผู้ใช้: #{{ auth()->user()->id }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
