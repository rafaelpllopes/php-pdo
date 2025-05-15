<?php

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . 'banco.sqlite';
$pdo = new PDO('sqlite:' . $databasePath);

echo "conected" . PHP_EOL;

$createTable =     '
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    );

    CREATE TABLE IF NOT EXISTS phones (
        id INTEGER PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,
        FOREIGN KEY (student_id) REFERENCES students(id)
    );
';

$pdo->exec(
    "
        INSERT INTO phones (area_code, number, student_id) VALUES ('01', '111111111', 1);
        INSERT INTO phones (area_code, number, student_id) VALUES ('01', '999999999', 1);
        INSERT INTO phones (area_code, number, student_id) VALUES ('02', '222222222', 2);
        INSERT INTO phones (area_code, number, student_id) VALUES ('02', '888888888', 2);
        INSERT INTO phones (area_code, number, student_id) VALUES ('03', '333333333', 3);
        INSERT INTO phones (area_code, number, student_id) VALUES ('03', '777777777', 3);
    "
);

$pdo->exec($createTable);
