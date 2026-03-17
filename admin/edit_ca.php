<?php

if(isset($_POST['submit'])){
    $fname = $_FILES['image']['name'];
    $file = $_FILES['image']['tmp_name'];
    if($con->query("update ca set 

name='".$_POST['name']."',
des='".$_POST['des']."',
cont='".$_POST['cont']."',
email='".$_POST['email']."',
reg_no='".$_POST['reg_no']."' where id='".$_POST['id']."'")===true){
        if($fname != ""){
            $con->query("update ca set image='$fname' where id='".$_POST['id']."'");
            move_uploaded_file($file,'ca/'.$fname);
        }
        
        echo"<script>window.location='dashboard.php?src=all_ca.php';</script>";
    }else{
        echo"<script>alert('Server Error');window.location='dashboard.php?src=all_ca.php';</script>";
    }
}

$id = $_GET['id'];
$sql = $con->query("select * from ca where id='$id'");
$row = $sql->fetch_assoc();

?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-tag"></i>
    </span> Edit CA
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Edit CA
      </li>
    </ul>
  </nav>
</div>
<div class="row">
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">CA Details</h3>
            </div>
            <div class="card-body">
                <form method="post" class="row" enctype="multipart/form-data">
                    <div class="col-lg-4 mb-3">
                        <label>CA Image</label>
                        <input type="file" name="image" class="form-control" >
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Name</label>
                        <input type="text" name="name" value="<?php echo $row['name'] ?>" class="form-control" placeholder="Enter CA Name" required>
                    </div> 
                    <div class="col-lg-4 mb-3">
                        <label>CA Designation</label>
                        <select name="des" class="form-control" required>
                            <option value="">Choose Option</option>
                            <?php
                            $sql2 = $con->query("select * from ca_des order by name asc");
                            while($row2 = $sql2->fetch_assoc()){
                                ?>
                                <option value="<?php echo $row2['id'] ?>" <?php if($row['des'] == $row2['id']){echo"selected";}?>><?php echo $row2['name'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Mobile Number</label>
                        <input type="text" name="cont" value="<?php echo $row['cont'] ?>" class="form-control" placeholder="Enter Mobile Number" required>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?php echo $row['email'] ?>" class="form-control" placeholder="Enter Email Address" required>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <label>CA Registration No.</label>
                        <input type="text" name="reg_no" value="<?php echo $row['reg_no'] ?>" class="form-control" placeholder="Enter Registration No." >
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <button class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
