<?php
include('db.php');
$sql= $con->query("select * from service where id='".$_GET['id']."'");
$row = $sql->fetch_assoc();

$sql2= $con->query("select * from cate where id='".$row['cate']."'");
if($row2 = $sql2->fetch_assoc()){
    $cate_name = $row2['name'];
}
// if(isset($_POST['submit'])){
//     $sid = $_POST['sid'];
//     $cid = $_POST['cid'];
//     $sql = $con->query("select * from apply where sid='$sid' and cid='$cid'");
//     if($sql->fetch_assoc()){
//         echo"<script>alert('Thanks For Apply.');window.location='service-details.php?id=$sid';</script>";
//     }else{
//         if($con->query("insert into apply(cid,sid,created) values('$cid','$sid','".date('Y-m-d',strtotime($current_date))."')") === true){
            
//             $file = $_FILES['file']['tmp_name'];
//             $fname = $_FILES['file']['name'];
//             $doc_id = $_POST['doc_id'];
//             $mi = new MultipleIterator();
//             $mi->attachIterator(new ArrayIterator($file));
//             $mi->attachIterator(new ArrayIterator($fname));
//             $mi->attachIterator(new ArrayIterator($doc_id));
//             foreach($mi as $value){
//                 list($file,$fname,$doc_id) = $value;
//                 $file_name = str_replace(" ","",$fname);
//                 $con->query("insert into req_doc(sid,cid,type,file) values('$sid','$cid','$doc_id','$file_name')");
//                 move_uploaded_file($file,"req_doc/".$file_name);
//             }
            
//             echo"<script>alert('Thanks For Apply.');window.location='service-details.php?id=$sid';</script>";
//         }else{
//             echo"<script>alert('Server Error!!!');window.location='service-details.php?id=$sid';</script>";
//         }
//     }
// }


?>
<!doctype html>
<html lang="en-gb" class="no-js">
<head>
    <title><?php echo $cate_name ?></title>
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
                        <li><b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h3 class="mb-4">Proceed with Your Application</h3>
        <form method="post" enctype="multipart/form-data" class="row">
            <div class="col-lg-6 mb-2">
                <label>Your Name</label>
                <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter Name" required>
            </div>
            <div class="col-lg-6 mb-2">
                <label>Email</label>
                <input type="email" name="email" class="form-control form-control-sm" placeholder="Enter Email Address" required>
            </div>
            <div class="col-lg-6 mb-2">
                <label>Contact</label>
                <input type="text" name="contact" class="form-control form-control-sm" placeholder="Enter Contact" required>
            </div>
            <div class="col-lg-6 mb-2">
                <label>Upload Documents</label>
                <input type="file" name="documents" class="form-control form-control-sm" required>
            </div>
            <div class="col-lg-12 mb-2">
                <button type="submit" class="btn btn-sm btn-success mt-4">Submit Application <i class="fa fa-arrow-circle-right"></i></button>
            </div>
        </form>
    </div>
</section>

<?php include("includes/footer.php");?>

<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
