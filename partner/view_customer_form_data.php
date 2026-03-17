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
$application_id = $_GET['application_id'] ?? 0;

// If no application ID, redirect to leads page
if(!$application_id){
    echo "<script>window.location='leads.php';</script>";
    exit();
}

// Get application details
$application = [];
$sql = $con->query("SELECT a.*, s.title as service_title, s.id as service_id, 
                           c.name as customer_name, c.email as customer_email, 
                           c.contact as customer_contact 
                    FROM apply a 
                    LEFT JOIN service s ON a.sid = s.id 
                    LEFT JOIN customer c ON a.cid = c.id 
                    WHERE a.id='$application_id'");
if($row = $sql->fetch_assoc()){
    $application = $row;
    $service_id = $row['service_id'];
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

// Get form responses for this application
$form_responses = [];
$sql = $con->query("SELECT r.*, f.field_name, f.field_label, f.field_type 
                    FROM service_form_responses r 
                    LEFT JOIN service_form_fields f ON r.field_id = f.id 
                    WHERE r.application_id='$application_id'");
while($row = $sql->fetch_assoc()){
    $form_responses[$row['field_id']] = $row;
}

// Get uploaded documents for this application
$documents = [];
$sql = $con->query("SELECT r.*, d.name as doc_name 
                    FROM req_doc r 
                    LEFT JOIN doc d ON r.type = d.id 
                    WHERE r.sid='$application_id' AND r.cid='".$application['cid']."' 
                    ORDER BY r.id DESC");
while($row = $sql->fetch_assoc()){
    $documents[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Form Data - Legal Taxation</title>
  
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
              </span>&nbsp;Customer Form Data
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="leads.php">Leads</a></li>
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
                        <td><span class="badge badge-primary">#<?php echo $application['id']; ?></span></td>
                      </tr>
                      <tr>
                        <th>Applied Date:</th>
                        <td><?php echo date('d M Y', strtotime($application['created'])); ?></td>
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
                      <?php if($application['send_to']): ?>
                      <tr>
                        <th>Assigned To:</th>
                        <td>
                          <?php 
                          $ca_sql = $con->query("SELECT name FROM ca WHERE id='".$application['send_to']."'");
                          if($ca_row = $ca_sql->fetch_assoc()){
                              echo $ca_row['name'];
                          } else {
                              echo 'Not Assigned';
                          }
                          ?>
                        </td>
                      </tr>
                      <?php endif; ?>
                    </table>
                  </div>
                  
                  <div class="col-md-4">
                    <h5>Customer Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Customer Name:</th>
                        <td><?php echo htmlspecialchars($application['customer_name']); ?></td>
                      </tr>
                      <tr>
                        <th>Customer ID:</th>
                        <td><span class="badge badge-info">#<?php echo $application['cid']; ?></span></td>
                      </tr>
                      <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($application['customer_email']); ?></td>
                      </tr>
                      <tr>
                        <th>Contact:</th>
                        <td><?php echo htmlspecialchars($application['customer_contact']); ?></td>
                      </tr>
                    </table>
                  </div>
                  
                  <div class="col-md-4">
                    <h5>Service Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Service:</th>
                        <td><?php echo htmlspecialchars($application['service_title']); ?></td>
                      </tr>
                      <tr>
                        <th>Service ID:</th>
                        <td><span class="badge badge-secondary">#<?php echo $service['id']; ?></span></td>
                      </tr>
                      <tr>
                        <th>Service Price:</th>
                        <td>₹<?php echo $service['o_price']; ?></td>
                      </tr>
                      <tr>
                        <th>Status:</th>
                        <td>
                          <?php if($service['status'] == '1'): ?>
                            <span class="badge badge-success">Active</span>
                          <?php else: ?>
                            <span class="badge badge-danger">Inactive</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a href="leads.php?sid=<?php echo $service_id; ?>" class="btn btn-secondary">
                  <i class="fa fa-arrow-left"></i> Back to Leads
                </a>
                <?php if($application['send_to']): ?>
                <a href="#" class="btn btn-info" data-toggle="modal" data-target="#assignModal">
                  <i class="fa fa-user-edit"></i> Re-assign to CA
                </a>
                <?php else: ?>
                <a href="#" class="btn btn-success" data-toggle="modal" data-target="#assignModal">
                  <i class="fa fa-user-plus"></i> Assign to CA
                </a>
                <?php endif; ?>
                <a href="edit_application.php?id=<?php echo $application_id; ?>" class="btn btn-warning">
                  <i class="fa fa-edit"></i> Edit Application
                </a>
                <button type="button" class="btn btn-primary" onclick="printFormData()">
                  <i class="fa fa-print"></i> Print
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Customer Form Data -->
          <div class="col-md-8">
            <div class="card">
              <div class="card-header bg-info">
                <h3 class="card-title">
                  <i class="fa fa-list-alt"></i> Customer Form Data
                  <span class="badge badge-light ml-2"><?php echo count($form_responses); ?> fields filled</span>
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
                <?php elseif(empty($form_responses)): ?>
                  <div class="empty-state">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4>No Data Submitted</h4>
                    <p class="text-muted">The customer hasn't filled the form yet.</p>
                    <div class="alert alert-warning">
                      <i class="fa fa-exclamation-triangle"></i> 
                      Customer needs to fill the form from their dashboard.
                    </div>
                  </div>
                <?php else: ?>
                  <div class="table-responsive" id="formDataTable">
                    <table class="table table-bordered">
                      <thead>
                        <tr class="bg-light">
                          <th width="5%">#</th>
                          <th width="30%">Field Name</th>
                          <th width="25%">Field Type</th>
                          <th width="40%">Customer Response</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach($form_fields as $field): 
                          $response = $form_responses[$field['id']] ?? null;
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
                                  <small class="text-muted"><?php echo $field_value; ?></small>
                                </div>
                              <?php elseif($field['field_type'] == 'checkbox' && $field_value != 'Not Provided'): 
                                $values = explode(',', $field_value);
                                foreach($values as $val):
                              ?>
                                <span class="badge badge-primary mb-1"><?php echo htmlspecialchars($val); ?></span><br>
                              <?php endforeach;
                              else: ?>
                                <div class="field-value">
                                  <?php echo nl2br(htmlspecialchars($field_value)); ?>
                                </div>
                                <?php if($field['help_text']): ?>
                                  <small class="text-info">
                                    <i class="fa fa-info-circle"></i> <?php echo htmlspecialchars($field['help_text']); ?>
                                  </small>
                                <?php endif; ?>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <small class="text-muted">
                  <i class="fa fa-clock"></i> Last Updated: 
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
          </div>

          <!-- Additional Information Sidebar -->
          <div class="col-md-4">
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
                          <i class="fa fa-file-pdf text-danger mr-2"></i>
                          <?php echo htmlspecialchars($doc['doc_name'] ?? 'Document'); ?>
                        </div>
                        <span class="badge badge-primary">
                          <i class="fa fa-download"></i>
                        </span>
                      </a>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <small class="text-muted">
                  These are required documents for the service
                </small>
              </div>
            </div>

            <!-- Form Statistics -->
            <div class="card mt-3">
              <div class="card-header bg-warning">
                <h3 class="card-title">
                  <i class="fa fa-chart-bar"></i> Form Statistics
                </h3>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-6">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-primary">
                        <i class="fa fa-field"></i>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">Total Fields</span>
                        <span class="info-box-number"><?php echo count($form_fields); ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-success">
                        <i class="fa fa-check-circle"></i>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">Filled</span>
                        <span class="info-box-number"><?php echo count($form_responses); ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="progress-group mt-3">
                  Completion Rate
                  <span class="float-right">
                    <b><?php echo count($form_responses); ?></b>/<?php echo count($form_fields); ?>
                  </span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-success" 
                         style="width: <?php 
                           echo count($form_fields) > 0 ? 
                           (count($form_responses)/count($form_fields)*100) : 0; 
                         ?>%">
                    </div>
                  </div>
                </div>
                
                <div class="mt-3">
                  <h6>Required Fields Status:</h6>
                  <?php
                  $required_fields = array_filter($form_fields, function($field) {
                      return $field['is_required'] == 1;
                  });
                  $required_filled = 0;
                  foreach($required_fields as $field){
                      if(isset($form_responses[$field['id']])){
                          $required_filled++;
                      }
                  }
                  ?>
                  <div class="progress-group">
                    <div class="progress progress-sm">
                      <div class="progress-bar bg-danger" 
                           style="width: <?php 
                             echo count($required_fields) > 0 ? 
                             ($required_filled/count($required_fields)*100) : 0; 
                           ?>%">
                      </div>
                    </div>
                    <small class="text-muted">
                      <?php echo $required_filled; ?> of <?php echo count($required_fields); ?> required fields filled
                    </small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
              <div class="card-header bg-secondary">
                <h3 class="card-title">
                  <i class="fa fa-bolt"></i> Quick Actions
                </h3>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <a href="mailto:<?php echo $application['customer_email']; ?>" 
                     class="btn btn-outline-primary btn-block">
                    <i class="fa fa-envelope"></i> Email Customer
                  </a>
                  <a href="tel:<?php echo $application['customer_contact']; ?>" 
                     class="btn btn-outline-success btn-block">
                    <i class="fa fa-phone"></i> Call Customer
                  </a>
                  <button type="button" class="btn btn-outline-info btn-block" onclick="copyApplicationDetails()">
                    <i class="fa fa-copy"></i> Copy Details
                  </button>
                  <a href="send_reminder.php?application_id=<?php echo $application_id; ?>" 
                     class="btn btn-outline-warning btn-block">
                    <i class="fa fa-bell"></i> Send Reminder
                  </a>
                </div>
              </div>
            </div>
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
                <form method="POST" action="save_notes.php">
                  <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
                  <div class="form-group">
                    <textarea class="form-control" name="admin_notes" rows="3" 
                              placeholder="Add notes about this application..."></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save Notes
                  </button>
                </form>
                
                <!-- Previous Notes (if any) -->
                <?php
                $notes_sql = $con->query("SELECT * FROM application_notes WHERE application_id='$application_id' ORDER BY created_at DESC");
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
                      <a href="#">Admin</a> added a note
                    </h3>
                    <div class="timeline-body">
                      <?php echo nl2br(htmlspecialchars($note['notes'])); ?>
                    </div>
                  </div>
                  <?php endwhile; ?>
                </div>
                <?php endif; ?>
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
                      placeholder="Add notes for the CA..."></textarea>
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
    var printContent = document.getElementById('formDataTable').innerHTML;
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Customer Form Data - Application #<?php echo $application_id; ?></title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; }
                th { background-color: #f2f2f2; }
                .print-header { text-align: center; margin-bottom: 20px; }
                .print-info { margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>Customer Form Data</h2>
                <p>Application #<?php echo $application_id; ?> | <?php echo date('d M Y'); ?></p>
            </div>
            
            <div class="print-info">
                <p><strong>Customer:</strong> <?php echo htmlspecialchars($application['customer_name']); ?></p>
                <p><strong>Service:</strong> <?php echo htmlspecialchars($application['service_title']); ?></p>
                <p><strong>Application Date:</strong> <?php echo date('d M Y', strtotime($application['created'])); ?></p>
            </div>
            
            ${printContent}
            
            <div style="margin-top: 30px; text-align: center; font-size: 12px;">
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
    var details = `
Application ID: #<?php echo $application_id; ?>
Customer: <?php echo htmlspecialchars($application['customer_name']); ?>
Service: <?php echo htmlspecialchars($application['service_title']); ?>
Email: <?php echo htmlspecialchars($application['customer_email']); ?>
Contact: <?php echo htmlspecialchars($application['customer_contact']); ?>
Applied Date: <?php echo date('d M Y', strtotime($application['created'])); ?>

Form Data:
<?php foreach($form_fields as $field): 
    $response = $form_responses[$field['id']] ?? null;
    $field_value = $response ? $response['field_value'] : 'Not Provided';
?>
- <?php echo $field['field_label']; ?>: <?php echo $field_value; ?>
<?php endforeach; ?>
    `;
    
    navigator.clipboard.writeText(details).then(function() {
        alert('Application details copied to clipboard!');
    });
}

// Auto-expand textareas
$(document).ready(function() {
    $('textarea').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
</body>
</html>