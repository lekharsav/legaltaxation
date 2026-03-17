<?php
if(isset($_GET['status'])){
   $status = $_GET['status'];
   $id = $_GET['id'];
   if($con->query("update attendance set status='$status' where id='$id'") === true){
      echo "<script>window.location='dashboard.php?src=attendance.php';</script>";

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
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Attendance</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-lg-12">
		<div class="table-responsive">
			<table class="table table-striped example" id="datatable-basic">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Attendence Date</th>
                    <th>Employee Name</th>
                    <th>In Time</th>
                    <th class="remark_th">In Location</th>
                    <th>Out Time</th>
                    <th class="remark_th">Out Location</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                $sql = $con->query("select * from attendance order by id desc");
                while($row = $sql->fetch_assoc()){
                  $eid = $row["eid"];
                  $sql2 = $con->query("select * from employee where id='$eid'");
                  $row2 = $sql2->fetch_assoc();
                  $name = $row2['name'];
                    ?>
                    <tr>
                        <td><?php echo $sl ?></td>
                        <td><?php echo $row['in_date'] ?></td>
                        <td><?php echo $name ?></td>
                        <td><?php echo $row['in_time'] ?></td>
                        <td><?php echo $row['in_loc'] ?></td>
                        <td><?php echo $row['out_time'] ?></td>
                        <td><?php echo $row['out_loc'] ?></td>
                        <td>
                            <?php
                            if($row['status'] == 0){
                                echo"<span class='badge bg-warning'>Absent</span>";
                            }if($row['status'] == 1){
                                echo"<span class='badge bg-success'>Present</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if($row['status'] == 0){
                            ?>
                            <a href="dashboard.php?src=attendance.php&id=<?php echo $row['id'] ?>&status=1" class="btn btn-sm btn-success">Approve</a>
                            <?php
                            }if($row['status'] == 1){
                            ?>
                            <a href="dashboard.php?src=attendance.php&id=<?php echo $row['id'] ?>&status=0" class="btn btn-sm btn-warning" onclick="return dlt();">Reject</a>
                            <?php
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