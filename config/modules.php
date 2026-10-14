<?php

/*
|--------------------------------------------------------------------------
| โมดูลของระบบ
|--------------------------------------------------------------------------
| เพิ่มโมดูลใหม่: สร้างโฟลเดอร์ app/Modules/<ชื่อ> ที่มี ServiceProvider
| แล้วเพิ่มบรรทัดในรายการด้านล่าง (key => ServiceProvider)
| การเปิด/ปิดโมดูลทำได้ที่หน้า แอดมิน > ตั้งค่าระบบ (ค่า <key>.enabled)
*/

return [
    'attendance' => App\Modules\Attendance\AttendanceServiceProvider::class,
    'exam_registration' => App\Modules\ExamRegistration\ExamRegistrationServiceProvider::class,
];
