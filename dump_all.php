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
        $rows = DB::table($tbl)->get();
        foreach ($rows as $row) {
            $str = json_encode($row);
            if (stripos($str, 'malaysia') !== false || stripos($str, 'vietnam') !== false || stripos($str, 'Thailand') !== false || stripos($str, 'Honeymoon') !== false) {
                echo "TABLE: " . $tbl . "\n";
                echo "ROW: " . $str . "\n\n";
            }
        }
    } catch (\Exception $e) {
    }
}
