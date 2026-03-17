<?php
if(isset($_POST['submit'])){
	$client = $_POST['client'];
	$p_date = $_POST['p_date'];
	$p_no = $_POST['p_no'];
	$d_des = $_POST['d_des'];
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
		
		echo"<script>window.location='dashboard.php?src=client_details.php&id=$client';</script>";
	}else{
		echo"<script>alert('Server Error');window.location='dashboard.php?src=client_details.php&id=$client';</script>";
	}
}

if(isset($_GET['delete'])){
    $id = $_GET['id'];
    $qid = $_GET['qid'];
    if($con->query("delete from quote where quote_id='$qid'")===true){
        $con->query("delete from quote_item where qid='$qid'");
        echo"<script>window.location='dashboard.php?src=client_details.php&id=$id';</script>";
    }
}

$id = $_GET['id'];
$sql = $con->query("select * from client where id='$id'");
$row = $sql->fetch_assoc();

?>
<div class="row">
   <div class="col-sm-12 mb-2">
      <nav aria-label="breadcrumb ">
         <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
               <a class="opacity-3 text-dark" href="dashboard.php">
               Dashboard
               </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Entry</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="dashboard.php?src=client_all.php">Client</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Client Details</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-lg-7 mb-2">
   	<div class="card">
   		<div class="card-body">
   			<p class="text-sm mb-2 text-capitalize font-weight-bold">Client Details: </p>
            <h5><?php echo $row['company'] ?> | ID: <?php echo $row['cid'] ?></h5>
            <small style="position: relative; top: -13px;"><i class="fa fa-map-marker"></i> <?php echo $row['address'] ?>, <?php echo $row['district'] ?>, <?php echo $row['state'] ?>, <?php echo $row['country'] ?></small>
   			<form method="post" class="row">
   				<div class="col-lg-3 mb-2">
                  <label>Contact Person</label>
   					<input type="text" name="" value="<?php echo $row['person'] ?>"	class="form-control" readonly required>
   				</div>
   				<div class="col-lg-3 mb-2">
                  <label>Contact No.</label>
   					<input type="text" name="" value="<?php echo $row['contact'] ?>"	class="form-control" readonly required>
   				</div>
   				<div class="col-lg-3 mb-2">
                  <label>Alt Contact No.</label>
   					<input type="text" name="" value="<?php echo $row['alt_contact'] ?>"	class="form-control" readonly required>
   				</div>
   				<div class="col-lg-3 mb-2">
                  <label>Email Address</label>
   					<input type="text" name="" value="<?php echo $row['email'] ?>"	class="form-control" readonly required>
   				</div>
               <div class="col-lg-2">
                  <label>Lead Source</label>
                  <select name="country" class="form-control" readonly required>
                     <option value="India" <?php if($row['country'] == "India"){echo"selected";} ?>>India</option>
                     <option value="USA" <?php if($row['country'] == "USA"){echo"selected";} ?>>USA</option>
                     <option value="Canada" <?php if($row['country'] == "Canada"){echo"selected";} ?>>Canada</option>
                     <option value="Germany" <?php if($row['country'] == "Germany"){echo"selected";} ?>>Germany</option>
                     <option value="Dubai" <?php if($row['country'] == "Dubai"){echo"selected";} ?>>Dubai</option>
                     <option value="Oman" <?php if($row['country'] == "Oman"){echo"selected";} ?>>Oman</option>
                     <option value="Kuwait" <?php if($row['country'] == "Kuwait"){echo"selected";} ?>>Kuwait</option>Kuwait
                  </select>
               </div>
               <div class="col-lg-2">
                  <label>Reffered By</label>
                  <select name="ref" class="form-control" readonly required>
                     <?php
                     $sql11 = $con->query("select * from refered_by where id='".$row['ref_by']."'");
                     if($row11 = $sql11->fetch_assoc()){
                        echo "<option>".$row11['name']."</option>";
                     }
                     ?>
                  </select>
               </div>
               <div class="col-lg-2">
                  <a href="" class="btn btn-sm btn-secondary mt-4 client_followup" data-id="<?php echo $id ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">Followup</a>
               </div>
               <div class="col-lg-12 ">
                  <p class="text-sm mb-1 mt-4 text-capitalize font-weight-bold">Quotation Details</p>
               </div>
               <div class="col-lg-3 mb-2">
                  <label>Quotation Date</label>
                  <input type="date" name="q_date" class="form-control" value="<?php echo date('Y-m-d',strtotime($current_date)) ?>" readonly required>
               </div>
               <div class="col-lg-3 mb-2">
                  <label>Quotation Number</label>
                  <input type="text" name="q_no" class="form-control" value="AIM-<?php echo date('dmy',strtotime($current_date))."-".rand(100,999) ?>" readonly required>
               </div>
               <div class="col-lg-3 mb-2">
                  <label>PO No.</label>
                  <input type="text" name="p_no" class="form-control" placeholder="PO No.">
               </div>
               <div class="col-lg-3 mb-2">
                  <label>PO Date</label>
                  <input type="text" name="p_date" class="form-control" placeholder="PO Date">
               </div>
               <div class="col-lg-6 mb-2">
                  <label>Discount Description</label>
                  <input type="text" name="d_des" class="form-control" placeholder="Discount Description" >
               </div>
               <div class="col-lg-6 mb-2">
                  <label>Payment Terms</label>
                  <input list="brow" name="p_terms" class="form-control" autocomplete="off" placeholder="Choose Option " required>
                  <datalist id="brow">
                     <option value="Full payment in advance.">
                     <option value="60% in advance, next 20% middle of work, rest 20% the time of delivery.">
                  </datalist>
                  <!-- <select name="p_terms" class="form-control" required>
                     <option value="">Choose Option</option>
                     <option value="Full payment in advance.">Full payment in advance.</option>
                     <option value="">60% in advance, next 20% middle of work, rest 20% the time of delivery.</option>
                  </select> -->
                  <!-- <input type="text" name="p_terms" class="form-control" placeholder="Payment Terms" required> -->
               </div>
               <div class="col-lg-2 mb-2">
                  <label>GST Type</label>
                  <select name="gst_type" class="form-control quote_gst" required>
                     <option value="1">GST</option>
                     <option value="2">IGST</option>
                     <option value="0">N/A</option>
                  </select>
               </div>
               <div class="col-lg-3 mb-2">
                  <label>GST Number</label>
                  <input type="text" name="gst" class="form-control gst_no" value="<?php echo $row['gst']; ?>" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <label>Anexture</label>
                  <select name="if_anex" class="form-control if_anex" required>
                     <option value="0">NO</option>
                     <option value="1">YES</option>
                  </select>
               </div>
               <div class="col-lg-12 anex d-none">
               	<textarea name="anex">
               		<?php
               		$qry = $con->query("select * from anex");
               		if($rws= $qry->fetch_assoc()){
               			echo $rws['content'];
               		}
               		?>
               	</textarea>
               </div>
               <div class="col-lg-12 mb-2">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Product Details</p>
                  <?php
                  $sl1 = 1;
                  $p = explode(",",$row['product']);
                  foreach($p as $pid){
                     
                     $sql3 = $con->query("select * from product where id='$pid'");
                     if($row3 = $sql3->fetch_assoc()){
                        $ids = rand(100,999);
                        $s_id = rand(100,999);
                        $d_id = rand(100,999);
                        $dis_id = rand(100,999);
                     ?>
                     <div class="bg-lite prod_box" id="ids_<?php echo $ids?>">
                        <div class="row">
                           <div class="col-lg-4 mb-2">
                              <label>Product Name</label>
                              <input type="text" name="name[]" value="<?php echo $row3['name'] ?>" class="form-control" required>
                           </div>
                           <div class="col-lg-1 mb-2">
                              <label>HSN</label>
                              <input type="text" name="hsn[]" value="<?php echo $row3['hsn'] ?>" class="form-control" required>
                           </div>
                           <div class="col-lg-1 mb-2">
                              <label>Qty</label>
                              <input type="text" name="qty[]" value="1" onkeypress="return isNumber(event);" class="form-control" required>
                           </div>
                           <div class="col-lg-1 mb-2">
                              <label>Per</label>
                              <input type="text" name="per[]" value="<?php echo $row3['per'] ?>" class="form-control" required>
                           </div>
                           <div class="col-lg-2 mb-2">
                              <label>Selling Price</label>
                              <input type="text" name="s_price[]" value="<?php echo $row3['s_price'] ?>" id="<?php echo $s_id ?>" data-val="<?php echo $dis_id ?>" data-dis="<?php echo $d_id ?>" class="form-control selling_price" onkeypress="return isNumber(event);" required>
                           </div>
                           <div class="col-lg-1 mb-2">
                              <label>Dis(%)</label>
                              <input type="text" name="dis[]" value="<?php echo $row3['dis'] ?>" id="<?php echo $d_id ?>" data-sel="<?php echo $s_id ?>" data-val="<?php echo $dis_id ?>" class="form-control discounted_price" onkeypress="return isNumber(event);" required>
                           </div>
                           <div class="col-lg-1 mb-2">
                              <label>D. Price</label>
                              <input type="text" name="d_price[]" value="<?php echo $row3['d_price'] ?>" id="<?php echo $dis_id ?>" class="form-control" onkeypress="return isNumber(event);" readonly required>
                           </div>
                           <div class="col-lg-1">
                              <a href="#" class="btn btn-danger mt-3 remove_sec" data-id="ids_<?php echo $ids?>"><i class="fa fa-trash"></i></a>
                           </div>
                           <div class="col-lg-12">
                              <textarea name="des[]" class="form-control" ><?php echo $row3['des'] ?></textarea>
                              <!-- <input type="text" name="des[]" value="" class="form-control" placeholder="Description" required> -->
                           </div>
                        </div>
                        <input type="hidden" name="pid[]" value="<?php echo $row3['id'] ?>">
                     </div>
                     
                     <?php
                     }
                     $sl1++;
                  }
                  ?>
                  <div id="item_append"></div>
               </div>
               <div class="col-lg-12 mb-2">
               	<input type="hidden" name="client" value="<?php echo $id ?>">
               	<button class="btn btn-sm btn-primary" name="submit">Preview Quotation</button>
               </div>
   			</form>
   		</div>
   	</div>
   </div>
   <div class="col-lg-5 mb-2">
      <div class="card">
         <div class="card-body">
            <p class="text-sm mb-2 text-capitalize font-weight-bold text-center">Select Product For Quotation</p>
            <div class="table-responsive" style="height: 530px;">
               <table class="table table-sm table-striped">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Avg. Purchase Price</th>
                        <th>Selling Price</th>
                        <th>Discounted Price</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $sl = 1;
                     $sql2 = $con->query("select * from product order by name asc");
                     while($row2= $sql2->fetch_assoc()){
                        ?>
                     <tr>
                        <td>
                           <a href="#" class="badge bg-primary item_list" data-id="<?php echo $row2['id'] ?>" data-item="<?php echo $row2['name'] ?>" data-hsn="<?php echo $row2['hsn'] ?>" data-per="<?php echo $row2['per'] ?>" data-sprice="<?php echo $row2['s_price'] ?>" data-dis="<?php echo $row2['dis'] ?>" data-dprice="<?php echo $row2['d_price'] ?>" data-des="<?php echo $row2['des'] ?>"><i class="fa fa-plus"></i></a>
                        </td>
                        <td><?php echo $row2['name'] ?></td>
                        <td>
                            <?php
                            $tr = 0;
                            $qty = 0;
                            $avg = 0;
                            $sql22 = $con->query("select * from stock_manage where product='".$row2['id']."'");
                            while($row22 = $sql22->fetch_assoc()){
                              $qty = $row22['qty'];
                              $avg = $row22['avg'];
                            }
                            echo $qty;
                            ?>
                        </td>
                        <td><b>₹<?php echo $avg ?></b></td>
                        <td><?php echo $row2['s_price']; ?></td>
                        <td><?php echo $row2['d_price']; ?></td>
                     </tr>
                        <?php
                        $sl++;
                     }
                     ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-12 mb-2">
   	<div class="table-responsive">
   		<table class="table table-striped example" >
   			<thead>
   				<tr>
   					<th>SL</th>
   					<th>Executive Name</th>
   					<th>Quotation Date</th>
   					<th>Quotation No.</th>
   					<th>Products</th>
   					<th>PO No.</th>
   					<th>PO Date</th>
                  <th>Lead Source</th>
   					<th>Refered By</th>
   					<th>Order Close</th>
                  
   					<th>Billing Amount</th>
   					<th>Outstanding & Collection</th>
   					<th>Job Work</th>
                  <th>TAX Invoice No.</th>
   					<th>Action</th>
   				</tr>
   			</thead>
   			<tbody>
   				<?php
   				$sl2 = 1;
   				$sql2 = $con->query("select * from quote where client='$id' order by id desc");
   				while($row2 = $sql2->fetch_assoc()){
   					?>
   					<tr>
   						<td><?php echo $sl2 ?></td>
   						<td>
                        <?php
                        if($row['uploaded_type'] == "admin"){
                           $sql3 = $con->query("select * from admin where id='".$row['uploaded_by']."'");
                           if($row3 = $sql3->fetch_assoc()){
                              echo $row3['name'];
                           }
                        }else{
                           $sql3 = $con->query("select * from employee where id='".$row['uploaded_by']."'");
                           if($row3 = $sql3->fetch_assoc()){
                              echo $row3['name'];
                           }
                        }
                        ?>
                     </td>
   						<td><?php echo $row2['created'] ?></td>
   						<td><a href="quote_details.php?qid=<?php echo $row2['quote_id'] ?>&cid=<?php echo $id ?>" target='blank'><?php echo $row2['quote_id'] ?></a></td>
   						<td>
   						    <?php
                            $prod = explode(",",$row2['product']);
                            foreach($prod as $pid){
                               $sql33 = $con->query("select * from product where id='$pid'");
                               if($row33 = $sql33->fetch_assoc()){
                                  echo "<span class='badge bg-primary'>".$row33['name']."</span>&nbsp;";
                               }
                            }
                        ?>
   						</td>
   						<td><?php echo $row2['p_no'] ?></td>
   						<td><?php echo $row2['p_date'] ?></td>
                     <td><?php echo $row['source'] ?></td>
   						<td>
                        <?php
                        if($row['ref_by']>0){
                           $sql3 = $con->query("select * from refered_by where id='".$row['ref_by']."'");
                           if($row3 = $sql3->fetch_assoc()){
                              echo $row3['name'];
                           }
                        }else{
                           echo"Direct";
                        }
                        
                        ?>
                     </td>
   						<td>
   						    <a href="" class="badge bg-warning <?=$row2['quote_id']?>_order_close order_close_btn <?php if($row2['status']=="1"){echo"d-none";}?>" data-qid="<?php echo $row2['quote_id'] ?>" data-cid="<?php echo $row2['client'];?>" data-bs-toggle="modal" data-bs-target="#ordercloseModal">Pending</a>
   						    <a href="" class="badge bg-success <?=$row2['quote_id']?>_collection collection_update <?php if($row2['status']=="0"){echo"d-none";}?>" data-qid="<?php echo $row2['quote_id'] ?>" data-cid="<?php echo $row2['client'];?>" data-bs-toggle="modal" data-bs-target="#collectionupdateModal">Order Closed</a>
   						</td>
   						<td>
                        <?php
                        $total_coll= 0;
                        $sql1 = $con->query("select * from client_order where qid='".$row2['quote_id']."'");
                        if($row1 = $sql1->fetch_assoc()){
                           $total_coll = $row1['total'];
                        }
                        echo "₹".$total_coll;
                        ?>            
                     </td>
   						<td>
                        <?php
                        $out = 0;
                        $coll = 0;
                        $sql11 = $con->query("select * from client_txn where qid='".$row2['quote_id']."'");
                        while($row11 = $sql11->fetch_assoc()){
                           $coll += $row11['amt'];
                        }
                        $out = $total_coll-$coll;
                        echo "₹".$out;
                        ?>            
                     </td>
   						<td>
                        <?php
                        $sql3 = $con->query("select * from job_work where qid='".$row2['quote_id']."'");
                        if($row3 = $sql3->fetch_assoc()){
                           ?>
                           <a href="" data-bs-toggle="modal" data-bs-target="#jobworkModal" data-cid="<?=$_GET['id']?>" data-qid="<?=$row2['quote_id']?>" class="badge bg-success job_work_btn">Re-assign</a>
                           <?php
                        }else{
                           ?>
                           <a href="" data-bs-toggle="modal" data-bs-target="#jobworkModal" data-cid="<?=$_GET['id']?>" data-qid="<?=$row2['quote_id']?>" class="badge bg-warning job_work_btn">Pending</a>
                           <?php
                        }
                        ?>
                        
                        
                     </td>
   						<td>
                        <?php
                        $sql1 = $con->query("select * from invoice where qid='".$row2['quote_id']."'");
                        if($row1 = $sql1->fetch_assoc()){
                           ?>
                           <a href="invoice.php?cid=<?php echo $_GET['id']?>&qid=<?php echo $row2['quote_id']?>" target="blank"><?=$row1['inv_no']?></a>
                           <?php
                        }else{
                           if($row2['gst_type'] == "0"){
                              ?>
                              <a href="#" onclick="alert('GST Not Applied.');" class="badge bg-info">Generate</a>
                              <?php
                           }else{
                              if($out > 0 || $total_coll == 0){
                                 ?>
                                 <a href="#" onclick="alert('Please clear the outstanding amount.');" class="badge bg-info">Generate</a>
                                 <?php
                              }else{
                                 ?>
                                 <a href="invoice_generate.php?qid=<?=$row2['quote_id']?>&cid=<?=$row['cid']?>" class="badge bg-info">Generate</a>
                                 <?php
                              }
                           }
                           
                        }
                        
                        ?>
                                    
                     </td>
   						<td>
   							<a href="dashboard.php?src=quote_edit.php&cid=<?php echo $_GET['id']?>&qid=<?php echo $row2['quote_id']?>" class="badge bg-success"><i class="fa fa-edit"></i></a>
   							<a href="dashboard.php?src=client_details.php&id=<?=$_GET['id']?>&qid=<?=$row2['quote_id']?>&delete=delete" class="badge bg-danger" onclick="return dlt();"><i class="fa fa-trash"></i></a>
   						</td>
   					</tr>
   					<?php
   					$sl2++;
   				}
   				?>
   			</tbody>
   		</table>
   	</div>
   </div>
