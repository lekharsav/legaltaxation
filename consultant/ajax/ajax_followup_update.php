<?php
include("../db.php");
$id = $_POST['id'];
$exe = $_POST["exe"];
$cid = $_POST['cid'];
$status = $_POST['status'];
$next_f = $_POST['next_f'];
$remarks = $con->real_escape_string($_POST['remarks']);
$q = "update client set status='$status',next_followup='$next_f',remarks='$remarks' where id='$id'";
if($con->query($q) === true){
	$con->query("insert into followup_history(cid,executive,status,remarks,next_f,created) values('$cid','$exe','$status','$remarks','$next_f','$current_date')");
	echo 1;
}else{
	echo 0;
}
?>