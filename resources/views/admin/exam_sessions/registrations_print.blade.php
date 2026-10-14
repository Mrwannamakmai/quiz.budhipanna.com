<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายชื่อผู้สมัครสอบธรรมศึกษา — ปีการศึกษา {{ $session->academic_year }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 10mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Sarabun', 'TH SarabunPSK', 'Tahoma', sans-serif;
            font-size: 11px;
            color: #222;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            font-size: 13px;
            color: #555;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        th, td {
            border: 1px solid #999;
            padding: 3px 5px;
            text-align: left;
            white-space: nowrap;
        }
        th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        td.center { text-align: center; }
        tr:nth-child(even) { background: #fafafa; }
        .no-print { margin: 16px 0; text-align: center; }
        .no-print button {
            padding: 10px 28px;
            font-size: 15px;
            cursor: pointer;
            border: none;
            border-radius: 6px;
            color: #fff;
            margin: 0 6px;
        }
        .btn-print { background: #007bff; }
        .btn-back { background: #6c757d; }
        @media print {
            .no-print { display: none; }
            body { font-size: 10px; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="btn-print" onclick="window.print()">
        &#128424; สั่งพิมพ์ / บันทึก PDF
    </button>
    <button class="btn-back" onclick="history.back()">
        &#8592; กลับ
    </button>
</div>

<div class="header">
    <h1>รายชื่อผู้สมัครสอบธรรมศึกษา</h1>
    <p>
        ปีการศึกษา {{ $session->academic_year }}
        @if($session->exam_date)
            &middot; วันสอบ {{ $session->exam_date->format('d/m/') }}{{ $session->exam_date->year + 543 }}
        @endif
    </p>
</div>

<div class="summary">
    <span>จำนวนผู้สมัครทั้งหมด: <strong>{{ $registrations->count() }}</strong> คน</span>
    <span>
        @php
            $tierCounts = $registrations->groupBy('exam_tier')->map->count();
        @endphp
        ตรี: {{ $tierCounts->get('tree', 0) }}
        &middot; โท: {{ $tierCounts->get('tho', 0) }}
        &middot; เอก: {{ $tierCounts->get('ek', 0) }}
    </span>
    <span>วันที่พิมพ์: {{ date('d/m/') . (date('Y') + 543) }}</span>
</div>

@php
    $tierLabels = ['tree' => 'ตรี', 'tho' => 'โท', 'ek' => 'เอก'];
    $statusLabels = \App\ExamRegistration::STATUSES;
@endphp

<table>
    <thead>
        <tr>
            <th>ลำดับ</th>
            <th>รหัสอ้างอิง</th>
            <th>คำนำหน้า</th>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>เลขประจำตัว</th>
            <th>อายุ</th>
            <th>เบอร์โทร</th>
            <th>จังหวัด</th>
            <th>ระดับการศึกษา</th>
            <th>ชั้นสอบ</th>
            <th>เคยสอบผ่าน</th>
            <th>เลขที่ ป.น.บ.</th>
            <th>สำนักเรียน</th>
            <th>สนามสอบ</th>
            <th>ช่องทาง</th>
            <th>สถานะ</th>
            <th>วันที่สมัคร</th>
        </tr>
    </thead>
    <tbody>
        @forelse($registrations as $i => $r)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $r->reference_code }}</td>
                <td>{{ $r->title }}</td>
                <td>{{ $r->first_name }}</td>
                <td>{{ $r->last_name }}</td>
                <td>{{ $r->national_id }}</td>
                <td class="center">{{ $r->age }}</td>
                <td>{{ $r->phone }}</td>
                <td>{{ $r->province }}</td>
                <td>{{ $r->education_level ?: $r->education_class }}</td>
                <td class="center">{{ $tierLabels[$r->exam_tier] ?? $r->exam_tier }}</td>
                <td class="center">{{ $r->has_previous_cert ? $r->previous_level : '-' }}</td>
                <td>{{ $r->previous_certificate_no ?: '-' }}</td>
                <td>{{ $r->office_name ?: '-' }}</td>
                <td>{{ $r->examCenter->name ?? '-' }}</td>
                <td class="center">{{ $r->registration_channel === 'public' ? 'สาธารณะ' : 'ระบบ' }}</td>
                <td class="center">{{ $statusLabels[$r->status] ?? $r->status }}</td>
                <td>{{ $r->created_at ? $r->created_at->format('d/m/Y') : '' }}</td>
            </tr>
        @empty
            <tr><td colspan="18" style="text-align:center; padding:20px;">ยังไม่มีผู้สมัคร</td></tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
