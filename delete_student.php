<?php
// delete_student.php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $deleteStmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
    $success = $deleteStmt->execute(['id' => $id]);

    if ($success) {
        echo json_encode(['success' => true,'message'=>'Record is deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete student.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

?>
