<?php
session_start();
include '../components/sidebar.php';
include_once '../database.php';

// your PHP code for querying the database, etc.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_id'])) {
        $id = intval($_POST['approve_id']);
        $conn->query("UPDATE leave_applications SET status = 'Approved' WHERE Leaves_ID = $id");
        $_SESSION['leave_approved'] = true;
    }
    if (isset($_POST['decline_id'])) {
        $id = intval($_POST['decline_id']);
        $conn->query("UPDATE leave_applications SET status = 'Declined' WHERE Leaves_ID = $id");
    }
    // Optionally, redirect to avoid form resubmission
    header("Location: leave.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Employee Leaves</title>
  <link rel="stylesheet" href="style3.css" />
  <link rel="stylesheet" href="/finals_integrated/components/sidebar.css" />
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>LEAVES</h1>
      <p>Dashboard / Employee Leave</p>
    </header>
    <div class="controls">
      <label for="entries">Show
        <select id="entries">
          <option value="5" selected>5</option>
          <option value="10">10</option>
        </select> entries
      </label>
    </div>
    <div class="pagination">
      <button id="prev" disabled>Previous</button>
      <span id="page-indicator">1</span>
      <button id="next">Next</button>
    </div>
    <p id="entry-info">Showing 1 to 3 of 3 entries</p>
  </div>
  <table>
    <thead>
      <tr>
        <th>Employee</th>
        <th>From</th>
        <th>To</th>
        <th>No. Of Days</th>
        <th>Reason</th>
        <th>Date Filed</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="leave-body">
      <?php
      $sql = "SELECT la.*, CONCAT(e.Fname, ' ', e.Lname) AS fullname
              FROM leave_applications la
              JOIN employees e ON la.Employee_ID = e.Employee_ID
              ORDER BY la.date_applied DESC"; // Newest entries first
      $result = $conn->query($sql);

      while ($row = $result->fetch_assoc()) {
          // Calculate number of days
          $start = new DateTime($row['start_date']);
          $end = new DateTime($row['end_date']);
          $interval = $start->diff($end);
          $days = $interval->days + 1;

          // Format date_applied to show only the date
          $date_filed = date('Y-m-d', strtotime($row['date_applied']));

          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
          echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
          echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
          echo "<td>" . $days . "</td>";
          echo "<td>" . htmlspecialchars($row['leaveType']) . "</td>";
          echo "<td>" . htmlspecialchars($date_filed) . "</td>";
          echo "<td>";
          if (strtolower($row['status']) == 'pending') {
              echo "<a href='approve_letter.php?leave_id={$row['Leaves_ID']}' style='display:inline;'>
                      <button type='button' class='approve-btn' title='Approve'>&#10003;</button>
                    </a>
                    <a href='decline_letter.php?leave_id={$row['Leaves_ID']}' style='display:inline;'>
                      <button type='button' class='decline-btn' title='Decline'>&#10005;</button>
                    </a>";
          }
          // Always show status badge and view button
          $status_badge = '';
          if (strtolower($row['status']) == 'approved') {
              $status_badge = "<span class='badge bg-success approve-badge' title='Approved'>&#10003;</span> ";
          } elseif (strtolower($row['status']) == 'declined') {
              $status_badge = "<span class='badge bg-danger decline-badge' title='Declined'>&#10005;</span> ";
          }
          echo $status_badge;
          echo "<a href='leave details.php?leave_id={$row['Leaves_ID']}' target='_blank'>
                    <button type='button' class='view-btn' title='View'>👁️</button>
                </a>";
          echo "</td>";
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>
  <?php if (isset($_SESSION['leave_approved'])): ?>
  <script>alert('Approved leave successful');</script>
  <?php unset($_SESSION['leave_approved']); endif; ?>
  <script src="/finals_integrated/components/script1-2.js"></script>
</body>
</html>
