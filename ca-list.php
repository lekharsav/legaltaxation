<?php
include("db.php");
?>
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
                        <li>CA List</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <?php
                    $sql = $con->query("select * from ca order by id desc");
                    while($row = $sql->fetch_assoc()){
                        $sql2 = $con->query("select * from ca_des where id='".$row['des']."'");
                        if($row2 = $sql2->fetch_assoc()){
                            $ca_des = $row2['name'];
                        }
                        ?>
                        <div class="col-lg-4 mb-3">
                            <div class="card">
                                <img src="admin/ca/<?php echo $row['image'] ?>" style="width: 100%; height: 300px; object-fit: cover;">
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo $row['name'] ?></h4>
                                    <hr>
                                    <p><i class="fa fa-tag"></i> <?php echo $ca_des ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>




<?php include("includes/footer.php");?>
</body>
</html>