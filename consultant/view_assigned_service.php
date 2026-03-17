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
  <title>View Assigned Service - Legal Taxation</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .info-card {
      border-left: 4px solid #007bff;
      margin-bottom: 20px;
    }
    .user-type-badge {
      padding: 3px 8px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: normal;
    }
    .customer-badge {
      background-color: #28a745;
      color: white;
    }
    .partner-badge {
      background-color: #007bff;
      color: white;
    }
    .field-value {
      background: #f8f9fa;
      padding: 8px;
      border-radius: 4px;
      border-left: 3px solid #28a745;
    }
    .document-item {
      border: 1px solid #dee2e6;
      border-radius: 4px;
      padding: 10px;
      margin-bottom: 10px;
      transition: all 0.3s;
    }
    .document-item:hover {
      background: #f8f9fa;
      border-color: #007bff;
    }
    .timeline-item {
      padding: 15px;
      border-left: 3px solid #007bff;
      background: #f8f9fa;
      margin-bottom: 10px;
      border-radius: 0 5px 5px 0;
    }
    .status-badge {
      padding: 5px 10px;
      border-radius: 15px;
      font-size: 0.9rem;
    }
    .price-highlight {
      font-size: 1.3rem;
      font-weight: bold;
      color: #28a745;
    }
    .client-tab {
      cursor: pointer;
      padding: 8px 15px;
      margin-right: 5px;
      border: 1px solid #dee2e6;
      border-radius: 5px 5px 0 0;
    }
    .client-tab.active {
      background: #007bff;
      color: white;
      border-color: #007bff;
    }
    .client-content {
      border: 1px solid #dee2e6;
      padding: 20px;
      border-radius: 0 5px 5px 5px;
      margin-top: -1px;
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
            <h1 class="m-0">
              <i class="fas fa-file-alt mr-2"></i>Service Details
              <small class="text-muted">Application #<?php echo $application_id; ?></small>
            </h1>
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
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Quick Actions Row -->
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="btn-group">
                  <a href="assigned_services.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                  </a>
                  <?php if($user_type == 'partner' && $total_clients > 1): ?>
                    <a href="view_partner_clients_ca.php?application_id=<?php echo $application_id; ?>" class="btn btn-primary">
                      <i class="fas fa-users"></i> View All Clients
                    </a>
                  <?php else: ?>
                    <a href="view_customer_form.php?application_id=<?php echo $application_id; ?>" class="btn btn-primary">
                      <i class="fas fa-file-alt"></i> View Form Data
                    </a>
                  <?php endif; ?>
                  <button type="button" class="btn btn-success" onclick="updateStatus(<?php echo $application_id; ?>, <?php echo $application['status']; ?>)">
                    <i class="fas fa-<?php echo $application['status'] == '0' ? 'check' : 'undo'; ?>"></i>
                    <?php echo $application['status'] == '0' ? 'Mark Completed' : 'Mark Pending'; ?>
                  </button>
                  <button type="button" class="btn btn-info" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                  </button>
                </div>
                
                <div class="float-right">
                  <span class="badge <?php echo $application['status'] == '0' ? 'badge-warning' : 'badge-success'; ?> status-badge mr-2">
                    <?php echo $application['status'] == '0' ? 'Pending' : 'Completed'; ?>
                  </span>
                  <span class="user-type-badge <?php echo $user_type == 'customer' ? 'customer-badge' : 'partner-badge'; ?>">
                    <?php echo ucfirst($user_type); ?> Application
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Details Row -->
        <div class="row">
          <!-- Left Column - User and Payment Details -->
          <div class="col-md-4">
            <!-- User Information -->
            <div class="card info-card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-user mr-2"></i>
                  <?php echo $user_type == 'customer' ? 'Customer' : 'Partner'; ?> Information
                </h3>
              </div>
              <div class="card-body">
                <table class="table table-sm">
                  <tr>
                    <th width="40%">Name:</th>
                    <td><strong><?php echo htmlspecialchars($application['user_name']); ?></strong></td>
                  </tr>
                  <tr>
                    <th>Email:</th>
                    <td><a href="mailto:<?php echo $application['user_email']; ?>"><?php echo htmlspecialchars($application['user_email']); ?></a></td>
                  </tr>
                  <tr>
                    <th>Contact:</th>
                    <td><a href="tel:<?php echo $application['user_contact']; ?>"><?php echo htmlspecialchars($application['user_contact']); ?></a></td>
                  </tr>
                  <tr>
                    <th>ID:</th>
                    <td><span class="badge badge-secondary">#<?php echo $application[$user_type == 'customer' ? 'cid' : 'partner_id']; ?></span></td>
                  </tr>
                  <?php if($user_type == 'partner'): ?>
                  <tr>
                    <th>Business:</th>
                    <td><?php echo htmlspecialchars($application['business_name'] ?? 'N/A'); ?></td>
                  </tr>
                  <tr>
                    <th>GST:</th>
                    <td><?php echo htmlspecialchars($application['gst_number'] ?? 'N/A'); ?></td>
                  </tr>
                  <?php endif; ?>
                </table>
                
                <div class="mt-3">
                  <a href="mailto:<?php echo $application['user_email']; ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-envelope"></i> Email
                  </a>
                  <a href="tel:<?php echo $application['user_contact']; ?>" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-phone"></i> Call
                  </a>
                </div>
              </div>
            </div>
            
            <!-- Payment Information -->
            <?php if($application['payment_status']): ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-credit-card mr-2"></i>Payment Details</h3>
              </div>
              <div class="card-body">
                <table class="table table-sm">
                  <tr>
                    <th>Status:</th>
                    <td>
                      <span class="badge badge-<?php echo $application['payment_status'] == 'Success' ? 'success' : 'warning'; ?>">
                        <?php echo $application['payment_status']; ?>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <th>Amount:</th>
                    <td class="price-highlight">₹<?php echo number_format($application['total_amount'], 2); ?></td>
                  </tr>
                  <tr>
                    <th>Unit Price:</th>
                    <td>₹<?php echo number_format($application['unit_price'], 2); ?></td>
                  </tr>
                  <?php if($application['is_partner_price_applied']): ?>
                  <tr>
                    <th>Partner Price:</th>
                    <td><span class="badge badge-success">Applied</span></td>
                  </tr>
                  <?php endif; ?>
                  <?php if($application['billing_name']): ?>
                  <tr>
                    <th>Billed To:</th>
                    <td><?php echo htmlspecialchars($application['billing_name']); ?></td>
                  </tr>
                  <?php endif; ?>
                  <?php if($application['payment_date']): ?>
                  <tr>
                    <th>Date:</th>
                    <td><?php echo date('d M Y', strtotime($application['payment_date'])); ?></td>
                  </tr>
                  <?php endif; ?>
                </table>
              </div>
            </div>
            <?php endif; ?>
            
            <!-- Application Info -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Application Info</h3>
              </div>
              <div class="card-body">
                <table class="table table-sm">
                  <tr>
                    <th>Applied On:</th>
                    <td><?php echo date('d M Y, h:i A', strtotime($application['created'])); ?></td>
                  </tr>
                  <tr>
                    <th>Clients:</th>
                    <td>
                      <?php echo $total_clients; ?>
                      <?php if($user_type == 'partner' && $total_clients > 1): ?>
                        <span class="badge badge-info ml-2">Bulk</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <th>Form Progress:</th>
                    <td>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-<?php echo $completion_percentage >= 100 ? 'success' : ($completion_percentage >= 50 ? 'warning' : 'danger'); ?>" 
                             style="width: <?php echo $completion_percentage; ?>%"></div>
                      </div>
                      <small><?php echo $completion_percentage; ?>% (<?php echo $total_responses; ?>/<?php echo $total_expected_fields; ?> fields)</small>
                    </td>
                  </tr>
                  <tr>
                    <th>Documents:</th>
                    <td><span class="badge badge-info"><?php echo count($documents); ?> uploaded</span></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          
          <!-- Right Column - Service and Form Details -->
          <div class="col-md-8">
            <!-- Service Information -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-copy mr-2"></i><?php echo htmlspecialchars($application['service_title']); ?>
                  <small class="text-muted ml-2">(<?php echo htmlspecialchars($application['category_name']); ?>)</small>
                </h3>
              </div>
              <div class="card-body">
                <?php if($application['short_description']): ?>
                <div class="mb-3">
                  <h6>Short Description:</h6>
                  <p><?php echo nl2br(htmlspecialchars($application['short_description'])); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if($application['full_description']): ?>
                <div>
                  <h6>Full Description:</h6>
                  <div class="p-3 bg-light">
                    <?php echo $application['full_description']; ?>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            </div>
            
            <!-- Form Data Summary -->
            <?php if(!empty($form_fields)): ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-file-alt mr-2"></i>Form Data Summary
                </h3>
                <?php if($user_type == 'partner' && $total_clients > 1): ?>
                <div class="card-tools">
                  <a href="view_partner_clients_ca.php?application_id=<?php echo $application_id; ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-users"></i> View All Clients
                  </a>
                </div>
                <?php endif; ?>
              </div>
              <div class="card-body">
                <?php if(empty($form_responses)): ?>
                  <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    No form data has been submitted yet.
                  </div>
                <?php else: ?>
                  <?php if($user_type == 'partner' && $total_clients > 1): ?>
                    <!-- Client Tabs -->
                    <ul class="nav nav-tabs" id="clientTabs">
                      <?php for($i = 1; $i <= $total_clients; $i++): 
                        $client_responses = $form_responses[$i] ?? [];
                      ?>
                      <li class="nav-item">
                        <a class="nav-link <?php echo $i == 1 ? 'active' : ''; ?>" 
                           data-toggle="tab" 
                           href="#client-<?php echo $i; ?>">
                          Client <?php echo $i; ?>
                          <span class="badge badge-<?php echo count($client_responses) == count($form_fields) ? 'success' : 'warning'; ?> ml-1">
                            <?php echo count($client_responses); ?>/<?php echo count($form_fields); ?>
                          </span>
                        </a>
                      </li>
                      <?php endfor; ?>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content mt-3">
                      <?php for($i = 1; $i <= $total_clients; $i++): 
                        $client_responses = $form_responses[$i] ?? [];
                      ?>
                      <div class="tab-pane fade <?php echo $i == 1 ? 'show active' : ''; ?>" id="client-<?php echo $i; ?>">
                        <?php if(empty($client_responses)): ?>
                          <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            No form data for this client.
                          </div>
                        <?php else: ?>
                          <table class="table table-bordered table-sm">
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
                        <?php endif; ?>
                      </div>
                      <?php endfor; ?>
                    </div>
                  <?php else: ?>
                    <!-- Single Client View -->
                    <table class="table table-bordered">
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
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>
            
            <!-- Documents -->
            <?php if(!empty($documents)): ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-upload mr-2"></i>Uploaded Documents</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <?php foreach($documents as $doc): ?>
                  <div class="col-md-6">
                    <div class="document-item">
                      <a href="../uploads/<?php echo $doc['file']; ?>" target="_blank">
                        <?php if(preg_match('/\.pdf$/i', $doc['file'])): ?>
                          <i class="fas fa-file-pdf fa-2x text-danger mr-2"></i>
                        <?php elseif(preg_match('/\.(jpg|jpeg|png|gif)$/i', $doc['file'])): ?>
                          <i class="fas fa-file-image fa-2x text-primary mr-2"></i>
                        <?php else: ?>
                          <i class="fas fa-file fa-2x text-secondary mr-2"></i>
                        <?php endif; ?>
                        <strong><?php echo htmlspecialchars($doc['doc_name'] ?? 'Document'); ?></strong>
                      </a>
                      <br>
                      <small class="text-muted">
                        <i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($doc['created'])); ?>
                      </small>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
            
            <!-- Admin Notes -->
            <?php if(!empty($notes)): ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-sticky-note mr-2"></i>Admin Notes</h3>
              </div>
              <div class="card-body">
                <?php foreach($notes as $note): ?>
                <div class="timeline-item">
                  <div class="d-flex justify-content-between">
                    <strong><?php echo htmlspecialchars($note['admin_name'] ?? 'Admin'); ?></strong>
                    <small class="text-muted"><?php echo date('d M Y, h:i A', strtotime($note['created_at'])); ?></small>
                  </div>
                  <p class="mb-0 mt-2"><?php echo nl2br(htmlspecialchars($note['notes'])); ?></p>
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