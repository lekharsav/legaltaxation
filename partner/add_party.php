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

// Generate Party ID
function generatePartyId($con) {
    $prefix = "PID";
    $year = date('y');
    $month = date('m');
    
    // Get last ID
    $result = $con->query("SELECT party_id FROM parties ORDER BY id DESC LIMIT 1");
    if($result->num_rows > 0) {
        $last_id = $result->fetch_assoc()['party_id'];
        // Extract number and increment
        if(preg_match('/\d+$/', $last_id, $matches)) {
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
    $party_id = generatePartyId($con);
    $party_type = $_POST['party_type'];
    $party_name = $con->real_escape_string($_POST['party_name']);
    $contact_person = $con->real_escape_string($_POST['contact_person']);
    $email = $con->real_escape_string($_POST['email']);
    $phone = $con->real_escape_string($_POST['phone']);
    $mobile = $con->real_escape_string($_POST['mobile']);
    $gst_no = $con->real_escape_string($_POST['gst_no']);
    $pan_no = $con->real_escape_string($_POST['pan_no']);
    $address = $con->real_escape_string($_POST['address']);
    $city = $con->real_escape_string($_POST['city']);
    $state = $con->real_escape_string($_POST['state']);
    $pincode = $con->real_escape_string($_POST['pincode']);
    $opening_balance = floatval($_POST['opening_balance']);
    $balance_type = $_POST['balance_type'];
    $credit_limit = floatval($_POST['credit_limit']);
    $payment_terms = $con->real_escape_string($_POST['payment_terms']);
    $bank_name = $con->real_escape_string($_POST['bank_name']);
    $bank_account = $con->real_escape_string($_POST['bank_account']);
    $ifsc_code = $con->real_escape_string($_POST['ifsc_code']);
    
    // Check if party already exists with same GST or PAN
    $check_sql = "SELECT id FROM parties WHERE (gst_no = '$gst_no' AND gst_no != '') OR (pan_no = '$pan_no' AND pan_no != '')";
    $check_result = $con->query($check_sql);
    
    if($check_result->num_rows > 0) {
        $_SESSION['error'] = "Party with same GST or PAN already exists!";
    } else {
        // Insert party
        $sql = "INSERT INTO parties (
            party_id, party_type, party_name, contact_person, email, phone, mobile,
            gst_no, pan_no, address, city, state, pincode, opening_balance,
            balance_type, credit_limit, payment_terms, bank_name, bank_account,
            ifsc_code, created_by, status
        ) VALUES (
            '$party_id', '$party_type', '$party_name', '$contact_person', '$email',
            '$phone', '$mobile', '$gst_no', '$pan_no', '$address', '$city',
            '$state', '$pincode', '$opening_balance', '$balance_type',
            '$credit_limit', '$payment_terms', '$bank_name', '$bank_account',
            '$ifsc_code', '$ca_id', 'active'
        )";
        
        if($con->query($sql)) {
            $_SESSION['success'] = "Party added successfully! Party ID: $party_id";
            header("Location: parties.php");
            exit();
        } else {
            $_SESSION['error'] = "Error adding party: " . $con->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add New Party - Legal Taxation</title>

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
        <a href="parties.php" class="nav-link">Parties</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Party</a>
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
            <h1 class="m-0">Add New Party/Vendor</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="parties.php">Parties</a></li>
              <li class="breadcrumb-item active">Add Party</li>
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
                <h3 class="card-title">Party Details</h3>
              </div>
              <!-- form start -->
              <form method="POST" action="">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Party Type</label>
                        <select name="party_type" class="form-control" required>
                          <option value="customer">Customer</option>
                          <option value="vendor">Vendor</option>
                          <option value="both">Both (Customer & Vendor)</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Party Name</label>
                        <input type="text" name="party_name" class="form-control" placeholder="Enter party name" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Contact person name">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email address" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="Landline number">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Mobile</label>
                        <input type="text" name="mobile" class="form-control" placeholder="Mobile number" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>GST Number</label>
                        <input type="text" name="gst_no" class="form-control" placeholder="GSTIN">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>PAN Number</label>
                        <input type="text" name="pan_no" class="form-control" placeholder="PAN">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Opening Balance</label>
                        <div class="input-group">
                          <input type="number" name="opening_balance" class="form-control" placeholder="0.00" step="0.01" value="0">
                          <div class="input-group-append">
                            <select name="balance_type" class="form-control">
                              <option value="debit">Debit (Dr)</option>
                              <option value="credit">Credit (Cr)</option>
                            </select>
                          </div>
                        </div>
                        <small class="text-muted">Debit means party owes you, Credit means you owe party</small>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Full address"></textarea>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" placeholder="City">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" placeholder="State">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" name="pincode" class="form-control" placeholder="Pincode">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Credit Limit (₹)</label>
                        <input type="number" name="credit_limit" class="form-control" placeholder="0.00" step="0.01" value="0">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Payment Terms</label>
                        <input type="text" name="payment_terms" class="form-control" placeholder="e.g., 30 Days">
                      </div>
                    </div>
                  </div>

                  <h5 class="mt-4 mb-3">Bank Details</h5>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" placeholder="Bank name">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Account Number</label>
                        <input type="text" name="bank_account" class="form-control" placeholder="Account number">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control" placeholder="IFSC code">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Add Party</button>
                  <button type="reset" class="btn btn-default">Reset</button>
                  <a href="parties.php" class="btn btn-secondary">Cancel</a>
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
  
  // Form validation
  $('form').submit(function() {
    // Additional validation can be added here
    return true;
  });
});
</script>
</body>
</html>