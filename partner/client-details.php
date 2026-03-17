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
  <title>Client Details - Partner Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .client-header {
        background: linear-gradient(45deg, #3498db, #2ecc71);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .nav-pills .nav-link.active {
        background-color: #3498db;
        color: white;
    }
    .response-card {
        border-left: 4px solid #3498db;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .response-card:hover {
        transform: translateX(5px);
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    .field-required:after {
        content: " *";
        color: #e74c3c;
    }
    .client-nav-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
    }
    .client-nav-btn:hover {
        transform: scale(1.1);
        text-decoration: none;
        color: white;
    }
    .client-nav-btn.active {
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3);
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
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Client Details</h1>
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
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Client Header -->
        <div class="client-header">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h2>
                <i class="fas fa-user mr-2"></i>Client <?php echo $client_index; ?>
                <small class="ml-2">of <?php echo $application['client_count']; ?></small>
              </h2>
              <p class="mb-1">
                <i class="fas fa-shopping-cart mr-1"></i> 
                Service: <?php echo htmlspecialchars($application['service_name']); ?>
              </p>
              <p class="mb-0">
                <i class="fas fa-tag mr-1"></i> 
                Category: <?php echo htmlspecialchars($application['category_name']); ?> | 
                Application ID: #<?php echo str_pad($application_id, 6, '0', STR_PAD_LEFT); ?>
              </p>
            </div>
            <div class="col-md-4 text-right">
              <a href="service-details.php?id=<?php echo $application_id; ?>" 
                 class="btn btn-light">
                <i class="fas fa-arrow-left mr-1"></i> Back to Service
              </a>
              <button class="btn btn-outline-light ml-2" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> Print
              </button>
            </div>
          </div>
        </div>

        <!-- Client Navigation -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-users mr-2"></i>Navigate Clients</h5>
              </div>
              <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                  <?php for($i = 1; $i <= $application['client_count']; $i++): 
                    $is_active = ($i == $client_index);
                    $has_data = in_array($i, $all_clients);
                    $bg_color = $is_active ? '#e74c3c' : ($has_data ? '#2ecc71' : '#3498db');
                  ?>
                  <a href="client-details.php?app_id=<?php echo $application_id; ?>&client=<?php echo $i; ?>" 
                     class="client-nav-btn <?php echo $is_active ? 'active' : ''; ?>" 
                     style="background-color: <?php echo $bg_color; ?>"
                     title="Client <?php echo $i; ?><?php echo $has_data ? ' (Data submitted)' : ''; ?>">
                    <?php echo $i; ?>
                    <?php if($has_data): ?>
                      <i class="fas fa-check ml-1" style="font-size: 10px;"></i>
                    <?php endif; ?>
                  </a>
                  <?php endfor; ?>
                </div>
                <div class="mt-3 d-flex justify-content-between">
                  <?php if($client_index > 1): ?>
                  <a href="client-details.php?app_id=<?php echo $application_id; ?>&client=<?php echo $client_index - 1; ?>" 
                     class="btn btn-outline-primary">
                    <i class="fas fa-chevron-left mr-1"></i> Previous Client
                  </a>
                  <?php else: ?>
                  <span></span>
                  <?php endif; ?>
                  
                  <?php if($client_index < $application['client_count']): ?>
                  <a href="client-details.php?app_id=<?php echo $application_id; ?>&client=<?php echo $client_index + 1; ?>" 
                     class="btn btn-outline-primary">
                    Next Client <i class="fas fa-chevron-right ml-1"></i>
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Client Information -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <ul class="nav nav-pills card-header-pills">
                  <li class="nav-item">
                    <a class="nav-link active" href="#formData" data-toggle="tab">
                      <i class="fas fa-file-alt mr-1"></i> Form Data
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#documents" data-toggle="tab">
                      <i class="fas fa-file-upload mr-1"></i> Documents
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#timeline" data-toggle="tab">
                      <i class="fas fa-history mr-1"></i> Timeline
                    </a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  
                  <!-- Form Data Tab -->
                  <div class="tab-pane active" id="formData">
                    <?php if($responses_query->num_rows > 0): ?>
                      <div class="row">
                        <?php while($response = $responses_query->fetch_assoc()): ?>
                        <div class="col-md-6 mb-3">
                          <div class="response-card">
                            <div class="card-body">
                              <h6 class="card-title <?php echo $response['is_required'] ? 'field-required' : ''; ?>">
                                <?php echo htmlspecialchars($response['field_label']); ?>
                                <?php if($response['is_required']): ?>
                                  <small class="text-danger">(Required)</small>
                                <?php endif; ?>
                              </h6>
                              <div class="mt-2">
                                <?php if($response['field_type'] == 'file' && !empty($response['field_value'])): ?>
                                  <a href="../uploads/<?php echo $response['field_value']; ?>" 
                                     target="_blank" 
                                     class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download mr-1"></i> Download File
                                  </a>
                                <?php else: ?>
                                  <p class="mb-0">
                                    <?php echo nl2br(htmlspecialchars($response['field_value'])); ?>
                                  </p>
                                <?php endif; ?>
                              </div>
                              <?php if($response['field_placeholder']): ?>
                              <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                <?php echo htmlspecialchars($response['field_placeholder']); ?>
                              </small>
                              <?php endif; ?>
                            </div>
                          </div>
                        </div>
                        <?php endwhile; ?>
                      </div>
                      
                      <!-- Completion Status -->
                      <div class="alert alert-success mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Form Completed</strong>
                            <p class="mb-0 mt-1">All required fields have been filled for this client.</p>
                          </div>
                          <div>
                            <small class="text-muted">
                              Last updated: 
                              <?php 
                              $last_response = $responses_query->num_rows > 0 ? 
                                date('d M Y, h:i A', strtotime($response['created_at'])) : 'N/A'; 
                              echo $last_response;
                              ?>
                            </small>
                          </div>
                        </div>
                      </div>
                    <?php else: ?>
                      <div class="text-center py-5">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <h4>No Data Submitted</h4>
                        <p class="text-muted">No form data has been submitted for this client yet.</p>
                        <div class="mt-3">
                          <button class="btn btn-primary" data-toggle="modal" data-target="#addDataModal">
                            <i class="fas fa-plus mr-1"></i> Add Client Data
                          </button>
                          <a href="service-details.php?id=<?php echo $application_id; ?>" 
                             class="btn btn-outline-secondary ml-2">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Service
                          </a>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                  
                  <!-- Documents Tab -->
                  <div class="tab-pane" id="documents">
                    <div class="text-center py-5">
                      <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                      <h4>No Documents Uploaded</h4>
                      <p class="text-muted">No additional documents have been uploaded for this client.</p>
                      <button class="btn btn-primary" data-toggle="modal" data-target="#uploadDocumentModal">
                        <i class="fas fa-upload mr-1"></i> Upload Document
                      </button>
                    </div>
                  </div>
                  
                  <!-- Timeline Tab -->
                  <div class="tab-pane" id="timeline">
                    <div class="timeline">
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
                        while($event = $timeline_query->fetch_assoc()):
                      ?>
                      <div class="time-label">
                        <span class="bg-info">
                          <?php echo date('d M', strtotime($event['created_at'])); ?>
                        </span>
                      </div>
                      <div>
                        <i class="fas fa-user bg-blue"></i>
                        <div class="timeline-item">
                          <span class="time">
                            <i class="fas fa-clock"></i> 
                            <?php echo date('h:i A', strtotime($event['created_at'])); ?>
                          </span>
                          <h3 class="timeline-header">
                            <?php echo htmlspecialchars($event['action']); ?>
                          </h3>
                          <div class="timeline-body">
                            <?php echo htmlspecialchars($event['description']); ?>
                          </div>
                        </div>
                      </div>
                      <?php endwhile; else: ?>
                      <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h4>No Timeline Events</h4>
                        <p class="text-muted">No activity recorded for this client yet.</p>
                      </div>
                      <?php endif; ?>
                      
                      <div>
                        <i class="fas fa-clock bg-gray"></i>
                      </div>
                    </div>
                  </div>
                  
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

<!-- Add Data Modal -->
<div class="modal fade" id="addDataModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-plus mr-2"></i>Add Data for Client <?php echo $client_index; ?>
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="clientDataForm">
          <?php
          // Get all form fields for this service
          $fields_query_all = $con->query("
            SELECT * FROM service_form_fields 
            WHERE service_id = '{$application['sid']}'
            ORDER BY field_order
          ");
          
          if($fields_query_all->num_rows > 0):
            while($field = $fields_query_all->fetch_assoc()):
              $field_value = '';
              // Check if this field already has a value
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveClientData">Save Data</button>
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
          <i class="fas fa-upload mr-2"></i>Upload Document
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="documentUploadForm" enctype="multipart/form-data">
          <div class="form-group">
            <label for="documentTitle">Document Title</label>
            <input type="text" class="form-control" id="documentTitle" required>
          </div>
          <div class="form-group">
            <label for="documentType">Document Type</label>
            <select class="form-control" id="documentType" required>
              <option value="">Select type</option>
              <option value="identification">Identification</option>
              <option value="address_proof">Address Proof</option>
              <option value="income_proof">Income Proof</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="documentFile">Choose File</label>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="uploadDocumentBtn">Upload</button>
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
    
    // Collect all form fields
    $('#clientDataForm input, #clientDataForm select, #clientDataForm textarea').each(function() {
      var fieldId = $(this).attr('id').replace('field_', '');
      var fieldValue = $(this).val();
      formData.append('fields[' + fieldId + ']', fieldValue);
    });
    
    // In a real application, you would send this via AJAX
    console.log('Saving client data:', {
      application_id: <?php echo $application_id; ?>,
      client_index: <?php echo $client_index; ?>,
      fields: Object.fromEntries(formData)
    });
    
    $('#addDataModal').modal('hide');
    alert('Client data saved successfully!');
    
    // Reload page to show updated data
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
    
    // In a real application, you would send this via AJAX
    console.log('Uploading document:', {
      title: title,
      type: type,
      notes: notes,
      file: fileInput.files[0].name
    });
    
    $('#uploadDocumentModal').modal('hide');
    alert('Document uploaded successfully!');
    
    // Reset form
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