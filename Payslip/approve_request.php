<?php
include('../database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Mark as approved
    $query = "UPDATE payslip_requests SET status = 'approved' WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo "Request approved.";
    } else {
        echo "Error approving request.";
    }
} else {
    echo "No ID provided.";
}
?>
