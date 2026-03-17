<?php
include("db.php");
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
		move_uploaded_file($photo_file,"../emp_doc/".$photo_name);
		move_uploaded_file($pan_file,"../emp_doc/".$pan_file_name);
		move_uploaded_file($aadhaar_file,"../emp_doc/".$aadhaar_file_name);
		move_uploaded_file($passbook_file,"../emp_doc/".$passbook_file_name);

		
		$mi = new MultipleIterator();
		$mi->attachIterator(new ArrayIterator($edu));
		$mi->attachIterator(new ArrayIterator($edu_name));
		$mi->attachIterator(new ArrayIterator($exp));
		$mi->attachIterator(new ArrayIterator($exp_name));
		foreach($mi as $value){
			list($edu,$edu_name,$exp,$exp_name) = $value;
			move_uploaded_file($edu,"../emp_doc/".$edu_name);
			move_uploaded_file($exp,"../emp_doc/".$exp_name);
		}
		setcookie("msg","New Employee Added",time()+1);
		// echo"<script>window.location='dashboard.php?src=employee.php';</script>";
		echo 1;
	}else{
		// echo"<script>alert('Server Error!!');window.location='dashboard.php?src=employee.php';</script>";
		echo 0;
	}
	
}
if(isset($_GET['delete'])){
	if($con->query("delete from employee where id='".$_GET['id']."'") === true){
		// setcookie("msg","Deleted",time()+1);
		// echo"<script>window.location='dashboard.php?src=employee.php';</script>";
		echo 1;
	}
}
?>