<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Handle logout if ?logout=1 is in the URL
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /finals_integrated/Login/login.php");
    exit();
}

if (!isset($_SESSION['employee_username'])) {
    header('Location: /finals_integrated/Login/login.php');
    exit();
}
$conn = mysqli_connect('localhost', 'root', '', 'accounting management system');
$username = $_SESSION['employee_username'];
$sql = "SELECT Fname, middlename, Lname FROM employees WHERE Username='$username'";
$result = mysqli_query($conn, $sql);
$name = '';
if ($row = mysqli_fetch_assoc($result)) {
    $name = trim($row['Fname'] . ' ' . ($row['middlename'] ? $row['middlename'] . ' ' : '') . $row['Lname']);
}

$employee_id = $_SESSION['Employee_ID'];
$notif_sql = "SELECT * FROM leave_applications WHERE Employee_ID = $employee_id AND (status = 'Approved' OR status = 'Declined') ORDER BY date_applied DESC";
$notif_result = mysqli_query($conn, $notif_sql);
$notif_count = mysqli_num_rows($notif_result);
?>
<header class="dashboard-header">
  <div class="logo-box" id="homeLogo" style="cursor:pointer;">
    <img src="/finals_integrated/user_side/user dashboard/486450f7-5524-4851-a7ea-64609947bc2c 1.png" alt="Logo" class="logo-img">
  </div>
  <div class="header-right">
    <div class="notification-wrapper" style="position:relative;">
      <img src="/finals_integrated/user_side/user dashboard/image.png" alt="Notifications" class="notification-icon" id="notifBell" style="cursor:pointer;">
      <?php if ($notif_count > 0): ?>
        <span class="notif-badge"><?php echo $notif_count; ?></span>
      <?php endif; ?>
      <div class="notif-dropdown" id="notifDropdown" style="display:none;">
        <?php if ($notif_count > 0): ?>
          <?php while ($notif = mysqli_fetch_assoc($notif_result)): ?>
            <div class="notif-item" data-leave-id="<?php echo $notif['Leaves_ID']; ?>" data-leave-status="<?php echo $notif['status']; ?>">
              <div class="notif-title">
                <?php
                  if ($notif['status'] === 'Approved') {
                    echo "Your application for " . htmlspecialchars($notif['leaveType']) . " is approved!";
                  } elseif ($notif['status'] === 'Declined') {
                    echo "Your leave application has been declined";
                  }
                ?>
              </div>
              <div class="notif-date"><?php echo date('M d, Y', strtotime($notif['date_applied'])); ?></div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="notif-item">No new notifications</div>
        <?php endif; ?>
      </div>
    </div>
    <div class="profile-dropdown" id="profileDropdown">
      <img src="/finals_integrated/user_side/user dashboard/profile.png" alt="Profile" class="profile-pic">
      <span class="profile-name"><?php echo htmlspecialchars($name); ?></span>
      <img src="/finals_integrated/user_side/user dashboard/arrow-down-sign-to-navigate.png" alt="Dropdown" class="dropdown-arrow">
      <div class="dropdown-menu" id="dropdownMenu">
        <a href="/finals_integrated/user_side/user dashboard/profile.php" class="dropdown-item" id="profileMenuItem"><span class="dropdown-icon user-icon"></span> Profile</a>
        <a href="/finals_integrated/Login/login.php" class="dropdown-item logout"><span class="dropdown-icon logout-icon"></span> Log Out</a>
      </div>
    </div>
  </div>
</header>
<link rel="stylesheet" href="/finals_integrated/user_side/user dashboard/style.css">
<script src="/finals_integrated/user_side/user dashboard/dashboard.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const profileDropdown = document.getElementById('profileDropdown');
  const dropdownMenu = document.getElementById('dropdownMenu');
  const homeLogo = document.getElementById('homeLogo');
  const notifBell = document.getElementById('notifBell');
  const notifDropdown = document.getElementById('notifDropdown');
  if (profileDropdown) {
    profileDropdown.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdownMenu.classList.toggle('show');
    });
    document.addEventListener('click', function() {
      dropdownMenu.classList.remove('show');
    });
  }
  if (homeLogo) {
    homeLogo.addEventListener('click', function() {
      window.location.href = '/finals_integrated/user_side/user dashboard/userdashboard.php';
    });
  }
  if (notifBell && notifDropdown) {
    notifBell.addEventListener('click', function(e) {
      e.stopPropagation();
      notifDropdown.style.display = notifDropdown.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', function() {
      notifDropdown.style.display = 'none';
    });
  }
  if (notifDropdown) {
    notifDropdown.addEventListener('click', function(e) {
      const item = e.target.closest('.notif-item');
      if (item) {
        const leaveId = item.getAttribute('data-leave-id');
        const status = item.getAttribute('data-leave-status');
        if (status === 'Approved') {
          window.open('/finals_integrated/Leaves/approve_letter.php?leave_id=' + leaveId, '_blank');
        } else if (status === 'Declined') {
          window.open('/finals_integrated/Leaves/decline_letter.php?leave_id=' + leaveId, '_blank');
        }
      }
    });
  }
});
</script> 