<?php
include("../db.php");

// Check if already logged in
if(@$_COOKIE["tax_admin_log"]){
    echo"<script>window.location='dashboard.php';</script>";
    exit;
}

if(isset($_POST["login"])){
    $email = $con->real_escape_string($_POST["email"]);
    $pass = $con->real_escape_string($_POST["pass"]);
    $remember = isset($_POST["remember"]) ? 1 : 0;

    // Debug: Check if data is coming
    error_log("Login attempt - Email: $email, Remember: $remember");

    $sql = $con->query("SELECT * FROM admin WHERE email='$email' AND password='$pass'");
    
    if($sql && $row = $sql->fetch_assoc()){
        error_log("Login successful for: $email");
        
        if($remember){
            setcookie("tax_admin_email", $email, time() + 60*60*24*30, "/");
            setcookie("tax_admin_pass", $pass, time() + 60*60*24*30, "/");
        } else {
            setcookie("tax_admin_email", "", time() - 3600, "/");
            setcookie("tax_admin_pass", "", time() - 3600, "/");
        }
        
        $id = $row["id"];
        setcookie("tax_admin_log", $id, time() + 60*60*24*30, "/");
        
        // Clear any error message
        setcookie("msg", "", time() - 3600, "/");
        
        echo"<script>window.location='dashboard.php';</script>";
        exit;
    } else {
        error_log("Login failed for: $email");
        setcookie("msg", 'Invalid Login Details', time() + 5, "/");
        echo"<script>window.location='index.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Portal | Legal Taxation</title>
    <link rel="icon" type="image/webp" href="../images/favicon.webp">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --secondary-color: #7209b7;
            --success-color: #06d6a0;
            --danger-color: #ef476f;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --border-color: #e0e0e0;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 40px 30px 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
        }
        
        .brand-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            padding: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }
        
        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .welcome-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .welcome-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }
        
        .card-body {
            padding: 40px 35px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 0.9rem;
        }
        
        .input-group {
            position: relative;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 45px 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            font-size: 1.2rem;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            border-left: 4px solid var(--danger-color);
            background: rgba(239, 71, 111, 0.05);
            color: var(--danger-color);
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(67, 97, 238, 0.3);
            background: linear-gradient(135deg, var(--primary-dark), #5a0a9c);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            position: relative;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-label {
            font-size: 0.9rem;
            color: var(--gray-color);
            cursor: pointer;
        }
        
        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .copyright {
            text-align: center;
            margin-top: 30px;
            color: rgba(255,255,255,0.8);
            font-size: 0.85rem;
        }
        
        .copyright a {
            color: white;
            text-decoration: none;
        }
        
        /* Responsive Design */
        @media (max-width: 480px) {
            body {
                padding: 15px;
            }
            
            .login-card {
                border-radius: 15px;
            }
            
            .card-header {
                padding: 30px 20px 25px;
            }
            
            .card-body {
                padding: 30px 25px;
            }
            
            .welcome-title {
                font-size: 1.5rem;
            }
            
            .form-options {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
        
        /* Loading state */
        .btn-login.loading {
            position: relative;
            color: transparent;
        }
        
        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Success message */
        .alert-success {
            border-left-color: var(--success-color);
            background: rgba(6, 214, 160, 0.05);
            color: var(--success-color);
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="card-header">
                <div class="brand-logo">
                    <img src="../images/logo.webp" alt="Legal Taxation Logo">
                </div>
                <h1 class="welcome-title">Admin Portal</h1>
                <p class="welcome-subtitle">Secure access to dashboard</p>
            </div>
            
            <div class="card-body">
                <?php if(@$_COOKIE["msg"]): ?>
                <div class="alert" id="errorMessage">
                    <b><?php echo $_COOKIE["msg"]; ?></b>
                </div>
                <?php endif; ?>
                
                <!-- Debug form - using same structure as original -->
                <form method="POST" id="loginForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="login" value="1">
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <input type="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars(@$_COOKIE['tax_admin_email']) ?>" 
                                   class="form-control" 
                                   placeholder="Enter your email"
                                   required
                                   autocomplete="email"
                                   autofocus>
                            <span class="input-icon mdi mdi-email-outline"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" 
                                   name="pass" 
                                   value="<?= htmlspecialchars(@$_COOKIE['tax_admin_pass']) ?>" 
                                   class="form-control" 
                                   placeholder="Enter your password"
                                   required
                                   autocomplete="current-password"
                                   id="passwordInput">
                            <span class="input-icon mdi mdi-lock-outline" 
                                  id="togglePassword" 
                                  style="cursor: pointer;"></span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-login" name="login" id="loginBtn">
                        <span class="mdi mdi-login"></span>
                        SIGN IN
                    </button>
                    
                    <div class="form-options">
                        <label class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="rememberMe" 
                                   name="remember"
                                   value="1"
                                   <?= @$_COOKIE['tax_admin_email'] ? 'checked' : '' ?>>
                            <span class="form-check-label">Keep me signed in</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>
                </form>
                
                <!-- Debug section (remove in production) -->
                <?php if(isset($_POST) && !empty($_POST)): ?>
                <div style="margin-top: 20px; padding: 10px; background: #f0f0f0; border-radius: 5px; font-size: 12px;">
                    <strong>Debug Info:</strong><br>
                    POST Data: <?php print_r($_POST); ?><br>
                    Email: <?php echo @$_POST['email']; ?><br>
                    Remember: <?php echo isset($_POST['remember']) ? 'Yes' : 'No'; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> Legal Taxation. All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('passwordInput');
            
            if(togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('mdi-eye-outline');
                    this.classList.toggle('mdi-lock-outline');
                });
            }
            
            // Form submission handler
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            
            if(loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Simple validation
                    const email = this.querySelector('input[name="email"]').value;
                    const password = this.querySelector('input[name="pass"]').value;
                    
                    if(!email || !password) {
                        e.preventDefault();
                        alert('Please fill in both email and password');
                        return;
                    }
                    
                    if(loginBtn) {
                        loginBtn.classList.add('loading');
                        loginBtn.disabled = true;
                        loginBtn.innerHTML = '';
                    }
                    
                    // Form will submit normally
                });
            }
            
            // Remove error message after 5 seconds
            const errorMessage = document.getElementById('errorMessage');
            if(errorMessage) {
                setTimeout(function() {
                    errorMessage.style.opacity = '0';
                    errorMessage.style.transition = 'opacity 0.5s';
                    setTimeout(function() {
                        errorMessage.style.display = 'none';
                    }, 500);
                }, 5000);
            }
            
            // Auto-focus on email field
            const emailInput = document.querySelector('input[name="email"]');
            if(emailInput && emailInput.value === '') {
                emailInput.focus();
            }
        });
        
        // Debug: Log form submission
        window.addEventListener('submit', function(e) {
            console.log('Form submitted:', e.target.id);
        });
    </script>
</body>
</html>