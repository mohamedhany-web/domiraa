# 🔧 دليل إصلاح أخطاء الحجز (419/500) وملفات PDF

## 📋 المشاكل والحلول

### 1️⃣ خطأ 419 (CSRF Token Mismatch)

**الأسباب المحتملة:**
- انتهاء صلاحية الجلسة
- Session غير محفوظة بشكل صحيح
- CSRF token غير محدث في الـ forms

**الحلول المطبقة:**

#### ✅ تم إصلاحه في الكود:

1. **AJAX Setup شامل** - في `layouts/app.blade.php`
   - إضافة CSRF token تلقائياً لجميع AJAX requests
   - تحديث token عند تغيير visibility

2. **CSRF Handler Component** - `components/csrf-handler.blade.php`
   - تحديث token قبل submit
   - معالجة أخطاء 419 تلقائياً
   - إعادة تحميل الصفحة عند انتهاء الجلسة

3. **Route للـ CSRF Token** - `/csrf-token`
   - يمكن الحصول على token جديد عبر AJAX

4. **Error Handling** - في `bootstrap/app.php`
   - معالجة أخطاء 419 بشكل أفضل
   - رسائل خطأ واضحة

#### 🔧 خطوات إضافية على السيرفر:

```bash
# 1. تأكد من Session Driver
# في .env:
SESSION_DRIVER=database  # أو file
SESSION_LIFETIME=120

# 2. إذا استخدمت database، شغّل migration:
php artisan session:table
php artisan migrate

# 3. تأكد من الصلاحيات:
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/framework/cache

# 4. مسح الكاش:
php artisan config:clear
php artisan cache:clear
php artisan session:clear
```

---

### 2️⃣ خطأ 500 (Server Error)

**الأسباب المحتملة:**
- خطأ في الكود
- مشكلة في الصلاحيات
- خطأ في قاعدة البيانات
- خطأ في الـ logs

**الحلول:**

#### ✅ تم إصلاحه في الكود:

1. **Error Handling محسّن** - في `bootstrap/app.php`
   - تسجيل الأخطاء في logs
   - رسائل خطأ واضحة

2. **Route محسّن** - في `routes/web.php`
   - معالجة أخطاء الوصول للملفات
   - تسجيل الأخطاء

#### 🔧 خطوات التشخيص:

```bash
# 1. فحص الـ logs:
tail -f storage/logs/laravel.log

# 2. فحص الصلاحيات:
ls -la storage/logs/
chmod -R 775 storage/logs

# 3. فحص قاعدة البيانات:
php artisan migrate:status

# 4. فحص الـ config:
php artisan config:clear
php artisan config:cache
```

#### 📝 فحص الأخطاء:

1. **افتح Developer Tools (F12)**
2. **اذهب إلى Console tab** - ابحث عن أخطاء JavaScript
3. **اذهب إلى Network tab** - افحص الطلبات الفاشلة
4. **تحقق من Response** - اقرأ رسالة الخطأ

---

### 3️⃣ ملفات PDF لا تظهر

**الأسباب:**
- نفس مشكلة الصور (Storage/Symlink)
- MIME type غير صحيح
- Route لا يدعم PDF

**الحلول المطبقة:**

#### ✅ تم إصلاحه في الكود:

1. **Route محسّن** - في `routes/web.php`
   - دعم PDF files
   - MIME type صحيح: `application/pdf`
   - Content-Disposition: inline (لعرض PDF في المتصفح)
   - معالجة أخطاء أفضل

2. **StorageHelper** - يعمل مع PDF أيضاً
   - يستخدم نفس الـ URL generation
   - يعمل مع Route البديل

#### 🔧 خطوات على السيرفر:

```bash
# 1. تأكد من Symlink (نفس خطوات الصور):
php artisan storage:link

# 2. تأكد من Route:
# Route موجود في routes/web.php ويجب أن يكون قبل أي routes أخرى

# 3. اختبار PDF:
# افتح في المتصفح:
https://yourdomain.com/storage/ownership_proofs/test.pdf

# 4. إذا لم يعمل، تحقق من:
ls -la storage/app/public/ownership_proofs/
chmod -R 775 storage/app/public
```

