<?php
include('../database.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Mark as rejected
    $query = "UPDATE payslip_requests SET status = 'rejected' WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo "Request rejected.";
    } else {
        echo "Error rejecting request.";
    }
} else {
    echo "No ID provided.";
}
?>
