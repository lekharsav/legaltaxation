<?php
include("../db.php");
$desig = $_GET['desig'];
$sql = $con->query("select * from employee where desig='$desig'");
while($row = $sql->fetch_assoc()){
	?>
	<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
	<?php
}
?>