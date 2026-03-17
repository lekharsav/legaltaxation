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

// Handle payment addition
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $invoice_id = intval($_POST['invoice_id']);
    $invoice_no = $con->real_escape_string($_POST['invoice_no']);
    $payment_date = $con->real_escape_string($_POST['payment_date']);
    $amount = floatval($_POST['amount']);
    $payment_mode = $con->real_escape_string($_POST['payment_mode']);
    $reference_no = $con->real_escape_string($_POST['reference_no']);
    $notes = $con->real_escape_string($_POST['notes']);
    
    // Get current invoice details
    $invoice_result = $con->query("SELECT paid_amount, balance_amount, grand_total FROM purchase_invoices WHERE id='$invoice_id' AND created_by='$ca_id'");
    if($invoice_result->num_rows > 0) {
        $invoice = $invoice_result->fetch_assoc();
        $current_paid = $invoice['paid_amount'];
        $current_balance = $invoice['balance_amount'];
        $grand_total = $invoice['grand_total'];
        
        // Validate amount
        if($amount <= 0) {
            $_SESSION['error'] = "Payment amount must be greater than 0!";
            header("Location: view_purchase.php?id=$invoice_id");
            exit();
        }
        
        if($amount > $current_balance) {
            $_SESSION['error'] = "Payment amount cannot exceed balance amount!";
            header("Location: view_purchase.php?id=$invoice_id");
            exit();
        }
        
        // Start transaction
        $con->begin_transaction();
        
        try {
            // Insert payment
            $payment_sql = "INSERT INTO purchase_payments (
                invoice_id, payment_date, amount, payment_mode, reference_no, notes, created_by
            ) VALUES (
                '$invoice_id', '$payment_date', '$amount', '$payment_mode', '$reference_no', '$notes', '$ca_id'
            )";
            
            if($con->query($payment_sql)) {
                // Update invoice payment status
                $new_paid = $current_paid + $amount;
                $new_balance = $current_balance - $amount;
                
                // Determine payment status
                if($new_balance <= 0) {
                    $payment_status = 'paid';
                } else {
                    $payment_status = 'partial';
                }
                
                $update_sql = "UPDATE purchase_invoices SET 
                              paid_amount = '$new_paid',
                              balance_amount = '$new_balance',
                              payment_status = '$payment_status',
                              payment_mode = '$payment_mode',
                              updated_date = NOW()
                              WHERE id = '$invoice_id'";
                
                if($con->query($update_sql)) {
                    // Commit transaction
                    $con->commit();
                    $_SESSION['success'] = "Payment of ₹" . number_format($amount, 2) . " added successfully!";
                } else {
                    throw new Exception("Error updating purchase invoice: " . $con->error);
                }
            } else {
                throw new Exception("Error adding payment: " . $con->error);
            }
        } catch (Exception $e) {
            // Rollback transaction
            $con->rollback();
            $_SESSION['error'] = $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Purchase invoice not found!";
    }
    
    header("Location: view_purchase.php?id=$invoice_id");
    exit();
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: purchase.php");
    exit();
}
?>