<?php
if(isset($_GET['status'])){
   $status = $_GET['status'];
   $id = $_GET['id'];
   if($con->query("update leave_record set approval_2='$status' where id='$id'") === true){
      echo "<script>window.location='dashboard.php?src=leave.php';</script>";

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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Leave</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-sm-12 mb-2">
      <div class="card">
         <div class="card-body">
            <p class="text-sm mb-2 text-capitalize font-weight-bold">Leave Appication Details</p>
            <div class="table-responsive">
               <table class="table table-striped example">
                  <thead>
                     <tr>
                        <th>SL</th>
                        <th>Apply Date</th>
                        <th>Leave Type</th>
                        <th>Employee ID</th>
                        <th>Employee</th>
                        <th>Designation</th>
                        <th>Branch</th>
                        <th>Days</th>
                        <th>Manager Approval</th>
                        <th>HR Approval</th>
                        <th>Approval</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $sl = 1;
                     $sql = $con->query("select * from leave_record order by id desc");
                     while($row = $sql->fetch_assoc()){
                        $sql1 = $con->query("select * from employee where id='".$row['emp_id']."' and branch='".$emp_branch."'");
                        if($row1 = $sql1->fetch_assoc()){
                           $emp_id = $row1['emp_id'];
                           $emp_name = $row1['name'];
                           $b = $row1['branch'];
                           $d = $row1['desig'];
                        
                        $sql2 = $con->query("select * from branch where id='$b'");
                        if($row2 = $sql2->fetch_assoc()){
                           $branch_name = $row1['name'];
                        }
                        $sql2 = $con->query("select * from designation where id='$d'");
                        if($row2 = $sql2->fetch_assoc()){
                           $designation = $row2['name'];
                        }
                        ?>
                        <tr>
                           <td><?php echo $sl ?></td>
                           <td><?php echo date('d-M-Y',strtotime($row['created'])) ?></td>
                           <td><?php if($row['type'] == 0){echo"CL";}if($row['type'] == 1){echo"SL";}if($row['type'] == 2){echo"LOP";}if($row['type'] == 3){echo"EL";} ?></td>
                           <td><a href="#"><?php echo $emp_id ?></a></td>
                           <td><?php echo $emp_name ?></td>
                           <td><?php echo $designation ?></td>
                           <td><?php echo $branch_name ?></td>
                           <td><?php echo $row['days'] ?> Days</td>
                           <td><?php if($row['approval_1'] == 0){echo"<span class='badge bg-warning'>Pending</span>";}if($row['approval_1'] == 1){echo"<span class='badge bg-success'>Approved</span>";}if($row['approval_1'] == 2){echo"<span class='badge bg-danger'>Rejected</span>";} ?></td>
                           <td><?php if($row['approval_2'] == 0){echo"<span class='badge bg-warning'>Pending</span>";}if($row['approval_2'] == 1){echo"<span class='badge bg-success'>Approved</span>";}if($row['approval_2'] == 2){echo"<span class='badge bg-danger'>Rejected</span>";} ?></td>

                           <td>
                              <a href="" class="badge bg-primary leave_action" data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#leaveModal">Action</a>
                           </td>
                        </tr>
                        <?php
                        $sl++;
                        }
                     }
                     ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Leave Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
     <div class="modal-content">
       <div class="modal-body" >
         <div id="leave_details"></div>
       </div>
     </div>
   </div>
 </div>
<!-- End -->