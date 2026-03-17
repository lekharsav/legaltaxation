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

// Get application details with user info
$application = [];
$sql = $con->query("
    SELECT a.*, 
           s.title as service_title,
           s.id as service_id,
           cat.name as category_name,
           CASE 
               WHEN a.user_type = 'customer' THEN c.name
               WHEN a.user_type = 'partner' THEN p.name
           END as user_name,
           CASE 
               WHEN a.user_type = 'customer' THEN c.email
               WHEN a.user_type = 'partner' THEN p.email
           END as user_email,
           CASE 
               WHEN a.user_type = 'customer' THEN c.contact
               WHEN a.user_type = 'partner' THEN p.contact
           END as user_contact,
           a.user_type,
           a.client_count,
           p.business_name as partner_business
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN cate cat ON s.cate = cat.id
    LEFT JOIN customer c ON a.user_type = 'customer' AND a.cid = c.id
    LEFT JOIN partner p ON a.user_type = 'partner' AND a.partner_id = p.id
    WHERE a.id='$application_id'
");

if($row = $sql->fetch_assoc()){
    $application = $row;
    $service_id = $row['service_id'];
    $user_type = $row['user_type'];
    $total_clients = $row['client_count'] ?: 1;
} else {
    echo "<script>alert('Application not found!'); window.location='assigned_services.php';</script>";
    exit();
}

// If this is a partner with multiple clients, redirect to the partner clients page
if($user_type == 'partner' && $total_clients > 1){
    header("Location: view_partner_clients_ca.php?application_id=$application_id");
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
    WHERE r.application_id='$application_id' AND r.client_index='1'
    ORDER BY f.field_order
");

while($row = $sql->fetch_assoc()){
    $form_responses[$row['field_id']] = $row;
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
$filled_fields = count($form_responses);
$total_fields = count($form_fields);
$completion_percentage = $total_fields > 0 ? round(($filled_fields / $total_fields) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Customer Form - Legal Taxation</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .form-card {
      border-left: 4px solid #007bff;
      margin-bottom: 20px;
    }
    .user-info {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
    }
    .field-group {
      margin-bottom: 20px;
      border-bottom: 1px solid #dee2e6;
      padding-bottom: 15px;
    }
    .field-label {
      font-weight: bold;
      color: #495057;
      margin-bottom: 5px;
    }
    .field-value {
      background: #f8f9fa;
      padding: 12px;
      border-radius: 5px;
      border-left: 3px solid #28a745;
    }
    .document-item {
      padding: 10px;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      margin-bottom: 10px;
    }
    .document-item:hover {
      background: #f8f9fa;
    }
    .progress-thin {
      height: 8px;
    }
    .badge-complete {
      background-color: #28a745;
      color: white;
    }
    .badge-incomplete {
      background-color: #dc3545;
      color: white;
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
              <i class="fas fa-file-alt mr-2"></i>Customer Form Data
              <small class="text-muted">Application #<?php echo $application_id; ?></small>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="assigned_services.php">Assigned Services</a></li>
              <li class="breadcrumb-item"><a href="view_assigned_service.php?application_id=<?php echo $application_id; ?>">Service Details</a></li>
              <li class="breadcrumb-item active">Form Data</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Quick Info Row -->
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <h5>
                      <i class="fas fa-user mr-2"></i>
                      <?php echo htmlspecialchars($application['user_name']); ?>
                      <small class="text-muted">(<?php echo ucfirst($user_type); ?>)</small>
                    </h5>
                    <p class="mb-1">
                      <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($application['user_email']); ?> |
                      <i class="fas fa-phone"></i> <?php echo htmlspecialchars($application['user_contact']); ?>
                    </p>
                    <p class="mb-0">
                      <i class="fas fa-copy"></i> <?php echo htmlspecialchars($application['service_title']); ?>
                      <span class="badge badge-info ml-2"><?php echo htmlspecialchars($application['category_name']); ?></span>
                    </p>
                  </div>
                  <div class="col-md-4 text-right">
                    <div class="btn-group">
                      <a href="view_assigned_service.php?application_id=<?php echo $application_id; ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                      </a>
                      <a href="mailto:<?php echo $application['user_email']; ?>" class="btn btn-info">
                        <i class="fas fa-envelope"></i> Email
                      </a>
                      <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Form Data Column -->
          <div class="col-md-8">
            <!-- Form Completion Progress -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-bar mr-2"></i>Form Completion
                  <span class="badge badge-<?php echo $completion_percentage >= 100 ? 'success' : ($completion_percentage >= 50 ? 'warning' : 'danger'); ?> ml-2">
                    <?php echo $completion_percentage; ?>%
                  </span>
                </h3>
              </div>
              <div class="card-body">
                <div class="progress progress-thin">
                  <div class="progress-bar bg-<?php echo $completion_percentage >= 100 ? 'success' : ($completion_percentage >= 50 ? 'warning' : 'danger'); ?>" 
                       style="width: <?php echo $completion_percentage; ?>%"></div>
                </div>
                <p class="text-muted mt-2">
                  <?php echo $filled_fields; ?> out of <?php echo $total_fields; ?> fields filled
                </p>
              </div>
            </div>
            
            <!-- Form Fields -->
            <?php if(empty($form_fields)): ?>
              <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No form fields configured for this service.
              </div>
            <?php elseif(empty($form_responses)): ?>
              <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> No form data has been submitted yet.
              </div>
            <?php else: ?>
              <div class="card form-card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-list-alt mr-2"></i>Submitted Form Data
                  </h3>
                </div>
                <div class="card-body">
                  <?php foreach($form_fields as $field): 
                    $response = $form_responses[$field['id']] ?? null;
                    $has_response = !empty($response);
                  ?>
                    <div class="field-group">
                      <div class="field-label">
                        <?php echo htmlspecialchars($field['field_label']); ?>
                        <?php if($field['is_required']): ?>
                          <span class="badge badge-danger ml-2">Required</span>
                        <?php endif; ?>
                        <small class="text-muted ml-2">(<?php echo $field['field_name']; ?>)</small>
                      </div>
                      
                      <?php if($has_response): ?>
                        <div class="field-value">
                          <?php if($field['field_type'] == 'file'): ?>
                            <a href="../uploads/service_docs/<?php echo $response['field_value']; ?>" target="_blank" class="btn btn-sm btn-info">
                              <i class="fas fa-download"></i> Download File
                            </a>
                            <small class="text-muted d-block mt-1"><?php echo basename($response['field_value']); ?></small>
                            
                          <?php elseif($field['field_type'] == 'checkbox'): 
                            $values = explode(',', $response['field_value']);
                            foreach($values as $val): ?>
                              <span class="badge badge-primary mr-1"><?php echo htmlspecialchars(trim($val)); ?></span>
                            <?php endforeach; ?>
                            
                          <?php elseif($field['field_type'] == 'select' || $field['field_type'] == 'radio'): ?>
                            <span class="badge badge-info"><?php echo htmlspecialchars($response['field_value']); ?></span>
                            
                          <?php elseif($field['field_type'] == 'textarea'): ?>
                            <div class="bg-white p-2 border rounded">
                              <?php echo nl2br(htmlspecialchars($response['field_value'])); ?>
                            </div>
                            
                          <?php else: ?>
                            <?php echo htmlspecialchars($response['field_value']); ?>
                          <?php endif; ?>
                          
                          <?php if($field['help_text']): ?>
                            <small class="text-info d-block mt-1">
                              <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($field['help_text']); ?>
                            </small>
                          <?php endif; ?>
                        </div>
                      <?php else: ?>
                        <div class="text-muted font-italic p-2 bg-light rounded">
                          Not provided
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="card-footer">
                  <small class="text-muted">
                    <i class="fas fa-clock"></i> Last updated: 
                    <?php 
                    if(!empty($form_responses)){
                        $last_response = end($form_responses);
                        echo date('d M Y, h:i A', strtotime($last_response['created_at']));
                    } else {
                        echo 'Never';
                    }
                    ?>
                  </small>
                </div>
              </div>
            <?php endif; ?>
          </div>
          
          <!-- Sidebar Column -->
          <div class="col-md-4">
            <!-- Documents Card -->
            <div class="card">
              <div class="card-header bg-success">
                <h3 class="card-title">
                  <i class="fas fa-file-upload mr-2"></i>Uploaded Documents
                  <span class="badge badge-light ml-2"><?php echo count($documents); ?></span>
                </h3>
              </div>
              <div class="card-body">
                <?php if(empty($documents)): ?>
                  <p class="text-muted text-center py-3">
                    <i class="fas fa-folder-open fa-3x mb-2"></i><br>
                    No documents uploaded
                  </p>
                <?php else: ?>
                  <?php foreach($documents as $doc): ?>
                  <div class="document-item">
                    <a href="../uploads/<?php echo $doc['file']; ?>" target="_blank" class="d-block">
                      <?php if(preg_match('/\.pdf$/i', $doc['file'])): ?>
                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                      <?php elseif(preg_match('/\.(jpg|jpeg|png|gif)$/i', $doc['file'])): ?>
                        <i class="fas fa-file-image text-primary mr-2"></i>
                      <?php elseif(preg_match('/\.(doc|docx)$/i', $doc['file'])): ?>
                        <i class="fas fa-file-word text-info mr-2"></i>
                      <?php else: ?>
                        <i class="fas fa-file text-secondary mr-2"></i>
                      <?php endif; ?>
                      <strong><?php echo htmlspecialchars($doc['doc_name'] ?? 'Document'); ?></strong>
                    </a>
                    <small class="text-muted d-block mt-1">
                      <i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($doc['created'])); ?>
                    </small>
                  </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
            
            <!-- Quick Stats Card -->
            <div class="card mt-3">
              <div class="card-header bg-info">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Quick Stats</h3>
              </div>
              <div class="card-body">
                <div class="info-box bg-light">
                  <span class="info-box-icon bg-primary"><i class="fas fa-file-alt"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Form Fields</span>
                    <span class="info-box-number"><?php echo $total_fields; ?></span>
                  </div>
                </div>
                
                <div class="info-box bg-light">
                  <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Filled Fields</span>
                    <span class="info-box-number"><?php echo $filled_fields; ?></span>
                  </div>
                </div>
                
                <div class="info-box bg-light">
                  <span class="info-box-icon bg-warning"><i class="fas fa-file-upload"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Documents</span>
                    <span class="info-box-number"><?php echo count($documents); ?></span>
                  </div>
                </div>
                
                <div class="info-box bg-light">
                  <span class="info-box-icon bg-secondary"><i class="fas fa-clock"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Applied On</span>
                    <span class="info-box-number"><?php echo date('d M Y', strtotime($application['created'])); ?></span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Actions Card -->
            <div class="card mt-3">
              <div class="card-header bg-secondary">
                <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>Actions</h3>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <a href="view_assigned_service.php?application_id=<?php echo $application_id; ?>" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-info-circle"></i> View Full Details
                  </a>
                  <button type="button" class="btn btn-outline-success btn-block" onclick="updateStatus(<?php echo $application_id; ?>, <?php echo $application['status']; ?>)">
                    <i class="fas fa-<?php echo $application['status'] == '0' ? 'check' : 'undo'; ?>"></i>
                    <?php echo $application['status'] == '0' ? 'Mark Completed' : 'Mark Pending'; ?>
                  </button>
                  <a href="mailto:<?php echo $application['user_email']; ?>" class="btn btn-outline-info btn-block">
                    <i class="fas fa-envelope"></i> Send Email
                  </a>
                </div>
              </div>
            </div>
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