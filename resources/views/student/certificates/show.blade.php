@extends('layouts.student')
@section('title','ใบประกาศ')

@section('content')
<div class="container py-4" style="max-width:700px;">
    <!-- Certificate card -->
    <div class="card shadow border-warning" id="certificateCard">
        <div class="card-body text-center py-5" style="background: linear-gradient(135deg, #fff9e6 0%, #fff 100%);">
            <div class="mb-3">
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-certificate fa-4x text-warning mx-2"></i>
                <i class="fas fa-star text-warning"></i>
            </div>
            <h5 class="text-muted mb-1">ใบประกาศนียบัตร</h5>
            <h6 class="text-muted mb-4">Certificate of Achievement</h6>
            <hr class="border-warning">
            <p class="text-muted mb-1">ขอมอบใบประกาศนียบัตรนี้ให้แก่</p>
            <h3 class="font-weight-bold my-3">{{ $certificate->user->name ?? '-' }}</h3>
            <p class="text-muted mb-1">ได้สอบผ่านในชุดข้อสอบ</p>
            <h5 class="font-weight-bold text-primary my-2">{{ $certificate->examSet->name ?? '-' }}</h5>
            <p class="mb-0">คะแนน <strong class="text-success">{{ $certificate->score }}</strong> คะแนน</p>
            <hr class="border-warning mt-4">
            <div class="row text-muted small mt-3">
                <div class="col-6 text-left">
                    <i class="fas fa-barcode mr-1"></i>เลขที่: <code>{{ $certificate->certificate_no }}</code>
                </div>
                <div class="col-6 text-right">
                    <i class="fas fa-calendar mr-1"></i>{{ $certificate->issued_at ? $certificate->issued_at->format('d/m/Y') : '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-between">
        <a href="{{ route('student.certificates.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i>กลับ
        </a>
        <div>
            <a href="{{ route('certificates.verify', $certificate->certificate_no) }}"
               target="_blank" class="btn btn-outline-info mr-2">
                <i class="fas fa-external-link-alt mr-1"></i>ตรวจสอบ
            </a>
            <button onclick="window.print()" class="btn btn-warning">
                <i class="fas fa-print mr-1"></i>พิมพ์
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, .btn, a { display: none !important; }
    .card { border: 2px solid #f0c040 !important; }
}
</style>
@endsection
