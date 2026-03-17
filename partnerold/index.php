<?php
include("../db.php");
if(@$_COOKIE["tax_partner_log"]){
  echo"<script>window.location='dashboard.php';</script>";
}
if(isset($_POST["login"])){
  $email = $con->real_escape_string($_POST["email"]);
  $pass = $con->real_escape_string($_POST["pass"]);

  $sql = $con->query("select * from partner where email='$email' and password='$pass'");
  if($row = $sql->fetch_assoc()){
    if($row['status'] == 1){
      if(@$_POST["ck"]){
        setcookie("tax_admin_email",$email,time()+60*60*24*30);
        setcookie("tax_admin_pass",$pass,time()+60*60*24*30);
      }
      $id = $row["id"];
      setcookie("tax_partner_log",$id,time()+60*60*24*30);
      echo"<script>window.location='dashboard.php';</script>";
    }else{
      setcookie("msg",'Your Profile Not Approved Yet. Please Try a While..',time()+1);
      echo"<script>window.location='index.php';</script>";
    }
  }else{
    setcookie("msg",'Invalid Login Details',time()+1);
    echo"<script>window.location='index.php';</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!--<link rel="shortcut icon" href="assets/images/favicon.ico" />-->
    <style>
        .btn-gradient-primary{
            background: linear-gradient(to right, #ffbb8c, #ff5555);
        }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-5 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="..//images/logo.webp" class="w-100">
                </div>
                <!--<h4>Hello! let's get started</h4>-->
                <!--<h6 class="font-weight-light">Sign in to continue.</h6>-->
                <form class="pt-3" method="POST">
                <?php
                  if(@$_COOKIE["msg"]){
                  ?>
                  <div class="form-group">
                    <div class="alert alert-danger">
                      <b><?php echo $_COOKIE["msg"]; ?></b>
                    </div>
                  </div>
                     
                  <?php
                  }
                  ?>
                  <div class="form-group">
                    <input type="email" name="email" value="<?=@$_COOKIE['tax_admin_email']?>" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username">
                  </div>
                  <div class="form-group">
                    <input type="password" name="pass" value="<?=@$_COOKIE['tax_admin_pass']?>" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" name="login">SIGN IN</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                    </div>
                    <a href="#" class="auth-link text-black">Forgot password?</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <!-- endinject -->
  </body>
</html>