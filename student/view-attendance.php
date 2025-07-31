<?php
include("../includes/student_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!$_SESSION)
	session_start();

$enroll_no = $_SESSION['enroll_no'];
$attendanceData = [];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT pre_ct1_attendance, pre_ct2_attendance, pre_mid_attendance, pre_final_attendance FROM attendance WHERE enroll_no = ?");
$stmt->bind_param("s", $enroll_no);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
	// Assign percentages
	$attendanceData = [
		"Class Test 1" => $row['pre_ct1_attendance'],
		"Midyear" => $row['pre_mid_attendance'],
		"Class Test 2" => $row['pre_ct2_attendance'],
		"Final" => $row['pre_final_attendance']
	];
}
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
	<title>View Attendance</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

	<script
		src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>

	<style>
		/* Your CSS styles remain here */
		body {
			font-family: 'Segoe UI', sans-serif;
			background: #f2f4f8;
			padding: 30px;
		}

		.chart-container {
			max-width: 850px;
			margin: auto;
			background: #fff;
			padding: 25px;
			border-radius: 12px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
			height: 400px;
		}

		h2 {
			text-align: center;
			color: #2c3e50;
			margin-bottom: 20px;
		}

		canvas {
			width: 100% !important;
			height: 90% !important;
		}


		@media (max-width: 768px) {
			body {
				padding: 15px;
			}

			.chart-container {
				padding: 15px;
			}

			h2 {
				font-size: 1.25rem;
			}
		}
	</style>
</head>

<body>

	<div class="chart-container">
		<h2>Attendance Overview</h2>
		<canvas id="attendanceChart"></canvas>
		<div style="text-align: center; margin-top: 10px;">
			<span style="color: green; font-weight: 600;">■ Eligible (≥ 70%)</span> &nbsp;
			<span style="color: red; font-weight: 600;">■ Not Eligible (< 70%)</span>
		</div>

	</div>

	<script>

		const labels = <?= json_encode(array_keys($attendanceData)) ?>;
		const data = <?= json_encode(array_values($attendanceData)) ?>;

		const backgroundColors = data.map(val => val >= 70 ? 'rgba(46, 204, 113, 0.8)' : 'rgba(231, 76, 60, 0.8)');

		const ctx = document.getElementById('attendanceChart').getContext('2d');
		const attendanceChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [{
					label: 'Attendance (%)',
					data: data,
					backgroundColor: backgroundColors,
					borderRadius: 8
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					title: {
						display: true,
						text: 'Student Attendance Before Each Exam',
						font: {
							size: 18
						}
					},
					tooltip: {
						callbacks: {
							label: ctx => `${ctx.raw}% attendance`
						}
					},
					annotation: {
						annotations: {
							cutoff: {
								type: 'line',
								yMin: 70,
								yMax: 70,
								borderColor: 'red',
								borderWidth: 2,
								label: {
									content: '70% Cutoff',
									enabled: true,
									backgroundColor: 'red',
									color: 'white',
									position: 'end',
									yAdjust: -8
								}
							}
						}
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						max: 100,
						title: {
							display: true,
							text: 'Attendance %'
						},
						ticks: {
							callback: value => value + "%"
						}
					}
				}
			}
		});
	</script>

</body>

</html>