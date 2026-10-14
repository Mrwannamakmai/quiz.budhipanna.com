<style>
    footer {
        background: #1a1a2e;
        color: rgba(255, 255, 255, .5);
    }

    .ft-stats-strip {
        background: rgba(255, 255, 255, .04);
        border-top: 1px solid rgba(255, 255, 255, .07);
        border-bottom: 1px solid rgba(255, 255, 255, .07);
        padding: 22px 0;
    }

    .ft-stats-grid {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0;
    }

    .ft-stat {
        text-align: center;
        padding: 8px 20px;
        border-right: 1px solid rgba(255, 255, 255, .06);
    }

    .ft-stat:last-child {
        border-right: none;
    }

    .fsv {
        display: block;
        font-size: 1.4rem;
        font-weight: 800;
    }

    .fsl {
        font-size: .7rem;
        color: rgba(255, 255, 255, .4);
    }

    .ft-body {
        padding: 40px 0 24px;
    }

    footer .footer-brand {
        color: #fff;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    footer .footer-desc {
        font-size: .85rem;
        line-height: 1.7;
        max-width: 280px;
        color: rgba(255, 255, 255, .5);
    }

    footer .footer-link {
        color: rgba(255, 255, 255, .5);
        text-decoration: none;
        display: block;
        padding: 4px 0;
        font-size: .87rem;
        transition: color .15s;
    }

    footer .footer-link:hover {
        color: var(--primary-light);
        text-decoration: none;
    }

    footer .footer-col-title {
        color: rgba(255, 255, 255, .9);
        font-weight: 700;
        margin-bottom: 14px;
        font-size: .88rem;
        letter-spacing: .3px;
    }

    footer .ft-bottom {
        padding: 16px 0;
        border-top: 1px solid rgba(255, 255, 255, .07);
        font-size: .78rem;
        color: rgba(255, 255, 255, .3);
        text-align: center;
    }
</style>

