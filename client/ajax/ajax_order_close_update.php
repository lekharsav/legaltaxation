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
    $emp_mail = $rws['email'];
    $comp = $rws['company'];
    $branch = $rws['branch'];
}

$total = $_POST['total_amt'];
$price = $_POST['price'];
$margin = $_POST['margin'];

if($rcv_amt>$margin){
    $margin_amt = $margin;
}else{
    $margin_amt = $rcv_amt;
}

// *** Stock Update *** //

$sql = $con->query("select * from quote_item where qid='$qid'");
while($row = $sql->fetch_assoc()){
    $order_qty = $row['qty'];
    $sql1 = $con->query("select * from stock_manage where product='".$row['pid']."'");
    if($row1 = $sql1->fetch_assoc()){
        $stock_qty = $row1['qty']-$order_qty;
        $con->query("update stock_manage set qty='$stock_qty' where product='".$row['pid']."'");
    }
}

//*** End ***//

$sql = $con->query("select * from client_order  where qid='$qid'");
if($sql->fetch_assoc()){

}else{
    if($con->query("insert into client_order(branch,qid,emp_id,cid,product,price,total,margin,status,order_date,created) values('$branch','$qid','$emp_id','$cid','$prod','$price','$total','$margin','1','$p_date','$current_date')") === true){
        $total_coll = 0;
        $sql = $con->query("select * from client_txn where qid='$qid'");
        while($row = $sql->fetch_assoc()){
            $total_coll += $row['amt'];
        }
        $outs = $total - $total_coll;
        if($outs != 0 || $outs >= $rcv_amt){
            $con->query("insert into client_txn(qid,emp_id,cid,product,amt,p_mode,txn_id,margin,p_date,created) values('$qid','$emp_id','$cid','$prod','$rcv_amt','$p_mode','$txn_id','$margin_amt','$p_date','$current_date')");
        }
        
        $con->query("update quote set status='1' where quote_id='$qid'");
        $con->query("update client set status='4' where id='$cid'");
        $p = explode(",",$prod);
        $pr_id = rand(1000,9999);
        foreach($p as $pid){
            $sql = $con->query("select * from product where id='$pid'");
            if($row = $sql->fetch_assoc()){
                if($row['category']>=1){
                    $con->query("insert into project(pid,cid,qid,project_name,product,manager,tl,executive,start_date,end_date,status,created) values('$pr_id','$cid','$qid','$comp','$pid','','','','','','0','$current_date')");
                }
            }
        }
        $data = array(
            "qid" => $qid,
            "bill_amt" => $total,
            "outs_amt" => $total-$rcv_amt,
            "status" => 1
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        // echo $qid;
    }else{
        $data = array(
            "qid" => $qid,
            "bill_amt" => $total,
            "outs_amt" => $total-$rcv_amt,
            "status" => 0
        );
    }
}

?>