</div>
<!-- Order Close Modal -->
 <div class="modal fade" id="ordercloseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form method="post" action="ajax/ajax_order_close_update.php" id="ajax_order_close_form">
         <div class="modal-header">
            <button type="button" class="btn-close text-dark float-end" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
         </div>
        <div class="modal-body" >
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div id="order_close_details"></div>
          <button class="btn btn-sm btn-primary" id="save_order_close" name="followup_update">Save Changes</button>
        </div>
      </form>
      </div>
    </div>
  </div>
 <!-- End -->
 
 <!-- Collection Update Modal -->
 <div class="modal fade" id="collectionupdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form method="post" action="ajax/ajax_collection_update.php" id="ajax_collection_update_form">
         <div class="modal-header">
            <button type="button" class="btn-close text-dark float-end" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
         </div>
        <div class="modal-body" >
         
          <div id="collection_update_details"></div>
          <button class="btn btn-sm btn-primary" id="save_collection" name="followup_update">Save Changes</button>
        </div>
      </form>
      </div>
    </div>
  </div>
 <!-- End -->

 <!-- Job work Modal -->
 <div class="modal fade" id="jobworkModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form method="post" action="ajax/ajax_job_work_update.php" enctype="multipart/form-data" id="ajax_job_work_form">
         <div class="modal-header">
            <button type="button" class="btn-close text-dark float-end" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
         </div>
        <div class="modal-body" >
         
          <div id="job_work_details"></div>
          <button class="btn btn-sm btn-primary" id="job_work" name="job_work">Submit</button>
        </div>
      </form>
      </div>
    </div>
  </div>
 <!-- End -->

<!-- Job work Modal -->
 <div class="modal fade" id="projectBriefModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        
         <div class="modal-header">
            <button type="button" class="btn-close text-dark float-end" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
         </div>
        <div class="modal-body" >
         <pre id="projectBrief"></pre>
        </div>
      </div>
    </div>
  </div>
<!-- End -->