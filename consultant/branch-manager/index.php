<?php
include("../db.php");
if(@$_COOKIE["bm_log"]){
  echo"<script>window.location='dashboard.php';</script>";
}
if(isset($_POST["login"])){
  $emp_id = $con->real_escape_string($_POST["emp_id"]);
  $pass = $con->real_escape_string($_POST["pass"]);

  // $email = $_POST["email"];
  // $pass = $_POST["pass"];
  $sql = $con->query("select * from employee where emp_id='$emp_id' and password='$pass' and desig='34'");
  if($row = $sql->fetch_assoc()){
    if(@$_POST["ck"]){
      setcookie("bm_email",$emp_id,time()+60*60*24*30);
      setcookie("bm_pass",$pass,time()+60*60*24*30);
    }
    if($row['log_type'] == 1){
      echo"<script>window.location='two_factory_login.php?email=".$_POST['email']."';</script>";
    }else{
      $id = $row["id"];
      setcookie("bm_log",$id,time()+60*60*24*30);
      echo"<script>window.location='dashboard.php';</script>";
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
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="icon" type="image/png" href="https://aimdigitalise.com/images/logo.png">
      <title>
         BM Login - AIM Digitalise
      </title>
      <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
      <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
      <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
      <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
      <link id="pagestyle" href="../assets/css/material-dashboard.min.css?v=3.0.6" rel="stylesheet" />
      <script defer data-site="demos.creative-tim.com" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
      <style type="text/css">
        .form-control{
          font-size: 10pt;
          padding: 3px;
        }
      </style>
   </head>
   <body class="bg-gray-200">
      
      <main class="main-content  mt-0">
         <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container my-auto">
               <div class="row">
                  <div class="col-lg-4 col-md-8 col-12 mx-auto">
                     <div class="card z-index-0 fadeIn3 fadeInBottom">
                        
                        <div class="card-body">
                          <div class="text-center">
                            <img src="https://aimdigitalise.com/images/logo.png" class="w-25">
                          </div>
                          
                           <form role="form" method="post" class="text-start" autocomplete="off">
                              <div class="input-group input-group-outline my-3">
                                 <!-- <label class="form-label">Email</label> -->
                                 <input type="text" class="form-control text-uppercase" name="emp_id" value="<?php echo @$_COOKIE['bm_email']?>" placeholder="Employee ID" required>
                              </div>
                              <div class="input-group input-group-outline mb-3">
                                 <!-- <label class="form-label">Password</label> -->
                                 <input type="password" class="form-control" name="pass" value="<?php echo @$_COOKIE['bm_pass']?>" placeholder="Enter Password" required>
                              </div>
                              <div class="form-check form-switch d-flex align-items-center mb-3">
                                 <input class="form-check-input" name="ck" type="checkbox" id="rememberMe" checked>
                                 <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                              </div>
                              <div class="text-center">
                                 <button name="login" class="btn bg-gradient-primary w-100 my-4 mb-2">Login</button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </main>
      <script src="../assets/js/core/popper.min.js"></script>
      <script src="../assets/js/core/bootstrap.min.js"></script>
      <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
      <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
      <script src="../assets/js/plugins/dragula/dragula.min.js"></script>
      <script src="../assets/js/plugins/jkanban/jkanban.js"></script>
      
   </body>
</html>