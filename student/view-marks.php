<?php
include("../includes/student_header.php");
require_once("../includes/db.php");
require_once("../includes/auth.php");

if (!$_SESSION)
  session_start();

$enroll_no = $_SESSION['enroll_no'];

// 1. Fetch the logged-in student's marks
$stmt = $conn->prepare("SELECT * FROM marks WHERE enroll_no = ?");
$stmt->bind_param("s", $enroll_no);
$stmt->execute();
$student_result = $stmt->get_result();

$subjects = [];
$student_marks = [];

while ($row = $student_result->fetch_assoc()) {
  $subject = $row['subject_name'];
  $subjects[] = $subject;
  $student_marks[$subject] = [
    'Class Test 1' => $row['class_test_1'],
    'Midyear' => $row['midyear'],
    'Class Test 2' => $row['class_test_2'],
    'Final' => $row['final']
  ];
}

// 2. Fetch class averages for all relevant subjects in ONE query
$class_averages = [];
if (!empty($subjects)) {
  // Creates placeholders like ?,?,? for the IN clause
  $placeholders = implode(',', array_fill(0, count($subjects), '?'));
  $types = str_repeat('s', count($subjects)); // e.g., 'sss'

  $avg_query = "SELECT 
                    subject_name,
                    AVG(class_test_1) as ct1,
                    AVG(midyear) as mid,
                    AVG(class_test_2) as ct2,
                    AVG(final) as fin
                  FROM marks
                  WHERE subject_name IN ($placeholders)
                  GROUP BY subject_name";

  $stmt = $conn->prepare($avg_query);
  $stmt->bind_param($types, ...$subjects);
  $stmt->execute();
  $avg_result = $stmt->get_result();

  while ($avg_row = $avg_result->fetch_assoc()) {
    $class_averages[$avg_row['subject_name']] = [
      'Class Test 1' => round($avg_row['ct1'], 2),
      'Midyear' => round($avg_row['mid'], 2),
      'Class Test 2' => round($avg_row['ct2'], 2),
      'Final' => round($avg_row['fin'], 2)
    ];
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Marks</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: sans-serif;
      background: #f2f4f8;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #2c3e50;
    }

    .chart-container {
      width: 100%;
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    }

    canvas {
      max-height: 400px;
    }

    select {
      margin-top: 10px;
    }

    @media (max-width: 768px) {
      body {
        padding: 10px;
      }

      .chart-container {
        padding: 10px;
        margin: 20px auto;
      }

      h2 {
        font-size: 1.5em;
      }
    }
  </style>
</head>

<body>

  <h2>Student Marks</h2>
  <div class="chart-container">
    <select id="studentMarksSelect" class="form-select mb-3">
      <option value="All Subjects">All Subjects</option>
      <?php foreach ($subjects as $sub): ?>
        <option value="<?= $sub ?>"><?= $sub ?></option>
      <?php endforeach; ?>
    </select>
    <canvas id="studentMarksChart"></canvas>
  </div>

  <h2>Compare With Class Average</h2>
  <div class="chart-container">
    <select id="subjectSelect" class="form-select mb-3">
      <?php foreach ($subjects as $sub): ?>
        <option value="<?= $sub ?>"><?= $sub ?></option>
      <?php endforeach; ?>
    </select>
    <canvas id="comparisonChart"></canvas>
  </div>


  <script>
    const exams = ['Class Test 1', 'Midyear', 'Class Test 2', 'Final'];
    const subjectData = <?php echo json_encode($student_marks); ?>;
    const averageData = <?php echo json_encode($class_averages); ?>;

    // Chart 1: Line per subject (student only)
    let studentMarksChart;

    function drawStudentMarksChart(subject) {
      if (studentMarksChart) studentMarksChart.destroy();

      let chartData;

      if (subject === 'All Subjects') {
        chartData = {
          labels: exams,
          datasets: Object.entries(subjectData).map(([subj, marks], i) => ({
            label: subj,
            data: exams.map(e => marks[e] ? (marks[e] / (e.includes('Test') ? 25 : 100)) * 100 : 0),
            borderColor: `hsl(${i * 60}, 70%, 50%)`,
            backgroundColor: `hsla(${i * 60}, 70%, 50%, 0.2)`,
            tension: 0,
            fill: true
          }))
        };
      } else {
        const marks = subjectData[subject];
        chartData = {
          labels: exams,
          datasets: [
            {
              label: `${subject} (%)`,
              data: exams.map(e => marks[e] ? (marks[e] / (e.includes('Test') ? 25 : 100)) * 100 : 0),
              borderColor: '#2980b9',
              backgroundColor: 'rgba(41, 128, 185, 0.2)',
              tension: 0,
              fill: true
            }
          ]
        };
      }

      const options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: (subject === 'All Subjects') ? 'All Subjects - Marks % Across Exams' : `${subject} - Marks % Across Exams`
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            title: { display: true, text: 'Percentage (%)' }
          }
        }
      };

      studentMarksChart = new Chart(document.getElementById('studentMarksChart'), {
        type: 'line',
        data: chartData,
        options: options
      });
    }

    document.getElementById("studentMarksSelect").addEventListener("change", function () {
      drawStudentMarksChart(this.value);
    });

    drawStudentMarksChart("All Subjects");

    // Chart 2: Student vs Average (toggle subject)
    let comparisonChart;
    function updateComparisonChart(subject) {
      if (comparisonChart) comparisonChart.destroy();
      const student = subjectData[subject];
      const avg = averageData[subject];

      comparisonChart = new Chart(document.getElementById('comparisonChart'), {
        type: 'line',
        data: {
          labels: exams,
          datasets: [
            {
              label: 'Your Marks (%)',
              data: exams.map(e => (student[e] / (e.includes('Test') ? 25 : 100)) * 100),
              borderColor: '#3498db',
              fill: false,
              tension: 0
            },
            {
              label: 'Class Average (%)',
              data: exams.map(e => (avg[e] / (e.includes('Test') ? 25 : 100)) * 100),
              borderColor: '#e67e22',
              fill: false,
              borderDash: [5, 5],
              tension: 0
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            title: { display: true, text: `You vs Class Average in ${subject}` }
          },
          scales: {
            y: { beginAtZero: true, max: 100, title: { display: true, text: 'Percentage (%)' } }
          }
        }
      });
    }

    // Initialize with first subject
    updateComparisonChart("<?= $subjects[0] ?>");

    // Handle dropdown change
    document.getElementById("subjectSelect").addEventListener("change", (e) => {
      updateComparisonChart(e.target.value);
    });
  </script>

</body>

</html>