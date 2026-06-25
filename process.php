<?php
require_once 'db_connect.php'; // ربط ملف الاتصال
session_start(); // لبدء الجلسة ونقل رسائل التنبيه بين الصفحات


// أواً: عملية إضافة طالب جديد 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // تنظيف البيانات
    $name = htmlspecialchars(trim($_POST['student_name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $student_number = htmlspecialchars(trim($_POST['student_number']));
    $year_of_study = intval($_POST['year_of_study']);
    $batch_name = htmlspecialchars(trim($_POST['batch_name']));

    // التحقق من الحقول والإيميل
    if (empty($name) || empty($email) || empty($student_number) || empty($year_of_study) || empty($batch_name)) {
        $_SESSION['msg'] = "Please fill in all required fields.";
        $_SESSION['msg_type'] = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "Invalid email format.";
        $_SESSION['msg_type'] = "error";
    } else {
        try {
            // استخدام الاستعلامات المجهزة للحماية من 
            $sql = "INSERT INTO students (student_name, email, student_number, year_of_study, batch_name) 
                    VALUES (:name, :email, :student_number, :year_of_study, :batch_name)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':student_number' => $student_number,
                ':year_of_study' => $year_of_study,
                ':batch_name' => $batch_name
            ]);
           $_SESSION['msg'] = "Student registered successfully!";
            $_SESSION['msg_type'] = "success";
        } catch (PDOException $e) {
           if ($e->getCode() == 23000) { // تكرار الحقول الفريدة (Email أو Student Number)
             $_SESSION['msg'] = "Error: Student Number or Email already exists.";
            } else {
                $_SESSION['msg'] = "An error occurred: " . $e->getMessage();
            }
            $_SESSION['msg_type'] = "error";
        }
    }
    header("Location: index.php"); // العودة للصفحة الرئيسية لعرض النتيجة
    exit;
}

// ثانياً: عملية تحديث بيانات طالب (UPDATE)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = htmlspecialchars(trim($_POST['student_name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $student_number = htmlspecialchars(trim($_POST['student_number']));
    $year_of_study = intval($_POST['year_of_study']);
    $batch_name = htmlspecialchars(trim($_POST['batch_name']));

    if (empty($name) || empty($email) || empty($student_number) || empty($year_of_study) || empty($batch_name)) {
        $_SESSION['msg'] = "All fields are required for update.";
        $_SESSION['msg_type'] = "error";
        header("Location: edit.php?id=" . $id);
        exit;
    } else {
        try {
            $sql = "UPDATE students SET student_name = :name, email = :email, 
                    student_number = :student_number, year_of_study = :year_of_study, batch_name = :batch_name 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':student_number' => $student_number,
                ':year_of_study' => $year_of_study,
                ':batch_name' => $batch_name,
                ':id' => $id
            ]);
            $_SESSION['msg'] = "Student record updated successfully!";
            $_SESSION['msg_type'] = "success";
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['msg'] = "Error: Student Number or Email already exists.";
            } else {
                $_SESSION['msg'] = "Update failed: " . $e->getMessage();
            }
            $_SESSION['msg_type'] = "error";
            header("Location: edit.php?id=" . $id);
            exit;
        }
    }
}


// ثالثاً: عملية حذف طالب (DELETE)

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    try {
       // الحذف بالاعتماد على الرقم الفريد id
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $delete_id]);
        
       $_SESSION['msg'] = "Student record deleted successfully.";
        $_SESSION['msg_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Failed to delete student: " . $e->getMessage();
        $_SESSION['msg_type'] = "error";
    }
    header("Location: index.php");
    exit;
}
?>