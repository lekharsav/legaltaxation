<?php
$emp_id = $_GET['emp_id'];
$sql = $con->query("select * from employee where id='$emp_id'");
$row = $sql->fetch_assoc();

$sql1 = $con->query("select * from branch where id='".$row['branch']."'");
if($row1 = $sql1->fetch_assoc()){
   $branch_name = $row1['name'];
}
$sql1 = $con->query("select * from department where id='".$row['dept']."'");
if($row1 = $sql1->fetch_assoc()){
   $department = $row1['name'];
}
$sql1 = $con->query("select * from designation where id='".$row['desig']."'");
if($row1 = $sql1->fetch_assoc()){
   $designation = $row1['name'];
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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Employee Report</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-sm-12 mb-2">
      
      <div class="row">
         <div class="col-md-4">
            <div class="card">
               <div class="card-body">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Employee Details</p>
                  <img src="https://script.viserlab.com/riselab/placeholder-image/400x200" class="w-100">
                  <hr>
                  <p>Name: <b><?php echo $row['name'] ?></b></p>
                  <p>Branch: <b><?php echo $branch_name ?></b></p>
                  <p>Department: <b><?php echo $department ?></b></p>
                  <p>Designation: <b><?php echo $designation ?></b></p>
                  <p>Joining Date: <b><?php echo date('d-M-Y',strtotime($row['joining_date'])) ?></b></p>
               </div>
            </div>
         </div>
         <div class="col-md-8 mb-2">
            <div class="card mb-2">
               <div class="card-body">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Leave Report</p>
                  <div class="table-responsive">
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>CL</th><th>EL</th><th>SL</th><th>LOP</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>2</td>
                              <td>0</td>
                              <td>5</td>
                              <td>2</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="card mb-2">
               <div class="card-body">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Attendance Report</p>
                  <div class="table-responsive">
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>Days</th><th>Present</th><th>Apsent</th><th>Late Coming</th><th>Overtime</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>30</td>
                              <td>25</td>
                              <td>5</td>
                              <td>2</td>
                              <td>3</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="card mb-2">
               <div class="card-body">
                  <p class="text-sm mb-2 text-capitalize font-weight-bold">Payroll Report</p>
                  <div class="table-responsive">
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>Salary</th><th>Attendance</th><th>Apsent</th><th>Late Coming</th><th>Overtime</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td><?php echo $row['gross'] ?></td>
                              <td>25</td>
                              <td>5</td>
                              <td>2</td>
                              <td>3</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>