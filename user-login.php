<?php
// user-login.php
// Fixed: no "headers already sent" warning, session flash messages, prepared statements.

// Hide PHP warnings from being displayed to users (optional for production)
ini_set('display_errors', 0);
error_reporting(0);

session_start();
require_once __DIR__ . '/db.php'; // expects $con as mysqli

// ----------------------
// Helpers
// ----------------------
function flash($text) {
    $_SESSION['flash_msg'] = $text;
}
function get_flash() {
    if (!empty($_SESSION['flash_msg'])) {
        $m = $_SESSION['flash_msg'];
        unset($_SESSION['flash_msg']);
        return $m;
    }
    return '';
}

// Safe fetch of GET/POST
$redirect_url = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
$auto_apply   = isset($_GET['apply']) ? intval($_GET['apply']) : 0;

// Preserve POST-hidden values on redirect: if present in POST, use those to override
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : $redirect_url;
    $auto_apply   = isset($_POST['auto_apply']) ? intval($_POST['auto_apply']) : $auto_apply;
}

// ------------------------------------------------------------------
// If a cookie exists, validate it before trusting.
// If valid -> redirect immediately (no output should be sent before header()).
// ------------------------------------------------------------------
if (!empty($_COOKIE['tax_customer_log'])) {
    $check_id = $_COOKIE['tax_customer_log'];

    $valid = false;
    if ($stmt = $con->prepare("SELECT id FROM customer WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $check_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $valid = true;
        }
        $stmt->close();
    }

    if ($valid) {
        // cookie valid — redirect to requested page (add apply param if needed)
        if ($auto_apply === 1) {
            $redirect_url .= (strpos($redirect_url, '?') === false) ? '?apply=1' : '&apply=1';
        }
        header('Location: ' . $redirect_url);
        exit();
    } else {
        // Cookie stale — clear it on common paths BEFORE any output
        $past  = time() - 3600;
        $paths = ['/', '/legalTaxation/', '/legalTaxation'];
        foreach ($paths as $p) {
            // setcookie parameters: name, value, expire, path
            setcookie('tax_customer_log', '', $past, $p);
        }
        unset($_COOKIE['tax_customer_log']);
        // fall through to login form
    }
}

// ------------------------------------------------------------------
// Handle login POST (all header/setcookie calls occur BEFORE any output)
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $cont = isset($_POST['cont']) ? trim($_POST['cont']) : '';
    $pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    // basic validation
    if ($cont === '' || $pass === '') {
        // preserve entered mobile for UX
        $_SESSION['old_cont'] = $cont;
        flash('Please enter both mobile number and password.');
        // redirect back to keep logic consistent and avoid re-posts
        header('Location: user-login.php?redirect=' . urlencode($redirect_url) . '&apply=' . intval($auto_apply));
        exit();
    }

    // Use prepared statement for login check (keeps your plaintext password logic for now)
    if ($stmt = $con->prepare("SELECT id, status FROM customer WHERE contact = ? AND password = ? LIMIT 1")) {
        $stmt->bind_param('ss', $cont, $pass);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            if ((int)$row['status'] === 1) {
                $id = (int)$row['id'];
                // Set cookie with explicit path '/' before any output so logout can clear it reliably
                setcookie('tax_customer_log', $id, time() + 60 * 60 * 24 * 30, '/');

                // Add auto-apply param if needed
                if ($auto_apply === 1) {
                    $redirect_url .= (strpos($redirect_url, '?') === false) ? '?apply=1' : '&apply=1';
                }

                header('Location: ' . $redirect_url);
                exit();
            } else {
                // not approved
                $_SESSION['old_cont'] = $cont;
                flash('Your Profile Is Not Approved Yet!! Please Try a While');
                header('Location: user-login.php?redirect=' . urlencode($redirect_url) . '&apply=' . intval($auto_apply));
                exit();
            }
        } else {
            // invalid credentials
            $_SESSION['old_cont'] = $cont;
            flash('Invalid Login Details');
            header('Location: user-login.php?redirect=' . urlencode($redirect_url) . '&apply=' . intval($auto_apply));
            exit();
        }
    } else {
        // DB prepare failed
        error_log('DB prepare failed (customer login): ' . $con->error);
        $_SESSION['old_cont'] = $cont;
        flash('Server error — please try again later.');
        header('Location: user-login.php?redirect=' . urlencode($redirect_url) . '&apply=' . intval($auto_apply));
        exit();
    }
}

