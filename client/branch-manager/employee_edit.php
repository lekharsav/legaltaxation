<?php

if(isset($_POST['submit'])){
	$id = $_POST['id'];

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

	$q = "update employee set
		branch='".$_POST['branch']."',
		work_under='".$_POST['work_under']."',
		person='".$_POST['person']."',
		prefix='".$_POST['prefix']."',
		name='".$_POST['name']."',
		dob='".$_POST['dob']."',
		gender='".$_POST['gender']."',
		cont='".$_POST['cont']."',
		alt_cont='".$_POST['alt_cont']."',
		email='".$_POST['email']."',
		adrs1='".$_POST['adrs1']."',
		adrs2='".$_POST['adrs2']."',
		state='".$_POST['state']."',
		dist='".$_POST['dist']."',
		pin='".$_POST['pin']."',
		p_adrs1='".$_POST['p_adrs1']."',
		p_adrs2='".$_POST['p_adrs2']."',
		p_state='".$_POST['p_state']."',
		p_dist='".$_POST['p_dist']."',
		p_pin='".$_POST['p_pin']."',
		doj='".$_POST['doj']."',
		dept='".$_POST['dept']."',
		desig='".$_POST['desig']."',
		emp_type='".$_POST['emp_type']."',
		gross='".$_POST['gross']."',
		next_s='".$_POST['next']."',
		pt='".$_POST['pt']."',
		pf='".$_POST['pf']."',
		vpf='".$_POST['vpf']."',
		esi='".$_POST['esi']."',
		l_comp='".$_POST['l_comp']."',
		l_salary='".$_POST['l_salary']."',
		edu='".$_POST['edu']."',
		pan='".$_POST['pan']."',
		aadhaar='".$_POST['aadhaar']."',
		uan='".$_POST['uan']."',
		bank='".$_POST['bank']."',
		account='".$_POST['account']."',
		ifsc='".$_POST['ifsc']."' where id='$id'";
	if($con->query($q) === true){
		if($photo_name){
			// echo"<script>alert('Photo');</script>";
			$con->query("update employee set photo='$photo_name' where id='$id'");
			move_uploaded_file($photo,"emp_doc/".$photo_name);
		}
		if($pan_file_name){
			$con->query("update employee set pan_doc='$pan_file_name' where id='$id'");
			move_uploaded_file($pan_file,"emp_doc/".$pan_file_name);
		}
		if($aadhaar_file_name){
			$con->query("update employee set aadhaar_doc='$aadhaar_file_name' where id='$id'");
			move_uploaded_file($aadhaar_file,"emp_doc/".$aadhaar_file_name);
		}
		if($passbook_file_name){
			$con->query("update employee set passbook='$passbook_file_name' where id='$id'");
			move_uploaded_file($passbook_file,"emp_doc/".$passbook_file_name);
		}
		
		$mi = new MultipleIterator();
		if($edu_imp){
			$mi->attachIterator(new ArrayIterator($edu));
			$mi->attachIterator(new ArrayIterator($edu_name));
			foreach($mi as $value){
				list($edu,$edu_name) = $value;
				move_uploaded_file($edu,"emp_doc/".$edu_name);
			}
			$con->query("update employee set edu_doc='$edu_imp' where id='$id'");
		}
		if($exp_imp){
			$mi->attachIterator(new ArrayIterator($exp));
			$mi->attachIterator(new ArrayIterator($exp_name));
			foreach($mi as $value){
				list($exp,$exp_name) = $value;
				move_uploaded_file($exp,"emp_doc/".$exp_name);
			}
			$con->query("update employee set exp_doc='$exp_imp' where id='$id'");
		}
		setcookie("msg","Saved..",time()+1);
		echo"<script>window.location='dashboard.php?src=employee_edit.php&id=$id';</script>";
	}else{
		echo"<script>alert('Server Error!!');window.location='dashboard.php?src=employee.php';</script>";
	}
}

