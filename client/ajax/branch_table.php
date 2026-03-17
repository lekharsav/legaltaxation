<?php
include("../db.php");
$sl=1;
$sql = $con->query("select * from branch order by id desc");
while($row = $sql->fetch_assoc()){
	?>
	<tr>
		<td><?php echo $sl ?></td>
		<td><?php echo $row['bid'] ?></td>
		<td><?php echo $row['name'] ?></td>
		<td><?php echo $row['email'] ?></td>
		<td><?php echo $row['cont'] ?></td>
		<td><?php echo $row['location'] ?></td>
		<td><?php echo $row['adrs'] ?></td>
		<td>
			<a href="dashboard.php?src=branch.php&id=<?php echo $row['id'] ?>&edit=edit" class="badge bg-success"><i class="fa fa-edit"></i></a>
			<a href="#" class="badge bg-danger" onclick="myFnc(<?php echo $row['id'] ?>,'branch_dlt')"><i class="fa fa-trash"></i></a>
		</td>

	</tr>
	<?php
	$sl++;
}
?>