@extends('layouts.student')
@section('title','{{ $examSet->name }}')

@section('content')
<div class="container py-4" style="max-width:700px;">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">{{ $examSet->name }}</h5>
        </div>
        <div class="card-body">
            @if($examSet->description)
            <p class="text-muted">{{ $examSet->description }}</p>
            @endif

            {{-- ข้อความแจ้งเตือนเมื่อเกินกำหนด --}}
            @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                {{ $errors->first() }}
            </div>
            @endif

            {{-- แสดงช่วงเวลาสอบ --}}
            @if($examSet->available_from || $examSet->available_until)
            <div class="alert alert-{{ $scheduleBlocked ? ($examSet->isExpired() ? 'danger' : 'warning') : 'info' }} py-2">
                <i class="fas fa-calendar-alt mr-1"></i>
                <strong>ช่วงเวลาสอบ:</strong>
                {{ $examSet->available_from ? $examSet->available_from->format('d/m/Y H:i') : 'เปิดแล้ว' }}
                —
                {{ $examSet->available_until ? $examSet->available_until->format('d/m/Y H:i') : 'ไม่จำกัด' }}
                @if($examSet->isExpired())
                    <span class="badge badge-danger ml-1">หมดสิทธิ์สอบแล้ว</span>
                @elseif($examSet->isNotYetOpen())
                    <span class="badge badge-warning ml-1">ยังไม่เปิดสอบ</span>
                @else
                    <span class="badge badge-success ml-1">เปิดสอบอยู่</span>
                @endif
            </div>
            @endif

            <div class="row mb-3">
                <div class="col-6 col-md-3 mb-2">
                    <div class="text-center border rounded p-2">
                        <div class="font-weight-bold text-primary">{{ $examSet->time_limit ? $examSet->time_limit.' นาที' : 'ไม่จำกัด' }}</div>
                        <small class="text-muted">เวลา</small>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="text-center border rounded p-2">
                        <div class="font-weight-bold text-success">{{ $examSet->pass_percentage }}%</div>
                        <small class="text-muted">เกณฑ์ผ่าน</small>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="text-center border rounded p-2">
                        <div class="font-weight-bold">{{ $examSet->max_attempts }}</div>
                        <small class="text-muted">ครั้งที่สอบได้</small>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="text-center border rounded p-2">
                        <div class="font-weight-bold text-info">{{ $attemptCount }}</div>
                        <small class="text-muted">ครั้งที่สอบแล้ว</small>
                    </div>
                </div>
            </div>

            @if($latestAttempt && $latestAttempt->submitted_at)
            <div class="alert alert-{{ $latestAttempt->is_passed ? 'success' : 'warning' }}">
                <strong>ผลล่าสุด:</strong>
                {{ $latestAttempt->score }} คะแนน ({{ number_format($latestAttempt->score_percentage, 1) }}%) —
                {{ $latestAttempt->is_passed ? 'ผ่าน ✓' : 'ไม่ผ่าน' }}
                <a href="{{ route('student.exams.result', $latestAttempt) }}" class="btn btn-sm btn-outline-{{ $latestAttempt->is_passed ? 'success' : 'warning' }} ml-2">ดูผล</a>
            </div>
            @endif

            @if($canAttempt)
                <form method="POST" action="{{ route('student.exams.start', $examSet) }}">
                    @csrf
                    <button class="btn btn-primary btn-lg btn-block mt-3">
                        <i class="fas fa-play-circle mr-2"></i>
                        {{ $attemptCount > 0 ? 'สอบอีกครั้ง' : 'เริ่มสอบ' }}
                    </button>
                </form>
            @else
                <div class="alert alert-{{ $examSet->isExpired() ? 'danger' : ($examSet->isNotYetOpen() ? 'warning' : 'secondary') }} mt-3">
                    <i class="fas fa-lock mr-1"></i>
                    @if(!$examSet->is_active)
                        ชุดข้อสอบนี้ปิดอยู่
                    @elseif($examSet->isExpired())
                        <strong>หมดสิทธิ์การสอบแล้ว</strong> — เกินกำหนดวันที่ {{ $examSet->available_until->format('d/m/Y H:i') }}
                    @elseif($examSet->isNotYetOpen())
                        <strong>ยังไม่ถึงเวลาเปิดสอบ</strong> — เปิดสอบวันที่ {{ $examSet->available_from->format('d/m/Y H:i') }}
                    @else
                        คุณสอบครบจำนวนครั้งที่กำหนดแล้ว ({{ $examSet->max_attempts }} ครั้ง)
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($attempts->count() > 0)
    <div class="card mt-4">
        <div class="card-header">ประวัติการสอบ</div>
        <div class="table-responsive">
        <table class="table table-sm mb-0">
            <thead class="thead-light">
                <tr><th>#</th><th>คะแนน</th><th>%</th><th>ผล</th><th>วันที่</th><th></th></tr>
            </thead>
            <tbody>
            @foreach($attempts as $i => $a)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $a->score ?? '-' }}</td>
                <td>{{ $a->submitted_at ? number_format($a->score_percentage,1).'%' : '-' }}</td>
                <td>
                    @if($a->submitted_at)
                        <span class="badge badge-{{ $a->is_passed ? 'success' : 'danger' }}">{{ $a->is_passed ? 'ผ่าน' : 'ไม่ผ่าน' }}</span>
                    @else
                        <span class="badge badge-warning">ค้างอยู่</span>
                    @endif
                </td>
                <td>{{ $a->submitted_at ? $a->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                <td>
                    @if($a->submitted_at)
                    <a href="{{ route('student.exams.result', $a) }}" class="btn btn-xs btn-outline-secondary">ดูผล</a>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
    @endif
</div>
@endsection