// At this point: no header() will be called later, safe to render HTML below.
// Retrieve flash message (if any) for display in the HTML.
$flash_msg = get_flash();

// Old input handling (when we redirected back after failed login)
$old_cont = '';
if (!empty($_SESSION['old_cont'])) {
    $old_cont = $_SESSION['old_cont'];
    unset($_SESSION['old_cont']);
}
?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
<title>Customer Login - Legal Taxation</title>
<link rel="shortcut icon" href="images/favicon.webp" type="image/x-icon" />
<meta charset="utf-8">
<meta name="author" content="">
<meta name="keywords" content="">
<meta name="description" content="">		
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
 <!--styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
<link href="css/slick.css" rel="stylesheet">
<link href="css/slick-theme.css" rel="stylesheet">

<!-- <style>
    /* (keep your existing CSS here — unchanged) */
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
    }
    /* ... rest of your existing styles ... */
    /* For brevity I won't repeat the entire style block here in the example; keep yours unchanged */

    
    .customer-login-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 50px 0;
    }
    
    .login-container-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-top: 30px;
    }
    
    .login-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 50px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 500px;
        position: relative;
        overflow: hidden;
    }
    
    .login-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('images/pattern.png');
        opacity: 0.1;
    }
    
    .login-form-container {
        padding: 50px;
    }
    
    .login-hero-content {
        position: relative;
        z-index: 2;
    }
    
    .login-hero-content h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .login-hero-content p {
        font-size: 18px;
        line-height: 1.6;
        margin-bottom: 30px;
        opacity: 0.9;
    }
    
    .benefits-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .benefits-list li {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        font-size: 16px;
    }
    
    .benefits-list i {
        margin-right: 12px;
        background: rgba(255, 255, 255, 0.2);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .login-header h2 {
        font-size: 28px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    
    .login-header p {
        color: #666;
        font-size: 16px;
    }
    
    .brand-logo {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .brand-logo h3 {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }
    
    .brand-logo span {
        color: #667eea;
    }
    
    .brand-logo .tagline {
        color: #666;
        font-size: 14px;
        margin-top: 5px;
    }
    
    .form-group {
        margin-bottom: 25px;
        position: relative;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
        font-size: 14px;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 14px 20px;
        padding-left: 45px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 16px;
        transition: all 0.3s;
        font-family: 'Poppins', sans-serif;
    }
    
    .form-control-custom:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .input-icon {
        position: absolute;
        left: 15px;
        top: 42px;
        color: #999;
        font-size: 18px;
    }
    
    .submit-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Poppins', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .divider {
        text-align: center;
        margin: 30px 0;
        position: relative;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e0e0e0;
    }
    
    .divider span {
        background: white;
        padding: 0 15px;
        color: #666;
        font-size: 14px;
        position: relative;
    }
    
    .register-link {
        text-align: center;
        margin-top: 25px;
        color: #666;
        font-size: 15px;
    }
    
    .register-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    
    .register-link a:hover {
        text-decoration: underline;
        color: #764ba2;
    }
    
    .error-alert {
        background: #fee;
        color: #c33;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        border-left: 4px solid #c33;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .error-alert i {
        font-size: 18px;
    }
    
    .breadcrumb-custom {
        padding: 15px 0;
        background: transparent;
    }
    
    .breadcrumb-custom ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 10px;
    }
    
    .breadcrumb-custom li {
        font-size: 14px;
    }
    
    .breadcrumb-custom a {
        color: #667eea;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .breadcrumb-custom a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    .login-notification {
        background: #e3f2fd;
        border-left: 4px solid #2196f3;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .login-notification i {
        color: #2196f3;
        font-size: 18px;
    }
    
    .login-notification a {
        color: #2196f3;
        font-weight: 600;
        text-decoration: none;
    }
    
    .login-notification a:hover {
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .login-hero {
            min-height: 400px;
            padding: 30px;
        }
        
        .login-form-container {
            padding: 30px;
        }
        
        .login-hero-content h1 {
            font-size: 28px;
        }
        
        .customer-login-section {
            padding: 20px 0;
        }
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 42px;
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        font-size: 18px;
        z-index: 2;
    }
</style> -->
<style>
    /* ========================================
   Customer Login Page Custom Styles
   ======================================== */

:root {
    --primary: #2a5c8a;        /* Deep blue – trust & professionalism */
    --primary-dark: #1e3f5a;
    --secondary: #e9b741;       /* Gold accent – for highlights */
    --light-bg: #f8f9fc;
    --white: #ffffff;
    --gray-100: #f3f6f9;
    --gray-200: #e2e8f0;
    --gray-600: #4a5568;
    --gray-800: #1e293b;
    --success: #10b981;
    --danger: #ef4444;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 10px 25px rgba(0, 0, 0, 0.1);
    --border-radius: 16px;
    --transition: all 0.3s ease;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #eef2f6 100%);
    min-height: 100vh;
    color: var(--gray-800);
}

/* Section container */
.customer-login-section {
    padding: 2rem 0 4rem;
    position: relative;
}

/* Breadcrumb */
.breadcrumb-custom ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0 2rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    font-size: 0.95rem;
}
.breadcrumb-custom li {
    display: inline-flex;
    align-items: center;
    color: var(--gray-600);
}
.breadcrumb-custom li a {
    color: var(--primary);
    text-decoration: none;
    transition: var(--transition);
}
.breadcrumb-custom li a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}
.breadcrumb-custom li:not(:last-child)::after {
    content: "/";
    margin: 0 0.6rem;
    color: var(--gray-400);
    font-weight: 300;
}

