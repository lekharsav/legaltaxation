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
      background: linear-gradient(135deg, #fef9c3 0%, #fde047 100%);
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
      color: #854d0e;
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
      padding: 1.5rem 1rem;
      text-align: center;
      text-decoration: none;
      color: #334155;
      transition: all 0.2s;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.75rem;
    }

    .quick-action-card:hover {
      background: #ffffff;
      border-color: #3b82f6;
      color: #3b82f6;
      transform: translateY(-4px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .quick-action-card i {
      font-size: 2rem;
      color: #3b82f6;
    }

    .quick-action-card span {
      font-weight: 600;
      font-size: 0.95rem;
    }

    .quick-action-card small {
      font-size: 0.75rem;
      color: #64748b;
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

  <!-- Include Navbar -->
  <?php include('navbar.php'); ?>

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
                <i class="fas fa-handshake"></i>
                Partner Dashboard
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
            <?php if(!empty($partner_image)): ?>
              <img src="../uploads/partners/<?php echo $partner_image; ?>" 
                   alt="<?php echo htmlspecialchars($partner_name); ?>"
                   onerror="this.src='../assets/images/default-partner.jpg'">
            <?php else: ?>
              <i class="fas fa-user-tie"></i>
            <?php endif; ?>
          </div>
          <div class="welcome-text">
            <h2>Welcome back, <?php echo htmlspecialchars($partner_name); ?>! 🤝</h2>
            <p><i class="fas fa-building"></i> <?php echo htmlspecialchars($business_name); ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($partner_email); ?> | <i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($partner_contact); ?></p>
            <p><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($business_type); ?></p>
            <?php if(!empty($gst_number)): ?>
              <p><i class="fas fa-file-invoice"></i> GST: <?php echo htmlspecialchars($gst_number); ?></p>
            <?php endif; ?>
            <?php if(!empty($created_date)): ?>
              <small><i class="fas fa-calendar-alt"></i> Partner since <?php echo date('F d, Y', strtotime($created_date)); ?></small>
            <?php endif; ?>
          </div>
          <div class="welcome-actions">
            <a href="my-profile.php" class="btn-outline-modern">
              <i class="fas fa-user-edit"></i> Edit Profile
            </a>
            <a href="refer-client.php" class="btn-solid-modern">
              <i class="fas fa-user-plus"></i> Refer Client
            </a>
            <a href="../index.php" class="btn-outline-modern" target="_blank">
              <i class="fas fa-globe"></i> Main Website
            </a>
          </div>
        </div>

        <!-- KPI Cards -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
              <i class="fas fa-users"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Referrals</div>
              <div class="kpi-value">0</div>
              <div class="kpi-trend"><i class="fas fa-user-plus"></i> Start referring</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #dcfce7; color: #166534;">
              <i class="fas fa-indian-rupee-sign"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Earnings</div>
              <div class="kpi-value">₹0</div>
              <div class="kpi-trend"><i class="fas fa-chart-line"></i> Lifetime</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #fef9c3; color: #a16207;">
              <i class="fas fa-clock"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Pending Commissions</div>
              <div class="kpi-value">0</div>
              <div class="kpi-trend"><i class="fas fa-hourglass-half"></i> Awaiting</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
              <i class="fas fa-bullhorn"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Active Campaigns</div>
              <div class="kpi-value">0</div>
              <div class="kpi-trend"><i class="fas fa-rocket"></i> Launch now</div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-card">
          <div class="section-header">
            <h3>
              <i class="fas fa-bolt"></i> Quick Actions
            </h3>
          </div>
          <div class="section-body">
            <div class="quick-actions-grid">
              <a href="refer-client.php" class="quick-action-card">
                <i class="fas fa-user-plus"></i>
                <span>Refer a Client</span>
                <small>Earn commissions</small>
              </a>
              <a href="resources.php" class="quick-action-card">
                <i class="fas fa-download"></i>
                <span>Resources</span>
                <small>Tools & materials</small>
              </a>
              <a href="support.php" class="quick-action-card">
                <i class="fas fa-headset"></i>
                <span>Support</span>
                <small>Get help</small>
              </a>
              <a href="documents.php" class="quick-action-card">
                <i class="fas fa-file-contract"></i>
                <span>Documents</span>
                <small>Agreements</small>
              </a>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="section-card">
          <div class="section-header">
            <h3>
              <i class="fas fa-history"></i> Recent Activity
            </h3>
          </div>
          <div class="section-body">
            <div class="empty-state">
              <i class="fas fa-box-open"></i>
              <p>No recent activity found. Start by referring your first client!</p>
              <a href="refer-client.php" class="btn-solid-modern" style="display: inline-flex;">
                <i class="fas fa-user-plus"></i> Make Your First Referral
              </a>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
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
  console.log('Modern Partner Dashboard loaded');
});
</script>
</body>
</html>