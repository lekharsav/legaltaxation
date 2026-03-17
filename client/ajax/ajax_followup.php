<?php
include('../db.php');
$id = $_GET['id'];
$sql = $con->query("select * from client where id='$id'");
if ($row = $sql->fetch_assoc()) {
?>
	<form method="post" action="ajax/ajax_followup_update.php" id="ajax_followup_form">
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Client ID</th>
						<th>Company Name</th>
						<th>Contact Person</th>
						<th>Contact Number</th>
						<th>Alt Contact No.+</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><a href="dashboard.php?src=client_details.php&id=<?php echo $row['id'] ?>"><?php echo $row['cid'] ?></a></td>
						<td><?php echo $row['company'] ?></td>
						<td><?php echo $row['person'] ?></td>
						<td><?php echo $row['contact'] ?></td>
						<td><?php echo $row['alt_contact'] ?></td>

					</tr>
					<tr>
						<td colspan="3">
							<?php
							$p = explode(",", $row['product']);
							foreach ($p as $pid) {
								$sql2 = $con->query("select * from product where id='$pid'");
								if ($row2 = $sql2->fetch_assoc()) {
									echo "<span class='badge bg-success mt-1'>" . $row2['name'] . "</span>&nbsp;";
								}
							}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-lg-12 mb-2">

			</div>
			<div class="col-lg-4 mb-2">
				<!-- <label>Status</label> -->
				<select name="status" class="form-control" required id="statusSelect" onchange="toggleFields()">
					<option value="0" <?php if ($row['status'] == "0") {
											echo "selected";
										} ?>>Attended</option>
					<option value="1" <?php if ($row['status'] == "1") {
											echo "selected";
										} ?>>Not Interested</option>
					<option value="3" <?php if ($row['status'] == "3") {
											echo "selected";
										} ?>>Quotation Sent</option>
					<option value="2" <?php if ($row['status'] == "2") {
											echo "selected";
										} ?>>Persuing to Purchase</option>
					<!-- <option value="4">Order Closed</option> -->
				</select>
			</div>
			<div class="col-lg-4 mb-2" id="nextFollowupField">
				<!-- <label>Next Followup Date</label> -->
				<input type="date" name="next_f" value="<?php echo $row['next_followup'] ?>" class="form-control" placeholder="Next Followup" required>
			</div>
			<div class="col-lg-4 mb-2" id="remarksField">
				<textarea name="remarks" class="form-control" placeholder="Enter Remarks"><?php echo $row['remarks'] ?></textarea>
			</div>

		</div>
		<input type="hidden" name="id" value="<?php echo $row['id'] ?>">
		<input type="hidden" name="cid" value="<?php echo $row['cid'] ?>">
		<input type="hidden" name="exe" value="<?php echo $row['uploaded_by'] ?>">
		<button class="btn btn-sm btn-primary" type="submit">UPDATE DETAILS</button>
	</form>


	<script>
		document.getElementById('ajax_followup_form').onsubmit = function(event) {
			event.preventDefault(); // Prevent the default form submission

			var formData = new FormData(this); // Collect the form data

			// Debugging: Log form data to console
			formData.forEach(function(value, key) {
				console.log(key + ": " + value);
			});

			// Send the form data via fetch (AJAX)
			fetch('ajax/ajax_followup_update.php', {
					method: 'POST',
					body: formData
				})
				.then(response => response.text())
				.then(result => {
					console.log(result); // Log server response
					alert('Form submitted successfully!');
				})
				.catch(error => {
					console.error('Error:', error); // Handle errors
					alert('An error occurred.');
				});
		};
	</script>




	<hr>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>SL</th>
					<th>Followup Date</th>
					<th>Status</th>
					<th>Next Followup</th>
					<th>Remarks</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sl = 1;
				$sql = $con->query("select * from followup_history where cid='" . $row['cid'] . "' or cid='$id'");
				while ($row = $sql->fetch_assoc()) {
				?>
					<tr>
						<td><?php echo $sl ?></td>
						<td><?php echo date('d-M-y h:i A', strtotime($row['created'])) ?></td>
						<td>
							<?php
							if ($row['status'] == 0) {
								echo "Attended";
							}
							if ($row['status'] == 1) {
								echo "Not Interested";
							}
							if ($row['status'] == 2) {
								echo "Pursuing to Purchase";
							}
							if ($row['status'] == 3) {
								echo "Quotation Sent";
							}
							if ($row['status'] == 4) {
								echo "Order Closed";
							}
							?>
						</td>
						<td><?php echo date('d-M-y', strtotime($row['next_f'])) ?></td>
						<td><?php echo $row['remarks'] ?></td>
					</tr>
				<?php
					$sl++;
				}
				?>
			</tbody>
		</table>
	</div>

	<hr>


	<!-- Checkbox to toggle form visibility -->
	<div style=" padding-bottom: 10px;">
		<input type="checkbox" id="toggleAppoinmentForm" onclick="toggleAppoinmentFormVisibility()"> Show Sales Appointment Form
	</div>

<?php
}
?>
<?php
$sql = $con->query("select * from client where id='$id'");
if ($row = $sql->fetch_assoc()) {
?>
	<!-- Form to be toggled -->
	<div id="appoinmentFormContainer" style="display: none;">

		<h5>Sales Appointment</h5>

		<form method="post" action="ajax/ajax_appoinment_update.php" id="ajax_appoinment_form">
			<div class="row">
				<div class="col-lg-12 mb-1">
				</div>
				<div class="col-lg-3 mb-2">
					<label for="date">Select a Date:</label>
					<input type="date" id="appointment_date" name="appointment_date" required><br><br>
				</div>
				<div class="col-lg-2 mb-2">
					<label for="time">Select a Time:</label>
					<input type="time" id="appointment_time" name="appointment_time" required><br><br>
				</div>
				<div class="col-lg-3 mb-2 me-4">
					<label for="location">Location</label>
					<input type="text" id="location" name="location" required><br><br>
				</div>
				<div class="col-lg-3 mb-2 ms-2 mt-2">
					<textarea name="remarks" class="form-control" placeholder="Describe Apportionment"></textarea>
				</div>
			</div>
			<input type="hidden" name="id" value="<?php echo $row['id'] ?>">
			<input type="hidden" name="cid" value="<?php echo $row['cid'] ?>">
			<input type="hidden" name="eid" value="<?php echo $row['uploaded_by'] ?>">
			<button class="btn btn-sm btn-primary" type="submit">APPOINMENT UPDATE</button>
		</form>

	</div>

<?php
}
?>




<script>
	function toggleAppoinmentFormVisibility() {
		var checkbox = document.getElementById('toggleAppoinmentForm');
		var formContainer = document.getElementById('appoinmentFormContainer');
		formContainer.style.display = checkbox.checked ? 'block' : 'none';
	}
</script>

<script>
	document.getElementById('ajax_appoinment_form').onsubmit = function(event) {
		event.preventDefault(); // Prevent the default form submission

		var formData = new FormData(this); // Collect the form data

		// Debugging: Log form data to console
		formData.forEach(function(value, key) {
			console.log(key + ": " + value);
		});

		// You can also alert the form data one by one if needed
		let formDataString = "";
		formData.forEach(function(value, key) {
			formDataString += key + ": " + value + "\n";
		});
		//alert(formDataString); // Show form data in an alert

		// Send the form data via fetch (AJAX)
		fetch('ajax/ajax_appoinment_update.php', {
				method: 'POST',
				body: formData
			})
			.then(response => response.text())
			.then(result => {
				console.log(result); // Log server response
				alert('Form submitted successfully!');
			})
			.catch(error => {
				console.error('Error:', error); // Handle errors
				alert('An error occurred.');
			});
	};
</script>