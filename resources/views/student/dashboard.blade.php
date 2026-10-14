@extends('layouts.student')
@section('title','หน้าหลัก')

@section('content')
<div class="container-fluid py-4">
    <h4 class="mb-4">สวัสดี, <strong>{{ auth()->user()->name }}</strong></h4>

    {{-- Daily Challenge Banner --}}
    @php $user = auth()->user(); @endphp
    <div class="mb-4">
        @if($dailyAttempt)
        {{-- ทำแล้ววันนี้ --}}
        <div style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);border:2px solid #6ee7b7;border-radius:14px;padding:16px 20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <div style="font-size:2rem;line-height:1;">🔥</div>
            <div style="flex:1;">
                <div style="font-weight:800;color:#065f46;font-size:1rem;">โจทย์ประจำวัน — ทำแล้ว! ✓</div>
                <div style="font-size:.82rem;color:#047857;margin-top:2px;">
                    คะแนน {{ $dailyAttempt->score }}/{{ $dailyAttempt->total }}
                    ({{ $dailyAttempt->total > 0 ? round($dailyAttempt->score/$dailyAttempt->total*100) : 0 }}%)
                    &nbsp;·&nbsp; 🔥 Streak {{ $user->daily_streak ?? 0 }} วัน
                </div>
            </div>
            <a href="{{ route('daily.result') }}"
               style="background:#065f46;color:#fff;border-radius:20px;padding:8px 20px;font-weight:700;font-size:.85rem;text-decoration:none;white-space:nowrap;">
                <i class="fas fa-chart-bar mr-1"></i>ดูเฉลย
            </a>
        </div>
        @else
        {{-- ยังไม่ได้ทำ --}}
        <div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:2px solid #fbbf24;border-radius:14px;padding:16px 20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <div style="font-size:2rem;line-height:1;">🔥</div>
            <div style="flex:1;">
                <div style="font-weight:800;color:#92400e;font-size:1rem;">โจทย์ประจำวัน — รอคุณอยู่!</div>
                <div style="font-size:.82rem;color:#a16207;margin-top:2px;">
                    ข้อสอบ 10 ข้อ · ใช้เวลา ~5 นาที
                    @if(($user->daily_streak ?? 0) > 0)
                    &nbsp;·&nbsp; 🔥 Streak {{ $user->daily_streak }} วัน — อย่าให้ขาด!
                    @endif
                </div>
            </div>
            <a href="{{ route('daily.play') }}"
               style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border-radius:20px;padding:8px 20px;font-weight:700;font-size:.85rem;text-decoration:none;white-space:nowrap;box-shadow:0 3px 10px rgba(217,119,6,.3);">
                <i class="fas fa-play mr-1"></i>เริ่มเลย!
            </a>
        </div>
        @endif
    </div>

    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="display-4 font-weight-bold text-primary">{{ $totalExams }}</div>
                    <small class="text-muted">ชุดข้อสอบทั้งหมด</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="display-4 font-weight-bold text-success">{{ $passedCount }}</div>
                    <small class="text-muted">ผ่านแล้ว</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="display-4 font-weight-bold text-warning">{{ $attempts }}</div>
                    <small class="text-muted">ครั้งที่สอบ</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="display-4 font-weight-bold text-info">{{ $certificates }}</div>
                    <small class="text-muted">ใบประกาศ</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>ชุดข้อสอบล่าสุด</span>
                    <a href="{{ route('student.exams.index') }}" class="btn btn-sm btn-outline-primary">ดูทั้งหมด</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentExamSets as $es)
                    <a href="{{ route('student.exams.show', $es) }}"
                       class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $es->name }}</strong>
                            <span class="badge badge-{{ $es->is_active ? 'success' : 'secondary' }}">
                                {{ $es->is_active ? 'เปิด' : 'ปิด' }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $es->subject->name ?? '' }} | เวลา: {{ $es->time_limit ? $es->time_limit.' นาที' : 'ไม่จำกัด' }}</small>
                    </a>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">ยังไม่มีชุดข้อสอบ</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-5 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>ใบประกาศล่าสุด</span>
                    <a href="{{ route('student.certificates.index') }}" class="btn btn-sm btn-outline-info">ดูทั้งหมด</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentCertificates as $cert)
                    <a href="{{ route('student.certificates.show', $cert) }}"
                       class="list-group-item list-group-item-action">
                        <div><strong>{{ $cert->examSet->name ?? '-' }}</strong></div>
                        <small class="text-muted"><code>{{ $cert->certificate_no }}</code> | {{ $cert->issued_at ? $cert->issued_at->format('d/m/Y') : '' }}</small>
                    </a>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">ยังไม่มีใบประกาศ</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
