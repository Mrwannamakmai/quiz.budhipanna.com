@extends('layouts.admin')
@section('title','อัพเดทระบบ')
@section('page-title','จัดการรายการอัพเดท')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted small">รายการอัพเดททั้งหมด {{ $updates->total() }} รายการ</span>
    <a href="{{ route('admin.updates.create') }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus mr-1"></i>เพิ่มรายการอัพเดท
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="thead-light">
                <tr>
                    <th style="width:48px;">#</th>
                    <th>หัวข้อ</th>
                    <th style="width:120px;">ประเภท</th>
                    <th style="width:100px;">สถานะ</th>
                    <th style="width:130px;">วันที่เผยแพร่</th>
                    <th style="width:120px;"></th>
                </tr>
            </thead>
            <tbody>
            @forelse($updates as $upd)
            @php $meta = $upd->getTypeMeta(); @endphp
            <tr>
                <td class="text-muted small">{{ $upd->id }}</td>
                <td>
                    <div style="font-weight:600;color:#1e293b;">{{ $upd->title }}</div>
                    @if($upd->description)
                    <div class="text-muted small text-truncate" style="max-width:400px;">{{ $upd->description }}</div>
                    @endif
                </td>
                <td>
                    <span style="background:{{ $meta['bg'] }};color:{{ $meta['color'] }};border-radius:10px;padding:2px 10px;font-size:.75rem;font-weight:700;">
                        <i class="fas {{ $meta['icon'] }} mr-1"></i>{{ $meta['label'] }}
                    </span>
                </td>
                <td>
                    @if($upd->is_published)
                        <span class="badge badge-success">เผยแพร่</span>
                    @else
                        <span class="badge badge-secondary">ซ่อน</span>
                    @endif
                </td>
                <td class="small text-muted">
                    @if($upd->published_at)
                        {{ $upd->published_at->format('d/m/Y H:i') }}
                    @else
                        —
                    @endif
                </td>
                <td class="text-right">
                    <form method="POST" action="{{ route('admin.updates.toggle', $upd) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $upd->is_published ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $upd->is_published ? 'ซ่อน' : 'เผยแพร่' }}">
                            <i class="fas {{ $upd->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.updates.edit', $upd) }}" class="btn btn-sm btn-outline-primary" title="แก้ไข">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.updates.destroy', $upd) }}" class="d-inline"
                          onsubmit="return confirm('ลบรายการนี้?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">ยังไม่มีรายการอัพเดท</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $updates->links() }}</div>

@endsection
