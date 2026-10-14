@extends('layouts.student')
@section('title','สถิติ — {{ $user->name }}')
@section('content')

<style>
.stat-card { border-radius: 14px; padding: 20px; text-align: center; background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.07); }
.stat-card .num { font-size: 2rem; font-weight: 800; }
.stat-card .lbl { font-size: .8rem; color: #888; margin-top: 4px; }
.circle-wrap { position: relative; width: 110px; height: 110px; margin: 0 auto 8px; }
.circle-wrap svg { transform: rotate(-90deg); }
.circle-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); text-align: center; }
.heat-cell { width: 20px; height: 20px; border-radius: 3px; display: inline-block; margin: 1px; }
.exam-row { padding: 12px 16px; border-radius: 10px; background: #f8fffe; margin-bottom: 8px; }
.exam-row .bar-wrap { height: 8px; background: #eee; border-radius: 4px; margin-top: 6px; }
.exam-row .bar-fill { height: 100%; border-radius: 4px; background: linear-gradient(to right,#16a085,#1abc9c); }
.attempt-row { font-size: .88rem; border-bottom: 1px solid #f0f0f0; padding: 8px 0; }
.pass-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 6px; }
</style>

{{-- Header --}}
<div class="d-flex align-items-center mb-4 flex-wrap" style="gap:12px;">
    <a href="{{ route('student.leaderboard.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i>กลับ
    </a>
    <div class="d-flex align-items-center" style="gap:12px;">
        <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#16a085,#1abc9c);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;">
            {{ mb_substr($user->name,0,1) }}
        </div>
        <div>
            <h5 class="mb-0 font-weight-bold">{{ $user->name }}</h5>
            <small class="text-muted">{{ $user->classroom->name ?? 'ไม่มีห้องเรียน' }}</small>
        </div>
    </div>
    @if((int) auth()->id() === (int) $user->id)
        <span class="badge badge-success" style="font-size:.85rem;">คุณ</span>
    @endif
</div>

{{-- Top stats --}}
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card">
            <div class="num text-primary">{{ $totalAttempts }}</div>
            <div class="lbl">ครั้งที่สอบ</div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card">
            <div class="num text-success">{{ $passCount }}</div>
            <div class="lbl">ผ่านแล้ว</div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card">
            <div class="num text-danger">{{ $failCount }}</div>
            <div class="lbl">ยังไม่ผ่าน</div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card">
            <div class="num" style="color:#f39c12;">{{ $bestPercent }}%</div>
            <div class="lbl">คะแนนสูงสุด</div>
        </div>
    </div>
</div>

<div class="row mb-4">
    {{-- Pass rate circle --}}
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <h6 class="font-weight-bold mb-3">อัตราการผ่าน</h6>
                @php $r = 48; $circ = 2 * M_PI * $r; $dash = ($passRate / 100) * $circ; @endphp
                <div class="circle-wrap">
                    <svg width="110" height="110" viewBox="0 0 110 110">
                        <circle cx="55" cy="55" r="{{ $r }}" fill="none" stroke="#eee" stroke-width="10"/>
                        <circle cx="55" cy="55" r="{{ $r }}" fill="none"
                            stroke="{{ $passRate >= 70 ? '#16a085' : ($passRate >= 50 ? '#f39c12' : '#e74c3c') }}"
                            stroke-width="10"
                            stroke-dasharray="{{ number_format($dash,2) }} {{ number_format($circ,2) }}"
                            stroke-linecap="round"/>
                    </svg>
                    <div class="circle-center">
                        <div style="font-size:1.4rem;font-weight:800;color:{{ $passRate >= 70 ? '#16a085' : ($passRate >= 50 ? '#f39c12' : '#e74c3c') }};">{{ $passRate }}%</div>
                        <div style="font-size:.7rem;color:#999;">ผ่าน</div>
                    </div>
                </div>
                <div class="text-muted small mt-2">เฉลี่ย {{ $avgPercent }}% ต่อครั้ง</div>
            </div>
        </div>
    </div>

    {{-- Activity heatmap 30 วัน --}}
    <div class="col-md-8 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="font-weight-bold mb-3"><i class="fas fa-fire mr-1 text-danger"></i>กิจกรรม 30 วันล่าสุด</h6>
                <div style="display:flex;flex-wrap:wrap;gap:3px;">
                    @for($d = 29; $d >= 0; $d--)
                    @php
                        $date = \Carbon\Carbon::today()->subDays($d)->format('Y-m-d');
                        $cnt  = $recent30->get($date, 0);
                        $opacity = $cnt === 0 ? '0.08' : ($cnt === 1 ? '0.35' : ($cnt === 2 ? '0.65' : '1'));
                        $title = $date . ': ' . $cnt . ' ครั้ง';
                    @endphp
                    <div class="heat-cell" title="{{ $title }}"
                         style="background:{{ $cnt > 0 ? '#16a085' : '#ccc' }};opacity:{{ $opacity }};"></div>
                    @endfor
                </div>
                <div class="d-flex align-items-center mt-2" style="gap:6px;font-size:.75rem;color:#999;">
                    <span>น้อย</span>
                    @foreach(['0.08','0.35','0.65','1'] as $op)
                    <div style="width:14px;height:14px;border-radius:2px;background:#16a085;opacity:{{ $op }};"></div>
                    @endforeach
                    <span>มาก</span>
                </div>
                @if($totalAttempts > 0)
                <div class="mt-3 small text-muted">
                    สอบครั้งล่าสุด: <strong>{{ $attempts->first()->submitted_at->diffForHumans() }}</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Per exam set --}}
@if($byExamSet->count())
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header"><i class="fas fa-layer-group mr-1"></i>สถิติรายชุดข้อสอบ</div>
    <div class="card-body">
        @foreach($byExamSet as $es)
        <div class="exam-row">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="font-weight-bold" style="font-size:.92rem;">{{ $es['exam_set']->name ?? '(ลบแล้ว)' }}</div>
                    <small class="text-muted">
                        สอบ {{ $es['count'] }} ครั้ง &bull;
                        <span class="text-success">ผ่าน {{ $es['pass_count'] }}</span> /
                        <span class="text-danger">ไม่ผ่าน {{ $es['count'] - $es['pass_count'] }}</span>
                    </small>
                </div>
                <span class="font-weight-bold {{ $es['best_percent'] >= 80 ? 'text-success' : ($es['best_percent'] >= 60 ? 'text-warning' : 'text-danger') }}" style="font-size:1.1rem;">
                    {{ $es['best_percent'] }}%
                </span>
            </div>
            <div class="bar-wrap mt-1">
                <div class="bar-fill" style="width:{{ min($es['best_percent'],100) }}%;"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- History --}}
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history mr-1"></i>ประวัติการสอบ</span>
        <small class="text-muted">{{ $totalAttempts }} ครั้ง</small>
    </div>
    <div class="card-body p-0">
        @forelse($attempts->take(20) as $a)
        @php $pct = $a->total_points > 0 ? round($a->score / $a->total_points * 100, 1) : 0; @endphp
        <div class="attempt-row px-3 d-flex align-items-center">
            <span class="pass-dot" style="background:{{ $a->passed ? '#27ae60' : '#e74c3c' }};"></span>
            <div class="flex-grow-1">
                <div style="font-size:.88rem;font-weight:600;">{{ $a->examSet->name ?? '(ลบแล้ว)' }}</div>
                <div style="font-size:.75rem;color:#aaa;">{{ $a->submitted_at->format('d M Y H:i') }}</div>
            </div>
            <span class="font-weight-bold {{ $a->passed ? 'text-success' : 'text-danger' }}" style="font-size:.95rem;">
                {{ $pct }}%
            </span>
        </div>
        @empty
        <div class="text-center text-muted py-4">ยังไม่มีประวัติการสอบ</div>
        @endforelse
        @if($attempts->count() > 20)
        <div class="text-center py-2 text-muted small">แสดง 20 จาก {{ $attempts->count() }} ครั้ง</div>
        @endif
    </div>
</div>

@endsection
