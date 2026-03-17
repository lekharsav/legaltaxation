<?php
if(isset($_POST['submit'])){
   $id = $_POST['id'];
   $prod = implode(",",$_POST['prod_type']);
   if($con->query("update client set
      company='".$_POST['c_name']."',
      address='".$_POST['adrs']."',
      district='".$_POST['dist']."',
      state='".$_POST['state']."',
      country='".$_POST['country']."',
      gst='".$_POST['gst']."',
      person='".$_POST['person']."',
      contact='".$_POST['cont']."',
      alt_contact='".$_POST['alt_cont']."',
      email='".$_POST['email']."',
      source='".$_POST['source']."',
      product='".$prod."',
      next_followup='".$_POST['f_data']."',
      remarks='".$_POST['remarks']."' where id='$id'") === true){
      echo"<script>window.location='dashboard.php?src=client.php';</script>";
   }else{
      echo"<script>alert('server error!!');window.location='dashboard.php?src=client.php';</script>";
   }
}

$id = $_GET['id'];
$sql = $con->query("select * from client where id='$id'");
$row = $sql->fetch_assoc();

$prod_type = explode(",",$row['product']);
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
   <div class="col-sm-7 mb-3">
      <div class="card">
         <div class="card-body">
            <p class="text-sm mb-2 text-capitalize font-weight-bold">Client Details</p>
            <form method="post"  class="row">
               <div class="col-lg-2">
                  <select name="branch" class="form-control" required>
                     <option value="">Select Branch</option>
                     <?php
                     $sql2 = $con->query("select * from branch order by name asc");
                     while($row2 = $sql2->fetch_assoc()){
                        ?>
                        <option value="<?php echo $row2['id'] ?>" <?php if($row['branch'] == $row2['id']){echo"selected";}?>><?php echo $row2['name'] ?></option>
                        <?php
                     }
                     ?>
                  </select>
               </div>
               <div class="col-lg-3 mb-2">
                  <input type="text" name="c_name" value="<?php echo $row['company'] ?>" class="form-control" placeholder="Company Name" required>
               </div>
               <div class="col-lg-3 mb-2">
                  <input type="text" name="adrs" value="<?php echo $row['address'] ?>" class="form-control" placeholder="Address" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="text" name="dist" value="<?php echo $row['district'] ?>" class="form-control" placeholder="District" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="text" name="state" value="<?php echo $row['state'] ?>" class="form-control" placeholder="State" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <select name="country" class="form-control" required>
                     <option value="India" <?php if($row['country'] == "India"){echo"selected";} ?>>India</option>
                     <option value="USA" <?php if($row['country'] == "USA"){echo"selected";} ?>>USA</option>
                     <option value="Canada" <?php if($row['country'] == "Canada"){echo"selected";} ?>>Canada</option>
                     <option value="Germany" <?php if($row['country'] == "Germany"){echo"selected";} ?>>Germany</option>
                     <option value="Dubai" <?php if($row['country'] == "Dubai"){echo"selected";} ?>>Dubai</option>
                     <option value="Oman" <?php if($row['country'] == "Oman"){echo"selected";} ?>>Oman</option>
                     <option value="Kuwait" <?php if($row['country'] == "Kuwait"){echo"selected";} ?>>Kuwait</option>Kuwait
                  </select>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="text" name="gst" value="<?php echo $row['gst'] ?>" class="form-control" placeholder="GST" >
               </div>
               <div class="col-lg-12">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Personal Details</p>
               </div>
               <div class="col-lg-4 mb-2">
                  <input type="text" name="person" value="<?php echo $row['person'] ?>" class="form-control" placeholder="Contact Person" required>
               </div>
               <div class="col-lg-2 mb-2">
                  <input type="text" name="cont" value="<?php echo $row['contact'] ?>" class="form-control" placeholder="Contact No." required>
               </div>
               <div class="col-lg-3 mb-2">
                  <input type="text" name="alt_cont" value="<?php echo $row['alt_contact'] ?>" class="form-control" placeholder="Alt Contact (Optional)">
               </div>
               <div class="col-lg-3 mb-2">
                  <input type="text" name="email" value="<?php echo $row['email'] ?>" class="form-control" placeholder="Email (Optional)" required>
               </div>
               <div class="col-lg-12 mb-2">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Lead Details</p>
               </div>
               <div class="col-lg-2 mb-2">
                  <select name="source" class="form-control" required>
                     <option value="">Lead Source</option>
                     <option value="Facebook" <?php if($row['source'] == "Facebook"){echo"selected";} ?>>Facebook</option>
                     <option value="Google" <?php if($row['source'] == "Google"){echo"selected";} ?>>Google</option>
                     <option value="Linkedin" <?php if($row['source'] == "Linkedin"){echo"selected";} ?>>Linkedin</option>
                     <option value="Google Ads" <?php if($row['source'] == "Google Ads"){echo"selected";} ?>>Google Ads</option>
                     <option value="Office Database" <?php if($row['source'] == "Office Database"){echo"selected";} ?>>Office Database</option>
                     <option value="Cold Calling" <?php if($row['source'] == "Cold Calling"){echo"selected";} ?>>Cold Calling</option>
                  </select>
               </div>
               <div class="col-lg-2 mb-2">
                  <div class="dropdown d-grid dash_drop_btn">
                    <button class="btn btn-outline-secondary dropdown_btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="true"> Products </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" >
                     <?php
                     $sql2 = $con->query("select * from product order by name asc");
                     while($row2 = $sql2->fetch_assoc()){
                        ?>
                        <li>
                             <a class="dropdown-item">
                              <input type="checkbox" class="select_product" id="prod_<?php echo $row2['id'] ?>" style="width: 15px; height: 15px;" name="prod_type[]" value="<?php echo $row2['id'] ?>" <?php if(in_array($row2['id'],$prod_type)){echo"checked";}?>>
                              <label for="prod_<?php echo $row2['id'] ?>"><?php echo $row2['name'] ?></label>
                             </a>
                         </li>
                        <?php
                     }
                     ?>
                     </ul>
                  </div>
               </div>
               <div class="col-lg-3 mb-2">
                  <input type="date" name="f_data" value="<?php echo $row['next_followup'] ?>" class="form-control" placeholder="NFD" required>
               </div>
               <div class="col-lg-12 mb-2">
                  <textarea class="form-control" name="remarks" placeholder="Remarks"><?php echo $row['remarks'] ?></textarea>
               </div>
               <div class="col-lg-12 mb-2">
                  <input type="hidden" name="id" value="<?php echo $id ?>">
                  <button class="btn btn-primary" name="submit">Submit</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>