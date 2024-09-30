<?php
// home.php
session_start();
include 'db.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch all students from the database
$students = $pdo->query("SELECT * FROM students")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Teacher Portal</title>
</head>
<body>
    <header class="header">
        <h1>Teacher Portal - Student Listing</h1>
        <button id="add-student-btn">Add Student</button> <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th>Marks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr data-student-id="<?= $student['id']; ?>">
                    <td class="student-name"><?= htmlspecialchars($student['name']) ?></td>
                    <td class="student-subject"><?= htmlspecialchars($student['subject']) ?></td>
                    <td class="student-marks"><?= htmlspecialchars($student['marks']) ?></td>
                    <td>
                        <button onclick="editStudent(<?= $student['id'] ?>)">Edit</button>
                        <button onclick="deleteStudent(<?= $student['id'] ?>)">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="add-student-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <form id="add-student-form" action="add_student.php" method="POST">
                <h2>Add Student</h2>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
                <label for="marks">Marks:</label>
                <input type="number" id="marks" name="marks" required>
                <button type="submit">Add Student</button>
            </form>
        </div>
    </div>


    <!-- Edit Student Modal -->
    <div id="edit-student-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" id="edit-close-btn">&times;</span>
            <form id="edit-student-form" method="POST">
                <input type="hidden" id="edit-student-id" name="id">
                <label for="edit-name">Name:</label>
                <input type="text" id="edit-name" name="name" required>
                <label for="edit-subject">Subject:</label>
                <input type="text" id="edit-subject" name="subject" required>
                <label for="edit-marks">Marks:</label>
                <input type="number" id="edit-marks" name="marks" required>
                <button type="submit">Update Student</button>
            </form>
        </div>
    </div>

    <script src="assets/app.js"></script>
</body>
</html>
