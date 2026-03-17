<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}
 $currentDate = date("Y-m-d");
$givenDate = "2024-09-16";
 if (strtotime($currentDate) > strtotime($givenDate)) {
        // $initialValue = "Date Passed"; // Update value if current date is greater
      $sql = $con->query("select * from admin where id='$aid'");
        // echo"<script>window.location='index.php';</script>";
    }
    else
    {
        $sql = $con->query("select * from admin where id='$aid'");
    }
    
    //   $sql = $con->query("select * from admin where id='$aid'");

if($row = $sql->fetch_assoc()){
  $admin_image = $row["image"];
  $admin_name = $row["name"];
  $admin_cont = $row["contact"];
  $admin_email = $row["email"];
  $admin_pass = $row["password"];
}

if(isset($_POST["submit"])){
	if($con->query("update plan set cate='".$_POST['cate']."',type='".$_POST['type']."',leads='".$_POST['leads']."',valid='".$_POST['valid']."',price='".$_POST['price']."' where id='".$_POST['id']."'") === true){
        echo"<script>alert('Saved..');window.location='plan.php';</script>";
    }else{
        echo"<script>alert('Server Error');window.location='plan.php?id=".$_POST['id']."';</script>";
    }
}
$sql = $con->query("select * from plan where id='".$_GET['id']."'");
$row = $sql->fetch_assoc();



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  






  <!-- Navbar -->
  <?php include("navbar.php"); ?>
  <!-- /.navbar -->





  <!-- Main Sidebar Container -->
  <?php include("sidebar.php"); ?>

<!--- Main Sidebar Container--->






  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">



    <!-- Content Header (Page header) -->
  <div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">
          <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-airplay"></i>
          </span>&nbsp;Edit Plans
        </h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

    <!-- /.content-header -->

    <!-- Main content -->







    <section class="content">
      <div class="container-fluid">
      



      
      



      <div class="page-header">
  
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span><a href="plan.php"><i class="fa fa-arrow-circle-left"></i> Go Back</a>
      </li>
    </ul>
  </nav>
</div>

<div class="row">
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Plan</h4></br>
        <form method="POST">



        <div class="mb-3">
    <label>Plan Category</label>
    <select name="cate" class="form-control" required>
        <option value="" disabled>Choose Option</option>
        <?php
        // Fetch all categories from the database
        $query = "SELECT * FROM cate";
        $result = $con->query($query);

        // Iterate through each category
        if ($result->num_rows > 0) {
            while ($category = $result->fetch_assoc()) {
                // Check if the current category matches the one in the $row array (for editing)
                $selected = ($row['cate'] == $category['id']) ? "selected" : "";
                echo '<option value="' . $category['id'] . '" ' . $selected . '>' . $category['name'] . '</option>';
            }
        } else {
            echo '<option value="" disabled>No categories available</option>';
        }
        ?>
    </select>
</div>


          <div class="mb-3">
            <label>Plan Type</label>
            <select name="type" class="form-control" required>
              <option value="" disabled selected>Choose Option</option>
              <option value="0" <?php if($row['type'] == 0){echo"selected";}?>>Bronze</option>
              <option value="1" <?php if($row['type'] == 1){echo"selected";}?>>Silver</option>
              <option value="2" <?php if($row['type'] == 2){echo"selected";}?>>Gold</option>
            </select>
          </div>



          <div class="mb-3">
            <label>Number of Leads</label>
            <input type="text" name="leads" value="<?=$row['leads']?>" class="form-control" placeholder="Enter Number of Leads">
          </div>
          <div class="mb-3">
            <label>Validity (in Days)</label>
            <input type="text" name="valid" value="<?=$row['valid']?>" class="form-control" placeholder="Enter Plan Validity" required>
          </div>
          <div class="mb-3">
            <label>Price</label>
            <input type="text" name="price" value="<?=$row['price']?>" class="form-control" placeholder="Enter Price" required>
          </div>
          <div class="mb-3">
            <input type="hidden" name="id" value="<?=$_GET['id']?>">
          
            <button name="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



      

      

       
        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->








  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
</body>
</html>
