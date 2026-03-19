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

if(isset($_GET['status'])){
    if($con->query("update customer set status='".$_GET['status']."' where id='".$_GET['id']."'") === true){
        echo"<script>window.location='customers.php';</script>";
    }else{
        echo"<script>alert('Server Error!!!');window.location='customers.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customers | Admin Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter (modern) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    /* Main Card */
    .customers-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
      overflow: hidden;
      margin-top: 1rem;
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
    .card-header-custom h3 i { color: #3b82f6; }
    .card-body-custom { padding: 1.5rem 1.75rem; }

    /* Table Styling */
    .table-responsive {
      overflow-x: auto;
    }
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
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-approved { background: #d1fae5; color: #065f46; }

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
    .btn-modern:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }
    .btn-modern-success {
      background: #10b981;
      border-color: #10b981;
      color: white;
    }
    .btn-modern-success:hover {
      background: #059669;
    }
    .btn-modern-warning {
      background: #f59e0b;
      border-color: #f59e0b;
      color: white;
    }
    .btn-modern-warning:hover {
      background: #d97706;
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
              <div class="icon"><i class="fas fa-users"></i></div>
              <h1>All Customers</h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Customers</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="customers-card">
              <div class="card-header-custom">
                <h3><i class="fas fa-list"></i> Customer List</h3>
                <span class="badge badge-info"><?php
                  $count = $con->query("SELECT COUNT(*) as total FROM customer")->fetch_assoc()['total'];
                  echo $count; ?> total
                </span>
              </div>
              <div class="card-body-custom">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Mobile Number</th>
                        <th>Email Address</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sl = 1;
                      $sql = $con->query("select * from customer order by id desc");
                      while($row = $sql->fetch_assoc()){
                      ?>
                      <tr>
                        <td><?php echo $sl; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                          <?php
                          if($row['status'] == 0){
                              echo '<span class="badge-modern badge-pending">Pending</span>';
                          } elseif($row['status'] == 1){
                              echo '<span class="badge-modern badge-approved">Approved</span>';
                          }
                          ?>
                        </td>
                        <td>
                          <?php if($row['status'] == 0): ?>
                            <a href="customers.php?status=1&id=<?php echo $row['id']; ?>" class="btn-modern btn-modern-success">
                              <i class="fas fa-check"></i> Approve
                            </a>
                          <?php elseif($row['status'] == 1): ?>
                            <a href="customers.php?status=0&id=<?php echo $row['id']; ?>" class="btn-modern btn-modern-warning">
                              <i class="fas fa-undo-alt"></i> Pending
                            </a>
                          <?php endif; ?>
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
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>