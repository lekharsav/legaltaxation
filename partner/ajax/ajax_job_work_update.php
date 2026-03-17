<?php
include("../db.php");
/*
$files = implode(",",$_FILES['file']['name']);
$file = $_FILES['file']['tmp_name'];
$fname = $_FILES['file']['name'];
*/
if($con->query("insert job_work(qid,s_date,e_date,schedule,daily_budget,cid,ticket_id,product,job_type,p_mode,txn_id,amount,title,file,brief,created) values(
'".$_POST['qid']."',
'".$_POST['s_date']."',
'".$_POST['e_date']."',
'".$_POST['schedule']."',
'".$_POST['daily_budget']."',
'".$_POST['cid']."',
'".$_POST['ticket']."',
'".$_POST['product']."',
'".$_POST['job_type']."',
'".$_POST['p_mode']."',
'".$_POST['txn_id']."',
'".$_POST['amount']."',
'".$_POST['title']."',
'$files',
'".$_POST['brief']."',
'$current_date')") === true){
	/*
	$mi = new MultipleIterator();
	$mi->attachIterator(new ArrayIterator($file));
	$mi->attachIterator(new ArrayIterator($fname));
	foreach($mi as $value){
		list($file,$fname) = $value;
		move_uploaded_file($file,"../doc/".$fname);
	}
	*/
	echo $_POST['qid'];
}else{
	echo"0";
}
?>