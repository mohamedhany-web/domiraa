# تقرير حل مشكلة عدم ظهور الصور في النظام

## 📋 ملخص المشكلة

كانت الصور والملفات (PDF) لا تظهر على الموقع الإلكتروني بعد رفعه على السيرفر، حيث كانت جميع محاولات الوصول تعيد خطأ `HTTP/2 404` رغم أن الملفات موجودة فعلياً على السيرفر ويمكن قراءتها.

## 🔍 التحليل والتشخيص

### المشاكل المكتشفة:

1. **Route افتراضي من Laravel يتداخل مع Route المخصص**
   - Laravel يسجل Route افتراضي اسمه `storage.local` من خلال `FilesystemServiceProvider`
   - هذا Route يستخدم `storage/app/private` بدلاً من `storage/app/public`
   - كان يتسبب في منع Route المخصص من العمل

2. **إعدادات `.htaccess` تمنع Laravel من معالجة الطلبات**
   - كانت هناك قواعد في `public/.htaccess` تسمح بالوصول المباشر للملفات
   - هذه القواعد كانت تمنع Laravel من معالجة `/storage/` requests

3. **Route Cache قديم**
   - Route Cache كان يحتوي على معلومات قديمة
   - Route name كان `storage.local` بدلاً من `storage.file`

## ✅ الحلول المطبقة

### 1. تعطيل Route الافتراضي من Laravel

**الملف:** `config/filesystems.php`

**التعديل:**
```php
'local' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'serve' => false, // تم تعطيله لاستخدام Route مخصص
    'throw' => false,
    'report' => false,
],
```

**السبب:** تعطيل `serve => false` يمنع Laravel من تسجيل Route افتراضي تلقائياً، مما يسمح لـ Route المخصص بالعمل.

### 2. إزالة القواعد المانعة من `.htaccess`

**الملف:** `public/.htaccess`

**القواعد التي تم إزالتها:**
```apache
# Allow direct access to storage files (symlink support)
RewriteCond %{REQUEST_FILENAME} -f
RewriteCond %{REQUEST_URI} ^/storage/
RewriteRule ^ - [L]
```

**السبب:** هذه القواعد كانت تمنع Laravel من معالجة طلبات `/storage/` وتجعل الخادم يعيد 404 مباشرة.

### 3. تحسين Route المخصص

**الملف:** `routes/web.php`

**التحسينات:**
- إضافة `try-catch` شامل لمعالجة الأخطاء
- استخدام `@` لتجنب warnings من `file_exists()` و `realpath()`
- تحسين logging لتتبع المشاكل
- إضافة middleware `web` للتأكد من تحميل Session و CSRF
- تحسين فحص الأمان للمسارات

**الكود النهائي:**
```php
Route::get('/storage/{path}', function ($path) {
    try {
        // Clean path to prevent directory traversal
        $path = str_replace('..', '', $path);
        $path = ltrim($path, '/');
        
        // Build file path
        $basePath = storage_path('app/public');
        $filePath = $basePath . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        
        // Normalize path separators
        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);
        
        // Log the request for debugging
        \Log::info('Storage route accessed', [
            'requested_path' => $path,
            'file_path' => $filePath,
            'file_exists' => @file_exists($filePath),
            'is_file' => @is_file($filePath),
            'storage_path' => $basePath,
        ]);
    
        // Check if file exists
        if (!@file_exists($filePath)) {
            \Log::warning('Storage file not found', [
                'requested_path' => $path,
                'file_path' => $filePath,
            ]);
            abort(404, 'File not found');
        }
        
        if (!@is_file($filePath)) {
            \Log::warning('Storage path is not a file', [
                'requested_path' => $path,
                'file_path' => $filePath,
            ]);
            abort(404, 'Not a file');
        }
        
        // Get real path for security check
        $realPath = @realpath($filePath) ?: $filePath;
        $allowedPath = @realpath($basePath) ?: $basePath;
        
        // Security check: ensure the file is within storage/app/public
        $normalizedRealPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $realPath);
        $normalizedAllowedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $allowedPath);
        
        if (strpos($normalizedRealPath, $normalizedAllowedPath) !== 0) {
            \Log::warning('Storage access denied - path outside allowed directory', [
                'requested_path' => $path,
                'file_path' => $filePath,
                'real_path' => $realPath,
                'allowed_path' => $allowedPath,
            ]);
            abort(404, 'Access denied');
        }
        
        // Check if file is readable
        if (!@is_readable($realPath)) {
            \Log::warning('Storage file is not readable', [
                'requested_path' => $path,
                'real_path' => $realPath,
            ]);
            abort(403, 'File not readable');
        }
        
        // Get mime type
        $mimeType = @mime_content_type($realPath);
        if (!$mimeType) {
            $extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
                'svg' => 'image/svg+xml',
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];
            $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        }
        
        // Set proper headers
        $headers = [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ];
        
        // For PDF files, add inline display
        if ($mimeType === 'application/pdf') {
            $headers['Content-Disposition'] = 'inline; filename="' . basename($realPath) . '"';
        }
        
        \Log::info('Storage file served successfully', [
            'requested_path' => $path,
            'real_path' => $realPath,
            'mime_type' => $mimeType,
        ]);
        
        return response()->file($realPath, $headers);
    } catch (\Exception $e) {
        \Log::error('Storage route error', [
            'path' => $path ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        abort(404, 'File not found');
    }
})->where('path', '.*')->name('storage.file')->middleware('web');
```

