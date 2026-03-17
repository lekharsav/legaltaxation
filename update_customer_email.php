<?php
include('db.php');

if(isset($_POST['customer_id']) && isset($_POST['email'])) {
    $customer_id = intval($_POST['customer_id']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $verified = isset($_POST['verified']) ? intval($_POST['verified']) : 1;
    
    $sql = "UPDATE customer SET email = '$email', email_verified = '$verified' WHERE id = '$customer_id'";
    
    if($con->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $con->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}
?>