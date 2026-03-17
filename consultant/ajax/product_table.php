<?php
include("../db.php");
$sl = 1;
$sql = $con->query("select * from product order by id asc");
while($row = $sql->fetch_assoc()){
   ?>
   <tr>
      <td><?php echo $sl ?></td>
      <td>
         <b><?php echo $row['name']; ?></b><br>
         <span style="font-size: 8pt;"><?php echo $row['des'] ?></span>    
      </td>
      <td>
         <?php
         if($row['category'] == 0){
            echo"Delivery";
         }if($row['category'] == 1){
            echo"Development";
         }if($row['category'] == 2){
            echo"Marketing";
         }
         ?>
      </td>
      <td><?php echo $row['hsn'] ?></td>
      <td><?php echo $row['per']?></td>
      <td>₹<?php echo $row['s_price']?></td>
      <td><?php echo $row['dis']?></td>
      <td>₹<?php echo $row['d_price']?></td>
      <td>
         <a href="dashboard.php?src=product.php&id=<?php echo $row['id'] ?>&edit=" class="badge bg-success"><i class="fa fa-edit"></i></a>
         <a href="#" class="badge bg-danger" onclick="myFnc(<?php echo $row['id'] ?>,'product_dlt')"><i class="fa fa-trash"></i></a>
      </td>
   </tr>
   <?php
   $sl++;
}
?>