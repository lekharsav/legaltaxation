<?php
if(isset($_GET['status'])){
  if($con->query("update apply set status='".$_GET['status']."' where id='".$_GET['id']."'") === true){
    echo"<script>window.location='dashboard.php?src=myleads.php';</script>";
  }else{
    echo"<script>alert('Server Error!!!');window.location='dashboard.php?src=myleads.php';</script>";
  }
}
?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-airplay"></i>
    </span> List of Leads
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Services / List of Leads
      </li>
    </ul>
  </nav>
</div>
<div class="row">
  <div class="col-lg-12 mb-3">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Your All Purchased Leads</h4>
        <div class="table-responsive">
          <div class="card">
            <div class="card-body">
              <table class="example">
                <thead>
                  <tr>
                    <th>SL</th>
                    <th>Service</th>
                    <th>Client Name</th>
                    <th>Mobile Number</th>
                    <th>Email Address</th>
                    <th>Documents</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sl = 1;
                  $sql = $con->query("select * from apply where send_to='$aid' order by id desc");
                  while($row = $sql->fetch_assoc()){
                    $sql2 = $con->query("select * from customer where id='".$row['cid']."'");
                    $row2 = $sql2->fetch_assoc();
                    $sql3 = $con->query("select * from service where id='".$row['sid']."'");
                    if($row3 = $sql3->fetch_assoc()){
                      $cate = $row3['cate'];
                    }
                    $sql3 = $con->query("select * from cate where id='$cate'");
                    if($row3 = $sql3->fetch_assoc()){
                      $cate_name = $row3['name'];
                    }
                    ?>
                    <tr>
                      <td><?php echo $sl ?></td>
                      <td><?php echo $cate_name ?></td>
                      <td><?php echo $row2['name'] ?></td>
                      <td><?php echo $row2['contact'] ?></td>
                      <td><?php echo $row2['email'] ?></td>
                      <td>
                        <?php
                        $sql3 = $con->query("select * from req_doc where cid='".$row['cid']."' and sid='".$row['sid']."'");
                        while($row3 = $sql3->fetch_assoc()){
                          $sql4 = $con->query("select * from doc where id='".$row3['type']."'");
                          $row4 = $sql4->fetch_assoc();
                          echo"<a href='../req_doc/".$row3['file']."' class='badge bg-info' target='blank'>".$row4['name']."</a>&nbsp;";
                        }
                        ?>
                      </td>
                      <td>
                        <?php
                        if($row['status'] == 0){
                          echo'<span class="badge bg-warning text-dark">Pending</span>';
                        }if($row['status'] == 1){
                          echo'<span class="badge bg-success">Completed</span>';
                        }
                        ?>
                      </td>
                      <td>
                        <?php
                        if($row['status'] == 0){
                          echo'<a href="dashboard.php?src=myleads.php&status=1&id='.$row['id'].'" onclick="return dlt();" class="btn btn-sm btn-success">Work Done</a>';
                        }if($row['status'] == 1){
                          echo'<a href="dashboard.php?src=myleads.php&status=0&id='.$row['id'].'" onclick="return dlt();" class="btn btn-sm btn-danger">Not Done</a>';
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
    </div>
  </div>
</div>