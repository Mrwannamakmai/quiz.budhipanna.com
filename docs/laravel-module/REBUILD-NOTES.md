# บันทึกการสร้างใหม่โมดูล "ลงทะเบียนสอบธรรมศึกษา" (srd_ prefix)

วันที่ดำเนินการ: 2026-10-07

โมดูลนี้ถูกสร้างใหม่ทั้งหมดตามสเปกเดิมของ srd-app (Next.js/Google Sheets) โดยย้ายมาอยู่บน Laravel 5.8
เป็นชุดตารางใหม่ที่มีคำนำหน้า `srd_` (`srd_exam_centers`, `srd_exam_sessions`, `srd_exam_registrations`,
`srd_exam_followups`) **แยกเป็นอิสระโดยสมบูรณ์จากตารางเดิม** `exam_sessions`/`exam_centers`/`exam_registrations`
ตามคำสั่งผู้ใช้ว่า "ห้ามยุ่งตารางเก่า" — ดูหัวข้อ "โมดูลใหม่แยกจากของเดิมโดยสมบูรณ์" ด้านล่าง

## ไฟล์ที่สร้างใหม่
- `database/migrations/2026_10_07_000001_create_srd_exam_registration_tables.php`
  สร้างเฉพาะตาราง `srd_exam_centers`, `srd_exam_sessions`, `srd_exam_registrations`, `srd_exam_followups`
  (แต่ละตารางมี guard `Schema::hasTable` ทำให้รันซ้ำได้อย่างปลอดภัย) **ไม่แตะตารางเดิมเลย**
- `app/Modules/ExamRegistration/Models/SrdExamCenter.php`
- `app/Modules/ExamRegistration/Models/SrdExamSession.php`
- `app/Modules/ExamRegistration/Models/SrdExamRegistration.php`
- `app/Modules/ExamRegistration/Models/SrdExamFollowUp.php`
- `app/Modules/ExamRegistration/Http/Requests/PublicExamRegistrationRequest.php`
- `app/Modules/ExamRegistration/Http/Requests/FollowUpRequest.php`
- `app/Modules/ExamRegistration/Http/Controllers/PublicExamRegistrationController.php`
- `app/Http/Controllers/Teacher/ExamFollowUpController.php`
- `app/Http/Controllers/Teacher/ExamPrintController.php`
- `app/Http/Controllers/Teacher/ExamReportController.php`
- `app/Http/Controllers/Teacher/ExamPeriodSettingsController.php`
- Views ภายใต้ `app/Modules/ExamRegistration/Views/` (namespace `examregistration::...`):
  `public/create.blade.php`, `public/success.blade.php`, `public/closed.blade.php`,
  `teacher/index.blade.php`, `teacher/show.blade.php`,
  `teacher/followup_index.blade.php`, `teacher/followup_edit.blade.php`,
  `teacher/print_index.blade.php`, `teacher/print_show.blade.php`, `teacher/reports.blade.php`,
  `admin/period_settings.blade.php`
- `public/data/thai-address.json` — ข้อมูลจังหวัด/อำเภอ/ตำบล/รหัสไปรษณีย์ (77 จังหวัด) ใช้กับฟอร์มสมัครแบบ cascading select

## ไฟล์ที่แก้ไข (ทับของเดิมทั้งไฟล์)
- `app/Http/Controllers/Teacher/ExamRegistrationController.php` — เขียนใหม่ทั้งหมด: `index()` (รายการ+ค้นหา+กรองสถานะ),
  `show()`, `approve()`/`reject()` (เฉพาะ superadmin, ตรวจสิทธิ์ซ้ำในคอนโทรลเลอร์ด้วย `abort_unless`)
- `app/Modules/ExamRegistration/routes.php` — เพิ่ม route กลุ่มสาธารณะ (`exam_registration.public.*`)
  และกลุ่มเจ้าหน้าที่ (`teacher.exam_registration.*`: index/show/approve/reject/followup/print/reports/settings)
- `app/Modules/ExamRegistration/ExamRegistrationServiceProvider.php` — เพิ่ม `loadViewsFrom` และการโหลด `routes.php`
  จริง (ของเดิมไม่มีการโหลด routes.php เลย ดูหัวข้อ "ข้อสังเกต/บั๊กที่พบ" ด้านล่าง) เพิ่มฟิลด์ตั้งค่าโหมด/ช่วงเวลารับสมัคร
  และข้อความ QR Code, แก้เมนูให้ชี้ไปที่ route ใหม่ `teacher.exam_registration.index`

