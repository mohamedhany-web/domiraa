<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$path = (string) ($argv[1] ?? '');
if ($path === '') {
    fwrite(STDERR, "Usage: php scripts/check_r2_exists.php <path>\n");
    exit(1);
}

$disk = \App\Helpers\StorageHelper::publicDisk();
$st = \Illuminate\Support\Facades\Storage::disk($disk);

echo "disk={$disk}\n";
echo "path={$path}\n";
echo 'exists=' . ($st->exists($path) ? 'yes' : 'no') . "\n";

