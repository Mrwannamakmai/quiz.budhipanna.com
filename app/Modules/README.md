# ระบบโมดูล (app/Modules)

แต่ละโมดูลอยู่ในโฟลเดอร์ของตัวเอง ไม่ต้องแก้ไฟล์หลักของระบบ ยกเว้นเพิ่ม 1 บรรทัดใน `config/modules.php`

```
app/Modules/<ชื่อโมดูล>/
├── <ชื่อ>ServiceProvider.php   ลงทะเบียน views / routes / migrations / settings / เมนู
├── routes.php                   routes ของโมดูล (ใส่ middleware 'module:<key>' เพื่อปิดได้จากหน้าตั้งค่า)
├── Http/Controllers/
├── Models/
├── Support/                     ตรรกะกลางของโมดูล
├── Views/                       เรียกใช้ด้วย view('<key>::ชื่อไฟล์')
└── Database/Migrations/         php artisan migrate จะรวมให้อัตโนมัติ
```

## ระบบตั้งค่า (Settings)
- ตาราง `settings` (key / value) อ่านผ่าน `App\Support\Settings\Settings::get('group.key', ค่าเริ่มต้น)`
- ลงทะเบียนฟิลด์ใน ServiceProvider ของโมดูล:

```php
app(\App\Support\Settings\SettingsRegistry::class)->group('mymodule', [
    'label' => 'ชื่อที่แสดง', 'icon' => 'fas fa-cog', 'order' => 30,
], [
    ['key' => 'mymodule.enabled', 'label' => 'เปิดใช้งาน', 'type' => 'boolean', 'default' => true],
    ['key' => 'mymodule.limit',   'label' => 'จำนวนสูงสุด', 'type' => 'number', 'default' => 10, 'min' => 1, 'suffix' => 'รายการ'],
    ['key' => 'mymodule.mode',    'label' => 'โหมด', 'type' => 'select', 'default' => 'a', 'options' => ['a' => 'แบบ A', 'b' => 'แบบ B']],
]);
```
ชนิดฟิลด์: `text`, `textarea`, `number`, `boolean`, `select`, `time`
หน้า **แอดมิน > ตั้งค่าระบบ** จะสร้างแท็บและฟอร์มให้อัตโนมัติ — ค่า `<key>.enabled` ใช้เปิด/ปิดโมดูล

## เมนู
```php
app(\App\Support\Modules\ModuleRegistry::class)
    ->add('mymodule', ['label' => 'โมดูลของฉัน'])
    ->menu('teacher', ['module' => 'mymodule', 'section' => 'หมวด', 'label' => 'เมนู', 'icon' => 'fas fa-star', 'route' => 'mymodule.index']);
```
พื้นที่เมนู: `teacher`, `admin`, `student` (เมนูถูกซ่อนเองเมื่อปิดโมดูล)

## โมดูลที่มี
| key | ชื่อ | ตาราง |
|---|---|---|
| attendance | เช็คชื่อเข้าเรียน | attendance_sessions, attendance_records |
| exam_registration | ลงทะเบียนสอบธรรมศึกษา | exam_sessions, exam_centers, exam_registrations |

ผู้สมัครสอบธรรมศึกษาเก็บเป็นระเบียนใน `exam_registrations` โดยไม่ต้องมีบัญชีนักเรียนในระบบ และบันทึกชั้นสอบที่เลือกไว้ในแต่ละระเบียน

ผู้ดูแลจัดการรอบสอบและเผยแพร่ประกาศได้ที่ **แอดมิน > รอบสอบธรรมศึกษา** ส่วนประกาศที่เผยแพร่จะแสดงที่ `/exam-announcements` พร้อมรายละเอียด กำหนดการ และสถานะรับสมัคร

รายชื่อผู้สมัครที่ผู้ดูแลเผยแพร่จะแสดงบนหน้า Landing พร้อมชั้นสอบและสถานะที่ผู้ดูแลปรับได้ โดยไม่เปิดเผยเลขประจำตัวประชาชนหรือวันเกิด
สถานะผู้สมัครมี 4 ขั้น: ลงทะเบียนแล้ว, ตรวจสอบแล้ว, มีสิทธิ์สอบ และขาดสอบ
