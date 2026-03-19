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
  <title>My Services - Partner Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter (modern, clean) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE 3) - we keep for layout but override heavily -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables (keep for functionality, but we'll style it) -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <!-- Modern Styles -->
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

    /* KPI Cards */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    @media (max-width: 992px) {
      .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 576px) {
      .kpi-grid {
        grid-template-columns: 1fr;
      }
    }

    .kpi-card {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      border: 1px solid #f1f5f9;
      transition: transform 0.2s, box-shadow 0.2s;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }

    .kpi-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
      border-color: #e2e8f0;
    }

    .kpi-icon {
      width: 56px;
      height: 56px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .kpi-icon i {
      font-size: 1.75rem;
    }

    .kpi-content {
      flex: 1;
    }

    .kpi-label {
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      margin-bottom: 0.25rem;
      font-weight: 500;
    }

    .kpi-value {
      font-size: 2rem;
      font-weight: 700;
      color: #0f172a;
      line-height: 1.2;
      margin-bottom: 0.25rem;
    }

    .kpi-trend {
      font-size: 0.8rem;
      color: #10b981;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    /* Main Card */
    .main-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .card-header-custom {
      padding: 1.25rem 1.75rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-header-custom h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .card-header-custom h3 i {
      color: #3b82f6;
    }

    .card-body-custom {
      padding: 1.5rem 1.75rem;
    }

    /* DataTable Styling */
    .table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 0.5rem;
      margin: 0;
    }

    .table thead th {
      border: none;
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      background: #f8fafc;
      padding: 0.75rem 1rem;
    }

    .table tbody tr {
      background: #ffffff;
      border-radius: 1rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
      transition: all 0.2s;
    }

    .table tbody tr:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .table tbody td {
      border: none;
      padding: 1rem;
      vertical-align: middle;
      font-size: 0.9rem;
      color: #334155;
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

    .badge-info {
      background: #e0f2fe;
      color: #0284c7;
    }

    .badge-success {
      background: #d1fae5;
      color: #065f46;
    }

    .badge-warning {
      background: #fff3cd;
      color: #856404;
    }

    .badge-danger {
      background: #fee2e2;
      color: #991b1b;
    }

    .badge-secondary {
      background: #f1f5f9;
      color: #334155;
    }

    /* Client Avatar */
    .client-avatar {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      background: #e0f2fe;
      color: #0284c7;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 1rem;
    }

    /* Progress Bar */
    .progress-sm {
      height: 0.5rem;
      border-radius: 1rem;
      background: #e9ecef;
    }

    .progress-bar {
      border-radius: 1rem;
    }

    /* Buttons */
    .btn-group-sm > .btn, .btn-sm {
      border-radius: 0.5rem;
      padding: 0.4rem 0.75rem;
      font-size: 0.8rem;
    }

    .btn-info {
      background: #e0f2fe;
      border: none;
      color: #0284c7;
    }
    .btn-info:hover {
      background: #bae6fd;
      color: #0369a1;
    }

    .btn-primary {
      background: #3b82f6;
      border: none;
      color: white;
    }
    .btn-primary:hover {
      background: #2563eb;
    }

    .btn-secondary {
      background: #f1f5f9;
      border: none;
      color: #334155;
    }
    .btn-secondary:hover {
      background: #e2e8f0;
    }

    /* DataTable controls */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
      border: 1px solid #e2e8f0;
      border-radius: 0.5rem;
      padding: 0.4rem;
      font-size: 0.9rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
      border-radius: 0.5rem;
      margin: 0 0.2rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: #3b82f6 !important;
      color: white !important;
      border: none;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
    }
    .empty-state i {
      font-size: 4rem;
      color: #cbd5e1;
      margin-bottom: 1.5rem;
    }
    .empty-state h4 {
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 0.75rem;
    }
    .empty-state p {
      color: #64748b;
      margin-bottom: 2rem;
    }
    .btn-empty {
      background: #3b82f6;
      color: white;
      border: none;
      border-radius: 100px;
      padding: 0.75rem 2rem;
      font-weight: 500;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6">
            <div class="page-header">
              <h1>
                <i class="fas fa-shopping-bag"></i>
                My Services
              </h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Services</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- KPI Cards -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
              <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Services</div>
              <div class="kpi-value"><?php echo $total_services; ?></div>
              <div class="kpi-trend"><i class="fas fa-arrow-up"></i> All time</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #dcfce7; color: #166534;">
              <i class="fas fa-users"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Clients</div>
              <div class="kpi-value"><?php echo $total_clients; ?></div>
              <div class="kpi-trend"><i class="fas fa-user-plus"></i> Across services</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #fef9c3; color: #a16207;">
              <i class="fas fa-indian-rupee-sign"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Spent</div>
              <div class="kpi-value">₹<?php echo number_format($total_amount, 2); ?></div>
              <div class="kpi-trend"><i class="fas fa-credit-card"></i> Lifetime</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
              <i class="fas fa-chart-bar"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Avg. Clients/Service</div>
              <div class="kpi-value">
                <?php 
                $avg_clients = $total_services > 0 ? round($total_clients / $total_services, 1) : 0;
                echo $avg_clients;
                ?>
              </div>
              <div class="kpi-trend"><i class="fas fa-chart-line"></i> Per service</div>
            </div>
          </div>
        </div>

        <!-- Services List Card -->
        <div class="main-card">
          <div class="card-header-custom">
            <h3>
              <i class="fas fa-history"></i> Purchased Services
            </h3>
            <a href="../service.php" class="btn-solid-modern" style="background: #3b82f6; color: white; padding: 0.6rem 1.25rem; border-radius: 100px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;" target="_blank">
              <i class="fas fa-plus"></i> Buy More
            </a>
          </div>
          <div class="card-body-custom">
            <?php if($services_query->num_rows > 0): ?>
              <div class="table-responsive">
                <table id="servicesTable" class="table">
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
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                          <?php if($service['service_image']): ?>
                            <img src="../images/<?php echo $service['service_image']; ?>" 
                                 alt="<?php echo htmlspecialchars($service['service_name']); ?>" 
                                 style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover;">
                          <?php else: ?>
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                              <i class="fas fa-box" style="color: #64748b;"></i>
                            </div>
                          <?php endif; ?>
                          <div>
                            <strong style="color: #0f172a;"><?php echo htmlspecialchars($service['service_name']); ?></strong><br>
                            <small style="color: #64748b;">#<?php echo str_pad($service['application_id'], 6, '0', STR_PAD_LEFT); ?></small>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge-modern badge-info"><?php echo htmlspecialchars($service['category_name']); ?></span>
                      </td>
                      <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                          <div class="client-avatar">
                            <?php echo $service['client_count']; ?>
                          </div>
                          <span><?php echo $service['client_count']; ?> clients</span>
                        </div>
                      </td>
                      <td>
                        <div>
                          <small style="color: #64748b;">Unit: <strong style="color: #0f172a;">₹<?php echo $service['unit_price']; ?></strong></small><br>
                          <small style="color: #64748b;">Total: <strong style="color: #0f172a;">₹<?php echo $service['total_amount']; ?></strong></small><br>
                          <?php if($service['is_partner_price_applied']): ?>
                            <span class="badge-modern badge-success">Partner Price</span>
                          <?php else: ?>
                            <span class="badge-modern badge-secondary">Regular</span>
                          <?php endif; ?>
                        </div>
                      </td>
                      <td>
                        <?php 
                        $form_completion = $service['client_count'] > 0 ? 
                          round(($service['forms_submitted'] / ($service['client_count'] * 5)) * 100) : 0; // Assuming 5 fields per client
                        ?>
                        <div style="width: 100px;">
                          <div class="progress progress-sm">
                            <div class="progress-bar <?php echo $form_completion >= 100 ? 'bg-success' : 'bg-info'; ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo min($form_completion, 100); ?>%">
                            </div>
                          </div>
                          <small style="color: #64748b;"><?php echo $service['forms_submitted']; ?> fields</small>
                        </div>
                      </td>
                      <td>
                        <div>
                          <strong><?php echo date('d M Y', strtotime($service['purchase_date'])); ?></strong><br>
                          <small style="color: #64748b;"><?php echo date('h:i A', strtotime($service['purchase_date'])); ?></small>
                        </div>
                      </td>
                      <td>
                        <?php
                        $status_badge = '';
                        switch($service['application_status']) {
                            case '0': $status_badge = '<span class="badge-modern badge-warning">Pending</span>'; break;
                            case '1': $status_badge = '<span class="badge-modern badge-info">Processing</span>'; break;
                            case '2': $status_badge = '<span class="badge-modern badge-success">Completed</span>'; break;
                            case '3': $status_badge = '<span class="badge-modern badge-danger">Rejected</span>'; break;
                            default: $status_badge = '<span class="badge-modern badge-secondary">Unknown</span>';
                        }
                        echo $status_badge;
                        ?>
                      </td>
                      <td>
                        <div class="btn-group" style="gap: 0.25rem;">
                          <a href="service-details.php?id=<?php echo $service['application_id']; ?>" 
                             class="btn btn-sm btn-info" 
                             title="View Details">
                            <i class="fas fa-eye"></i>
                          </a>
                          <a href="client-details.php?app_id=<?php echo $service['application_id']; ?>" 
                             class="btn btn-sm btn-primary" 
                             title="Client Details">
                            <i class="fas fa-users"></i>
                          </a>
                          <a href="../service-details.php?id=<?php echo $service['service_id']; ?>" 
                             class="btn btn-sm btn-secondary" 
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
              <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h4>No Services Purchased Yet</h4>
                <p>You haven't purchased any services yet. Start by exploring our services.</p>
                <a href="../service.php" class="btn-empty" target="_blank">
                  <i class="fas fa-plus"></i> Browse Services
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div><!-- /.container-fluid -->
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
  var table = $('#servicesTable').DataTable({
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
    },
    "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    "buttons": [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ]
  });

  // Add export buttons container
  table.buttons().container()
    .appendTo('#servicesTable_wrapper .col-md-6:eq(0)');
  
  // Re-style DataTable elements after initialization
  $('#servicesTable_wrapper .dataTables_filter input').css({
    'border': '1px solid #e2e8f0',
    'border-radius': '0.5rem',
    'padding': '0.4rem 0.75rem'
  });
  
  $('#servicesTable_wrapper .dataTables_length select').css({
    'border': '1px solid #e2e8f0',
    'border-radius': '0.5rem',
    'padding': '0.4rem'
  });
});
</script>
</body>
</html>