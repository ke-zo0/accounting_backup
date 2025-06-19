<?php
session_start();
include '../components/sidebar.php';

$conn = new mysqli("localhost", "root", "", "accounting management system");

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM employees WHERE Employee_ID = $id");
}

// Handle Edit/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_save']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $fname = $conn->real_escape_string($_POST['first_name']);
    $mname = $conn->real_escape_string($_POST['middle_name']);
    $lname = $conn->real_escape_string($_POST['last_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $position = $conn->real_escape_string($_POST['position']);
    $schedule_id = $conn->real_escape_string($_POST['schedule_id']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $date = $conn->real_escape_string($_POST['join_date']);
    $sql = "UPDATE employees SET Fname='$fname', middlename='$mname', Lname='$lname', Address='$address', EmailAdd='$email', PhoneNum='$phone', Position_ID='$position', Schedule_ID='$schedule_id', Sex='$sex', DateHired='$date' WHERE Employee_ID=$id";
    $conn->query($sql);
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name']) && !isset($_POST['edit_save'])) {
    $fname = $conn->real_escape_string($_POST['first_name']);
    $mname = $conn->real_escape_string($_POST['middle_name']);
    $lname = $conn->real_escape_string($_POST['last_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $position = $conn->real_escape_string($_POST['position']);
    $schedule_id = $conn->real_escape_string($_POST['schedule_id']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $date = $conn->real_escape_string($_POST['join_date']);
    $sql = "INSERT INTO employees (Fname, middlename, Lname, Address, EmailAdd, PhoneNum, Position_ID, Schedule_ID, Sex, DateHired)
            VALUES ('$fname', '$mname', '$lname', '$address', '$email', '$phone', '$position', '$schedule_id', '$sex', '$date')";
    $conn->query($sql);

    // Get the new Employee_ID
    $new_employee_id = $conn->insert_id;

    // Set Username and Password
    $conn->query("UPDATE employees SET Username = EmailAdd, Password = Employee_ID WHERE Employee_ID = $new_employee_id");
}

// Fetch all employees for the table
$employees = [];
$result = $conn->query("SELECT e.*, p.Title AS Position FROM employees e LEFT JOIN positions p ON e.Position_ID = p.Position_ID ORDER BY e.Employee_ID DESC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Fetch all positions
$positions = [];
$posResult = $conn->query("SELECT Position_ID, Title FROM positions");
while ($row = $posResult->fetch_assoc()) {
    $positions[] = $row;
}

// Fetch all schedules
$schedules = [];
$schedResult = $conn->query("SELECT * FROM schedule");
while ($row = $schedResult->fetch_assoc()) {
    $schedules[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>All Employees</title>
  <link rel="stylesheet" href="style4.css" />
  <link rel="stylesheet" href="/finals_integrated/components/sidebar.css" />
  <link rel="stylesheet" href="form-modal.css" />
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>ALL EMPLOYEES</h1>
      <p>Dashboard / Employee</p>
    </header>
    <div class="top-controls">
      <select id="positionFilterTable">
        <option value="" disabled selected hidden>Select Position</option>
        <?php
          $posResult = $conn->query("SELECT Position_ID, Title FROM positions");
          while ($pos = $posResult->fetch_assoc()) {
            echo '<option value="'.$pos['Position_ID'].'">'.htmlspecialchars($pos['Title']).'</option>';
          }
        ?>
      </select>
      <input type="text" id="searchInputTable" placeholder="Enter employee ID/NAME" />
      <button id="searchBtnTable">SEARCH</button>
      <button class="add-btn" id="addEmployeeBtn">+ Add Employee</button>
      <button id="clearFilterBtn" style="display:none;">Clear Filter</button>
    </div>
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th class="sticky-col">Name</th>
            <th>Employee ID</th>
            <th>Email</th>
            <th>Mobile Number</th>
            <th>Date Hired</th>
            <th>Position</th>
            <th class="sticky-action">Actions</th>
          </tr>
        </thead>
        <tbody id="employeeBody">
          <?php foreach ($employees as $emp): ?>
            <tr>
              <td class="sticky-col"><?php echo htmlspecialchars($emp['Fname'] . ' ' . $emp['middlename'] . ' ' . $emp['Lname']); ?></td>
              <td><?php echo htmlspecialchars($emp['Employee_ID']); ?></td>
              <td><?php echo htmlspecialchars($emp['EmailAdd']); ?></td>
              <td><?php echo htmlspecialchars($emp['PhoneNum']); ?></td>
              <td><?php echo htmlspecialchars($emp['DateHired']); ?></td>
              <td><?php echo htmlspecialchars($emp['Position']); ?></td>
              <td class="sticky-action">
                <div class="action-btns">
                  <button class="edit-btn" type="button"
                    data-id="<?php echo $emp['Employee_ID']; ?>"
                    data-fname="<?php echo htmlspecialchars($emp['Fname']); ?>"
                    data-mname="<?php echo htmlspecialchars($emp['middlename']); ?>"
                    data-lname="<?php echo htmlspecialchars($emp['Lname']); ?>"
                    data-address="<?php echo htmlspecialchars($emp['Address']); ?>"
                    data-email="<?php echo htmlspecialchars($emp['EmailAdd']); ?>"
                    data-phone="<?php echo htmlspecialchars($emp['PhoneNum']); ?>"
                    data-position="<?php echo htmlspecialchars($emp['Position_ID']); ?>"
                    data-sex="<?php echo htmlspecialchars($emp['Sex']); ?>"
                    data-datehired="<?php echo htmlspecialchars($emp['DateHired']); ?>"
                    data-schedule_id="<?php echo htmlspecialchars($emp['Schedule_ID']); ?>"
                    title="Edit"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19.5 2 20l.5-5L16.5 3.5z"/></svg>
                  </button>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $emp['Employee_ID']; ?>">
                    <button class="delete-btn" type="submit" name="delete" onclick="return confirm('Delete this employee?');" title="Delete">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Add Modal Overlay -->
  <div class="modal-overlay" id="employeeModal">
    <div class="modal">
      <h2>New Employee Personal Data</h2>
      <form id="employeeForm" method="POST" autocomplete="off">
        <div class="form-row"><label>First Name <span style="color:red">*</span><input type="text" name="first_name" required placeholder="Enter first name"></label></div>
        <div class="form-row"><label>Middle Name<input type="text" name="middle_name" placeholder="Enter middle name"></label></div>
        <div class="form-row"><label>Last Name <span style="color:red">*</span><input type="text" name="last_name" required placeholder="Enter last name"></label></div>
        <div class="form-row"><label>Suffix<input type="text" name="suffix" placeholder="N/A"></label></div>
        <div class="form-row"><label>Address <span style="color:red">*</span><input type="text" name="address" required placeholder="Enter address....."></label></div>
        <div class="form-row"><label>Email Address <span style="color:red">*</span><input type="email" name="email" required placeholder="Enter email address..."></label></div>
        <div class="form-row"><label>Phone Number <span style="color:red">*</span><input type="text" name="phone" required placeholder="Enter phone no." pattern="[0-9]+" inputmode="numeric"></label></div>
        <div class="form-row">
          <label>Position <span style="color:red">*</span>
            <select name="position" id="positionSelect" required>
              <option value="">Select a position</option>
              <?php foreach ($positions as $pos): ?>
                <option value="<?= $pos['Position_ID'] ?>"><?= htmlspecialchars($pos['Title']) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
        </div>
        <div class="form-row">
          <label>Schedule <span style="color:red">*</span>
            <select name="schedule_id" id="scheduleSelect" required>
              <option value="">Select a schedule</option>
              <!-- Options will be populated by JS -->
            </select>
          </label>
        </div>
        <div class="form-row sex"><label>Sex</label>
          <label><input type="radio" name="sex" value="Female"> Female</label>
          <label><input type="radio" name="sex" value="Male"> Male</label>
        </div>
        <div class="form-row"><label>Date Hired <span style="color:red">*</span><input type="date" name="join_date" required></label></div>
        <div class="form-actions">
          <button type="submit" class="save-btn">&#8595; SAVE</button>
          <button type="button" class="cancel-btn" id="cancelEmployeeBtn">&#10006; CANCEL</button>
        </div>
      </form>
    </div>
  </div>
  <!-- Edit Modal Overlay -->
  <div class="modal-overlay" id="editEmployeeModal">
    <div class="modal">
      <h2>Edit Employee</h2>
      <form id="editEmployeeForm" method="POST" autocomplete="off">
        <input type="hidden" name="id" id="edit_id">
        <div class="form-row"><label>First Name <span style="color:red">*</span><input type="text" name="first_name" id="edit_fname" required></label></div>
        <div class="form-row"><label>Middle Name<input type="text" name="middle_name" id="edit_mname"></label></div>
        <div class="form-row"><label>Last Name <span style="color:red">*</span><input type="text" name="last_name" id="edit_lname" required></label></div>
        <div class="form-row"><label>Suffix<input type="text" name="suffix" id="edit_suffix"></label></div>
        <div class="form-row"><label>Address<input type="text" name="address" id="edit_address"></label></div>
        <div class="form-row"><label>Email Address <span style="color:red">*</span><input type="email" name="email" id="edit_email" required></label></div>
        <div class="form-row"><label>Phone Number <span style="color:red">*</span><input type="text" name="phone" id="edit_phone" required></label></div>
        <div class="form-row"><label>Position <span style="color:red">*</span>
          <select name="position" id="edit_position" required>
            <option value="">Select a position</option>
            <?php
              $posResult = $conn->query("SELECT Position_ID, Title FROM positions");
              while ($pos = $posResult->fetch_assoc()) {
                echo '<option value="'.$pos['Position_ID'].'">'.htmlspecialchars($pos['Title']).'</option>';
              }
            ?>
          </select></label></div>
        <div class="form-row">
          <label>Schedule <span style="color:red">*</span>
            <select name="schedule_id" id="edit_schedule" required>
              <option value="">Select a schedule</option>
              <!-- Options will be populated by JS -->
            </select>
          </label>
        </div>
        <div class="form-row sex"><label>Sex</label>
          <label><input type="radio" name="sex" value="Female" id="edit_sex_female"> Female</label>
          <label><input type="radio" name="sex" value="Male" id="edit_sex_male"> Male</label>
        </div>
        <div class="form-row"><label>Date Hired<input type="date" name="join_date" id="edit_join_date"></label></div>
        <div class="form-actions">
          <button type="submit" class="save-btn" name="edit_save">&#8595; SAVE</button>
          <button type="button" class="cancel-btn" id="cancelEditEmployeeBtn">&#10006; CANCEL</button>
        </div>
      </form>
    </div>
  </div>
  <script src="/finals_integrated/components/script1-2.js"></script>
  <script src="form-modal.js"></script>
  <script>
    // Make allSchedules available globally for form-modal.js
    window.allSchedules = <?php echo json_encode($schedules); ?>;
  </script>
  <script>
    // Position filter logic and placeholder style
    const positionFilter = document.getElementById('positionFilterTable');
    function updatePlaceholderStyle() {
      if (!positionFilter.value) {
        positionFilter.classList.add('placeholder-selected');
      } else {
        positionFilter.classList.remove('placeholder-selected');
      }
    }
    updatePlaceholderStyle();
    positionFilter.addEventListener('change', function() {
      updatePlaceholderStyle();
      const posId = this.value;
      const rows = document.querySelectorAll('#employeeBody tr');
      let hasVisibleRows = false;
      rows.forEach(row => {
        const posCell = row.querySelector('td:nth-child(6)');
        if (posCell && posCell.textContent.trim() === this.options[this.selectedIndex].text) {
          row.style.display = '';
          hasVisibleRows = true;
        } else {
          row.style.display = 'none';
        }
      });
      document.getElementById('clearFilterBtn').style.display = hasVisibleRows ? 'inline-block' : 'none';
    });
    document.getElementById('clearFilterBtn').addEventListener('click', function() {
      positionFilter.value = '';
      updatePlaceholderStyle();
      const rows = document.querySelectorAll('#employeeBody tr');
      rows.forEach(row => row.style.display = '');
      this.style.display = 'none';
    });
  </script>
  <script>
    // Custom validation for phone number field in Add Employee modal
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
      phoneInput.addEventListener('invalid', function() {
        this.setCustomValidity('Please enter a valid numeric value');
      });
      phoneInput.addEventListener('input', function() {
        this.setCustomValidity('');
      });
    }
  </script>
</body>
</html>

