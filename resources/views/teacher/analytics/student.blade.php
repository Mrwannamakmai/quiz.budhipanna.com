@extends('layouts.teacher')
@section('title','วิเคราะห์รายบุคคล — '.$user->name)
@section('page-title','วิเคราะห์รายบุคคล')

@section('content')
<style>
.dim-card       { border-radius:12px; padding:16px; border-left:4px solid; height:100%; }
.dim-label      { font-weight:700; font-size:.9rem; }
.dim-grade      { font-size:.78rem; padding:2px 8px; border-radius:10px; color:#fff; }
.profile-avatar { width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:700; flex-shrink:0; }
.mini-stat      { text-align:center; }
.mini-stat .val { font-size:1.5rem; font-weight:700; }
.mini-stat .lbl { font-size:.75rem; color:#6c757d; }
.chart-card     { border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.07); }
.attempt-row    { font-size:.85rem; }
.attempt-row td { vertical-align:middle; padding:8px 12px; }
</style>

{{-- ── Header ─────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap" style="gap:12px;">
    <div class="d-flex align-items-center" style="gap:14px;">
        <div class="profile-avatar">{{ mb_substr($user->name, 0, 1) }}</div>
        <div>
            <h5 class="mb-0">{{ $user->name }}</h5>
            <small class="text-muted">{{ $user->email }} · สอบครั้งแรก {{ $summary['first_at']->format('d/m/y') }} — ล่าสุด {{ $summary['last_at']->format('d/m/y') }}</small>
        </div>
    </div>
    <div class="d-flex" style="gap:8px;">
        @php
            $idx = $students->search(fn($s) => (int) $s->id === (int) $user->id);
            $prev = $idx > 0 ? $students[$idx-1] : null;
            $next = $idx < $students->count()-1 ? $students[$idx+1] : null;
        @endphp
        @if($prev)
        <a href="{{ route('teacher.analytics.student', $prev->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-chevron-left mr-1"></i>{{ $prev->name }}
        </a>
        @endif
        <a href="{{ route('teacher.analytics.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-users mr-1"></i>ทั้งหมด
        </a>
        @if($next)
        <a href="{{ route('teacher.analytics.student', $next->id) }}" class="btn btn-sm btn-outline-secondary">
            {{ $next->name }}<i class="fas fa-chevron-right ml-1"></i>
        </a>
        @endif
    </div>
</div>

{{-- ── Summary mini stats ───────────────────────────────────────── --}}
<div class="row mb-4">
    @php $passed = $summary['passed']; $total = $summary['total']; @endphp
    <div class="col-6 col-md-3 mb-3">
        <div class="card text-center py-3"><div class="mini-stat">
            <div class="val text-primary">{{ $total }}</div><div class="lbl">ครั้งที่สอบ</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card text-center py-3"><div class="mini-stat">
            <div class="val text-success">{{ $passed }}</div><div class="lbl">ผ่าน</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card text-center py-3"><div class="mini-stat">
            <div class="val {{ $summary['avg_score']>=60?'text-success':'text-danger' }}">{{ $summary['avg_score'] }}%</div>
            <div class="lbl">คะแนนเฉลี่ย</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card text-center py-3"><div class="mini-stat">
            <div class="val text-warning">{{ $summary['best'] }}%</div><div class="lbl">คะแนนสูงสุด</div>
        </div></div>
    </div>
</div>

{{-- ── Charts row ────────────────────────────────────────────────── --}}
<div class="row mb-4">
    {{-- Score trend --}}
    <div class="col-md-7 mb-3">
        <div class="card chart-card">
            <div class="card-header"><i class="fas fa-chart-line mr-2 text-primary"></i><strong>แนวโน้มคะแนน (ทุกครั้งที่สอบ)</strong></div>
            <div class="card-body"><canvas id="trendChart" height="140"></canvas></div>
        </div>
    </div>
    {{-- Radar / dimension --}}
    <div class="col-md-5 mb-3">
        <div class="card chart-card">
            <div class="card-header"><i class="fas fa-bullseye mr-2 text-danger"></i><strong>ผังวิเคราะห์ 4 ด้าน</strong></div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="radarChart" height="200" width="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── Monthly activity chart ─────────────────────────────────────── --}}
<div class="card chart-card mb-4">
    <div class="card-header"><i class="fas fa-calendar-alt mr-2 text-info"></i><strong>กิจกรรมรายเดือน (12 เดือนล่าสุด)</strong></div>
    <div class="card-body"><canvas id="monthChart" height="80"></canvas></div>
</div>

{{-- ── 4 Dimension detail cards ────────────────────────────────────── --}}
@php
$dimDescriptions = [
    'kaya'    => [[80,'ดีมาก','มีความเข้าใจด้านกายภาพและสถานที่ได้ดีเยี่ยม','#27ae60'],[60,'ดี','เข้าใจด้านกายภาพได้เป็นอย่างดี','#2980b9'],[40,'พอใช้','ควรศึกษาเพิ่มเติมด้านสถานที่และเหตุการณ์','#e67e22'],[0,'ต้องปรับปรุง','ยังขาดความเข้าใจด้านกายภาพ ควรทบทวน','#e74c3c']],
    'conduct' => [[80,'ดีมาก','มีความเข้าใจหลักพฤติกรรมและจริยธรรมได้ดีเยี่ยม','#27ae60'],[60,'ดี','เข้าใจหลักพฤติกรรมได้ดี','#2980b9'],[40,'พอใช้','ควรศึกษาเรื่องจริยธรรมและพฤติกรรมเพิ่ม','#e67e22'],[0,'ต้องปรับปรุง','ยังขาดความเข้าใจด้านพฤติกรรม ควรทบทวน','#e74c3c']],
    'citta'   => [[80,'ดีมาก','มีความเข้าใจด้านจิตใจและสภาวะจิตได้ดีเยี่ยม','#27ae60'],[60,'ดี','เข้าใจด้านจิตใจได้ดี','#2980b9'],[40,'พอใช้','ควรฝึกฝนการสังเกตสภาวะจิตเพิ่มเติม','#e67e22'],[0,'ต้องปรับปรุง','ยังขาดความเข้าใจด้านจิตใจ ควรทบทวน','#e74c3c']],
    'panna'   => [[80,'ดีมาก','มีปัญญาและความเข้าใจในหลักธรรมได้ดีเยี่ยม','#27ae60'],[60,'ดี','มีปัญญาและเข้าใจหลักธรรมได้ดี','#2980b9'],[40,'พอใช้','ควรฝึกคิดวิเคราะห์และศึกษาหลักธรรมเพิ่ม','#e67e22'],[0,'ต้องปรับปรุง','ยังขาดความเข้าใจด้านปัญญา ควรทบทวน','#e74c3c']],
];
@endphp

@if(collect($dimData)->sum('total') > 0)
<div class="card chart-card mb-4">
    <div class="card-header"><i class="fas fa-layer-group mr-2 text-purple" style="color:#8e44ad;"></i><strong>วิเคราะห์ 4 ด้าน รายละเอียด</strong></div>
    <div class="card-body">
        <div class="row">
        @foreach($dims as $key => $dim)
        @php
            $d    = $dimData[$key];
            $pct  = $d['points'] > 0 ? round($d['earned'] / $d['points'] * 100) : 0;
            $row  = collect($dimDescriptions[$key])->first(fn($r) => $pct >= $r[0]);
            [$minP, $grade, $text, $barColor] = $row;
        @endphp
        <div class="col-md-6 mb-3">
            <div class="dim-card" style="border-color:{{ $dim['color'] }};background:{{ $dim['color'] }}08;">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas {{ $dim['icon'] }} mr-2" style="color:{{ $dim['color'] }};font-size:1.1rem;width:22px;text-align:center;"></i>
                    <span class="dim-label" style="color:{{ $dim['color'] }};">{{ $dim['label'] }}</span>
                    <span class="ml-auto dim-grade" style="background:{{ $barColor }};">{{ $grade }}</span>
                </div>
                @if($d['total'] > 0)
                <div class="progress mb-1" style="height:8px;border-radius:4px;">
                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $barColor }};border-radius:4px;"></div>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <small class="text-muted">{{ $pct }}%</small>
                    <small class="text-muted">{{ $d['correct'] }}/{{ $d['total'] }} ข้อถูก · {{ $d['earned'] }}/{{ $d['points'] }} คะแนน</small>
                </div>
                <p class="mb-0 text-muted" style="font-size:.8rem;line-height:1.5;">{{ $text }}</p>
                @else
                <p class="mb-0 text-muted" style="font-size:.8rem;">ยังไม่มีข้อสอบที่ระบุด้านนี้</p>
                @endif
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>
@endif

{{-- ── Per exam stats ─────────────────────────────────────────────── --}}
<div class="card chart-card mb-4">
    <div class="card-header"><i class="fas fa-file-alt mr-2 text-secondary"></i><strong>สรุปแยกตามชุดข้อสอบ</strong></div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>ชุดข้อสอบ</th>
                    <th>วิชา</th>
                    <th class="text-center">ครั้งที่สอบ</th>
                    <th class="text-center">ผ่าน</th>
                    <th class="text-center">สูงสุด</th>
                    <th class="text-center">เฉลี่ย</th>
                    <th class="text-center">ล่าสุด</th>
                </tr>
            </thead>
            <tbody>
            @foreach($perExam as $e)
            <tr class="attempt-row">
                <td><strong>{{ $e['name'] }}</strong></td>
                <td><small class="text-muted">{{ $e['subject'] }}</small></td>
                <td class="text-center">{{ $e['attempts'] }}</td>
                <td class="text-center"><span class="text-success">{{ $e['passed'] }}</span>/{{ $e['attempts'] }}</td>
                <td class="text-center"><span class="badge badge-warning">{{ $e['best'] }}%</span></td>
                <td class="text-center">
                    <span class="badge {{ $e['avg']>=60?'badge-success':'badge-danger' }}">{{ $e['avg'] }}%</span>
                </td>
                <td class="text-center"><small class="text-muted">{{ $e['latest']->format('d/m/y') }}</small></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ── Attempt history ─────────────────────────────────────────────── --}}
