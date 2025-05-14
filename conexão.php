<?php

$pathDB = __DIR__ . 'banco.sqlite';
$pdo = new PDO("sqlite:$pathDB");

echo "conected" . PHP_EOL;