/* Main login container */
.login-container-wrapper {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    margin-top: 1rem;
    animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Left side – hero welcome section */
.login-hero {
    background: linear-gradient(145deg, var(--primary) 0%, #1e4b6e 100%);
    color: white;
    height: 100%;
    padding: 3rem 2.5rem;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.login-hero::before {
    content: "";
    position: absolute;
    top: -30%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    pointer-events: none;
}
.login-hero::after {
    content: "";
    position: absolute;
    bottom: -20%;
    left: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 50%;
}
.login-hero-content {
    position: relative;
    z-index: 2;
}
.login-hero h1 {
    font-size: 2.2rem;
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: 1.5rem;
    color: white;
}
.login-hero p {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    line-height: 1.6;
}

/* Benefits list */
.benefits-list {
    list-style: none;
    padding: 0;
    margin: 2rem 0 0;
}
.benefits-list li {
    display: flex;
    align-items: center;
    margin-bottom: 1.25rem;
    font-size: 1rem;
}
.benefits-list i {
    width: 2.2rem;
    height: 2.2rem;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.1rem;
    color: var(--secondary);
    transition: var(--transition);
}
.benefits-list li:hover i {
    background: var(--secondary);
    color: var(--primary);
    transform: scale(1.1);
}
.benefits-list span {
    flex: 1;
    line-height: 1.4;
}

/* Right side – login form */
.login-form-container {
    padding: 2.5rem 2.5rem 2rem;
    background: var(--white);
}
.brand-logo {
    text-align: center;
    margin-bottom: 1.5rem;
}
.brand-logo h3 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.25rem;
}
.brand-logo h3 span {
    color: var(--secondary);
    font-weight: 300;
}
.brand-logo .tagline {
    color: var(--gray-600);
    font-size: 0.9rem;
    letter-spacing: 1px;
}
.login-header {
    text-align: center;
    margin-bottom: 2rem;
}
.login-header h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
}
.login-header p {
    color: var(--gray-600);
    font-size: 0.95rem;
}

/* Notification banner (when redirecting from application) */
.login-notification {
    background: #fff3cd;
    border: 1px solid #ffeeba;
    color: #856404;
    padding: 1rem 1.2rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 0.8rem;
    font-size: 0.95rem;
}
.login-notification i {
    font-size: 1.2rem;
    color: #f0b400;
    margin-top: 0.1rem;
}

/* Flash error alert */
.error-alert {
    background: #fee;
    border: 1px solid #fcc;
    color: var(--danger);
    padding: 1rem 1.2rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    font-size: 0.95rem;
    animation: shake 0.4s ease;
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20% { transform: translateX(-5px); }
    40% { transform: translateX(5px); }
    60% { transform: translateX(-3px); }
    80% { transform: translateX(3px); }
}
.error-alert i {
    font-size: 1.2rem;
}

/* Form groups */
.form-group {
    margin-bottom: 1.5rem;
    position: relative;
}
.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    font-size: 0.9rem;
    color: var(--gray-700);
}
.input-icon {
    position: absolute;
    left: 1rem;
    top: 2.5rem; /* adjust based on label */
    color: var(--gray-500);
    font-size: 1.1rem;
    pointer-events: none;
}
.form-control-custom {
    width: 100%;
    padding: 0.85rem 1rem 0.85rem 2.8rem; /* leave space for icon */
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 1rem;
    transition: var(--transition);
    background: var(--gray-100);
    color: var(--gray-800);
}
.form-control-custom:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--white);
    box-shadow: 0 0 0 4px rgba(42, 92, 138, 0.1);
}
.form-control-custom::placeholder {
    color: #a0aec0;
    font-weight: 300;
}

