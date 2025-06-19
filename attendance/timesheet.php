<?php
session_start();
include '../components/sidebar.php';
include '../database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Attendance Timesheet</title>
  <link rel="stylesheet" href="timesheet.css" />
  <link rel="stylesheet" href="../components/sidebar.css" />
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>TIMESHEET</h1>
      <p>Dashboard / Attendance</p>
    </header>
    <div class="positions-card">
      <div class="positions-controls">
        <form method="GET" style="display:flex; align-items:center; gap:1rem; margin:0;">
          <input type="date" name="start_date" class="add-position-btn" style="background:#fff; color:#333; font-weight:400; width:auto; min-width:140px;" required>
          <span style="font-weight:600; color:#333;">to</span>
          <input type="date" name="end_date" class="add-position-btn" style="background:#fff; color:#333; font-weight:400; width:auto; min-width:140px;" required>
          <button type="submit" class="add-position-btn" style="margin:0;">Filter Date</button>
        </form>
      </div>
      <div style="background:#fff; border-radius:8px; padding:2rem 1.5rem;">
        <div style="font-weight:600; margin-bottom:1rem;">Attendance Table for <b>June 12, 2025</b></div>
        <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
          <label>Show <input type="number" value="10" min="1" style="width:3rem; padding:0.2rem 0.5rem; border-radius:4px; border:1px solid #ccc;"> entries</label>
          <span style="margin-left:auto;">Search: <input type="text" style="padding:0.2rem 0.5rem; border-radius:4px; border:1px solid #ccc;"></span>
        </div>
        <table style="width:100%; border-collapse:collapse;">
          <thead style="background:#f4f3ef;">
            <tr>
              <th>EMPLOYEE NAME</th>
              <th>TIMEIN AM</th>
              <th>TIMEOUT AM</th>
              <th>TOTAL TIME</th>
              <th>TIMEIN PM</th>
              <th>TIMEOUT PM</th>
              <th>TOTAL TIME</th>
              <th>DATE</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
<?php
// Filter by date range if set
$where = "";
$params = [];
$types = "";
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $where = "WHERE a.Date BETWEEN ? AND ?";
    $params[] = $_GET['start_date'];
    $params[] = $_GET['end_date'];
    $types = "ss";
}

$sql = "SELECT a.*, e.Fname, e.middlename, e.Lname 
        FROM attendance a
        JOIN employees e ON a.Employee_ID = e.Employee_ID
        $where
        ORDER BY a.Date DESC";
$stmt = $conn->prepare($sql);
if ($where) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate total hours for AM and PM
        $total_am = "";
        if ($row['TimeIn_AM'] && $row['TimeOut_AM']) {
            $in_am = strtotime($row['TimeIn_AM']);
            $out_am = strtotime($row['TimeOut_AM']);
            $total_am = round(($out_am - $in_am) / 3600, 2) . " hrs";
        }
        $total_pm = "";
        if ($row['TimeIn_PM'] && $row['TimeOut_PM']) {
            $in_pm = strtotime($row['TimeIn_PM']);
            $out_pm = strtotime($row['TimeOut_PM']);
            $total_pm = round(($out_pm - $in_pm) / 3600, 2) . " hrs";
        }
        echo "<tr>
            <td>{$row['Fname']} {$row['middlename']} {$row['Lname']}</td>
            <td>" . ($row['TimeIn_AM'] ?? '-') . "</td>
            <td>" . ($row['TimeOut_AM'] ?? '-') . "</td>
            <td>" . ($total_am ?: '-') . "</td>
            <td>" . ($row['TimeIn_PM'] ?? '-') . "</td>
            <td>" . ($row['TimeOut_PM'] ?? '-') . "</td>
            <td>" . ($total_pm ?: '-') . "</td>
            <td>{$row['Date']}</td>
            <td>-</td>
        </tr>";
    }
} else {
    echo '<tr><td colspan="9" style="text-align:center; color:#888;">No data available in table</td></tr>';
}
?>
          </tbody>
        </table>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:1rem;">
          <span>Showing 0 to 0 of 0 entries</span>
          <div>
            <button style="padding:0.3rem 1rem; border-radius:4px; border:1px solid #ccc; background:#eee; color:#888; margin-right:0.5rem;" disabled>Previous</button>
            <button style="padding:0.3rem 1rem; border-radius:4px; border:1px solid #ccc; background:#3b82f6; color:#fff;">1</button>
            <button style="padding:0.3rem 1rem; border-radius:4px; border:1px solid #ccc; background:#eee; color:#888; margin-left:0.5rem;" disabled>Next</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html> 