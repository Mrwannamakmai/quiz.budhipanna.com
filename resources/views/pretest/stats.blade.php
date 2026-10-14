<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถิติ Pre-Test – V-Quiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; margin: 0; }

        /* ─── Navbar ─── */
        .pt-nav { background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.08); padding: 10px 0; position: sticky; top: 0; z-index: 999; }
        .pt-nav .inner { display: flex; align-items: center; justify-content: space-between; }
        .pt-nav .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .pt-nav .logo-icon { width: 38px; height: 38px; background: linear-gradient(135deg,#16a085,#1abc9c); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; }
        .pt-nav .logo-text { font-size: 1.1rem; font-weight: 700; color: #16a085; }
        .nav-links { display: flex; align-items: center; gap: 12px; }
        .nav-links a { color: #64748b; text-decoration: none; font-size: .88rem; font-weight: 600; padding: 6px 14px; border-radius: 20px; transition: all .15s; }
        .nav-links a:hover { background: #f1f5f9; color: #334155; text-decoration: none; }
        .nav-links a.active { background: #16a085; color: #fff; }

        /* ─── Hero ─── */
        .st-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #3b82f6 100%);
            color: #fff; padding: 48px 0 36px; text-align: center;
            position: relative; overflow: hidden;
        }
        .st-hero::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='1.5' fill='white' opacity='.06'/%3E%3C/svg%3E");
        }
        .st-hero * { position: relative; }
        .st-hero h2 { font-size: 1.6rem; font-weight: 800; margin-bottom: 6px; }
        .st-hero p { font-size: .95rem; opacity: .85; margin-bottom: 0; }

        /* ─── Summary cards ─── */
        .sum-row { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; margin-top: -36px; margin-bottom: 30px; position: relative; z-index: 2; }
        .sum-card {
            background: #fff; border-radius: 14px; padding: 20px; text-align: center;
            box-shadow: 0 4px 16px rgba(0,0,0,.08); border: 1.5px solid #f1f5f9;
            transition: transform .2s;
        }
        .sum-card:hover { transform: translateY(-3px); }
        .sum-card .sc-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff; margin: 0 auto 10px; }
        .sum-card .sc-val { font-size: 1.8rem; font-weight: 800; color: #1e293b; display: block; line-height: 1; }
        .sum-card .sc-lbl { font-size: .78rem; color: #94a3b8; font-weight: 500; }
        .sum-card .sc-sub { font-size: .72rem; color: #64748b; margin-top: 4px; }

        /* ─── Filter tabs ─── */
        .filter-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
        .filter-tab {
            padding: 8px 18px; border-radius: 20px; font-size: .85rem; font-weight: 600;
            border: 2px solid #e2e8f0; background: #fff; color: #64748b;
            cursor: pointer; transition: all .15s; display: inline-flex; align-items: center; gap: 6px;
        }
        .filter-tab:hover { border-color: #94a3b8; }
        .filter-tab.active { background: #2563eb; color: #fff; border-color: #2563eb; }
        .filter-tab .cnt { font-size: .72rem; background: rgba(0,0,0,.08); padding: 1px 8px; border-radius: 10px; }
        .filter-tab.active .cnt { background: rgba(255,255,255,.25); }

        /* ─── Table ─── */
        .stats-table-wrap {
            background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06);
            border: 1.5px solid #f1f5f9; overflow: hidden; margin-bottom: 30px;
        }
        .stats-table-wrap .st-head {
            padding: 16px 20px; border-bottom: 2px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .stats-table-wrap .st-head h5 { margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b; }
        .st-table { width: 100%; border-collapse: collapse; }
        .st-table thead th {
            background: #f8fafc; font-size: .75rem; font-weight: 700; color: #64748b;
            text-transform: uppercase; padding: 12px 16px; border-bottom: 2px solid #f1f5f9;
            white-space: nowrap; text-align: left;
        }
        .st-table tbody td {
            padding: 14px 16px; font-size: .85rem; border-bottom: 1px solid #f8fafc; vertical-align: middle;
        }
        .st-table tbody tr:hover { background: #fafbfd; }
        .st-table .es-name { font-weight: 600; color: #1e293b; }
        .st-table .es-sub { font-size: .72rem; color: #94a3b8; }
        .st-table .num-cell { text-align: center; font-weight: 700; font-size: .95rem; }
        .st-table .num-cell.views { color: #2563eb; }
        .st-table .num-cell.attempts { color: #7c3aed; }
        .st-table .num-cell.passed { color: #16a34a; }
        .st-table .num-cell.failed { color: #ef4444; }

        .lv-badge { font-size: .72rem; font-weight: 600; padding: 3px 10px; border-radius: 12px; display: inline-block; }
        .lv-tri { background: #dcfce7; color: #15803d; }
        .lv-tho { background: #dbeafe; color: #1d4ed8; }
        .lv-ek  { background: #f3e8ff; color: #7c3aed; }
        .lv-na  { background: #f1f5f9; color: #64748b; }

        /* ─── Progress bar ─── */
        .rate-bar { display: flex; align-items: center; gap: 8px; }
        .rate-track { flex: 1; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; min-width: 60px; }
        .rate-fill { height: 100%; border-radius: 4px; transition: width .5s ease; }
        .rate-pct { font-size: .8rem; font-weight: 700; min-width: 42px; text-align: right; }

        /* ─── Recent ─── */
        .recent-wrap {
            background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06);
            border: 1.5px solid #f1f5f9; overflow: hidden; margin-bottom: 30px;
        }
        .recent-wrap .rw-head { padding: 16px 20px; border-bottom: 2px solid #f1f5f9; }
        .recent-wrap .rw-head h5 { margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b; }
        .recent-list { padding: 0; margin: 0; list-style: none; }
        .recent-item {
            display: flex; align-items: center; gap: 14px; padding: 14px 20px;
            border-bottom: 1px solid #f8fafc; transition: background .12s;
        }
        .recent-item:hover { background: #fafbfd; }
        .recent-item:last-child { border-bottom: none; }
        .ri-avatar { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .85rem; color: #fff; font-weight: 700; flex-shrink: 0; }
        .ri-info { flex: 1; min-width: 0; }
        .ri-name { font-weight: 600; font-size: .88rem; color: #1e293b; }
        .ri-exam { font-size: .78rem; color: #94a3b8; }
        .ri-score { text-align: right; }
        .ri-score .pct { font-size: 1rem; font-weight: 700; display: block; }
        .ri-score .tag { font-size: .7rem; font-weight: 600; padding: 2px 8px; border-radius: 10px; }
        .tag-pass { background: #dcfce7; color: #15803d; }
        .tag-fail { background: #fef2f2; color: #dc2626; }
        .ri-time { font-size: .72rem; color: #94a3b8; white-space: nowrap; }

        .empty-recent { text-align: center; padding: 40px 20px; color: #94a3b8; }
        .empty-recent .fa-inbox { font-size: 2.5rem; display: block; margin-bottom: 10px; color: #cbd5e1; }

        /* ─── Footer ─── */
        .st-footer { text-align: center; padding: 30px 0; color: #94a3b8; font-size: .8rem; }

        @media (max-width: 768px) {
            .sum-row { grid-template-columns: repeat(2, 1fr); }
            .st-table-scroll { overflow-x: auto; }
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="pt-nav">
    <div class="container inner">
        <a href="{{ route('pretest.index') }}" class="logo">
            <div class="logo-icon"><i class="fas fa-pen-alt"></i></div>
            <span class="logo-text">V-Quiz</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('pretest.index') }}">ข้อสอบ</a>
            <a href="{{ route('pretest.guide') }}">คู่มือ</a>
            <a href="{{ route('pretest.stats') }}" class="active">สถิติ</a>
            @auth
                <a href="{{ route('home') }}">เข้าระบบ</a>
            @else
                <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Hero --}}
<div class="st-hero">
    <div class="container">
        <h2><i class="fas fa-chart-bar mr-2"></i>สถิติ Pre-Test</h2>
        <p>ภาพรวมยอดผู้ชม ผู้ทำข้อสอบ และอัตราการผ่าน</p>
    </div>
</div>

{{-- Summary --}}
<div class="container">
    <div class="sum-row">
        <div class="sum-card">
            <div class="sc-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);"><i class="fas fa-eye"></i></div>
            <span class="sc-val">{{ number_format($totals['views']) }}</span>
            <span class="sc-lbl">ยอดผู้ชม</span>
        </div>
        <div class="sum-card">
            <div class="sc-icon" style="background:linear-gradient(135deg,#7c3aed,#a78bfa);"><i class="fas fa-pencil-alt"></i></div>
            <span class="sc-val">{{ number_format($totals['attempts']) }}</span>
            <span class="sc-lbl">ยอดทำข้อสอบ</span>
        </div>
        <div class="sum-card">
            <div class="sc-icon" style="background:linear-gradient(135deg,#16a34a,#4ade80);"><i class="fas fa-check-circle"></i></div>
            <span class="sc-val">{{ number_format($totals['passed']) }}</span>
            <span class="sc-lbl">ผ่านเกณฑ์</span>
            @if($totals['attempts'] > 0)
                <div class="sc-sub">{{ $totals['rate'] }}% ของผู้ทำทั้งหมด</div>
            @endif
        </div>
        <div class="sum-card">
            <div class="sc-icon" style="background:linear-gradient(135deg,#ef4444,#f87171);"><i class="fas fa-times-circle"></i></div>
            <span class="sc-val">{{ number_format($totals['failed']) }}</span>
            <span class="sc-lbl">ไม่ผ่านเกณฑ์</span>
        </div>
    </div>

    {{-- Filter tabs --}}
    <div class="filter-tabs" id="filterTabs">
        <button class="filter-tab active" data-filter="all">
            <i class="fas fa-layer-group"></i> ทั้งหมด
            <span class="cnt">{{ $examSets->count() }}</span>
        </button>
        @foreach($classrooms as $cl)
            @php
                $cnt = $examSets->where('classroom_id', $cl->id)->count();
            @endphp
            @if($cnt > 0)
            <button class="filter-tab" data-filter="{{ $cl->id }}">
                {{ $cl->name }}
                <span class="cnt">{{ $cnt }}</span>
            </button>
            @endif
        @endforeach
    </div>

    {{-- Stats table --}}
    <div class="stats-table-wrap">
        <div class="st-head">
            <h5><i class="fas fa-table mr-2" style="color:#3b82f6;"></i>สถิติรายชุดข้อสอบ</h5>
        </div>
        <div class="st-table-scroll">
        <table class="st-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ชุดข้อสอบ</th>
                    <th>ระดับชั้น</th>
                    <th style="text-align:center;">ข้อสอบ</th>
                    <th style="text-align:center;"><i class="fas fa-eye mr-1"></i>ผู้ชม</th>
                    <th style="text-align:center;"><i class="fas fa-pencil-alt mr-1"></i>ผู้ทำ</th>
                    <th style="text-align:center;"><i class="fas fa-check mr-1"></i>ผ่าน</th>
                    <th style="text-align:center;"><i class="fas fa-times mr-1"></i>ไม่ผ่าน</th>
                    <th style="min-width:140px;">อัตราผ่าน</th>
                </tr>
            </thead>
            <tbody>
            @forelse($examSets as $i => $es)
                @php
                    $lvCls = 'lv-na';
                    $lvName = '-';
                    if ($es->classroom) {
                        $lvName = $es->classroom->name;
                        if (str_contains($lvName, 'ตรี')) $lvCls = 'lv-tri';
                        elseif (str_contains($lvName, 'โท')) $lvCls = 'lv-tho';
                        elseif (str_contains($lvName, 'เอก')) $lvCls = 'lv-ek';
                    }
                    $failed = $es->attempt_count - $es->pass_count;
                    $rateColor = $es->pass_rate >= 70 ? '#16a34a' : ($es->pass_rate >= 40 ? '#eab308' : '#ef4444');
                @endphp
                <tr class="es-stat-row" data-classroom="{{ $es->classroom_id ?? 'none' }}">
                    <td style="color:#94a3b8;">{{ $i + 1 }}</td>
                    <td>
                        <div class="es-name">{{ $es->name }}</div>
                        @if($es->subject)
                            <div class="es-sub">{{ $es->subject->name }}</div>
                        @endif
                    </td>
                    <td><span class="lv-badge {{ $lvCls }}">{{ $lvName }}</span></td>
                    <td class="num-cell" style="color:#64748b;">{{ $es->total_questions }}</td>
                    <td class="num-cell views">{{ number_format($es->view_count) }}</td>
                    <td class="num-cell attempts">{{ number_format($es->attempt_count) }}</td>
                    <td class="num-cell passed">{{ number_format($es->pass_count) }}</td>
                    <td class="num-cell failed">{{ number_format($failed) }}</td>
                    <td>
                        <div class="rate-bar">
                            <div class="rate-track">
                                <div class="rate-fill" style="width:{{ $es->pass_rate }}%;background:{{ $rateColor }};"></div>
                            </div>
                            <span class="rate-pct" style="color:{{ $rateColor }};">{{ $es->pass_rate }}%</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">ยังไม่มีชุดข้อสอบ</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- Recent attempts --}}
    <div class="recent-wrap">
        <div class="rw-head">
            <h5><i class="fas fa-history mr-2" style="color:#7c3aed;"></i>ผู้ทำข้อสอบล่าสุด</h5>
        </div>
        @if($recentAttempts->isEmpty())
            <div class="empty-recent">
                <i class="fas fa-inbox"></i>
                <p>ยังไม่มีผู้ทำข้อสอบ</p>
            </div>
        @else
            <ul class="recent-list">
            @foreach($recentAttempts as $att)
                @php
                    $avatarBg = $att->passed ? '#16a34a' : '#ef4444';
                    $initials = mb_substr($att->user->name ?? 'ผู้เข้าสอบ', 0, 1);
                    $pct = $att->total_points > 0 ? round($att->score / $att->total_points * 100, 1) : 0;
                @endphp
                <li class="recent-item">
                    <div class="ri-avatar" style="background:{{ $avatarBg }};">{{ $initials }}</div>
                    <div class="ri-info">
                        <div class="ri-name">{{ $att->user->name ?? 'ผู้เข้าสอบ (ไม่ระบุชื่อ)' }}</div>
                        <div class="ri-exam">{{ $att->examSet->name ?? '-' }}</div>
                    </div>
                    <div class="ri-score">
                        <span class="pct" style="color:{{ $att->passed ? '#16a34a' : '#ef4444' }};">{{ $pct }}%</span>
                        <span class="tag {{ $att->passed ? 'tag-pass' : 'tag-fail' }}">
                            {{ $att->passed ? 'ผ่าน' : 'ไม่ผ่าน' }}
                        </span>
                    </div>
                    <div class="ri-time">{{ $att->created_at->diffForHumans() }}</div>
                </li>
            @endforeach
            </ul>
        @endif
    </div>
</div>

{{-- Footer --}}
<div class="st-footer">
    <div class="container">
        &copy; {{ date('Y') }} V-Quiz — ระบบสอบออนไลน์ นักธรรม-ธรรมศึกษา
    </div>
</div>

<script>
(function () {
    var tabs = document.querySelectorAll('.filter-tab');
    var rows = document.querySelectorAll('.es-stat-row');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');

            var filter = tab.getAttribute('data-filter');
            rows.forEach(function (row) {
                if (filter === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = row.getAttribute('data-classroom') === filter ? '' : 'none';
                }
            });
        });
    });
})();
</script>

</body>
</html>
