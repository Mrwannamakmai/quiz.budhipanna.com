@extends('layouts.teacher')
@section('title', 'พิมพ์ใบสมัคร')
@section('page-title', 'พิมพ์ใบสมัครสอบธรรมศึกษา')

@section('content')
<style>.er-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:18px}</style>

<a href="{{ route('teacher.exam_registration.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fas fa-arrow-left mr-1"></i>กลับ</a>

<div class="er-card">
    <form method="GET" class="form-inline mb-3">
        <input type="text" name="q" class="form-control form-control-sm mr-2" placeholder="ค้นหาชื่อ/เลขบัตร/เลขอ้างอิง" value="{{ request('q') }}">
        <button class="btn btn-sm btn-primary" type="submit">ค้นหา</button>
    </form>
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead><tr><th>เลขอ้างอิง</th><th>ชื่อ-สกุล</th><th>สถานะ</th><th></th></tr></thead>
            <tbody>
                @forelse ($registrations as $r)
                    <tr>
                        <td>{{ $r->reference_code }}</td>
                        <td>{{ $r->fullName() }}</td>
                        <td>{{ $r->statusLabel() }}</td>
                        <td><a href="{{ route('teacher.exam_registration.print.show', $r->id) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-print mr-1"></i>พิมพ์</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">ไม่มีข้อมูล</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $registrations->links() }}
</div>
@endsection
