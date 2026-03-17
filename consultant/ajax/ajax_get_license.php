<?php 
include("../db.php");
$id = $_GET['id'];
$sql = $con->query("select * from users where id='$id'");
if($row = $sql->fetch_assoc()){
	$sql2 = $con->query("select * from license where user='' order by id asc limit 1");
	$row2 = $sql2->fetch_assoc();
	?>
	<div class="col-lg-6 mb-3">
		<label>User Name</label>
		<input type="text" name="person" value="<?php echo $row['person'] ?>" class="form-control" readonly>
	</div>
	<div class="col-lg-6 mb-3">
		<label>License Type</label>
		<input type="text" name="type" value="<?php if($row['type'] == "0"){echo"Monthly Subscription";}else{echo"Yearly Renewal";} ?>" class="form-control" readonly required>
	</div>
	<div class="col-lg-6 mb-3">
		<label>Database</label>
		<input type="text" name="db_name" value="<?php echo $row2['db_name'] ?>" class="form-control" readonly>
	</div>
	<div class="col-lg-6 mb-3">
		<label>License</label>
		<input type="text" name="license" value="<?php echo $row2['license_key'] ?>" class="form-control" readonly>
	</div>
	<input type="hidden" name="uid" value="<?php echo $row['id'] ?>">
	<input type="hidden" name="db_user_name" value="<?php echo $row2["user_name"] ?>">
	<input type="hidden" name="pass" value="<?php echo $row2["password"] ?>">
	<?php
}
?>