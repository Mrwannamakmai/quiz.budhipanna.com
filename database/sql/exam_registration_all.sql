-- ============================================================================
-- SQL สำหรับระบบสมัครสอบธรรมศึกษา (ครบทุกตาราง)
-- รันตามลำดับ — ถ้าตารางมีอยู่แล้วข้ามส่วนนั้นได้
-- ============================================================================

-- ──────────────────────────────────────────────────────────────────────────────
-- 1) ตาราง exam_sessions (รอบสอบ)
-- ──────────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `exam_sessions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `academic_year` VARCHAR(20) NOT NULL COMMENT 'ปีการศึกษา เช่น 2569',
    `exam_date` DATE NULL COMMENT 'วันสอบ',
    `status` VARCHAR(20) NOT NULL DEFAULT 'draft' COMMENT 'draft|open|closed|completed',
    `details` TEXT NULL COMMENT 'รายละเอียดรอบสอบ',
    `schedule` TEXT NULL COMMENT 'กำหนดการ',
    `announcement` TEXT NULL COMMENT 'ประกาศรอบสอบ',
    `notebooklm_url` VARCHAR(1000) NULL COMMENT 'ลิงก์ NotebookLM',
    `is_published` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'เผยแพร่ประกาศ',
    `registration_mode` VARCHAR(20) NOT NULL DEFAULT 'auto' COMMENT 'auto|force_open|force_closed',
    `registration_start_date` DATE NULL COMMENT 'วันเริ่มรับสมัครสาธารณะ',
    `registration_end_date` DATE NULL COMMENT 'วันสิ้นสุดรับสมัครสาธารณะ',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ──────────────────────────────────────────────────────────────────────────────
-- 2) ตาราง exam_centers (สนามสอบ)
-- ──────────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `exam_centers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ──────────────────────────────────────────────────────────────────────────────
-- 3) ตาราง exam_registrations (ข้อมูลผู้สมัครสอบ)
-- ──────────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `exam_registrations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reference_code` VARCHAR(30) NULL COMMENT 'รหัสอ้างอิง REG-YYYYMMDD-XXXXXXXX',
    `exam_session_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK → exam_sessions',
    `classroom_id` BIGINT UNSIGNED NULL COMMENT 'FK → classrooms (สมัครผ่านระบบ login)',
    `created_by` BIGINT UNSIGNED NULL COMMENT 'FK → users (ครูที่สมัครให้)',
    `exam_center_id` BIGINT UNSIGNED NULL COMMENT 'FK → exam_centers',

    -- ข้อมูลผู้สมัคร (ขั้นตอนที่ 1)
    `national_id` VARCHAR(13) NOT NULL COMMENT 'เลขประจำตัวประชาชน 13 หลัก',
    `title` VARCHAR(50) NOT NULL COMMENT 'คำนำหน้า: เด็กชาย/เด็กหญิง/นาย/นาง/นางสาว',
    `first_name` VARCHAR(255) NOT NULL COMMENT 'ชื่อ',
    `last_name` VARCHAR(255) NOT NULL COMMENT 'นามสกุล',
    `birth_date` DATE NOT NULL COMMENT 'วันเกิด (ค.ศ.)',
    `birth_day` TINYINT UNSIGNED NULL COMMENT 'วันเกิด (1-31)',
    `birth_month` TINYINT UNSIGNED NULL COMMENT 'เดือนเกิด (1-12)',
    `birth_year_be` SMALLINT UNSIGNED NULL COMMENT 'ปีเกิด พ.ศ.',
    `age` TINYINT UNSIGNED NULL COMMENT 'อายุ (ปี)',
    `phone` VARCHAR(20) NULL COMMENT 'เบอร์โทรศัพท์',
    `line_id` VARCHAR(100) NULL COMMENT 'LINE ID',

    -- ที่อยู่ (ขั้นตอนที่ 2)
    `house_number` VARCHAR(100) NULL COMMENT 'บ้านเลขที่/ที่อยู่',
    `sub_district` VARCHAR(100) NULL COMMENT 'ตำบล/แขวง',
    `district` VARCHAR(100) NULL COMMENT 'อำเภอ/เขต',
    `province` VARCHAR(100) NULL COMMENT 'จังหวัด',
    `postal_code` VARCHAR(5) NULL COMMENT 'รหัสไปรษณีย์',
    `education_class` VARCHAR(50) NOT NULL COMMENT 'ระดับการศึกษา (ใช้ร่วมกับระบบเดิม)',
    `education_level` VARCHAR(50) NULL COMMENT 'ระดับการศึกษา (ป.4-ม.6/ปริญญาตรี/ประชาชนทั่วไป)',

    -- ชั้นสอบ
    `exam_tier` VARCHAR(10) NOT NULL COMMENT 'tree|tho|ek',

    -- ประวัติเดิม (ขั้นตอนที่ 3)
    `has_previous_cert` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'เคยสอบได้ธรรมศึกษามาก่อน',
    `previous_level` VARCHAR(20) NULL COMMENT 'ชั้นเดิมที่สอบได้: ตรี/โท/เอก',
    `previous_period` VARCHAR(50) NULL COMMENT 'ช่วงชั้นเดิม',
    `previous_certificate_no` VARCHAR(100) NULL COMMENT 'เลขที่ประกาศนียบัตรเดิม',
    `previous_year` VARCHAR(10) NULL COMMENT 'ปีที่สอบได้ (พ.ศ.)',
    `previous_province` VARCHAR(100) NULL COMMENT 'จังหวัดที่สอบได้เดิม',

    -- สำนักเรียน (ขั้นตอนที่ 4)
    `office_name` VARCHAR(255) NULL COMMENT 'ชื่อสำนักเรียน/โรงเรียน',
    `office_sub_district` VARCHAR(100) NULL COMMENT 'ตำบล/แขวง สำนักเรียน',
    `office_district` VARCHAR(100) NULL COMMENT 'อำเภอ/เขต สำนักเรียน',
    `office_province` VARCHAR(100) NULL COMMENT 'จังหวัด สำนักเรียน',
    `office_postal_code` VARCHAR(5) NULL COMMENT 'รหัสไปรษณีย์ สำนักเรียน',

    -- อื่นๆ
    `referral_sources` TEXT NULL COMMENT 'ช่องทางที่ได้รู้จัก (comma separated)',
    `registration_channel` VARCHAR(20) NOT NULL DEFAULT 'internal' COMMENT 'public|internal',
    `remarks` TEXT NULL COMMENT 'หมายเหตุ',
    `status` VARCHAR(20) NOT NULL DEFAULT 'registered' COMMENT 'registered|checked|eligible|absent',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `exam_registrations_reference_code_unique` (`reference_code`),
    UNIQUE KEY `exam_registrations_session_national_unique` (`exam_session_id`, `national_id`),

    CONSTRAINT `exam_registrations_exam_session_id_foreign`
        FOREIGN KEY (`exam_session_id`) REFERENCES `exam_sessions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `exam_registrations_classroom_id_foreign`
        FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`) ON DELETE SET NULL,
    CONSTRAINT `exam_registrations_created_by_foreign`
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `exam_registrations_exam_center_id_foreign`
        FOREIGN KEY (`exam_center_id`) REFERENCES `exam_centers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================================
