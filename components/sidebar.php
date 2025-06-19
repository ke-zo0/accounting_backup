<?php
// session_start(); // Removed to avoid duplicate session_start warnings
if(!isset($_SESSION['admin_username'])) {
    header("Location: /finals_integrated/Login/login.php");
    exit();
}

// Fetch admin name from database
$admin_name = 'PROFILE';
$sidebar_conn = new mysqli("localhost", "root", "", "accounting management system");
if (!$sidebar_conn->connect_error) {
    $username = $sidebar_conn->real_escape_string($_SESSION['admin_username']);
    $result = $sidebar_conn->query("SELECT admin_name FROM admin WHERE admin_username='$username' LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        $admin_name = htmlspecialchars($row['admin_name']);
    }
}
?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-logo-container">
        <a href="/finals_integrated/Admin_Dashboard/admin_dashboard.php">
            <img src="/finals_integrated/components/486450f7-5524-4851-a7ea-64609947bc2c 1.png" alt="Logo" class="sidebar-logo" style="cursor:pointer;" />
        </a>
    </div>
    
    <div class="sidebar-item sidebar-item-with-submenu" id="employeesMenu">
        <img src="/finals_integrated/components/employeeicon.png" alt="Employees Icon" class="icon" />
        <span class="label">EMPLOYEES</span>
        <img src="/finals_integrated/components/arrow.png" alt="Arrow" class="submenu-arrow" />
    </div>

    <div class="submenu" id="employeesSubmenu">
        <a href="/finals_integrated/Employee/all employees.php" class="submenu-item">All Employees</a>
        <a href="/finals_integrated/Leaves/leave.php" class="submenu-item">Employee Leave</a>
    </div>
    
    <div class="sidebar-item">
        <a href="/finals_integrated/Position/position.php">
            <img src="/finals_integrated/components/position.png" alt="Position Icon" />
            <span class="label">POSITION</span>
        </a>
    </div>

    <div class="sidebar-item">
        <a href="/finals_integrated/schedule/schedule.php">
        <img src="/finals_integrated/components/schedule.png" alt="Schedule Icon" />
        <span class="label">SCHEDULE</span>
        </a>
    </div>

    <div class="sidebar-item sidebar-item-with-submenu" id="attendanceMenu">
        <img src="/finals_integrated/components/attendance.png" alt="Attendance Icon" class="icon" />
        <span class="label">ATTENDANCE</span>
        <img src="/finals_integrated/components/arrow.png" alt="Arrow" class="submenu-arrow" />
    </div>

    <div class="submenu" id="attendanceSubmenu">
        <a href="/finals_integrated/attendance/timesheet.php" class="submenu-item">Timesheet</a>
        <a href="/finals_integrated/attendance/overtime.php" class="submenu-item">Overtime</a>
    </div>

    <a href="/finals_integrated/Payslip/payslip.php" class="sidebar-item">
        <img src="/finals_integrated/components/payroll.png" alt="Payroll Icon" />
        <span class="label">PAYROLL</span>
    </a>

    <a href="/finals_integrated/Taxes/taxes.php" class="sidebar-item">
        <img src="/finals_integrated/components/accounts.png" alt="Account Icon" />
        <span class="label">ACCOUNTS</span>
    </a>

    <!-- Bottom items container -->
    <div class="sidebar-bottom-items">
        <div class="sidebar-item">
            <img src="" alt="Profile Icon" class="icon" />
            <span class="label"><?php echo $admin_name; ?></span>
        </div>

        <div class="sidebar-divider"></div>

        <a href="/finals_integrated/Login/login.php" class="sidebar-item" style="text-decoration: none;">
            <img src="/finals_integrated/components/power-on.png" alt="Logout Icon" class="icon" />
            <span class="label" style="color: red;">LOGOUT</span>
        </a>
    </div>
</div> 