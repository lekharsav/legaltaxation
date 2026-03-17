<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-airplay"></i>
    </span> Your Wallet
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Wallet
      </li>
    </ul>
  </nav>
</div>
<div class="row">
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-body">
        <div class="float-end">
          <div class="input-group mb-3">
            <input type="hidden" id="partner" value="<?php echo $aid ?>">
            <input type="hidden" id="txn_id" value="<?php echo uniqid() ?>">
            <input type="text" class="form-control" id="amt" placeholder="Enter Amount" aria-label="Recipient's username" aria-describedby="basic-addon2">
            <button class="input-group-text bg-info" onclick="pay_now()" id="basic-addon2">Recharge Wallet &nbsp;<i class="fa fa-inr"></i></button>
          </div>
          <!-- <button class="btn btn-info">Recharge Wallet <i class="fa fa-inr"></i></button> -->
        </div>
        <br>
        <br>
        <h4 class="card-title">Recharge History</h4>
        <div class="table-responsive">
          <table class="example">
            <thead>
              <tr>
                <th>SL NO</th>
                <th>Amount</th>
                <th>TXN ID</th>
                <th>TXN Date</th>
                <th>Payment Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sl = 1;
              $sql = $con->query("select * from recharge where partner='$aid' order by id desc");
              while($row = $sql->fetch_assoc()){
                ?>
                <tr>
                  <td><?php echo $sl ?></td>
                  <td>₹<?php echo $row['amount'] ?></td>
                  <td><?php echo strtoupper($row['txn_id']) ?></td>
                  <td><?php echo $row['created'] ?></td>
                  <td>
                    <?php
                    if($row['status'] == 0){echo'<span class="badge bg-danger">Failed</span>';}
                    if($row['status'] == 1){echo'<span class="badge bg-success">Success</span>';}
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
