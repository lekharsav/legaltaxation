<?php
if(isset($_GET['delete'])){
    if($con->query("delete from service where id='".$_GET['id']."'")===true){
        echo"<script>window.location='dashboard.php?src=all_service.php';</script>";
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
                <h3 class="card-title">Service List</h3>
                <div class="table-responsive">
                    <table class="table table-stripped example">
                        <thead>
                            <tr>
                                <th>SL NO</th><th>Image</th><th>Title</th><th>Price</th><th>Description</th><th>Date</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            $sql = $con->query("select * from service order by id desc");
                            while($row = $sql->fetch_assoc()){
                                ?>
                                <tr>
                                    <td><?=$sl?></td>
                                    <td><img src="../images/<?=$row['image']?>"></td>
                                    <td><?=$row['title']?></td>
                                    <td><?=$row['price']?></td>
                                    <td><?=$row['des']?></td>
                                    <td><?=date('d-M-Y',strtotime($row['posted']))?></td>
                                    <td>
                                        <a href="dashboard.php?src=edit_service.php&id=<?=$row['id']?>" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></a>
                                        <a href="dashboard.php?src=all_service.php&id=<?=$row['id']?>&delete=delete" class="btn btn-sm btn-danger" onclick="return dlt();"><i class="fa fa-trash"></i></a>
                                    </td>
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
    </div>
</div>