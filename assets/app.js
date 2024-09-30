document.addEventListener('DOMContentLoaded', function () {
    // Toggle Sidebar Links
    const studentsLink = document.getElementById('students-link');
    if (studentsLink) {
        studentsLink.addEventListener('click', function () {
            document.getElementById('student-content').style.display = 'block';
        });
    }

    // Add Student Modal Toggle
    const addStudentBtn = document.getElementById('add-student-btn');
    const addStudentModal = document.getElementById('add-student-modal');
    const closeBtn = document.querySelector('.close-btn'); // Select the close button

     // Close the modal when the "×" close button is clicked
     closeBtn.addEventListener('click', function() {
        addStudentModal.style.display = 'none';
    });

     // Close the modal when clicking outside of the modal content
     window.addEventListener('click', function(event) {
        if (event.target === addStudentModal) {
            addStudentModal.style.display = 'none';
        }
    });

    if (addStudentBtn && addStudentModal) {
        addStudentBtn.addEventListener('click', function () {
            addStudentModal.style.display = 'block';
        });

        window.addEventListener('click', function (event) {
            if (event.target == addStudentModal) {
                addStudentModal.style.display = 'none';
            }
        });
    }

    // Edit Student Modal Toggle
    const editStudentModal = document.getElementById('edit-student-modal');
    const editCloseBtn = document.getElementById('edit-close-btn');

    if (editCloseBtn && editStudentModal) {
        editCloseBtn.addEventListener('click', function () {
            editStudentModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target == editStudentModal) {
                editStudentModal.style.display = 'none';
            }
        });
    }

    // Show edit modal when edit button is clicked
    window.editStudent = function (studentId) {
        editStudentModal.style.display = 'block';
        const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
        if (row) {
            const studentName = row.querySelector('.student-name')?.textContent;
            const studentSubject = row.querySelector('.student-subject')?.textContent;
            const studentMarks = row.querySelector('.student-marks')?.textContent;

            // Populate the modal with existing data
            if (studentName && studentSubject && studentMarks) {
                document.getElementById('edit-student-id').value = studentId;
                document.getElementById('edit-name').value = studentName;
                document.getElementById('edit-subject').value = studentSubject;
                document.getElementById('edit-marks').value = studentMarks;

                // Show the modal
                editStudentModal.style.display = 'block';
            }
        }
    };

    // Handle form submission for adding student
    document.getElementById('add-student-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get form data
        const name = document.getElementById('name').value;
        const subject = document.getElementById('subject').value;
        const marks = document.getElementById('marks').value;

        // Create a FormData object to send data via POST
        const formData = new FormData();
        formData.append('name', name);
        formData.append('subject', subject);
        formData.append('marks', marks);

        // Send AJAX request to add the student
        fetch('add_student.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            if (data.success) {
                
                alert(data.message);

                // Close the modal
                document.getElementById('add-student-modal').style.display = 'none';

                // Reset the form
                document.getElementById('add-student-form').reset();

                // Dynamically add the new student to the table
                const studentTable = document.querySelector('table tbody');
                const newRow = `
                    <tr data-student-id="${data.student.id}">
                        <td class="student-name">${data.student.name}</td>
                        <td class="student-subject">${data.student.subject}</td>
                        <td class="student-marks">${data.student.marks}</td>
                        <td>
                            <button onclick="editStudent(${data.student.id})">Edit</button>
                            <button onclick="deleteStudent(${data.student.id})">Delete</button>
                        </td>
                    </tr>`;
                studentTable.insertAdjacentHTML('beforeend', newRow);
            } else {
                alert('Failed to add student.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the student.');
        });
    });


    document.getElementById('edit-student-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting the traditional way
    
        const studentId = document.getElementById('edit-student-id').value;
        const studentName = document.getElementById('edit-name').value;
        const studentSubject = document.getElementById('edit-subject').value;
        const studentMarks = document.getElementById('edit-marks').value;
    
        // Prepare form data for sending via AJAX
        const formData = new FormData();
        formData.append('id', studentId);
        formData.append('name', studentName);
        formData.append('subject', studentSubject);
        formData.append('marks', studentMarks);
    
        // Send AJAX request to edit_student.php
        fetch('edit_student.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Update the table with new data (optional)
                const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
                if (row) {
                    row.querySelector('.student-name').textContent = studentName;
                    row.querySelector('.student-subject').textContent = studentSubject;
                    row.querySelector('.student-marks').textContent = studentMarks;
                }
    
                // Close the modal
                document.getElementById('edit-student-modal').style.display = 'none';
            } else {
                console.error('Update failed:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

function deleteStudent(studentId) {
    if (confirm("Are you sure you want to delete this student?")) {
        const formData = new FormData();
        formData.append('id', studentId);

        fetch('delete_student.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                // Successfully deleted, refresh the page or remove the row
                window.location.reload();
            } else {
                // Handle error response
                alert("Failed to delete the student.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while deleting the student.");
        });
    }
}

