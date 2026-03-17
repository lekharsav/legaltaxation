<?php
include('db.php');
$sql= $con->query("select * from service where id='".$_GET['id']."'");
$row = $sql->fetch_assoc();

$sql2= $con->query("select * from cate where id='".$row['cate']."'");
if($row2 = $sql2->fetch_assoc()){
    $cate_name = $row2['name'];
}
if(isset($_POST['submit'])){
    $sid = $_POST['sid'];
    $cid = $_POST['cid'];
    $sql = $con->query("select * from apply where sid='$sid' and cid='$cid'");
    if($sql->fetch_assoc()){
        echo"<script>alert('Thanks For Apply.');window.location='service-details.php?id=$sid';</script>";
    }else{
        if($con->query("insert into apply(cid,sid,created) values('$cid','$sid','".date('Y-m-d',strtotime($current_date))."')") === true){
            
            $file = $_FILES['file']['tmp_name'];
            $fname = $_FILES['file']['name'];
            $doc_id = $_POST['doc_id'];
            $mi = new MultipleIterator();
            $mi->attachIterator(new ArrayIterator($file));
            $mi->attachIterator(new ArrayIterator($fname));
            $mi->attachIterator(new ArrayIterator($doc_id));
            foreach($mi as $value){
                list($file,$fname,$doc_id) = $value;
                $file_name = str_replace(" ","",$fname);
                $con->query("insert into req_doc(sid,cid,type,file) values('$sid','$cid','$doc_id','$file_name')");
                move_uploaded_file($file,"req_doc/".$file_name);
            }
            
            echo"<script>alert('Thanks For Apply.');window.location='service-details.php?id=$sid';</script>";
        }else{
            echo"<script>alert('Server Error!!!');window.location='service-details.php?id=$sid';</script>";
        }
    }
}
if(isset($_POST['review_submit'])){
    $sql = $con->query("select * from reviews where sid='".$_POST['sid']."' and cid='".$_COOKIE["tax_customer_log"]."'");
    if($sql->fetch_assoc()){
        echo"<script>alert('You already submit your review.');window.location='service-details.php?id=".$_POST['sid']."';</script>";
    }else{
        if($con->query("insert into reviews(sid,cid,star,content) values('".$_POST['sid']."','".$_COOKIE['tax_customer_log']."','".$_POST['rate']."','".$_POST['content']."')") === true){
            echo"<script>alert('Thanks for submit your review');window.location='service-details.php?id=".$_POST['sid']."';</script>";
        }else{
            echo"<script>alert('Server Error!!');window.location='service-details.php?id=".$_POST['sid']."';</script>";
        }
    }
}

$num_of_review = 0;
$total_star = 0;
$av_rating = 0;
$query = $con->query("select * from reviews where sid='".$_GET['id']."'");
while($result = $query->fetch_assoc()){
    $total_star += $result['star'];
    $num_of_review++;
}
if($num_of_review == 0){
    $av_rating = 0;
}else{
    $av_rating = $total_star/$num_of_review;
}
?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
<title><?php echo $cate_name ?></title>
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
    <style type="text/css">
        .oth_head{
            font-size: 14pt;
            color: #004678 !important;
        }
        .oth_des{
            font-size: 10pt;
        }
        .other_serv_box .card:hover{
            transition: 0.3s;
            box-shadow: 0 0 30px rgba(0,0,0,0.2);
            z-index:1;
            position:relative;
        }
    </style>
</head>
<body>


<?php include("includes/header.php");?>

