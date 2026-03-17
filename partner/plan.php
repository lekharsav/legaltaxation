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
	$sql = $con->query("select * from plan where cate='".$_POST['cate']."' and type='".$_POST['type']."'");
	if($sql->fetch_assoc()){
	    echo"<script>alert('This plan already created');window.location='plan.php?id=".$_POST['cate']."';</script>";
	}else{
	    if($con->query("insert into plan(cate,type,leads,valid,price) values('".$_POST['cate']."','".$_POST['type']."','".$_POST['leads']."','".$_POST['valid']."','".$_POST['price']."')") === true){
	        echo"<script>alert('Created Successfully');window.location='plan.php?id=".$_POST['cate']."';</script>";
	    }else{
	        echo"<script>alert('Server Error');window.location='plan.php?id=".$_POST['cate']."';</script>";
	    }
	}
}
if(isset($_GET['delete'])){
    if($con->query("delete from plan where id='".$_GET['id']."'")===true){
        echo"<script>window.location='plan.php?id=".$_GET['cate']."';</script>";
    }
}



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
          </span>&nbsp;Plans
        </h1>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

    <!-- /.content-header -->

    <!-- Main content -->







    <section class="content">
      <div class="container-fluid">
      



      
      <div class="row">
  <div class="col-md-12 mb-3">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Add New Plan</h4></br>

        <form method="POST">

          <div class="mb-3">
            <label>Plan Category</label>
            <select name="cate" class="form-control" required>
              <option value="" disabled selected>Choose Option</option>
              <?php
    

    $query = "SELECT * FROM cate";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
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
              <option value="0">Bronze</option>
              <option value="1">Silver</option>
              <option value="2">Gold</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Number of Leads</label>
            <input type="text" name="leads" class="form-control" placeholder="Enter Num of Leads">
          </div>
          <div class="mb-3">
            <label>Validity (in Day's)</label>
            <input type="text" name="valid" class="form-control" placeholder="Enter Plan Validity" required>
          </div>
          <div class="mb-3">
            <label>Price</label>
            <input type="text" name="price" class="form-control" placeholder="Enter Price" required>
          </div>
          <div class="mb-3">
            <button name="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-12 mb-3">
    <div class="table-responsive">
      <table class="table table-bordered table-striped example">
        <thead>
          <tr>
            <th>SL NO.</th>
            <th>Category</th>
            <th>Plan Type</th>
            <th>Num of Leads</th>
            <th>Validity</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sl = 1;
          $sql = $con->query("select * from plan");
          while($row = $sql->fetch_assoc()){
        
            // Ensure $cate_id is valid before querying
            $cate_id = $row['cate'];
            
            if (!empty($cate_id) && is_numeric($cate_id)) {
                $query = $con->query("SELECT name FROM cate WHERE id = $cate_id");
            
                // Check if the query returned a result
                if ($query && $query->num_rows > 0) {
                    $cate = $query->fetch_assoc();
                    $categoryName = $cate['name'];
                } else {
                    $categoryName = "Unknown"; // Fallback if no category found
                }
            } else {
                $categoryName = "Invalid ID"; // Fallback if ID is invalid
            }
            
        ?>
          <tr>
            <td><?=$sl?></td>
            <td><?=$categoryName?></td>
            <td>
              <?php 
              if($row['type'] == 0) { echo "Bronze"; } 
              if($row['type'] == 1) { echo "Silver"; }
              if($row['type'] == 2) { echo "Gold"; }
              ?>
            </td>
            <td><?=$row['leads']?></td>
            <td><?=$row['valid']?> Days</td>
            <td>₹<?=$row['price']?></td>
            <td>
              <a href="edit_plan.php?id=<?=$row['id']?>" class="badge bg-success"><i class="fa fa-edit"></i></a>
              <a href="plan.php?id=<?=$row['id']?>&delete=delete" class="badge bg-danger" onclick="return dlt()"><i class="fa fa-trash"></i></a>
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
