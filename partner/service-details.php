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

// Get application ID from URL
$application_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($application_id == 0){
    header("Location: services.php");
    exit();
}

// Get service application details
$app_query = $con->query("
    SELECT 
        a.*,
        s.title as service_name,
        s.image as service_image,
        s.s_des as service_description,
        s.des as service_full_description,
        s.m_price as market_price,
        s.o_price as regular_price,
        s.partner_o_price as partner_price,
        s.min_clients_for_partner,
        c.name as category_name,
        rb.billing_email,
        rb.billing_name,
        rb.billing_mobile,
        rb.payment_status,
        rb.created_at as payment_date
    FROM apply a
    INNER JOIN service s ON a.sid = s.id
    INNER JOIN cate c ON s.cate = c.id
    LEFT JOIN razorpaybill rb ON a.razorpay_order_id = rb.order_id
    WHERE a.id = '$application_id' 
    AND a.partner_id = '$partner_id'
    AND a.user_type = 'partner'
");

if($app_query->num_rows == 0){
    header("Location: services.php");
    exit();
}

$application = $app_query->fetch_assoc();

// Get all clients for this application
$clients_query = $con->query("
    SELECT 
        client_index,
        COUNT(*) as fields_filled,
        MAX(created_at) as last_updated
    FROM service_form_responses 
    WHERE application_id = '$application_id'
    GROUP BY client_index
    ORDER BY client_index
");

$total_clients = $application['client_count'];
$clients_data = [];
$forms_completed = 0;

while($client = $clients_query->fetch_assoc()){
    $clients_data[$client['client_index']] = $client;
    if($client['fields_filled'] >= 5){ // Assuming 5 required fields per client
        $forms_completed++;
    }
}

// Get service form fields
$fields_query = $con->query("
    SELECT * FROM service_form_fields 
    WHERE service_id = '{$application['sid']}'
    ORDER BY field_order
");

// Get all form responses for this application
$responses_query = $con->query("
    SELECT 
        sfr.*,
        sff.field_label,
        sff.field_type,
        sff.field_placeholder
    FROM service_form_responses sfr
    LEFT JOIN service_form_fields sff ON sfr.field_id = sff.id
    WHERE sfr.application_id = '$application_id'
    ORDER BY sfr.client_index, sff.field_order
");

$responses_by_client = [];
while($response = $responses_query->fetch_assoc()){
    $client_index = $response['client_index'];
    if(!isset($responses_by_client[$client_index])){
        $responses_by_client[$client_index] = [];
    }
    $responses_by_client[$client_index][] = $response;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Service Details - Partner Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .service-header {
        background: linear-gradient(45deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .info-card {
        border-left: 4px solid;
        margin-bottom: 15px;
    }
    .info-card.border-primary { border-left-color: #007bff; }
    .info-card.border-success { border-left-color: #28a745; }
    .info-card.border-info { border-left-color: #17a2b8; }
    .info-card.border-warning { border-left-color: #ffc107; }
    .client-card {
        transition: all 0.3s;
        cursor: pointer;
    }
    .client-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .tab-content {
        padding: 20px;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 10px 10px;
    }
    .response-value {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        border-left: 3px solid #007bff;
        margin-bottom: 10px;
    }
    .pricing-breakdown {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
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
            <h1 class="m-0">Service Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="services.php">Services</a></li>
              <li class="breadcrumb-item active">Details</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Service Header -->
        <div class="service-header">
          <div class="row">
            <div class="col-md-8">
              <h2><?php echo htmlspecialchars($application['service_name']); ?></h2>
              <p class="mb-1">
                <i class="fas fa-tag"></i> Category: <?php echo htmlspecialchars($application['category_name']); ?> | 
                <i class="fas fa-calendar"></i> Purchased: <?php echo date('F d, Y', strtotime($application['created'])); ?>
              </p>
              <p class="mb-0">
                Application ID: <strong>#<?php echo str_pad($application_id, 6, '0', STR_PAD_LEFT); ?></strong> | 
                Status: 
                <?php
                $status_text = '';
                $status_class = '';
                switch($application['status']) {
                    case '0': $status_text = 'Pending'; $status_class = 'badge-warning'; break;
                    case '1': $status_text = 'Processing'; $status_class = 'badge-info'; break;
                    case '2': $status_text = 'Completed'; $status_class = 'badge-success'; break;
                    case '3': $status_text = 'Rejected'; $status_class = 'badge-danger'; break;
                    default: $status_text = 'Unknown'; $status_class = 'badge-secondary';
                }
                ?>
                <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
              </p>
            </div>
            <div class="col-md-4 text-right">
              <a href="../service-details.php?id=<?php echo $application['sid']; ?>" 
                 class="btn btn-light" 
                 target="_blank">
                <i class="fas fa-external-link-alt mr-1"></i> View Service Page
              </a>
              <a href="client-details.php?app_id=<?php echo $application_id; ?>" 
                 class="btn btn-outline-light ml-2">
                <i class="fas fa-users mr-1"></i> View All Clients
              </a>
            </div>
          </div>
        </div>

        <!-- Main Content Row -->
        <div class="row">
          <!-- Left Column: Service Info & Clients -->
          <div class="col-md-8">
            
            <!-- Service Information -->
            <div class="card mb-4">
              <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i>Service Information</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="info-card border-primary">
                      <div class="p-3">
                        <h5 class="mb-1"><i class="fas fa-users text-primary mr-2"></i>Client Information</h5>
                        <p class="mb-1"><strong>Total Clients:</strong> <?php echo $application['client_count']; ?></p>
                        <p class="mb-1"><strong>Forms Completed:</strong> <?php echo $forms_completed; ?> of <?php echo $application['client_count']; ?></p>
                        <div class="progress mt-2" style="height: 10px;">
                          <div class="progress-bar <?php echo $forms_completed == $application['client_count'] ? 'bg-success' : 'bg-warning'; ?>" 
                               role="progressbar" 
                               style="width: <?php echo $application['client_count'] > 0 ? ($forms_completed / $application['client_count'] * 100) : 0; ?>%">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="info-card border-success">
                      <div class="p-3">
                        <h5 class="mb-1"><i class="fas fa-file-alt text-success mr-2"></i>Form Progress</h5>
                        <?php 
                        $total_fields = $fields_query->num_rows * $application['client_count'];
                        $fields_filled = $responses_query->num_rows;
                        $progress_percentage = $total_fields > 0 ? round(($fields_filled / $total_fields) * 100) : 0;
                        ?>
                        <p class="mb-1"><strong>Fields Filled:</strong> <?php echo $fields_filled; ?> of <?php echo $total_fields; ?></p>
                        <div class="progress mt-2" style="height: 10px;">
                          <div class="progress-bar <?php echo $progress_percentage >= 100 ? 'bg-success' : 'bg-info'; ?>" 
                               role="progressbar" 
                               style="width: <?php echo $progress_percentage; ?>%">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Service Description -->
                <?php if(!empty($application['service_description'])): ?>
                <div class="mt-4">
                  <h5><i class="fas fa-align-left mr-2"></i>Service Description</h5>
                  <p><?php echo htmlspecialchars($application['service_description']); ?></p>
                </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Clients Overview -->
            <div class="card">
              <div class="card-header bg-info text-white">
                <h3 class="card-title mb-0"><i class="fas fa-users mr-2"></i>Clients Overview</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <?php for($i = 1; $i <= $application['client_count']; $i++): 
                    $client_data = isset($clients_data[$i]) ? $clients_data[$i] : null;
                    $fields_filled = $client_data ? $client_data['fields_filled'] : 0;
                    $is_complete = $fields_filled >= 5; // Assuming 5 required fields
                  ?>
                  <div class="col-md-6 mb-3">
                    <div class="card client-card <?php echo $is_complete ? 'border-success' : 'border-warning'; ?>">
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <h5 class="card-title mb-0">
                            <i class="fas fa-user mr-2"></i>Client <?php echo $i; ?>
                          </h5>
                          <span class="badge <?php echo $is_complete ? 'badge-success' : 'badge-warning'; ?>">
                            <?php echo $is_complete ? 'Complete' : 'In Progress'; ?>
                          </span>
                        </div>
                        <p class="card-text">
                          <small class="text-muted">
                            <i class="fas fa-file-alt mr-1"></i> 
                            <?php echo $fields_filled; ?> fields filled
                          </small>
                          <?php if($client_data && $client_data['last_updated']): ?>
                          <br>
                          <small class="text-muted">
                            <i class="fas fa-clock mr-1"></i> 
                            Last updated: <?php echo date('d M Y', strtotime($client_data['last_updated'])); ?>
                          </small>
                          <?php endif; ?>
                        </p>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="collapse" 
                                data-target="#clientDetails<?php echo $i; ?>">
                          <i class="fas fa-eye mr-1"></i> View Details
                        </button>
                        <a href="client-details.php?app_id=<?php echo $application_id; ?>&client=<?php echo $i; ?>" 
                           class="btn btn-sm btn-outline-info ml-1">
                          <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                      </div>
                      
                      <!-- Client Details Collapse -->
                      <div class="collapse" id="clientDetails<?php echo $i; ?>">
                        <div class="card-footer">
                          <?php if(isset($responses_by_client[$i]) && !empty($responses_by_client[$i])): ?>
                            <h6><i class="fas fa-list mr-1"></i> Submitted Information:</h6>
                            <?php foreach($responses_by_client[$i] as $response): ?>
                              <div class="response-value mb-2">
                                <strong><?php echo htmlspecialchars($response['field_label']); ?>:</strong><br>
                                <span><?php echo htmlspecialchars($response['field_value']); ?></span>
                              </div>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <div class="alert alert-warning mb-0">
                              <i class="fas fa-exclamation-triangle mr-1"></i>
                              No information submitted for this client yet.
                            </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endfor; ?>
                </div>
              </div>
            </div>

          </div>

          <!-- Right Column: Pricing & Payment Info -->
          <div class="col-md-4">
            
            <!-- Pricing Information -->
            <div class="card mb-4">
              <div class="card-header bg-success text-white">
                <h3 class="card-title mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Pricing Information</h3>
              </div>
              <div class="card-body">
                <div class="pricing-breakdown">
                  <h5>Pricing Breakdown</h5>
                  <table class="table table-sm">
                    <tr>
                      <td>Regular Price:</td>
                      <td class="text-right">₹<?php echo $application['regular_price']; ?></td>
                    </tr>
                    <?php if($application['partner_price'] > 0): ?>
                    <tr>
                      <td>Partner Price:</td>
                      <td class="text-right">₹<?php echo $application['partner_price']; ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td>Min Clients for Partner Price:</td>
                      <td class="text-right"><?php echo $application['min_clients_for_partner']; ?></td>
                    </tr>
                    <tr>
                      <td><strong>Applied Price:</strong></td>
                      <td class="text-right">
                        <strong>₹<?php echo $application['unit_price']; ?></strong>
                        <?php if($application['is_partner_price_applied']): ?>
                          <br><span class="badge badge-success">Partner Price Applied</span>
                        <?php else: ?>
                          <br><span class="badge badge-secondary">Regular Price</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <tr class="table-active">
                      <td><strong>Total Amount:</strong></td>
                      <td class="text-right">
                        <strong>₹<?php echo $application['total_amount']; ?></strong><br>
                        <small>(<?php echo $application['client_count']; ?> clients × ₹<?php echo $application['unit_price']; ?>)</small>
                      </td>
                    </tr>
                  </table>
                  
                  <?php 
                  $savings = ($application['regular_price'] * $application['client_count']) - $application['total_amount'];
                  if($savings > 0): 
                  ?>
                  <div class="alert alert-success mt-3">
                    <i class="fas fa-piggy-bank mr-2"></i>
                    <strong>You saved: ₹<?php echo $savings; ?></strong>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Payment Information -->
            <div class="card mb-4">
              <div class="card-header bg-warning text-white">
                <h3 class="card-title mb-0"><i class="fas fa-credit-card mr-2"></i>Payment Information</h3>
              </div>
              <div class="card-body">
                <?php if(!empty($application['billing_email'])): ?>
                <div class="info-card border-warning">
                  <div class="p-3">
                    <h6><i class="fas fa-user-circle mr-2"></i>Billing Details</h6>
                    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($application['billing_name']); ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($application['billing_email']); ?></p>
                    <p class="mb-1"><strong>Mobile:</strong> <?php echo htmlspecialchars($application['billing_mobile']); ?></p>
                    <p class="mb-0"><strong>Payment Status:</strong> 
                      <span class="badge <?php echo $application['payment_status'] == 'Success' ? 'badge-success' : 'badge-warning'; ?>">
                        <?php echo $application['payment_status'] ?: 'Pending'; ?>
                      </span>
                    </p>
                  </div>
                </div>
                <?php endif; ?>
                
                <div class="mt-3">
                  <h6><i class="fas fa-receipt mr-2"></i>Order Details</h6>
                  <table class="table table-sm">
                    <tr>
                      <td>Order ID:</td>
                      <td class="text-right">
                        <?php echo $application['razorpay_order_id'] ?: 'N/A'; ?>
                      </td>
                    </tr>
                    <tr>
                      <td>Payment Date:</td>
                      <td class="text-right">
                        <?php echo $application['payment_date'] ? date('d M Y', strtotime($application['payment_date'])) : 'N/A'; ?>
                      </td>
                    </tr>
                    <tr>
                      <td>Payment Method:</td>
                      <td class="text-right">
                        <?php echo $application['payment_method'] ?: 'Razorpay'; ?>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
              <div class="card-header bg-secondary text-white">
                <h3 class="card-title mb-0"><i class="fas fa-bolt mr-2"></i>Quick Actions</h3>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <a href="client-details.php?app_id=<?php echo $application_id; ?>" 
                     class="btn btn-primary">
                    <i class="fas fa-users mr-2"></i> Manage All Clients
                  </a>
                  <a href="../service-details.php?id=<?php echo $application['sid']; ?>" 
                     class="btn btn-info" 
                     target="_blank">
                    <i class="fas fa-external-link-alt mr-2"></i> View Service Page
                  </a>
                  <a href="services.php" 
                     class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Services
                  </a>
                  <?php if($application['status'] == '0'): ?>
                  <button class="btn btn-warning" data-toggle="modal" data-target="#followupModal">
                    <i class="fas fa-headset mr-2"></i> Request Follow-up
                  </button>
                  <?php endif; ?>
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

<!-- Follow-up Modal -->
<div class="modal fade" id="followupModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-headset mr-2"></i>Request Follow-up</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Request a follow-up for Application #<?php echo str_pad($application_id, 6, '0', STR_PAD_LEFT); ?></p>
        <form id="followupForm">
          <div class="form-group">
            <label for="followupMessage">Message:</label>
            <textarea class="form-control" id="followupMessage" rows="3" 
                      placeholder="Enter any specific questions or concerns..."></textarea>
          </div>
          <div class="form-group">
            <label for="followupContact">Preferred Contact Method:</label>
            <select class="form-control" id="followupContact">
              <option value="email">Email</option>
              <option value="phone">Phone Call</option>
              <option value="whatsapp">WhatsApp</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitFollowup">Submit Request</button>
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
  // Handle follow-up request
  $('#submitFollowup').click(function() {
    var message = $('#followupMessage').val();
    var contact = $('#followupContact').val();
    
    if(message.trim() === '') {
      alert('Please enter a message');
      return;
    }
    
    // In a real application, you would send this data to the server
    console.log('Follow-up requested:', {
      application_id: <?php echo $application_id; ?>,
      message: message,
      contact_method: contact
    });
    
    $('#followupModal').modal('hide');
    alert('Follow-up request submitted successfully!');
    
    // Reset form
    $('#followupMessage').val('');
    $('#followupContact').val('email');
  });
  
  // Initialize tooltips
  $('[data-toggle="tooltip"]').tooltip();
  
  // Print functionality
  $('#printBtn').click(function() {
    window.print();
  });
});
</script>
</body>
</html>