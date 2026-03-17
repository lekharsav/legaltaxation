<?php
session_start();
include("../db.php");

// Check if user is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../partner-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Get CA details
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
if($sql->num_rows == 0){
    session_destroy();
    header("Location: ../partner-login.php");
    exit();
}
$ca = $sql->fetch_assoc();

// Set variables
$ca_name = $ca["name"];
$ca_image = $ca["image"];

// Handle party deletion
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $con->query("UPDATE parties SET status='inactive' WHERE id='$delete_id' AND created_by='$ca_id'");
    $_SESSION['success'] = "Party deleted successfully!";
    header("Location: parties.php");
    exit();
}

// Handle status change
if(isset($_GET['toggle_status'])) {
    $party_id = $_GET['toggle_status'];
    $result = $con->query("SELECT status FROM parties WHERE id='$party_id' AND created_by='$ca_id'");
    if($result->num_rows > 0) {
        $party = $result->fetch_assoc();
        $new_status = $party['status'] == 'active' ? 'inactive' : 'active';
        $con->query("UPDATE parties SET status='$new_status' WHERE id='$party_id'");
        $_SESSION['success'] = "Party status updated!";
    }
    header("Location: parties.php");
    exit();
}

// Get all parties for this CA
$parties = [];
$sql = $con->query("SELECT * FROM parties WHERE created_by='$ca_id' ORDER BY created_date DESC");
if($sql->num_rows > 0) {
    $parties = $sql->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Parties - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
  
  <style>
    .party-badge {
      padding: 3px 8px;
      border-radius: 10px;
      font-size: 12px;
      font-weight: bold;
    }
    .badge-customer { background: #17a2b8; color: white; }
    .badge-vendor { background: #28a745; color: white; }
    .badge-both { background: #6f42c1; color: white; }
    .status-active { color: #28a745; }
    .status-inactive { color: #dc3545; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="parties.php" class="nav-link">Parties</a>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Include your sidebar.php here -->
    <?php include('sidebar.php'); ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Parties/Vendors</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Parties</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php if(isset($_SESSION['success'])): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
          </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Parties/Vendors</h3>
                <div class="card-tools">
                  <a href="add_party.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Party
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="partiesTable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Party ID</th>
                      <th>Party Name</th>
                      <th>Type</th>
                      <th>Contact</th>
                      <th>Email</th>
                      <th>GST/PAN</th>
                      <th>Balance</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($parties) > 0): ?>
                      <?php $counter = 1; ?>
                      <?php foreach($parties as $party): ?>
                        <tr>
                          <td><?php echo $counter++; ?></td>
                          <td><?php echo htmlspecialchars($party['party_id']); ?></td>
                          <td><?php echo htmlspecialchars($party['party_name']); ?></td>
                          <td>
                            <?php 
                              $type_class = '';
                              $type_text = '';
                              switch($party['party_type']) {
                                case 'customer': $type_class = 'badge-customer'; $type_text = 'Customer'; break;
                                case 'vendor': $type_class = 'badge-vendor'; $type_text = 'Vendor'; break;
                                case 'both': $type_class = 'badge-both'; $type_text = 'Both'; break;
                              }
                            ?>
                            <span class="party-badge <?php echo $type_class; ?>">
                              <?php echo $type_text; ?>
                            </span>
                          </td>
                          <td><?php echo htmlspecialchars($party['phone'] ?: $party['mobile']); ?></td>
                          <td><?php echo htmlspecialchars($party['email']); ?></td>
                          <td>
                            <?php 
                              if($party['gst_no']) {
                                echo 'GST: ' . htmlspecialchars($party['gst_no']);
                              } elseif($party['pan_no']) {
                                echo 'PAN: ' . htmlspecialchars($party['pan_no']);
                              } else {
                                echo '-';
                              }
                            ?>
                          </td>
                          <td>
                            <?php 
                              $balance = number_format($party['opening_balance'], 2);
                              $balance_class = $party['balance_type'] == 'debit' ? 'text-danger' : 'text-success';
                              $balance_sign = $party['balance_type'] == 'debit' ? 'Dr' : 'Cr';
                              echo "<span class='$balance_class'>₹$balance $balance_sign</span>";
                            ?>
                          </td>
                          <td>
                            <?php if($party['status'] == 'active'): ?>
                              <span class="status-active"><i class="fas fa-check-circle"></i> Active</span>
                            <?php else: ?>
                              <span class="status-inactive"><i class="fas fa-times-circle"></i> Inactive</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <a href="edit_party.php?id=<?php echo $party['id']; ?>" class="btn btn-info btn-sm" title="Edit">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a href="?toggle_status=<?php echo $party['id']; ?>" class="btn btn-warning btn-sm" title="Toggle Status">
                              <i class="fas fa-toggle-on"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $party['id']; ?>)" class="btn btn-danger btn-sm" title="Delete">
                              <i class="fas fa-trash"></i>
                            </button>
                            <a href="view_party.php?id=<?php echo $party['id']; ?>" class="btn btn-primary btn-sm" title="View Details">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="10" class="text-center">No parties found. <a href="add_party.php">Add your first party</a></td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(function () {
  $('#partiesTable').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });
});

function confirmDelete(partyId) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'parties.php?delete_id=' + partyId;
    }
  });
}
</script>
</body>
</html>