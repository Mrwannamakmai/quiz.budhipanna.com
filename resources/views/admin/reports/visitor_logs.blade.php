@extends('layouts.admin')
@section('title','สถิติผู้ชมเว็บ')
@section('page-title','สถิติผู้ชมเว็บ')

@section('content')
<style>
/* ─── Stats cards ─── */
.vl-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:14px; margin-bottom:22px; }
.vl-stat { background:#fff; border-radius:12px; padding:18px 16px; text-align:center;
           box-shadow:0 1px 6px rgba(0,0,0,.07); border:1.5px solid #f1f5f9; }
.vl-stat .sv { font-size:1.8rem; font-weight:800; display:block; line-height:1.1; }
.vl-stat .sl { font-size:.75rem; color:#94a3b8; margin-top:4px; display:block; }
.vl-stat.v-total  .sv { color:#1e293b; }
.vl-stat.v-unique .sv { color:#2563eb; }
.vl-stat.v-return .sv { color:#f59e0b; }
.vl-stat.v-today  .sv { color:#16a34a; }

/* ─── Section cards ─── */
.vl-card { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.07);
           border:1.5px solid #f1f5f9; overflow:hidden; margin-bottom:20px; }
.vl-card-head { padding:14px 18px; border-bottom:1.5px solid #f1f5f9; display:flex;
                align-items:center; justify-content:space-between; }
.vl-card-head h6 { margin:0; font-size:.92rem; font-weight:700; color:#1e293b; }

/* ─── Filter bar ─── */
.vl-filter { display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; padding:14px 18px;
             background:#fafbfc; border-bottom:1.5px solid #f1f5f9; }
.vl-filter .form-group { margin:0; }
.vl-filter label { font-size:.75rem; font-weight:600; color:#64748b; display:block; margin-bottom:3px; }
.vl-filter .form-control { font-size:.82rem; padding:6px 10px; height:auto; border-radius:7px; border:1.5px solid #e2e8f0; }
.vl-filter .btn { font-size:.82rem; padding:6px 14px; border-radius:7px; }

/* ─── Table ─── */
.vl-table { margin:0; width:100%; }
.vl-table thead th { background:#f8fafc; font-size:.73rem; font-weight:700; color:#64748b;
                     text-transform:uppercase; padding:10px 12px; border-bottom:2px solid #f1f5f9; white-space:nowrap; }
.vl-table tbody td { padding:9px 12px; vertical-align:middle; font-size:.83rem; border-bottom:1px solid #f8fafc; }
.vl-table tbody tr:hover { background:#fafbfd; }
.vl-table .ip-cell code { background:#eff6ff; color:#1d4ed8; border-radius:5px;
                          padding:2px 7px; font-size:.8rem; }
.vl-table .url-cell { max-width:300px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.vl-table .url-cell a { color:#475569; font-size:.8rem; }
.vl-table .url-cell a:hover { color:#1d4ed8; }

/* ─── Top lists ─── */
.top-item { display:flex; align-items:center; gap:10px; padding:9px 0;
            border-bottom:1px solid #f8fafc; font-size:.83rem; }
.top-item:last-child { border-bottom:none; }
.top-rank { width:24px; height:24px; border-radius:50%; background:#f1f5f9; color:#64748b;
            font-size:.7rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.top-rank.gold   { background:#fef3c7; color:#92400e; }
.top-rank.silver { background:#f1f5f9; color:#475569; }
.top-rank.bronze { background:#fde8d8; color:#9a3412; }
.top-bar-wrap { flex:1; min-width:0; }
.top-label { display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
             color:#1e293b; font-weight:500; font-size:.8rem; }
.top-bar-bg { height:4px; background:#f1f5f9; border-radius:3px; margin-top:4px; }
.top-bar-fill { height:4px; border-radius:3px; }
.top-hits { font-size:.78rem; font-weight:700; color:#64748b; flex-shrink:0; white-space:nowrap; }

/* ─── Day filter pills ─── */
.day-pills { display:flex; gap:6px; }
.day-pill { padding:4px 12px; border-radius:20px; font-size:.75rem; font-weight:600;
            border:1.5px solid #e2e8f0; background:#fff; color:#64748b;
            text-decoration:none; transition:all .15s; }
.day-pill:hover { border-color:#93c5fd; color:#1d4ed8; text-decoration:none; }
.day-pill.active { background:#2563eb; color:#fff; border-color:#2563eb; }
</style>

{{-- ── Day range selector ── --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap" style="gap:10px;">
    <span style="font-size:.88rem;color:#64748b;">
        <i class="fas fa-calendar-alt mr-1"></i>แสดงข้อมูลย้อนหลัง:
    </span>
    <div class="day-pills">
        @foreach([7,30,60,90] as $d)
        <a href="{{ request()->fullUrlWithQuery(['days'=>$d]) }}"
           class="day-pill {{ $days==$d ? 'active' : '' }}">{{ $d }} วัน</a>
        @endforeach
    </div>
</div>

{{-- ── Summary stats ── --}}
<div class="vl-stats">
    <div class="vl-stat v-total">
        <span class="sv">{{ number_format($totalVisits) }}</span>
        <span class="sl"><i class="fas fa-eye mr-1"></i>เข้าชมรวม</span>
    </div>
    <div class="vl-stat v-unique">
        <span class="sv">{{ number_format($uniqueIps) }}</span>
        <span class="sl"><i class="fas fa-fingerprint mr-1"></i>IP ไม่ซ้ำ</span>
    </div>
    <div class="vl-stat v-return">
        <span class="sv">{{ number_format($returningIps) }}</span>
        <span class="sl"><i class="fas fa-redo mr-1"></i>IP กลับมาซ้ำ</span>
    </div>
    <div class="vl-stat v-today">
        <span class="sv">{{ number_format($todayCount) }}</span>
        <span class="sl"><i class="fas fa-sun mr-1"></i>วันนี้</span>
    </div>
    @if($uniqueIps > 0)
    <div class="vl-stat" style="border-color:#ede9fe;">
        <span class="sv" style="color:#7c3aed;">{{ round($totalVisits/$uniqueIps,1) }}</span>
        <span class="sl"><i class="fas fa-layer-group mr-1"></i>ครั้ง/IP เฉลี่ย</span>
    </div>
    @endif
</div>

{{-- ── Chart ── --}}
<div class="vl-card">
    <div class="vl-card-head">
        <h6><i class="fas fa-chart-area mr-2" style="color:#2563eb;"></i>จำนวนผู้เข้าชมรายวัน ({{ $days }} วันล่าสุด)</h6>
        <div style="display:flex;gap:14px;font-size:.75rem;">
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#3b82f6;margin-right:4px;"></span>เข้าชมรวม</span>
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#22c55e;margin-right:4px;"></span>IP ไม่ซ้ำ</span>
        </div>
    </div>
    <div style="padding:16px 18px;">
        <canvas id="visitorChart" style="max-height:260px;"></canvas>
    </div>
</div>

{{-- ── Top pages + Top IPs ── --}}
<div class="row">
    <div class="col-md-7 mb-3">
        <div class="vl-card h-100">
            <div class="vl-card-head">
                <h6><i class="fas fa-link mr-2" style="color:#f59e0b;"></i>หน้าที่เข้าชมมากสุด</h6>
            </div>
            <div style="padding:10px 18px;">
                @php $maxHits = $topPages->max('hits') ?: 1; @endphp
                @forelse($topPages as $i => $page)
                <div class="top-item">
                    <div class="top-rank {{ $i===0?'gold':($i===1?'silver':($i===2?'bronze':'')) }}">{{ $i+1 }}</div>
                    <div class="top-bar-wrap">
                        <span class="top-label" title="{{ $page->url }}">
                            {{ parse_url($page->url, PHP_URL_PATH) ?: '/' }}
                        </span>
                        <div class="top-bar-bg">
                            <div class="top-bar-fill" style="width:{{ round($page->hits/$maxHits*100) }}%;background:#f59e0b;"></div>
                        </div>
                    </div>
                    <span class="top-hits">{{ number_format($page->hits) }} ครั้ง</span>
                </div>
                @empty
                <p class="text-muted text-center py-4" style="font-size:.85rem;">ยังไม่มีข้อมูล</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-5 mb-3">
        <div class="vl-card h-100">
            <div class="vl-card-head">
                <h6><i class="fas fa-network-wired mr-2" style="color:#ef4444;"></i>Top IP Addresses</h6>
            </div>
            <div style="padding:10px 18px;">
                @php $maxIpHits = $topIps->max('hits') ?: 1; @endphp
                @forelse($topIps as $i => $ip)
                <div class="top-item">
                    <div class="top-rank {{ $i===0?'gold':($i===1?'silver':($i===2?'bronze':'')) }}">{{ $i+1 }}</div>
                    <div class="top-bar-wrap">
                        <span class="top-label">
                            <code style="background:#fff0f0;color:#dc2626;border-radius:5px;padding:1px 6px;font-size:.78rem;">{{ $ip->ip_address }}</code>
                        </span>
                        <div class="top-bar-bg">
                            <div class="top-bar-fill" style="width:{{ round($ip->hits/$maxIpHits*100) }}%;background:#ef4444;"></div>
                        </div>
                    </div>
                    <span class="top-hits">{{ number_format($ip->hits) }}</span>
                </div>
                @empty
                <p class="text-muted text-center py-4" style="font-size:.85rem;">ยังไม่มีข้อมูล</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Log table ── --}}
<div class="vl-card">
    <div class="vl-card-head">
        <h6><i class="fas fa-list mr-2" style="color:#64748b;"></i>รายการเข้าชม</h6>
        <span style="font-size:.78rem;color:#94a3b8;">{{ $logs->total() }} รายการ</span>
    </div>

    {{-- Filter --}}
    <form method="GET" action="" class="vl-filter">
        <input type="hidden" name="days" value="{{ $days }}">
        <div class="form-group">
            <label>กรอง IP</label>
            <input type="text" name="ip_filter" class="form-control" style="width:150px;"
                   value="{{ request('ip_filter') }}" placeholder="เช่น 192.168">
        </div>
        <div class="form-group">
            <label>กรอง URL</label>
            <input type="text" name="url_filter" class="form-control" style="width:220px;"
                   value="{{ request('url_filter') }}" placeholder="เช่น pretest">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-filter mr-1"></i>กรอง
        </button>
        <a href="{{ route('admin.reports.visitors', ['days'=>$days]) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-times mr-1"></i>ล้าง
        </a>
    </form>

    <div class="table-responsive">
    <table class="vl-table table mb-0">
        <thead>
            <tr>
                <th>IP Address</th>
                <th>URL</th>
                <th style="width:70px;">Method</th>
                <th>ผู้ใช้</th>
                <th style="width:160px;">เวลา</th>
            </tr>
        </thead>
        <tbody>
        @forelse($logs as $log)
            <tr>
                <td class="ip-cell"><code>{{ $log->ip_address }}</code></td>
                <td class="url-cell">
                    <a href="{{ $log->url }}" target="_blank" rel="noopener" title="{{ $log->url }}">
                        {{ parse_url($log->url, PHP_URL_PATH) ?: '/' }}
                    </a>
                </td>
                <td>
                    <span class="badge badge-{{ $log->method==='GET'?'secondary':'warning' }}" style="font-size:.7rem;">
                        {{ $log->method }}
                    </span>
                </td>
                <td style="font-size:.82rem;">
                    @if($log->user)
                        <i class="fas fa-user-circle mr-1 text-primary" style="font-size:.75rem;"></i>{{ $log->user->name }}
                    @else
                        <span class="text-muted">Guest</span>
                    @endif
                </td>
                <td style="font-size:.78rem;color:#64748b;">
                    {{ $log->visited_at ? $log->visited_at->format('d/m/Y H:i:s') : '-' }}
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีข้อมูลในช่วงนี้</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-3 py-2">{{ $logs->links() }}</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function () {
    var ctx = document.getElementById('visitorChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'เข้าชมรวม',
                    data: {!! json_encode($chartTotal) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,.08)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.35,
                },
                {
                    label: 'IP ไม่ซ้ำ',
                    data: {!! json_encode($chartUnique) !!},
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,.07)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.35,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12 },
                    bodyFont: { size: 12 },
                    padding: 10,
                },
            },
            scales: {
                x: {
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11 }, color: '#94a3b8',
                             maxTicksLimit: {{ $days <= 30 ? 15 : 10 }} },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11 }, color: '#94a3b8', precision: 0 },
                },
            },
        },
    });
})();
</script>
@endsection
