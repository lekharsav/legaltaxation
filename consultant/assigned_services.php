<?php
session_start();

// Check if CA is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../ca-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Now include database connection AFTER session check
include("../db.php");

// Get CA details for this page
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
if($sql->num_rows == 0){
    session_destroy();
    header("Location: ../ca-login.php");
    exit();
}

$ca = $sql->fetch_assoc();

// Set CA variables for use in THIS page
$ca_image = $ca["image"];
$ca_name = $ca["name"];
$ca_contact = $ca["cont"];
$ca_email = $ca["email"];
$ca_reg_no = $ca["reg_no"];
$ca_id_number = $ca["ca_id"];

// Filter by user type (customer/partner/all)
$filter_user_type = isset($_GET['user_type']) ? $_GET['user_type'] : 'all';

// Get assigned services with customer/partner details
$assigned_services = [];
$total_assignments = 0;
$pending_assignments = 0;
$completed_assignments = 0;
$customer_count = 0;
$partner_count = 0;

// Build query with filters
$where_clause = "WHERE a.send_to = '$ca_id'";
if($filter_user_type != 'all'){
    $where_clause .= " AND a.user_type = '$filter_user_type'";
}

$sql = $con->query("SELECT 
    a.id as application_id,
    a.cid as user_id,
    a.sid as service_id,
    a.created as application_date,
    a.status as application_status,
    a.user_type,
    a.client_count,
    a.is_partner_price_applied,
    a.unit_price,
    a.total_amount,
    CASE 
        WHEN a.user_type = 'customer' THEN c.name
        WHEN a.user_type = 'partner' THEN p.name
        ELSE 'Unknown'
    END as user_name,
    CASE 
        WHEN a.user_type = 'customer' THEN c.contact
        WHEN a.user_type = 'partner' THEN p.contact
        ELSE ''
    END as user_contact,
    CASE 
        WHEN a.user_type = 'customer' THEN c.email
        WHEN a.user_type = 'partner' THEN p.email
        ELSE ''
    END as user_email,
    s.title as service_title,
    s.cate as category_id,
    s.m_price as market_price,
    s.o_price as original_price,
    s.partner_o_price as partner_price,
    cat.name as category_name,
    p.business_name as partner_business
    FROM apply a
    LEFT JOIN customer c ON a.user_type = 'customer' AND a.cid = c.id
    LEFT JOIN partner p ON a.user_type = 'partner' AND a.partner_id = p.id
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN cate cat ON s.cate = cat.id
    $where_clause
    ORDER BY a.created DESC");

if($sql){
    $total_assignments = $sql->num_rows;
    while($row = $sql->fetch_assoc()){
        $assigned_services[] = $row;
        
        // Count by user type
        if($row['user_type'] == 'customer'){
            $customer_count++;
        } elseif($row['user_type'] == 'partner'){
            $partner_count++;
        }
        
        // Count by status
        if($row['application_status'] == '0'){
            $pending_assignments++;
        } elseif($row['application_status'] == '1'){
            $completed_assignments++;
        }
    }
}

// Get statistics for dashboard
$total_customers = count(array_unique(array_column(
    array_filter($assigned_services, function($s) { return $s['user_type'] == 'customer'; }), 
    'user_id'
)));
$total_partners = count(array_unique(array_column(
    array_filter($assigned_services, function($s) { return $s['user_type'] == 'partner'; }), 
    'user_id'
)));
$total_services = count(array_unique(array_column($assigned_services, 'service_id')));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assigned Services - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  
  <style>
    .service-card {
      border-left: 4px solid #007bff;
      margin-bottom: 15px;
      transition: all 0.3s;
    }
    .service-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .user-info {
      background: #f8f9fa;
      border-radius: 5px;
      padding: 10px;
    }
    .status-badge {
      font-size: 0.8rem;
      padding: 3px 8px;
    }
    .action-buttons {
      white-space: nowrap;
    }
    .user-type-badge {
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 10px;
    }
    .customer-badge {
      background-color: #28a745;
      color: white;
    }
    .partner-badge {
      background-color: #007bff;
      color: white;
    }
    .client-count-badge {
      background-color: #6c757d;
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 10px;
    }
    .filter-active {
      background-color: #007bff !important;
      color: white !important;
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
              <i class="fas fa-tasks mr-2"></i>Assigned Services
              <small class="text-muted">(<?php echo $total_assignments; ?> total)</small>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Assigned Services</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $total_assignments; ?></h3>
                <p>Total Assignments</p>
                <div class="small">
                  <span class="badge badge-success">Customers: <?php echo $customer_count; ?></span>
                  <span class="badge badge-primary">Partners: <?php echo $partner_count; ?></span>
                </div>
              </div>
              <div class="icon">
                <i class="fas fa-tasks"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $pending_assignments; ?></h3>
                <p>Pending</p>
                <div class="small">Awaiting action</div>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $completed_assignments; ?></h3>
                <p>Completed</p>
                <div class="small">Service delivered</div>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?php echo $total_customers + $total_partners; ?></h3>
                <p>Unique Clients</p>
                <div class="small">
                  <span class="badge badge-success">Customers: <?php echo $total_customers; ?></span>
                  <span class="badge badge-primary">Partners: <?php echo $total_partners; ?></span>
                </div>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter Options -->
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Filter by User Type:</label>
                      <div class="btn-group d-flex">
                        <a href="?user_type=all" class="btn btn-sm <?php echo $filter_user_type == 'all' ? 'btn-primary' : 'btn-outline-primary'; ?> flex-fill">
                          All (<?php echo $total_assignments; ?>)
                        </a>
                        <a href="?user_type=customer" class="btn btn-sm <?php echo $filter_user_type == 'customer' ? 'btn-success' : 'btn-outline-success'; ?> flex-fill">
                          Customers (<?php echo $customer_count; ?>)
                        </a>
                        <a href="?user_type=partner" class="btn btn-sm <?php echo $filter_user_type == 'partner' ? 'btn-info' : 'btn-outline-info'; ?> flex-fill">
                          Partners (<?php echo $partner_count; ?>)
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Filter by Status:</label>
                      <select class="form-control" id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Completed</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Filter by Service:</label>
                      <select class="form-control" id="serviceFilter">
                        <option value="all">All Services</option>
                        <?php
                        if(!empty($assigned_services)){
                            $services = array_unique(array_column($assigned_services, 'service_title'));
                            foreach($services as $service){
                                echo "<option value='".htmlspecialchars($service)."'>".htmlspecialchars($service)."</option>";
                            }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Filter by Date:</label>
                      <input type="date" class="form-control" id="dateFilter">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Assigned Services List -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-list mr-2"></i>Your Assigned Services
                  <span class="badge badge-primary ml-2"><?php echo $total_assignments; ?></span>
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <?php if(empty($assigned_services)): ?>
                  <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4>No Services Assigned Yet</h4>
                    <p class="text-muted">You don't have any services assigned to you at the moment.</p>
                    <?php if($filter_user_type != 'all'): ?>
                      <p class="text-info">
                        <i class="fas fa-info-circle"></i> 
                        No <?php echo $filter_user_type; ?> assignments found. 
                        <a href="?user_type=all">View all assignments</a>
                      </p>
                    <?php else: ?>
                      <p class="text-info">
                        <i class="fas fa-info-circle"></i> 
                        Services will appear here once admin assigns them to you.
                      </p>
                    <?php endif; ?>
                  </div>
                <?php else: ?>
                  <div class="table-responsive" id="assignments-list">
                    <table class="table table-striped table-bordered" id="servicesTable">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Application ID</th>
                          <th>User Type</th>
                          <th>User Details</th>
                          <th>Service Details</th>
                          <th>Clients</th>
                          <th>Application Date</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $sl = 1;
                        foreach($assigned_services as $service):
                          // Get form responses count for this application
                          $form_count = 0;
                          $form_sql = $con->query("SELECT COUNT(*) as count FROM service_form_responses WHERE application_id='".$service['application_id']."'");
                          if($form_sql && $form_row = $form_sql->fetch_assoc()){
                            $form_count = $form_row['count'];
                          }
                          
                          // Calculate form completion percentage
                          $total_fields_query = $con->query("SELECT COUNT(*) as count FROM service_form_fields WHERE service_id='".$service['service_id']."'");
                          $total_fields = 0;
                          if($total_fields_query && $field_row = $total_fields_query->fetch_assoc()){
                              $total_fields = $field_row['count'];
                          }
                          $total_expected_fields = $total_fields * ($service['client_count'] ?: 1);
                          $completion_percentage = $total_expected_fields > 0 ? round(($form_count / $total_expected_fields) * 100) : 0;
                          
                          // Get documents count
                          $doc_count = 0;
                          $doc_sql = $con->query("SELECT COUNT(*) as count FROM req_doc WHERE sid='".$service['application_id']."'");
                          if($doc_sql && $doc_row = $doc_sql->fetch_assoc()){
                            $doc_count = $doc_row['count'];
                          }
                        ?>
                          <tr data-status="<?php echo $service['application_status']; ?>" 
                              data-service="<?php echo htmlspecialchars($service['service_title']); ?>"
                              data-date="<?php echo $service['application_date']; ?>">
                            <td><?php echo $sl++; ?></td>
                            <td>
                              <strong>#<?php echo $service['application_id']; ?></strong>
                              <br>
                              <small class="text-muted">Service: #<?php echo $service['service_id']; ?></small>
                            </td>
                            <td>
                              <span class="user-type-badge <?php echo $service['user_type'] == 'customer' ? 'customer-badge' : 'partner-badge'; ?>">
                                <?php echo ucfirst($service['user_type']); ?>
                              </span>
                              <?php if($service['user_type'] == 'partner' && $service['is_partner_price_applied']): ?>
                                <br><small class="text-success">Partner Price</small>
                              <?php endif; ?>
                            </td>
                            <td>
                              <div class="user-info">
                                <strong><?php echo htmlspecialchars($service['user_name']); ?></strong>
                                <?php if($service['user_type'] == 'partner' && $service['partner_business']): ?>
                                  <br><small><?php echo htmlspecialchars($service['partner_business']); ?></small>
                                <?php endif; ?>
                                <br>
                                <small>
                                  <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($service['user_email']); ?>
                                  <br>
                                  <i class="fas fa-phone"></i> <?php echo htmlspecialchars($service['user_contact']); ?>
                                </small>
                                <br>
                                <span class="badge badge-secondary">
                                  ID: #<?php echo $service['user_id']; ?>
                                </span>
                              </div>
                            </td>
                            <td>
                              <strong><?php echo htmlspecialchars($service['service_title']); ?></strong>
                              <br>
                              <small class="text-muted">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($service['category_name']); ?>
                              </small>
                              <br>
                              <div class="mt-2">
                                <?php if($service['user_type'] == 'partner' && $service['partner_price']): ?>
                                  <span class="badge badge-info">
                                    <i class="fas fa-tag"></i> Partner: ₹<?php echo $service['partner_price']; ?>
                                  </span>
                                <?php else: ?>
                                  <span class="badge badge-info">
                                    <i class="fas fa-tag"></i> Price: ₹<?php echo $service['original_price']; ?>
                                  </span>
                                <?php endif; ?>
                              </div>
                            </td>
                            <td>
                              <?php if($service['user_type'] == 'partner' && $service['client_count'] > 1): ?>
                                <span class="client-count-badge" title="Multiple clients">
                                  <?php echo $service['client_count']; ?> clients
                                </span>
                                <br>
                                <span class="badge badge-<?php echo $completion_percentage >= 100 ? 'success' : ($completion_percentage > 0 ? 'warning' : 'danger'); ?>">
                                  <?php echo $completion_percentage; ?>% complete
                                </span>
                              <?php else: ?>
                                <span class="text-muted">1 client</span>
                                <br>
                                <?php if($form_count > 0): ?>
                                  <span class="badge badge-success">Form filled</span>
                                <?php else: ?>
                                  <span class="badge badge-warning">Form pending</span>
                                <?php endif; ?>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php echo date('d M Y', strtotime($service['application_date'])); ?>
                              <br>
                              <small class="text-muted">
                                <?php 
                                $days = floor((time() - strtotime($service['application_date'])) / (60 * 60 * 24));
                                echo $days == 0 ? 'Today' : ($days . ' day(s) ago');
                                ?>
                              </small>
                            </td>
                            <td>
                              <?php if($service['application_status'] == '0'): ?>
                                <span class="badge badge-warning status-badge">Pending</span>
                              <?php elseif($service['application_status'] == '1'): ?>
                                <span class="badge badge-success status-badge">Completed</span>
                              <?php else: ?>
                                <span class="badge badge-secondary status-badge"><?php echo $service['application_status']; ?></span>
                              <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                              <!-- View Full Details -->
                              <a href="view_assigned_service.php?application_id=<?php echo $service['application_id']; ?>" 
                                 class="btn btn-sm btn-info mb-1" title="View Full Details">
                                <i class="fas fa-eye"></i> View
                              </a>
                              
                              <!-- View Form Data - Different for Partner vs Customer -->
                              <?php if($service['user_type'] == 'partner' && $service['client_count'] > 1): ?>
                                <a href="view_partner_clients_ca.php?application_id=<?php echo $service['application_id']; ?>" 
                                   class="btn btn-sm btn-primary mb-1" 
                                   title="View All Clients Data">
                                  <i class="fas fa-users"></i> Clients
                                </a>
                              <?php else: ?>
                                <a href="view_customer_form.php?application_id=<?php echo $service['application_id']; ?>" 
                                   class="btn btn-sm btn-primary mb-1" 
                                   title="View Form Data"
                                   <?php if($form_count == 0): ?>disabled<?php endif; ?>>
                                  <i class="fas fa-file-alt"></i> Form
                                </a>
                              <?php endif; ?>
                              
                              <!-- Update Status -->
                              <button type="button" 
                                      class="btn btn-sm btn-<?php echo $service['application_status'] == '0' ? 'success' : 'warning'; ?> mb-1"
                                      onclick="updateStatus(<?php echo $service['application_id']; ?>, <?php echo $service['application_status']; ?>)"
                                      title="<?php echo $service['application_status'] == '0' ? 'Mark as Completed' : 'Mark as Pending'; ?>">
                                <i class="fas fa-<?php echo $service['application_status'] == '0' ? 'check' : 'undo'; ?>"></i>
                                <?php echo $service['application_status'] == '0' ? 'Complete' : 'Reopen'; ?>
                              </button>
                              
                              <!-- Contact User -->
                              <div class="btn-group">
                                <a href="mailto:<?php echo $service['user_email']; ?>" 
                                   class="btn btn-sm btn-secondary" title="Email">
                                  <i class="fas fa-envelope"></i>
                                </a>
                                <a href="tel:<?php echo $service['user_contact']; ?>" 
                                   class="btn btn-sm btn-secondary" title="Call">
                                  <i class="fas fa-phone"></i>
                                </a>
                              </div>
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
                      <i class="fas fa-info-circle"></i> 
                      Showing <?php echo count($assigned_services); ?> assigned service(s)
                      <?php if($filter_user_type != 'all'): ?>
                        (Filtered by: <?php echo $filter_user_type; ?>)
                      <?php endif; ?>
                    </small>
                  </div>
                  <div class="col-md-6 text-right">
                    <?php if(!empty($assigned_services)): ?>
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-sm" onclick="printTable()">
                        <i class="fas fa-print"></i> Print
                      </button>
                      <button type="button" class="btn btn-default btn-sm" onclick="exportToExcel()">
                        <i class="fas fa-download"></i> Export
                      </button>
                      <button type="button" class="btn btn-default btn-sm" onclick="window.location.reload()">
                        <i class="fas fa-sync"></i> Refresh
                      </button>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Service Statistics -->
        <?php if(!empty($assigned_services)): ?>
        <div class="row mt-3">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i> Assignment Distribution</h3>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="assignmentChart" height="150"></canvas>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i> Monthly Assignments</h3>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="monthlyChart" height="150"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

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
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#servicesTable').DataTable({
        "pageLength": 10,
        "responsive": true,
        "autoWidth": false,
        "order": [[6, 'desc']], // Sort by date desc
        "language": {
            "search": "Search assignments:",
            "lengthMenu": "Show _MENU_ assignments per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ assignments",
            "emptyTable": "No assignments found"
        }
    });
    
    <?php if(!empty($assigned_services)): ?>
    initializeCharts();
    <?php endif; ?>
});

