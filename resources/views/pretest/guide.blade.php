<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V-Quiz | คู่มือการสอบ Pre-Test</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f8; font-size: 15px; margin: 0; }
        .fas,.far,.fab,.fal { font-family: "Font Awesome 5 Free" !important; }
        .fab { font-family: "Font Awesome 5 Brands" !important; }

        /* Navbar */
        .g-nav { background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.08); padding: 10px 0; position: sticky; top: 0; z-index: 999; }
        .g-nav .inner { display: flex; align-items: center; justify-content: space-between; }
        .g-nav .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .g-nav .logo-icon { width: 38px; height: 38px; background: linear-gradient(135deg,#16a085,#1abc9c); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1rem; }
        .g-nav .logo-text { font-size: 1.1rem; font-weight: 700; color: #16a085; }

        /* Hero */
        .g-hero {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #3b82f6 100%);
            color: #fff; padding: 52px 0 44px; text-align: center;
            position: relative; overflow: hidden;
        }
        .g-hero::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5 L55 30 L30 55 L5 30Z' fill='none' stroke='white' stroke-width='.5' opacity='.06'/%3E%3C/svg%3E");
        }
        .g-hero * { position: relative; }
        .g-hero h1 { font-size: 1.9rem; font-weight: 800; margin-bottom: 8px; text-shadow: 0 2px 8px rgba(0,0,0,.15); }
        .g-hero p { font-size: 1rem; opacity: .88; margin: 0; }
        .g-hero .breadcrumb-bar { display: flex; justify-content: center; gap: 8px; margin-bottom: 18px; font-size: .85rem; }
        .g-hero .breadcrumb-bar a { color: rgba(255,255,255,.7); text-decoration: none; }
        .g-hero .breadcrumb-bar a:hover { color: #fff; }
        .g-hero .breadcrumb-bar span { color: rgba(255,255,255,.5); }

        /* TOC nav */
        .toc-bar {
            background: #fff; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,.06);
            padding: 16px 24px; margin-top: -28px; position: relative; z-index: 10;
            display: flex; flex-wrap: wrap; justify-content: center; gap: 8px;
        }
        .toc-link {
            display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px;
            border-radius: 25px; font-size: .85rem; font-weight: 600;
            color: #475569; text-decoration: none; border: 1.5px solid #e2e8f0;
            transition: all .2s;
        }
        .toc-link:hover { background: #2563eb; color: #fff; border-color: #2563eb; text-decoration: none; transform: translateY(-1px); }
        .toc-link .toc-icon { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: .65rem; }

        /* Section */
        .g-section { margin-bottom: 48px; scroll-margin-top: 90px; }
        .section-header {
            display: flex; align-items: center; gap: 14px; margin-bottom: 22px;
            padding-bottom: 14px; border-bottom: 3px solid #f1f5f9;
        }
        .section-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: #fff; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }
        .section-header h3 { font-size: 1.2rem; font-weight: 800; color: #1e293b; margin: 0; }
        .section-header p { font-size: .82rem; color: #94a3b8; margin: 2px 0 0; }

        /* Steps */
        .flow-steps { display: flex; flex-direction: column; gap: 0; }
        .flow-step { display: flex; gap: 20px; position: relative; padding-bottom: 32px; }
        .flow-step:last-child { padding-bottom: 0; }
        .flow-step:last-child .step-line { display: none; }
        .step-indicator { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
        .step-circle {
            width: 52px; height: 52px; border-radius: 50%; display: flex;
            align-items: center; justify-content: center; font-size: 1.1rem;
            font-weight: 800; color: #fff; position: relative; z-index: 1;
            box-shadow: 0 4px 16px rgba(0,0,0,.18);
        }
        .step-line { width: 3px; flex: 1; border-radius: 2px; margin-top: 6px; min-height: 24px; }
        .step-body { flex: 1; padding-top: 8px; }
        .step-body h5 { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0 0 6px; }
        .step-body .desc { font-size: .9rem; color: #64748b; line-height: 1.7; margin: 0 0 12px; }
        .step-box {
            background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px;
            padding: 14px 18px; display: flex; align-items: flex-start; gap: 12px;
        }
        .step-box .sb-icon { font-size: 1.5rem; flex-shrink: 0; line-height: 1; }
        .step-box .sb-text { font-size: .85rem; color: #475569; line-height: 1.6; }
        .step-box .sb-text strong { color: #1e293b; }

        /* Step colors */
        .fs-1 .step-circle { background: linear-gradient(135deg,#16a34a,#22c55e); }
        .fs-1 .step-line   { background: linear-gradient(to bottom,#22c55e,#3b82f6); }
        .fs-2 .step-circle { background: linear-gradient(135deg,#2563eb,#3b82f6); }
        .fs-2 .step-line   { background: linear-gradient(to bottom,#3b82f6,#f59e0b); }
        .fs-3 .step-circle { background: linear-gradient(135deg,#f59e0b,#fbbf24); }
        .fs-3 .step-line   { background: linear-gradient(to bottom,#fbbf24,#7c3aed); }
        .fs-4 .step-circle { background: linear-gradient(135deg,#7c3aed,#8b5cf6); }

        /* Info cards grid */
        .info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
        .info-card {
            background: #fff; border-radius: 14px; padding: 22px;
            border: 1.5px solid #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,.04);
            transition: all .2s;
        }
        .info-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.08); }
        .info-card .ic-icon {
            width: 46px; height: 46px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff; margin-bottom: 14px;
        }
        .info-card h6 { font-size: .95rem; font-weight: 700; color: #1e293b; margin: 0 0 6px; }
        .info-card p { font-size: .84rem; color: #64748b; margin: 0; line-height: 1.6; }

        /* Level table */
        .level-table { width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
        .level-table th { background: #1e293b; color: #fff; font-weight: 700; font-size: .85rem; padding: 14px 18px; text-align: left; }
        .level-table td { background: #fff; padding: 14px 18px; font-size: .88rem; color: #475569; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .level-table tr:last-child td { border-bottom: none; }
        .level-table .lv-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 8px; }

        /* Scoring section */
        .scoring-box {
            background: #fff; border-radius: 14px; padding: 24px; border: 1.5px solid #f1f5f9;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }
        .scoring-row {
            display: flex; align-items: center; gap: 14px; padding: 12px 0;
            border-bottom: 1px solid #f8fafc;
        }
        .scoring-row:last-child { border-bottom: none; }
        .scoring-badge {
            min-width: 90px; text-align: center; padding: 6px 14px;
            border-radius: 8px; font-size: .82rem; font-weight: 700;
        }
        .scoring-desc { font-size: .88rem; color: #475569; }

        /* Tips */
        .tip-card {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border: 1.5px solid #bbf7d0; border-radius: 14px;
            padding: 22px 24px;
        }
        .tip-card h6 { font-size: .95rem; font-weight: 700; color: #166534; margin: 0 0 12px; }
        .tip-card ul { margin: 0; padding-left: 18px; }
        .tip-card li { color: #15803d; font-size: .88rem; margin-bottom: 8px; line-height: 1.6; }

        /* Warning */
        .warn-card {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border: 1.5px solid #fde68a; border-radius: 14px;
            padding: 22px 24px;
        }
        .warn-card h6 { font-size: .95rem; font-weight: 700; color: #92400e; margin: 0 0 12px; }
        .warn-card ul { margin: 0; padding-left: 18px; }
        .warn-card li { color: #a16207; font-size: .88rem; margin-bottom: 8px; line-height: 1.6; }

        /* FAQ */
        .faq-item { background: #fff; border-radius: 12px; padding: 20px 22px; border: 1.5px solid #f1f5f9; margin-bottom: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.03); }
        .faq-item .faq-q { font-size: .95rem; font-weight: 700; color: #1e293b; margin: 0 0 8px; display: flex; align-items: flex-start; gap: 10px; }
        .faq-item .faq-q .q-badge { background: #2563eb; color: #fff; font-size: .7rem; font-weight: 800; padding: 2px 8px; border-radius: 6px; flex-shrink: 0; margin-top: 2px; }
        .faq-item .faq-a { font-size: .88rem; color: #64748b; margin: 0; padding-left: 36px; line-height: 1.7; }

        /* CTA */
        .cta-box {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            border-radius: 18px; padding: 40px 30px; text-align: center; color: #fff;
            position: relative; overflow: hidden;
        }
        .cta-box::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='1.5' fill='white' opacity='.08'/%3E%3C/svg%3E");
        }
        .cta-box * { position: relative; }
        .cta-box h4 { font-size: 1.3rem; font-weight: 800; margin: 0 0 8px; }
        .cta-box p { font-size: .95rem; opacity: .88; margin: 0 0 20px; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; color: #0f766e; font-weight: 700; font-size: 1rem;
            padding: 12px 32px; border-radius: 30px; text-decoration: none;
            box-shadow: 0 4px 16px rgba(0,0,0,.15); transition: all .2s;
        }
        .btn-cta:hover { transform: translateY(-2px) scale(1.03); box-shadow: 0 6px 24px rgba(0,0,0,.2); color: #0f766e; text-decoration: none; }

        /* Footer */
        .g-footer { background: #1a1a2e; color: #bdc3c7; padding: 28px 0 14px; font-size: .82rem; margin-top: 50px; }

        @media (max-width: 576px) {
            .g-hero { padding: 36px 0 32px; }
            .g-hero h1 { font-size: 1.4rem; }
            .toc-bar { flex-direction: column; align-items: stretch; margin-top: -20px; }
            .toc-link { justify-content: center; }
            .info-grid { grid-template-columns: 1fr; }
            .step-circle { width: 44px; height: 44px; font-size: .95rem; }
            .flow-step { gap: 14px; }
            .scoring-row { flex-direction: column; align-items: flex-start; gap: 6px; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="g-nav">
    <div class="container inner">
        <a href="{{ route('landing') }}" class="logo">
            <div class="logo-icon"><i class="fas fa-book-open"></i></div>
            <span class="logo-text">V-Quiz</span>
        </a>
        <div class="d-flex align-items-center" style="gap:10px;">
            <a href="{{ route('pretest.index') }}" style="color:#555;font-size:.9rem;text-decoration:none;">
                <i class="fas fa-arrow-left mr-1"></i>กลับหน้าข้อสอบ
            </a>
            <a href="{{ route('login') }}" style="background:#16a085;color:#fff;border-radius:20px;padding:7px 20px;font-weight:600;text-decoration:none;font-size:.9rem;">
                <i class="fas fa-sign-in-alt mr-1"></i>เข้าสู่ระบบ
            </a>
        </div>
    </div>
</nav>

<!-- Hero -->
<div class="g-hero">
    <div class="container">
        <div class="breadcrumb-bar">
            <a href="{{ route('pretest.index') }}"><i class="fas fa-home mr-1"></i>Pre-Test</a>
            <span>/</span>
            <span style="color:#fff;">คู่มือการสอบ</span>
        </div>
        <h1><i class="fas fa-book-reader mr-2"></i>คู่มือการสอบ Pre-Test</h1>
        <p>ขั้นตอนและวิธีใช้งานระบบสอบออนไลน์ V-Quiz อย่างละเอียด</p>
    </div>
</div>

<div class="container py-4">

    <!-- TOC navigation -->
    <div class="toc-bar">
        <a href="#flow" class="toc-link">
            <span class="toc-icon" style="background:linear-gradient(135deg,#16a34a,#22c55e);"><i class="fas fa-route"></i></span>
            ขั้นตอนการสอบ
        </a>
        <a href="#exam-types" class="toc-link">
            <span class="toc-icon" style="background:linear-gradient(135deg,#2563eb,#3b82f6);"><i class="fas fa-th-list"></i></span>
            ประเภทข้อสอบ
        </a>
        <a href="#levels" class="toc-link">
            <span class="toc-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);"><i class="fas fa-layer-group"></i></span>
            ระดับชั้น
        </a>
        <a href="#scoring" class="toc-link">
            <span class="toc-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);"><i class="fas fa-star"></i></span>
            เกณฑ์การให้คะแนน
        </a>
        <a href="#faq" class="toc-link">
            <span class="toc-icon" style="background:linear-gradient(135deg,#dc2626,#ef4444);"><i class="fas fa-question"></i></span>
            คำถามที่พบบ่อย
        </a>
    </div>

    <div style="max-width:820px;margin:0 auto;">

    <!-- ======================== 1. ขั้นตอนการสอบ ======================== -->
    <div class="g-section" id="flow" style="margin-top:40px;">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                <i class="fas fa-route"></i>
            </div>
            <div>
                <h3>ขั้นตอนการสอบ</h3>
                <p>จากเลือกข้อสอบจนถึงดูผลลัพธ์ — 4 ขั้นตอนง่ายๆ</p>
            </div>
        </div>

        <div class="flow-steps">

            <!-- Step 1 -->
            <div class="flow-step fs-1">
                <div class="step-indicator">
                    <div class="step-circle">1</div>
                    <div class="step-line"></div>
                </div>
                <div class="step-body">
                    <h5><i class="fas fa-hand-pointer mr-2" style="color:#16a34a;"></i>เลือกชุดข้อสอบ</h5>
                    <p class="desc">
                        เข้าที่หน้า <strong>Pre-Test ฟรี</strong> เลือกระดับชั้นที่ต้องการ ได้แก่ ธ.ศ.ตรี, ธ.ศ.โท หรือ ธ.ศ.เอก
                        แต่ละชุดจะแสดงจำนวนข้อ เวลาที่กำหนด และเกณฑ์ผ่าน จากนั้นกดปุ่ม <strong>"เริ่มทำเลย"</strong>
                    </p>
                    <div class="step-box">
                        <span class="sb-icon">📋</span>
                        <span class="sb-text">
                            <strong>ตัวอย่าง:</strong> ชุด "Pre-Test พุทธะ ธ.ศ.ตรี" — 5 ข้อ, 30 นาที, เกณฑ์ผ่าน 60%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flow-step fs-2">
                <div class="step-indicator">
                    <div class="step-circle">2</div>
                    <div class="step-line"></div>
                </div>
                <div class="step-body">
                    <h5><i class="fas fa-info-circle mr-2" style="color:#2563eb;"></i>ตรวจสอบรายละเอียด</h5>
                    <p class="desc">
                        ระบบจะแสดงหน้ารายละเอียดของชุดข้อสอบ ได้แก่ จำนวนข้อ เวลาสอบ และเกณฑ์ผ่าน
                        ตรวจสอบให้เรียบร้อย เมื่อพร้อมแล้วกดปุ่ม <strong>"เริ่มทำแบบทดสอบ"</strong>
                    </p>
                    <div class="step-box">
                        <span class="sb-icon">💡</span>
                        <span class="sb-text">
                            Pre-Test ฟรี <strong>ไม่บันทึกผลลงในระบบ</strong> หากต้องการบันทึกผลและรับใบประกาศนียบัตร กรุณาเข้าสู่ระบบก่อนทำข้อสอบ
                        </span>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flow-step fs-3">
                <div class="step-indicator">
                    <div class="step-circle">3</div>
                    <div class="step-line"></div>
                </div>
                <div class="step-body">
                    <h5><i class="fas fa-pencil-alt mr-2" style="color:#f59e0b;"></i>ทำแบบทดสอบ</h5>
                    <p class="desc">
                        ตอบคำถามทีละข้อ — <strong>ข้อปรนัย</strong> เลือกตัวเลือก ก. ข. ค. ง.
                        <strong>ข้ออัตนัย</strong> พิมพ์คำตอบในช่องที่กำหนด
                        เมื่อตอบครบทุกข้อแล้ว กดปุ่ม <strong>"ส่งคำตอบ"</strong>
                    </p>
                    <div class="step-box">
                        <span class="sb-icon">⏱️</span>
                        <span class="sb-text">
                            หากชุดข้อสอบมีเวลาจำกัด ตัวจับเวลาจะแสดงที่มุมบนขวา เมื่อหมดเวลาระบบจะ <strong>ส่งคำตอบอัตโนมัติ</strong>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flow-step fs-4">
                <div class="step-indicator">
                    <div class="step-circle">4</div>
                </div>
                <div class="step-body">
                    <h5><i class="fas fa-chart-bar mr-2" style="color:#7c3aed;"></i>ดูผลลัพธ์และเฉลย</h5>
                    <p class="desc">
                        ระบบแสดงผลทันที — เปอร์เซ็นต์คะแนน ผ่าน/ไม่ผ่าน พร้อม <strong>เฉลยทุกข้อ</strong> เพื่อทบทวน
                        สามารถกด <strong>"ทำอีกครั้ง"</strong> ได้ไม่จำกัดจำนวนครั้ง
                    </p>
                    <div class="step-box">
                        <span class="sb-icon">🏆</span>
                        <span class="sb-text">
                            หน้าผลลัพธ์จะแสดงคะแนนที่ได้ เฉลยแต่ละข้อ และสามารถกลับไปทำใหม่ได้ทันที
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================== 2. ประเภทข้อสอบ ======================== -->
    <div class="g-section" id="exam-types">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#2563eb,#3b82f6);">
                <i class="fas fa-th-list"></i>
            </div>
            <div>
                <h3>ประเภทข้อสอบ</h3>
                <p>รู้จักข้อสอบทั้ง 2 รูปแบบ พร้อมวิธีตอบ</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#2563eb,#3b82f6);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h6>ข้อสอบปรนัย (Multiple Choice)</h6>
                <p>เลือกคำตอบจากตัวเลือก 4 ข้อ (ก. ข. ค. ง.) โดยคลิกที่ตัวเลือกที่ถูกต้อง
                    ระบบตรวจให้คะแนนอัตโนมัติ ตอบถูกได้เต็ม ตอบผิดได้ 0</p>
            </div>
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <h6>ข้อสอบอัตนัย (Essay)</h6>
                <p>พิมพ์คำตอบในช่องข้อความ ระบบเทียบความตรงกันกับเฉลยอัตโนมัติ
                    ตรงกัน 80% ขึ้นไป = ได้เต็ม, 50-79% = ได้ครึ่ง, ต่ำกว่า 50% = ไม่ได้คะแนน</p>
            </div>
        </div>
    </div>

    <!-- ======================== 3. ระดับชั้น ======================== -->
    <div class="g-section" id="levels">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <h3>ระดับชั้นธรรมศึกษา</h3>
                <p>3 ระดับชั้น เรียงจากพื้นฐานไปสูง</p>
            </div>
        </div>

        <table class="level-table">
            <thead>
                <tr>
                    <th style="width:35%;">ระดับชั้น</th>
                    <th>คำอธิบาย</th>
                    <th style="width:18%;">ระดับ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span class="lv-dot" style="background:#16a34a;"></span>
                        <strong>ธ.ศ.ตรี</strong>
                    </td>
                    <td>ธรรมศึกษาชั้นตรี — ระดับพื้นฐาน เหมาะสำหรับผู้เริ่มต้นศึกษาพระพุทธศาสนา</td>
                    <td><span style="background:#dcfce7;color:#166534;padding:4px 12px;border-radius:8px;font-size:.8rem;font-weight:600;">พื้นฐาน</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="lv-dot" style="background:#2563eb;"></span>
                        <strong>ธ.ศ.โท</strong>
                    </td>
                    <td>ธรรมศึกษาชั้นโท — ระดับกลาง เนื้อหาลึกซึ้งขึ้น ต้องมีพื้นฐานจากชั้นตรี</td>
                    <td><span style="background:#dbeafe;color:#1e40af;padding:4px 12px;border-radius:8px;font-size:.8rem;font-weight:600;">กลาง</span></td>
                </tr>
                <tr>
                    <td>
                        <span class="lv-dot" style="background:#7c3aed;"></span>
                        <strong>ธ.ศ.เอก</strong>
                    </td>
                    <td>ธรรมศึกษาชั้นเอก — ระดับสูงสุด เนื้อหาเจาะลึกครบทุกหมวดวิชา</td>
                    <td><span style="background:#ede9fe;color:#5b21b6;padding:4px 12px;border-radius:8px;font-size:.8rem;font-weight:600;">สูง</span></td>
                </tr>
            </tbody>
        </table>

        <div class="info-grid" style="margin-top:20px;">
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#e67e22,#f39c12);">
                    <i class="fas fa-scroll"></i>
                </div>
                <h6>กระทู้ธรรม</h6>
                <p>การเขียนเรียงความแก้กระทู้ธรรม ฝึกการตีความและประยุกต์ใช้หลักธรรม</p>
            </div>
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#16a34a,#22c55e);">
                    <i class="fas fa-om"></i>
                </div>
                <h6>ธรรมวิภาค</h6>
                <p>หมวดธรรมะ — ศึกษาหลักธรรมต่างๆ ที่พระพุทธเจ้าทรงแสดงไว้</p>
            </div>
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#2563eb,#3b82f6);">
                    <i class="fas fa-landmark"></i>
                </div>
                <h6>พุทธประวัติ</h6>
                <p>ประวัติพระพุทธเจ้า ตั้งแต่ประสูติ ตรัสรู้ ปรินิพพาน และพระสาวก</p>
            </div>
            <div class="info-card">
                <div class="ic-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <h6>วินัยบัญญัติ</h6>
                <p>พระวินัย ศีล ข้อปฏิบัติ กฎระเบียบสำหรับพระภิกษุและอุบาสกอุบาสิกา</p>
            </div>
        </div>
    </div>

    <!-- ======================== 4. เกณฑ์การให้คะแนน ======================== -->
    <div class="g-section" id="scoring">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <h3>เกณฑ์การให้คะแนน</h3>
                <p>วิธีตรวจคำตอบและการคำนวณคะแนน</p>
            </div>
        </div>

        <div class="scoring-box">
            <h6 style="font-weight:700;color:#1e293b;margin:0 0 16px;font-size:1rem;">
                <i class="fas fa-check-circle mr-2" style="color:#2563eb;"></i>ข้อปรนัย (Multiple Choice)
            </h6>
            <div class="scoring-row">
                <span class="scoring-badge" style="background:#dcfce7;color:#166534;">ตอบถูก</span>
                <span class="scoring-desc">ได้คะแนนเต็มตามที่กำหนดในแต่ละข้อ</span>
            </div>
            <div class="scoring-row">
                <span class="scoring-badge" style="background:#fee2e2;color:#991b1b;">ตอบผิด</span>
                <span class="scoring-desc">ได้ 0 คะแนน (ไม่หักคะแนน)</span>
            </div>

            <hr style="border-color:#f1f5f9;margin:20px 0;">

            <h6 style="font-weight:700;color:#1e293b;margin:0 0 16px;font-size:1rem;">
                <i class="fas fa-pen-fancy mr-2" style="color:#f59e0b;"></i>ข้ออัตนัย (Essay)
            </h6>
            <div class="scoring-row">
                <span class="scoring-badge" style="background:#dcfce7;color:#166534;">80-100%</span>
                <span class="scoring-desc">ตรงกับเฉลยสูง — ได้ <strong>คะแนนเต็ม</strong></span>
            </div>
            <div class="scoring-row">
                <span class="scoring-badge" style="background:#fef3c7;color:#92400e;">50-79%</span>
                <span class="scoring-desc">ตรงกับเฉลยปานกลาง — ได้ <strong>ครึ่งหนึ่งของคะแนน</strong></span>
            </div>
            <div class="scoring-row">
                <span class="scoring-badge" style="background:#fee2e2;color:#991b1b;">ต่ำกว่า 50%</span>
                <span class="scoring-desc">ตรงกับเฉลยน้อย — ได้ <strong>0 คะแนน</strong></span>
            </div>

            <hr style="border-color:#f1f5f9;margin:20px 0;">

            <h6 style="font-weight:700;color:#1e293b;margin:0 0 16px;font-size:1rem;">
                <i class="fas fa-trophy mr-2" style="color:#7c3aed;"></i>เกณฑ์ผ่าน
            </h6>
            <div class="scoring-row">
                <span class="scoring-badge" style="background:#ede9fe;color:#5b21b6;">ผ่าน / ไม่ผ่าน</span>
                <span class="scoring-desc">คะแนนรวมต้อง <strong>ไม่ต่ำกว่าเกณฑ์ผ่าน</strong> ที่กำหนดในแต่ละชุดข้อสอบ (เช่น 60%)</span>
            </div>
        </div>
    </div>

    <!-- ======================== เคล็ดลับ + ข้อควรระวัง ======================== -->
    <div class="g-section">
        <div class="row" style="gap:0;">
            <div class="col-md-6 mb-3">
                <div class="tip-card h-100">
                    <h6><i class="fas fa-lightbulb mr-2"></i>เคล็ดลับทำข้อสอบ</h6>
                    <ul>
                        <li>อ่านคำถามให้เข้าใจก่อนเลือกตอบ</li>
                        <li>ข้อไหนไม่แน่ใจ ให้ตอบไปก่อน — ไม่หักคะแนน</li>
                        <li>ข้ออัตนัย พิมพ์คำตอบให้ใกล้เคียงกับหลักธรรมที่ถูกต้อง</li>
                        <li>ดูเฉลยหลังส่ง เพื่อทบทวนและจดจำ</li>
                        <li>ทำซ้ำได้ไม่จำกัด ยิ่งทำบ่อยยิ่งจำได้</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="warn-card h-100">
                    <h6><i class="fas fa-exclamation-triangle mr-2"></i>ข้อควรระวัง</h6>
                    <ul>
                        <li>หากมีเวลาจำกัด เมื่อหมดเวลาระบบจะส่งคำตอบทันที</li>
                        <li>อย่าปิดหน้าเว็บขณะทำข้อสอบ ข้อมูลจะหายไป</li>
                        <li>Pre-Test ฟรีไม่บันทึกผลในระบบ หากต้องการบันทึกให้เข้าสู่ระบบก่อน</li>
                        <li>ตรวจสอบว่าอินเทอร์เน็ตใช้งานได้ก่อนเริ่มทำข้อสอบ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================== 5. FAQ ======================== -->
    <div class="g-section" id="faq">
        <div class="section-header">
            <div class="section-icon" style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                <i class="fas fa-question-circle"></i>
            </div>
            <div>
                <h3>คำถามที่พบบ่อย</h3>
                <p>รวมคำตอบสำหรับข้อสงสัยเกี่ยวกับระบบสอบ</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-q">
                <span class="q-badge">Q</span>
                ต้องสมัครสมาชิกก่อนถึงจะทำข้อสอบได้หรือไม่?
            </div>
            <p class="faq-a">ไม่จำเป็น! Pre-Test ฟรีสามารถทำได้ทันทีโดยไม่ต้องสมัครสมาชิกหรือเข้าสู่ระบบ แต่ถ้าต้องการบันทึกผลและรับใบประกาศนียบัตร จะต้องเข้าสู่ระบบก่อน</p>
        </div>

        <div class="faq-item">
            <div class="faq-q">
                <span class="q-badge">Q</span>
                ทำข้อสอบได้กี่ครั้ง?
            </div>
            <p class="faq-a">ทำได้ไม่จำกัดจำนวนครั้ง! สามารถทำซ้ำเพื่อฝึกฝนและทบทวนได้ตลอด</p>
        </div>

        <div class="faq-item">
            <div class="faq-q">
                <span class="q-badge">Q</span>
                ข้ออัตนัยต้องพิมพ์ตรงกับเฉลยเป๊ะๆ หรือไม่?
            </div>
            <p class="faq-a">ไม่จำเป็น ระบบเทียบความตรงกันโดยอัตโนมัติ ตรงกัน 80% ขึ้นไปได้คะแนนเต็ม ตรงกัน 50-79% ได้ครึ่งหนึ่ง เขียนให้ใกล้เคียงกับหลักธรรมที่ถูกต้องก็เพียงพอ</p>
        </div>

        <div class="faq-item">
            <div class="faq-q">
                <span class="q-badge">Q</span>
                หมดเวลาแล้วยังตอบไม่ครบจะเกิดอะไรขึ้น?
            </div>
            <p class="faq-a">ระบบจะส่งคำตอบที่ตอบไปแล้วให้อัตโนมัติ ข้อที่ยังไม่ได้ตอบจะนับเป็น 0 คะแนน</p>
        </div>

        <div class="faq-item">
            <div class="faq-q">
                <span class="q-badge">Q</span>
                ผลคะแนน Pre-Test มีผลต่อผลสอบจริงหรือไม่?
            </div>
            <p class="faq-a">ไม่มีผลใดๆ Pre-Test เป็นการทดสอบฟรีเพื่อฝึกซ้อมเท่านั้น ผลไม่ถูกบันทึกในระบบ ไม่มีผลต่อการสอบจริงหรือการออกใบประกาศ</p>
        </div>

        <div class="faq-item">
            <div class="faq-q">
                <span class="q-badge">Q</span>
                สามารถดูเฉลยได้หลังส่งคำตอบหรือไม่?
            </div>
            <p class="faq-a">ได้! หลังจากส่งคำตอบ ระบบจะแสดงเฉลยทุกข้อพร้อมคำตอบที่ถูกต้อง เพื่อให้ทบทวนและเรียนรู้</p>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-box">
        <h4><i class="fas fa-rocket mr-2"></i>พร้อมแล้ว? เริ่มทำข้อสอบเลย!</h4>
        <p>เลือกชุดข้อสอบที่สนใจแล้วเริ่มทดสอบความรู้ของคุณ</p>
        <a href="{{ route('pretest.index') }}" class="btn-cta">
            <i class="fas fa-play-circle"></i> ไปหน้าข้อสอบ
        </a>
    </div>

    </div>{{-- max-width wrapper --}}
</div>

<!-- Footer -->
<footer class="g-footer">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center mb-3" style="gap:8px;">
            <span style="background:#16a085;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Quiz</span>
            <span style="background:#2980b9;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Kru.One</span>
            <span style="background:#8e44ad;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">ALevel</span>
            <span style="background:#c0392b;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Pali</span>
            <span style="background:#d35400;color:#fff;border-radius:20px;padding:4px 16px;font-weight:700;font-size:.8rem;">Tutor</span>
        </div>
        <div class="text-center mb-2">
            <i class="fas fa-hands-helping mr-1" style="color:#1abc9c;"></i>
            <strong style="color:#ecf0f1;">สนับสนุนโดย</strong> วัดสารอด | สำนักเรียนวัดสารอด เขตราษฎร์บูรณะ กรุงเทพฯ
        </div>
        <div class="text-center mb-3">
            <i class="fas fa-school mr-1" style="color:#1abc9c;"></i>
            สำนักเรียนวัดสารอด เขตราษฏร์บูรณะ กรุงเทพฯ
            &nbsp;|&nbsp; พระปริยัติธรรม &nbsp;|&nbsp; แผนกบาลี &nbsp;|&nbsp; แผนกธรรม
        </div>
        <hr style="border-color:#2c3e50;margin:8px 0 10px;">
        <div class="text-center" style="color:#636e72;font-size:.75rem;">
            &copy; {{ date('Y') }} V-Quiz &mdash; สงวนลิขสิทธิ์
        </div>
    </div>
</footer>

</body>
</html>
