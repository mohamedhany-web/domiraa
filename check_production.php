<?php

/**
 * سكريبت PHP شامل للتحقق من إعدادات Production
 * استخدم: php check_production.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "==========================================\n";
echo "فحص شامل لإعدادات Production\n";
echo "==========================================\n\n";

$issues = [];
$fixes = [];

// 1. فحص APP_URL
echo "[1/7] فحص APP_URL...\n";
$appUrl = config('app.url');
$envUrl = env('APP_URL');

if (!$appUrl || $appUrl === 'http://localhost' || $appUrl === 'http://127.0.0.1:8000') {
    $issues[] = "APP_URL غير مضبوط: $appUrl";
    $fixes[] = "تحديث APP_URL في .env إلى رابط الموقع الفعلي";
    echo "❌ APP_URL: $appUrl (غير صحيح)\n";
} else {
    echo "✅ APP_URL: $appUrl\n";
}
echo "\n";

// 2. فحص Symbolic Link
echo "[2/7] فحص Symbolic Link...\n";
$storageLink = public_path('storage');
$target = storage_path('app/public');

if (!file_exists($storageLink)) {
    $issues[] = "الرابط الرمزي غير موجود";
    $fixes[] = "php artisan storage:link";
    echo "❌ الرابط الرمزي غير موجود\n";
} elseif (is_link($storageLink)) {
    $linkTarget = readlink($storageLink);
    echo "✅ الرابط الرمزي موجود: $linkTarget\n";
} elseif (is_dir($storageLink)) {
    $issues[] = "public/storage موجود لكنه مجلد وليس رابط رمزي";
    $fixes[] = "rm -rf public/storage && php artisan storage:link";
    echo "❌ public/storage موجود لكنه مجلد وليس رابط رمزي\n";
} else {
    echo "✅ public/storage موجود\n";
}
echo "\n";

// 3. فحص الصلاحيات
echo "[3/7] فحص الصلاحيات...\n";
$paths = [
    'storage' => storage_path(),
    'storage/app/public' => storage_path('app/public'),
    'bootstrap/cache' => base_path('bootstrap/cache'),
];

foreach ($paths as $name => $path) {
    if (!is_writable($path)) {
        $issues[] = "المجلد غير قابل للكتابة: $name";
        $fixes[] = "chmod -R 755 $path";
        echo "❌ $name: غير قابل للكتابة\n";
    } else {
        echo "✅ $name: قابل للكتابة\n";
    }
}
echo "\n";

// 4. فحص المجلدات
echo "[4/7] فحص المجلدات المطلوبة...\n";
$requiredDirs = ['properties', 'rooms', 'receipts', 'ownership_proofs', 'contracts', 'documents', 'images'];
foreach ($requiredDirs as $dir) {
    $fullPath = storage_path("app/public/$dir");
    if (!is_dir($fullPath)) {
        $issues[] = "المجلد غير موجود: storage/app/public/$dir";
        $fixes[] = "mkdir -p $fullPath";
        echo "❌ $dir: غير موجود\n";
    } else {
        echo "✅ $dir: موجود\n";
    }
}
echo "\n";

// 5. فحص Route
echo "[5/7] فحص Route...\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$storageRoute = null;
foreach ($routes as $route) {
    if ($route->uri() === 'storage/{path}') {
        $storageRoute = $route;
        break;
    }
}

if (!$storageRoute) {
    $issues[] = "Route للصور غير موجود";
    echo "❌ Route للصور غير موجود\n";
} else {
    echo "✅ Route للصور موجود\n";
}
echo "\n";

// 6. اختبار URL Generation
echo "[6/7] اختبار إنشاء URLs...\n";
try {
    $testPath = 'properties/test.jpg';
    $url = \App\Helpers\StorageHelper::url($testPath);
    echo "✅ URL Example: $url\n";
    
    if (strpos($url, $appUrl) === 0) {
        echo "✅ URL يستخدم APP_URL بشكل صحيح\n";
    } else {
        $issues[] = "URL لا يستخدم APP_URL بشكل صحيح";
        echo "❌ URL لا يستخدم APP_URL بشكل صحيح\n";
    }
} catch (\Exception $e) {
    $issues[] = "خطأ في إنشاء URL: " . $e->getMessage();
    echo "❌ خطأ في إنشاء URL: " . $e->getMessage() . "\n";
}
echo "\n";

// 7. فحص الملفات
echo "[7/7] فحص الملفات في storage...\n";
$files = glob(storage_path('app/public/**/*'), GLOB_BRACE);
$fileCount = 0;
foreach ($files as $file) {
    if (is_file($file)) {
        $fileCount++;
    }
}

if ($fileCount > 0) {
    echo "✅ يوجد $fileCount ملف في storage/app/public\n";
    echo "أمثلة على الملفات:\n";
    $examples = array_slice(array_filter($files, 'is_file'), 0, 5);
    foreach ($examples as $file) {
        $relativePath = str_replace(storage_path('app/public/'), '', $file);
        echo "   - $relativePath\n";
    }
} else {
    $issues[] = "لا توجد ملفات في storage/app/public";
    echo "❌ لا توجد ملفات في storage/app/public\n";
}
echo "\n";

// عرض النتائج
echo "==========================================\n";
if (empty($issues)) {
    echo "✅ كل شيء يعمل بشكل صحيح!\n";
} else {
    echo "❌ تم العثور على " . count($issues) . " مشكلة:\n\n";
    foreach ($issues as $index => $issue) {
        echo "المشكلة #" . ($index + 1) . ": $issue\n";
    }
    
    if (!empty($fixes)) {
        echo "\n💡 الحلول المقترحة:\n";
        foreach (array_unique($fixes) as $fix) {
            echo "   $fix\n";
        }
    }
}
echo "==========================================\n";