/* Password field with toggle */
.password-wrapper {
    position: relative;
}
.password-wrapper .form-control-custom {
    padding-right: 3rem; /* make space for toggle button */
}
.toggle-password {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gray-600);
    cursor: pointer;
    padding: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: var(--transition);
}
.toggle-password:hover {
    color: var(--primary);
}

/* Submit button */
.submit-btn {
    width: 100%;
    padding: 0.9rem;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 1rem;
    box-shadow: var(--shadow-sm);
}
.submit-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.submit-btn:active {
    transform: translateY(0);
}
.submit-btn i {
    font-size: 1.1rem;
}

/* Divider */
.divider {
    text-align: center;
    margin: 2rem 0 1.5rem;
    position: relative;
}
.divider::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--gray-200);
    z-index: 1;
}
.divider span {
    background: var(--white);
    padding: 0 1rem;
    color: var(--gray-600);
    font-size: 0.9rem;
    position: relative;
    z-index: 2;
}

/* Register link */
.register-link {
    text-align: center;
    font-size: 0.95rem;
    color: var(--gray-600);
    padding-bottom: 0.5rem;
}
.register-link a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    margin-left: 0.3rem;
}
.register-link a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .login-container-wrapper .row {
        flex-direction: column-reverse;
    }
    .login-hero {
        padding: 2rem 1.5rem;
        text-align: center;
    }
    .login-hero h1 {
        font-size: 1.8rem;
    }
    .benefits-list li {
        justify-content: center;
    }
    .login-form-container {
        padding: 2rem 1.5rem;
    }
    .login-header h2 {
        font-size: 1.5rem;
    }
}
@media (max-width: 576px) {
    .breadcrumb-custom ul {
        justify-content: center;
    }
    .login-hero h1 {
        font-size: 1.6rem;
    }
    .benefits-list li {
        font-size: 0.95rem;
    }
    .form-control-custom {
        font-size: 0.95rem;
    }
}
</style>
</head>
<body>

