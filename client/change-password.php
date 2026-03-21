<?php
session_start();
include("../db.php");

if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../ca-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];
$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current password hash
    $sql = $con->query("SELECT password FROM ca WHERE id='$ca_id'");
    $ca = $sql->fetch_assoc();
    
    // Verify current password
    if(!password_verify($current_password, $ca['password'])){
        $error = "Current password is incorrect";
    } elseif(strlen($new_password) < 6){
        $error = "New password must be at least 6 characters long";
    } elseif($new_password !== $confirm_password){
        $error = "New passwords do not match";
    } else {
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password
        if($con->query("UPDATE ca SET password='$hashed_password' WHERE id='$ca_id'")){
            $success = "Password changed successfully!";
        } else {
            $error = "Error updating password: " . $con->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Change Password - CA Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    * { font-family: 'Inter', sans-serif; }
    body { background-color: #f8fafc; }
    .content-wrapper { background-color: #f8fafc; }

    /* Page Header */
    .page-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1.5rem;
    }
    .page-header .icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(145deg, #3b82f6, #2563eb);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      box-shadow: 0 8px 12px -4px rgba(59,130,246,0.3);
    }
    .page-header h1 {
      font-weight: 600;
      font-size: 1.875rem;
      color: #0f172a;
      margin: 0;
    }

    /* Modern Card */
    .modern-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
      overflow: hidden;
      max-width: 600px;
      margin: 0 auto;
    }
    .card-header-custom {
      padding: 1.25rem 1.75rem;
      border-bottom: 1px solid #f1f5f9;
      background: #ffffff;
    }
    .card-header-custom h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .card-header-custom h3 i { color: #3b82f6; }
    .card-body-custom { padding: 1.5rem 1.75rem; }
    .card-footer-custom {
      padding: 1rem 1.75rem;
      border-top: 1px solid #f1f5f9;
      background: #f8fafc;
    }

    /* Form */
    .form-group { margin-bottom: 1.5rem; }
    .form-group label {
      font-weight: 500;
      color: #334155;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      margin-bottom: 0.5rem;
      display: block;
    }
    .input-wrapper {
      position: relative;
    }
    .input-wrapper i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 1rem;
      pointer-events: none;
    }
    .form-control {
      width: 100%;
      padding: 0.75rem 1rem 0.75rem 2.5rem;
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      font-size: 0.95rem;
      transition: all 0.2s;
      background: #ffffff;
    }
    .form-control:focus {
      border-color: #3b82f6;
      outline: none;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .password-toggle {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      cursor: pointer;
      font-size: 1rem;
      background: transparent;
      border: none;
      padding: 0;
      z-index: 5;
    }
    .password-toggle:hover { color: #3b82f6; }

    /* Alerts */
    .alert {
      border-radius: 1rem;
      border: none;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
    }
    .alert-danger { background: #fee2e2; color: #991b1b; }
    .alert-success { background: #d1fae5; color: #065f46; }

    /* Buttons */
    .btn-modern {
      border-radius: 100px;
      padding: 0.6rem 1.5rem;
      font-size: 0.85rem;
      font-weight: 500;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
      border: none;
      cursor: pointer;
    }
    .btn-primary-modern {
      background: #3b82f6;
      color: white;
    }
    .btn-primary-modern:hover { background: #2563eb; }
    .btn-secondary-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      color: #334155;
    }
    .btn-secondary-modern:hover { background: #f8fafc; border-color: #94a3b8; }

    /* Footer */
    .main-footer {
      background: #ffffff;
      border-top: 1px solid #f1f5f9;
      color: #64748b;
      font-size: 0.85rem;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('navbar.php'); ?>

  <!-- Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6">
            <div class="page-header">
              <div class="icon"><i class="fas fa-key"></i></div>
              <h1>Change Password</h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Change Password</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="modern-card">
              <div class="card-header-custom">
                <h3><i class="fas fa-lock"></i> Update Your Password</h3>
              </div>
              <div class="card-body-custom">
                <?php if($error): ?>
                  <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?>
                  </div>
                <?php endif; ?>

                <?php if($success): ?>
                  <div class="alert alert-success">
                    <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
                  </div>
                <?php endif; ?>

                <form method="POST">
                  <div class="form-group">
                    <label>Current Password</label>
                    <div class="input-wrapper">
                      <i class="fas fa-lock"></i>
                      <input type="password" name="current_password" class="form-control" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>New Password</label>
                    <div class="input-wrapper">
                      <i class="fas fa-key"></i>
                      <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6">
                      <button type="button" class="password-toggle" onclick="togglePassword('new_password', this)">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                    <small class="text-muted">Minimum 6 characters</small>
                  </div>

                  <div class="form-group">
                    <label>Confirm New Password</label>
                    <div class="input-wrapper">
                      <i class="fas fa-check-circle"></i>
                      <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="6">
                      <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', this)">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>

                  <button type="submit" name="change_password" class="btn-modern btn-primary-modern">
                    <i class="fas fa-save"></i> Change Password
                  </button>
                </form>
              </div>
              <div class="card-footer-custom">
                <a href="dashboard.php" class="btn-modern btn-secondary-modern">
                  <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
</script>
</body>
</html>