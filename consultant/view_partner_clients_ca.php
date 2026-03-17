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
$check_sql = $con->query("SELECT * FROM apply WHERE id='$application_id' AND send_to='$ca_id' AND user_type='partner'");
if($check_sql->num_rows == 0){
    echo "<script>alert('Unauthorized access or not a partner application!'); window.location='assigned_services.php';</script>";
    exit();
}

// Get application details with partner info
$application = [];
$sql = $con->query("
    SELECT a.*, 
           s.title as service_title,
           s.id as service_id,
           p.name as partner_name,
           p.email as partner_email,
           p.contact as partner_contact,
           p.business_name as partner_business
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN partner p ON a.partner_id = p.id
    WHERE a.id='$application_id'
");
if($row = $sql->fetch_assoc()){
    $application = $row;
    $service_id = $row['service_id'];
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

// Get all clients data
$clients_data = [];
$client_count = $application['client_count'] ?: 1;

for($i = 1; $i <= $client_count; $i++){
    $client_data = [
        'client_index' => $i,
        'fields' => []
    ];
    
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
    
    $client_data['filled_fields'] = count($client_data['fields']);
    $client_data['total_fields'] = count($form_fields);
    $client_data['completion'] = count($form_fields) > 0 ? round(($client_data['filled_fields'] / count($form_fields)) * 100) : 0;
    
    $clients_data[$i] = $client_data;
}

// Calculate statistics
$total_fields_filled = 0;
$complete_clients = 0;
foreach($clients_data as $client){
    $total_fields_filled += $client['filled_fields'];
    if($client['completion'] == 100){
        $complete_clients++;
    }
}
$overall_percentage = ($client_count * count($form_fields)) > 0 ? 
    round(($total_fields_filled / ($client_count * count($form_fields))) * 100) : 0;
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
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .client-card {
      border-left: 4px solid #007bff;
      margin-bottom: 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .client-header {
      background-color: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 15px;
      border-left: 4px solid #007bff;
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
      margin-bottom: 20px;
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
              <i class="fas fa-users mr-2"></i>Partner Client Details
              <small class="text-muted">(Application #<?php echo $application_id; ?>)</small>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="assigned_services.php">Assigned Services</a></li>
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
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-info-circle"></i> Application Overview
                </h3>
              </div>
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
                        <td><?php echo htmlspecialchars($application['partner_business'] ?? 'N/A'); ?></td>
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
                        <td><span class="badge badge-info"><?php echo $client_count; ?> clients</span></td>
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
                    <h5>Progress Summary</h5>
                    <table class="table table-sm">
                      <tr>
                        <th width="40%">Overall Progress:</th>
                        <td>
                          <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width: <?php echo $overall_percentage; ?>%"></div>
                          </div>
                          <small><?php echo $overall_percentage; ?>% (<?php echo $total_fields_filled; ?>/<?php echo $client_count * count($form_fields); ?> fields)</small>
                        </td>
                      </tr>
                      <tr>
                        <th>Complete Clients:</th>
                        <td><span class="badge badge-success"><?php echo $complete_clients; ?>/<?php echo $client_count; ?></span></td>
                      </tr>
                      <tr>
                        <th>Application Status:</th>
                        <td>
                          <?php if($application['status'] == '0'): ?>
                            <span class="badge badge-warning">Pending</span>
                          <?php elseif($application['status'] == '1'): ?>
                            <span class="badge badge-success">Completed</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a href="assigned_services.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back to Assigned Services
                </a>
                <button type="button" class="btn btn-primary" onclick="printAllClients()">
                  <i class="fas fa-print"></i> Print All
                </button>
                <a href="mailto:<?php echo $application['partner_email']; ?>" class="btn btn-info">
                  <i class="fas fa-envelope"></i> Email Partner
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Client Navigation -->
        <?php if($client_count > 1): ?>
        <div class="client-nav">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Jump to Client:</h5>
            <div>
              <?php for($i = 1; $i <= $client_count; $i++): 
                $client = $clients_data[$i];
                $badge_class = 'completion-' . floor($client['completion'] / 25) * 25;
              ?>
              <a href="#client-<?php echo $i; ?>" class="btn btn-sm btn-outline-primary mr-1 mb-1">
                Client <?php echo $i; ?>
                <span class="badge <?php echo $badge_class; ?> ml-1">
                  <?php echo $client['completion']; ?>%
                </span>
              </a>
              <?php endfor; ?>
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
                    <table class="table table-bordered table-hover">
                      <thead class="thead-light">
                        <tr>
                          <th width="5%">#</th>
                          <th width="35%">Field</th>
                          <th width="60%">Value</th>
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
                                  <span class="badge badge-danger ml-1">Required</span>
                                <?php endif; ?>
                              </small>
                            </td>
                            <td>
                              <?php if($field['field_type'] == 'file' && $field_value != 'Not Provided'): ?>
                                <a href="../uploads/service_docs/<?php echo $field_value; ?>" 
                                   target="_blank" class="btn btn-sm btn-info">
                                  <i class="fa fa-download"></i> Download File
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
                  <i class="fas fa-chart-bar"></i> Client Progress Summary
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3 col-6">
                    <div class="info-box">
                      <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Total Clients</span>
                        <span class="info-box-number"><?php echo $client_count; ?></span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-3 col-6">
                    <div class="info-box">
                      <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Complete</span>
                        <span class="info-box-number"><?php echo $complete_clients; ?></span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-3 col-6">
                    <div class="info-box">
                      <span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">In Progress</span>
                        <span class="info-box-number"><?php echo $client_count - $complete_clients; ?></span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-3 col-6">
                    <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-file-alt"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Total Fields</span>
                        <span class="info-box-number"><?php echo $total_fields_filled; ?>/<?php echo $client_count * count($form_fields); ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Progress Distribution -->
                <div class="mt-4">
                  <h6>Client Completion Distribution:</h6>
                  <div class="progress" style="height: 30px;">
                    <?php
                    $ranges = [
                        '100%' => ['min' => 100, 'max' => 100, 'color' => 'bg-success', 'label' => 'Complete'],
                        '75-99%' => ['min' => 75, 'max' => 99, 'color' => 'bg-info', 'label' => 'Almost Done'],
                        '50-74%' => ['min' => 50, 'max' => 74, 'color' => 'bg-warning', 'label' => 'Halfway'],
                        '1-49%' => ['min' => 1, 'max' => 49, 'color' => 'bg-orange', 'label' => 'Started'],
                        '0%' => ['min' => 0, 'max' => 0, 'color' => 'bg-danger', 'label' => 'Not Started']
                    ];
                    
                    foreach($ranges as $range_name => $range){
                        $count = 0;
                        foreach($clients_data as $client){
                            if($client['completion'] >= $range['min'] && $client['completion'] <= $range['max']){
                                $count++;
                            }
                        }
                        $percentage = $client_count > 0 ? round(($count / $client_count) * 100) : 0;
                        if($count > 0):
                    ?>
                    <div class="progress-bar <?php echo $range['color']; ?>" 
                         style="width: <?php echo $percentage; ?>%"
                         title="<?php echo $range['label']; ?>: <?php echo $count; ?> clients">
                      <?php if($percentage >= 10): ?>
                        <?php echo $range['label']; ?> (<?php echo $count; ?>)
                      <?php endif; ?>
                    </div>
                    <?php endif; } ?>
                  </div>
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
// Smooth scroll to client sections
$(document).ready(function() {
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this.getAttribute('href'));
        if(target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
});

function printAllClients() {
    var printContent = '';
    var clientCards = document.querySelectorAll('.client-card');
    
    clientCards.forEach(function(card, index) {
        var clientIndex = index + 1;
        var table = card.querySelector('.table-responsive');
        if(table) {
            printContent += `
                <div style="page-break-after: always; margin-bottom: 30px;">
                    <h3 style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Client ${clientIndex}</h3>
                    ${table.outerHTML}
                </div>
            `;
        }
    });
    
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Partner Clients Report - Application #<?php echo $application_id; ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 30px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 10px; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #333; }
                .info { background: #f8f9fa; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
                .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Partner Clients Form Data Report</h1>
                <h2>Application #<?php echo $application_id; ?></h2>
            </div>
            
            <div class="info">
                <p><strong>Partner:</strong> <?php echo htmlspecialchars($application['partner_name']); ?></p>
                <p><strong>Service:</strong> <?php echo htmlspecialchars($application['service_title']); ?></p>
                <p><strong>Total Clients:</strong> <?php echo $client_count; ?></p>
                <p><strong>Generated on:</strong> <?php echo date('d M Y, h:i A'); ?></p>
            </div>
            
            ${printContent}
            
            <div class="footer">
                <p>Generated by Legal Taxation CA Panel</p>
            </div>
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
}
</script>
</body>
</html>