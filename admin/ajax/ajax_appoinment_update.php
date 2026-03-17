<?php
include("../db.php");
$id = $_POST['id'];
$cid = $_POST['cid'];
$appointment_date = $_POST['appointment_date'];
$appointment_time = $_POST['appointment_time'];
$remarks = $con->real_escape_string($_POST['remarks']);
$eid = $_POST['eid'];
$location = $_POST['location'];
if($con->query("insert into appointment_details(cid,executive,appointment_date,appointment_time,clint_id,remarks,location)
 values('$cid','$eid','$appointment_date','$appointment_time','$id','$remarks','$location')")){
	
	echo 1; 
}else{
	echo 0;
}
?>  