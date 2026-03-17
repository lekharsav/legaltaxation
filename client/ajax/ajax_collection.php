<?php
include("../db.php");
$cid = $_GET['cid'];
$qid = $_GET['qid'];
$qry = $con->query("select * from client where id='$cid'");
$rws = $qry->fetch_assoc();

?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Client ID</th>
				<th width=300>Company Name</th>
				<th>Quotation ID</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><a href="dashboard.php?src=client_details.php&id=<?php echo $rws['id'] ?>"><?php echo $rws['cid'] ?></a></td>
				<td><?php echo $rws['company'] ?></td>
				<td><?=$qid?></td>
			</tr>
			<tr>
				<td colspan="5">
					<?php
					$p = explode(",",$rws['product']);
					foreach($p as $pid){
						$sql2 = $con->query("select * from product where id='$pid'");
						if($row2 = $sql2->fetch_assoc()){
							echo"<span class='badge bg-success mt-1'>".$row2['name']."</span>&nbsp;";
						}
					}
					?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="row container-fluid">
    <?php
    $inp = 1;
    $total_price = 0;
    $total_margin = 0;
    $sql = $con->query("select * from quote_item where qid='$qid'");
    while($row = $sql->fetch_assoc()){
        
        //*** Stock Calculation ***//
        $p_rate = "0";
        $stock_qty = "0";
        $num_of_slot = 0;
        $sql1 = $con->query("select * from stock where product='".$row['pid']."'");
        while($row1 = $sql1->fetch_assoc()){
            $num_of_slot++;
        }
        // $sql1 = $con->query("select * from stock where product='".$row['pid']."'");
        // if($row1 = $sql1->fetch_assoc()){
        //     $p_rate = $row1['rate'];
        //     $stock_qty = $row1['qty'];
        // }

        $sql1 = $con->query("select * from stock_manage where product='".$row['pid']."'");
        if($row1 = $sql1->fetch_assoc()){
            $p_rate = $row1['avg_price']*$row['qty'];
            $stock_qty = $row1['qty'];
        }
        // *** End *** //
        
        if($stock_qty > 0){
            $msg_val = 1;
            $msg = "Available Stock: ".$stock_qty;
        }else{
            $inp = 0;
            $msg_val = 0;
            $msg = "Out of stock";
        }
        $act_price = round($row['price']-($row['price']*$row['dis']/100))*$row['qty'];
        $margin = $act_price-$p_rate;
        
        $total_price += $act_price;
        $total_margin += $margin;
    ?>
    <div class="col-lg-12 mb-2">
        <div class="row">
            <div class="col-lg-4">
                <label>Product</label>
                <input type="text" value="<?=$row['name']?>" class="form-control" readonly>
            </div>
            <div class="col-lg-1">
                <label>Price</label>
                <input type="text" value="<?=$row['price']?>" class="form-control" readonly>
            </div>
            <div class="col-lg-1">
                <label>Qty</label>
                <input type="text" value="<?=$row['qty']?>" class="form-control" readonly>
            </div>
            <div class="col-lg-1">
                <label>Dis(%)</label>
                <input type="text" value="<?=$row['dis']?>" class="form-control" readonly>
            </div>
            <div class="col-lg-2">
                <label>Act. Price</label>
                <input type="text" value="<?=$act_price?>" class="form-control" readonly>
            </div>
            <div class="col-lg-1">
                <label>P. Rate</label>
                <input type="text" value="<?=$p_rate?>" class="form-control" readonly>
            </div>
            <div class="col-lg-2">
                <label>Margin</label>
                <input type="text" value="<?=$margin?>" class="form-control" readonly>
            </div>
            <div class="col-lg-12">
                <br>
                <?php
                /*
                if($msg_val > 0){
                    ?>
                    <span class="text-success fw-bold small"><?=$msg?></span>
                    <?php
                }else{
                    ?>
                    <span class="text-danger fw-bold small"><?=$msg?></span>
                    <?php
                }
                */
                ?>
            </div> 
        </div>
    </div>
    
    <?php
    $act_price = 0;
    }
    $sql1 = $con->query("select * from quote where quote_id='$qid'");
    if($row1 = $sql1->fetch_assoc()){
        if($row1['gst_type'] >0){
            $total = round(($total_price*18/100)+$total_price);
        }else{
            $total = $total_price;
        }
    }
    ?>
    <div class="col-lg-12">
        <div class="row" style="position: relative; top: -20px;">
            <div class="col-lg-8">
                
            </div>
            <div class="col-lg-2">
                <label>Total Amount</label>
                <input type="text" name="total_amt" id="total_amt" value="<?=$total?>" class="form-control" readonly>
            </div>
            <div class="col-lg-2">
                <label>Margin Amount</label>
                <input type="text" name="margin" value="<?=$total_margin?>" class="form-control" readonly>
            </div>
        </div>
    </div>
    <div class="col-lg-12 mb-2">
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Payment Date</th>
                                <th>Received Amount</th>
                                <th>Payment Mode</th>
                                <th>TXN ID</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rcv_amt = 0;
                            $sql = $con->query("select * from client_txn where qid='$qid' order by id asc");
                            while($row = $sql->fetch_assoc()){
                                $rcv_amt += $row['amt'];
                                ?>
                                <tr>
                                    <td><?=date('d-M-Y',strtotime($row['p_date']))?></td>
                                    <td>₹<?=$row['amt']?></td>
                                    <td><?php if($row['p_mode'] == 0){echo"Cash";}else{echo"Online";}?></td>
                                    <td><?=$row['txn_id']?></td>
                                    <td><?=date('d-M-Y h:i A',strtotime($row['created']))?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-3 mb-1">
                <label>Recieved Date</label>
                <input type="date" name="p_date" value="<?=date('Y-m-d',strtotime($current_date))?>" class="form-control" required>
            </div>
            <div class="col-lg-3 mb-1">
                <label>Recieved Amount</label>
                <input type="number" name="rcv_amt" id="rcv_amt" onkeyup="outstandingCal2()" min="0" max="<?=$total-$rcv_amt?>" class="form-control" onkeypress="return isNumber(event);" placeholder="Enter Recieved Amount" required>
            </div>
            <div class="col-lg-3">
                <label>Payment Mode</label>
              <select class="form-control" onchange="paymentMode();" id="p_mode" name="p_mode" required="">
                 <option value="">Choose Option</option>
                 <option value="0">Cash</option>
                 <option value="1">Online</option>
              </select>
           </div>
           <div class="col-lg-3 d-none" id="tnx_sec">
               <label>Transaction ID</label>
              <input type="text" name="txn_id" class="form-control" placeholder="Transaction ID">
           </div>
           <div class="col-lg-3 mb-1">
                <label>Outstanding Amount</label>
                <input type="text" name="out_amt" id="out_amt" value="<?=$total-$rcv_amt?>" class="form-control" onkeypress="return isNumber(event);" readonly required>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="qid" value="<?=$qid?>">
<input type="hidden" name="cid" value="<?=$cid?>">
<input type="hidden" name="price" value="<?=$total_price?>">
<input type="hidden" name="prod" value="<?=$rws['product']?>">
<input type="hidden" id="tot_out_amt" value="<?=$total-$rcv_amt?>">