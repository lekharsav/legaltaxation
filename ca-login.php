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
  
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
 <!--styles -->
<link href="css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
<link href="css/slick.css" rel="stylesheet">
<link href="css/slick-theme.css" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
    }
    
    .partner-login-section {
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
    
    .success-alert {
        background: #efe;
        color: #2a7;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
        border-left: 4px solid #2a7;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .success-alert i {
        font-size: 18px;
    }
    
    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .remember-me {
        display: flex;
        align-items: center;
    }
    
    .remember-me input {
        margin-right: 8px;
    }
    
    .remember-me label {
        margin: 0;
        font-size: 14px;
        color: #555;
    }
    
    .forgot-password {
        color: #667eea;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s;
    }
    
    .forgot-password:hover {
        color: #764ba2;
        text-decoration: underline;
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
        
        .partner-login-section {
            padding: 20px 0;
        }
        
        .remember-forgot {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
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
                    <div class="row">
                        <!-- Left Side - Welcome Section -->
                        <div class="col-md-6">
                            <div class="login-hero">
                                <div class="login-hero-content">
                                    <h1>Welcome Back, CA Partner!</h1>
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
                        
                        <!-- Right Side - Login Form -->
                        <div class="col-md-6">
                            <div class="login-form-container">
                                <div class="brand-logo">
                                    <h3>Legal <span>Taxation</span></h3>
                                    <p class="tagline">CA Partner Portal</p>
                                </div>
                                
                                <div class="login-header">
                                    <h2>Sign In to Your Account</h2>
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
                                    
                                    <!-- <div class="divider">
                                        <span>New to Legal Taxation?</span>
                                    </div>
                                    
                                    <div class="register-link">
                                        Don't have a partner account? 
                                        <a href="ca-registration.php">Become a CA Partner</a>
                                    </div> -->
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
        
        // Form animation
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