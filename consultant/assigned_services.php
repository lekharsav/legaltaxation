<?php
session_start();

// Check if CA is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../ca-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Now include database connection AFTER session check
include("../db.php");

// Get CA details for this page
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
if($sql->num_rows == 0){
    session_destroy();
    header("Location: ../ca-login.php");
    exit();
}

$ca = $sql->fetch_assoc();

// Set CA variables for use in THIS page
$ca_image = $ca["image"];
$ca_name = $ca["name"];
$ca_contact = $ca["cont"];
$ca_email = $ca["email"];
$ca_reg_no = $ca["reg_no"];
$ca_id_number = $ca["ca_id"];

// Filter by user type (customer/partner/all)
$filter_user_type = isset($_GET['user_type']) ? $_GET['user_type'] : 'all';

// Get assigned services with customer/partner details
$assigned_services = [];
$total_assignments = 0;
$pending_assignments = 0;
$completed_assignments = 0;
$customer_count = 0;
$partner_count = 0;

// Build query with filters
$where_clause = "WHERE a.send_to = '$ca_id'";
if($filter_user_type != 'all'){
    $where_clause .= " AND a.user_type = '$filter_user_type'";
}

$sql = $con->query("SELECT 
    a.id as application_id,
    a.cid as user_id,
    a.sid as service_id,
    a.created as application_date,
    a.status as application_status,
    a.user_type,
    a.client_count,
    a.is_partner_price_applied,
    a.unit_price,
    a.total_amount,
    CASE 
        WHEN a.user_type = 'customer' THEN c.name
        WHEN a.user_type = 'partner' THEN p.name
        ELSE 'Unknown'
    END as user_name,
    CASE 
        WHEN a.user_type = 'customer' THEN c.contact
        WHEN a.user_type = 'partner' THEN p.contact
        ELSE ''
    END as user_contact,
    CASE 
        WHEN a.user_type = 'customer' THEN c.email
        WHEN a.user_type = 'partner' THEN p.email
        ELSE ''
    END as user_email,
    s.title as service_title,
    s.cate as category_id,
    s.m_price as market_price,
    s.o_price as original_price,
    s.partner_o_price as partner_price,
    cat.name as category_name,
    p.business_name as partner_business
    FROM apply a
    LEFT JOIN customer c ON a.user_type = 'customer' AND a.cid = c.id
    LEFT JOIN partner p ON a.user_type = 'partner' AND a.partner_id = p.id
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN cate cat ON s.cate = cat.id
    $where_clause
    ORDER BY a.created DESC");

if($sql){
    $total_assignments = $sql->num_rows;
    while($row = $sql->fetch_assoc()){
        $assigned_services[] = $row;
        
        // Count by user type
        if($row['user_type'] == 'customer'){
            $customer_count++;
        } elseif($row['user_type'] == 'partner'){
            $partner_count++;
        }
        
        // Count by status
        if($row['application_status'] == '0'){
            $pending_assignments++;
        } elseif($row['application_status'] == '1'){
            $completed_assignments++;
        }
    }
}

// Get statistics for dashboard
$total_customers = count(array_unique(array_column(
    array_filter($assigned_services, function($s) { return $s['user_type'] == 'customer'; }), 
    'user_id'
)));
$total_partners = count(array_unique(array_column(
    array_filter($assigned_services, function($s) { return $s['user_type'] == 'partner'; }), 
    'user_id'
)));
$total_services = count(array_unique(array_column($assigned_services, 'service_id')));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assigned Services - CA Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter (modern, clean) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE 3) - we keep for layout but override heavily -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables (for functionality only, we'll restyle) -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

  <!-- Modern Styles (matching other dashboards) -->
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

    .kpi-stats {
      font-size: 0.75rem;
      color: #64748b;
      margin-top: 0.5rem;
    }

    .kpi-stats span {
      margin-right: 0.5rem;
    }

    /* Filter Bar */
    .filter-bar {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1.5rem;
      margin-bottom: 2rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .filter-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
    }

    @media (max-width: 768px) {
      .filter-grid {
        grid-template-columns: 1fr;
      }
    }

    .filter-group label {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      font-weight: 600;
      margin-bottom: 0.5rem;
      display: block;
    }

    .filter-group select,
    .filter-group input {
      width: 100%;
      padding: 0.6rem 1rem;
      border: 1px solid #e2e8f0;
      border-radius: 0.75rem;
      font-size: 0.9rem;
      color: #0f172a;
      background: #ffffff;
      transition: border 0.2s;
    }

    .filter-group select:focus,
    .filter-group input:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .user-type-filters {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .user-type-btn {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.5rem 1rem;
      font-size: 0.8rem;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
    }

    .user-type-btn:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }

    .user-type-btn.active {
      background: #3b82f6;
      border-color: #3b82f6;
      color: #ffffff;
    }

    /* Main Card */
    .main-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      margin-bottom: 2rem;
    }

    .card-header-custom {
      padding: 1.25rem 1.75rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-header-custom h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .card-header-custom h3 i {
      color: #3b82f6;
    }

    .card-body-custom {
      padding: 1.5rem 1.75rem;
    }

    .card-footer-custom {
      padding: 1rem 1.75rem;
      border-top: 1px solid #f1f5f9;
      background: #f8fafc;
    }

    /* Table Styling (modern) */
    .table-responsive {
      overflow-x: auto;
    }

    .table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 0.5rem;
    }

    .table thead th {
      border: none;
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      background: #f8fafc;
      padding: 0.75rem 1rem;
    }

    .table tbody tr {
      background: #ffffff;
      border-radius: 1rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
      transition: all 0.2s;
    }

    .table tbody tr:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .table tbody td {
      border: none;
      padding: 1rem;
      vertical-align: middle;
      font-size: 0.9rem;
      color: #334155;
    }

    /* Badges */
    .badge-modern {
      padding: 0.25rem 0.75rem;
      border-radius: 100px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .badge-customer {
      background: #d1fae5;
      color: #065f46;
    }

    .badge-partner {
      background: #dbeafe;
      color: #1e40af;
    }

    .badge-pending {
      background: #fff3cd;
      color: #856404;
    }

    .badge-completed {
      background: #d1fae5;
      color: #065f46;
    }

    .badge-info {
      background: #e0f2fe;
      color: #0284c7;
    }

    .badge-secondary {
      background: #f1f5f9;
      color: #334155;
    }

    /* Buttons */
    .btn-icon-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.4rem 0.75rem;
      font-size: 0.75rem;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      transition: all 0.2s;
    }

    .btn-icon-modern:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }

    .btn-icon-modern i {
      font-size: 0.8rem;
    }

    .btn-group-modern {
      display: flex;
      gap: 0.25rem;
      flex-wrap: wrap;
    }

    /* User info box */
    .user-info-box {
      background: #f8fafc;
      border-radius: 0.75rem;
      padding: 0.5rem;
      font-size: 0.8rem;
    }

    .user-info-box strong {
      color: #0f172a;
    }

    .user-info-box small {
      color: #64748b;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 3rem;
    }

    .empty-state i {
      font-size: 3rem;
      color: #cbd5e1;
      margin-bottom: 1rem;
    }

    .empty-state h4 {
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 0.5rem;
    }

    .empty-state p {
      color: #64748b;
    }

    /* Charts */
    .chart-container {
      position: relative;
      height: 150px;
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

  <!-- Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6">
            <div class="page-header">
              <h1>
                <i class="fas fa-tasks"></i>
                Assigned Services
                <span class="text-muted" style="font-size: 1rem; margin-left: 0.5rem;">(<?php echo $total_assignments; ?> total)</span>
              </h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Assigned Services</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- KPI Cards -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
              <i class="fas fa-tasks"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Assignments</div>
              <div class="kpi-value"><?php echo $total_assignments; ?></div>
              <div class="kpi-stats">
                <span><span class="badge badge-customer">C: <?php echo $customer_count; ?></span></span>
                <span><span class="badge badge-partner">P: <?php echo $partner_count; ?></span></span>
              </div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #fef9c3; color: #a16207;">
              <i class="fas fa-clock"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Pending</div>
              <div class="kpi-value"><?php echo $pending_assignments; ?></div>
              <div class="kpi-trend"><i class="fas fa-hourglass-half"></i> Awaiting action</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #dcfce7; color: #166534;">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Completed</div>
              <div class="kpi-value"><?php echo $completed_assignments; ?></div>
              <div class="kpi-trend"><i class="fas fa-check-double"></i> Delivered</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
              <i class="fas fa-users"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Unique Clients</div>
              <div class="kpi-value"><?php echo $total_customers + $total_partners; ?></div>
              <div class="kpi-stats">
                <span><span class="badge badge-customer">C: <?php echo $total_customers; ?></span></span>
                <span><span class="badge badge-partner">P: <?php echo $total_partners; ?></span></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
          <div class="filter-grid">
            <div class="filter-group">
              <label>User Type</label>
              <div class="user-type-filters">
                <a href="?user_type=all" class="user-type-btn <?php echo $filter_user_type == 'all' ? 'active' : ''; ?>">
                  <i class="fas fa-users"></i> All (<?php echo $total_assignments; ?>)
                </a>
                <a href="?user_type=customer" class="user-type-btn <?php echo $filter_user_type == 'customer' ? 'active' : ''; ?>">
                  <i class="fas fa-user"></i> Customers (<?php echo $customer_count; ?>)
                </a>
                <a href="?user_type=partner" class="user-type-btn <?php echo $filter_user_type == 'partner' ? 'active' : ''; ?>">
                  <i class="fas fa-handshake"></i> Partners (<?php echo $partner_count; ?>)
                </a>
              </div>
            </div>
            <div class="filter-group">
              <label>Status</label>
              <select id="statusFilter" class="form-control">
                <option value="all">All Status</option>
                <option value="0">Pending</option>
                <option value="1">Completed</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Service</label>
              <select id="serviceFilter" class="form-control">
                <option value="all">All Services</option>
                <?php
                if(!empty($assigned_services)){
                    $services = array_unique(array_column($assigned_services, 'service_title'));
                    foreach($services as $service){
                        echo "<option value='".htmlspecialchars($service)."'>".htmlspecialchars($service)."</option>";
                    }
                }
                ?>
              </select>
            </div>
            <div class="filter-group">
              <label>Date</label>
              <input type="date" id="dateFilter" class="form-control">
            </div>
          </div>
        </div>

        <!-- Assignments List -->
        <div class="main-card">
          <div class="card-header-custom">
            <h3><i class="fas fa-list"></i> Your Assigned Services</h3>
            <div>
              <button class="btn-icon-modern" onclick="window.location.reload()">
                <i class="fas fa-sync"></i> Refresh
              </button>
            </div>
          </div>
          <div class="card-body-custom">
            <?php if(empty($assigned_services)): ?>
              <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h4>No Services Assigned Yet</h4>
                <p>You don't have any services assigned to you at the moment.</p>
                <?php if($filter_user_type != 'all'): ?>
                  <p><a href="?user_type=all" class="user-type-btn">View all assignments</a></p>
                <?php endif; ?>
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table" id="servicesTable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>App ID</th>
                      <th>Type</th>
                      <th>User Details</th>
                      <th>Service</th>
                      <th>Clients</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $sl = 1;
                    foreach($assigned_services as $service):
                      // Get form responses count
                      $form_count = 0;
                      $form_sql = $con->query("SELECT COUNT(*) as count FROM service_form_responses WHERE application_id='".$service['application_id']."'");
                      if($form_sql && $form_row = $form_sql->fetch_assoc()){
                        $form_count = $form_row['count'];
                      }
                      
                      // Calculate form completion percentage
                      $total_fields_query = $con->query("SELECT COUNT(*) as count FROM service_form_fields WHERE service_id='".$service['service_id']."'");
                      $total_fields = 0;
                      if($total_fields_query && $field_row = $total_fields_query->fetch_assoc()){
                          $total_fields = $field_row['count'];
                      }
                      $total_expected_fields = $total_fields * ($service['client_count'] ?: 1);
                      $completion_percentage = $total_expected_fields > 0 ? round(($form_count / $total_expected_fields) * 100) : 0;
                      
                      // Get documents count
                      $doc_count = 0;
                      $doc_sql = $con->query("SELECT COUNT(*) as count FROM req_doc WHERE sid='".$service['application_id']."'");
                      if($doc_sql && $doc_row = $doc_sql->fetch_assoc()){
                        $doc_count = $doc_row['count'];
                      }
                    ?>
                      <tr data-status="<?php echo $service['application_status']; ?>" 
                          data-service="<?php echo htmlspecialchars($service['service_title']); ?>"
                          data-date="<?php echo $service['application_date']; ?>">
                        <td><?php echo $sl++; ?></td>
                        <td>
                          <strong>#<?php echo $service['application_id']; ?></strong>
                        </td>
                        <td>
                          <span class="badge-modern <?php echo $service['user_type'] == 'customer' ? 'badge-customer' : 'badge-partner'; ?>">
                            <?php echo ucfirst($service['user_type']); ?>
                          </span>
                          <?php if($service['user_type'] == 'partner' && $service['is_partner_price_applied']): ?>
                            <br><small class="badge badge-info">Partner Price</small>
                          <?php endif; ?>
                        </td>
                        <td>
                          <div class="user-info-box">
                            <strong><?php echo htmlspecialchars($service['user_name']); ?></strong>
                            <?php if($service['user_type'] == 'partner' && $service['partner_business']): ?>
                              <div><small><?php echo htmlspecialchars($service['partner_business']); ?></small></div>
                            <?php endif; ?>
                            <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($service['user_email']); ?></div>
                            <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($service['user_contact']); ?></div>
                            <div><span class="badge badge-secondary">ID: #<?php echo $service['user_id']; ?></span></div>
                          </div>
                        </td>
                        <td>
                          <strong><?php echo htmlspecialchars($service['service_title']); ?></strong>
                          <div><small class="text-muted"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($service['category_name']); ?></small></div>
                          <?php if($service['user_type'] == 'partner' && $service['partner_price']): ?>
                            <span class="badge badge-info"><i class="fas fa-tag"></i> Partner: ₹<?php echo $service['partner_price']; ?></span>
                          <?php else: ?>
                            <span class="badge badge-info"><i class="fas fa-tag"></i> ₹<?php echo $service['original_price']; ?></span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php if($service['user_type'] == 'partner' && $service['client_count'] > 1): ?>
                            <span class="badge badge-secondary"><?php echo $service['client_count']; ?> clients</span>
                            <br>
                            <span class="badge badge-<?php echo $completion_percentage >= 100 ? 'success' : ($completion_percentage > 0 ? 'warning' : 'danger'); ?>">
                              <?php echo $completion_percentage; ?>% complete
                            </span>
                          <?php else: ?>
                            <span class="badge badge-secondary">1 client</span>
                            <br>
                            <?php if($form_count > 0): ?>
                              <span class="badge badge-success">Form filled</span>
                            <?php else: ?>
                              <span class="badge badge-warning">Form pending</span>
                            <?php endif; ?>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php echo date('d M Y', strtotime($service['application_date'])); ?>
                          <br>
                          <small class="text-muted">
                            <?php 
                            $days = floor((time() - strtotime($service['application_date'])) / (60 * 60 * 24));
                            echo $days == 0 ? 'Today' : ($days . ' day(s) ago');
                            ?>
                          </small>
                        </td>
                        <td>
                          <?php if($service['application_status'] == '0'): ?>
                            <span class="badge-modern badge-pending">Pending</span>
                          <?php elseif($service['application_status'] == '1'): ?>
                            <span class="badge-modern badge-completed">Completed</span>
                          <?php else: ?>
                            <span class="badge-modern badge-secondary"><?php echo $service['application_status']; ?></span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <div class="btn-group-modern">
                            <a href="view_assigned_service.php?application_id=<?php echo $service['application_id']; ?>" 
                               class="btn-icon-modern" title="View Full Details">
                              <i class="fas fa-eye"></i> View
                            </a>
                            
                            <?php if($service['user_type'] == 'partner' && $service['client_count'] > 1): ?>
                              <a href="view_partner_clients_ca.php?application_id=<?php echo $service['application_id']; ?>" 
                                 class="btn-icon-modern" title="View All Clients Data">
                                <i class="fas fa-users"></i> Clients
                              </a>
                            <?php else: ?>
                              <a href="view_customer_form.php?application_id=<?php echo $service['application_id']; ?>" 
                                 class="btn-icon-modern" 
                                 title="View Form Data"
                                 <?php if($form_count == 0): ?>onclick="return false;" style="opacity:0.5; pointer-events:none;"<?php endif; ?>>
                                <i class="fas fa-file-alt"></i> Form
                              </a>
                            <?php endif; ?>
                            
                            <button type="button" 
                                    class="btn-icon-modern <?php echo $service['application_status'] == '0' ? 'btn-primary' : 'btn-warning'; ?>"
                                    onclick="updateStatus(<?php echo $service['application_id']; ?>, <?php echo $service['application_status']; ?>)"
                                    title="<?php echo $service['application_status'] == '0' ? 'Mark as Completed' : 'Mark as Pending'; ?>">
                              <i class="fas fa-<?php echo $service['application_status'] == '0' ? 'check' : 'undo'; ?>"></i>
                              <?php echo $service['application_status'] == '0' ? 'Complete' : 'Reopen'; ?>
                            </button>
                            
                            <div class="btn-group-modern">
                              <a href="mailto:<?php echo $service['user_email']; ?>" class="btn-icon-modern" title="Email">
                                <i class="fas fa-envelope"></i>
                              </a>
                              <a href="tel:<?php echo $service['user_contact']; ?>" class="btn-icon-modern" title="Call">
                                <i class="fas fa-phone"></i>
                              </a>
                            </div>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
          <div class="card-footer-custom">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Showing <?php echo count($assigned_services); ?> assigned service(s)
                <?php if($filter_user_type != 'all'): ?>
                  (Filtered by: <?php echo $filter_user_type; ?>)
                <?php endif; ?>
              </small>
              <?php if(!empty($assigned_services)): ?>
              <div class="btn-group-modern">
                <button class="btn-icon-modern" onclick="printTable()">
                  <i class="fas fa-print"></i> Print
                </button>
                <button class="btn-icon-modern" onclick="exportToExcel()">
                  <i class="fas fa-download"></i> Export
                </button>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Service Statistics -->
        <?php if(!empty($assigned_services)): ?>
        <div class="row">
          <div class="col-md-6">
            <div class="main-card">
              <div class="card-header-custom">
                <h3><i class="fas fa-chart-pie"></i> Assignment Distribution</h3>
              </div>
              <div class="card-body-custom">
                <div class="chart-container">
                  <canvas id="assignmentChart" height="150"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="main-card">
              <div class="card-header-custom">
                <h3><i class="fas fa-chart-line"></i> Monthly Assignments</h3>
              </div>
              <div class="card-body-custom">
                <div class="chart-container">
                  <canvas id="monthlyChart" height="150"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div><!-- /.container-fluid -->
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- DataTables (for functionality, but we'll style it minimal) -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable (we keep it for search/pagination, but restyled)
    $('#servicesTable').DataTable({
        "pageLength": 10,
        "responsive": true,
        "autoWidth": false,
        "order": [[6, 'desc']],
        "language": {
            "search": "Search:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "emptyTable": "No assignments found"
        },
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: function() {
            // Style the search input and select
            $('#servicesTable_filter input').css({
                'border': '1px solid #e2e8f0',
                'border-radius': '0.75rem',
                'padding': '0.4rem 0.75rem'
            });
            $('#servicesTable_length select').css({
                'border': '1px solid #e2e8f0',
                'border-radius': '0.75rem',
                'padding': '0.4rem'
            });
        }
    });

    <?php if(!empty($assigned_services)): ?>
    initializeCharts();
    <?php endif; ?>
});

