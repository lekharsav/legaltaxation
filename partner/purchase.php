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

// Handle invoice deletion (only drafts)
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $result = $con->query("SELECT status FROM purchase_invoices WHERE id='$delete_id' AND created_by='$ca_id'");
    if($result->num_rows > 0) {
        $invoice = $result->fetch_assoc();
        if($invoice['status'] == 'draft') {
            // Delete items first
            $con->query("DELETE FROM purchase_invoice_items WHERE invoice_id='$delete_id'");
            // Delete invoice
            $con->query("DELETE FROM purchase_invoices WHERE id='$delete_id'");
            $_SESSION['success'] = "Purchase invoice deleted successfully!";
        } else {
            $_SESSION['error'] = "Only draft invoices can be deleted!";
        }
    }
    header("Location: purchase.php");
    exit();
}

// Handle invoice cancellation
if(isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    $result = $con->query("SELECT status FROM purchase_invoices WHERE id='$cancel_id' AND created_by='$ca_id'");
    if($result->num_rows > 0) {
        $invoice = $result->fetch_assoc();
        if($invoice['status'] == 'final') {
            $con->query("UPDATE purchase_invoices SET status='cancelled' WHERE id='$cancel_id'");
            $_SESSION['success'] = "Purchase invoice cancelled successfully!";
        } else {
            $_SESSION['error'] = "Only final invoices can be cancelled!";
        }
    }
    header("Location: purchase.php");
    exit();
}

