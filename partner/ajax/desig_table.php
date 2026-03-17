<?php
include("../db.php");
$sl=1;
$sql = $con->query("select * from designation order by id desc");
while($row = $sql->fetch_assoc()){
	?>
	<tr>
		<td><?php echo $sl ?></td>
		<td><?php echo $row['name'] ?></td>
		<td><?php if($row['type'] == "0"){echo"Executive";}if($row['type'] == "1"){echo"Management";} ?></td>
		<td><?php if($row['project'] == "0"){echo"All";}if($row['project'] == "1"){echo"Development";}if($row['project'] == "2"){echo"Marketing";}if($row['project'] == "3"){echo"N/A";} ?></td>
		<td>
			<a href="dashboard.php?src=department.php&id=<?php echo $row['id'] ?>&edit2=edit" class="badge bg-success"><i class="fa fa-edit"></i></a>
			<a href="#" class="badge bg-danger" onclick="myFnc(<?php echo $row['id'] ?>,'desig_dlt')"><i class="fa fa-trash"></i></a>
		</td>

	</tr>
	<?php
	$sl++;
}
?>