#### 📝 ملاحظات مهمة:

1. **Route البديل موجود** - يعمل حتى لو فشل Symlink
2. **MIME Type صحيح** - `application/pdf`
3. **Content-Disposition: inline** - لعرض PDF في المتصفح
4. **نفس مشكلة الصور** - راجع `FIX_IMAGES_PRODUCTION.md`

---

## 🚀 الحل السريع (كل شيء مرة واحدة)

### على السيرفر:

```bash
# 1. Session (إذا استخدمت database):
php artisan session:table
php artisan migrate

# 2. Storage Link:
php artisan storage:link

# 3. الصلاحيات:
chmod -R 775 storage bootstrap/cache
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/logs

# 4. الكاش:
php artisan config:clear
php artisan cache:clear
php artisan session:clear
php artisan route:clear
php artisan view:clear

# 5. إعادة بناء الكاش (للإنتاج):
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### في ملف `.env`:

```env
# Session
SESSION_DRIVER=database  # أو file
SESSION_LIFETIME=120

# App URL (مهم جداً!)
APP_URL=https://yourdomain.com

# Environment
APP_ENV=production
APP_DEBUG=false
```

---

## 🧪 اختبار الحلول

### 1. اختبار CSRF Token:

```javascript
// في Console المتصفح:
fetch('/csrf-token')
  .then(r => r.json())
  .then(data => console.log('CSRF Token:', data.token));
```

### 2. اختبار PDF:

افتح في المتصفح:
```
https://yourdomain.com/storage/ownership_proofs/test.pdf
```

### 3. اختبار الحجز:

1. املأ نموذج الحجز
2. اضغط Submit
3. تحقق من Network tab في Developer Tools
4. إذا ظهر 419، يجب أن يعيد تحميل الصفحة تلقائياً

---

## 📝 ملاحظات مهمة

### ✅ ما تم إصلاحه:

1. **CSRF Token Handler** - تحديث تلقائي
2. **AJAX Setup** - CSRF token في جميع الطلبات
3. **Error Handling** - معالجة أفضل للأخطاء
4. **PDF Support** - Route محسّن مع MIME types صحيحة
5. **Session Management** - تحسين إدارة الجلسات

### ⚠️ تحذيرات:

1. **SESSION_DRIVER** - تأكد من أنه database أو file
2. **APP_URL** - يجب أن يكون صحيحاً في `.env`
3. **Storage Link** - يجب أن يكون موجوداً
4. **Session Table** - إذا استخدمت database، يجب إنشاء الجدول

---

## 🔍 إذا استمرت المشكلة

### 1. فحص الـ Logs:

```bash
tail -f storage/logs/laravel.log
```

### 2. فحص Session:

```bash
# إذا استخدمت database:
php artisan tinker
>>> \DB::table('sessions')->count()
```

### 3. فحص CSRF Token:

افتح Developer Tools → Console:
```javascript
console.log(document.querySelector('meta[name="csrf-token"]').content);
```

### 4. فحص PDF Route:

```bash
php artisan route:list | grep storage
```

---

## ✅ قائمة التحقق النهائية

- [ ] `SESSION_DRIVER` مضبوط في `.env`
- [ ] `SESSION_LIFETIME` مناسب (120 دقيقة)
- [ ] Session table موجود (إذا استخدمت database)
- [ ] `php artisan storage:link` تم تنفيذه
- [ ] الصلاحيات صحيحة (775 للـ storage)
- [ ] `APP_URL` صحيح في `.env`
- [ ] `php artisan config:cache` تم تنفيذه
- [ ] Route للـ storage موجود قبل أي routes أخرى
- [ ] CSRF handler component موجود في layouts
- [ ] تم اختبار الحجز بنجاح
- [ ] تم اختبار PDF بنجاح

---

**تم إنشاء هذا الدليل بواسطة Laravel Senior Developer** 🚀

