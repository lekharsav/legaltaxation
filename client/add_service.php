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


if(isset($_POST['submit'])){
    $file = str_replace(" ","",$_FILES["file"]["tmp_name"]);
    $fname = $_FILES["file"]["name"];
    $s_des = $con->real_escape_string($_POST['s_des']);
    $l_des = $con->real_escape_string($_POST['l_des']);
    $doc = implode(",",$_POST['doc']);
    if($con->query("insert into service(cate,image,title,s_des,m_price,o_price,doc_req,des,posted,status) values('".$_POST['cate']."','$fname','".$_POST['title']."','$s_des','".$_POST['m_price']."','".$_POST['o_price']."','$doc','$l_des','".date('Y-m-d',strtotime($current_date))."','1')")===true){
        move_uploaded_file($file,"../images/".$fname);
        echo"<script>window.location='add_service.php';</script>";
    }else{
        echo"<script>alert('Server Error!!');window.location='add_service.php';</script>";
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
            <h1 class="m-0"><span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-airplay"></i>
    </span>&nbsp;Add Service</h1>
          </div><!-- /.col -->
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div> -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->







    <section class="content">
      <div class="container-fluid">
      

   
      <div class="row">
    <div class="col-lg-12 mb-3">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Service Details</h3>
                <form method="post" enctype="multipart/form-data" class="row">
                    <div class="form-group col-lg-4">
                        <label>Category</label>
                        <select name="cate" class="form-control" required>
                            <option value="" selected disabled>Choose Category</option>
                            <?php
                            $sql = $con->query("select * from cate order by id desc");
                            while($row = $sql->fetch_assoc()){
                                ?>
                                <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Service Image</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Service Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter Service Title" required>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Market Price</label>
                        <input type="text" name="m_price" class="form-control" placeholder="Enter Market Price" required>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Original Price</label>
                        <input type="text" name="o_price" class="form-control" placeholder="Enter Original Price" required>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Document Required</label>
                        <div class="dropdown d-grid">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Choose Option
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <?php
                                $sql = $con->query("select * from doc order by id asc");
                                while($row = $sql->fetch_assoc()){
                                    ?>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <input type="checkbox" class="select_product" style="width: 20px; height: 20px;" name="doc[]" value="<?=$row['id']?>">
                                            <label><?php echo $row['name']; ?></label>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>Short Description</label>
                        <textarea name="s_des" class="form-control" placeholder="Enter Short Description" required></textarea>
                    </div>
                    
                    <div class="form-group col-lg-12">
                        <label>Full Description</label>
                        <textarea name="l_des" class="form-control" placeholder="Enter Full Description" required></textarea>
                    </div>
                   


                    <div class="form-group col-lg-6">
                        <button class="btn btn-primary" name="submit">Submit</button>
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
      <b>Version</b> 3.2.0
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
