<?php
session_start();
include("../db.php");

// Check if user is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../ca-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Get CA details
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
if($sql->num_rows == 0){
    session_destroy();
    header("Location: ../ca-login.php");
    exit();
}
$ca = $sql->fetch_assoc();

$ca_name = $ca["name"];
$ca_image = $ca["image"];
$ca_id_number = $ca["ca_id"];

// Handle item deletion
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $con->query("UPDATE items SET status='inactive' WHERE id='$delete_id' AND created_by='$ca_id'");
    $_SESSION['success'] = "Item deleted successfully!";
    header("Location: items.php");
    exit();
}

// Handle status change
if(isset($_GET['toggle_status'])) {
    $item_id = $_GET['toggle_status'];
    $result = $con->query("SELECT status FROM items WHERE id='$item_id' AND created_by='$ca_id'");
    if($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        $new_status = $item['status'] == 'active' ? 'inactive' : 'active';
        $con->query("UPDATE items SET status='$new_status' WHERE id='$item_id'");
        $_SESSION['success'] = "Item status updated!";
    }
    header("Location: items.php");
    exit();
}

// Get all items for this CA
$items = [];
$sql = $con->query("SELECT i.* FROM items i WHERE i.created_by='$ca_id' ORDER BY i.created_date DESC");
if($sql->num_rows > 0) {
    $items = $sql->fetch_all(MYSQLI_ASSOC);
}

// Get all categories for filter
$categories = [];
$cat_sql = $con->query("SELECT category_name FROM item_categories WHERE status='active' ORDER BY category_name ASC");
if($cat_sql->num_rows > 0) {
    $categories = $cat_sql->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Items - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
  
  <style>
    .item-badge {
      padding: 3px 8px;
      border-radius: 10px;
      font-size: 12px;
      font-weight: bold;
    }
    .badge-product { background: #17a2b8; color: white; }
    .badge-service { background: #28a745; color: white; }
    .badge-other { background: #6f42c1; color: white; }
    .stock-low { color: #dc3545; font-weight: bold; }
    .stock-normal { color: #28a745; }
    .stock-zero { color: #6c757d; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="items.php" class="nav-link active">Items</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="categories.php" class="nav-link">Categories</a>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php include('sidebar.php'); ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Items</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Items</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php if(isset($_SESSION['success'])): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
          </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Items</h3>
                <div class="card-tools">
                  <a href="add_item.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Item
                  </a>
                  <a href="categories.php" class="btn btn-info btn-sm">
                    <i class="fas fa-tags"></i> Manage Categories
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="itemsTable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Item Code</th>
                      <th>Item Name</th>
                      <th>Type</th>
                      <th>Category</th>
                      <th>Unit</th>
                      <th>HSN/SAC</th>
                      <th>Purchase Price</th>
                      <th>Sales Price</th>
                      <th>Stock</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($items) > 0): ?>
                      <?php $counter = 1; ?>
                      <?php foreach($items as $item): ?>
                        <tr>
                          <td><?php echo $counter++; ?></td>
                          <td><?php echo htmlspecialchars($item['item_code']); ?></td>
                          <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                          <td>
                            <?php 
                              $type_class = '';
                              $type_text = '';
                              switch($item['item_type']) {
                                case 'product': $type_class = 'badge-product'; $type_text = 'Product'; break;
                                case 'service': $type_class = 'badge-service'; $type_text = 'Service'; break;
                                case 'other': $type_class = 'badge-other'; $type_text = 'Other'; break;
                              }
                            ?>
                            <span class="item-badge <?php echo $type_class; ?>">
                              <?php echo $type_text; ?>
                            </span>
                          </td>
                          <td><?php echo htmlspecialchars($item['item_category'] ?: '-'); ?></td>
                          <td><?php echo htmlspecialchars($item['unit']); ?></td>
                          <td><?php echo htmlspecialchars($item['hsn_sac_code'] ?: '-'); ?></td>
                          <td>₹<?php echo number_format($item['purchase_price'], 2); ?></td>
                          <td>₹<?php echo number_format($item['sales_price'], 2); ?></td>
                          <td>
                            <?php 
                              if($item['item_type'] == 'service') {
                                echo '<span class="text-muted">N/A</span>';
                              } else {
                                $stock = $item['current_stock'];
                                $low_stock = $item['low_stock_alert'];
                                
                                if($stock <= 0) {
                                  echo '<span class="stock-zero">' . number_format($stock, 2) . '</span>';
                                } elseif($stock <= $low_stock && $low_stock > 0) {
                                  echo '<span class="stock-low">' . number_format($stock, 2) . '</span>';
                                } else {
                                  echo '<span class="stock-normal">' . number_format($stock, 2) . '</span>';
                                }
                              }
                            ?>
                          </td>
                          <td>
                            <?php if($item['status'] == 'active'): ?>
                              <span class="badge badge-success">Active</span>
                            <?php else: ?>
                              <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-sm" title="Edit">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a href="?toggle_status=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm" title="Toggle Status">
                              <i class="fas fa-toggle-on"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $item['id']; ?>)" class="btn btn-danger btn-sm" title="Delete">
                              <i class="fas fa-trash"></i>
                            </button>
                            <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-primary btn-sm" title="View Details">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="12" class="text-center">No items found. <a href="add_item.php">Add your first item</a></td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
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
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(function () {
  $('#itemsTable').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });
});

function confirmDelete(itemId) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'items.php?delete_id=' + itemId;
    }
  });
}
</script>
</body>
</html>