<?php
include("../includes/admin_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!$_SESSION)
	session_start();

// Total students, boys, girls
$totalStudents = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];
$totalBoys = $conn->query("SELECT COUNT(*) AS total FROM students WHERE gender = 'Male'")->fetch_assoc()['total'];
$totalGirls = $conn->query("SELECT COUNT(*) AS total FROM students WHERE gender = 'Female'")->fetch_assoc()['total'];

// Average Marks per Subject
$subjects = ['English', 'Hindi', 'Mathematics', 'Science', 'SST', 'Computer'];
$subjectAverages = [];
foreach ($subjects as $subject) {
	$res = $conn->query("SELECT AVG(total_marks) AS avg_marks FROM marks WHERE subject_name = '$subject'");
	$row = $res->fetch_assoc();
	$subjectAverages[$subject] = round(($row['avg_marks'] / 250) * 100, 2);
}

//Total Passed and Failed
$failResult = $conn->query("SELECT DISTINCT enroll_no FROM marks WHERE result = 'Fail'");
$totalFailed = $failResult->num_rows;
$totalPassed = $totalStudents - $totalFailed;

// Attendance Averages
$attendanceRes = $conn->query("SELECT AVG(pre_ct1_attendance) AS ct1, AVG(pre_mid_attendance) AS mid, AVG(pre_ct2_attendance) AS ct2, AVG(pre_final_attendance) AS final FROM attendance");
$attendance = $attendanceRes->fetch_assoc();

// Top 3s (marks)
function getTop3($conn, $gender = null, $by = 'marks')
{
	$where = $gender ? "AND s.gender = '$gender'" : "";
	$column = $by === 'attendance' ? "(a.pre_ct1_attendance + a.pre_mid_attendance + a.pre_ct2_attendance + a.pre_final_attendance)/4" : "SUM(m.total_marks)";
	$join = $by === 'attendance' ? "INNER JOIN attendance a ON a.enroll_no = s.enroll_no" : "INNER JOIN marks m ON m.enroll_no = s.enroll_no";
	$sql = "SELECT s.name, ROUND($column, 2) AS value FROM students s $join WHERE 1 $where GROUP BY s.enroll_no ORDER BY value DESC LIMIT 3";
	$result = $conn->query($sql);
	$list = [];
	while ($row = $result->fetch_assoc())
		$list[] = $row;
	return $list;
}

$topMarksAll = getTop3($conn);
$topMarksBoys = getTop3($conn, 'Male');
$topMarksGirls = getTop3($conn, 'Female');
$topAttAll = getTop3($conn, null, 'attendance');
$topAttBoys = getTop3($conn, 'Male', 'attendance');
$topAttGirls = getTop3($conn, 'Female', 'attendance');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<style>
		.metric-box {
			border-radius: 10px;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
			padding: 20px;
			text-align: center;
			background: #fff;
		}

		body {
			margin-top: 70px;
		}

		.top {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			align-items: center;
		}

		.top .col-md-4 {
			display: flex;
			align-items: center;
			justify-content: center;
		}


		#marksChart {
			max-height: 80% !important;
		}

		@media (max-width: 768px) {
			
			#marksChart {
				height: 300px !important;
			}
		}
	</style>
</head>

