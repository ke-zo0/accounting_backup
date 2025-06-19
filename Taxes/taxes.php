<?php
session_start();
include '../components/sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Taxes</title>
  <link rel="stylesheet" href="style6.css">
  <link rel="stylesheet" href="/finals_integrated/components/sidebar.css">
</head>
<body>
  
  <div class="main-content">
    <header class="header">
      <div class="breadcrumbs">
        <h1>LIST OF TAXES</h1>
        <p>Dashboard / Accounts</p>
      </div>
    </header>
    <section class="table-section">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Tax Name</th>
            <th>Tax Percentage(%)</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Social Security System</td>
            <td>4.5%</td>
            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label> Active</td>
          </tr>
          <tr>
            <td>2</td>
            <td>PhilHealth</td>
            <td>2.5%</td>
            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label> Active</td>
          </tr>
          <tr>
            <td>3</td>
            <td>Pag-IBIG (HDMF)</td>
            <td>2% (up to ₱100 max)</td>
            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label> Active</td>
          </tr>
          <tr>
            <td>4</td>
            <td>Withholding Tax</td>
            <td>Varies (0% to 35%) depending on</td>
            <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label> Active</td>
          </tr>
        </tbody>
      </table>
    </section>
  </div>
  <script src="scipt6.js"></script>
  <script src="/finals_integrated/components/script1-2.js"></script>
</body>
</html>
