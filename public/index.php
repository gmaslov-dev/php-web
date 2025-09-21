<?php

require __DIR__ . '/../vendor/autoload.php';

session_start();

$app = require __DIR__ . '/../bootstrap/app.php';
$app->run();
