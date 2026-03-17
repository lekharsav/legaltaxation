<?php
include("../db.php");
$sl=1;
$sql = $con->query("select * from department order by id desc");
while($row = $sql->fetch_assoc()){
	?>
	<tr>
		<td><?php echo $sl ?></td>
		<td><?php echo $row['name'] ?></td>
		<td>
			<a href="dashboard.php?src=department.php&id=<?php echo $row['id'] ?>&edit=edit" class="badge bg-success"><i class="fa fa-edit"></i></a>
			<a href="#" data-id="" class="badge bg-danger" onclick="myFnc(<?php echo $row['id'] ?>,'dept_dlt')"><i class="fa fa-trash"></i></a>
		</td>

	</tr>
	<?php
	$sl++;
}
?>