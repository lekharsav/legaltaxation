<?php
include("../db.php");
$qid = $_GET['qid'];
?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>SL</th>
			<th>Date</th>
			<th>Product</th>
			<th>Job Type</th>
			<th>Ticket ID</th>
			<th>Project Title</th>
			<th>Project Brief</th>
			<th>Amount</th>
			<th>Files</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$sl = 1;
		$sql = $con->query("select * from job_work where qid='$qid'");
		while($row = $sql->fetch_assoc()){
			?>
			<tr>
				<td><?php echo $sl ?></td>
				<td><?php echo date('d-m-Y',strtotime($row['created'])) ?></td>
				<td>
					<?php
					$sql1 = $con->query("select * from product where id='".$row['product']."'");
					if($row1 = $sql1->fetch_assoc()){
						echo $row1['name'];
					}
					?>
				</td>
				<td>
					<?php
					if($row['status'] == 0){
						echo"New Requirement";
					}if($row['status'] == 1){
						echo"Free Changes";
					}if($row['status'] == 2){
						echo"Customization / Addon Features";
					}
					?>
				</td>
				<td><?php echo $row['ticket_id'] ?></td>
				<td><?php echo $row['title'] ?></td>
				<td><a href="" data-bs-toggle="modal" data-bs-target="#projectBriefModal" onclick="projectBrief('<?php echo $row['brief']?>')" class="badge bg-primary">View Brief</a></td>
				<td><?php echo $row['amount'] ?></td>
				<td>Files</td>
				<td>
					<?php
					if($row['status'] == 0){
						echo"Pending";
					}else{
						echo"Complete";
					}
					?>
				</td>
			</tr>
			<?php
			$sl++;
		}
		?>
	</tbody>
</table>
<?php
?>