<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Phone;
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
        $studentDataList = $stmt->fetchAll();
        $studentList = [];

        foreach ($studentDataList as $studentData) {
            $studentList = new Student(
                $studentData['id'],
                $studentData['name'],
                new DateTimeImmutable($studentData['birth_date'])
            );

            // $student = new Student(
            //     $studentData['id'],
            //     $studentData['name'],
            //     new DateTimeImmutable($studentData['birth_date'])
            // );

            // $studentList[] = $student = new Student(
            //     $studentData['id'],
            //     $studentData['name'],
            //     new DateTimeImmutable($studentData['birth_date'])
            // );

            // $this->fillPhonesOf($student);

            // $studentList[] = $student;
        }

        return $studentList;
    }

    // private function fillPhonesOf(Student $student): void
    // {
    //     $sqlQuery = 'SELECT id, area_code, number FROM phones WHERE student_id = ?;';
    //     $stmt = $this->connection->prepare($sqlQuery);
    //     $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);
    //     $stmt->execute();

    //     $phoneDataList = $stmt->fetchAll();

    //     foreach ($phoneDataList as $phoneData) {
    //         $phone = new Phone(
    //             $phoneData['id'],
    //             $phoneData['area_code'],
    //             $phoneData['number']
    //         );

    //         $student->addPhone($phone);
    //     }
    // }

    #[\Override]
    public function studentsWithPhones(): array
    {
        $sqlQuery = 'SELECT st.id,
                        st.name,
                        st.birth_date,
                        ph.id as phone_id,
                        ph.area_code,
                        ph.number 
                    FROM students st
                    INNER JOIN phones ph ON st.id = ph.student_id;';
        $stmt = $this->connection->query($sqlQuery);
        $result = $stmt->fetchAll();
        $studentList = [];

        foreach ($result as $row) {
            if (!array_key_exists($row['id'], $studentList)) {
                $studentList[$row['id']] = new Student(
                    $row['id'],
                    $row['name'],
                    new DateTimeImmutable($row['birth_date'])
                );
            }
            $phone = new Phone ($row['phone_id'], $row['area_code'], $row['number']);
            $studentList[$row['id']]->addPhone($phone);
        }
        
        return $studentList;
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
        $stmt->bindValue(':name', $student->name());
        $stmt->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));

        $success = $stmt->execute();

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