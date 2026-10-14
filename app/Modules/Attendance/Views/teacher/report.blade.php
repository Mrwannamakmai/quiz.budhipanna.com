@extends(auth()->user()->isSuperAdmin() ? 'layouts.admin' : 'layouts.teacher')
@section('title', 'รายงานเวลาเรียน')
@section('page-title', 'รายงานเวลาเรียน')

@section('content')
@include('attendance::_styles')
@php use App\Modules\Attendance\Support\Attendance; @endphp
<style>
.at-matrix th.rot { writing-mode:vertical-rl; transform:rotate(180deg); font-size:.7rem; padding:8px 4px; max-height:140px; }
.at-matrix td.cell { text-align:center; padding:6px 4px; font-weight:700; font-size:.78rem; }
.at-matrix td.sticky, .at-matrix th.sticky { position:sticky; left:0; background:#fff; z-index:1; }
.at-low { background:#fef2f2 !important; }
</style>

<div class="at-top">
    <h5><i class="fas fa-table mr-1"></i> รายงานเวลาเรียน @if($classroom)<span class="at-muted">· {{ $classroom->name }}</span>@endif</h5>
    <div class="d-flex" style="gap:8px;">
        <a href="{{ route('attendance.teacher.index') }}" class="at-btn ghost"><i class="fas fa-arrow-left"></i> รายการคาบ</a>
        @if($classroom)
        <a href="{{ route('attendance.teacher.report.export', request()->query() + ['classroom_id' => $classroom->id, 'from' => $from, 'to' => $to]) }}" class="at-btn green"><i class="fas fa-file-excel"></i> ส่งออก CSV</a>
        @endif
    </div>
</div>

<form method="GET" class="at-card at-filter">
    <div><label>ห้องเรียน</label>
        <select name="classroom_id" class="form-control form-control-sm">
            @foreach($classrooms as $c)
                <option value="{{ $c->id }}" {{ $classroom && (int) $classroom->id === (int) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select></div>
    <div><label>วิชา</label>
        <select name="subject_id" class="form-control form-control-sm">
            <option value="">ทุกวิชา</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ (int) $subjectId === (int) $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
        </select></div>
    <div><label>ตั้งแต่</label><input type="date" name="from" value="{{ $from }}" class="form-control form-control-sm"></div>
    <div><label>ถึง</label><input type="date" name="to" value="{{ $to }}" class="form-control form-control-sm"></div>
    <div><button class="at-btn" style="padding:6px 16px;"><i class="fas fa-search"></i> แสดง</button></div>
</form>

<div class="d-flex flex-wrap mb-2" style="gap:10px;">
    @foreach($statuses as $k => $st)
        <span class="at-pill" style="background:{{ $st['bg'] }};color:{{ $st['color'] }}">{{ $st['short'] }} = {{ $st['label'] }}</span>
    @endforeach
    <span class="at-muted">เกณฑ์ขั้นต่ำ {{ rtrim(rtrim(number_format($minPercent, 1), '0'), '.') }}% (แถวสีแดง = ต่ำกว่าเกณฑ์)</span>
</div>

@if(!$classroom)
    <div class="at-empty"><i class="fas fa-door-closed"></i>ไม่มีห้องเรียนที่คุณรับผิดชอบ</div>
@elseif($sessions->isEmpty())
    <div class="at-empty"><i class="far fa-calendar"></i>ไม่มีคาบเรียนในช่วงวันที่เลือก</div>
@else
<div class="at-card at-table at-matrix">
    <table class="table table-bordered mb-0">
        <thead><tr>
            <th class="sticky">#</th><th class="sticky" style="left:36px;">ชื่อ-สกุล</th>
            @foreach($sessions as $s)
                <th class="rot" title="{{ $s->label() }}">
                    <a href="{{ route('attendance.teacher.sessions.show', $s) }}" style="color:#475569;">{{ $s->session_date->format('d/m') }}{{ $s->start_time ? ' ' . substr($s->start_time, 0, 5) : '' }}</a>
                </th>
            @endforeach
            @foreach($statuses as $k => $st)<th class="text-center">{{ $st['label'] }}</th>@endforeach
            <th class="text-center">ร้อยละ</th>
        </tr></thead>
        <tbody>
        @foreach($students as $i => $st)
            @php $sum = $summaries[$st->id]; $low = $sum['percent'] !== null && $sum['percent'] < $minPercent; @endphp
            <tr class="{{ $low ? 'at-low' : '' }}">
                <td class="sticky at-muted">{{ $i + 1 }}</td>
                <td class="sticky text-nowrap font-weight-bold" style="left:36px;">{{ $st->name }}</td>
                @foreach($sessions as $s)
                    @php $status = $matrix[$st->id][$s->id] ?? null; $info = Attendance::status($status); @endphp
                    <td class="cell" style="{{ $status ? 'background:' . $info['bg'] . ';color:' . $info['color'] : 'color:#cbd5e1' }}">{{ $status ? $info['short'] : '·' }}</td>
                @endforeach
                @foreach($statuses as $k => $sinfo)<td class="text-center">{{ $sum[$k] }}</td>@endforeach
                <td class="text-center font-weight-bold" style="color:{{ $sum['percent'] === null ? '#94a3b8' : ($low ? '#dc2626' : '#16a34a') }}">{{ $sum['percent'] === null ? '-' : $sum['percent'] . '%' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="at-muted mt-2">{{ $sessions->count() }} คาบ · {{ $students->count() }} คน · ช่อง "·" = ยังไม่ได้เช็ค (ไม่นำมาคิดร้อยละ)</div>
@endif
@endsection
