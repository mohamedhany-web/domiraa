# ⚡ إصلاح سريع لأخطاء الحجز و PDF

## 🚀 الحل السريع (3 خطوات)

### 1. على السيرفر، شغّل:
```bash
# Session (إذا استخدمت database):
php artisan session:table
php artisan migrate

# Storage Link:
php artisan storage:link

# الصلاحيات:
chmod -R 775 storage bootstrap/cache storage/framework/sessions storage/logs

# الكاش:
php artisan config:clear && php artisan cache:clear && php artisan session:clear
php artisan config:cache && php artisan route:cache
```

### 2. في ملف `.env`:
```env
SESSION_DRIVER=database  # أو file
SESSION_LIFETIME=120
APP_URL=https://yourdomain.com  # بدون / في النهاية
```

### 3. اختبار:
- حاول الحجز مرة أخرى
- افتح ملف PDF في المتصفح

---

## ✅ تم إصلاحه في الكود:

1. ✅ **CSRF Handler** - تحديث تلقائي للـ token
2. ✅ **AJAX Setup** - CSRF token في جميع الطلبات
3. ✅ **Error Handling** - معالجة أفضل للأخطاء
4. ✅ **PDF Support** - Route محسّن مع MIME types
5. ✅ **Route للـ CSRF Token** - `/csrf-token`

---

## 📞 إذا استمرت المشكلة:

1. شغّل: `tail -f storage/logs/laravel.log`
2. حاول الحجز مرة أخرى
3. أرسل آخر 50 سطر من الـ log

---

**راجع `FIX_BOOKING_AND_PDF_ERRORS.md` للتفاصيل الكاملة**

