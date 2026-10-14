@extends('layouts.student')
@section('title','จัดอันดับ')
@section('content')

<style>
.rank-1 { background: linear-gradient(135deg,#fff8e1,#fffde7); border-left: 4px solid #f39c12; }
.rank-2 { background: linear-gradient(135deg,#f5f5f5,#fafafa); border-left: 4px solid #90a4ae; }
.rank-3 { background: linear-gradient(135deg,#fbe9e7,#fff3e0); border-left: 4px solid #a1590f; }
.period-btn { border-radius: 20px; padding: 6px 18px; font-size: .88rem; font-weight: 600; border: 2px solid #dee2e6; background: #fff; color: #555; text-decoration: none; transition: all .15s; }
.period-btn.active, .period-btn:hover { background: #16a085; border-color: #16a085; color: #fff; text-decoration: none; }
.medal-1 { color: #f39c12; }
.medal-2 { color: #90a4ae; }
.medal-3 { color: #a1590f; }
.stat-pill { background: #f0f4f8; border-radius: 10px; padding: 14px 20px; text-align: center; }
.stat-pill .num { font-size: 1.6rem; font-weight: 800; color: #16a085; }
.stat-pill .lbl { font-size: .78rem; color: #888; margin-top: 2px; }
.top3-card { border-radius: 16px; padding: 20px; text-align: center; background: #fff; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.avatar { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: 800; margin: 0 auto 8px; }
.progress-sm { height: 6px; border-radius: 3px; background: #eee; }
.progress-sm .bar { height: 100%; border-radius: 3px; background: linear-gradient(to right,#16a085,#1abc9c); }
.my-rank-badge { background: linear-gradient(135deg,#16a085,#1abc9c); color: #fff; border-radius: 8px; padding: 10px 16px; font-size: .9rem; }
</style>

<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap" style="gap:10px;">
    <h5 class="mb-0 font-weight-bold"><i class="fas fa-trophy mr-2 text-warning"></i>จัดอันดับผู้สอบ</h5>
    @if($myRank)
    <div class="my-rank-badge">
        <i class="fas fa-map-marker-alt mr-1"></i>อันดับของคุณ: <strong>#{{ $myRank }}</strong>
    </div>
    @endif
</div>

{{-- Overview stats --}}
<div class="row mb-4">
    <div class="col-4">
        <div class="stat-pill">
            <div class="num">{{ number_format($overview['total_attempts']) }}</div>
            <div class="lbl">ครั้งที่สอบ</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-pill">
            <div class="num">{{ number_format($overview['total_passed']) }}</div>
            <div class="lbl">ผ่านแล้ว</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-pill">
            <div class="num">{{ number_format($overview['unique_users']) }}</div>
            <div class="lbl">ผู้เข้าสอบ</div>
        </div>
    </div>
</div>

{{-- Period filter --}}
<div class="d-flex flex-wrap mb-4" style="gap:8px;">
    @foreach(['day'=>'วันนี้','week'=>'สัปดาห์นี้','month'=>'เดือนนี้','year'=>'ปีนี้','all'=>'ตลอดเวลา'] as $key=>$label)
    <a href="{{ route('student.leaderboard.index', ['period'=>$key]) }}"
       class="period-btn {{ $period===$key ? 'active' : '' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Top 3 highlight --}}
@if($rankings->count() >= 3)
<div class="row mb-4">
    {{-- อันดับ 2 --}}
    <div class="col-4 mt-4">
        <div class="top3-card">
            <div class="avatar" style="background:#e8eaf6;color:#5c6bc0;">{{ mb_substr($rankings[1]->user->name,0,1) }}</div>
            <div class="font-weight-bold small mb-1">{{ $rankings[1]->user->name }}</div>
            <div style="font-size:1.4rem;" class="medal-2"><i class="fas fa-medal"></i></div>
            <div class="font-weight-bold text-secondary">{{ number_format($rankings[1]->best_percent,1) }}%</div>
            <small class="text-muted">{{ $rankings[1]->pass_count }} ผ่าน</small>
        </div>
    </div>
    {{-- อันดับ 1 --}}
    <div class="col-4">
        <div class="top3-card" style="border: 2px solid #f39c12;">
            <div style="font-size:1.6rem;" class="medal-1 mb-1"><i class="fas fa-crown"></i></div>
            <div class="avatar" style="background:#fff8e1;color:#f39c12;width:60px;height:60px;font-size:1.5rem;">{{ mb_substr($rankings[0]->user->name,0,1) }}</div>
            <div class="font-weight-bold mb-1">{{ $rankings[0]->user->name }}</div>
            <div style="font-size:1.6rem;" class="medal-1"><i class="fas fa-medal"></i></div>
            <div class="font-weight-bold text-warning h5 mb-0">{{ number_format($rankings[0]->best_percent,1) }}%</div>
            <small class="text-muted">{{ $rankings[0]->pass_count }} ผ่าน</small>
        </div>
    </div>
    {{-- อันดับ 3 --}}
    <div class="col-4 mt-4">
        <div class="top3-card">
            <div class="avatar" style="background:#fbe9e7;color:#a1590f;">{{ mb_substr($rankings[2]->user->name,0,1) }}</div>
            <div class="font-weight-bold small mb-1">{{ $rankings[2]->user->name }}</div>
            <div style="font-size:1.4rem;" class="medal-3"><i class="fas fa-medal"></i></div>
            <div class="font-weight-bold" style="color:#a1590f;">{{ number_format($rankings[2]->best_percent,1) }}%</div>
            <small class="text-muted">{{ $rankings[2]->pass_count }} ผ่าน</small>
        </div>
    </div>
</div>
@endif

{{-- Full ranking table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list-ol mr-1"></i>ตารางอันดับ</span>
        <small class="text-muted">แสดงสูงสุด 50 อันดับ</small>
    </div>
    @forelse($rankings as $i => $r)
    @php $rank = $i + 1; @endphp
    <div class="d-flex align-items-center px-3 py-2 border-top {{ $rank===1?'rank-1':($rank===2?'rank-2':($rank===3?'rank-3':'')) }} {{ (int) auth()->id() === (int) $r->user_id ? 'bg-light' : '' }}">
        {{-- rank --}}
        <div style="width:36px;font-weight:800;font-size:{{ $rank<=3?'1.2rem':'.95rem' }};color:{{ $rank===1?'#f39c12':($rank===2?'#607d8b':($rank===3?'#a1590f':'#999')) }};">
            @if($rank===1) <i class="fas fa-crown"></i>
            @elseif($rank<=3) {{ $rank }}
            @else {{ $rank }}
            @endif
        </div>
        {{-- avatar --}}
        <div class="avatar mr-3" style="width:38px;height:38px;font-size:.95rem;background:{{ $rank===1?'#fff8e1':($rank===2?'#eceff1':($rank===3?'#fbe9e7':'#e8f5e9')) }};color:{{ $rank===1?'#f39c12':($rank===2?'#607d8b':($rank===3?'#a1590f':'#2e7d32')) }};">
            {{ mb_substr($r->user->name,0,1) }}
        </div>
        {{-- name --}}
        <div class="flex-grow-1">
            <div class="font-weight-bold" style="font-size:.95rem;">
                {{ $r->user->name }}
                @if((int) auth()->id() === (int) $r->user_id)
                    <span class="badge badge-success ml-1" style="font-size:.7rem;">คุณ</span>
                @endif
            </div>
            <div style="font-size:.78rem;color:#888;">
                สอบ {{ $r->attempt_count }} ครั้ง &bull; ผ่าน {{ $r->pass_count }} ครั้ง
            </div>
            <div class="progress-sm mt-1" style="max-width:160px;">
                <div class="bar" style="width:{{ min($r->best_percent,100) }}%;"></div>
            </div>
        </div>
        {{-- scores --}}
        <div class="text-right ml-3">
            <div class="font-weight-bold" style="font-size:1.05rem;color:{{ $rank===1?'#f39c12':'#16a085' }};">
                {{ number_format($r->best_percent,1) }}%
            </div>
            <div style="font-size:.75rem;color:#aaa;">เฉลี่ย {{ number_format($r->avg_percent,1) }}%</div>
        </div>
        {{-- link --}}
        <a href="{{ route('student.leaderboard.show', $r->user_id) }}" class="btn btn-sm btn-outline-secondary ml-3" style="font-size:.78rem;">
            <i class="fas fa-chart-bar"></i>
        </a>
    </div>
    @empty
    <div class="card-body text-center text-muted py-5">
        <i class="fas fa-trophy fa-2x mb-2 d-block text-warning opacity-50"></i>
        ยังไม่มีข้อมูลในช่วงเวลานี้
    </div>
    @endforelse
</div>

@endsection
