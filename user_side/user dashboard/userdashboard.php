<?php
session_start();
include '../../database.php';
if (!isset($_SESSION['employee_username'])) {
    header('Location: /finals_integrated/Login/login.php');
    exit();
}
// Fetch employee name from database
$conn = mysqli_connect('localhost', 'root', '', 'accounting management system');
$username = $_SESSION['employee_username'];
$sql = "SELECT Fname, middlename, Lname FROM employees WHERE Username='$username'";
$result = mysqli_query($conn, $sql);
$name = '';
if ($row = mysqli_fetch_assoc($result)) {
    $name = trim($row['Fname'] . ' ' . ($row['middlename'] ? $row['middlename'] . ' ' : '') . $row['Lname']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .notification-wrapper {
      position: relative;
      display: inline-block;
    }
    .notif-badge {
      position: absolute;
      top: 0;
      right: 0;
      background: #f43f5e;
      color: #fff;
      font-size: 0.85em;
      border-radius: 50%;
      padding: 2px 7px;
      font-weight: bold;
    }
    .notif-dropdown {
      position: absolute;
      right: 0;
      top: 35px;
      width: 320px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.13);
      z-index: 100;
      max-height: 400px;
      overflow-y: auto;
      border: 1px solid #eee;
      padding: 0.5em 0;
    }
    .notif-item {
      padding: 0.8em 1em;
      border-bottom: 1px solid #f0f0f0;
      cursor: pointer;
      transition: background 0.2s;
    }
    .notif-item:last-child {
      border-bottom: none;
    }
    .notif-item:hover {
      background: #f4f4f4;
    }
    .notif-title {
      font-weight: 500;
      color: #222;
    }
    .notif-date {
      font-size: 0.9em;
      color: #888;
      margin-top: 2px;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  <div class="dashboard-container">
    <main class="dashboard-main">
      <div class="dashboard-card">
        <div class="dashboard-btn-row">
          <button class="dashboard-btn" id="timeInOutBtn">Time In/Out</button>
          <button class="dashboard-btn">Request Payslip</button>
          <button class="dashboard-btn">Application for Leave</button>
        </div>
        <div class="dashboard-btn-row single">
          <button class="dashboard-btn">Payslip Request History</button>
        </div>
      </div>
    </main>
  </div>
  <script src="dashboard.js"></script>
  <script>
    document.getElementById('timeInOutBtn').onclick = function() {
      window.location.href = '../time in-out/timeinout.php';
    };
    var btns = document.getElementsByClassName('dashboard-btn');
    if (btns[1]) btns[1].onclick = function() { window.location.href = '../employeepayslip/employeepayslip.php'; };
    if (btns[2]) btns[2].onclick = function() { window.location.href = '../application for leave/applicationforleave.php'; };
    if (btns[3]) btns[3].onclick = function() { window.location.href = '../payslip history/paysliphistory.php'; };
  </script>
</body>
</html>
