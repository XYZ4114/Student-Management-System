<?php
session_start();
require_once "includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enroll_no = trim($_POST['enroll_no']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE enroll_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $enroll_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['enroll_no'] = $user['enroll_no'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_image'] = 'default_pp.jpg';

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: student/dashboard.php");
            }
            exit();
        } else {
            // Wrong password
            header("Location: index.php?error=Invalid+credentials");
            exit();
        }
    } else {
        // No user found
        header("Location: index.php?error=No+such+user+found");
        exit();
    }
} else {
    // Accessing login.php directly without POST
    header("Location: index.php");
    exit();
}
