@extends(auth()->user()->isSuperAdmin() ? 'layouts.admin' : 'layouts.teacher')
@section('title', 'เช็คชื่อ — ' . $session->label())
@section('page-title', 'เช็คชื่อเข้าเรียน')

@section('content')
@include('attendance::_styles')
@php use App\Modules\Attendance\Support\Attendance; @endphp

<div class="at-top">
    <div>
        <h5>{{ $session->label() }} <span class="at-muted">· {{ optional($session->classroom)->name }}</span></h5>
        <div class="at-muted mt-1">
            <i class="far fa-calendar"></i> {{ $session->session_date->format('d/m/Y') }}
            @if($session->start_time) · <i class="far fa-clock"></i> {{ substr($session->start_time, 0, 5) }} น.@endif
            @if($session->creator) · โดย {{ $session->creator->name }}@endif
        </div>
        @if($session->note)<div class="mt-1" style="font-size:.85rem;color:#475569;">{{ $session->note }}</div>@endif
    </div>
    <div class="d-flex" style="gap:8px;">
        <a href="{{ route('attendance.teacher.index', ['classroom_id' => $session->classroom_id, 'month' => $session->session_date->format('Y-m')]) }}" class="at-btn ghost"><i class="fas fa-arrow-left"></i> รายการคาบ</a>
        <form method="POST" action="{{ route('attendance.teacher.sessions.destroy', $session) }}" onsubmit="return confirm('ลบคาบนี้และผลการเช็คชื่อทั้งหมด?')">
            @csrf @method('DELETE')
            <button class="at-btn ghost" style="color:#dc2626;"><i class="fas fa-trash-alt"></i> ลบคาบ</button>
        </form>
    </div>
</div>

<div class="at-stats">
    @foreach($statuses as $k => $st)
        <div class="at-stat"><span class="v" style="color:{{ $st['color'] }}">{{ $summary[$k] }}</span><span class="l">{{ $st['label'] }}</span></div>
    @endforeach
    <div class="at-stat"><span class="v" style="color:#64748b">{{ max(0, $students->count() - $summary['total']) }}</span><span class="l">ยังไม่เช็ค</span></div>
    <div class="at-stat"><span class="v" style="color:#1e293b">{{ $students->count() }}</span><span class="l">นักเรียนทั้งห้อง</span></div>
</div>

@if($selfCheckin)
<div class="at-card at-card-pad mb-3 d-flex flex-wrap align-items-center justify-content-between" style="gap:14px;">
    @if($session->isCheckinOpen())
        <div>
            <div class="at-muted">รหัสเช็คชื่อ (ให้นักเรียนกรอกที่เมนู "เช็คชื่อ")</div>
            <div class="at-code">{{ $session->checkin_code }}</div>
            <div class="at-muted">ปิดรับเวลา {{ Attendance::local($session->checkin_closes_at) }} น.
                · เหลือ <span id="at-remain" data-end="{{ $session->checkin_closes_at->timestamp }}">-</span></div>
        </div>
        <div class="d-flex" style="gap:8px;">
            <form method="POST" action="{{ route('attendance.teacher.sessions.open', $session) }}">@csrf
                <button class="at-btn ghost"><i class="fas fa-redo"></i> รหัสใหม่ / ต่อเวลา</button></form>
            <form method="POST" action="{{ route('attendance.teacher.sessions.close', $session) }}">@csrf
                <button class="at-btn red"><i class="fas fa-stop"></i> ปิดรับรหัส</button></form>
            <a href="{{ route('attendance.teacher.sessions.show', $session) }}" class="at-btn ghost" title="โหลดรายชื่อที่เช็คแล้ว"><i class="fas fa-sync-alt"></i></a>
        </div>
    @else
        <div>
            <div class="font-weight-bold" style="color:#334155;">ให้นักเรียนเช็คชื่อด้วยตนเอง</div>
            <div class="at-muted">ระบบจะสร้างรหัส 6 ตัว ใช้ได้ {{ \App\Support\Settings\Settings::get('attendance.checkin_window', 15) }} นาที
                — เช็คหลังเวลาเริ่มคาบเกิน {{ \App\Support\Settings\Settings::get('attendance.late_after', 15) }} นาทีนับเป็น "สาย"</div>
        </div>
        <form method="POST" action="{{ route('attendance.teacher.sessions.open', $session) }}">@csrf
            <button class="at-btn green"><i class="fas fa-key"></i> เปิดรหัสเช็คชื่อ</button></form>
    @endif