## การตัดสินใจเรื่องสิทธิ์ (role mapping)
แอปนี้มีแค่ 3 สิทธิ์ (superadmin/teacher/student) จึงแมปจากสเปกเดิม (5 สิทธิ์) ดังนี้:
- อนุมัติ/ไม่อนุมัติใบสมัคร และตั้งค่าช่วงเวลา/QR → **superadmin เท่านั้น**
- ดูรายงาน, บันทึกการติดตาม (follow-up), พิมพ์ใบสมัคร → **superadmin + teacher**
- หน้าสมัครสอบสาธารณะ → **ไม่ต้อง login** (เหมือนสเปกเดิม)
- สิทธิ์ "student พิมพ์ได้" ในสเปกเดิม **ไม่ย้ายมาใช้** เพราะ `student` ในแอปนี้คือนักเรียนที่มีบัญชีในระบบจริง
  ไม่ใช่เจ้าหน้าที่พิมพ์เอกสารแบบในสเปกเดิม — จึงไม่ให้สิทธิ์พิเศษกับ role นี้ในโมดูลนี้

## โมดูลใหม่แยกจากของเดิมโดยสมบูรณ์ (ไม่แตะตารางเก่า)
ตามคำสั่งล่าสุดของผู้ใช้ (ห้ามยุ่งตารางเก่า) migration นี้สร้าง **เฉพาะ** ตาราง `srd_exam_centers`,
`srd_exam_sessions`, `srd_exam_registrations`, `srd_exam_followups` เท่านั้น (แต่ละตารางมี guard
`if (!Schema::hasTable(...))` ทำให้รันซ้ำได้อย่างปลอดภัย) **ไม่มีโค้ดส่วนใดที่อ่าน ย้ายข้อมูล หรือลบ**
ตารางเดิม `exam_centers`, `exam_sessions`, `exam_registrations` อีกต่อไป — ตารางเดิมเหล่านี้ รวมถึงโมเดลเดิม
`App\ExamRegistration`, `App\ExamSession`, `App\ExamCenter` และคอนโทรลเลอร์เดิมที่ใช้งานอยู่
(`App\Http\Controllers\ExamAnnouncementController`, `App\Http\Controllers\Admin\ExamSessionController`,
และ route เดิม `exam_registration.index`/`.store` ใน `routes/web.php`) **ไม่ถูกแก้ไขหรือแตะต้องเลย**
ทั้งสองระบบ (เก่าและใหม่) อยู่คู่กันได้โดยอิสระ ไม่ใช้ตารางหรือโมเดลร่วมกัน

## ℹ️ ฟีเจอร์เดิมที่ยังใช้ตารางเก่า (ไม่ได้แก้ไข ไม่ได้รับผลกระทบ)
ระหว่างสำรวจโค้ด พบว่าแอปนี้มีฟีเจอร์เดิมที่อ้างอิงตาราง/โมเดลเดิมอยู่:
- `app/Http/Controllers/ExamAnnouncementController.php` (route `exam_announcements.index`, `exam_registrations.live`)
- `app/Http/Controllers/Admin/ExamSessionController.php` (route `exam_sessions.*` ในแอดมิน)
- route เดิมใน `routes/web.php` ชื่อ `exam_registration.index`/`exam_registration.store` (กลุ่ม teacher/student ลงทะเบียนแบบเดิม)
- โมเดลเดิม `App\ExamSession`, `App\ExamCenter`, `App\ExamRegistration`

**ไม่ได้แก้ไขไฟล์เหล่านี้เลย และเนื่องจาก migration ใหม่ไม่แตะตารางเดิมแล้ว (ตามคำสั่งผู้ใช้) ฟีเจอร์เหล่านี้
ยังทำงานได้ตามปกติเหมือนก่อนหน้านี้ทุกประการ** ไม่มีสิ่งใดในงานรอบนี้ไปแก้ไขให้ไปอ้างอิงตารางเก่าในทางที่ทำลายข้อมูล
หรือเปลี่ยนพฤติกรรมของไฟล์เหล่านี้ — ระบบเก่า (ตาราง non-prefixed + คอนโทรลเลอร์ข้างต้น) และระบบใหม่
(ตาราง `srd_*` + โมดูล ExamRegistration ที่สร้างรอบนี้) อยู่คู่กันแบบเป็นอิสระจากกันอย่างสมบูรณ์

