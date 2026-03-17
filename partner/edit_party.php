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

// Get party ID from URL
if(!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Party ID not specified!";
    header("Location: parties.php");
    exit();
}

$party_id = $_GET['id'];

// Get party details
$sql = $con->query("SELECT * FROM parties WHERE id='$party_id' AND created_by='$ca_id'");
if($sql->num_rows == 0) {
    $_SESSION['error'] = "Party not found or access denied!";
    header("Location: parties.php");
    exit();
}

$party = $sql->fetch_assoc();

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    
    // Check if GST/PAN already exists (excluding current party)
    $check_sql = "SELECT id FROM parties WHERE id != '$party_id' AND 
                  ((gst_no = '$gst_no' AND gst_no != '') OR (pan_no = '$pan_no' AND pan_no != ''))";
    $check_result = $con->query($check_sql);
    
    if($check_result->num_rows > 0) {
        $_SESSION['error'] = "Another party with same GST or PAN already exists!";
    } else {
        // Update party
        $sql = "UPDATE parties SET
            party_type = '$party_type',
            party_name = '$party_name',
            contact_person = '$contact_person',
            email = '$email',
            phone = '$phone',
            mobile = '$mobile',
            gst_no = '$gst_no',
            pan_no = '$pan_no',
            address = '$address',
            city = '$city',
            state = '$state',
            pincode = '$pincode',
            opening_balance = '$opening_balance',
            balance_type = '$balance_type',
            credit_limit = '$credit_limit',
            payment_terms = '$payment_terms',
            bank_name = '$bank_name',
            bank_account = '$bank_account',
            ifsc_code = '$ifsc_code'
        WHERE id = '$party_id' AND created_by = '$ca_id'";
        
        if($con->query($sql)) {
            $_SESSION['success'] = "Party updated successfully!";
            header("Location: parties.php");
            exit();
        } else {
            $_SESSION['error'] = "Error updating party: " . $con->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Party - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
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
        <a href="#" class="nav-link">Edit Party</a>
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
            <h1 class="m-0">Edit Party: <?php echo htmlspecialchars($party['party_name']); ?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="parties.php">Parties</a></li>
              <li class="breadcrumb-item active">Edit Party</li>
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
                <h3 class="card-title">Edit Party Details</h3>
              </div>
              <!-- form start -->
              <form method="POST" action="">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Party Type</label>
                        <select name="party_type" class="form-control" required>
                          <option value="customer" <?php echo $party['party_type'] == 'customer' ? 'selected' : ''; ?>>Customer</option>
                          <option value="vendor" <?php echo $party['party_type'] == 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                          <option value="both" <?php echo $party['party_type'] == 'both' ? 'selected' : ''; ?>>Both (Customer & Vendor)</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Party Name</label>
                        <input type="text" name="party_name" class="form-control" value="<?php echo htmlspecialchars($party['party_name']); ?>" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" value="<?php echo htmlspecialchars($party['contact_person']); ?>">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($party['email']); ?>" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($party['phone']); ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Mobile</label>
                        <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($party['mobile']); ?>" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>GST Number</label>
                        <input type="text" name="gst_no" class="form-control" value="<?php echo htmlspecialchars($party['gst_no']); ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>PAN Number</label>
                        <input type="text" name="pan_no" class="form-control" value="<?php echo htmlspecialchars($party['pan_no']); ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Opening Balance</label>
                        <div class="input-group">
                          <input type="number" name="opening_balance" class="form-control" value="<?php echo $party['opening_balance']; ?>" step="0.01">
                          <div class="input-group-append">
                            <select name="balance_type" class="form-control">
                              <option value="debit" <?php echo $party['balance_type'] == 'debit' ? 'selected' : ''; ?>>Debit (Dr)</option>
                              <option value="credit" <?php echo $party['balance_type'] == 'credit' ? 'selected' : ''; ?>>Credit (Cr)</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($party['address']); ?></textarea>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($party['city']); ?>">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" class="form-control" value="<?php echo htmlspecialchars($party['state']); ?>">
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" name="pincode" class="form-control" value="<?php echo htmlspecialchars($party['pincode']); ?>">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Credit Limit (₹)</label>
                        <input type="number" name="credit_limit" class="form-control" value="<?php echo $party['credit_limit']; ?>" step="0.01">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Payment Terms</label>
                        <input type="text" name="payment_terms" class="form-control" value="<?php echo htmlspecialchars($party['payment_terms']); ?>">
                      </div>
                    </div>
                  </div>

                  <h5 class="mt-4 mb-3">Bank Details</h5>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="<?php echo htmlspecialchars($party['bank_name']); ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Account Number</label>
                        <input type="text" name="bank_account" class="form-control" value="<?php echo htmlspecialchars($party['bank_account']); ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control" value="<?php echo htmlspecialchars($party['ifsc_code']); ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update Party</button>
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
  $('.select2').select2();
});
</script>
</body>
</html>