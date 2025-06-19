<?php
session_start();
include '../components/sidebar.php';
include('../database.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Payslip Requests</title>
  <link rel="stylesheet" href="payslip.css">
  <link rel="stylesheet" href="/finals_integrated/components/sidebar.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h1>PAYSLIP</h1>
      <p>Dashboard / Payslip</p>
      <div class="tabs-bar">
        <nav class="tabs">
          <span id="tab-requests" class="active">Payslip Requests</span>
          <span id="tab-template">Payslip Template</span>
        </nav>
        <button class="generate-btn" id="generateAllBtn">Generate Payslips for All Employees</button>
      </div>
    </header>

    <!-- Payslip Requests Section -->
    <div id="requests-section">
      <div class="table-controls">
        <label>Show
          <select>
            <option>5</option>
            <option>10</option>
          </select>
          entries
        </label>
      </div>
      <table>
        <thead>
          <tr>
            <th>Employee</th>
            <th>Date Requested</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="payslipTable">
          <?php
          $query = "SELECT pr.id, pr.requested_at, pr.employee_id, CONCAT(e.Fname, ' ', e.Lname) AS name
                    FROM payslip_requests pr
                    JOIN employees e ON e.employee_id = pr.employee_id
                    WHERE pr.status = 'pending'
                    ORDER BY pr.requested_at DESC";

          $result = mysqli_query($conn, $query);

          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td>{$row['name']}</td>
                      <td>{$row['requested_at']}</td>
                      <td>
                        <button class='action-btn approve' data-id='{$row['id']}'>✓</button>
                        <button class='action-btn reject' data-id='{$row['id']}'>✗</button>
                        <button class='action-btn view' data-id='{$row['id']}'>👁️</button>
                      </td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='3'>No pending payslip requests.</td></tr>";
          }
          ?>
        </tbody>
      </table>
      <footer class="pagination">
        <span>Showing recent entries</span>
        <div class="page-controls">
          <button disabled>Previous</button>
          <button class="active">1</button>
          <button disabled>Next</button>
        </div>
      </footer>
    </div>

    <!-- Template Viewer Section -->
    <div id="template-section" style="display:none;">
      <div class="payslip-template-container">
        <div class="payslip-template">
          <div class="payslip-header">
            <div class="payslip-logo-block">
              <img src="/finals_integrated/Payslip/486450f7-5524-4851-a7ea-64609947bc2c 1.png" alt="Logo" class="payslip-logo" />
              <div class="payslip-company">
                <b>Tekinologia Corp.</b><br>
                1234 Orange Building, 2nd floor, Buhay na tubig, Imus City, Cavite.
              </div>
            </div>
            <div class="payslip-meta-block">
              <div class="payslip-title">Payslip for the month of</div>
              <div class="payslip-number">
                <b>PAYSLIP #</b><br>
                <span style="font-size: 0.9em; color: #888;">Salary Month:</span>
              </div>
            </div>
          </div>
          <div class="payslip-employee-block">
            <div>
              <b>Name of employee</b><br>
              Position<br>
              Employee ID<br>
              Joining Date
            </div>
          </div>
          <div class="payslip-main">
            <div class="payslip-earnings">
              <b>Earnings</b>
              <div class="payslip-table">
                <div class="row"><span>Basic salary</span><span>&#8369;21,000/month</span></div>
                <div class="row"><span>Allowances</span><span>&#8369;2,000/month</span></div>
                <div class="row"><span>Bonus</span><span>&#8369;0</span></div>
                <div class="row"><span>Overtime pay</span><span>&#8369;0</span></div>
                <div class="row"><span>Holiday pay</span><span>&#8369;0</span></div>
                <div class="row"><span>Gross pay</span><span>&#8369;0</span></div>
              </div>
              <div class="payslip-net">NET SALARY: <b>&#8369;0</b></div>
            </div>
            <div class="payslip-deductions">
              <b>Deductions</b>
              <div class="payslip-table">
                <div class="row"><span>Withholding TAX</span><span>&#8369;33.40</span></div>
                <div class="row"><span>SSS, PhilHealth, Pag-IBIG<br>contributions</span><span>&#8369;1,517.50</span></div>
                <div class="row"><span>Absent deduction</span><span>&#8369;0</span></div>
                <div class="row"><span>Late deduction</span><span>&#8369;0</span></div>
                <div class="row"><span>Others</span><span>&#8369;700</span></div>
                <div class="row"><span>Total Deductions</span><span>&#8369;0</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
    const tabRequests = document.getElementById('tab-requests');
    const tabTemplate = document.getElementById('tab-template');
    const requestsSection = document.getElementById('requests-section');
    const templateSection = document.getElementById('template-section');
    const generateBtn = document.getElementById('generateAllBtn');

    tabRequests.onclick = function () {
      tabRequests.classList.add('active');
      tabTemplate.classList.remove('active');
      requestsSection.style.display = '';
      templateSection.style.display = 'none';
      generateBtn.style.display = '';
    };

    tabTemplate.onclick = function () {
      tabTemplate.classList.add('active');
      tabRequests.classList.remove('active');
      requestsSection.style.display = 'none';
      templateSection.style.display = '';
      generateBtn.style.display = '';
    };

    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.approve').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.dataset.id;
          fetch(`approve_request.php?id=${id}`)
            .then(res => res.text())
            .then(response => {
              alert(response);
              location.reload();
            });
        });
      });

      document.querySelectorAll('.reject').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.dataset.id;
          fetch(`reject_request.php?id=${id}`)
            .then(res => res.text())
            .then(response => {
              alert(response);
              location.reload();
            });
        });
      });

      document.querySelectorAll('.view').forEach(btn => {
        btn.addEventListener('click', function () {
          const id = this.dataset.id;
          fetch(`view_request.php?id=${id}`)
            .then(res => res.text())
            .then(html => {
              document.getElementById('template-section').innerHTML = html;
              tabTemplate.click();
            });
        });
      });
    });

    generateBtn.onclick = function () {
      alert('Payslips for all employees will be generated (to be implemented).');
    };
  </script>
</body>
</html>
