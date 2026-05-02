<?php
require_once __DIR__ . '/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY id ASC");
    $students = $stmt->fetchAll();
    
    header('Content-Type: application/json');
    echo json_encode($students);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
