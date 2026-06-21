<?php
session_start();
header('Content-Type: application/json');

$watchFiles = [
    __DIR__ . '/app/views/layouts/main.php',
    __DIR__ . '/public/css/style.css',
    __DIR__ . '/public/css/page-header.css',
    __DIR__ . '/public/css/header-menu.css',
];

$current = 0;
foreach ($watchFiles as $f) {
    $t = filemtime($f);
    if ($t > $current) $current = $t;
}

$last = $_SESSION['dev_last_mtime'] ?? 0;
$_SESSION['dev_last_mtime'] = $current;

echo json_encode(['reload' => $current > $last, 'mtime' => $current]);
