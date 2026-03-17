<?php
include("db.php");
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $cont = $_POST['cont'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $sql = $con->query("select * from customer where email='$email' or contact='$cont'");
    if($sql->fetch_assoc()){
        echo"<script>alert('Mobile Number or Email Already Exists.');window.location='customer-reg';</script>";
    }else{
        if($con->query("insert into customer(name,contact,email,password,created,status) values('$name','$cont','$email','$pass','".date('Y-m-d',strtotime($current_date))."','0')")==true){
            echo"<script>alert('Account Created Successfully.');window.location='user-login';</script>";
        }else{
          echo"<script>alert('Server ERROR!!!');window.location='customer-reg';</script>";  
        }
    }
}

?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
<title>Customer Registration</title>
<link rel="shortcut icon" href="images/favicon.webp" type="image/x-icon" /><meta charset="utf-8">
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
</head>
<body>


<?php include("includes/header.php");?>

<section class="income_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb_area">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li>/</li>
                        <li>Customer Registration</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="inner_area">
                    <div class="login_box">
                        <form method="post">
                            <label>Full Name</label>
                            <input class="form-control" name="name" id="User" placeholder="Your Full Name" required="">
                            <label>Mobile Number</label>
                            <input class="form-control" name="cont" id="" placeholder="Your Mobile Number" required="">
                            <label>Email Address</label>
                            <input class="form-control" name="email" id="" placeholder="Your Email Address" required="">
                            <label>Create a Password</label>
                            <input type="password" class="form-control" name="pass" id="password" placeholder="Create Password" required="">
                            <label>Create a Password</label>
                            <input type="password" class="form-control" name="cpass" id="confirm_password" placeholder="Confirm Password" required="">
                            <span id="message" class="fw-bold d-block"></span>
                            <a href="user-login.php">Already Have an Account? Login</a>
                            <div class="text-center">
                                <button class="submit_btn" type="submit" name="submit">Submit Details <i class="fa-solid fa-arrow-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<?php include("includes/footer.php");?>
<script type="text/javascript">
$('#password, #confirm_password').on('keyup', function () {
    if($('#password').val() != "" && $('#confirm_password').val() != ""){
        if ($('#password').val() == $('#confirm_password').val()) {
        $('#message').html('Matching').css('color', 'green');
      } else 
        $('#message').html('Not Matching').css('color', 'red');
    }
  
});
</script>
</body>
</html>