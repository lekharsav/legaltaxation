<?php
session_start();
include("../db.php");

// Check if PARTNER is logged in
if(!isset($_COOKIE['tax_partner_log']) || empty($_COOKIE['tax_partner_log'])){
    header("Location: ../partner-login.php");
    exit();
}

$partner_id = $_COOKIE['tax_partner_log'];

// Get partner details
$sql = $con->query("SELECT * FROM partner WHERE id='$partner_id' AND status='1'");

if(!$sql || $sql->num_rows == 0){
    setcookie("tax_partner_log", "", time() - 3600, "/");
    header("Location: ../partner-login.php");
    exit();
}

$partner = $sql->fetch_assoc();

// Set partner variables for use in sidebar and other includes
$partner_name = $partner["name"];
$partner_email = $partner["email"];
$partner_contact = $partner["contact"];
$partner_image = $partner["image"];
$business_name = $partner["business_name"];
$business_type = $partner["business_type"];
$gst_number = $partner["gst_number"];
$created_date = $partner["created"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Partner Dashboard - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .partner-profile-info {
      background: linear-gradient(45deg, #ff7e5f, #feb47b);
      color: white;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .partner-profile-info h5 {
      margin-bottom: 5px;
      font-weight: bold;
    }
    .quick-action-btn {
      transition: all 0.3s;
    }
    .quick-action-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Include Navbar -->
  <?php include('navbar.php'); ?>

  <!-- Include Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Partner Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Welcome Message -->
        <div class="row">
          <div class="col-12">
            <div class="partner-profile-info">
              <div class="row align-items-center">
                <div class="col-md-8">
                  <h5>Welcome back, <?php echo htmlspecialchars($partner_name); ?>!</h5>
                  <p><i class="fas fa-building mr-1"></i> <?php echo htmlspecialchars($business_name); ?> | 
                     <i class="fas fa-envelope mr-1 ml-2"></i> <?php echo htmlspecialchars($partner_email); ?></p>
                  <p><i class="fas fa-phone mr-1"></i> <?php echo htmlspecialchars($partner_contact); ?> | 
                     <i class="fas fa-briefcase mr-1 ml-2"></i> <?php echo htmlspecialchars($business_type); ?></p>
                  <?php if(!empty($gst_number)): ?>
                    <p><i class="fas fa-file-invoice-dollar mr-1"></i> GST: <?php echo htmlspecialchars($gst_number); ?></p>
                  <?php endif; ?>
                  <?php if(!empty($created_date)): ?>
                    <small><i class="fas fa-calendar-alt mr-1"></i> Partner since: <?php echo date('F d, Y', strtotime($created_date)); ?></small>
                  <?php endif; ?>
                </div>
                <div class="col-md-4 text-right">
                  <a href="my-profile.php" class="btn btn-light btn-sm mb-2">
                    <i class="fas fa-user-edit mr-1"></i> Edit Profile
                  </a>
                  <a href="refer-client.php" class="btn btn-outline-light btn-sm mb-2">
                    <i class="fas fa-user-plus mr-1"></i> Refer Client
                  </a>
                  <a href="../index.php" class="btn btn-outline-light btn-sm mb-2">
                    <i class="fas fa-globe mr-1"></i> Main Website
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>0</h3>
                <p>Total Referrals</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="referrals.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>₹0</h3>
                <p>Total Earnings</p>
              </div>
              <div class="icon">
                <i class="fas fa-rupee-sign"></i>
              </div>
              <a href="earnings.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>0</h3>
                <p>Pending Commissions</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
              <a href="commissions.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>0</h3>
                <p>Active Campaigns</p>
              </div>
              <div class="icon">
                <i class="fas fa-bullhorn"></i>
              </div>
              <a href="campaigns.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>Quick Actions</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3 col-6 text-center mb-3">
                    <a href="refer-client.php" class="btn btn-outline-primary btn-block quick-action-btn py-3">
                      <i class="fas fa-user-plus fa-2x mb-2"></i><br>
                      <strong>Refer a Client</strong><br>
                      <small class="text-muted">Earn commissions</small>
                    </a>
                  </div>
                  <div class="col-md-3 col-6 text-center mb-3">
                    <a href="resources.php" class="btn btn-outline-success btn-block quick-action-btn py-3">
                      <i class="fas fa-download fa-2x mb-2"></i><br>
                      <strong>Resources</strong><br>
                      <small class="text-muted">Tools & materials</small>
                    </a>
                  </div>
                  <div class="col-md-3 col-6 text-center mb-3">
                    <a href="support.php" class="btn btn-outline-warning btn-block quick-action-btn py-3">
                      <i class="fas fa-headset fa-2x mb-2"></i><br>
                      <strong>Support</strong><br>
                      <small class="text-muted">Get help</small>
                    </a>
                  </div>
                  <div class="col-md-3 col-6 text-center mb-3">
                    <a href="documents.php" class="btn btn-outline-info btn-block quick-action-btn py-3">
                      <i class="fas fa-file-contract fa-2x mb-2"></i><br>
                      <strong>Documents</strong><br>
                      <small class="text-muted">Agreements & forms</small>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history mr-2"></i>Recent Activity</h3>
              </div>
              <div class="card-body">
                <div class="alert alert-info">
                  <i class="icon fas fa-info-circle"></i>
                  No recent activity. Start by referring your first client!
                </div>
                <a href="refer-client.php" class="btn btn-primary">
                  <i class="fas fa-user-plus mr-2"></i>Make Your First Referral
                </a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>

  <!-- Include Footer -->
  <?php include('footer.php'); ?>
</div>

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
  // Initialize any dashboard scripts here
  console.log('Partner Dashboard loaded successfully');
  
  // Auto-refresh notifications (optional)
  setInterval(function() {
    // You can add notification refresh logic here
  }, 30000);
});
</script>
</body>
</html>