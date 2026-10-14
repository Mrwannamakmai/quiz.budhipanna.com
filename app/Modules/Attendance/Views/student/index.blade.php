@extends('layouts.student')
@section('title', 'เช็คชื่อเข้าเรียน')

@section('content')
@include('attendance::_styles')
@php use App\Modules\Attendance\Support\Attendance; $low = $summary['percent'] !== null && $summary['percent'] < $minPercent; @endphp

<div class="row">
    <div class="col-lg-5 mb-3">
        @if($selfCheckin)
        <div class="at-card at-card-pad">
            <h5 class="font-weight-bold mb-1" style="color:#1e293b;"><i class="fas fa-user-check mr-1" style="color:#16a085;"></i> เช็คชื่อเข้าเรียน</h5>
            <div class="at-muted mb-3">กรอกรหัส 6 ตัวที่ครูแสดงในห้องเรียน</div>
            @if(!$user->classroom_id)
                <div class="alert alert-warning mb-0">บัญชีของคุณยังไม่ได้อยู่ในห้องเรียน กรุณาติดต่อครู</div>
            @else
            <form method="POST" action="{{ route('attendance.student.checkin') }}">
                @csrf
                <input type="text" name="code" value="{{ old('code') }}" maxlength="12" autocomplete="off" autofocus required
                       class="form-control form-control-lg text-center {{ $errors->has('code') ? 'is-invalid' : '' }}"
                       style="font-family:ui-monospace,Menlo,Consolas,monospace;font-size:1.8rem;letter-spacing:.3em;text-transform:uppercase;" placeholder="XXXXXX">
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <button class="at-btn green btn-block mt-3 justify-content-center" style="width:100%;padding:12px;"><i class="fas fa-check"></i> เช็คชื่อ</button>
            </form>
            @endif
        </div>
        @endif
    </div>
    <div class="col-lg-7 mb-3">
        <div class="at-card at-card-pad h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="font-weight-bold mb-0" style="color:#1e293b;">สรุปเวลาเรียนของฉัน</h5>
                    <div class="at-muted">{{ optional($user->classroom)->name }}</div>
                </div>
                <div class="text-right">
                    <div style="font-size:2rem;font-weight:800;line-height:1;color:{{ $summary['percent'] === null ? '#94a3b8' : ($low ? '#dc2626' : '#16a34a') }}">
                        {{ $summary['percent'] === null ? '-' : $summary['percent'] . '%' }}</div>
                    <div class="at-muted">เกณฑ์ {{ rtrim(rtrim(number_format($minPercent, 1), '0'), '.') }}%</div>
                </div>
            </div>
            <div class="at-stats mb-0">
                @foreach(Attendance::statuses() as $k => $st)
                    <div class="at-stat"><span class="v" style="color:{{ $st['color'] }}">{{ $summary[$k] }}</span><span class="l">{{ $st['label'] }}</span></div>
                @endforeach
            </div>
            @if($low)<div class="alert alert-danger mt-3 mb-0 py-2"><i class="fas fa-exclamation-triangle mr-1"></i> เวลาเรียนต่ำกว่าเกณฑ์ กรุณาติดต่อครูประจำวิชา</div>@endif
        </div>
    </div>
</div>

<div class="at-card at-table">
    <table class="table">
        <thead><tr><th>วันที่</th><th>เวลา</th><th>วิชา / คาบ</th><th class="text-center">สถานะ</th><th>หมายเหตุ</th></tr></thead>
        <tbody>
        @forelse($records as $r)
            @php $info = Attendance::status($r->status); @endphp
            <tr>
                <td class="text-nowrap">{{ $r->session->session_date->format('d/m/Y') }}</td>
                <td>{{ $r->session->start_time ? substr($r->session->start_time, 0, 5) : '-' }}</td>
                <td>{{ $r->session->label() }}</td>
                <td class="text-center"><span class="at-pill" style="background:{{ $info['bg'] }};color:{{ $info['color'] }}">{{ $info['label'] }}</span>
                    @if($r->method === 'self')<div class="at-muted">เช็คเอง {{ Attendance::local($r->checked_in_at) }}</div>@endif</td>
                <td class="at-muted">{{ $r->note }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center at-muted py-4">ยังไม่มีประวัติการเช็คชื่อ</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
