<!doctype html>
<html lang="en-gb" class="no-js">
<head>
<title>Legal Taxation</title>
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
                        <li>Contact Us</li>
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
                        <form>
                            <label>Full Name</label>
                            <input class="form-control" name="Name" id="Name" placeholder="Full Name" required="">
                            <label>Phone Number</label>
                            <input class="form-control" name="Number" id="Number" placeholder="Phone Number" required="">
                            <label>Mail Id</label>
                            <input class="form-control" name="Mail" id="Mail" placeholder="Mail Id" required="">
                            <label>Subject</label>
                            <input class="form-control" name="Subject" id="Subject" placeholder="Subject" required="">
                            <label>Message</label>
                            <textarea class="form-control" name="Message" id="Message" placeholder="Message" required=""></textarea>
                            <div class="text-center">
                                <button class="submit_btn" type="submit" name="submit-form">Submit <i class="fa-solid fa-arrow-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<?php include("includes/footer.php");?>
</body>
</html>