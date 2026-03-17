<?php
include("../db.php");
$id = $_GET['id'];
$sql = $con->query("select * from leave_record where id='$id'");
if($row = $sql->fetch_assoc()){
	$sql1 = $con->query("select * from employee where id='".$row['emp_id']."'");
	if($row1 = $sql1->fetch_assoc()){
		$employee_name = $row1['name'];
	}

	?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th>Employee Name: </th>
					<td><?php echo $employee_name ?></td>
				</tr>
				<tr>
					<th>From Date: </th>
					<td><?php echo $row['f_date'] ?> (<?php if($row['f_type'] == 0){echo"FH";}if($row['f_type'] == 1){echo"SH";} ?>)</td>
				</tr>
				<tr>
					<th>To Date: </th>
					<td><?php echo $row['t_date'] ?> (<?php if($row['t_type'] == 0){echo"FH";}if($row['t_type'] == 1){echo"SH";} ?>)</td>
				</tr>
			</tbody>
		</table>
	</div>
	<strong>Reason:</strong><br>
	<small><?php echo $row['reason'] ?></small>
	<hr>
	<a href="dashboard.php?src=leave.php&id=<?php echo $row['id'] ?>&status=1" class="btn btn-sm btn-success">Approve</a>
	<a href="dashboard.php?src=leave.php&id=<?php echo $row['id'] ?>&status=2" class="btn btn-sm btn-primary" onclick="return dlt()">Reject</a>
	<?php
}
?>