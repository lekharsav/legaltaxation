<?php
session_start();

// Check if customer is logged in using cookie
if(!isset($_COOKIE['tax_customer_log']) || empty($_COOKIE['tax_customer_log'])){
    header("Location: ../user-login.php");
    exit();
}

$customer_id = $_COOKIE['tax_customer_log'];

// Include database connection
include("../db.php");

// Get customer details
$sql = $con->query("SELECT * FROM customer WHERE id='$customer_id' AND status='1'");
if(!$sql || $sql->num_rows == 0){
    setcookie("tax_customer_log", "", time() - 3600, "/");
    header("Location: ../user-login.php");
    exit();
}

$customer = $sql->fetch_assoc();

// Set customer variables for this page
$customer_name = $customer["name"];
$customer_email = $customer["email"];
$customer_contact = $customer["contact"];
$customer_image = $customer["image"] ?? '';
$created_date = $customer["created"];

// Get purchased services with details
$purchased_services = [];
$sql = $con->query("SELECT 
    a.id as application_id,
    a.sid as service_id,
    a.created as purchase_date,
    a.status as service_status,
    a.send_to as assigned_to,
    s.title as service_title,
    s.image as service_image,
    s.cate as category_id,
    s.m_price as market_price,
    s.o_price as paid_price,
    s.s_des as short_description,
    cat.name as category_name,
    ca.name as ca_name
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN cate cat ON s.cate = cat.id
    LEFT JOIN ca ON a.send_to = ca.id
    WHERE a.cid = '$customer_id'
    ORDER BY a.created DESC");

$total_services = $sql->num_rows;
$pending_services = 0;
$completed_services = 0;
$total_spent = 0;

while($row = $sql->fetch_assoc()){
    $purchased_services[] = $row;
    if($row['service_status'] == '0'){
        $pending_services++;
    } elseif($row['service_status'] == '1'){
        $completed_services++;
    }
    $total_spent += $row['paid_price'];
}

// Get recent payments
$recent_payments = [];
$payment_sql = $con->query("SELECT * FROM razorpaybill 
    WHERE cid LIKE '%$customer_id%' 
    ORDER BY created_at DESC 
    LIMIT 5");

if($payment_sql){
    while($row = $payment_sql->fetch_assoc()){
        $recent_payments[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Services - Legal Taxation</title>
  <!-- Google Font: Inter (modern, clean) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE 3) - we keep for layout but will override heavily -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Completely new custom design -->
  <style>
    * {
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: #f5f7fa;
    }

    .wrapper {
      background-color: #f5f7fa;
    }

    /* Override AdminLTE defaults */
    .main-header {
      background-color: #ffffff !important;
      border-bottom: 1px solid #e9ecef;
      box-shadow: none;
    }

    .main-sidebar {
      background-color: #1e293b !important;
      box-shadow: none;
    }

    .content-wrapper {
      background-color: #f5f7fa;
    }

    /* Custom page header */
    .page-header {
      margin-bottom: 2rem;
    }

    .page-header h1 {
      font-weight: 600;
      font-size: 1.8rem;
      color: #1e293b;
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
      color: #6c757d;
    }

    .page-header .breadcrumb .active {
      color: #1e293b;
      font-weight: 500;
    }

    /* KPI Cards - minimal, borderless, with soft shadows */
    .kpi-card {
      background: #ffffff;
      border-radius: 1rem;
      padding: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
      border: 1px solid #f1f4f9;
      transition: all 0.2s;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .kpi-card:hover {
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
      border-color: #e2e8f0;
    }

    .kpi-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
    }

    .kpi-icon i {
      font-size: 1.5rem;
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
      color: #1e293b;
      line-height: 1.2;
    }

    .kpi-footer {
      margin-top: 0.75rem;
      font-size: 0.85rem;
      color: #3b82f6;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      font-weight: 500;
    }

    .kpi-footer:hover {
      color: #2563eb;
    }

    /* Filter bar */
    .filter-bar {
      background: #ffffff;
      border-radius: 1rem;
      padding: 1.25rem;
      border: 1px solid #f1f4f9;
      margin-bottom: 2rem;
      display: flex;
      flex-wrap: wrap;
      align-items: flex-end;
      gap: 1rem;
    }

    .filter-group {
      flex: 1 1 200px;
    }

    .filter-group label {
      display: block;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600;
      color: #64748b;
      margin-bottom: 0.5rem;
    }

    .filter-group select,
    .filter-group input {
      width: 100%;
      padding: 0.6rem 1rem;
      border: 1px solid #e2e8f0;
      border-radius: 0.75rem;
      font-size: 0.9rem;
      color: #1e293b;
      background: #ffffff;
      transition: border 0.2s;
    }

    .filter-group select:focus,
    .filter-group input:focus {
      outline: none;
      border-color: #3b82f6;
    }

    .filter-actions {
      display: flex;
      gap: 0.5rem;
      align-items: center;
    }

    .btn-filter {
      background: #3b82f6;
      color: #ffffff;
      border: none;
      padding: 0.6rem 1.5rem;
      border-radius: 0.75rem;
      font-weight: 500;
      font-size: 0.9rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-filter:hover {
      background: #2563eb;
    }

    .btn-outline {
      background: transparent;
      border: 1px solid #e2e8f0;
      color: #64748b;
      padding: 0.6rem 1.2rem;
      border-radius: 0.75rem;
      font-weight: 500;
      font-size: 0.9rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-outline:hover {
      border-color: #3b82f6;
      color: #3b82f6;
      background: #f8fafc;
    }

    /* Services grid */
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
    }

    .service-item {
      background: #ffffff;
      border-radius: 1rem;
      border: 1px solid #f1f4f9;
      overflow: hidden;
      transition: all 0.2s;
      display: flex;
      flex-direction: column;
    }

    .service-item:hover {
      border-color: #e2e8f0;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    .service-header {
      padding: 1.25rem 1.25rem 0.75rem 1.25rem;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .service-title h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #1e293b;
      margin: 0 0 0.25rem 0;
    }

    .service-category {
      font-size: 0.8rem;
      color: #64748b;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    .service-category i {
      color: #3b82f6;
      font-size: 0.7rem;
    }

    .service-badge {
      padding: 0.25rem 0.75rem;
      border-radius: 2rem;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      white-space: nowrap;
    }

    .badge-pending {
      background: #fff3cd;
      color: #856404;
    }

    .badge-completed {
      background: #d1fae5;
      color: #065f46;
    }

    .badge-other {
      background: #e2e8f0;
      color: #334155;
    }

    .service-details {
      padding: 0 1.25rem 1rem 1.25rem;
      border-bottom: 1px solid #f1f4f9;
    }

    .detail-row {
      display: flex;
      justify-content: space-between;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }

    .detail-label {
      color: #64748b;
    }

    .detail-value {
      font-weight: 500;
      color: #1e293b;
    }

    .service-progress {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #f1f4f9;
    }

    .progress-header {
      display: flex;
      justify-content: space-between;
      font-size: 0.8rem;
      color: #64748b;
      margin-bottom: 0.5rem;
    }

    .progress-bar-bg {
      background: #e9ecef;
      height: 0.5rem;
      border-radius: 1rem;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      border-radius: 1rem;
      transition: width 0.3s;
    }

    .progress-fill.pending {
      background: #f59e0b;
    }

    .progress-fill.completed {
      background: #10b981;
    }

    .service-meta {
      padding: 1rem 1.25rem;
      display: flex;
      gap: 1rem;
      border-bottom: 1px solid #f1f4f9;
    }

    .meta-item {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.8rem;
      color: #64748b;
    }

    .meta-item i {
      color: #3b82f6;
      width: 16px;
    }

    .service-actions {
      padding: 1rem 1.25rem 1.25rem 1.25rem;
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: auto;
    }

    .btn-service {
      flex: 1 1 auto;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 2rem;
      padding: 0.5rem 0.75rem;
      font-size: 0.8rem;
      font-weight: 500;
      color: #334155;
      text-align: center;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      transition: all 0.2s;
    }

    .btn-service:hover {
      background: #ffffff;
      border-color: #3b82f6;
      color: #3b82f6;
    }

    .btn-service.primary {
      background: #3b82f6;
      border-color: #3b82f6;
      color: #ffffff;
    }

    .btn-service.primary:hover {
      background: #2563eb;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      background: #ffffff;
      border-radius: 1rem;
      border: 1px solid #f1f4f9;
    }

    .empty-state i {
      font-size: 4rem;
      color: #cbd5e1;
      margin-bottom: 1.5rem;
    }

    .empty-state h4 {
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 0.75rem;
    }

    .empty-state p {
      color: #64748b;
      margin-bottom: 2rem;
    }

    .empty-state .btn-empty {
      background: #3b82f6;
      color: #ffffff;
      border: none;
      padding: 0.75rem 2rem;
      border-radius: 2rem;
      font-weight: 500;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* Recent payments table */
    .payments-card {
      background: #ffffff;
      border-radius: 1rem;
      border: 1px solid #f1f4f9;
      margin-top: 2rem;
    }

    .payments-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #f1f4f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .payments-header h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #1e293b;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .payments-header h3 i {
      color: #3b82f6;
    }

    .payments-table {
      width: 100%;
      border-collapse: collapse;
    }

    .payments-table th {
      text-align: left;
      padding: 1rem 1.5rem;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 600;
      color: #64748b;
      border-bottom: 1px solid #f1f4f9;
    }

    .payments-table td {
      padding: 1rem 1.5rem;
      font-size: 0.9rem;
      color: #334155;
      border-bottom: 1px solid #f8fafc;
    }

    .payments-table tr:last-child td {
      border-bottom: none;
    }

    .payment-status {
      display: inline-block;
      padding: 0.2rem 0.75rem;
      border-radius: 2rem;
      font-size: 0.7rem;
      font-weight: 600;
    }

    .status-success {
      background: #d1fae5;
      color: #065f46;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
    }

    .payments-footer {
      padding: 1rem 1.5rem;
      border-top: 1px solid #f1f4f9;
      text-align: right;
    }

    .btn-view-all {
      background: transparent;
      border: 1px solid #e2e8f0;
      border-radius: 2rem;
      padding: 0.5rem 1.25rem;
      font-size: 0.8rem;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
    }

    .btn-view-all:hover {
      border-color: #3b82f6;
      color: #3b82f6;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .services-grid {
        grid-template-columns: 1fr;
      }
      .filter-bar {
        flex-direction: column;
        align-items: stretch;
      }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('navbar.php'); ?>
  <!-- /.navbar -->

  <!-- Sidebar -->
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
                <i class="fas fa-file-invoice"></i>
                My Services
              </h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">My Services</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- KPI Cards -->
        <div class="row g-3 mb-4">
          <div class="col-lg-3 col-md-6">
            <div class="kpi-card">
              <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fas fa-briefcase"></i>
              </div>
              <div class="kpi-content">
                <div class="kpi-label">Total Services</div>
                <div class="kpi-value"><?php echo $total_services; ?></div>
              </div>
              <a href="#services-list" class="kpi-footer">View all <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="kpi-card">
              <div class="kpi-icon" style="background: #fef9c3; color: #a16207;">
                <i class="fas fa-clock"></i>
              </div>
              <div class="kpi-content">
                <div class="kpi-label">In Progress</div>
                <div class="kpi-value"><?php echo $pending_services; ?></div>
              </div>
              <a href="#pending" class="kpi-footer">View pending <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="kpi-card">
              <div class="kpi-icon" style="background: #dcfce7; color: #166534;">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="kpi-content">
                <div class="kpi-label">Completed</div>
                <div class="kpi-value"><?php echo $completed_services; ?></div>
              </div>
              <a href="#completed" class="kpi-footer">View completed <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="kpi-card">
              <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
                <i class="fas fa-indian-rupee-sign"></i>
              </div>
              <div class="kpi-content">
                <div class="kpi-label">Total Spent</div>
                <div class="kpi-value">₹<?php echo number_format($total_spent, 2); ?></div>
              </div>
              <a href="my-payments.php" class="kpi-footer">View payments <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
          <div class="filter-group">
            <label>Status</label>
            <select id="statusFilter">
              <option value="all">All Status</option>
              <option value="0">In Progress</option>
              <option value="1">Completed</option>
            </select>
          </div>
          <div class="filter-group">
            <label>Category</label>
            <select id="categoryFilter">
              <option value="all">All Categories</option>
              <?php
              if(!empty($purchased_services)){
                  $categories = array_unique(array_column($purchased_services, 'category_name'));
                  foreach($categories as $category){
                      if($category) {
                          echo "<option value='".htmlspecialchars($category)."'>".htmlspecialchars($category)."</option>";
                      }
                  }
              }
              ?>
            </select>
          </div>
          <div class="filter-group">
            <label>Sort by</label>
            <select id="sortFilter">
              <option value="newest">Newest First</option>
              <option value="oldest">Oldest First</option>
              <option value="price_high">Price: High to Low</option>
              <option value="price_low">Price: Low to High</option>
            </select>
          </div>
          <div class="filter-actions">
            <button class="btn-filter" onclick="applyFilters()">
              <i class="fas fa-sliders-h"></i> Apply
            </button>
            <button class="btn-outline" onclick="resetFilters()">
              <i class="fas fa-undo-alt"></i> Reset
            </button>
          </div>
        </div>

        <!-- Services Grid -->
        <?php if(empty($purchased_services)): ?>
          <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h4>No Services Purchased Yet</h4>
            <p>You haven't purchased any services. Explore our offerings and get started today!</p>
            <a href="../service.php" class="btn-empty">
              <i class="fas fa-plus-circle"></i> Browse Services
            </a>
          </div>
        <?php else: ?>
          <div class="services-grid" id="services-list">
            <?php foreach($purchased_services as $service): 
              // Get form responses count
              $form_count = 0;
              $form_sql = $con->query("SELECT COUNT(*) as count FROM service_form_responses WHERE application_id='".$service['application_id']."'");
              if($form_sql && $form_row = $form_sql->fetch_assoc()){
                $form_count = $form_row['count'];
              }
              
              // Get documents count
              $doc_count = 0;
              $doc_sql = $con->query("SELECT COUNT(*) as count FROM req_doc WHERE sid='".$service['application_id']."'");
              if($doc_sql && $doc_row = $doc_sql->fetch_assoc()){
                $doc_count = $doc_row['count'];
              }
            ?>
              <div class="service-item" 
                   data-status="<?php echo $service['service_status']; ?>"
                   data-category="<?php echo htmlspecialchars($service['category_name']); ?>"
                   data-price="<?php echo $service['paid_price']; ?>"
                   data-date="<?php echo $service['purchase_date']; ?>">
                <div class="service-header">
                  <div class="service-title">
                    <h3><?php echo htmlspecialchars($service['service_title']); ?></h3>
                    <div class="service-category">
                      <i class="fas fa-tag"></i> <?php echo htmlspecialchars($service['category_name'] ?: 'Uncategorized'); ?>
                    </div>
                  </div>
                  <div>
                    <?php if($service['service_status'] == '0'): ?>
                      <span class="service-badge badge-pending">In Progress</span>
                    <?php elseif($service['service_status'] == '1'): ?>
                      <span class="service-badge badge-completed">Completed</span>
                    <?php else: ?>
                      <span class="service-badge badge-other"><?php echo $service['service_status']; ?></span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="service-details">
                  <div class="detail-row">
                    <span class="detail-label">Paid Amount</span>
                    <span class="detail-value">₹<?php echo number_format($service['paid_price']); ?></span>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Market Price</span>
                    <span class="detail-value">₹<?php echo number_format($service['market_price']); ?></span>
                  </div>
                  <div class="detail-row">
                    <span class="detail-label">Purchase Date</span>
                    <span class="detail-value"><?php echo date('d M Y', strtotime($service['purchase_date'])); ?></span>
                  </div>
                </div>

                <div class="service-progress">
                  <div class="progress-header">
                    <span>Progress</span>
                    <span><?php echo $service['service_status'] == '1' ? '100%' : '50%'; ?></span>
                  </div>
                  <div class="progress-bar-bg">
                    <div class="progress-fill <?php echo $service['service_status'] == '1' ? 'completed' : 'pending'; ?>" 
                         style="width: <?php echo $service['service_status'] == '1' ? '100' : '50'; ?>%"></div>
                  </div>
                </div>

                <div class="service-meta">
                  <div class="meta-item">
                    <i class="fas fa-file-alt"></i> Forms: <?php echo $form_count; ?>
                  </div>
                  <div class="meta-item">
                    <i class="fas fa-file-upload"></i> Docs: <?php echo $doc_count; ?>
                  </div>
                  <div class="meta-item">
                    <i class="fas fa-hashtag"></i> #<?php echo $service['application_id']; ?>
                  </div>
                </div>

                <div class="service-actions">
                  <a href="view-service.php?application_id=<?php echo $service['application_id']; ?>" class="btn-service primary">
                    <i class="fas fa-eye"></i> Details
                  </a>
                  <?php if($form_count > 0): ?>
                  <a href="view-form.php?application_id=<?php echo $service['application_id']; ?>" class="btn-service">
                    <i class="fas fa-file-alt"></i> Form
                  </a>
                  <?php endif; ?>
                  <?php if($doc_count > 0): ?>
                  <a href="my-documents.php?service=<?php echo $service['application_id']; ?>" class="btn-service">
                    <i class="fas fa-download"></i> Docs
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- Recent Payments -->
        <?php if(!empty($recent_payments)): ?>
        <div class="payments-card">
          <div class="payments-header">
            <h3><i class="fas fa-credit-card"></i> Recent Payments</h3>
          </div>
          <div class="table-responsive">
            <table class="payments-table">
              <thead>
                <tr>
                  <th>Payment ID</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Method</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($recent_payments as $payment): ?>
                <tr>
                  <td><strong><?php echo $payment['order_id']; ?></strong></td>
                  <td>₹<?php echo number_format($payment['pay_amount'], 2); ?></td>
                  <td>
                    <?php if($payment['payment_status'] == 'Success'): ?>
                      <span class="payment-status status-success">Success</span>
                    <?php else: ?>
                      <span class="payment-status status-pending">Pending</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo date('d M Y', strtotime($payment['created_at'])); ?></td>
                  <td><?php echo ucfirst($payment['payment_option'] ?? 'N/A'); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="payments-footer">
            <a href="my-payments.php" class="btn-view-all">
              View All Payments <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
        <?php endif; ?>

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
    console.log('My Services page - new design');
});

function applyFilters() {
    var status = $('#statusFilter').val();
    var category = $('#categoryFilter').val();
    var sort = $('#sortFilter').val();

    var items = $('.service-item');
    var visibleItems = [];

    items.each(function() {
        var show = true;
        var item = $(this);
        var itemStatus = item.data('status');
        var itemCategory = item.data('category');
        var itemPrice = parseFloat(item.data('price'));
        var itemDate = item.data('date');

        if (status !== 'all' && itemStatus != status) show = false;
        if (category !== 'all' && itemCategory !== category) show = false;

        if (show) {
            item.show();
            visibleItems.push({
                element: item,
                price: itemPrice,
                date: itemDate
            });
        } else {
            item.hide();
        }
    });

    sortItems(visibleItems, sort);
}

function sortItems(items, sortType) {
    var container = $('#services-list');
    items.sort(function(a, b) {
        switch(sortType) {
            case 'newest': return new Date(b.date) - new Date(a.date);
            case 'oldest': return new Date(a.date) - new Date(b.date);
            case 'price_high': return b.price - a.price;
            case 'price_low': return a.price - b.price;
            default: return 0;
        }
    });

    // Detach and reattach in order
    items.forEach(function(item) {
        item.element.detach();
    });
    items.forEach(function(item) {
        container.append(item.element);
    });
}

function resetFilters() {
    $('#statusFilter').val('all');
    $('#categoryFilter').val('all');
    $('#sortFilter').val('newest');
    applyFilters();
}

function printServices() {
    var printWindow = window.open('', '_blank');
    var content = document.getElementById('services-list').innerHTML;
    var style = document.querySelector('style').innerHTML;

    printWindow.document.write(`
        <html>
        <head>
            <title>My Services - <?php echo htmlspecialchars($customer_name); ?></title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Inter', sans-serif; margin: 2rem; color: #1e293b; }
                .print-header { text-align: center; margin-bottom: 2rem; }
                .print-header h2 { color: #3b82f6; }
                .services-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
                .service-item { border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; page-break-inside: avoid; }
                .badge-pending { background: #fff3cd; color: #856404; padding: 0.25rem 0.75rem; border-radius: 2rem; }
                .badge-completed { background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 2rem; }
                ${style}
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>My Purchased Services</h2>
                <p>Customer: <?php echo htmlspecialchars($customer_name); ?> (ID: #<?php echo $customer_id; ?>)</p>
                <p>Generated: <?php echo date('d M Y, h:i A'); ?></p>
                <p>Total: <?php echo $total_services; ?> | In Progress: <?php echo $pending_services; ?> | Completed: <?php echo $completed_services; ?> | Spent: ₹<?php echo number_format($total_spent, 2); ?></p>
            </div>
            <div class="services-grid">
                ${content}
            </div>
            <div style="margin-top: 2rem; text-align: center; font-size: 0.8rem; color: #64748b;">
                Generated by Legal Taxation Customer Panel
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>
</body>
</html>