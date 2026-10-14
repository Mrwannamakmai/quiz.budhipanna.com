<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} — คลังความรู้</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background:#f0f4f8; font-family:'Sarabun',sans-serif; font-size:15px; }

        /* ── Layout ── */
        .page-wrap { max-width:860px; margin:0 auto; padding:28px 16px 48px; }

        /* ── Breadcrumb ── */
        .breadcrumb-bar { font-size:.78rem; color:#94a3b8; margin-bottom:20px; display:flex; align-items:center; flex-wrap:wrap; gap:4px; }
        .breadcrumb-bar a { color:#16a085; text-decoration:none; }
        .breadcrumb-bar a:hover { text-decoration:underline; }
        .breadcrumb-sep { font-size:.6rem; color:#cbd5e1; }

        /* ── Hero Image ── */
        .hero-img { width:100%; max-height:420px; object-fit:cover; display:block; border-radius:18px; margin-bottom:0; box-shadow:0 8px 32px rgba(0,0,0,.12); }
        .hero-img-wrap { position:relative; margin-bottom:24px; }

        /* ── Article Card ── */
        .art-card { background:#fff; border-radius:20px; border:1.5px solid #e8f0fe; box-shadow:0 2px 16px rgba(0,0,0,.06); overflow:hidden; margin-bottom:20px; }
        .art-card-head { padding:28px 32px 24px; border-bottom:1.5px solid #f1f5f9; }
        .art-card-body { padding:28px 32px; }

        /* Subject badge */
        .subject-badge { display:inline-flex; align-items:center; gap:6px; background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#065f46; border:1px solid #6ee7b7; border-radius:20px; padding:5px 14px; font-size:.74rem; font-weight:700; margin-bottom:14px; }

        /* Title */
        .art-title { font-size:1.75rem; font-weight:800; color:#0f172a; line-height:1.35; margin-bottom:14px; }
        @media(max-width:576px){ .art-title { font-size:1.3rem; } }

        /* Tags */
        .art-tag { display:inline-flex; align-items:center; gap:4px; background:#f0fdf4; color:#065f46; border:1px solid #a7f3d0; border-radius:20px; padding:4px 12px; font-size:.72rem; font-weight:600; margin:3px; text-decoration:none; transition:all .15s; }
        .art-tag:hover { background:#16a085; color:#fff; border-color:#16a085; text-decoration:none; }

        /* Meta strip */
        .meta-strip { display:flex; flex-wrap:wrap; align-items:center; gap:16px; padding-top:16px; border-top:1.5px solid #f1f5f9; font-size:.78rem; color:#64748b; }
        .meta-strip .meta-item { display:flex; align-items:center; gap:5px; }
        .meta-strip .meta-item i { color:#16a085; font-size:.8rem; }

        /* ── Content Typography ── */
        .art-content { font-size:.97rem; line-height:2; color:#334155; white-space:pre-wrap; word-break:break-word; }

        /* ── Section Heading (media blocks) ── */
        .media-heading { display:flex; align-items:center; gap:8px; font-size:.78rem; font-weight:800; color:#475569; letter-spacing:.6px; text-transform:uppercase; margin-bottom:12px; }
        .media-heading::after { content:''; flex:1; height:1.5px; background:#f1f5f9; }

        /* ── YouTube / Canva embed ── */
        .embed-wrap { position:relative; padding-bottom:56.25%; height:0; border-radius:14px; overflow:hidden; background:#0f172a; box-shadow:0 4px 20px rgba(0,0,0,.12); }
        .embed-wrap iframe { position:absolute; top:0; left:0; width:100%; height:100%; border:none; }

        /* ── Ebook & File buttons ── */
        .dl-btn { display:flex; align-items:center; gap:14px; border-radius:14px; padding:14px 20px; text-decoration:none; font-weight:700; font-size:.9rem; flex:1; min-width:220px; transition:transform .15s,box-shadow .15s; border:none; }
        .dl-btn:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.12); text-decoration:none; }
        .dl-btn-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
        .dl-btn-sub { font-size:.68rem; font-weight:600; opacity:.7; margin-bottom:1px; }

        /* ── Related articles ── */
        .rel-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        @media(max-width:576px){ .rel-grid { grid-template-columns:1fr; } }
        .rel-card { background:#fff; border-radius:12px; border:1.5px solid #f1f5f9; padding:14px 16px; text-decoration:none; color:inherit; display:flex; flex-direction:column; gap:6px; transition:all .18s; }
        .rel-card:hover { border-color:#16a085; box-shadow:0 4px 16px rgba(22,160,133,.12); transform:translateY(-2px); text-decoration:none; color:inherit; }
        .rel-subject { font-size:.68rem; font-weight:700; color:#16a085; }
        .rel-title { font-size:.88rem; font-weight:700; color:#1e293b; line-height:1.4; }
        .rel-meta  { font-size:.72rem; color:#94a3b8; }

        /* ── CTA strip ── */
        .cta-strip { background:linear-gradient(135deg,#ecfdf5,#f0fdf4); border:1.5px solid #bbf7d0; border-radius:16px; padding:20px 24px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
        .cta-strip-title { font-weight:700; color:#065f46; font-size:.95rem; }
        .cta-strip-sub   { font-size:.78rem; color:#059669; margin-top:2px; }
        .btn-pretest { background:linear-gradient(135deg,#16a085,#1abc9c); color:#fff; border-radius:25px; padding:10px 24px; font-weight:700; font-size:.88rem; text-decoration:none; border:none; white-space:nowrap; transition:all .15s; }
        .btn-pretest:hover { background:linear-gradient(135deg,#0e7060,#16a085); color:#fff; text-decoration:none; box-shadow:0 4px 12px rgba(22,160,133,.35); }

        /* ── Action bar ── */
        .action-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:18px; }
        .btn-back { display:inline-flex; align-items:center; gap:6px; color:#16a085; font-size:.85rem; font-weight:700; text-decoration:none; background:#fff; border:1.5px solid #a7f3d0; border-radius:20px; padding:6px 16px; transition:all .15s; }
        .btn-back:hover { background:#ecfdf5; text-decoration:none; color:#065f46; }
        .btn-print { display:inline-flex; align-items:center; gap:6px; background:#f8fafc; color:#475569; border:1.5px solid #e2e8f0; border-radius:20px; padding:6px 16px; font-size:.82rem; font-weight:600; cursor:pointer; font-family:inherit; transition:all .15s; }
        .btn-print:hover { background:#f1f5f9; color:#334155; }

        /* ── Copy link ── */
        .btn-copy { background:none; border:1.5px solid #e2e8f0; border-radius:8px; padding:4px 12px; font-size:.72rem; color:#475569; cursor:pointer; font-family:inherit; transition:all .15s; }
        .btn-copy:hover { background:#f8fafc; border-color:#cbd5e1; }

        /* ── Print ── */
        @media print {
            .navbar-main, .no-print, footer { display:none !important; }
            body { background:#fff; }
            .art-card { border:none; box-shadow:none; }
        }
    </style>
</head>
<body>

@include('knowhow._navbar')

<div class="page-wrap">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb-bar no-print">
        <a href="{{ route('landing') }}"><i class="fas fa-home"></i></a>
        <span class="breadcrumb-sep"><i class="fas fa-chevron-right"></i></span>
        <a href="{{ route('knowhow.index') }}">คลังความรู้</a>
        <span class="breadcrumb-sep"><i class="fas fa-chevron-right"></i></span>
        <span>{{ mb_substr($article->title, 0, 45) }}{{ mb_strlen($article->title) > 45 ? '…' : '' }}</span>
    </nav>

    {{-- Action bar --}}
    <div class="action-bar no-print">
        <a href="{{ route('knowhow.index') }}" class="btn-back">
            <i class="fas fa-arrow-left" style="font-size:.78rem;"></i>คลังความรู้
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print" style="font-size:.78rem;"></i>พิมพ์
        </button>
    </div>

    {{-- Hero Image (ถ้ามี) --}}
    @if($article->image_url)
    <div class="hero-img-wrap no-print">
        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="hero-img">
    </div>
    @endif

    {{-- Main Article Card --}}
    <div class="art-card">
        <div class="art-card-head">
            {{-- Subject --}}
            @if($article->subject)
            <div>
                <span class="subject-badge">
                    <i class="fas fa-layer-group" style="font-size:.75rem;"></i>{{ $article->subject->name }}
                </span>
            </div>
            @endif

            {{-- Title --}}
            <h1 class="art-title">{{ $article->title }}</h1>

            {{-- Tags --}}
            @if($article->tags_array)
            <div style="margin-bottom:14px;">
                @foreach($article->tags_array as $tag)
                <a href="{{ route('knowhow.index', ['tag'=>$tag]) }}" class="art-tag">
                    <i class="fas fa-hashtag" style="font-size:.6rem;"></i>{{ $tag }}
                </a>
                @endforeach
            </div>
            @endif

            {{-- Meta --}}
            <div class="meta-strip">
                @if($article->creator)
                <span class="meta-item"><i class="fas fa-user-circle"></i>{{ $article->creator->name }}</span>
                @endif
                <span class="meta-item"><i class="fas fa-calendar-alt"></i>{{ $article->created_at->format('d M Y') }}</span>
                <span class="meta-item"><i class="fas fa-eye"></i>{{ number_format($article->view_count) }} ครั้ง</span>
                <span style="margin-left:auto;" class="no-print">
                    <button onclick="copyLink(this)" class="btn-copy">
                        <i class="fas fa-link mr-1"></i>คัดลอกลิงก์
                    </button>
                </span>
            </div>
        </div>

        {{-- Content --}}
        <div class="art-card-body">
            <div class="art-content">{{ $article->body }}</div>
        </div>
    </div>

    {{-- YouTube --}}
    @if($article->youtube_url)
    @php
        $ytId = null;
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $article->youtube_url, $m)) {
            $ytId = $m[1];
        }
    @endphp
    @if($ytId)
    <div style="margin-bottom:24px;">
        <div class="media-heading">
            <i class="fab fa-youtube" style="color:#dc2626;font-size:1rem;"></i>วิดีโอประกอบ
        </div>
        <div class="embed-wrap">
            <iframe src="https://www.youtube.com/embed/{{ $ytId }}"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
    @endif
    @endif

    {{-- Canva --}}
    @if($article->canva_url)
    <div style="margin-bottom:24px;">
        <div class="media-heading">
            <i class="fas fa-chalkboard-teacher" style="color:#7c3aed;font-size:.95rem;"></i>สไลด์ประกอบ
        </div>
        <div class="embed-wrap" style="background:#f8f4ff;">
            <iframe src="{{ $article->canva_url }}" allowfullscreen></iframe>
        </div>
        <div style="text-align:right;margin-top:8px;">
            <a href="{{ $article->canva_url }}" target="_blank"
               style="font-size:.75rem;color:#7c3aed;text-decoration:none;font-weight:600;">
                <i class="fas fa-external-link-alt mr-1"></i>เปิดเต็มหน้าจอใน Canva
            </a>
        </div>
    </div>
    @endif

    {{-- NotebookLM Presentations --}}
    @php $nlmItems = $article->notebooklm_items ?? []; @endphp
    @if(!empty($nlmItems))
    <div style="margin-bottom:24px;">
        <div class="media-heading">
            <i class="fas fa-brain" style="color:#0369a1;font-size:.95rem;"></i>Presentation จาก NotebookLM
        </div>

        <div style="display:flex;flex-direction:column;gap:12px;">
        @foreach($nlmItems as $nlmIdx => $nlm)
        @php $nlmLabel = !empty($nlm['label']) ? $nlm['label'] : ('Presentation ' . ($nlmIdx + 1)); @endphp

        {{-- Preview Card --}}
        <div style="background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 60%,#1d4ed8 100%);border-radius:16px;overflow:hidden;box-shadow:0 6px 24px rgba(29,78,216,.22);position:relative;">
            <div style="position:absolute;top:0;right:0;width:160px;height:160px;background:radial-gradient(circle,rgba(99,179,237,.12) 0%,transparent 70%);pointer-events:none;"></div>
            <div style="padding:20px 22px 16px;">
                <div style="display:flex;align-items:center;gap:14px;">
                    <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(255,255,255,.2);">
                        <i class="fas fa-brain" style="color:#93c5fd;font-size:1.15rem;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.65rem;font-weight:700;color:#93c5fd;letter-spacing:.7px;text-transform:uppercase;margin-bottom:3px;">Google NotebookLM</div>
                        <div style="font-size:.95rem;font-weight:800;color:#fff;line-height:1.3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $nlmLabel }}</div>
                    </div>
                    @if(count($nlmItems) > 1)
                    <div style="flex-shrink:0;background:rgba(255,255,255,.12);color:#93c5fd;border-radius:20px;padding:3px 10px;font-size:.68rem;font-weight:700;">{{ $nlmIdx + 1 }}/{{ count($nlmItems) }}</div>
                    @endif
                </div>
                <div style="display:flex;gap:8px;margin-top:16px;flex-wrap:wrap;">
                    <button onclick="openNLMPopup({{ $nlmIdx }}, '{{ addslashes($nlm['url']) }}', '{{ addslashes($nlmLabel) }}')"
                            style="display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;border:none;border-radius:20px;padding:9px 20px;font-size:.84rem;font-weight:700;cursor:pointer;font-family:inherit;box-shadow:0 3px 10px rgba(59,130,246,.4);transition:all .15s;"
                            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 5px 16px rgba(59,130,246,.5)';"
                            onmouseout="this.style.transform='';this.style.boxShadow='0 3px 10px rgba(59,130,246,.4)';">
                        <i class="fas fa-expand-alt" style="font-size:.78rem;"></i>เปิดดู Presentation
                    </button>
                    <a href="{{ $nlm['url'] }}" target="_blank" rel="noopener"
                       style="display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,.1);color:#e2e8f0;border:1px solid rgba(255,255,255,.18);border-radius:20px;padding:9px 16px;font-size:.8rem;font-weight:600;text-decoration:none;transition:all .15s;"
                       onmouseover="this.style.background='rgba(255,255,255,.2)';"
                       onmouseout="this.style.background='rgba(255,255,255,.1)';">
                        <i class="fas fa-external-link-alt" style="font-size:.72rem;"></i>แท็บใหม่
                    </a>
                </div>
            </div>
            <div style="background:rgba(0,0,0,.2);padding:6px 22px;display:flex;align-items:center;gap:5px;">
                <i class="fas fa-circle" style="color:#22c55e;font-size:.4rem;"></i>
                <i class="fas fa-circle" style="color:#f59e0b;font-size:.4rem;"></i>
                <i class="fas fa-circle" style="color:#ef4444;font-size:.4rem;"></i>
                <span style="font-size:.65rem;color:#64748b;margin-left:5px;">notebooklm.google.com</span>
            </div>
        </div>
        @endforeach
        </div>
    </div>

    {{-- Single shared Popup Modal --}}
    <div id="nlm-backdrop"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.78);z-index:9999;backdrop-filter:blur(4px);"
         onclick="closeNLMPopup(event)">
        <div id="nlm-modal"
             style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:95vw;max-width:1100px;height:90vh;display:flex;flex-direction:column;border-radius:16px;overflow:hidden;box-shadow:0 24px 80px rgba(0,0,0,.5);">

            {{-- Toolbar --}}
            <div style="background:#1e293b;display:flex;align-items:center;gap:10px;padding:10px 16px;flex-shrink:0;user-select:none;">
                <div style="width:14px;height:14px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-brain" style="color:#93c5fd;font-size:.55rem;"></i>
                </div>
                <span id="nlm-title" style="font-size:.8rem;font-weight:700;color:#e2e8f0;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
                <a id="nlm-btn-newt" href="#" target="_blank" rel="noopener" title="เปิดในแท็บใหม่"
                   style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.08);color:#94a3b8;text-decoration:none;transition:all .15s;border:none;font-size:.8rem;"
                   onmouseover="this.style.background='rgba(255,255,255,.16)';this.style.color='#e2e8f0';"
                   onmouseout="this.style.background='rgba(255,255,255,.08)';this.style.color='#94a3b8';">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                <button id="nlm-btn-fs" onclick="toggleNLMFullscreen()" title="เต็มหน้าจอ"
                        style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.08);color:#94a3b8;cursor:pointer;font-family:inherit;border:none;transition:all .15s;font-size:.8rem;"
                        onmouseover="this.style.background='rgba(255,255,255,.16)';this.style.color='#e2e8f0';"
                        onmouseout="this.style.background='rgba(255,255,255,.08)';this.style.color='#94a3b8';">
                    <i id="nlm-fs-icon" class="fas fa-expand"></i>
                </button>
                <button onclick="closeNLMPopup(null)" title="ปิด"
                        style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:rgba(239,68,68,.15);color:#f87171;cursor:pointer;font-family:inherit;border:none;transition:all .15s;font-size:.85rem;"
                        onmouseover="this.style.background='rgba(239,68,68,.3)';this.style.color='#fca5a5';"
                        onmouseout="this.style.background='rgba(239,68,68,.15)';this.style.color='#f87171';">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- iframe area --}}
            <div style="flex:1;position:relative;background:#f1f5f9;">
                <iframe id="nlm-frame" src="about:blank"
                        style="width:100%;height:100%;border:none;display:block;"
                        allow="autoplay; fullscreen" allowfullscreen></iframe>
                <div id="nlm-fallback"
                     style="display:none;position:absolute;inset:0;background:#f8fafc;flex-direction:column;align-items:center;justify-content:center;gap:16px;padding:32px;text-align:center;">
                    <div style="width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#2563eb,#3b82f6);display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(59,130,246,.3);">
                        <i class="fas fa-brain" style="color:#fff;font-size:1.6rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:1rem;font-weight:800;color:#1e293b;margin-bottom:6px;">Google NotebookLM</div>
                        <div style="font-size:.82rem;color:#64748b;max-width:340px;line-height:1.6;">ไม่สามารถแสดง Embedded ได้เนื่องจากนโยบายของ Google<br>กรุณาเปิดในแท็บใหม่เพื่อดู Presentation</div>
                    </div>
                    <a id="nlm-fallback-link" href="#" target="_blank" rel="noopener"
                       style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;border-radius:25px;padding:12px 28px;font-size:.9rem;font-weight:700;text-decoration:none;box-shadow:0 4px 14px rgba(59,130,246,.35);">
                        <i class="fas fa-external-link-alt"></i>เปิดดู Presentation ใน NotebookLM
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
    @keyframes nlmFadeIn  { from{opacity:0} to{opacity:1} }
    @keyframes nlmSlideIn { from{transform:translate(-50%,-48%);opacity:0} to{transform:translate(-50%,-50%);opacity:1} }
    #nlm-backdrop { animation:nlmFadeIn .2s ease; }
    #nlm-modal    { animation:nlmSlideIn .22s ease; }
    #nlm-backdrop.nlm-fullscreen #nlm-modal {
        width:100vw;max-width:100vw;height:100vh;top:0;left:0;transform:none;border-radius:0;
    }
    </style>

    <script>
    var _nlmLoadedUrl = null;

    function openNLMPopup(idx, url, label) {
        var bd = document.getElementById('nlm-backdrop');
        bd.style.display = 'block';
        document.body.style.overflow = 'hidden';
        document.getElementById('nlm-title').textContent = 'NotebookLM — ' + label;
        document.getElementById('nlm-btn-newt').href = url;
        document.getElementById('nlm-fallback-link').href = url;

        if (_nlmLoadedUrl !== url) {
            _nlmLoadedUrl = url;
            var frame = document.getElementById('nlm-frame');
            var fb = document.getElementById('nlm-fallback');
            frame.style.display = 'block';
            fb.style.display = 'none';
            frame.src = url;
            setTimeout(function() {
                try {
                    var doc = frame.contentDocument || frame.contentWindow.document;
                    if (!doc || !doc.body || doc.body.innerHTML === '') { showNLMFallback(url); }
                } catch(e) { /* cross-origin = loaded fine */ }
            }, 4000);
        }
    }

    function showNLMFallback(url) {
        document.getElementById('nlm-frame').style.display = 'none';
        var fb = document.getElementById('nlm-fallback');
        fb.style.display = 'flex';
    }

    function closeNLMPopup(e) {
        if (e && e.target !== document.getElementById('nlm-backdrop')) return;
        document.getElementById('nlm-backdrop').style.display = 'none';
        document.getElementById('nlm-backdrop').classList.remove('nlm-fullscreen');
        document.getElementById('nlm-fs-icon').className = 'fas fa-expand';
        document.getElementById('nlm-frame').src = 'about:blank';
        _nlmLoadedUrl = null;
        document.body.style.overflow = '';
    }

    function toggleNLMFullscreen() {
        var bd   = document.getElementById('nlm-backdrop');
        var icon = document.getElementById('nlm-fs-icon');
        var isFs = bd.classList.toggle('nlm-fullscreen');
        icon.className = isFs ? 'fas fa-compress' : 'fas fa-expand';
    }

    document.addEventListener('keydown', function(e) {
        if (document.getElementById('nlm-backdrop').style.display === 'none') return;
        if (e.key === 'Escape') closeNLMPopup(null);
        if (e.key === 'f' || e.key === 'F') toggleNLMFullscreen();
    });
    </script>
    @endif

    {{-- Ebook & File Download --}}
    @if($article->ebook_url || $article->file_url)
    <div style="margin-bottom:24px;">
        <div class="media-heading">
            <i class="fas fa-download" style="color:#16a085;font-size:.9rem;"></i>ดาวน์โหลดและอ่านเพิ่มเติม
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:12px;">
            @if($article->ebook_url)
            <a href="{{ $article->ebook_url }}" target="_blank" rel="noopener"
               class="dl-btn" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);color:#065f46;border:1.5px solid #6ee7b7;">
                <span class="dl-btn-icon" style="background:#fff;color:#059669;">
                    <i class="fas fa-book-open"></i>
                </span>
                <div>
                    <div class="dl-btn-sub">อ่านออนไลน์</div>
                    อ่าน Ebook
                </div>
                <i class="fas fa-external-link-alt ml-auto" style="font-size:.8rem;opacity:.5;"></i>
            </a>
            @endif

            @if($article->file_url)
            <a href="{{ $article->file_url }}" target="_blank" rel="noopener"
               class="dl-btn" style="background:linear-gradient(135deg,#fefce8,#fef08a);color:#713f12;border:1.5px solid #fde047;">
                <span class="dl-btn-icon" style="background:#fff;color:#ca8a04;">
                    <i class="fas fa-file-pdf"></i>
                </span>
                <div>
                    <div class="dl-btn-sub">ดาวน์โหลดไฟล์</div>
                    {{ $article->file_name ?: 'ดาวน์โหลด PDF' }}
                </div>
                <i class="fas fa-download ml-auto" style="font-size:.8rem;opacity:.5;"></i>
            </a>
            @endif
        </div>
    </div>
    @endif

    {{-- Related --}}
    @if($related->isNotEmpty())
    <div style="margin-bottom:24px;" class="no-print">
        <div class="media-heading">
            <i class="fas fa-book-reader" style="color:#16a085;font-size:.9rem;"></i>บทความที่เกี่ยวข้อง
        </div>
        <div class="rel-grid">
            @foreach($related as $r)
            <a href="{{ route('knowhow.show', $r) }}" class="rel-card">
                @if($r->subject)
                <div class="rel-subject"><i class="fas fa-layer-group mr-1" style="font-size:.65rem;"></i>{{ $r->subject->name }}</div>
                @endif
                <div class="rel-title">{{ $r->title }}</div>
                <div class="rel-meta">
                    <i class="fas fa-eye mr-1"></i>{{ number_format($r->view_count) }}
                    &nbsp;·&nbsp;<i class="fas fa-calendar mr-1"></i>{{ $r->created_at->format('d/m/Y') }}
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- CTA Strip --}}
    <div class="cta-strip no-print">
        <div>
            <div class="cta-strip-title"><i class="fas fa-pencil-alt mr-2"></i>ทดสอบความเข้าใจ</div>
            <div class="cta-strip-sub">ทำ Pre-Test ฟรี ไม่ต้องสมัครสมาชิก รับใบประกาศเมื่อผ่าน</div>
        </div>
        <a href="{{ route('pretest.index') }}" class="btn-pretest">
            <i class="fas fa-play-circle mr-1"></i>เริ่มทำแบบทดสอบ
        </a>
    </div>

    {{-- Back link --}}
    <div class="text-center no-print mt-4">
        <a href="{{ route('knowhow.index') }}" style="color:#94a3b8;font-size:.82rem;text-decoration:none;">
            <i class="fas fa-arrow-left mr-1"></i>กลับคลังความรู้ทั้งหมด
        </a>
    </div>

</div>

@include('knowhow._footer')

<script>
function copyLink(btn) {
    navigator.clipboard.writeText(window.location.href).then(function() {
        var orig = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-1" style="color:#16a085;"></i>คัดลอกแล้ว!';
        setTimeout(function(){ btn.innerHTML = orig; }, 2000);
    }).catch(function(){
        prompt('คัดลอก URL:', window.location.href);
    });
}
</script>
</body>
</html>
