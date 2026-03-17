<?php
if(isset($_POST['submit'])){
   $prod = implode(",",$_POST['prod_type']);
   $cid = rand(100000,999999);
   $sql = $con->query("select * from client where contact='".$_POST['cont']."'");
   if($sql->fetch_assoc()){
       echo"<script>alert('Lead Already Exists.');window.location='dashboard.php?src=client.php';</script>";
   }else{
       if($con->query("insert into client(cid,branch,company,address,district,state,country,gst,person,contact,alt_contact,email,source,product,next_followup,remarks,ref_by,uploaded_type,uploaded_by,created) values(
          '$cid',
          '".$_POST['branch']."',
          '".$_POST['c_name']."',
          '".$_POST['adrs']."',
          '".$_POST['dist']."',
          '".$_POST['state']."',
          '".$_POST['country']."',
          '".$_POST['gst']."',
          '".$_POST['person']."',
          '".$_POST['cont']."',
          '".$_POST['alt_cont']."',
          '".$_POST['email']."',
          '".$_POST['source']."',
          '".$prod."',
          '".$_POST['f_data']."',
          '".$_POST['remarks']."',
          '".$_POST['ref']."',
          'admin',
          '$aid',
          '".$current_date."'
       )") === true){
          echo"<script>window.location='dashboard.php?src=client.php';</script>";
       }
   }
   
}
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Client</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-sm-6 mb-3">
      <div class="card">
         <div class="card-body">
            <p class="text-sm mb-2 text-capitalize font-weight-bold">Add New Client</p>
            <form method="post"  class="row">
               <div class="col-lg-3">
                  <select name="branch" class="form-control" required>
                     <option value="">Select Branch</option>
                     <?php
                     $sql = $con->query("select * from branch order by name asc");
                     while($row = $sql->fetch_assoc()){
                        ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                        <?php
                     }
                     ?>
                  </select>
               </div>
               <div class="col-lg-4 mb-2">
                  <input type="text" name="c_name" class="form-control" placeholder="Company Name" required>
               </div>
               <div class="col-lg-5 mb-2">
                  <input type="text" name="adrs" class="form-control" placeholder="Address" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="text" name="dist" class="form-control" placeholder="District" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="text" name="state" class="form-control" placeholder="State" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <select name="country" class="form-control" required>
                     <option value="India">India</option>
                     <option value="USA">USA</option>
                     <option value="Canada">Canada</option>
                     <option value="Germany">Germany</option>
                     <option value="Dubai">Dubai</option>
                     <option value="Oman">Oman</option>
                     <option value="Kuwait">Kuwait</option>Kuwait
                  </select>
               </div>
               <div class="col-lg-2 mb-2">
                  <!-- <label>GST Type</label> -->
                  <select name="gst_type" class="form-control" >
                     <option value="">GST Type</option>
                     <option value="1">GST</option>
                     <option value="2">IGST</option>
                     <option value="0">N/A</option>
                  </select>
               </div>
               <div class="col-lg-4 mb-2">
                  <input type="text" name="gst" class="form-control" placeholder="GST Number (Optional)" >
               </div>
               <div class="col-lg-12">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Personal Details</p>
               </div>
               <div class="col-lg-4 mb-2">
                  <input type="text" name="person" class="form-control" placeholder="Contact Person" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="text" name="cont" class="form-control" onkeypress="return isNumber(event);" maxlength="10" placeholder="Contact No." required>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="text" name="alt_cont" class="form-control" onkeypress="return isNumber(event);" maxlength="10" placeholder="Alt Contact No.">
               </div>
               <div class="col-lg-4 mb-2">
                  <input type="text" name="email" class="form-control" placeholder="Email (Optional)" required>
               </div>
               <div class="col-lg-12 mb-2">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Lead Details</p>
               </div>
               <div class="col-lg-3 mb-2">
                  <select name="source" class="form-control" required>
                     <option value="">Lead Source</option>
                     <option value="Reference">Reference</option>
                     <option value="Facebook">Facebook</option>
                     <option value="Google">Google</option>
                     <option value="Linkedin">Linkedin</option>
                     <option value="Google Ads">Google Ads</option>
                     <option value="Office Database">Office Database</option>
                     <option value="Cold Calling">Cold Calling</option>
                  </select>
               </div>
               <div class="col-lg-2 mb-2">
                  <div class="dropdown d-grid dash_drop_btn">
                    <button class="btn btn-outline-secondary dropdown_btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="true"> Products </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" >
                     <?php
                     $sql = $con->query("select * from product order by name asc");
                     while($row = $sql->fetch_assoc()){
                        ?>
                        <li>
                             <a class="dropdown-item">
                              <input type="checkbox" class="select_product" id="prod_<?php echo $row['id'] ?>" style="width: 15px; height: 15px;" name="prod_type[]" value="<?php echo $row['id'] ?>">
                              <label for="prod_<?php echo $row['id'] ?>"><?php echo $row['name'] ?></label>
                             </a>
                         </li>
                        <?php
                     }
                     ?>
                     </ul>
                  </div>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="date" name="f_data" class="form-control" placeholder="NFD" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <!-- <label>Reffered By</label> -->
                  <select name="ref" class="form-control" required>
                     <option value="0">Refered By</option>
                     <?php
                     $sql = $con->query("select * from refered_by order by name asc");
                     while($row = $sql->fetch_assoc()){
                        ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                        <?php
                     }
                     ?>
                  </select>
               </div>
               <div class="col-lg-12 mb-2">
                  <textarea class="form-control" name="remarks" placeholder="Remarks"></textarea>
               </div>
               <div class="col-lg-12 mb-2">
                  <button class="btn btn-primary" name="submit">Submit</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="col-sm-6 mb-3">
      <div class="table-responsive dash_table">
         <table class="table table-striped example">
            <thead>
               <tr>
                  <th>#</th><th>Product Name</th><th>Selling Price</th><th>Dis(%)</th><th>Discounted Price</th><th>Margin Price</th>
               </tr>
            </thead>
            <tbody>
            <?php
			$sl = 1;
			$sql = $con->query("select * from product order by name asc");
			while($row = $sql->fetch_assoc()){
			   ?>
			   <tr>
			      <td><?php echo $sl ?></td>
			      <td>
			         <b><?php echo $row['name']; ?></b><br>
			         <!-- <span style="font-size: 8pt;"><?php echo $row['des'] ?></span>     -->
			      </td>
			      <td>₹<?php echo $row['s_price']?></td>
			      <td><?php echo $row['dis']?></td>
			      <td>₹<?php echo $row['d_price']?></td>
			      <td>
			         0
			      </td>
			   </tr>
			   <?php
			   $sl++;
			}
			?>
            </tbody>
         </table>
      </div>
   </div>
   <div class="col-lg-12 ">
      <div class="table-responsive">
         <table class="table table-striped example">
            <thead>
               <tr>
                  <th>SL</th>
                  <th>Client ID</th>
                  <th>Branch</th>
                  <th>Company</th>
                  <th>Address</th>
                  <th>District</th>
                  <th>State</th>
                  <th>Country</th>
                  <th>GST</th>
                  <th>Person</th>
                  <th>Contact</th>
                  <th>Alt Contact</th>
                  <th>Email</th>
                  <th>Lead Source</th>
                  <th>Products</th>
                  <th>Next Followup Date</th>
                  <th>Remarks</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $sl = 1;
               $sql  =$con->query("select * from client order by id desc limit 5");
               while($row = $sql->fetch_assoc()){
                  $sql2 = $con->query("select * from branch where id='".$row['branch']."'");
                  if($row2 = $sql2->fetch_assoc()){
                     $branch_name = $row2['name'];
                  }
                  ?>
                  <tr>
                     <td><?php echo $sl ?></td>
                     <td><?php echo $row['cid'] ?></td>
                     <td><?php echo $branch_name ?></td>
                     <td><?php echo $row['company'] ?></td>
                     <td><?php echo $row['address'] ?></td>
                     <td><?php echo $row['district'] ?></td>
                     <td><?php echo $row['state'] ?></td>
                     <td><?php echo $row['country'] ?></td>
                     <td><?php echo $row['gst'] ?></td>
                     <td><?php echo $row['person'] ?></td>
                     <td><?php echo $row['contact'] ?></td>
                     <td><?php echo $row['alt_contact'] ?></td>
                     <td><?php echo $row['email'] ?></td>
                     <td><?php echo $row['source'] ?></td>
                     <td>
                        <?php
                        $prod = explode(",",$row['product']);
                        foreach($prod as $pid){
                           $sql2 = $con->query("select * from product where id='$pid'");
                           if($row2 = $sql2->fetch_assoc()){
                              echo "<span class='badge bg-primary'>".$row2['name']."</span>&nbsp;";
                           }
                        }
                        ?>
                     </td>
                     <td><?php echo $row['next_followup'] ?></td>
                     <td><?php echo $row['remarks'] ?></td>
                     <td>
                        <a href="dashboard.php?src=client_edit.php&id=<?php echo $row['id'] ?>" class="badge bg-success"><i class="fa fa-edit"></i></a>
                        <a href="dashboard.php?src=client.php" class="badge bg-danger" onclick="return dlt();"><i class="fa fa-trash"></i></a>
                     </td>
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