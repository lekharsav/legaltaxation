<?php

// *** CRUD Category ***//

if(isset($_POST['submit'])){
	$name = $_POST['des'];
	if($con->query("insert into ca_des(name) values('$name')")===true){
		echo"<script>window.location='dashboard.php?src=add_ca_des.php';</script>";
	}
}
if(isset($_POST['update'])){
    $id = $_POST['id'];
	$name = $_POST['des'];
	if($con->query("update ca_des set name='$name' where id='$id'")===true){
		echo"<script>window.location='dashboard.php?src=add_ca_des.php';</script>";
	}
}
if(isset($_GET['delete'])){
    if($con->query("delete from ca_des where id='".$_GET['id']."'")===true){
        echo"<script>window.location='dashboard.php?src=add_ca_des.php';</script>";
    }
}

?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-tag"></i>
    </span> Designation
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span> <a href="dashboard.php">Dashboard</a> / Designation
      </li>
    </ul>
  </nav>
</div>
<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Designation</h3>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="form-group">
                        <label>Designation Name</label>
                        <input type="text" name="des" class="form-control" placeholder="Enter Designation Name" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-3">
        <div class="table-responsive bg-white">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>SL NO</th><th>Designation Name</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sl=1;
                    $sql = $con->query("select * from ca_des order by id desc");
                    while($row = $sql->fetch_assoc()){
                        ?>
                        <tr>
                            <form method="POST">
                                <td><?=$sl?></td>
                                <td>
                                    <input type="text" value="<?=$row['name']?>" name="des" class="form-control" required>
                                    <input type="hidden" name="id" value="<?=$row['id']?>">
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm" name="update">Update</button>
                                    <a href="dashboard.php?src=add_ca_des.php&id=<?=$row['id']?>&delete=delete" class="btn btn-danger btn-sm" onclick="return dlt();"><i class="fa fa-trash"></i></a>
                                </td>
                            </form>
                        </tr>
                        <?php
                        $sl++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
