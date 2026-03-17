<?php 
include('../db.php');

if(isset($_POST['submit'])){
	if($con->query("insert into department(name) values(
		'".$_POST['name']."'
	)") === true){
		echo 1;
	}
}
if(isset($_POST['save'])){
	$id = $_POST['id'];
	if($con->query("update department set name='".$_POST['name']."' where id='$id'") === true){
		echo"<script>window.location='dashboard.php?src=department.php';</script>";
	}
}
if(isset($_GET['delete'])){
	$id = $_GET['id'];
	if($con->query("delete from department where id='$id'") === true){
		echo"<script>window.location='dashboard.php?src=department.php';</script>";
	}
}
?>