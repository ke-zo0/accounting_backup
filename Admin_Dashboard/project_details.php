<?php
session_start();
if(!isset($_SESSION['admin_username'])) {
    header("Location: ../Login/login.php");
    exit();
}
$projectName = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Project';
// Example static employees
$employees = [
  ['name' => 'Juana Dela Cruz', 'position' => 'Frontend Developer'],
  ['name' => 'Fred Rich', 'position' => 'Backend Developer'],
  ['name' => 'Precious Batumbakal', 'position' => 'UI/UX Designer'],
];
include '../database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Project Details</title>
  <link rel="stylesheet" href="style1.css" />
  <link rel="stylesheet" href="/finals_integrated/components/sidebar.css" />
</head>
<body>
  <?php include '../components/sidebar.php'; ?>
  <div class="dashboard-container">
    <header class="dashboard-header">
      <h1><?php echo $projectName; ?></h1>
      <p>Project Details</p>
    </header>
    <section class="projects">
      <h3>Employees Working on this Project</h3>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Position</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($employees as $emp): ?>
          <tr>
            <td><?php echo $emp['name']; ?></td>
            <td><?php echo $emp['position']; ?></td>
            <td><button class="action-btn">Remove</button></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <button class="action-btn" style="margin-top:1rem;">Add Employee</button>
    </section>
  </div>
</body>
</html> 