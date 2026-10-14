@extends('layouts.teacher')
@section('title','วิเคราะห์พฤติกรรมนักเรียน')
@section('page-title','วิเคราะห์พฤติกรรมนักเรียน')

@section('content')
<style>
.stat-card      { border-radius:12px; padding:20px 24px; color:#fff; display:flex; align-items:center; gap:16px; }
.stat-icon      { width:52px; height:52px; border-radius:50%; background:rgba(255,255,255,.25); display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.stat-val       { font-size:2rem; font-weight:700; line-height:1; }
.stat-lbl       { font-size:.82rem; opacity:.85; margin-top:2px; }
.period-btn     { border-radius:8px; font-size:.85rem; padding:5px 14px; }
.period-btn.active { box-shadow:0 2px 8px rgba(0,0,0,.2); }
.student-row:hover { background:#f8f9fa; cursor:pointer; }
.rank-badge     { width:28px; height:28px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:.78rem; }
.chart-card     { border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.07); }
</style>

{{-- ── Summary cards ─────────────────────────────────────────────── --}}
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#667eea,#764ba2);">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div><div class="stat-val">{{ $totalStudents }}</div><div class="stat-lbl">นักเรียนทั้งหมด</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f093fb,#f5576c);">
            <div class="stat-icon"><i class="fas fa-pen-alt"></i></div>
            <div><div class="stat-val">{{ $totalAttempts }}</div><div class="stat-lbl">ครั้งที่สอบทั้งหมด</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#4facfe,#00f2fe);">
            <div class="stat-icon"><i class="fas fa-check-double"></i></div>
            <div><div class="stat-val">{{ $passRate }}%</div><div class="stat-lbl">อัตราผ่าน</div></div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#43e97b,#38f9d7);">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div><div class="stat-val">{{ $avgScore }}%</div><div class="stat-lbl">คะแนนเฉลี่ย</div></div>
        </div>
    </div>
</div>

{{-- ── Period selector ───────────────────────────────────────────── --}}
<div class="d-flex align-items-center mb-3 flex-wrap" style="gap:.5rem;">
    <span class="text-muted mr-2"><i class="fas fa-calendar-alt mr-1"></i>ช่วงเวลา:</span>
    @foreach(['day'=>'รายวัน (30 วัน)','week'=>'รายสัปดาห์ (12 สัปดาห์)','month'=>'รายเดือน (12 เดือน)','year'=>'รายปี (5 ปี)'] as $p => $label)
    <a href="{{ route('teacher.analytics.index', ['period'=>$p]) }}"
       class="btn btn-sm period-btn {{ $period===$p ? 'btn-primary active' : 'btn-outline-secondary' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- ── Charts row ────────────────────────────────────────────────── --}}
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card chart-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-chart-line mr-2 text-primary"></i>
                <strong>จำนวนการสอบ / คะแนนเฉลี่ย</strong>
            </div>
            <div class="card-body"><canvas id="lineChart" height="120"></canvas></div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card chart-card">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-chart-bar mr-2 text-success"></i>
                <strong>อัตราผ่าน (%)</strong>
            </div>
            <div class="card-body"><canvas id="barChart" height="200"></canvas></div>
        </div>
    </div>
</div>

{{-- ── Student table ─────────────────────────────────────────────── --}}
<div class="card chart-card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div><i class="fas fa-users mr-2 text-primary"></i><strong>สรุปรายบุคคล</strong></div>
        <small class="text-muted">{{ $studentStats->count() }} คน</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>ชื่อนักเรียน</th>
                        <th class="text-center">ครั้งที่สอบ</th>
                        <th class="text-center">ผ่าน</th>
                        <th class="text-center">อัตราผ่าน</th>
                        <th class="text-center">คะแนนเฉลี่ย</th>
                        <th class="text-center">ครั้งล่าสุด</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($studentStats as $i => $s)
                <tr class="student-row">
                    <td>
                        @if($i < 3)
                            <span class="rank-badge" style="background:{{ ['#FFD700','#C0C0C0','#CD7F32'][$i] }};color:#333;">{{ $i+1 }}</span>
                        @else
                            <span class="text-muted" style="font-size:.82rem;">{{ $i+1 }}</span>
                        @endif
                    </td>
                    <td><strong>{{ $s['name'] }}</strong></td>
                    <td class="text-center">{{ $s['attempts'] }}</td>
                    <td class="text-center"><span class="text-success">{{ $s['passed'] }}</span></td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                            <div class="progress flex-grow-1" style="height:6px;max-width:60px;">
                                <div class="progress-bar {{ $s['pass_rate']>=60?'bg-success':'bg-danger' }}"
                                     style="width:{{ $s['pass_rate'] }}%"></div>
                            </div>
                            <small>{{ $s['pass_rate'] }}%</small>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $s['avg_score']>=80?'badge-success':($s['avg_score']>=60?'badge-info':'badge-danger') }}">
                            {{ $s['avg_score'] }}%
                        </span>
                    </td>
                    <td class="text-center"><small class="text-muted">{{ $s['latest'] ? $s['latest']->format('d/m/y') : '-' }}</small></td>
                    <td>
                        @if(!empty($s['user_id']))
                        <a href="{{ route('teacher.analytics.student', $s['user_id']) }}"
                           class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:2px 8px;">
                            วิเคราะห์
                        </a>
                        @else
                        <span class="text-muted" style="font-size:.75rem;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($studentStats->isEmpty())
                <tr><td colspan="8" class="text-center text-muted py-4">ยังไม่มีข้อมูลการสอบ</td></tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
var labels      = @json($labels);
var countData   = @json($countData);
var passRate    = @json($passRateData);
var avgScore    = @json($avgScoreData);

// Line chart: attempts + avg score
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'จำนวนครั้งที่สอบ',
                data: countData,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102,126,234,.1)',
                fill: true,
                tension: .4,
                pointBackgroundColor: '#667eea',
                yAxisID: 'y',
            },
            {
                label: 'คะแนนเฉลี่ย (%)',
                data: avgScore,
                borderColor: '#43e97b',
                backgroundColor: 'rgba(67,233,123,.08)',
                fill: true,
                tension: .4,
                pointBackgroundColor: '#43e97b',
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top' } },
        scales: {
            y:  { type:'linear', display:true, position:'left',  title:{display:true,text:'ครั้ง'}, beginAtZero:true },
            y1: { type:'linear', display:true, position:'right', title:{display:true,text:'%'},
                  grid:{drawOnChartArea:false}, min:0, max:100 },
        }
    }
});

// Bar chart: pass rate
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'อัตราผ่าน (%)',
            data: passRate,
            backgroundColor: passRate.map(v => v >= 60 ? 'rgba(67,233,123,.7)' : 'rgba(245,87,108,.7)'),
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { min:0, max:100, title:{display:true,text:'%'} } }
    }
});
</script>
@endsection
@endsection
