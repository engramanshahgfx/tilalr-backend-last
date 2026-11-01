<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $tbl = current((array)$t);
    try {
        $count = DB::table($tbl)->count();
        if ($count > 0) {
            echo "TABLE: {$tbl} ({$count} rows)\n";
        }
    } catch (\Exception $e) {
    }
}
