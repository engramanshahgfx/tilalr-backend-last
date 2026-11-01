<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TourismDestination;

$all = TourismDestination::all();
echo "TOTAL COUNT: " . $all->count() . "\n";
foreach ($all as $d) {
    echo "ID: {$d->id} | Title: {$d->title_en} | Region: '{$d->region}' | Active: {$d->active}\n";
}
