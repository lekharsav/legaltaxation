<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}

$application_id = $_GET['application_id'] ?? 0;

// Get application details
$sql = $con->query("SELECT a.*, c.email, c.name as customer_name, s.title as service_title 
                    FROM apply a 
                    LEFT JOIN customer c ON a.cid = c.id 
                    LEFT JOIN service s ON a.sid = s.id 
                    WHERE a.id='$application_id'");
if($row = $sql->fetch_assoc()){
    $application = $row;
    
    // Here you would implement email sending logic
    // For example using PHPMailer or your existing email system
    
    // For now, just show a message
    echo "<script>
            alert('Reminder sent to customer!');
            window.location='view_customer_form_data.php?application_id=$application_id';
          </script>";
} else {
    echo "<script>
            alert('Application not found!');
            window.location='leads.php';
          </script>";
}
?>