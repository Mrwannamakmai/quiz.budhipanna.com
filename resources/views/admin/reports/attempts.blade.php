@extends('layouts.admin')
@section('title', 'รายงานผลการสอบ')
@section('page-title', 'รายงานผลการสอบ')

@section('content')
<style>
/* ─── Attempts Report Modern Styling ─── */
:root {
    --rpt-primary: #2563eb;
    --rpt-primary-soft: #eff6ff;
    --rpt-success: #10b981;
    --rpt-success-soft: #ecfdf5;
    --rpt-danger: #ef4444;
    --rpt-danger-soft: #fef2f2;
    --rpt-warning: #f59e0b;
    --rpt-slate-50: #f8fafc;
    --rpt-slate-100: #f1f5f9;
    --rpt-slate-200: #e2e8f0;
    --rpt-slate-300: #cbd5e1;
    --rpt-slate-600: #475569;
    --rpt-slate-700: #334155;
    --rpt-slate-800: #1e293b;
    --rpt-card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.03);
}

.rpt-container {
    max-width: 100%;
}

/* Top Banner Card */
.rpt-top {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--rpt-slate-200);
    box-shadow: var(--rpt-card-shadow);
    padding: 20px 24px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.rpt-top-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.rpt-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: var(--rpt-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

.rpt-top h5 {
    margin: 0;
    font-weight: 700;
    color: var(--rpt-slate-800);
    font-size: 1.2rem;
    line-height: 1.3;
}

.rpt-top p {
    margin: 3px 0 0 0;
    font-size: 0.875rem;
    color: #64748b;
}

/* Filter Card */
.rpt-filter {
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid var(--rpt-slate-200);
    box-shadow: var(--rpt-card-shadow);
    padding: 18px 22px;
    margin-bottom: 20px;
}

.rpt-select {
    border-radius: 8px;
    border: 1.5px solid var(--rpt-slate-300);
    font-size: 0.9rem;
    padding: 8px 14px;
    height: calc(1.5em + 1.1rem + 2px);
    transition: all 0.2s;
    background-color: #ffffff;
    color: var(--rpt-slate-800);
    min-width: 180px;
}

.rpt-select:focus {
    border-color: var(--rpt-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

.btn-rpt-filter {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 8px;
    padding: 9px 20px;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    transition: all 0.2s;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: calc(1.5em + 1.1rem + 2px);
}

.btn-rpt-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
}

.btn-rpt-clear {
    color: #ef4444;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s;
}

.btn-rpt-clear:hover {
    background: #fef2f2;
    color: #dc2626;
    text-decoration: none;
}

/* Main Table Card */
.rpt-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid var(--rpt-slate-200);
    box-shadow: var(--rpt-card-shadow);
    overflow: hidden;
    margin-bottom: 24px;
}

.rpt-table-wrapper {
    overflow-x: auto;
}

.rpt-table {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.rpt-table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.825rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    padding: 14px 18px;
    border-bottom: 2px solid var(--rpt-slate-200);
    border-top: none;
    white-space: nowrap;
}

.rpt-table tbody td {
    padding: 14px 18px;
    vertical-align: middle;
    font-size: 0.925rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease;
}

.rpt-table tbody tr:hover td {
    background-color: #f8fafc;
}

.rpt-table tbody tr:last-child td {
    border-bottom: none;
}

/* Badges & Chips */
.rpt-student-badge {
    display: flex;
    align-items: center;
    gap: 10px;
}

.rpt-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: var(--rpt-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.rpt-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.825rem;
    font-weight: 700;
}

.rpt-pill.passed {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.rpt-pill.failed {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.rpt-score-chip {
    font-weight: 700;
    font-size: 0.95rem;
    color: var(--rpt-slate-800);
}

.rpt-percent-chip {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
}

.rpt-percent-high {
    background: #ecfdf5;
    color: #059669;
}

.rpt-percent-low {
    background: #fef2f2;
    color: #dc2626;
}

.rpt-card-footer {
    padding: 16px 20px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.rpt-empty {
    text-align: center;
    padding: 50px 20px;
    color: #64748b;
}

.rpt-empty i {
    font-size: 44px;
    color: #cbd5e1;
    margin-bottom: 12px;
    display: block;
}
</style>

<div class="rpt-container">
    {{-- Header Banner --}}
    <div class="rpt-top">
        <div class="rpt-top-left">
            <div class="rpt-icon-box">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div>
                <h5>รายงานผลการสอบของนักเรียน</h5>
                <p>ตรวจสอบประวัติ คะแนนการสอบ และสถานะการสอบผ่านของนักเรียนรายบุคคล</p>
            </div>
        </div>
        <div>
            <span class="badge badge-light border" style="font-size: 0.85rem; padding: 7px 14px; border-radius: 20px; color: #475569;">
                <i class="fas fa-list-ol text-primary mr-1"></i> รายการทั้งหมด: <strong>{{ $attempts->total() }}</strong> ครั้ง
            </span>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="rpt-filter">
        <form method="GET" class="d-flex align-items-center flex-wrap" style="gap: 12px; margin: 0;">
            <div class="d-flex align-items-center" style="gap: 8px;">
                <label class="m-0 font-weight-bold text-muted" style="font-size: 0.85rem; white-space: nowrap;">
                    <i class="fas fa-school text-primary mr-1"></i> ห้องเรียน:
                </label>
                <select name="classroom_id" class="rpt-select">
                    <option value="">ทุกห้องเรียน</option>
                    @foreach($classrooms as $cl)
                        <option value="{{ $cl->id }}" {{ request('classroom_id') == $cl->id ? 'selected' : '' }}>
                            {{ $cl->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex align-items-center" style="gap: 8px;">
                <label class="m-0 font-weight-bold text-muted" style="font-size: 0.85rem; white-space: nowrap;">
                    <i class="fas fa-check-circle text-primary mr-1"></i> ผลการสอบ:
                </label>
                <select name="passed" class="rpt-select">
                    <option value="">ทุกผลการสอบ</option>
                    <option value="1" {{ request('passed') === '1' ? 'selected' : '' }}>✅ ผ่าน</option>
                    <option value="0" {{ request('passed') === '0' ? 'selected' : '' }}>❌ ไม่ผ่าน</option>
                </select>
            </div>

            <div class="d-flex align-items-center" style="gap: 8px;">
                <button type="submit" class="btn-rpt-filter">
                    <i class="fas fa-filter"></i> กรองข้อมูล
                </button>
                @if(request('classroom_id') || request('passed') !== null && request('passed') !== '')
                    <a href="{{ route('admin.reports.attempts') }}" class="btn-rpt-clear">
                        <i class="fas fa-times-circle"></i> ล้างตัวกรอง
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Main Table --}}
    <div class="rpt-card">
        <div class="rpt-table-wrapper">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th style="min-width: 200px;">นักเรียน</th>
                        <th>ห้องเรียน</th>
                        <th style="min-width: 220px;">ชุดข้อสอบ</th>
                        <th class="text-center">คะแนนที่ได้</th>
                        <th class="text-center">ร้อยละ (%)</th>
                        <th class="text-center">ผลการสอบ</th>
                        <th class="text-right">วันที่ส่งข้อสอบ</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($attempts as $a)
                    @php
                        $pct = $a->total_points > 0 ? round(($a->score / $a->total_points) * 100, 1) : 0;
                        $studentInitial = mb_substr(optional($a->user)->name ?: 'น', 0, 1, 'UTF-8');
                    @endphp
                    <tr>
                        <td>
                            <div class="rpt-student-badge">
                                <div class="rpt-avatar">
                                    {{ $studentInitial }}
                                </div>
                                <div>
                                    <div class="font-weight-bold" style="color: var(--rpt-slate-800);">
                                        {{ $a->user->name ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card-alt mr-1"></i> {{ $a->user->student_code ?? '#' . ($a->user->id ?? '-') }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-light border" style="padding: 5px 10px; border-radius: 6px; font-size: 0.85rem; color: #475569;">
                                {{ $a->user->classroom->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="font-weight-bold text-dark">
                                {{ $a->examSet->name ?? '-' }}
                            </div>
                            @if(optional($a->examSet)->subject)
                                <small class="text-muted">
                                    <i class="fas fa-book mr-1"></i> {{ $a->examSet->subject->name }}
                                </small>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="rpt-score-chip">{{ $a->score }}</span>
                            <span class="text-muted font-weight-normal">/ {{ $a->total_points }}</span>
                        </td>
                        <td class="text-center">
                            <span class="rpt-percent-chip {{ $pct >= 60 ? 'rpt-percent-high' : 'rpt-percent-low' }}">
                                {{ $pct }}%
                            </span>
                        </td>
                        <td class="text-center">
                            @if($a->passed)
                                <span class="rpt-pill passed">
                                    <i class="fas fa-check-circle"></i> ผ่าน
                                </span>
                            @else
                                <span class="rpt-pill failed">
                                    <i class="fas fa-times-circle"></i> ไม่ผ่าน
                                </span>
                            @endif
                        </td>
                        <td class="text-right text-muted text-nowrap" style="font-size: 0.875rem;">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $a->submitted_at ? $a->submitted_at->format('d/m/Y H:i') . ' น.' : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="rpt-empty">
                                <i class="fas fa-inbox"></i>
                                <strong style="font-size: 1.05rem; color: #334155;">ไม่พบข้อมูลผลการสอบ</strong>
                                <div class="text-muted small mt-1">ยังไม่มีรายการที่ตรงกับเงื่อนไขการค้นหาในขณะนี้</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($attempts->hasPages())
            <div class="rpt-card-footer">
                <div class="text-muted small">
                    แสดงหน้า <strong>{{ $attempts->currentPage() }}</strong> จาก <strong>{{ $attempts->lastPage() }}</strong>
                </div>
                <div>
                    {{ $attempts->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
