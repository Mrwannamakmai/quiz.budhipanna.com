@extends('layouts.teacher')
@section('title', 'รายชื่อผู้สมัครสอบ')
@section('page-title', 'รายชื่อผู้สมัครสอบ')

@section('content')
<style>
.er-card { background:#fff; border-radius:16px; padding:24px; box-shadow:0 4px 20px -2px rgba(0,0,0,0.05), 0 2px 6px -1px rgba(0,0,0,0.03); margin-bottom:24px; border: 1px solid rgba(226, 232, 240, 0.8); }
.er-head { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px; border-bottom:1px solid #f1f5f9; padding-bottom:16px; }
.er-head h5 { font-size:1.15rem; font-weight:700; margin:0; color:#1e293b; }
.er-muted { color:#64748b; font-size:.875rem; }
.er-filter-box { background:#f8fafc; border-radius:12px; padding:18px; margin-bottom:22px; border:1px solid #e2e8f0; }
.table-er { border-collapse: separate; border-spacing: 0; width: 100%; }
.table-er thead th { background: #f8fafc; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.825rem; padding: 14px 16px; border-bottom: 2px solid #e2e8f0; border-top: none; }
.table-er tbody td { padding: 14px 16px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; color: #334155; }
.table-er tbody tr:hover { background: #f8fafc; }
.badge-er-info { background-color: #eff6ff; color: #1d4ed8; padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
.badge-er-secondary { background-color: #f1f5f9; color: #64748b; padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
.btn-er-export { border-radius: 8px; font-weight: 600; padding: 7px 16px; transition: all 0.2s; }
.btn-er-export:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
</style>

<div class="er-card">
    <div class="er-head">
        <div>
            <h5><i class="fas fa-users text-primary mr-2"></i>รายชื่อผู้สมัครสอบที่ดูแล</h5>
            <div class="er-muted mt-1">รายชื่อนักเรียนที่คุณลงทะเบียนหรืออยู่ในความดูแลของคุณ</div>
        </div>
        <div>
            @if($session)
                <a href="{{ route('exam_registration.export', array_merge(request()->all(), ['format' => 'excel', 'session_id' => $session->id])) }}" class="btn btn-success btn-sm btn-er-export mr-2">
                    <i class="fas fa-file-excel mr-1"></i> ส่งออก Excel
                </a>
                <a href="{{ route('exam_registration.export', array_merge(request()->all(), ['format' => 'pdf', 'session_id' => $session->id])) }}" target="_blank" class="btn btn-danger btn-sm btn-er-export">
                    <i class="fas fa-file-pdf mr-1"></i> พิมพ์ PDF
                </a>
            @endif
        </div>
    </div>

    <div class="er-filter-box">
        <form method="GET" action="{{ route('exam_registration.list') }}">
            <div class="row align-items-end">
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="text-muted font-weight-bold" style="font-size: 0.85rem;">รอบสอบ</label>
                    <select name="session_id" class="form-control" onchange="this.form.submit()">
                        @foreach($sessions as $examSession)
                            <option value="{{ $examSession->id }}" {{ $session && $session->id === $examSession->id ? 'selected' : '' }}>
                                ปี {{ $examSession->academic_year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="text-muted font-weight-bold" style="font-size: 0.85rem;">แยกชั้น</label>
                    <select name="tier" class="form-control">
                        <option value="">ทั้งหมด</option>
                        <option value="tree" {{ request('tier') === 'tree' ? 'selected' : '' }}>ตรี</option>
                        <option value="tho" {{ request('tier') === 'tho' ? 'selected' : '' }}>โท</option>
                        <option value="ek" {{ request('tier') === 'ek' ? 'selected' : '' }}>เอก</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="text-muted font-weight-bold" style="font-size: 0.85rem;">สถานะสมัคร</label>
                    <select name="status" class="form-control">
                        <option value="">ทั้งหมด</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>รออนุมัติ</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                        <option value="incomplete" {{ request('status') === 'incomplete' ? 'selected' : '' }}>ตามเอกสาร</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <label class="text-muted font-weight-bold" style="font-size: 0.85rem;">การติดตาม</label>
                    <select name="follow_up_status" class="form-control">
                        <option value="">ทั้งหมด</option>
                        <option value="none" {{ request('follow_up_status') === 'none' ? 'selected' : '' }}>ยังไม่ติดตาม</option>
                        <option value="โทร" {{ request('follow_up_status') === 'โทร' ? 'selected' : '' }}>📱 โทร</option>
                        <option value="ไลน์" {{ request('follow_up_status') === 'ไลน์' ? 'selected' : '' }}>💬 ไลน์</option>
                        <option value="อีเมล" {{ request('follow_up_status') === 'อีเมล' ? 'selected' : '' }}>✉️ อีเมล</option>
                        <option value="ติดต่อไม่ได้" {{ request('follow_up_status') === 'ติดต่อไม่ได้' ? 'selected' : '' }}>❌ ติดต่อไม่ได้</option>
                        <option value="ไม่มีผู้รับสาย" {{ request('follow_up_status') === 'ไม่มีผู้รับสาย' ? 'selected' : '' }}>📵 ไม่มีผู้รับสาย</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="text-muted font-weight-bold" style="font-size: 0.85rem;">ค้นหาขั้นสูง</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="keyword" class="form-control border-left-0" placeholder="ชื่อ, นามสกุล หรือ เลข ปชช." value="{{ request('keyword') }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-block px-0" title="ค้นหา"><i class="fas fa-filter"></i></button>
                </div>
            </div>
            @if(request('keyword') || request('tier') || request('status') || request('follow_up_status'))
                <div class="mt-3 text-right">
                    <a href="{{ route('exam_registration.list', ['session_id' => request('session_id')]) }}" class="text-danger" style="font-size: 0.9rem;">
                        <i class="fas fa-times-circle"></i> ล้างการค้นหา
                    </a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-er text-nowrap">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>ชื่อ - นามสกุล</th>
                    <th>เบอร์โทร</th>
                    <th class="text-center">ชั้นสมัครสอบ</th>
                    <th class="text-center">สถานะสมัคร</th>
                    <th>การติดตาม</th>
                    <th class="text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $i => $reg)
                    <tr>
                        <td class="text-muted">{{ $registrations->firstItem() + $i }}</td>
                        <td>
                            <div class="font-weight-bold text-dark">{{ $reg->title }} {{ $reg->first_name }} {{ $reg->last_name }}</div>
                            <div class="text-muted" style="font-size: 0.8rem;">รหัส ปชช. {{ $reg->national_id }}</div>
                        </td>
                        <td>
                            @if($reg->phone)
                                <a href="tel:{{ $reg->phone }}" class="text-decoration-none"><i class="fas fa-phone-alt text-muted mr-1" style="font-size:0.8rem"></i>{{ $reg->phone }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($reg->exam_tier == 'tree') <span class="badge badge-success">ตรี</span>
                            @elseif($reg->exam_tier == 'tho') <span class="badge badge-warning">โท</span>
                            @else <span class="badge badge-danger">เอก</span> @endif
                        </td>
                        <td class="text-center">
                            @php
                                $statusKey = $reg->status;
                                if ($statusKey === 'registered' || $statusKey === 'checked') {
                                    $statusKey = 'pending';
                                } elseif ($statusKey === 'eligible') {
                                    $statusKey = 'approved';
                                }
                                
                                $statusLabels = [
                                    'pending'    => 'รออนุมัติ',
                                    'approved'   => 'อนุมัติแล้ว',
                                    'rejected'   => 'ไม่อนุมัติ',
                                    'incomplete' => 'ตามเอกสาร',
                                    'cancelled'  => 'ยกเลิก',
                                ];
                                
                                $statusColors = [
                                    'pending'    => 'badge-warning',
                                    'approved'   => 'badge-success',
                                    'rejected'   => 'badge-danger',
                                    'incomplete' => 'badge-info',
                                    'cancelled'  => 'badge-secondary',
                                ];
                                
                                $badgeColor = $statusColors[$statusKey] ?? 'badge-secondary';
                                $badgeText = $statusLabels[$statusKey] ?? (\App\ExamRegistration::STATUSES[$reg->status] ?? $reg->status);
                            @endphp
                            <span class="badge {{ $badgeColor }} px-2 py-1 font-weight-normal" style="font-size: 0.82rem;">
                                {{ $badgeText }}
                            </span>
                        </td>
                        <td>
                            @if($reg->follow_up_status)
                                @php
                                    $fuIcons = [
                                        'โทร' => '📱',
                                        'ไลน์' => '💬',
                                        'อีเมล' => '✉️',
                                        'ติดต่อไม่ได้' => '❌',
                                        'ไม่มีผู้รับสาย' => '📵',
                                    ];
                                    $fuBadgeClasses = [
                                        'โทร' => 'badge-info',
                                        'ไลน์' => 'badge-success',
                                        'อีเมล' => 'badge-primary',
                                        'ติดต่อไม่ได้' => 'badge-danger',
                                        'ไม่มีผู้รับสาย' => 'badge-warning',
                                    ];
                                    $icon = $fuIcons[$reg->follow_up_status] ?? '📌';
                                    $badgeClass = $fuBadgeClasses[$reg->follow_up_status] ?? 'badge-secondary';
                                @endphp
                                <div>
                                    <span class="badge {{ $badgeClass }} px-2 py-1 font-weight-normal" style="font-size: 0.85rem;">
                                        {{ $icon }} {{ $reg->follow_up_status }}
                                    </span>
                                </div>
                                @if($reg->follow_up_note)
                                    <div class="text-dark mt-1" style="font-size: 0.78rem; max-width: 170px; white-space: normal; line-height: 1.25;" title="{{ $reg->follow_up_note }}">
                                        <i class="far fa-comment-dots text-muted mr-1"></i>{{ Str::limit($reg->follow_up_note, 35) }}
                                    </div>
                                @endif
                                @if($reg->updated_at)
                                    <div class="text-muted mt-1" style="font-size: 0.7rem;">
                                        <i class="far fa-clock mr-1"></i>{{ $reg->updated_at->format('d/m/y H:i') }}
                                    </div>
                                @endif
                            @else
                                <span class="badge-er-secondary">ยังไม่ติดตาม</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-info shadow-sm" data-toggle="modal" data-target="#followUpModal{{ $reg->id }}" title="บันทึกติดตาม">
                                    <i class="fas fa-headset"></i>
                                </button>
                                <a href="{{ route('exam_registration.show', $reg->id) }}" class="btn btn-sm btn-outline-primary shadow-sm" title="ดูรายละเอียด">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('exam_registration.edit', $reg->id) }}" class="btn btn-sm btn-outline-secondary shadow-sm" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('exam_registration.destroy', $reg->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบข้อมูลผู้สมัครรายนี้? ข้อมูลที่ลบจะไม่สามารถกู้คืนได้');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="ลบข้อมูล">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Modal Follow Up -->
                            <div class="modal fade" id="followUpModal{{ $reg->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow-lg">
                                        <form action="{{ route('exam_registration.follow_up', $reg->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header bg-info text-white border-0">
                                                <h5 class="modal-title font-weight-bold"><i class="fas fa-headset mr-2"></i>บันทึกการติดตาม</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-left p-4">
                                                <div class="mb-3 p-3 bg-light rounded text-center">
                                                    <h6 class="mb-0 text-dark">{{ $reg->title }} {{ $reg->first_name }} {{ $reg->last_name }}</h6>
                                                    <small class="text-muted">โทร: {{ $reg->phone ?: '-' }}</small>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-secondary">ผลการติดต่อ</label>
                                                    <select name="follow_up_status" class="form-control form-control-lg bg-light border-0">
                                                        <option value="">-- ยังไม่มีการติดต่อ --</option>
                                                        <option value="โทร" {{ $reg->follow_up_status == 'โทร' ? 'selected' : '' }}>📱 โทร</option>
                                                        <option value="ไลน์" {{ $reg->follow_up_status == 'ไลน์' ? 'selected' : '' }}>💬 ไลน์</option>
                                                        <option value="อีเมล" {{ $reg->follow_up_status == 'อีเมล' ? 'selected' : '' }}>✉️ อีเมล</option>
                                                        <option value="ติดต่อไม่ได้" {{ $reg->follow_up_status == 'ติดต่อไม่ได้' ? 'selected' : '' }}>❌ ติดต่อไม่ได้</option>
                                                        <option value="ไม่มีผู้รับสาย" {{ $reg->follow_up_status == 'ไม่มีผู้รับสาย' ? 'selected' : '' }}>📵 ไม่มีผู้รับสาย</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="font-weight-bold text-secondary">บันทึกเพิ่มเติม</label>
                                                    <textarea name="follow_up_note" class="form-control border-light" rows="3" placeholder="ระบุรายละเอียดเพิ่มเติม...">{{ $reg->follow_up_note }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0 pb-4 pr-4">
                                                <button type="button" class="btn btn-light" data-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn btn-info px-4 shadow-sm">บันทึกข้อมูล</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="fas fa-search fa-3x"></i></div>
                            <h6 class="text-secondary">ไม่พบรายชื่อผู้สมัคร</h6>
                            <small class="text-muted">ลองเปลี่ยนเงื่อนไขการค้นหาหรือเลือกรอบสอบอื่น</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($registrations->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $registrations->links() }}
        </div>
    @endif
</div>
@endsection
