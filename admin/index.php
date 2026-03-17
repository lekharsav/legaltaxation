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
    <title>Admin Portal | Legal Taxation</title>
    <link rel="icon" type="image/webp" href="../images/favicon.webp">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ===== Global Styles ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --accent: #f72585;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --white: #ffffff;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --shadow: 0 20px 40px rgba(0,0,0,0.15);
            --border-radius: 24px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: rotate 30s linear infinite;
            z-index: 0;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .login-wrapper {
            width: 100%;
            max-width: 1200px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== Left Panel (Branding) ===== */
        .brand-panel {
            flex: 1 1 45%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 60px 40px;
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
            background: rgba(255,255,255,0.1);
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
            background: rgba(255,255,255,0.1);
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
            background: white;
            border-radius: 50%;
            padding: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            margin-bottom: 30px;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .brand-description {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.8;
            margin-bottom: 40px;
        }

        .feature-list {
            list-style: none;
            margin-top: 30px;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .feature-list li i {
            width: 24px;
            font-size: 1.2rem;
            color: rgba(255,255,255,0.8);
        }

        /* ===== Right Panel (Form) ===== */
        .form-panel {
            flex: 1 1 45%;
            padding: 60px 40px;
            background: white;
        }

        .form-header {
            margin-bottom: 35px;
        }

        .form-header h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-header p {
            color: var(--gray);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.9rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 1.1rem;
            transition: var(--transition);
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: var(--transition);
            background: var(--light);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            cursor: pointer;
            font-size: 1.1rem;
            transition: var(--transition);
            background: transparent;
            border: none;
            padding: 0;
            z-index: 5;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .alert {
            padding: 14px 16px;
            border-radius: 16px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            border-left: 4px solid #ef476f;
            background: rgba(239, 71, 111, 0.05);
            color: #ef476f;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease-out;
        }

        .alert i {
            font-size: 1.2rem;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(67, 97, 238, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            font-size: 1.1rem;
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
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .checkbox-wrapper span {
            font-size: 0.9rem;
            color: var(--gray);
        }

        .forgot-link {
            color: var(--primary);
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

        /* ===== Responsive ===== */
        @media (max-width: 992px) {
            .login-card {
                flex-direction: column;
            }
            .brand-panel {
                padding: 40px 30px;
            }
            .brand-title {
                font-size: 2rem;
            }
            .form-panel {
                padding: 40px 30px;
            }
        }

        @media (max-width: 576px) {
            .brand-panel {
                padding: 30px 20px;
            }
            .form-panel {
                padding: 30px 20px;
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
            <!-- Left Panel (Branding) -->
            <div class="brand-panel">
                <div class="brand-content">
                    <div class="brand-logo">
                        <img src="../images/logo.webp" alt="Legal Taxation Logo">
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
                                   value="<?= htmlspecialchars(@$_COOKIE['tax_admin_email']) ?>" 
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
                                   value="<?= htmlspecialchars(@$_COOKIE['tax_admin_pass']) ?>" 
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
                        <i class="fas fa-sign-in-alt"></i> SIGN IN
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
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.querySelector('i').className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                });
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