<?php
session_start();
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
	// Redirect if already logged in
	if ($_SESSION['role'] == 'admin') {
		header("Location: admin/dashboard.php");
	} else {
		header("Location: student/dashboard.php");
	}
	exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Login - Student Management System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

	<div class="container d-flex justify-content-center align-items-center min-vh-100">
		<div class="card shadow p-4" style="width: 100%; max-width: 400px;">
			<h3 class="text-center mb-4">Student Management Login</h3>

			<?php
			if (isset($_GET['error'])) {
				echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
			}
			?>
			<?php
			if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
				echo '<div class="alert alert-success">You have been logged out successfully.</div>';
			}
			?>

			<form action="login.php" method="POST">
				<div class="mb-3">
					<label for="enroll_no" class="form-label">Enrollment No.</label>
					<input type="text" name="enroll_no" id="enroll_no" class="form-control" required
						placeholder="e.g., 2023STD001">
				</div>

				<div class="mb-3">
					<label for="password" class="form-label">Password</label>
					<input type="password" name="password" id="password" class="form-control" required>
				</div>

				<button type="submit" class="btn btn-primary w-100">Login</button>
			</form>

			<p class="text-muted text-center mt-3 mb-0" style="font-size: 0.9rem;">
				Enter your valid enrollment number and password.
			</p>
		</div>
	</div>

</body>

</html>