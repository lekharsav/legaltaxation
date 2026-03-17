<?php
include("../db.php");
$partner = $_POST['partner'];
$amt = $_POST['amt'];
$txn_id = $_POST['txn_id'];
$status = $_POST['status'];

$wallet = 0;
$sql = $con->query("select * from partner where id='$partner'");
if($row = $sql->fetch_assoc()){
	$wallet = $row['wallet'];
}

$sql = $con->query("select * from recharge where txn_id='$txn_id'");
if($row = $sql->fetch_assoc()){
	if($con->query("update recharge set amount='$amt',status='$status' where txn_id='$txn_id'") === true){
		$wt = $wallet+$amt;
		if($con->query("update partner set wallet='$wt' where id='$partner'") === true){
			echo "Success";
		}else{
			echo "Failed";
		}
	}
	
	
}else{
	$con->query("insert into recharge(partner,amount,txn_id,created,status) values('$partner','$amt','$txn_id','$current_date','$status')");
}
?>