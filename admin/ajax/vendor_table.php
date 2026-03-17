<?php
include("../db.php");
$sl= 1;
$sql = $con->query("select * from vendor order by id desc");
while($row = $sql->fetch_assoc()){
   ?>
   <tr>
      <td><?php echo $sl ?></td>
      <td><a href="#" class="text-primary"><?php echo $row['vid'] ?></a></td>
      <td><?php echo $row['company'] ?></td>
      <td><?php echo $row['address'] ?></td>
      <td><?php echo $row['location'] ?></td>
      <td><?php echo $row['gst'] ?></td>
      <td><?php if($row['gst'] == 0){echo"GST";}else{echo"IGST";} ?></td>
      <td><?php echo $row['person'] ?></td>
      <td><?php echo $row['cont_1'] ?></td>
      <td><?php echo $row['cont_2'] ?></td>
      <td><?php echo $row['email'] ?></td>
      <td class="lead_company">
         <a href="dashboard.php?src=vendor.php&id=<?php echo $row['id'] ?>&edit=" class="badge bg-success"><i class="fa fa-edit"></i></a>
         <a href="#" class="badge bg-danger" onclick="myFnc(<?php echo $row['id'] ?>,'vendor_dlt')"><i class="fa fa-trash"></i></a>
      </td>
   </tr>
   <?php
   $sl++;
}
?>