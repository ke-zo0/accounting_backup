<?php
session_start();
if(!isset($_SESSION['admin_username'])) {
    header("Location: ../Login/login.php");
    exit();
}

include '../database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="style1.css" />
  <link rel="stylesheet" href="/finals_integrated/components/sidebar.css" />
</head>
<body>
  <?php include '../components/sidebar.php'; ?>

  <div class="dashboard-container">
    <header class="dashboard-header">
      <h1>Welcome Admin!</h1>
      <p>Dashboard</p>
    </header>

    <div class="stats">
      <div class="stat-box"><h2>12</h2><p>EMPLOYEES</p></div>
      <div class="stat-box"><h2>3</h2><p>ON TIME</p></div>
      <div class="stat-box"><h2>3</h2><p>LATE</p></div>
      <div class="stat-box"><h2>2</h2><p>ABSENT</p></div>
    </div>

    <section class="projects">
      <h3>Recent Projects</h3>
      <table>
        <thead>
          <tr>
            <th>Project</th>
            <th>Progress</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="project-list">
          <!-- JavaScript will insert rows here -->
        </tbody>
      </table>
    </section>
  </div>

  <script src="script1.js"></script>
  <script src="/finals_integrated/components/script1-2.js"></script>
</body>
</html>