-- ถ้าตารางมีอยู่แล้ว (จาก migration เดิม) ให้รัน ALTER TABLE เพิ่มคอลัมน์แทน
-- รัน **ทีละคำสั่ง** ใน phpMyAdmin (คัดลอกทีละ ALTER TABLE)
-- ถ้าคอลัมน์มีอยู่แล้ว MySQL จะแจ้ง Duplicate column — ข้ามไปคำสั่งถัดไปได้
-- ============================================================================

-- ─── exam_sessions: เพิ่ม registration_mode ─────────────────────────────────
ALTER TABLE `exam_sessions`
    ADD COLUMN `registration_mode` VARCHAR(20) NOT NULL DEFAULT 'auto'
        COMMENT 'auto|force_open|force_closed' AFTER `is_published`;

ALTER TABLE `exam_sessions`
    ADD COLUMN `registration_start_date` DATE NULL
        COMMENT 'วันเริ่มรับสมัครสาธารณะ' AFTER `registration_mode`;

ALTER TABLE `exam_sessions`
    ADD COLUMN `registration_end_date` DATE NULL
        COMMENT 'วันสิ้นสุดรับสมัครสาธารณะ' AFTER `registration_start_date`;

-- ─── exam_registrations: เพิ่มคอลัมน์ทีละตัว ────────────────────────────────
ALTER TABLE `exam_registrations`
    ADD COLUMN `reference_code` VARCHAR(30) NULL
        COMMENT 'รหัสอ้างอิง REG-YYYYMMDD-XXXXXXXX' AFTER `id`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `phone` VARCHAR(20) NULL
        COMMENT 'เบอร์โทรศัพท์' AFTER `previous_certificate_no`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `line_id` VARCHAR(100) NULL
        COMMENT 'LINE ID' AFTER `phone`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `age` TINYINT UNSIGNED NULL
        COMMENT 'อายุ (ปี)' AFTER `line_id`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `birth_day` TINYINT UNSIGNED NULL
        COMMENT 'วันเกิด (1-31)' AFTER `age`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `birth_month` TINYINT UNSIGNED NULL
        COMMENT 'เดือนเกิด (1-12)' AFTER `birth_day`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `birth_year_be` SMALLINT UNSIGNED NULL
        COMMENT 'ปีเกิด พ.ศ.' AFTER `birth_month`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `house_number` VARCHAR(100) NULL
        COMMENT 'บ้านเลขที่/ที่อยู่' AFTER `birth_year_be`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `sub_district` VARCHAR(100) NULL
        COMMENT 'ตำบล/แขวง' AFTER `house_number`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `district` VARCHAR(100) NULL
        COMMENT 'อำเภอ/เขต' AFTER `sub_district`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `province` VARCHAR(100) NULL
        COMMENT 'จังหวัด' AFTER `district`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `postal_code` VARCHAR(5) NULL
        COMMENT 'รหัสไปรษณีย์' AFTER `province`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `education_level` VARCHAR(50) NULL
        COMMENT 'ระดับการศึกษา' AFTER `postal_code`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `has_previous_cert` TINYINT(1) NOT NULL DEFAULT 0
        COMMENT 'เคยสอบได้ธรรมศึกษามาก่อน' AFTER `education_level`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `previous_level` VARCHAR(20) NULL
        COMMENT 'ชั้นเดิมที่สอบได้: ตรี/โท/เอก' AFTER `has_previous_cert`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `previous_period` VARCHAR(50) NULL
        COMMENT 'ช่วงชั้นเดิม' AFTER `previous_level`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `previous_year` VARCHAR(10) NULL
        COMMENT 'ปีที่สอบได้ (พ.ศ.)' AFTER `previous_period`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `previous_province` VARCHAR(100) NULL
        COMMENT 'จังหวัดที่สอบได้เดิม' AFTER `previous_year`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `office_name` VARCHAR(255) NULL
        COMMENT 'ชื่อสำนักเรียน/โรงเรียน' AFTER `previous_province`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `office_sub_district` VARCHAR(100) NULL
        COMMENT 'ตำบล/แขวง สำนักเรียน' AFTER `office_name`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `office_district` VARCHAR(100) NULL
        COMMENT 'อำเภอ/เขต สำนักเรียน' AFTER `office_sub_district`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `office_province` VARCHAR(100) NULL
        COMMENT 'จังหวัด สำนักเรียน' AFTER `office_district`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `office_postal_code` VARCHAR(5) NULL
        COMMENT 'รหัสไปรษณีย์ สำนักเรียน' AFTER `office_province`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `referral_sources` TEXT NULL
        COMMENT 'ช่องทางที่ได้รู้จัก' AFTER `office_postal_code`;

ALTER TABLE `exam_registrations`
    ADD COLUMN `registration_channel` VARCHAR(20) NOT NULL DEFAULT 'internal'
        COMMENT 'public|internal' AFTER `referral_sources`;

-- ─── เพิ่ม unique index ให้ reference_code ───────────────────────────────────
ALTER TABLE `exam_registrations`
    ADD UNIQUE INDEX `exam_registrations_reference_code_unique` (`reference_code`);


-- ============================================================================
-- ข้อมูลตัวอย่าง: สร้างรอบสอบที่เปิดรับสมัครสาธารณะ
-- ============================================================================
-- INSERT INTO `exam_sessions` (
--     `academic_year`, `exam_date`, `status`,
--     `announcement`, `is_published`,
--     `registration_mode`, `registration_start_date`, `registration_end_date`,
--     `created_at`, `updated_at`
-- ) VALUES (
--     '2569', '2026-11-15', 'open',
--     'เปิดรับสมัครสอบธรรมศึกษา ประจำปีการศึกษา 2569',
--     1,
--     'auto', '2026-10-01', '2026-11-01',
--     NOW(), NOW()
-- );
