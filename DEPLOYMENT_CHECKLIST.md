# قائمة التحقق قبل الرفع على السيرفر

## ✅ التحقق من الملفات المهمة

- [x] `app/Helpers/StorageHelper.php` - محدث ويستخدم APP_URL بشكل صحيح
- [x] `routes/web.php` - يحتوي على route بديل للصور
- [x] `public/.htaccess` - محدث مع دعم FollowSymLinks
- [x] جميع Views تستخدم `$image->url` أو `StorageHelper::url()`

## 📋 خطوات ما بعد الرفع

### 1. إنشاء Storage Link (مهم جداً!)

```bash
rm -rf public/storage
ln -s ../storage/app/public public/storage
ls -la public/storage  # للتحقق
```

### 2. تعيين الصلاحيات

```bash
chmod -R 775 storage bootstrap/cache
chmod 755 public/storage
```

### 3. إنشاء المجلدات

```bash
mkdir -p storage/app/public/{properties,rooms,receipts,ownership_proofs,contracts,documents}
chmod -R 775 storage/app/public
```

### 4. إعداد .env

تأكد من:
- `APP_URL=https://yourdomain.com` (بدون / في النهاية)
- `APP_ENV=production`
- `APP_DEBUG=false`
- إعدادات قاعدة البيانات صحيحة

### 5. مسح الكاش

```bash
php artisan config:clear && php artisan config:cache
php artisan route:clear && php artisan route:cache
php artisan view:clear && php artisan view:cache
```

## 🔍 التحقق من عمل الصور

1. **من Terminal:**
   ```bash
   ls -la public/storage
   # يجب أن ترى رابط رمزي
   ```

2. **من المتصفح:**
   - افتح: `https://yourdomain.com/storage/properties/test.jpg`
   - يجب أن تظهر الصورة أو 404 (إذا لم ترفع صور بعد)

3. **من الموقع:**
   - افتح قائمة الوحدات
   - يجب أن تظهر الصور

## ⚠️ ملاحظات مهمة

1. **بدون Storage Link، الصور لن تظهر** - تأكد من إنشائه!
2. **APP_URL مهم** - تأكد من أنه صحيح في .env
3. **Route البديل موجود** - إذا فشل symlink، route سيعمل تلقائياً
4. **جميع Views محدثة** - تستخدم StorageHelper الآن

## 🎯 الأوامر السريعة (كل شيء مرة واحدة)

```bash
# 1. Storage Link
rm -rf public/storage && ln -s ../storage/app/public public/storage

# 2. المجلدات
mkdir -p storage/app/public/{properties,rooms,receipts,ownership_proofs,contracts,documents}

# 3. الصلاحيات
chmod -R 775 storage bootstrap/cache && chmod 755 public/storage

# 4. الكاش
php artisan config:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

