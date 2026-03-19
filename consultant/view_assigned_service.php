<?php
session_start();

// Check if CA is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../ca-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

include("../db.php");

// Get CA details
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
if($sql->num_rows == 0){
    session_destroy();
    header("Location: ../ca-login.php");
    exit();
}

$ca = $sql->fetch_assoc();

// Get application ID
$application_id = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;

if(!$application_id){
    echo "<script>alert('Application ID required!'); window.location='assigned_services.php';</script>";
    exit();
}

// Verify this application belongs to this CA
$check_sql = $con->query("SELECT * FROM apply WHERE id='$application_id' AND send_to='$ca_id'");
if($check_sql->num_rows == 0){
    echo "<script>alert('Unauthorized access!'); window.location='assigned_services.php';</script>";
    exit();
}

// Get complete application details
$application = [];
$sql = $con->query("
    SELECT a.*, 
           s.title as service_title,
           s.id as service_id,
           s.cate as category_id,
           s.m_price as market_price,
           s.o_price as regular_price,
           s.partner_o_price as partner_price,
           s.s_des as short_description,
           s.des as full_description,
           cat.name as category_name,
           CASE 
               WHEN a.user_type = 'customer' THEN c.name
               WHEN a.user_type = 'partner' THEN p.name
               ELSE 'Unknown'
           END as user_name,
           CASE 
               WHEN a.user_type = 'customer' THEN c.email
               WHEN a.user_type = 'partner' THEN p.email
               ELSE ''
           END as user_email,
           CASE 
               WHEN a.user_type = 'customer' THEN c.contact
               WHEN a.user_type = 'partner' THEN p.contact
               ELSE ''
           END as user_contact,
           CASE 
               WHEN a.user_type = 'customer' THEN NULL
               WHEN a.user_type = 'partner' THEN p.business_name
           END as business_name,
           CASE 
               WHEN a.user_type = 'customer' THEN NULL
               WHEN a.user_type = 'partner' THEN p.gst_number
           END as gst_number,
           rb.billing_name,
           rb.billing_email,
           rb.billing_mobile,
           rb.payment_status,
           rb.created_at as payment_date,
           rb.pay_amount
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN cate cat ON s.cate = cat.id
    LEFT JOIN razorpaybill rb ON a.razorpay_order_id = rb.order_id
    LEFT JOIN customer c ON a.user_type = 'customer' AND a.cid = c.id
    LEFT JOIN partner p ON a.user_type = 'partner' AND a.partner_id = p.id
    WHERE a.id='$application_id'
");

if($row = $sql->fetch_assoc()){
    $application = $row;
    $service_id = $row['service_id'];
    $user_type = $row['user_type'];
} else {
    echo "<script>alert('Application not found!'); window.location='assigned_services.php';</script>";
    exit();
}

// Get form fields for this service
$form_fields = [];
$sql = $con->query("SELECT * FROM service_form_fields WHERE service_id='$service_id' ORDER BY field_order ASC");
while($row = $sql->fetch_assoc()){
    $form_fields[] = $row;
}

// Get form responses
$form_responses = [];
$sql = $con->query("
    SELECT r.*, f.field_name, f.field_label, f.field_type, f.is_required, f.help_text
    FROM service_form_responses r 
    LEFT JOIN service_form_fields f ON r.field_id = f.id 
    WHERE r.application_id='$application_id'
    ORDER BY r.client_index, f.field_order
");

while($row = $sql->fetch_assoc()){
    $client_index = $row['client_index'];
    if(!isset($form_responses[$client_index])){
        $form_responses[$client_index] = [];
    }
    $form_responses[$client_index][$row['field_id']] = $row;
}

// Get documents
$documents = [];
$sql = $con->query("
    SELECT r.*, d.name as doc_name 
    FROM req_doc r 
    LEFT JOIN doc d ON r.type = d.id 
    WHERE r.sid='$application_id' 
    ORDER BY r.id DESC
");

while($row = $sql->fetch_assoc()){
    $documents[] = $row;
}

// Calculate statistics
$total_clients = $application['client_count'] ?: 1;
$total_responses = 0;
$total_expected_fields = count($form_fields) * $total_clients;
foreach($form_responses as $client_responses){
    $total_responses += count($client_responses);
}
$completion_percentage = $total_expected_fields > 0 ? round(($total_responses / $total_expected_fields) * 100) : 0;

// Get admin notes
$notes = [];
$sql = $con->query("
    SELECT n.*, a.name as admin_name 
    FROM application_notes n 
    LEFT JOIN admin a ON n.created_by = a.id 
    WHERE n.application_id='$application_id' 
    ORDER BY n.created_at DESC
");
while($row = $sql->fetch_assoc()){
    $notes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Assigned Service - CA Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter (modern, clean) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE 3) - we keep for layout but override heavily -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

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

    /* Action Bar */
    .action-bar {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1rem 1.5rem;
      margin-bottom: 2rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .btn-group-modern {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .btn-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.5rem 1.25rem;
      font-size: 0.85rem;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
    }

    .btn-modern:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      color: #0f172a;
    }

    .btn-modern-primary {
      background: #3b82f6;
      border-color: #3b82f6;
      color: #ffffff;
    }
    .btn-modern-primary:hover {
      background: #2563eb;
      border-color: #2563eb;
    }

    .btn-modern-success {
      background: #10b981;
      border-color: #10b981;
      color: #ffffff;
    }
    .btn-modern-success:hover {
      background: #059669;
      border-color: #059669;
    }

    .badge-modern {
      padding: 0.4rem 1rem;
      border-radius: 100px;
      font-size: 0.8rem;
      font-weight: 600;
      letter-spacing: 0.3px;
    }
    .badge-pending {
      background: #fff3cd;
      color: #856404;
    }
    .badge-completed {
      background: #d1fae5;
      color: #065f46;
    }
    .badge-customer {
      background: #d1fae5;
      color: #065f46;
    }
    .badge-partner {
      background: #dbeafe;
      color: #1e40af;
    }

    /* Cards */
    .detail-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      margin-bottom: 1.5rem;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .detail-card-header {
      padding: 1.25rem 1.75rem;
      border-bottom: 1px solid #f1f5f9;
      background: #f8fafc;
    }

    .detail-card-header h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .detail-card-header h3 i {
      color: #3b82f6;
    }

    .detail-card-body {
      padding: 1.5rem 1.75rem;
    }

    /* Info tables */
    .info-table {
      width: 100%;
    }
    .info-table tr td {
      padding: 0.75rem 0;
      border-bottom: 1px solid #f1f5f9;
    }
    .info-table tr:last-child td {
      border-bottom: none;
    }
    .info-table td:first-child {
      font-weight: 500;
      color: #64748b;
      width: 40%;
    }
    .info-table td:last-child {
      color: #0f172a;
      font-weight: 500;
    }

    .price-highlight {
      font-size: 1.5rem;
      font-weight: 700;
      color: #10b981;
    }

    /* Progress bar */
    .progress-modern {
      height: 0.5rem;
      background: #e9ecef;
      border-radius: 1rem;
      overflow: hidden;
    }
    .progress-bar-modern {
      height: 100%;
      border-radius: 1rem;
    }

    /* Tabs */
    .nav-tabs-modern {
      border-bottom: 1px solid #f1f5f9;
    }
    .nav-tabs-modern .nav-link {
      border: none;
      color: #64748b;
      font-weight: 500;
      padding: 0.75rem 1.25rem;
      margin-right: 0.25rem;
      border-radius: 100px 100px 0 0;
    }
    .nav-tabs-modern .nav-link:hover {
      color: #0f172a;
      background: #f1f5f9;
    }
    .nav-tabs-modern .nav-link.active {
      color: #3b82f6;
      background: transparent;
      border-bottom: 2px solid #3b82f6;
    }

    .tab-content-modern {
      padding: 1.5rem 0;
    }

    /* Field value */
    .field-value {
      background: #f8fafc;
      border-radius: 0.75rem;
      padding: 0.75rem;
      border-left: 3px solid #3b82f6;
    }

    /* Documents */
    .document-item {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      padding: 1rem;
      transition: all 0.2s;
    }
    .document-item:hover {
      border-color: #3b82f6;
      background: #ffffff;
    }
    .document-item a {
      color: #0f172a;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .document-item i {
      font-size: 1.5rem;
    }
    .fa-file-pdf { color: #ef4444; }
    .fa-file-image { color: #3b82f6; }
    .fa-file { color: #64748b; }

    /* Notes */
    .note-item {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      padding: 1.25rem;
      margin-bottom: 1rem;
    }
    .note-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
      font-size: 0.85rem;
      color: #64748b;
    }
    .note-header strong {
      color: #0f172a;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .action-bar {
        flex-direction: column;
        align-items: flex-start;
      }
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
                <i class="fas fa-file-alt"></i>
                Service Details
                <span class="text-muted" style="font-size: 1rem; margin-left: 0.5rem;">#<?php echo $application_id; ?></span>
              </h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="assigned_services.php">Assigned Services</a></li>
              <li class="breadcrumb-item active">Service Details</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Action Bar -->
        <div class="action-bar">
          <div class="btn-group-modern">
            <a href="assigned_services.php" class="btn-modern">
              <i class="fas fa-arrow-left"></i> Back
            </a>
            <?php if($user_type == 'partner' && $total_clients > 1): ?>
              <a href="view_partner_clients_ca.php?application_id=<?php echo $application_id; ?>" class="btn-modern btn-modern-primary">
                <i class="fas fa-users"></i> View All Clients
              </a>
            <?php else: ?>
              <a href="view_customer_form.php?application_id=<?php echo $application_id; ?>" class="btn-modern btn-modern-primary">
                <i class="fas fa-file-alt"></i> View Form Data
              </a>
            <?php endif; ?>
            <button type="button" class="btn-modern btn-modern-success" onclick="updateStatus(<?php echo $application_id; ?>, <?php echo $application['status']; ?>)">
              <i class="fas fa-<?php echo $application['status'] == '0' ? 'check' : 'undo'; ?>"></i>
              <?php echo $application['status'] == '0' ? 'Mark Completed' : 'Mark Pending'; ?>
            </button>
            <button type="button" class="btn-modern" onclick="window.print()">
              <i class="fas fa-print"></i> Print
            </button>
          </div>
          <div>
            <span class="badge-modern <?php echo $application['status'] == '0' ? 'badge-pending' : 'badge-completed'; ?> mr-2">
              <?php echo $application['status'] == '0' ? 'Pending' : 'Completed'; ?>
            </span>
            <span class="badge-modern <?php echo $user_type == 'customer' ? 'badge-customer' : 'badge-partner'; ?>">
              <?php echo ucfirst($user_type); ?> Application
            </span>
          </div>
        </div>

        <!-- Main Details Row -->
        <div class="row">
          <!-- Left Column - User and Payment Details -->
          <div class="col-lg-4">
            <!-- User Information Card -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3><i class="fas fa-user"></i> <?php echo $user_type == 'customer' ? 'Customer' : 'Partner'; ?> Information</h3>
              </div>
              <div class="detail-card-body">
                <table class="info-table">
                  <tr>
                    <td>Name:</td>
                    <td><strong><?php echo htmlspecialchars($application['user_name']); ?></strong></td>
                  </tr>
                  <tr>
                    <td>Email:</td>
                    <td><a href="mailto:<?php echo $application['user_email']; ?>"><?php echo htmlspecialchars($application['user_email']); ?></a></td>
                  </tr>
                  <tr>
                    <td>Contact:</td>
                    <td><a href="tel:<?php echo $application['user_contact']; ?>"><?php echo htmlspecialchars($application['user_contact']); ?></a></td>
                  </tr>
                  <tr>
                    <td>ID:</td>
                    <td><span class="badge badge-secondary">#<?php echo $application[$user_type == 'customer' ? 'cid' : 'partner_id']; ?></span></td>
                  </tr>
                  <?php if($user_type == 'partner'): ?>
                  <tr>
                    <td>Business:</td>
                    <td><?php echo htmlspecialchars($application['business_name'] ?? 'N/A'); ?></td>
                  </tr>
                  <tr>
                    <td>GST:</td>
                    <td><?php echo htmlspecialchars($application['gst_number'] ?? 'N/A'); ?></td>
                  </tr>
                  <?php endif; ?>
                </table>
                <div class="mt-3">
                  <a href="mailto:<?php echo $application['user_email']; ?>" class="btn-modern">
                    <i class="fas fa-envelope"></i> Email
                  </a>
                  <a href="tel:<?php echo $application['user_contact']; ?>" class="btn-modern">
                    <i class="fas fa-phone"></i> Call
                  </a>
                </div>
              </div>
            </div>

            <!-- Payment Information Card (if exists) -->
            <?php if($application['payment_status']): ?>
            <div class="detail-card">
              <div class="detail-card-header">
                <h3><i class="fas fa-credit-card"></i> Payment Details</h3>
              </div>
              <div class="detail-card-body">
                <table class="info-table">
                  <tr>
                    <td>Status:</td>
                    <td>
                      <span class="badge-modern <?php echo $application['payment_status'] == 'Success' ? 'badge-completed' : 'badge-pending'; ?>">
                        <?php echo $application['payment_status']; ?>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>Amount:</td>
                    <td class="price-highlight">₹<?php echo number_format($application['total_amount'], 2); ?></td>
                  </tr>
                  <tr>
                    <td>Unit Price:</td>
                    <td>₹<?php echo number_format($application['unit_price'], 2); ?></td>
                  </tr>
                  <?php if($application['is_partner_price_applied']): ?>
                  <tr>
                    <td>Partner Price:</td>
                    <td><span class="badge-modern badge-completed">Applied</span></td>
                  </tr>
                  <?php endif; ?>
                  <?php if($application['billing_name']): ?>
                  <tr>
                    <td>Billed To:</td>
                    <td><?php echo htmlspecialchars($application['billing_name']); ?></td>
                  </tr>
                  <?php endif; ?>
                  <?php if($application['payment_date']): ?>
                  <tr>
                    <td>Date:</td>
                    <td><?php echo date('d M Y', strtotime($application['payment_date'])); ?></td>
                  </tr>
                  <?php endif; ?>
                </table>
              </div>
            </div>
            <?php endif; ?>

            <!-- Application Info Card -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3><i class="fas fa-info-circle"></i> Application Info</h3>
              </div>
              <div class="detail-card-body">
                <table class="info-table">
                  <tr>
                    <td>Applied On:</td>
                    <td><?php echo date('d M Y, h:i A', strtotime($application['created'])); ?></td>
                  </tr>
                  <tr>
                    <td>Clients:</td>
                    <td>
                      <?php echo $total_clients; ?>
                      <?php if($user_type == 'partner' && $total_clients > 1): ?>
                        <span class="badge badge-info ml-2">Bulk</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Form Progress:</td>
                    <td>
                      <div class="progress-modern">
                        <div class="progress-bar-modern bg-<?php echo $completion_percentage >= 100 ? 'success' : ($completion_percentage >= 50 ? 'warning' : 'danger'); ?>" 
                             style="width: <?php echo $completion_percentage; ?>%;"></div>
                      </div>
                      <small><?php echo $completion_percentage; ?>% (<?php echo $total_responses; ?>/<?php echo $total_expected_fields; ?> fields)</small>
                    </td>
                  </tr>
                  <tr>
                    <td>Documents:</td>
                    <td><span class="badge badge-info"><?php echo count($documents); ?> uploaded</span></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>

          <!-- Right Column - Service and Form Details -->
          <div class="col-lg-8">
            <!-- Service Information Card -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3><i class="fas fa-copy"></i> <?php echo htmlspecialchars($application['service_title']); ?></h3>
              </div>
              <div class="detail-card-body">
                <div class="mb-3">
                  <span class="badge badge-info"><?php echo htmlspecialchars($application['category_name']); ?></span>
                </div>
                <?php if($application['short_description']): ?>
                <div class="mb-3">
                  <h6 style="font-weight: 600;">Short Description</h6>
                  <p><?php echo nl2br(htmlspecialchars($application['short_description'])); ?></p>
                </div>
                <?php endif; ?>
                <?php if($application['full_description']): ?>
                <div>
                  <h6 style="font-weight: 600;">Full Description</h6>
                  <div class="p-3 bg-light rounded">
                    <?php echo $application['full_description']; ?>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Form Data Summary Card -->
            <?php if(!empty($form_fields)): ?>
            <div class="detail-card">
              <div class="detail-card-header">
                <h3><i class="fas fa-file-alt"></i> Form Data Summary</h3>
                <?php if($user_type == 'partner' && $total_clients > 1): ?>
                <div>
                  <a href="view_partner_clients_ca.php?application_id=<?php echo $application_id; ?>" class="btn-modern btn-modern-primary btn-sm">
                    <i class="fas fa-users"></i> View All Clients
                  </a>
                </div>
                <?php endif; ?>
              </div>
              <div class="detail-card-body">
                <?php if(empty($form_responses)): ?>
                  <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    No form data has been submitted yet.
                  </div>
                <?php else: ?>
                  <?php if($user_type == 'partner' && $total_clients > 1): ?>
                    <!-- Client Tabs -->
                    <ul class="nav nav-tabs-modern" id="clientTabs" role="tablist">
                      <?php for($i = 1; $i <= $total_clients; $i++): 
                        $client_responses = $form_responses[$i] ?? [];
                      ?>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo $i == 1 ? 'active' : ''; ?>" 
                           id="client-<?php echo $i; ?>-tab" 
                           data-toggle="tab" 
                           href="#client-<?php echo $i; ?>" 
                           role="tab">
                          Client <?php echo $i; ?>
                          <span class="badge badge-<?php echo count($client_responses) == count($form_fields) ? 'success' : 'warning'; ?> ml-1">
                            <?php echo count($client_responses); ?>/<?php echo count($form_fields); ?>
                          </span>
                        </a>
                      </li>
                      <?php endfor; ?>
                    </ul>
                    
                    <div class="tab-content-modern tab-content" id="clientTabsContent">
                      <?php for($i = 1; $i <= $total_clients; $i++): 
                        $client_responses = $form_responses[$i] ?? [];
                      ?>
                      <div class="tab-pane fade <?php echo $i == 1 ? 'show active' : ''; ?>" 
                           id="client-<?php echo $i; ?>" 
                           role="tabpanel">
                        <?php if(empty($client_responses)): ?>
                          <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            No form data for this client.
                          </div>
                        <?php else: ?>
                          <div class="table-responsive">
                            <table class="table">
                              <?php foreach($form_fields as $field): 
                                $response = $client_responses[$field['id']] ?? null;
                              ?>
                                <tr>
                                  <th width="30%"><?php echo htmlspecialchars($field['field_label']); ?></th>
                                  <td>
                                    <?php if($response): ?>
                                      <?php if($field['field_type'] == 'file'): ?>
                                        <a href="../uploads/service_docs/<?php echo $response['field_value']; ?>" target="_blank">
                                          <i class="fas fa-download"></i> Download
                                        </a>
                                      <?php elseif($field['field_type'] == 'checkbox'): 
                                        $values = explode(',', $response['field_value']);
                                        foreach($values as $val): ?>
                                          <span class="badge badge-primary"><?php echo htmlspecialchars(trim($val)); ?></span>
                                        <?php endforeach;
                                      else: ?>
                                        <?php echo nl2br(htmlspecialchars($response['field_value'])); ?>
                                      <?php endif; ?>
                                    <?php else: ?>
                                      <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            </table>
                          </div>
                        <?php endif; ?>
                      </div>
                      <?php endfor; ?>
                    </div>
                  <?php else: ?>
                    <!-- Single Client View -->
                    <div class="table-responsive">
                      <table class="table">
                        <?php 
                        $client_responses = $form_responses[1] ?? [];
                        foreach($form_fields as $field): 
                          $response = $client_responses[$field['id']] ?? null;
                        ?>
                          <tr>
                            <th width="30%"><?php echo htmlspecialchars($field['field_label']); ?></th>
                            <td>
                              <?php if($response): ?>
                                <?php if($field['field_type'] == 'file'): ?>
                                  <a href="../uploads/service_docs/<?php echo $response['field_value']; ?>" target="_blank">
                                    <i class="fas fa-download"></i> Download File
                                  </a>
                                <?php elseif($field['field_type'] == 'checkbox'): 
                                  $values = explode(',', $response['field_value']);
                                  foreach($values as $val): ?>
                                    <span class="badge badge-primary"><?php echo htmlspecialchars(trim($val)); ?></span>
                                  <?php endforeach;
                                else: ?>
                                  <?php echo nl2br(htmlspecialchars($response['field_value'])); ?>
                                <?php endif; ?>
                              <?php else: ?>
                                <span class="text-muted">Not provided</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </table>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>

            <!-- Documents Card -->
            <?php if(!empty($documents)): ?>
            <div class="detail-card">
              <div class="detail-card-header">
                <h3><i class="fas fa-file-upload"></i> Uploaded Documents</h3>
              </div>
              <div class="detail-card-body">
                <div class="row">
                  <?php foreach($documents as $doc): ?>
                  <div class="col-md-6 mb-3">
                    <div class="document-item">
                      <a href="../uploads/<?php echo $doc['file']; ?>" target="_blank">
                        <?php if(preg_match('/\.pdf$/i', $doc['file'])): ?>
                          <i class="fas fa-file-pdf fa-2x"></i>
                        <?php elseif(preg_match('/\.(jpg|jpeg|png|gif)$/i', $doc['file'])): ?>
                          <i class="fas fa-file-image fa-2x"></i>
                        <?php else: ?>
                          <i class="fas fa-file fa-2x"></i>
                        <?php endif; ?>
                        <div>
                          <strong><?php echo htmlspecialchars($doc['doc_name'] ?? 'Document'); ?></strong>
                          <br>
                          <small class="text-muted">
                            <i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($doc['created'])); ?>
                          </small>
                        </div>
                      </a>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <!-- Admin Notes Card -->
            <?php if(!empty($notes)): ?>
            <div class="detail-card">
              <div class="detail-card-header">
                <h3><i class="fas fa-sticky-note"></i> Admin Notes</h3>
              </div>
              <div class="detail-card-body">
                <?php foreach($notes as $note): ?>
                <div class="note-item">
                  <div class="note-header">
                    <strong><?php echo htmlspecialchars($note['admin_name'] ?? 'Admin'); ?></strong>
                    <small><?php echo date('d M Y, h:i A', strtotime($note['created_at'])); ?></small>
                  </div>
                  <p class="mb-0"><?php echo nl2br(htmlspecialchars($note['notes'])); ?></p>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

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
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
function updateStatus(applicationId, currentStatus) {
    var newStatus = currentStatus == '0' ? '1' : '0';
    var confirmMessage = currentStatus == '0' 
        ? 'Are you sure you want to mark this service as completed?' 
        : 'Are you sure you want to reopen this service?';
    
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
                window.location.reload();
            },
            error: function() {
                alert('Error updating status. Please try again.');
            }
        });
    }
}
</script>
</body>
</html>