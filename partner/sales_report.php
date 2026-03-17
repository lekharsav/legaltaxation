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

// Default date range (current month)
$start_date = date('Y-m-01');
$end_date = date('Y-m-t');

// Get filter parameters
if(isset($_GET['start_date']) && !empty($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
}
if(isset($_GET['end_date']) && !empty($_GET['end_date'])) {
    $end_date = $_GET['end_date'];
}

// Get sales summary
$summary_sql = $con->query("SELECT 
                          COUNT(*) as total_invoices,
                          SUM(sub_total) as total_sub_total,
                          SUM(discount_amount) as total_discount,
                          SUM(taxable_amount) as total_taxable,
                          SUM(tax_amount) as total_tax,
                          SUM(grand_total) as total_sales,
                          SUM(paid_amount) as total_paid,
                          SUM(balance_amount) as total_balance
                          FROM sales_invoices 
                          WHERE created_by='$ca_id' 
                          AND status='final'
                          AND invoice_date BETWEEN '$start_date' AND '$end_date'");
$summary = $summary_sql->fetch_assoc();

// Get daily sales
$daily_sales = [];
$daily_sql = $con->query("SELECT 
                         DATE(invoice_date) as sale_date,
                         COUNT(*) as invoice_count,
                         SUM(grand_total) as daily_total
                         FROM sales_invoices 
                         WHERE created_by='$ca_id' 
                         AND status='final'
                         AND invoice_date BETWEEN '$start_date' AND '$end_date'
                         GROUP BY DATE(invoice_date)
                         ORDER BY sale_date DESC");
if($daily_sql->num_rows > 0) {
    $daily_sales = $daily_sql->fetch_all(MYSQLI_ASSOC);
}

// Get top customers
$top_customers = [];
$customer_sql = $con->query("SELECT 
                            party_name,
                            COUNT(*) as invoice_count,
                            SUM(grand_total) as total_amount,
                            SUM(paid_amount) as paid_amount,
                            SUM(balance_amount) as balance_amount
                            FROM sales_invoices 
                            WHERE created_by='$ca_id' 
                            AND status='final'
                            AND invoice_date BETWEEN '$start_date' AND '$end_date'
                            GROUP BY party_name
                            ORDER BY total_amount DESC
                            LIMIT 10");
if($customer_sql->num_rows > 0) {
    $top_customers = $customer_sql->fetch_all(MYSQLI_ASSOC);
}

// Get sales by payment status
$payment_stats = [];
$payment_sql = $con->query("SELECT 
                           payment_status,
                           COUNT(*) as invoice_count,
                           SUM(grand_total) as total_amount
                           FROM sales_invoices 
                           WHERE created_by='$ca_id' 
                           AND status='final'
                           AND invoice_date BETWEEN '$start_date' AND '$end_date'
                           GROUP BY payment_status");
if($payment_sql->num_rows > 0) {
    $payment_stats = $payment_sql->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sales Report - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .stats-card {
      border-left: 4px solid;
      margin-bottom: 15px;
    }
    .stats-card.sales { border-color: #28a745; }
    .stats-card.paid { border-color: #17a2b8; }
    .stats-card.balance { border-color: #ffc107; }
    .stats-card.invoices { border-color: #6c757d; }
    .chart-container {
      position: relative;
      height: 300px;
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
        <a href="sales.php" class="nav-link">Sales</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="sales_report.php" class="nav-link active">Sales Report</a>
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
            <h1 class="m-0">Sales Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="sales.php">Sales</a></li>
              <li class="breadcrumb-item active">Sales Report</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Filter Form -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Filter Report</h3>
          </div>
          <div class="card-body">
            <form method="GET" action="">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Apply Filter
                      </button>
                      <a href="sales_report.php" class="btn btn-default">
                        <i class="fas fa-sync"></i> Reset
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Report Period</label>
                    <div>
                      <a href="sales_report.php?start_date=<?php echo date('Y-m-01'); ?>&end_date=<?php echo date('Y-m-t'); ?>" class="btn btn-sm btn-outline-primary">This Month</a>
                      <a href="sales_report.php?start_date=<?php echo date('Y-m-d', strtotime('-30 days')); ?>&end_date=<?php echo date('Y-m-d'); ?>" class="btn btn-sm btn-outline-primary">Last 30 Days</a>
                      <a href="sales_report.php?start_date=<?php echo date('Y-01-01'); ?>&end_date=<?php echo date('Y-12-31'); ?>" class="btn btn-sm btn-outline-primary">This Year</a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $summary['total_invoices'] ?: 0; ?></h3>
                <p>Total Invoices</p>
              </div>
              <div class="icon">
                <i class="fas fa-file-invoice"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>₹<?php echo number_format($summary['total_sales'] ?: 0, 2); ?></h3>
                <p>Total Sales</p>
              </div>
              <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>₹<?php echo number_format($summary['total_balance'] ?: 0, 2); ?></h3>
                <p>Outstanding</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>₹<?php echo number_format($summary['total_paid'] ?: 0, 2); ?></h3>
                <p>Amount Received</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Detailed Summary -->
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sales Summary</h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <tr>
                    <td width="70%">Sub Total:</td>
                    <td class="text-right">₹<?php echo number_format($summary['total_sub_total'] ?: 0, 2); ?></td>
                  </tr>
                  <tr>
                    <td>Discount:</td>
                    <td class="text-right">₹<?php echo number_format($summary['total_discount'] ?: 0, 2); ?></td>
                  </tr>
                  <tr>
                    <td>Taxable Amount:</td>
                    <td class="text-right">₹<?php echo number_format($summary['total_taxable'] ?: 0, 2); ?></td>
                  </tr>
                  <tr>
                    <td>Tax Amount:</td>
                    <td class="text-right">₹<?php echo number_format($summary['total_tax'] ?: 0, 2); ?></td>
                  </tr>
                  <tr class="table-success">
                    <td><strong>Grand Total Sales:</strong></td>
                    <td class="text-right"><strong>₹<?php echo number_format($summary['total_sales'] ?: 0, 2); ?></strong></td>
                  </tr>
                  <tr>
                    <td>Amount Received:</td>
                    <td class="text-right">₹<?php echo number_format($summary['total_paid'] ?: 0, 2); ?></td>
                  </tr>
                  <tr class="table-warning">
                    <td><strong>Outstanding Balance:</strong></td>
                    <td class="text-right"><strong>₹<?php echo number_format($summary['total_balance'] ?: 0, 2); ?></strong></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Payment Status Distribution</h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Status</th>
                      <th>Invoices</th>
                      <th>Amount</th>
                      <th>Percentage</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($payment_stats) > 0): ?>
                      <?php foreach($payment_stats as $stat): ?>
                        <tr>
                          <td>
                            <?php 
                              $badge_class = '';
                              switch($stat['payment_status']) {
                                case 'paid': $badge_class = 'badge-success'; break;
                                case 'unpaid': $badge_class = 'badge-warning'; break;
                                case 'partial': $badge_class = 'badge-info'; break;
                              }
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                              <?php echo ucfirst($stat['payment_status']); ?>
                            </span>
                          </td>
                          <td><?php echo $stat['invoice_count']; ?></td>
                          <td>₹<?php echo number_format($stat['total_amount'], 2); ?></td>
                          <td>
                            <?php 
                              if($summary['total_sales'] > 0) {
                                $percentage = ($stat['total_amount'] / $summary['total_sales']) * 100;
                                echo number_format($percentage, 1) . '%';
                              } else {
                                echo '0%';
                              }
                            ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="4" class="text-center">No payment data available</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Daily Sales Report -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Daily Sales Report</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="dailySalesTable" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Invoices</th>
                        <th>Sales Amount</th>
                        <th>Average per Invoice</th>
                        <th>Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($daily_sales) > 0): ?>
                        <?php foreach($daily_sales as $daily): ?>
                          <tr>
                            <td><?php echo date('d M Y', strtotime($daily['sale_date'])); ?></td>
                            <td><?php echo $daily['invoice_count']; ?></td>
                            <td>₹<?php echo number_format($daily['daily_total'], 2); ?></td>
                            <td>₹<?php echo number_format($daily['daily_total'] / $daily['invoice_count'], 2); ?></td>
                            <td>
                              <a href="sales.php?date=<?php echo $daily['sale_date']; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="5" class="text-center">No sales data for selected period</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Customers -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Top 10 Customers</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table id="topCustomersTable" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Invoices</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Balance</th>
                        <th>Avg per Invoice</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(count($top_customers) > 0): ?>
                        <?php $rank = 1; ?>
                        <?php foreach($top_customers as $customer): ?>
                          <tr>
                            <td><?php echo $rank++; ?></td>
                            <td><?php echo htmlspecialchars($customer['party_name']); ?></td>
                            <td><?php echo $customer['invoice_count']; ?></td>
                            <td>₹<?php echo number_format($customer['total_amount'], 2); ?></td>
                            <td>₹<?php echo number_format($customer['paid_amount'], 2); ?></td>
                            <td>
                              <?php if($customer['balance_amount'] > 0): ?>
                                <span class="badge badge-warning">₹<?php echo number_format($customer['balance_amount'], 2); ?></span>
                              <?php else: ?>
                                <span class="badge badge-success">Paid</span>
                              <?php endif; ?>
                            </td>
                            <td>₹<?php echo number_format($customer['total_amount'] / $customer['invoice_count'], 2); ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="7" class="text-center">No customer data available</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
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
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- Chart.js -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(function () {
  // Initialize DataTables
  $('#dailySalesTable').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "order": [[0, 'desc']] // Sort by date descending
  });
  
  $('#topCustomersTable').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
    "order": [[3, 'desc']] // Sort by total amount descending
  });
});
</script>
</body>
</html>