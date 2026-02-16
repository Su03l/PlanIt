<div align="center">

# PlanIt API

### نظام إدارة المهام والمساحات العمل الجماعية المتطور

<p>
    <a href="#"><img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" /></a>
    <a href="#"><img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" /></a>
    <a href="#"><img src="https://img.shields.io/badge/Sanctum-Auth-38BDF8?style=for-the-badge&logo=laravel&logoColor=white" alt="Sanctum" /></a>
    <a href="#"><img src="https://img.shields.io/badge/Clean-Architecture-green?style=for-the-badge" alt="Clean Arch" /></a>
</p>

<p>
    <img src="https://img.shields.io/badge/License-MIT-blue?style=flat-square" alt="License" />
    <img src="https://img.shields.io/badge/Status-Production_Ready-success?style=flat-square" alt="Status" />
</p>

<br>

**PlanIt** هو محرك إدارة مهام متكامل مصمم للفرق البرمجية والشركات الناشئة. يعتمد النظام على معمارية الخدمات (Service Layer) لضمان فصل المنطق البرمجي عن الواجهات، مع توفير نظام صلاحيات دقيق (RBAC) وتنبيهات زمنية ذكية. النظام مبني ليكون Headless API متوافق تماماً مع تطبيقات Next.js الحديثة.

</div>

---

## أبرز الحلول التقنية

| التحدي | الحل الذكي في PlanIt |
| :------------------ | :--------------------------------------------------------------------------- |
| **خصوصية البيانات** | استخدام **UUIDs** بدلاً من المعرفات الرقمية لمنع تخمين الروابط واختراق البيانات |
| **إدارة الصلاحيات** | نظام **Policies** صارم يضمن عدم وصول أي عضو لمهام أو مجموعات لا ينتمي إليها |
| **التنبيهات الزمنية** | محرك **Scheduled Tasks** يقوم بفحص المواعيد النهائية وبث تنبيهات تنازلية دقيقة |
| **معالجة الملفات** | استخدام **Image Intervention** لتحسين وضغط صور البروفايل والشعارات تلقائياً |

<br>

## المميزات الرئيسية

<div align="center">

`Workspace Management` &nbsp; `Task Lifecycle` &nbsp; `Polymorphic Comments`
<br>
`File Attachments` &nbsp; `Real-time Notifications` &nbsp; `Advanced Filtering`

</div>

---

## توثيق الواجهة البرمجية (API)

يمكنك الوصول للتوثيق الكامل عبر الرابط `/docs/api` عند التشغيل.

### 1. المصادقة والملف الشخصي

| الطريقة | المسار | الوصف |
| :------ | :--------------- | :----------------------------------------- |
| `POST`  | `/api/register`  | إنشاء حساب جديد مع مساحة عمل تلقائية |
| `POST`  | `/api/login`     | تسجيل الدخول وإصدار توكن Sanctum |
| `GET`   | `/api/profile`   | جلب بيانات المستخدم كاملة |
| `POST`  | `/api/profile`   | تحديث البيانات ورفع الصور |

### 2. إدارة المجموعات (Workspaces)

| الطريقة | المسار | الوصف |
| :------ | :--------------------- | :------------------------ |
| `GET`   | `/api/groups`          | عرض المجموعات التي ينتمي لها المستخدم |
| `POST`  | `/api/groups`          | إنشاء مجموعة جديدة (يكون المنشئ Admin) |
| `POST`  | `/api/groups/{id}/members` | إضافة أعضاء جدد للمجموعة |

### 3. إدارة المهام (Tasks)

| الطريقة | المسار | الوصف |
| :------ | :------------------------------- | :----------------------------------------- |
| `GET`   | `/api/groups/{id}/tasks`         | عرض المهام مع دعم البحث والفلترة المتقدمة |
| `POST`  | `/api/groups/{id}/tasks`         | إنشاء مهمة جديدة وإسنادها لعضو |
| `POST`  | `/api/tasks/{id}/comments`       | إضافة تعليق (Polymorphic) على المهمة |
| `POST`  | `/api/tasks/{id}/attachments`    | رفع مرفقات متعددة للمهمة |

---

## هيكلية المشروع (Architecture)

يتبع المشروع نمط **Service-Repository Pattern** (مبسط) لضمان قابلية الاختبار والتوسع:

- **Controllers**: مسؤولة فقط عن استقبال الطلبات وإرجاع الاستجابات.
- **Services**: تحتوي على منطق العمل (Business Logic) والعمليات المعقدة.
- **Resources**: مسؤولة عن تحويل الموديلات إلى JSON بتنسيق موحد.
- **Policies**: مسؤولة عن حماية الموارد والتحقق من الصلاحيات.
- **Traits**: وظائف مشتركة مثل رفع الملفات وتوحيد الاستجابات.

---

## المتطلبات التقنية

- PHP >= 8.2
- MySQL >= 8.0
- Composer

---

## التثبيت والتشغيل

### 1. إعداد المشروع
قم باستنساخ المستودع والدخول إلى مجلد المشروع:
```bash
git clone https://github.com/your-username/plan-it-api.git
cd plan-it-api
```

### 2. تثبيت الاعتمادات
قم بتثبيت مكتبات PHP المطلوبة عبر Composer:
```bash
composer install
```

### 3. إعداد البيئة
قم بإنشاء ملف الإعدادات وتوليد مفتاح التطبيق:
```bash
cp .env.example .env
php artisan key:generate
```
> **ملاحظة:** لا تنسَ ضبط إعدادات قاعدة البيانات في ملف `.env`.

### 4. قاعدة البيانات والتخزين
قم بإنشاء الجداول وربط مجلد التخزين:
```bash
php artisan migrate --seed
php artisan storage:link
```

### 5. تشغيل الخدمات
لتشغيل السيرفر المحلي ومحرك التنبيهات الزمنية:
```bash
# تشغيل السيرفر
php artisan serve

# تشغيل المجدول (في نافذة تيرمينال منفصلة)
php artisan schedule:work
```

---

<div align="center">
صنع بواسطة المهندس سليمان
</div>
