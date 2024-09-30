<?php
// edit_student.php
include 'db.php'; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];

    // Prepare and execute the update query
    $stmt = $pdo->prepare("UPDATE students SET name = ?, subject = ?, marks = ? WHERE id = ?");
    $success = $stmt->execute([$name, $subject, $marks, $id]);

    if ($success) {
        echo json_encode(['success' => true,'message'=>'Record is updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update student.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
