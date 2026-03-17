<?php
include("../db.php");
if(!isset($_POST['status'])){
	$status = 0;
}else{
	$status = $_POST['status'];
}
if($con->query("update project_task set report='".$_POST['report']."',status='".$status."' where id='".$_POST['id']."'") === true){
	echo 1;
}else{
	echo 0;
}
?>