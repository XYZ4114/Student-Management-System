<?php
include("../includes/student_header.php");

require_once ("../includes/db.php");
require_once ("../includes/auth.php");

if(!$_SESSION)
	session_start();

$enroll_no = $_SESSION['enroll_no'];

$student = $conn->query("SELECT * FROM students WHERE enroll_no = '$enroll_no'")->fetch_assoc();
$attendance = $conn->query("SELECT * FROM attendance WHERE enroll_no = '$enroll_no'")->fetch_assoc();
$marksRaw = $conn->query("SELECT * FROM marks WHERE enroll_no = '$enroll_no'");
$examTotals = ['class_test_1' => 0, 'midyear' => 0, 'class_test_2' => 0, 'final' => 0];
$subjectCount = 0;
$totalScored = 0;
$pass_fail = 'Pass';

while ($row = $marksRaw->fetch_assoc()) {
	$examTotals['class_test_1'] += $row['class_test_1'];
	$examTotals['midyear'] += $row['midyear'];
	$examTotals['class_test_2'] += $row['class_test_2'];
	$examTotals['final'] += $row['final'];

	$totalScored += $row['class_test_1'] + $row['midyear'] + $row['class_test_2'] + $row['final'];
	$subjectCount++;
	if ($row['result'] === 'Fail')
		$pass_fail = $row['result'];

}

$subjectAverages = [];
$marksRaw->data_seek(0);
while ($row = $marksRaw->fetch_assoc()) {
	$subject = $row['subject_name'];
	$total = $row['class_test_1'] + $row['midyear'] + $row['class_test_2'] + $row['final'];
	$subjectAverages[$subject] = round($total / 250 * 100, 2);
}

$grandTotalOutOf = 250 * $subjectCount;
$ctOutOf = 25 * $subjectCount;
$mainOutOf = 100 * $subjectCount;

$examPercents = [
	'Class Test 1' => round(($examTotals['class_test_1'] / $ctOutOf) * 100, 2),
	'Midyear' => round(($examTotals['midyear'] / $mainOutOf) * 100, 2),
	'Class Test 2' => round(($examTotals['class_test_2'] / $ctOutOf) * 100, 2),
	'Final' => round(($examTotals['final'] / $mainOutOf) * 100, 2)
];

$exams = [
	'Pre CT1' => $attendance['pre_ct1_attendance'] ?? 0,
	'Pre Mid' => $attendance['pre_mid_attendance'] ?? 0,
	'Pre CT2' => $attendance['pre_ct2_attendance'] ?? 0,
	'Pre Final' => $attendance['pre_final_attendance'] ?? 0
];
$presentTotal = round(array_sum($exams) / count($exams), 2);
$absentPercent = 100 - $presentTotal;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Student Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<style>
		body {
			background-color: #f5f6fa;
			font-family: 'Segoe UI', sans-serif;
			padding: 20px;
		}

		.card {
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
			border: none;
			border-radius: 12px;
		}

		.profile-box {
			min-height: 300px;
		}
	</style>
</head>

<body>

	<div class="container-fluid">
		<div class="row g-4">

			<!-- Top Row: Profile + Marks -->
			<div class="col-md-4">
				<div class="card profile-box p-4 h-100">
					<h5 class="mb-3">👤 Student Profile</h5>
					<p><strong>Roll No.:</strong> <?= $student['enroll_no'] ?></p>
					<p><strong>Name:</strong> <?= $student['name'] ?></p>
					<p><strong>Class:</strong> <?= $student['class'] ?></p>
					<p><strong>Date of Admission:</strong> <?= $student['admission_date'] ?></p>
					<p><strong>Date of Birth:</strong> <?= $student['dob'] ?></p>
					<p><strong>Email:</strong> <?= $student['email'] ?></p>
					<p><strong>Phone:</strong> <?= $student['phone'] ?></p>
				</div>
			</div>

			<div class="col-md-8">
				<div class="card p-4 pb-0 h-100">
					<h5 class="mb-3">📈 Exam Performance</h5>
					<div style="overflow-x: auto;">
						<canvas id="marksChart" height="200" style="max-height: 200px;"></canvas>
					</div>
					<div class="mt-3 text-center">
						<strong>Total:</strong> <?= $totalScored ?> / <?= $grandTotalOutOf ?><br>
						<strong>Percentage:</strong> <?= round($totalScored / $grandTotalOutOf * 100, 2) ?> % <br>
						<strong>Status:</strong> <?= $pass_fail ?? 'N/A' ?>
					</div>
				</div>
			</div>

			<!-- Bottom Row: Avg Marks + Attendance -->
			<div class="col-md-4">
				<div class="card p-4 h-100">
					<h5 class="mb-5">📚 Subject-Wise Averages</h5>
					<?php foreach ($subjectAverages as $subject => $avg): ?>
						<div class="border rounded p-2 mb-3 bg-light">
							<strong><?= htmlspecialchars($subject) ?>:</strong>
							<span class="float-end text-primary"><?= $avg ?> / 100</span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="col-md-8">
				<div class="card p-4 h-100">
					<h5 class="mb-3">📊 Attendance Overview</h5>
					<div class="d-flex flex-wrap align-items-center justify-content-between h-100">

						<!-- Pie Chart -->
						<div style="flex: 1; display: flex; justify-content: center; min-width: 260px;">
							<canvas id="attendanceChart" width="260" height="260"></canvas>
						</div>

						<!-- Exam Attendance -->
						<div style="flex: 1; min-width: 250px;" class="mt-3 mt-md-0 ps-3">
							<div class="row g-3">
								<div class="col-12 text-center">
									<div class="mt-2 badge bg-success p-2 fs-6">
										🎯 Overall Attendance:
										<strong><?= $presentTotal ?>%</strong>
									</div>
								</div>
								<div class="col-12 text-center">
									<div class="mt-2 badge bg-danger p-2 fs-6">
										🎯 Overall Absence:
										<strong><?= $absentPercent ?>%</strong>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</div>


	<script>

		const attendanceChart = new Chart(document.getElementById('attendanceChart'), {
			type: 'pie',
			data: {
				labels: [
					'Absent',
					'Present'
				],
				datasets: [{
					data: [
						<?= $absentPercent ?>,
						<?= $presentTotal ?>
						
					],
					backgroundColor: [
						'#e74c3c',  // Absent - Red
						'#4caf50',  // Present - Green
					],
					borderColor: '#fff',
					borderWidth: 2
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: true,
				plugins: {
					legend: {
						position: 'bottom',
					}
				}
			}
		});


		const marksBar = new Chart(document.getElementById('marksChart'), {
			type: 'bar',
			data: {
				labels: ['Class Test 1', 'Midyear', 'Class Test 2', 'Final'],
				datasets: [{
					label: 'Average %',
					data: [
						<?= $examPercents['Class Test 1'] ?>,
						<?= $examPercents['Midyear'] ?>,
						<?= $examPercents['Class Test 2'] ?>,
						<?= $examPercents['Final'] ?>
					],
					backgroundColor: ['#4caf50', '#2196f3', '#ff9800', '#9c27b0'],
					borderRadius: 8,
					borderSkipped: false,
					barThickness: 18
				}]
			},
			options: {
				indexAxis: 'y',
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: { display: false },
					tooltip: {
						callbacks: {
							label: function (context) {
								return context.raw + '%';
							}
						}
					}
				},
				scales: {
					x: {
						beginAtZero: true,
						max: 100,
						title: {
							display: true,
							text: 'Percentage',
							font: { size: 14 }
						}
					},
					y: {
						ticks: { font: { size: 12 } }
					}
				}
			}
		});

	</script>

</body>

</html>