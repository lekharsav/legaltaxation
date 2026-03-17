<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $application_id = $_POST['application_id'];
    $admin_notes = mysqli_real_escape_string($con, $_POST['admin_notes']);
    
    if(!empty($admin_notes)){
        $sql = "INSERT INTO application_notes (application_id, notes, created_by) 
                VALUES ('$application_id', '$admin_notes', '$aid')";
        
        if($con->query($sql)){
            echo "<script>
                    alert('Notes saved successfully!');
                    window.location='view_customer_form_data.php?application_id=$application_id';
                  </script>";
        }
    }
}
?>