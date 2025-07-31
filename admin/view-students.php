<?php
include("../includes/admin_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!$_SESSION)
	session_start();

$profile_image = $_SESSION["profile_image"];

$filter_pass = $_GET['pass'] ?? '';
$filter_att = $_GET['attendance'] ?? '';
$filter_marks = $_GET['marks'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

$query = "SELECT s.*, a.*, 
  (a.pre_ct1_attendance + a.pre_mid_attendance + a.pre_ct2_attendance + a.pre_final_attendance)/4 AS avg_attendance,
  (SELECT SUM(total_marks)/COUNT(DISTINCT subject_name) FROM marks m WHERE m.enroll_no = s.enroll_no) AS avg_marks,
  (SELECT COUNT(*) FROM marks m WHERE m.enroll_no = s.enroll_no AND m.result = 'Fail') AS fail_count
  FROM students s
  LEFT JOIN attendance a ON a.enroll_no = s.enroll_no
  WHERE 1";

if ($search) {
	$query .= " AND (s.name LIKE '%$search%' OR s.enroll_no LIKE '%$search%')";
}

if ($filter_pass === 'pass') {
	$query .= " HAVING fail_count = 0";
} elseif ($filter_pass === 'fail') {
	$query .= " HAVING fail_count > 0";
}

if ($filter_att) {
	$query .= (strpos($query, 'HAVING') !== false ? " AND" : " HAVING") . " avg_attendance >= $filter_att";
}

if ($filter_marks) {
	$raw_marks = $filter_marks * 2.5;
	$query .= (strpos($query, 'HAVING') !== false ? " AND" : " HAVING") . " avg_marks >= $raw_marks";
}

switch ($sort) {
	case 'name':
		$query .= " ORDER BY s.name ASC";
		break;
	case 'enroll_no':
		$query .= " ORDER BY s.enroll_no ASC";
		break;
	case 'marksHOL':
		$query .= " ORDER BY avg_marks DESC";
		break;
	case 'attendanceHOL':
		$query .= " ORDER BY avg_attendance DESC";
		break;
	case 'marksLOH':
		$query .= " ORDER BY avg_marks ASC";
		break;
	case 'attendanceLOH':
		$query .= " ORDER BY avg_attendance ASC";
		break;
}

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>View Students</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	<div class="container py-4">
		<h3 class="mb-4 text-center">View Students</h3>
		<form method="get" class="row g-2 mb-4 align-items-end">
			<div class="col-md-3">
				<input type="text" name="search" class="form-control" placeholder="Search by Name or Enroll No"
					value="<?= htmlspecialchars($search) ?>">
			</div>
			<div class="col-md-2">
				<select name="pass" class="form-select">
					<option value="">Pass/Fail</option>
					<option value="pass" <?= $filter_pass === 'pass' ? 'selected' : '' ?>>Pass</option>
					<option value="fail" <?= $filter_pass === 'fail' ? 'selected' : '' ?>>Fail</option>
				</select>
			</div>
			<div class="col-md-2">
				<input type="number" name="attendance" class="form-control" placeholder="Min Attendance %"
					value="<?= htmlspecialchars($filter_att) ?>">
			</div>
			<div class="col-md-2">
				<input type="number" name="marks" class="form-control" placeholder="Min Marks %"
					value="<?= htmlspecialchars($filter_marks) ?>">
			</div>
			<div class="col-md-2">
				<select name="sort" class="form-select">
					<option value="">Sort By</option>
					<option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name</option>
					<option value="enroll_no" <?= $sort === 'enroll_no' ? 'selected' : '' ?>>Enroll No</option>
					<option value="marksHOL" <?= $sort === 'marksHOL' ? 'selected' : '' ?>>Marks (High to Low)</option>
					<option value="marksLOH" <?= $sort === 'marksLOH' ? 'selected' : '' ?>>Marks (Low to High)</option>
					<option value="attendanceHOL" <?= $sort === 'attendanceHOL' ? 'selected' : '' ?>>Attendance (High to
						Low)</option>
					<option value="attendanceLOH" <?= $sort === 'attendanceLOH' ? 'selected' : '' ?>>Attendance (Low to
						High)</option>
				</select>
			</div>
			<div class="col-md-1">
				<button class="btn btn-primary w-100">Filter</button>
			</div>
		</form>

		<div class="row row-cols-1 row-cols-md-3 g-4">
			<?php while ($row = $result->fetch_assoc()):
				$avg_marks = round($row['avg_marks'] / 250 * 100, 2);
				$avg_att = round($row['avg_attendance'], 2);
				$status = $row['fail_count'] > 0 ? 'Fail' : 'Pass';
				?>
				<div class="col">
					<div class="card h-100">
						<div class="card-body text-center">
							<img src="../assets/uploads/<?= htmlspecialchars($row['profile_image'] ?: $profile_image) ?>"
								class="rounded-circle mb-3" alt="Profile" width="80" height="80">
							<h5 class="card-title mb-0"><?= htmlspecialchars($row['name']) ?></h5>
							<p class="text-muted small mb-2">Enroll: <?= $row['enroll_no'] ?></p>
							<p class="mb-1"><strong>Attendance:</strong> <?= $avg_att ?>%</p>
							<p class="mb-1"><strong>Marks:</strong> <?= $avg_marks ?>%</p>
							<span class="badge bg-<?= $status === 'Pass' ? 'success' : 'danger' ?>"><?= $status ?></span>
							<br><br>
							<button class="btn btn-sm btn-primary" data-bs-toggle="modal"
								data-bs-target="#modal_<?= $row['enroll_no'] ?>">View More</button>
						</div>
					</div>
				</div>

				<!-- Modal -->
				<div class="modal fade" id="modal_<?= $row['enroll_no'] ?>" tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<div class="modal-content mb-4">
							<div class="modal-header">
								<h5 class="modal-title">Student Details - <?= htmlspecialchars($row['name']) ?></h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-4 text-center">
										<img src="../assets/uploads/<?= htmlspecialchars($row['profile_image'] ?: $profile_image) ?>"
											class="rounded-circle" width="120" height="120">
									</div>
									<div class="col-md-8">
										<p><strong>Enroll No:</strong> <?= $row['enroll_no'] ?></p>
										<p><strong>Class:</strong> <?= $row['class'] ?></p>
										<p><strong>Gender:</strong> <?= $row['gender'] ?></p>
										<p><strong>Date of Birth:</strong> <?= $row['dob'] ?></p>
										<p><strong>Admission Date:</strong> <?= $row['admission_date'] ?></p>
										<p><strong>Email:</strong> <?= $row['email'] ?></p>
										<p><strong>Phone:</strong> <?= $row['phone'] ?></p>
										<p><strong>Address:</strong> <?= $row['address'] ?></p>
										<p><strong>Father:</strong> <?= $row['father_name'] ?> (<?= $row['father_phone'] ?>)
										</p>
										<p><strong>Mother:</strong> <?= $row['mother_name'] ?> (<?= $row['mother_phone'] ?>)
										</p>
										<p><strong>Blood Group:</strong> <?= $row['blood_group'] ?></p>
										<p><strong>Avg Attendance:</strong> <?= $avg_att ?>%</p>
										<p><strong>Avg Marks:</strong> <?= $avg_marks ?>%</p>
										<p><strong>Status:</strong> <span
												class="badge bg-<?= $status === 'Pass' ? 'success' : 'danger' ?>"><?= $status ?></span>
										</p>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="edit-student.php?enroll_no=<?= $row['enroll_no'] ?>"
									class="btn btn-warning">Edit</a>
								<a href="view-report.php?enroll_no=<?= $row['enroll_no'] ?>" class="btn btn-info">View
									Report</a>
								<button class="btn btn-danger"
									onclick="confirmDelete('<?= $row['enroll_no'] ?>')">Delete</button>
							</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>

	<script>
		function confirmDelete(enroll_no) {
			if (confirm("Are you sure you want to delete this student and all related records?")) {
				window.location.href = "delete-student.php?enroll_no=" + enroll_no;
			}
		}
	</script>
</body>

</html>