@extends('layouts.admin')
@section('title', isset($update) ? 'แก้ไขรายการอัพเดท' : 'เพิ่มรายการอัพเดท')
@section('page-title', isset($update) ? 'แก้ไขรายการอัพเดท' : 'เพิ่มรายการอัพเดท')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST"
              action="{{ isset($update) ? route('admin.updates.update', $update) : route('admin.updates.store') }}">
            @csrf
            @if(isset($update)) @method('PUT') @endif

            {{-- ประเภท --}}
            <div class="form-group">
                <label class="font-weight-bold">ประเภท <span class="text-danger">*</span></label>
                <div class="d-flex flex-wrap" style="gap:8px;">
                    @foreach(\App\SystemUpdate::$types as $key => $meta)
                    <label style="cursor:pointer;margin:0;">
                        <input type="radio" name="type" value="{{ $key }}"
                               {{ old('type', $update->type ?? 'feature') === $key ? 'checked' : '' }}
                               style="display:none;" class="type-radio">
                        <span class="type-badge" data-bg="{{ $meta['bg'] }}" data-color="{{ $meta['color'] }}"
                              style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:20px;font-size:.82rem;font-weight:700;border:2px solid {{ $meta['color'] }};color:{{ $meta['color'] }};background:#fff;transition:all .15s;">
                            <i class="fas {{ $meta['icon'] }}"></i>{{ $meta['label'] }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- หัวข้อ --}}
            <div class="form-group">
                <label class="font-weight-bold">หัวข้อ <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $update->title ?? '') }}" placeholder="เช่น เพิ่มฟีเจอร์แชร์ใบประกาศ">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- คำอธิบาย --}}
            <div class="form-group">
                <label class="font-weight-bold">รายละเอียด</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                          rows="3" placeholder="อธิบายรายละเอียดเพิ่มเติม (ไม่บังคับ)">{{ old('description', $update->description ?? '') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- เผยแพร่ --}}
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_published" name="is_published"
                           {{ old('is_published', $update->is_published ?? true) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_published">เผยแพร่ทันที (แสดงบนหน้าแรก)</label>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.updates.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>ย้อนกลับ
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-save mr-1"></i>{{ isset($update) ? 'บันทึกการแก้ไข' : 'เพิ่มรายการ' }}
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>

<script>
document.querySelectorAll('.type-radio').forEach(function(radio) {
    radio.addEventListener('change', updateBadges);
});

function updateBadges() {
    document.querySelectorAll('.type-radio').forEach(function(radio) {
        var badge = radio.nextElementSibling;
        if (radio.checked) {
            badge.style.background = badge.dataset.bg;
            badge.style.color      = badge.dataset.color;
        } else {
            badge.style.background = '#fff';
            badge.style.color      = badge.dataset.color;
        }
    });
}
updateBadges();
</script>
@endsection
