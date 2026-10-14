@extends('layouts.admin')
@section('title','แดชบอร์ด – Admin')
@section('page-title','แดชบอร์ด')

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-left-primary h-100">
            <div class="card-body d-flex align-items-center">
                <div class="mr-3 text-primary" style="font-size:2.5rem;"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <div class="text-muted small">นักเรียนทั้งหมด</div>
                    <div class="h3 font-weight-bold mb-0">{{ $stats['total_students'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="mr-3 text-info" style="font-size:2.5rem;"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <div class="text-muted small">ครูผู้สอน</div>
                    <div class="h3 font-weight-bold mb-0">{{ $stats['total_teachers'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="mr-3 text-success" style="font-size:2.5rem;"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <div class="text-muted small">ครั้งที่สอบแล้ว</div>
                    <div class="h3 font-weight-bold mb-0">{{ $stats['total_attempts'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="mr-3 text-warning" style="font-size:2.5rem;"><i class="fas fa-certificate"></i></div>
                <div>
                    <div class="text-muted small">ใบประกาศที่ออก</div>
                    <div class="h3 font-weight-bold mb-0">{{ $stats['total_certificates'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history mr-1 text-muted"></i> การสอบล่าสุด</span>
        <a href="{{ route('admin.reports.attempts') }}" class="btn btn-sm btn-outline-secondary">ดูทั้งหมด</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr><th>นักเรียน</th><th>ชุดข้อสอบ</th><th>คะแนน</th><th>ผล</th><th>วันที่สอบ</th></tr>
                </thead>
                <tbody>
                @forelse($recentAttempts as $a)
                    <tr>
                        <td>{{ $a->user->name ?? '-' }}</td>
                        <td>{{ $a->examSet->name ?? '-' }}</td>
                        <td>{{ $a->score }} / {{ $a->total_points }}</td>
                        <td>
                            @if($a->passed)
                                <span class="badge badge-success">ผ่าน</span>
                            @else
                                <span class="badge badge-danger">ไม่ผ่าน</span>
                            @endif
                        </td>
                        <td>{{ $a->submitted_at ? $a->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีข้อมูล</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
