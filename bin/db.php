<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpWeb\Database;

$db = new Database();

echo "🔧 Creating database and tables...\n";
$db->createDatabase();
$db->createTables();

echo "🌱 Seeding test data...\n";
$db->seedData();

echo "✅ Done!\n";