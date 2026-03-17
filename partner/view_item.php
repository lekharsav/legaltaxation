<?php
session_start();
include("../db.php");

// Check if user is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../partner-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Get CA details
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
if($sql->num_rows == 0){
    session_destroy();
    header("Location: ../partner-login.php");
    exit();
}
$ca = $sql->fetch_assoc();

$ca_name = $ca["name"];
$ca_image = $ca["image"];
$ca_id_number = $ca["ca_id"];

// Get item ID from URL
if(!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Item ID not specified!";
    header("Location: items.php");
    exit();
}

$item_id = $_GET['id'];

// Get item details
$sql = $con->query("SELECT * FROM items WHERE id='$item_id' AND created_by='$ca_id'");
if($sql->num_rows == 0) {
    $_SESSION['error'] = "Item not found or access denied!";
    header("Location: items.php");
    exit();
}

$item = $sql->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Item - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .item-detail-card {
      border-left: 4px solid #007bff;
    }
    .detail-label {
      font-weight: bold;
      color: #495057;
    }
    .detail-value {
      color: #6c757d;
    }
    .stock-indicator {
      display: inline-block;
      width: 15px;
      height: 15px;
      border-radius: 50%;
      margin-right: 5px;
    }
    .stock-good { background-color: #28a745; }
    .stock-low { background-color: #ffc107; }
    .stock-out { background-color: #dc3545; }
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
        <a href="items.php" class="nav-link">Items</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link active">View Item</a>
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
            <h1 class="m-0">Item Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="items.php">Items</a></li>
              <li class="breadcrumb-item active">View Item</li>
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
            <div class="card item-detail-card">
              <div class="card-header">
                <h3 class="card-title">
                  <?php echo htmlspecialchars($item['item_name']); ?>
                  <span class="badge badge-info float-right">
                    <?php 
                      switch($item['item_type']) {
                        case 'product': echo 'Product'; break;
                        case 'service': echo 'Service'; break;
                        case 'other': echo 'Other'; break;
                      }
                    ?>
                  </span>
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="info-box">
                      <span class="info-box-icon bg-info"><i class="fas fa-barcode"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Item Code</span>
                        <span class="info-box-number"><?php echo $item['item_code']; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="info-box">
                      <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Selling Price</span>
                        <span class="info-box-number">₹<?php echo number_format($item['sales_price'], 2); ?></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-6">
                    <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <td class="detail-label" width="40%">Item Type</td>
                        <td class="detail-value">
                          <?php 
                            switch($item['item_type']) {
                              case 'product': echo '<span class="badge badge-info">Product</span>'; break;
                              case 'service': echo '<span class="badge badge-success">Service</span>'; break;
                              case 'other': echo '<span class="badge badge-secondary">Other</span>'; break;
                            }
                          ?>
                        </td>
                      </tr>
                      <tr>
                        <td class="detail-label">Category</td>
                        <td class="detail-value"><?php echo htmlspecialchars($item['item_category'] ?: '-'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Unit</td>
                        <td class="detail-value"><?php echo htmlspecialchars($item['unit']); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">HSN/SAC Code</td>
                        <td class="detail-value"><?php echo htmlspecialchars($item['hsn_sac_code'] ?: '-'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Manufacturer</td>
                        <td class="detail-value"><?php echo htmlspecialchars($item['manufacturer'] ?: '-'); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Brand</td>
                        <td class="detail-value"><?php echo htmlspecialchars($item['brand'] ?: '-'); ?></td>
                      </tr>
                    </table>
                    
                    <h5 class="mt-4"><i class="fas fa-file-alt"></i> Description</h5>
                    <div class="card card-body bg-light">
                      <?php echo nl2br(htmlspecialchars($item['description'] ?: 'No description provided')); ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <?php if($item['item_type'] == 'product'): ?>
                      <h5><i class="fas fa-boxes"></i> Stock Information</h5>
                      <table class="table table-sm">
                        <tr>
                          <td class="detail-label" width="40%">Opening Stock</td>
                          <td class="detail-value"><?php echo number_format($item['opening_stock'], 2); ?></td>
                        </tr>
                        <tr>
                          <td class="detail-label">Current Stock</td>
                          <td class="detail-value">
                            <?php 
                              $stock = $item['current_stock'];
                              $low_stock = $item['low_stock_alert'];
                              
                              if($stock <= 0) {
                                echo '<span class="text-danger"><i class="fas fa-times-circle"></i> Out of Stock: ' . number_format($stock, 2) . '</span>';
                              } elseif($stock <= $low_stock && $low_stock > 0) {
                                echo '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Low Stock: ' . number_format($stock, 2) . '</span>';
                              } else {
                                echo '<span class="text-success"><i class="fas fa-check-circle"></i> In Stock: ' . number_format($stock, 2) . '</span>';
                              }
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td class="detail-label">Low Stock Alert</td>
                          <td class="detail-value"><?php echo number_format($item['low_stock_alert'], 2); ?></td>
                        </tr>
                        <tr>
                          <td class="detail-label">Barcode</td>
                          <td class="detail-value"><?php echo htmlspecialchars($item['barcode'] ?: '-'); ?></td>
                        </tr>
                      </table>
                    <?php endif; ?>

                    <h5 class="mt-4"><i class="fas fa-shopping-cart"></i> Purchase Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <td class="detail-label" width="40%">Purchase Price</td>
                        <td class="detail-value">₹<?php echo number_format($item['purchase_price'], 2); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Purchase Tax Rate</td>
                        <td class="detail-value"><?php echo number_format($item['purchase_tax_rate'], 2); ?>%</td>
                      </tr>
                      <tr>
                        <td class="detail-label">Purchase Account</td>
                        <td class="detail-value"><?php echo htmlspecialchars($item['purchase_account'] ?: '-'); ?></td>
                      </tr>
                    </table>

                    <h5 class="mt-4"><i class="fas fa-cash-register"></i> Sales Information</h5>
                    <table class="table table-sm">
                      <tr>
                        <td class="detail-label" width="40%">Sales Price</td>
                        <td class="detail-value">₹<?php echo number_format($item['sales_price'], 2); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Sales Tax Rate</td>
                        <td class="detail-value"><?php echo number_format($item['sales_tax_rate'], 2); ?>%</td>
                      </tr>
                      <tr>
                        <td class="detail-label">MRP</td>
                        <td class="detail-value">₹<?php echo number_format($item['mrp'], 2); ?></td>
                      </tr>
                      <tr>
                        <td class="detail-label">Discount</td>
                        <td class="detail-value"><?php echo number_format($item['discount_percentage'], 2); ?>%</td>
                      </tr>
                      <tr>
                        <td class="detail-label">Sales Account</td>
                        <td class="detail-value"><?php echo htmlspecialchars($item['sales_account'] ?: '-'); ?></td>
                      </tr>
                    </table>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                    <div class="card-footer">
                      <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Item
                      </a>
                      <a href="items.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                      </a>
                      <span class="float-right text-muted">
                        Created: <?php echo date('d M Y, h:i A', strtotime($item['created_date'])); ?>
                        <?php if($item['updated_date']): ?>
                          <br>Last Updated: <?php echo date('d M Y, h:i A', strtotime($item['updated_date'])); ?>
                        <?php endif; ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
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
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>