@extends('layouts.teacher')
@section('title', 'ลงทะเบียนสอบธรรมศึกษา')
@section('page-title', 'ลงทะเบียนสอบธรรมศึกษา')

@section('content')
<style>
.er-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:18px}
.er-head{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:16px}
.er-head h5{font-size:1rem;font-weight:700;margin:0;color:#1e293b}
.badge-pending{background:#fbbf24;color:#78350f}
.badge-approved{background:#34d399;color:#064e3b}
.badge-rejected{background:#f87171;color:#7f1d1d}
</style>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="er-card">
    <div class="er-head">
        <div>
            <h5>ผู้สมัครสอบธรรมศึกษา</h5>
            <div class="text-muted small">
                @foreach ($statuses as $key => $label)
                    <span class="badge badge-{{ strtolower($key) }} mr-1">{{ $label }}: {{ $counts[$key] ?? 0 }}</span>
                @endforeach
            </div>
        </div>
        <div>
            <a href="{{ route('teacher.exam_registration.followup.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-phone-alt mr-1"></i>ติดตามผู้สมัคร</a>
            <a href="{{ route('teacher.exam_registration.print.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-print mr-1"></i>พิมพ์ใบสมัคร</a>
            <a href="{{ route('teacher.exam_registration.reports.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chart-pie mr-1"></i>รายงาน</a>
            @if ($canManage)
                <a href="{{ route('teacher.exam_registration.settings.period') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-qrcode mr-1"></i>ตั้งค่า/QR</a>
            @endif
        </div>
    </div>

    <form method="GET" class="form-row mb-3">
        <div class="col-md-3">
            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                <option value="">ทุกสถานะ</option>
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="exam_session_id" class="form-control form-control-sm" onchange="this.form.submit()">
                <option value="">ทุกรอบสอบ</option>
                @foreach ($sessions as $s)
                    <option value="{{ $s->id }}" {{ (string) request('exam_session_id') === (string) $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="ค้นหาชื่อ/เลขบัตร/เลขอ้างอิง" value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-sm btn-primary btn-block" type="submit">ค้นหา</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead>
                <tr>
                    <th>เลขอ้างอิง</th>
                    <th>ชื่อ-สกุล</th>
                    <th>เลขบัตรประชาชน</th>
                    <th>ชั้น/ช่วงที่สมัคร</th>
                    <th>สถานะ</th>
                    <th>วันที่สมัคร</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $r)
                    <tr>
                        <td>{{ $r->reference_code }}</td>
                        <td>{{ $r->fullName() }}</td>
                        <td>{{ $r->citizen_id }}</td>
                        <td>{{ $r->target_level }} / {{ $r->target_range }}</td>
                        <td><span class="badge badge-{{ strtolower($r->status) }}">{{ $r->statusLabel() }}</span></td>
                        <td>{{ optional($r->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="text-right">
                            <a href="{{ route('teacher.exam_registration.show', $r->id) }}" class="btn btn-sm btn-outline-primary">รายละเอียด</a>
                            @if ($canManage && $r->status === 'PENDING')
                                <form method="POST" action="{{ route('teacher.exam_registration.approve', $r->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('อนุมัติผู้สมัครนี้?')">อนุมัติ</button>
                                </form>
                                <form method="POST" action="{{ route('teacher.exam_registration.reject', $r->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('ไม่อนุมัติผู้สมัครนี้?')">ไม่อนุมัติ</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">ไม่มีข้อมูลผู้สมัคร</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $registrations->links() }}
</div>
@endsection
