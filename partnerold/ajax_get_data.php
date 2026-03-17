<?php
include("../db.php");
$cate = $_GET["cate"];
$service = $_GET['sid'];
$partner = $_COOKIE["tax_partner_log"];

$sql = $con->query("select * from plan_buy where partner='$partner' and cate='$cate'");
if($row = $sql->fetch_assoc()){
    if($row['expired'] < date('Y-m-d',strtotime($current_date))){
        echo 1;
    }else{
        echo 0;
        
    }
}else{
    echo 2;
}
?>