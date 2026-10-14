@extends('layouts.admin')
@section('title','ใบประกาศนียบัตร')
@section('page-title','รายงานใบประกาศนียบัตร')

@section('content')
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="form-inline">
            <input type="text" name="search" class="form-control form-control-sm mr-2"
                   value="{{ request('search') }}" placeholder="ค้นหาเลขที่ / ชื่อ">
            <button class="btn btn-sm btn-secondary"><i class="fas fa-search mr-1"></i>ค้นหา</button>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr><th>เลขที่</th><th>ผู้รับ</th><th>ชุดข้อสอบ</th><th>คะแนน</th><th>ประเภท</th><th>ออกโดย</th><th>วันที่ออก</th><th>ตรวจสอบ</th></tr>
            </thead>
            <tbody>
            @forelse($certificates as $cert)
                <tr>
                    <td><code>{{ $cert->certificate_no }}</code></td>
                    <td>{{ $cert->user->name ?? '-' }}</td>
                    <td>{{ $cert->examSet->name ?? '-' }}</td>
                    <td>{{ $cert->score }}</td>
                    <td>
                        @if($cert->issued_type === 'auto')
                            <span class="badge badge-info">อัตโนมัติ</span>
                        @else
                            <span class="badge badge-warning text-dark">ออกเอง</span>
                        @endif
                    </td>
                    <td>{{ $cert->issuedBy->name ?? 'ระบบ' }}</td>
                    <td>{{ $cert->issued_at ? $cert->issued_at->format('d/m/Y') : '-' }}</td>
                    <td>
                        <a href="{{ route('certificates.verify', $cert->certificate_no) }}"
                           target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">ยังไม่มีใบประกาศ</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $certificates->appends(request()->query())->links() }}</div>
</div>
@endsection
