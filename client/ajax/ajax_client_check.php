<?php
include("../db.php");
if(isset($_GET['cont'])){
	$cont = $_GET['cont'];
	$sql = $con->query("select * from client where contact='$cont'");
	if($sql->fetch_assoc()){
		echo"Mobile Number Already Exists.";
	}
}
if(isset($_GET['email'])){
	$email = $_GET['email'];
	$sql = $con->query("select * from client where email='$email'");
	if($sql->fetch_assoc()){
		echo"Email Already Exists.";
	}
}
?>