<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'hotel_db',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    // Check if table exists
    if (!$capsule->schema()->hasTable('reservations')) {
        echo "Table 'reservations' does not exist!\n";
        // Show what tables do exist
        $tables = $capsule->connection()->select("SHOW TABLES");
        echo "Available tables:\n";
        foreach ($tables as $table) {
            echo "- ".array_values((array)$table)[0]."\n";
        }
    } else {
        echo "Table 'reservations' exists.\n";

        // Get column information
        $columns = $capsule->connection()->select("DESCRIBE reservations");
        echo "\nTable structure:\n";
        foreach ($columns as $col) {
            echo "- {$col->Field}: {$col->Type} {$col->Null} {$col->Key} {$col->Default} {$col->Extra}\n";
        }

        // Check if we have any data
        $count = $capsule->table('reservations')->count();
        echo "\nRow count: {$count}\n";

        if ($count > 0) {
            $sample = $capsule->table('reservations')->first();
            echo "\nSample row:\n";
            foreach ($sample as $key => $value) {
                echo "- {$key}: {$value}\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
?>