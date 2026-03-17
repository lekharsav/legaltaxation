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

// Handle delete
if(isset($_GET['delete']) && isset($_GET['id'])){
    $id = intval($_GET['id']);
    
    // Get CA details to delete image
    $ca_sql = $con->query("SELECT image FROM ca WHERE id='$id'");
    if($ca_row = $ca_sql->fetch_assoc()){
        $image_file = $ca_row['image'];
        
        // Delete from database
        if($con->query("DELETE FROM ca WHERE id='$id'")){
            // Delete image file if not default
            if($image_file != 'default_profile.jpg' && file_exists('ca/' . $image_file)){
                unlink('ca/' . $image_file);
            }
            
            echo "<script>
                    toastr.success('CA deleted successfully!');
                    setTimeout(function(){
                        window.location.href = 'all_ca.php';
                    }, 1500);
                  </script>";
        } else {
            echo "<script>
                    toastr.error('Error deleting CA: " . addslashes($con->error) . "');
                  </script>";
        }
    }
}

// Handle status toggle
if(isset($_GET['toggle_status']) && isset($_GET['id'])){
    $id = intval($_GET['id']);
    $status = $_GET['toggle_status'] == 'activate' ? '1' : '0';
    
    if($con->query("UPDATE ca SET status='$status' WHERE id='$id'")){
        $status_text = $status == '1' ? 'activated' : 'deactivated';
        echo "<script>
                toastr.success('CA $status_text successfully!');
                setTimeout(function(){
                    window.location.href = 'all_ca.php';
                }, 1500);
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>All CA - Legal Taxation</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .ca-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }
    .action-buttons {
        min-width: 120px;
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
                <i class="mdi mdi-account-group"></i>
              </span>&nbsp;All CA
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">All CA</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Stats Cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <?php
                $total_ca = $con->query("SELECT COUNT(*) as total FROM ca")->fetch_assoc()['total'];
                ?>
                <h3><?php echo $total_ca; ?></h3>
                <p>Total CA</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-tie"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <?php
                $active_ca = $con->query("SELECT COUNT(*) as active FROM ca WHERE status='1'")->fetch_assoc()['active'];
                ?>
                <h3><?php echo $active_ca; ?></h3>
                <p>Active CA</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <?php
                $inactive_ca = $con->query("SELECT COUNT(*) as inactive FROM ca WHERE status='0'")->fetch_assoc()['inactive'];
                ?>
                <h3><?php echo $inactive_ca; ?></h3>
                <p>Inactive CA</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>Add</h3>
                <p>New CA</p>
              </div>
              <div class="icon">
                <i class="fas fa-plus"></i>
              </div>
              <a href="add_ca.php" class="small-box-footer">Add Now <i class="fas fa-plus-circle"></i></a>
            </div>
          </div>
        </div>

        <!-- CA List -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">CA List</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" id="searchInput" class="form-control float-right" placeholder="Search...">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="card-body">
                <div class="table-responsive">
                  <table id="caTable" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>CA ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Reg. No.</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sl = 1;
                      $sql = $con->query("SELECT ca.*, ca_des.name as designation_name 
                                          FROM ca 
                                          LEFT JOIN ca_des ON ca.des = ca_des.id 
                                          ORDER BY ca.id DESC");
                      
                      if($sql->num_rows > 0){
                          while($row = $sql->fetch_assoc()){
                              $image_path = $row['image'] ? 'ca/' . $row['image'] : 'assets/images/profile.jpg';
                              if(!file_exists($image_path) || $row['image'] == ''){
                                  $image_path = 'assets/images/profile.jpg';
                              }
                      ?>
                      <tr>
                        <td><?php echo $sl; ?></td>
                        <td>
                          <img src="<?php echo $image_path; ?>" 
                               alt="<?php echo htmlspecialchars($row['name']); ?>" 
                               class="ca-image"
                               data-toggle="tooltip" 
                               title="<?php echo htmlspecialchars($row['name']); ?>">
                        </td>
                        <td>
                          <strong><?php echo $row['ca_id']; ?></strong>
                        </td>
                        <td>
                          <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                        </td>
                        <td>
                          <?php 
                          if($row['designation_name']){
                              echo '<span class="badge badge-info">' . htmlspecialchars($row['designation_name']) . '</span>';
                          } else {
                              echo '<span class="badge badge-secondary">Not Set</span>';
                          }
                          ?>
                        </td>
                        <td>
                          <a href="tel:<?php echo htmlspecialchars($row['cont']); ?>" class="text-primary">
                            <i class="fas fa-phone-alt mr-1"></i><?php echo htmlspecialchars($row['cont']); ?>
                          </a>
                        </td>
                        <td>
                          <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-primary">
                            <i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($row['email']); ?>
                          </a>
                        </td>
                        <td>
                          <?php 
                          echo $row['reg_no'] ? htmlspecialchars($row['reg_no']) : '<span class="text-muted">N/A</span>';
                          ?>
                        </td>
                        <td>
                          <?php if($row['status'] == '1'): ?>
                            <span class="badge badge-success">Active</span>
                          <?php else: ?>
                            <span class="badge badge-danger">Inactive</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <small><?php echo $row['created']; ?></small>
                        </td>
                        <td class="action-buttons">
                          <div class="btn-group btn-group-sm">
                            <!-- Edit Button -->
                            <a href="edit_ca.php&id=<?php echo $row['id']; ?>" 
                               class="btn btn-info" title="Edit">
                              <i class="fas fa-edit"></i>
                            </a>
                            
                            <!-- Status Toggle -->
                            <?php if($row['status'] == '1'): ?>
                              <a href="?src=all_ca.php&id=<?php echo $row['id']; ?>&toggle_status=deactivate" 
                                 class="btn btn-warning" 
                                 title="Deactivate"
                                 onclick="return confirm('Deactivate this CA?')">
                                <i class="fas fa-ban"></i>
                              </a>
                            <?php else: ?>
                              <a href="?src=all_ca.php&id=<?php echo $row['id']; ?>&toggle_status=activate" 
                                 class="btn btn-success" 
                                 title="Activate"
                                 onclick="return confirm('Activate this CA?')">
                                <i class="fas fa-check"></i>
                              </a>
                            <?php endif; ?>
                            
                            <!-- Delete Button -->
                            <a href="?src=all_ca.php&id=<?php echo $row['id']; ?>&delete=true" 
                               class="btn btn-danger" 
                               title="Delete"
                               onclick="return confirmDelete('<?php echo addslashes($row['name']); ?>')">
                              <i class="fas fa-trash"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                      <?php
                          $sl++;
                          }
                      } else {
                      ?>
                      <tr>
                        <td colspan="11" class="text-center py-4">
                          <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                          <h5>No CA Found</h5>
                          <p class="text-muted">Add your first CA to get started</p>
                          <a href="add_ca.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New CA
                          </a>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              
              <div class="card-footer clearfix">
                <div class="float-left">
                  <div class="dataTables_info" id="caTable_info" role="status" aria-live="polite">
                    Showing <?php echo $total_ca; ?> entries
                  </div>
                </div>
                <div class="float-right">
                  <a href="add_ca.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New CA
                  </a>
                  <button type="button" class="btn btn-default" onclick="printTable()">
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

      </div><!-- /.container-fluid -->
    </section>
  </div>

  <!-- Footer -->
    <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
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
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#caTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "pageLength": 25,
      "language": {
        "search": "Search CA:",
        "lengthMenu": "Show _MENU_ entries",
        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
        "emptyTable": "No CA data available"
      }
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
      $('#caTable').DataTable().search($(this).val()).draw();
    });
    
    // Initialize toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
});