<?php include("includes/header.php");?>

<section class="customer-login-section">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-custom">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li>/</li>
                        <li>Customer Login</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="login-container-wrapper">
                    <div class="row">
                        <!-- Left Side - Welcome Section -->
                        <div class="col-md-6">
                            <div class="login-hero">
                                <div class="login-hero-content">
                                    <h1>Welcome Back to Legal Taxation</h1>
                                    <p>Access your personal dashboard to manage your tax filings, track submissions, and get expert support from our CA partners.</p>
                                    
                                    <ul class="benefits-list">
                                        <li>
                                            <i class="fas fa-file-invoice-dollar"></i>
                                            <span>Track your tax submissions & filings</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-user-shield"></i>
                                            <span>Secure & confidential document storage</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-headset"></i>
                                            <span>24/7 access to CA expert support</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-bell"></i>
                                            <span>Real-time notifications & reminders</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-chart-line"></i>
                                            <span>View your tax history & analytics</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Login Form -->
                        <div class="col-md-6">
                            <div class="login-form-container">
                                <div class="brand-logo">
                                    <h3>Legal <span>Taxation</span></h3>
                                    <p class="tagline">Customer Portal</p>
                                </div>
                                
                                <div class="login-header">
                                    <h2>Sign In to Your Account</h2>
                                    <p>Enter your credentials to access your dashboard</p>
                                </div>
                                
                                <?php if($redirect_url != 'index.php'): ?>
                                <div class="login-notification">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Please login to continue with your application. After login, you'll be redirected back to complete your application.</span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if(!empty($flash_msg)): ?>
                                    <div class="error-alert" role="alert">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span><?php echo htmlspecialchars($flash_msg, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" novalidate>
                                    <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="auto_apply" value="<?php echo intval($auto_apply); ?>">
                                    
                                    <div class="form-group">
                                        <label for="cont">Registered Mobile Number</label>
                                        <div class="input-icon">
                                            <i class="fas fa-mobile-alt"></i>
                                        </div>
                                        <input type="text" 
                                               id="cont" 
                                               name="cont" 
                                               class="form-control-custom" 
                                               placeholder="Enter your registered mobile number" 
                                               required
                                               maxlength="10"
                                               pattern="[0-9]{10}"
                                               title="Please enter a valid 10-digit mobile number"
                                               value="<?php echo htmlspecialchars($old_cont ?: (isset($_POST['cont']) ? $_POST['cont'] : ''), ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pass">Password</label>
                                        <div class="input-icon">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <div class="password-wrapper">
                                            <input type="password" 
                                                   id="pass" 
                                                   name="pass" 
                                                   class="form-control-custom" 
                                                   placeholder="Enter your password" 
                                                   required>
                                            <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" name="login" class="submit-btn">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Sign In</span>
                                    </button>
                                    
                                    <div class="divider">
                                        <span>New to Legal Taxation?</span>
                                    </div>
                                    
                                    <div class="register-link">
                                        Don't have an account? 
                                        <a href="customer-reg.php">Create New Account</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("includes/footer.php");?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('pass');
        const eyeIcon = togglePassword.querySelector('i');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                eyeIcon.className = 'fas fa-eye';
            }
        });
        
        // Mobile number validation
        const mobileInput = document.getElementById('cont');
        mobileInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
        
        // Form animation (optional)
        const loginContainer = document.querySelector('.login-container-wrapper');
        if (loginContainer) {
            loginContainer.style.opacity = '0';
            loginContainer.style.transform = 'translateY(20px)';
            setTimeout(() => {
                loginContainer.style.transition = 'all 0.6s ease';
                loginContainer.style.opacity = '1';
                loginContainer.style.transform = 'translateY(0)';
            }, 200);
        }
    });
</script>
</body>
</html>
