<!DOCTYPE html>
<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "accounting management system");

if (isset($_GET['msg']) && $_GET['msg'] === 'password_changed') {
    echo "<script>alert('Password changed successfully. Please log in again.');</script>";
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check admin credentials
    $sql = "SELECT * FROM admin WHERE admin_username='$username' AND admin_pass='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin_username'] = $username;
        header("Location: /finals_integrated/Admin_Dashboard/admin_dashboard.php");
        exit();
    } else {
        // Check employee credentials (username = EmailAdd or Username, password = Employee_ID or Password)
        $sql_emp = "SELECT * FROM employees WHERE Username='$username' AND Password='$password'";
        $result_emp = mysqli_query($conn, $sql_emp);

        if (mysqli_num_rows($result_emp) > 0) {
            $row = mysqli_fetch_assoc($result_emp);
            $_SESSION['employee_username'] = $username;
            $_SESSION['employee_id'] = $row['Employee_ID']; // ✅ this is what submit_payslip_request.php uses
            header("Location: /finals_integrated/user_side/user dashboard/userdashboard.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <img src="486450f7-5524-4851-a7ea-64609947bc2c 1.png" alt="Logo" class="logo" />
      <h2>Login</h2>
      <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter username or email" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" />

        <label for="password">Password:</label>
        <div class="password-wrapper">
          <input type="password" id="password" name="password" placeholder="Enter password" required value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '' ?>" />
          <span id="togglePassword">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </span>
        </div>

        <button type="submit" name="login">LOGIN</button>
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
      </form>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    let isVisible = false;

    if (togglePassword) {
      togglePassword.addEventListener('click', function() {
        isVisible = !isVisible;
        passwordInput.type = isVisible ? 'text' : 'password';
        eyeIcon.innerHTML = isVisible
          ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.292M6.873 6.873A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.293 5.066M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />'
          : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
      });
    }
  });
  </script>
</body>
</html>
