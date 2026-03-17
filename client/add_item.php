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

// Get categories for dropdown
$categories = [];
$cat_sql = $con->query("SELECT category_name FROM item_categories WHERE status='active' ORDER BY category_name ASC");
if($cat_sql->num_rows > 0) {
    $categories = $cat_sql->fetch_all(MYSQLI_ASSOC);
}

// Common units
$units = ['PCS', 'KG', 'G', 'L', 'ML', 'M', 'CM', 'FT', 'BOX', 'SET', 'PAIR', 'DOZEN'];

// Generate Item Code
function generateItemCode($con, $type) {
    $prefix = strtoupper(substr($type, 0, 3));
    $year = date('y');
    $month = date('m');
    
    // Get last ID
    $result = $con->query("SELECT item_code FROM items WHERE item_type='$type' ORDER BY id DESC LIMIT 1");
    if($result->num_rows > 0) {
        $last_code = $result->fetch_assoc()['item_code'];
        // Extract number and increment
        if(preg_match('/\d+$/', $last_code, $matches)) {
            $num = intval($matches[0]) + 1;
        } else {
            $num = 1;
        }
    } else {
        $num = 1;
    }
    
    return $prefix . $year . $month . str_pad($num, 4, '0', STR_PAD_LEFT);
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_type = $_POST['item_type'];
    $item_code = generateItemCode($con, $item_type);
    $item_name = $con->real_escape_string($_POST['item_name']);
    $item_category = $con->real_escape_string($_POST['item_category']);
    $unit = $con->real_escape_string($_POST['unit']);
    $hsn_sac_code = $con->real_escape_string($_POST['hsn_sac_code']);
    $opening_stock = floatval($_POST['opening_stock']);
    $current_stock = $opening_stock;
    $low_stock_alert = floatval($_POST['low_stock_alert']);
    
    // Purchase details
    $purchase_price = floatval($_POST['purchase_price']);
    $purchase_tax_rate = floatval($_POST['purchase_tax_rate']);
    $purchase_account = $con->real_escape_string($_POST['purchase_account']);
    
    // Sales details
    $sales_price = floatval($_POST['sales_price']);
    $sales_tax_rate = floatval($_POST['sales_tax_rate']);
    $sales_account = $con->real_escape_string($_POST['sales_account']);
    $mrp = floatval($_POST['mrp']);
    $discount_percentage = floatval($_POST['discount_percentage']);
    
    // Additional details
    $manufacturer = $con->real_escape_string($_POST['manufacturer']);
    $brand = $con->real_escape_string($_POST['brand']);
    $description = $con->real_escape_string($_POST['description']);
    $barcode = $con->real_escape_string($_POST['barcode']);
    
    // Check if item already exists with same name or code
    $check_sql = "SELECT id FROM items WHERE item_code = '$item_code' OR (item_name = '$item_name' AND item_type = '$item_type')";
    $check_result = $con->query($check_sql);
    
    if($check_result->num_rows > 0) {
        $_SESSION['error'] = "Item with same code or name already exists!";
    } else {
        // Insert item
        $sql = "INSERT INTO items (
            item_code, item_name, item_type, item_category, unit, hsn_sac_code,
            opening_stock, current_stock, low_stock_alert,
            purchase_price, purchase_tax_rate, purchase_account,
            sales_price, sales_tax_rate, sales_account, mrp, discount_percentage,
            manufacturer, brand, description, barcode, created_by, status
        ) VALUES (
            '$item_code', '$item_name', '$item_type', '$item_category', '$unit', '$hsn_sac_code',
            '$opening_stock', '$current_stock', '$low_stock_alert',
            '$purchase_price', '$purchase_tax_rate', '$purchase_account',
            '$sales_price', '$sales_tax_rate', '$sales_account', '$mrp', '$discount_percentage',
            '$manufacturer', '$brand', '$description', '$barcode', '$ca_id', 'active'
        )";
        
        if($con->query($sql)) {
            $_SESSION['success'] = "Item added successfully! Item Code: $item_code";
            header("Location: items.php");
            exit();
        } else {
            $_SESSION['error'] = "Error adding item: " . $con->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add New Item - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  
  <style>
    .required:after {
      content: " *";
      color: red;
    }
    .section-header {
      background: #f8f9fa;
      padding: 10px;
      border-left: 4px solid #007bff;
      margin: 20px 0;
    }
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
        <a href="#" class="nav-link active">Add Item</a>
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
            <h1 class="m-0">Add New Item</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="items.php">Items</a></li>
              <li class="breadcrumb-item active">Add Item</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php if(isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Item Details</h3>
              </div>
              <!-- form start -->
              <form method="POST" action="">
                <div class="card-body">
                  <div class="section-header">
                    <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Item Type</label>
                        <select name="item_type" class="form-control" id="item_type" required>
                          <option value="product">Product</option>
                          <option value="service">Service</option>
                          <option value="other">Other</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Item Name</label>
                        <input type="text" name="item_name" class="form-control" placeholder="Enter item name" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Category</label>
                        <select name="item_category" class="form-control select2">
                          <option value="">Select Category</option>
                          <?php foreach($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['category_name']); ?>">
                              <?php echo htmlspecialchars($category['category_name']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Unit</label>
                        <select name="unit" class="form-control select2">
                          <?php foreach($units as $unit): ?>
                            <option value="<?php echo $unit; ?>"><?php echo $unit; ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>HSN/SAC Code</label>
                        <input type="text" name="hsn_sac_code" class="form-control" placeholder="Enter HSN/SAC code">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Manufacturer</label>
                        <input type="text" name="manufacturer" class="form-control" placeholder="Manufacturer name">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control" placeholder="Brand name">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Item description"></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- Stock Information (only for products) -->
                  <div class="section-header stock-section">
                    <h5><i class="fas fa-boxes"></i> Stock Information</h5>
                  </div>
                  
                  <div class="row stock-section">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Opening Stock</label>
                        <input type="number" name="opening_stock" class="form-control" value="0" step="0.01" min="0">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Low Stock Alert</label>
                        <input type="number" name="low_stock_alert" class="form-control" value="0" step="0.01" min="0">
                        <small class="text-muted">Set 0 to disable alert</small>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Barcode</label>
                        <input type="text" name="barcode" class="form-control" placeholder="Barcode number">
                      </div>
                    </div>
                  </div>

                  <!-- Purchase Information -->
                  <div class="section-header">
                    <h5><i class="fas fa-shopping-cart"></i> Purchase Information</h5>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Purchase Price (₹)</label>
                        <input type="number" name="purchase_price" class="form-control" value="0" step="0.01" min="0">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Purchase Tax Rate (%)</label>
                        <input type="number" name="purchase_tax_rate" class="form-control" value="0" step="0.01" min="0" max="100">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Purchase Account</label>
                        <input type="text" name="purchase_account" class="form-control" placeholder="Purchase account name">
                      </div>
                    </div>
                  </div>

                  <!-- Sales Information -->
                  <div class="section-header">
                    <h5><i class="fas fa-cash-register"></i> Sales Information</h5>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="required">Sales Price (₹)</label>
                        <input type="number" name="sales_price" class="form-control" value="0" step="0.01" min="0" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Sales Tax Rate (%)</label>
                        <input type="number" name="sales_tax_rate" class="form-control" value="0" step="0.01" min="0" max="100">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>MRP (₹)</label>
                        <input type="number" name="mrp" class="form-control" value="0" step="0.01" min="0">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Discount (%)</label>
                        <input type="number" name="discount_percentage" class="form-control" value="0" step="0.01" min="0" max="100">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Sales Account</label>
                        <input type="text" name="sales_account" class="form-control" placeholder="Sales account name">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Add Item</button>
                  <button type="reset" class="btn btn-default">Reset</button>
                  <a href="items.php" class="btn btn-secondary">Cancel</a>
                </div>
              </form>
            </div>
            <!-- /.card -->
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
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(function () {
  // Initialize Select2
  $('.select2').select2();
  
  // Show/hide stock section based on item type
  function toggleStockSection() {
    var itemType = $('#item_type').val();
    if(itemType === 'service') {
      $('.stock-section').hide();
      $('input[name="opening_stock"]').val(0);
      $('input[name="low_stock_alert"]').val(0);
    } else {
      $('.stock-section').show();
    }
  }
  
  // Initial call
  toggleStockSection();
  
  // On item type change
  $('#item_type').change(function() {
    toggleStockSection();
  });
  
  // Calculate selling price if MRP and discount are entered
  $('input[name="mrp"], input[name="discount_percentage"]').on('input', function() {
    var mrp = parseFloat($('input[name="mrp"]').val()) || 0;
    var discount = parseFloat($('input[name="discount_percentage"]').val()) || 0;
    
    if(mrp > 0 && discount > 0) {
      var sellingPrice = mrp - (mrp * discount / 100);
      $('input[name="sales_price"]').val(sellingPrice.toFixed(2));
    }
  });
});
</script>
</body>
</html>