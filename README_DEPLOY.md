# دليل رفع الموقع على السيرفر

## متطلبات السيرفر

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Nginx أو Apache مع mod_rewrite
- Extension: GD أو Imagick (للصور)

## خطوات الرفع (بعد رفع الملفات)

### 1. تثبيت المتطلبات

```bash
composer install --no-dev --optimize-autoloader
```

### 2. إعداد ملف .env

```bash
cp .env.example .env
nano .env
```

**إعدادات مهمة:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. توليد مفتاح التطبيق

```bash
php artisan key:generate
```

### 4. ربط التخزين (Storage Link) ⚠️ مهم جداً للصور

```bash
# حذف المجلد القديم إن وجد
rm -rf public/storage

# إنشاء رابط رمزي
ln -s ../storage/app/public public/storage

# التحقق
ls -la public/storage
# يجب أن ترى: lrwxrwxrwx ... public/storage -> ../storage/app/public
```

### 5. تعيين الصلاحيات

```bash
chmod -R 775 storage bootstrap/cache
chmod 755 public/storage
```

### 6. تشغيل Migrations

```bash
php artisan migrate --force
```

### 7. إنشاء المجلدات المطلوبة

```bash
mkdir -p storage/app/public/properties
mkdir -p storage/app/public/rooms
mkdir -p storage/app/public/receipts
mkdir -p storage/app/public/ownership_proofs
mkdir -p storage/app/public/contracts
mkdir -p storage/app/public/documents
chmod -R 775 storage/app/public
```

### 8. تحسين الأداء

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## التحقق من عمل الصور

### 1. التحقق من الرابط الرمزي

```bash
ls -la public/storage
```

يجب أن يكون رابط رمزي يشير إلى `../storage/app/public`

### 2. اختبار الصور

افتح في المتصفح:
```
https://yourdomain.com/storage/properties/test.jpg
```

### 3. إذا لم تعمل الصور

```bash
# إعادة إنشاء الرابط الرمزي
rm -rf public/storage
ln -s ../storage/app/public public/storage

# مسح الكاش
php artisan config:clear && php artisan config:cache
php artisan view:clear && php artisan view:cache
```

## ملاحظات مهمة

1. **APP_URL:** يجب أن يكون صحيحاً في `.env` (بدون `/` في النهاية)
2. **الصلاحيات:** تأكد من أن `storage` و `bootstrap/cache` قابلان للكتابة
3. **الرابط الرمزي:** بدون `public/storage` الرابط الرمزي، الصور لن تظهر
4. **Route البديل:** إذا لم يعمل symlink، route في `routes/web.php` سيعمل تلقائياً

## هيكل المجلدات

```
storage/
├── app/
│   └── public/
│       ├── properties/      # صور الوحدات
│       ├── rooms/           # صور الغرف
│       ├── receipts/        # إيصالات الدفع
│       ├── ownership_proofs/# وثائق الملكية
│       ├── contracts/       # العقود
│       └── documents/       # المستندات

public/
└── storage -> ../storage/app/public  # رابط رمزي
```

## الأوامر السريعة (انسخ كلها مرة واحدة)

```bash
# 1. إنشاء الرابط الرمزي
rm -rf public/storage && ln -s ../storage/app/public public/storage

# 2. إنشاء المجلدات
mkdir -p storage/app/public/{properties,rooms,receipts,ownership_proofs,contracts,documents}

# 3. تعيين الصلاحيات
chmod -R 775 storage bootstrap/cache && chmod 755 public/storage

# 4. مسح الكاش
php artisan config:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