<div class="card chart-card">
    <div class="card-header"><i class="fas fa-history mr-2 text-secondary"></i><strong>ประวัติการสอบทั้งหมด ({{ $attempts->count() }} ครั้ง)</strong></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>ชุดข้อสอบ</th>
                        <th class="text-center">คะแนน</th>
                        <th class="text-center">%</th>
                        <th class="text-center">ผล</th>
                        <th class="text-center">วันที่สอบ</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($attempts->sortByDesc('submitted_at') as $i => $att)
                <tr class="attempt-row">
                    <td><small class="text-muted">{{ $i+1 }}</small></td>
                    <td>{{ $att->examSet->name ?? '-' }}</td>
                    <td class="text-center">{{ $att->score }}/{{ $att->total_points }}</td>
                    <td class="text-center">
                        @php $pct = $att->total_points>0?round($att->score/$att->total_points*100,1):0; @endphp
                        <span class="badge {{ $pct>=80?'badge-success':($pct>=60?'badge-info':'badge-danger') }}">{{ $pct }}%</span>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $att->passed?'badge-success':'badge-danger' }}">{{ $att->passed?'ผ่าน':'ไม่ผ่าน' }}</span>
                    </td>
                    <td class="text-center"><small>{{ $att->submitted_at->format('d/m/y H:i') }}</small></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('teacher.analytics.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-1"></i>กลับภาพรวม
    </a>
</div>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
var trendLabels = @json($trendLabels);
var trendScores = @json($trendScores);
var trendPass   = @json($trendPass);

// Score trend line
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'คะแนน (%)',
            data: trendScores,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102,126,234,.1)',
            fill: true,
            tension: .35,
            pointBackgroundColor: trendPass.map(p => p ? '#43e97b' : '#f5576c'),
            pointRadius: 6,
            pointHoverRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    afterLabel: function(ctx) {
                        return trendPass[ctx.dataIndex] ? '✅ ผ่าน' : '❌ ไม่ผ่าน';
                    }
                }
            }
        },
        scales: {
            y: { min: 0, max: 100, title: { display: true, text: 'คะแนน (%)' } }
        }
    }
});

