<?php
session_start();
include '../../database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $conn = mysqli_connect('localhost', 'root', '', 'accounting management system');
    $username = $_SESSION['employee_username'];
    $current = $_POST['currentPassword'];
    $new = $_POST['newPassword'];
    $retype = $_POST['retypePassword'];
    $result = mysqli_query($conn, "SELECT Password FROM employees WHERE Username='$username'");
    $row = mysqli_fetch_assoc($result);
    if ($row && $row['Password'] == $current) {
        if ($new === $retype) {
            mysqli_query($conn, "UPDATE employees SET Password='$new' WHERE Username='$username'");
            // Destroy session and redirect to login
            session_destroy();
            echo json_encode(['success' => true, 'logout' => true, 'message' => 'Password now changed. Please log in again.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'New password and retype password do not match.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    }
    exit();
}
// Info update endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_info') {
    $conn = mysqli_connect('localhost', 'root', '', 'accounting management system');
    $oldUsername = $_SESSION['employee_username'];
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPhone = $_POST['phone'];
    $update = mysqli_query($conn, "UPDATE employees SET Username='$newUsername', EmailAdd='$newEmail', PhoneNum='$newPhone' WHERE Username='$oldUsername'");
    if ($update) {
        $_SESSION['employee_username'] = $newUsername;
        echo json_encode(['success' => true, 'message' => 'Information updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update information.']);
    }
    exit();
}
// Fetch employee info
$conn = mysqli_connect('localhost', 'root', '', 'accounting management system');
$username = $_SESSION['employee_username'];
$sql = "SELECT Username, Fname, middlename, Lname, EmailAdd, PhoneNum FROM employees WHERE Username='$username'";
$result = mysqli_query($conn, $sql);
$emp = mysqli_fetch_assoc($result);
$fullName = trim($emp['Fname'] . ' ' . ($emp['middlename'] ? $emp['middlename'] . ' ' : '') . $emp['Lname']);
$usernameVal = $emp['Username'];
$email = $emp['EmailAdd'];
$phone = $emp['PhoneNum'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Settings</title>
  <link rel="stylesheet" href="profile.css">
  <style>
    .header {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      background: #f0f0f0;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .header img {
      height: 40px;
      margin-right: 10px;
    }
    .header h1 {
      margin: 0;
      font-size: 1.5em;
      color: #333;
    }
  </style>
</head>
<body>
  <div class="profile-header">
    <button class="back-btn" id="backBtn" title="Back">&#8592;</button>
    <img src="486450f7-5524-4851-a7ea-64609947bc2c 1.png" alt="Logo" class="profile-logo" id="homeLogo">
    <span class="profile-title">Profile Settings</span>
  </div>
  <div class="profile-container">
    <div class="profile-card">
      <div class="profile-sidebar">
        <img src="profile.png" alt="Profile" class="profile-avatar">
        <div class="profile-fullname" style="font-weight:bold;text-align:center;margin:10px 0;">
          <?php echo htmlspecialchars($fullName); ?>
        </div>
        <a href="#" class="change-photo">Change photo</a>
        <hr>
        <div class="profile-links">
          <a href="#" class="profile-link active" id="infoLink"><span class="icon user"></span> Manage Information</a>
          <a href="#" class="profile-link" id="passwordLink"><span class="icon lock"></span> Change Password</a>
        </div>
        <a href="/finals_integrated/Login/login.php" class="logout-link">Logout</a>
      </div>
      <div class="profile-main">
        <h2>PROFILE</h2>
        <form id="infoForm">
          <label>Username:<input type="text" id="infoUsername" value="<?php echo htmlspecialchars($usernameVal); ?>" disabled required></label>
          <label>Email:<input type="email" id="infoEmail" value="<?php echo htmlspecialchars($email); ?>" disabled required></label>
          <label>Contact No.<input type="text" id="infoPhone" value="<?php echo htmlspecialchars($phone); ?>" disabled required></label>
          <div class="profile-actions" id="infoActions">
            <button type="button" class="edit-btn" id="editInfoBtn">Edit</button>
          </div>
          <div id="infoMessage" style="margin-top:10px;color:red;"></div>
        </form>
        <form id="passwordForm" style="display:none;">
          <label>Type current password <span style="color:red">*</span>
            <div class="input-icon"><input type="password" id="currentPassword" disabled required><span class="icon eye"></span></div>
          </label>
          <label>New password <span style="color:red">*</span>
            <div class="input-icon"><input type="password" id="newPassword" disabled required><span class="icon eye"></span></div>
          </label>
          <label>Retype new password <span style="color:red">*</span>
            <div class="input-icon"><input type="password" id="retypePassword" disabled required><span class="icon eye"></span></div>
          </label>
          <div class="profile-actions" id="passwordActions">
            <button type="button" class="edit-btn" id="editPasswordBtn">Edit</button>
          </div>
          <div id="passwordMessage" style="margin-top:10px;color:red;"></div>
        </form>
      </div>
    </div>
  </div>
  <script src="profile.js"></script>
</body>
</html>
