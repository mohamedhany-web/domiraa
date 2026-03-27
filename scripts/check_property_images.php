<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$propertyId = (int) ($argv[1] ?? 23);

$count = \App\Models\PropertyImage::where('property_id', $propertyId)->count();
echo "property_id={$propertyId}\n";
echo "count={$count}\n";

$last = \App\Models\PropertyImage::where('property_id', $propertyId)->latest('id')->first();
if ($last) {
    echo "last_id={$last->id}\n";
    echo "last_path={$last->image_path}\n";
    echo "last_url={$last->url}\n";
}

