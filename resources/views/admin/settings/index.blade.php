@extends('layouts.admin')
@section('title', 'ตั้งค่าระบบ')
@section('page-title', 'ตั้งค่าระบบ')

@section('content')
<style>
.st-wrap { display:grid; grid-template-columns:220px 1fr; gap:20px; align-items:start; }
@media (max-width: 768px) { .st-wrap { grid-template-columns:1fr; } }
.st-nav { background:#fff; border-radius:12px; border:1.5px solid #f1f5f9; box-shadow:0 1px 4px rgba(0,0,0,.05); padding:8px; }
.st-nav a { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; color:#475569; font-size:.88rem; text-decoration:none; }
.st-nav a:hover { background:#f8fafc; text-decoration:none; color:#1e293b; }
.st-nav a.active { background:#eff6ff; color:#1d4ed8; font-weight:600; }
.st-card { background:#fff; border-radius:12px; border:1.5px solid #f1f5f9; box-shadow:0 1px 6px rgba(0,0,0,.06); }
.st-head { padding:18px 22px; border-bottom:1.5px solid #f1f5f9; }
.st-head h5 { margin:0; font-weight:700; color:#1e293b; font-size:1.05rem; }
.st-head p { margin:4px 0 0; color:#94a3b8; font-size:.82rem; }
.st-row { display:grid; grid-template-columns:260px 1fr; gap:16px; padding:16px 22px; border-bottom:1px solid #f8fafc; align-items:center; }
@media (max-width: 768px) { .st-row { grid-template-columns:1fr; gap:6px; } }
.st-row label.st-label { margin:0; font-weight:600; color:#334155; font-size:.88rem; }
.st-help { display:block; font-weight:400; color:#94a3b8; font-size:.76rem; margin-top:2px; }
.st-input { max-width:420px; }
.st-foot { padding:16px 22px; display:flex; justify-content:flex-end; }
.st-switch { position:relative; display:inline-block; width:46px; height:26px; }
.st-switch input { opacity:0; width:0; height:0; }
.st-slider { position:absolute; inset:0; background:#cbd5e1; border-radius:26px; transition:.2s; cursor:pointer; }
.st-slider:before { content:""; position:absolute; width:20px; height:20px; left:3px; top:3px; background:#fff; border-radius:50%; transition:.2s; }
.st-switch input:checked + .st-slider { background:#16a34a; }
.st-switch input:checked + .st-slider:before { transform:translateX(20px); }
</style>

<div class="st-wrap">
    <nav class="st-nav">
        @foreach($groups as $g)
            <a href="{{ route('admin.settings.index', ['group' => $g['key']]) }}" class="{{ $active === $g['key'] ? 'active' : '' }}">
                <i class="{{ $g['icon'] }} fa-fw"></i> {{ $g['label'] }}
            </a>
        @endforeach
    </nav>

    <form method="POST" action="{{ route('admin.settings.update', $active) }}" class="st-card">
        @csrf
        @method('PUT')
        <div class="st-head">
            <h5><i class="{{ $groups[$active]['icon'] }} mr-1"></i> {{ $groups[$active]['label'] }}</h5>
            @if($groups[$active]['description'])<p>{{ $groups[$active]['description'] }}</p>@endif
        </div>

        @forelse($fields as $f)
            @php $old = old($f['name'], is_bool($f['value']) ? (int) $f['value'] : $f['value']); @endphp
            <div class="st-row">
                <label class="st-label" for="f_{{ $f['name'] }}">
                    {{ $f['label'] }}
                    @if($f['help'])<span class="st-help">{{ $f['help'] }}</span>@endif
                </label>
                <div>
                    @if($f['type'] === 'boolean')
                        <input type="hidden" name="{{ $f['name'] }}" value="0">
                        <label class="st-switch mb-0">
                            <input type="checkbox" id="f_{{ $f['name'] }}" name="{{ $f['name'] }}" value="1" {{ $old ? 'checked' : '' }}>
                            <span class="st-slider"></span>
                        </label>
                    @elseif($f['type'] === 'select')
                        <select id="f_{{ $f['name'] }}" name="{{ $f['name'] }}" class="form-control st-input">
                            @foreach($f['options'] as $val => $text)
                                <option value="{{ $val }}" {{ (string) $old === (string) $val ? 'selected' : '' }}>{{ $text }}</option>
                            @endforeach
                        </select>
                    @elseif($f['type'] === 'textarea')
                        <textarea id="f_{{ $f['name'] }}" name="{{ $f['name'] }}" rows="3" class="form-control st-input">{{ $old }}</textarea>
                    @elseif($f['type'] === 'number')
                        <div class="input-group st-input" style="max-width:220px;">
                            <input type="number" id="f_{{ $f['name'] }}" name="{{ $f['name'] }}" value="{{ $old }}" class="form-control"
                                   @if($f['min'] !== null) min="{{ $f['min'] }}" @endif @if($f['max'] !== null) max="{{ $f['max'] }}" @endif>
                            @if($f['suffix'])<div class="input-group-append"><span class="input-group-text">{{ $f['suffix'] }}</span></div>@endif
                        </div>
                    @elseif($f['type'] === 'time')
                        <input type="time" id="f_{{ $f['name'] }}" name="{{ $f['name'] }}" value="{{ $old }}" class="form-control st-input" style="max-width:160px;">
                    @else
                        <input type="text" id="f_{{ $f['name'] }}" name="{{ $f['name'] }}" value="{{ $old }}" class="form-control st-input">
                    @endif
                </div>
            </div>
        @empty
            <div class="p-4 text-muted">ไม่มีค่าตั้งค่าในกลุ่มนี้</div>
        @endforelse

        <div class="st-foot">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> บันทึก</button>
        </div>
    </form>
</div>
@endsection
