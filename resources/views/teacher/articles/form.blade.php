@extends('layouts.teacher')

@section('title', isset($article) ? 'แก้ไขบทความ' : 'เพิ่มบทความ')

@section('content')
@php $editing = isset($article); @endphp

<style>
.media-section-title { font-size:.72rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; margin-bottom:10px; }
.media-row { background:#f8fafc; border-radius:10px; padding:14px 16px; margin-bottom:10px; border:1.5px solid #e2e8f0; }
.media-icon { width:32px; height:32px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; }
.media-label { font-size:.82rem; font-weight:700; color:#374151; margin-bottom:4px; }
.media-hint  { font-size:.7rem; color:#94a3b8; margin-top:3px; }
.preview-thumb { max-width:100%; border-radius:8px; border:1px solid #e2e8f0; margin-top:8px; display:none; }
</style>

<div class="d-flex align-items-center mb-4">
    <a href="{{ route('teacher.articles.index') }}" class="btn btn-sm btn-outline-secondary mr-3">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h4 class="mb-0 font-weight-bold">
            <i class="fas fa-{{ $editing ? 'edit' : 'plus-circle' }} mr-2 text-success"></i>
            {{ $editing ? 'แก้ไขบทความ' : 'เพิ่มบทความใหม่' }}
        </h4>
        @if($editing)
        <small class="text-muted">{{ $article->title }}</small>
        @endif
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0 pl-3">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ $editing ? route('teacher.articles.update', $article) : route('teacher.articles.store') }}"
      method="POST">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="row">
        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">

            {{-- Title + Body --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-700 small text-uppercase text-muted">ชื่อบทความ <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="เช่น ธรรมวิภาค ๒ — สัมมัปปธาน ๔" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-700 small text-uppercase text-muted">เนื้อหา <span class="text-danger">*</span></label>
                        <div style="font-size:.75rem;color:#94a3b8;margin-bottom:6px;">รองรับการขึ้นบรรทัดใหม่ด้วย Enter</div>
                        <textarea name="body" rows="16"
                                  class="form-control @error('body') is-invalid @enderror"
                                  placeholder="เขียนเนื้อหาบทความที่นี่..." required
                                  style="font-family:'Sarabun',sans-serif;font-size:.92rem;line-height:1.8;resize:vertical;">{{ old('body', $article->body ?? '') }}</textarea>
                        @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- ── MEDIA SECTION ── --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom" style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;">
                    <i class="fas fa-photo-video mr-1 text-primary"></i>สื่อและไฟล์แนบ
                </div>
                <div class="card-body">

                    {{-- Image URL --}}
                    <div class="media-row">
                        <div class="d-flex align-items-start" style="gap:10px;">
                            <div class="media-icon" style="background:#dbeafe;color:#2563eb;"><i class="fas fa-image"></i></div>
                            <div style="flex:1;">
                                <div class="media-label">รูปภาพหลัก (Image URL)</div>
                                <input type="url" name="image_url" id="image_url"
                                       value="{{ old('image_url', $article->image_url ?? '') }}"
                                       class="form-control form-control-sm @error('image_url') is-invalid @enderror"
                                       placeholder="https://example.com/image.jpg"
                                       oninput="previewImage(this)">
                                <div class="media-hint">ลิงก์รูปภาพ (.jpg .png .webp) แสดงที่ด้านบนบทความ</div>
                                @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <img id="image_preview" class="preview-thumb"
                                     src="{{ old('image_url', $article->image_url ?? '') }}"
                                     style="{{ !empty(old('image_url', $article->image_url ?? '')) ? 'display:block;' : 'display:none;' }}">
                            </div>
                        </div>
                    </div>

                    {{-- YouTube --}}
                    <div class="media-row">
                        <div class="d-flex align-items-start" style="gap:10px;">
                            <div class="media-icon" style="background:#fee2e2;color:#dc2626;"><i class="fab fa-youtube"></i></div>
                            <div style="flex:1;">
                                <div class="media-label">YouTube Video</div>
                                <input type="url" name="youtube_url"
                                       value="{{ old('youtube_url', $article->youtube_url ?? '') }}"
                                       class="form-control form-control-sm @error('youtube_url') is-invalid @enderror"
                                       placeholder="https://www.youtube.com/watch?v=...">
                                <div class="media-hint">วาง URL จาก YouTube หรือ YouTube Shorts — ระบบฝัง player อัตโนมัติ</div>
                                @error('youtube_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Canva --}}
                    <div class="media-row">
                        <div class="d-flex align-items-start" style="gap:10px;">
                            <div class="media-icon" style="background:#ede9fe;color:#7c3aed;"><i class="fas fa-chalkboard-teacher"></i></div>
                            <div style="flex:1;">
                                <div class="media-label">Canva Presentation</div>
                                <input type="url" name="canva_url"
                                       value="{{ old('canva_url', $article->canva_url ?? '') }}"
                                       class="form-control form-control-sm @error('canva_url') is-invalid @enderror"
                                       placeholder="https://www.canva.com/design/.../view">
                                <div class="media-hint">ลิงก์ Canva แบบ Present / Share → "Embed" → นำ URL มาวาง</div>
                                @error('canva_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Ebook URL --}}
                    <div class="media-row">
                        <div class="d-flex align-items-start" style="gap:10px;">
                            <div class="media-icon" style="background:#d1fae5;color:#059669;"><i class="fas fa-book-open"></i></div>
                            <div style="flex:1;">
                                <div class="media-label">Ebook / อ่านออนไลน์ (URL)</div>
                                <input type="url" name="ebook_url"
                                       value="{{ old('ebook_url', $article->ebook_url ?? '') }}"
                                       class="form-control form-control-sm @error('ebook_url') is-invalid @enderror"
                                       placeholder="https://anyflip.com/... หรือ issuu.com/...">
                                <div class="media-hint">ลิงก์ Ebook ออนไลน์ (Anyflip, Issuu, Fliphtml5 ฯลฯ) — เปิดในแท็บใหม่</div>
                                @error('ebook_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- NotebookLM (multiple, max 10) --}}
                    <div class="media-row" style="border-color:#bfdbfe;padding-bottom:10px;">
                        <div class="d-flex align-items-center mb-2" style="gap:8px;">
                            <div class="media-icon" style="background:#e0f2fe;color:#0369a1;flex-shrink:0;"><i class="fas fa-brain"></i></div>
                            <div style="flex:1;">
                                <div class="media-label mb-0">NotebookLM Presentations <span style="font-weight:400;color:#94a3b8;">(สูงสุด 10 รายการ)</span></div>
                                <div class="media-hint mt-0">ลิงก์ Share จาก NotebookLM — กรอก URL + ชื่อที่แสดง (ไม่บังคับ)</div>
                            </div>
                            <button type="button" id="nlm-add-btn" onclick="nlmAddRow()"
                                    style="flex-shrink:0;display:inline-flex;align-items:center;gap:5px;background:#2563eb;color:#fff;border:none;border-radius:8px;padding:5px 12px;font-size:.75rem;font-weight:700;cursor:pointer;font-family:inherit;">
                                <i class="fas fa-plus"></i>เพิ่ม
                            </button>
                        </div>
                        <div id="nlm-rows" style="display:flex;flex-direction:column;gap:8px;">
                            @php
                                $nlmItems = old('notebooklm', $editing ? ($article->notebooklm_items ?? []) : []);
                                if (empty($nlmItems)) $nlmItems = [['url'=>'','label'=>'']];
                            @endphp
                            @foreach($nlmItems as $ni => $nlm)
                            <div class="nlm-row" style="display:flex;gap:6px;align-items:flex-start;">
                                <div style="flex:1;display:flex;flex-direction:column;gap:4px;">
                                    <input type="url" name="notebooklm[{{ $ni }}][url]"
                                           value="{{ $nlm['url'] ?? '' }}"
                                           class="form-control form-control-sm"
                                           placeholder="https://notebook.google.com/notebook/.../artifact/...">
                                    <input type="text" name="notebooklm[{{ $ni }}][label]"
                                           value="{{ $nlm['label'] ?? '' }}"
                                           class="form-control form-control-sm"
                                           placeholder="ชื่อที่แสดง เช่น บทที่ 1, ตอนที่ 2 (ไม่บังคับ)">
                                </div>
                                <button type="button" onclick="nlmRemoveRow(this)"
                                        style="flex-shrink:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fee2e2;color:#dc2626;border:none;border-radius:8px;cursor:pointer;margin-top:2px;font-size:.8rem;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <div id="nlm-limit-msg" style="display:none;font-size:.72rem;color:#dc2626;margin-top:6px;"><i class="fas fa-exclamation-circle mr-1"></i>ครบ 10 รายการแล้ว</div>
                    </div>

                    {{-- File Download --}}
                    <div class="media-row" style="border-color:#fde68a;">
                        <div class="d-flex align-items-start" style="gap:10px;">
                            <div class="media-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-file-download"></i></div>
                            <div style="flex:1;">
                                <div class="media-label">ไฟล์ดาวน์โหลด (PDF / Doc)</div>
                                <input type="url" name="file_url"
                                       value="{{ old('file_url', $article->file_url ?? '') }}"
                                       class="form-control form-control-sm mb-2 @error('file_url') is-invalid @enderror"
                                       placeholder="https://drive.google.com/... หรือ URL ตรงถึงไฟล์">
                                <input type="text" name="file_name"
                                       value="{{ old('file_name', $article->file_name ?? '') }}"
                                       class="form-control form-control-sm @error('file_name') is-invalid @enderror"
                                       placeholder="ชื่อไฟล์ที่แสดง เช่น สัมมัปปธาน-๔.pdf">
                                <div class="media-hint">บรรทัดบน = URL ไฟล์ &nbsp;·&nbsp; บรรทัดล่าง = ชื่อที่แสดงบนปุ่มดาวน์โหลด</div>
                                @error('file_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-4">

            {{-- Publish --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom" style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;">
                    <i class="fas fa-cog mr-1"></i>การเผยแพร่
                </div>
                <div class="card-body">
                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="is_published" name="is_published"
                               {{ old('is_published', $editing && $article->is_published) ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-600" for="is_published">เผยแพร่ให้สาธารณะ</label>
                    </div>
                    <p class="text-muted small mb-0">เปิด = นักเรียนและผู้ใช้ทั่วไปเห็น<br>ปิด = บันทึกเป็นแบบร่าง</p>
                </div>
                <div class="card-footer bg-white">
                    <button type="submit" class="btn btn-success btn-block font-weight-700">
                        <i class="fas fa-save mr-1"></i>{{ $editing ? 'บันทึกการแก้ไข' : 'เพิ่มบทความ' }}
                    </button>
                    <a href="{{ route('teacher.articles.index') }}" class="btn btn-outline-secondary btn-block btn-sm mt-2">ยกเลิก</a>
                </div>
            </div>

            {{-- Subject --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom" style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;">
                    <i class="fas fa-layer-group mr-1"></i>วิชา
                </div>
                <div class="card-body">
                    <select name="subject_id" class="form-control form-control-sm @error('subject_id') is-invalid @enderror">
                        <option value="">— ไม่ระบุวิชา —</option>
                        @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ old('subject_id', $editing ? $article->subject_id : '') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tags --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom" style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;">
                    <i class="fas fa-tags mr-1"></i>แท็ก
                </div>
                <div class="card-body">
                    <input type="text" name="tags" value="{{ old('tags', $article->tags ?? '') }}"
                           class="form-control form-control-sm @error('tags') is-invalid @enderror"
                           placeholder="ธรรมวิภาค, สัมมัปปธาน, ตรี">
                    <div class="text-muted" style="font-size:.73rem;margin-top:5px;">คั่นด้วยเครื่องหมาย , (คอมมา)</div>
                </div>
            </div>

            {{-- Media quick check --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom" style="font-size:.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;">
                    <i class="fas fa-photo-video mr-1 text-primary"></i>สื่อที่กรอก
                </div>
                <div class="card-body py-2">
                    <div style="font-size:.8rem;" id="media-checklist">
                        <div class="d-flex align-items-center py-1 border-bottom" style="border-color:#f1f5f9!important;">
                            <i class="fas fa-image mr-2" style="color:#2563eb;width:16px;"></i>
                            <span class="flex-grow-1">รูปภาพ</span>
                            <span id="chk-image" class="text-muted">—</span>
                        </div>
                        <div class="d-flex align-items-center py-1 border-bottom" style="border-color:#f1f5f9!important;">
                            <i class="fab fa-youtube mr-2" style="color:#dc2626;width:16px;"></i>
                            <span class="flex-grow-1">YouTube</span>
                            <span id="chk-youtube" class="text-muted">—</span>
                        </div>
                        <div class="d-flex align-items-center py-1 border-bottom" style="border-color:#f1f5f9!important;">
                            <i class="fas fa-chalkboard-teacher mr-2" style="color:#7c3aed;width:16px;"></i>
                            <span class="flex-grow-1">Canva</span>
                            <span id="chk-canva" class="text-muted">—</span>
                        </div>
                        <div class="d-flex align-items-center py-1 border-bottom" style="border-color:#f1f5f9!important;">
                            <i class="fas fa-book-open mr-2" style="color:#059669;width:16px;"></i>
                            <span class="flex-grow-1">Ebook</span>
                            <span id="chk-ebook" class="text-muted">—</span>
                        </div>
                        <div class="d-flex align-items-center py-1 border-bottom" style="border-color:#f1f5f9!important;">
                            <i class="fas fa-brain mr-2" style="color:#0369a1;width:16px;"></i>
                            <span class="flex-grow-1">NotebookLM</span>
                            <span id="chk-notebooklm" class="text-muted">—</span>
                        </div>

                        <div class="d-flex align-items-center py-1">
                            <i class="fas fa-file-download mr-2" style="color:#d97706;width:16px;"></i>
                            <span class="flex-grow-1">ไฟล์ดาวน์โหลด</span>
                            <span id="chk-file" class="text-muted">—</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($editing)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-2">
                    <div class="text-muted small">
                        <div><i class="fas fa-eye mr-1"></i>ยอดชม: <strong>{{ number_format($article->view_count) }}</strong></div>
                        <div><i class="fas fa-calendar mr-1"></i>สร้าง: {{ $article->created_at->format('d/m/Y H:i') }}</div>
                        <div><i class="fas fa-pen mr-1"></i>แก้ไขล่าสุด: {{ $article->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <a href="{{ route('knowhow.show', $article) }}" target="_blank" class="btn btn-sm btn-outline-info btn-block mt-2">
                        <i class="fas fa-external-link-alt mr-1"></i>ดูบทความ
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

<script>
function previewImage(input) {
    var img = document.getElementById('image_preview');
    if (input.value) {
        img.src = input.value;
        img.style.display = 'block';
    } else {
        img.style.display = 'none';
    }
}

// Live checklist
function updateChecklist() {
    var fields = {
        'image':   document.querySelector('[name="image_url"]'),
        'youtube': document.querySelector('[name="youtube_url"]'),
        'canva':   document.querySelector('[name="canva_url"]'),
        'ebook':   document.querySelector('[name="ebook_url"]'),
        'file':    document.querySelector('[name="file_url"]'),
    };
    Object.keys(fields).forEach(function(key) {
        var el = document.getElementById('chk-' + key);
        if (!el) return;
        var val = fields[key] ? fields[key].value.trim() : '';
        if (val) {
            el.innerHTML = '<span style="color:#16a085;font-weight:700;">✓</span>';
        } else {
            el.innerHTML = '<span style="color:#cbd5e1;">—</span>';
        }
    });
}
document.querySelectorAll('[name="image_url"],[name="youtube_url"],[name="canva_url"],[name="ebook_url"],[name="file_url"]')
    .forEach(function(el) { el.addEventListener('input', updateChecklist); });

// NotebookLM checklist count
function nlmUpdateChecklist() {
    var filled = Array.from(document.querySelectorAll('#nlm-rows .nlm-row input[type="url"]'))
                      .filter(function(i){ return i.value.trim() !== ''; }).length;
    var el = document.getElementById('chk-notebooklm');
    if (el) el.innerHTML = filled > 0
        ? '<span style="color:#16a085;font-weight:700;">' + filled + ' รายการ</span>'
        : '<span style="color:#cbd5e1;">—</span>';
}
document.getElementById('nlm-rows').addEventListener('input', nlmUpdateChecklist);

// Add / Remove rows
function nlmReindex() {
    document.querySelectorAll('#nlm-rows .nlm-row').forEach(function(row, i) {
        row.querySelectorAll('input').forEach(function(inp) {
            inp.name = inp.name.replace(/notebooklm\[\d+\]/, 'notebooklm[' + i + ']');
        });
    });
}
function nlmAddRow() {
    var rows = document.querySelectorAll('#nlm-rows .nlm-row');
    if (rows.length >= 10) {
        document.getElementById('nlm-limit-msg').style.display = 'block';
        return;
    }
    var idx = rows.length;
    var div = document.createElement('div');
    div.className = 'nlm-row';
    div.style.cssText = 'display:flex;gap:6px;align-items:flex-start;';
    div.innerHTML = '<div style="flex:1;display:flex;flex-direction:column;gap:4px;">'
        + '<input type="url" name="notebooklm[' + idx + '][url]" class="form-control form-control-sm" placeholder="https://notebook.google.com/notebook/.../artifact/...">'
        + '<input type="text" name="notebooklm[' + idx + '][label]" class="form-control form-control-sm" placeholder="ชื่อที่แสดง เช่น บทที่ 1, ตอนที่ 2 (ไม่บังคับ)">'
        + '</div>'
        + '<button type="button" onclick="nlmRemoveRow(this)" style="flex-shrink:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fee2e2;color:#dc2626;border:none;border-radius:8px;cursor:pointer;margin-top:2px;font-size:.8rem;"><i class="fas fa-times"></i></button>';
    document.getElementById('nlm-rows').appendChild(div);
    div.querySelector('input[type="url"]').focus();
    var total = document.querySelectorAll('#nlm-rows .nlm-row').length;
    document.getElementById('nlm-limit-msg').style.display = total >= 10 ? 'block' : 'none';
    document.getElementById('nlm-add-btn').disabled = total >= 10;
    nlmUpdateChecklist();
}
function nlmRemoveRow(btn) {
    var rows = document.querySelectorAll('#nlm-rows .nlm-row');
    if (rows.length <= 1) {
        var row = btn.closest('.nlm-row');
        row.querySelectorAll('input').forEach(function(i){ i.value=''; });
        nlmUpdateChecklist();
        return;
    }
    btn.closest('.nlm-row').remove();
    nlmReindex();
    var total = document.querySelectorAll('#nlm-rows .nlm-row').length;
    document.getElementById('nlm-limit-msg').style.display = 'none';
    document.getElementById('nlm-add-btn').disabled = false;
    nlmUpdateChecklist();
}

updateChecklist();
nlmUpdateChecklist();
</script>
@endsection
