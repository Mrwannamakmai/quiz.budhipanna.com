@extends('layouts.teacher')
@section('title','นำเข้าข้อสอบจากไฟล์')
@section('page-title','นำเข้าข้อสอบจากไฟล์ .txt')

@section('content')
<style>
.imp-layout { display:grid; grid-template-columns:1fr 400px; gap:20px; }
@media(max-width:900px) { .imp-layout { grid-template-columns:1fr; } }

.imp-card { background:#fff; border-radius:12px; box-shadow:0 1px 6px rgba(0,0,0,.08); border:1.5px solid #f1f5f9; padding:22px; margin-bottom:20px; }
.imp-card h6 { font-size:.88rem; font-weight:700; color:#1e293b; margin:0 0 16px; padding-bottom:10px; border-bottom:1.5px solid #f1f5f9; }
.imp-card h6 i { color:#3b82f6; margin-right:8px; }

.fg { margin-bottom:14px; }
.fg label { font-size:.8rem; font-weight:600; color:#334155; margin-bottom:4px; display:block; }
.fg .req { color:#ef4444; }
.fg select { width:100%; border:1.5px solid #e2e8f0; border-radius:8px; padding:9px 12px; font-size:.85rem; background:#f8fafc; }
.fg select:focus { outline:none; border-color:#3b82f6; background:#fff; }

/* ─── Drop zone ─── */
.drop-zone { border:2.5px dashed #cbd5e1; border-radius:12px; padding:40px 20px; text-align:center; background:#f8fafc; cursor:pointer; transition:all .2s; position:relative; }
.drop-zone:hover, .drop-zone.drag-over { border-color:#3b82f6; background:#eff6ff; }
.drop-zone .dz-icon { font-size:42px; color:#94a3b8; margin-bottom:12px; }
.drop-zone.drag-over .dz-icon { color:#3b82f6; }
.drop-zone h6 { font-size:.9rem; font-weight:600; color:#475569; margin:0 0 4px; }
.drop-zone p { font-size:.78rem; color:#94a3b8; margin:0; }
.drop-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; }

.file-info { display:flex; align-items:center; gap:12px; background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:10px; padding:12px 16px; margin-top:14px; }
.file-info .fi-icon { font-size:1.3rem; color:#3b82f6; }
.file-info .fi-name { font-size:.85rem; font-weight:600; color:#1e293b; flex:1; }
.file-info .fi-size { font-size:.72rem; color:#94a3b8; }
.file-info .fi-remove { background:none; border:none; color:#ef4444; cursor:pointer; font-size:.9rem; padding:4px; }

/* ─── Preview ─── */
.preview-area { margin-top:16px; }
.preview-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
.preview-head h6 { font-size:.82rem; font-weight:700; color:#1e293b; margin:0; }
.preview-count { font-size:.78rem; color:#3b82f6; font-weight:600; }

.pv-item { background:#f8fafc; border:1.5px solid #f1f5f9; border-radius:10px; padding:14px; margin-bottom:10px; }
.pv-item.error { border-color:#fca5a5; background:#fef2f2; }
.pv-num { font-size:.7rem; font-weight:700; color:#94a3b8; text-transform:uppercase; margin-bottom:6px; }
.pv-text { font-size:.85rem; color:#1e293b; font-weight:500; margin-bottom:6px; }
.pv-opts { display:grid; grid-template-columns:1fr 1fr; gap:4px; margin-bottom:6px; }
.pv-opt { font-size:.78rem; color:#475569; padding:4px 8px; background:#fff; border-radius:6px; border:1px solid #e2e8f0; }
.pv-opt.correct { background:#dcfce7; border-color:#86efac; color:#15803d; font-weight:600; }
.pv-badges { display:flex; gap:6px; flex-wrap:wrap; }
.pv-badge { font-size:.68rem; font-weight:600; padding:2px 8px; border-radius:10px; }
.pv-badge.mc { background:#dbeafe; color:#1d4ed8; }
.pv-badge.es { background:#fef3c7; color:#b45309; }
.pv-badge.pts { background:#ecfdf5; color:#059669; }
.pv-badge.err { background:#fef2f2; color:#dc2626; }

/* ─── Format guide ─── */
.fmt-box { background:#f8fafc; border-radius:10px; padding:16px; border:1.5px solid #f1f5f9; margin-bottom:14px; }
.fmt-box h6 { font-size:.8rem; font-weight:700; color:#334155; margin:0 0 10px; }
.fmt-code { background:#1e293b; color:#e2e8f0; border-radius:8px; padding:14px 16px; font-size:.78rem; line-height:1.7; font-family:'Courier New',monospace; overflow-x:auto; white-space:pre; }
.fmt-code .kw { color:#93c5fd; }
.fmt-code .val { color:#fde68a; }
.fmt-code .sep { color:#64748b; }
.fmt-note { font-size:.72rem; color:#94a3b8; margin-top:8px; line-height:1.5; }
.fmt-note strong { color:#64748b; }

/* ─── Buttons ─── */
.btn-import { background:#1e40af; color:#fff; border:none; border-radius:8px; padding:11px 24px; font-size:.88rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:background .15s; width:100%; justify-content:center; }
.btn-import:hover { background:#1d3a9a; }
.btn-import:disabled { background:#94a3b8; cursor:not-allowed; }
.btn-cancel { background:transparent; color:#64748b; border:1.5px solid #e2e8f0; border-radius:8px; padding:11px 24px; font-size:.88rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; width:100%; justify-content:center; margin-top:8px; }
.btn-cancel:hover { background:#f1f5f9; color:#334155; text-decoration:none; }
</style>

<form method="POST" action="{{ route('teacher.question_bank.import') }}" enctype="multipart/form-data" id="importForm">
@csrf
<div class="imp-layout">

{{-- Left: Upload & Preview --}}
<div>
    <div class="imp-card">
        <h6><i class="fas fa-file-upload"></i>อัปโหลดไฟล์ข้อสอบ</h6>

        <div class="fg">
            <label>หมวดวิชา <span style="font-size:.72rem;color:#94a3b8;">(ไม่บังคับ)</span></label>
            <select name="subject_id" id="subjectSelect">
                <option value="">-- ไม่ระบุหมวด --</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}">
                        {{ $s->name }} ({{ $s->course->name ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="drop-zone" id="dropZone">
            <input type="file" name="txt_file" id="fileInput" accept=".txt">
            <div class="dz-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <h6>ลากไฟล์มาวางที่นี่ หรือคลิกเพื่อเลือกไฟล์</h6>
            <p>รองรับไฟล์ .txt ขนาดไม่เกิน 2MB (สูงสุด 50 ข้อต่อไฟล์)</p>
        </div>

        <div id="fileInfo" style="display:none;" class="file-info">
            <span class="fi-icon"><i class="fas fa-file-alt"></i></span>
            <span class="fi-name" id="fileName"></span>
            <span class="fi-size" id="fileSize"></span>
            <button type="button" class="fi-remove" id="fileRemove" title="ลบไฟล์"><i class="fas fa-times-circle"></i></button>
        </div>

        <div class="preview-area" id="previewArea" style="display:none;">
            <div class="preview-head">
                <h6><i class="fas fa-eye mr-2" style="color:#3b82f6;"></i>ตัวอย่างข้อสอบที่จะนำเข้า</h6>
                <span class="preview-count" id="previewCount"></span>
            </div>
            <div id="previewList"></div>
        </div>
    </div>
</div>

{{-- Right: Format guide & Submit --}}
<div>
    <div class="imp-card">
        <h6><i class="fas fa-info-circle"></i>รูปแบบไฟล์ .txt</h6>

        <div class="fmt-box">
            <h6><i class="fas fa-list-ul mr-1" style="color:#3b82f6;"></i>ข้อสอบปรนัย</h6>
            <div class="fmt-code"><span class="kw">คำถาม:</span> <span class="val">พระพุทธเจ้าตรัสรู้ที่ใด</span>
<span class="kw">ก.:</span> <span class="val">ลุมพินีวัน</span>
<span class="kw">ข.:</span> <span class="val">พุทธคยา</span>
<span class="kw">ค.:</span> <span class="val">สารนาถ</span>
<span class="kw">ง.:</span> <span class="val">กุสินารา</span>
<span class="kw">เฉลย:</span> <span class="val">ข</span>
<span class="kw">คะแนน:</span> <span class="val">1</span>
<span class="sep">---</span></div>
        </div>

        <div class="fmt-box">
            <h6><i class="fas fa-pen-alt mr-1" style="color:#f59e0b;"></i>ข้อสอบอัตนัย</h6>
            <div class="fmt-code"><span class="kw">คำถาม:</span> <span class="val">จงอธิบายอริยสัจ 4</span>
<span class="kw">ประเภท:</span> <span class="val">อัตนัย</span>
<span class="kw">เฉลยอัตนัย:</span> <span class="val">อริยสัจ 4 คือ ...</span>
<span class="kw">คะแนน:</span> <span class="val">10</span>
<span class="sep">---</span></div>
        </div>

        <div class="fmt-note">
            <strong>หมายเหตุ:</strong><br>
            - คั่นแต่ละข้อด้วย <code>---</code> (ขีด 3 ตัว) บนบรรทัดใหม่<br>
            - <strong>เฉลย</strong> รับ: ก, ข, ค, ง หรือ a, b, c, d หรือ 1, 2, 3, 4<br>
            - ถ้าไม่ระบุ <strong>ประเภท</strong> จะเป็นปรนัยอัตโนมัติ<br>
            - ถ้าไม่ระบุ <strong>คะแนน</strong> จะเป็น 1 คะแนน<br>
            - ไฟล์ต้องบันทึกเป็น <strong>UTF-8</strong>
        </div>
    </div>

    <div class="imp-card">
        <button type="submit" class="btn-import" id="btnImport" disabled>
            <i class="fas fa-file-import"></i> นำเข้าข้อสอบ
        </button>
        <a href="{{ route('teacher.question_bank.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-left"></i> กลับคลังข้อสอบ
        </a>
    </div>
</div>

</div>
</form>

@section('scripts')
<script>
(function () {
    var dropZone   = document.getElementById('dropZone');
    var fileInput  = document.getElementById('fileInput');
    var fileInfo   = document.getElementById('fileInfo');
    var fileName   = document.getElementById('fileName');
    var fileSize   = document.getElementById('fileSize');
    var fileRemove = document.getElementById('fileRemove');
    var previewArea = document.getElementById('previewArea');
    var previewList = document.getElementById('previewList');
    var previewCount = document.getElementById('previewCount');
    var btnImport  = document.getElementById('btnImport');
    var subjectSel = document.getElementById('subjectSelect');

    var MAX_QUESTIONS = 50;
    var overLimit = false;

    function updateBtn() {
        btnImport.disabled = !(fileInput.files.length > 0 && !overLimit);
    }
    subjectSel.addEventListener('change', updateBtn);

    // drag & drop
    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });
    dropZone.addEventListener('dragleave', function () {
        dropZone.classList.remove('drag-over');
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFile(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', function () {
        if (this.files.length) handleFile(this.files[0]);
    });

    fileRemove.addEventListener('click', function () {
        fileInput.value = '';
        fileInfo.style.display = 'none';
        dropZone.style.display = '';
        previewArea.style.display = 'none';
        previewList.innerHTML = '';
        updateBtn();
    });

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        return (bytes / 1024).toFixed(1) + ' KB';
    }

    function handleFile(file) {
        if (!file.name.toLowerCase().endsWith('.txt')) {
            alert('กรุณาเลือกไฟล์ .txt เท่านั้น');
            fileInput.value = '';
            return;
        }
        fileName.textContent = file.name;
        fileSize.textContent = formatSize(file.size);
        fileInfo.style.display = 'flex';
        dropZone.style.display = 'none';
        updateBtn();

        var reader = new FileReader();
        reader.onload = function (e) { parsePreview(e.target.result); };
        reader.readAsText(file, 'UTF-8');
    }

    function parsePreview(text) {
        var blocks = text.split(/\n\s*---\s*\n|\n\s*\n/);
        var items = [];
        for (var i = 0; i < blocks.length; i++) {
            var block = blocks[i].trim();
            if (!block) continue;
            items.push(parseTxtBlock(block, i + 1));
        }

        previewArea.style.display = '';
        overLimit = items.length > MAX_QUESTIONS;
        previewCount.textContent = items.length + ' ข้อ' + (overLimit ? ' (เกินกำหนด! สูงสุด ' + MAX_QUESTIONS + ' ข้อ)' : '');
        previewCount.style.color = overLimit ? '#dc2626' : '';
        previewList.innerHTML = '';

        if (overLimit) {
            var warn = document.createElement('div');
            warn.style.cssText = 'background:#fef2f2;border:1.5px solid #fca5a5;border-radius:10px;padding:14px;margin-bottom:12px;color:#dc2626;font-size:.85rem;font-weight:600;text-align:center;';
            warn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>ไฟล์มี ' + items.length + ' ข้อ — สูงสุด ' + MAX_QUESTIONS + ' ข้อต่อไฟล์ กรุณาแบ่งไฟล์';
            previewList.appendChild(warn);
        }

        var showCount = Math.min(items.length, MAX_QUESTIONS);
        for (var j = 0; j < showCount; j++) {
            previewList.appendChild(renderPreviewItem(items[j], j + 1));
        }
        if (items.length > showCount) {
            var more = document.createElement('div');
            more.style.cssText = 'text-align:center;padding:10px;color:#94a3b8;font-size:.82rem;';
            more.textContent = '... และอีก ' + (items.length - showCount) + ' ข้อ (ไม่แสดงเพราะเกินกำหนด)';
            previewList.appendChild(more);
        }
        updateBtn();
    }

    function parseTxtBlock(block, num) {
        var lines = block.split(/\r?\n/);
        var data = { question_text: '', type: 'multiple_choice', options: {}, correct: null, points: 1, essay_answer: '', error: null };
        var currentKey = null, multi = '';

        for (var i = 0; i < lines.length; i++) {
            var line = lines[i];
            var m = line.match(/^(คำถาม|ประเภท|ก\.|ข\.|ค\.|ง\.|เฉลย|คะแนน|เฉลยอัตนัย)\s*[:：]\s*(.*)$/);
            if (m) {
                if (currentKey === 'question_text' && multi) { data.question_text = multi.trim(); multi = ''; }
                if (currentKey === 'essay_answer' && multi) { data.essay_answer = multi.trim(); multi = ''; }

                var key = m[1].trim(), val = m[2].trim();
                switch (key) {
                    case 'คำถาม': currentKey = 'question_text'; multi = val; break;
                    case 'ประเภท':
                        if (/อัตนัย|essay/i.test(val)) data.type = 'essay';
                        currentKey = null; break;
                    case 'ก.': data.options['a'] = val; currentKey = null; break;
                    case 'ข.': data.options['b'] = val; currentKey = null; break;
                    case 'ค.': data.options['c'] = val; currentKey = null; break;
                    case 'ง.': data.options['d'] = val; currentKey = null; break;
                    case 'เฉลย':
                        var ansMap = {'ก':'a','ข':'b','ค':'c','ง':'d','a':'a','b':'b','c':'c','d':'d','1':'a','2':'b','3':'c','4':'d'};
                        data.correct = ansMap[val.toLowerCase()] || null;
                        currentKey = null; break;
                    case 'เฉลยอัตนัย': currentKey = 'essay_answer'; multi = val; break;
                    case 'คะแนน': data.points = Math.max(1, parseInt(val) || 1); currentKey = null; break;
                }
            } else {
                if (currentKey === 'question_text' || currentKey === 'essay_answer') multi += '\n' + line;
            }
        }
        if (currentKey === 'question_text' && multi) data.question_text = multi.trim();
        if (currentKey === 'essay_answer' && multi) data.essay_answer = multi.trim();

        if (!data.question_text) data.error = 'ไม่พบคำถาม';
        else if (data.type === 'multiple_choice' && !data.correct) data.error = 'ปรนัยต้องระบุเฉลย';

        return data;
    }

    function esc(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    function renderPreviewItem(item, num) {
        var div = document.createElement('div');
        div.className = 'pv-item' + (item.error ? ' error' : '');

        var html = '<div class="pv-num">ข้อที่ ' + num + '</div>';
        html += '<div class="pv-text">' + esc(item.question_text || '(ว่าง)') + '</div>';

        if (item.type === 'multiple_choice' && Object.keys(item.options).length > 0) {
            html += '<div class="pv-opts">';
            var labels = { a: 'ก', b: 'ข', c: 'ค', d: 'ง' };
            ['a','b','c','d'].forEach(function (k) {
                if (item.options[k]) {
                    html += '<div class="pv-opt' + (item.correct === k ? ' correct' : '') + '">' +
                            labels[k] + '. ' + esc(item.options[k]) + '</div>';
                }
            });
            html += '</div>';
        }

        if (item.type === 'essay' && item.essay_answer) {
            html += '<div style="font-size:.78rem;color:#64748b;background:#fff;padding:6px 10px;border-radius:6px;border:1px solid #e2e8f0;margin-bottom:6px;">' +
                    '<strong>เฉลย:</strong> ' + esc(item.essay_answer.substring(0, 100)) +
                    (item.essay_answer.length > 100 ? '...' : '') + '</div>';
        }

        html += '<div class="pv-badges">';
        html += '<span class="pv-badge ' + (item.type === 'essay' ? 'es' : 'mc') + '">' +
                (item.type === 'essay' ? 'อัตนัย' : 'ปรนัย') + '</span>';
        html += '<span class="pv-badge pts">' + item.points + ' คะแนน</span>';
        if (item.error) html += '<span class="pv-badge err"><i class="fas fa-exclamation-triangle mr-1"></i>' + esc(item.error) + '</span>';
        html += '</div>';

        div.innerHTML = html;
        return div;
    }
})();
</script>
@endsection
@endsection
