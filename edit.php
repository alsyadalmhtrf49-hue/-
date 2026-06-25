<?php
require_once 'db_connect.php'; // ربط ملف الاتصال
session_start();

// التأكد من تمرير معرّف الطالب في الرابط
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
$stmt->execute([':id' => $id]);
$student = $stmt->fetch();

// إذا لم يتم العثور على الطالب
if (!$student) {
    $_SESSION['msg'] = "Student record not found.";
    $_SESSION['msg_type'] = "error";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Information</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h2>Edit Student Profile</h2>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert <?php echo $_SESSION['msg_type']; ?>">
                <?php echo $_SESSION['msg']; unset($_SESSION['msg']); unset($_SESSION['msg_type']); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h3>Update Information for: <?php echo htmlspecialchars($student['student_name']); ?></h3>
            <form action="process.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="student_name" value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Student Number:</label>
                    <input type="text" name="student_number" value="<?php echo htmlspecialchars($student['student_number']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Year of Study:</label>
                    <input type="number" name="year_of_study" min="1" max="7" value="<?php echo htmlspecialchars($student['year_of_study']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Batch Name:</label>
                    <input type="text" name="batch_name" value="<?php echo htmlspecialchars($student['batch_name']); ?>" required>
                </div>
                
                <button type="submit" name="update" class="btn-submit" style="background-color: #007bff;">Update Records</button>
                <a href="index.php" class="btn-cancel">Cancel</a>
            </form>
        </div>
    </div>

</body>
</html>