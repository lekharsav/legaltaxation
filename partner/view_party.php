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

$ca_name = $ca["name"];
$ca_image = $ca["image"];

// Get party ID from URL
if(!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Party ID not specified!";
    header("Location: parties.php");
    exit();
}

$party_id = $_GET['id'];

// Get party details
$sql = $con->query("SELECT * FROM parties WHERE id='$party_id' AND created_by='$ca_id'");
if($sql->num_rows == 0) {
    $_SESSION['error'] = "Party not found or access denied!";
    header("Location: parties.php");
    exit();
}

$party = $sql->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Party - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .party-detail-card {
      border-left: 4px solid #007bff;
    }
    .detail-label {
      font-weight: bold;
      color: #495057;
    }
    .detail-value {
      color: #6c757d;
    }
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
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">View Party</a>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php include('sidebar.php'); ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Party Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="parties.php">Parties</a></li>
              <li class="breadcrumb-item active">View Party</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card party-detail-card">
              <div class="card-header">
                <h3 class="card-title">
                  <?php echo htmlspecialchars($party['party_name']); ?>
                  <span class="badge badge-info float-right">
                    <?php 
                      switch($party['party_type']) {
                        case 'customer': echo 'Customer'; break;
                        case 'vendor': echo 'Vendor'; break;
                        case 'both': echo 'Customer & Vendor'; break;
                      }
                    ?>
                  </span>
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-id-card"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Party ID</span>
                        <span class="info-box-number"><?php echo $party['party_id']; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="info-box">
                      <span class="info-box-icon bg-success"><i class="fas fa-balance-scale"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Opening Balance</span>
                        <span class="info-box-number">
                          ₹<?php echo number_format($party['opening_balance'], 2); ?>
                          <?php echo $party['balance_type'] == 'debit' ? 'Dr' : 'Cr'; ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-6">
                    <h5><i class="fas fa-user-tie"></i> Basic Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <td class="detail-label" width="40%">Contact Person</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['contact_person'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Email</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['email']); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Phone</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['phone'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Mobile</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['mobile']); ?></td>
                      </tr>
                    </table>
                  </div>

                  <div class="col-md-6">
                    <h5><i class="fas fa-file-invoice-dollar"></i> Tax Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <td class="detail-label" width="40%">GST Number</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['gst_no'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">PAN Number</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['pan_no'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Credit Limit</td>
                        <td class="detail-value">₹<?php echo number_format($party['credit_limit'], 2); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Payment Terms</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['payment_terms'] ?: 'N/A'); ?></td>
                      </tr>
                    </table>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-6">
                    <h5><i class="fas fa-map-marker-alt"></i> Address Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <td class="detail-label" width="40%">Address</td>
                        <td class="detail-value"><?php echo nl2br(htmlspecialchars($party['address'] ?: 'N/A')); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">City</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['city'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">State</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['state'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Pincode</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['pincode'] ?: 'N/A'); ?></td>
                      </tr>
                    </table>
                  </div>

                  <div class="col-md-6">
                    <h5><i class="fas fa-university"></i> Bank Details</h5>
                    <table class="table table-sm">
                      <tr>
                        <td class="detail-label" width="40%">Bank Name</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['bank_name'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Account Number</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['bank_account'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">IFSC Code</td>
                        <td class="detail-value"><?php echo htmlspecialchars($party['ifsc_code'] ?: 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Status</td>
                        <td class="detail-value">
                          <?php if($party['status'] == 'active'): ?>
                            <span class="badge badge-success">Active</span>
                          <?php else: ?>
                            <span class="badge badge-danger">Inactive</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                    <div class="card-footer">
                      <a href="edit_party.php?id=<?php echo $party['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Party
                      </a>
                      <a href="parties.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                      </a>
                      <span class="float-right text-muted">
                        Created: <?php echo date('d M Y, h:i A', strtotime($party['created_date'])); ?>
                      </span>
                    </div>
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
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>