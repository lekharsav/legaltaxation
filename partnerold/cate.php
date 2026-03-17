<?php
$id = $_GET['id'];

$query = $con->query("select * from cate where id='".$_GET['id']."'");
$rows = $query->fetch_assoc();
?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-airplay"></i>
    </span> <?php echo $rows['name'] ?>
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Services / <?php echo $rows['name'] ?>
      </li>
    </ul>
  </nav>
</div>
<div class="row">
    <?php
    $total_applicant = 0;
    $sql = $con->query("select * from service where cate='$id'");
    while($row = $sql->fetch_assoc()){
        $sql2 = $con->query("select * from apply where sid='".$row['id']."'");
        while($row2 = $sql2->fetch_assoc()){
            if($row2['send_to'] == ""){
                $total_applicant++;
            }
            
        }
    ?>
    <div class="col-lg-4 mb-3">
        <div class="card">
            <img src="../images/<?=$row['image']?>">
            <div class="card-body">
                <h4 class="card-title"><?=$row['title']?></h4>
                <p>Price: ₹<?=$row['o_price']?></p>
                <p>Applicants: <span class="badge bg-success"><?=$total_applicant?></span></p>
                <div class="d-grid">
                   <!--
                    <button class="btn btn-<?php if($total_applicant == 0){echo"danger";}else{echo"primary";}?> data_req" id="data_btn_<?=$row['id']?>" data-apply="<?=$total_applicant?>" data-id="data_btn_<?=$row['id']?>" data-cate="<?=$_GET['id']?>" data-sid='<?=$row['id']?>' <?php if($total_applicant == 0){echo"disabled";}?>>Request Data</button>
                    -->
                    <a href="dashboard.php?src=leads.php&sid=<?php echo $row['id'] ?>&cate=<?php echo $row['cate'] ?>" class="btn btn-<?php if($total_applicant == 0){echo"danger";}else{echo"primary";}?>">View Leads</a> 
                </div>
                
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    
</div>
