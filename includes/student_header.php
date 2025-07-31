<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student - Student Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f6fa;
      transition: filter 0.3s ease;
    }

    /* Navbar */
    .navbar {
      z-index: 1000;
      background: linear-gradient(to left, #afafb1ff, #ffffff);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .toggle-btn {
      font-size: 1.5rem;
      background-color: #000;
      color: #fff;
      border: none;
      padding: 5px 12px;
      border-radius: 6px;
      cursor: pointer;
    }

    .navbar .logo {
      height: 40px;
    }

    /* Sidebar Overlay (click to close + blur) */
    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 100vw;
      background-color: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(3px);
      display: none;
      z-index: 1100;
    }

    .sidebar-overlay.active {
      display: block;
    }

    /* Sidebar */
    #sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 240px;
      background-color: #1e1e2f;
      color: white;
      padding-top: 60px;
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      z-index: 1101; /* higher than navbar */
    }

    #sidebar.show {
      transform: translateX(0);
    }

    #sidebar a {
      color: #ffffff;
      display: block;
      padding: 12px 20px;
      text-decoration: none;
      transition: 0.2s;
    }

    #sidebar a:hover {
      background-color: #5c67f2;
    }

    /* Blur content when sidebar is open */
    .blurred {
      filter: blur(3px);
      pointer-events: none;
    }
  </style>
</head>
<body>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<div id="sidebar" onclick="event.stopPropagation()">
  <a href="../student/dashboard.php">Dashboard</a>
  <a href="../student/view-profile.php">Profile</a>
  <a href="../student/view-marks.php">Marks</a>
  <a href="../student/view-attendance.php">Attendance</a>
  <a href="../student/download-report.php">Download Report</a>
  <a href="../logout.php">Logout</a>
</div>

<!-- Navbar -->
<nav class="navbar navbar-light px-3 fixed-top">
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
  <div class="ms-auto">
    <img src="../assets/images/logo.png" alt="Logo" class="logo">
  </div>
</nav>

<!-- Start of page content wrapper -->
<div id="mainContent" class="mt-5">
  <!-- Your page content here -->
</div>

<script>
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const mainContent = document.getElementById('mainContent');

  function toggleSidebar() {
    const isOpen = sidebar.classList.contains('show');
    if (isOpen) {
      closeSidebar();
    } else {
      sidebar.classList.add('show');
      overlay.classList.add('active');
      mainContent.classList.add('blurred');
    }
  }

  function closeSidebar() {
    sidebar.classList.remove('show');
    overlay.classList.remove('active');
    mainContent.classList.remove('blurred');
  }
</script>
