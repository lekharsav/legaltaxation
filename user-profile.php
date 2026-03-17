<?php
include('db.php');
$uid = @$_COOKIE['tax_customer_log'];
if(!$uid){
    echo"<script>window.location='index.php';</script>";
}
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $cont = $_POST['cont'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    
    $sql = $con->query("select * from customer where (contact='$cont' or email='$email') and id != '$uid'");
    if($row = $sql->fetch_assoc()){
        echo"<script>alert('This Mobile Number Already Registered!!!!');window.location='user-profile.php';</script>";
    }else{
        if($con->query("update customer set name='$name',contact='$cont',email='$email',password='$pass' where id='$uid'")===true){
            echo"<script>alert('Saved...');window.location='user-profile.php';</script>";
        }else{
            echo"<script>alert('Server Error!!!!');window.location='user-profile.php';</script>";
        }
    }
}
?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
<title>Profile</title>
<link rel="shortcut icon" href="images/favicon.webp" type="image/x-icon" /><meta charset="utf-8">
<meta name="author" content="">
<meta name="keywords" content="">
<meta name="description" content="">		
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
 <!--styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
<link href="css/slick.css" rel="stylesheet">
<link href="css/slick-theme.css" rel="stylesheet">
</head>
<body>


<?php include("includes/header.php");?>

<section class="income_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb_area">
                    <ul>
                        <li><a href="index">Legal Taxation</a></li>
                        <li>/</li>
                        <li>Your Profile</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="d-flex align-items-start">
          <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Dashboard</button>
            <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Your Profile</button>
          </div>
          <div class="tab-content" id="v-pills-tabContent">
              
            <!-- Dashboard Section -->
            
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th>SL No.</th>
                                <th>Service Name</th>
                                <th>Price</th>
                                <th>Your Documents</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            $sql = $con->query("select * from apply where cid='$uid'");
                            while($row = $sql->fetch_assoc()){
                                $sql2 = $con->query("select * from service where id='".$row['sid']."'");
                                $row2 = $sql2->fetch_assoc();
                                ?>
                                <tr>
                                    <td><?=$sl?></td>
                                    <td><?=$row2['title']?></td>
                                    <td>₹<?=$row2['o_price']?></td>
                                    <td>
                                        <?php
                                        $sql3 = $con->query("select * from req_doc where cid='$uid' and sid='".$row['sid']."'");
                                        while($row3 = $sql3->fetch_assoc()){
                                            ?>
                                            <a href="req_doc/<?=$row3['file']?>" class="d-block badge bg-info mb-1" target="blank"><?=$row3['file']?></a>
                                            <?php
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
                                </tr>
                                <?php
                                $sl++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Profile Section -->
            
            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="mb-3">
                                        <label>Your Name</label>
                                        <input type="text" name="name" value="<?=$customer_name?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Mobile Number</label>
                                        <input type="text" name="cont" value="<?=$customer_cont?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email Address</label>
                                        <input type="email" name="email" value="<?=$customer_email?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Password</label>
                                        <input type="password" name="pass" value="<?=$customer_pass?>" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <button class="btn btn-primary" name="submit">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</section>
<?php include("includes/footer.php");?>

</body>
</html>