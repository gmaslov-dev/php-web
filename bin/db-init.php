<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpWeb\Database\Database;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

echo "🚀 Starting database initialization...\n";

try {
    $db = new Database();

    echo "✅ Database created or already exists.\n";
    echo "🔧 Creating tables...\n";
    $db->createTables();
    echo "✅ Tables created.\n";

    echo "🌱 Seeding data...\n";
    $db->seedData();
    echo "✅ Data seeded successfully!\n";

} catch (\Exception $e) {
    echo "❌ Error during initialization: " . $e->getMessage() . "\n";
}