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

// Get application ID and client index from URL
$application_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;
$client_index = isset($_GET['client']) ? intval($_GET['client']) : 1;

if($application_id == 0){
    header("Location: services.php");
    exit();
}

// Get application details
$app_query = $con->query("
    SELECT 
        a.*,
        s.title as service_name,
        c.name as category_name
    FROM apply a
    INNER JOIN service s ON a.sid = s.id
    INNER JOIN cate c ON s.cate = c.id
    WHERE a.id = '$application_id' 
    AND a.partner_id = '$partner_id'
    AND a.user_type = 'partner'
");

if($app_query->num_rows == 0){
    header("Location: services.php");
    exit();
}

$application = $app_query->fetch_assoc();

// Validate client index
if($client_index < 1 || $client_index > $application['client_count']){
    $client_index = 1;
}

// Get service form fields
$fields_query = $con->query("
    SELECT * FROM service_form_fields 
    WHERE service_id = '{$application['sid']}'
    ORDER BY field_order
");

// Get form responses for this specific client
$responses_query = $con->query("
    SELECT 
        sfr.*,
        sff.field_label,
        sff.field_type,
        sff.field_placeholder,
        sff.is_required
    FROM service_form_responses sfr
    LEFT JOIN service_form_fields sff ON sfr.field_id = sff.id
    WHERE sfr.application_id = '$application_id'
    AND sfr.client_index = '$client_index'
    ORDER BY sff.field_order
");

// Get all clients for navigation
$clients_query = $con->query("
    SELECT DISTINCT client_index 
    FROM service_form_responses 
    WHERE application_id = '$application_id'
    ORDER BY client_index
");

$all_clients = [];
while($client = $clients_query->fetch_assoc()){
    $all_clients[] = $client['client_index'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Client Details - Partner Dashboard | Legal Taxation</title>

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

    /* Client Header Card */
    .client-header-card {
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

    .client-header-content h2 {
      font-weight: 700;
      font-size: 1.5rem;
      color: #0f172a;
      margin-bottom: 0.5rem;
    }

    .client-header-content p {
      color: #475569;
      margin-bottom: 0.25rem;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .client-header-content p i {
      color: #3b82f6;
      width: 18px;
    }

    .client-header-actions {
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

    /* Navigation Card */
    .nav-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      margin-bottom: 1.5rem;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .nav-card-header {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      background: #f8fafc;
    }

    .nav-card-header h5 {
      font-weight: 600;
      font-size: 1rem;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .nav-card-body {
      padding: 1.5rem;
    }

    /* Client navigation pills */
    .client-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
    }

    .client-pill {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 40px;
      height: 40px;
      border-radius: 100px;
      background: #f1f5f9;
      color: #334155;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.2s;
      border: 1px solid #e2e8f0;
    }

    .client-pill:hover {
      background: #e2e8f0;
      color: #0f172a;
    }

    .client-pill.active {
      background: #3b82f6;
      color: white;
      border-color: #3b82f6;
    }

    .client-pill.has-data {
      background: #d1fae5;
      color: #065f46;
      border-color: #a7f3d0;
    }
    .client-pill.has-data.active {
      background: #3b82f6;
      color: white;
    }

    .pill-small-icon {
      font-size: 0.6rem;
      margin-left: 2px;
    }

    /* Tabs styling */
    .nav-tabs-modern {
      border-bottom: 1px solid #f1f5f9;
    }
    .nav-tabs-modern .nav-link {
      border: none;
      color: #64748b;
      font-weight: 500;
      padding: 0.75rem 1.25rem;
      margin-right: 0.5rem;
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

    /* Main Card */
    .main-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .main-card-header {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      background: #f8fafc;
    }

    .main-card-body {
      padding: 1.5rem;
    }

    /* Response Card */
    .response-card {
      background: #ffffff;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      overflow: hidden;
      transition: all 0.2s;
      height: 100%;
    }
    .response-card:hover {
      border-color: #e2e8f0;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .response-card-body {
      padding: 1.25rem;
    }

    .response-card-title {
      font-weight: 600;
      font-size: 0.9rem;
      color: #0f172a;
      margin-bottom: 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .required-star {
      color: #ef4444;
      font-size: 0.7rem;
    }
    .field-value {
      font-size: 0.9rem;
      color: #334155;
      background: #f8fafc;
      border-radius: 0.75rem;
      padding: 0.75rem;
      margin-bottom: 0.5rem;
    }
    .field-placeholder {
      font-size: 0.7rem;
      color: #64748b;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    /* Completion alert */
    .completion-alert {
      background: #d1fae5;
      border: 1px solid #a7f3d0;
      border-radius: 1rem;
      padding: 1rem 1.5rem;
      margin-top: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
    }
    .completion-alert i {
      color: #10b981;
    }

    /* Timeline */
    .timeline-modern {
      position: relative;
      padding-left: 2rem;
    }
    .timeline-modern::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 2px;
      background: #e2e8f0;
    }
    .timeline-item {
      position: relative;
      padding-bottom: 1.5rem;
    }
    .timeline-item::before {
      content: '';
      position: absolute;
      left: -2.1rem;
      top: 0;
      width: 1rem;
      height: 1rem;
      border-radius: 50%;
      background: #3b82f6;
      border: 2px solid #ffffff;
    }
    .timeline-date {
      font-size: 0.75rem;
      color: #64748b;
      margin-bottom: 0.25rem;
    }
    .timeline-content {
      background: #f8fafc;
      border-radius: 1rem;
      padding: 1rem;
    }
    .timeline-content h6 {
      font-weight: 600;
      font-size: 0.9rem;
      color: #0f172a;
      margin-bottom: 0.25rem;
    }
    .timeline-content p {
      font-size: 0.85rem;
      color: #334155;
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
    .custom-file-label {
      border: 1px solid #e2e8f0;
      border-radius: 0.75rem;
    }
    .custom-file-label::after {
      border-radius: 0 0.75rem 0.75rem 0;
    }
    .field-required:after {
      content: " *";
      color: #ef4444;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
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
      margin-bottom: 1.5rem;
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
                <i class="fas fa-user-circle"></i>
                Client Details
              </h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="services.php">Services</a></li>
              <li class="breadcrumb-item"><a href="service-details.php?id=<?php echo $application_id; ?>">Service Details</a></li>
              <li class="breadcrumb-item active">Client <?php echo $client_index; ?></li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Client Header Card -->
        <div class="client-header-card">
          <div class="client-header-content">
            <h2>
              <i class="fas fa-user"></i> Client <?php echo $client_index; ?>
              <small style="font-size: 0.9rem; color: #64748b; margin-left: 0.5rem;">of <?php echo $application['client_count']; ?></small>
            </h2>
            <p><i class="fas fa-shopping-cart"></i> Service: <?php echo htmlspecialchars($application['service_name']); ?></p>
            <p><i class="fas fa-tag"></i> Category: <?php echo htmlspecialchars($application['category_name']); ?> | Application ID: #<?php echo str_pad($application_id, 6, '0', STR_PAD_LEFT); ?></p>
          </div>
          <div class="client-header-actions">
            <a href="service-details.php?id=<?php echo $application_id; ?>" class="btn-outline-modern">
              <i class="fas fa-arrow-left"></i> Back to Service
            </a>
            <button class="btn-outline-modern" onclick="window.print()">
              <i class="fas fa-print"></i> Print
            </button>
          </div>
        </div>

        <!-- Client Navigation Card -->
        <div class="nav-card">
          <div class="nav-card-header">
            <h5><i class="fas fa-users"></i> Navigate Clients</h5>
          </div>
          <div class="nav-card-body">
            <div class="client-pills">
              <?php for($i = 1; $i <= $application['client_count']; $i++): 
                $is_active = ($i == $client_index);
                $has_data = in_array($i, $all_clients);
                $pill_class = 'client-pill';
                if($is_active) $pill_class .= ' active';
                if($has_data && !$is_active) $pill_class .= ' has-data';
              ?>
              <a href="client-details.php?app_id=<?php echo $application_id; ?>&client=<?php echo $i; ?>" 
                 class="<?php echo $pill_class; ?>"
                 title="Client <?php echo $i; ?><?php echo $has_data ? ' (Data submitted)' : ''; ?>">
                <?php echo $i; ?>
                <?php if($has_data): ?>
                  <i class="fas fa-check pill-small-icon"></i>
                <?php endif; ?>
              </a>
              <?php endfor; ?>
            </div>

            <div style="display: flex; justify-content: space-between; margin-top: 1.5rem;">
              <?php if($client_index > 1): ?>
              <a href="client-details.php?app_id=<?php echo $application_id; ?>&client=<?php echo $client_index - 1; ?>" 
                 class="btn-outline-modern">
                <i class="fas fa-chevron-left"></i> Previous Client
              </a>
              <?php else: ?>
              <span></span>
              <?php endif; ?>

              <?php if($client_index < $application['client_count']): ?>
              <a href="client-details.php?app_id=<?php echo $application_id; ?>&client=<?php echo $client_index + 1; ?>" 
                 class="btn-outline-modern">
                Next Client <i class="fas fa-chevron-right"></i>
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Main Tab Card -->
        <div class="main-card">
          <div class="main-card-header">
            <ul class="nav nav-tabs-modern" id="clientTabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="form-data-tab" data-toggle="tab" href="#formData" role="tab">
                  <i class="fas fa-file-alt mr-1"></i> Form Data
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                  <i class="fas fa-file-upload mr-1"></i> Documents
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab">
                  <i class="fas fa-history mr-1"></i> Timeline
                </a>
              </li>
            </ul>
          </div>
          <div class="main-card-body">
            <div class="tab-content" id="clientTabsContent">

              <!-- Form Data Tab -->
              <div class="tab-pane fade show active" id="formData" role="tabpanel">
                <?php if($responses_query->num_rows > 0): ?>
                  <div class="row">
                    <?php while($response = $responses_query->fetch_assoc()): ?>
                    <div class="col-md-6 mb-3">
                      <div class="response-card">
                        <div class="response-card-body">
                          <div class="response-card-title">
                            <?php echo htmlspecialchars($response['field_label']); ?>
                            <?php if($response['is_required']): ?>
                              <span class="required-star">*</span>
                            <?php endif; ?>
                          </div>
                          <div class="field-value">
                            <?php if($response['field_type'] == 'file' && !empty($response['field_value'])): ?>
                              <a href="../uploads/<?php echo $response['field_value']; ?>" 
                                 target="_blank" 
                                 class="btn-sm-modern" style="display: inline-flex; align-items: center; gap: 0.3rem;">
                                <i class="fas fa-download"></i> Download File
                              </a>
                            <?php else: ?>
                              <?php echo nl2br(htmlspecialchars($response['field_value'])); ?>
                            <?php endif; ?>
                          </div>
                          <?php if($response['field_placeholder']): ?>
                          <div class="field-placeholder">
                            <i class="fas fa-info-circle"></i>
                            <?php echo htmlspecialchars($response['field_placeholder']); ?>
                          </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                    <?php endwhile; ?>
                  </div>

                  <!-- Completion Status -->
                  <div class="completion-alert">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <i class="fas fa-check-circle fa-lg"></i>
                      <div>
                        <strong>Form Completed</strong>
                        <p class="mb-0 mt-1" style="font-size: 0.85rem;">All required fields have been filled for this client.</p>
                      </div>
                    </div>
                    <div>
                      <small style="color: #065f46;">
                        <i class="fas fa-clock mr-1"></i> 
                        Last updated: 
                        <?php 
                        $last_response = $responses_query->num_rows > 0 ? 
                          date('d M Y, h:i A', strtotime($response['created_at'])) : 'N/A'; 
                        echo $last_response;
                        ?>
                      </small>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="empty-state">
                    <i class="fas fa-user-slash"></i>
                    <h4>No Data Submitted</h4>
                    <p>No form data has been submitted for this client yet.</p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                      <button class="btn-solid-modern" data-toggle="modal" data-target="#addDataModal">
                        <i class="fas fa-plus"></i> Add Client Data
                      </button>
                      <a href="service-details.php?id=<?php echo $application_id; ?>" class="btn-outline-modern">
                        <i class="fas fa-arrow-left"></i> Back to Service
                      </a>
                    </div>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Documents Tab -->
              <div class="tab-pane fade" id="documents" role="tabpanel">
                <div class="empty-state">
                  <i class="fas fa-file-upload"></i>
                  <h4>No Documents Uploaded</h4>
                  <p>No additional documents have been uploaded for this client.</p>
                  <button class="btn-solid-modern" data-toggle="modal" data-target="#uploadDocumentModal">
                    <i class="fas fa-upload"></i> Upload Document
                  </button>
                </div>
              </div>

              <!-- Timeline Tab -->
              <div class="tab-pane fade" id="timeline" role="tabpanel">
                <?php
                // Get timeline events for this client
                $timeline_query = $con->query("
                  SELECT * FROM activity_logs 
                  WHERE user_id = '$partner_id' 
                  AND user_type = 'partner'
                  AND description LIKE '%Client $client_index%'
                  AND description LIKE '%application $application_id%'
                  ORDER BY created_at DESC
                  LIMIT 10
                ");

                if($timeline_query->num_rows > 0):
                ?>
                <div class="timeline-modern">
                  <?php while($event = $timeline_query->fetch_assoc()): ?>
                  <div class="timeline-item">
                    <div class="timeline-date">
                      <i class="fas fa-calendar-alt mr-1"></i> <?php echo date('d M Y', strtotime($event['created_at'])); ?>
                      <i class="fas fa-clock ml-2 mr-1"></i> <?php echo date('h:i A', strtotime($event['created_at'])); ?>
                    </div>
                    <div class="timeline-content">
                      <h6><?php echo htmlspecialchars($event['action']); ?></h6>
                      <p><?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                  </div>
                  <?php endwhile; ?>
                </div>
                <?php else: ?>
                <div class="empty-state">
                  <i class="fas fa-history"></i>
                  <h4>No Timeline Events</h4>
                  <p>No activity recorded for this client yet.</p>
                </div>
                <?php endif; ?>
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

<!-- Add Data Modal -->
<div class="modal fade" id="addDataModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-plus mr-2" style="color: #3b82f6;"></i> Add Data for Client <?php echo $client_index; ?>
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="clientDataForm">
          <?php
          $fields_query_all = $con->query("
            SELECT * FROM service_form_fields 
            WHERE service_id = '{$application['sid']}'
            ORDER BY field_order
          ");
          
          if($fields_query_all->num_rows > 0):
            while($field = $fields_query_all->fetch_assoc()):
              $field_value = '';
              $value_query = $con->query("
                SELECT field_value FROM service_form_responses 
                WHERE application_id = '$application_id' 
                AND client_index = '$client_index' 
                AND field_id = '{$field['id']}'
              ");
              if($value_query->num_rows > 0){
                $value_row = $value_query->fetch_assoc();
                $field_value = $value_row['field_value'];
              }
          ?>
          <div class="form-group">
            <label for="field_<?php echo $field['id']; ?>" 
                   class="<?php echo $field['is_required'] ? 'field-required' : ''; ?>">
              <?php echo htmlspecialchars($field['field_label']); ?>
            </label>
            
            <?php if($field['field_type'] == 'textarea'): ?>
              <textarea class="form-control" 
                        id="field_<?php echo $field['id']; ?>" 
                        name="field_<?php echo $field['id']; ?>"
                        rows="3"
                        placeholder="<?php echo htmlspecialchars($field['field_placeholder']); ?>"
                        <?php echo $field['is_required'] ? 'required' : ''; ?>><?php echo htmlspecialchars($field_value); ?></textarea>
            
            <?php elseif($field['field_type'] == 'select'): ?>
              <select class="form-control" 
                      id="field_<?php echo $field['id']; ?>" 
                      name="field_<?php echo $field['id']; ?>"
                      <?php echo $field['is_required'] ? 'required' : ''; ?>>
                <option value=""><?php echo htmlspecialchars($field['field_placeholder']); ?></option>
                <?php
                if($field['field_options']){
                  $options = explode(',', $field['field_options']);
                  foreach($options as $option):
                    $option = trim($option);
                    $selected = ($field_value == $option) ? 'selected' : '';
                ?>
                <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $selected; ?>>
                  <?php echo htmlspecialchars($option); ?>
                </option>
                <?php endforeach; } ?>
              </select>
            
            <?php else: ?>
              <input type="<?php echo $field['field_type']; ?>" 
                     class="form-control" 
                     id="field_<?php echo $field['id']; ?>" 
                     name="field_<?php echo $field['id']; ?>"
                     value="<?php echo htmlspecialchars($field_value); ?>"
                     placeholder="<?php echo htmlspecialchars($field['field_placeholder']); ?>"
                     <?php echo $field['is_required'] ? 'required' : ''; ?>>
            <?php endif; ?>
            
            <?php if($field['help_text']): ?>
              <small class="form-text text-muted">
                <?php echo htmlspecialchars($field['help_text']); ?>
              </small>
            <?php endif; ?>
          </div>
          <?php endwhile; else: ?>
          <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            No form fields defined for this service.
          </div>
          <?php endif; ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline-modern" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn-solid-modern" id="saveClientData">Save Data</button>
      </div>
    </div>
  </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-upload mr-2" style="color: #3b82f6;"></i> Upload Document
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="documentUploadForm" enctype="multipart/form-data">
          <div class="form-group">
            <label for="documentTitle">Document Title <span class="required-star">*</span></label>
            <input type="text" class="form-control" id="documentTitle" required>
          </div>
          <div class="form-group">
            <label for="documentType">Document Type <span class="required-star">*</span></label>
            <select class="form-control" id="documentType" required>
              <option value="">Select type</option>
              <option value="identification">Identification</option>
              <option value="address_proof">Address Proof</option>
              <option value="income_proof">Income Proof</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="documentFile">Choose File <span class="required-star">*</span></label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="documentFile" required>
              <label class="custom-file-label" for="documentFile">Choose file</label>
            </div>
          </div>
          <div class="form-group">
            <label for="documentNotes">Notes (Optional)</label>
            <textarea class="form-control" id="documentNotes" rows="2"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-outline-modern" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn-solid-modern" id="uploadDocumentBtn">Upload</button>
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
  // Handle file input label
  $('#documentFile').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
  });
  
  // Handle save client data
  $('#saveClientData').click(function() {
    var formData = new FormData();
    formData.append('application_id', <?php echo $application_id; ?>);
    formData.append('client_index', <?php echo $client_index; ?>);
    formData.append('action', 'save_client_data');
    
    $('#clientDataForm input, #clientDataForm select, #clientDataForm textarea').each(function() {
      var fieldId = $(this).attr('id').replace('field_', '');
      var fieldValue = $(this).val();
      formData.append('fields[' + fieldId + ']', fieldValue);
    });
    
    console.log('Saving client data:', {
      application_id: <?php echo $application_id; ?>,
      client_index: <?php echo $client_index; ?>,
      fields: Object.fromEntries(formData)
    });
    
    $('#addDataModal').modal('hide');
    alert('Client data saved successfully!');
    
    setTimeout(function() {
      location.reload();
    }, 1000);
  });
  
  // Handle document upload
  $('#uploadDocumentBtn').click(function() {
    var title = $('#documentTitle').val();
    var type = $('#documentType').val();
    var notes = $('#documentNotes').val();
    var fileInput = $('#documentFile')[0];
    
    if(!title || !type || !fileInput.files[0]) {
      alert('Please fill all required fields');
      return;
    }
    
    var formData = new FormData();
    formData.append('title', title);
    formData.append('type', type);
    formData.append('notes', notes);
    formData.append('file', fileInput.files[0]);
    formData.append('application_id', <?php echo $application_id; ?>);
    formData.append('client_index', <?php echo $client_index; ?>);
    formData.append('action', 'upload_document');
    
    console.log('Uploading document:', {
      title: title,
      type: type,
      notes: notes,
      file: fileInput.files[0].name
    });
    
    $('#uploadDocumentModal').modal('hide');
    alert('Document uploaded successfully!');
    
    $('#documentTitle').val('');
    $('#documentType').val('');
    $('#documentNotes').val('');
    $('#documentFile').val('');
    $('.custom-file-label').html('Choose file');
  });
});
</script>
</body>
</html>