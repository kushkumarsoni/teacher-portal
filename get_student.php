<?php
include 'db.php';

if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $statement = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $statement->execute([$studentId]);
    $student = $statement->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        echo json_encode($student);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }
}
?>
