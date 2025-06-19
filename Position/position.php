<?php
session_start();
include '../components/sidebar.php';
$conn = new mysqli("localhost", "root", "", "accounting management system");

// Handle Add Position
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_position'])) {
    $title = $conn->real_escape_string($_POST['position_title']);
    $rate = $conn->real_escape_string($_POST['rate_per_hour']);
    $conn->query("INSERT INTO positions (Title, RatePerHour) VALUES ('$title', '$rate')");
}
// Handle Edit Position
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_position'])) {
    $id = intval($_POST['position_id']);
    $title = $conn->real_escape_string($_POST['position_title']);
    $rate = $conn->real_escape_string($_POST['rate_per_hour']);
    $conn->query("UPDATE positions SET Title='$title', RatePerHour='$rate' WHERE Position_ID=$id");
}

// Pagination logic
$entries = isset($_GET['entries']) ? intval($_GET['entries']) : 5;
if (!in_array($entries, [5, 10])) $entries = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $entries;

// Fetch total count
$count_sql = "SELECT COUNT(*) as total FROM positions";
$count_result = $conn->query($count_sql);
$total_positions = ($count_result && $row = $count_result->fetch_assoc()) ? intval($row['total']) : 0;
$total_pages = ceil($total_positions / $entries);

// Fetch paginated positions and count employees for each
$positions = [];
$sql = "SELECT p.Position_ID, p.Title, p.RatePerHour, COUNT(e.Employee_ID) AS EmployeeCount
        FROM positions p
        LEFT JOIN employees e ON p.Position_ID = e.Position_ID
        GROUP BY p.Position_ID, p.Title, p.RatePerHour
        ORDER BY p.Position_ID DESC
        LIMIT $entries OFFSET $offset";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $positions[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Position</title>
  <link rel="stylesheet" href="../Leaves/style3.css" />
  <link rel="stylesheet" href="../components/sidebar.css" />
  <link rel="stylesheet" href="position.css" />
  <link rel="stylesheet" href="position-modal.css" />
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>POSITIONS</h1>
      <p>Dashboard / Company Positions</p>
    </header>
    <button class="add-position-btn" id="openAddModal">+ Add Position</button>
    <div class="controls">
      <form method="get" id="entriesForm" style="display:inline;">
        <label for="entries">Show
          <select id="entries" name="entries" onchange="document.getElementById('entriesForm').submit();">
            <option value="5" <?php if($entries==5) echo 'selected'; ?>>5</option>
            <option value="10" <?php if($entries==10) echo 'selected'; ?>>10</option>
          </select> entries
        </label>
        <input type="hidden" name="page" value="1" />
      </form>
    </div>
    <table id="positions-table">
      <thead>
        <tr>
          <th>No. of Employees</th>
          <th>Position ID</th>
          <th>Position Title</th>
          <th>Rate per Hour</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($positions as $pos): ?>
        <tr>
          <td><?php echo $pos['EmployeeCount']; ?></td>
          <td><?php echo htmlspecialchars($pos['Position_ID']); ?></td>
          <td><?php echo htmlspecialchars($pos['Title']); ?></td>
          <td><?php echo htmlspecialchars($pos['RatePerHour']); ?></td>
          <td><button class="edit-btn" type="button" data-id="<?php echo $pos['Position_ID']; ?>" data-title="<?php echo htmlspecialchars($pos['Title']); ?>" data-rate="<?php echo htmlspecialchars($pos['RatePerHour']); ?>">Edit</button></td>
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
    <p id="entry-info">Showing <?php echo ($total_positions==0)?0:($offset+1); ?> to <?php echo min($offset+$entries, $total_positions); ?> of <?php echo $total_positions; ?> entries</p>
  </div>

  <!-- Add/Edit Modal -->
  <div class="modal-overlay" id="positionModal">
    <div class="modal">
      <form method="POST" id="positionForm">
        <div class="modal-header" id="modalTitle">New Position</div>
        <div class="modal-body">
          <input type="hidden" name="position_id" id="position_id">
          <label>Position Title
            <input type="text" name="position_title" id="position_title" placeholder="Position..." required>
          </label>
          <label>Rate Per Hour
            <input type="number" name="rate_per_hour" id="rate_per_hour" placeholder="Rate..." required>
          </label>
        </div>
        <div class="modal-footer">
          <button type="button" class="close-btn" id="closeModal">Close</button>
          <button type="submit" class="save-btn" id="modalSaveBtn" name="add_position">Add Position</button>
        </div>
      </form>
    </div>
  </div>

  <script src="position-modal.js"></script>
  <script src="../components/script1-2.js"></script>
</body>
</html> 