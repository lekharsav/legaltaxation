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

// Get invoice ID from URL
if(!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invoice ID not specified!";
    header("Location: purchase.php");
    exit();
}

$invoice_id = $_GET['id'];

// Get invoice details
$sql = $con->query("SELECT pi.*, p.cont as vendor_phone, p.email as vendor_email 
                    FROM purchase_invoices pi
                    LEFT JOIN parties p ON pi.party_id = p.id
                    WHERE pi.id='$invoice_id' AND pi.created_by='$ca_id'");
if($sql->num_rows == 0) {
    $_SESSION['error'] = "Invoice not found or access denied!";
    header("Location: purchase.php");
    exit();
}

$invoice = $sql->fetch_assoc();

// Get invoice items
$items = [];
$item_sql = $con->query("SELECT * FROM purchase_invoice_items WHERE invoice_id='$invoice_id'");
if($item_sql->num_rows > 0) {
    $items = $item_sql->fetch_all(MYSQLI_ASSOC);
}

// Get payments
$payments = [];
$payment_sql = $con->query("SELECT * FROM purchase_payments WHERE invoice_id='$invoice_id' ORDER BY payment_date DESC");
if($payment_sql->num_rows > 0) {
    $payments = $payment_sql->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Purchase Invoice - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .invoice-card {
      border-left: 4px solid #ffc107;
    }
    .invoice-header {
      background: linear-gradient(45deg, #ffc107, #fd7e14);
      color: white;
      padding: 20px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    .invoice-details {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
    }
    .amount-box {
      border: 2px solid #28a745;
      border-radius: 5px;
      padding: 15px;
      background: #f8fff9;
    }
    .status-badge {
      padding: 5px 15px;
      border-radius: 20px;
      font-weight: bold;
    }
    .badge-draft { background: #6c757d; color: white; }
    .badge-final { background: #28a745; color: white; }
    .badge-cancelled { background: #dc3545; color: white; }
    .badge-paid { background: #28a745; color: white; }
    .badge-unpaid { background: #ffc107; color: #212529; }
    .badge-partial { background: #17a2b8; color: white; }
    .item-table th {
      background: #f8f9fa;
    }
    .print-only { display: none; }
    @media print {
      .no-print { display: none !important; }
      .print-only { display: block !important; }
      body { font-size: 12px; }
      .invoice-header { background: #333 !important; -webkit-print-color-adjust: exact; }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light no-print">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="purchase.php" class="nav-link">Purchase</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link active">View Purchase</a>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 no-print">
    <?php include('sidebar.php'); ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header no-print">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Purchase Invoice</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="purchase.php">Purchase</a></li>
              <li class="breadcrumb-item active">View Purchase</li>
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
            <div class="card invoice-card">
              <div class="card-body">
                <!-- Invoice Header -->
                <div class="invoice-header">
                  <div class="row">
                    <div class="col-md-6">
                      <h3 class="m-0"><i class="fas fa-shopping-cart"></i> PURCHASE INVOICE</h3>
                      <h2 class="mt-2"><?php echo htmlspecialchars($invoice['invoice_no']); ?></h2>
                    </div>
                    <div class="col-md-6 text-right">
                      <h5>Legal Taxation</h5>
                      <p>CA: <?php echo htmlspecialchars($ca_name); ?><br>
                      CA ID: <?php echo htmlspecialchars($ca_id_number); ?></p>
                      <p class="print-only">
                        Date: <?php echo date('d M Y', strtotime($invoice['invoice_date'])); ?><br>
                        Due Date: <?php echo $invoice['due_date'] ? date('d M Y', strtotime($invoice['due_date'])) : 'N/A'; ?>
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Invoice Details -->
                <div class="row mb-4">
                  <div class="col-md-6">
                    <div class="invoice-details">
                      <h5><i class="fas fa-truck"></i> Purchased From:</h5>
                      <h4><?php echo htmlspecialchars($invoice['party_name']); ?></h4>
                      <?php if($invoice['party_gst']): ?>
                        <p><strong>GST:</strong> <?php echo htmlspecialchars($invoice['party_gst']); ?></p>
                      <?php endif; ?>
                      <p><?php echo nl2br(htmlspecialchars($invoice['party_address'])); ?></p>
                      <?php if(!empty($invoice['vendor_phone']) || !empty($invoice['vendor_email'])): ?>
                        <p>
                          <?php if(!empty($invoice['vendor_phone'])): ?>
                            <strong>Phone:</strong> <?php echo htmlspecialchars($invoice['vendor_phone']); ?><br>
                          <?php endif; ?>
                          <?php if(!empty($invoice['vendor_email'])): ?>
                            <strong>Email:</strong> <?php echo htmlspecialchars($invoice['vendor_email']); ?>
                          <?php endif; ?>
                        </p>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="invoice-details">
                      <div class="row">
                        <div class="col-md-6">
                          <p><strong>Invoice Date:</strong><br>
                          <?php echo date('d M Y', strtotime($invoice['invoice_date'])); ?></p>
                        </div>
                        <div class="col-md-6">
                          <p><strong>Due Date:</strong><br>
                          <?php echo $invoice['due_date'] ? date('d M Y', strtotime($invoice['due_date'])) : 'N/A'; ?></p>
                        </div>
                      </div>
                      <?php if($invoice['bill_no']): ?>
                        <p><strong>Vendor Bill No:</strong> <?php echo htmlspecialchars($invoice['bill_no']); ?></p>
                      <?php endif; ?>
                      <?php if($invoice['bill_date']): ?>
                        <p><strong>Vendor Bill Date:</strong> <?php echo date('d M Y', strtotime($invoice['bill_date'])); ?></p>
                      <?php endif; ?>
                      <?php if($invoice['reference_no']): ?>
                        <p><strong>Reference No:</strong> <?php echo htmlspecialchars($invoice['reference_no']); ?></p>
                      <?php endif; ?>
                      <div class="row mt-2">
                        <div class="col-md-6">
                          <p><strong>Invoice Status:</strong><br>
                          <?php 
                            $status_class = '';
                            $status_text = '';
                            switch($invoice['status']) {
                              case 'draft': $status_class = 'badge-draft'; $status_text = 'Draft'; break;
                              case 'final': $status_class = 'badge-final'; $status_text = 'Final'; break;
                              case 'cancelled': $status_class = 'badge-cancelled'; $status_text = 'Cancelled'; break;
                            }
                          ?>
                          <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></p>
                        </div>
                        <div class="col-md-6">
                          <p><strong>Payment Status:</strong><br>
                          <?php 
                            $payment_class = '';
                            $payment_text = '';
                            switch($invoice['payment_status']) {
                              case 'paid': $payment_class = 'badge-paid'; $payment_text = 'Paid'; break;
                              case 'unpaid': $payment_class = 'badge-unpaid'; $payment_text = 'Unpaid'; break;
                              case 'partial': $payment_class = 'badge-partial'; $payment_text = 'Partial'; break;
                            }
                          ?>
                          <span class="status-badge <?php echo $payment_class; ?>"><?php echo $payment_text; ?></span></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                  <table class="table table-bordered item-table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>HSN/SAC</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Rate (₹)</th>
                        <th>Discount (%)</th>
                        <th>Tax (%)</th>
                        <th class="text-right">Amount (₹)</th>
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
                            <td><?php echo htmlspecialchars($item['hsn_sac'] ?: '-'); ?></td>
                            <td><?php echo htmlspecialchars($item['unit']); ?></td>
                            <td><?php echo number_format($item['quantity'], 2); ?></td>
                            <td>₹<?php echo number_format($item['rate'], 2); ?></td>
                            <td><?php echo number_format($item['discount_percentage'], 2); ?>%</td>
                            <td><?php echo number_format($item['tax_percentage'], 2); ?>%</td>
                            <td class="text-right">₹<?php echo number_format($item['amount'], 2); ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="10" class="text-center">No items found</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>

                <!-- Totals Section -->
                <div class="row">
                  <div class="col-md-8">
                    <?php if($invoice['terms_conditions']): ?>
                      <div class="card">
                        <div class="card-header">
                          <h5 class="card-title">Terms & Conditions</h5>
                        </div>
                        <div class="card-body">
                          <?php echo nl2br(htmlspecialchars($invoice['terms_conditions'])); ?>
                        </div>
                      </div>
                    <?php endif; ?>
                    
                    <?php if($invoice['notes']): ?>
                      <div class="card mt-3">
                        <div class="card-header">
                          <h5 class="card-title">Notes</h5>
                        </div>
                        <div class="card-body">
                          <?php echo nl2br(htmlspecialchars($invoice['notes'])); ?>
                        </div>
                      </div>
                    <?php endif; ?>
                    
                    <!-- Payments History -->
                    <?php if(count($payments) > 0): ?>
                      <div class="card mt-3">
                        <div class="card-header">
                          <h5 class="card-title"><i class="fas fa-history"></i> Payment History</h5>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-sm">
                              <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Amount</th>
                                  <th>Mode</th>
                                  <th>Reference</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach($payments as $payment): ?>
                                  <tr>
                                    <td><?php echo date('d M Y', strtotime($payment['payment_date'])); ?></td>
                                    <td>₹<?php echo number_format($payment['amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $payment['payment_mode']))); ?></td>
                                    <td><?php echo htmlspecialchars($payment['reference_no'] ?: '-'); ?></td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="amount-box">
                      <h5 class="text-center mb-3">PURCHASE TOTAL</h5>
                      <table class="table table-sm">
                        <tr>
                          <td>Sub Total:</td>
                          <td class="text-right">₹<?php echo number_format($invoice['sub_total'], 2); ?></td>
                        </tr>
                        <tr>
                          <td>Discount:</td>
                          <td class="text-right">₹<?php echo number_format($invoice['discount_amount'], 2); ?></td>
                        </tr>
                        <tr>
                          <td>Taxable Amount:</td>
                          <td class="text-right">₹<?php echo number_format($invoice['taxable_amount'], 2); ?></td>
                        </tr>
                        <tr>
                          <td>Tax Amount:</td>
                          <td class="text-right">₹<?php echo number_format($invoice['tax_amount'], 2); ?></td>
                        </tr>
                        <?php if($invoice['round_off'] != 0): ?>
                          <tr>
                            <td>Round Off:</td>
                            <td class="text-right">₹<?php echo number_format($invoice['round_off'], 2); ?></td>
                          </tr>
                        <?php endif; ?>
                        <tr class="table-success">
                          <td><strong>GRAND TOTAL:</strong></td>
                          <td class="text-right"><strong>₹<?php echo number_format($invoice['grand_total'], 2); ?></strong></td>
                        </tr>
                        <tr>
                          <td>Paid Amount:</td>
                          <td class="text-right">₹<?php echo number_format($invoice['paid_amount'], 2); ?></td>
                        </tr>
                        <tr class="table-warning">
                          <td><strong>BALANCE DUE:</strong></td>
                          <td class="text-right"><strong>₹<?php echo number_format($invoice['balance_amount'], 2); ?></strong></td>
                        </tr>
                      </table>
                      
                      <?php if($invoice['payment_mode']): ?>
                        <p class="text-center mt-3">
                          <strong>Payment Mode:</strong><br>
                          <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $invoice['payment_mode']))); ?>
                        </p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <!-- Footer -->
                <div class="row mt-4">
                  <div class="col-md-12">
                    <div class="text-center">
                      <p class="text-muted">
                        Invoice Generated On: <?php echo date('d M Y, h:i A', strtotime($invoice['created_date'])); ?>
                        <?php if($invoice['updated_date']): ?>
                          <br>Last Updated: <?php echo date('d M Y, h:i A', strtotime($invoice['updated_date'])); ?>
                        <?php endif; ?>
                      </p>
                      <p class="print-only">
                        <strong>This is a computer generated purchase invoice.</strong>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Card Footer -->
              <div class="card-footer no-print">
                <div class="row">
                  <div class="col-md-12 text-center">
                    <button onclick="window.print()" class="btn btn-secondary">
                      <i class="fas fa-print"></i> Print Invoice
                    </button>
                    <?php if($invoice['status'] == 'draft'): ?>
                      <a href="edit_purchase.php?id=<?php echo $invoice_id; ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Invoice
                      </a>
                    <?php endif; ?>
                    <a href="purchase.php" class="btn btn-default">
                      <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <?php if($invoice['status'] == 'final' && $invoice['balance_amount'] > 0): ?>
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addPaymentModal">
                        <i class="fas fa-money-bill-wave"></i> Add Payment
                      </button>
                    <?php endif; ?>
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

  <!-- Add Payment Modal -->
  <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addPaymentModalLabel">Add Payment to Vendor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="add_purchase_payment.php">
          <div class="modal-body">
            <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
            <input type="hidden" name="invoice_no" value="<?php echo htmlspecialchars($invoice['invoice_no']); ?>">
            <input type="hidden" name="balance_amount" value="<?php echo $invoice['balance_amount']; ?>">
            
            <div class="form-group">
              <label>Payment Date</label>
              <input type="date" name="payment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="form-group">
              <label>Amount (₹)</label>
              <input type="number" name="amount" class="form-control" max="<?php echo $invoice['balance_amount']; ?>" step="0.01" min="0.01" required>
              <small class="text-muted">Maximum: ₹<?php echo number_format($invoice['balance_amount'], 2); ?></small>
            </div>
            
            <div class="form-group">
              <label>Payment Mode</label>
              <select name="payment_mode" class="form-control" required>
                <option value="cash">Cash</option>
                <option value="cheque">Cheque</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="upi">UPI</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Reference No</label>
              <input type="text" name="reference_no" class="form-control" placeholder="Cheque/Transaction number">
            </div>
            
            <div class="form-group">
              <label>Notes</label>
              <textarea name="notes" class="form-control" rows="2" placeholder="Payment notes"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Payment</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <footer class="main-footer no-print">
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

<script>
$(function () {
  // Auto-focus on amount field when payment modal opens
  $('#addPaymentModal').on('shown.bs.modal', function () {
    $(this).find('input[name="amount"]').focus();
  });
});
</script>
</body>
</html>