## 🔧 خطوات التنفيذ على السيرفر

### 1. رفع الملفات المعدلة:
- `config/filesystems.php`
- `public/.htaccess`
- `routes/web.php`

### 2. مسح جميع الـ Cache:
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

### 3. التحقق من Route:
```bash
php artisan route:list | grep storage
```
يجب أن يظهر: `storage.file` (وليس `storage.local`)

### 4. اختبار الصور:
```bash
curl -I https://domiraa.net/storage/properties/[image-name].png
```
يجب أن يعيد: `HTTP/2 200` بدلاً من `HTTP/2 404`

## 📊 النتائج

### قبل الحل:
- ❌ جميع محاولات الوصول للصور تعيد `HTTP/2 404`
- ❌ Route name كان `storage.local` (Route افتراضي من Laravel)
- ❌ Route المخصص لا يتم استدعاؤه
- ❌ لا توجد logs في `laravel.log` عن محاولات الوصول

### بعد الحل:
- ✅ الصور تظهر بشكل صحيح
- ✅ Route name أصبح `storage.file` (Route المخصص)
- ✅ Route يتم استدعاؤه ويعمل بشكل صحيح
- ✅ Logs تظهر محاولات الوصول الناجحة

## 🔒 الأمان

تم تطبيق عدة إجراءات أمنية:

1. **منع Directory Traversal:**
   - تنظيف المسار من `..` و `/` في البداية
   - التحقق من أن الملف داخل `storage/app/public`

2. **فحص الصلاحيات:**
   - التحقق من وجود الملف
   - التحقق من أن المسار ملف وليس مجلد
   - التحقق من إمكانية القراءة

3. **Logging:**
   - تسجيل جميع محاولات الوصول
   - تسجيل الأخطاء والتحذيرات
   - تسجيل الملفات المقدمة بنجاح

## 📝 ملاحظات مهمة

1. **Route يجب أن يكون في أول الملف:**
   - Route `/storage/{path}` يجب أن يكون قبل أي routes أخرى في `routes/web.php`
   - هذا يضمن عدم اعتراضه من routes أخرى

2. **Config Cache:**
   - بعد تعديل `config/filesystems.php` يجب مسح config cache
   - استخدام: `php artisan config:clear`

3. **Route Cache:**
   - بعد تعديل Routes يجب مسح route cache
   - استخدام: `php artisan route:clear`

4. **Web Server Configuration:**
   - تأكد من أن `.htaccess` لا يحتوي على قواعد تمنع Laravel
   - تأكد من أن `Options +FollowSymLinks` مفعل

## 🐛 استكشاف الأخطاء

### إذا استمرت المشكلة:

1. **فحص Route:**
   ```bash
   php artisan route:list | grep storage
   ```

2. **فحص Logs:**
   ```bash
   tail -n 100 storage/logs/laravel.log | grep -A 10 "Storage route"
   ```

3. **فحص الملف:**
   ```bash
   ls -la storage/app/public/properties/[image-name].png
   ```

4. **اختبار Route مباشرة:**
   ```bash
   php artisan tinker --execute="
   \$request = Request::create('/storage/properties/[image-name].png', 'GET');
   \$route = Route::getRoutes()->match(\$request);
   echo 'Route: ' . \$route->getName() . PHP_EOL;
   "
   ```

## 📚 المراجع

- [Laravel Filesystem Configuration](https://laravel.com/docs/filesystem)
- [Laravel Routing](https://laravel.com/docs/routing)
- [Apache .htaccess Configuration](https://httpd.apache.org/docs/current/howto/htaccess.html)

---

**تاريخ الحل:** 9 يناير 2026  
**الإصدار:** Laravel 11.x  
**الخادم:** LiteSpeed (Hostinger)

