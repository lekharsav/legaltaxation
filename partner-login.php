<?php
// partner-login.php
// Fixed: no "headers already sent" warning, session flash messages, prepared statements.

// Hide PHP warnings in production (optional)
ini_set('display_errors', 0);
error_reporting(0);

session_start();
require_once __DIR__ . '/db.php'; // expects $con as mysqli

// ----------------------
// Flash message helpers
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

// ------------------------------------------------------------------
// If a cookie exists, validate it against DB before trusting.
// ------------------------------------------------------------------
if (!empty($_COOKIE['tax_partner_log'])) {
    $check_id = $_COOKIE['tax_partner_log'];

    $valid = false;
    if ($stmt = $con->prepare("SELECT id FROM partner WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $check_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $valid = true;
        }
        $stmt->close();
    }

    if ($valid) {
        header('Location: index.php');
        exit();
    } else {
        // Stale cookie – clear it on common paths
        $past = time() - 3600;
        $paths = ['/', '/legalTaxation/', '/legalTaxation'];
        foreach ($paths as $p) {
            setcookie('tax_partner_log', '', $past, $p);
        }
        unset($_COOKIE['tax_partner_log']);
        // fall through to login form
    }
}

// ------------------------------------------------------------------
// Handle login POST (all header/setcookie calls happen BEFORE any output)
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $cont = trim($_POST['cont'] ?? '');
    $pass = trim($_POST['pass'] ?? '');

    if ($cont === '' || $pass === '') {
        $_SESSION['old_cont'] = $cont;
        flash('Please enter both mobile number and password.');
        header('Location: partner-login.php');
        exit();
    }

    // Use prepared statement
    if ($stmt = $con->prepare("SELECT id, status FROM partner WHERE contact = ? AND password = ? LIMIT 1")) {
        $stmt->bind_param('ss', $cont, $pass);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            if ((int)$row['status'] === 1) {
                $id = (int)$row['id'];
                setcookie('tax_partner_log', $id, time() + 60 * 60 * 24 * 30, '/');
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['old_cont'] = $cont;
                flash('Your Partner Profile Is Not Approved Yet! Please try again later.');
                header('Location: partner-login.php');
                exit();
            }
        } else {
            $_SESSION['old_cont'] = $cont;
            flash('Invalid Login Details');
            header('Location: partner-login.php');
            exit();
        }
    } else {
        error_log('DB prepare failed (partner login): ' . $con->error);
        $_SESSION['old_cont'] = $cont;
        flash('Server error — please try again later.');
        header('Location: partner-login.php');
        exit();
    }
}

