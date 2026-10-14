<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบสมัครสอบธรรมศึกษา - {{ $registration->fullName() }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: 'Sarabun', Tahoma, sans-serif; padding: 24px; }
        .doc-title { text-align:center; font-weight:700; font-size: 1.4rem; margin-bottom: 4px; }
        .doc-sub { text-align:center; color:#555; margin-bottom: 20px; }
        table.info td { padding: 6px 8px; vertical-align: top; }
        .label { color:#555; width: 180px; display:inline-block; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print text-right mb-3">
        <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> พิมพ์</button>
    </div>

    <div class="doc-title">ใบสมัครสอบธรรมศึกษา</div>
    <div class="doc-sub">{{ $schoolName }}</div>
    <div class="text-right mb-2">เลขที่อ้างอิง: <strong>{{ $registration->reference_code }}</strong></div>

    <table class="table table-borderless info">
        <tr>
            <td><span class="label">ชื่อ-สกุล</span> {{ $registration->fullName() }}</td>
            <td><span class="label">เลขประจำตัวประชาชน</span> {{ $registration->citizen_id }}</td>
        </tr>
        <tr>
            <td><span class="label">วันเกิด</span> {{ $registration->dob_day }}/{{ $registration->dob_month }}/{{ $registration->dob_year_be }}</td>
            <td><span class="label">อายุ</span> {{ $registration->age }}</td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">ที่อยู่</span> {{ $registration->address_no }} หมู่ {{ $registration->moo }} ต.{{ $registration->subdistrict }} อ.{{ $registration->district }} จ.{{ $registration->province }} {{ $registration->postal_code }}</td>
        </tr>
        <tr>
            <td><span class="label">ระดับการศึกษา</span> {{ $registration->education_level }}</td>
            <td><span class="label">สถานศึกษา/สำนักเรียน</span> {{ $registration->office_name }}</td>
        </tr>
        <tr>
            <td><span class="label">สมัครสอบชั้น</span> {{ $registration->target_level }} / {{ $registration->target_range }}</td>
            <td><span class="label">รอบสอบ</span> {{ optional($registration->examSession)->name }}</td>
        </tr>
        <tr>
            <td><span class="label">สถานที่สอบ</span> {{ optional($registration->examCenter)->name }}</td>
            <td><span class="label">สถานะ</span> {{ $registration->statusLabel() }}</td>
        </tr>
    </table>

    <div class="mt-5 row">
        <div class="col-6 text-center">
            <div>ลงชื่อ ....................................... ผู้สมัคร</div>
        </div>
        <div class="col-6 text-center">
            <div>ลงชื่อ ....................................... เจ้าหน้าที่รับสมัคร</div>
        </div>
    </div>
</body>
</html>