<section class="income_area service_details_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb_area">
                    <ul>
                        <li><a href="index">Legal Taxation</a></li>
                        <li>/</li>
                        <li><?php echo $cate_name?></li>
                        <li>/</li>
                        <li><b><?php echo $row['title'] ?></b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-7 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="serv_doc_area">
                                    <img src="images/<?php echo $row['image'] ?>" class="w-100 mb-3">
                                    <p class="fw-bold">Document Required</p>
                                    <?php
                                    $d = explode(",",$row['doc_req']);
                                    foreach($d as $doc){
                                        $sql2 = $con->query("select * from doc where id='$doc'");
                                        if($row2 = $sql2->fetch_assoc()){
                                            echo'<p>'.$row2['name'].'</p>';
                                        }
                                        ?>
                                        
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-7">

                                <p class="head"><?php echo $row['title'] ?></p>
                                <p><span class="badge"><i class="fa fa-star"></i> <?php echo $av_rating ?> Ratings</span> <a href="#cus_review"><?php if($num_of_review == 0){echo'No Reviews';}else{echo $num_of_review." Customer's Reviews";} ?> </a></p>
                                <hr>
                                <div class="serv_price_box">
                                    <ul>
                                        <p>Pricing Summary:- </p>
                                        <li>Market Price: <del>₹<?php echo $row['m_price'] ?></del></li>
                                        <li>Offer Price: ₹<?php echo $row['o_price'] ?></li>
                                        <li>You Save: <span>₹<?php echo $row['m_price']-$row['o_price'] ?></span></li>
                                    </ul>
                                </div>
                                <div class="serv_details_para">
                                    <p><?php echo $row['s_des'] ?></p>
                                    <?php
                                    if(@$_COOKIE['tax_customer_log']){
                                        ?>
                                        <button class="btn btn-primary apply_btn" data-sid='<?=$row['id']?>' data-cid='<?=$_COOKIE['tax_customer_log']?>' data-bs-toggle="modal" data-bs-target="#applyModal">Apply Now <i class="fa fa-external-link"></i></button>
                                        <!-- <a href="checkout.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary apply_btn">Apply Now <i class="fa fa-external-link"></i></a> -->
                                        <?php
                                    }else{
                                        ?>
                                        <div class="btn_area">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">Apply Now <i class="fa-solid fa-arrow-right"></i></a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <div class="serv_desc">
                                    <?php echo $row['des'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-3">
                <div class="service_right">
                    <p class="head">Other's Services</p>
                    <hr>
                    <div class="row">
                        <?php
                        $sql2 = $con->query("select * from service where cate='".$row['cate']."' and id!='".$_GET['id']."' order by id desc limit 6");
                        while($row2 = $sql2->fetch_assoc()){
                            ?>
                            <div class="col-lg-12 col-sm-12">
                                <a href="service-details.php?id=<?php echo $row2['id']?>" class="other_serv_box">
                                    <div class="card">
                                        <div class="card_img">
                                            <img src="./images/<?php echo $row2['image'] ?>">
                                        </div>
                                        <div class="card-body">
                                            <p class="oth_head"><?php echo $row2['title'] ?></p>
                                            <p class="oth_des"><?php echo substr($row2['s_des'],0,50) ?>...</p>
                                            <p class="text-dark">Market Price: <del>₹<?php echo $row2['m_price'] ?></del></p>
                                            <p class="text-dark">Offer Price: ₹<?php echo $row2['o_price'] ?></p>
                                            <p class="text-dark">You Save: <span>₹<?php echo $row2['o_price'] ?></span></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12"><span  id="cus_review"></span></div>
            <div class="col-md-12 mb-3">
                <p class="h4 fw-bold text-center mb-3">Customer's Review</p>
                <div class="row">
                    <?php
                    $r_count = 0;
                    $review_sql = $con->query("select * from reviews where sid='".$_GET['id']."'");
                    while($review_row = $review_sql->fetch_assoc()){

                        $sql2 = $con->query("select * from customer where id='".$review_row['cid']."'");
                        $row2 = $sql2->fetch_assoc();
                        ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="fw-bold"><?php echo $row2['name'] ?></h4>
                                <?php
                                for($i=1;$i<=5;$i++){
                                    if($i<=$review_row['star']){
                                        $gold = "text-warning";
                                    }else{
                                        $gold = "";
                                    }
                                    echo"<small><i class='fa fa-star ".$gold."'></i></small>";
                                }
                                ?>
                                <p class="mt-2"><i class="fa fa-quote-left"></i> <?php echo $review_row['content'] ?> <i class="fa fa-quote-right"></i></p>
                            </div>
                        </div>
                    </div>
                        <?php
                        $r_count++;
                    }
                    if($r_count == 0){
                        echo"<h5 class='text-grey text-center fw-bold'>No Review Found !!</h5>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-12 <?php if(!@$_COOKIE['tax_customer_log']){echo'd-none';}?>">
                <p class="h4 fw-bold text-center mb-3">Write a Review for Us</p>
                <form method="POST" class="mb-3">
                    <div class="rate">
                        <input type="radio" id="star5" name="rate" value="5" />
                        <label for="star5" title="text">5 stars</label>
                        <input type="radio" id="star4" name="rate" value="4" />
                        <label for="star4" title="text">4 stars</label>
                        <input type="radio" id="star3" name="rate" value="3" />
                        <label for="star3" title="text">3 stars</label>
                        <input type="radio" id="star2" name="rate" value="2" />
                        <label for="star2" title="text">2 stars</label>
                        <input type="radio" id="star1" name="rate" value="1" />
                        <label for="star1" title="text">1 star</label>
                    </div>
                    <textarea name="content" class="form-control mb-3" rows="5" placeholder="Write Review" required></textarea>
                    <input type="hidden" name="sid" value="<?php echo $_GET['id'] ?>">
                    <button class="btn btn-primary" name="review_submit">Submit</button>
                </form>
            </div>
            
        </div>
    </div>
</section>

<!-- Applications Modals -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header alert alert-danger text-white">
        <h5 class="modal-title text-dark fw-bold" id="exampleModalLabel">Please Login First. <a href="user-login.php">Click Here</a> to Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Application Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" enctype="multipart/form-data">
          <div class="col-lg-6 mb-2">
        <label>Your Name</label>
        <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter Name" required>
    </div>
    <div class="col-lg-6 mb-2">
        <label>Email</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control form-control-sm" id="email_sec" placeholder="Enter Email Address" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary btn-sm" type="button" id="otp_btn">Send OTP</button>
        </div>
    </div>
    <div class="col-lg-6 mb-2 otp_box">
        <label>OTP</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control form-control-sm" id="otp_sec" placeholder="Enter OTP" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-success btn-sm" type="button" id="verify_otp">Verify</button>
        </div>
    </div>
    <div class="col-lg-6 mb-2">
        <label>Contact</label>
        <input type="text" name="contact" class="form-control form-control-sm" placeholder="Enter Contact" required>
    </div>
              <div class="form-group mb-3 row">
                <?php
                $d = explode(",",$row['doc_req']);
                foreach($d as $doc){
                    $sql5 = $con->query("select * from doc where id='$doc'");
                    if($row5 = $sql5->fetch_assoc()){
                        ?>
                        <div class="col-lg-6 mb-3">
                            <label><?php echo $row5['name']; ?></label>
                            <input type="file" name="file[]" class="form-control" required>
                            <input type="hidden" name="doc_id[]" value="<?php echo $row5['id'] ?>">
                        </div>
                        <?php
                    }
                }
                ?>
              </div>
              <div class="form-group mb-3">
                <input type="hidden" name="cid" value="<?php echo @$_COOKIE['tax_customer_log']?>">
                <input type="hidden" name="sid" id="sid_val">
                <button class="btn btn-primary" name="submit">Submit Application</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Application Form</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <!-- <form method="post" enctype="multipart/form-data" class="row">
    <div class="col-lg-6 mb-2">
        <label>Your Name</label>
        <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter Name" required>
    </div>
    <div class="col-lg-6 mb-2">
        <label>Email</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control form-control-sm" id="email_sec" placeholder="Enter Email Address" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary btn-sm" type="button" id="otp_btn">Send OTP</button>
        </div>
    </div>
    <div class="col-lg-6 mb-2 otp_box">
        <label>OTP</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control form-control-sm" id="otp_sec" placeholder="Enter OTP" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-success btn-sm" type="button" id="verify_otp">Verify</button>
        </div>
    </div>
    <div class="col-lg-6 mb-2">
        <label>Contact</label>
        <input type="text" name="contact" class="form-control form-control-sm" placeholder="Enter Contact" required>
    </div>
    <div class="col-lg-6 mb-2">
        <button class="btn btn-sm btn-success mt-4" id="proceed_btn" disabled>Proceed <i class="fa fa-arrow-circle-right"></i></button>
    </div>
</form> -->


      </div>
    </div>
  </div>
</div>
<?php include("includes/footer.php");?>
<script>
    $('.apply_btn').click(function(){
        var sid = $(this).data("sid");
        $('#sid_val').val(sid);
    });
</script>
<script type="text/javascript">
$('#otp_btn').click(function(){
    var email = $('#email_sec').val();
    if(email == ""){
        alert("Please enter an email address");
    }else{
        $(this).html("Sending..");
        $('#email_sec').attr("readonly",true);
        $.ajax({
            url: 'partner_ajax_email.php',
            type: 'get',
            data: 'email=' + email,
            success: function(data){
                $('#otp_btn').html("Sent ✓");
                if(data > 0){
                    $('#otp_box').removeClass('d-none');
                    $('#gen_otp').val(data);
                } else {
                    alert("Server ERROR!! Please try again later.");
                }
            }
        });
    }
});

$('#verify_otp').click(function(){
    var enteredOtp = $('#otp_sec').val();
    $.ajax({
        url: 'verify_otp.php',
        type: 'post',
        data: {otp: enteredOtp},
        success: function(response){
            if(response == "success"){
                alert("OTP verified successfully!");
                $('#otp_btn').prop('disabled', true); // Disable "Send OTP" button after verification
                $('#verify_otp').prop('disabled', true); // Disable "Verify" button after verification
                $('#otp_sec').prop('readonly', true); // Make the OTP input field read-only after verification

                // Enable the Proceed button
                $('#proceed_btn').prop('disabled', false);
            } else {
                alert("Invalid OTP. Please try again.");
                $('#otp_sec').val(''); // Clear the OTP field for re-entry
            }
        }
    });
});

$('#proceed_btn').click(function(){
    // Change the button text to show processing
    $(this).html("Proceeding...");
    
    // Optionally disable the button while redirecting
    $(this).prop('disabled', true);

    // Redirect to the apply-form.php page
    window.location.href = 'apply-form.php'; // Redirect to the specified page
});

</script>
</body>
</html>