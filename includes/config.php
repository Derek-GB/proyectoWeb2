<?php

$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'proyecto_web_ii';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
    die("Error conexión MySQL: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear carpetas necesarias si no existen
$uploadsDir = __DIR__ . '/../assets/uploads';
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

$dataDir = __DIR__ . '/../data';
if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);
$contactsFile = $dataDir . '/contacts.json';
if (!file_exists($contactsFile)) file_put_contents($contactsFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
