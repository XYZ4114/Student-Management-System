<?php
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);
$action = $_GET['action'];

// Fetch the request
$request = $conn->query("SELECT * FROM profile_update_requests WHERE id = $id")->fetch_assoc();
if (!$request || $request['status'] !== 'Pending') {
    die("Invalid or already processed request.");
}

if ($action === 'approve') {
    // Update the student's field
    $field = $request['field_name'];
    $value = $conn->real_escape_string($request['requested_value']);
    $enroll_no = $conn->real_escape_string($request['enroll_no']);

    // Update student record
    $conn->query("UPDATE students SET `$field` = '$value' WHERE enroll_no = '$enroll_no'");

    // Update request status
    $conn->query("UPDATE profile_update_requests SET status = 'Approved' WHERE id = $id");

    echo "<script>alert('Request approved and profile updated.'); window.location.href='manage_requests.php';</script>";

} elseif ($action === 'reject') {
    $conn->query("UPDATE profile_update_requests SET status = 'Rejected' WHERE id = $id");
    echo "<script>alert('Request rejected.'); window.location.href='manage_requests.php';</script>";
} else {
    die("Invalid action.");
}
?>