function applyFilters() {
    var status = $('#statusFilter').val();
    var service = $('#serviceFilter').val();
    var date = $('#dateFilter').val();
    
    $('#servicesTable tbody tr').each(function() {
        var show = true;
        var rowStatus = $(this).data('status');
        var rowService = $(this).data('service');
        var rowDate = $(this).data('date');
        
        if(status !== 'all' && rowStatus != status) {
            show = false;
        }
        
        if(service !== 'all' && rowService !== service) {
            show = false;
        }
        
        if(date && rowDate !== date) {
            show = false;
        }
        
        if(show) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

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

function printTable() {
    var printContent = document.getElementById('servicesTable').outerHTML;
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Assigned Services Report - <?php echo $ca_name; ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .print-footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                .user-type-badge { padding: 2px 6px; border-radius: 10px; font-size: 11px; }
                .customer-badge { background-color: #28a745; color: white; }
                .partner-badge { background-color: #007bff; color: white; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>Assigned Services Report</h2>
                <h3>CA: <?php echo $ca_name; ?> (ID: <?php echo $ca_id_number; ?>)</h3>
                <p>Generated on: <?php echo date('d M Y, h:i A'); ?></p>
                <p>Total Assignments: <?php echo $total_assignments; ?> | 
                   Customers: <?php echo $customer_count; ?> | 
                   Partners: <?php echo $partner_count; ?> |
                   Pending: <?php echo $pending_assignments; ?> | 
                   Completed: <?php echo $completed_assignments; ?></p>
            </div>
            
            ${printContent}
            
            <div class="print-footer">
                <p>Generated by Legal Taxation CA Panel</p>
                <p>Page generated on: <?php echo date('d M Y, h:i A'); ?></p>
            </div>
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}

function exportToExcel() {
    var table = document.getElementById("servicesTable");
    var html = table.outerHTML;
    
    var blob = new Blob([html], {type: 'application/vnd.ms-excel'});
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'assigned_services_<?php echo date('Y-m-d'); ?>.xls';
    
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    alert('Export started. The file will be downloaded shortly.');
}

<?php if(!empty($assigned_services)): ?>
function initializeCharts() {
    // Assignment Distribution Chart (Customer vs Partner)
    var assignmentCtx = document.getElementById('assignmentChart').getContext('2d');
    var assignmentChart = new Chart(assignmentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Customers (<?php echo $customer_count; ?>)', 'Partners (<?php echo $partner_count; ?>)'],
            datasets: [{
                data: [<?php echo $customer_count; ?>, <?php echo $partner_count; ?>],
                backgroundColor: ['#28a745', '#007bff'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Monthly Assignments Chart
    var monthlyData = {};
    <?php 
    foreach($assigned_services as $service) {
        $month = date('M Y', strtotime($service['application_date']));
        echo "if(!monthlyData['$month']) monthlyData['$month'] = 0;";
        echo "monthlyData['$month']++;";
    }
    ?>
    
    var monthLabels = Object.keys(monthlyData).slice(-6); // Last 6 months
    var monthValues = monthLabels.map(function(month) {
        return monthlyData[month] || 0;
    });
    
    var monthCtx = document.getElementById('monthlyChart').getContext('2d');
    var monthChart = new Chart(monthCtx, {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Assignments',
                data: monthValues,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}
<?php endif; ?>
</script>
</body>
</html>