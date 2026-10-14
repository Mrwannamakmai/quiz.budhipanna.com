<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายชื่อผู้สมัครสอบ - {{ $session->academic_year }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; font-size: 14px; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
        .text-nowrap { white-space: nowrap; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">พิมพ์หน้านี้ (Print PDF)</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">ปิดหน้าต่าง</button>
    </div>

    <h2 class="text-center mb-2">รายชื่อผู้สมัครสอบธรรมศึกษา ปีการศึกษา {{ $session->academic_year }}</h2>
    <p class="text-center mb-4">
        ผู้พิมพ์: {{ Auth::user()->name }} | วันที่พิมพ์: {{ date('d/m/Y H:i') }}<br>
        @if(request('tier')) ชั้น: {{ ['tree'=>'ตรี','tho'=>'โท','ek'=>'เอก'][request('tier')] }} @endif
    </p>

    <table>
        <thead>
            <tr>
                <th class="text-center">ลำดับ</th>
                <th>ชื่อ - นามสกุล</th>
                <th>เลขประจำตัวประชาชน</th>
                <th>ชั้นที่สอบ</th>
                <th>ระดับชั้นเรียน</th>
                <th>สนามสอบ</th>
                <th>สถานะสมัคร</th>
                <th>ผลติดตาม</th>
                <th>หมายเหตุ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $i => $reg)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-nowrap">{{ $reg->title }}{{ $reg->first_name }} {{ $reg->last_name }}</td>
                    <td class="text-nowrap">{{ $reg->national_id }}</td>
                    <td class="text-center">{{ ['tree'=>'ตรี','tho'=>'โท','ek'=>'เอก'][$reg->exam_tier] ?? $reg->exam_tier }}</td>
                    <td>{{ $reg->education_class }}</td>
                    <td>{{ $reg->examCenter->name ?? '-' }}</td>
                    <td>{{ \App\ExamRegistration::STATUSES[$reg->status] ?? $reg->status }}</td>
                    <td>{{ $reg->follow_up_status ?: '-' }}</td>
                    <td>{{ $reg->follow_up_note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            // setTimeout(function() { window.print(); }, 500);
        };
    </script>
</body>
</html>
