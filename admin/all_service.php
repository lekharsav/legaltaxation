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


if(isset($_GET['delete'])){
    if($con->query("delete from service where id='".$_GET['id']."'")===true){
        echo"<script>window.location='all_service.php';</script>";
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
          </span>&nbsp;All Services
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="add_service.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New Service</a></li>
        </ol>
      </div>
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
                <h3 class="card-title">Service List</h3>
                <div class="table-responsive">
                    <table class="table table-striped example">
                        <thead>
                            <tr>
                                <th>SL NO</th>
                                <th>Category</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Market Price</th>
                                <th>Original Price</th>
                                <th>Partner Price</th>
                                <th>Min Clients</th>
                                <th>Applications</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            $sql = $con->query("select * from service order by id desc");
                            while($row = $sql->fetch_assoc()){
                                $sql2 = $con->query("select * from cate where id='".$row['cate']."'");
                                if($row2 = $sql2->fetch_assoc()){
                                    $cate = $row2['name'];
                                }
                                ?>
                                <tr>
                                    <td><?=$sl?></td>
                                    <td><?php echo $cate ?></td>
                                    <td><img src="../images/<?=$row['image']?>" alt="Service Image" width="100"></td>
                                    <td><?=$row['title']?></td>
                                    <td><?=$row['m_price']?></td>
                                    <td><?=$row['o_price']?></td>
                                    <td>
                                        <?php 
                                        if(!empty($row['partner_o_price'])) {
                                            echo $row['partner_o_price'];
                                        } else {
                                            echo '<span class="badge badge-secondary">N/A</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if(!empty($row['min_clients_for_partner'])) {
                                            echo $row['min_clients_for_partner'];
                                        } else {
                                            echo '<span class="badge badge-secondary">N/A</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $total_applicant = 0;
                                        $sql2 = $con->query("select * from apply where sid='".$row['id']."'");
                                        while($row2 = $sql2->fetch_assoc()){
                                            if($row2['send_to'] == ''){
                                                $total_applicant++;
                                            }
                                        }
                                        echo $total_applicant;
                                        ?>
                                    </td>
                                    <td>
                                        <a href="leads.php?sid=<?php echo $row['id'] ?>&cate=<?php echo $row['cate'] ?>" class="btn btn-sm btn-warning text-dark">Leads</a>
                                        <a href="service_form_submissions.php?service_id=<?=$row['id']?>" 
   class="btn btn-sm btn-info" title="View Form Submissions">
   <i class="fa fa-eye"></i> Forms
</a>
                                        <a href="edit_service.php?id=<?=$row['id']?>" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                                        <a href="all_service.php?id=<?=$row['id']?>&delete=delete" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this service?');"><i class="fa fa-trash"></i></a>
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