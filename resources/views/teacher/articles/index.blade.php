@extends('layouts.teacher')

@section('title','คลังความรู้')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 font-weight-bold"><i class="fas fa-lightbulb mr-2 text-warning"></i>คลังความรู้ (Know How)</h4>
        <small class="text-muted">จัดการบทความและเนื้อหาสำหรับนักเรียน</small>
    </div>
    <div class="d-flex" style="gap:8px;">
        <a href="{{ route('knowhow.index') }}" target="_blank" class="btn btn-sm btn-outline-success">
            <i class="fas fa-external-link-alt mr-1"></i>ดูหน้าสาธารณะ
        </a>
        <a href="{{ route('teacher.articles.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus mr-1"></i>เพิ่มบทความ
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('teacher.articles.index') }}" class="d-flex flex-wrap" style="gap:8px;align-items:center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อบทความ..."
                   class="form-control form-control-sm" style="max-width:240px;">
            <select name="published" class="form-control form-control-sm" style="max-width:140px;">
                <option value="">— สถานะทั้งหมด —</option>
                <option value="1" {{ request('published')==='1'?'selected':'' }}>เผยแพร่แล้ว</option>
                <option value="0" {{ request('published')==='0'?'selected':'' }}>แบบร่าง</option>
            </select>
            <button type="submit" class="btn btn-sm btn-primary">กรอง</button>
            @if(request()->hasAny(['search','published']))
            <a href="{{ route('teacher.articles.index') }}" class="btn btn-sm btn-outline-secondary">ล้าง</a>
            @endif
            <span class="ml-auto text-muted small">{{ $articles->total() }} บทความ</span>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.9rem;">
            <thead style="background:#f8fafc;">
                <tr>
                    <th style="width:40px;">#</th>
                    <th>ชื่อบทความ</th>
                    <th>วิชา</th>
                    <th>แท็ก</th>
                    <th class="text-center">เผยแพร่</th>
                    <th class="text-center">ยอดชม</th>
                    <th>วันที่</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $a)
                <tr>
                    <td class="text-muted" style="vertical-align:middle;">{{ $loop->iteration + ($articles->currentPage()-1)*$articles->perPage() }}</td>
                    <td style="vertical-align:middle;">
                        <a href="{{ route('knowhow.show', $a) }}" target="_blank" class="font-weight-600 text-dark" style="text-decoration:none;">
                            {{ $a->title }}
                        </a>
                        @if(!$a->is_published)
                        <span class="badge badge-secondary ml-1" style="font-size:.65rem;">ร่าง</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle;">
                        @if($a->subject)
                        <span class="badge badge-light" style="color:#0f766e;background:#f0fdf4;border:1px solid #a7f3d0;">{{ $a->subject->name }}</span>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle;max-width:160px;">
                        @foreach(array_slice($a->tags_array, 0, 3) as $tag)
                        <span style="background:#f1f5f9;color:#475569;border-radius:8px;padding:2px 7px;font-size:.7rem;display:inline-block;margin:1px;">{{ $tag }}</span>
                        @endforeach
                    </td>
                    <td class="text-center" style="vertical-align:middle;">
                        <form action="{{ route('teacher.articles.toggle', $a) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-xs border-0 p-0" title="{{ $a->is_published ? 'คลิกเพื่อซ่อน' : 'คลิกเพื่อเผยแพร่' }}">
                                @if($a->is_published)
                                <span style="color:#16a34a;font-size:1.2rem;"><i class="fas fa-toggle-on"></i></span>
                                @else
                                <span style="color:#94a3b8;font-size:1.2rem;"><i class="fas fa-toggle-off"></i></span>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="text-center" style="vertical-align:middle;">
                        <span class="text-muted small"><i class="fas fa-eye mr-1"></i>{{ number_format($a->view_count) }}</span>
                    </td>
                    <td style="vertical-align:middle;white-space:nowrap;">
                        <span class="text-muted small">{{ $a->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td style="vertical-align:middle;">
                        <a href="{{ route('teacher.articles.edit', $a) }}" class="btn btn-xs btn-outline-primary mr-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('teacher.articles.destroy', $a) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('ลบบทความ \"{{ addslashes($a->title) }}\" ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                        ยังไม่มีบทความ — <a href="{{ route('teacher.articles.create') }}">เพิ่มบทความแรก</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($articles->hasPages())
    <div class="card-footer bg-white border-top-0 d-flex justify-content-center py-2">
        {{ $articles->links() }}
    </div>
    @endif
</div>
@endsection
