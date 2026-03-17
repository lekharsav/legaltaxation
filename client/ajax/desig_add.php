<?php
include("../db.php");
if(isset($_POST['submit'])){
	
	if($con->query("insert into designation(name,type,project) values(
		'".$_POST['name']."','".$_POST['type']."','".$_POST['project']."'
	)") === true){
		// echo"<script>window.location='dashboard.php?src=department.php';</script>";
		echo 1;
	}
}
if(isset($_POST['save'])){
	$id = $_POST['id'];
	if($con->query("update designation set name='".$_POST['name']."',type='".$_POST['type']."',project='".$_POST['project']."' where id='$id'") === true){
		// echo"<script>window.location='dashboard.php?src=department.php';</script>";
		echo 1;
	}
}
if(isset($_GET['delete'])){
	$id = $_GET['id'];
	if($con->query("delete from designation where id='$id'") === true){
		// echo"<script>window.location='dashboard.php?src=department.php';</script>";
		echo 1;
	}
}
?>
