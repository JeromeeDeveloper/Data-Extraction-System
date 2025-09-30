<?php
// Bootstrap Laravel and inspect Microbanker tables

use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
\Illuminate\Contracts\Console\Kernel::class;
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();

function printTableInfo(string $table): void {
    echo "--STRUCT {$table}--\n";
    $cols = DB::connection('sqlsrv2')->select(
        "SELECT COLUMN_NAME, DATA_TYPE FROM Microbanker.INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? ORDER BY ORDINAL_POSITION",
        [$table]
    );
    foreach ($cols as $c) {
        echo $c->COLUMN_NAME . '|' . $c->DATA_TYPE . "\n";
    }
    echo "--SAMPLE {$table}--\n";
    $rows = DB::connection('sqlsrv2')->select("SELECT TOP 5 * FROM Microbanker.dbo." . $table);
    $i = 1;
    foreach ($rows as $row) {
        echo "---ROW {$i}---\n";
        foreach ($row as $k => $v) {
            if (is_null($v)) {
                $v = 'NULL';
            } elseif (is_string($v)) {
                $v = substr($v, 0, 200);
            }
            echo $k . ': ' . $v . "\n";
        }
        $i++;
    }
}

$tables = ['CIFIDINFO','CIFADDRINFO','LNINST','LNACC'];
foreach ($tables as $t) {
    try {
        printTableInfo($t);
    } catch (\Throwable $e) {
        echo "--ERROR {$t}--\n" . $e->getMessage() . "\n";
    }
}

// Try to print some foreign key relationships for context
try {
    echo "--FOREIGN_KEYS--\n";
    $rels = DB::connection('sqlsrv2')->select("SELECT fk.name AS FK, tp.name AS parent, cp.name AS parent_col, tr.name AS ref, cr.name AS ref_col
        FROM sys.foreign_keys fk
        JOIN sys.foreign_key_columns fkc ON fkc.constraint_object_id = fk.object_id
        JOIN sys.tables tp ON tp.object_id = fkc.parent_object_id
        JOIN sys.columns cp ON cp.object_id = fkc.parent_object_id AND cp.column_id = fkc.parent_column_id
        JOIN sys.tables tr ON tr.object_id = fkc.referenced_object_id
        JOIN sys.columns cr ON cr.object_id = fkc.referenced_object_id AND cr.column_id = fkc.referenced_column_id
        ORDER BY fk.name");
    foreach ($rels as $r) {
        echo $r->FK . '|' . $r->parent . '.' . $r->parent_col . '->' . $r->ref . '.' . $r->ref_col . "\n";
    }
} catch (\Throwable $e) {
    echo "--ERROR FOREIGN_KEYS--\n" . $e->getMessage() . "\n";
}


