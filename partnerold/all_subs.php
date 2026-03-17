<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-airplay"></i>
    </span> All Subscribtion
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / All Subscribtion
      </li>
    </ul>
  </nav>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">List of Subscribtion</h4>
        <div class="table-responsive">
          <table class="example">
            <thead>
              <tr>
                <th>SL No</th>
                <th>Service</th>
                <th>Package Name</th>
                <th>Amount</th>
                <th>Total Leads</th>
                <th>Leads Available</th>
                <th>Purchased Date</th>
                <th>Expiry Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sl = 1;
              $sql = $con->query("select * from plan_buy where partner='$aid'");
              while($row = $sql->fetch_assoc()){
                $sql2 = $con->query("select * from plan where id='".$row['plan']."'");
                if($row2 = $sql2->fetch_assoc()){
                  $plan_price = $row2['price'];
                  $type = $row2['type'];
                  $leads = $row2['leads'];
                }
                $sql2 = $con->query("select * from cate where id='".$row['cate']."'");
                if($row2 = $sql2->fetch_assoc()){
                  $cate = $row2['name'];
                }
                ?>
                <tr>
                  <td><?php echo $sl ?></td>
                  <td><?php echo $cate ?></td>
                  <td>
                  <?php
                  if($type == 0){echo"Bronze";}
                  if($type == 1){echo"Silver";}
                  if($type == 2){echo"Gold";} 
                  ?>
                  </td>
                  <td><?php echo $plan_price ?></td>
                  <td><?php echo $leads ?></td>
                  <td><?php echo $row['leads'] ?></td>
                  <td><?php echo date('d-M-Y',strtotime($row['purchased'])); ?></td>
                  <td><?php echo date('d-M-Y',strtotime($row['expired'])); ?></td>
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