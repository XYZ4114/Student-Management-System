<?php
require_once("../includes/db.php");
require_once("../includes/auth.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enroll_no = $_POST['enroll_no'];
    $field = $_POST['field_name'];
    $new_value = $_POST['requested_value'];

    // Fetch current value from DB
    $result = $conn->query("SELECT `$field` FROM students WHERE enroll_no = '$enroll_no'");
    if ($result && $row = $result->fetch_assoc()) {
        $current_value = $row[$field];

        // Insert request
        $stmt = $conn->prepare("INSERT INTO profile_update_requests (enroll_no, field_name, current_value, requested_value) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $enroll_no, $field, $current_value, $new_value);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Request sent to admin.'); window.location.href='view-profile.php';</script>";
    } else {
        echo "<script>alert('Invalid request.'); window.location.href='view-profile.php';</script>";
    }
}
?>
