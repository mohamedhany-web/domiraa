<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$disk = \App\Helpers\StorageHelper::publicDisk();
echo "disk={$disk}\n";

$source = __DIR__ . '/../storage/app/tmp_test.png';
if (!file_exists($source)) {
    fwrite(STDERR, "Missing tmp_test.png at {$source}\n");
    exit(1);
}

$file = new \Illuminate\Http\UploadedFile(
    $source,
    'tmp_test.png',
    'image/png',
    null,
    true
);

$svc = new \App\Services\ImageService();
$res = $svc->upload($file, 'properties', true);

echo "path={$res['path']}\n";
echo "thumb=" . ($res['thumbnail'] ?? '') . "\n";
echo "url={$res['url']}\n";

$st = \Illuminate\Support\Facades\Storage::disk($disk);
echo 'exists=' . ($st->exists($res['path']) ? 'yes' : 'no') . "\n";
if (!empty($res['thumbnail'])) {
    echo 'thumb_exists=' . ($st->exists($res['thumbnail']) ? 'yes' : 'no') . "\n";
}

