<?php
if(isset($_POST['submit'])){
    $cid = $_POST['cid'];
    $qid = $_POST['qid'];
	$client = $_POST['client'];
	$p_date = $_POST['p_date'];
	$p_no = $_POST['p_no'];
	$d_des = $_POST['d_des'];
	$p_terms = $_POST['p_terms'];
	$gst_type = $_POST['gst_type'];
	$gst = $_POST['gst'];
	$if_anex = $_POST['if_anex'];
	$anex = $_POST['anex'];

	$pid = implode(",",$_POST['pid']);

	$name = $_POST['name'];
	$hsn = $_POST['hsn'];
	$qty = $_POST["qty"];
	$price = $_POST['s_price'];
	$per = $_POST['per'];
	$dis = $_POST['dis'];
	$des = $_POST['des'];

	$con->query("update client set product='$pid' where id='$cid'");

	if($con->query("update quote set client='$cid',product='$pid',p_no='$p_no',p_date='$p_date',d_des='$d_des',p_terms='$p_terms',gst_type='$gst_type',gst_no='$gst',if_anex='$if_anex',anex='$anex' where quote_id='$qid'") === true){
        $con->query("delete from quote_item where qid='$qid'");
		
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
			$q = "insert into quote_item(pid,qid,cid,name,hsn,qty,price,per,dis,des) values('$itemid','$qid','$cid','$item','$hsn','$qty','$price','$per','$dis','$des')";
			$con->query($q);
		}
		
		echo"<script>window.location='dashboard.php?src=client_details.php&id=$cid';</script>";
	}else{
		echo"<script>alert('Server Error');window.location='dashboard.php?src=client_details.php&id=$cid';</script>";
	}
}

$id = $_GET['cid'];
$sql = $con->query("select * from client where id='$id'");
$row = $sql->fetch_assoc();