function confirmDelete(caName) {
    return confirm('Are you sure you want to delete "' + caName + '"?\n\nThis action cannot be undone!');
}

function printTable() {
    window.print();
}

function exportToExcel() {
    // Get table data
    const table = document.getElementById('caTable');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let row of rows) {
        let cells = row.querySelectorAll('td, th');
        let rowData = [];
        for (let cell of cells) {
            // Skip image column (index 1) for export
            if (cell.cellIndex !== 1) {
                let text = cell.textContent || cell.innerText;
                text = text.replace(/"/g, '""');
                if (text.includes(',') || text.includes('"') || text.includes('\n')) {
                    text = '"' + text + '"';
                }
                rowData.push(text);
            }
        }
        csv.push(rowData.join(','));
    }
    
    // Create download link
    const csvString = csv.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('href', url);
    a.setAttribute('download', 'ca_list_' + new Date().toISOString().slice(0,10) + '.csv');
    a.click();
}

// Quick status toggle
function toggleStatus(caId, currentStatus) {
    var newStatus = currentStatus == '1' ? '0' : '1';
    var statusText = newStatus == '1' ? 'activate' : 'deactivate';
    
    if(confirm('Are you sure you want to ' + statusText + ' this CA?')) {
        window.location.href = '?src=all_ca.php&id=' + caId + '&toggle_status=' + statusText;
    }
}
</script>
</body>
</html>