// Retrieve flash message and old input
$flash_msg = get_flash();
$old_cont = $_SESSION['old_cont'] ?? '';
unset($_SESSION['old_cont']);
?>
<!doctype html>
<html lang="en-gb">
<head>
<title>Partner Login - Legal Taxation</title>
<link rel="shortcut icon" href="images/favicon.webp" type="image/x-icon" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<!-- Additional inline styles (or move to your style.css) -->
<style>
    /* ===== Partner Login Specific Styles ===== */
    :root {
        --primary: #ff7e5f;
        --primary-dark: #feb47b;
        --gradient: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
        --white: #ffffff;
        --gray-100: #f8f9fc;
        --gray-200: #e9ecef;
        --gray-600: #6c757d;
        --gray-800: #343a40;
        --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        --border-radius: 16px;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: #f0f2f5;
    }

    .partner-login-section {
        padding: 2rem 0 4rem;
    }

    .breadcrumb-custom ul {
        list-style: none;
        padding: 0;
        margin: 1rem 0 2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    .breadcrumb-custom li a {
        color: var(--primary);
        text-decoration: none;
    }
    .breadcrumb-custom li a:hover {
        text-decoration: underline;
    }

    .login-container-wrapper {
        background: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Left side – hero */
    .login-hero {
        background: var(--gradient);
        color: white;
        padding: 3rem 2.5rem;
        height: 100%;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .login-hero::before {
        content: "";
        position: absolute;
        top: -20%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .login-hero-content {
        position: relative;
        z-index: 2;
    }
    .login-hero h1 {
        font-size: 2.2rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    .login-hero p {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .benefits-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .benefits-list li {
        display: flex;
        align-items: center;
        margin-bottom: 1.2rem;
        font-size: 1rem;
    }
    .benefits-list i {
        width: 2.2rem;
        height: 2.2rem;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
        transition: all 0.3s;
    }
    .benefits-list li:hover i {
        background: white;
        color: var(--primary);
        transform: scale(1.1);
    }

    /* Right side – form */
    .login-form-container {
        padding: 2.5rem;
    }
    .brand-logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .brand-logo h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-800);
    }
    .brand-logo h3 span {
        background: var(--gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .brand-logo .tagline {
        color: var(--gray-600);
        font-size: 0.9rem;
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
    }

    .error-alert {
        background: #fee;
        border: 1px solid #fcc;
        color: #c33;
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
        0%,100%{ transform: translateX(0); }
        20%{ transform: translateX(-5px); }
        40%{ transform: translateX(5px); }
    }

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
    .form-control-custom {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
        background: var(--gray-100);
    }
    .form-control-custom:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(255,126,95,0.1);
    }

    /* Password toggle */
    .password-wrapper {
        position: relative;
    }
    .password-wrapper .form-control-custom {
        padding-right: 3rem;
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
        font-size: 1.2rem;
    }
    .toggle-password:hover {
        color: var(--primary);
    }

    .submit-btn {
        width: 100%;
        padding: 0.9rem;
        background: var(--gradient);
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
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(255,126,95,0.3);
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255,126,95,0.4);
    }

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
    }
    .divider span {
        background: white;
        padding: 0 1rem;
        color: var(--gray-600);
        font-size: 0.9rem;
        position: relative;
    }

    .register-link {
        text-align: center;
        font-size: 0.95rem;
        color: var(--gray-600);
    }
    .register-link a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
    }
    .register-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .login-hero { padding: 2rem 1.5rem; }
        .login-form-container { padding: 2rem 1.5rem; }
        .login-hero h1 { font-size: 1.8rem; }
    }
</style>
</head>
<body>

<?php include("includes/header.php"); ?>

<section class="partner-login-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-custom">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li>/</li>
                        <li>Partner Login</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="login-container-wrapper">
                    <div class="row g-0">
                        <!-- Left side – Welcome -->
                        <div class="col-md-6">
                            <div class="login-hero">
                                <div class="login-hero-content">
                                    <h1>Partner Login</h1>
                                    <p>Access your partner dashboard to manage your business partnership with Legal Taxation.</p>
                                    <ul class="benefits-list">
                                        <li><i class="fas fa-chart-pie"></i> <span>Track your earnings & commissions</span></li>
                                        <li><i class="fas fa-users"></i> <span>Manage client referrals</span></li>
                                        <li><i class="fas fa-file-contract"></i> <span>Access partnership agreements</span></li>
                                        <li><i class="fas fa-tools"></i> <span>Business resources & tools</span></li>
                                        <li><i class="fas fa-headset"></i> <span>Dedicated partner support</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Right side – Form -->
                        <div class="col-md-6">
                            <div class="login-form-container">
                                <div class="brand-logo">
                                    <h3>Legal <span>Taxation</span></h3>
                                    <p class="tagline">Partner Portal</p>
                                </div>
                                <div class="login-header">
                                    <h2>Partner Sign In</h2>
                                    <p>Enter your credentials to access partner dashboard</p>
                                </div>

                                <?php if ($flash_msg): ?>
                                    <div class="error-alert">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span><?php echo htmlspecialchars($flash_msg, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                <?php endif; ?>

                                <form method="POST">
                                    <div class="form-group">
                                        <label for="cont">Registered Mobile Number</label>
                                        <input type="text"
                                               id="cont"
                                               name="cont"
                                               class="form-control-custom"
                                               placeholder="Enter your 10-digit mobile number"
                                               required
                                               maxlength="10"
                                               pattern="[0-9]{10}"
                                               title="10-digit mobile number"
                                               value="<?php echo htmlspecialchars($old_cont, ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="pass">Password</label>
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
                                        <i class="fas fa-sign-in-alt"></i> Sign In as Partner
                                    </button>

                                    <div class="divider">
                                        <span>New to Partner Program?</span>
                                    </div>

                                    <div class="register-link">
                                        Not a partner yet? <a href="partner-reg.php">Apply as Partner</a>
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

<?php include("includes/footer.php"); ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile number validation
        const mobileInput = document.getElementById('cont');
        mobileInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('pass');
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
    });
</script>
</body>
</html>