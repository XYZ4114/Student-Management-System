<?php
require_once('../TCPDF/tcpdf.php');
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!isset($_SESSION["enroll_no"])) {
    die("Not logged in.");
}

$enroll_no = $_SESSION["enroll_no"];

$student = $conn->query("SELECT * FROM students WHERE enroll_no = '$enroll_no'")->fetch_assoc();
$attendance = $conn->query("SELECT * FROM attendance WHERE enroll_no = '$enroll_no'")->fetch_assoc();
$marks = $conn->query("SELECT * FROM marks WHERE enroll_no = '$enroll_no'")->fetch_all(MYSQLI_ASSOC);

if (!$student) die("Student not found.");

$total_sum = 0;
$subject_count = 0;
$failed = false;

foreach ($marks as $m) {
    $total_sum += $m['total_marks'];
    $subject_count++;
    if ($m['result'] === 'Fail') $failed = true;
}

$avg_att = ($attendance['pre_ct1_attendance'] + $attendance['pre_mid_attendance'] + $attendance['pre_ct2_attendance'] + $attendance['pre_final_attendance']) / 4;

// Load and encode profile image
$imagePath = realpath(__DIR__ . '/../assets/uploads/' . $student['profile_image']);
$profileImageHTML = '';

if (file_exists($imagePath)) {
    $imageData = base64_encode(file_get_contents($imagePath));
    $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
    $src = 'data:image/' . $ext . ';base64,' . $imageData;

    // Centered and properly spaced image
    $profileImageHTML = '<div style="text-align:center;">
        <img src="' . $src . '" width="80" height="80" />
    </div>';
}

// Generate PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle('Student Report');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(10, 10, 10); // Small margins
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();
$pdf->SetDisplayMode('real', 'default'); // 'real' shows full page, 'fullwidth' for screen-width fit
$pdf->SetFont('dejavusans', '', 10);

$html = $profileImageHTML . '<h2 style="text-align:center;">XYZ Institute of Technology</h2>
<h4 style="text-align:center; padding-bottom:4px; margin-bottom:30px;">Student Academic Report</h4>


<br><br>
<table cellpadding="6" cellspacing="0" border="1">
<tr><td><strong>Name</strong></td><td>' . $student['name'] . '</td></tr>
<tr><td><strong>Enroll No</strong></td><td>' . $student['enroll_no'] . '</td></tr>
<tr><td><strong>Class</strong></td><td>' . $student['class'] . '</td></tr>
<tr><td><strong>Date of Birth</strong></td><td>' . $student['dob'] . '</td></tr>
<tr><td><strong>Admission Date</strong></td><td>' . $student['admission_date'] . '</td></tr>
</table>

<br><h4 style="text-align:center;">Attendance Summary</h4>
<table cellpadding="6" cellspacing="0" border="1">
<tr>
  <th>CT1 (%)</th><th>Mid (%)</th><th>CT2 (%)</th><th>Final (%)</th><th>Average (%)</th>
</tr>
<tr>
  <td>' . $attendance['pre_ct1_attendance'] . '%</td>
  <td>' . $attendance['pre_mid_attendance'] . '%</td>
  <td>' . $attendance['pre_ct2_attendance'] . '%</td>
  <td>' . $attendance['pre_final_attendance'] . '%</td>
  <td><strong>' . round($avg_att, 2) . '%</strong></td>
</tr>
</table>

<br><h4 style="text-align:center;">Marks Summary</h4>
<table cellpadding="6" cellspacing="0" border="1">
<tr>
  <th>Subject</th><th>CT1(/25)</th><th>Mid(/100)</th><th>CT2(/25)</th><th>Final(/100)</th><th>Total(/250)</th><th>Result</th>
</tr>';

foreach ($marks as $m) {
    $html .= '<tr>
    <td>' . $m['subject_name'] . '</td>
    <td>' . $m['class_test_1'] . '</td>
    <td>' . $m['midyear'] . '</td>
    <td>' . $m['class_test_2'] . '</td>
    <td>' . $m['final'] . '</td>
    <td><strong>' . $m['total_marks'] . '</strong></td>
    <td>' . ($m['result'] === 'Pass' ? 'Pass' : 'Fail') . '</td>
  </tr>';
}

$percentage = round(($total_sum / ($subject_count * 250)) * 100, 2);
$result_text = $failed ? 'Fail' : 'Pass';

$html .= '
<tr>
  <td colspan="5"><strong>Average Marks</strong></td>
  <td colspan="2"><strong>' . $total_sum . ' / 1500</strong></td>
</tr>
<tr>
  <td colspan="5"><strong>Marks Percentage</strong></td>
  <td colspan="2"><strong>' . $percentage . '%</strong></td>
</tr>
<tr>
  <td colspan="5"><strong>Final Result</strong></td>
  <td colspan="2"><strong>' . $result_text . '</strong></td>
</tr>
</table>

<br><br><br>
<table width="100%">
  <tr>
    <td align="center" width="50%">____________________<br>Teacher\'s Signature</td>
    <td align="center" width="50%">____________________<br>Principal\'s Signature</td>
  </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

function isMobileDevice() {
    return preg_match('/(android|iphone|ipad|ipod|blackberry|windows phone|opera mini|mobile)/i', $_SERVER['HTTP_USER_AGENT']);
}
// Show in browser instead of forcing download
$filename = 'Student_Report_' . $student['enroll_no'] . '.pdf';
if (isMobileDevice()) {
    $pdf->Output($filename, 'D'); // Force download
} else {
    $pdf->Output($filename, 'I'); // Show in browser
}

exit;
?>
