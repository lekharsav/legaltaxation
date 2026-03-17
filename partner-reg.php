<?php
include("db.php");
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $cont = $_POST['cont'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $business_name = $_POST['business_name'];
    $business_type = $_POST['business_type'];
    $gst_number = $_POST['gst_number'];
    
    $sql = $con->query("select * from partner where email='$email' or contact='$cont'");
    if($sql->fetch_assoc()){
        echo"<script>alert('Mobile Number or Email Already Exists.');window.location='partner-reg.php';</script>";
    }else{
        if($con->query("insert into partner(name,contact,email,password,business_name,business_type,gst_number,created,status) values('$name','$cont','$email','$pass','$business_name','$business_type','$gst_number','".date('Y-m-d')."','0')")==true){
            echo"<script>alert('Partner Registration Submitted Successfully. Waiting for admin approval.');window.location='partner-login.php';</script>";
        }else{
          echo"<script>alert('Server ERROR!!!');window.location='partner-reg.php';</script>";  
        }
    }
}

?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
<title>Partner Registration - Legal Taxation</title>
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
    
    .partner-reg-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 50px 0;
    }
    
    .reg-container-wrapper {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 30px;
    }
    
    .reg-hero {
        background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
        padding: 40px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 500px;
    }
    
    .reg-form-container {
        padding: 40px;
    }
    
    .reg-hero-content h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .reg-hero-content p {
        font-size: 16px;
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
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        font-size: 14px;
    }
    
    .benefits-list i {
        margin-right: 10px;
        background: rgba(255, 255, 255, 0.2);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .reg-header {
        text-align: center;
        margin-bottom: 30px;
    }
    
    .reg-header h2 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }
    
    .reg-header p {
        color: #666;
        font-size: 14px;
    }
    
    .brand-logo {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .brand-logo h3 {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }
    
    .brand-logo span {
        color: #ff7e5f;
    }
    
    .brand-logo .tagline {
        color: #666;
        font-size: 13px;
        margin-top: 5px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: 500;
        font-size: 13px;
    }
    
    .form-control-custom {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        transition: all 0.3s;
        font-family: 'Poppins', sans-serif;
    }
    
    .form-control-custom:focus {
        border-color: #ff7e5f;
        box-shadow: 0 0 0 2px rgba(255, 126, 95, 0.1);
        outline: none;
    }
    
    .submit-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Poppins', sans-serif;
        margin-top: 10px;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 126, 95, 0.3);
    }
    
    .divider {
        text-align: center;
        margin: 20px 0;
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
        padding: 0 10px;
        color: #666;
        font-size: 13px;
        position: relative;
    }
    
    .login-link {
        text-align: center;
        margin-top: 15px;
        color: #666;
        font-size: 14px;
    }
    
    .login-link a {
        color: #ff7e5f;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }
    
    .login-link a:hover {
        text-decoration: underline;
        color: #fe8c6a;
    }
    
    .error-alert {
        background: #fee;
        color: #c33;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        border-left: 3px solid #c33;
        font-size: 13px;
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
        gap: 8px;
    }
    
    .breadcrumb-custom li {
        font-size: 13px;
    }
    
    .breadcrumb-custom a {
        color: #ff7e5f;
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .breadcrumb-custom a:hover {
        color: #fe8c6a;
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .reg-hero {
            min-height: 300px;
            padding: 25px;
        }
        
        .reg-form-container {
            padding: 25px;
        }
        
        .reg-hero-content h1 {
            font-size: 24px;
        }
        
        .partner-reg-section {
            padding: 15px 0;
        }
    }
</style>
</head>
<body>

<?php include("includes/header.php");?>

<section class="partner-reg-section">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-custom">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li>/</li>
                        <li>Partner Registration</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="reg-container-wrapper">
                    <div class="row">
                        <!-- Left Side - Welcome Section -->
                        <div class="col-md-6">
                            <div class="reg-hero">
                                <div class="reg-hero-content">
                                    <h1>Join as Our Partner</h1>
                                    <p>Register as a business partner and grow with Legal Taxation. Get access to exclusive business opportunities and resources.</p>
                                    
                                    <ul class="benefits-list">
                                        <li>
                                            <i class="fas fa-handshake"></i>
                                            <span>Business partnership opportunities</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-chart-line"></i>
                                            <span>Revenue sharing model</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-users"></i>
                                            <span>Access to client referrals</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-tools"></i>
                                            <span>Business tools & resources</span>
                                        </li>
                                        <li>
                                            <i class="fas fa-headset"></i>
                                            <span>Dedicated partner support</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Registration Form -->
                        <div class="col-md-6">
                            <div class="reg-form-container">
                                <div class="brand-logo">
                                    <h3>Legal <span>Taxation</span></h3>
                                    <p class="tagline">Partner Registration</p>
                                </div>
                                
                                <div class="reg-header">
                                    <h2>Partner Registration</h2>
                                    <p>Fill in your business details</p>
                                </div>
                                
                                <form method="POST">
                                    <!-- Personal Details -->
                                    <div class="form-group">
                                        <label for="name">Full Name *</label>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               class="form-control-custom" 
                                               placeholder="Your full name" 
                                               required
                                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cont">Mobile Number *</label>
                                                <input type="text" 
                                                       id="cont" 
                                                       name="cont" 
                                                       class="form-control-custom" 
                                                       placeholder="10-digit mobile" 
                                                       required
                                                       maxlength="10"
                                                       pattern="[0-9]{10}"
                                                       title="10-digit mobile number"
                                                       value="<?php echo isset($_POST['cont']) ? htmlspecialchars($_POST['cont']) : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email Address *</label>
                                                <input type="email" 
                                                       id="email" 
                                                       name="email" 
                                                       class="form-control-custom" 
                                                       placeholder="your@email.com" 
                                                       required
                                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Business Details -->
                                    <div class="form-group">
                                        <label for="business_name">Business/Company Name *</label>
                                        <input type="text" 
                                               id="business_name" 
                                               name="business_name" 
                                               class="form-control-custom" 
                                               placeholder="Your business name" 
                                               required
                                               value="<?php echo isset($_POST['business_name']) ? htmlspecialchars($_POST['business_name']) : ''; ?>">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="business_type">Business Type</label>
                                                <select id="business_type" name="business_type" class="form-control-custom">
                                                    <option value="">Select Business Type</option>
                                                    <option value="Proprietorship">Proprietorship</option>
                                                    <option value="Partnership">Partnership</option>
                                                    <option value="LLP">LLP</option>
                                                    <option value="Private Limited">Private Limited</option>
                                                    <option value="Public Limited">Public Limited</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gst_number">GST Number</label>
                                                <input type="text" 
                                                       id="gst_number" 
                                                       name="gst_number" 
                                                       class="form-control-custom" 
                                                       placeholder="GST No (Optional)"
                                                       value="<?php echo isset($_POST['gst_number']) ? htmlspecialchars($_POST['gst_number']) : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Password -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pass">Password *</label>
                                                <input type="password" 
                                                       id="pass" 
                                                       name="pass" 
                                                       class="form-control-custom" 
                                                       placeholder="Create password" 
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cpass">Confirm Password *</label>
                                                <input type="password" 
                                                       id="cpass" 
                                                       name="cpass" 
                                                       class="form-control-custom" 
                                                       placeholder="Confirm password" 
                                                       required>
                                                <span id="password-match" class="d-block mt-1" style="font-size: 11px;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" name="submit" class="submit-btn">
                                        <i class="fas fa-user-plus"></i>
                                        Register as Partner
                                    </button>
                                    
                                    <div class="divider">
                                        <span>Already a Partner?</span>
                                    </div>
                                    
                                    <div class="login-link">
                                        Already have an account? 
                                        <a href="partner-login.php">Login Here</a>
                                    </div>
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
$(document).ready(function() {
    // Password match validation
    $('#pass, #cpass').on('keyup', function () {
        if($('#pass').val() != "" && $('#cpass').val() != ""){
            if ($('#pass').val() == $('#cpass').val()) {
                $('#password-match').html('✓ Passwords match').css('color', 'green');
            } else {
                $('#password-match').html('✗ Passwords do not match').css('color', 'red');
            }
        } else {
            $('#password-match').html('');
        }
    });
    
    // Mobile number validation
    $('#cont').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });
});
</script>
</body>
</html>