function applyFilters() {
    var status = $('#statusFilter').val();
    var service = $('#serviceFilter').val();
    var date = $('#dateFilter').val();
    
    $('#servicesTable tbody tr').each(function() {
        var show = true;
        var rowStatus = $(this).data('status');
        var rowService = $(this).data('service');
        var rowDate = $(this).data('date');
        
        if(status !== 'all' && rowStatus != status) show = false;
        if(service !== 'all' && rowService !== service) show = false;
        if(date && rowDate !== date) show = false;
        
        if(show) $(this).show(); else $(this).hide();
    });
}

function updateStatus(applicationId, currentStatus) {
    var newStatus = currentStatus == '0' ? '1' : '0';
    var confirmMessage = currentStatus == '0' 
        ? 'Mark this service as completed?' 
        : 'Reopen this service?';
    
    if(confirm(confirmMessage)) {
        $.ajax({
            url: 'update_application_status.php',
            type: 'POST',
            data: {
                application_id: applicationId,
                new_status: newStatus
            },
            success: function(response) {
                alert('Status updated successfully!');
                location.reload();
            },
            error: function() {
                alert('Error updating status.');
            }
        });
    }
}

function printTable() {
    var printContent = document.getElementById('servicesTable').outerHTML;
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Assigned Services Report - <?php echo $ca_name; ?></title>
            <style>
                body { font-family: 'Inter', sans-serif; margin: 2rem; }
                .print-header { text-align: center; margin-bottom: 2rem; }
                .print-header h2 { color: #3b82f6; }
                table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
                th, td { border: 1px solid #e2e8f0; padding: 0.75rem; text-align: left; }
                th { background: #f8fafc; font-weight: 600; }
                .badge-modern { padding: 0.2rem 0.5rem; border-radius: 100px; font-size: 0.7rem; }
                .badge-customer { background: #d1fae5; color: #065f46; }
                .badge-partner { background: #dbeafe; color: #1e40af; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>Assigned Services Report</h2>
                <h3>CA: <?php echo $ca_name; ?> (ID: <?php echo $ca_id_number; ?>)</h3>
                <p>Generated: <?php echo date('d M Y, h:i A'); ?></p>
                <p>Total: <?php echo $total_assignments; ?> | Pending: <?php echo $pending_assignments; ?> | Completed: <?php echo $completed_assignments; ?></p>
            </div>
            ${printContent}
        </body>
        </html>
    `;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

function exportToExcel() {
    var table = document.getElementById("servicesTable");
    var html = table.outerHTML;
    var blob = new Blob([html], {type: 'application/vnd.ms-excel'});
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'assigned_services_<?php echo date('Y-m-d'); ?>.xls';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

<?php if(!empty($assigned_services)): ?>
function initializeCharts() {
    // Assignment Distribution Chart
    var assignmentCtx = document.getElementById('assignmentChart').getContext('2d');
    new Chart(assignmentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Customers (<?php echo $customer_count; ?>)', 'Partners (<?php echo $partner_count; ?>)'],
            datasets: [{
                data: [<?php echo $customer_count; ?>, <?php echo $partner_count; ?>],
                backgroundColor: ['#10b981', '#3b82f6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
    
    // Monthly Assignments Chart
    var monthlyData = {};
    <?php 
    foreach($assigned_services as $service) {
        $month = date('M Y', strtotime($service['application_date']));
        echo "if(!monthlyData['$month']) monthlyData['$month'] = 0;";
        echo "monthlyData['$month']++;";
    }
    ?>
    var monthLabels = Object.keys(monthlyData).slice(-6);
    var monthValues = monthLabels.map(function(month) { return monthlyData[month]; });
    
    var monthCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthCtx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Assignments',
                data: monthValues,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
}
<?php endif; ?>
</script>
</body>
</html>