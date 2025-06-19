<?php
// leave details.php
if (!isset($_GET['leave_id'])) {
    die('No leave ID provided.');
}

include_once '../database.php';
$leave_id = intval($_GET['leave_id']);

// Fetch leave application and employee info
$sql = "SELECT la.*, e.Fname, e.middlename, e.Lname, e.EmailAdd, p.Title AS Position
        FROM leave_applications la
        JOIN employees e ON la.Employee_ID = e.Employee_ID
        LEFT JOIN positions p ON e.Position_ID = p.Position_ID
        WHERE la.Leaves_ID = $leave_id";
$result = $conn->query($sql);
if (!$result || $result->num_rows === 0) {
    die('No leave found for this ID.');
}
$leave = $result->fetch_assoc();
$employee_name = trim($leave['Fname'] . ' ' . ($leave['middlename'] ? $leave['middlename'] . ' ' : '') . $leave['Lname']);
$employee_email = $leave['EmailAdd'];
$employee_position = $leave['Position'];
$start_date = date('F j, Y', strtotime($leave['start_date']));
$end_date = date('F j, Y', strtotime($leave['end_date']));
$today = date('F j, Y');
$company_name = 'Tekinologia Corp.'; // Change as needed
$logo_path = file_exists('486450f7-5524-4851-a7ea-64609947bc2c 1.png') ? '486450f7-5524-4851-a7ea-64609947bc2c 1.png' : '../Leaves/486450f7-5524-4851-a7ea-64609947bc2c 1.png';
$reason = $leave['reason'];
$attachment = $leave['attachment'];
$status = $leave['status'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave Application Details</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .letter-container { background: #fff; max-width: 700px; margin: 2rem auto; padding: 2.5rem 2.5rem 2rem 2.5rem; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .company-header { display: flex; align-items: center; gap: 1.2rem; margin-bottom: 1.5rem; }
        .company-logo { width: 70px; height: 70px; object-fit: contain; border-radius: 8px; background: #fff; border: 1px solid #eee; }
        .company-name { font-size: 1.5rem; font-weight: bold; color: #122f5e; }
        h1 { text-align: center; font-size: 2rem; margin-bottom: 2rem; color: #222; }
        .letter-meta { margin-bottom: 1.5rem; }
        .letter-meta b { display: inline-block; min-width: 120px; }
        .letter-body { margin-bottom: 1.5rem; }
        .letter-body p { margin: 0 0 1.1em 0; }
        .letter-footer { margin-top: 2rem; font-size: 1.05em; color: #444; }
        .attachment-link { margin-top: 1em; display: block; }
    </style>
</head>
<body>
<div class="letter-container">
    <div class="company-header">
        <img src="<?php echo $logo_path; ?>" alt="Logo" class="company-logo">
        <span class="company-name"><?php echo htmlspecialchars($company_name); ?></span>
    </div>
    <h1>Leave Application Details</h1>
    <div class="letter-meta">
        <b>Date:</b> <?php echo $today; ?><br>
        <b>Employee:</b> <?php echo htmlspecialchars($employee_name); ?><br>
        <b>Position:</b> <?php echo htmlspecialchars($employee_position); ?><br>
        <b>Email:</b> <?php echo htmlspecialchars($employee_email); ?><br>
        <b>Type of Leave:</b> <?php echo htmlspecialchars($leave['leaveType']); ?><br>
        <b>From:</b> <?php echo $start_date; ?><br>
        <b>To:</b> <?php echo $end_date; ?><br>
        <b>Status:</b> <?php echo htmlspecialchars($status); ?>
    </div>
    <div class="letter-body">
        <p><b>Additional Information:</b><br><?php echo nl2br(htmlspecialchars($reason)); ?></p>
        <?php if ($attachment): ?>
            <a href="/finals_integrated/<?php echo htmlspecialchars($attachment); ?>" target="_blank">View Attachment</a>
        <?php endif; ?>
    </div>
    <div class="letter-footer">
        <b>Application Submitted:</b> <?php echo htmlspecialchars($leave['date_applied']); ?><br>
    </div>
</div>
</body>
</html> 