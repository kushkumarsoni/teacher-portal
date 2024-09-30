<?php
// add_student.php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];

    try {
        // Check if a student with the same name and subject exists
        $stmt = $pdo->prepare("SELECT * FROM students WHERE name = :name AND subject = :subject");
        $stmt->execute(['name' => $name, 'subject' => $subject]);
        $student = $stmt->fetch();

        if ($student) {
            // Update marks if the student already exists
            $updateStmt = $pdo->prepare("UPDATE students SET marks = :marks WHERE id = :id");
            $success = $updateStmt->execute(['marks' => $marks, 'id' => $student['id']]);
            $studentId = $student['id'];
        } else {
            // Add a new student if they don't exist
            $insertStmt = $pdo->prepare("INSERT INTO students (name, subject, marks) VALUES (:name, :subject, :marks)");
            $success = $insertStmt->execute(['name' => $name, 'subject' => $subject, 'marks' => $marks]);
            $studentId = $pdo->lastInsertId(); // Get the last inserted ID
        }

        if ($success) {
            // Prepare the response with student data
            $response = [
                'success' => true,
                'message' => 'Record is added/updated successfully.',
                'student' => [
                    'id' => $studentId,
                    'name' => $name,
                    'subject' => $subject,
                    'marks' => $marks
                ]
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update student.']);
        }
    } catch (PDOException $e) {
        // Handle any database errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
