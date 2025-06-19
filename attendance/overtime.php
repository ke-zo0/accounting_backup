<?php
include '../database.php';
session_start();
include '../components/sidebar.php';
// Do NOT include anything that destroys the session or closes the connection here

// Handle POST actions first
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_overtime'])) {
    $employee_id = intval($_POST['employee_id']);
    $position_id = intval($_POST['position_id']);
    $date = $_POST['date'];
    $hours = intval($_POST['hours']);
    $minutes = intval($_POST['minutes']);
    $numof_hrs = $hours + ($minutes / 60);
    $numof_hrs_str = rtrim(rtrim(number_format($numof_hrs, 2), '0'), '.') . ' HRS';

    // Get rate from positions
    $rate_result = $conn->query("SELECT RatePerHour FROM positions WHERE Position_ID = $position_id");
    $rate_row = $rate_result->fetch_assoc();
    $rate = floatval(preg_replace('/[^0-9.]/', '', $rate_row['RatePerHour'])); // Remove ₱

    $gross = $numof_hrs * $rate;

    // Insert into overtime
    $stmt = $conn->prepare("INSERT INTO overtime (Employee_ID, Position_ID, Numof_HRS, Date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $employee_id, $position_id, $numof_hrs_str, $date);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['delete_overtime']) && isset($_POST['overtime_id'])) {
    $overtime_id = intval($_POST['overtime_id']);
    $conn->query("DELETE FROM overtime WHERE Overtime_ID = $overtime_id");
}

// Now fetch employees and positions for the form
$employees = [];
$positions = [];
$empResult = $conn->query("SELECT e.Employee_ID, e.Fname, e.middlename, e.Lname, e.Position_ID, p.RatePerHour FROM employees e JOIN positions p ON e.Position_ID = p.Position_ID");
while ($row = $empResult->fetch_assoc()) {
  $employees[] = $row;
}

if (!is_object($conn)) {
    die('Database connection is not an object!');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Overtime</title>
  <link rel="stylesheet" href="overtime.css" />
  <link rel="stylesheet" href="../components/sidebar.css" />
  <link rel="stylesheet" href="overtime-modal.css" />
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>OVERTIME</h1>
      <p>Dashboard / Attendance</p>
    </header>
    <div class="positions-card">
      <button class="add-position-btn" id="openOvertimeModal">&#43; Add Overtime</button>
      <div style="background:#fff; border-radius:8px; padding:2rem 1.5rem;">
        <div style="font-weight:600; margin-bottom:1rem;">Overtime Table</div>
        <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
          <label>Show <input type="number" value="10" min="1" style="width:3rem; padding:0.2rem 0.5rem; border-radius:4px; border:1px solid #ccc;"> entries</label>
          <span style="margin-left:auto;">Search: <input type="text" style="padding:0.2rem 0.5rem; border-radius:4px; border:1px solid #ccc;"></span>
        </div>
        <table>
          <thead>
            <tr>
              <th>OVERTIME ID</th>
              <th>EMPLOYEE ID</th>
              <th>EMPLOYEE NAME</th>
              <th>NUMBER OF HOURS</th>
              <th>RATE</th>
              <th>GROSS</th>
              <th>DATE</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT o.*, e.Fname, e.middlename, e.Lname, p.RatePerHour
                    FROM overtime o
                    JOIN employees e ON o.Employee_ID = e.Employee_ID
                    JOIN positions p ON o.Position_ID = p.Position_ID
                    ORDER BY o.Overtime_ID DESC";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $numof_hrs = $row['Numof_HRS'];
                    $rate = floatval(preg_replace('/[^0-9.]/', '', $row['RatePerHour']));
                    // Remove ' HRS' for calculation
                    $numof_hrs_val = floatval(str_replace(' HRS', '', $numof_hrs));
                    $gross = $numof_hrs_val * $rate;
                    echo "<tr>
                        <td>{$row['Overtime_ID']}</td>
                        <td>{$row['Employee_ID']}</td>
                        <td>{$row['Fname']} {$row['middlename']} {$row['Lname']}</td>
                        <td>{$numof_hrs}</td>
                        <td>{$row['RatePerHour']}</td>
                        <td>₱" . number_format($gross, 2) . "</td>
                        <td>{$row['Date']}</td>
                        <td>
                            <form method='POST' style='display:inline;' onsubmit='return confirm(\"Delete this overtime record?\");'>
                                <input type='hidden' name='delete_overtime' value='1'>
                                <input type='hidden' name='overtime_id' value='{$row['Overtime_ID']}'>
                                <button type='submit' class='delete-btn' title='Delete'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo '<tr><td colspan="8" style="text-align:center; color:#888;">No data available in table</td></tr>';
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
  <!-- Overtime Modal -->
  <div class="modal-overlay" id="overtimeModal">
    <div class="modal">
      <form id="overtimeForm" method="POST">
        <input type="hidden" name="add_overtime" value="1">
        <div class="modal-header">New Overtime</div>
        <div class="modal-body">
          <label>Employee
            <select id="employeeSelect" class="styled-select" name="employee_id" required>
              <option value="">Select Employee</option>
              <?php foreach ($employees as $emp): ?>
                <option value="<?= $emp['Employee_ID'] ?>" data-rate="<?= htmlspecialchars($emp['RatePerHour']) ?>" data-position="<?= $emp['Position_ID'] ?>">
                  <?= htmlspecialchars($emp['Fname'] . ' ' . $emp['middlename'] . ' ' . $emp['Lname']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </label>
          <input type="hidden" id="positionId" name="position_id">
          <label>Date of Overtime
            <input type="date" id="overtimeDate" name="date" required>
          </label>
          <label>Number of Hours
            <input type="number" name="hours" placeholder="Enter number of hours..." min="0" required>
          </label>
          <label>Number of Minutes
            <input type="number" name="minutes" placeholder="Enter number of minutes..." min="0" max="59" required>
          </label>
          <label>Rate Per Hour
            <input type="text" id="ratePerHour" placeholder="Enter rate per hour..." readonly required>
          </label>
        </div>
        <div class="modal-footer">
          <button type="button" class="close-btn" id="closeOvertimeModal">Close</button>
          <button type="submit" class="save-btn">Add Overtime</button>
        </div>
      </form>
    </div>
  </div>
  <script src="overtime-modal.js"></script>
  <script src="../components/script1-2.js"></script>
  <style>
    .delete-btn {
      background: #e74c3c;
      color: #fff;
      border: none;
      border-radius: 4px;
      padding: 0.3rem 1rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }
    .delete-btn:hover {
      background: #c0392b;
    }
  </style>
</body>
</html> 