<?php
include("../includes/student_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!$_SESSION)
  session_start();

$profile_image = $_SESSION["profile_image"];

$enroll_no = $_SESSION['enroll_no'];
$query = "SELECT * FROM students WHERE enroll_no = '$enroll_no'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Student Info</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="view-profile.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

  <div class="title-bar">
    <h2>Student Info</h2>
  </div>

  <!-- FLIP CARD CONTAINER -->
  <div class="flip-card-container" onclick="this.querySelector('.flip-card').classList.toggle('flipped')">
    <div class="flip-card" id="flipCard">

      <!-- FRONT SIDE -->
      <div class="flip-card-side flip-card-front row g-3">
        <!-- Profile Image Column -->
        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center border-end">
          <img src="../assets/uploads/<?php echo htmlspecialchars($student['profile_image']) ?: $profile_image; ?>"
            alt="Profile Image" class="mb-3 img-fluid rounded" style="max-height: 200px; object-fit: scale-down;">
          <h5 class="text-center"><?php echo htmlspecialchars($student['name']); ?></h5>
          <small class="text-muted">Tap to view more</small>
        </div>

        <!-- Info Column -->
        <div class="col-md-8 row text-center">
          <div class="col-sm-6 mb-3"><strong>Enrollment
              No.:</strong><br><?php echo htmlspecialchars($student['enroll_no']); ?></div>
          <div class="col-sm-6 mb-3"><strong>Class:</strong><br><?php echo htmlspecialchars($student['class']); ?></div>
          <div class="col-sm-6 mb-3"><strong>Gender:</strong><br><?php echo htmlspecialchars($student['gender']); ?>
          </div>
          <div class="col-sm-6 mb-3"><strong>Date of Birth:</strong><br><?php echo htmlspecialchars($student['dob']); ?>
          </div>
          <div class="col-sm-6 mb-3"><strong>Phone No.:</strong><br><?php echo htmlspecialchars($student['phone']); ?>
          </div>
          <div class="col-sm-6 mb-3"><strong>Email:</strong><br><?php echo htmlspecialchars($student['email']); ?></div>
        </div>
      </div>

      <!-- BACK SIDE -->
      <div class="flip-card-side flip-card-back p-4 text-center">
        <div class="container-fluid">
          <h4 class="text-center mb-3">Additional Details</h4>
          <div class="row">
            <div class="col-sm-6 mb-2"><strong>Admission
                Date:</strong><br><?php echo htmlspecialchars($student['admission_date']); ?></div>
            <div class="col-sm-6 mb-2"><strong>Blood
                Group:</strong><br><?php echo htmlspecialchars($student['blood_group']); ?></div>
            <div class="col-sm-6 mb-2"><strong>Father's
                Name:</strong><br><?php echo htmlspecialchars($student['father_name']); ?></div>
            <div class="col-sm-6 mb-2"><strong>Father's
                Phone:</strong><br><?php echo htmlspecialchars($student['father_phone']); ?></div>
            <div class="col-sm-6 mb-2"><strong>Mother's
                Name:</strong><br><?php echo htmlspecialchars($student['mother_name']); ?></div>
            <div class="col-sm-6 mb-2"><strong>Mother's
                Phone:</strong><br><?php echo htmlspecialchars($student['mother_phone']); ?></div>
            <div class="col-12 mt-2"><strong>Address:</strong><br><?php echo htmlspecialchars($student['address']); ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Request Update Button -->
  <div class="text-center mt-4">
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateRequestModal">
      Request Profile Update
    </button>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="updateRequestModal" tabindex="-1" aria-labelledby="updateRequestModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="send_update_request.php">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updateRequestModalLabel">Profile Update Request</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

            <div class="mb-3">
              <label for="field_name" class="form-label">Field to Update</label>
              <select class="form-select" id="field_name" name="field_name" required>
                <option value="">Select field</option>
                <option value="name">Name</option>
                <option value="phone">Phone Number</option>
                <option value="email">Email</option>
                <option value="address">Address</option>
                <option value="father_name">Father's Name</option>
                <option value="mother_name">Mother's Name</option>
                <option value="father_phone">Father's Phone Number</option>
                <option value="mother_phone">Mother's Phone Number</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="requested_value" class="form-label">New Value</label>
              <input type="text" class="form-control" id="requested_value" name="requested_value" required>
            </div>

            <input type="hidden" name="enroll_no" value="<?= $student['enroll_no'] ?>">

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Send Request</button>
          </div>
        </div>
      </form>
    </div>
  </div>


</body>

</html>