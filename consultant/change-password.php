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

  <!-- Google Font: Inter (modern, clean) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 (free) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE 3) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: #f8fafc;
    }

    .wrapper {
      background-color: #f8fafc;
    }

    .main-header {
      background-color: #ffffff !important;
      border-bottom: 1px solid #e9ecef;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .content-wrapper {
      background-color: #f8fafc;
    }

    /* Page Header */
    .page-header {
      margin-bottom: 2rem;
    }

    .page-header h1 {
      font-weight: 600;
      font-size: 1.875rem;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
    }

    .page-header h1 i {
      color: #3b82f6;
      margin-right: 0.75rem;
      font-size: 2rem;
    }

    .page-header .breadcrumb {
      background: transparent;
      padding: 0;
      margin: 0;
      font-size: 0.9rem;
    }

    .page-header .breadcrumb a {
      color: #64748b;
    }

    .page-header .breadcrumb .active {
      color: #0f172a;
      font-weight: 500;
    }

    /* Card styling */
    .form-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      padding: 2rem;
      max-width: 600px;
      margin: 0 auto;
    }

    .form-card h3 {
      font-size: 1.25rem;
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-card h3 i {
      color: #3b82f6;
    }

    .form-group {
      margin-bottom: 1.5rem;
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

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #e2e8f0;
      border-radius: 0.75rem;
      font-size: 0.95rem;
      transition: border 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
      border-color: #3b82f6;
      outline: none;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }

    .btn-primary {
      background: #3b82f6;
      border: none;
      border-radius: 100px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: background 0.2s;
      width: 100%;
    }

    .btn-primary:hover {
      background: #2563eb;
    }

    .alert {
      border-radius: 1rem;
      border: none;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
    }

    .alert-danger {
      background: #fee2e2;
      color: #991b1b;
    }

    .alert-success {
      background: #d1fae5;
      color: #065f46;
    }

    .password-requirements {
      font-size: 0.8rem;
      color: #64748b;
      margin-top: 0.25rem;
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
              <h1>
                <i class="fas fa-key"></i>
                Change Password
              </h1>
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
            <div class="form-card">
              <h3><i class="fas fa-lock"></i> Update Your Password</h3>

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
                  <label for="current_password">Current Password</label>
                  <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                  <label for="new_password">New Password</label>
                  <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                  <div class="password-requirements">
                    <i class="fas fa-info-circle"></i> Minimum 6 characters
                  </div>
                </div>

                <div class="form-group">
                  <label for="confirm_password">Confirm New Password</label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                </div>

                <button type="submit" name="change_password" class="btn btn-primary">
                  <i class="fas fa-save mr-2"></i> Change Password
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>