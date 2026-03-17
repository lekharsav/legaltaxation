<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $application_id = $_POST['application_id'];
    $ca_id = $_POST['ca_id'];
    $assignment_notes = mysqli_real_escape_string($con, $_POST['assignment_notes']);
    
    // Update application
    $sql = "UPDATE apply SET send_to='$ca_id' WHERE id='$application_id'";
    
    if($con->query($sql)){
        // Save assignment notes if provided
        if(!empty($assignment_notes)){
            $notes_sql = "INSERT INTO application_notes (application_id, notes, created_by) 
                          VALUES ('$application_id', '$assignment_notes', '$aid')";
            $con->query($notes_sql);
        }
        
        echo "<script>
                alert('Application assigned to CA successfully!');
                window.location='view_customer_form_data.php?application_id=$application_id';
              </script>";
    } else {
        echo "<script>
                alert('Error assigning application!');
                window.location='view_customer_form_data.php?application_id=$application_id';
              </script>";
    }
}
?>