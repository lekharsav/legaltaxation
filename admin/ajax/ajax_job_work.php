<?php
include("../db.php");

$qid = $_GET['qid'];
$cid = $_GET['cid'];
$sql = $con->query("select * from client where id='$cid'");
$row = $sql->fetch_assoc();

$sqll = $con->query("select * from quote where quote_id='$qid'");
if($roww = $sqll->fetch_assoc()){
	$pid = explode(",",$roww['product']);
}

foreach($pid as $p){
	$sql1 = $con->query("select * from product where id='$p'");
	if($row1 = $sql1->fetch_assoc()){
		$p_name[] = $row1['name'];
	}

}
?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Company Name</th>
				<th>Executive Name</th>
				<th>Branch</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $row['company'] ?></td>
				<td>
					<?php
                    if($row['uploaded_type'] == "admin"){
                       $sql3 = $con->query("select * from admin where id='".$row['uploaded_by']."'");
                       if($row3 = $sql3->fetch_assoc()){
                          echo $row3['name'];
                       }
                    }else{
                       $sql3 = $con->query("select * from employee where id='".$row['uploaded_by']."'");
                       if($row3 = $sql3->fetch_assoc()){
                          echo $row3['name'];
                       }
                    }
                    ?>
				</td>
				<td>
					<?php
					$sql2 = $con->query("select * from branch where id='".$row['branch']."'");
					if($row2 = $sql2->fetch_assoc()){
						echo $row2['name'];
					}
					?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-lg-12 mb-2">
		<div class="container-fluid">
		<?php
		$pid = explode(",",$roww['product']);
		foreach($pid as $p){
			$type = "";
			// echo "select * from product where id='$p' and category between '1' and '2'";
			$sql1 = $con->query("select * from product where id='$p' and category between '1' and '2'");
			if($row1 = $sql1->fetch_assoc()){
				?>
				<input type="radio" name="product" value="<?php echo $row1['id'] ?>" id="prod_<?=$row1['id']?>" onclick="job_work_form('job_work_sec_<?=$p?>')">
				<label for="prod_<?=$row1['id']?>"><a href="javascript:;"><?php echo $row1['name'] ?></a></label>
				<?php
			}else{
				echo"No Product Available";
			}

		}
		echo"<hr>";
		foreach($pid as $p){
			$type = "";
			$sql1 = $con->query("select * from product where id='$p' and category between '1' and '2'");
			if($row1 = $sql1->fetch_assoc()){
				if($row1['category'] == 1){
					$type = "development";
				}else{
					$type = "marketing";
				}

				if($type == "development"){
				?>
				<div class="jobs_sec job_work_sec_<?=$p?> d-none">
					<div class="row">
						<div class="col-lg-2 mb-2">
							<label>Product Type</label>
							<input type="text" value="Development" class="form-control" readonly>
						</div>
						<div class="col-lg-3 mb-2">
							<label>Job Type</label>
							<select name="job_type" class="form-control" >
								<option value="">Choose Option</option>
								<option value="0">New Requirement</option>
								<option value="1">Free Changes</option>
								<option value="2">Customization / Addon Features</option>
							</select>
						</div>
						<div class="col-lg-2 mb-2">
							<label>Payment Type</label>
							<select class="form-control" onchange="paymentMode();" id="p_mode" name="p_mode">
					        <option value="">Choose Option</option>
					        <option value="0">Cash</option>
					        <option value="1">Online</option>
					        <option value="2">N/A</option>
					     </select>
						</div>
						<div class="col-lg-2 mb-2 d-none" id="tnx_sec">
					      <label>Transaction ID</label>
					     <input type="text" name="txn_id" class="form-control" placeholder="Transaction ID">
					  	</div>
						<div class="col-lg-2 mb-2">
							<label>Amount</label>
							<input type="text" name="amount" placeholder="Charges" class="form-control" >
						</div>
						<div class="col-lg-2 mb-2">
							<label>Ticket ID</label>
							<input type="text" name="ticket" value="<?php echo date('dmy',strtotime($current_date))."-".rand(1000,9999) ?>" class="form-control" readonly>
						</div>
						<div class="col-lg-2 mb-2">
							<label>Date</label>
							<input type="date" name="date" value="<?php echo date('Y-m-d',strtotime($current_date)) ?>" class="form-control" readonly>
						</div>
						<div class="col-lg-6 mb-2">
							<label>Project Title</label>
							<input type="text" name="title" value="<?php echo implode($p_name) ?>" placeholder="Enter Project Title" class="form-control" >
						</div>
						<!-- <div class="col-lg-2 mb-2">
							<label>Attach File</label>
							<input type="file" name="file[]" class="form-control" >
						</div> -->
						<div class="col-lg-12 mb-2">
							<label>Project Brief</label>
							<textarea name="brief" class="form-control" placeholder="Write Project Brief" rows="5" ></textarea>
						</div>
					</div>
					<input type="hidden" name="qid" value="<?php echo $qid ?>">
					<input type="hidden" name="cid" value="<?php echo $cid ?>">
					<hr>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive" id="development_job_work_list">
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
										$sql = $con->query("select * from job_work where qid='$qid' and product='".$p."'");
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
							</div>
						</div>
					</div>
				</div>
				<?php
				}if($type == "marketing"){
				?>
				<div class="jobs_sec job_work_sec_<?=$p?> d-none">
					<div class="row">
						<div class="col-lg-2 mb-2">
							<label>Product Type</label>
							<input type="text" value="Marketing" class="form-control" readonly>
						</div>
						<div class="col-lg-2 mb-2">
							<label>Start Date</label>
							<input type="date" name="s_date" class="form-control" >
						</div>
						<div class="col-lg-2 mb-2">
							<label>End Date</label>
							<input type="date" name="e_date" class="form-control" >
						</div>
						<div class="col-lg-2 mb-2">
							<label>Schedule</label>
							<input type="text" name="schedule" class="form-control" placeholder="Campaign Schedule">
						</div>
						<div class="col-lg-2 mb-2">
							<label>Daily Budget</label>
							<input type="text" name="daily_budget" class="form-control" placeholder="Daily Budget" >
						</div>
						<div class="col-lg-3 mb-2">
							<label>Job Type</label>
							<select name="job_type" class="form-control" >
								<option value="">Choose Option</option>
								<option value="0">New Requirement</option>
								<option value="1">Free Changes</option>
								<option value="2">Customization / Addon Features</option>
							</select>
						</div>
						<div class="col-lg-2 mb-2">
							<label>Payment Type</label>
							<select class="form-control" onchange="paymentMode();" id="p_mode" name="p_mode" >
					        <option value="">Choose Option</option>
					        <option value="0">Cash</option>
					        <option value="1">Online</option>
					        <option value="2">N/A</option>
					     </select>
						</div>
						<div class="col-lg-2 mb-2 d-none" id="tnx_sec">
					      <label>Transaction ID</label>
					     <input type="text" name="txn_id" class="form-control" placeholder="Transaction ID">
					  	</div>
						<div class="col-lg-2 mb-2">
							<label>Amount</label>
							<input type="text" name="amount" placeholder="Charges" class="form-control" >
						</div>
						<div class="col-lg-2 mb-2">
							<label>Ticket ID</label>
							<input type="text" name="ticket" value="<?php echo date('dmy',strtotime($current_date))."-".rand(1000,9999) ?>" class="form-control" readonly>
						</div>
						<div class="col-lg-2 mb-2">
							<label>Date</label>
							<input type="date" name="date" value="<?php echo date('Y-m-d',strtotime($current_date)) ?>" class="form-control" readonly>
						</div>
						<div class="col-lg-6 mb-2">
							<label>Project Title</label>
							<input type="text" name="title" value="<?php echo $row1['name'] ?>" placeholder="Enter Project Title" class="form-control" >
						</div>
						<!-- <div class="col-lg-2 mb-2">
							<label>Attach File</label>
							<input type="file" name="file[]" class="form-control" >
						</div> -->
						<div class="col-lg-12 mb-2">
							<label>Project Brief</label>
							<textarea name="brief" class="form-control" placeholder="Write Project Brief" rows="5" ></textarea>
						</div>
					</div>
					<input type="hidden" name="qid" value="<?php echo $qid ?>">
					<input type="hidden" name="cid" value="<?php echo $cid ?>">
					<hr>
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive" id="marketing_job_work_list">
								<table class="table table-striped">
									<thead>
										<tr>
											<th>SL</th>
											<th>Date</th>
											<th>Product</th>
											<th>Start Date</th>
											<th>End Date</th>
											<th>Schedule</th>
											<th>Daily Budget</th>
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
										$sql = $con->query("select * from job_work where qid='$qid' and product='".$p."'");
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
												<td><?php echo date('d-M-Y',strtotime($row['s_date'])) ?></td>
												<td><?php echo date('d-M-Y',strtotime($row['e_date'])) ?></td>
												<td><?php echo $row['schedule'] ?></td>
												<td><?php echo $row['daily_budget']; ?></td>
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
							</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
				
				<?php
			}

		}
		?>
		</div>
	</div>
</div>