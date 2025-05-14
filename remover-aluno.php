<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__ . 'banco.sqlite';
$pdo = new PDO("sqlite:$databasePath");

$preparedStatement = $pdo->prepare("DELETE FROM students WHERE id=?;");
$preparedStatement->bindValue(1, 1, PDO::PARAM_INT);

if ($preparedStatement->execute()) {
    echo "Aluno removido com sucesso" . PHP_EOL;
}

