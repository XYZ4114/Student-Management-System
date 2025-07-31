<?php
include("../includes/admin_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

// Fetch all profile update requests
$result = $conn->query("SELECT * FROM profile_update_requests ORDER BY request_date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Profile Update Requests</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

	<h2 class="mb-4">Student Profile Update Requests</h2>

	<?php if ($result->num_rows > 0): ?>
		<div class="table-responsive">
			<table class="table table-bordered table-hover align-middle">
				<thead class="table-dark text-nowrap">
					<tr>
						<th>Enroll No</th>
						<th>Field</th>
						<th>Current Value</th>
						<th>Requested Value</th>
						<th>Status</th>
						<th>Requested At</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody class="text-nowrap">
					<?php while ($row = $result->fetch_assoc()): ?>
						<tr>
							<td><?= htmlspecialchars($row['enroll_no']) ?></td>
							<td><?= htmlspecialchars($row['field_name']) ?></td>
							<td><?= htmlspecialchars($row['current_value']) ?></td>
							<td><?= htmlspecialchars($row['requested_value']) ?></td>
							<td><span
									class="badge 
			  <?= $row['status'] === 'Pending' ? 'bg-warning' : ($row['status'] === 'Approved' ? 'bg-success' : 'bg-danger') ?>">
									<?= $row['status'] ?>
								</span></td>
							<td><?= $row['request_date'] ?></td>
							<td>
								<?php if ($row['status'] === 'Pending'): ?>
									<div class="d-flex flex-wrap gap-1">
										<a href="process_request.php?id=<?= $row['id'] ?>&action=approve"
											class="btn btn-success btn-sm">Approve</a>
										<a href="process_request.php?id=<?= $row['id'] ?>&action=reject"
											class="btn btn-danger btn-sm">Reject</a>
									</div>
								<?php else: ?>
									<em>No actions</em>
								<?php endif; ?>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<p class="text-center">No profile update requests found.</p>
	<?php endif; ?>

</body>

</html>