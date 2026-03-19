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
  <title>Dashboard - Legal Taxation</title>

  <!-- Google Font: Inter (modern, clean) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE 3) - we keep for layout but override heavily -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Modern Dashboard Styles -->
  <style>
    * {
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: #f8fafc;
    }

    .wrapper {
      background-color: #f8fafc;
    }

    /* Override AdminLTE defaults */
    .main-header {
      background-color: #ffffff !important;
      border-bottom: 1px solid #e9ecef;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .content-wrapper {
      background-color: #f8fafc;
    }

    /* Page Header */
    .page-header {
      margin-bottom: 2rem;
    }

    .page-header h1 {
      font-weight: 600;
      font-size: 1.875rem;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
    }

    .page-header h1 i {
      color: #3b82f6;
      margin-right: 0.75rem;
      font-size: 2rem;
    }

    .page-header .breadcrumb {
      background: transparent;
      padding: 0;
      margin: 0;
      font-size: 0.9rem;
    }

    .page-header .breadcrumb a {
      color: #64748b;
    }

    .page-header .breadcrumb .active {
      color: #0f172a;
      font-weight: 500;
    }

    /* Welcome Card */
    .welcome-card {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1.75rem;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      border: 1px solid #f1f5f9;
    }

    .avatar-wrapper {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      flex-shrink: 0;
      border: 3px solid #ffffff;
      box-shadow: 0 8px 12px -4px rgba(0, 0, 0, 0.1);
    }

    .avatar-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .avatar-wrapper i {
      font-size: 2.5rem;
      color: #0284c7;
    }

    .welcome-text {
      flex: 1;
    }

    .welcome-text h2 {
      font-weight: 700;
      font-size: 1.5rem;
      color: #0f172a;
      margin-bottom: 0.5rem;
    }

    .welcome-text p {
      color: #475569;
      margin-bottom: 0.25rem;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .welcome-text p i {
      color: #3b82f6;
      width: 18px;
    }

    .welcome-text small {
      color: #64748b;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .welcome-actions {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .btn-outline-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.6rem 1.25rem;
      font-size: 0.85rem;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
    }

    .btn-outline-modern:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      color: #0f172a;
    }

    .btn-solid-modern {
      background: #3b82f6;
      border: 1px solid #3b82f6;
      border-radius: 100px;
      padding: 0.6rem 1.25rem;
      font-size: 0.85rem;
      font-weight: 500;
      color: #ffffff;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
    }

    .btn-solid-modern:hover {
      background: #2563eb;
      border-color: #2563eb;
    }

    /* KPI Cards */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    @media (max-width: 992px) {
      .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .kpi-grid {
        grid-template-columns: 1fr;
      }
    }

    .kpi-card {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      border: 1px solid #f1f5f9;
      transition: transform 0.2s, box-shadow 0.2s;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }

    .kpi-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
      border-color: #e2e8f0;
    }

    .kpi-icon {
      width: 56px;
      height: 56px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .kpi-icon i {
      font-size: 1.75rem;
    }

    .kpi-content {
      flex: 1;
    }

    .kpi-label {
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      margin-bottom: 0.25rem;
      font-weight: 500;
    }

    .kpi-value {
      font-size: 2rem;
      font-weight: 700;
      color: #0f172a;
      line-height: 1.2;
      margin-bottom: 0.25rem;
    }

    .kpi-trend {
      font-size: 0.8rem;
      color: #10b981;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    /* Section Cards */
    .section-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      margin-bottom: 1.5rem;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .section-header {
      padding: 1.25rem 1.75rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .section-header h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .section-header h3 i {
      color: #3b82f6;
    }

    .section-header .badge {
      background: #f1f5f9;
      color: #334155;
      padding: 0.25rem 0.75rem;
      border-radius: 100px;
      font-size: 0.7rem;
      font-weight: 500;
    }

    .section-body {
      padding: 1.5rem 1.75rem;
    }

    .section-footer {
      padding: 1rem 1.75rem;
      border-top: 1px solid #f1f5f9;
      background: #f8fafc;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Recent Services List */
    .service-list-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 0;
      border-bottom: 1px solid #f1f5f9;
    }

    .service-list-item:last-child {
      border-bottom: none;
    }

    .service-info {
      flex: 1;
    }

    .service-title {
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 0.25rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .service-meta {
      font-size: 0.8rem;
      color: #64748b;
      display: flex;
      gap: 1.5rem;
      align-items: center;
    }

    .service-meta span {
      display: flex;
      align-items: center;
      gap: 0.3rem;
    }

    .service-meta i {
      color: #3b82f6;
      font-size: 0.7rem;
    }

    .status-badge {
      padding: 0.25rem 0.75rem;
      border-radius: 100px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .status-badge.pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-badge.completed {
      background: #d1fae5;
      color: #065f46;
    }

    .btn-icon-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.4rem 1rem;
      font-size: 0.8rem;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      transition: all 0.2s;
    }

    .btn-icon-modern:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      color: #0f172a;
    }

    /* Quick Actions Grid */
    .quick-actions-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 1rem;
    }

    @media (max-width: 992px) {
      .quick-actions-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 576px) {
      .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .quick-action-card {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      padding: 1.25rem 0.75rem;
      text-align: center;
      text-decoration: none;
      color: #334155;
      transition: all 0.2s;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
    }

    .quick-action-card:hover {
      background: #ffffff;
      border-color: #3b82f6;
      color: #3b82f6;
      transform: translateY(-2px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .quick-action-card i {
      font-size: 1.75rem;
      color: #3b82f6;
    }

    .quick-action-card span {
      font-weight: 500;
      font-size: 0.85rem;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 2rem;
      color: #64748b;
    }

    .empty-state i {
      font-size: 3rem;
      color: #cbd5e1;
      margin-bottom: 1rem;
    }

    .empty-state p {
      margin-bottom: 1.5rem;
    }

    /* Footer */
    .main-footer {
      background: #ffffff;
      border-top: 1px solid #f1f5f9;
      color: #64748b;
      font-size: 0.85rem;
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6">
            <div class="page-header">
              <h1>
                <i class="fas fa-chart-pie"></i>
                Dashboard
              </h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Welcome Card (Modern) -->
        <div class="welcome-card">
          <div class="avatar-wrapper">
            <?php if(!empty($customer_image)): ?>
              <img src="../uploads/customers/<?php echo $customer_image; ?>" 
                   alt="<?php echo htmlspecialchars($customer_name); ?>"
                   onerror="this.src='../assets/images/default-user.jpg'">
            <?php else: ?>
              <i class="fas fa-user"></i>
            <?php endif; ?>
          </div>
          <div class="welcome-text">
            <h2>Welcome back, <?php echo htmlspecialchars($customer_name); ?>! 👋</h2>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($customer_email); ?></p>
            <p><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($customer_contact); ?></p>
            <?php if(!empty($created_date)): ?>
              <small><i class="fas fa-calendar-alt"></i> Member since <?php echo date('F d, Y', strtotime($created_date)); ?></small>
            <?php endif; ?>
          </div>
          <div class="welcome-actions">
            <a href="my-profile.php" class="btn-outline-modern">
              <i class="fas fa-user-edit"></i> Edit Profile
            </a>
            <a href="my-services.php" class="btn-solid-modern">
              <i class="fas fa-cogs"></i> View Services
            </a>
          </div>
        </div>

        <!-- KPI Cards -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
              <i class="fas fa-briefcase"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Services</div>
              <div class="kpi-value"><?php echo $total_services; ?></div>
              <div class="kpi-trend"><i class="fas fa-arrow-up"></i> All time</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #fef9c3; color: #a16207;">
              <i class="fas fa-clock"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Active Services</div>
              <div class="kpi-value"><?php echo $active_services; ?></div>
              <div class="kpi-trend"><i class="fas fa-spinner"></i> In progress</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #dcfce7; color: #166534;">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Documents</div>
              <div class="kpi-value"><?php echo $documents_count; ?></div>
              <div class="kpi-trend"><i class="fas fa-cloud-upload-alt"></i> Uploaded</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
              <i class="fas fa-indian-rupee-sign"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Spent</div>
              <div class="kpi-value">₹<?php echo number_format($total_spent, 2); ?></div>
              <div class="kpi-trend"><i class="fas fa-credit-card"></i> Lifetime</div>
            </div>
          </div>
        </div>

        <!-- Recent Services Card -->
        <div class="section-card">
          <div class="section-header">
            <h3>
              <i class="fas fa-history"></i> Recent Services
            </h3>
            <span class="badge">Last 5</span>
          </div>
          <div class="section-body">
            <?php if(empty($recent_services)): ?>
              <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>No services purchased yet.</p>
                <a href="../service.php" class="btn-solid-modern" style="display: inline-flex;">
                  <i class="fas fa-plus-circle"></i> Browse Services
                </a>
              </div>
            <?php else: ?>
              <?php foreach($recent_services as $service): ?>
                <div class="service-list-item">
                  <div class="service-info">
                    <div class="service-title">
                      <?php echo htmlspecialchars($service['service_title']); ?>
                      <?php if($service['service_status'] == '0'): ?>
                        <span class="status-badge pending">In Progress</span>
                      <?php else: ?>
                        <span class="status-badge completed">Completed</span>
                      <?php endif; ?>
                    </div>
                    <div class="service-meta">
                      <span><i class="fas fa-tag"></i> ₹<?php echo number_format($service['price']); ?></span>
                      <span><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($service['purchase_date'])); ?></span>
                    </div>
                  </div>
                  <a href="view-service.php?application_id=<?php echo $service['application_id']; ?>" class="btn-icon-modern">
                    <i class="fas fa-eye"></i> View
                  </a>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <div class="section-footer">
            <a href="my-services.php" class="btn-icon-modern">
              <i class="fas fa-list"></i> View All Services
            </a>
            <a href="../service.php" class="btn-solid-modern">
              <i class="fas fa-plus"></i> Buy New Service
            </a>
          </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="section-card">
          <div class="section-header">
            <h3>
              <i class="fas fa-bolt"></i> Quick Actions
            </h3>
          </div>
          <div class="section-body">
            <div class="quick-actions-grid">
              <a href="my-services.php" class="quick-action-card">
                <i class="fas fa-cogs"></i>
                <span>My Services</span>
              </a>
              <a href="my-documents.php" class="quick-action-card">
                <i class="fas fa-file-alt"></i>
                <span>Documents</span>
              </a>
              <a href="my-payments.php" class="quick-action-card">
                <i class="fas fa-credit-card"></i>
                <span>Payments</span>
              </a>
              <a href="../service.php" class="quick-action-card">
                <i class="fas fa-plus-circle"></i>
                <span>Buy Service</span>
              </a>
              <a href="my-profile.php" class="quick-action-card">
                <i class="fas fa-user"></i>
                <span>Profile</span>
              </a>
              <a href="support-tickets.php" class="quick-action-card">
                <i class="fas fa-headset"></i>
                <span>Support</span>
              </a>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
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
  console.log('Modern Dashboard loaded');
});
</script>
</body>
</html>