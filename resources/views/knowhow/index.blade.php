<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คลังความรู้ — ศูนย์ข้อสอบออนไลน์</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background:#f0f4f8; font-family:'Sarabun',sans-serif; }

        /* ── Hero ── */
        .kh-hero { background:linear-gradient(135deg,rgba(13,102,81,.75) 0%,rgba(22,160,133,.65) 50%,rgba(26,188,156,.55) 100%), url('{{ asset("images/ฉากหลัง3.jpg") }}') center/cover no-repeat; color:#fff; padding:52px 0 80px; position:relative; overflow:hidden; }
        .kh-hero::before { content:''; position:absolute; top:-80px; right:-80px; width:480px; height:480px; background:rgba(255,255,255,.05); border-radius:50%; pointer-events:none; }
        .kh-hero::after  { content:''; position:absolute; bottom:-100px; left:-60px; width:360px; height:360px; background:rgba(255,255,255,.04); border-radius:50%; pointer-events:none; }
        .hero-label { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.35); border-radius:20px; padding:5px 16px; font-size:.82rem; font-weight:600; margin-bottom:14px; letter-spacing:.4px; }
        .kh-hero h1 { font-size:2.2rem; font-weight:800; margin-bottom:8px; position:relative; z-index:1; }
        .kh-hero .hero-sub { opacity:.8; font-size:.95rem; margin-bottom:28px; }

        /* ── Hero Search ── */
        .hero-search-wrap { position:relative; z-index:5; }
        .hero-search-box { display:flex; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 8px 32px rgba(0,0,0,.18); }
        .hero-search-box input { flex:1; border:none; outline:none; padding:14px 20px; font-family:'Sarabun',sans-serif; font-size:1rem; color:#1e293b; background:transparent; }
        .hero-search-box input::placeholder { color:#94a3b8; }
        .hero-search-box button { background:#16a085; color:#fff; border:none; padding:0 28px; font-weight:700; font-size:.95rem; cursor:pointer; font-family:'Sarabun',sans-serif; white-space:nowrap; transition:background .15s; display:flex; align-items:center; gap:7px; }
        .hero-search-box button:hover { background:#0e7060; }
        .hero-search-hint { margin-top:10px; font-size:.78rem; opacity:.65; display:flex; flex-wrap:wrap; gap:6px; align-items:center; }
        .hero-hint-tag { background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25); border-radius:20px; padding:2px 10px; cursor:pointer; transition:background .15s; text-decoration:none; color:#fff; font-size:.75rem; }
        .hero-hint-tag:hover { background:rgba(255,255,255,.25); color:#fff; text-decoration:none; }

        /* ── Subject Filter Bar ── */
        .subject-bar-wrap { background:#fff; border-bottom:1.5px solid #e2e8f0; position:sticky; top:62px; z-index:90; box-shadow:0 2px 8px rgba(0,0,0,.05); }
        .subject-bar { display:flex; align-items:center; overflow-x:auto; gap:0; scrollbar-width:none; -ms-overflow-style:none; }
        .subject-bar::-webkit-scrollbar { display:none; }
        .subject-tab { display:inline-flex; align-items:center; gap:7px; padding:14px 20px; font-size:.85rem; font-weight:700; color:#64748b; text-decoration:none; border-bottom:3px solid transparent; white-space:nowrap; transition:all .15s; flex-shrink:0; }
        .subject-tab:hover { color:#16a085; text-decoration:none; border-bottom-color:#a7f3d0; }
        .subject-tab.active { color:#16a085; border-bottom-color:#16a085; background:rgba(22,160,133,.04); text-decoration:none; }
        .subject-tab .tab-count { background:#f1f5f9; color:#64748b; border-radius:20px; padding:1px 8px; font-size:.68rem; font-weight:700; transition:all .15s; }
        .subject-tab.active .tab-count { background:#d1fae5; color:#065f46; }
        .subject-bar-scroll-hint { flex-shrink:0; color:#cbd5e1; padding:0 8px; font-size:.75rem; }

        /* ── Active Filter Strip ── */
        .filter-strip { background:#fffbeb; border:1.5px solid #fde68a; border-radius:12px; padding:10px 16px; margin-bottom:16px; display:flex; align-items:center; flex-wrap:wrap; gap:8px; }
        .filter-chip { display:inline-flex; align-items:center; gap:5px; background:#fef3c7; border:1px solid #fbbf24; border-radius:20px; padding:4px 12px; font-size:.78rem; font-weight:700; color:#92400e; }
        .filter-chip .rm { color:#b45309; cursor:pointer; font-size:.7rem; text-decoration:none; margin-left:2px; }
        .filter-chip .rm:hover { color:#92400e; }
        .clear-all { margin-left:auto; font-size:.78rem; color:#dc2626; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:4px; }
        .clear-all:hover { color:#b91c1c; text-decoration:none; }

        /* ── Tag Cloud ── */
        .tag-cloud { display:flex; flex-wrap:wrap; gap:5px; }
        .kh-tag { display:inline-flex; align-items:center; gap:3px; background:#f0fdf4; color:#065f46; border:1px solid #a7f3d0; border-radius:20px; padding:4px 11px; font-size:.73rem; font-weight:600; text-decoration:none; transition:all .15s; }
        .kh-tag:hover, .kh-tag.active { background:#16a085; color:#fff; border-color:#16a085; text-decoration:none; }
        .kh-tag .cnt { opacity:.6; font-size:.65rem; }

        /* ── Sidebar ── */
        .kh-side-box { background:#fff; border-radius:12px; padding:16px; border:1.5px solid #f1f5f9; margin-bottom:14px; }
        .kh-side-title { font-size:.72rem; font-weight:800; color:#64748b; letter-spacing:.5px; text-transform:uppercase; margin-bottom:12px; display:flex; align-items:center; gap:6px; }

        /* ── View toggle ── */
        .view-toggle { display:flex; gap:3px; }
        .vt-btn { width:32px; height:32px; border:1.5px solid #e2e8f0; background:#fff; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#94a3b8; font-size:.82rem; transition:all .15s; }
        .vt-btn:hover { border-color:#16a085; color:#16a085; }
        .vt-btn.active { background:#16a085; border-color:#16a085; color:#fff; }

        /* ── Article Card ── */
        .art-card { background:#fff; border-radius:14px; border:1.5px solid #f1f5f9; box-shadow:0 2px 10px rgba(0,0,0,.05); padding:20px; height:100%; display:flex; flex-direction:column; transition:transform .18s,box-shadow .18s; text-decoration:none; color:inherit; }
        .art-card:hover { transform:translateY(-3px); box-shadow:0 8px 28px rgba(0,0,0,.1); text-decoration:none; color:inherit; }
        .art-card-thumb { margin:-20px -20px 14px; height:110px; border-radius:12px 12px 0 0; overflow:hidden; }
        .art-card-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .art-card-subject { font-size:.7rem; font-weight:700; color:#16a085; background:#f0fdf4; border-radius:20px; padding:3px 10px; display:inline-block; margin-bottom:8px; }
        .art-card-title { font-size:.95rem; font-weight:700; color:#1e293b; line-height:1.45; margin-bottom:7px; flex:1; }
        .art-card-excerpt { font-size:.8rem; color:#64748b; line-height:1.65; margin-bottom:10px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .art-badge { background:#f8fafc; color:#475569; border-radius:8px; padding:2px 7px; font-size:.67rem; font-weight:600; }
        .art-badge.yt  { background:#fee2e2; color:#dc2626; }
        .art-badge.cv  { background:#ede9fe; color:#7c3aed; }
        .art-badge.eb  { background:#d1fae5; color:#059669; }
        .art-badge.dl  { background:#fef3c7; color:#d97706; }
        .art-card-meta { display:flex; align-items:center; gap:8px; font-size:.72rem; color:#94a3b8; margin-top:auto; padding-top:10px; border-top:1px solid #f8fafc; }

        /* ── Table view ── */
        .art-table { width:100%; border-collapse:separate; border-spacing:0 5px; }
        .art-table thead th { font-size:.7rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:.5px; padding:8px 12px; border-bottom:2px solid #e2e8f0; }
        .art-table tbody tr { background:#fff; transition:all .15s; }
        .art-table tbody tr:hover { box-shadow:0 2px 12px rgba(22,160,133,.12); }
        .art-table tbody td { padding:11px 12px; border-top:1.5px solid #f1f5f9; border-bottom:1.5px solid #f1f5f9; vertical-align:middle; }
        .art-table tbody td:first-child { border-left:1.5px solid #f1f5f9; border-radius:10px 0 0 10px; }
        .art-table tbody td:last-child  { border-right:1.5px solid #f1f5f9; border-radius:0 10px 10px 0; }
        .tbl-title { font-size:.88rem; font-weight:700; color:#1e293b; text-decoration:none; line-height:1.4; display:block; }
        .tbl-title:hover { color:#16a085; text-decoration:none; }
        .tbl-sub-badge { font-size:.67rem; font-weight:700; color:#16a085; background:#f0fdf4; border-radius:8px; padding:2px 8px; white-space:nowrap; }
        .tbl-meta { font-size:.72rem; color:#94a3b8; white-space:nowrap; }

        /* ── Pagination ── */
        .page-link { color:#16a085; }
        .page-item.active .page-link { background:#16a085; border-color:#16a085; }

        /* ── Empty ── */
        .kh-empty { text-align:center; padding:60px 20px; color:#94a3b8; }
        .kh-empty i { font-size:3rem; margin-bottom:14px; display:block; opacity:.4; }
        .kh-empty p { font-size:.9rem; margin-bottom:12px; }
    </style>
</head>
<body>

@include('knowhow._navbar')

{{-- ── HERO + SEARCH ── --}}
<section class="kh-hero">
    <div class="container" style="position:relative;z-index:2;">
        <div class="hero-label"><i class="fas fa-lightbulb"></i>คลังความรู้</div>
        <h1><i class="fas fa-book-reader mr-2" style="opacity:.75;"></i>คลังความรู้ธรรมศึกษา (Know How)</h1>
        <p class="hero-sub">เนื้อหา สื่อการสอน บทความ วิดีโอ แนวข้อสอบ เตรียมความพร้อมก่อนสอบสนามหลวง</p>

        <div class="hero-search-wrap">
            <form action="{{ route('knowhow.index') }}" method="GET" id="search-form">
                <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                <div class="hero-search-box">
                    <input type="text" name="search" id="hero-search"
                           value="{{ request('search') }}"
                           placeholder="ค้นหาบทความ ชื่อเรื่อง หรือแท็ก..."
                           autocomplete="off">
                    @if(request()->hasAny(['search','subject_id','tag']))
                    <a href="{{ route('knowhow.index') }}"
                       style="background:#f1f5f9;color:#64748b;border:none;padding:0 16px;display:flex;align-items:center;text-decoration:none;font-size:.85rem;"
                       title="ล้างการค้นหา">
                        <i class="fas fa-times"></i>
                    </a>
                    @endif
                    <button type="submit"><i class="fas fa-search"></i>ค้นหา</button>
                </div>
            </form>
            @if($allTags->isNotEmpty())
            <div class="hero-search-hint">
                <span style="opacity:.8;">แท็กยอดนิยม:</span>
                @foreach($allTags->take(6) as $tag => $cnt)
                <a href="{{ route('knowhow.index', ['tag'=>$tag]) }}" class="hero-hint-tag">{{ $tag }}</a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ── SUBJECT TAB BAR ── --}}
@if($subjects->isNotEmpty())
<div class="subject-bar-wrap">
    <div class="container px-0">
        <div class="subject-bar">
            <a href="{{ route('knowhow.index', request()->except(['subject_id','page'])) }}"
               class="subject-tab {{ !request('subject_id') ? 'active' : '' }}">
                <i class="fas fa-th" style="font-size:.75rem;"></i>
                ทั้งหมด
                <span class="tab-count">{{ $articles->total() }}</span>
            </a>
            @foreach($subjects as $s)
            <a href="{{ route('knowhow.index', array_merge(request()->except('page'), ['subject_id'=>$s->id])) }}"
               class="subject-tab {{ request('subject_id') == $s->id ? 'active' : '' }}">
                {{ $s->name }}
                @if(request('subject_id') == $s->id)
                <span class="tab-count">{{ $articles->total() }}</span>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ── MAIN CONTENT ── --}}
<div class="container py-4">
    <div class="row">

        {{-- ── SIDEBAR ── --}}
        <div class="col-lg-3 mb-4">

            {{-- Tag cloud --}}
            @if($allTags->isNotEmpty())
            <div class="kh-side-box">
                <div class="kh-side-title">
                    <i class="fas fa-tags" style="color:#16a085;"></i>แท็กยอดนิยม
                </div>
                <div class="tag-cloud">
                    @foreach($allTags as $tag => $cnt)
                    <a href="{{ route('knowhow.index', array_merge(request()->except(['tag','page']), ['tag'=>$tag])) }}"
                       class="kh-tag {{ request('tag') === $tag ? 'active' : '' }}">
                        <i class="fas fa-hashtag" style="font-size:.6rem;"></i>{{ $tag }}
                        <span class="cnt">({{ $cnt }})</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick links --}}
            <div class="kh-side-box">
                <div class="kh-side-title">
                    <i class="fas fa-rocket" style="color:#16a085;"></i>ลิงก์ด่วน
                </div>
                <a href="{{ route('pretest.index') }}"
                   style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid #f1f5f9;text-decoration:none;color:#374151;font-size:.85rem;font-weight:600;">
                    <span style="width:28px;height:28px;background:#ecfdf5;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-pencil-alt" style="color:#16a085;font-size:.78rem;"></i>
                    </span>Pre-Test ฟรี
                </a>
                <a href="{{ route('certificates.verify', 'example') }}"
                   style="display:flex;align-items:center;gap:10px;padding:9px 0;text-decoration:none;color:#374151;font-size:.85rem;font-weight:600;">
                    <span style="width:28px;height:28px;background:#fffbeb;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-certificate" style="color:#f59e0b;font-size:.78rem;"></i>
                    </span>ตรวจสอบใบประกาศ
                </a>
            </div>
        </div>

        {{-- ── ARTICLES ── --}}
        <div class="col-lg-9">

            {{-- Active filter strip --}}
            @if(request()->hasAny(['search','subject_id','tag']))
            <div class="filter-strip mb-3">
                <span style="font-size:.78rem;font-weight:700;color:#92400e;white-space:nowrap;">
                    <i class="fas fa-filter mr-1"></i>กำลังกรอง:
                </span>
                @if(request('search'))
                <span class="filter-chip">
                    <i class="fas fa-search" style="font-size:.7rem;"></i>
                    "{{ request('search') }}"
                    <a href="{{ route('knowhow.index', request()->except(['search','page'])) }}" class="rm"><i class="fas fa-times"></i></a>
                </span>
                @endif
                @if(request('subject_id'))
                @php $activeSub = $subjects->firstWhere('id', request('subject_id')); @endphp
                @if($activeSub)
                <span class="filter-chip">
                    <i class="fas fa-layer-group" style="font-size:.7rem;"></i>
                    {{ $activeSub->name }}
                    <a href="{{ route('knowhow.index', request()->except(['subject_id','page'])) }}" class="rm"><i class="fas fa-times"></i></a>
                </span>
                @endif
                @endif
                @if(request('tag'))
                <span class="filter-chip">
                    <i class="fas fa-hashtag" style="font-size:.7rem;"></i>
                    {{ request('tag') }}
                    <a href="{{ route('knowhow.index', request()->except(['tag','page'])) }}" class="rm"><i class="fas fa-times"></i></a>
                </span>
                @endif
                <a href="{{ route('knowhow.index') }}" class="clear-all">
                    <i class="fas fa-times-circle"></i>ล้างทั้งหมด
                </a>
            </div>
            @endif

            {{-- Toolbar: count + view toggle --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div style="font-size:.83rem;color:#64748b;">
                    พบ <strong style="color:#1e293b;">{{ $articles->total() }}</strong> บทความ
                </div>
                <div class="view-toggle">
                    <button class="vt-btn active" id="btn-grid" onclick="setView('grid')" title="แบบการ์ด">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="vt-btn" id="btn-table" onclick="setView('table')" title="แบบตาราง">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            @if($articles->isEmpty())
            <div class="kh-empty">
                <i class="fas fa-search"></i>
                <p>ไม่พบบทความที่ตรงกัน</p>
                <a href="{{ route('knowhow.index') }}"
                   style="background:#16a085;color:#fff;border-radius:20px;padding:8px 22px;text-decoration:none;font-weight:700;font-size:.85rem;">
                    ดูบทความทั้งหมด
                </a>
            </div>
            @else

            {{-- GRID VIEW --}}
            <div id="view-grid">
                <div class="row">
                    @foreach($articles as $a)
                    <div class="col-sm-6 col-xl-4 mb-4">
                        <a href="{{ route('knowhow.show', $a) }}" class="art-card">
                            @if($a->image_url)
                            <div class="art-card-thumb">
                                <img src="{{ $a->image_url }}" alt="{{ $a->title }}">
                            </div>
                            @endif
                            @if($a->subject)
                            <div class="art-card-subject">{{ $a->subject->name }}</div>
                            @endif
                            <div class="art-card-title">{{ $a->title }}</div>
                            <div class="art-card-excerpt">{{ mb_substr(strip_tags($a->body), 0, 100) }}…</div>
                            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px;">
                                @if($a->youtube_url)<span class="art-badge yt"><i class="fab fa-youtube mr-1"></i>VDO</span>@endif
                                @if($a->canva_url)<span class="art-badge cv"><i class="fas fa-chalkboard-teacher mr-1"></i>Slides</span>@endif
                                @if($a->ebook_url)<span class="art-badge eb"><i class="fas fa-book-open mr-1"></i>Ebook</span>@endif
                                @if($a->file_url)<span class="art-badge dl"><i class="fas fa-file-pdf mr-1"></i>PDF</span>@endif
                                @foreach(array_slice($a->tags_array, 0, 2) as $tag)
                                <span class="art-badge"># {{ $tag }}</span>
                                @endforeach
                            </div>
                            <div class="art-card-meta">
                                <i class="fas fa-eye" style="color:#16a085;"></i>{{ number_format($a->view_count) }}
                                <span style="margin-left:4px;"><i class="fas fa-calendar" style="color:#16a085;"></i>{{ $a->created_at->format('d/m/Y') }}</span>
                                @if($a->creator)
                                <span style="margin-left:auto;"><i class="fas fa-user" style="color:#16a085;"></i>{{ explode(' ', $a->creator->name)[0] }}</span>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- TABLE VIEW --}}
            <div id="view-table" style="display:none;">
                <table class="art-table">
                    <thead>
                        <tr>
                            <th>บทความ</th>
                            <th class="d-none d-md-table-cell" style="width:110px;">วิชา</th>
                            <th class="d-none d-lg-table-cell" style="width:120px;">สื่อ</th>
                            <th class="d-none d-sm-table-cell text-right" style="width:80px;">ยอดชม</th>
                            <th class="text-right" style="width:90px;">วันที่</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $a)
                        <tr>
                            <td>
                                <a href="{{ route('knowhow.show', $a) }}" class="tbl-title">{{ $a->title }}</a>
                                @if($a->tags_array)
                                <div style="margin-top:4px;display:flex;flex-wrap:wrap;gap:3px;">
                                    @foreach(array_slice($a->tags_array, 0, 3) as $tag)
                                    <span style="font-size:.66rem;background:#f8fafc;color:#64748b;border-radius:6px;padding:1px 7px;">#{{ $tag }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($a->subject)
                                <span class="tbl-sub-badge">{{ $a->subject->name }}</span>
                                @else
                                <span style="color:#e2e8f0;">—</span>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <div style="display:flex;gap:4px;flex-wrap:wrap;">
                                    @if($a->youtube_url)<span class="art-badge yt" style="font-size:.64rem;"><i class="fab fa-youtube"></i></span>@endif
                                    @if($a->canva_url)<span class="art-badge cv" style="font-size:.64rem;"><i class="fas fa-chalkboard-teacher"></i></span>@endif
                                    @if($a->ebook_url)<span class="art-badge eb" style="font-size:.64rem;"><i class="fas fa-book-open"></i></span>@endif
                                    @if($a->file_url)<span class="art-badge dl" style="font-size:.64rem;"><i class="fas fa-file-pdf"></i></span>@endif
                                </div>
                            </td>
                            <td class="d-none d-sm-table-cell tbl-meta text-right">{{ number_format($a->view_count) }}</td>
                            <td class="tbl-meta text-right">{{ $a->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">{{ $articles->links() }}</div>
            @endif

        </div>{{-- /col --}}
    </div>{{-- /row --}}
</div>{{-- /container --}}

@include('knowhow._footer')

<script>
function setView(v) {
    var g = document.getElementById('view-grid');
    var t = document.getElementById('view-table');
    var bg = document.getElementById('btn-grid');
    var bt = document.getElementById('btn-table');
    if (!g || !t) return;
    if (v === 'table') {
        g.style.display = 'none'; t.style.display = 'block';
        bg.classList.remove('active'); bt.classList.add('active');
    } else {
        g.style.display = 'block'; t.style.display = 'none';
        bg.classList.add('active'); bt.classList.remove('active');
    }
    try { localStorage.setItem('kh_view', v); } catch(e){}
}
try { var sv = localStorage.getItem('kh_view'); if (sv === 'table') setView('table'); } catch(e){}
</script>
</body>
</html>
