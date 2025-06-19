<?php
include('../database.php');

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $query = "SELECT pr.requested_at, e.Fname, e.Lname, e.employee_id, e.position_id, e.datehired, p.title AS PositionName, p.RatePerHour
            FROM payslip_requests pr
            JOIN employees e ON pr.employee_id = e.employee_id
            JOIN positions p ON e.position_id = p.position_id
            WHERE pr.id = '$id'";

  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($result);

  $emp_id = $data['employee_id'];
  $rate_raw = $data['RatePerHour'];
  $rate = preg_replace('/[^0-9.]/', '', $rate_raw); // Remove ₱ or other symbols

  // ✅ Fetch total PRESENT days from attendance
  $att_query = "SELECT COUNT(*) AS total_days FROM attendance WHERE employee_ID = '$emp_id'";
  $att_result = mysqli_query($conn, $att_query);
  $att_row = mysqli_fetch_assoc($att_result);
  $total_days = $att_row['total_days'] ?? 0;

  // Assume 8 hours per day
  $present_hours = $total_days * 8;
  $basic_salary = bcmul($present_hours, $rate, 2);

  // ✅ Fetch total overtime hours
  $ot_query = "SELECT SUM(NumOf_HRS) AS total_ot FROM overtime WHERE employee_ID = '$emp_id'";
  $ot_result = mysqli_query($conn, $ot_query);
  $ot_row = mysqli_fetch_assoc($ot_result);
  $ot_hours = $ot_row['total_ot'] ?? 0;
  $overtime = bcmul($ot_hours, $rate, 2);

  // 💸 Sample Add-ons and Deductions
  $allowances = 1000;
  $bonus = 0;
  $holiday = 0;
  $gross = $basic_salary + $allowances + $bonus + $overtime + $holiday;
  $withholding = 33.40;
  $contributions = 1517.50;
  $absent = 0;
  $late = 0;
  $others = 700;
  $deductions = $withholding + $contributions + $absent + $late + $others;
  $net = $gross - $deductions;

  echo "
  <div class='payslip-template-container'>
    <div class='payslip-template'>
      <div class='payslip-header'>
        <div class='payslip-logo-block'>
          <img src='/finals_integrated/Payslip/486450f7-5524-4851-a7ea-64609947bc2c 1.png' alt='Logo' class='payslip-logo' />
          <div class='payslip-company'>
            <b>Tekinologia Corp.</b><br>
            1234 Orange Building, 2nd floor, Buhay na Tubig, Imus City, Cavite.
          </div>
        </div>
        <div class='payslip-meta-block'>
          <div class='payslip-title'>Payslip for the month of</div>
          <div class='payslip-number'>
            <b>PAYSLIP #</b><br>
            <span style='font-size: 0.9em; color: #888;'>Requested at: {$data['requested_at']}</span>
          </div>
        </div>
      </div>
      <div class='payslip-employee-block'>
        <div>
          <b>Name of employee</b><br>
          {$data['Fname']} {$data['Lname']}<br>
          {$data['PositionName']}<br>
          {$data['employee_id']}<br>
          {$data['datehired']}
        </div>
      </div>
      <div class='payslip-main'>
        <div class='payslip-earnings'>
          <b>Earnings</b>
          <div class='payslip-table'>
            <div class='row'><span>Basic salary</span><span>&#8369;" . number_format($basic_salary, 2) . "</span></div>
            <div class='row'><span>Allowances</span><span>&#8369;" . number_format($allowances, 2) . "</span></div>
            <div class='row'><span>Bonus</span><span>&#8369;" . number_format($bonus, 2) . "</span></div>
            <div class='row'><span>Overtime pay</span><span>&#8369;" . number_format($overtime, 2) . "</span></div>
            <div class='row'><span>Holiday pay</span><span>&#8369;" . number_format($holiday, 2) . "</span></div>
            <div class='row'><span>Gross pay</span><span>&#8369;" . number_format($gross, 2) . "</span></div>
          </div>
          <div class='payslip-net'>NET SALARY: <b>&#8369;" . number_format($net, 2) . "</b></div>
        </div>
        <div class='payslip-deductions'>
          <b>Deductions</b>
          <div class='payslip-table'>
            <div class='row'><span>Withholding TAX</span><span>&#8369;" . number_format($withholding, 2) . "</span></div>
            <div class='row'><span>SSS, PhilHealth, Pag-IBIG<br>contributions</span><span>&#8369;" . number_format($contributions, 2) . "</span></div>
            <div class='row'><span>Absent deduction</span><span>&#8369;" . number_format($absent, 2) . "</span></div>
            <div class='row'><span>Late deduction</span><span>&#8369;" . number_format($late, 2) . "</span></div>
            <div class='row'><span>Others</span><span>&#8369;" . number_format($others, 2) . "</span></div>
            <div class='row'><span>Total Deductions</span><span>&#8369;" . number_format($deductions, 2) . "</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>";
}
?>
