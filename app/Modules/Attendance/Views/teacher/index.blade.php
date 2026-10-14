@extends(auth()->user()->isSuperAdmin() ? 'layouts.admin' : 'layouts.teacher')
@section('title', 'เช็คชื่อเข้าเรียน')
@section('page-title', 'เช็คชื่อเข้าเรียน')

@section('content')
@include('attendance::_styles')
@php use App\Modules\Attendance\Support\Attendance; @endphp

<div class="at-top">
    <h5><i class="fas fa-user-check mr-1"></i> คาบเรียนที่เช็คชื่อ</h5>
    <div class="d-flex" style="gap:8px;">
        <a href="{{ route('attendance.teacher.report', ['classroom_id' => $classroomId ?: null]) }}" class="at-btn ghost"><i class="fas fa-table"></i> รายงานเวลาเรียน</a>
        <a href="{{ route('attendance.teacher.sessions.create', ['classroom_id' => $classroomId ?: null]) }}" class="at-btn"><i class="fas fa-plus"></i> เช็คชื่อคาบใหม่</a>
    </div>
</div>

<form method="GET" class="at-card at-filter">
    <div>
        <label>ห้องเรียน</label>
        <select name="classroom_id" class="form-control form-control-sm" onchange="this.form.submit()">
            <option value="">ทุกห้องที่รับผิดชอบ</option>
            @foreach($classrooms as $c)
                <option value="{{ $c->id }}" {{ (int) $classroomId === (int) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>เดือน</label>
        <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm" onchange="this.form.submit()">
    </div>
</form>

@if($classrooms->isEmpty())
    <div class="at-empty"><i class="fas fa-door-closed"></i>
        ยังไม่มีห้องเรียนที่คุณรับผิดชอบ<br><span class="at-muted">ให้แอดมินกำหนดวิชา (หลักสูตร) ของคุณให้ผูกกับห้องเรียน หรือเปลี่ยนการตั้งค่า "ครูเช็คชื่อได้ห้องใดบ้าง"</span>
    </div>
@elseif($sessions->isEmpty())
    <div class="at-empty"><i class="far fa-calendar-check"></i>ยังไม่มีการเช็คชื่อในเดือนนี้</div>
@else
    <div class="at-card at-table">
        <table class="table">
            <thead><tr>
                <th>วันที่</th><th>เวลา</th><th>ห้อง</th><th>วิชา / คาบ</th>
                @foreach(Attendance::statuses() as $k => $s)<th class="text-center">{{ $s['label'] }}</th>@endforeach
                <th class="text-center">ยังไม่เช็ค</th><th class="text-center">รหัส</th><th></th>
            </tr></thead>
            <tbody>
            @foreach($sessions as $s)
                @php
                    $c = ($counts[$s->id] ?? collect())->pluck('c', 'status');
                    $marked = $c->sum();
                    $total = (int) ($studentCounts[$s->classroom_id] ?? 0);
                @endphp
                <tr>
                    <td class="text-nowrap">{{ $s->session_date->format('d/m/Y') }}</td>
                    <td>{{ $s->start_time ? substr($s->start_time, 0, 5) : '-' }}</td>
                    <td>{{ optional($s->classroom)->name }}</td>
                    <td><a href="{{ route('attendance.teacher.sessions.show', $s) }}" class="font-weight-bold">{{ $s->label() }}</a></td>
                    @foreach(Attendance::statuses() as $k => $st)
                        <td class="text-center">
                            @if($c[$k] ?? 0)<span class="at-pill" style="background:{{ $st['bg'] }};color:{{ $st['color'] }}">{{ $c[$k] }}</span>@else<span class="at-muted">0</span>@endif
                        </td>
                    @endforeach
                    <td class="text-center">{{ max(0, $total - $marked) }}</td>
                    <td class="text-center">
                        @if($s->isCheckinOpen())<span class="at-pill" style="background:#dcfce7;color:#15803d">เปิด</span>@else<span class="at-muted">-</span>@endif
                    </td>
                    <td class="text-right"><a href="{{ route('attendance.teacher.sessions.show', $s) }}" class="at-btn ghost" style="padding:5px 12px;">เช็คชื่อ</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
