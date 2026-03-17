<?php
include("../db.php");
$cid = $_POST['cid'];
$rcv_amt = $_POST['rcv_amt'];
$qid = $_POST['qid'];
$p_date = $_POST['p_date'];
$p_mode = $_POST['p_mode'];
$txn_id = $_POST['txn_id'];
$prod = $_POST['prod'];

$qry = $con->query("select * from client where id='$cid'");
if($rws = $qry->fetch_assoc()){
    $emp_id = $rws['uploaded_by'];
}

$total = $_POST['total_amt'];
$price = $_POST['price'];
$margin = $_POST['margin'];

if($rcv_amt>$margin){
    $margin_amt = $margin;
}else{
    $margin_amt = $rcv_amt;
}

$sql = $con->query("select * from client_order where qid='$qid'");
if($row = $sql->fetch_assoc()){
    $deal_amt = $row['total'];
}

$total_coll = 0;
$sql = $con->query("select * from client_txn where qid='$qid'");
while($row = $sql->fetch_assoc()){
    $total_coll += $row['amt'];
}
$outs = $deal_amt - $total_coll;
if($outs != 0 || $outs >= $rcv_amt){
    if($con->query("insert into client_txn(qid,emp_id,cid,product,amt,p_mode,txn_id,margin,p_date,created) values('$qid','$emp_id','$cid','$prod','$rcv_amt','$p_mode','$txn_id','$margin_amt','$p_date','$current_date')")){
        $data = array(
            "qid" => $qid,
            "bill_amt" => $total,
            "outs_amt" => $outs-$rcv_amt,
            "status" => 1
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }else{
        $data = array(
            "qid" => $qid,
            "bill_amt" => $total,
            "outs_amt" => $outs-$rcv_amt,
            "status" => 0
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}else{
    $data = array(
        "qid" => $qid,
        "bill_amt" => $total,
        "outs_amt" => $outs-$rcv_amt,
        "status" => 1
    );
    header('Content-Type: application/json');
    echo json_encode($data);
}


?>