<?php
include("../db.php");
$id = $_GET['id'];
$sql = $con->query("select * from project_task where id='$id'");
if($row = $sql->fetch_assoc()){
	?>
	<textarea name="report" class="form-control" placeholder="Enter Your Task Report" rows="6" required><?php echo $row['report'] ?></textarea>
	<input type="checkbox" name="status" value="1" id="comp" <?php if($row['status'] == 1){echo"checked";}?>> <label for="comp">Complete</label>
	<input type="hidden" name="id" value="<?php echo $id ?>">
	<?php
}
?>