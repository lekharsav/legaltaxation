<?php
if(isset($_POST['submit'])){

	$sql = $con->query("select * from employee order by id desc");
	if($row = $sql->fetch_assoc()){
		$b = substr($row['emp_id'],3,7);
		$emp_id = "AIM".str_pad( $b+1, 4, "0", STR_PAD_LEFT );
	}else{
		$emp_id = "AIM0001";
	}

	

	$photo_name = $_FILES["photo"]["name"];
	$photo = $_FILES["photo"]["tmp_name"];

	$pan_file_name = $_FILES["pan_doc"]["name"];
	$pan_file = $_FILES["pan_doc"]["tmp_name"];

	$aadhaar_file_name = $_FILES["aadhaar_doc"]["name"];
	$aadhaar_file = $_FILES["aadhaar_doc"]["tmp_name"];

	$edu_name = $_FILES["edu_doc"]["name"];
	$edu = $_FILES["edu_doc"]["tmp_name"];
	$edu_imp = implode(",",$edu_name);

	$exp_name = $_FILES['exp_doc']['name'];
	$exp = $_FILES['exp_doc']['tmp_name'];
	$exp_imp = implode(",",$exp_name);

	$passbook_file_name = $_FILES["passbook"]["name"];
	$passbook_file = $_FILES["passbook"]["tmp_name"];

	$q = "insert into employee(emp_id,image,branch,work_under,person,prefix,name,dob,gender,cont,alt_cont,email,adrs1,adrs2,state,dist,pin,p_adrs1,p_adrs2,p_state,p_dist,p_pin,doj,dept,desig,emp_type,gross,next_s,pt,pf,vpf,esi,l_comp,l_salary,edu,pan,aadhaar,uan,bank,account,ifsc,photo,pan_doc,aadhaar_doc,edu_doc,exp_doc,passbook,created) values(
		'$emp_id',
		'$photo_name',
		'".$_POST['branch']."',
		'".$_POST['work_under']."',
		'".$_POST['person']."',
		'".$_POST['prefix']."',
		'".$_POST['name']."',
		'".$_POST['dob']."',
		'".$_POST['gender']."',
		'".$_POST['cont']."',
		'".$_POST['alt_cont']."',
		'".$_POST['email']."',
		'".$_POST['adrs1']."',
		'".$_POST['adrs2']."',
		'".$_POST['state']."',
		'".$_POST['dist']."',
		'".$_POST['pin']."',
		'".$_POST['p_adrs1']."',
		'".$_POST['p_adrs2']."',
		'".$_POST['p_state']."',
		'".$_POST['p_dist']."',
		'".$_POST['p_pin']."',
		'".$_POST['doj']."',
		'".$_POST['dept']."',
		'".$_POST['desig']."',
		'".$_POST['emp_type']."',
		'".$_POST['gross']."',
		'".$_POST['next']."',
		'".$_POST['pt']."',
		'".$_POST['pf']."',
		'".$_POST['vpf']."',
		'".$_POST['esi']."',
		'".$_POST['l_comp']."',
		'".$_POST['l_salary']."',
		'".$_POST['edu']."',
		'".$_POST['pan']."',
		'".$_POST['aadhaar']."',
		'".$_POST['uan']."',
		'".$_POST['bank']."',
		'".$_POST['account']."',
		'".$_POST['ifsc']."',
		'$photo_name',
		'$pan_file_name',
		'$aadhaar_file_name',
		'$edu_imp',
		'$exp_imp',
		'$passbook_file_name',
		'$current_date')";
	if($con->query($q) === true){
		move_uploaded_file($photo_file,"emp_doc/".$photo_name);
		move_uploaded_file($pan_file,"emp_doc/".$pan_file_name);
		move_uploaded_file($aadhaar_file,"emp_doc/".$aadhaar_file_name);
		move_uploaded_file($passbook_file,"emp_doc/".$passbook_file_name);

		
		$mi = new MultipleIterator();
		$mi->attachIterator(new ArrayIterator($edu));
		$mi->attachIterator(new ArrayIterator($edu_name));
		$mi->attachIterator(new ArrayIterator($exp));
		$mi->attachIterator(new ArrayIterator($exp_name));
		foreach($mi as $value){
			list($edu,$edu_name,$exp,$exp_name) = $value;
			move_uploaded_file($edu,"emp_doc/".$edu_name);
			move_uploaded_file($exp,"emp_doc/".$exp_name);
		}
		setcookie("msg","New Employee Added",time()+1);
		echo"<script>window.location='dashboard.php?src=employee.php';</script>";
	}else{
		echo"<script>alert('Server Error!!');window.location='dashboard.php?src=employee.php';</script>";
	}
}
if(isset($_GET['delete'])){
	if($con->query("delete from employee where id='".$_GET['id']."'") === true){
		setcookie("msg","Deleted",time()+1);
		echo"<script>window.location='dashboard.php?src=employee.php';</script>";
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">HRM</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Employee</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-lg-6">
      <div class="card">
         <div class="card-body">
         	
         	<div class="row">
         		<div class="col-lg-8">
         			<p class="text-sm mb-2 text-capitalize font-weight-bold">Add New Employee</p>
         		</div>
         		<div class="col-lg-4 text-end">
         			<a href="" class="text-blue ">Add New +</a>

         		</div>
         	</div>
         	<hr>
            <form class="row" method="post" enctype="multipart/form-data">
               <div class="col-lg-4 mb-2">
	               <label>Branch </label>
	               <select name="branch" class="form-control" required>
	               	<!-- <option value="">Choose Branch</option> -->
	               	<?php
	               	$sql = $con->query("select * from branch where id='$emp_branch' order by name asc");
	               	while($row = $sql->fetch_assoc()){
	               		?>
	               		<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
	               		<?php
	               	}
	               	?>
	               </select>
               </div>
               <div class="col-lg-4 mb-2">
	               <label>Work Under </label>
	               <select name="work_under" class="form-control" required>
	               	<option value="">Choose Option</option>
	               	<?php
	               	$sql = $con->query("select * from designation order by name asc");
	               	while($row = $sql->fetch_assoc()){
	               		?>
	               		<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
	               		<?php
	               	}
	               	?>
	               </select>
               </div>
               <div class="col-lg-4 mb-2">
	               <label>Person </label>
	               <select name="person" class="form-control" required>
	               	<option value="">Choose Option </option>
	               	<?php
	               	$sql = $con->query("select * from employee order by name asc");
	               	while($row = $sql->fetch_assoc()){
	               		?>
	               		<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
	               		<?php
	               	}
	               	?>
	               </select>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Personal Details</p>
               </div>
               <div class="col-lg-2 mb-2">
	               <select name="prefix" class="form-control" required>
	               	<option value="Mr">Mr</option>
	               	<option value="Ms">Ms</option>
	               	<option value="Mrs">Mrs</option>
	               </select>
               </div>
               <div class="col-lg-5 mb-2">
	               <input type="text" name="name" class="form-control" placeholder="Employee Name" required>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="date"  placeholder="DOB" name="dob" class="form-control" required>
               </div>
               <div class="col-lg-2 mb-2">
	               <select name="gender" class="form-control" required>
	               	<option value="">Gender</option>
	               	<option value="MALE">Male</option>
	               	<option value="FEMALE">Female</option>
	               </select>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="text" name="cont" class="form-control" onkeypress="return isNumber(event);" maxlength="10" placeholder="Contact Number" required>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="text" name="alt_cont" class="form-control" placeholder="Alt Contact (Optional)" onkeypress="return isNumber(event);" maxlength="10" >
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="email" name="email" class="form-control" placeholder="Email Address" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Present Address</p>
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="text" name="adrs1" id="adrs1" class="form-control" placeholder="Address Line 1" required>
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="text" name="adrs2" id="adrs2" class="form-control" placeholder="Address Line 2 (Optional)" >
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="state" id="state" class="form-control" placeholder="State" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="dist" id="dist" class="form-control" placeholder="District" required>
               </div>
               
               <div class="col-lg-4 mb-2">
	               <input type="text" name="pin" id="pin"  onkeypress="return isNumber(event);" class="form-control" placeholder="PIN Code" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Permanent Address <input type="checkbox" id="same_as"> <label for="same_as"><small class="sm_txt">Same as present address</small></label></p>
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="text" name="p_adrs1" id="p_adrs1" class="form-control" placeholder="Address Line 1" required>
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="text" name="p_adrs2" id="p_adrs2" class="form-control" placeholder="Address Line 2 (Optional)" >
               </div>
               
               <div class="col-lg-4 mb-2">
	               <input type="text" name="p_state" id="p_state" class="form-control" placeholder="State" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="p_dist" id="p_dist" class="form-control" placeholder="District" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="p_pin" id="p_pin"  onkeypress="return isNumber(event);" class="form-control" placeholder="PIN Code" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">HR Details</p>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="date" name="doj" placeholder="DOJ" class="form-control" required>
               </div>
               <div class="col-lg-3 mb-2">
	               <select name="dept" class="form-control" required>
	               	<option value="">Department</option>
	               	<?php
	               	$sql = $con->query("select * from department order by name asc");
	               	while($row = $sql->fetch_assoc()){
	               		?>
	               		<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
	               		<?php
	               	}
	               	?>
	               </select>
               </div>
               <div class="col-lg-3 mb-2">
	               <select name="desig" class="form-control" required>
	               	<option value="">Designation</option>
	               	<?php
	               	$sql = $con->query("select * from designation order by name asc");
	               	while($row = $sql->fetch_assoc()){
	               		?>
	               		<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
	               		<?php
	               	}
	               	?>
	               </select>
               </div>
               <div class="col-lg-3 mb-2">
	               <select class="form-control" name="emp_type" required>
	               	<option value="">Emp. Type</option>
	               	<option value="0">Full Time</option>
	               	<option value="1">Part Time</option>
	               	<option value="2">Contractual</option>
	               </select>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="text" name="gross" onkeypress="return isNumber(event);" class="form-control" placeholder="Gross Salary" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <select class="form-control" name="next" required>
	               	<option value="">Next Salary Review</option>
	               	<option value="3">3 Months</option>
	               	<option value="6">6 Months</option>
	               	<option value="12">1 Year</option>
	               </select>
               </div>
               <div class="col-lg-5 mb-2 mt-1">
	               <input type="checkbox" name="pt" id="pt" value="1"> <label for="pt">PT</label>&nbsp;&nbsp;
	               <input type="checkbox" name="pf" id="pf" value="1"> <label for="pf">PF</label>&nbsp;&nbsp;
	               <input type="checkbox" name="vpf" id="vpf" value="1"> <label for="vpf">VPF</label>&nbsp;&nbsp;
	               <input type="checkbox" name="esi" id="esi" value="1"> <label for="esi">ESI</label>
               </div>
               <div class="col-lg-5 mb-2">
	               <input type="text" name="l_comp" class="form-control" placeholder="Last Company" required>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="text" name="l_salary" onkeypress="return isNumber(event);" class="form-control" placeholder="Last Salary" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="edu" class="form-control" placeholder="Educational Qualification" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Bank Details</p>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="pan" onkeypress="return isNumber(event);" class="form-control" placeholder="PAN No." required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="aadhaar" onkeypress="return isNumber(event);" class="form-control" placeholder="Aadhaar No." required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="uan" onkeypress="return isNumber(event);" class="form-control" placeholder="UAN No. (Optional)" >
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="bank" class="form-control text-uppercase" placeholder="Bank Name" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="account" onkeypress="return isNumber(event);" class="form-control" placeholder="Account No." required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="ifsc" class="form-control text-uppercase" placeholder="IFSC CODE" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Documents</p>
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Photo</label>
               	<label class="file_choose" for="e_photo">Choose File</label>
               	<input type="file" name="photo" class="form-control d-none" id="e_photo" required>
               </div>
               <div class="col-lg-4 mb-2">
               	<label>PAN</label>
               	<label class="file_choose" for="sasa">Choose File</label>
               	<input type="file" name="pan_doc" class="form-control d-none" id="sasa" required>
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Aadhaar</label>
               	<label class="file_choose" for="vfd">Choose File</label>
               	<input type="file" name="aadhaar_doc" class="form-control d-none" id="vfd" required>
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Educational Doc</label>
               	<label class="file_choose" for="asv">Choose File</label>
               	<input type="file" name="edu_doc[]" class="form-control d-none" id="asv" multiple required>
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Experience Doc</label>
               	<label class="file_choose" for="dfe">Choose File</label>
               	<input type="file" name="exp_doc[]" class="form-control d-none" id="dfe" multiple required>
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Bank Passbook</label>
               	<label class="file_choose" for="cdd">Choose File</label>
               	<input type="file" name="passbook" class="form-control d-none" id="cdd" required>
               </div>
               <div class="col-lg-12 mb-2">
               	<button class="btn bg-gradient-warning ms-auto mb-0 js-btn-next" name="submit" >Submit</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>