$id = $_GET['id'];
$qry = $con->query("select * from employee where id='$id'");
$rws = $qry->fetch_assoc();
?>
<div class="row">
	<div class="col-lg-6">
      <div class="card">
         <div class="card-body">
         	
         	<div class="row">
         		<div class="col-lg-8">
         			<p class="text-sm mb-2 text-capitalize font-weight-bold">Employee Details</p>
         		</div>
         		<div class="col-lg-4 text-end">
         			<a href="dashboard.php?src=employee.php" class="text-blue ">Add New +</a>

         		</div>
         	</div>
         	<hr>
            <form class="row" method="post" enctype="multipart/form-data">
               <div class="col-lg-4 mb-2">
	               <label>Branch </label>
	               <select name="branch" class="form-control" required>
	               	<option value="">Choose Branch</option>
	               	<?php
	               	$sql = $con->query("select * from branch order by name asc");
	               	while($row = $sql->fetch_assoc()){
	               		?>
	               		<option value="<?php echo $row['id'] ?>" <?php if($rws['branch'] == $row['id']){echo"selected";}?>><?php echo $row['name'] ?></option>
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
	               		<option value="<?php echo $row['id'] ?>" <?php if($rws['work_under'] == $row['id']){echo"selected";}?>><?php echo $row['name'] ?></option>
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
	               		<option value="<?php echo $row['id'] ?>" <?php if($rws['person'] == $row['id']){echo"selected";}?>><?php echo $row['name'] ?></option>
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
	               	<option value="Mr" <?php if($rws['prefix'] == "Mr"){echo"selected";}?>>Mr</option>
	               	<option value="Ms" <?php if($rws['prefix'] == "Ms"){echo"selected";}?>>Ms</option>
	               	<option value="Mrs" <?php if($rws['prefix'] == "Mrs"){echo"selected";}?>>Mrs</option>
	               </select>
               </div>
               <div class="col-lg-5 mb-2">
	               <input type="text" name="name" value="<?php echo $rws['name'] ?>" class="form-control" placeholder="Employee Name" required>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="date"  placeholder="DOB" value="<?php echo $rws['dob'] ?>" name="dob" class="form-control" required>
               </div>
               <div class="col-lg-2 mb-2">
	               <select name="gender" class="form-control" required>
	               	<!-- <option value="">Gender</option> -->
	               	<option value="MALE" <?php if($rws['gender'] == "MALE"){echo"selected";}?>>Male</option>
	               	<option value="FEMALE" <?php if($rws['gender'] == "FEMALE"){echo"selected";}?>>Female</option>
	               </select>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="text" name="cont" value="<?php echo $rws['cont'] ?>" class="form-control" onkeypress="return isNumber(event);" maxlength="10" placeholder="Contact Number" required>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="text" name="alt_cont" value="<?php echo $rws['alt_cont'] ?>" class="form-control" placeholder="Alt Contact (Optional)" onkeypress="return isNumber(event);" maxlength="10" >
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="email" name="email" value="<?php echo $rws['email'] ?>" class="form-control" placeholder="Email Address" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Present Address</p>
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="text" name="adrs1" value="<?php echo $rws['adrs1'] ?>" id="adrs1" class="form-control" placeholder="Address Line 1" required>
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="text" name="adrs2" value="<?php echo $rws['adrs2'] ?>" id="adrs2" class="form-control" placeholder="Address Line 2 (Optional)" >
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="state" value="<?php echo $rws['state'] ?>" id="state" class="form-control" placeholder="State" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="dist" value="<?php echo $rws['dist'] ?>" id="dist" class="form-control" placeholder="District" required>
               </div>
               
               <div class="col-lg-4 mb-2">
	               <input type="text" name="pin" value="<?php echo $rws['pin'] ?>" id="pin"  onkeypress="return isNumber(event);" class="form-control" placeholder="PIN Code" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Permanent Address <input type="checkbox" id="same_as"> <label for="same_as"><small class="sm_txt">Same as present address</small></label></p>
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="text" name="p_adrs1" value="<?php echo $rws['p_adrs1'] ?>" id="p_adrs1" class="form-control" placeholder="Address Line 1" required>
               </div>
               <div class="col-lg-6 mb-2">
	               <input type="text" name="p_adrs2" value="<?php echo $rws['p_adrs2'] ?>" id="p_adrs2" class="form-control" placeholder="Address Line 2 (Optional)" >
               </div>
               
               <div class="col-lg-4 mb-2">
	               <input type="text" name="p_state" value="<?php echo $rws['p_state'] ?>" id="p_state" class="form-control" placeholder="State" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="p_dist" value="<?php echo $rws['p_dist'] ?>" id="p_dist" class="form-control" placeholder="District" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="p_pin" value="<?php echo $rws['p_pin'] ?>" id="p_pin"  onkeypress="return isNumber(event);" class="form-control" placeholder="PIN Code" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">HR Details</p>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="date" name="doj" value="<?php echo $rws['doj'] ?>" placeholder="DOJ" class="form-control" required>
               </div>
               <div class="col-lg-3 mb-2">
	               <select name="dept" class="form-control" required>
	               	<option value="">Department</option>
	               	<?php
	               	$sql = $con->query("select * from department order by name asc");
	               	while($row = $sql->fetch_assoc()){
	               		?>
	               		<option value="<?php echo $row['id'] ?>" <?php if($rws['dept'] == $row['id']){echo"selected";}?>><?php echo $rws['name'] ?></option>
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
	               		<option value="<?php echo $row['id'] ?>" <?php if($rws['desig'] == $row['id']){echo"selected";}?>><?php echo $rws['name'] ?></option>
	               		<?php
	               	}
	               	?>
	               </select>
               </div>
               <div class="col-lg-3 mb-2">
	               <select class="form-control" name="emp_type" required>
	               	<option value="">Emp. Type</option>
	               	<option value="0" <?php if($rws['emp_type'] == "0"){echo"selected";}?>>Full Time</option>
	               	<option value="1" <?php if($rws['emp_type'] == "1"){echo"selected";}?>>Part Time</option>
	               	<option value="2" <?php if($rws['emp_type'] == "2"){echo"selected";}?>>Contractual</option>
	               </select>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="text" name="gross" value="<?php echo $rws['gross'] ?>" onkeypress="return isNumber(event);" class="form-control" placeholder="Gross Salary" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <select class="form-control" name="next" required>
	               	<option value="">Next Salary Review</option>
	               	<option value="3" <?php if($rws['next_s'] == "3"){echo"selected";}?>>3 Months</option>
	               	<option value="6" <?php if($rws['next_s'] == "6"){echo"selected";}?>>6 Months</option>
	               	<option value="12" <?php if($rws['next_s'] == "12"){echo"selected";}?>>1 Year</option>
	               </select>
               </div>
               <div class="col-lg-5 mb-2 mt-1">
	               <input type="checkbox" name="pt" id="pt" value="1" <?php if($rws['pf'] == 1){echo "checked";}?>> <label for="pt">PT</label>&nbsp;&nbsp;
	               <input type="checkbox" name="pf" id="pf" value="1" <?php if($rws['pt'] == 1){echo "checked";}?>> <label for="pf">PF</label>&nbsp;&nbsp;
	               <input type="checkbox" name="vpf" id="vpf" value="1" <?php if($rws['vpf'] == 1){echo "checked";}?>> <label for="vpf">VPF</label>&nbsp;&nbsp;
	               <input type="checkbox" name="esi" id="esi" value="1" <?php if($rws['esi'] == 1){echo "checked";}?>> <label for="esi">ESI</label>
               </div>
               <div class="col-lg-5 mb-2">
	               <input type="text" name="l_comp" value="<?php echo $rws['l_comp'] ?>" class="form-control" placeholder="Last Company" required>
               </div>
               <div class="col-lg-3 mb-2">
	               <input type="text" name="l_salary" value="<?php echo $rws['next_s'] ?>" onkeypress="return isNumber(event);" class="form-control" placeholder="Last Salary" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="edu" value="<?php echo $rws['edu'] ?>" class="form-control" placeholder="Educational Qualification" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Bank Details</p>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="pan" value="<?php echo $rws['pan'] ?>" onkeypress="return isNumber(event);" class="form-control" placeholder="PAN No." required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="aadhaar" value="<?php echo $rws['aadhaar'] ?>" onkeypress="return isNumber(event);" class="form-control" placeholder="Aadhaar No." required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="uan" value="<?php echo $rws['uan'] ?>" onkeypress="return isNumber(event);" class="form-control" placeholder="UAN No. (Optional)" >
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="bank" value="<?php echo $rws['bank'] ?>" class="form-control text-uppercase" placeholder="Bank Name" required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="account" value="<?php echo $rws['account'] ?>" onkeypress="return isNumber(event);" class="form-control" placeholder="Account No." required>
               </div>
               <div class="col-lg-4 mb-2">
	               <input type="text" name="ifsc" value="<?php echo $rws['ifsc'] ?>" class="form-control text-uppercase" placeholder="IFSC CODE" required>
               </div>
               <div class="col-lg-12">
               	<p class="text-sm mb-2 text-capitalize font-weight-bold">Documents</p>
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Photo</label>
               	<label class="file_choose" for="e_photo">Choose File</label>
               	<input type="file" name="photo" class="form-control d-none" id="e_photo" >
               </div>
               <div class="col-lg-4 mb-2">
               	<label>PAN</label>
               	<label class="file_choose" for="sasa">Choose File</label>
               	<input type="file" name="pan_doc" class="form-control d-none" id="sasa" >
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Aadhaar</label>
               	<label class="file_choose" for="vfd">Choose File</label>
               	<input type="file" name="aadhaar_doc" class="form-control d-none" id="vfd" >
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Educational Doc</label>
               	<label class="file_choose" for="asv">Choose File</label>
               	<input type="file" name="edu_doc[]" class="form-control d-none" id="asv" multiple >
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Experience Doc</label>
               	<label class="file_choose" for="dfe">Choose File</label>
               	<input type="file" name="exp_doc[]" class="form-control d-none" id="dfe" multiple >
               </div>
               <div class="col-lg-4 mb-2">
               	<label>Bank Passbook</label>
               	<label class="file_choose" for="cdd">Choose File</label>
               	<input type="file" name="passbook" class="form-control d-none" id="cdd" >
               </div>
               <div class="col-lg-12 mb-2">
               	<input type="hidden" name="id" value="<?php echo $id ?>">
               	<button class="btn bg-gradient-warning ms-auto mb-0 js-btn-next" name="submit" >Save Changes</button>
               </div>
            </form>
         </div>
      </div>
   	</div>
   	<div class="col-lg-6">
   		<div class="row">
   			<div class="col-lg-6">
   				<img src="emp_doc/<?php echo $rws['photo'] ?>" class="w-100">
   			</div>
   			<div class="col-lg-6">
   				<p class="text-sm mb-2 text-capitalize font-weight-bold">General Documents</p>
   				<hr>
   				<a href="" class="badge badge-sm bg-warning" target="blank">View PAN Card</a>
   				<a href="" class="badge badge-sm bg-warning" target="blank">View Aadhaar Card</a>
   				<a href="" class="badge badge-sm bg-warning" target="blank">View Passbook</a>
   				<hr>
   				<p class="text-sm mb-2 text-capitalize font-weight-bold">Educational Documents</p>
   				<hr>
   				<?php
   				$educ = explode(",",$rws['edu_doc']);
   				foreach($educ as $edu){
   					echo"<a href='".$edu."' class='badge badge-sm bg-warning'>".$edu."</a>&nbsp;&nbsp;";
   				}
   				?>
   				<hr>
   				<p class="text-sm mb-2 text-capitalize font-weight-bold">Experience Documents</p>
   				<hr>
   				<?php
   				$expc = explode(",",$rws['edu_doc']);
   				foreach($expc as $exp){
   					echo"<a href='".$exp."' class='badge badge-sm bg-warning'>".$exp."</a>&nbsp;&nbsp;";
   				}
   				?>
   			</div>
   		</div>
   	</div>
</div>