// Get all purchase invoices for this CA
$invoices = [];
$sql = $con->query("SELECT pi.*, p.party_name as vendor_name 
                    FROM purchase_invoices pi
                    LEFT JOIN parties p ON pi.party_id = p.id
                    WHERE pi.created_by='$ca_id' 
                    ORDER BY pi.created_date DESC");
if($sql->num_rows > 0) {
    $invoices = $sql->fetch_all(MYSQLI_ASSOC);
}

// Get statistics
$stats_sql = $con->query("SELECT 
                          COUNT(*) as total_invoices,
                          SUM(CASE WHEN status = 'final' THEN 1 ELSE 0 END) as final_invoices,
                          SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_invoices,
                          SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_invoices,
                          SUM(CASE WHEN status = 'final' THEN grand_total ELSE 0 END) as total_purchases,
                          SUM(CASE WHEN status = 'final' AND payment_status = 'unpaid' THEN balance_amount ELSE 0 END) as total_unpaid
                          FROM purchase_invoices WHERE created_by='$ca_id'");
$stats = $stats_sql->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Purchase Invoices - Legal Taxation</title>

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
    .invoice-badge {
      padding: 3px 8px;
      border-radius: 10px;
      font-size: 12px;
      font-weight: bold;
    }
    .badge-draft { background: #6c757d; color: white; }
    .badge-final { background: #28a745; color: white; }
    .badge-cancelled { background: #dc3545; color: white; }
    .badge-paid { background: #28a745; color: white; }
    .badge-unpaid { background: #ffc107; color: #212529; }
    .badge-partial { background: #17a2b8; color: white; }
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
        <a href="purchase.php" class="nav-link active">Purchase</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="purchase_report.php" class="nav-link">Reports</a>
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
            <h1 class="m-0">Purchase Invoices</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Purchase</li>
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

        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $stats['total_invoices'] ?: 0; ?></h3>
                <p>Total Purchase Invoices</p>
              </div>
              <div class="icon">
                <i class="fas fa-shopping-cart"></i>
              </div>
              <a href="purchase.php" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>₹<?php echo number_format($stats['total_purchases'] ?: 0, 2); ?></h3>
                <p>Total Purchases</p>
              </div>
              <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
              </div>
              <a href="purchase_report.php" class="small-box-footer">View Report <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>₹<?php echo number_format($stats['total_unpaid'] ?: 0, 2); ?></h3>
                <p>Total Unpaid</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <a href="purchase.php?filter=unpaid" class="small-box-footer">View Unpaid <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?php echo $stats['draft_invoices'] ?: 0; ?></h3>
                <p>Draft Invoices</p>
              </div>
              <div class="icon">
                <i class="fas fa-edit"></i>
              </div>
              <a href="purchase.php?filter=draft" class="small-box-footer">View Drafts <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Purchase Invoices</h3>
                <div class="card-tools">
                  <a href="add_purchase.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Create New Purchase
                  </a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="purchaseTable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Invoice No</th>
                      <th>Date</th>
                      <th>Vendor</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Payment</th>
                      <th>Balance</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($invoices) > 0): ?>
                      <?php $counter = 1; ?>
                      <?php foreach($invoices as $invoice): ?>
                        <tr>
                          <td><?php echo $counter++; ?></td>
                          <td><?php echo htmlspecialchars($invoice['invoice_no']); ?></td>
                          <td><?php echo date('d M Y', strtotime($invoice['invoice_date'])); ?></td>
                          <td><?php echo htmlspecialchars($invoice['vendor_name'] ?: $invoice['party_name']); ?></td>
                          <td>₹<?php echo number_format($invoice['grand_total'], 2); ?></td>
                          <td>
                            <?php 
                              $status_class = '';
                              $status_text = '';
                              switch($invoice['status']) {
                                case 'draft': $status_class = 'badge-draft'; $status_text = 'Draft'; break;
                                case 'final': $status_class = 'badge-final'; $status_text = 'Final'; break;
                                case 'cancelled': $status_class = 'badge-cancelled'; $status_text = 'Cancelled'; break;
                              }
                            ?>
                            <span class="invoice-badge <?php echo $status_class; ?>">
                              <?php echo $status_text; ?>
                            </span>
                          </td>
                          <td>
                            <?php 
                              $payment_class = '';
                              $payment_text = '';
                              switch($invoice['payment_status']) {
                                case 'paid': $payment_class = 'badge-paid'; $payment_text = 'Paid'; break;
                                case 'unpaid': $payment_class = 'badge-unpaid'; $payment_text = 'Unpaid'; break;
                                case 'partial': $payment_class = 'badge-partial'; $payment_text = 'Partial'; break;
                              }
                            ?>
                            <span class="invoice-badge <?php echo $payment_class; ?>">
                              <?php echo $payment_text; ?>
                            </span>
                          </td>
                          <td>₹<?php echo number_format($invoice['balance_amount'], 2); ?></td>
                          <td>
                            <a href="view_purchase.php?id=<?php echo $invoice['id']; ?>" class="btn btn-info btn-sm" title="View">
                              <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_purchase.php?id=<?php echo $invoice['id']; ?>" class="btn btn-primary btn-sm" title="Edit">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a href="print_purchase.php?id=<?php echo $invoice['id']; ?>" target="_blank" class="btn btn-secondary btn-sm" title="Print">
                              <i class="fas fa-print"></i>
                            </a>
                            <?php if($invoice['status'] == 'draft'): ?>
                              <button onclick="confirmDelete(<?php echo $invoice['id']; ?>)" class="btn btn-danger btn-sm" title="Delete">
                                <i class="fas fa-trash"></i>
                              </button>
                            <?php elseif($invoice['status'] == 'final'): ?>
                              <button onclick="confirmCancel(<?php echo $invoice['id']; ?>)" class="btn btn-warning btn-sm" title="Cancel">
                                <i class="fas fa-times"></i>
                              </button>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="9" class="text-center">No purchase invoices found. <a href="add_purchase.php">Create your first purchase invoice</a></td>
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
  $('#purchaseTable').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "order": [[1, 'desc']] // Sort by invoice date descending
  });
});

function confirmDelete(invoiceId) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This will delete the draft invoice permanently!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'purchase.php?delete_id=' + invoiceId;
    }
  });
}

function confirmCancel(invoiceId) {
  Swal.fire({
    title: 'Cancel Invoice?',
    text: "This will mark the invoice as cancelled!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ffc107',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, cancel it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'purchase.php?cancel_id=' + invoiceId;
    }
  });
}
</script>
</body>
</html>