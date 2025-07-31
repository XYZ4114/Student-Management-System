<?php
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['enroll_no']) || !isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit();
}
?>
