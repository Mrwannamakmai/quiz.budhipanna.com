# Workflow: เข้าสู่ระบบ (Login)

**Route:** `/login`
**ไฟล์หลัก:** `src/app/login/page.tsx`, `src/app/dashboard/actions.ts` (`loginAction`), `src/lib/auth.ts`, `src/proxy.ts`

## 1. Flow

```mermaid
flowchart TD
    Visit["ผู้ใช้เข้า /login"] --> HasCookie{มี session cookie\nอยู่แล้วหรือไม่?}
    HasCookie -->|มี, ถูกต้อง| RedirectDash["redirect → /dashboard\n(ทำที่ Middleware proxy.ts)"]
    HasCookie -->|ไม่มี| ShowForm["แสดงฟอร์ม Login"]
    ShowForm --> Fill["กรอก Username + Password"]
    Fill --> Submit["กด 'เข้าสู่ระบบ'\n(React Server Action: loginAction)"]
    Submit --> Check{username + password\nตรงกับ users[] หรือไม่?}
    Check -->|ไม่ตรง| ShowErr["แสดง error:\n'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'\n(ฟอร์มยังอยู่ ไม่รีเซ็ต)"]
    Check -->|ว่างฟิลด์ใดฟิลด์หนึ่ง| ShowErr2["'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน'"]
    Check -->|ตรง| SetCookie["สร้าง HMAC token,\nตั้ง cookie 'srd-session'\n(httpOnly, 24 ชม.)"]
    SetCookie --> RedirectDash2["redirect → /dashboard"]
```

## 2. โครงหน้าจอ

```
┌───────────────────────────┐
│   [โลโก้วัดสารอด] [โลโก้สำนัก] │
│        เข้าสู่ระบบ            │
│  ระบบบริหารจัดการสำนักเรียนวัดสารอด │
│  ┌─────────────────────┐   │
│  │ ชื่อผู้ใช้              │   │
│  │ [input]              │   │
│  │ รหัสผ่าน               │   │
│  │ [input type=password]│   │
│  │ [error ถ้ามี]          │   │
│  │ [ปุ่ม เข้าสู่ระบบ]       │   │
│  └─────────────────────┘   │
└───────────────────────────┘
```

- ใช้ `useActionState` (React 19) ผูกกับ `loginAction` — ฟอร์มทำงานได้แม้ JavaScript โหลดช้า (progressive enhancement)
- ปุ่ม submit เปลี่ยนข้อความเป็น "กำลังเข้าสู่ระบบ…" และ disable ระหว่าง pending
- ไม่มี "ลืมรหัสผ่าน", ไม่มี remember-me, ไม่มี 2FA, ไม่มี rate-limit/lockout หลัง login ผิดหลายครั้ง

## 3. การป้องกัน Route (Route Guard)

มีการเช็คสิทธิ์ **2 ชั้นซ้อนกัน**:

1. **Middleware (`proxy.ts`)** — ทำงานก่อนถึง Server Component ใดๆ เช็คแค่ "มี cookie หรือไม่" (ไม่ verify signature):
   - เข้า `/dashboard/*` โดยไม่มี cookie → redirect `/login`
   - เข้า `/login` ทั้งที่มี cookie อยู่แล้ว → redirect `/dashboard`
2. **`DashboardLayout` (Server Component)** — เรียก `getSession()` ซึ่ง verify HMAC signature จริง ถ้า invalid/expired → redirect `/login` อีกชั้น (ป้องกันกรณี cookie ถูกปลอมแต่ไม่มี signature ถูกต้อง)

หน้าเฉพาะที่ต้องสิทธิ์สูงกว่าทั่วไป (reports / followup / print / qrcode) จะเช็คสิทธิ์เพิ่มเติมเป็นรายหน้า (ดู `09-data-model-permissions.md`) — ถ้าสิทธิ์ไม่พอจะ **redirect กลับ `/dashboard`** เงียบๆ (ไม่มีข้อความ "ไม่มีสิทธิ์เข้าถึง" แจ้งผู้ใช้)

## 4. ออกจากระบบ (Logout)

- ปุ่ม "ออกจากระบบ" อยู่ท้าย sidebar ทุกหน้า dashboard (`DashboardShell`)
- เป็นฟอร์ม POST เรียก `logoutAction` → ลบ cookie → `redirect('/login')`
- ไม่มี confirm dialog ก่อนออกจากระบบ

## 5. ข้อสังเกตด้านความปลอดภัย/UX

- รายชื่อผู้ใช้/รหัสผ่าน hardcode ในซอร์สโค้ด ไม่มีหน้าแก้ไข/เปลี่ยนรหัสผ่านใดๆ ในระบบ
- ไม่มีการจำกัดจำนวนครั้งที่ login ผิด (brute-force risk)
- Error message ไม่บอกว่า "username" หรือ "password" ผิด (ดีต่อความปลอดภัย แต่ debug ยากสำหรับผู้ใช้)