$qid = $_GET['qid'];
$qry = $con->query("select * from quote where quote_id='$qid'");
$rws = $qry->fetch_assoc();
?>
<div class="row">
   <div class="col-sm-12 mb-2">
      <nav aria-label="breadcrumb ">
         <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm text-dark active">
               <a class="opacity-3 text-dark" href="dashboard.php?src=client_details.php&id=<?=$id?>">
               <i class="fa fa-arrow-circle-left"></i> Back To Client Details Page
               </a>
            </li>
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
                     <option value="">Refered By</option>
                  </select>
               </div>
               <div class="col-lg-2">
                  <!--<a href="" class="btn btn-sm btn-secondary mt-4 client_followup" data-id="<?php echo $id ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">Followup</a>-->
               </div>
               <div class="col-lg-12 ">
                  <p class="text-sm mb-1 mt-4 text-capitalize font-weight-bold">Quotation Details</p>
               </div>
               <div class="col-lg-3 mb-2">
                  <label>Quotation Date</label>
                  <input type="date" name="q_date" class="form-control" value="<?php echo date('Y-m-d',strtotime($rws['created'])) ?>" readonly required>
               </div>
               <div class="col-lg-3 mb-2">
                  <label>Quotation Number</label>
                  <input type="text" name="q_no" class="form-control" value="<?=$qid?>" readonly required>
               </div>
               <div class="col-lg-3 mb-2">
                  <label>PO No.</label>
                  <input type="text" name="p_no" value="<?=$rws['p_no']?>" class="form-control" placeholder="PO No.">
               </div>
               <div class="col-lg-3 mb-2">
                  <label>PO Date</label>
                  <input type="text" name="p_date" value="<?=$rws['p_date']?>" class="form-control" placeholder="PO Date">
               </div>
               <div class="col-lg-6 mb-2">
                  <label>Discount Description</label>
                  <input type="text" name="d_des" value="<?=$rws['p_des']?>" class="form-control" placeholder="Discount Description" >
               </div>
               <div class="col-lg-6 mb-2">
                  <label>Payment Terms</label>
                  <input type="text" name="p_terms" value="<?=$rws['p_terms']?>" class="form-control" placeholder="Payment Terms" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <label>GST Type</label>
                  <select name="gst_type" class="form-control" required>
                     <option value="1" <?php if($rws['gst_type'] == "1"){echo"selected";}?>>GST</option>
                     <option value="2" <?php if($rws['gst_type'] == "2"){echo"selected";}?>>IGST</option>
                     <option value="0" <?php if($rws['gst_type'] == "0"){echo"selected";}?>>N/A</option>
                  </select>
               </div>
               <div class="col-lg-3 mb-2">
                  <label>GST Number</label>
                  <input type="text" name="gst" class="form-control" value="<?php echo $row['gst']; ?>" readonly required>
               </div>
               <div class="col-lg-2 mb-2">
                  <label>Anexture</label>
                  <select name="if_anex" class="form-control if_anex" required>
                     <option value="0" <?php if($rws['if_anex'] == "0"){echo"selected";}?>>NO</option>
                     <option value="1" <?php if($rws['if_anex'] == "1"){echo"selected";}?>>YES</option>
                  </select>
               </div>
               <div class="col-lg-12 anex <?php if($rws['if_anex'] == "0"){echo"d-none";}?>">
               	<textarea name="anex">
               		<?php
               		if($rws['if_anex'] == "1"){
               		    echo $rws['anex'];
               		}else{
               		    $qry = $con->query("select * from anex");
                   		if($rws= $qry->fetch_assoc()){
                   			echo $rws['content'];
                   		}
               		}
               		?>
               	</textarea>
               </div>
               <div class="col-lg-12 mb-2">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Product Details</p>
                  <?php
                 $sl1 = 1;
                 $sql3 = $con->query("select * from quote_item where qid='$qid'");
                 while($row3 = $sql3->fetch_assoc()){
                    $ids = rand(100,999);
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
                          <input type="text" name="qty[]" value="1" class="form-control" required>
                       </div>
                       <div class="col-lg-1 mb-2">
                          <label>Per</label>
                          <input type="text" name="per[]" value="<?php echo $row3['per'] ?>" class="form-control" required>
                       </div>
                       <div class="col-lg-2 mb-2">
                          <label>Selling Price</label>
                          <input type="text" name="s_price[]" value="<?php echo $row3['price'] ?>" class="form-control" required>
                       </div>
                       <div class="col-lg-1 mb-2">
                          <label>Dis(%)</label>
                          <input type="text" name="dis[]" value="<?php echo $row3['dis'] ?>" class="form-control" required>
                       </div>
                       <div class="col-lg-1 mb-2">
                          <label>D. Price</label>
                          <input type="text" name="d_price[]" value="<?php echo $row3['price']-($row3['price']*$row3['dis'])/100 ?>" class="form-control" required>
                       </div>
                       <div class="col-lg-1">
                          <a href="#" class="btn btn-danger mt-3 remove_sec" data-id="ids_<?php echo $ids?>"><i class="fa fa-trash"></i></a>
                       </div>
                       <div class="col-lg-12">
                          <textarea name="des[]" class="form-control" ><?php echo $row3['des'] ?></textarea>
                          <!-- <input type="text" name="des[]" value="" class="form-control" placeholder="Description" required> -->
                       </div>
                    </div>
                 </div>
                 <input type="hidden" name="pid[]" value="<?php echo $row3['id'] ?>">
                 <?php
                 $sl1++;
                 }
                 
                  ?>
                  <div id="item_append"></div>
               </div>
               <div class="col-lg-12 mb-2">
               	<input type="hidden" name="cid" value="<?php echo $id ?>">
               	<input type="hidden" name="qid" value="<?php echo $qid ?>">
               	<button class="btn btn-sm btn-primary" name="submit">Save Changes</button>
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
                        <th>Action</th>
                        <th>Product</th>
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
</div>