<footer>
    <div class="ft-stats-strip">
        <div class="container">
            <div class="ft-stats-grid">
                <div class="ft-stat"><span class="fsv"
                        style="color:#1abc9c;">{{ number_format($visitorStats['total'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-eye mr-1"></i>ผู้เข้าชมรวม</span></div>
                <div class="ft-stat"><span class="fsv"
                        style="color:#3b82f6;">{{ number_format($visitorStats['unique'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-fingerprint mr-1"></i>IP ไม่ซ้ำ</span></div>
                <div class="ft-stat"><span class="fsv"
                        style="color:#f59e0b;">{{ number_format($visitorStats['returning'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-redo mr-1"></i>IP กลับมาซ้ำ</span></div>
                <div class="ft-stat"><span class="fsv"
                        style="color:#a78bfa;">{{ number_format($visitorStats['today'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-sun mr-1"></i>เข้าชมวันนี้</span></div>
                <div class="ft-stat"><span class="fsv"
                        style="color:#34d399;">{{ number_format($siteStats['students'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-user-graduate mr-1"></i>นักเรียน</span></div>
                <div class="ft-stat"><span class="fsv"
                        style="color:#60a5fa;">{{ number_format($siteStats['exam_sets'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-layer-group mr-1"></i>ชุดข้อสอบ</span></div>
                <div class="ft-stat"><span class="fsv"
                        style="color:#fb923c;">{{ number_format($siteStats['attempts'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-pencil-alt mr-1"></i>ผู้เข้าสอบ</span></div>
                <div class="ft-stat"><span class="fsv"
                        style="color:#4ade80;">{{ number_format($siteStats['passed'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-check-circle mr-1"></i>สอบผ่าน</span></div>
                <div class="ft-stat"><span class="fsv"
                        style="color:#f9a8d4;">{{ number_format($siteStats['certificates'] ?? 0) }}</span><span
                        class="fsl"><i class="fas fa-certificate mr-1"></i>ใบประกาศ</span></div>
            </div>
        </div>
    </div>

    <div class="ft-body">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="footer-brand"><i class="fas fa-book-open mr-2"
                            style="color:#1abc9c;"></i>ศูนย์ข้อสอบออนไลน์</div>
                    <p class="footer-desc">ระบบข้อสอบออนไลน์ สำหรับนักเรียนและครูผู้สอน<br>ใช้งานง่าย
                        เข้าถึงได้ทุกที่ทุกเวลา</p>
                    <div class="mt-3" style="font-size:.78rem;color:rgba(255,255,255,.35);">
                        <i class="fas fa-school mr-1" style="color:#1abc9c;"></i>สำนักเรียนวัดสารอด กรุงเทพฯ
                    </div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.25);margin-top:3px;padding-left:14px;">
                        พระปริยัติธรรม &nbsp;·&nbsp; แผนกบาลี &nbsp;·&nbsp; แผนกธรรม</div>
                </div>
                <div class="col-6 col-md-2 mb-4">
                    <div class="footer-col-title">เมนู</div>
                    <a href="{{ route('landing') }}" class="footer-link">หน้าแรก</a>
                    <a href="{{ route('knowhow.index') }}" class="footer-link"
                        style="color:var(--primary-light);">คลังความรู้</a>
                    <a href="{{ route('pretest.index') }}" class="footer-link">Pre-Test ฟรี</a>
                    <a href="{{ route('login') }}" class="footer-link">เข้าสู่ระบบ</a>
                    <a href="{{ route('register') }}" class="footer-link">สมัครสมาชิก</a>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <div class="footer-col-title">ฟีเจอร์</div>
                    <a href="{{ route('knowhow.index') }}" class="footer-link">คลังความรู้</a>
                    <a href="#" class="footer-link">ระบบข้อสอบ</a>
                    <a href="#" class="footer-link">ใบประกาศนียบัตร</a>
                    <a href="{{ route('certificates.verify', 'example') }}" class="footer-link">ตรวจสอบใบประกาศ</a>
                    <a href="{{ route('pretest.stats') }}" class="footer-link">สถิติการสอบ</a>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="footer-col-title">เริ่มต้น</div>
                    <a href="{{ route('pretest.index') }}" class="footer-link"><i class="fas fa-play-circle mr-1"
                            style="color:#1abc9c;font-size:.7rem;"></i>ทดลองฟรี ไม่ต้องสมัคร</a>
                    <a href="{{ route('register') }}" class="footer-link"><i class="fas fa-user-plus mr-1"
                            style="color:#3b82f6;font-size:.7rem;"></i>สมัครนักเรียน</a>
                    <a href="{{ route('login') }}" class="footer-link"><i class="fas fa-chalkboard-teacher mr-1"
                            style="color:#f59e0b;font-size:.7rem;"></i>เข้าสู่ระบบครู</a>
                    <div class="mt-3 d-inline-flex align-items-center"
                        style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:20px;padding:5px 12px;font-size:.75rem;color:rgba(255,255,255,.5);">
                        <i class="fas fa-file-alt mr-2" style="color:#1abc9c;"></i>Pre-Test
                        {{ number_format($siteStats['pretest'] ?? 0) }} ชุด พร้อมสอบ
                    </div>
                </div>
            </div>
            <div style="border-top:1px solid rgba(255,255,255,.07);padding-top:20px;margin-top:4px;">
                <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap:12px;">
                    <div style="font-size:.78rem;color:rgba(255,255,255,.35);"><i class="fas fa-hands-helping mr-1"
                            style="color:#1abc9c;"></i><strong
                            style="color:rgba(255,255,255,.55);">สนับสนุนโดย</strong> วัดสารอด | สำนักเรียนวัดสารอด เขตราษฎร์บูรณะ กรุงเทพฯ</div>
                    <div class="d-flex flex-wrap" style="gap:6px;">
                        <span
                            style="background:rgba(22,160,133,.25);color:#1abc9c;border:1px solid rgba(22,160,133,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">Quiz</span>
                        <span
                            style="background:rgba(41,128,185,.25);color:#3b82f6;border:1px solid rgba(41,128,185,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">Kru.One</span>
                        <span
                            style="background:rgba(142,68,173,.25);color:#a78bfa;border:1px solid rgba(142,68,173,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">ALevel</span>
                        <span
                            style="background:rgba(192,57,43,.25);color:#f87171;border:1px solid rgba(192,57,43,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">Pali</span>
                        <span
                            style="background:rgba(211,84,0,.25);color:#fb923c;border:1px solid rgba(211,84,0,.3);border-radius:20px;padding:3px 12px;font-weight:700;font-size:.72rem;">Tutor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ft-bottom">&copy; {{ date('Y') }} ระบบข้อสอบธรรมศึกษา &mdash; สงวนลิขสิทธิ์</div>
</footer>
