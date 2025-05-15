<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$query = "SELECT * FROM students;";
echo $query . PHP_EOL;
$statement = $pdo->query($query);

/**
 * Traz somente uma coluna
 */
var_dump($statement->fetchColumn(1));

/**
 * Caso precise carregar individual
 */
while ($studentData = $statement->fetch(PDO::FETCH_ASSOC)) {
    $student = new Student(
        $studentData['id'],
        $studentData['name'],
        new DateTimeImmutable($studentData['birth_date'])
    );
    echo "{$student->name()}, tem {$student->age()} anos" . PHP_EOL;
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
