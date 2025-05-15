<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$studentRepository = new PdoStudentRepository($connection);

// realizo processo de definição da turma

try {
    $connection->beginTransaction();

    $aStudent = new Student(
        null,
        "Teste test",
        new DateTimeImmutable('1986-02-21')
    );
    $studentRepository->save($aStudent);

    $anotherStudent = new Student(
        null,
        "Teste do Teste",
        new DateTimeImmutable('1986-02-21')
    );
    $studentRepository->save($anotherStudent);

    $connection->commit();
} catch (\RuntimeException $erro) {
    echo $erro->getMessage() . PHP_EOL;
    $connection->rollBack();
}
