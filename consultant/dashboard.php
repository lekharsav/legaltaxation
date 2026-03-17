<?php
session_start();
include("../db.php");

// Check if CA is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../ca-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Get CA details
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");

if($sql->num_rows == 0){
    session_destroy();
    header("Location: ../ca-login.php");
    exit();
}

$ca = $sql->fetch_assoc();

// Set CA variables for use in the dashboard
$ca_image = $ca["image"];
$ca_name = $ca["name"];
$ca_contact = $ca["cont"];
$ca_email = $ca["email"];
$ca_reg_no = $ca["reg_no"];
$ca_id_number = $ca["ca_id"];

// Get assigned services statistics for dashboard
$assigned_stats = [
    'total' => 0,
    'pending' => 0,
    'completed' => 0
];

$stats_sql = $con->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status='0' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status='1' THEN 1 ELSE 0 END) as completed
    FROM apply WHERE send_to = '$ca_id'");

if($stats_row = $stats_sql->fetch_assoc()){
    $assigned_stats = $stats_row;
}

// Get total unique customers
$customers_sql = $con->query("SELECT COUNT(DISTINCT cid) as total_customers FROM apply WHERE send_to = '$ca_id'");
$total_customers = $customers_sql->fetch_assoc()['total_customers'] ?? 0;

// Get recent assigned services (last 5)
$recent_services = [];
$recent_sql = $con->query("SELECT 
    a.id as application_id,
    a.created as application_date,
    a.status as application_status,
    c.name as customer_name,
    s.title as service_title
    FROM apply a
    LEFT JOIN customer c ON a.cid = c.id
    LEFT JOIN service s ON a.sid = s.id
    WHERE a.send_to = '$ca_id'
    ORDER BY a.created DESC
    LIMIT 5");

while($row = $recent_sql->fetch_assoc()){
    $recent_services[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CA Dashboard - Legal Taxation</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .ca-profile-info {
      background: linear-gradient(45deg, #007bff, #6610f2);
      color: white;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .ca-profile-info h5 {
      margin-bottom: 5px;
      font-weight: bold;
    }
    .recent-service-item {
      border-left: 3px solid #007bff;
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

  <!-- Main Sidebar Container -->
  <?php include('sidebar.php'); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">CA Dashboard</h1>
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
            <div class="ca-profile-info">
              <div class="row">
                <div class="col-md-8">
                  <h5>Welcome, <?php echo $ca_name; ?>!</h5>
                  <p>CA ID: <?php echo $ca_id_number; ?> | Email: <?php echo $ca_email; ?> | Mobile: <?php echo $ca_contact; ?></p>
                  <?php if(!empty($ca_reg_no)): ?>
                    <p>Registration No: <?php echo $ca_reg_no; ?></p>
                  <?php endif; ?>
                  <p><small>Last login: Today at <?php echo date('h:i A'); ?></small></p>
                </div>
                <div class="col-md-4 text-right">
                  <a href="profile.php" class="btn btn-light btn-sm">Edit Profile</a>
                  <a href="assigned_services.php" class="btn btn-light btn-sm mt-2">View All Services</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $assigned_stats['total']; ?></h3>
                <p>Total Assignments</p>
              </div>
              <div class="icon">
                <i class="fas fa-tasks"></i>
              </div>
              <a href="assigned_services.php" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $assigned_stats['pending']; ?></h3>
                <p>Pending</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
              <a href="assigned_services.php?filter=pending" class="small-box-footer">View Pending <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $assigned_stats['completed']; ?></h3>
                <p>Completed</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <a href="assigned_services.php?filter=completed" class="small-box-footer">View Completed <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?php echo $total_customers; ?></h3>
                <p>Unique Customers</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <a href="my-customers.php" class="small-box-footer">View Customers <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Recent Assignments -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-history mr-2"></i>Recent Assignments
                </h3>
                <div class="card-tools">
                  <span class="badge badge-primary">Last 5</span>
                </div>
              </div>
              <div class="card-body">
                <?php if(empty($recent_services)): ?>
                  <div class="text-center py-3">
                    <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                    <p class="text-muted">No assignments yet</p>
                  </div>
                <?php else: ?>
                  <div class="list-group">
                    <?php foreach($recent_services as $service): ?>
                      <div class="recent-service-item">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <strong><?php echo htmlspecialchars($service['customer_name']); ?></strong>
                            <br>
                            <small class="text-muted">
                              <?php echo htmlspecialchars($service['service_title']); ?>
                              | Applied: <?php echo date('d M', strtotime($service['application_date'])); ?>
                            </small>
                          </div>
                          <div>
                            <?php if($service['application_status'] == '0'): ?>
                              <span class="badge badge-warning">Pending</span>
                            <?php else: ?>
                              <span class="badge badge-success">Completed</span>
                            <?php endif; ?>
                            <a href="view_assigned_service.php?application_id=<?php echo $service['application_id']; ?>" 
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
                <a href="assigned_services.php" class="btn btn-primary btn-sm">
                  <i class="fas fa-list"></i> View All Assignments
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
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
                    <a href="assigned_services.php" class="btn btn-outline-primary btn-block mb-2">
                      <i class="fas fa-cogs"></i> Manage Services
                    </a>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <a href="my-customers.php" class="btn btn-outline-success btn-block mb-2">
                      <i class="fas fa-users"></i> View Customers
                    </a>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <a href="documents.php" class="btn btn-outline-info btn-block mb-2">
                      <i class="fas fa-file-alt"></i> Documents
                    </a>
                  </div>
                  <div class="col-md-3 col-sm-6">
                    <a href="profile.php" class="btn btn-outline-warning btn-block mb-2">
                      <i class="fas fa-user-edit"></i> Edit Profile
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
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
  console.log('CA Dashboard loaded successfully');
  
  // Auto-refresh dashboard every 60 seconds
  setInterval(function() {
    console.log('Refreshing dashboard...');
    // You could implement partial refresh here if needed
  }, 60000);
});
</script>
</body>
</html>