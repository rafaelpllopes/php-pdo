<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . 'banco.sqlite';
$pdo = new PDO("sqlite:$databasePath");

$query = "SELECT * FROM students;";
echo $query . PHP_EOL;
$statement = $pdo->query($query);

/**
 * Traz somente uma coluna
 */
var_dump($statement->fetchColumn(1));
exit();

/**
 * Caso precise carregar individual
 */
while ($studentData = $statement->fetch(PDO::FETCH_ASSOC)) {
    $student = new Student(
        $studentData['id'],
        $studentData['name'],
        new DateTimeImmutable($studentData['birth_date'])
    );
    echo $student->age() . PHP_EOL;
}


/**
 * fetch
 */

$student = $statement->fetch(PDO::FETCH_ASSOC);
var_dump(($student));

/**
 * fetchAll
 * Forma mais comum
 */
$studentDataList = $statement->fetchAll(PDO::FETCH_ASSOC);
$studentList = [];
foreach ($studentDataList as $studentData) {
    $studentList[] = new Student(
        $studentData['id'],
        $studentData['name'],
        new DateTimeImmutable($studentData['birth_date'])
    );
}

var_dump($studentList);
