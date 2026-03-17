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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Service Forms - Legal Taxation</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <style>
    .service-card {
      transition: all 0.3s ease;
      cursor: pointer;
    }
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .field-count {
      font-size: 0.8rem;
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
                <i class="mdi mdi-form-select"></i>
              </span>&nbsp;Manage Service Forms
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Service Forms</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Info Box -->
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-info">
              <h5><i class="icon fas fa-info-circle"></i> Information</h5>
              Manage custom form fields for each service. Customers will fill these forms when applying for services.
            </div>
          </div>
        </div>

        <!-- Services List -->
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Select a Service to Manage Forms</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" id="searchInput" class="form-control float-right" placeholder="Search services...">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover" id="servicesTable">
                    <thead>
                      <tr>
                        <th width="5%">ID</th>
                        <th width="30%">Service Name</th>
                        <th width="20%">Category</th>
                        <th width="15%">Form Fields</th>
                        <th width="15%">Status</th>
                        <th width="15%">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = $con->query("SELECT s.*, c.name as category_name 
                                          FROM service s 
                                          LEFT JOIN cate c ON s.cate = c.id 
                                          WHERE s.status='1' 
                                          ORDER BY s.id DESC");
                      while($row = $sql->fetch_assoc()):
                          // Count form fields for this service
                          $count_sql = $con->query("SELECT COUNT(*) as field_count 
                                                    FROM service_form_fields 
                                                    WHERE service_id='".$row['id']."'");
                          $count_row = $count_sql->fetch_assoc();
                          $field_count = $count_row['field_count'];
                          
                          // Get last updated date for form fields
                          $last_update_sql = $con->query("SELECT MAX(created_at) as last_updated 
                                                          FROM service_form_fields 
                                                          WHERE service_id='".$row['id']."'");
                          $last_update_row = $last_update_sql->fetch_assoc();
                          $last_updated = $last_update_row['last_updated'] ? date('d M Y', strtotime($last_update_row['last_updated'])) : 'Never';
                      ?>
                      <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <?php if(!empty($row['image'])): ?>
                            <img src="../images/<?php echo $row['image']; ?>" alt="Service Image" width="40" height="40" class="rounded-circle mr-3">
                            <?php endif; ?>
                            <div>
                              <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                              <small class="text-muted">₹<?php echo $row['o_price']; ?> (Original)</small>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="badge badge-secondary"><?php echo htmlspecialchars($row['category_name']); ?></span>
                        </td>
                        <td>
                          <div class="d-flex flex-column">
                            <span class="badge <?php echo $field_count > 0 ? 'badge-success' : 'badge-secondary'; ?> mb-1">
                              <?php echo $field_count; ?> field<?php echo $field_count != 1 ? 's' : ''; ?>
                            </span>
                            <small class="text-muted field-count">Last updated: <?php echo $last_updated; ?></small>
                          </div>
                        </td>
                        <td>
                          <?php if($row['status'] == '1'): ?>
                            <span class="badge badge-success">Active</span>
                          <?php else: ?>
                            <span class="badge badge-danger">Inactive</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <div class="btn-group" role="group">
                            <a href="service_form_fields.php?service_id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-primary" title="Manage Form Fields">
                               <i class="fa fa-edit"></i> Manage
                            </a>
                            <!-- <a href="quick_add_field.php?service_id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-success" title="Quick Add Field">
                               <i class="fa fa-plus"></i>
                            </a> -->
                          </div>
                        </td>
                      </tr>
                      <?php endwhile; ?>
                      
                      <?php if($sql->num_rows == 0): ?>
                      <tr>
                        <td colspan="6" class="text-center py-4">
                          <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                          <h5>No services found</h5>
                          <p class="text-muted">Add services first to manage form fields</p>
                          <a href="add_service.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Service
                          </a>
                        </td>
                      </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer clearfix">
                <div class="float-right">
                  <div class="btn-group">
                    <button type="button" class="btn btn-default" onclick="window.print()">
                      <i class="fas fa-print"></i> Print
                    </button>
                    <button type="button" class="btn btn-default" onclick="exportToExcel()">
                      <i class="fas fa-download"></i> Export
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-md-3 col-sm-6">
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="fas fa-copy"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Services</span>
                <span class="info-box-number">
                  <?php
                  $total_services = $con->query("SELECT COUNT(*) as total FROM service WHERE status='1'")->fetch_assoc();
                  echo $total_services['total'];
                  ?>
                </span>
              </div>
            </div>
          </div>
          
          <div class="col-md-3 col-sm-6">
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="fas fa-list-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Services with Forms</span>
                <span class="info-box-number">
                  <?php
                  $with_forms = $con->query("SELECT COUNT(DISTINCT service_id) as total FROM service_form_fields")->fetch_assoc();
                  echo $with_forms['total'];
                  ?>
                </span>
              </div>
            </div>
          </div>
          
          <div class="col-md-3 col-sm-6">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fas fa-field"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Form Fields</span>
                <span class="info-box-number">
                  <?php
                  $total_fields = $con->query("SELECT COUNT(*) as total FROM service_form_fields")->fetch_assoc();
                  echo $total_fields['total'];
                  ?>
                </span>
              </div>
            </div>
          </div>
          
          <div class="col-md-3 col-sm-6">
            <div class="info-box">
              <span class="info-box-icon bg-danger"><i class="fas fa-star"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Most Fields</span>
                <span class="info-box-number">
                  <?php
                  $most_fields = $con->query("SELECT COUNT(*) as count, s.title 
                                              FROM service_form_fields f 
                                              JOIN service s ON f.service_id = s.id 
                                              GROUP BY service_id 
                                              ORDER BY count DESC 
                                              LIMIT 1")->fetch_assoc();
                  echo $most_fields['count'] ?? 0;
                  ?>
                </span>
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
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

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
      "pageLength": 25,
      "language": {
        "search": "Search services:",
        "lengthMenu": "Show _MENU_ services per page",
        "info": "Showing _START_ to _END_ of _TOTAL_ services",
        "emptyTable": "No services available"
      }
    });
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
      $('#servicesTable').DataTable().search($(this).val()).draw();
    });
});

function exportToExcel() {
    // Simple table export
    const table = document.getElementById('servicesTable');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let row of rows) {
        let cells = row.querySelectorAll('td, th');
        let rowData = [];
        for (let cell of cells) {
            // Remove HTML tags and get text
            let text = cell.textContent || cell.innerText;
            // Escape commas and quotes for CSV
            text = text.replace(/"/g, '""');
            if (text.includes(',') || text.includes('"') || text.includes('\n')) {
                text = '"' + text + '"';
            }
            rowData.push(text);
        }
        csv.push(rowData.join(','));
    }
    
    // Create download link
    const csvString = csv.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('href', url);
    a.setAttribute('download', 'service_forms_' + new Date().toISOString().slice(0,10) + '.csv');
    a.click();
}

// Quick add field modal
function quickAddField(serviceId, serviceName) {
    // This would be implemented with a modal
    // For now, redirect to the service_form_fields page
    window.location.href = 'service_form_fields.php?service_id=' + serviceId + '&add_new=true';
}
</script>
</body>
</html>