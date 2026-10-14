@extends('layouts.teacher')
@section('title','แก้ไขชุดข้อสอบ')
@section('page-title','แก้ไขชุดข้อสอบ')

@section('content')
<style>
.es-grid { display:grid; grid-template-columns:1fr 300px; gap:20px; max-width:960px; align-items:start; }
@media(max-width:768px){ .es-grid{ grid-template-columns:1fr; } }

.es-card { background:#fff; border-radius:10px; box-shadow:0 1px 6px rgba(0,0,0,.08); overflow:hidden; margin-bottom:20px; }
.es-card-header { display:flex; align-items:center; gap:10px; padding:14px 20px; border-bottom:1px solid #f0f0f0; }
.es-card-header .icon { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; flex-shrink:0; }
.es-card-header h6 { margin:0; font-weight:600; font-size:.9rem; color:#1e293b; }
.es-card-body { padding:20px; }

.field-label { font-size:.8rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; display:block; }
.field-label span.req { color:#ef4444; }
.es-input { border:1.5px solid #e2e8f0; border-radius:8px; padding:9px 13px; font-size:.9rem; transition:border-color .15s,box-shadow .15s; width:100%; background:#fafafa; }
.es-input:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.12); background:#fff; }
.es-input.is-invalid { border-color:#ef4444; }
.invalid-feedback { font-size:.78rem; color:#ef4444; margin-top:4px; }

/* Category pills */
.cat-strip { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:10px; }
.cat-pill { border:1.5px solid #e2e8f0; border-radius:20px; padding:4px 14px; font-size:.8rem; cursor:pointer; background:#fff; color:#475569; transition:all .15s; font-weight:500; }
.cat-pill:hover { border-color:#94a3b8; background:#f8fafc; }
.cat-pill.active { background:#1e40af; border-color:#1e40af; color:#fff; }
.dot { width:8px; height:8px; border-radius:50%; display:inline-block; margin-right:4px; }

/* Number row */
.num-row { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.num-box { text-align:center; }
.num-box .es-input { text-align:center; font-size:1.1rem; font-weight:600; }

/* Switches */
.switch-item { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f1f5f9; }
.switch-item:last-child { border-bottom:none; padding-bottom:0; }
.switch-label { font-size:.88rem; color:#334155; display:flex; align-items:center; gap:8px; }
.switch-label .si { width:28px; height:28px; border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
.switch-desc { font-size:.75rem; color:#94a3b8; margin-top:1px; }

.toggle { position:relative; display:inline-block; width:42px; height:24px; flex-shrink:0; }
.toggle input { opacity:0; width:0; height:0; }
.slider { position:absolute; cursor:pointer; inset:0; background:#cbd5e1; border-radius:24px; transition:.2s; }
.slider:before { position:absolute; content:""; height:18px; width:18px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.2s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
input:checked + .slider { background:#3b82f6; }
input:checked + .slider:before { transform:translateX(18px); }

.pool-size-row { display:flex; align-items:center; gap:10px; margin-top:12px; padding:12px; background:#f0f7ff; border-radius:8px; border:1px solid #bfdbfe; }
.pool-size-row label { font-size:.82rem; color:#1e40af; font-weight:600; margin:0; white-space:nowrap; }
.pool-size-row input { width:90px; border:1.5px solid #93c5fd; border-radius:6px; padding:6px 10px; text-align:center; font-size:.9rem; }

.submit-bar { display:flex; gap:10px; padding:16px 20px; background:#f8fafc; border-top:1px solid #f0f0f0; }
.btn-save { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:10px 24px; font-size:.9rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:7px; transition:background .15s; }
.btn-save:hover { background:#1d3a9a; }
.btn-cancel { color:#64748b; background:#fff; border:1.5px solid #e2e8f0; border-radius:8px; padding:10px 20px; font-size:.9rem; text-decoration:none; display:flex; align-items:center; gap:7px; transition:all .15s; }
.btn-cancel:hover { background:#f1f5f9; color:#334155; text-decoration:none; }
</style>

<form method="POST" action="{{ route('teacher.exam_sets.update', $examSet) }}">
@csrf @method('PUT')
<div class="es-grid">

    {{-- ═══ LEFT ═══ --}}
    <div>

        {{-- ข้อมูลทั่วไป --}}
        <div class="es-card">
            <div class="es-card-header">
                <div class="icon" style="background:#eff6ff;color:#3b82f6;"><i class="fas fa-file-alt"></i></div>
                <h6>ข้อมูลทั่วไป</h6>
            </div>
            <div class="es-card-body">
                <div class="mb-3">
                    <label class="field-label">ชื่อชุดข้อสอบ <span class="req">*</span></label>
                    <input type="text" name="name"
                           class="es-input @error('name') is-invalid @enderror"
                           value="{{ old('name', $examSet->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="field-label">คำอธิบาย</label>
                    <textarea name="description" class="es-input" rows="2">{{ old('description', $examSet->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- หมวดวิชา --}}
        <div class="es-card">
            <div class="es-card-header">
                <div class="icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-layer-group"></i></div>
                <h6>หมวดวิชา <small class="text-muted font-weight-normal">(ระบุทีหลังได้)</small></h6>
            </div>
            <div class="es-card-body">
                @php
                $currentCat = $examSet->subject->category ?? '';
                @endphp
                <div class="cat-strip">
                    <button type="button"
                            class="cat-pill {{ $currentCat === '' && $examSet->subject_id ? '' : ($currentCat === '' ? 'active' : '') }}"
                            data-cat="" id="catAll">ทั้งหมด</button>
                    @foreach([
                        ['กระทู้','#f59e0b'],
                        ['ธรรมะ','#10b981'],
                        ['พุทธะ','#f97316'],
                        ['วินัย','#3b82f6'],
                        ['ศาสนพิธี','#8b5cf6'],
                    ] as [$cat,$color])
                    <button type="button" class="cat-pill {{ $currentCat === $cat ? 'active' : '' }}" data-cat="{{ $cat }}">
                        <span class="dot" style="background:{{ $color }}"></span>{{ $cat }}
                    </button>
                    @endforeach
                    <button type="button" class="cat-pill {{ !$examSet->subject_id ? 'active' : '' }}" data-cat="__none__" id="catNone">
                        <span class="dot" style="background:#94a3b8"></span>ไม่ระบุ
                    </button>
                </div>
                <select name="subject_id" id="subject-select"
                        class="es-input @error('subject_id') is-invalid @enderror">
                    <option value="">— ไม่ระบุ / ระบุทีหลัง —</option>
                    @foreach($subjects as $s)
                    <option value="{{ $s->id }}"
                            data-cat="{{ $s->category ?? '' }}"
                            {{ old('subject_id', $examSet->subject_id) == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                    @endforeach
                </select>
                @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ห้องเรียน --}}
        <div class="es-card">
            <div class="es-card-header">
                <div class="icon" style="background:#fdf4ff;color:#a21caf;"><i class="fas fa-door-open"></i></div>
                <h6>ห้องเรียนที่สอบ <small class="text-muted font-weight-normal">(ถ้าไม่ระบุ = ทุกห้อง)</small></h6>
            </div>
            <div class="es-card-body">
                <select name="classroom_id" class="es-input">
                    <option value="">— ทุกห้องเรียน —</option>
                    @foreach($classrooms as $cl)
                    <option value="{{ $cl->id }}" {{ old('classroom_id',$examSet->classroom_id)==$cl->id?'selected':'' }}>
                        {{ $cl->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ช่วงเวลาสอบ --}}
        <div class="es-card">
            <div class="es-card-header">
                <div class="icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-clock"></i></div>
                <h6>ช่วงเวลาสอบ <small class="text-muted font-weight-normal">(ถ้าไม่ระบุ = ไม่จำกัดเวลา)</small></h6>
            </div>
            <div class="es-card-body">
                <div class="mb-3">
                    <label class="field-label">เปิดสอบวันที่</label>
                    <input type="datetime-local" name="available_from"
                           class="es-input @error('available_from') is-invalid @enderror"
                           value="{{ old('available_from', $examSet->available_from ? $examSet->available_from->format('Y-m-d\TH:i') : '') }}">
                    @error('available_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="field-label">ปิดสอบวันที่ <small class="text-muted">(deadline)</small></label>
                    <input type="datetime-local" name="available_until"
                           class="es-input @error('available_until') is-invalid @enderror"
                           value="{{ old('available_until', $examSet->available_until ? $examSet->available_until->format('Y-m-d\TH:i') : '') }}">
                    @error('available_until')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div style="font-size:.72rem;color:#94a3b8;margin-top:4px;">
                        <i class="fas fa-info-circle"></i> นักเรียนจะไม่สามารถเข้าสอบได้หลังจากเวลานี้
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══ RIGHT ═══ --}}
    <div>
        <div class="es-card">
            <div class="es-card-header">
                <div class="icon" style="background:#fff7ed;color:#ea580c;"><i class="fas fa-sliders-h"></i></div>
                <h6>ตั้งค่าการสอบ</h6>
            </div>
            <div class="es-card-body">
                <div class="num-row mb-3">
                    <div class="num-box">
                        <label class="field-label">เวลา (นาที)</label>
                        <input type="number" name="time_limit" class="es-input"
                               value="{{ old('time_limit', $examSet->time_limit) }}" min="1" placeholder="∞">
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:3px;">ว่าง = ไม่จำกัด</div>
                    </div>
                    <div class="num-box">
                        <label class="field-label">เกณฑ์ผ่าน (%) <span class="req">*</span></label>
                        <input type="number" name="pass_percentage" class="es-input"
                               value="{{ old('pass_percentage', $examSet->pass_percentage) }}" min="1" max="100" required>
                    </div>
                    <div class="num-box">
                        <label class="field-label">ครั้งที่สอบได้ <span class="req">*</span></label>
                        <input type="number" name="max_attempts" class="es-input"
                               value="{{ old('max_attempts', $examSet->max_attempts) }}" min="1" required>
                    </div>
                </div>

                <div class="switch-item">
                    <div class="switch-label">
                        <span class="si" style="background:#fef9c3;color:#ca8a04;"><i class="fas fa-random"></i></span>
                        สุ่มลำดับคำถาม
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="shuffle_questions" value="1"
                               {{ old('shuffle_questions', $examSet->shuffle_questions) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="switch-item">
                    <div class="switch-label">
                        <span class="si" style="background:#ecfdf5;color:#059669;"><i class="fas fa-swimming-pool"></i></span>
                        เลือกข้อสอบจาก Pool
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="pool_enabled" value="1" id="poolToggle"
                               {{ old('pool_enabled', $examSet->pool_enabled) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="pool-size-row" id="poolSizeGroup"
                     style="{{ old('pool_enabled', $examSet->pool_enabled) ? '' : 'display:none' }}">
                    <label><i class="fas fa-hashtag mr-1"></i>จำนวนข้อใน Pool</label>
                    <input type="number" name="pool_size" class="es-input"
                           value="{{ old('pool_size', $examSet->pool_size) }}" min="1">
                </div>

                <div class="switch-item" style="margin-top:4px;">
                    <div class="switch-label">
                        <span class="si" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-power-off"></i></span>
                        เปิดใช้งานทันที
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $examSet->is_active) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="switch-item">
                    <div class="switch-label">
                        <span class="si" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-globe-asia"></i></span>
                        <div>
                            Pre-Test ฟรี
                            <div class="switch-desc">ไม่ต้องล็อกอิน</div>
                        </div>
                    </div>
                    <label class="toggle">
                        <input type="checkbox" name="is_public" value="1"
                               {{ old('is_public', $examSet->is_public) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="submit-bar">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> บันทึก
                </button>
                <a href="{{ route('teacher.exam_sets.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> ยกเลิก
                </a>
            </div>
        </div>
    </div>

</div>
</form>
@endsection

@section('scripts')
<script>
(function () {
    var allOptions = [];
    var sel = document.getElementById('subject-select');
    Array.from(sel.options).forEach(function (o) { allOptions.push(o); });

    document.querySelectorAll('.cat-pill').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.cat-pill').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');

            var cat = this.dataset.cat;
            var current = sel.value;

            if (cat === '__none__') {
                // ไม่ระบุ — clear selection
                sel.innerHTML = '<option value="" selected>— ไม่ระบุ / ระบุทีหลัง —</option>';
                allOptions.forEach(function (o) {
                    if (o.value) sel.appendChild(o.cloneNode(true));
                });
                sel.value = '';
                return;
            }

            sel.innerHTML = '<option value="">— ไม่ระบุ / ระบุทีหลัง —</option>';
            allOptions.forEach(function (o) {
                if (!o.value) return;
                if (cat === '' || o.dataset.cat === cat) sel.appendChild(o.cloneNode(true));
            });

            if (current) {
                var found = sel.querySelector('option[value="' + current + '"]');
                if (found) found.selected = true;
            }
        });
    });

    // Pool toggle
    var poolToggle = document.getElementById('poolToggle');
    var poolGroup  = document.getElementById('poolSizeGroup');
    poolToggle.addEventListener('change', function () {
        poolGroup.style.display = this.checked ? 'flex' : 'none';
    });
})();
</script>
@endsection
