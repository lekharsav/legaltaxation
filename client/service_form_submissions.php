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
$service_id = isset($_GET['service_id']) ? $_GET['service_id'] : 0;

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

// Get all applications for this service
$applications = [];
$total_applications = 0;
$pending_applications = 0;
$assigned_applications = 0;

$sql = $con->query("SELECT a.*, c.name as customer_name, c.contact, c.email, 
                    ca.name as ca_name
                    FROM apply a 
                    LEFT JOIN customer c ON a.cid = c.id 
                    LEFT JOIN ca ON a.send_to = ca.id 
                    WHERE a.sid='$service_id' 
                    ORDER BY a.created DESC");

if($sql){
    $total_applications = $sql->num_rows;
    while($row = $sql->fetch_assoc()){
        $applications[] = $row;
        if($row['send_to']){
            $assigned_applications++;
        } else {
            $pending_applications++;
        }
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
                  <div class="col-md-8">
                    <h4><i class="fas fa-copy"></i> <?php echo htmlspecialchars($service_details['title']); ?></h4>
                    <p class="mb-1"><strong>Category:</strong> <?php echo htmlspecialchars($category_name); ?></p>
                    <p class="mb-1"><strong>Price:</strong> ₹<?php echo $service_details['o_price']; ?> 
                      <small class="text-muted">(Market: ₹<?php echo $service_details['m_price']; ?>)</small>
                    </p>
                    <?php if($service_details['s_des']): ?>
                    <p class="mb-0"><strong>Description:</strong> <?php echo htmlspecialchars(substr($service_details['s_des'], 0, 150)); ?>...</p>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-4 text-right">
                    <a href="leads.php?sid=<?php echo $service_id; ?>&cate=<?php echo $service_details['cate']; ?>" 
                       class="btn btn-warning btn-lg">
                      <i class="fas fa-users"></i> View Leads
                    </a>
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
              </div>
              <div class="icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <a href="#" class="small-box-footer">All Applications <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $pending_applications; ?></h3>
                <p>Pending Review</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
              <a href="#" class="small-box-footer">Needs Attention <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $assigned_applications; ?></h3>
                <p>Assigned to CA</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-check"></i>
              </div>
              <a href="#" class="small-box-footer">In Progress <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>₹<?php echo $total_applications * $service_details['o_price']; ?></h3>
                <p>Total Value</p>
              </div>
              <div class="icon">
                <i class="fas fa-rupee-sign"></i>
              </div>
              <a href="#" class="small-box-footer">Revenue <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Form Submissions Table -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-list-alt mr-2"></i> Customer Form Submissions
                  <div class="float-right">
                    <a href="all_service.php" class="btn btn-secondary btn-sm">
                      <i class="fa fa-arrow-left"></i> Back to Services
                    </a>
                    <?php if($total_applications > 0): ?>
                    <button class="btn btn-info btn-sm" onclick="exportToExcel()">
                      <i class="fa fa-download"></i> Export Excel
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
                    <p class="text-muted">No customers have submitted forms for this service yet.</p>
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
                          <th>Customer Name</th>
                          <th>Contact</th>
                          <th>Email</th>
                          <th>Applied Date</th>
                          <th>Status</th>
                          <th>Assigned To</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $sl = 1;
                        foreach($applications as $app): 
                          $has_form_data = false;
                          // Check if this application has form data
                          $check_sql = $con->query("SELECT COUNT(*) as count FROM service_form_responses WHERE application_id='".$app['id']."'");
                          if($check_row = $check_sql->fetch_assoc()){
                            $has_form_data = $check_row['count'] > 0;
                          }
                        ?>
                          <tr>
                            <td><?php echo $sl++; ?></td>
                            <td>
                              <span class="badge badge-primary">#<?php echo $app['id']; ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($app['customer_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($app['contact'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($app['email'] ?? 'N/A'); ?></td>
                            <td><?php echo date('d M Y', strtotime($app['created'])); ?></td>
                            <td>
                              <?php if($app['send_to']): ?>
                                <span class="badge badge-success">Assigned</span>
                              <?php else: ?>
                                <span class="badge badge-warning">Pending</span>
                              <?php endif; ?>
                              <?php if($app['status'] == '1'): ?>
                                <span class="badge badge-info ml-1">Approved</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if($app['ca_name']): ?>
                                <?php echo htmlspecialchars($app['ca_name']); ?>
                              <?php else: ?>
                                <span class="text-muted">Not Assigned</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <!-- View Form Button -->
                              <a href="view_customer_form_data.php?application_id=<?php echo $app['id']; ?>" 
                                 class="btn btn-sm btn-info" 
                                 title="View Form Data"
                                 <?php if(!$has_form_data): ?>disabled<?php endif; ?>>
                                <i class="fa fa-eye"></i> View Form
                                <?php if(!$has_form_data): ?>
                                  <span class="badge badge-warning ml-1">No Data</span>
                                <?php endif; ?>
                              </a>
                              
                              <!-- View in Leads -->
                              <a href="leads.php?sid=<?php echo $service_id; ?>&cate=<?php echo $service_details['cate']; ?>" 
                                 class="btn btn-sm btn-secondary" title="View in Leads">
                                <i class="fa fa-list"></i> Leads
                              </a>
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
                      Showing <?php echo $total_applications; ?> submission(s)
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

        <!-- Additional Information -->
        <?php if(!empty($applications)): ?>
        <div class="row mt-3">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i> Submission Statistics</h3>
              </div>
              <div class="card-body">
                <div class="chart-container">
                  <canvas id="submissionChart" height="150"></canvas>
                </div>
                <div class="mt-3">
                  <small class="text-muted">
                    <i class="fas fa-calendar-alt"></i> 
                    Last submission: <?php echo date('d M Y', strtotime($applications[0]['created'])); ?>
                  </small>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cogs mr-2"></i> Quick Actions</h3>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <a href="edit_service.php?id=<?php echo $service_id; ?>" class="btn btn-outline-primary">
                    <i class="fa fa-edit"></i> Edit Service Details
                  </a>
                  <a href="service_form_fields.php?service_id=<?php echo $service_id; ?>" class="btn btn-outline-success">
                    <i class="fa fa-plus"></i> Configure Form Fields
                  </a>
                  <button type="button" class="btn btn-outline-info" onclick="sendBulkReminders()">
                    <i class="fa fa-bell"></i> Send Reminders to Pending
                  </button>
                  <a href="dashboard.php" class="btn btn-outline-secondary">
                    <i class="fa fa-dashboard"></i> Back to Dashboard
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

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

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    if($.fn.DataTable){
        $('#submissionsTable').DataTable({
            "pageLength": 25,
            "responsive": true,
            "autoWidth": false,
            "ordering": true,
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
    }
    
    // Initialize chart if needed
    <?php if(!empty($applications)): ?>
    initializeChart();
    <?php endif; ?>
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
                .badge-primary { background-color: #007bff; color: white; }
                .badge-success { background-color: #28a745; color: white; }
                .badge-warning { background-color: #ffc107; color: black; }
                .badge-info { background-color: #17a2b8; color: white; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>Form Submissions Report</h2>
                <h3><?php echo htmlspecialchars($service_details['title']); ?></h3>
                <p>Service ID: <?php echo $service_id; ?> | Generated on: <?php echo date('d M Y, h:i A'); ?></p>
                <p>Total Submissions: <?php echo $total_applications; ?> | 
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

function sendBulkReminders() {
    if(confirm('Send reminder emails to all pending applicants?')) {
        // You would typically make an AJAX call here
        // For now, just show a message
        alert('Reminder feature would be implemented here. This would send emails to all pending applicants.');
        
        // Example AJAX implementation:
        /*
        $.ajax({
            url: 'send_bulk_reminders.php',
            type: 'POST',
            data: { service_id: <?php echo $service_id; ?> },
            success: function(response) {
                alert('Reminders sent successfully!');
                window.location.reload();
            },
            error: function() {
                alert('Error sending reminders. Please try again.');
            }
        });
        */
    }
}

<?php if(!empty($applications)): ?>
function initializeChart() {
    var ctx = document.getElementById('submissionChart').getContext('2d');
    
    // Group applications by month
    var monthlyData = {};
    <?php 
    foreach($applications as $app) {
        $month = date('M Y', strtotime($app['created']));
        echo "if(!monthlyData['$month']) monthlyData['$month'] = 0;";
        echo "monthlyData['$month']++;";
    }
    ?>
    
    var labels = Object.keys(monthlyData).slice(-6); // Last 6 months
    var data = labels.map(function(month) {
        return monthlyData[month] || 0;
    });
    
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Form Submissions',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Number of Submissions'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });
}
<?php endif; ?>
</script>
</body>
</html>