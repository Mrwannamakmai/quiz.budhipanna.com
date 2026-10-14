@extends('layouts.teacher')
@section('title', 'ติดตามผู้สมัครสอบ')
@section('page-title', 'ติดตามรายชื่อผู้สมัครสอบธรรมศึกษา')

@section('content')
<style>
.er-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:18px}
.sort-btn{border:1px solid #d1d5db;background:#fff;color:#374151;padding:5px 14px;border-radius:6px;font-size:.85rem;cursor:pointer;transition:all .15s}
.sort-btn:hover{background:#f3f4f6;border-color:#9ca3af}
.sort-btn.active{background:#3b82f6;color:#fff;border-color:#3b82f6}
.follow-badge{font-size:.75rem;padding:4px 10px;border-radius:12px;font-weight:600}
.follow-badge-pending{background:#fef3c7;color:#92400e}
.follow-badge-progress{background:#dbeafe;color:#1e40af}
.stat-box{display:inline-block;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:8px 16px;margin-right:8px;margin-bottom:8px;font-size:.85rem}
.stat-box strong{color:#1e293b}
</style>

<a href="{{ route('teacher.exam_registration.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left mr-1"></i>กลับ</a>

<div class="er-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
        <div>
            <h5 class="mb-1" style="font-weight:700;color:#1e293b">รายชื่อที่รอการติดตาม</h5>
            <div class="text-muted small">ทั้งหมด {{ $registrations->total() }} คน</div>
        </div>
    </div>

    {{-- ค้นหา --}}
    <form method="GET" class="mb-3">
        <input type="hidden" name="sort" value="{{ $currentSort }}">
        @if (request('follow_up_status'))
            <input type="hidden" name="follow_up_status" value="{{ request('follow_up_status') }}">
        @endif
        <div class="input-group" style="max-width:500px">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="ค้นหาชื่อ / โทรศัพท์ / LINE ID / เลขบัตร / เลขอ้างอิง" value="{{ request('q') }}">
            <div class="input-group-append">
                <button class="btn btn-sm btn-primary" type="submit"><i class="fas fa-search mr-1"></i>ค้นหา</button>
                @if (request('q'))
                    <a href="{{ route('teacher.exam_registration.followup.index', ['sort' => $currentSort, 'follow_up_status' => request('follow_up_status')]) }}" class="btn btn-sm btn-outline-secondary">ล้าง</a>
                @endif
            </div>
        </div>
    </form>

    {{-- กรองสถานะ + เรียงลำดับ --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3" style="gap:12px">
        <div>
            <span class="text-muted small mr-2">กรองสถานะ:</span>
            <a href="{{ route('teacher.exam_registration.followup.index', array_merge(request()->only(['q', 'sort']), [])) }}"
                class="sort-btn {{ !request('follow_up_status') ? 'active' : '' }}">ทั้งหมด</a>
            @foreach ($followUpStatuses as $key => $label)
                @if (in_array($key, ['FOLLOW_PENDING', 'FOLLOW_IN_PROGRESS']))
                    <a href="{{ route('teacher.exam_registration.followup.index', array_merge(request()->only(['q', 'sort']), ['follow_up_status' => $key])) }}"
                        class="sort-btn {{ request('follow_up_status') === $key ? 'active' : '' }}">{{ $label }}</a>
                @endif
            @endforeach
        </div>
        <div>
            <span class="text-muted small mr-2">เรียงตาม:</span>
            <a href="{{ route('teacher.exam_registration.followup.index', array_merge(request()->only(['q', 'follow_up_status']), ['sort' => 'latest'])) }}"
                class="sort-btn {{ $currentSort === 'latest' ? 'active' : '' }}"><i class="fas fa-clock mr-1"></i>ล่าสุด</a>
            <a href="{{ route('teacher.exam_registration.followup.index', array_merge(request()->only(['q', 'follow_up_status']), ['sort' => 'class'])) }}"
                class="sort-btn {{ $currentSort === 'class' ? 'active' : '' }}"><i class="fas fa-layer-group mr-1"></i>ชั้น</a>
            <a href="{{ route('teacher.exam_registration.followup.index', array_merge(request()->only(['q', 'follow_up_status']), ['sort' => 'name'])) }}"
                class="sort-btn {{ $currentSort === 'name' ? 'active' : '' }}"><i class="fas fa-sort-alpha-down mr-1"></i>ชื่อ ก-ฮ</a>
        </div>
    </div>

    {{-- ตาราง --}}
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead>
                <tr>
                    <th style="width:30px">#</th>
                    <th>ชื่อ-สกุล</th>
                    <th>ชั้นที่สมัคร</th>
                    <th>โทรศัพท์</th>
                    <th>LINE ID</th>
                    <th>สถานะติดตาม</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $r)
                    <tr>
                        <td class="text-muted">{{ $registrations->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="font-weight-bold">{{ $r->fullName() }}</div>
                            @if ($r->reference_code)
                                <small class="text-muted">{{ $r->reference_code }}</small>
                            @endif
                        </td>
                        <td>{{ $r->target_level ?: '-' }} {{ $r->target_range ? '/ ' . $r->target_range : '' }}</td>
                        <td>{{ $r->phone ?: '-' }}</td>
                        <td>{{ $r->line_id ?: '-' }}</td>
                        <td>
                            @if ($r->follow_up_status === 'FOLLOW_IN_PROGRESS')
                                <span class="follow-badge follow-badge-progress">{{ $r->followUpStatusLabel() }}</span>
                            @else
                                <span class="follow-badge follow-badge-pending">{{ $r->followUpStatusLabel() }}</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('teacher.exam_registration.followup.edit', $r->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-phone-alt mr-1"></i>บันทึกการติดตาม</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">
                        @if (request('q'))
                            ไม่พบผู้สมัครที่ตรงกับ "{{ request('q') }}"
                        @else
                            ไม่มีรายการที่ต้องติดตาม
                        @endif
                    </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $registrations->links() }}
</div>
@endsection
