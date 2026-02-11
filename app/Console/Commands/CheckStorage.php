<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CheckStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'فحص شامل لإعدادات التخزين والصور';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 بدء الفحص الشامل لإعدادات التخزين...');
        $this->newLine();

        $issues = [];
        $fixes = [];

        // 1. فحص APP_URL
        $this->checkAppUrl($issues, $fixes);

        // 2. فحص Symbolic Link
        $this->checkSymbolicLink($issues, $fixes);

        // 3. فحص الصلاحيات
        $this->checkPermissions($issues, $fixes);

        // 4. فحص المجلدات
        $this->checkDirectories($issues, $fixes);

        // 5. فحص Route البديل
        $this->checkStorageRoute($issues);

        // 6. فحص Config Cache
        $this->checkConfigCache($issues, $fixes);

        // 7. اختبار URL Generation
        $this->testUrlGeneration($issues);

        // عرض النتائج
        $this->displayResults($issues, $fixes);

        return $issues ? 1 : 0;
    }

    protected function checkAppUrl(&$issues, &$fixes)
    {
        $this->info('📋 1. فحص APP_URL...');
        
        $appUrl = config('app.url');
        $envUrl = env('APP_URL');
        
        if (!$appUrl || $appUrl === 'http://localhost' || $appUrl === 'http://127.0.0.1:8000') {
            $issues[] = [
                'type' => 'APP_URL',
                'message' => "APP_URL غير مضبوط بشكل صحيح: {$appUrl}",
                'fix' => "تأكد من تعيين APP_URL في ملف .env إلى عنوان الموقع الفعلي (مثال: https://yourdomain.com)"
            ];
        } else {
            $this->line("   ✅ APP_URL: {$appUrl}");
        }

        if ($envUrl && ($envUrl === 'http://localhost' || $envUrl === 'http://127.0.0.1:8000')) {
            $issues[] = [
                'type' => 'APP_URL',
                'message' => "APP_URL في .env يحتاج تحديث: {$envUrl}",
                'fix' => "قم بتحديث APP_URL في ملف .env"
            ];
        }

        $this->newLine();
    }

    protected function checkSymbolicLink(&$issues, &$fixes)
    {
        $this->info('🔗 2. فحص Symbolic Link...');
        
        $storageLink = public_path('storage');
        $target = storage_path('app/public');

        if (!file_exists($storageLink)) {
            $issues[] = [
                'type' => 'SYMLINK',
                'message' => 'الرابط الرمزي غير موجود: public/storage',
                'fix' => 'php artisan storage:link'
            ];
            $fixes[] = 'php artisan storage:link';
        } else {
            // Check if it's a symlink (works on both Unix and Windows)
            $isLink = is_link($storageLink) || 
                     (is_dir($storageLink) && file_exists($storageLink . DIRECTORY_SEPARATOR . '.laravel-link'));
            
            // On Windows, also check if it's a junction or symlink
            if (PHP_OS_FAMILY === 'Windows') {
                $isLink = $isLink || @is_dir($storageLink);
            }
            
            if (!$isLink && is_dir($storageLink)) {
                // Check if it's actually a directory with files (not a link)
                $files = @scandir($storageLink);
                if ($files && count($files) > 2) {
                    // It's a real directory, not a symlink
                    $issues[] = [
                        'type' => 'SYMLINK',
                        'message' => 'public/storage موجود لكنه مجلد وليس رابط رمزي',
                        'fix' => 'rm -rf public/storage && php artisan storage:link'
                    ];
                    $fixes[] = 'rm -rf public/storage && php artisan storage:link';
                } else {
                    // Might be a symlink, check target
                    $linkTarget = @readlink($storageLink);
                    if ($linkTarget) {
                        $this->line("   ✅ الرابط الرمزي موجود ويشير إلى: {$linkTarget}");
                    } else {
                        $this->line("   ✅ public/storage موجود");
                    }
                }
            } else {
                $linkTarget = @readlink($storageLink);
                $realTarget = realpath($target);
                
                // Normalize paths for comparison
                $normalizedLink = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $linkTarget ?? '');
                $normalizedTarget = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $target);
                $normalizedRelative = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, '../storage/app/public');
                
                if ($linkTarget && 
                    $normalizedLink !== $normalizedTarget && 
                    $normalizedLink !== $normalizedRelative &&
                    !str_ends_with($normalizedLink, 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public')) {
                    $issues[] = [
                        'type' => 'SYMLINK',
                        'message' => "الرابط الرمزي يشير إلى مسار خاطئ: {$linkTarget}",
                        'fix' => 'rm -rf public/storage && php artisan storage:link'
                    ];
                    $fixes[] = 'rm -rf public/storage && php artisan storage:link';
                } else {
                    $this->line("   ✅ الرابط الرمزي موجود ويشير بشكل صحيح");
                }
            }
        }

        $this->newLine();
    }

    protected function checkPermissions(&$issues, &$fixes)
    {
        $this->info('🔐 3. فحص الصلاحيات...');
        
        $paths = [
            'storage' => storage_path(),
            'storage/app' => storage_path('app'),
            'storage/app/public' => storage_path('app/public'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        foreach ($paths as $name => $path) {
            if (!is_writable($path)) {
                $issues[] = [
                    'type' => 'PERMISSIONS',
                    'message' => "المجلد غير قابل للكتابة: {$name}",
                    'fix' => "chmod -R 775 {$path}"
                ];
                $fixes[] = "chmod -R 775 {$path}";
            } else {
                $this->line("   ✅ {$name}: قابل للكتابة");
            }
        }

        $this->newLine();
    }

    protected function checkDirectories(&$issues, &$fixes)
    {
        $this->info('📁 4. فحص المجلدات المطلوبة...');
        
        $requiredDirs = [
            'properties',
            'rooms',
            'receipts',
            'ownership_proofs',
            'contracts',
            'documents',
            'images',
            'images/thumbnails',
        ];

        foreach ($requiredDirs as $dir) {
            $fullPath = storage_path("app/public/{$dir}");
            if (!is_dir($fullPath)) {
                $issues[] = [
                    'type' => 'DIRECTORY',
                    'message' => "المجلد غير موجود: storage/app/public/{$dir}",
                    'fix' => "mkdir -p {$fullPath}"
                ];
                $fixes[] = "mkdir -p {$fullPath}";
            } else {
                $this->line("   ✅ {$dir}: موجود");
            }
        }

        $this->newLine();
    }

    protected function checkStorageRoute(&$issues)
    {
        $this->info('🛣️  5. فحص Route البديل...');
        
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $storageRoute = null;
        
        foreach ($routes as $route) {
            if ($route->uri() === 'storage/{path}') {
                $storageRoute = $route;
                break;
            }
        }

        if (!$storageRoute) {
            $issues[] = [
                'type' => 'ROUTE',
                'message' => 'Route البديل للصور غير موجود في routes/web.php',
                'fix' => 'تأكد من وجود Route::get(\'/storage/{path}\', ...) في routes/web.php'
            ];
        } else {
            $this->line("   ✅ Route البديل موجود");
        }

        $this->newLine();
    }

    protected function checkConfigCache(&$issues, &$fixes)
    {
        $this->info('💾 6. فحص Config Cache...');
        
        $configCache = base_path('bootstrap/cache/config.php');
        
        if (file_exists($configCache)) {
            $cacheTime = filemtime($configCache);
            $envTime = filemtime(base_path('.env'));
            
            if ($envTime > $cacheTime) {
                $issues[] = [
                    'type' => 'CACHE',
                    'message' => 'ملف .env تم تحديثه بعد cache، يحتاج إعادة بناء',
                    'fix' => 'php artisan config:clear && php artisan config:cache'
                ];
                $fixes[] = 'php artisan config:clear && php artisan config:cache';
            } else {
                $this->line("   ✅ Config cache محدث");
            }
        } else {
            $this->line("   ℹ️  Config cache غير موجود (طبيعي في development)");
        }

        $this->newLine();
    }

    protected function testUrlGeneration(&$issues)
    {
        $this->info('🧪 7. اختبار إنشاء URLs...');
        
        try {
            $testPath = 'properties/test.jpg';
            $url = \App\Helpers\StorageHelper::url($testPath);
            
            $this->line("   ✅ URL تم إنشاؤه: {$url}");
            
            // التحقق من أن URL يحتوي على APP_URL
            $appUrl = config('app.url');
            if ($appUrl && strpos($url, $appUrl) === 0) {
                $this->line("   ✅ URL يستخدم APP_URL بشكل صحيح");
            } else {
                $issues[] = [
                    'type' => 'URL_GENERATION',
                    'message' => 'URL لا يستخدم APP_URL بشكل صحيح',
                    'fix' => 'تأكد من تعيين APP_URL في .env'
                ];
            }
        } catch (\Exception $e) {
            $issues[] = [
                'type' => 'URL_GENERATION',
                'message' => "خطأ في إنشاء URL: {$e->getMessage()}",
                'fix' => 'تحقق من إعدادات StorageHelper'
            ];
        }

        $this->newLine();
    }

    protected function displayResults($issues, $fixes)
    {
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════');
        
        if (empty($issues)) {
            $this->info('✅ كل شيء يعمل بشكل صحيح!');
        } else {
            $this->error('❌ تم العثور على ' . count($issues) . ' مشكلة:');
            $this->newLine();

            foreach ($issues as $index => $issue) {
                $this->error("المشكلة #" . ($index + 1) . ": {$issue['type']}");
                $this->line("   📝 {$issue['message']}");
                $this->line("   🔧 الحل: {$issue['fix']}");
                $this->newLine();
            }

            if (!empty($fixes)) {
                $this->info('💡 الأوامر المقترحة للإصلاح:');
                $this->newLine();
                foreach (array_unique($fixes) as $fix) {
                    $this->line("   {$fix}");
                }
            }
        }

        $this->info('═══════════════════════════════════════════════════');
    }
}

