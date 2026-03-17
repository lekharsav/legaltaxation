<?php
// payment-success.php
// Full page replacement with fixes for undefined variables and mail warnings.

session_start();
include("db.php"); // expects $con as mysqli connection

// Helper to safely fetch GET params
$oid = isset($_GET['oid']) ? trim($_GET['oid']) : '';

if ($oid === '') {
    // show friendly message and stop
    http_response_code(400);
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Payment Success</title></head><body>';
    echo '<p>Missing order id (oid). Cannot process payment success page.</p>';
    echo '<a href="index.php">Back to Home</a>';
    echo '</body></html>';
    exit;
}

// 1) Mark payment status = 'Success' for the order (prepared statement)
$sql1 = "UPDATE `razorpaybill` SET `payment_status` = 'Success' WHERE `order_id` = ? LIMIT 1";
if ($stmt1 = $con->prepare($sql1)) {
    $stmt1->bind_param("s", $oid);
    $stmt1->execute();
    $stmt1->close();
} else {
    // handle prepare error silently
    error_log("DB prepare failed (update payment_status): " . $con->error);
}

// 2) Generate CID and set default password (as previously)
// You may want to use a stronger initial password or force reset on first login.
$cid = 'AIM' . rand(1111111, 9999999);
$default_password_plain = '12345';
$default_password_encoded = base64_encode($default_password_plain);

// Update cid and password
$sqlUpdateCID = "UPDATE `razorpaybill` SET `cid` = ?, `password` = ? WHERE `order_id` = ? LIMIT 1";
if ($stmt2 = $con->prepare($sqlUpdateCID)) {
    $stmt2->bind_param("sss", $cid, $default_password_encoded, $oid);
    $stmt2->execute();
    $stmt2->close();
} else {
    error_log("DB prepare failed (update cid/password): " . $con->error);
}

// 3) Fetch the row for email + billing_name etc.
$sqlSelect = "SELECT * FROM `razorpaybill` WHERE `order_id` = ? LIMIT 1";
$fetch = [];
if ($stmt3 = $con->prepare($sqlSelect)) {
    $stmt3->bind_param("s", $oid);
    $stmt3->execute();
    $result = $stmt3->get_result();
    $fetch = $result->fetch_assoc() ?: [];
    $stmt3->close();
} else {
    error_log("DB prepare failed (select razorpaybill): " . $con->error);
}

// Set email/name/userid safely (fallbacks if missing)
$email  = isset($fetch['billing_email']) && filter_var($fetch['billing_email'], FILTER_VALIDATE_EMAIL) ? $fetch['billing_email'] : '';
$name   = isset($fetch['billing_name']) ? $fetch['billing_name'] : '';
// prefer cid from DB if present (after update), otherwise use generated $cid
$userid = !empty($fetch['cid']) ? $fetch['cid'] : $cid;

// Prepare email content only if we have a valid email
if ($email !== '') {
    $to = $email;
    $subject = "Welcome to AIM Digitalise - Monthly Rental Software";
    // Use htmlspecialchars on name when injecting into HTML to avoid accidental tags
    $safe_name = htmlspecialchars($name === '' ? 'Customer' : $name, ENT_QUOTES, 'UTF-8');
    $safe_userid = htmlspecialchars($userid, ENT_QUOTES, 'UTF-8');
    $safe_password_plain = htmlspecialchars($default_password_plain, ENT_QUOTES, 'UTF-8');

    $message = "<html>
                <head><title>Welcome to AIM Digitalise</title></head>
                <body>
                    <h2>Hello {$safe_name},</h2>
                    <p>Thank you for choosing AIM Digitalise for your monthly rental software needs!</p>
                    <p>We are excited to have you on board and are committed to providing you with the best service and support.</p>
                    <p><strong>Here's what to do next:</strong></p>
                    <ul>
                        <li>Log in to your account to access the software.</li>
                        <li><strong>UserId:</strong> {$safe_userid}</li>
                        <li><strong>Password:</strong> {$safe_password_plain}</li>
                        <li><a href=\"https://aimdigitalise.com/portal/customerlogin.php\">Login to Your Dashboard</a></li>
                        <li>Contact our support team for any assistance or guidance.</li>
                    </ul>
                    <p><strong>Support Details:</strong></p>
                    <ul>
                        <li>Email: support@aimdigitalise.in</li>
                        <li>Phone: 91 9110642507</li>
                    </ul>
                    <p>We're here to help you succeed!</p>
                    <p>Best Regards,</p>
                    <p><strong>The AIM Digitalise Team</strong></p>
                </body>
                </html>";

    // Headers
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: AIM Digitalise <info@aimdigitalise.com>" . "\r\n";
    // optionally add Reply-To
    $headers .= "Reply-To: support@aimdigitalise.in" . "\r\n";

    // Try sending mail. Suppress direct warnings with @ and check return value.
    // NOTE: Using @mail hides PHP warning when no local mailserver available.
    // For production, use PHPMailer or similar with SMTP (recommended).
    $mail_sent = false;
    try {
        $mail_sent = @mail($to, $subject, $message, $headers);
        if (!$mail_sent) {
            // log reason — mail() gives no detailed error; just log the event
            error_log("Mail sending failed for order_id={$oid}, to={$to}");
        }
    } catch (\Throwable $e) {
        // just log the exception
        error_log("Mail exception for order_id={$oid}: " . $e->getMessage());
        $mail_sent = false;
    }
} else {
    // No valid email provided
    error_log("No valid email for order_id={$oid}");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .success-container {
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 100%;
        }

        .success-icon {
            margin-bottom: 20px;
        }

        .success-title {
            font-size: 24px;
            color: #4caf50;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .success-message {
            font-size: 16px;
            color: #555555;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .payment-details {
            font-size: 14px;
            color: #333333;
            margin-bottom: 30px;
            text-align: left;
            padding: 0 10px;
        }

        .success-btn {
            display: inline-block;
            text-decoration: none;
            background-color: #4caf50;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .success-btn:hover {
            background-color: #43a047;
        }

        .note {
            margin-top: 10px;
            font-size: 13px;
            color: #666;
        }

    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#4caf50" width="72" height="72">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.59L5.91 12.5 7.34 11.08l2.66 2.67 6.34-6.34 1.41 1.41-7.75 7.77z"/>
            </svg>
        </div>

        <h1 class="success-title">Payment Successful</h1>

        <p class="success-message">
            Thank you for your payment! Your transaction has been completed successfully.
        </p>

        <div class="payment-details">
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($oid, ENT_QUOTES, 'UTF-8'); ?></p>

            <?php if (!empty($fetch['amount'])): ?>
                <p><strong>Amount:</strong> <?php echo htmlspecialchars($fetch['amount'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <?php if (!empty($fetch['created_at'])): ?>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($fetch['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($name ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>UserID:</strong> <?php echo htmlspecialchars($userid, ENT_QUOTES, 'UTF-8'); ?></p>

            <?php if (isset($mail_sent)): ?>
                <p class="note">
                    <?php if ($mail_sent): ?>
                        Confirmation email sent to <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>.
                    <?php else: ?>
                        Confirmation email could not be sent automatically. If you did not receive an email, contact support@aimdigitalise.in.
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>

        <a href="index.php" class="success-btn">Back to Home</a>
    </div>

<?php
// OPTIONAL: clear specific session keys if used. Do NOT destroy session if you rely on it elsewhere.
// Example: unset($_SESSION['checkout_cart'], $_SESSION['razorpay_order']); 
// If you really want to destroy session data related to checkout specifically, unset them above.

?>
</body>
</html>