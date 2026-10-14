@extends(auth()->user()->isSuperAdmin() ? 'layouts.admin' : 'layouts.teacher')
@section('title', 'เช็คชื่อคาบใหม่')
@section('page-title', 'เช็คชื่อคาบใหม่')

@section('content')
@include('attendance::_styles')
<div class="at-top">
    <h5><i class="fas fa-plus-circle mr-1"></i> สร้างคาบเรียนเพื่อเช็คชื่อ</h5>
    <a href="{{ route('attendance.teacher.index') }}" class="at-btn ghost"><i class="fas fa-arrow-left"></i> กลับ</a>
</div>

<form method="POST" action="{{ route('attendance.teacher.sessions.store') }}" class="at-card at-card-pad" style="max-width:720px;">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="font-weight-bold">ห้องเรียน <span class="text-danger">*</span></label>
            <select name="classroom_id" class="form-control" required>
                <option value="">— เลือกห้อง —</option>
                @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ (int) old('classroom_id', $classroomId) === (int) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label class="font-weight-bold">วิชา <span class="at-muted">(ไม่บังคับ)</span></label>
            <select name="subject_id" class="form-control">
                <option value="">— ไม่ระบุ —</option>
                @foreach($subjects->groupBy(function ($s) { return optional($s->course)->name ?: 'อื่น ๆ'; }) as $course => $list)
                    <optgroup label="{{ $course }}">
                        @foreach($list as $s)
                            <option value="{{ $s->id }}" {{ (int) old('subject_id') === (int) $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label class="font-weight-bold">วันที่ <span class="text-danger">*</span></label>
            <input type="date" name="session_date" value="{{ old('session_date', $today) }}" class="form-control" required>
        </div>
        <div class="form-group col-md-3">
            <label class="font-weight-bold">เวลาเริ่มคาบ</label>
            <input type="time" name="start_time" value="{{ old('start_time', $nowTime) }}" class="form-control">
        </div>
        <div class="form-group col-md-5">
            <label class="font-weight-bold">ชื่อคาบ <span class="at-muted">(เช่น คาบ 1, ภาคเช้า)</span></label>
            <input type="text" name="title" value="{{ old('title') }}" maxlength="150" class="form-control">
        </div>
        <div class="form-group col-12">
            <label class="font-weight-bold">หมายเหตุ</label>
            <textarea name="note" rows="2" class="form-control">{{ old('note') }}</textarea>
        </div>
    </div>
    @if(\App\Support\Settings\Settings::get('attendance.self_checkin', true))
        <div class="custom-control custom-checkbox mb-3">
            <input type="checkbox" class="custom-control-input" id="open_checkin" name="open_checkin" value="1" {{ old('open_checkin') ? 'checked' : '' }}>
            <label class="custom-control-label" for="open_checkin">เปิดรหัสให้นักเรียนเช็คชื่อด้วยตนเองทันที
                ({{ \App\Support\Settings\Settings::get('attendance.checkin_window', 15) }} นาที)</label>
        </div>
    @endif
    <button type="submit" class="at-btn"><i class="fas fa-arrow-right"></i> สร้างและเริ่มเช็คชื่อ</button>
</form>
@endsection
