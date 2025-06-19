<?php
// approve_letter.php
include_once '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_id'])) {
    $leave_id = intval($_POST['leave_id']);
    // Update status to Approved
    $sql = "UPDATE leave_applications SET status='Approved' WHERE Leaves_ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $leave_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        header("Location: leave.php");
        exit();
    } else {
        $error = 'Failed to update leave application.';
    }
}

$leave_id = isset($_GET['leave_id']) ? intval($_GET['leave_id']) : (isset($_POST['leave_id']) ? intval($_POST['leave_id']) : 0);
if (!$leave_id) {
    die('No leave ID provided.');
}

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
$leave_type = strtolower($leave['leaveType']);
$status = strtolower($leave['status']);
$logo_path = file_exists('486450f7-5524-4851-a7ea-64609947bc2c 1.png') ? '486450f7-5524-4851-a7ea-64609947bc2c 1.png' : '../Leaves/486450f7-5524-4851-a7ea-64609947bc2c 1.png';

// Approval letter template
$fixed_letter = "Dear $employee_name,\n\nWe are pleased to inform you that your request for {$leave['leaveType']} from $start_date to $end_date has been approved.\n\nSincerely,\n$company_name";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approval Letter</title>
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
        .letter-footer { margin-top: 2rem; font-size: 1.05em; color: #444; }
        .success { color: green; margin-bottom: 1em; }
        .error { color: red; margin-bottom: 1em; }
    </style>
</head>
<body>
<div class="letter-container">
    <div class="company-header">
        <img src="<?php echo $logo_path; ?>" alt="Logo" class="company-logo">
        <span class="company-name"><?php echo htmlspecialchars($company_name); ?></span>
    </div>
    <h1>Approval Letter</h1>
    <div class="letter-meta">
        <b>Date:</b> <?php echo $today; ?><br>
        <b>To:</b> <?php echo htmlspecialchars($employee_name); ?><br>
        <b>Position:</b> <?php echo htmlspecialchars($employee_position); ?><br>
        <b>Email:</b> <?php echo htmlspecialchars($employee_email); ?><br>
        <b>Leave Type:</b> <?php echo htmlspecialchars($leave['leaveType']); ?><br>
        <b>From:</b> <?php echo $start_date; ?><br>
        <b>To:</b> <?php echo $end_date; ?><br>
    </div>
    <?php if (isset($success) && $success): ?>
        <div class="success">Leave application approved!</div>
    <?php elseif (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($status === 'pending'): ?>
    <form method="POST">
        <input type="hidden" name="leave_id" value="<?php echo $leave_id; ?>">
        <div class="letter-body">
            <div style="white-space: pre-line; border: 1px solid #bbb; border-radius: 8px; padding: 1em; background: #f9f9f9; margin-bottom: 1em;">
                <?php echo nl2br(htmlspecialchars($fixed_letter)); ?>
            </div>
        </div>
        <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Approve Leave</button>
    </form>
    <?php else: ?>
    <div class="letter-body">
        <div style="white-space: pre-line; border: 1px solid #bbb; border-radius: 8px; padding: 1em; background: #f9f9f9; margin-bottom: 1em;">
            <?php echo nl2br(htmlspecialchars($fixed_letter)); ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="letter-footer">
        Sincerely,<br><br>
        <?php echo htmlspecialchars($company_name); ?>
    </div>
</div>
</body>
</html> 