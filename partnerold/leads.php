<?php
if(isset($_POST['submit'])){
  $sid = $_POST['sid'];
  $cate = $_POST['cate'];
  $leads = $_POST['leads'];
  $num_of_leads = count($leads);

  //*** If any plan available ***//

  $sql = $con->query("select * from plan_buy where partner='$aid' and cate='$cate'");
  if($row = $sql->fetch_assoc()){

    //*** Check Expiry ***//

    if($row['expired'] < date('Y-m-d',strtotime($current_date))){
      echo"<script>alert('Plan Exired. Please Renew');window.location='dashboard.php?src=leads.php&sid=$sid&cate=$cate';</script>";
    }else{

      //*** Check Leads Availablity ***//

      if($row['leads'] != 0 && $num_of_leads <= $row['leads']){
        $rest_leads = $row['leads'] - $num_of_leads;
        $con->query("update plan_buy set leads='$rest_leads' where id='".$row['id']."'");
        foreach($leads as $l){
          $con->query("update apply set send_to='$aid' where id='$l'");
        }
        echo"<script>window.location='dashboard.php?src=myleads.php';</script>";
      }else{
        echo"<script>alert('Not Enough Leads. Please Purchase Some Leads.');window.location='dashboard.php?src=leads.php&sid=$sid&cate=$cate';</script>";
      }
    }
  }else{
    echo"<script>alert('Please Buy Our Subscription');window.location='dashboard.php?src=leads.php&sid=$sid&cate=$cate';</script>";
  }
  
}

if(isset($_POST['plan_buy'])){
  $id = $_POST['id'];
  $sid = $_POST['sid'];
  $cate = $_POST['cate'];
  $valid = $_POST['valid'];
  $leads = $_POST['leads'];
  $price = $_POST['price'];
  $bal = $admin_wallet-$price;
  if($price > $admin_wallet){
    echo"<script>alert('Please Recharge Your Wallet.');window.location='dashboard.php?src=wallet.php';</script>";
  }else{
    $pur = date('Y-m-d',strtotime($current_date));
    $exp = date('Y-m-d', strtotime($pur. ' + '.$valid.' day'));
    $sql = $con->query("select * from plan_buy where partner='$aid' and plan='$id'");
    if($row = $sql->fetch_assoc()){
        echo"<script>alert('Alreay Purchased..');window.location='dashboard.php?src=cate.php&id=$cate';</script>";
    }else{
        if($con->query("insert into plan_buy(partner,plan,cate,leads,purchased,expired,created) values('$aid','$id','$cate','$leads','$pur','$exp','$current_date')") === true){
          $con->query("update partner set wallet='$bal' where id='$aid'");
            echo"<script>alert('Thanks for Purchased..');window.location='dashboard.php?src=leads.php&cate=$cate&sid=$sid';</script>";
        }else{
            echo"<script>alert('Server Error!!!');window.location='dashboard.php?src=leads.php&cate=$cate&sid=$sid';</script>";
        }
    }
  }
}

$query = $con->query("select * from cate where id='".$_GET['cate']."'");
$rows = $query->fetch_assoc();
?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-airplay"></i>
    </span> <?php echo $rows['name'] ?> Data List
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Services / <a href="dashboard.php?src=cate.php&id=<?php echo $_GET['cate'] ?>"><?php echo $rows['name'] ?></a> / List of Leads
      </li>
    </ul>
  </nav>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <form method="post">
          <table class="table table-stripped example">
            <thead>
              <tr>
                <th><input type="checkbox" id="all_select"></th>
                <th>SL</th>
                <th>Image</th>
                <th>Customer Name</th>
                <th>Mobile Number</th>
                <th>Email Address</th>
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>
              <?php
              $sl = 1;
              $service = $_GET['sid'];
              $sql = $con->query("select * from apply where sid='".$service."'");
              while($row = $sql->fetch_assoc()){
                if($row['send_to'] == ""){
                  $sql2 = $con->query("select * from customer where id='".$row['cid']."'");
                  $row2 = $sql2->fetch_assoc();
                  ?>
                  <tr>
                    <td><input type="checkbox" name="leads[]" value="<?php echo $row['id'] ?>"></td>
                    <td><?php echo $sl ?></td>
                    <td><img src="../images/no-profile.png" ></td>
                    <td>
                      <?php
                      echo substr($row2['name'], 0, 1).str_repeat('*', strlen($row2['name']) - 2).substr($row2['name'], strlen($row2['name']) - 1, 1);
                      ?>  
                    </td>
                    <td><?php echo substr($row2['contact'], 0, 1).str_repeat('*', strlen($row2['contact']) - 2).substr($row2['contact'], strlen($row2['contact']) - 1, 1); ?></td>
                    <td><?php echo substr($row2['email'], 0, 1).str_repeat('*', strlen($row2['email']) - 2).substr($row2['email'], strlen($row2['email']) - 1, 1); ?></td>
                    <!-- <td></td> -->
                  </tr>
                  <?php
                  $sl++;
                }
                
              }
              ?>
            </tbody>
          </table>
          <input type="hidden" name="sid" value="<?php echo $_GET['sid'] ?>">
          <input type="hidden" name="cate" value="<?php echo $_GET['cate'] ?>">
          <button class="btn btn-primary" name="submit">Get Selected Leads</button>
          <a href="dashboard.php?src=leads.php&sid=<?php echo $_GET['sid'] ?>&cate=<?php echo $_GET['cate'] ?>" class="btn btn-warning text-dark">Reset</a>
        </form>
        
      </div>
    </div>
  </div>
