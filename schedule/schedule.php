<?php
session_start();
include '../components/sidebar.php';
$conn = new mysqli("localhost", "root", "", "accounting management system");

// Handle Add Schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule'])) {
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $position = $conn->real_escape_string($_POST['position']);
    $schedule = $conn->real_escape_string($_POST['schedule']);
    $conn->query("INSERT INTO schedule (Employee_ID, Name, Position, Schedule) VALUES ('$employee_id', '$name', '$position', '$schedule')");
}
// Handle Edit Schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_schedule'])) {
    $id = intval($_POST['schedule_id']);
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $position = $conn->real_escape_string($_POST['position']);
    $schedule = $conn->real_escape_string($_POST['schedule']);
    $conn->query("UPDATE schedule SET Employee_ID='$employee_id', Name='$name', Position='$position', Schedule='$schedule' WHERE Schedule_ID=$id");
}

// Pagination logic
$entries = isset($_GET['entries']) ? intval($_GET['entries']) : 5;
if (!in_array($entries, [5, 10])) $entries = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $entries;

// Fetch total count
$count_sql = "SELECT COUNT(*) as total FROM schedule";
$count_result = $conn->query($count_sql);
$total_schedules = ($count_result && $row = $count_result->fetch_assoc()) ? intval($row['total']) : 0;
$total_pages = ceil($total_schedules / $entries);

// Fetch paginated schedules
$schedules = [];
$sql = "SELECT 
    s.Schedule_ID,
    e.Employee_ID,
    CONCAT(e.Fname, ' ', e.middlename, ' ', e.Lname) AS Name,
    p.Title AS Position,
    s.FirstTime_IN,
    s.FirstTime_OUT,
    s.SecondTime_IN,
    s.SecondTime_OUT
FROM employees e
JOIN schedule s ON e.Schedule_ID = s.Schedule_ID
JOIN positions p ON e.Position_ID = p.Position_ID
ORDER BY s.Schedule_ID DESC
LIMIT $entries OFFSET $offset";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Schedule</title>
  <link rel="stylesheet" href="../Position/position.css" />
  <link rel="stylesheet" href="../components/sidebar.css" />
</head>
<body>
  <div class="container">
    <header class="header" style="margin-bottom: 1.5rem;">
      <h1 style="margin-bottom: 0.3rem;">SCHEDULE</h1>
      <p style="margin-bottom: 1.5rem; color: #888;">Dashboard / Employee Schedules</p>
    </header>
    <div class="positions-card">
      <div class="positions-controls" style="margin-bottom: 1.2rem;">
        <form method="get" id="entriesForm" style="display:inline; margin-right: 1.5rem;">
          <label for="entries">Show
            <select id="entries" name="entries" onchange="document.getElementById('entriesForm').submit();">
              <option value="5" <?php if($entries==5) echo 'selected'; ?>>5</option>
              <option value="10" <?php if($entries==10) echo 'selected'; ?>>10</option>
            </select> entries
          </label>
          <input type="hidden" name="page" value="1" />
        </form>
        <span class="search-label">
          Search: <input type="text" id="search" placeholder="" onkeyup="filterTable()" />
        </span>
      </div>
      <table id="positions-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Schedule</th>
          </tr>
        </thead>
        <tbody id="scheduleBody">
          <?php foreach ($schedules as $sched): ?>
          <tr>
            <td><?php echo htmlspecialchars($sched['Schedule_ID']); ?></td>
            <td><?php echo htmlspecialchars($sched['Employee_ID']); ?></td>
            <td><?php echo htmlspecialchars($sched['Name']); ?></td>
            <td><?php echo htmlspecialchars($sched['Position']); ?></td>
            <td>
              <?php
                echo htmlspecialchars($sched['FirstTime_IN']) . ' - ' . htmlspecialchars($sched['FirstTime_OUT']) .
                     ' / ' .
                     htmlspecialchars($sched['SecondTime_IN']) . ' - ' . htmlspecialchars($sched['SecondTime_OUT']);
              ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="pagination">
        <form method="get" style="display:inline;">
          <input type="hidden" name="entries" value="<?php echo $entries; ?>" />
          <button id="prev" name="page" value="<?php echo max(1, $page-1); ?>" <?php if($page<=1) echo 'disabled'; ?>>Previous</button>
        </form>
        <span id="page-indicator"><?php echo $page; ?></span>
        <form method="get" style="display:inline;">
          <input type="hidden" name="entries" value="<?php echo $entries; ?>" />
          <button id="next" name="page" value="<?php echo min($total_pages, $page+1); ?>" <?php if($page>=$total_pages) echo 'disabled'; ?>>Next</button>
        </form>
      </div>
      <p id="entry-info">Showing <?php echo ($total_schedules==0)?0:($offset+1); ?> to <?php echo min($offset+$entries, $total_schedules); ?> of <?php echo $total_schedules; ?> entries</p>
    </div>
  </div>
  <script>
    function filterTable() {
      var input = document.getElementById('search');
      var filter = input.value.toUpperCase();
      var table = document.getElementById('positions-table');
      var tr = table.getElementsByTagName('tr');
      for (var i = 1; i < tr.length; i++) {
        var tds = tr[i].getElementsByTagName('td');
        var show = false;
        for (var j = 0; j < tds.length; j++) {
          if (tds[j].textContent.toUpperCase().indexOf(filter) > -1) {
            show = true;
            break;
          }
        }
        tr[i].style.display = show ? '' : 'none';
      }
    }
  </script>
</body>
</html> 