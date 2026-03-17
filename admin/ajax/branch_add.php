<?php
include("../db.php");
if(isset($_POST['submit'])){
	$pref = strtoupper(substr($_POST['name'],0,3));
	$sql = $con->query("select * from branch order by id desc");
	if($row = $sql->fetch_assoc()){
		$b = substr($row['bid'],8);
		$bid = $pref."BRCH-".str_pad( $b+1, 4, "0", STR_PAD_LEFT );
	}else{
		$bid = $pref."BRCH-"."0001";
	}
	if($con->query("insert into branch(bid,name,email,cont,location,adrs,created) values(
		'$bid',
		'".$_POST['name']."',
		'".$_POST['email']."',
		'".$_POST['cont']."',
		'".$_POST['loca']."',
		'".$_POST['adrs']."',
		'$current_date'
	)") === true){
		// echo"<script>window.location='dashboard.php?src=branch.php';</script>";
		echo 1;
	}else{
		echo 0;
	}
}

if(isset($_POST['save'])){
	$id = $_POST['id'];
	if($con->query("update branch set
		name='".$_POST['name']."',
		email='".$_POST['email']."',
		cont='".$_POST['cont']."',
		location='".$_POST['loca']."',
		adrs='".$_POST['adrs']."' where id='$id'") === true){
		// echo"<script>window.location='dashboard.php?src=branch.php';</script>";
		echo 1;
	}
}

if(isset($_GET['delete'])){
	$id = $_GET['id'];
	if($con->query("delete from branch where id='$id'") === true){
		// echo"<script>window.location='dashboard.php?src=branch.php';</script>";
		echo 1;
	}
}

?>