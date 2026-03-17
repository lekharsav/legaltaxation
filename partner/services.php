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

// Get all services purchased by this partner
$services_query = $con->query("
    SELECT 
        a.id as application_id,
        a.sid as service_id,
        a.client_count,
        a.is_partner_price_applied,
        a.unit_price,
        a.total_amount,
        a.created as purchase_date,
        a.status as application_status,
        s.title as service_name,
        s.image as service_image,
        c.name as category_name,
        COUNT(DISTINCT sfr.id) as forms_submitted
    FROM apply a
    INNER JOIN service s ON a.sid = s.id
    INNER JOIN cate c ON s.cate = c.id
    LEFT JOIN service_form_responses sfr ON a.id = sfr.application_id
    WHERE a.partner_id = '$partner_id' 
    AND a.user_type = 'partner'
    GROUP BY a.id
    ORDER BY a.created DESC
");

// Calculate totals
$total_services = $services_query->num_rows;
$total_clients = 0;
$total_amount = 0;

while($service = $services_query->fetch_assoc()) {
    $total_clients += $service['client_count'];
    $total_amount += $service['total_amount'];
}

// Reset pointer for display
$services_query->data_seek(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Services - Partner Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  
  <style>
    .service-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 10px;
        overflow: hidden;
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .service-image {
        height: 150px;
        object-fit: cover;
        width: 100%;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .client-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 5px;
    }
    .stats-card {
        border-left: 4px solid;
    }
    .stats-card.border-primary { border-left-color: #007bff; }
    .stats-card.border-success { border-left-color: #28a745; }
    .stats-card.border-info { border-left-color: #17a2b8; }
    .stats-card.border-warning { border-left-color: #ffc107; }
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
            <h1 class="m-0">Purchased Services</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Services</li>
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
                <h3><?php echo $total_services; ?></h3>
                <p>Total Services</p>
              </div>
              <div class="icon">
                <i class="fas fa-shopping-cart"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $total_clients; ?></h3>
                <p>Total Clients</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>₹<?php echo number_format($total_amount, 2); ?></h3>
                <p>Total Spent</p>
              </div>
              <div class="icon">
                <i class="fas fa-rupee-sign"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>
                  <?php 
                  $avg_clients = $total_services > 0 ? round($total_clients / $total_services, 1) : 0;
                  echo $avg_clients;
                  ?>
                </h3>
                <p>Avg. Clients/Service</p>
              </div>
              <div class="icon">
                <i class="fas fa-chart-bar"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Services List -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Purchased Services</h3>
                <div class="card-tools">
                  <a href="../service.php" class="btn btn-primary btn-sm" target="_blank">
                    <i class="fas fa-plus mr-1"></i> Buy More Services
                  </a>
                </div>
              </div>
              <div class="card-body">
                <?php if($services_query->num_rows > 0): ?>
                <div class="table-responsive">
                  <table id="servicesTable" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Service</th>
                        <th>Category</th>
                        <th>Clients</th>
                        <th>Price Info</th>
                        <th>Forms</th>
                        <th>Purchase Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $counter = 1;
                      while($service = $services_query->fetch_assoc()): 
                      ?>
                      <tr>
                        <td><?php echo $counter++; ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <?php if($service['service_image']): ?>
                              <img src="../images/<?php echo $service['service_image']; ?>" 
                                   alt="<?php echo htmlspecialchars($service['service_name']); ?>" 
                                   class="img-circle mr-2" 
                                   style="width: 40px; height: 40px; object-fit: cover;">
                            <?php else: ?>
                              <div class="img-circle bg-secondary d-flex align-items-center justify-content-center mr-2" 
                                   style="width: 40px; height: 40px;">
                                <i class="fas fa-box text-white"></i>
                              </div>
                            <?php endif; ?>
                            <div>
                              <strong><?php echo htmlspecialchars($service['service_name']); ?></strong><br>
                              <small class="text-muted">ID: #<?php echo str_pad($service['application_id'], 6, '0', STR_PAD_LEFT); ?></small>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge badge-info"><?php echo htmlspecialchars($service['category_name']); ?></span>
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="client-avatar">
                              <?php echo $service['client_count']; ?>
                            </div>
                            <div>
                              <strong><?php echo $service['client_count']; ?></strong> clients
                            </div>
                          </div>
                        </td>
                        <td>
                          <div>
                            <small>Unit Price: <strong>₹<?php echo $service['unit_price']; ?></strong></small><br>
                            <small>Total: <strong>₹<?php echo $service['total_amount']; ?></strong></small><br>
                            <?php if($service['is_partner_price_applied']): ?>
                              <span class="badge badge-success">Partner Price Applied</span>
                            <?php else: ?>
                              <span class="badge badge-secondary">Regular Price</span>
                            <?php endif; ?>
                          </div>
                        </td>
                        <td>
                          <?php 
                          $form_completion = $service['client_count'] > 0 ? 
                            round(($service['forms_submitted'] / ($service['client_count'] * 5)) * 100) : 0; // Assuming 5 fields per client
                          ?>
                          <div class="progress" style="height: 20px;">
                            <div class="progress-bar <?php echo $form_completion >= 100 ? 'bg-success' : 'bg-info'; ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo min($form_completion, 100); ?>%">
                              <?php echo $form_completion; ?>%
                            </div>
                          </div>
                          <small class="text-muted"><?php echo $service['forms_submitted']; ?> fields filled</small>
                        </td>
                        <td>
                          <?php echo date('d M Y', strtotime($service['purchase_date'])); ?><br>
                          <small class="text-muted"><?php echo date('h:i A', strtotime($service['purchase_date'])); ?></small>
                        </td>
                        <td>
                          <?php
                          $status_badge = '';
                          switch($service['application_status']) {
                              case '0': $status_badge = '<span class="badge badge-warning">Pending</span>'; break;
                              case '1': $status_badge = '<span class="badge badge-info">Processing</span>'; break;
                              case '2': $status_badge = '<span class="badge badge-success">Completed</span>'; break;
                              case '3': $status_badge = '<span class="badge badge-danger">Rejected</span>'; break;
                              default: $status_badge = '<span class="badge badge-secondary">Unknown</span>';
                          }
                          echo $status_badge;
                          ?>
                        </td>
                        <td>
                          <div class="btn-group">
                            <a href="service-details.php?id=<?php echo $service['application_id']; ?>" 
                               class="btn btn-info btn-sm" 
                               title="View Details">
                              <i class="fas fa-eye"></i>
                            </a>
                            <a href="client-details.php?app_id=<?php echo $service['application_id']; ?>" 
                               class="btn btn-primary btn-sm" 
                               title="Client Details">
                              <i class="fas fa-users"></i>
                            </a>
                            <a href="../service-details.php?id=<?php echo $service['service_id']; ?>" 
                               class="btn btn-secondary btn-sm" 
                               target="_blank" 
                               title="View Service">
                              <i class="fas fa-external-link-alt"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                  <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                  <h4>No Services Purchased Yet</h4>
                  <p class="text-muted">You haven't purchased any services yet. Start by exploring our services.</p>
                  <a href="../service.php" class="btn btn-primary" target="_blank">
                    <i class="fas fa-plus mr-1"></i> Browse Services
                  </a>
                </div>
                <?php endif; ?>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div>
    </section>
  </div>

  <!-- Include Footer -->
  <?php include('footer.php'); ?>
</div>

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- DataTables & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script>
$(document).ready(function() {
  // Initialize DataTable
  $('#servicesTable').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "order": [[0, 'asc']],
    "language": {
      "search": "Search:",
      "lengthMenu": "Show _MENU_ entries",
      "info": "Showing _START_ to _END_ of _TOTAL_ entries",
      "paginate": {
        "first": "First",
        "last": "Last",
        "next": "Next",
        "previous": "Previous"
      }
    }
  });

  // Add export buttons
  new $.fn.dataTable.Buttons($('#servicesTable'), {
    buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });

  $('#servicesTable').on('draw.dt', function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
  
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
</body>
</html>