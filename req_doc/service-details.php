<?php
include('db.php');
$sql= $con->query("select * from service where id='".$_GET['id']."'");
$row = $sql->fetch_assoc();

$sql2= $con->query("select * from cate where id='".$row['cate']."'");
if($row2 = $sql2->fetch_assoc()){
    $cate_name = $row2['name'];
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
</head>
<body>


<?php include("includes/header.php");?>

<section class="income_area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb_area">
                    <ul>
                        <li><a href="index">Legal Taxation</a></li>
                        <li>/</li>
                        <li><?php echo $cate_name?></li>
                        <li>/</li>
                        <li><?php echo $row['title'] ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <img src="images/<?php echo $row['image'] ?>" class="w-100 mb-3">
                                <p class="fw-bold">Document Required</p>
                                <?php
                                $d = explode(",",$row['doc_req']);
                                foreach($d as $doc){
                                    ?>
                                    <p><i class="fa fa-check"></i> <?php echo $doc ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-md-7">
                                <p class="h3 mb-3 fw-bold"><?php echo $row['title'] ?></p>
                                <p><span class="badge bg-success"><i class="fa fa-star"></i> 4.5</span> <a href="">1253 Customer's Reviews</a></p>
                                <hr>
                                <p class="fw-bold">Pricing Summary:- </p>
                                <p>Market Price: ₹<?php echo $row['m_price'] ?></p>
                                <p>Offer Price: ₹<?php echo $row['o_price'] ?></p>
                                <p><?php echo $row['s_des'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include("includes/footer.php");?>
</body>
</html>