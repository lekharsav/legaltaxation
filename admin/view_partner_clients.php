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

// If no application ID, redirect
if(!$application_id){
    echo "<script>alert('Application ID required!'); window.location='leads.php';</script>";
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
           p.name as partner_name,
           p.email as partner_email,
           p.contact as partner_contact,
           p.business_name as partner_business,
           p.gst_number as partner_gst
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN partner p ON a.partner_id = p.id
    WHERE a.id='$application_id' AND a.user_type = 'partner'
");

if($row = $sql->fetch_assoc()){
    $application = $row;
    $service_id = $row['service_id'];
    $partner_id = $row['partner_id'];
} else {
    echo "<script>alert('Partner application not found!'); window.location='leads.php';</script>";
    exit();
}

// Get form fields for this service
$form_fields = [];
$sql = $con->query("SELECT * FROM service_form_fields WHERE service_id='$service_id' ORDER BY field_order ASC");
while($row = $sql->fetch_assoc()){
    $form_fields[] = $row;
}

// Get all clients data for this partner application
$clients_data = [];
$client_count = $application['client_count'] ?: 1;

for($i = 1; $i <= $client_count; $i++){
    $client_data = [
        'client_index' => $i,
        'fields' => []
    ];
    
    // Get form responses for this client
    $sql = $con->query("
        SELECT r.*, f.field_name, f.field_label, f.field_type, f.is_required
        FROM service_form_responses r 
        LEFT JOIN service_form_fields f ON r.field_id = f.id 
        WHERE r.application_id='$application_id' 
        AND r.client_index = '$i'
        ORDER BY f.field_order
    ");
    
    while($row = $sql->fetch_assoc()){
        $client_data['fields'][$row['field_id']] = $row;
    }
    
    // Calculate completion stats
    $filled_fields = count($client_data['fields']);
    $total_fields = count($form_fields);
    $completion_percentage = $total_fields > 0 ? round(($filled_fields / $total_fields) * 100) : 0;
    
    $client_data['filled_fields'] = $filled_fields;
    $client_data['total_fields'] = $total_fields;
    $client_data['completion'] = $completion_percentage;
    
    $clients_data[$i] = $client_data;
}

// Get service details
$service = [];
$sql = $con->query("SELECT * FROM service WHERE id='$service_id'");
if($row = $sql->fetch_assoc()){
    $service = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Partner Clients - Legal Taxation</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .client-card {
      border-left: 4px solid #007bff;
      margin-bottom: 20px;
    }
    .client-header {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 15px;
    }
    .completion-badge {
      font-size: 0.8rem;
      padding: 3px 8px;
      border-radius: 10px;
    }
    .completion-100 { background-color: #28a745; color: white; }
    .completion-75 { background-color: #17a2b8; color: white; }
    .completion-50 { background-color: #ffc107; color: black; }
    .completion-25 { background-color: #fd7e14; color: white; }
    .completion-0 { background-color: #dc3545; color: white; }
    .field-row {
      border-bottom: 1px solid #dee2e6;
      padding: 10px 0;
    }
    .field-row:last-child {
      border-bottom: none;
    }
    .field-label {
      font-weight: bold;
      color: #495057;
    }
    .field-value {
      background: #f8f9fa;
      padding: 8px;
      border-radius: 4px;
      word-break: break-word;
    }
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      background: #f8f9fa;
      border-radius: 10px;
    }
    .progress-thin {
      height: 8px;
    }
    .client-nav {
      position: sticky;
      top: 0;
      background: white;
      z-index: 100;
      padding: 10px 0;
      border-bottom: 2px solid #dee2e6;
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
                <i class="mdi mdi-account-multiple"></i>
              </span>&nbsp;Partner Clients
              <small class="text-muted">(Application #<?php echo $application_id; ?>)</small>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="service_form_submissions.php?service_id=<?php echo $service_id; ?>">Form Submissions</a></li>
              <li class="breadcrumb-item active">Partner Clients</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Application Summary -->
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <h5>Partner Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Partner:</th>
                        <td><?php echo htmlspecialchars($application['partner_name']); ?></td>
                      </tr>
                      <tr>
                        <th>Business:</th>
                        <td><?php echo htmlspecialchars($application['partner_business']); ?></td>
                      </tr>
                      <tr>
                        <th>Contact:</th>
                        <td><?php echo htmlspecialchars($application['partner_contact']); ?></td>
                      </tr>
                      <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($application['partner_email']); ?></td>
                      </tr>
                    </table>
                  </div>
                  
                  <div class="col-md-4">
                    <h5>Service Details</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Service:</th>
                        <td><?php echo htmlspecialchars($application['service_title']); ?></td>
                      </tr>
                      <tr>
                        <th>Client Count:</th>
                        <td>
                          <span class="badge badge-info"><?php echo $client_count; ?> clients</span>
                        </td>
                      </tr>
                      <tr>
                        <th>Unit Price:</th>
                        <td>₹<?php echo number_format($application['unit_price'], 2); ?></td>
                      </tr>
                      <tr>
                        <th>Total Amount:</th>
                        <td class="text-success font-weight-bold">₹<?php echo number_format($application['total_amount'], 2); ?></td>
                      </tr>
                    </table>
                  </div>
                  
                  <div class="col-md-4">
                    <h5>Form Statistics</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Total Fields:</th>
                        <td><?php echo count($form_fields); ?> per client</td>
                      </tr>
                      <tr>
                        <th>Overall Progress:</th>
                        <td>
                          <?php
                          $total_filled = 0;
                          $total_possible = count($form_fields) * $client_count;
                          foreach($clients_data as $client){
                              $total_filled += $client['filled_fields'];
                          }
                          $overall_percentage = $total_possible > 0 ? round(($total_filled / $total_possible) * 100) : 0;
                          ?>
                          <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: <?php echo $overall_percentage; ?>%"></div>
                          </div>
                          <small><?php echo $overall_percentage; ?>% (<?php echo $total_filled; ?>/<?php echo $total_possible; ?>)</small>
                        </td>
                      </tr>
                      <tr>
                        <th>Complete Clients:</th>
                        <td>
                          <?php
                          $complete_clients = 0;
                          foreach($clients_data as $client){
                              if($client['completion'] == 100) $complete_clients++;
                          }
                          ?>
                          <span class="badge badge-success"><?php echo $complete_clients; ?>/<?php echo $client_count; ?></span>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a href="view_customer_form_data.php?application_id=<?php echo $application_id; ?>" 
                   class="btn btn-secondary">
                  <i class="fa fa-arrow-left"></i> Back to Application
                </a>
                <button type="button" class="btn btn-primary" onclick="printAllClients()">
                  <i class="fa fa-print"></i> Print All
                </button>
                <button type="button" class="btn btn-info" onclick="exportClientsData()">
                  <i class="fa fa-download"></i> Export Data
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Client Navigation -->
        <?php if($client_count > 1): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="client-nav">
              <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Clients:</h5>
                <div class="btn-group">
                  <?php for($i = 1; $i <= $client_count; $i++): 
                    $client = $clients_data[$i];
                    $badge_class = 'completion-' . floor($client['completion'] / 25) * 25;
                  ?>
                  <a href="#client-<?php echo $i; ?>" class="btn btn-sm btn-outline-primary">
                    Client <?php echo $i; ?>
                    <span class="badge <?php echo $badge_class; ?> ml-1">
                      <?php echo $client['completion']; ?>%
                    </span>
                  </a>
                  <?php endfor; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Clients Data -->
        <div class="row">
          <div class="col-md-12">
            <?php for($i = 1; $i <= $client_count; $i++): 
              $client = $clients_data[$i];
              $badge_class = 'completion-' . floor($client['completion'] / 25) * 25;
            ?>
            <div class="card client-card" id="client-<?php echo $i; ?>">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-user"></i> Client <?php echo $i; ?>
                  <span class="badge <?php echo $badge_class; ?> ml-2">
                    <?php echo $client['completion']; ?>% Complete
                  </span>
                  <small class="text-muted ml-2">
                    (<?php echo $client['filled_fields']; ?>/<?php echo $client['total_fields']; ?> fields)
                  </small>
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <?php if(empty($client['fields'])): ?>
                  <div class="empty-state">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h4>No Form Data Available</h4>
                    <p class="text-muted">This client hasn't submitted any form data yet.</p>
                    <div class="alert alert-info">
                      <i class="fas fa-info-circle"></i> 
                      The partner needs to fill the form for this client from their dashboard.
                    </div>
                  </div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr class="bg-light">
                          <th width="5%">#</th>
                          <th width="35%">Field</th>
                          <th width="15%">Type</th>
                          <th width="45%">Value</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach($form_fields as $field): 
                          $response = $client['fields'][$field['id']] ?? null;
                          $field_value = $response ? $response['field_value'] : 'Not Provided';
                        ?>
                          <tr>
                            <td><?php echo $counter++; ?></td>
                            <td>
                              <div class="field-label"><?php echo htmlspecialchars($field['field_label']); ?></div>
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
                            </td>
                            <td>
                              <?php if($field['field_type'] == 'file' && $field_value != 'Not Provided'): ?>
                                <a href="../uploads/service_docs/<?php echo $field_value; ?>" 
                                   target="_blank" class="btn btn-sm btn-info">
                                  <i class="fa fa-download"></i> Download
                                </a>
                                <small class="text-muted d-block"><?php echo basename($field_value); ?></small>
                              <?php elseif($field['field_type'] == 'checkbox' && $field_value != 'Not Provided'): 
                                $values = explode(',', $field_value);
                                foreach($values as $val):
                              ?>
                                <span class="badge badge-primary mb-1"><?php echo htmlspecialchars(trim($val)); ?></span>
                              <?php endforeach;
                              else: ?>
                                <div class="field-value">
                                  <?php echo nl2br(htmlspecialchars($field_value)); ?>
                                </div>
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
                <div class="row">
                  <div class="col-md-6">
                    <small class="text-muted">
                      <i class="fa fa-clock"></i> 
                      <?php if(!empty($client['fields'])): ?>
                        Last updated: 
                        <?php 
                        $last_response = end($client['fields']);
                        echo date('d M Y, h:i A', strtotime($last_response['created_at']));
                        ?>
                      <?php else: ?>
                        No data submitted yet
                      <?php endif; ?>
                    </small>
                  </div>
                  <div class="col-md-6 text-right">
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-primary" onclick="printClient(<?php echo $i; ?>)">
                        <i class="fa fa-print"></i> Print
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-info" onclick="copyClientData(<?php echo $i; ?>)">
                        <i class="fa fa-copy"></i> Copy
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php endfor; ?>
          </div>
        </div>

        <!-- Summary Statistics -->
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header bg-info">
                <h3 class="card-title">
                  <i class="fas fa-chart-bar"></i> Client Statistics Summary
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3 col-6">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-primary">
                        <i class="fas fa-users"></i>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">Total Clients</span>
                        <span class="info-box-number"><?php echo $client_count; ?></span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-3 col-6">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">Complete</span>
                        <span class="info-box-number">
                          <?php
                          $complete = 0;
                          foreach($clients_data as $client){
                              if($client['completion'] == 100) $complete++;
                          }
                          echo $complete;
                          ?>
                        </span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-3 col-6">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-warning">
                        <i class="fas fa-hourglass-half"></i>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">In Progress</span>
                        <span class="info-box-number">
                          <?php
                          $in_progress = 0;
                          foreach($clients_data as $client){
                              if($client['completion'] > 0 && $client['completion'] < 100) $in_progress++;
                          }
                          echo $in_progress;
                          ?>
                        </span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-3 col-6">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                      </span>
                      <div class="info-box-content">
                        <span class="info-box-text">Not Started</span>
                        <span class="info-box-number">
                          <?php
                          $not_started = 0;
                          foreach($clients_data as $client){
                              if($client['completion'] == 0) $not_started++;
                          }
                          echo $not_started;
                          ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Progress Chart -->
                <div class="mt-4">
                  <h6>Client Completion Distribution:</h6>
                  <div class="progress" style="height: 30px;">
                    <?php
                    $groups = [
                        '100%' => ['min' => 100, 'max' => 100, 'color' => 'bg-success'],
                        '75-99%' => ['min' => 75, 'max' => 99, 'color' => 'bg-info'],
                        '50-74%' => ['min' => 50, 'max' => 74, 'color' => 'bg-warning'],
                        '1-49%' => ['min' => 1, 'max' => 49, 'color' => 'bg-orange'],
                        '0%' => ['min' => 0, 'max' => 0, 'color' => 'bg-danger']
                    ];
                    
                    foreach($groups as $label => $range){
                        $count = 0;
                        foreach($clients_data as $client){
                            if($client['completion'] >= $range['min'] && $client['completion'] <= $range['max']){
                                $count++;
                            }
                        }
                        $percentage = $client_count > 0 ? round(($count / $client_count) * 100) : 0;
                        if($percentage > 0):
                    ?>
                    <div class="progress-bar <?php echo $range['color']; ?>" 
                         style="width: <?php echo $percentage; ?>%"
                         title="<?php echo $label; ?>: <?php echo $count; ?> clients (<?php echo $percentage; ?>%)">
                      <?php if($percentage >= 10): ?>
                        <?php echo $label; ?> (<?php echo $count; ?>)
                      <?php endif; ?>
                    </div>
                    <?php endif; } ?>
                  </div>
                  <div class="mt-2">
                    <small class="text-muted">
                      <?php echo $total_filled; ?> total fields filled across all clients
                    </small>
                  </div>
                </div>
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

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
function printClient(clientIndex) {
    var clientCard = document.getElementById('client-' + clientIndex);
    var printContent = clientCard.querySelector('.table-responsive').innerHTML;
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Client ${clientIndex} Data - Application #<?php echo $application_id; ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; }
                th { background-color: #f2f2f2; }
                .print-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .print-info { margin-bottom: 20px; background: #f8f9fa; padding: 15px; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>Client ${clientIndex} Form Data</h2>
                <p>Application #<?php echo $application_id; ?> | Partner: <?php echo htmlspecialchars($application['partner_name']); ?></p>
            </div>
            
            <div class="print-info">
                <p><strong>Service:</strong> <?php echo htmlspecialchars($application['service_title']); ?></p>
                <p><strong>Total Clients:</strong> <?php echo $client_count; ?></p>
                <p><strong>Print Date:</strong> ${new Date().toLocaleDateString()}</p>
            </div>
            
            ${printContent}
            
            <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
                <p>Generated by Legal Taxation Admin Panel</p>
            </div>
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
}

function printAllClients() {
    var printContent = '';
    var clientCards = document.querySelectorAll('.client-card');
    
    clientCards.forEach(function(card, index) {
        var clientIndex = index + 1;
        var clientData = card.querySelector('.table-responsive') ? 
                        card.querySelector('.table-responsive').innerHTML : 
                        '<p>No data available for this client</p>';
        
        printContent += `
            <div style="page-break-after: always;">
                <h3>Client ${clientIndex}</h3>
                ${clientData}
                <hr style="margin: 30px 0;">
            </div>
        `;
    });
    
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>All Clients Data - Application #<?php echo $application_id; ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; }
                th { background-color: #f2f2f2; }
                .header { text-align: center; margin-bottom: 30px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>All Clients Form Data</h1>
                <h3>Application #<?php echo $application_id; ?></h3>
                <p><strong>Partner:</strong> <?php echo htmlspecialchars($application['partner_name']); ?></p>
                <p><strong>Service:</strong> <?php echo htmlspecialchars($application['service_title']); ?></p>
                <p><strong>Total Clients:</strong> <?php echo $client_count; ?></p>
                <p><strong>Print Date:</strong> ${new Date().toLocaleDateString()}</p>
            </div>
            
            ${printContent}
            
            <div style="text-align: center; margin-top: 50px; font-size: 12px; color: #666;">
                <p>Generated by Legal Taxation Admin Panel</p>
                <p>Total Pages: ${clientCards.length}</p>
            </div>
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
}

function exportClientsData() {
    // Collect all data
    var data = [];
    var headers = ['Client', 'Field', 'Value'];
    
    <?php foreach($clients_data as $client_index => $client): ?>
    <?php foreach($form_fields as $field): ?>
    <?php
    $response = $client['fields'][$field['id']] ?? null;
    $field_value = $response ? $response['field_value'] : 'Not Provided';
    ?>
    data.push([
        'Client <?php echo $client_index; ?>',
        '<?php echo addslashes($field['field_label']); ?>',
        '<?php echo addslashes($field_value); ?>'
    ]);
    <?php endforeach; ?>
    <?php endforeach; ?>
    
    // Convert to CSV
    var csvContent = "data:text/csv;charset=utf-8,";
    csvContent += headers.join(",") + "\n";
    data.forEach(function(rowArray) {
        var row = rowArray.map(function(cell) {
            return '"' + cell.replace(/"/g, '""') + '"';
        }).join(",");
        csvContent += row + "\n";
    });
    
    // Create download link
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "clients_data_<?php echo $application_id; ?>_<?php echo date('Y-m-d'); ?>.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    alert('Data exported successfully!');
}

function copyClientData(clientIndex) {
    var clientData = "Client " + clientIndex + " Details:\n";
    clientData += "=====================\n\n";
    
    <?php foreach($form_fields as $field): ?>
    clientData += "<?php echo addslashes($field['field_label']); ?>: \n";
    <?php endforeach; ?>
    
    navigator.clipboard.writeText(clientData).then(function() {
        alert('Client ' + clientIndex + ' data structure copied to clipboard!');
    });
}

// Smooth scroll to client sections
$(document).ready(function() {
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this.getAttribute('href'));
        if(target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 70
            }, 1000);
        }
    });
});
</script>
</body>
</html>