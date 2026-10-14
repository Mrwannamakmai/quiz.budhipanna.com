@extends('layouts.student')
@section('title','ใบประกาศของฉัน')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">ใบประกาศของฉัน</h4>

    @if($certificates->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fas fa-certificate fa-4x mb-3 text-muted"></i>
        <p>ยังไม่มีใบประกาศ — สอบผ่านเพื่อรับใบประกาศ</p>
        <a href="{{ route('student.exams.index') }}" class="btn btn-primary">ไปสอบเลย</a>
    </div>
    @else
    <div class="row">
        @foreach($certificates as $cert)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-warning shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="fas fa-certificate fa-3x text-warning mb-3"></i>
                    <h6 class="card-title">{{ $cert->examSet->name ?? '-' }}</h6>
                    <p class="text-muted small mb-1"><code>{{ $cert->certificate_no }}</code></p>
                    <p class="text-muted small">{{ $cert->issued_at ? $cert->issued_at->format('d/m/Y') : '-' }}</p>
                    <p class="mb-0">คะแนน: <strong class="text-success">{{ $cert->score }}</strong></p>
                </div>
                <div class="card-footer bg-transparent text-center">
                    <a href="{{ route('student.certificates.show', $cert) }}" class="btn btn-warning btn-sm btn-block">
                        <i class="fas fa-eye mr-1"></i>ดูใบประกาศ
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">{{ $certificates->links() }}</div>
    @endif
</div>
@endsection
