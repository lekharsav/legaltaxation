<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if (!$aid) {
  echo "<script>window.location='index.php';</script>";
}
$currentDate = date("Y-m-d");
$givenDate = "2024-09-16";
if (strtotime($currentDate) > strtotime($givenDate)) {
  // $initialValue = "Date Passed"; // Update value if current date is greater
  $sql = $con->query("select * from admin where id='$aid'");
  // echo"<script>window.location='index.php';</script>";
} else {
  $sql = $con->query("select * from admin where id='$aid'");
}

//   $sql = $con->query("select * from admin where id='$aid'");

if ($row = $sql->fetch_assoc()) {
  $admin_image = $row["image"];
  $admin_name = $row["name"];
  $admin_cont = $row["contact"];
  $admin_email = $row["email"];
  $admin_pass = $row["password"];
}

// Get service ID and category from URL parameters
$service_id = isset($_GET['sid']) ? $_GET['sid'] : 0;
$category_id = isset($_GET['cate']) ? $_GET['cate'] : 0;

// Redirect if no service ID is provided
if (!$service_id) {
  echo "<script>alert('Please select a service first!'); window.location='all_service.php';</script>";
  exit();
}

// Get service details for display
$service_details = [];
$sql_service = $con->query("SELECT * FROM service WHERE id='$service_id'");
if ($service_row = $sql_service->fetch_assoc()) {
  $service_details = $service_row;
}

