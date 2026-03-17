<?php
include("../db.php");
// if(isset($_POST['submit'])){
	$client = $_POST['client'];
	$p_date = $_POST['p_date'];
	$p_no = $_POST['p_no'];
	$d_des = $con->real_escape_string($_POST['d_des']);
	$p_terms = $_POST['p_terms'];
	$gst_type = $_POST['gst_type'];
	$gst = $_POST['gst'];
	$if_anex = $_POST['if_anex'];
	$anex = $_POST['anex'];
	$q_no = $_POST['q_no'];

	$pid = implode(",",$_POST['pid']);

	$name = $_POST['name'];
	$hsn = $_POST['hsn'];
	$qty = $_POST["qty"];
	$price = $_POST['s_price'];
	$per = $_POST['per'];
	$dis = $_POST['dis'];
	$des = $_POST['des'];

	$con->query("update client set product='$pid' where id='$client'");

	if($con->query("insert into quote(quote_id,client,product,p_no,p_date,d_des,p_terms,gst_type,gst_no,if_anex,anex,created) values('$q_no','$client','$pid','$p_no','$p_date','$d_des','$p_terms','$gst_type','$gst','$if_anex','$anex','$current_date')") === true){

		
		$mi = new MultipleIterator();
		$mi->attachIterator(new ArrayIterator($_POST['pid']));
		$mi->attachIterator(new ArrayIterator($name));
		$mi->attachIterator(new ArrayIterator($hsn));
		$mi->attachIterator(new ArrayIterator($qty));
		$mi->attachIterator(new ArrayIterator($price));
		$mi->attachIterator(new ArrayIterator($per));
		$mi->attachIterator(new ArrayIterator($dis));
		$mi->attachIterator(new ArrayIterator($des));
		foreach($mi as $value){
			list($itemid,$item,$hsn,$qty,$price,$per,$dis,$des) = $value;
			$q = "insert into quote_item(pid,qid,cid,name,hsn,qty,price,per,dis,des) values('$itemid','$q_no','$client','$item','$hsn','$qty','$price','$per','$dis','$des')";
			$con->query($q);
		}
		echo $client;
	}else{
		echo"0";
	}
// }
?>