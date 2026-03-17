<?php
if(isset($_POST['submit'])){
    $file = str_replace(" ","",$_FILES["file"]["tmp_name"]);
    $fname = $_FILES["file"]["name"];
    
    if($con->query("insert into service(cate,child_cate,sub_cate,image,title,price,des,posted,status) values('".$_POST['cate']."','".$_POST['child_cate']."','".$_POST['sub_cate']."','$fname','".$_POST['title']."','".$_POST['price']."','".$_POST['s_des']."','".date('Y-m-d',strtotime($current_date))."','0')")===true){
        move_uploaded_file($file,"../images/".$fname);
        echo"<script>window.location='dashboard.php?src=add_service.php';</script>";
    }else{
        echo"<script>alert('Server Error!!');window.location='dashboard.php?src=add_service.php';</script>";
    }
}
?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-airplay"></i>
    </span> Add Service
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Services / Add Service
      </li>
    </ul>
  </nav>
</div>
<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Service Details</h3>
                <form method="post" enctype="multipart/form-data" class="row">
                    <div class="form-group col-lg-4">
                        <label>Category</label>
                        <select name="cate" class="form-control cate" required>
                            <option value="" selected disabled>Choose Category</option>
                            <?php
                            $sql = $con->query("select * from cate order by id desc");
                            while($row = $sql->fetch_assoc()){
                                ?>
                                <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Child Category</label>
                        <select name="child_cate" class="form-control child_cate" required>
                            <option value="" selected disabled>Choose Child Category</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Sub Category</label>
                        <select name="sub_cate" class="form-control sub_cate" required>
                            <option value="" selected disabled>Choose Child Category</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Service Image</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Service Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter Service Title" required>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Price</label>
                        <input type="text" name="price" class="form-control" placeholder="Enter Service Price" required>
                    </div>
                    <div class="form-group col-lg-12">
                        <label>Description</label>
                        <textarea name="s_des" class="form-control" placeholder="Enter Short Description" required></textarea>
                    </div>
                    <div class="form-group col-lg-6">
                        <button class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>