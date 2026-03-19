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

  <!-- Google Font: Inter (modern, clean) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE 3) - we keep for layout but override heavily -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Modern Dashboard Styles -->
  <!-- (CSS is omitted as requested) -->
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
      background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
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
      color: #2563eb;
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

    .section-body {
      padding: 1.5rem 1.75rem;
    }

    .section-footer {
      padding: 1rem 1.75rem;
      border-top: 1px solid #f1f5f9;
      background: #f8fafc;
    }

    /* Recent Assignments List */
    .assignment-list {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .assignment-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.75rem 1rem;
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      transition: all 0.2s;
    }

    .assignment-item:hover {
      background: #ffffff;
      border-color: #e2e8f0;
    }

    .assignment-info {
      flex: 1;
    }

    .assignment-info strong {
      font-weight: 600;
      color: #0f172a;
      font-size: 0.95rem;
    }

    .assignment-info .meta {
      font-size: 0.8rem;
      color: #64748b;
      margin-top: 0.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .assignment-info .meta i {
      color: #3b82f6;
      width: 14px;
    }

    .status-badge {
      padding: 0.25rem 0.75rem;
      border-radius: 100px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-completed { background: #d1fae5; color: #065f46; }

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
      grid-template-columns: repeat(4, 1fr);
      gap: 1rem;
    }

    @media (max-width: 992px) {
      .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .quick-actions-grid {
        grid-template-columns: 1fr;
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
    }

    .quick-action-card i {
      font-size: 1.75rem;
      color: #3b82f6;
    }

    .quick-action-card span {
      font-weight: 500;
      font-size: 0.85rem;
    }

    /* Empty state */
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

  <!-- Main Sidebar Container -->
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
                CA Dashboard
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

        <!-- Welcome Card -->
        <div class="welcome-card">
          <div class="avatar-wrapper">
            <?php if(!empty($ca_image)): ?>
              <img src="../uploads/ca/<?php echo $ca_image; ?>" 
                   alt="<?php echo htmlspecialchars($ca_name); ?>"
                   onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-user-tie\'></i>';">
            <?php else: ?>
              <i class="fas fa-user-tie"></i>
            <?php endif; ?>
          </div>
          <div class="welcome-text">
            <h2>Welcome, <?php echo htmlspecialchars($ca_name); ?>!</h2>
            <p><i class="fas fa-id-card"></i> CA ID: <?php echo htmlspecialchars($ca_id_number); ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($ca_email); ?></p>
            <p><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($ca_contact); ?></p>
            <?php if(!empty($ca_reg_no)): ?>
              <p><i class="fas fa-certificate"></i> Reg. No: <?php echo htmlspecialchars($ca_reg_no); ?></p>
            <?php endif; ?>
            <small><i class="fas fa-clock"></i> Last login: Today at <?php echo date('h:i A'); ?></small>
          </div>
          <div class="welcome-actions">
            <a href="profile.php" class="btn-outline-modern">
              <i class="fas fa-user-edit"></i> Edit Profile
            </a>
            <a href="assigned_services.php" class="btn-solid-modern">
              <i class="fas fa-tasks"></i> View All Services
            </a>
          </div>
        </div>

        <!-- KPI Cards -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
              <i class="fas fa-tasks"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Assignments</div>
              <div class="kpi-value"><?php echo $assigned_stats['total']; ?></div>
              <div class="kpi-trend"><i class="fas fa-arrow-up"></i> All time</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #fef9c3; color: #a16207;">
              <i class="fas fa-clock"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Pending</div>
              <div class="kpi-value"><?php echo $assigned_stats['pending']; ?></div>
              <div class="kpi-trend"><i class="fas fa-hourglass-half"></i> In progress</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #dcfce7; color: #166534;">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Completed</div>
              <div class="kpi-value"><?php echo $assigned_stats['completed']; ?></div>
              <div class="kpi-trend"><i class="fas fa-check-double"></i> Done</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
              <i class="fas fa-users"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Unique Customers</div>
              <div class="kpi-value"><?php echo $total_customers; ?></div>
              <div class="kpi-trend"><i class="fas fa-user-plus"></i> Served</div>
            </div>
          </div>
        </div>

        <!-- Recent Assignments Card -->
        <div class="section-card">
          <div class="section-header">
            <h3>
              <i class="fas fa-history"></i> Recent Assignments
            </h3>
            <span class="badge" style="background: #f1f5f9; color: #334155; padding: 0.25rem 0.75rem; border-radius: 100px;">Last 5</span>
          </div>
          <div class="section-body">
            <?php if(empty($recent_services)): ?>
              <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No assignments yet.</p>
              </div>
            <?php else: ?>
              <div class="assignment-list">
                <?php foreach($recent_services as $service): ?>
                  <div class="assignment-item">
                    <div class="assignment-info">
                      <strong><?php echo htmlspecialchars($service['customer_name']); ?></strong>
                      <div class="meta">
                        <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($service['service_title']); ?></span>
                        <span><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($service['application_date'])); ?></span>
                      </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <?php if($service['application_status'] == '0'): ?>
                        <span class="status-badge badge-pending">Pending</span>
                      <?php else: ?>
                        <span class="status-badge badge-completed">Completed</span>
                      <?php endif; ?>
                      <a href="view_assigned_service.php?application_id=<?php echo $service['application_id']; ?>" 
                         class="btn-icon-modern">
                        <i class="fas fa-eye"></i> View
                      </a>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="section-footer">
            <a href="assigned_services.php" class="btn-icon-modern">
              <i class="fas fa-list"></i> View All Assignments
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
              <a href="assigned_services.php" class="quick-action-card">
                <i class="fas fa-cogs"></i>
                <span>Manage Services</span>
              </a>
              <a href="my-customers.php" class="quick-action-card">
                <i class="fas fa-users"></i>
                <span>View Customers</span>
              </a>
              <a href="documents.php" class="quick-action-card">
                <i class="fas fa-file-alt"></i>
                <span>Documents</span>
              </a>
              <a href="profile.php" class="quick-action-card">
                <i class="fas fa-user-edit"></i>
                <span>Edit Profile</span>
              </a>
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
  console.log('CA Dashboard loaded - modern design');
});
</script>
</body>
</html>