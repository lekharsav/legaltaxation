<?php
session_start();
include("../db.php");

// Check if CUSTOMER is logged in (using cookie as shown in header.php)
if(!isset($_COOKIE['tax_customer_log']) || empty($_COOKIE['tax_customer_log'])){
    header("Location: ../user-login.php");
    exit();
}

$customer_id = $_COOKIE['tax_customer_log'];

// Get customer details from 'customer' table (singular)
$sql = $con->query("SELECT * FROM customer WHERE id='$customer_id' AND status='1'");

if(!$sql || $sql->num_rows == 0){
    // Customer not found or inactive
    setcookie("tax_customer_log", "", time() - 3600, "/");
    header("Location: ../user-login.php");
    exit();
}

$customer = $sql->fetch_assoc();

// Set customer variables for use in the dashboard
$customer_name = $customer["name"];
$customer_email = $customer["email"];
$customer_contact = $customer["contact"];
$customer_image = $customer["image"];
$created_date = $customer["created"];

// Get customer statistics
$active_services = 0;
$total_services = 0;
$total_spent = 0;
$pending_services = 0;

// Get services count - FIXED: specify table for 'status' column
$services_sql = $con->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN a.status='0' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN a.status='1' THEN 1 ELSE 0 END) as completed,
    SUM(s.o_price) as total_spent
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    WHERE a.cid = '$customer_id'");

if($services_sql && $services_row = $services_sql->fetch_assoc()){
    $total_services = $services_row['total'] ?? 0;
    $pending_services = $services_row['pending'] ?? 0;
    $active_services = $services_row['pending'] ?? 0; // Pending services are active
    $total_spent = $services_row['total_spent'] ?? 0;
}

// Get documents count
$documents_count = 0;
$doc_sql = $con->query("SELECT COUNT(*) as count FROM req_doc WHERE cid='$customer_id'");
if($doc_sql && $doc_row = $doc_sql->fetch_assoc()){
    $documents_count = $doc_row['count'];
}

// Get recent services - FIXED: specify table for 'status' column
$recent_services = [];
$recent_sql = $con->query("SELECT 
    a.id as application_id,
    a.created as purchase_date,
    a.status as service_status,
    s.title as service_title,
    s.o_price as price
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    WHERE a.cid = '$customer_id'
    ORDER BY a.created DESC
    LIMIT 5");

if($recent_sql){
    while($row = $recent_sql->fetch_assoc()){
        $recent_services[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Dashboard - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .customer-profile-info {
      background: linear-gradient(45deg, #28a745, #20c997);
      color: white;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .customer-profile-info h5 {
      margin-bottom: 5px;
      font-weight: bold;
    }
    .customer-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
    }
    .customer-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .recent-service-item {
      border-left: 3px solid #28a745;
      margin-bottom: 10px;
      padding: 10px;
      background: #f8f9fa;
      border-radius: 5px;
    }
    .recent-service-item:hover {
      background: #e9ecef;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('navbar.php'); ?>
  <!-- /.navbar -->

  <!-- Include Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Customer Dashboard</h1>
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
            <div class="customer-profile-info">
              <div class="row align-items-center">
                <div class="col-md-2 col-4">
                  <div class="customer-avatar">
                    <?php if(!empty($customer_image)): ?>
                      <img src="../uploads/customers/<?php echo $customer_image; ?>" alt="<?php echo htmlspecialchars($customer_name); ?>"
                           onerror="this.src='../assets/images/default-user.jpg'">
                    <?php else: ?>
                      <i class="fas fa-user"></i>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="col-md-6 col-8">
                  <h5>Welcome back, <?php echo htmlspecialchars($customer_name); ?>!</h5>
                  <p>Email: <?php echo htmlspecialchars($customer_email); ?> | Mobile: <?php echo htmlspecialchars($customer_contact); ?></p>
                  <?php if(!empty($created_date)): ?>
                    <small>Member since: <?php echo date('F d, Y', strtotime($created_date)); ?></small>
                  <?php endif; ?>
                </div>
                <div class="col-md-4 text-right">
                  <a href="my-profile.php" class="btn btn-light btn-sm">Edit Profile</a>
                  <a href="my-services.php" class="btn btn-light btn-sm mt-2">View Services</a>
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
                <h3><?php echo $total_services; ?></h3>
                <p>Total Services</p>
              </div>
              <div class="icon">
                <i class="fas fa-cogs"></i>
              </div>
              <a href="my-services.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $active_services; ?></h3>
                <p>Active Services</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
              <a href="my-services.php?filter=active" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $documents_count; ?></h3>
                <p>Documents</p>
              </div>
              <div class="icon">
                <i class="fas fa-file"></i>
              </div>
              <a href="my-documents.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>₹<?php echo $total_spent; ?></h3>
                <p>Total Spent</p>
              </div>
              <div class="icon">
                <i class="fas fa-rupee-sign"></i>
              </div>
              <a href="my-payments.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Recent Services Section -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-history mr-2"></i>Recent Services
                  <span class="badge badge-primary ml-2">Last 5</span>
                </h3>
              </div>
              <div class="card-body">
                <?php if(empty($recent_services)): ?>
                  <div class="text-center py-3">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-2"></i>
                    <p class="text-muted">No services purchased yet.</p>
                    <a href="../service.php" class="btn btn-primary">
                      <i class="fas fa-plus-circle"></i> Browse Services
                    </a>
                  </div>
                <?php else: ?>
                  <div class="list-group">
                    <?php foreach($recent_services as $service): ?>
                      <div class="recent-service-item">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <strong><?php echo htmlspecialchars($service['service_title']); ?></strong>
                            <br>
                            <small class="text-muted">
                              ₹<?php echo $service['price']; ?> | 
                              Purchased: <?php echo date('d M', strtotime($service['purchase_date'])); ?>
                            </small>
                          </div>
                          <div>
                            <?php if($service['service_status'] == '0'): ?>
                              <span class="badge badge-warning">In Progress</span>
                            <?php else: ?>
                              <span class="badge badge-success">Completed</span>
                            <?php endif; ?>
                            <a href="view-service.php?application_id=<?php echo $service['application_id']; ?>" 
                               class="btn btn-sm btn-info ml-2">
                              <i class="fas fa-eye"></i>
                            </a>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <a href="my-services.php" class="btn btn-primary btn-sm">
                  <i class="fas fa-list"></i> View All Services
                </a>
                <a href="../service.php" class="btn btn-success btn-sm float-right">
                  <i class="fas fa-plus"></i> Buy New Service
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-bolt mr-2"></i>Quick Actions
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3 col-sm-6">
                    <a href="my-services.php" class="btn btn-outline-primary btn-block mb-2">
                      <i class="fas fa-cogs"></i> My Services
                    </a>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <a href="my-documents.php" class="btn btn-outline-success btn-block mb-2">
                      <i class="fas fa-file-alt"></i> Documents
                    </a>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <a href="my-payments.php" class="btn btn-outline-info btn-block mb-2">
                      <i class="fas fa-credit-card"></i> Payments
                    </a>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <a href="../service.php" class="btn btn-outline-warning btn-block mb-2">
                      <i class="fas fa-plus-circle"></i> Buy Service
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
  console.log('Customer Dashboard loaded successfully');
});
</script>
</body>
</html>