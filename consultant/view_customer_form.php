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
  <title>Customer Form Data - CA Dashboard | Legal Taxation</title>

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

    /* Info Bar */
    .info-bar {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1.5rem;
      margin-bottom: 2rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }

    .user-details h5 {
      font-weight: 600;
      font-size: 1.1rem;
      color: #0f172a;
      margin-bottom: 0.25rem;
    }

    .user-details p {
      color: #475569;
      margin-bottom: 0.25rem;
      font-size: 0.9rem;
    }

    .user-details i {
      color: #3b82f6;
      width: 18px;
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

    .btn-modern-info {
      background: #e0f2fe;
      border-color: #bae6fd;
      color: #0369a1;
    }
    .btn-modern-info:hover {
      background: #bae6fd;
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

    .detail-card-footer {
      padding: 1rem 1.75rem;
      border-top: 1px solid #f1f5f9;
      background: #f8fafc;
      font-size: 0.85rem;
      color: #64748b;
    }

    /* Progress */
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

    /* Form fields */
    .field-group {
      margin-bottom: 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 1.5rem;
    }
    .field-group:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
    .field-label {
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .field-label .badge {
      font-size: 0.7rem;
    }
    .field-value {
      background: #f8fafc;
      border-radius: 1rem;
      padding: 1rem;
      border-left: 3px solid #3b82f6;
    }

    /* Documents */
    .document-item {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      padding: 1rem;
      margin-bottom: 0.75rem;
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
    .fa-file-word { color: #0284c7; }
    .fa-file { color: #64748b; }

    /* Info boxes */
    .info-box-modern {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      padding: 1rem;
      margin-bottom: 0.75rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .info-box-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
    }
    .info-box-content {
      flex: 1;
    }
    .info-box-label {
      font-size: 0.8rem;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .info-box-number {
      font-size: 1.25rem;
      font-weight: 600;
      color: #0f172a;
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
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #e0f2fe; color: #0284c7; }

    /* Responsive */
    @media (max-width: 768px) {
      .info-bar {
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
                Customer Form Data
                <span class="text-muted" style="font-size: 1rem; margin-left: 0.5rem;">#<?php echo $application_id; ?></span>
              </h1>
            </div>
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
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Info Bar -->
        <div class="info-bar">
          <div class="user-details">
            <h5>
              <i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($application['user_name']); ?>
              <span class="badge-modern <?php echo $user_type == 'customer' ? 'badge-success' : 'badge-info'; ?>">
                <?php echo ucfirst($user_type); ?>
              </span>
            </h5>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($application['user_email']); ?></p>
            <p><i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($application['user_contact']); ?></p>
            <p><i class="fas fa-copy"></i> <?php echo htmlspecialchars($application['service_title']); ?> 
              <span class="badge badge-info"><?php echo htmlspecialchars($application['category_name']); ?></span>
            </p>
          </div>
          <div class="btn-group-modern">
            <a href="view_assigned_service.php?application_id=<?php echo $application_id; ?>" class="btn-modern">
              <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="mailto:<?php echo $application['user_email']; ?>" class="btn-modern btn-modern-info">
              <i class="fas fa-envelope"></i> Email
            </a>
            <button type="button" class="btn-modern" onclick="window.print()">
              <i class="fas fa-print"></i> Print
            </button>
          </div>
        </div>

        <div class="row">
          <!-- Left Column - Form Data -->
          <div class="col-lg-8">
            <!-- Progress Card -->
            <div class="detail-card">
              <div class="detail-card-header">
                <h3><i class="fas fa-chart-bar"></i> Form Completion</h3>
                <span class="badge-modern <?php echo $completion_percentage >= 100 ? 'badge-success' : ($completion_percentage >= 50 ? 'badge-warning' : 'badge-danger'); ?>">
                  <?php echo $completion_percentage; ?>%
                </span>
              </div>
              <div class="detail-card-body">
                <div class="progress-modern">
                  <div class="progress-bar-modern bg-<?php echo $completion_percentage >= 100 ? 'success' : ($completion_percentage >= 50 ? 'warning' : 'danger'); ?>" 
                       style="width: <?php echo $completion_percentage; ?>%;"></div>
                </div>
                <p class="text-muted mt-2"><?php echo $filled_fields; ?> out of <?php echo $total_fields; ?> fields filled</p>
              </div>
            </div>

            <!-- Form Fields Card -->
            <?php if(empty($form_fields)): ?>
              <div class="alert alert-info">No form fields configured for this service.</div>
            <?php elseif(empty($form_responses)): ?>
              <div class="alert alert-warning">No form data has been submitted yet.</div>
            <?php else: ?>
              <div class="detail-card">
                <div class="detail-card-header">
                  <h3><i class="fas fa-list-alt"></i> Submitted Form Data</h3>
                </div>
                <div class="detail-card-body">
                  <?php foreach($form_fields as $field): 
                    $response = $form_responses[$field['id']] ?? null;
                    $has_response = !empty($response);
                  ?>
                    <div class="field-group">
                      <div class="field-label">
                        <?php echo htmlspecialchars($field['field_label']); ?>
                        <?php if($field['is_required']): ?>
                          <span class="badge badge-danger">Required</span>
                        <?php endif; ?>
                        <small class="text-muted">(<?php echo $field['field_name']; ?>)</small>
                      </div>
                      
                      <?php if($has_response): ?>
                        <div class="field-value">
                          <?php if($field['field_type'] == 'file'): ?>
                            <a href="../uploads/service_docs/<?php echo $response['field_value']; ?>" target="_blank" class="btn-modern btn-modern-info btn-sm">
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
                        <div class="text-muted font-italic p-3 bg-light rounded">
                          Not provided
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="detail-card-footer">
                  <i class="fas fa-clock"></i> Last updated: 
                  <?php 
                  if(!empty($form_responses)){
                      $last_response = end($form_responses);
                      echo date('d M Y, h:i A', strtotime($last_response['created_at']));
                  } else {
                      echo 'Never';
                  }
                  ?>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <!-- Right Column - Sidebar -->
          <div class="col-lg-4">
            <!-- Documents Card -->
            <div class="detail-card">
              <div class="detail-card-header" style="background: #d1fae5;">
                <h3><i class="fas fa-file-upload" style="color: #065f46;"></i> Uploaded Documents</h3>
                <span class="badge-modern badge-success"><?php echo count($documents); ?></span>
              </div>
              <div class="detail-card-body">
                <?php if(empty($documents)): ?>
                  <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
                    <p class="text-muted">No documents uploaded</p>
                  </div>
                <?php else: ?>
                  <?php foreach($documents as $doc): ?>
                  <div class="document-item">
                    <a href="../uploads/<?php echo $doc['file']; ?>" target="_blank">
                      <?php if(preg_match('/\.pdf$/i', $doc['file'])): ?>
                        <i class="fas fa-file-pdf fa-2x"></i>
                      <?php elseif(preg_match('/\.(jpg|jpeg|png|gif)$/i', $doc['file'])): ?>
                        <i class="fas fa-file-image fa-2x"></i>
                      <?php elseif(preg_match('/\.(doc|docx)$/i', $doc['file'])): ?>
                        <i class="fas fa-file-word fa-2x"></i>
                      <?php else: ?>
                        <i class="fas fa-file fa-2x"></i>
                      <?php endif; ?>
                      <div>
                        <strong><?php echo htmlspecialchars($doc['doc_name'] ?? 'Document'); ?></strong>
                        <br>
                        <small class="text-muted"><i class="fas fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($doc['created'])); ?></small>
                      </div>
                    </a>
                  </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="detail-card mt-3">
              <div class="detail-card-header" style="background: #e0f2fe;">
                <h3><i class="fas fa-chart-pie" style="color: #0284c7;"></i> Quick Stats</h3>
              </div>
              <div class="detail-card-body">
                <div class="info-box-modern">
                  <div class="info-box-icon" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-file-alt"></i>
                  </div>
                  <div class="info-box-content">
                    <div class="info-box-label">Form Fields</div>
                    <div class="info-box-number"><?php echo $total_fields; ?></div>
                  </div>
                </div>
                <div class="info-box-modern">
                  <div class="info-box-icon" style="background: #d1fae5; color: #065f46;">
                    <i class="fas fa-check-circle"></i>
                  </div>
                  <div class="info-box-content">
                    <div class="info-box-label">Filled Fields</div>
                    <div class="info-box-number"><?php echo $filled_fields; ?></div>
                  </div>
                </div>
                <div class="info-box-modern">
                  <div class="info-box-icon" style="background: #fef9c3; color: #a16207;">
                    <i class="fas fa-file-upload"></i>
                  </div>
                  <div class="info-box-content">
                    <div class="info-box-label">Documents</div>
                    <div class="info-box-number"><?php echo count($documents); ?></div>
                  </div>
                </div>
                <div class="info-box-modern">
                  <div class="info-box-icon" style="background: #f1f5f9; color: #334155;">
                    <i class="fas fa-clock"></i>
                  </div>
                  <div class="info-box-content">
                    <div class="info-box-label">Applied On</div>
                    <div class="info-box-number"><?php echo date('d M Y', strtotime($application['created'])); ?></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Actions Card -->
            <div class="detail-card mt-3">
              <div class="detail-card-header" style="background: #f1f5f9;">
                <h3><i class="fas fa-bolt" style="color: #334155;"></i> Actions</h3>
              </div>
              <div class="detail-card-body">
                <div class="d-grid gap-2">
                  <a href="view_assigned_service.php?application_id=<?php echo $application_id; ?>" class="btn-modern btn-block">
                    <i class="fas fa-info-circle"></i> View Full Details
                  </a>
                  <button type="button" class="btn-modern btn-modern-primary btn-block" onclick="updateStatus(<?php echo $application_id; ?>, <?php echo $application['status']; ?>)">
                    <i class="fas fa-<?php echo $application['status'] == '0' ? 'check' : 'undo'; ?>"></i>
                    <?php echo $application['status'] == '0' ? 'Mark Completed' : 'Mark Pending'; ?>
                  </button>
                  <a href="mailto:<?php echo $application['user_email']; ?>" class="btn-modern btn-modern-info btn-block">
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