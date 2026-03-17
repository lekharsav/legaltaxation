<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}
 $currentDate = date("Y-m-d");
$givenDate = "2024-09-16";
 if (strtotime($currentDate) > strtotime($givenDate)) {
        // $initialValue = "Date Passed"; // Update value if current date is greater
      $sql = $con->query("select * from admin where id='$aid'");
        // echo"<script>window.location='index.php';</script>";
    }
    else
    {
        $sql = $con->query("select * from admin where id='$aid'");
    }
    
    //   $sql = $con->query("select * from admin where id='$aid'");

if($row = $sql->fetch_assoc()){
  $admin_image = $row["image"];
  $admin_name = $row["name"];
  $admin_cont = $row["contact"];
  $admin_email = $row["email"];
  $admin_pass = $row["password"];
}

// Get service ID from URL parameters
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;

// Redirect if no service ID is provided
if(!$service_id){
    echo "<script>alert('Please select a service first!'); window.location='all_service.php';</script>";
    exit();
}

// Get service details for display
$service_details = [];
$sql_service = $con->query("SELECT * FROM service WHERE id='$service_id'");
if($service_row = $sql_service->fetch_assoc()){
    $service_details = $service_row;
}

// Get category name
$category_name = "";
if(!empty($service_details)){
    $sql_cate = $con->query("SELECT name FROM cate WHERE id='".$service_details['cate']."'");
    if($cate_row = $sql_cate->fetch_assoc()){
        $category_name = $cate_row['name'];
    }
}

// Filter by user type (customer/partner)
$filter_user_type = isset($_GET['user_type']) ? $_GET['user_type'] : 'all';

// Build query with filters
$where_clause = "WHERE a.sid='$service_id'";
if($filter_user_type != 'all'){
    $where_clause .= " AND a.user_type = '$filter_user_type'";
}

// Get all applications for this service with user type filter
$applications = [];
$total_applications = 0;
$pending_applications = 0;
$assigned_applications = 0;
$customer_count = 0;
$partner_count = 0;