</div>
@endif

@if($students->isEmpty())
    <div class="at-empty"><i class="fas fa-users-slash"></i>ห้องนี้ยังไม่มีนักเรียน</div>
@else
<form method="POST" action="{{ route('attendance.teacher.sessions.marks', $session) }}">
    @csrf @method('PUT')
    <div class="d-flex flex-wrap align-items-center mb-2" style="gap:6px;">
        <span class="at-muted mr-1">ตั้งทุกคนที่ยังไม่เช็คเป็น:</span>
        @foreach($statuses as $k => $st)
            <button type="button" class="at-btn ghost" style="padding:4px 12px;color:{{ $st['color'] }}" onclick="atSetAll('{{ $k }}', false)">{{ $st['label'] }}</button>
        @endforeach
        <button type="button" class="at-btn ghost" style="padding:4px 12px;" onclick="atSetAll('present', true)">ทุกคน = มา</button>
    </div>

    <div class="at-card at-table">
        <table class="table">
            <thead><tr><th style="width:50px;">#</th><th>ชื่อ-สกุล</th><th>สถานะ</th><th>เวลาเช็คเอง</th><th>หมายเหตุ</th></tr></thead>
            <tbody>
            @foreach($students as $i => $st)
                @php
                    $rec = $records->get($st->id);
                    $current = old('status.' . $st->id, $rec ? $rec->status : ($defaultStatus !== 'none' ? $defaultStatus : null));
                @endphp
                <tr data-row data-marked="{{ $rec ? 1 : 0 }}">
                    <td class="at-muted">{{ $i + 1 }}</td>
                    <td class="font-weight-bold" style="color:#1e293b;">{{ $st->name }}</td>
                    <td>
                        <div class="at-radio">
                            @foreach($statuses as $k => $sinfo)
                                <input type="radio" id="s{{ $st->id }}_{{ $k }}" name="status[{{ $st->id }}]" value="{{ $k }}" {{ $current === $k ? 'checked' : '' }}>
                                <label for="s{{ $st->id }}_{{ $k }}" data-color="{{ $sinfo['color'] }}">{{ $sinfo['label'] }}</label>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-nowrap">
                        @if($rec && $rec->method === 'self')
                            <span class="at-pill" style="background:#eef2ff;color:#4338ca;"><i class="fas fa-mobile-alt mr-1"></i>{{ Attendance::local($rec->checked_in_at) }}</span>
                        @else<span class="at-muted">-</span>@endif
                    </td>
                    <td><input type="text" name="note[{{ $st->id }}]" value="{{ old('note.' . $st->id, $rec ? $rec->note : '') }}" maxlength="255" class="form-control form-control-sm" style="min-width:140px;"></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 d-flex justify-content-end">
        <button type="submit" class="at-btn"><i class="fas fa-save"></i> บันทึกการเช็คชื่อ</button>
    </div>
</form>
@endif

<script>
function atPaint() {
    document.querySelectorAll('.at-radio label').forEach(function (l) {
        var inp = document.getElementById(l.getAttribute('for'));
        var c = l.getAttribute('data-color');
        l.style.background = inp.checked ? c : '#fff';
        l.style.borderColor = inp.checked ? c : '#e2e8f0';
        l.style.color = inp.checked ? '#fff' : '#64748b';
    });
}
function atSetAll(status, force) {
    document.querySelectorAll('tr[data-row]').forEach(function (tr) {
        var anyChecked = tr.querySelector('input[type=radio]:checked');
        if (force || tr.getAttribute('data-marked') === '0' || !anyChecked) {
            var r = tr.querySelector('input[type=radio][value="' + status + '"]');
            if (r) r.checked = true;
        }
    });
    atPaint();
}
document.addEventListener('change', function (e) { if (e.target.matches('.at-radio input')) atPaint(); });
atPaint();
(function () {
    var el = document.getElementById('at-remain');
    if (!el) return;
    var end = parseInt(el.getAttribute('data-end'), 10) * 1000;
    function tick() {
        var s = Math.max(0, Math.round((end - Date.now()) / 1000));
        el.textContent = Math.floor(s / 60) + ':' + ('0' + (s % 60)).slice(-2) + ' นาที';
        if (s > 0) setTimeout(tick, 1000);
    }
    tick();
})();
</script>
@endsection