// Radar chart
new Chart(document.getElementById('radarChart'), {
    type: 'radar',
    data: {
        labels: @json($radarLabels),
        datasets: [{
            label: 'ความสามารถ (%)',
            data: @json(array_values($radarData)),
            borderColor: '#667eea',
            backgroundColor: 'rgba(102,126,234,.2)',
            pointBackgroundColor: '#667eea',
        }]
    },
    options: {
        responsive: false,
        plugins: { legend: { display: false } },
        scales: {
            r: {
                min: 0, max: 100,
                ticks: { stepSize: 25, font: { size: 10 } },
                pointLabels: { font: { size: 12 } }
            }
        }
    }
});

// Monthly chart
new Chart(document.getElementById('monthChart'), {
    type: 'bar',
    data: {
        labels: @json($pLabels),
        datasets: [
            {
                label: 'จำนวนครั้ง',
                data: @json($pCount),
                backgroundColor: 'rgba(102,126,234,.7)',
                borderRadius: 5,
                yAxisID: 'y',
            },
            {
                label: 'คะแนนเฉลี่ย (%)',
                data: @json($pAvg),
                type: 'line',
                borderColor: '#43e97b',
                backgroundColor: 'transparent',
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
            y:  { beginAtZero:true, title:{ display:true, text:'ครั้ง' } },
            y1: { position:'right', min:0, max:100, title:{ display:true, text:'%' },
                  grid:{ drawOnChartArea:false } }
        }
    }
});
</script>
@endsection
@endsection
