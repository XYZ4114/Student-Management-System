<?php
include("../includes/admin_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!$_SESSION) session_start();

// Check if enroll_no is set
if (!isset($_GET['enroll_no'])) {
    header("Location: view-students.php?error=Missing+Enroll+No");
    exit;
}

$enroll_no = $conn->real_escape_string($_GET['enroll_no']);

// Check if student exists
$check = $conn->query("SELECT * FROM students WHERE enroll_no = '$enroll_no'");
if ($check->num_rows === 0) {
    header("Location: view-students.php?error=Student+Not+Found");
    exit;
}

// Delete from related tables
$conn->query("DELETE FROM marks WHERE enroll_no = '$enroll_no'");
$conn->query("DELETE FROM attendance WHERE enroll_no = '$enroll_no'");
$conn->query("DELETE FROM students WHERE enroll_no = '$enroll_no'");
$conn->query("DELETE FROM users WHERE enroll_no = '$enroll_no'");

// Optional: Delete profile image from server (if needed)
$student = $check->fetch_assoc();
$profileImage = $student['profile_image'];
if ($profileImage && $profileImage!='default_pp.jpg' && file_exists("../assets/uploads/" . $profileImage)) {
    unlink("../assets/uploads/" . $profileImage);
}

// Redirect with success
header("Location: view-students.php?success=Student+Deleted");
exit;
?>
