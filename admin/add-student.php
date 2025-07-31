<?php
include("../includes/admin_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!$_SESSION)
	session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$enroll_no = $_POST['enroll_no'];
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

	$ct1 = $_POST['pre_ct1_attendance'];
	$mid = $_POST['pre_mid_attendance'];
	$ct2 = $_POST['pre_ct2_attendance'];
	$final = $_POST['pre_final_attendance'];

	$profile_image = $_FILES['profile_image']['name'];
	move_uploaded_file($_FILES['profile_image']['tmp_name'], '../assets/uploads/' . $profile_image);

	$conn->query("INSERT INTO students (enroll_no, name, email, phone, class, profile_image, dob, admission_date, gender, address, blood_group, father_name, father_phone, mother_name, mother_phone) VALUES ('$enroll_no', '$name', '$email', '$phone', '$class', '$profile_image', '$dob', '$admission_date', '$gender', '$address', '$blood_group', '$father_name', '$father_phone', '$mother_name', '$mother_phone')");

	$conn->query("INSERT INTO attendance (enroll_no, pre_ct1_attendance, pre_mid_attendance, pre_ct2_attendance, pre_final_attendance) VALUES ('$enroll_no', $ct1, $mid, $ct2, $final)");

	$subjects = ['English', 'Hindi', 'Mathematics', 'Science', 'SST', 'Computer'];
	foreach ($subjects as $subject) {
		$ct1_m = $_POST[$subject . '_ct1'];
		$mid_m = $_POST[$subject . '_mid'];
		$ct2_m = $_POST[$subject . '_ct2'];
		$final_m = $_POST[$subject . '_final'];
		$total = $ct1_m + $mid_m + $ct2_m + $final_m;
		$result = ($ct1_m < 10 || $mid_m < 30 || $ct2_m < 10 || $final_m < 30) ? 'Fail' : 'Pass';
		$conn->query("INSERT INTO marks (enroll_no, subject_name, class_test_1, midyear, class_test_2, final, total_marks, out_of_marks, result) VALUES ('$enroll_no', '$subject', $ct1_m, $mid_m, $ct2_m, $final_m, $total, 250, '$result')");
	}
	echo "<script>alert('Student added successfully'); window.location.href='manage_students.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Add Student</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 100px;">
	<h3 class="mb-4">➕ Add New Student</h3>
	<form method="POST" enctype="multipart/form-data">
		<div class="row g-3">
			<div class="col-md-4">
				<input required name="enroll_no" placeholder="Enrollment No" class="form-control">
			</div>
			<div class="col-md-4">
				<input required name="name" placeholder="Full Name" class="form-control">
			</div>
			<div class="col-md-4">
				<input required name="email" placeholder="Email" class="form-control" type="email">
			</div>
			<div class="col-md-4">
				<input required name="phone" placeholder="Phone" class="form-control">
			</div>
			<div class="col-md-4">
				<input required name="class" placeholder="Class (e.g., 10-A)" class="form-control">
			</div>
			<div class="col-md-4">
				<input type="file" name="profile_image" class="form-control">
			</div>
			<div class="col-md-4">
				<input required name="dob" placeholder="DOB" class="form-control" type="date">
			</div>
			<div class="col-md-4">
				<input required name="admission_date" placeholder="Admission Date" class="form-control" type="date">
			</div>
			<div class="col-md-4">
				<select name="gender" class="form-control">
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Other">Other</option>
				</select>
			</div>
			<div class="col-md-6">
				<textarea name="address" placeholder="Address" class="form-control"></textarea>
			</div>
			<div class="col-md-3">
				<select name="blood_group" class="form-control">
					<option value="">Blood Group</option>
					<option>A+</option><option>A-</option><option>B+</option><option>B-</option>
					<option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
				</select>
			</div>
			<div class="col-md-3">
				<input name="father_name" placeholder="Father's Name" class="form-control">
			</div>
			<div class="col-md-3">
				<input name="father_phone" placeholder="Father's Phone" class="form-control">
			</div>
			<div class="col-md-3">
				<input name="mother_name" placeholder="Mother's Name" class="form-control">
			</div>
			<div class="col-md-3">
				<input name="mother_phone" placeholder="Mother's Phone" class="form-control">
			</div>
			<hr>
			<h5>Attendance (%)</h5>
			<div class="col-md-3">
				<input name="pre_ct1_attendance" placeholder="Pre CT1" class="form-control">
			</div>
			<div class="col-md-3">
				<input name="pre_mid_attendance" placeholder="Pre Midyear" class="form-control">
			</div>
			<div class="col-md-3">
				<input name="pre_ct2_attendance" placeholder="Pre CT2" class="form-control">
			</div>
			<div class="col-md-3">
				<input name="pre_final_attendance" placeholder="Pre Final" class="form-control">
			</div>
			<hr>
			<h5>Marks for Each Subject</h5>
			<?php foreach (["English", "Hindi", "Mathematics", "Science", "SST", "Computer"] as $subject): ?>
				<div class="col-md-12"><strong><?= $subject ?></strong></div>
				<?php foreach (["ct1" => "Class Test 1", "mid" => "Midyear", "ct2" => "Class Test 2", "final" => "Final"] as $k => $v): ?>
					<div class="col-md-3">
						<input name="<?= $subject . '_' . $k ?>" placeholder="<?= $v ?> Marks" class="form-control">
					</div>
				<?php endforeach; ?>
			<?php endforeach; ?>

			<div class="col-12 mt-4 mb-5">
				<button type="submit" class="btn btn-primary w-100">+ Add Student</button>
			</div>
		</div>
	</form>
</div>
</body>
</html>
