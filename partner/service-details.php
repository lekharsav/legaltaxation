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
$partner_name = $partner["name"];

// Get application ID from URL
$application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($application_id == 0){
    header("Location: services.php");
    exit();
}

// Get service application details
$app_query = $con->query("
    SELECT 
        a.*,
        s.title as service_name,
        s.image as service_image,
        s.s_des as service_description,
        s.des as service_full_description,
        s.m_price as market_price,
        s.o_price as regular_price,
        s.partner_o_price as partner_price,
        s.min_clients_for_partner,
        c.name as category_name,
        rb.billing_email,
        rb.billing_name,
        rb.billing_mobile,
        rb.payment_status,
        rb.created_at as payment_date
    FROM apply a
    INNER JOIN service s ON a.sid = s.id
    INNER JOIN cate c ON s.cate = c.id
    LEFT JOIN razorpaybill rb ON a.razorpay_order_id = rb.order_id
    WHERE a.id = '$application_id' 
    AND a.partner_id = '$partner_id'
    AND a.user_type = 'partner'
");

if($app_query->num_rows == 0){
    header("Location: services.php");
    exit();
}

$application = $app_query->fetch_assoc();

// Get all clients for this application
$clients_query = $con->query("
    SELECT 
        client_index,
        COUNT(*) as fields_filled,
        MAX(created_at) as last_updated
    FROM service_form_responses 
    WHERE application_id = '$application_id'
    GROUP BY client_index
    ORDER BY client_index
");

$total_clients = $application['client_count'];
$clients_data = [];
$forms_completed = 0;

while($client = $clients_query->fetch_assoc()){
    $clients_data[$client['client_index']] = $client;
    if($client['fields_filled'] >= 5){ // Assuming 5 required fields per client
        $forms_completed++;
    }
}

// Get service form fields
$fields_query = $con->query("
    SELECT * FROM service_form_fields 
    WHERE service_id = '{$application['sid']}'
    ORDER BY field_order
");

// Get all form responses for this application
$responses_query = $con->query("
    SELECT 
        sfr.*,
        sff.field_label,
        sff.field_type,
        sff.field_placeholder
    FROM service_form_responses sfr
    LEFT JOIN service_form_fields sff ON sfr.field_id = sff.id
    WHERE sfr.application_id = '$application_id'
    ORDER BY sfr.client_index, sff.field_order
");

$responses_by_client = [];
while($response = $responses_query->fetch_assoc()){
    $client_index = $response['client_index'];
    if(!isset($responses_by_client[$client_index])){
        $responses_by_client[$client_index] = [];
    }
    $responses_by_client[$client_index][] = $response;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Service Details - Partner Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter (modern, clean) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE 3) - we keep for layout but override heavily -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Modern Styles -->
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

    /* Service Header Card */
    .service-header-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      padding: 1.75rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem;
    }

    .service-header-content h2 {
      font-weight: 700;
      font-size: 1.5rem;
      color: #0f172a;
      margin-bottom: 0.5rem;
    }

    .service-header-content p {
      color: #475569;
      margin-bottom: 0.25rem;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .service-header-content p i {
      color: #3b82f6;
      width: 18px;
    }

    .service-badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 100px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-processing { background: #e0f2fe; color: #0284c7; }
    .badge-completed { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-unknown { background: #f1f5f9; color: #334155; }

    .service-header-actions {
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

    /* Info Cards (two-column) */
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .info-card {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      padding: 1.25rem;
    }

    .info-card h5 {
      font-weight: 600;
      font-size: 0.95rem;
      color: #0f172a;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .info-card h5 i {
      color: #3b82f6;
    }

    .info-card p {
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
      color: #334155;
    }

    .info-card .progress {
      height: 0.5rem;
      border-radius: 1rem;
      background: #e9ecef;
      margin-top: 0.5rem;
    }

    .info-card .progress-bar {
      border-radius: 1rem;
    }

    /* Client Cards */
    .client-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }

    .client-card {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      overflow: hidden;
      transition: all 0.2s;
    }
    .client-card:hover {
      border-color: #e2e8f0;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .client-card-header {
      padding: 1rem 1.25rem;
      background: #f8fafc;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .client-card-header h6 {
      font-weight: 600;
      font-size: 1rem;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .client-card-body {
      padding: 1rem 1.25rem;
    }

    .client-card-body p {
      font-size: 0.85rem;
      color: #475569;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .client-card-body p i {
      color: #3b82f6;
      width: 16px;
    }

    .client-card-actions {
      padding: 1rem 1.25rem;
      border-top: 1px solid #f1f5f9;
      display: flex;
      gap: 0.5rem;
    }

    .btn-sm-modern {
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
      gap: 0.4rem;
      transition: all 0.2s;
    }
    .btn-sm-modern:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }

    .btn-sm-modern-primary {
      background: #e0f2fe;
      border: 1px solid #bae6fd;
      color: #0369a1;
    }
    .btn-sm-modern-primary:hover {
      background: #bae6fd;
    }

    /* Collapsible content */
    .collapse-content {
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      padding: 1rem 1.25rem;
    }

    .response-item {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 0.75rem;
      padding: 0.75rem;
      margin-bottom: 0.75rem;
    }
    .response-item strong {
      font-size: 0.8rem;
      color: #0f172a;
      display: block;
      margin-bottom: 0.25rem;
    }
    .response-item span {
      font-size: 0.85rem;
      color: #334155;
    }

    /* Pricing Table */
    .pricing-table {
      width: 100%;
      font-size: 0.9rem;
    }
    .pricing-table tr td {
      padding: 0.5rem 0;
      border-bottom: 1px solid #f1f5f9;
    }
    .pricing-table tr:last-child td {
      border-bottom: none;
    }
    .pricing-table .highlight {
      font-weight: 700;
      color: #0f172a;
    }
    .savings-alert {
      background: #d1fae5;
      border: 1px solid #a7f3d0;
      border-radius: 1rem;
      padding: 1rem;
      color: #065f46;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-top: 1rem;
    }

    /* Quick Actions */
    .quick-actions-grid {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    /* Modal */
    .modal-content {
      border-radius: 1.5rem;
      border: none;
    }
    .modal-header {
      border-bottom: 1px solid #f1f5f9;
      padding: 1.25rem 1.75rem;
    }
    .modal-body {
      padding: 1.75rem;
    }
    .modal-footer {
      border-top: 1px solid #f1f5f9;
      padding: 1.25rem 1.75rem;
    }
    .form-control {
      border: 1px solid #e2e8f0;
      border-radius: 0.75rem;
      padding: 0.6rem 1rem;
      font-size: 0.9rem;
    }
    .form-control:focus {
      border-color: #3b82f6;
      outline: none;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6">
            <div class="page-header">
              <h1>
                <i class="fas fa-file-invoice"></i>
                Service Details
              </h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="services.php">Services</a></li>
              <li class="breadcrumb-item active">Details</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Service Header Card -->
        <div class="service-header-card">
          <div class="service-header-content">
            <h2><?php echo htmlspecialchars($application['service_name']); ?></h2>
            <p><i class="fas fa-tag"></i> Category: <?php echo htmlspecialchars($application['category_name']); ?></p>
            <p><i class="fas fa-calendar-alt"></i> Purchased: <?php echo date('F d, Y', strtotime($application['created'])); ?></p>
            <p>
              <i class="fas fa-hashtag"></i> Application ID: #<?php echo str_pad($application_id, 6, '0', STR_PAD_LEFT); ?>
              <span class="service-badge 
                <?php
                switch($application['status']) {
                    case '0': echo 'badge-pending'; break;
                    case '1': echo 'badge-processing'; break;
                    case '2': echo 'badge-completed'; break;
                    case '3': echo 'badge-rejected'; break;
                    default: echo 'badge-unknown';
                }
                ?>">
                <?php
                switch($application['status']) {
                    case '0': echo 'Pending'; break;
                    case '1': echo 'Processing'; break;
                    case '2': echo 'Completed'; break;
                    case '3': echo 'Rejected'; break;
                    default: echo 'Unknown';
                }
                ?>
              </span>
            </p>
          </div>
          <div class="service-header-actions">
            <a href="../service-details.php?id=<?php echo $application['sid']; ?>" class="btn-outline-modern" target="_blank">
              <i class="fas fa-external-link-alt"></i> View Service Page
            </a>
            <a href="client-details.php?app_id=<?php echo $application_id; ?>" class="btn-solid-modern">
              <i class="fas fa-users"></i> Manage Clients
            </a>
          </div>
        </div>

        <!-- Main Row: Left Column (8) + Right Column (4) -->
        <div class="row">
          <!-- Left Column: Service Info & Clients -->
          <div class="col-lg-8">

            <!-- Service Information Card -->
            <div class="section-card">
              <div class="section-header">
                <h3><i class="fas fa-info-circle"></i> Service Information</h3>
              </div>
              <div class="section-body">
                <div class="info-grid">
                  <div class="info-card">
                    <h5><i class="fas fa-users"></i> Client Progress</h5>
                    <p><strong>Total Clients:</strong> <?php echo $application['client_count']; ?></p>
                    <p><strong>Forms Completed:</strong> <?php echo $forms_completed; ?> of <?php echo $application['client_count']; ?></p>
                    <div class="progress">
                      <div class="progress-bar <?php echo $forms_completed == $application['client_count'] ? 'bg-success' : 'bg-warning'; ?>" 
                           role="progressbar" 
                           style="width: <?php echo $application['client_count'] > 0 ? ($forms_completed / $application['client_count'] * 100) : 0; ?>%">
                      </div>
                    </div>
                  </div>
                  <div class="info-card">
                    <h5><i class="fas fa-file-alt"></i> Form Progress</h5>
                    <?php 
                    $total_fields = $fields_query->num_rows * $application['client_count'];
                    $fields_filled = $responses_query->num_rows;
                    $progress_percentage = $total_fields > 0 ? round(($fields_filled / $total_fields) * 100) : 0;
                    ?>
                    <p><strong>Fields Filled:</strong> <?php echo $fields_filled; ?> of <?php echo $total_fields; ?></p>
                    <div class="progress">
                      <div class="progress-bar <?php echo $progress_percentage >= 100 ? 'bg-success' : 'bg-info'; ?>" 
                           role="progressbar" 
                           style="width: <?php echo $progress_percentage; ?>%">
                      </div>
                    </div>
                  </div>
                </div>
                <?php if(!empty($application['service_description'])): ?>
                <div style="margin-top: 1rem;">
                  <h5 style="font-weight: 600; font-size: 0.95rem; color: #0f172a; margin-bottom: 0.5rem;">Service Description</h5>
                  <p style="color: #475569;"><?php echo htmlspecialchars($application['service_description']); ?></p>
                </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Clients Overview Card -->
            <div class="section-card">
              <div class="section-header">
                <h3><i class="fas fa-users"></i> Clients Overview</h3>
              </div>
              <div class="section-body">
                <div class="client-grid">
                  <?php for($i = 1; $i <= $application['client_count']; $i++): 
                    $client_data = isset($clients_data[$i]) ? $clients_data[$i] : null;
                    $fields_filled = $client_data ? $client_data['fields_filled'] : 0;
                    $is_complete = $fields_filled >= 5; // Assuming 5 required fields
                  ?>
                  <div class="client-card">
                    <div class="client-card-header">
                      <h6><i class="fas fa-user"></i> Client <?php echo $i; ?></h6>
                      <span class="service-badge <?php echo $is_complete ? 'badge-completed' : 'badge-pending'; ?>">
                        <?php echo $is_complete ? 'Complete' : 'In Progress'; ?>
                      </span>
                    </div>
                    <div class="client-card-body">
                      <p><i class="fas fa-file-alt"></i> <?php echo $fields_filled; ?> fields filled</p>
                      <?php if($client_data && $client_data['last_updated']): ?>
                      <p><i class="fas fa-clock"></i> Last updated: <?php echo date('d M Y', strtotime($client_data['last_updated'])); ?></p>
                      <?php endif; ?>
                    </div>
                    <div class="client-card-actions">
                      <button type="button" class="btn-sm-modern" data-toggle="collapse" data-target="#clientDetails<?php echo $i; ?>">
                        <i class="fas fa-eye"></i> View Details
                      </button>
                      <a href="client-details.php?app_id=<?php echo $application_id; ?>&client=<?php echo $i; ?>" class="btn-sm-modern btn-sm-modern-primary">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                    </div>
                    <!-- Collapsible Details -->
                    <div class="collapse" id="clientDetails<?php echo $i; ?>">
                      <div class="collapse-content">
                        <?php if(isset($responses_by_client[$i]) && !empty($responses_by_client[$i])): ?>
                          <h6 style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.75rem;">Submitted Information:</h6>
                          <?php foreach($responses_by_client[$i] as $response): ?>
                            <div class="response-item">
                              <strong><?php echo htmlspecialchars($response['field_label']); ?></strong>
                              <span><?php echo htmlspecialchars($response['field_value']); ?></span>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <div class="alert alert-warning" style="margin:0; background: #fff3cd; color: #856404; border: none;">
                            <i class="fas fa-exclamation-triangle mr-2"></i> No information submitted yet.
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <?php endfor; ?>
                </div>
              </div>
            </div>

          </div>

          <!-- Right Column: Pricing & Payment Info -->
          <div class="col-lg-4">

            <!-- Pricing Information Card -->
            <div class="section-card">
              <div class="section-header">
                <h3><i class="fas fa-money-bill-wave"></i> Pricing Information</h3>
              </div>
              <div class="section-body">
                <table class="pricing-table">
                  <tr>
                    <td>Regular Price (per client)</td>
                    <td class="text-right highlight">₹<?php echo $application['regular_price']; ?></td>
                  </tr>
                  <?php if($application['partner_price'] > 0): ?>
                  <tr>
                    <td>Partner Price (per client)</td>
                    <td class="text-right highlight">₹<?php echo $application['partner_price']; ?></td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                    <td>Min clients for partner price</td>
                    <td class="text-right"><?php echo $application['min_clients_for_partner']; ?></td>
                  </tr>
                  <tr>
                    <td><strong>Applied Price</strong></td>
                    <td class="text-right">
                      <strong>₹<?php echo $application['unit_price']; ?></strong><br>
                      <?php if($application['is_partner_price_applied']): ?>
                        <span class="service-badge badge-completed">Partner Price Applied</span>
                      <?php else: ?>
                        <span class="service-badge badge-unknown">Regular Price</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr style="border-top: 2px solid #f1f5f9;">
                    <td><strong>Total Amount</strong></td>
                    <td class="text-right"><strong>₹<?php echo $application['total_amount']; ?></strong></td>
                  </tr>
                </table>
                <?php 
                $savings = ($application['regular_price'] * $application['client_count']) - $application['total_amount'];
                if($savings > 0): 
                ?>
                <div class="savings-alert">
                  <i class="fas fa-piggy-bank fa-lg"></i>
                  <span><strong>You saved: ₹<?php echo $savings; ?></strong></span>
                </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Payment Information Card -->
            <div class="section-card">
              <div class="section-header">
                <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
              </div>
              <div class="section-body">
                <?php if(!empty($application['billing_email'])): ?>
                <div class="info-card" style="margin-bottom: 1rem;">
                  <h5><i class="fas fa-user-circle"></i> Billing Details</h5>
                  <p><strong>Name:</strong> <?php echo htmlspecialchars($application['billing_name']); ?></p>
                  <p><strong>Email:</strong> <?php echo htmlspecialchars($application['billing_email']); ?></p>
                  <p><strong>Mobile:</strong> <?php echo htmlspecialchars($application['billing_mobile']); ?></p>
                  <p><strong>Payment Status:</strong> 
                    <span class="service-badge <?php echo $application['payment_status'] == 'Success' ? 'badge-completed' : 'badge-pending'; ?>">
                      <?php echo $application['payment_status'] ?: 'Pending'; ?>
                    </span>
                  </p>
                </div>
                <?php endif; ?>
                <div class="info-card">
                  <h5><i class="fas fa-receipt"></i> Order Details</h5>
                  <p><strong>Order ID:</strong> <?php echo $application['razorpay_order_id'] ?: 'N/A'; ?></p>
                  <p><strong>Payment Date:</strong> <?php echo $application['payment_date'] ? date('d M Y', strtotime($application['payment_date'])) : 'N/A'; ?></p>
                  <p><strong>Payment Method:</strong> <?php echo $application['payment_method'] ?: 'Razorpay'; ?></p>
                </div>
              </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="section-card">
              <div class="section-header">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
              </div>
              <div class="section-body">
                <div class="quick-actions-grid">
                  <a href="client-details.php?app_id=<?php echo $application_id; ?>" class="btn-solid-modern" style="justify-content: center;">
                    <i class="fas fa-users"></i> Manage All Clients
                  </a>
                  <a href="../service-details.php?id=<?php echo $application['sid']; ?>" class="btn-outline-modern" style="justify-content: center;" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Service Page
                  </a>
                  <a href="services.php" class="btn-outline-modern" style="justify-content: center;">
                    <i class="fas fa-arrow-left"></i> Back to Services
                  </a>
                  <?php if($application['status'] == '0'): ?>
                  <button class="btn-outline-modern" style="justify-content: center; border-color: #f59e0b; color: #b45309;" data-toggle="modal" data-target="#followupModal">
                    <i class="fas fa-headset"></i> Request Follow-up
                  </button>
                  <?php endif; ?>
                </div>
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

<!-- Follow-up Modal -->
<div class="modal fade" id="followupModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-headset mr-2"></i> Request Follow-up</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Request a follow-up for Application #<?php echo str_pad($application_id, 6, '0', STR_PAD_LEFT); ?></p>
        <form id="followupForm">
          <div class="form-group">
            <label for="followupMessage">Message:</label>
            <textarea class="form-control" id="followupMessage" rows="3" 
                      placeholder="Enter any specific questions or concerns..."></textarea>
          </div>
          <div class="form-group">
            <label for="followupContact">Preferred Contact Method:</label>
            <select class="form-control" id="followupContact">
              <option value="email">Email</option>
              <option value="phone">Phone Call</option>
              <option value="whatsapp">WhatsApp</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitFollowup">Submit Request</button>
      </div>
    </div>
  </div>
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
  // Handle follow-up request
  $('#submitFollowup').click(function() {
    var message = $('#followupMessage').val();
    var contact = $('#followupContact').val();
    
    if(message.trim() === '') {
      alert('Please enter a message');
      return;
    }
    
    console.log('Follow-up requested:', {
      application_id: <?php echo $application_id; ?>,
      message: message,
      contact_method: contact
    });
    
    $('#followupModal').modal('hide');
    alert('Follow-up request submitted successfully!');
    
    $('#followupMessage').val('');
    $('#followupContact').val('email');
  });
  
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
</body>
</html>