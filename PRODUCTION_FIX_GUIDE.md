# دليل إصلاح مشكلة الصور في Production

## الخطوات المطلوبة

### 1. الاتصال بـ SSH

```bash

# Password: Memohany123#%
```

### 2. الانتقال إلى مجلد المشروع

```bash
cd domains
cd domiraa.net
cd public_html
cd domiraa
```

### 3. رفع الملفات المطلوبة

قم برفع الملفات التالية إلى السيرفر:
- `fix_production_images.sh`
- `check_production.php`

### 4. تشغيل السكريبتات

#### الطريقة 1: استخدام السكريبت Bash (الأفضل)

```bash
# جعل السكريبت قابل للتنفيذ
chmod +x fix_production_images.sh

# تشغيل السكريبت
bash fix_production_images.sh
```

#### الطريقة 2: استخدام السكريبت PHP

```bash
php check_production.php
```

### 5. الإصلاحات اليدوية (إذا لزم الأمر)

#### تحديث APP_URL في .env

```bash
# فتح ملف .env
nano .env

# البحث عن APP_URL وتحديثه إلى:
APP_URL=https://domiraa.net
# أو
APP_URL=http://domiraa.net

# حفظ الملف (Ctrl+X ثم Y ثم Enter)
```

#### مسح Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

#### إنشاء Symbolic Link

```bash
# حذف الرابط القديم إذا كان موجود
rm -rf public/storage

# إنشاء رابط جديد
php artisan storage:link
```

#### تحديث الصلاحيات

```bash
chmod -R 755 storage
chmod -R 755 public/storage
chmod -R 755 bootstrap/cache
```

#### إنشاء المجلدات المطلوبة

```bash
mkdir -p storage/app/public/properties
mkdir -p storage/app/public/rooms
mkdir -p storage/app/public/receipts
mkdir -p storage/app/public/ownership_proofs
mkdir -p storage/app/public/contracts
mkdir -p storage/app/public/documents
mkdir -p storage/app/public/images
mkdir -p storage/app/public/images/thumbnails
```

### 6. التحقق من النتيجة

#### اختبار URL

```bash
php artisan tinker
```

ثم في Tinker:
```php
$url = \App\Helpers\StorageHelper::url('properties/test.jpg');
echo $url;
exit
```

#### اختبار Route

افتح في المتصفح:
```
https://domiraa.net/storage/test.jpg
```

إذا ظهرت الصورة، Route يعمل بشكل صحيح.

### 7. فحص Logs

```bash
# عرض آخر 50 سطر من logs
tail -n 50 storage/logs/laravel.log

# أو متابعة logs في الوقت الفعلي
tail -f storage/logs/laravel.log
```

### 8. التحقق من Web Server Configuration

#### Apache (.htaccess)

تأكد من وجود `.htaccess` في `public`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### Nginx

تأكد من أن nginx configuration يحتوي على:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location /storage {
    alias /path/to/domiraa/storage/app/public;
    try_files $uri $uri/ =404;
}
```

## الأوامر السريعة (Copy & Paste)

```bash
# 1. الانتقال للمجلد
cd domains/domiraa.net/public_html/domiraa

# 2. تحديث APP_URL (افتح .env يدوياً)
nano .env
# ثم عدل APP_URL=https://domiraa.net

# 3. مسح Cache
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear

# 4. إنشاء Symbolic Link
rm -rf public/storage && php artisan storage:link

# 5. تحديث الصلاحيات
chmod -R 755 storage public/storage bootstrap/cache

# 6. إنشاء المجلدات
mkdir -p storage/app/public/{properties,rooms,receipts,ownership_proofs,contracts,documents,images,images/thumbnails}

# 7. التحقق
php artisan storage:check
```

## إذا استمرت المشكلة

1. افتح Developer Tools (F12) → Network tab
2. ابحث عن طلبات الصور الفاشلة (404)
3. انسخ URL الصورة وحاول فتحها مباشرة
4. أرسل:
   - نتيجة `php artisan storage:check`
   - مثال على URL صورة لا تعمل
   - آخر 50 سطر من `storage/logs/laravel.log`

