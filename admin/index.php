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
    <title>Admin Login | Legal Taxation</title>
    <link rel="icon" type="image/webp" href="../images/favicon.webp">
    
    <!-- Google Font: Inter (modern, clean) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ===== Global Styles ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --white: #ffffff;
            --gradient: linear-gradient(145deg, var(--dark), var(--dark-light));
            --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            --border-radius: 2rem;
            --transition: all 0.2s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1200px;
        }

        .login-card {
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-light);
            display: flex;
            flex-wrap: wrap;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== Left Panel (Branding) ===== */
        .brand-panel {
            flex: 1 1 45%;
            background: var(--gradient);
            color: var(--white);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -20%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-title {
            font-weight: 700;
            font-size: 2.2rem;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .brand-description {
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #e2e8f0;
        }

        .feature-list li i {
            width: 24px;
            color: var(--primary);
            background: rgba(59, 130, 246, 0.2);
            padding: 0.5rem;
            border-radius: 10px;
            font-size: 1rem;
        }

        /* ===== Right Panel (Form) ===== */
        .form-panel {
            flex: 1 1 45%;
            padding: 3rem;
            background: var(--white);
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-weight: 700;
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .form-header p {
            color: var(--gray);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 1rem;
            transition: var(--transition);
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 3rem 0.75rem 2.5rem;
            border: 1px solid var(--gray-light);
            border-radius: 1rem;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            background: var(--white);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            font-size: 1rem;
            transition: var(--transition);
            background: transparent;
            border: none;
            padding: 0;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideIn 0.3s ease-out;
        }

        .alert i {
            font-size: 1.1rem;
            color: #ef4444;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 100px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background: var(--primary-dark);
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        .btn-login.loading {
            position: relative;
            color: transparent;
            pointer-events: none;
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

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border: 1px solid var(--gray-light);
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .checkbox-wrapper span {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .copyright {
            text-align: center;
            margin-top: 2rem;
            color: var(--gray);
            font-size: 0.8rem;
        }

        /* ===== Responsive ===== */
        @media (max-width: 992px) {
            .login-card {
                flex-direction: column;
            }
            .brand-panel, .form-panel {
                padding: 2rem;
            }
            .brand-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .brand-panel, .form-panel {
                padding: 1.5rem;
            }
            .brand-title {
                font-size: 1.75rem;
            }
            .form-header h2 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Left Panel (Branding) - Dark Slate Gradient -->
            <div class="brand-panel">
                <div class="brand-content">
                    <div class="brand-logo">
                        <img src="../images/logo.png" alt="Legal Taxation Logo">
                    </div>
                    <h1 class="brand-title">Admin Portal</h1>
                    <p class="brand-description">
                        Secure access to manage your CA & accounting platform. Monitor clients, track filings, and oversee operations seamlessly.
                    </p>
                    <ul class="feature-list">
                        <li><i class="fas fa-shield-alt"></i> Enterprise-grade security</li>
                        <li><i class="fas fa-chart-line"></i> Real-time analytics dashboard</li>
                        <li><i class="fas fa-users-cog"></i> Full user & role management</li>
                        <li><i class="fas fa-file-invoice"></i> Tax filing oversight</li>
                    </ul>
                </div>
            </div>

            <!-- Right Panel (Form) -->
            <div class="form-panel">
                <div class="form-header">
                    <h2>Welcome Back</h2>
                    <p>Please sign in to continue to the admin dashboard</p>
                </div>

                <?php if(@$_COOKIE["msg"]): ?>
                <div class="alert" id="errorMessage">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($_COOKIE["msg"]); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" id="loginForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="login" value="1">

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars(@$_COOKIE['tax_admin_email'] ?? '') ?>" 
                                   class="form-control" 
                                   placeholder="admin@example.com"
                                   required
                                   autocomplete="email"
                                   autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   name="pass" 
                                   value="<?= htmlspecialchars(@$_COOKIE['tax_admin_pass'] ?? '') ?>" 
                                   class="form-control" 
                                   placeholder="••••••••"
                                   required
                                   autocomplete="current-password"
                                   id="passwordInput">
                            <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i> Sign In
                    </button>

                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember" value="1" <?= @$_COOKIE['tax_admin_email'] ? 'checked' : '' ?>>
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="copyright">
            &copy; <?php echo date('Y'); ?> Legal Taxation. All rights reserved.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('passwordInput');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent any default action
                    
                    // Get the current type
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle the icon class
                    const icon = this.querySelector('i');
                    if (icon) {
                        if (type === 'text') {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                    
                    console.log('Password visibility toggled to:', type); // Debug
                });
            } else {
                console.error('Password toggle elements not found');
            }

            // Form submission
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    loginBtn.classList.add('loading');
                    loginBtn.disabled = true;
                });
            }

            // Auto-hide error message
            const errorMsg = document.getElementById('errorMessage');
            if (errorMsg) {
                setTimeout(() => {
                    errorMsg.style.transition = 'opacity 0.5s';
                    errorMsg.style.opacity = '0';
                    setTimeout(() => errorMsg.style.display = 'none', 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>