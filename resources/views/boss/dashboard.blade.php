@extends('layouts.boss')
@section('title','Boss – ภาพรวมสำนักเรียน')
@section('page-title','ภาพรวมสำนักเรียน')

@section('content')

{{-- ===== Header ===== --}}
<div class="d-flex align-items-center mb-4">
    <div class="mr-3" style="font-size:2rem;color:#f59e0b;"><i class="fas fa-crown"></i></div>
    <div>
        <h4 class="mb-0 font-weight-bold">Boss Dashboard</h4>
        <small class="text-muted">ภาพรวมทุกสำนักเรียน — ดูได้อย่างเดียว (Read-only)</small>
    </div>
</div>

{{-- ===== สถิติหลัก ===== --}}
<div class="row mb-4">
    <div class="col-6 col-md-3 mb-3">
        <div class="d-flex align-items-center p-3 rounded h-100" style="background:#fef3c7;border-left:4px solid #f59e0b;">
            <div class="mr-3 text-warning" style="font-size:2rem;"><i class="fas fa-school"></i></div>
            <div>
                <div class="text-muted small">สำนักเรียนทั้งหมด</div>
                <div class="h3 font-weight-bold mb-0">{{ $stats['total_classrooms'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="d-flex align-items-center p-3 rounded h-100" style="background:#eef2ff;border-left:4px solid #4f46e5;">
            <div class="mr-3 text-primary" style="font-size:2rem;"><i class="fas fa-user-graduate"></i></div>
            <div>
                <div class="text-muted small">นักเรียนทั้งหมด</div>
                <div class="h3 font-weight-bold mb-0">{{ $stats['total_students'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="d-flex align-items-center p-3 rounded h-100" style="background:#ecfeff;border-left:4px solid #0891b2;">
            <div class="mr-3 text-info" style="font-size:2rem;"><i class="fas fa-chalkboard-teacher"></i></div>
            <div>
                <div class="text-muted small">ครูผู้สอน</div>
                <div class="h3 font-weight-bold mb-0">{{ $stats['total_teachers'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="d-flex align-items-center p-3 rounded h-100" style="background:#e8f5e9;border-left:4px solid #16a34a;">
            <div class="mr-3 text-success" style="font-size:2rem;"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <div class="text-muted small">ผู้สมัครสอบทั้งหมด</div>
                <div class="h3 font-weight-bold mb-0">{{ $regStats['total'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== รอบสอบล่าสุด ===== --}}
@if($latestSession)
<div class="alert mb-4" style="background:#f0fdf4;border:1px solid #86efac;border-left:4px solid #16a34a;">
    <div class="d-flex align-items-center">
        <i class="fas fa-calendar-check text-success mr-2" style="font-size:1.2rem;"></i>
        <div>
            <strong>รอบสอบล่าสุด:</strong>
            ปีการศึกษา {{ $latestSession->academic_year ?? '-' }}
            &nbsp;|&nbsp;
            วันสอบ: {{ $latestSession->exam_date ? $latestSession->exam_date->format('d/m/Y') : '-' }}
            &nbsp;|&nbsp;
            สถานะ:
            <span class="badge badge-{{ $latestSession->status === 'open' ? 'success' : ($latestSession->status === 'closed' ? 'secondary' : 'warning') }}">
                {{ $latestSession->status }}
            </span>
        </div>
    </div>
</div>
@endif

{{-- ===== สำนักเรียนทั้งหมด ===== --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center"
         style="cursor:pointer;" data-toggle="collapse" data-target="#classroomSection" aria-expanded="true">
        <span><i class="fas fa-school mr-1 text-warning"></i> สำนักเรียนทั้งหมด
            <span class="badge ml-1" style="background:#f59e0b;color:#fff;">{{ $classrooms->count() }}</span>
        </span>
        <i class="fas fa-chevron-down toggle-icon"></i>
    </div>
    <div id="classroomSection" class="collapse show">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:.88rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>ชื่อสำนักเรียน</th>
                            <th class="text-center">นักเรียน</th>
                            <th class="text-center">ครู</th>
                            <th class="text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($classrooms as $i => $cl)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>
                                <strong>{{ $cl->name }}</strong>
                                @if($cl->description)
                                    <br><small class="text-muted">{{ $cl->description }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-primary">{{ $cl->student_count }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-info">{{ $cl->teacher_count }}</span>
                            </td>
                            <td class="text-center">
                                @if($cl->is_active)
                                    <span class="badge badge-success">เปิดใช้งาน</span>
                                @else
                                    <span class="badge badge-secondary">ปิด</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีสำนักเรียน</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===== ผู้สมัครสอบตามสำนักเรียน ===== --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center"
         style="cursor:pointer;" data-toggle="collapse" data-target="#regBySchoolSection" aria-expanded="true">
        <span><i class="fas fa-clipboard-list mr-1 text-success"></i> ผู้สมัครสอบแยกตามสำนักเรียน
            <span class="badge badge-success ml-1">{{ $regStats['total'] }}</span>
        </span>
        <i class="fas fa-chevron-down toggle-icon"></i>
    </div>
    <div id="regBySchoolSection" class="collapse show">
        <div class="card-body">
            {{-- สรุปสถานะ --}}
            <div class="row mb-3">
                @foreach([
                    ['สมัครทั้งหมด', $regStats['total'],      '#e8f5e9', '#16a34a'],
                    ['รอตรวจสอบ',    $regStats['registered'],  '#fff3e0', '#f59e0b'],
                    ['ตรวจสอบแล้ว',  $regStats['checked'],     '#e3f2fd', '#0891b2'],
                    ['มีสิทธิ์สอบ',   $regStats['eligible'],    '#ede7f6', '#7c3aed'],
                    ['ขาดสอบ',       $regStats['absent'],      '#fef2f2', '#dc2626'],
                ] as [$label, $val, $bg, $color])
                <div class="col-6 col-md mb-2">
                    <div class="text-center p-2 rounded" style="background:{{ $bg }};border:1px solid {{ $color }}22;">
                        <div class="h4 font-weight-bold mb-0" style="color:{{ $color }};">{{ $val }}</div>
                        <small class="text-muted">{{ $label }}</small>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- สรุปตามชั้น --}}
            <div class="row mb-3">
                @foreach(['tree'=>['ธ.ศ.ตรี','#16a34a','fa-seedling'],'tho'=>['ธ.ศ.โท','#2563eb','fa-book-reader'],'ek'=>['ธ.ศ.เอก','#7c3aed','fa-crown']] as $key => $info)
                <div class="col-md-4 mb-2">
                    <div class="d-flex align-items-center p-2 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;">
                        <div class="mr-2" style="font-size:1.3rem;color:{{ $info[1] }};"><i class="fas {{ $info[2] }}"></i></div>
                        <div>
                            <div style="font-size:.82rem;color:#64748b;">{{ $info[0] }}</div>
                            <div class="font-weight-bold">{{ $regByTier[$key] ?? 0 }} คน</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ตารางแยกตามสำนักเรียน --}}
            @if($regByClassroom->count())
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0" style="font-size:.85rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>สำนักเรียน</th>
                            <th class="text-center">สมัครทั้งหมด</th>
                            <th class="text-center">มีสิทธิ์สอบ</th>
                            <th class="text-center">ขาดสอบ</th>
                            <th style="width:160px;">สัดส่วน</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($regByClassroom as $row)
                        @php $pct = $row->total > 0 ? round($row->eligible / $row->total * 100) : 0; @endphp
                        <tr>
                            <td>{{ $row->office_name ?: '(ไม่ระบุ)' }}</td>
                            <td class="text-center font-weight-bold">{{ $row->total }}</td>
                            <td class="text-center text-success font-weight-bold">{{ $row->eligible }}</td>
                            <td class="text-center text-danger">{{ $row->absent }}</td>
                            <td>
                                <div class="progress" style="height:14px;border-radius:4px;">
                                    <div class="progress-bar bg-success" style="width:{{ $pct }}%;font-size:.72rem;">
                                        {{ $pct }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted text-center py-3 mb-0">ยังไม่มีข้อมูลผู้สมัครสอบ</p>
            @endif
        </div>
    </div>
</div>

{{-- ===== ผู้สมัครล่าสุด ===== --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center"
         style="cursor:pointer;" data-toggle="collapse" data-target="#recentRegSection" aria-expanded="true">
        <span><i class="fas fa-history mr-1 text-muted"></i> รายชื่อผู้สมัครล่าสุด</span>
        <div>
            <a href="{{ route('boss.registrations.index') }}" class="btn btn-sm btn-outline-secondary mr-2" onclick="event.stopPropagation();">ดูทั้งหมด</a>
            <i class="fas fa-chevron-down toggle-icon"></i>
        </div>
    </div>
    <div id="recentRegSection" class="collapse show">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0" style="font-size:.85rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>รหัสอ้างอิง</th>
                            <th>ชื่อ-สกุล</th>
                            <th>สำนักเรียน</th>
                            <th>ชั้น</th>
                            <th>สถานะ</th>
                            <th>วันที่สมัคร</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($recentRegs as $reg)
                        @php
                            $statusColors = ['registered'=>'warning','checked'=>'info','eligible'=>'success','absent'=>'secondary'];
                            $statusLabels = \App\ExamRegistration::STATUSES;
                            $tierColors   = ['tree'=>'#16a34a','tho'=>'#2563eb','ek'=>'#7c3aed'];
                            $tierLabels   = \App\ExamRegistration::TIER_LABELS;
                        @endphp
                        <tr>
                            <td><code>{{ $reg->reference_code }}</code></td>
                            <td>{{ $reg->title }} {{ $reg->first_name }} {{ $reg->last_name }}</td>
                            <td>{{ $reg->office_name ?: '-' }}</td>
                            <td>
                                @if($reg->exam_tier)
                                    <span class="badge badge-pill" style="background:{{ $tierColors[$reg->exam_tier] ?? '#6b7280' }};color:#fff;">
                                        {{ $tierLabels[$reg->exam_tier] ?? $reg->exam_tier }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge badge-{{ $statusColors[$reg->status] ?? 'secondary' }}">{{ $statusLabels[$reg->status] ?? $reg->status }}</span></td>
                            <td>{{ $reg->created_at ? $reg->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('boss.registrations.show', $reg) }}" class="btn btn-sm btn-info" title="ดูข้อมูล"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('boss.registrations.edit', $reg) }}" class="btn btn-sm btn-warning" title="แก้ไข"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">ยังไม่มีผู้สมัคร</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$('.card [data-toggle="collapse"]').each(function() {
    var target = $($(this).data('target'));
    var icon = $(this).find('.toggle-icon');
    target.on('show.bs.collapse', function() {
        icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
    }).on('hide.bs.collapse', function() {
        icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
    });
});
</script>
@endsection
