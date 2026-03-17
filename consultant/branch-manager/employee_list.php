<?php
if(isset($_GET['delete'])){
	if($con->query("delete from employee where id='".$_GET['id']."'") === true){
		setcookie("msg","Deleted",time()+1);
		echo"<script>window.location='dashboard.php?src=employee.php';</script>";
	}
}
?>
<div class="row">
   <div class="col-sm-12 mb-2">
      <nav aria-label="breadcrumb ">
         <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
               <a class="opacity-3 text-dark" href="dashboard.php">
               Dashboard
               </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">HRM</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Employee</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-lg-12">
   	<div class="table-responsive">
   		<table class="example table table-striped">
   			<thead>
   				<tr>
   					<th>SL</th>
   					<th>EMP ID</th>
   					<th>Name</th>
   					<th>Mobile No.</th>
   					<th>Email Address</th>
   					<th>Department</th>
   					<th>Designation</th>
   					<th>Status</th>
   					<th>Action</th>
   				</tr>
   			</thead>
   			<tbody>
   				<?php
   				$sl = 1;
   				$sql = $con->query("select * from employee where branch='$emp_branch' order by id asc");
   				while($row = $sql->fetch_assoc()){
   					$sql2 = $con->query("select * from department where id='".$row['dept']."'");
   					if($row2 = $sql2->fetch_assoc()){
   						$dept = $row2['name'];
   					}
   					$sql2 = $con->query("select * from designation where id='".$row['desig']."'");
   					if($row2 = $sql2->fetch_assoc()){
   						$desig = $row2['name'];
   					}
   					?>
   					<tr>
   						<td><?php echo $sl ?></td>
   						<td><?php echo $row['emp_id'] ?></td>
   						<td><?php echo $row['name'] ?></td>
   						<td><?php echo $row['cont'] ?></td>
   						<td><?php echo $row['email'] ?></td>
   						<td><?php echo $dept ?></td>
   						<td><?php echo $desig ?></td>
   						<td>
   							<?php
   							if($row['status'] >0){
   								echo"<span class='badge bg-success'>Active</span>";
   							}else{
   								echo"<span class='badge bg-warning'>Released</span>";
   							}
   							?>
   						</td>
   						<td>
   							<a href="dashboard.php?src=employee_edit.php&id=<?php echo $row['id'] ?>" class="badge bg-success"><i class="fa fa-edit"></i></a>
   							<a href="dashboard.php?src=employee.php&id=<?php echo $row['id'] ?>&delete=delete" class="badge bg-danger" onclick="return dlt();"><i class="fa fa-trash"></i></a>
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