นอกจากนี้ ServiceProvider เดิมของโมดูลนี้ **ไม่ได้โหลด** `app/Modules/ExamRegistration/routes.php` เลย
(ไม่มีการเรียก `Route::group` ใน `boot()`) ทำให้ routes.php เดิมเป็นโค้ดที่ไม่ถูกใช้งานจริงมาตลอด — ได้แก้ไขจุดนี้
ในรอบนี้ให้โหลดจริงตามรูปแบบเดียวกับโมดูล Attendance (จุดนี้ไม่กระทบตารางเก่าแต่อย่างใด เป็นแค่การโหลด route)

## ที่มาของข้อมูลที่อยู่ไทย
ลองดึงจาก `kongvut/thai-province-data` หลาย URL แล้ว 404 ทั้งหมด จึงใช้ข้อมูลจาก repository
`thailand-geography-data/thailand-geography-json` (ไฟล์ `src/geography.json`, 7,436 แถวระดับตำบล)
แปลงเป็นโครงสร้าง nested (จังหวัด→อำเภอ→ตำบล+รหัสไปรษณีย์) แล้วบันทึกไว้ที่ `public/data/thai-address.json`
(77 จังหวัด, ขนาดไฟล์ ~596KB) หน้าสมัครสอบสาธารณะใช้ JS fetch ไฟล์นี้ครั้งเดียวแล้ว cascading select ฝั่ง client
ไม่มีการเพิ่ม composer/npm package ใด ๆ

## QR Code
สร้างด้วยไลบรารี JS `qrcodejs` (โหลดจาก CDN `cdnjs.cloudflare.com`) วาดผลลงใน `<canvas>` พร้อมหัวข้อ/ข้อความรอง
ที่ตั้งค่าได้ ดาวน์โหลดเป็น PNG ด้วย `canvas.toDataURL()` — ไม่มีการเพิ่ม composer package ฝั่ง backend

## หมายเหตุอื่น ๆ
- ฟอร์มสมัครสอบสาธารณะทำเป็นหน้าเดียวแบ่งเป็นหัวข้อ (section) แทนการทำ wizard หลายหน้าจริง ๆ ตามสเปก UX เดิม
  เพื่อความเร็วในการ implement — ฟังก์ชันครบตามฟิลด์ข้อมูลแล้ว แต่ยังไม่ใช่ multi-step wizard พร้อม progress bar
- หน้า "ตั้งค่า > ระบบ" แบบอัตโนมัติของแอป (ที่ auto-gen ฟอร์มจาก SettingsRegistry) จะเห็นฟิลด์ใหม่ทั้งหมดด้วย
  (โหมดรับสมัคร, วันเริ่ม/สิ้นสุด, ข้อความ QR) เพราะลงทะเบียนผ่าน SettingsRegistry เดียวกัน — หน้า
  ExamPeriodSettingsController ที่สร้างใหม่เป็นหน้าเสริมที่มี QR preview/download ให้โดยเฉพาะ ค่าที่บันทึก
  จะ sync กันเสมอเพราะเขียนไปที่ key ตั้งค่าเดียวกัน

## คำสั่งที่ต้องรันเองบนเครื่อง (cmd/PowerShell)
```
cd C:\laragon\www\quiz.budhipanna\myapp
composer dump-autoload
php artisan migrate
php artisan route:list | findstr exam_registration
php artisan config:clear
php artisan cache:clear
```
จากนั้นทดสอบ:
1. เปิด `/exam-registration` (หน้าสมัครสอบสาธารณะ) — กรอกและส่งใบสมัคร ตรวจว่าได้เลขอ้างอิง
2. เข้าสู่ระบบด้วยบัญชี teacher หรือ superadmin แล้วเปิดเมนู "ลงทะเบียนสอบธรรมศึกษา" ตรวจรายการที่สมัครมา
3. ด้วยบัญชี superadmin: ลองกดอนุมัติ/ไม่อนุมัติ 1 รายการ, เปิดหน้า "ตั้งค่า/QR" ตรวจว่า QR Code แสดงและดาวน์โหลดได้
4. ตรวจหน้า "ติดตามผู้สมัคร", "พิมพ์ใบสมัคร" (กด Ctrl+P ดู preview), "รายงาน" ว่าตารางสรุปแสดงถูกต้อง
5. ตรวจว่าหน้า `/exam-announcements`, เมนูแอดมิน "รอบสอบธรรมศึกษา" (Admin\ExamSessionController) และ
   เมนูลงทะเบียนแบบเดิม (ตาราง non-prefixed) ยังทำงานได้ตามปกติเหมือนก่อน migrate ทุกประการ
   เนื่องจาก migration นี้ไม่แตะตารางเดิมเลย จึงไม่ควรมีผลกระทบใด ๆ ต่อฟีเจอร์เดิม
