<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
  exit;
}

// Get admin details
$sql = $con->query("select * from admin where id='$aid'");
if($row = $sql->fetch_assoc()){
  $admin_image = $row["image"];
  $admin_name = $row["name"];
  $admin_cont = $row["contact"];
  $admin_email = $row["email"];
  $admin_pass = $row["password"];
}

$current_date = date('d-m-Y h:i a');

// Handle form submission
$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){
    $ca_id = rand(10000,99999);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $des = mysqli_real_escape_string($con, $_POST['des']);
    $cont = mysqli_real_escape_string($con, $_POST['cont']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $reg_no = mysqli_real_escape_string($con, $_POST['reg_no']);
    
    // Validate mobile number
    if(!preg_match('/^[0-9]{10}$/', $cont)){
        $error = "Invalid mobile number. Must be 10 digits.";
    }
    // Validate email
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email address.";
    }
    // Validate password
    elseif(strlen($password) < 6){
        $error = "Password must be at least 6 characters long.";
    }
    else{
        // Check if email already exists
        $check_email = $con->query("SELECT id FROM ca WHERE email='$email'");
        if($check_email->num_rows > 0){
            $error = "Email already registered. Please use a different email.";
        }
        else{
            // Handle file upload
            $image_name = '';
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024; // 2MB
                
                if(in_array($_FILES['image']['type'], $allowed_types)){
                    if($_FILES['image']['size'] <= $max_size){
                        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $image_name = 'ca_' . time() . '_' . rand(1000,9999) . '.' . $file_extension;
                        $upload_path = 'ca/' . $image_name;
                        
                        if(!is_dir('ca')){
                            mkdir('ca', 0755, true);
                        }
                        
                        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)){
                            // File uploaded successfully
                        } else {
                            $error = "Failed to upload image.";
                        }
                    } else {
                        $error = "Image size must be less than 2MB.";
                    }
                } else {
                    $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
                }
            } else {
                // No image uploaded, use default
                $image_name = 'default_profile.jpg';
            }
            
            if(empty($error)){
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert into database
                $sql = "INSERT INTO ca (ca_id, image, name, des, cont, email, password, reg_no, status, created) 
                        VALUES ('$ca_id', '$image_name', '$name', '$des', '$cont', '$email', '$hashed_password', '$reg_no', '1', '$current_date')";
                
                if($con->query($sql)){
                    $success = "CA added successfully!";
                    echo "<script>
                            setTimeout(function(){
                                window.location.href = 'all_ca.php';
                            }, 1500);
                          </script>";
                } else {
                    $error = "Database error: " . $con->error;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add CA | Admin Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

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
      margin-bottom: 1.5rem;
    }
    .card-header-custom {
      padding: 1.25rem 1.75rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
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
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Form */
    .form-group { margin-bottom: 1.25rem; }
    .form-group label {
      font-weight: 500;
      color: #334155;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      margin-bottom: 0.5rem;
      display: block;
    }
    .form-control, .form-select, .select2-container--bootstrap4 .select2-selection {
      border: 1px solid #e2e8f0;
      border-radius: 1rem !important;
      padding: 0.6rem 1rem;
      font-size: 0.95rem;
      transition: all 0.2s;
      width: 100%;
      height: auto;
    }
    .form-control:focus, .select2-container--bootstrap4.select2-container--focus .select2-selection {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
      outline: none;
    }
    .input-group-text {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 1rem 0 0 1rem;
      color: #64748b;
    }
    .input-group .form-control {
      border-left: none;
      border-radius: 0 1rem 1rem 0;
    }

    /* Password toggle */
    .password-toggle {
      cursor: pointer;
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #64748b;
      z-index: 10;
      background: white;
      padding: 0 5px;
    }
    .password-toggle:hover { color: #3b82f6; }

    /* Custom file input */
    .custom-file-label {
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      padding: 0.6rem 1rem;
    }
    .custom-file-label::after {
      background: #f8fafc;
      border-left: 1px solid #e2e8f0;
      border-radius: 0 1rem 1rem 0;
      color: #334155;
    }

    /* Preview image */
    .preview-image {
      max-width: 150px;
      max-height: 150px;
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      padding: 0.25rem;
      margin-top: 0.75rem;
      display: none;
    }
    .preview-image img { border-radius: 0.75rem; }

    /* Badge */
    .badge-modern {
      padding: 0.25rem 0.75rem;
      border-radius: 100px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .badge-success { background: #d1fae5; color: #065f46; }

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

    /* Info boxes */
    .info-box-modern {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .info-box-icon-modern {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .info-box-content-modern { flex: 1; }
    .info-box-text-modern {
      font-size: 0.8rem;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .info-box-number-modern {
      font-size: 1.25rem;
      font-weight: 600;
      color: #0f172a;
    }

    /* Callout */
    .callout-modern {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      padding: 1rem;
      margin-top: 1rem;
    }
    .callout-modern h5 {
      font-weight: 600;
      font-size: 0.95rem;
      color: #0f172a;
      margin-bottom: 0.5rem;
    }
    .callout-modern p { color: #334155; font-size: 0.85rem; margin-bottom: 0.25rem; }

    /* Alerts */
    .alert {
      border-radius: 1rem;
      border: none;
      padding: 1rem 1.25rem;
    }
    .alert-danger { background: #fee2e2; color: #991b1b; }
    .alert-success { background: #d1fae5; color: #065f46; }

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
  <?php include("navbar.php"); ?>

  <!-- Main Sidebar Container -->
  <?php include("sidebar.php"); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <div class="page-header">
              <div class="icon"><i class="fas fa-user-plus"></i></div>
              <h1>Add CA</h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="all_ca.php">All CA</a></li>
              <li class="breadcrumb-item active">Add CA</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Messages -->
        <?php if($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-8">
            <div class="modern-card">
              <div class="card-header-custom">
                <h3><i class="fas fa-user-tie"></i> CA Details</h3>
              </div>
              <div class="card-body-custom">
                <form method="post" enctype="multipart/form-data" id="caForm">
                  <div class="row">
                    <!-- Image Upload -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="image">CA Photo</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                          <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                        <small class="text-muted">Max 2MB. JPG, PNG, GIF allowed.</small>
                        <div id="imagePreview" class="preview-image"></div>
                      </div>
                    </div>
                    
                    <!-- CA ID (Auto-generated) -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>CA ID</label>
                        <input type="text" class="form-control bg-light" value="Auto-generated" readonly>
                        <small class="text-muted">Will be generated automatically</small>
                      </div>
                    </div>
                    
                    <!-- Name -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="Enter CA Name" required 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                      </div>
                    </div>
                    
                    <!-- Designation -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="des">Designation <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="des" name="des" required style="width: 100%;">
                          <option value="">Choose Designation</option>
                          <?php
                          $sql = $con->query("select * from ca_des order by name asc");
                          while($row = $sql->fetch_assoc()){
                              $selected = (isset($_POST['des']) && $_POST['des'] == $row['id']) ? 'selected' : '';
                              echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    
                    <!-- Contact -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="cont">Mobile Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="text" class="form-control" id="cont" name="cont" 
                                 placeholder="Enter 10-digit mobile number" required 
                                 pattern="[0-9]{10}"
                                 value="<?php echo isset($_POST['cont']) ? htmlspecialchars($_POST['cont']) : ''; ?>">
                        </div>
                        <small class="text-muted">10 digits without country code</small>
                      </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                          </div>
                          <input type="email" class="form-control" id="email" name="email" 
                                 placeholder="Enter email address" required
                                 value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                      </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="col-md-6">
                      <div class="form-group" style="position: relative;">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                          </div>
                          <input type="password" class="form-control" id="password" name="password" 
                                 placeholder="Enter password (min 6 characters)" required minlength="6">
                          <span class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                          </span>
                        </div>
                        <small class="text-muted">Minimum 6 characters</small>
                      </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="col-md-6">
                      <div class="form-group" style="position: relative;">
                        <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                          </div>
                          <input type="password" class="form-control" id="confirm_password" 
                                 placeholder="Confirm password" required minlength="6">
                          <span class="password-toggle" onclick="toggleConfirmPassword()">
                            <i class="fas fa-eye" id="toggleConfirmIcon"></i>
                          </span>
                        </div>
                        <div id="passwordMatch" class="invalid-feedback" style="display: none;">
                          Passwords do not match
                        </div>
                      </div>
                    </div>
                    
                    <!-- Registration Number -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="reg_no">CA Registration No.</label>
                        <input type="text" class="form-control" id="reg_no" name="reg_no" 
                               placeholder="Enter Registration Number"
                               value="<?php echo isset($_POST['reg_no']) ? htmlspecialchars($_POST['reg_no']) : ''; ?>">
                      </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Status</label>
                        <div class="form-control bg-light">
                          <span class="badge-modern badge-success">Active</span> <small class="text-muted">(New CAs are active by default)</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer-custom">
                <div>
                  <button type="submit" name="submit" form="caForm" class="btn-modern btn-primary-modern">
                    <i class="fas fa-save"></i> Save CA
                  </button>
                  <button type="reset" class="btn-modern btn-secondary-modern" onclick="resetForm()">
                    <i class="fas fa-undo"></i> Reset
                  </button>
                </div>
                <a href="all_ca.php" class="btn-modern btn-secondary-modern">
                  <i class="fas fa-list"></i> View All CA
                </a>
              </div>
            </div>
          </div>
          
          <!-- Info Card -->
          <div class="col-md-4">
            <div class="modern-card">
              <div class="card-header-custom">
                <h3><i class="fas fa-info-circle"></i> Information</h3>
              </div>
              <div class="card-body-custom">
                <ul style="padding-left: 1.2rem; color: #334155;">
                  <li>CA ID is auto-generated (5 digits)</li>
                  <li>Profile photo is optional</li>
                  <li>All active CAs will be visible to customers</li>
                  <li>Email will be used for login and notifications</li>
                  <li>Mobile number must be valid for OTP verification</li>
                  <li>Password must be at least 6 characters long</li>
                </ul>
                <div class="callout-modern">
                  <h5><i class="fas fa-lightbulb" style="color: #f59e0b;"></i> Tip</h5>
                  <p>Ensure all information is accurate. CA details cannot be edited easily once assigned to customers.</p>
                  <p><strong>Password Security:</strong> Use a strong password combination of letters, numbers, and special characters.</p>
                </div>
              </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="modern-card mt-3">
              <div class="card-header-custom">
                <h3><i class="fas fa-chart-bar"></i> CA Statistics</h3>
              </div>
              <div class="card-body-custom">
                <?php
                $total_ca = $con->query("SELECT COUNT(*) as total FROM ca")->fetch_assoc()['total'];
                $active_ca = $con->query("SELECT COUNT(*) as active FROM ca WHERE status='1'")->fetch_assoc()['active'];
                ?>
                <div class="row">
                  <div class="col-6">
                    <div class="info-box-modern">
                      <div class="info-box-icon-modern" style="background: #e0f2fe;"><i class="fas fa-user-tie" style="color: #0284c7;"></i></div>
                      <div class="info-box-content-modern">
                        <span class="info-box-text-modern">Total CA</span>
                        <span class="info-box-number-modern"><?php echo $total_ca; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="info-box-modern">
                      <div class="info-box-icon-modern" style="background: #d1fae5;"><i class="fas fa-check-circle" style="color: #059669;"></i></div>
                      <div class="info-box-content-modern">
                        <span class="info-box-text-modern">Active</span>
                        <span class="info-box-number-modern"><?php echo $active_ca; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Choose Designation',
        allowClear: true
    });
    
    // Initialize bs-custom-file-input
    bsCustomFileInput.init();
    
    // Image preview
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html('<img src="' + e.target.result + '" class="img-fluid" alt="Preview">').show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide().html('');
        }
    });
    
    // Password confirmation validation
    $('#confirm_password').on('keyup', function() {
        const password = $('#password').val();
        const confirmPassword = $(this).val();
        const passwordMatch = $('#passwordMatch');
        
        if (confirmPassword.length > 0 && password !== confirmPassword) {
            $(this).addClass('is-invalid');
            passwordMatch.show();
        } else {
            $(this).removeClass('is-invalid');
            passwordMatch.hide();
        }
    });
    
    // Form validation
    $('#caForm').submit(function(e) {
        var mobile = $('#cont').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        
        // Mobile validation
        if (!/^[0-9]{10}$/.test(mobile)) {
            alert('Please enter a valid 10-digit mobile number.');
            $('#cont').focus();
            e.preventDefault();
            return false;
        }
        
        // Email validation
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            alert('Please enter a valid email address.');
            $('#email').focus();
            e.preventDefault();
            return false;
        }
        
        // Password validation
        if (password.length < 6) {
            alert('Password must be at least 6 characters long.');
            $('#password').focus();
            e.preventDefault();
            return false;
        }
        
        // Password confirmation
        if (password !== confirmPassword) {
            alert('Passwords do not match. Please confirm your password.');
            $('#confirm_password').focus();
            e.preventDefault();
            return false;
        }
        
        return true;
    });
});

function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function toggleConfirmPassword() {
    const confirmInput = document.getElementById('confirm_password');
    const toggleIcon = document.getElementById('toggleConfirmIcon');
    
    if (confirmInput.type === 'password') {
        confirmInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        confirmInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function resetForm() {
    $('#imagePreview').hide().html('');
    $('#caForm')[0].reset();
    $('.select2').val('').trigger('change');
    $('#confirm_password').removeClass('is-invalid');
    $('#passwordMatch').hide();
}
</script>
</body>
</html>