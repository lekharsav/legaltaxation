<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}

// Get admin details
$sql = $con->query("select * from admin where id='$aid'");
if($row = $sql->fetch_assoc()){
  $admin_image = $row["image"];
  $admin_name = $row["name"];
  $admin_cont = $row["contact"];
  $admin_email = $row["email"];
  $admin_pass = $row["password"];
}

// Get application ID
$application_id = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;

// If no application ID, redirect to leads page
if(!$application_id){
    echo "<script>window.location='leads.php';</script>";
    exit();
}

// Get application details
$application = [];
$sql = $con->query("
    SELECT a.*, 
           s.title as service_title, 
           s.id as service_id,
           s.o_price as service_price,
           s.partner_o_price as partner_price,
           s.min_clients_for_partner,
           c.name as category_name,
           rb.billing_email,
           rb.billing_name,
           rb.billing_mobile,
           rb.payment_status,
           rb.created_at as payment_date,
           CASE 
               WHEN a.user_type = 'customer' THEN cust.name
               WHEN a.user_type = 'partner' THEN part.name
               ELSE 'Unknown'
           END as user_name,
           CASE 
               WHEN a.user_type = 'customer' THEN cust.email
               WHEN a.user_type = 'partner' THEN part.email
               ELSE ''
           END as user_email,
           CASE 
               WHEN a.user_type = 'customer' THEN cust.contact
               WHEN a.user_type = 'partner' THEN part.contact
               ELSE ''
           END as user_contact,
           a.user_type,
           part.business_name as partner_business,
           part.business_type as partner_business_type,
           part.gst_number as partner_gst
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN cate c ON s.cate = c.id
    LEFT JOIN razorpaybill rb ON a.razorpay_order_id = rb.order_id
    LEFT JOIN customer cust ON a.user_type = 'customer' AND a.cid = cust.id
    LEFT JOIN partner part ON a.user_type = 'partner' AND a.partner_id = part.id
    WHERE a.id='$application_id'
");

if($row = $sql->fetch_assoc()){
    $application = $row;
    $service_id = $row['service_id'];
    $user_type = $row['user_type'];
} else {
    echo "<script>alert('Application not found!'); window.location='leads.php';</script>";
    exit();
}

// Get service details
$service = [];
$sql = $con->query("SELECT * FROM service WHERE id='$service_id'");
if($row = $sql->fetch_assoc()){
    $service = $row;
}

// Get form fields for this service
$form_fields = [];
$sql = $con->query("SELECT * FROM service_form_fields WHERE service_id='$service_id' ORDER BY field_order ASC");
while($row = $sql->fetch_assoc()){
    $form_fields[] = $row;
}

// Get ALL form responses for this application (for all clients if partner)
$all_responses = [];
$sql = $con->query("
    SELECT r.*, f.field_name, f.field_label, f.field_type, f.is_required, f.help_text
    FROM service_form_responses r 
    LEFT JOIN service_form_fields f ON r.field_id = f.id 
    WHERE r.application_id='$application_id'
    ORDER BY r.client_index, f.field_order
");

while($row = $sql->fetch_assoc()){
    $client_index = $row['client_index'];
    if(!isset($all_responses[$client_index])){
        $all_responses[$client_index] = [];
    }
    $all_responses[$client_index][$row['field_id']] = $row;
}

// Get uploaded documents for this application
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
$total_fields = count($form_fields) * $total_clients;
foreach($all_responses as $client_responses){
    $total_responses += count($client_responses);
}

$completion_percentage = $total_fields > 0 ? round(($total_responses / $total_fields) * 100) : 0;

// Get partner details if applicable
$partner_details = [];
if($user_type == 'partner'){
    $sql = $con->query("SELECT * FROM partner WHERE id='".$application['partner_id']."'");
    if($row = $sql->fetch_assoc()){
        $partner_details = $row;
    }
}

// Get CA details if assigned
$ca_details = [];
if($application['send_to']){
    $sql = $con->query("SELECT * FROM ca WHERE id='".$application['send_to']."'");
    if($row = $sql->fetch_assoc()){
        $ca_details = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Data - Legal Taxation</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .form-data-card {
      border-left: 4px solid #007bff;
      margin-bottom: 20px;
    }
    .user-type-badge {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: bold;
    }
    .customer-badge {
      background-color: #28a745;
      color: white;
    }
    .partner-badge {
      background-color: #007bff;
      color: white;
    }
    .file-badge {
      cursor: pointer;
      transition: all 0.3s;
    }
    .file-badge:hover {
      transform: scale(1.05);
    }
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      background: #f8f9fa;
      border-radius: 10px;
    }
    .field-value {
      background: #f8f9fa;
      padding: 10px;
      border-radius: 5px;
      border-left: 3px solid #28a745;
      word-break: break-word;
    }
    .file-link {
      display: inline-block;
      margin: 5px;
    }
    .client-tab {
      cursor: pointer;
      padding: 10px 15px;
      border-radius: 5px 5px 0 0;
    }
    .client-tab.active {
      background-color: #007bff;
      color: white;
    }
    .price-highlight {
      font-size: 1.2em;
      font-weight: bold;
      color: #28a745;
    }
    .form-progress-bar {
      height: 10px;
      border-radius: 5px;
    }
    .client-form-section {
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
    }
    .client-header {
      background-color: #f8f9fa;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      border-left: 4px solid #007bff;
    }
    .nav-tabs .nav-link {
      border: 1px solid #dee2e6;
      border-bottom: none;
      margin-right: 5px;
    }
    .nav-tabs .nav-link.active {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include("navbar.php"); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include("sidebar.php"); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-file-document"></i>
              </span>&nbsp;Application Form Data
              <span class="user-type-badge <?php echo $user_type == 'customer' ? 'customer-badge' : 'partner-badge'; ?>">
                <?php echo ucfirst($user_type); ?>
              </span>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="service_form_submissions.php?service_id=<?php echo $service_id; ?>">Form Submissions</a></li>
              <li class="breadcrumb-item active">Form Data</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Application Details Card -->
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-info-circle"></i> Application Details
                  <span class="badge badge-light ml-2">ID: #<?php echo $application['id']; ?></span>
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <h5>Application Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Application ID:</th>
                        <td><span class="badge badge-dark">#<?php echo $application['id']; ?></span></td>
                      </tr>
                      <tr>
                        <th>Applied Date:</th>
                        <td><?php echo date('d M Y, h:i A', strtotime($application['created'])); ?></td>
                      </tr>
                      <tr>
                        <th>Application Status:</th>
                        <td>
                          <?php if($application['status'] == '0'): ?>
                            <span class="badge badge-warning">Pending</span>
                          <?php elseif($application['status'] == '1'): ?>
                            <span class="badge badge-success">Approved</span>
                          <?php else: ?>
                            <span class="badge badge-secondary"><?php echo $application['status']; ?></span>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <?php if($application['payment_status']): ?>
                      <tr>
                        <th>Payment Status:</th>
                        <td>
                          <span class="badge badge-<?php echo $application['payment_status'] == 'Success' ? 'success' : 'warning'; ?>">
                            <?php echo $application['payment_status']; ?>
                          </span>
                        </td>
                      </tr>
                      <?php endif; ?>
                    </table>
                  </div>
                  
                  <div class="col-md-4">
                    <h5><?php echo $user_type == 'customer' ? 'Customer' : 'Partner'; ?> Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Name:</th>
                        <td><?php echo htmlspecialchars($application['user_name']); ?></td>
                      </tr>
                      <tr>
                        <th><?php echo $user_type == 'customer' ? 'Customer' : 'Partner'; ?> ID:</th>
                        <td><span class="badge badge-info">#<?php echo $user_type == 'customer' ? $application['cid'] : $application['partner_id']; ?></span></td>
                      </tr>
                      <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($application['user_email']); ?></td>
                      </tr>
                      <tr>
                        <th>Contact:</th>
                        <td><?php echo htmlspecialchars($application['user_contact']); ?></td>
                      </tr>
                      <?php if($user_type == 'partner'): ?>
                      <tr>
                        <th>Business:</th>
                        <td><?php echo htmlspecialchars($application['partner_business'] ?? 'N/A'); ?></td>
                      </tr>
                      <tr>
                        <th>GST:</th>
                        <td><?php echo htmlspecialchars($application['partner_gst'] ?? 'N/A'); ?></td>
                      </tr>
                      <?php endif; ?>
                    </table>
                  </div>
                  
                  <div class="col-md-4">
                    <h5>Service & Pricing</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Service:</th>
                        <td><?php echo htmlspecialchars($application['service_title']); ?></td>
                      </tr>
                      <tr>
                        <th>Category:</th>
                        <td><?php echo htmlspecialchars($application['category_name']); ?></td>
                      </tr>
                      <tr>
                        <th>Number of Clients:</th>
                        <td>
                          <?php echo $total_clients; ?>
                          <?php if($user_type == 'partner' && $total_clients > 1): ?>
                            <span class="badge badge-info ml-2">Bulk</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <tr>
                        <th>Price per Client:</th>
                        <td>
                          ₹<?php echo number_format($application['unit_price'], 2); ?>
                          <?php if($application['is_partner_price_applied']): ?>
                            <span class="badge badge-success ml-2">Partner Price</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                      <tr>
                        <th>Total Amount:</th>
                        <td class="price-highlight">₹<?php echo number_format($application['total_amount'], 2); ?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                
                <!-- Assignment Information -->
                <?php if(!empty($ca_details) || !$application['send_to']): ?>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="callout <?php echo $application['send_to'] ? 'callout-success' : 'callout-warning'; ?>">
                      <h5><i class="fas fa-user-tie"></i> CA Assignment</h5>
                      <?php if($application['send_to'] && !empty($ca_details)): ?>
                      <p>
                        <strong>Assigned to:</strong> <?php echo htmlspecialchars($ca_details['name']); ?> 
                        (Reg: <?php echo htmlspecialchars($ca_details['reg_no']); ?>)<br>
                        <strong>Contact:</strong> <?php echo htmlspecialchars($ca_details['contact']); ?> |
                        <strong>Email:</strong> <?php echo htmlspecialchars($ca_details['email']); ?>
                      </p>
                      <?php else: ?>
                      <p><strong>Status:</strong> Not assigned to any CA yet.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <a href="service_form_submissions.php?service_id=<?php echo $service_id; ?>" class="btn btn-secondary">
                  <i class="fa fa-arrow-left"></i> Back to Submissions
                </a>
                <?php if(!$application['send_to']): ?>
                <a href="#" class="btn btn-success" data-toggle="modal" data-target="#assignModal">
                  <i class="fa fa-user-plus"></i> Assign to CA
                </a>
                <?php else: ?>
                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#assignModal">
                  <i class="fa fa-user-edit"></i> Re-assign CA
                </a>
                <?php endif; ?>
                <button type="button" class="btn btn-primary" onclick="printFormData()">
                  <i class="fa fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-warning" onclick="copyApplicationDetails()">
                  <i class="fa fa-copy"></i> Copy Details
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Completion Progress -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-bar"></i> Form Completion Progress
                  <span class="badge badge-info ml-2"><?php echo $completion_percentage; ?>% Complete</span>
                </h3>
              </div>
              <div class="card-body">
                <div class="progress-group">
                  <div class="progress progress-sm">
                    <div class="progress-bar <?php 
                      echo $completion_percentage >= 100 ? 'bg-success' : 
                            ($completion_percentage >= 50 ? 'bg-warning' : 'bg-danger'); 
                    ?>" style="width: <?php echo $completion_percentage; ?>%"></div>
                  </div>
                  <div class="progress-text">
                    <span class="float-left">
                      <b><?php echo $total_responses; ?></b> out of <b><?php echo $total_fields; ?></b> fields filled
                      (<?php echo count($form_fields); ?> fields × <?php echo $total_clients; ?> clients)
                    </span>
                    <span class="float-right">
                      <?php echo $completion_percentage; ?>%
                    </span>
                  </div>
                </div>
                
                <?php if($user_type == 'partner' && $total_clients > 1): ?>
                <div class="mt-3">
                  <h6>Client-wise Progress:</h6>
                  <div class="row">
                    <?php for($i = 1; $i <= $total_clients; $i++): 
                      $client_responses = $all_responses[$i] ?? [];
                      $client_fields_filled = count($client_responses);
                      $client_percentage = count($form_fields) > 0 ? round(($client_fields_filled / count($form_fields)) * 100) : 0;
                    ?>
                    <div class="col-md-3 col-6">
                      <div class="info-box mb-3">
                        <span class="info-box-icon bg-info">
                          <i class="fas fa-user"></i>
                        </span>
                        <div class="info-box-content">
                          <span class="info-box-text">Client <?php echo $i; ?></span>
                          <span class="info-box-number"><?php echo $client_percentage; ?>%</span>
                          <div class="progress">
                            <div class="progress-bar bg-info" style="width: <?php echo $client_percentage; ?>%"></div>
                          </div>
                          <span class="progress-description">
                            <?php echo $client_fields_filled; ?> of <?php echo count($form_fields); ?> fields
                          </span>
                        </div>
                      </div>
                    </div>
                    <?php endfor; ?>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Client Form Data Tabs -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-list-alt"></i> Form Data
                  <?php if($user_type == 'partner' && $total_clients > 1): ?>
                    <small class="text-muted">(<?php echo $total_clients; ?> clients)</small>
                  <?php endif; ?>
                </h3>
              </div>
              <div class="card-body">
                <?php if(empty($form_fields)): ?>
                  <div class="empty-state">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h4>No Form Fields Configured</h4>
                    <p class="text-muted">This service doesn't have any custom form fields configured.</p>
                    <a href="service_form_fields.php?service_id=<?php echo $service_id; ?>" class="btn btn-primary">
                      <i class="fa fa-plus"></i> Configure Form Fields
                    </a>
                  </div>
                <?php elseif(empty($all_responses)): ?>
                  <div class="empty-state">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4>No Form Data Submitted</h4>
                    <p class="text-muted">
                      <?php echo $user_type == 'customer' ? 'The customer' : 'The partner'; ?> hasn't filled the form yet.
                    </p>
                    <div class="alert alert-warning">
                      <i class="fa fa-exclamation-triangle"></i> 
                      Form needs to be filled from <?php echo $user_type; ?> dashboard.
                    </div>
                  </div>
                <?php else: ?>
                  <!-- Client Tabs (for multiple clients) -->
                  <?php if($user_type == 'partner' && $total_clients > 1): ?>
                  <ul class="nav nav-tabs" id="clientTabs" role="tablist">
                    <?php for($i = 1; $i <= $total_clients; $i++): 
                      $client_responses = $all_responses[$i] ?? [];
                      $client_fields_filled = count($client_responses);
                      $client_percentage = count($form_fields) > 0 ? round(($client_fields_filled / count($form_fields)) * 100) : 0;
                    ?>
                    <li class="nav-item">
                      <a class="nav-link <?php echo $i == 1 ? 'active' : ''; ?>" 
                         id="client-<?php echo $i; ?>-tab" 
                         data-toggle="tab" 
                         href="#client-<?php echo $i; ?>" 
                         role="tab"
                         aria-controls="client-<?php echo $i; ?>"
                         aria-selected="<?php echo $i == 1 ? 'true' : 'false'; ?>">
                         Client <?php echo $i; ?>
                         <?php if(!empty($client_responses)): ?>
                           <span class="badge badge-success"><?php echo $client_fields_filled; ?> fields</span>
                         <?php else: ?>
                           <span class="badge badge-danger">Empty</span>
                         <?php endif; ?>
                      </a>
                    </li>
                    <?php endfor; ?>
                  </ul>
                  
                  <!-- Tab Content -->
                  <div class="tab-content mt-3" id="clientTabsContent">
                    <?php for($i = 1; $i <= $total_clients; $i++): 
                      $client_responses = $all_responses[$i] ?? [];
                    ?>
                    <div class="tab-pane fade <?php echo $i == 1 ? 'show active' : ''; ?>" 
                         id="client-<?php echo $i; ?>" 
                         role="tabpanel"
                         aria-labelledby="client-<?php echo $i; ?>-tab">
                         
                      <div class="client-form-section">
                        <div class="client-header">
                          <h5>
                            <i class="fas fa-user"></i> 
                            Client <?php echo $i; ?> Details
                            <?php if(!empty($client_responses)): ?>
                              <span class="badge badge-success float-right">
                                <?php echo count($client_responses); ?>/<?php echo count($form_fields); ?> fields
                              </span>
                            <?php endif; ?>
                          </h5>
                        </div>
                        
                        <?php if(empty($client_responses)): ?>
                          <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                            <h5>No Data for This Client</h5>
                            <p class="text-muted">Form data has not been submitted for this client yet.</p>
                          </div>
                        <?php else: ?>
                          <div class="table-responsive" id="formDataTable<?php echo $i; ?>">
                            <table class="table table-bordered table-hover">
                              <thead class="thead-light">
                                <tr>
                                  <th width="5%">#</th>
                                  <th width="30%">Field Name</th>
                                  <th width="25%">Field Type</th>
                                  <th width="40%">Customer Response</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach($form_fields as $field): 
                                  $response = $client_responses[$field['id']] ?? null;
                                  $field_value = $response ? $response['field_value'] : 'Not Provided';
                                ?>
                                  <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td>
                                      <strong><?php echo htmlspecialchars($field['field_label']); ?></strong><br>
                                      <small class="text-muted">
                                        <code><?php echo $field['field_name']; ?></code>
                                        <?php if($field['is_required']): ?>
                                          <span class="badge badge-danger ml-2">Required</span>
                                        <?php endif; ?>
                                      </small>
                                    </td>
                                    <td>
                                      <span class="badge badge-secondary">
                                        <?php echo strtoupper($field['field_type']); ?>
                                      </span>
                                      <?php if($field['validation_rules']): ?>
                                        <br><small class="text-info"><?php echo $field['validation_rules']; ?></small>
                                      <?php endif; ?>
                                    </td>
                                    <td>
                                      <?php if($field['field_type'] == 'file' && $field_value != 'Not Provided'): ?>
                                        <div class="file-link">
                                          <a href="../uploads/service_docs/<?php echo $field_value; ?>" 
                                             target="_blank" class="btn btn-sm btn-info">
                                            <i class="fa fa-download"></i> Download File
                                          </a>
                                          <br>
                                          <small class="text-muted"><?php echo basename($field_value); ?></small>
                                        </div>
                                      <?php elseif($field['field_type'] == 'checkbox' && $field_value != 'Not Provided'): 
                                        $values = explode(',', $field_value);
                                        foreach($values as $val):
                                      ?>
                                        <span class="badge badge-primary mb-1"><?php echo htmlspecialchars(trim($val)); ?></span><br>
                                      <?php endforeach;
                                      elseif($field['field_type'] == 'select' || $field['field_type'] == 'radio'): ?>
                                        <div class="field-value">
                                          <?php echo htmlspecialchars($field_value); ?>
                                        </div>
                                      <?php else: ?>
                                        <div class="field-value">
                                          <?php echo nl2br(htmlspecialchars($field_value)); ?>
                                        </div>
                                      <?php endif; ?>
                                      <?php if($field['help_text'] && $field_value != 'Not Provided'): ?>
                                        <small class="text-info">
                                          <i class="fa fa-info-circle"></i> <?php echo htmlspecialchars($field['help_text']); ?>
                                        </small>
                                      <?php endif; ?>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                    <?php endfor; ?>
                  </div>
                  <?php else: ?>
                  <!-- Single client view (for customer or single client partner) -->
                  <div class="client-form-section">
                    <div class="client-header">
                      <h5>
                        <i class="fas fa-user"></i> Form Data
                        <?php if(!empty($all_responses[1])): ?>
                          <span class="badge badge-success float-right">
                            <?php echo count($all_responses[1]); ?>/<?php echo count($form_fields); ?> fields
                          </span>
                        <?php endif; ?>
                      </h5>
                    </div>
                    
                    <?php 
                    $client_responses = $all_responses[1] ?? [];
                    if(empty($client_responses)): 
                    ?>
                      <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-3"></i>
                        <h5>No Data Available</h5>
                        <p class="text-muted">Form data has not been submitted yet.</p>
                      </div>
                    <?php else: ?>
                      <div class="table-responsive" id="formDataTable">
                        <table class="table table-bordered table-hover">
                          <thead class="thead-light">
                            <tr>
                              <th width="5%">#</th>
                              <th width="30%">Field Name</th>
                              <th width="25%">Field Type</th>
                              <th width="40%">Customer Response</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php $counter = 1; ?>
                            <?php foreach($form_fields as $field): 
                              $response = $client_responses[$field['id']] ?? null;
                              $field_value = $response ? $response['field_value'] : 'Not Provided';
                            ?>
                              <tr>
                                <td><?php echo $counter++; ?></td>
                                <td>
                                  <strong><?php echo htmlspecialchars($field['field_label']); ?></strong><br>
                                  <small class="text-muted">
                                    <code><?php echo $field['field_name']; ?></code>
                                    <?php if($field['is_required']): ?>
                                      <span class="badge badge-danger ml-2">Required</span>
                                    <?php endif; ?>
                                  </small>
                                </td>
                                <td>
                                  <span class="badge badge-secondary">
                                    <?php echo strtoupper($field['field_type']); ?>
                                  </span>
                                  <?php if($field['validation_rules']): ?>
                                    <br><small class="text-info"><?php echo $field['validation_rules']; ?></small>
                                  <?php endif; ?>
                                </td>
                                <td>
                                  <?php if($field['field_type'] == 'file' && $field_value != 'Not Provided'): ?>
                                    <div class="file-link">
                                      <a href="../uploads/service_docs/<?php echo $field_value; ?>" 
                                         target="_blank" class="btn btn-sm btn-info">
                                        <i class="fa fa-download"></i> Download File
                                      </a>
                                      <br>
                                      <small class="text-muted"><?php echo basename($field_value); ?></small>
                                    </div>
                                  <?php elseif($field['field_type'] == 'checkbox' && $field_value != 'Not Provided'): 
                                    $values = explode(',', $field_value);
                                    foreach($values as $val):
                                  ?>
                                    <span class="badge badge-primary mb-1"><?php echo htmlspecialchars(trim($val)); ?></span><br>
                                  <?php endforeach;
                                  elseif($field['field_type'] == 'select' || $field['field_type'] == 'radio'): ?>
                                    <div class="field-value">
                                      <?php echo htmlspecialchars($field_value); ?>
                                    </div>
                                  <?php else: ?>
                                    <div class="field-value">
                                      <?php echo nl2br(htmlspecialchars($field_value)); ?>
                                    </div>
                                  <?php endif; ?>
                                  <?php if($field['help_text'] && $field_value != 'Not Provided'): ?>
                                    <small class="text-info">
                                      <i class="fa fa-info-circle"></i> <?php echo htmlspecialchars($field['help_text']); ?>
                                    </small>
                                  <?php endif; ?>
                                </td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    <?php endif; ?>
                  </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <small class="text-muted">
                  <i class="fa fa-clock"></i> Last Updated: 
                  <?php 
                  if(!empty($all_responses)){
                      $last_response = end($all_responses);
                      if(is_array($last_response)){
                          $last_response = end($last_response);
                      }
                      if($last_response && isset($last_response['created_at'])){
                          echo date('d M Y, h:i A', strtotime($last_response['created_at']));
                      } else {
                          echo 'Never';
                      }
                  } else {
                      echo 'Never';
                  }
                  ?>
                  | <i class="fa fa-database"></i> Total Responses: <?php echo $total_responses; ?>
                </small>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Information Sidebar -->
        <div class="row">
          <div class="col-md-8">
            <!-- Uploaded Documents -->
            <div class="card">
              <div class="card-header bg-success">
                <h3 class="card-title">
                  <i class="fa fa-file-upload"></i> Uploaded Documents
                  <span class="badge badge-light ml-2"><?php echo count($documents); ?></span>
                </h3>
              </div>
              <div class="card-body">
                <?php if(empty($documents)): ?>
                  <div class="text-center text-muted py-3">
                    <i class="fa fa-folder-open fa-3x mb-2"></i>
                    <p>No documents uploaded</p>
                  </div>
                <?php else: ?>
                  <div class="list-group">
                    <?php foreach($documents as $doc): ?>
                      <a href="../uploads/<?php echo $doc['file']; ?>" 
                         target="_blank" 
                         class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                          <?php if(preg_match('/\.pdf$/i', $doc['file'])): ?>
                            <i class="fa fa-file-pdf text-danger mr-2"></i>
                          <?php elseif(preg_match('/\.(jpg|jpeg|png|gif)$/i', $doc['file'])): ?>
                            <i class="fa fa-file-image text-primary mr-2"></i>
                          <?php elseif(preg_match('/\.(doc|docx)$/i', $doc['file'])): ?>
                            <i class="fa fa-file-word text-info mr-2"></i>
                          <?php else: ?>
                            <i class="fa fa-file text-secondary mr-2"></i>
                          <?php endif; ?>
                          <?php echo htmlspecialchars($doc['doc_name'] ?? 'Document'); ?>
                        </div>
                        <div>
                          <small class="text-muted mr-2">
                            <?php echo date('d M Y', strtotime($doc['created'])); ?>
                          </small>
                          <span class="badge badge-primary">
                            <i class="fa fa-download"></i>
                          </span>
                        </div>
                      </a>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <small class="text-muted">
                  These are required documents for the service. Click to download.
                </small>
              </div>
            </div>
          </div>
          
          <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card">
              <div class="card-header bg-secondary">
                <h3 class="card-title">
                  <i class="fa fa-bolt"></i> Quick Actions
                </h3>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <a href="mailto:<?php echo $application['user_email']; ?>" 
                     class="btn btn-outline-primary btn-block">
                    <i class="fa fa-envelope"></i> Email <?php echo $user_type == 'customer' ? 'Customer' : 'Partner'; ?>
                  </a>
                  <a href="tel:<?php echo $application['user_contact']; ?>" 
                     class="btn btn-outline-success btn-block">
                    <i class="fa fa-phone"></i> Call <?php echo $user_type == 'customer' ? 'Customer' : 'Partner'; ?>
                  </a>
                  <?php if($application['send_to'] && !empty($ca_details)): ?>
                  <a href="mailto:<?php echo $ca_details['email']; ?>" 
                     class="btn btn-outline-info btn-block">
                    <i class="fa fa-envelope"></i> Email CA
                  </a>
                  <a href="tel:<?php echo $ca_details['contact']; ?>" 
                     class="btn btn-outline-info btn-block">
                    <i class="fa fa-phone"></i> Call CA
                  </a>
                  <?php endif; ?>
                  <a href="edit_application.php?id=<?php echo $application_id; ?>" 
                     class="btn btn-outline-warning btn-block">
                    <i class="fa fa-edit"></i> Edit Application
                  </a>
                  <a href="send_reminder.php?application_id=<?php echo $application_id; ?>" 
                     class="btn btn-outline-danger btn-block">
                    <i class="fa fa-bell"></i> Send Reminder
                  </a>
                </div>
              </div>
            </div>

            <!-- Payment Information -->
            <?php if($application['payment_status']): ?>
            <div class="card mt-3">
              <div class="card-header bg-info">
                <h3 class="card-title">
                  <i class="fa fa-credit-card"></i> Payment Information
                </h3>
              </div>
              <div class="card-body">
                <div class="text-center">
                  <div class="mb-3">
                    <span class="badge badge-<?php echo $application['payment_status'] == 'Success' ? 'success' : 'warning'; ?> p-2" style="font-size: 1em;">
                      <?php echo $application['payment_status']; ?>
                    </span>
                  </div>
                  <p class="mb-1">
                    <strong>Amount:</strong><br>
                    <span class="price-highlight">₹<?php echo number_format($application['total_amount'], 2); ?></span>
                  </p>
                  <?php if($application['billing_name']): ?>
                  <p class="mb-1">
                    <strong>Billed To:</strong><br>
                    <?php echo htmlspecialchars($application['billing_name']); ?>
                  </p>
                  <?php endif; ?>
                  <?php if($application['payment_date']): ?>
                  <p class="mb-0">
                    <small class="text-muted">
                      <i class="fa fa-calendar"></i> 
                      <?php echo date('d M Y', strtotime($application['payment_date'])); ?>
                    </small>
                  </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Notes Section -->
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-sticky-note"></i> Admin Notes
                </h3>
              </div>
              <div class="card-body">
                <form method="POST" action="save_notes.php" id="notesForm">
                  <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
                  <div class="form-group">
                    <textarea class="form-control" name="admin_notes" rows="3" 
                              placeholder="Add notes about this application..." 
                              id="adminNotes"></textarea>
                  </div>
                  <button type="button" class="btn btn-primary" onclick="saveNotes()">
                    <i class="fa fa-save"></i> Save Notes
                  </button>
                </form>
                
                <!-- Previous Notes (if any) -->
                <?php
                // Check if application_notes table exists with proper structure
                $notes_sql = $con->query("SHOW TABLES LIKE 'application_notes'");
                if($notes_sql->num_rows > 0){
                    // Check table structure
                    $table_check = $con->query("SHOW COLUMNS FROM application_notes LIKE 'admin_id'");
                    if($table_check->num_rows > 0){
                        // Table has admin_id column
                        $notes_sql = $con->query("
                            SELECT n.*, a.name as admin_name 
                            FROM application_notes n 
                            LEFT JOIN admin a ON n.admin_id = a.id 
                            WHERE n.application_id='$application_id' 
                            ORDER BY n.created_at DESC
                        ");
                    } else {
                        // Table doesn't have admin_id column
                        $notes_sql = $con->query("
                            SELECT n.*, '$admin_name' as admin_name 
                            FROM application_notes n 
                            WHERE n.application_id='$application_id' 
                            ORDER BY n.created_at DESC
                        ");
                    }
                    
                    if($notes_sql->num_rows > 0):
                ?>
                <hr>
                <h6>Previous Notes:</h6>
                <div class="timeline">
                  <?php while($note = $notes_sql->fetch_assoc()): ?>
                  <div class="timeline-item">
                    <span class="time">
                      <i class="fa fa-clock"></i> 
                      <?php echo date('d M Y, h:i A', strtotime($note['created_at'])); ?>
                    </span>
                    <h3 class="timeline-header">
                      <a href="#"><?php echo htmlspecialchars($note['admin_name']); ?></a> added a note
                    </h3>
                    <div class="timeline-body">
                      <?php echo nl2br(htmlspecialchars($note['notes'])); ?>
                    </div>
                  </div>
                  <?php endwhile; ?>
                </div>
                <?php 
                    endif;
                }
                ?>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Legal Taxation</a></strong>
    All rights reserved.
  </footer>
</div>

<!-- Assign to CA Modal -->
<div class="modal fade" id="assignModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="assign_ca.php">
        <div class="modal-header">
          <h4 class="modal-title">
            <i class="fa fa-user-tie"></i> Assign to Chartered Accountant
          </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
          <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
          <div class="form-group">
            <label>Select CA:</label>
            <select class="form-control" name="ca_id" required>
              <option value="">-- Select CA --</option>
              <?php
              $ca_sql = $con->query("SELECT * FROM ca WHERE status='1' ORDER BY name ASC");
              while($ca = $ca_sql->fetch_assoc()):
                $selected = ($application['send_to'] == $ca['id']) ? 'selected' : '';
              ?>
                <option value="<?php echo $ca['id']; ?>" <?php echo $selected; ?>>
                  <?php echo htmlspecialchars($ca['name']); ?> 
                  (Reg: <?php echo $ca['reg_no']; ?>)
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Notes (Optional):</label>
            <textarea class="form-control" name="assignment_notes" 
                      placeholder="Add notes for the CA..." rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Assign</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
function printFormData() {
    var printContent = '';
    
    <?php if($user_type == 'partner' && $total_clients > 1): ?>
    // For multiple clients, print current tab
    var activeTab = document.querySelector('.tab-pane.active');
    if(activeTab) {
        var table = activeTab.querySelector('.table-responsive');
        if(table) {
            printContent = table.innerHTML;
        }
    }
    <?php else: ?>
    // For single client
    var table = document.querySelector('#formDataTable');
    if(table) {
        printContent = table.innerHTML;
    }
    <?php endif; ?>
    
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Form Data - Application #<?php echo $application_id; ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; }
                th { background-color: #f2f2f2; }
                .print-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .print-info { margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 5px; }
                .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; }
                .badge-success { background-color: #28a745; color: white; }
                .badge-danger { background-color: #dc3545; color: white; }
                .badge-secondary { background-color: #6c757d; color: white; }
                .badge-primary { background-color: #007bff; color: white; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>Form Data Report</h2>
                <p>Application #<?php echo $application_id; ?> | <?php echo date('d M Y, h:i A'); ?></p>
            </div>
            
            <div class="print-info">
                <p><strong>User:</strong> <?php echo htmlspecialchars($application['user_name']); ?> (<?php echo ucfirst($user_type); ?>)</p>
                <p><strong>Service:</strong> <?php echo htmlspecialchars($application['service_title']); ?></p>
                <p><strong>Applied Date:</strong> <?php echo date('d M Y, h:i A', strtotime($application['created'])); ?></p>
                <p><strong>Form Completion:</strong> <?php echo $completion_percentage; ?>% (<?php echo $total_responses; ?>/<?php echo $total_fields; ?> fields)</p>
            </div>
            
            ${printContent}
            
            <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
                <p>Generated by Legal Taxation Admin Panel</p>
                <p>Print Date: <?php echo date('d M Y, h:i A'); ?></p>
            </div>
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}

function copyApplicationDetails() {
    var details = `Application Details:\n====================\n`;
    details += `Application ID: #<?php echo $application_id; ?>\n`;
    details += `User Type: <?php echo ucfirst($user_type); ?>\n`;
    details += `User Name: <?php echo htmlspecialchars($application['user_name']); ?>\n`;
    details += `Email: <?php echo htmlspecialchars($application['user_email']); ?>\n`;
    details += `Contact: <?php echo htmlspecialchars($application['user_contact']); ?>\n\n`;
    
    details += `Service Details:\n================\n`;
    details += `Service: <?php echo htmlspecialchars($application['service_title']); ?>\n`;
    details += `Category: <?php echo htmlspecialchars($application['category_name']); ?>\n`;
    details += `Number of Clients: <?php echo $total_clients; ?>\n`;
    details += `Unit Price: ₹<?php echo number_format($application['unit_price'], 2); ?>\n`;
    details += `Total Amount: ₹<?php echo number_format($application['total_amount'], 2); ?>\n`;
    details += `Applied Date: <?php echo date('d M Y, h:i A', strtotime($application['created'])); ?>\n\n`;
    
    details += `Payment Status: <?php echo $application['payment_status'] ?? 'N/A'; ?>\n\n`;
    
    details += `Form Progress:\n==============\n`;
    details += `Completion: <?php echo $completion_percentage; ?>%\n`;
    details += `Fields Filled: <?php echo $total_responses; ?> out of <?php echo $total_fields; ?>\n`;
    
    <?php if(!empty($all_responses)): ?>
    details += '\n\nForm Data:\n==========\n';
    <?php 
    $client_counter = 0;
    foreach($all_responses as $client_index => $client_responses): 
        $client_counter++;
        if($total_clients > 1):
            echo "details += '\\nClient " . $client_index . ":\\n';";
        endif;
        foreach($form_fields as $field): 
            $response = $client_responses[$field['id']] ?? null;
            $field_value = $response ? $response['field_value'] : 'Not Provided';
            $field_label_escaped = addslashes($field['field_label']);
            $field_value_escaped = addslashes($field_value);
            echo "details += '- " . $field_label_escaped . ": " . $field_value_escaped . "\\n';";
        endforeach; 
    endforeach; 
    ?>
    <?php endif; ?>
    
    navigator.clipboard.writeText(details).then(function() {
        alert('Application details copied to clipboard!');
    });
}

function saveNotes() {
    var notes = document.getElementById('adminNotes').value;
    var applicationId = <?php echo $application_id; ?>;
    
    if(notes.trim() === '') {
        alert('Please enter some notes before saving.');
        return;
    }
    
    // AJAX call to save notes
    $.ajax({
        url: 'save_notes.php',
        type: 'POST',
        data: {
            application_id: applicationId,
            admin_notes: notes
        },
        success: function(response) {
            try {
                var result = JSON.parse(response);
                if(result.success) {
                    alert('Notes saved successfully!');
                    location.reload();
                } else {
                    alert('Error saving notes: ' + result.message);
                }
            } catch(e) {
                if(response.trim() === 'success') {
                    alert('Notes saved successfully!');
                    location.reload();
                } else {
                    alert('Notes saved!');
                    location.reload();
                }
            }
        },
        error: function() {
            alert('Error saving notes. Please try again.');
        }
    });
}

// Auto-expand textareas
$(document).ready(function() {
    $('textarea').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Initialize Bootstrap tabs
    $('#clientTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
});
</script>
</body>
</html>