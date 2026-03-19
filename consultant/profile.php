<?php
session_start();
include("../db.php");

if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../ca-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id'");
$ca = $sql->fetch_assoc();

// Handle profile update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $reg_no = mysqli_real_escape_string($con, $_POST['reg_no']);
    
    $update_sql = "UPDATE ca SET name='$name', cont='$contact', reg_no='$reg_no' WHERE id='$ca_id'";
    
    if($con->query($update_sql)){
        $_SESSION['ca_name'] = $name;
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile: " . $con->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Profile - CA Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE) -->
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
    .content-wrapper {
      background-color: #f8fafc;
    }
    .main-header {
      background-color: #ffffff !important;
      border-bottom: 1px solid #e9ecef;
    }
    /* Profile card styling */
    .profile-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }
    .profile-header {
      background: linear-gradient(145deg, #0f172a, #1e293b);
      padding: 2rem;
      color: white;
    }
    .profile-avatar {
      width: 100px;
      height: 100px;
      border-radius: 20px;
      background: #ffffff20;
      border: 3px solid #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
    }
    .profile-avatar img {
      width: 100%;
      height: 100%;
      border-radius: 18px;
      object-fit: cover;
    }
    .profile-avatar i {
      font-size: 3rem;
      color: #ffffff;
    }
    .profile-title {
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 0.25rem;
    }
    .profile-subtitle {
      color: #cbd5e1;
      font-size: 1rem;
    }
    .profile-detail-card {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      padding: 1.25rem;
      margin: 1.5rem 0;
    }
    .detail-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 0;
      border-bottom: 1px solid #e2e8f0;
    }
    .detail-item:last-child {
      border-bottom: none;
    }
    .detail-icon {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: #e0f2fe;
      color: #0284c7;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }
    .detail-content {
      flex: 1;
    }
    .detail-label {
      font-size: 0.8rem;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .detail-value {
      font-weight: 600;
      color: #0f172a;
    }
    .form-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      padding: 1.75rem;
    }
    .form-card h3 {
      font-size: 1.25rem;
      font-weight: 600;
      color: #0f172a;
      margin-bottom: 1.5rem;
    }
    .form-group {
      margin-bottom: 1.25rem;
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
    .form-control[readonly] {
      background-color: #f8fafc;
    }
    .btn-primary {
      background: #3b82f6;
      border: none;
      border-radius: 100px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: background 0.2s;
    }
    .btn-primary:hover {
      background: #2563eb;
    }
    .alert {
      border-radius: 1rem;
      border: none;
      padding: 1rem 1.25rem;
    }
    .alert-success {
      background: #d1fae5;
      color: #065f46;
    }
    .alert-danger {
      background: #fee2e2;
      color: #991b1b;
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
            <h1 class="m-0">My Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">My Profile</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Left Column: Profile Summary -->
          <div class="col-lg-5">
            <div class="profile-card">
              <div class="profile-header">
                <div class="profile-avatar">
                  <?php if(!empty($ca['image'])): ?>
                    <img src="../uploads/ca/<?php echo htmlspecialchars($ca['image']); ?>" 
                         alt="Profile"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-user-tie\'></i>';">
                  <?php else: ?>
                    <i class="fas fa-user-tie"></i>
                  <?php endif; ?>
                </div>
                <div class="profile-title"><?php echo htmlspecialchars($ca['name']); ?></div>
                <div class="profile-subtitle">CA ID: <?php echo htmlspecialchars($ca['ca_id']); ?></div>
              </div>
              <div class="p-4">
                <div class="profile-detail-card">
                  <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                    <div class="detail-content">
                      <div class="detail-label">Email</div>
                      <div class="detail-value"><?php echo htmlspecialchars($ca['email']); ?></div>
                    </div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="detail-content">
                      <div class="detail-label">Contact</div>
                      <div class="detail-value"><?php echo htmlspecialchars($ca['cont']); ?></div>
                    </div>
                  </div>
                  <div class="detail-item">
                    <div class="detail-icon"><i class="fas fa-certificate"></i></div>
                    <div class="detail-content">
                      <div class="detail-label">Registration No.</div>
                      <div class="detail-value"><?php echo htmlspecialchars($ca['reg_no'] ?: 'Not provided'); ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Edit Form -->
          <div class="col-lg-7">
            <div class="form-card">
              <h3><i class="fas fa-user-edit me-2"></i> Edit Profile Information</h3>

              <?php if(isset($success)): ?>
                <div class="alert alert-success">
                  <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                </div>
              <?php endif; ?>
              <?php if(isset($error)): ?>
                <div class="alert alert-danger">
                  <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                </div>
              <?php endif; ?>

              <form method="POST">
                <div class="form-group">
                  <label>CA ID</label>
                  <input type="text" class="form-control" value="<?php echo htmlspecialchars($ca['ca_id']); ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Full Name</label>
                  <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($ca['name']); ?>" required>
                </div>
                <div class="form-group">
                  <label>Email Address</label>
                  <input type="email" class="form-control" value="<?php echo htmlspecialchars($ca['email']); ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Contact Number</label>
                  <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($ca['cont']); ?>" required>
                </div>
                <div class="form-group">
                  <label>Registration Number</label>
                  <input type="text" name="reg_no" class="form-control" value="<?php echo htmlspecialchars($ca['reg_no']); ?>" placeholder="Enter registration number">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">
                  <i class="fas fa-save me-2"></i> Update Profile
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