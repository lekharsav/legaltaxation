<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
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
    $reg_no = mysqli_real_escape_string($con, $_POST['reg_no']);
    
    // Validate mobile number
    if(!preg_match('/^[0-9]{10}$/', $cont)){
        $error = "Invalid mobile number. Must be 10 digits.";
    }
    // Validate email
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email address.";
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
            // Insert into database
            $sql = "INSERT INTO ca (ca_id, image, name, des, cont, email, reg_no, status, created) 
                    VALUES ('$ca_id', '$image_name', '$name', '$des', '$cont', '$email', '$reg_no', '1', '$current_date')";
            
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add CA - Legal Taxation</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .preview-image {
        max-width: 150px;
        max-height: 150px;
        border: 1px solid #ddd;
        padding: 5px;
        margin-top: 10px;
        display: none;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include("navbar.php"); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include("sidebar.php"); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-plus"></i>
              </span>&nbsp;Add CA
            </h1>
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
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Error!</h5>
              <?php echo $error; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <?php if($success): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-check"></i> Success!</h5>
              <?php echo $success; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fas fa-user-tie"></i> CA Details</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form method="post" enctype="multipart/form-data" id="caForm">
                  <div class="row">
                    <!-- Image Upload -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="image">CA Photo</label>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                            <label class="custom-file-label" for="image">Choose file</label>
                          </div>
                        </div>
                        <small class="form-text text-muted">Max 2MB. JPG, PNG, GIF allowed.</small>
                        <div id="imagePreview" class="preview-image"></div>
                      </div>
                    </div>
                    
                    <!-- CA ID (Auto-generated) -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>CA ID</label>
                        <input type="text" class="form-control bg-light" value="Auto-generated" readonly>
                        <small class="form-text text-muted">Will be generated automatically</small>
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
                        <small class="form-text text-muted">10 digits without country code</small>
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
                          <span class="badge badge-success">Active</span> (New CAs are active by default)
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <button type="submit" name="submit" form="caForm" class="btn btn-primary">
                  <i class="fas fa-save"></i> Save CA
                </button>
                <button type="reset" class="btn btn-default" onclick="resetForm()">
                  <i class="fas fa-undo"></i> Reset
                </button>
                <a href="all_ca.php" class="btn btn-secondary float-right">
                  <i class="fas fa-list"></i> View All CA
                </a>
              </div>
            </div>
          </div>
          
          <!-- Info Card -->
          <div class="col-md-4">
            <div class="card">
              <div class="card-header bg-info">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Information</h3>
              </div>
              <div class="card-body">
                <p><strong>About CA Management:</strong></p>
                <ul>
                  <li>CA ID is auto-generated (5 digits)</li>
                  <li>Profile photo is optional</li>
                  <li>All active CAs will be visible to customers</li>
                  <li>Email will be used for notifications</li>
                  <li>Mobile number must be valid for OTP verification</li>
                </ul>
                <hr>
                <div class="callout callout-info">
                  <h5><i class="fas fa-lightbulb"></i> Tip</h5>
                  <p>Ensure all information is accurate. CA details cannot be edited easily once assigned to customers.</p>
                </div>
              </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="card mt-3">
              <div class="card-header bg-success">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> CA Statistics</h3>
              </div>
              <div class="card-body">
                <?php
                $total_ca = $con->query("SELECT COUNT(*) as total FROM ca")->fetch_assoc()['total'];
                $active_ca = $con->query("SELECT COUNT(*) as active FROM ca WHERE status='1'")->fetch_assoc()['active'];
                ?>
                <div class="row">
                  <div class="col-6 text-center">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-primary"><i class="fas fa-user-tie"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Total CA</span>
                        <span class="info-box-number"><?php echo $total_ca; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 text-center">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Active</span>
                        <span class="info-box-number"><?php echo $active_ca; ?></span>
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

  <!-- Footer -->
   <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      
    </div>
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();
    
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
    
    // Form validation
    $('#caForm').submit(function(e) {
        var mobile = $('#cont').val();
        var email = $('#email').val();
        
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
        
        return true;
    });
});

function resetForm() {
    $('#imagePreview').hide().html('');
    $('#caForm')[0].reset();
    $('.select2').val('').trigger('change');
}
</script>
</body>
</html>