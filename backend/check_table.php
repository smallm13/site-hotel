<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Get table columns
$columns = DB::getSchemaBuilder()->getColumnListing('reservations');
echo "Columns in reservations table:\n";
print_r($columns);

// Get column details
$details = DB::select("DESCRIBE reservations");
echo "\nColumn details:\n";
print_r($details);
?>