<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>สมัครสอบธรรมศึกษา</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background:#f4f6f9; }
        .reg-section{background:#fff;border-radius:10px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,.07);margin-bottom:18px}
        .reg-section h5{font-weight:700;color:#1e293b;border-bottom:2px solid #16a085;padding-bottom:8px;margin-bottom:16px}
    </style>
</head>
<body>
<nav class="navbar navbar-dark" style="background:#16a085;">
    <a class="navbar-brand font-weight-bold" href="{{ route('landing') }}">ธรรมศึกษา|สำนักเรียนวัดสารอด</a>
    <a class="btn btn-light btn-sm" href="{{ route('exam_announcements.index') }}">ดูประกาศรอบสอบ</a>
</nav>
<main class="container py-4" style="max-width:760px;">
    <h1 class="h4 mb-4"><i class="fas fa-id-card text-success mr-2"></i>แบบฟอร์มสมัครสอบธรรมศึกษา</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('exam_registration.public.store') }}" id="regForm">
        @csrf

        <div class="reg-section">
            <h5>1. ข้อมูลผู้สมัคร</h5>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>คำนำหน้า</label>
                    <select name="title" id="titleSelect" class="form-control" required>
                        <option value="">เลือก</option>
                        <option value="เด็กชาย" {{ old('title') == 'เด็กชาย' ? 'selected' : '' }}>เด็กชาย</option>
                        <option value="เด็กหญิง" {{ old('title') == 'เด็กหญิง' ? 'selected' : '' }}>เด็กหญิง</option>
                        <option value="นาย" {{ old('title') == 'นาย' ? 'selected' : '' }}>นาย</option>
                        <option value="นางสาว" {{ old('title') == 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                        <option value="นาง" {{ old('title') == 'นาง' ? 'selected' : '' }}>นาง</option>
                        <option value="พระ" {{ old('title') == 'พระ' ? 'selected' : '' }}>พระ</option>
                        <option value="สามเณร" {{ old('title') == 'สามเณร' ? 'selected' : '' }}>สามเณร</option>
                        <option value="อื่นๆ" {{ old('title') == 'อื่นๆ' || (old('title_other') && !in_array(old('title'), ['เด็กชาย','เด็กหญิง','นาย','นางสาว','นาง','พระ','สามเณร',''])) ? 'selected' : '' }}>อื่นๆ</option>
                    </select>
                    <input type="text" name="title_other" id="titleOtherInput" class="form-control mt-1"
                        placeholder="ระบุคำนำหน้า"
                        value="{{ old('title_other') }}"
                        style="display:none;">
                </div>
                <div class="form-group col-md-4">
                    <label>ชื่อ</label>
                    <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
                </div>
                <div class="form-group col-md-5">
                    <label>นามสกุล</label>
                    <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>เลขประจำตัวประชาชน (13 หลัก)</label>
                    <input type="text" name="citizen_id" maxlength="13" inputmode="numeric" class="form-control" required value="{{ old('citizen_id') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>อายุ</label>
                    <input type="number" name="age" class="form-control" value="{{ old('age') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>วัน (เกิด)</label>
                    <input type="number" min="1" max="31" name="dob_day" class="form-control" value="{{ old('dob_day') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>เดือน</label>
                    <input type="number" min="1" max="12" name="dob_month" class="form-control" value="{{ old('dob_month') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>ปี พ.ศ.</label>
                    <input type="number" name="dob_year_be" class="form-control" value="{{ old('dob_year_be') }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="form-group col-md-6">
                    <label>LINE ID</label>
                    <input type="text" name="line_id" class="form-control" value="{{ old('line_id') }}">
                </div>
            </div>
        </div>

        <div class="reg-section">
            <h5>2. ที่อยู่ปัจจุบัน</h5>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>บ้านเลขที่</label>
                    <input type="text" name="address_no" class="form-control" value="{{ old('address_no') }}">
                </div>
                <div class="form-group col-md-3">
                    <label>หมู่ที่</label>
                    <input type="text" name="moo" class="form-control" value="{{ old('moo') }}">
                </div>
                <div class="form-group col-md-6">
                    <label>จังหวัด</label>
                    <select class="form-control addr-province" data-target="district,subdistrict,postal_code" data-prefix="" required></select>
                    <input type="hidden" name="province" class="addr-province-value" value="{{ old('province') }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>อำเภอ/เขต</label>
                    <select class="form-control addr-district" data-prefix="" required></select>
                    <input type="hidden" name="district" class="addr-district-value" value="{{ old('district') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>ตำบล/แขวง</label>
                    <select class="form-control addr-subdistrict" data-prefix="" required></select>
                    <input type="hidden" name="subdistrict" class="addr-subdistrict-value" value="{{ old('subdistrict') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>รหัสไปรษณีย์</label>
                    <input type="text" name="postal_code" class="form-control addr-postal" data-prefix="" value="{{ old('postal_code') }}">
                </div>
            </div>
        </div>

        <div class="reg-section">
            <h5>3. ระดับการศึกษา/ประวัติการสอบ</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>ระดับการศึกษาปัจจุบัน</label>
                    <input type="text" name="education_level" class="form-control" required value="{{ old('education_level') }}" placeholder="เช่น ม.3, ปวช.1">
                </div>
                <div class="form-group col-md-6">
                    <label>เลขที่ประกาศนียบัตรเดิม (ถ้ามี)</label>
                    <input type="text" name="previous_certificate_no" class="form-control" value="{{ old('previous_certificate_no') }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>ชั้นที่เคยสอบได้</label>
                    <select name="previous_level" class="form-control">
                        <option value="">ไม่มี/ไม่เคยสอบ</option>
                        <option value="ตรี">ธรรมศึกษาชั้นตรี</option>
                        <option value="โท">ธรรมศึกษาชั้นโท</option>
                        <option value="เอก">ธรรมศึกษาชั้นเอก</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>ช่วงชั้นที่เคยสอบได้</label>
                    <select name="previous_range" class="form-control">
                        <option value="">-</option>
                        <option value="ประถม">ประถมศึกษา</option>
                        <option value="มัธยม">มัธยมศึกษา</option>
                        <option value="อุดมศึกษา">อุดมศึกษา/ประชาชนทั่วไป</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>ปีที่สอบได้ (พ.ศ.)</label>
                    <input type="number" name="previous_passed_year_be" class="form-control" value="{{ old('previous_passed_year_be') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>จังหวัดที่สอบ</label>
                    <input type="text" name="previous_province" class="form-control" value="{{ old('previous_province') }}">
                </div>
            </div>
        </div>

        <div class="reg-section">
            <h5>4. สมัครสอบชั้น</h5>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>ชั้นที่สมัครสอบ</label>
                    <select name="target_level" class="form-control" required>
                        <option value="">เลือก</option>
                        <option value="ตรี">ธรรมศึกษาชั้นตรี</option>
                        <option value="โท">ธรรมศึกษาชั้นโท</option>
                        <option value="เอก">ธรรมศึกษาชั้นเอก</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>ช่วงชั้น</label>
                    <select name="target_range" class="form-control" required>
                        <option value="">เลือก</option>
                        <option value="ประถม">ประถมศึกษา</option>
                        <option value="มัธยม">มัธยมศึกษา</option>
                        <option value="อุดมศึกษา">อุดมศึกษา/ประชาชนทั่วไป</option>
                    </select>
                </div>
            </div>
            @if ($sessions->isNotEmpty())
            <div class="form-group">
                <label>รอบสอบ</label>
                <select name="exam_session_id" class="form-control">
                    <option value="">ไม่ระบุ</option>
                    @foreach ($sessions as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->academic_year }})</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if ($centers->isNotEmpty())
            <div class="form-group">
                <label>สถานที่สอบ</label>
                <select name="exam_center_id" class="form-control">
                    <option value="">ไม่ระบุ</option>
                    @foreach ($centers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>

        <div class="reg-section">
            <h5>5. สถานศึกษา/สำนักเรียนที่สมัครผ่าน</h5>
            <div class="form-group">
                <label>ชื่อสถานศึกษา/สำนักเรียน</label>
                <input type="text" name="office_name" class="form-control" value="{{ old('office_name') }}">
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>ตำบล/แขวง</label>
                    <input type="text" name="office_subdistrict" class="form-control" value="{{ old('office_subdistrict') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>อำเภอ/เขต</label>
                    <input type="text" name="office_district" class="form-control" value="{{ old('office_district') }}">
                </div>
                <div class="form-group col-md-4">
                    <label>จังหวัด</label>
                    <input type="text" name="office_province" class="form-control" value="{{ old('office_province') }}">
                </div>
            </div>
        </div>

        <div class="reg-section">
            <h5>6. ทราบข่าวการรับสมัครจากช่องทางใด (เลือกได้หลายช่องทาง)</h5>
            @foreach (['เพื่อน/ครอบครัว','ครู/โรงเรียน','เฟซบุ๊ก/โซเชียล','เว็บไซต์วัด/สำนักเรียน','ป้ายประกาศ','อื่น ๆ'] as $src)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="referral_sources[]" value="{{ $src }}" id="src-{{ $loop->index }}">
                    <label class="form-check-label" for="src-{{ $loop->index }}">{{ $src }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success btn-lg btn-block">
            <i class="fas fa-paper-plane mr-1"></i>ส่งใบสมัคร
        </button>
    </form>
</main>

<script>
(function () {
    var addressData = null;

    function fetchAddress() {
        return fetch('{{ asset('data/thai-address.json') }}').then(function (r) { return r.json(); });
    }

    function populateProvinces(select) {
        select.innerHTML = '<option value="">เลือกจังหวัด</option>';
        addressData.forEach(function (p) {
            var opt = document.createElement('option');
            opt.value = p.code;
            opt.textContent = p.name_th;
            select.appendChild(opt);
        });
    }

    function findProvince(code) {
        return addressData.find(function (p) { return String(p.code) === String(code); });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var provinceSelect = document.querySelector('.addr-province');
        var districtSelect = document.querySelector('.addr-district');
        var subdistrictSelect = document.querySelector('.addr-subdistrict');
        var provinceHidden = document.querySelector('.addr-province-value');
        var districtHidden = document.querySelector('.addr-district-value');
        var subdistrictHidden = document.querySelector('.addr-subdistrict-value');
        var postalInput = document.querySelector('.addr-postal');

        if (!provinceSelect) return;

        fetchAddress().then(function (data) {
            addressData = data;
            populateProvinces(provinceSelect);
        });

        provinceSelect.addEventListener('change', function () {
            var province = findProvince(provinceSelect.value);
            provinceHidden.value = province ? province.name_th : '';
            districtSelect.innerHTML = '<option value="">เลือกอำเภอ/เขต</option>';
            subdistrictSelect.innerHTML = '<option value="">เลือกตำบล/แขวง</option>';
            districtHidden.value = '';
            subdistrictHidden.value = '';
            postalInput.value = '';
            if (!province) return;
            province.districts.forEach(function (d) {
                var opt = document.createElement('option');
                opt.value = d.code;
                opt.textContent = d.name_th;
                districtSelect.appendChild(opt);
            });
            districtSelect.dataset.provinceCode = province.code;
        });

        districtSelect.addEventListener('change', function () {
            var province = findProvince(districtSelect.dataset.provinceCode);
            var district = province ? province.districts.find(function (d) { return String(d.code) === String(districtSelect.value); }) : null;
            districtHidden.value = district ? district.name_th : '';
            subdistrictSelect.innerHTML = '<option value="">เลือกตำบล/แขวง</option>';
            subdistrictHidden.value = '';
            postalInput.value = '';
            if (!district) return;
            district.subdistricts.forEach(function (s) {
                var opt = document.createElement('option');
                opt.value = s.code;
                opt.textContent = s.name_th;
                opt.dataset.postal = s.postal_code;
                subdistrictSelect.appendChild(opt);
            });
        });

        subdistrictSelect.addEventListener('change', function () {
            var opt = subdistrictSelect.options[subdistrictSelect.selectedIndex];
            subdistrictHidden.value = opt ? opt.textContent : '';
            postalInput.value = opt && opt.dataset.postal ? opt.dataset.postal : '';
        });
    });
})();

// คำนำหน้า "อื่นๆ" toggle
(function () {
    var titleSelect = document.getElementById('titleSelect');
    var titleOtherInput = document.getElementById('titleOtherInput');

    function toggleTitleOther() {
        if (titleSelect.value === 'อื่นๆ') {
            titleOtherInput.style.display = 'block';
            titleOtherInput.required = true;
        } else {
            titleOtherInput.style.display = 'none';
            titleOtherInput.required = false;
            titleOtherInput.value = '';
        }
    }

    titleSelect.addEventListener('change', toggleTitleOther);
    // คืนสถานะเมื่อ page reload (เช่น validation error)
    toggleTitleOther();
})();
</script>
</body>
</html>
