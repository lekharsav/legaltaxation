<?php
session_start();
include("../db.php");

// Check if CA is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Check if this application belongs to this CA
if(isset($_POST['application_id']) && isset($_POST['new_status'])){
    $application_id = $_POST['application_id'];
    $new_status = $_POST['new_status'];
    
    // Verify ownership
    $check_sql = $con->query("SELECT * FROM apply WHERE id='$application_id' AND send_to='$ca_id'");
    if($check_sql->num_rows == 0){
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit();
    }
    
    // Update status
    if($con->query("UPDATE apply SET status='$new_status' WHERE id='$application_id'")){
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>