<option value="">Choose Option</option>
<?php
include("../db.php");
$branch = $_GET['branch'];
if($branch == 0){
	$where = "";
}else{
	$where = "where branch='$branch'";
}
$sql = $con->query("select * from employee $where");
while($row = $sql->fetch_assoc()){
	?>
	<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
	<?php
}
?>