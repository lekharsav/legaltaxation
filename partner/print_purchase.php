<?php
session_start();
include("../db.php");

// Check if user is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../partner-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Get invoice ID from URL
if(!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invoice ID not specified!");
}

$invoice_id = $_GET['id'];

// Get invoice details
$sql = $con->query("SELECT pi.*, p.cont as vendor_phone, p.email as vendor_email 
                    FROM purchase_invoices pi
                    LEFT JOIN parties p ON pi.party_id = p.id
                    WHERE pi.id='$invoice_id' AND pi.created_by='$ca_id'");
if($sql->num_rows == 0) {
    die("Invoice not found or access denied!");
}

$invoice = $sql->fetch_assoc();

// Get invoice items
$items = [];
$item_sql = $con->query("SELECT * FROM purchase_invoice_items WHERE invoice_id='$invoice_id'");
if($item_sql->num_rows > 0) {
    $items = $item_sql->fetch_all(MYSQLI_ASSOC);
}

// Get CA details
$ca_sql = $con->query("SELECT name, ca_id FROM ca WHERE id='$ca_id'");
$ca = $ca_sql->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Print Purchase Invoice - <?php echo htmlspecialchars($invoice['invoice_no']); ?></title>
  <style>
    @page { size: A4; margin: 0; }
    body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
    .container { max-width: 1000px; margin: 0 auto; }
    .header { display: flex; justify-content: space-between; margin-bottom: 30px; }
    .company-info { flex: 1; }
    .invoice-info { flex: 1; text-align: right; }
    .invoice-title { text-align: center; margin: 20px 0; }
    .details-section { display: flex; margin-bottom: 20px; }
    .vendor-info { flex: 1; }
    .invoice-details { flex: 1; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f5f5f5; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .totals { width: 300px; margin-left: auto; }
    .totals table { border: 2px solid #333; }
    .footer { margin-top: 50px; text-align: center; font-size: 10px; }
    .signature { margin-top: 50px; display: flex; justify-content: space-between; }
    .signature div { width: 200px; text-align: center; border-top: 1px solid #000; padding-top: 10px; }
    .print-btn { text-align: center; margin: 20px; }
    @media print {
      .print-btn { display: none; }
      body { padding: 0; }
    }
  </style>
</head>
<body>
  <div class="print-btn">
    <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px;">Print Invoice</button>
    <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; margin-left: 10px;">Close</button>
  </div>

  <div class="container">
    <!-- Header -->
    <div class="header">
      <div class="company-info">
        <h2>Legal Taxation</h2>
        <p><strong>CA:</strong> <?php echo htmlspecialchars($ca['name']); ?></p>
        <p><strong>CA ID:</strong> <?php echo htmlspecialchars($ca['ca_id']); ?></p>
      </div>
      <div class="invoice-info">
        <h2>PURCHASE INVOICE</h2>
        <p><strong>Invoice No:</strong> <?php echo htmlspecialchars($invoice['invoice_no']); ?></p>
        <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($invoice['invoice_date'])); ?></p>
        <?php if($invoice['due_date']): ?>
          <p><strong>Due Date:</strong> <?php echo date('d M Y', strtotime($invoice['due_date'])); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Vendor and Invoice Details -->
    <div class="details-section">
      <div class="vendor-info">
        <h4>Vendor Details:</h4>
        <p><strong><?php echo htmlspecialchars($invoice['party_name']); ?></strong></p>
        <?php if($invoice['party_gst']): ?>
          <p><strong>GST:</strong> <?php echo htmlspecialchars($invoice['party_gst']); ?></p>
        <?php endif; ?>
        <p><?php echo nl2br(htmlspecialchars($invoice['party_address'])); ?></p>
      </div>
      <div class="invoice-details">
        <?php if($invoice['bill_no']): ?>
          <p><strong>Vendor Bill No:</strong> <?php echo htmlspecialchars($invoice['bill_no']); ?></p>
        <?php endif; ?>
        <?php if($invoice['bill_date']): ?>
          <p><strong>Vendor Bill Date:</strong> <?php echo date('d M Y', strtotime($invoice['bill_date'])); ?></p>
        <?php endif; ?>
        <?php if($invoice['reference_no']): ?>
          <p><strong>Reference No:</strong> <?php echo htmlspecialchars($invoice['reference_no']); ?></p>
        <?php endif; ?>
        <p><strong>Status:</strong> <?php echo ucfirst($invoice['status']); ?> | 
           <strong>Payment:</strong> <?php echo ucfirst($invoice['payment_status']); ?></p>
      </div>
    </div>

    <!-- Items Table -->
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Item Code</th>
          <th>Item Name</th>
          <th>HSN/SAC</th>
          <th>Unit</th>
          <th>Quantity</th>
          <th>Rate</th>
          <th>Disc %</th>
          <th>Tax %</th>
          <th class="text-right">Amount</th>
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
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
      <table>
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
        <tr style="font-weight: bold; background-color: #f5f5f5;">
          <td>GRAND TOTAL:</td>
          <td class="text-right">₹<?php echo number_format($invoice['grand_total'], 2); ?></td>
        </tr>
        <tr>
          <td>Paid Amount:</td>
          <td class="text-right">₹<?php echo number_format($invoice['paid_amount'], 2); ?></td>
        </tr>
        <tr style="font-weight: bold; background-color: #fff3cd;">
          <td>BALANCE DUE:</td>
          <td class="text-right">₹<?php echo number_format($invoice['balance_amount'], 2); ?></td>
        </tr>
      </table>
    </div>

    <!-- Payment Mode -->
    <?php if($invoice['payment_mode']): ?>
      <div style="margin-top: 10px;">
        <strong>Payment Mode:</strong> <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $invoice['payment_mode']))); ?>
      </div>
    <?php endif; ?>

    <!-- Terms & Conditions -->
    <?php if($invoice['terms_conditions']): ?>
      <div style="margin-top: 20px;">
        <strong>Terms & Conditions:</strong>
        <p><?php echo nl2br(htmlspecialchars($invoice['terms_conditions'])); ?></p>
      </div>
    <?php endif; ?>

    <!-- Notes -->
    <?php if($invoice['notes']): ?>
      <div style="margin-top: 20px;">
        <strong>Notes:</strong>
        <p><?php echo nl2br(htmlspecialchars($invoice['notes'])); ?></p>
      </div>
    <?php endif; ?>

    <!-- Signature -->
    <div class="signature">
      <div>
        <p>Vendor Signature</p>
        <p>_________________________</p>
      </div>
      <div>
        <p>Authorized Signature</p>
        <p>_________________________</p>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p>This is a computer generated purchase invoice. No signature required.</p>
      <p>Generated on: <?php echo date('d M Y, h:i A', strtotime($invoice['created_date'])); ?></p>
      <?php if($invoice['updated_date']): ?>
        <p>Last Updated: <?php echo date('d M Y, h:i A', strtotime($invoice['updated_date'])); ?></p>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Auto print if needed
    window.onload = function() {
      if(window.location.search.includes('autoprint')) {
        window.print();
      }
    };
  </script>
</body>
</html>