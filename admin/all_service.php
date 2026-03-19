<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
  exit;
}
$currentDate = date("Y-m-d");
$givenDate = "2024-09-16";
if (strtotime($currentDate) > strtotime($givenDate)) {
    $sql = $con->query("select * from admin where id='$aid'");
} else {
    $sql = $con->query("select * from admin where id='$aid'");
}

if($row = $sql->fetch_assoc()){
    $admin_image = $row["image"];
    $admin_name = $row["name"];
    $admin_cont = $row["contact"];
    $admin_email = $row["email"];
    $admin_pass = $row["password"];
}

if(isset($_GET['delete'])){
    if($con->query("delete from service where id='".$_GET['id']."'")===true){
        echo"<script>window.location='all_service.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>All Services | Admin Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- DataTables (optional) -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

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
    .badge-secondary { background: #f1f5f9; color: #334155; }

    /* Action Buttons */
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
      margin-right: 0.25rem;
    }
    .btn-icon:hover { background: #f8fafc; border-color: #94a3b8; }
    .btn-icon-success:hover { background: #d1fae5; border-color: #10b981; color: #065f46; }
    .btn-icon-info:hover { background: #e0f2fe; border-color: #3b82f6; color: #0369a1; }
    .btn-icon-warning:hover { background: #fff3cd; border-color: #f59e0b; color: #92400e; }
    .btn-icon-danger:hover { background: #fee2e2; border-color: #f87171; color: #991b1b; }

    /* Add button */
    .btn-add {
      background: #3b82f6;
      border: none;
      border-radius: 100px;
      padding: 0.5rem 1.25rem;
      font-size: 0.85rem;
      font-weight: 500;
      color: white;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: background 0.2s;
    }
    .btn-add:hover { background: #2563eb; }

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
              <div class="icon"><i class="fas fa-list"></i></div>
              <h1>All Services</h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Services</li>
              <li class="breadcrumb-item"><a href="add_service.php" class="btn-add"><i class="fas fa-plus"></i> Add New Service</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="modern-card">
              <div class="card-header-custom">
                <h3><i class="fas fa-cogs"></i> Service List</h3>
                <span class="badge badge-info"><?php
                  $count = $con->query("SELECT COUNT(*) as total FROM service")->fetch_assoc()['total'];
                  echo $count; ?> total
                </span>
              </div>
              <div class="card-body-custom">
                <div class="table-responsive">
                  <table class="table example" id="servicesTable">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Market Price</th>
                        <th>Original Price</th>
                        <th>Partner Price</th>
                        <th>Min Clients</th>
                        <th>Applications</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sl = 1;
                      $sql = $con->query("select * from service order by id desc");
                      while($row = $sql->fetch_assoc()){
                          $cate = '';
                          $sql2 = $con->query("select * from cate where id='".$row['cate']."'");
                          if($row2 = $sql2->fetch_assoc()){
                              $cate = $row2['name'];
                          }
                          ?>
                          <tr>
                            <td><?= $sl ?></td>
                            <td><?= htmlspecialchars($cate) ?></td>
                            <td><img src="../images/<?= htmlspecialchars($row['image']) ?>" alt="Service Image" width="60" style="border-radius: 10px; object-fit: cover;"></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td>₹<?= number_format($row['m_price']) ?></td>
                            <td>₹<?= number_format($row['o_price']) ?></td>
                            <td>
                              <?php if(!empty($row['partner_o_price'])): ?>
                                ₹<?= number_format($row['partner_o_price']) ?>
                              <?php else: ?>
                                <span class="badge-modern badge-secondary">N/A</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if(!empty($row['min_clients_for_partner'])): ?>
                                <?= $row['min_clients_for_partner'] ?>
                              <?php else: ?>
                                <span class="badge-modern badge-secondary">N/A</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php
                              $total_applicant = 0;
                              $sql2 = $con->query("select * from apply where sid='".$row['id']."'");
                              while($row2 = $sql2->fetch_assoc()){
                                  if($row2['send_to'] == ''){
                                      $total_applicant++;
                                  }
                              }
                              echo $total_applicant;
                              ?>
                            </td>
                            <td>
                              <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                <a href="leads.php?sid=<?= $row['id'] ?>&cate=<?= $row['cate'] ?>" class="btn-icon btn-icon-warning" title="Leads">
                                  <i class="fas fa-users"></i> Leads
                                </a>
                                <a href="service_form_submissions.php?service_id=<?= $row['id'] ?>" class="btn-icon btn-icon-info" title="View Form Submissions">
                                  <i class="fas fa-file-alt"></i> Forms
                                </a>
                                <a href="edit_service.php?id=<?= $row['id'] ?>" class="btn-icon btn-icon-success" title="Edit">
                                  <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="all_service.php?id=<?= $row['id'] ?>&delete=delete" class="btn-icon btn-icon-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this service?');">
                                  <i class="fas fa-trash"></i> Delete
                                </a>
                              </div>
                            </td>
                          </tr>
                          <?php
                          $sl++;
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
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
<!-- DataTables (optional for better table features) -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
  $(function () {
    $('#servicesTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "language": {
        "search": "Search:",
        "lengthMenu": "Show _MENU_ entries",
        "info": "Showing _START_ to _END_ of _TOTAL_ entries"
      }
    });
  });
</script>
</body>
</html>