<body>
	<div class="container mt-4">
		<h2 class="text-center">Class Overview</h2>
		<div class="row text-center mb-4">
			<div class="col-md-4">
				<div class="metric-box">
					<h5>Total Students</h5>
					<h3><?= $totalStudents ?></h3>
				</div>
			</div>
			<div class="col-md-4">
				<div class="metric-box">
					<h5>Total Boys</h5>
					<h3><?= $totalBoys ?></h3>
				</div>
			</div>
			<div class="col-md-4">
				<div class="metric-box">
					<h5>Total Girls</h5>
					<h3><?= $totalGirls ?></h3>
				</div>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-md-6">
				<div class="card p-3">
					<h5>Average Marks of Class in Each Subject</h5>
					<canvas id="marksChart"></canvas>
				</div>
				<div class="row mb-4 mt-2">
					<div class="col-md-12">
						<div class="card p-4 text-center">
							<h5 class="mb-3">🎯 Class Result Summary</h5>
							<div class="text-success fw-bold fs-5 mb-2">✅ Total Passed: <?= $totalPassed ?></div>
							<div class="text-danger fw-bold fs-5">❌ Total Failed: <?= $totalFailed ?></div>
						</div>
					</div>
				</div>

			</div>
			<div class="col-md-6">
				<div class="card p-3">
					<h5>Average Attendance Before Exams</h5>
					<canvas id="attendanceDonut"></canvas>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center justify-content-center mt-4">
			<div class="row top">
				<?php
				$sections = [
					['Top 3 Students (Marks)', $topMarksAll],
					['Top 3 Boys (Marks)', $topMarksBoys],
					['Top 3 Girls (Marks)', $topMarksGirls],
					['Top 3 Students (Attendance)', $topAttAll],
					['Top 3 Boys (Attendance)', $topAttBoys],
					['Top 3 Girls (Attendance)', $topAttGirls]
				];
				foreach ($sections as $section): ?>
					<div class="col-md-4 mb-4">
						<div class="card p-3 h-100">
							<h6 class="text-center"><?= $section[0] ?></h6>
							<ol>
								<?php foreach ($section[1] as $entry): ?>
									<li><?= htmlspecialchars($entry['name']) ?> -
										<?= $entry['value'] > 100 ? round($entry['value'] / 15, 2) : $entry['value'] ?>%
									</li>
								<?php endforeach; ?>
							</ol>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<script>
		const subjects = <?= json_encode(array_keys($subjectAverages)) ?>;
		const subjectData = <?= json_encode(array_values($subjectAverages)) ?>;
		const ctx1 = document.getElementById('marksChart');
		new Chart(ctx1, {
			type: 'bar',
			data: {
				labels: subjects,
				datasets: [{
					label: 'Average %',
					data: subjectData,
					backgroundColor: '#3498db',
					borderRadius: 5
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					y: {
						beginAtZero: true,
						max: 100
					}
				}
			}
		});

		const config = {
			type: 'pie',
			data: {
				labels: ['Present', 'Absent'],
				datasets: [
					{
						label: 'Class Test 1',
						data: [<?= $attendance['ct1'] ?>, <?= 100 - $attendance['ct1'] ?>],
						backgroundColor: ['#2ecc71', '#e74c3c'],
						hoverOffset: 4
					},
					{
						label: 'Midyear',
						data: [<?= $attendance['mid'] ?>, <?= 100 - $attendance['mid'] ?>],
						backgroundColor: ['#3498db', '#e74c3c'],
						hoverOffset: 4
					},
					{
						label: 'Class Test 2',
						data: [<?= $attendance['ct2'] ?>, <?= 100 - $attendance['ct2'] ?>],
						backgroundColor: ['#9b59b6', '#e74c3c'],
						hoverOffset: 4
					},
					{
						label: 'Final',
						data: [<?= $attendance['final'] ?>, <?= 100 - $attendance['final'] ?>],
						backgroundColor: ['#f39c12', '#e74c3c'],
						hoverOffset: 4
					}
				]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						position: 'bottom',
						labels: {
							generateLabels(chart) {
								return chart.data.datasets.map((dataset, i) => {
									return {
										text: dataset.label,
										fillStyle: dataset.backgroundColor[0],
										strokeStyle: dataset.backgroundColor[0],
										hidden: !chart.isDatasetVisible(i),
										datasetIndex: i
									};
								});
							}
						},
						onClick(e, legendItem, legend) {
							const ci = legend.chart;
							const index = legendItem.datasetIndex;
							const meta = ci.getDatasetMeta(index);

							// Toggle visibility
							meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
							ci.update();
						}
					},
					tooltip: {
						callbacks: {
							label(context) {
								const datasetLabel = context.dataset.label || '';
								const value = context.formattedValue;
								const label = context.label; // "Present" or "Absent"
								return `${datasetLabel} - ${label}: ${value}%`;
							}
						}
					},
					title: {
						display: true,
						text: 'Multi-Ring Attendance Overview'
					}
				}
			}
		};
		const ctx2 = document.getElementById('attendanceDonut');
		new Chart(ctx2, config);

	</script>
</body>

</html>