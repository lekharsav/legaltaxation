<?php
session_start();
include("db.php");

if(isset($_SESSION['ca_logged_in'])){
    header("Location: consultant/dashboard.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    // Check if CA exists and is active
    $sql = $con->query("SELECT * FROM ca WHERE email='$email' AND status='1'");
    
    if($sql->num_rows == 1){
        $ca = $sql->fetch_assoc();
        
        // Verify password
        if(password_verify($password, $ca['password'])){
            $_SESSION['ca_id'] = $ca['id'];
            $_SESSION['ca_name'] = $ca['name'];
            $_SESSION['ca_email'] = $ca['email'];
            $_SESSION['ca_logged_in'] = true;
            
            // Set cookie for 30 days if remember me is checked
            if(isset($_POST['remember'])){
                setcookie('ca_remember', $ca['id'], time() + (30 * 24 * 60 * 60), '/');
            }
            
            header("Location: consultant/dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Account not found or inactive";
    }
}
?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
<title>CA Partner Login - Legal Taxation</title>
<link rel="shortcut icon" href="images/favicon.webp" type="image/x-icon" />
<meta charset="utf-8">
<meta name="author" content="">
<meta name="keywords" content="">
<meta name="description" content="">		
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!-- Google Font: Inter (modern, clean) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
<!-- Font Awesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Bootstrap (minimal) -->
<link href="css/bootstrap.css" rel="stylesheet">
<!-- Original style (overridden heavily) -->
<link rel="stylesheet" href="css/style.css" type="text/css">

<style>
    * {
        font-family: 'Inter', sans-serif;
    }
    
    body {
        background-color: #f8fafc;
    }
    
    .partner-login-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 50px 0;
    }
    
    .login-container-wrapper {
        background: #ffffff;
        border-radius: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        margin-top: 30px;
    }
    
    /* Left side - modern gradient/info panel */
    .login-hero {
        background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
        padding: 3rem;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 500px;
        position: relative;
    }
    
    .login-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('images/pattern.png') repeat;
        opacity: 0.05;
        pointer-events: none;
    }
    
    .login-hero-content {
        position: relative;
        z-index: 2;
    }
    
    .login-hero-content h1 {
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 1.5rem;
        color: #ffffff;
        letter-spacing: -0.02em;
    }
    
    .login-hero-content p {
        font-size: 1rem;
        line-height: 1.6;
        color: #cbd5e1;
        margin-bottom: 2.5rem;
    }
    
    .benefits-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .benefits-list li {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        font-size: 0.95rem;
        color: #e2e8f0;
    }
    
    .benefits-list i {
        margin-right: 1rem;
        background: rgba(59, 130, 246, 0.2);
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #3b82f6;
    }
    
    /* Right side - form */
    .login-form-container {
        padding: 3rem;
    }
    
    .brand-logo {
        margin-bottom: 2rem;
    }
    
    .brand-logo h3 {
        font-weight: 700;
        font-size: 1.8rem;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.02em;
    }
    
    .brand-logo span {
        color: #3b82f6;
    }
    
    .brand-logo .tagline {
        color: #64748b;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        font-weight: 400;
    }
    
    .login-header {
        margin-bottom: 2rem;
    }
    
    .login-header h2 {
        font-weight: 600;
        font-size: 1.5rem;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    
    .login-header p {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #334155;
        font-weight: 500;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        bottom: 0.9rem;
        color: #94a3b8;
        font-size: 1rem;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 0.75rem 1rem;
        padding-left: 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        font-size: 0.95rem;
        transition: border 0.2s, box-shadow 0.2s;
        background: #ffffff;
        color: #0f172a;
    }
    
    .form-control-custom:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 0.75rem;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1rem;
        padding: 0;
        display: flex;
        align-items: center;
    }
    
    .toggle-password:hover {
        color: #3b82f6;
    }
    
    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .remember-me {
        display: flex;
        align-items: center;
    }
    
    .remember-me input {
        margin-right: 0.5rem;
        accent-color: #3b82f6;
        width: 16px;
        height: 16px;
    }
    
    .remember-me label {
        margin: 0;
        font-size: 0.85rem;
        color: #334155;
    }
    
    .forgot-password {
        color: #3b82f6;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: color 0.2s;
    }
    
    .forgot-password:hover {
        color: #2563eb;
        text-decoration: underline;
    }
    
    .submit-btn {
        width: 100%;
        padding: 0.75rem 1.5rem;
        background: #3b82f6;
        color: #ffffff;
        border: none;
        border-radius: 100px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .submit-btn:hover {
        background: #2563eb;
    }
    
    .error-alert {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 1rem 1.25rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
    }
    
    .error-alert i {
        color: #ef4444;
        font-size: 1.1rem;
    }
    
    .success-alert {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 1rem 1.25rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
    }
    
    .success-alert i {
        color: #10b981;
        font-size: 1.1rem;
    }
    
    .breadcrumb-custom {
        padding: 0 0 1rem 0;
        background: transparent;
    }
    
    .breadcrumb-custom ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 0.5rem;
    }
    
    .breadcrumb-custom li {
        font-size: 0.85rem;
        color: #64748b;
    }
    
    .breadcrumb-custom a {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .breadcrumb-custom a:hover {
        color: #2563eb;
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .login-hero {
            min-height: auto;
            padding: 2rem;
        }
        
        .login-form-container {
            padding: 2rem;
        }
        
        .login-hero-content h1 {
            font-size: 1.8rem;
        }
        
        .remember-forgot {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
    }
</style>
</head>
<body>

<?php include("includes/header.php");?>

<section class="partner-login-section">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-custom">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li>/</li>
                        <li>CA Partner Login</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="login-container-wrapper">
                    <div class="row g-0">
                        <!-- Left Side - Welcome Section (redesigned) -->
                        <div class="col-md-6">
                            <div class="login-hero">
                                <div class="login-hero-content">
                                    <h1>Welcome back, CA Partner</h1>
                                    <p>Access your professional dashboard to manage clients, track submissions, and grow your practice with Legal Taxation.</p>
                                    
                                    <ul class="benefits-list">
                                        <li>
                                            <i class="fas fa-chart-line"></i>
                                            <span>Track your performance metrics</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-users"></i>
                                            <span>Manage client portfolio efficiently</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-file-invoice-dollar"></i>
                                            <span>Handle tax submissions & filings</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-shield-alt"></i>
                                            <span>Secure & confidential portal</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-handshake"></i>
                                            <span>Grow your professional network</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Login Form (redesigned) -->
                        <div class="col-md-6">
                            <div class="login-form-container">
                                <div class="brand-logo">
                                    <h3>Legal <span>Taxation</span></h3>
                                    <p class="tagline">CA Partner Portal</p>
                                </div>
                                
                                <div class="login-header">
                                    <h2>Sign in to your account</h2>
                                    <p>Enter your credentials to access the partner dashboard</p>
                                </div>
                                
                                <?php if($error): ?>
                                    <div class="error-alert">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span><?php echo htmlspecialchars($error); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(isset($_GET['success']) && $_GET['success'] == 'registered'): ?>
                                    <div class="success-alert">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Registration successful! Please login with your credentials.</span>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <div class="input-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               class="form-control-custom" 
                                               placeholder="Enter your registered email" 
                                               required
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-icon">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <div class="password-wrapper">
                                            <input type="password" 
                                                   id="password" 
                                                   name="password" 
                                                   class="form-control-custom" 
                                                   placeholder="Enter your password" 
                                                   required>
                                            <button type="button" class="toggle-password" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="remember-forgot">
                                        <div class="remember-me">
                                            <input type="checkbox" id="remember" name="remember">
                                            <label for="remember">Remember me</label>
                                        </div>
                                        <a href="forgot_password.php" class="forgot-password">
                                            <i class="fas fa-key"></i> Forgot Password?
                                        </a>
                                    </div>
                                    
                                    <button type="submit" name="login" class="submit-btn">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Sign In</span>
                                    </button>
                                    
                                    <!-- Optional register link commented out as in original -->
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
        const passwordInput = document.getElementById('password');
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
        
        // Form animation (optional)
        const loginContainer = document.querySelector('.login-container-wrapper');
        loginContainer.style.opacity = '0';
        loginContainer.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            loginContainer.style.transition = 'all 0.6s ease';
            loginContainer.style.opacity = '1';
            loginContainer.style.transform = 'translateY(0)';
        }, 200);
    });
</script>
</body>
</html>