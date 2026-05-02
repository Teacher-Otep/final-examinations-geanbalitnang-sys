<?php
require_once __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $address = $_POST['address'] ?? '';
    $contact = $_POST['contact'] ?? '';

    try {
        $sql = "UPDATE students SET name = :name, surname = :surname, middlename = :middlename, address = :address, contact_number = :contact WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id'         => $id,
            ':name'       => $name,
            ':surname'    => $surname,
            ':middlename' => $middlename,
            ':address'    => $address,
            ':contact'    => $contact
        ]);

        header("Location: ../index.php?status=updated");
        exit();
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}

// GET request - fetch a single student by ID
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        $student = $stmt->fetch();

        header('Content-Type: application/json');
        if ($student) {
            echo json_encode($student);
        } else {
            echo json_encode(['error' => 'Student not found']);
        }
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
