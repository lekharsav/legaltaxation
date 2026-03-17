<?php
if(isset($_GET['status'])){
  if($con->query("update partner set status='".$_GET['status']."' where id='".$_GET['id']."'") === true){
    echo"<script>window.location='dashboard.php?src=partners.php';</script>";
  }else{
    echo"<script>alert('Server Error!!!');window.location='dashboard.php?src=partners.php';</script>";
  }
}
?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="fa fa-users"></i>
    </span> All Partners
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Users / Partners
      </li>
    </ul>
  </nav>
</div>
<div class="row">
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="example">
            <thead>
              <tr>
                <th>SL</th>
                <th>Full Name</th>
                <th>Mobile Number</th>
                <th>Email Address</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sl = 1;
              $sql = $con->query("select * from partner order by id desc");
              while($row = $sql->fetch_assoc()){
                ?>
                <tr>
                  <td><?php echo $sl ?></td>
                  <td><?php echo $row['name'] ?></td>
                  <td><?php echo $row['contact'] ?></td>
                  <td><?php echo $row['email']; ?></td>
                  <td>
                    <?php
                    if($row['status'] == 0){echo'<span class="badge bg-warning text-dark">Pending</span>';}
                    if($row['status'] == 1){echo'<span class="badge bg-success">Approved</span>';}
                    ?>
                  </td>
                  <td>
                    <?php
                    if($row['status'] == 0){echo'<a href="dashboard.php?src=partners.php&status=1&id='.$row['id'].'" class="btn btn-sm btn-success">Approve</a>';}
                    if($row['status'] == 1){echo'<a href="dashboard.php?src=partners.php&status=0&id='.$row['id'].'" class="btn btn-sm btn-warning text-dark">Pending</a>';}
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