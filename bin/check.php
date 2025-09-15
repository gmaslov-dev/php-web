<?php
if (in_array('pgsql', \PDO::getAvailableDrivers())) {
    echo "✅ Драйвер pgsql установлен\n";
} else {
    echo "❌ Драйвер pgsql не найден\n";
}