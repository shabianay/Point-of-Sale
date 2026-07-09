<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$products = \App\Models\Product::all(['id', 'name', 'sku']);
foreach ($products as $p) {
    echo $p->id . '|' . $p->name . '|' . $p->sku . "\n";
}
