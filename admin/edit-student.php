<?php
include("../includes/admin_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!$_SESSION)
	session_start();

if (!isset($_GET['enroll_no'])) {
	header("Location: view-students.php");
	exit;
}

$enroll_no = $_GET['enroll_no'];

// Fetch student info
$student = $conn->query("SELECT * FROM students WHERE enroll_no = '$enroll_no'")->fetch_assoc();
$attendance = $conn->query("SELECT * FROM attendance WHERE enroll_no = '$enroll_no'")->fetch_assoc();
$marks_res = $conn->query("SELECT * FROM marks WHERE enroll_no = '$enroll_no'");

$marks = [];
while ($row = $marks_res->fetch_assoc()) {
	$marks[$row['subject_name']] = $row;
}

$subjects = ['English', 'Hindi', 'Mathematics', 'Science', 'SST', 'Computer'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Update student
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$class = $_POST['class'];
	$dob = $_POST['dob'];
	$admission_date = $_POST['admission_date'];
	$gender = $_POST['gender'];
	$address = $_POST['address'];
	$blood_group = $_POST['blood_group'];
	$father_name = $_POST['father_name'];
	$father_phone = $_POST['father_phone'];
	$mother_name = $_POST['mother_name'];
	$mother_phone = $_POST['mother_phone'];

	$conn->query("UPDATE students SET 
		name='$name', email='$email', phone='$phone', class='$class', dob='$dob', 
		admission_date='$admission_date', gender='$gender', address='$address', 
		blood_group='$blood_group', father_name='$father_name', father_phone='$father_phone', 
		mother_name='$mother_name', mother_phone='$mother_phone'
		WHERE enroll_no = '$enroll_no'");

	// Update attendance
	$conn->query("REPLACE INTO attendance SET 
		enroll_no='$enroll_no', 
		pre_ct1_attendance='{$_POST['pre_ct1_attendance']}', 
		pre_mid_attendance='{$_POST['pre_mid_attendance']}', 
		pre_ct2_attendance='{$_POST['pre_ct2_attendance']}', 
		pre_final_attendance='{$_POST['pre_final_attendance']}'");

	// Update marks
	foreach ($subjects as $subject) {
		$ct1 = $_POST["ct1_$subject"] ?? 0;
		$mid = $_POST["mid_$subject"] ?? 0;
		$ct2 = $_POST["ct2_$subject"] ?? 0;
		$final = $_POST["final_$subject"] ?? 0;

		$total = $ct1 + $mid + $ct2 + $final;

		// Automatically set result
		$result = ($ct1 < 10 || $mid < 30 || $ct2 < 10 || $final < 30) ? 'Fail' : 'Pass';

		// Insert/update
		$conn->query("REPLACE INTO marks SET 
        enroll_no = '$enroll_no',
        subject_name = '$subject',
        class_test_1 = '$ct1',
        midyear = '$mid',
        class_test_2 = '$ct2',
        final = '$final',
        total_marks = '$total',
        result = '$result'");
	}


	header("Location: view-students.php?updated=1");
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Edit Student</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
	<div class="container" style="margin-top: 100px;">
		<h3>Edit Student - <?= htmlspecialchars($student['enroll_no']) ?></h3>
		<form method="post">
			<div class="row">
				<div class="col-md-6">
					<label>Name</label>
					<input type="text" name="name" class="form-control" value="<?= $student['name'] ?>">

					<label>Email</label>
					<input type="email" name="email" class="form-control" value="<?= $student['email'] ?>">

					<label>Phone</label>
					<input type="text" name="phone" class="form-control" value="<?= $student['phone'] ?>">

					<label>Class</label>
					<input type="text" name="class" class="form-control" value="<?= $student['class'] ?>">

					<label>Date of Birth</label>
					<input type="date" name="dob" class="form-control" value="<?= $student['dob'] ?>">

					<label>Admission Date</label>
					<input type="date" name="admission_date" class="form-control"
						value="<?= $student['admission_date'] ?>">

					<label>Gender</label>
					<select name="gender" class="form-select">
						<option <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
						<option <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
						<option <?= $student['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
					</select>
				</div>

				<div class="col-md-6">
					<label>Address</label>
					<textarea name="address" class="form-control"><?= $student['address'] ?></textarea>

					<label>Blood Group</label>
					<select name="blood_group" class="form-select">
						<?php foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg): ?>
							<option <?= $student['blood_group'] === $bg ? 'selected' : '' ?>><?= $bg ?></option>
						<?php endforeach; ?>
					</select>

					<label>Father Name</label>
					<input type="text" name="father_name" class="form-control" value="<?= $student['father_name'] ?>">

					<label>Father Phone</label>
					<input type="text" name="father_phone" class="form-control" value="<?= $student['father_phone'] ?>">

					<label>Mother Name</label>
					<input type="text" name="mother_name" class="form-control" value="<?= $student['mother_name'] ?>">

					<label>Mother Phone</label>
					<input type="text" name="mother_phone" class="form-control" value="<?= $student['mother_phone'] ?>">
				</div>
			</div>

			<hr>
			<h5>Attendance</h5>
			<div class="row">
				<?php foreach (['pre_ct1_attendance', 'pre_mid_attendance', 'pre_ct2_attendance', 'pre_final_attendance'] as $field): ?>
					<div class="col-md-3">
						<label><?= ucwords(str_replace('_', ' ', $field)) ?></label>
						<input type="number" name="<?= $field ?>" class="form-control"
							value="<?= $attendance[$field] ?? 0 ?>">
					</div>
				<?php endforeach; ?>
			</div>

			<hr>
			<h5>Marks & Result</h5>
			<?php foreach ($subjects as $subject): ?>
				<div class="card p-3 mb-3">
					<h6><?= htmlspecialchars($subject) ?></h6>
					<div class="row">
						<?php
						$tests = [
							'ct1' => 'class_test_1',
							'mid' => 'midyear',
							'ct2' => 'class_test_2',
							'final' => 'final'
						];
						foreach ($tests as $key => $dbfield): ?>
							<div class="col-md-3">
								<label><?= ucwords(str_replace('_', ' ', $key)) ?></label>
								<input type="number" min="0" max="100" name="<?= $key ?>_<?= $subject ?>" class="form-control"
									value="<?= htmlspecialchars($marks[$subject][$dbfield] ?? 0) ?>">
							</div>
						<?php endforeach; ?>

						<?php
						$ct1 = $marks[$subject]['class_test_1'] ?? 0;
						$mid = $marks[$subject]['midyear'] ?? 0;
						$ct2 = $marks[$subject]['class_test_2'] ?? 0;
						$final = $marks[$subject]['final'] ?? 0;
						$result = ($ct1 < 10 || $mid < 30 || $ct2 < 10 || $final < 30) ? 'Fail' : 'Pass';
						?>
						<div class="col-md-3">
							<label>Result</label>
							<input type="text" class="form-control" value="<?= $result ?>" readonly>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

			<button class="btn btn-primary mb-5 w-100">Update Student</button>
		</form>
	</div>
</body>

</html>