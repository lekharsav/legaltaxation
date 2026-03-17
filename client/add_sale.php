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

// Get parties (customers)
$parties = [];
$party_sql = $con->query("SELECT id, party_id, party_name, gst_no, address, city, state, pincode 
                          FROM parties 
                          WHERE (party_type='customer' OR party_type='both') 
                          AND status='active' AND created_by='$ca_id'
                          ORDER BY party_name ASC");
if($party_sql->num_rows > 0) {
    $parties = $party_sql->fetch_all(MYSQLI_ASSOC);
}

// Get items
$items = [];
$item_sql = $con->query("SELECT id, item_code, item_name, unit, hsn_sac_code, sales_price, sales_tax_rate, current_stock 
                         FROM items 
                         WHERE status='active' AND created_by='$ca_id'
                         ORDER BY item_name ASC");
if($item_sql->num_rows > 0) {
    $items = $item_sql->fetch_all(MYSQLI_ASSOC);
}

// Generate Invoice Number
function generateInvoiceNumber($con, $ca_id) {
    $prefix = "INV";
    $year = date('y');
    $month = date('m');
    
    // Get last invoice number for this CA
    $result = $con->query("SELECT invoice_no FROM sales_invoices WHERE created_by='$ca_id' ORDER BY id DESC LIMIT 1");
    if($result->num_rows > 0) {
        $last_invoice = $result->fetch_assoc()['invoice_no'];
        // Extract number and increment
        if(preg_match('/\d+$/', $last_invoice, $matches)) {
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
    $invoice_no = generateInvoiceNumber($con, $ca_id);
    $invoice_date = $con->real_escape_string($_POST['invoice_date']);
    $due_date = $con->real_escape_string($_POST['due_date']);
    $party_id = intval($_POST['party_id']);
    
    // Get party details
    $party_result = $con->query("SELECT party_name, gst_no, address FROM parties WHERE id='$party_id'");
    if($party_result->num_rows > 0) {
        $party = $party_result->fetch_assoc();
        $party_name = $party['party_name'];
        $party_gst = $party['gst_no'];
        $party_address = $party['address'];
    } else {
        $_SESSION['error'] = "Selected party not found!";
        header("Location: add_sale.php");
        exit();
    }
    
    // Process items
    $item_ids = $_POST['item_id'];
    $quantities = $_POST['quantity'];
    $rates = $_POST['rate'];
    $discounts = $_POST['discount'];
    $tax_rates = $_POST['tax_rate'];
    
    $sub_total = 0;
    $discount_amount = 0;
    $taxable_amount = 0;
    $tax_amount = 0;
    
    // Calculate totals
    $invoice_items = [];
    for($i = 0; $i < count($item_ids); $i++) {
        if(!empty($item_ids[$i]) && $quantities[$i] > 0) {
            $item_id = intval($item_ids[$i]);
            $quantity = floatval($quantities[$i]);
            $rate = floatval($rates[$i]);
            $discount = floatval($discounts[$i]);
            $tax_rate = floatval($tax_rates[$i]);
            
            // Get item details
            $item_result = $con->query("SELECT item_code, item_name, unit, hsn_sac_code FROM items WHERE id='$item_id'");
            if($item_result->num_rows > 0) {
                $item = $item_result->fetch_assoc();
                
                $amount = $quantity * $rate;
                $item_discount = ($amount * $discount) / 100;
                $taxable = $amount - $item_discount;
                $item_tax = ($taxable * $tax_rate) / 100;
                
                $invoice_items[] = [
                    'item_id' => $item_id,
                    'item_code' => $item['item_code'],
                    'item_name' => $item['item_name'],
                    'unit' => $item['unit'],
                    'hsn_sac' => $item['hsn_sac_code'],
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'discount_percentage' => $discount,
                    'discount_amount' => $item_discount,
                    'tax_percentage' => $tax_rate,
                    'tax_amount' => $item_tax,
                    'amount' => $taxable + $item_tax
                ];
                
                $sub_total += $amount;
                $discount_amount += $item_discount;
                $taxable_amount += $taxable;
                $tax_amount += $item_tax;
            }
        }
    }
    
    if(empty($invoice_items)) {
        $_SESSION['error'] = "Please add at least one item to the invoice!";
        header("Location: add_sale.php");
        exit();
    }
    
    // Calculate final totals
    $round_off = 0;
    $grand_total = $taxable_amount + $tax_amount;
    
    // Apply round off if needed
    if(isset($_POST['round_off']) && $_POST['round_off'] == '1') {
        $grand_total = round($grand_total);
        $round_off = $grand_total - ($taxable_amount + $tax_amount);
    }
    
    // Payment details
    $payment_status = $_POST['payment_status'];
    $paid_amount = floatval($_POST['paid_amount']);
    $balance_amount = $grand_total - $paid_amount;
    $payment_mode = $con->real_escape_string($_POST['payment_mode']);
    
    // Additional details
    $reference_no = $con->real_escape_string($_POST['reference_no']);
    $terms_conditions = $con->real_escape_string($_POST['terms_conditions']);
    $notes = $con->real_escape_string($_POST['notes']);
    
    // Status
    $status = $_POST['save_as'];
    
    // Start transaction
    $con->begin_transaction();
    
    try {
        // Insert invoice
        $sql = "INSERT INTO sales_invoices (
            invoice_no, invoice_date, due_date, party_id, party_name, party_gst, party_address,
            sub_total, discount_amount, taxable_amount, tax_amount, round_off, grand_total,
            payment_status, paid_amount, balance_amount, payment_mode,
            reference_no, terms_conditions, notes, created_by, status
        ) VALUES (
            '$invoice_no', '$invoice_date', '$due_date', '$party_id', '$party_name', '$party_gst', '$party_address',
            '$sub_total', '$discount_amount', '$taxable_amount', '$tax_amount', '$round_off', '$grand_total',
            '$payment_status', '$paid_amount', '$balance_amount', '$payment_mode',
            '$reference_no', '$terms_conditions', '$notes', '$ca_id', '$status'
        )";
        
        if($con->query($sql)) {
            $invoice_id = $con->insert_id;
            
            // Insert invoice items
            foreach($invoice_items as $item) {
                $item_sql = "INSERT INTO sales_invoice_items (
                    invoice_id, item_id, item_code, item_name, unit, hsn_sac,
                    quantity, rate, discount_percentage, discount_amount, tax_percentage, tax_amount, amount
                ) VALUES (
                    '$invoice_id', '{$item['item_id']}', '{$item['item_code']}', '{$item['item_name']}', '{$item['unit']}', '{$item['hsn_sac']}',
                    '{$item['quantity']}', '{$item['rate']}', '{$item['discount_percentage']}', '{$item['discount_amount']}', 
                    '{$item['tax_percentage']}', '{$item['tax_amount']}', '{$item['amount']}'
                )";
                
                if(!$con->query($item_sql)) {
                    throw new Exception("Error inserting invoice item: " . $con->error);
                }
                
                // Update stock if item is a product
                if($status == 'final') {
                    $check_item_type = $con->query("SELECT item_type FROM items WHERE id='{$item['item_id']}'");
                    if($check_item_type->num_rows > 0) {
                        $item_type = $check_item_type->fetch_assoc()['item_type'];
                        if($item_type == 'product') {
                            $con->query("UPDATE items SET current_stock = current_stock - {$item['quantity']} WHERE id='{$item['item_id']}'");
                        }
                    }
                }
            }
            
            // Insert payment if paid
            if($paid_amount > 0) {
                $payment_sql = "INSERT INTO sales_payments (
                    invoice_id, payment_date, amount, payment_mode, reference_no, notes, created_by
                ) VALUES (
                    '$invoice_id', '$invoice_date', '$paid_amount', '$payment_mode', '$reference_no', '$notes', '$ca_id'
                )";
                
                if(!$con->query($payment_sql)) {
                    throw new Exception("Error inserting payment: " . $con->error);
                }
            }
            
            // Commit transaction
            $con->commit();
            
            $_SESSION['success'] = "Invoice created successfully! Invoice No: $invoice_no";
            header("Location: " . ($status == 'draft' ? "edit_sale.php?id=$invoice_id" : "view_sale.php?id=$invoice_id"));
            exit();
        } else {
            throw new Exception("Error creating invoice: " . $con->error);
        }
    } catch (Exception $e) {
        // Rollback transaction
        $con->rollback();
        $_SESSION['error'] = $e->getMessage();
        header("Location: add_sale.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Sales Invoice - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <!-- Datepicker -->
  <link rel="stylesheet" href="plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
  
  <style>
    .required:after { content: " *"; color: red; }
    .section-header { background: #f8f9fa; padding: 10px; border-left: 4px solid #007bff; margin: 15px 0; }
    .item-row { border-bottom: 1px solid #dee2e6; padding: 10px 0; }
    .item-row:last-child { border-bottom: none; }
    .item-select { min-width: 250px; }
    .amount-cell { text-align: right; }
    .total-row { background: #f8f9fa; font-weight: bold; }
    .remove-item { color: #dc3545; cursor: pointer; }
    .remove-item:hover { color: #bd2130; }
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
        <a href="#" class="nav-link active">Create Invoice</a>
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
            <h1 class="m-0">Create Sales Invoice</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="sales.php">Sales</a></li>
              <li class="breadcrumb-item active">Create Invoice</li>
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
            <form method="POST" action="" id="invoiceForm">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Invoice Details</h3>
                </div>
                
                <div class="card-body">
                  <!-- Customer and Date Section -->
                  <div class="section-header">
                    <h5><i class="fas fa-user"></i> Customer & Date Information</h5>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Customer</label>
                        <select name="party_id" id="party_id" class="form-control select2" required onchange="loadPartyDetails(this.value)">
                          <option value="">Select Customer</option>
                          <?php foreach($parties as $party): ?>
                            <option value="<?php echo $party['id']; ?>" data-gst="<?php echo htmlspecialchars($party['gst_no']); ?>" data-address="<?php echo htmlspecialchars($party['address'] . ', ' . $party['city'] . ', ' . $party['state'] . ' - ' . $party['pincode']); ?>">
                              <?php echo htmlspecialchars($party['party_name'] . ' (' . $party['party_id'] . ')'); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required">Invoice Date</label>
                        <input type="text" name="invoice_date" class="form-control datepicker" value="<?php echo date('Y-m-d'); ?>" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Due Date</label>
                        <input type="text" name="due_date" class="form-control datepicker" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>GST Number</label>
                        <input type="text" id="party_gst" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Address</label>
                        <textarea id="party_address" class="form-control" rows="2" readonly></textarea>
                      </div>
                    </div>
                  </div>

                  <!-- Items Section -->
                  <div class="section-header">
                    <h5><i class="fas fa-shopping-cart"></i> Items Details</h5>
                    <button type="button" class="btn btn-sm btn-success" onclick="addItemRow()">
                      <i class="fas fa-plus"></i> Add Item
                    </button>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table" id="itemsTable">
                      <thead>
                        <tr>
                          <th width="30%">Item</th>
                          <th width="10%">Unit</th>
                          <th width="10%">HSN/SAC</th>
                          <th width="10%">Quantity</th>
                          <th width="10%">Rate (₹)</th>
                          <th width="10%">Discount (%)</th>
                          <th width="10%">Tax (%)</th>
                          <th width="10%">Amount (₹)</th>
                          <th width="5%"></th>
                        </tr>
                      </thead>
                      <tbody id="itemsBody">
                        <!-- Items will be added dynamically -->
                      </tbody>
                      <tfoot id="itemsFooter">
                        <!-- Totals will be calculated here -->
                      </tfoot>
                    </table>
                  </div>

                  <!-- Payment Section -->
                  <div class="section-header">
                    <h5><i class="fas fa-credit-card"></i> Payment Information</h5>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="required">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control" onchange="togglePaymentFields()">
                          <option value="unpaid">Unpaid</option>
                          <option value="partial">Partial</option>
                          <option value="paid">Paid</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Paid Amount (₹)</label>
                        <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="0" step="0.01" min="0" oninput="calculateBalance()">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Payment Mode</label>
                        <select name="payment_mode" class="form-control">
                          <option value="">Select Mode</option>
                          <option value="cash">Cash</option>
                          <option value="cheque">Cheque</option>
                          <option value="bank_transfer">Bank Transfer</option>
                          <option value="credit_card">Credit Card</option>
                          <option value="debit_card">Debit Card</option>
                          <option value="upi">UPI</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Balance Amount (₹)</label>
                        <input type="text" id="balance_amount" class="form-control" readonly>
                      </div>
                    </div>
                  </div>

                  <!-- Additional Details -->
                  <div class="section-header">
                    <h5><i class="fas fa-file-alt"></i> Additional Details</h5>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Reference No</label>
                        <input type="text" name="reference_no" class="form-control" placeholder="Reference number">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Round Off</label>
                        <div class="form-check">
                          <input type="checkbox" name="round_off" id="round_off" value="1" class="form-check-input" onchange="calculateTotals()">
                          <label class="form-check-label" for="round_off">Apply Round Off</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Save As</label>
                        <select name="save_as" class="form-control">
                          <option value="draft">Save as Draft</option>
                          <option value="final">Final Invoice</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Terms & Conditions</label>
                        <textarea name="terms_conditions" class="form-control" rows="3" placeholder="Terms and conditions"></textarea>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Invoice
                  </button>
                  <button type="reset" class="btn btn-default">Reset</button>
                  <a href="sales.php" class="btn btn-secondary">Cancel</a>
                </div>
              </div>
            </form>
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
<!-- Datepicker -->
<script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
// Items data from PHP
var items = <?php echo json_encode($items); ?>;
var itemRowCounter = 0;

$(function () {
  // Initialize Select2
  $('.select2').select2();
  
  // Initialize Datepicker
  $('.datepicker').datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true
  });
  
  // Add first item row
  addItemRow();
  
  // Calculate initial totals
  calculateTotals();
});

function loadPartyDetails(partyId) {
  if(partyId) {
    var selectedOption = $('#party_id option:selected');
    $('#party_gst').val(selectedOption.data('gst') || '');
    $('#party_address').val(selectedOption.data('address') || '');
  } else {
    $('#party_gst').val('');
    $('#party_address').val('');
  }
}

function addItemRow() {
  itemRowCounter++;
  var rowHtml = `
    <tr id="itemRow${itemRowCounter}" class="item-row">
      <td>
        <select name="item_id[]" class="form-control item-select" onchange="loadItemDetails(${itemRowCounter}, this.value)">
          <option value="">Select Item</option>
          ${items.map(item => `
            <option value="${item.id}" 
                    data-unit="${item.unit || ''}" 
                    data-hsn="${item.hsn_sac_code || ''}" 
                    data-rate="${item.sales_price || 0}" 
                    data-tax="${item.sales_tax_rate || 0}"
                    data-stock="${item.current_stock || 0}">
              ${item.item_name} (${item.item_code}) - Stock: ${item.current_stock || 0}
            </option>
          `).join('')}
        </select>
      </td>
      <td><input type="text" name="unit[]" id="unit${itemRowCounter}" class="form-control" readonly></td>
      <td><input type="text" name="hsn_sac[]" id="hsn${itemRowCounter}" class="form-control" readonly></td>
      <td><input type="number" name="quantity[]" id="quantity${itemRowCounter}" class="form-control" value="1" step="0.01" min="0.01" oninput="calculateItemAmount(${itemRowCounter})"></td>
      <td><input type="number" name="rate[]" id="rate${itemRowCounter}" class="form-control" value="0" step="0.01" min="0" oninput="calculateItemAmount(${itemRowCounter})"></td>
      <td><input type="number" name="discount[]" id="discount${itemRowCounter}" class="form-control" value="0" step="0.01" min="0" max="100" oninput="calculateItemAmount(${itemRowCounter})"></td>
      <td><input type="number" name="tax_rate[]" id="taxRate${itemRowCounter}" class="form-control" value="0" step="0.01" min="0" max="100" oninput="calculateItemAmount(${itemRowCounter})"></td>
      <td><input type="text" name="amount[]" id="amount${itemRowCounter}" class="form-control amount-cell" readonly></td>
      <td>
        <button type="button" class="btn btn-sm btn-danger remove-item" onclick="removeItemRow(${itemRowCounter})">
          <i class="fas fa-times"></i>
        </button>
      </td>
    </tr>
  `;
  
  $('#itemsBody').append(rowHtml);
  $('.item-select').last().select2();
}

function removeItemRow(rowId) {
  if($('#itemsBody tr').length > 1) {
    $('#itemRow' + rowId).remove();
    calculateTotals();
  } else {
    alert('Invoice must have at least one item!');
  }
}

function loadItemDetails(rowId, itemId) {
  if(itemId) {
    var selectedItem = items.find(item => item.id == itemId);
    if(selectedItem) {
      $('#unit' + rowId).val(selectedItem.unit || '');
      $('#hsn' + rowId).val(selectedItem.hsn_sac_code || '');
      $('#rate' + rowId).val(selectedItem.sales_price || 0);
      $('#taxRate' + rowId).val(selectedItem.sales_tax_rate || 0);
      
      // Show stock alert
      if(selectedItem.current_stock <= 0) {
        alert('Warning: This item is out of stock!');
      } else if(selectedItem.current_stock < 10) {
        alert('Warning: Low stock! Only ' + selectedItem.current_stock + ' units available.');
      }
      
      calculateItemAmount(rowId);
    }
  } else {
    $('#unit' + rowId).val('');
    $('#hsn' + rowId).val('');
    $('#rate' + rowId).val(0);
    $('#taxRate' + rowId).val(0);
    $('#amount' + rowId).val('');
  }
}

function calculateItemAmount(rowId) {
  var quantity = parseFloat($('#quantity' + rowId).val()) || 0;
  var rate = parseFloat($('#rate' + rowId).val()) || 0;
  var discount = parseFloat($('#discount' + rowId).val()) || 0;
  var taxRate = parseFloat($('#taxRate' + rowId).val()) || 0;
  
  var amount = quantity * rate;
  var discountAmount = (amount * discount) / 100;
  var taxable = amount - discountAmount;
  var taxAmount = (taxable * taxRate) / 100;
  var total = taxable + taxAmount;
  
  $('#amount' + rowId).val(total.toFixed(2));
  calculateTotals();
}

function calculateTotals() {
  var subTotal = 0;
  var discountTotal = 0;
  var taxableTotal = 0;
  var taxTotal = 0;
  var grandTotal = 0;
  
  // Calculate from all item rows
  $('tr[id^="itemRow"]').each(function() {
    var rowId = this.id.replace('itemRow', '');
    var quantity = parseFloat($('#quantity' + rowId).val()) || 0;
    var rate = parseFloat($('#rate' + rowId).val()) || 0;
    var discount = parseFloat($('#discount' + rowId).val()) || 0;
    var taxRate = parseFloat($('#taxRate' + rowId).val()) || 0;
    
    if(quantity > 0 && rate > 0) {
      var amount = quantity * rate;
      var discountAmount = (amount * discount) / 100;
      var taxable = amount - discountAmount;
      var taxAmount = (taxable * taxRate) / 100;
      
      subTotal += amount;
      discountTotal += discountAmount;
      taxableTotal += taxable;
      taxTotal += taxAmount;
    }
  });
  
  grandTotal = taxableTotal + taxTotal;
  
  // Apply round off if checked
  if($('#round_off').is(':checked')) {
    grandTotal = Math.round(grandTotal);
    var roundOff = grandTotal - (taxableTotal + taxTotal);
  } else {
    var roundOff = 0;
  }
  
  // Update footer
  var footerHtml = `
    <tr class="total-row">
      <td colspan="6" class="text-right"><strong>Sub Total:</strong></td>
      <td class="amount-cell"><strong>₹${subTotal.toFixed(2)}</strong></td>
      <td></td>
    </tr>
    <tr class="total-row">
      <td colspan="6" class="text-right"><strong>Discount:</strong></td>
      <td class="amount-cell"><strong>₹${discountTotal.toFixed(2)}</strong></td>
      <td></td>
    </tr>
    <tr class="total-row">
      <td colspan="6" class="text-right"><strong>Taxable Amount:</strong></td>
      <td class="amount-cell"><strong>₹${taxableTotal.toFixed(2)}</strong></td>
      <td></td>
    </tr>
    <tr class="total-row">
      <td colspan="6" class="text-right"><strong>Tax Amount:</strong></td>
      <td class="amount-cell"><strong>₹${taxTotal.toFixed(2)}</strong></td>
      <td></td>
    </tr>
    <tr class="total-row">
      <td colspan="6" class="text-right"><strong>Round Off:</strong></td>
      <td class="amount-cell"><strong>₹${roundOff.toFixed(2)}</strong></td>
      <td></td>
    </tr>
    <tr class="total-row bg-success">
      <td colspan="6" class="text-right"><strong>GRAND TOTAL:</strong></td>
      <td class="amount-cell"><strong>₹${grandTotal.toFixed(2)}</strong></td>
      <td></td>
    </tr>
  `;
  
  $('#itemsFooter').html(footerHtml);
  
  // Update hidden totals
  $('#grand_total').val(grandTotal);
  calculateBalance();
}

function togglePaymentFields() {
  var status = $('#payment_status').val();
  if(status == 'paid') {
    var grandTotal = parseFloat($('#grand_total').val()) || 0;
    $('#paid_amount').val(grandTotal).prop('readonly', true);
  } else if(status == 'partial') {
    $('#paid_amount').val(0).prop('readonly', false);
  } else {
    $('#paid_amount').val(0).prop('readonly', false);
  }
  calculateBalance();
}

function calculateBalance() {
  var grandTotal = parseFloat($('#grand_total').val()) || 0;
  var paidAmount = parseFloat($('#paid_amount').val()) || 0;
  var balance = grandTotal - paidAmount;
  $('#balance_amount').val('₹' + balance.toFixed(2));
}

// Form validation
$('#invoiceForm').submit(function(e) {
  // Validate at least one item with quantity > 0
  var hasItems = false;
  $('input[name="quantity[]"]').each(function() {
    if(parseFloat($(this).val()) > 0) {
      hasItems = true;
    }
  });
  
  if(!hasItems) {
    e.preventDefault();
    alert('Please add at least one item with quantity greater than 0!');
    return false;
  }
  
  // Validate customer selected
  if(!$('#party_id').val()) {
    e.preventDefault();
    alert('Please select a customer!');
    return false;
  }
  
  return true;
});
</script>
</body>
</html>