</div>
<hr>
<h3 class="text-center mb-3">Subscriptions For This Category</h3>
<div class="row">
    <?php
    $sql = $con->query("select * from plan where cate='".$_GET['cate']."'");
    while($row = $sql->fetch_assoc()){
        
        ?>
        <div class="col-md-4 mb-3">
            <div class="card subs">
                <div class="card-body s_<?php echo $row['type']?>">
                    <h3 class="text-center"><span>₹</span><?=$row['price']?></h3>
                    <hr>
                    <p>Num of Leads: <?=$row['leads']?></p>
                    <p>Price: <?php if($row['type'] == 0){echo"Bronze";}if($row['type'] == 1){echo"Silver";}if($row['type'] == 2){echo"Gold";}?></p>
                    <p>Validity: <?=$row['valid']?> days</p>
                    <div class="d-grid">
                        <?php
                        $sql2 = $con->query("select * from plan_buy where partner='$aid' and plan='".$row['id']."'");
                        if($row2 = $sql2->fetch_assoc()){
                            if($row2['expired'] < date('Y-m-d',strtotime($current_date))){
                                // *** Expired ***//
                                $status=2;
                                ?>
                                <button class="btn btn-danger btn-sm">Renew</button>
                                <?php
                            }else{
                                // *** Active ***//
                                $status=1;
                                ?>
                                <button class="btn btn-success btn-sm">Activate</button>
                                <?php
                            }
                        }else{
                            // *** Not Buy ***//
                            $status = 0;
                            ?>
                            <form method="post">
                              <input type="hidden" value="<?php echo $_GET['cate'] ?>" name="cate">
                              <input type="hidden" value="<?php echo $row['id'] ?>" name="id">
                              <input type="hidden" value="<?php echo $row['valid'] ?>" name="valid">
                              <input type="hidden" value="<?php echo $row['leads'] ?>" name="leads">
                              <input type="hidden" value="<?php echo $row['price'] ?>" name="price">
                              <input type="hidden" value="<?php echo $_GET['sid'] ?>" name="sid">
                              <div class="d-grid">
                                <button class="btn btn-warning btn-sm text-dark" name="plan_buy">Buy Now</button>
                              </div>
                              
                            </form>
                            
                            <!-- <a href="dashboard.php?src=cate.php&cate=<?=$_GET['cate']?>&id=<?=$row['id']?>&valid=<?=$row['valid']?>&leads=<?php echo $row['leads'] ?>&plan_buy" class="btn btn-warning btn-sm">Buy Now</a> -->
                            <?php
                        }
                        ?>
                        
                    </div>
                </div> 
            </div>
        </div>
        <?php
    }
    ?>
</div>
<div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Fill The Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
            <div class="form-group">
                <label>Number of Leads</label>
                <input type="number" name="leads" class="form-control" id="myLeads" placeholder="Enter Number of Leads You Want" required>
            </div>
            <div class="form-group">
                <input type="hidden" name="service" id="service_value">
                <button class="btn btn-primary" name="submit">Get It Now</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>