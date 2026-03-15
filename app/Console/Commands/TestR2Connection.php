<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestR2Connection extends Command
{
    protected $signature = 'r2:test';
    protected $description = 'اختبار الاتصال بـ Cloudflare R2 ورفع ملف تجريبي';

    public function handle(): int
    {
        $diskName = config('filesystems.public_uploads_disk', 'public');

        $this->info('قرص الرفع الحالي: ' . $diskName);

        if ($diskName !== 'r2') {
            $this->warn('FILESYSTEM_DISK_PUBLIC ليس r2. ضع في .env: FILESYSTEM_DISK_PUBLIC=r2');
            return 1;
        }

        $this->info('جاري الاختبار...');

        $testPath = 'test-r2-connection/' . uniqid() . '.txt';
        $content = 'Test upload at ' . now()->toIso8601String();

        try {
            Storage::disk('r2')->put($testPath, $content);
            $this->info('تم رفع الملف بنجاح: ' . $testPath);

            $exists = Storage::disk('r2')->exists($testPath);
            $this->info('التحقق من الوجود: ' . ($exists ? 'نعم' : 'لا'));

            if ($exists) {
                $read = Storage::disk('r2')->get($testPath);
                $this->info('قراءة المحتوى: ' . ($read === $content ? 'متطابق' : 'مختلف'));
                Storage::disk('r2')->delete($testPath);
                $this->info('تم حذف الملف التجريبي.');
            }

            $this->newLine();
            $this->info('الاتصال بـ R2 يعمل بشكل صحيح.');
            return 0;
        } catch (\Throwable $e) {
            $this->error('فشل الاختبار: ' . $e->getMessage());
            $this->newLine();
            $this->line('التفاصيل:');
            $this->line($e->getTraceAsString());
            return 1;
        }
    }
}