$sql = $con->query("
    SELECT a.*, 
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
           ca.name as ca_name,
           (SELECT COUNT(*) FROM service_form_responses WHERE application_id = a.id) as forms_filled
    FROM apply a
    LEFT JOIN customer c ON a.user_type = 'customer' AND a.cid = c.id
    LEFT JOIN partner p ON a.user_type = 'partner' AND a.partner_id = p.id
    LEFT JOIN ca ON a.send_to = ca.id
    $where_clause
    ORDER BY a.created DESC
");

if($sql){
    $total_applications = $sql->num_rows;
    while($row = $sql->fetch_assoc()){
        $applications[] = $row;
        
        if($row['user_type'] == 'customer'){
            $customer_count++;
        } elseif($row['user_type'] == 'partner'){
            $partner_count++;
        }
        
        if($row['send_to']){
            $assigned_applications++;
        } else {
            $pending_applications++;
        }
    }
}

// Get service form fields to check required fields
$form_fields_query = $con->query("SELECT * FROM service_form_fields WHERE service_id='$service_id'");
$total_form_fields = $form_fields_query->num_rows;
$required_fields = 0;
while($field = $form_fields_query->fetch_assoc()){
    if($field['is_required']){
        $required_fields++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Service Form Submissions - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  
  <style>
    .info-box-content {
      padding-left: 10px;
      padding-right: 10px;
    }
    .info-box-number {
      font-size: 1.8rem;
      font-weight: bold;
    }
    .application-status {
      font-size: 0.8rem;
      padding: 3px 8px;
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
    .form-progress {
      width: 80px;
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
                <i class="mdi mdi-file-document-multiple"></i>
              </span>&nbsp;Form Submissions
              <?php if(!empty($service_details)): ?>
                <small class="text-muted">- <?php echo htmlspecialchars($service_details['title']); ?></small>
              <?php endif; ?>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="all_service.php">Services</a></li>
              <li class="breadcrumb-item active">Form Submissions</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      
        <!-- Service Info Box -->
        <?php if(!empty($service_details)): ?>
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <h4><i class="fas fa-copy"></i> <?php echo htmlspecialchars($service_details['title']); ?></h4>
                    <p class="mb-1"><strong>Category:</strong> <?php echo htmlspecialchars($category_name); ?></p>
                    <p class="mb-1"><strong>Regular Price:</strong> ₹<?php echo $service_details['o_price']; ?></p>
                    <?php if($service_details['partner_o_price']): ?>
                    <p class="mb-1"><strong>Partner Price:</strong> ₹<?php echo $service_details['partner_o_price']; ?> 
                      (Min: <?php echo $service_details['min_clients_for_partner']; ?> clients)
                    </p>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6 text-right">
                    <!-- User Type Filter -->
                    <div class="btn-group mb-3">
                      <a href="?service_id=<?php echo $service_id; ?>&user_type=all" 
                         class="btn btn-sm <?php echo $filter_user_type == 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                        All (<?php echo $total_applications; ?>)
                      </a>
                      <a href="?service_id=<?php echo $service_id; ?>&user_type=customer" 
                         class="btn btn-sm <?php echo $filter_user_type == 'customer' ? 'btn-success' : 'btn-outline-success'; ?>">
                        Customers (<?php echo $customer_count; ?>)
                      </a>
                      <a href="?service_id=<?php echo $service_id; ?>&user_type=partner" 
                         class="btn btn-sm <?php echo $filter_user_type == 'partner' ? 'btn-info' : 'btn-outline-info'; ?>">
                        Partners (<?php echo $partner_count; ?>)
                      </a>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div>
                      <a href="leads.php?sid=<?php echo $service_id; ?>&cate=<?php echo $service_details['cate']; ?>" 
                         class="btn btn-warning btn-sm">
                        <i class="fas fa-users"></i> View Leads
                      </a>
                      <a href="service_form_fields.php?service_id=<?php echo $service_id; ?>" 
                         class="btn btn-info btn-sm">
                        <i class="fas fa-cog"></i> Form Fields
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $total_applications; ?></h3>
                <p>Total Submissions</p>
                <div class="small">
                  <span class="badge badge-success">Customers: <?php echo $customer_count; ?></span>
                  <span class="badge badge-primary">Partners: <?php echo $partner_count; ?></span>
                </div>
              </div>
              <div class="icon">
                <i class="fas fa-file-alt"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $pending_applications; ?></h3>
                <p>Pending Review</p>
                <div class="small">
                  Needs CA Assignment
                </div>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $assigned_applications; ?></h3>
                <p>Assigned to CA</p>
                <div class="small">
                  In Progress
                </div>
              </div>
              <div class="icon">
                <i class="fas fa-user-check"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <?php 
            $total_revenue = 0;
            foreach($applications as $app){
                $total_revenue += $app['total_amount'];
            }
            ?>
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>₹<?php echo number_format($total_revenue, 2); ?></h3>
                <p>Total Revenue</p>
                <div class="small">
                  From all submissions
                </div>
              </div>
              <div class="icon">
                <i class="fas fa-rupee-sign"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Submissions Table -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-list-alt mr-2"></i> Form Submissions
                  <?php if($filter_user_type != 'all'): ?>
                    <small class="text-muted">(<?php echo ucfirst($filter_user_type); ?>s only)</small>
                  <?php endif; ?>
                  <div class="float-right">
                    <a href="all_service.php" class="btn btn-secondary btn-sm">
                      <i class="fa fa-arrow-left"></i> Back to Services
                    </a>
                    <?php if($total_applications > 0): ?>
                    <button class="btn btn-info btn-sm" onclick="exportToExcel()">
                      <i class="fa fa-download"></i> Export
                    </button>
                    <?php endif; ?>
                  </div>
                </h3>
              </div>
              <div class="card-body">
                <?php if(empty($applications)): ?>
                  <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4>No Form Submissions Yet</h4>
                    <p class="text-muted">No <?php echo $filter_user_type == 'all' ? '' : $filter_user_type; ?> submissions found for this service.</p>
                    <a href="leads.php?sid=<?php echo $service_id; ?>&cate=<?php echo $service_details['cate']; ?>" 
                       class="btn btn-primary">
                      <i class="fa fa-eye"></i> Check for New Applications
                    </a>
                  </div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="submissionsTable">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Application ID</th>
                          <th>User Type</th>
                          <th>User Name</th>
                          <th>Contact</th>
                          <th>Applied Date</th>
                          <th>Clients</th>
                          <th>Form Progress</th>
                          <th>Price Details</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $sl = 1;
                        foreach($applications as $app): 
                          $has_form_data = $app['forms_filled'] > 0;
                          
                          // Calculate form completion percentage
                          $completion_percentage = 0;
                          if($total_form_fields > 0 && $app['client_count'] > 0){
                              $total_fields_needed = $total_form_fields * $app['client_count'];
                              $completion_percentage = $total_fields_needed > 0 ? 
                                  round(($app['forms_filled'] / $total_fields_needed) * 100) : 0;
                          }
                          
                          // Determine form status color
                          $progress_color = 'bg-danger';
                          if($completion_percentage >= 100){
                              $progress_color = 'bg-success';
                          } elseif($completion_percentage >= 50){
                              $progress_color = 'bg-warning';
                          } elseif($completion_percentage > 0){
                              $progress_color = 'bg-info';
                          }
                          
                          // Check if form is complete (all required fields filled)
                          $is_form_complete = false;
                          if($required_fields > 0 && $app['forms_filled'] >= ($required_fields * $app['client_count'])){
                              $is_form_complete = true;
                          }
                        ?>
                          <tr>
                            <td><?php echo $sl++; ?></td>
                            <td>
                              <span class="badge badge-dark">#<?php echo $app['id']; ?></span>
                              <?php if($app['user_type'] == 'partner' && $app['client_count'] > 1): ?>
                                <br><small class="text-muted">Multiple Clients</small>
                              <?php endif; ?>
                            </td>
                            <td>
                              <span class="user-type-badge <?php echo $app['user_type'] == 'customer' ? 'customer-badge' : 'partner-badge'; ?>">
                                <?php echo ucfirst($app['user_type']); ?>
                              </span>
                            </td>
                            <td>
                              <strong><?php echo htmlspecialchars($app['user_name'] ?? 'N/A'); ?></strong>
                              <?php if($app['user_email']): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($app['user_email']); ?></small>
                              <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($app['user_contact'] ?? 'N/A'); ?></td>
                            <td>
                              <?php echo date('d M Y', strtotime($app['created'])); ?><br>
                              <small class="text-muted"><?php echo date('h:i A', strtotime($app['created'])); ?></small>
                            </td>
                            <td>
                              <?php if($app['user_type'] == 'partner' && $app['client_count'] > 1): ?>
                                <span class="client-count-badge" title="Multiple clients">
                                  <?php echo $app['client_count']; ?> clients
                                </span>
                                <?php if($app['is_partner_price_applied']): ?>
                                  <br><small class="text-success">Partner price applied</small>
                                <?php endif; ?>
                              <?php else: ?>
                                <span class="text-muted">1 client</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <div class="d-flex align-items-center">
                                <div class="form-progress mr-2">
                                  <div class="progress" style="height: 10px;">
                                    <div class="progress-bar <?php echo $progress_color; ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo min($completion_percentage, 100); ?>%">
                                    </div>
                                  </div>
                                </div>
                                <div>
                                  <small><?php echo $completion_percentage; ?>%</small><br>
                                  <small class="text-muted">
                                    <?php echo $app['forms_filled']; ?> fields
                                    <?php if($is_form_complete): ?>
                                      <span class="text-success">✓</span>
                                    <?php endif; ?>
                                  </small>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div>
                                <small>Unit: <strong>₹<?php echo $app['unit_price']; ?></strong></small><br>
                                <small>Total: <strong>₹<?php echo $app['total_amount']; ?></strong></small><br>
                                <?php if($app['user_type'] == 'partner' && $app['client_count'] > 1): ?>
                                  <small class="text-muted">
                                    (<?php echo $app['client_count']; ?> × ₹<?php echo $app['unit_price']; ?>)
                                  </small>
                                <?php endif; ?>
                              </div>
                            </td>
                            <td>
                              <?php if($app['send_to']): ?>
                                <span class="badge badge-success">Assigned</span><br>
                                <small class="text-muted">To: <?php echo htmlspecialchars($app['ca_name'] ?? 'CA'); ?></small>
                              <?php else: ?>
                                <span class="badge badge-warning">Pending</span>
                              <?php endif; ?>
                              <?php if($app['status'] == '1'): ?>
                                <br><span class="badge badge-info">Approved</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <!-- View Form Button -->
                              <a href="view_customer_form_data.php?application_id=<?php echo $app['id']; ?>" 
                                 class="btn btn-sm btn-info mb-1" 
                                 title="View Form Data">
                                <i class="fa fa-eye"></i> View
                                <?php if(!$has_form_data): ?>
                                  <span class="badge badge-warning ml-1">No Data</span>
                                <?php endif; ?>
                              </a>
                              
                              <!-- Client Details for Partners -->
                              <?php if($app['user_type'] == 'partner' && $app['client_count'] > 1): ?>
                                <a href="view_partner_clients.php?application_id=<?php echo $app['id']; ?>" 
                                   class="btn btn-sm btn-primary mb-1" 
                                   title="View All Clients">
                                  <i class="fa fa-users"></i> Clients
                                </a>
                              <?php endif; ?>
                              
                              <!-- Assign to CA -->
                              <?php if(!$app['send_to']): ?>
                                <button class="btn btn-sm btn-success mb-1" 
                                        onclick="assignToCA(<?php echo $app['id']; ?>)"
                                        title="Assign to CA">
                                  <i class="fa fa-user-plus"></i> Assign
                                </button>
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
                    <div class="small text-muted">
                      <i class="fas fa-info-circle"></i> 
                      Service ID: <?php echo $service_id; ?> | 
                      Showing <?php echo $total_applications; ?> submission(s) |
                      Required fields per client: <?php echo $required_fields; ?>
                    </div>
                  </div>
                  <div class="col-md-6 text-right">
                    <?php if($total_applications > 0): ?>
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-sm" onclick="printTable()">
                        <i class="fa fa-print"></i> Print
                      </button>
                      <button type="button" class="btn btn-default btn-sm" onclick="window.location.reload()">
                        <i class="fa fa-sync"></i> Refresh
                      </button>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#submissionsTable').DataTable({
        "pageLength": 25,
        "responsive": true,
        "autoWidth": false,
        "ordering": true,
        "order": [[0, 'asc']],
        "language": {
            "search": "Search submissions:",
            "lengthMenu": "Show _MENU_ submissions per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ submissions",
            "emptyTable": "No form submissions found",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });
});

function printTable() {
    var printContent = document.getElementById('submissionsTable').outerHTML;
    var originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>Form Submissions - <?php echo htmlspecialchars($service_details['title']); ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                .print-footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                .badge { padding: 3px 8px; border-radius: 3px; font-size: 12px; }
                .badge-dark { background-color: #343a40; color: white; }
                .badge-success { background-color: #28a745; color: white; }
                .badge-warning { background-color: #ffc107; color: black; }
                .badge-info { background-color: #17a2b8; color: white; }
                .user-type-badge { padding: 2px 6px; border-radius: 10px; font-size: 11px; }
                .customer-badge { background-color: #28a745; color: white; }
                .partner-badge { background-color: #007bff; color: white; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>Form Submissions Report</h2>
                <h3><?php echo htmlspecialchars($service_details['title']); ?></h3>
                <p>Service ID: <?php echo $service_id; ?> | Generated on: <?php echo date('d M Y, h:i A'); ?></p>
                <p>Total Submissions: <?php echo $total_applications; ?> | 
                   Customers: <?php echo $customer_count; ?> | 
                   Partners: <?php echo $partner_count; ?> | 
                   Pending: <?php echo $pending_applications; ?> | 
                   Assigned: <?php echo $assigned_applications; ?></p>
            </div>
            
            ${printContent}
            
            <div class="print-footer">
                <p>Generated by Legal Taxation Admin Panel</p>
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
    // Simple table export
    var table = document.getElementById("submissionsTable");
    var html = table.outerHTML;
    
    // Create a Blob with the table data
    var blob = new Blob([html], {type: 'application/vnd.ms-excel'});
    
    // Create a temporary anchor element
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'form_submissions_<?php echo $service_id; ?>_<?php echo date('Y-m-d'); ?>.xls';
    
    // Trigger download
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    
    // Show success message
    alert('Export started. The file will be downloaded shortly.');
}

function assignToCA(applicationId) {
    if(confirm('Assign this application to a Chartered Accountant?')) {
        window.location.href = 'leads.php?assign=' + applicationId + '&sid=<?php echo $service_id; ?>';
    }
}
</script>
</body>
</html>