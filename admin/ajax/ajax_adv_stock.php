<?php
include("../db.php");
$id = $_GET['id'];
$sql = $con->query("select * from stock_adv_stk_raw where id='$id'");
if($row = $sql->fetch_assoc()){
	$sql1 = $con->query("select * from vendor where id='".$row['vendor']."'");
   	if($row1 = $sql1->fetch_assoc()){
      $vendor_name = $row1['company'];
   	}
   	$sql1 = $con->query("select * from stock_adv_vnd_item where id='".$row['item']."'");
   	if($row1 = $sql1->fetch_assoc()){
      $item_name = $row1['item'];
   	}
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<!-- <th>Date</th> -->
							<th>Vendor</th>
							<th>Price</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<!-- <td><?php echo date('d-m-Y h:i a',strtotime($row['created'])) ?></td> -->
							<td><?php echo $vendor_name ?></td>
							<td><?php echo $row['price'] ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-lg-4">
			<label>Item Name</label>
			<input type="text" value="<?php echo $item_name ?>" class="form-control" readonly>
		</div>
		<div class="col-lg-2">
			<label>Weight</label>
			<input type="text" value="<?php echo $row['weight'] ?>" class="form-control" readonly>
		</div>
		<div class="col-lg-2">
			<label>Take</label>
			<input type="text" class="form-control" required>
		</div>
		<div class="col-lg-2">
			<label>Pen.</label>
			<input type="text" class="form-control" readonly>
		</div>
		<div class="col-lg-2">
			<label>Dep.</label>
			<input type="text" class="form-control" readonly>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4">
			<label>FG Product</label>
			<select name="fg" class="form-control" required>
				<option value="">Choose Option</option>
				<?php
				$sql = $con->query("select * from stock_adv_stk_item order by item asc");
				while($row = $sql->fetch_assoc()){
					?>
					<option value="<?php echo $row['id'] ?>"><?php echo $row['item'] ?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="col-lg-2">
			<label>Weight</label>
			<input type="text" name="" class="form-control" required>
		</div>
		<div class="col-lg-2">
			<label>Price</label>
			<input type="text" name="" class="form-control" required>
		</div>
		<div class="col-lg-12">
			<a href="#"><small>Add Product <i class="fa fa-plus"></i></small></a>
		</div>
	</div>
	<hr>
	<?php
}
?>
