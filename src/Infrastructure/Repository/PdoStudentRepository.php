<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use DateTimeImmutable;
use PDO;

class PdoStudentRepository implements StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    #[\Override]
    public function allStudents(): array
    {
        $statement = $this->connection->query('SELECT * FROM students;');

        return $this->hydrateStudent($statement);
    }

    #[\Override]
    public function studentBirthAt(\DateTimeInterface $birthDate): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM students WHERE birth_date = ?;');
        $stmt->bindValue(1, $birthDate->format('Y-m-d'));
        $stmt->execute();

        return $this->hydrateStudent($$stmt);
    }
    
    private function hydrateStudent(\PDOStatement $stmt): array
    {
        $studentDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $studentList = [];

        foreach ($studentDataList as $studentData) {
            $studentList[] = new Student(
                $studentData['id'],
                $studentData['name'],
                new DateTimeImmutable($studentData['birth_date'])
            );
        }

        return $studentDataList;
    }

    #[\Override]
    public function save(Student $student): bool
    {
        if ($student->id() === null) {
            return $this->insert($student);
        }
        
        return $this->update($student);
    }

    private function insert(Student $student): bool
    {
        $insertQuery = "INSERT INTO students (name, birth_date) 
        VALUES (:name, :birth_date);";
        $stmt = $this->connection->prepare($insertQuery);

        $success = $stmt->execute([
            ':name', $student->name(),
            ':birth_date', $student->birthDate()->format('Y-m-d')
        ]);

        $student->defeneId($this->connection->lastInsertId());

        return $success;
    }

    private function update(Student $student): bool
    {
        $updateQuery = "UPDATE FROM students SET name = :name, birth_date = :birth_date) WHERE id = :id;"; 
        $stmt = $this->connection->prepare($updateQuery);
        $stmt->bindValue(':id', $student->id(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $student->name());
        $stmt->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));

        return $stmt->execute(); 
    }

    #[\Override]
    public function remove(Student $student): bool
    {
        $preparedStatement = $this->connection->prepare("DELETE FROM students WHERE id=:id;");
        $preparedStatement->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $preparedStatement->execute();
    }
}