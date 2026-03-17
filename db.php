<?php
date_default_timezone_set('Asia/Kolkata');
$current_date = date('d-m-Y h:i a');
$con = mysqli_connect('localhost','root','','u681343650_legaltaxation');
if(!$con){
	echo"Not Connected";
}
$uid = @$_COOKIE['tax_customer_log'];
if($uid){
	$qry = $con->query("select * from customer where id='$uid'");
	if($rw = $qry->fetch_assoc()){
		$customer_name = $rw['name'];
		$customer_cont = $rw['contact'];
		$customer_email = $rw['email'];
		$customer_pass = $rw['password'];
	}

}
?>