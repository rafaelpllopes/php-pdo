<?php

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . 'banco.sqlite';
$pdo = new PDO("sqlite:$databasePath");

echo "conected" . PHP_EOL;

$pdo->exec(
    'CREATE TABLE students (id INTEGER PRIMARY KEY, name TEXT, birth_date TEXT);
'
);
