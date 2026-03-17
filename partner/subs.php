<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="fa fa-bell"></i>
    </span> Subscription
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Subscription
      </li>
    </ul>
  </nav>
</div>
<div class="row">
  <div class="col-lg-12 mb-3">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">List of all Subscription</h4>
        <div class="table-responsive">
          <table class="example">
            <thead>
              <tr>
                <th>SL NO.</th>
                <th>Partner Name</th>
                <th>Category</th>
                <th>Plan</th>
                <th>Amount</th>
                <th>Status</th>
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>
              <?php
              $sl = 1;
              $sql = $con->query("select * from plan_buy order by id desc");
              while($row = $sql->fetch_assoc()){
                $sql2 = $con->query("select * from partner where id='".$row['partner']."'");
                if($row2 = $sql2->fetch_assoc()){
                  $partner = $row2['name'];
                }
                $sql2 = $con->query("select * from cate where id='".$row['cate']."'");
                if($row2 = $sql2->fetch_assoc()){
                  $cate= $row2['name'];
                }
                $sql2 = $con->query("select * from plan where id='".$row['plan']."'");
                if($row2 = $sql2->fetch_assoc()){
                  if($row2['type'] == 0){
                    $plan = "Bronze";
                  }if($row2['type'] == 1){
                    $plan = "Bronze";
                  }if($row2['type'] == 2){
                    $plan = "Bronze";
                  }
                  $amount = $row2['price'];
                }
                if($row['expired'] < date('Y-m-d',strtotime($current_date))){
                  $status=0;
                }else{
                  $status = 1;
                }
                ?>
                <tr>
                  <td><?php echo $sl ?></td>
                  <td><?php echo $partner ?></td>
                  <td><?php echo $cate ?></td>
                  <td><?php echo $plan ?></td>
                  <td>₹<?php echo $amount ?></td>
                  <td><?php echo($status == 0) ? "<span class='badge bg-warning text-dark'>Expired</span>" : "<span class='badge bg-success '>Active</span>";?></td>
                  <!-- <td></td> -->
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