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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Service Forms | Admin Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

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

    /* Modern Alert */
    .modern-alert {
      background: #e0f2fe;
      border: 1px solid #bae6fd;
      border-radius: 1rem;
      padding: 1.25rem 1.75rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }
    .modern-alert i {
      color: #0284c7;
      font-size: 1.5rem;
    }
    .modern-alert h5 {
      font-weight: 600;
      font-size: 1rem;
      color: #0f172a;
      margin-bottom: 0.25rem;
    }
    .modern-alert p {
      color: #334155;
      font-size: 0.9rem;
      margin: 0;
    }

    /* KPI Cards */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-top: 1.5rem;
    }
    @media (max-width: 992px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
      border: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      gap: 1rem;
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
      font-size: 1.75rem;
      font-weight: 700;
      color: #0f172a;
      line-height: 1.2;
    }

    /* Main Card */
    .modern-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
      overflow: hidden;
      margin-top: 1.5rem;
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
      justify-content: flex-end;
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
    .badge-secondary { background: #f1f5f9; color: #334155; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-info { background: #e0f2fe; color: #0284c7; }

    /* Buttons */
    .btn-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.4rem 1rem;
      font-size: 0.75rem;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      transition: all 0.2s;
    }
    .btn-modern:hover { background: #f8fafc; border-color: #94a3b8; }
    .btn-primary-modern {
      background: #3b82f6;
      border-color: #3b82f6;
      color: white;
    }
    .btn-primary-modern:hover { background: #2563eb; }

    .btn-group-modern { display: flex; gap: 0.25rem; flex-wrap: wrap; }

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

  <!-- Sidebar -->
  <?php include("sidebar.php"); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <div class="page-header">
              <div class="icon"><i class="fas fa-list-alt"></i></div>
              <h1>Manage Service Forms</h1>
            </div>
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

        <!-- Info Alert (redesigned) -->
        <div class="modern-alert">
          <i class="fas fa-info-circle"></i>
          <div>
            <h5>Information</h5>
            <p>Manage custom form fields for each service. Customers will fill these forms when applying for services.</p>
          </div>
        </div>

        <!-- Services Table Card -->
        <div class="modern-card">
          <div class="card-header-custom">
            <h3><i class="fas fa-cogs"></i> Services & Forms</h3>
            <div style="display: flex; gap: 0.5rem;">
              <input type="text" id="searchInput" class="form-control" placeholder="Search services..." style="width: 200px; border-radius: 100px; border: 1px solid #e2e8f0; padding: 0.4rem 1rem;">
            </div>
          </div>
          <div class="card-body-custom">
            <div class="table-responsive">
              <table class="table" id="servicesTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Service Name</th>
                    <th>Category</th>
                    <th>Form Fields</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                      $count_sql = $con->query("SELECT COUNT(*) as field_count 
                                                FROM service_form_fields 
                                                WHERE service_id='".$row['id']."'");
                      $count_row = $count_sql->fetch_assoc();
                      $field_count = $count_row['field_count'];
                      
                      $last_update_sql = $con->query("SELECT MAX(created_at) as last_updated 
                                                      FROM service_form_fields 
                                                      WHERE service_id='".$row['id']."'");
                      $last_update_row = $last_update_sql->fetch_assoc();
                      $last_updated = $last_update_row['last_updated'] ? date('d M Y', strtotime($last_update_row['last_updated'])) : 'Never';
                  ?>
                  <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                      <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <?php if(!empty($row['image'])): ?>
                        <img src="../images/<?php echo $row['image']; ?>" alt="Service Image" width="40" height="40" style="border-radius: 10px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                          <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                          <small class="text-muted">₹<?php echo $row['o_price']; ?> (Original)</small>
                        </div>
                      </div>
                    </td>
                    <td><span class="badge badge-secondary"><?php echo htmlspecialchars($row['category_name']); ?></span></td>
                    <td>
                      <div>
                        <span class="badge-modern <?php echo $field_count > 0 ? 'badge-success' : 'badge-secondary'; ?>">
                          <?php echo $field_count; ?> field<?php echo $field_count != 1 ? 's' : ''; ?>
                        </span>
                        <br>
                        <small class="text-muted">Updated: <?php echo $last_updated; ?></small>
                      </div>
                    </td>
                    <td>
                      <span class="badge-modern badge-success">Active</span>
                    </td>
                    <td>
                      <div class="btn-group-modern">
                        <a href="service_form_fields.php?service_id=<?php echo $row['id']; ?>" 
                           class="btn-modern btn-primary-modern" title="Manage Form Fields">
                           <i class="fas fa-edit"></i> Manage
                        </a>
                      </div>
                    </td>
                  </tr>
                  <?php endwhile; ?>
                  
                  <?php if($sql->num_rows == 0): ?>
                  <tr>
                    <td colspan="6" class="empty-state">
                      <i class="fas fa-clipboard-list"></i>
                      <h5>No services found</h5>
                      <p class="text-muted">Add services first to manage form fields</p>
                      <a href="add_service.php" class="btn-modern btn-primary-modern" style="padding: 0.6rem 1.5rem;">
                        <i class="fas fa-plus"></i> Add New Service
                      </a>
                    </td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer-custom">
            <div class="btn-group-modern">
              <button type="button" class="btn-modern" onclick="window.print()">
                <i class="fas fa-print"></i> Print
              </button>
              <button type="button" class="btn-modern" onclick="exportToExcel()">
                <i class="fas fa-download"></i> Export
              </button>
            </div>
          </div>
        </div>

        <!-- Statistics Cards (KPI style) -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#e0f2fe; color:#0284c7;"><i class="fas fa-copy"></i></div>
            <div class="kpi-content">
              <div class="kpi-label">Total Services</div>
              <div class="kpi-value"><?php
                $total_services = $con->query("SELECT COUNT(*) as total FROM service WHERE status='1'")->fetch_assoc();
                echo $total_services['total'];
              ?></div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#d1fae5; color:#059669;"><i class="fas fa-list-alt"></i></div>
            <div class="kpi-content">
              <div class="kpi-label">Services with Forms</div>
              <div class="kpi-value"><?php
                $with_forms = $con->query("SELECT COUNT(DISTINCT service_id) as total FROM service_form_fields")->fetch_assoc();
                echo $with_forms['total'];
              ?></div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#fef9c3; color:#b45309;"><i class="fas fa-field"></i></div>
            <div class="kpi-content">
              <div class="kpi-label">Total Form Fields</div>
              <div class="kpi-value"><?php
                $total_fields = $con->query("SELECT COUNT(*) as total FROM service_form_fields")->fetch_assoc();
                echo $total_fields['total'];
              ?></div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background:#fee2e2; color:#b91c1c;"><i class="fas fa-star"></i></div>
            <div class="kpi-content">
              <div class="kpi-label">Most Fields</div>
              <div class="kpi-value"><?php
                $most_fields = $con->query("SELECT COUNT(*) as count FROM service_form_fields GROUP BY service_id ORDER BY count DESC LIMIT 1")->fetch_assoc();
                echo $most_fields['count'] ?? 0;
              ?></div>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
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
      },
      "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
    
    // Custom search input
    $('#searchInput').on('keyup', function() {
      $('#servicesTable').DataTable().search($(this).val()).draw();
    });
});

function exportToExcel() {
    const table = document.getElementById('servicesTable');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let row of rows) {
        let cells = row.querySelectorAll('td, th');
        let rowData = [];
        for (let cell of cells) {
            let text = cell.textContent || cell.innerText;
            text = text.replace(/"/g, '""');
            if (text.includes(',') || text.includes('"') || text.includes('\n')) {
                text = '"' + text + '"';
            }
            rowData.push(text);
        }
        csv.push(rowData.join(','));
    }
    
    const csvString = csv.join('\n');
    const blob = new Blob([csvString], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('href', url);
    a.setAttribute('download', 'service_forms_' + new Date().toISOString().slice(0,10) + '.csv');
    a.click();
}
</script>
</body>
</html>