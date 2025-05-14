<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . 'banco.sqlite';
$pdo = new PDO("sqlite:$databasePath");

/**
 * Criar uma camada de segurançã no codigo, não deixa inserir direto.
 */
// $student = new Student(
//     null,
//     "Rafael', ''); DROP TABLE students; -- ",
//     new DateTimeImmutable('1986-02-21')
// );

$student = new Student(
    null,
    "Rafael Lopes",
    new DateTimeImmutable('1986-02-21')
);

/**
 * bindValue
 * $sqlInsert = "INSERT INTO students (name, birth_date) VALUES (?, ?);";
 * $statement = $pdo->prepare($sqlInsert);
 * $statement->bindValue(1, $student->name());
 * $statement->bindValue(2, $student->birthDate()->format('Y-m-d'));
 */

$sqlInsert = "INSERT INTO students (name, birth_date) 
VALUES (:name, :birth_date);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(':name', $student->name());
$statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));

if ($statement->execute()) {
    echo "Estudante cadastrado com sucesso." . PHP_EOL;
}