if (isset($_POST['submit'])) {
  $sid = $_POST['sid'];
  $cate = $_POST['cate'];
  $leads = $_POST['leads'];
  $num_of_leads = count($leads);

  //*** If any plan available ***//

  $sql = $con->query("select * from plan_buy where partner='$aid' and cate='$cate'");
  if ($row = $sql->fetch_assoc()) {

    //*** Check Expiry ***//

    if ($row['expired'] < date('Y-m-d', strtotime($current_date))) {
      echo "<script>alert('Plan Exired. Please Renew');window.location='dashboard.php?src=leads.php&sid=$sid&cate=$cate';</script>";
    } else {

      //*** Check Leads Availablity ***//

      if ($row['leads'] != 0 && $num_of_leads >= $row['leads']) {
        $rest_leads = $row['leads'] - $num_of_leads;
        $con->query("update plan_buy set leads='$rest_leads' where id='" . $row['id'] . "'");
        foreach ($leads as $l) {
          $con->query("update apply set send_to='$aid' where id='$l'");
        }
        echo "<script>window.location='dashboard.php?src=myleads.php';</script>";
      } else {
        echo "<script>alert('Not Enough Leads. Please Purchase Some Leads.');window.location='dashboard.php?src=leads.php&sid=$sid&cate=$cate';</script>";
      }
    }
  } else {
    echo "<script>alert('Please Buy Our Subscription');window.location='dashboard.php?src=leads.php&sid=$sid&cate=$cate';</script>";
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
                </span>&nbsp;Lead List
                <?php if (!empty($service_details)): ?>
                  <small class="text-muted">- <?php echo htmlspecialchars($service_details['title']); ?></small>
                <?php endif; ?>
              </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item"><a href="all_service.php">Services</a></li>
                <li class="breadcrumb-item active">Leads</li>
              </ol>
            </div>
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>

      <!-- /.content-header -->

      <!-- Main content -->







      <section class="content">
        <div class="container-fluid">

          <!-- Service Info Box -->
          <?php if (!empty($service_details)): ?>
            <div class="row mb-3">
              <div class="col-md-12">
                <div class="info-box bg-light">
                  <span class="info-box-icon bg-info"><i class="fas fa-copy"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text"><?php echo htmlspecialchars($service_details['title']); ?></span>
                    <span class="info-box-number">
                      Price: ₹<?php echo $service_details['o_price']; ?>
                      <small class="text-muted">(Original: ₹<?php echo $service_details['m_price']; ?>)</small>
                    </span>
                    <div class="progress">
                      <div class="progress-bar bg-info" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">
                      Total applications for this service
                    </span>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i> Customer Applications
                    <a href="all_service.php" class="btn btn-secondary btn-sm float-right">
                      <i class="fa fa-arrow-left"></i> Back to Services
                    </a>
                  </h3>
                </div>
                <div class="card-body">
                  <?php if (!$service_id): ?>
                    <div class="alert alert-warning">
                      <i class="icon fas fa-exclamation-triangle"></i>
                      <strong>No service selected!</strong> Please select a service from the services page.
                    </div>
                  <?php else: ?>
                    <form method="post">
                      <div class="table-responsive">
                        <table class="table table-striped example">
                          <thead>
                            <tr>
                              <th><input type="checkbox" id="all_select"></th>
                              <th>SL</th>
                              <th>Customer Name</th>
                              <th>Mobile Number</th>
                              <th>Email Address</th>
                              <th>Application Date</th>
                              <th>Status</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $sl = 1;
                            $service = $service_id; // Use the validated service ID
                            $sql = $con->query("select * from apply where sid='" . $service . "' order by id desc");
                            $total_applications = $sql->num_rows;

                            if ($total_applications > 0):
                              while ($row = $sql->fetch_assoc()):
                                $sql2 = $con->query("select * from customer where id='" . $row['cid'] . "'");
                                $row2 = $sql2->fetch_assoc();
                            ?>
                                <tr>
                                  <td>
                                    <?php if ($row['send_to'] == ""): ?>
                                      <input type="checkbox" name="leads[]" value="<?php echo $row['id'] ?>">
                                    <?php else: ?>
                                      <i class="fas fa-check text-success" title="Already Assigned"></i>
                                    <?php endif; ?>
                                  </td>
                                  <td><?php echo $sl ?></td>
                                  <td>
                                    <?php echo htmlspecialchars($row2['name'] ?? 'N/A'); ?>
                                  </td>
                                  <td><?php echo htmlspecialchars($row2['contact'] ?? 'N/A'); ?></td>
                                  <td><?php echo htmlspecialchars($row2['email'] ?? 'N/A'); ?></td>
                                  <td><?php echo date('d M Y', strtotime($row['created'])); ?></td>
                                  <td>
                                    <?php if ($row['send_to'] == ""): ?>
                                      <span class="badge badge-warning">Pending</span>
                                    <?php else: ?>
                                      <span class="badge badge-success">Assigned</span>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <!-- View Form Data Button -->
                                    <a href="view_customer_form_data.php?application_id=<?php echo $row['id']; ?>"
                                      class="btn btn-sm btn-info" title="View Form Data">
                                      <i class="fa fa-eye"></i> View Form
                                    </a>
                                  </td>
                                </tr>
                              <?php
                                $sl++;
                              endwhile;
                            else: ?>
                              <tr>
                                <td colspan="8" class="text-center py-4">
                                  <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                  <h4>No applications found</h4>
                                  <p class="text-muted">No customers have applied for this service yet.</p>
                                </td>
                              </tr>
                            <?php endif; ?>
                          </tbody>
                        </table>
                      </div>
                      <?php if ($total_applications > 0): ?>
                        <input type="hidden" name="sid" value="<?php echo $service_id; ?>">
                        <input type="hidden" name="cate" value="<?php echo $category_id; ?>">
                        <!--
          <button class="btn btn-primary" name="submit">Get Selected Leads</button>
          <a href="dashboard.php?src=leads.php&sid=<?php echo $service_id; ?>&cate=<?php echo $category_id; ?>" class="btn btn-warning text-dark">Reset</a>
          -->
                      <?php endif; ?>
                    </form>
                  <?php endif; ?>

                </div>
                <div class="card-footer">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="small text-muted">
                        <i class="fas fa-info-circle"></i> Showing applications for Service ID: <?php echo $service_id; ?>
                        <?php if (!empty($service_details)): ?>
                          - <?php echo htmlspecialchars($service_details['title']); ?>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="col-md-6 text-right">
                      <span class="badge badge-info">Total: <?php echo $total_applications ?? 0; ?> applications</span>
                    </div>
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

  <script>
    $(document).ready(function() {
      // Select all checkbox functionality
      $('#all_select').click(function() {
        if ($(this).is(':checked')) {
          $('input[name="leads[]"]').prop('checked', true);
        } else {
          $('input[name="leads[]"]').prop('checked', false);
        }
      });

      // Initialize DataTable if needed
      if ($.fn.DataTable) {
        $('.example').DataTable({
          "pageLength": 25,
          "responsive": true,
          "autoWidth": false,
          "ordering": true,
          "language": {
            "search": "Search leads:",
            "lengthMenu": "Show _MENU_ leads per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ leads",
            "emptyTable": "No leads available"
          }
        });
      }
    });
  </script>
</body>

</html>