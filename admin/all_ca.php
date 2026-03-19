<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
  exit;
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
  <title>All CA | Admin Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    * { font-family: 'Inter', sans-serif; }
    body { background-color: #f8fafc; }
    .content-wrapper { background-color: #f8fafc; }

    /* Page Header */
    .page-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1.5rem;
    }
    .page-header .icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(145deg, #3b82f6, #2563eb);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      box-shadow: 0 8px 12px -4px rgba(59,130,246,0.3);
    }
    .page-header h1 {
      font-weight: 600;
      font-size: 1.875rem;
      color: #0f172a;
      margin: 0;
    }

    /* KPI Cards */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    @media (max-width: 992px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
      border: 1px solid #f1f5f9;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .kpi-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 10px 10px -5px rgba(0,0,0,0.02);
      border-color: #e2e8f0;
    }
    .kpi-icon {
      width: 56px;
      height: 56px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
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
    }
    .kpi-link {
      font-size: 0.75rem;
      color: #3b82f6;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
    }

    /* Modern Card */
    .modern-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
      overflow: hidden;
      margin-bottom: 1.5rem;
    }
    .card-header-custom {
      padding: 1.25rem 1.75rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #ffffff;
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
    .card-header-custom h3 i { color: #3b82f6; }
    .card-body-custom { padding: 1.5rem 1.75rem; }
    .card-footer-custom {
      padding: 1rem 1.75rem;
      border-top: 1px solid #f1f5f9;
      background: #f8fafc;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Table */
    .table-responsive { overflow-x: auto; }
    .table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 0.5rem;
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
      box-shadow: 0 2px 4px rgba(0,0,0,0.02);
      transition: all 0.2s;
    }
    .table tbody tr:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #e0f2fe; color: #0284c7; }
    .badge-secondary { background: #f1f5f9; color: #334155; }

    /* CA Image */
    .ca-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
    }

    /* Action Buttons */
    .btn-group-modern {
      display: flex;
      gap: 0.25rem;
    }
    .btn-icon {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.4rem 0.8rem;
      font-size: 0.75rem;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      transition: all 0.2s;
    }
    .btn-icon:hover { background: #f8fafc; border-color: #94a3b8; }
    .btn-icon-info:hover { background: #e0f2fe; border-color: #3b82f6; color: #0369a1; }
    .btn-icon-success:hover { background: #d1fae5; border-color: #10b981; color: #065f46; }
    .btn-icon-warning:hover { background: #fff3cd; border-color: #f59e0b; color: #92400e; }
    .btn-icon-danger:hover { background: #fee2e2; border-color: #f87171; color: #991b1b; }

    .btn-modern {
      border-radius: 100px;
      padding: 0.6rem 1.5rem;
      font-size: 0.85rem;
      font-weight: 500;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
      border: none;
      cursor: pointer;
    }
    .btn-primary-modern {
      background: #3b82f6;
      color: white;
    }
    .btn-primary-modern:hover { background: #2563eb; }
    .btn-secondary-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      color: #334155;
    }
    .btn-secondary-modern:hover { background: #f8fafc; border-color: #94a3b8; }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 3rem;
    }
    .empty-state i {
      font-size: 3rem;
      color: #cbd5e1;
      margin-bottom: 1rem;
    }
    .empty-state h5 {
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 0.5rem;
    }
    .empty-state p {
      color: #64748b;
      margin-bottom: 1.5rem;
    }

    /* Footer */
    .main-footer {
      background: #ffffff;
      border-top: 1px solid #f1f5f9;
      color: #64748b;
      font-size: 0.85rem;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include("navbar.php"); ?>

  <!-- Main Sidebar Container -->
  <?php include("sidebar.php"); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <div class="page-header">
              <div class="icon"><i class="fas fa-user-tie"></i></div>
              <h1>All CA</h1>
            </div>
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
        
        <!-- KPI Cards -->
        <?php
        $total_ca = $con->query("SELECT COUNT(*) as total FROM ca")->fetch_assoc()['total'];
        $active_ca = $con->query("SELECT COUNT(*) as active FROM ca WHERE status='1'")->fetch_assoc()['active'];
        $inactive_ca = $con->query("SELECT COUNT(*) as inactive FROM ca WHERE status='0'")->fetch_assoc()['inactive'];
        ?>
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#e0f2fe; color:#0284c7;"><i class="fas fa-user-tie"></i></div>
            <div class="kpi-content">
              <div class="kpi-label">Total CA</div>
              <div class="kpi-value"><?php echo $total_ca; ?></div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#d1fae5; color:#059669;"><i class="fas fa-check-circle"></i></div>
            <div class="kpi-content">
              <div class="kpi-label">Active CA</div>
              <div class="kpi-value"><?php echo $active_ca; ?></div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#fef9c3; color:#b45309;"><i class="fas fa-clock"></i></div>
            <div class="kpi-content">
              <div class="kpi-label">Inactive CA</div>
              <div class="kpi-value"><?php echo $inactive_ca; ?></div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#f1f5f9; color:#334155;"><i class="fas fa-plus"></i></div>
            <div class="kpi-content">
              <div class="kpi-label">New CA</div>
              <div class="kpi-value">Add</div>
              <a href="add_ca.php" class="kpi-link">Add Now <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- CA List Card -->
        <div class="modern-card">
          <div class="card-header-custom">
            <h3><i class="fas fa-list"></i> CA List</h3>
            <input type="text" id="searchInput" class="form-control" placeholder="Search CA..." style="width: 200px; border-radius: 100px; border: 1px solid #e2e8f0; padding: 0.4rem 1rem;">
          </div>
          <div class="card-body-custom">
            <div class="table-responsive">
              <table id="caTable" class="table">
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
                    <td><strong><?php echo $row['ca_id']; ?></strong></td>
                    <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                    <td>
                      <?php 
                      if($row['designation_name']){
                          echo '<span class="badge-modern badge-info">' . htmlspecialchars($row['designation_name']) . '</span>';
                      } else {
                          echo '<span class="badge-modern badge-secondary">Not Set</span>';
                      }
                      ?>
                    </td>
                    <td>
                      <a href="tel:<?php echo htmlspecialchars($row['cont']); ?>" class="text-decoration-none">
                        <i class="fas fa-phone-alt mr-1" style="color: #3b82f6;"></i><?php echo htmlspecialchars($row['cont']); ?>
                      </a>
                    </td>
                    <td>
                      <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-decoration-none">
                        <i class="fas fa-envelope mr-1" style="color: #3b82f6;"></i><?php echo htmlspecialchars($row['email']); ?>
                      </a>
                    </td>
                    <td>
                      <?php 
                      echo $row['reg_no'] ? htmlspecialchars($row['reg_no']) : '<span class="text-muted">N/A</span>';
                      ?>
                    </td>
                    <td>
                      <?php if($row['status'] == '1'): ?>
                        <span class="badge-modern badge-success">Active</span>
                      <?php else: ?>
                        <span class="badge-modern badge-danger">Inactive</span>
                      <?php endif; ?>
                    </td>
                    <td><small><?php echo $row['created']; ?></small></td>
                    <td>
                      <div class="btn-group-modern">
                        <!-- Edit Button -->
                        <a href="edit_ca.php&id=<?php echo $row['id']; ?>" 
                           class="btn-icon btn-icon-info" title="Edit">
                          <i class="fas fa-edit"></i> Edit
                        </a>
                        
                        <!-- Status Toggle -->
                        <?php if($row['status'] == '1'): ?>
                          <a href="?src=all_ca.php&id=<?php echo $row['id']; ?>&toggle_status=deactivate" 
                             class="btn-icon btn-icon-warning" 
                             title="Deactivate"
                             onclick="return confirm('Deactivate this CA?')">
                            <i class="fas fa-ban"></i> Deactivate
                          </a>
                        <?php else: ?>
                          <a href="?src=all_ca.php&id=<?php echo $row['id']; ?>&toggle_status=activate" 
                             class="btn-icon btn-icon-success" 
                             title="Activate"
                             onclick="return confirm('Activate this CA?')">
                            <i class="fas fa-check"></i> Activate
                          </a>
                        <?php endif; ?>
                        
                        <!-- Delete Button -->
                        <a href="?src=all_ca.php&id=<?php echo $row['id']; ?>&delete=true" 
                           class="btn-icon btn-icon-danger" 
                           title="Delete"
                           onclick="return confirmDelete('<?php echo addslashes($row['name']); ?>')">
                          <i class="fas fa-trash"></i> Delete
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
                    <td colspan="11" class="empty-state">
                      <i class="fas fa-user-tie"></i>
                      <h5>No CA Found</h5>
                      <p class="text-muted">Add your first CA to get started</p>
                      <a href="add_ca.php" class="btn-modern btn-primary-modern">
                        <i class="fas fa-plus"></i> Add New CA
                      </a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer-custom">
            <div class="dataTables_info">
              Showing <?php echo $total_ca; ?> entries
            </div>
            <div class="btn-group-modern">
              <a href="add_ca.php" class="btn-modern btn-primary-modern">
                <i class="fas fa-plus"></i> Add New CA
              </a>
              <button type="button" class="btn-modern btn-secondary-modern" onclick="printTable()">
                <i class="fas fa-print"></i> Print
              </button>
              <button type="button" class="btn-modern btn-secondary-modern" onclick="exportToExcel()">
                <i class="fas fa-download"></i> Export
              </button>
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
    
    // Custom search input
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