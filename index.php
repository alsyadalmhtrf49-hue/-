<?php
require_once 'db_connect.php'; // ربط ملف الاتصال بجلب البيانات
session_start();

// جلب جميع الطلاب الحالية حياً عبر استعلام SELECT
$students = [];
try {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
    $students = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_fetch = "Failed to fetch student records: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration System</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>

    <div class="container">
        <h2>Student Registration System</h2>
        
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert <?php echo $_SESSION['msg_type']; ?>">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); unset($_SESSION['msg_type']); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h3>Register New Student</h3>
            <form action="process.php" method="POST">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="student_name" required>
                </div>
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Student Number:</label>
                    <input type="text" name="student_number" required>
                </div>
                <div class="form-group">
                    <label>Year of Study:</label>
                    <input type="number" name="year_of_study" min="1" max="10" required>
                </div>
                <div class="form-group">
                    <label>Batch Name:</label>
                    <input type="text" name="batch_name" required>
                </div>
                <button type="submit" name="register" class="btn-submit">Register Student</button>
            </form>
        </div>

        <hr>

        <div class="table-container">
            <h3>Registered Students</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Student Number</th>
                        <th>Year</th>
                        <th>Batch</th>
                        <th>Created_at</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) > 0): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['id']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                                <td><?php echo htmlspecialchars($student['year_of_study']); ?></td>
                                <td><?php echo htmlspecialchars($student['batch_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['Created_at']); ?></td>
                                <td>
                                 <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn-edit">Edit</a>
                                   </td><td>   <a href="process.php?delete_id=<?php echo $student['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center;">No students registered yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>