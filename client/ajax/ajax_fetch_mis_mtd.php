<?php
include("../db.php");
$branch = $_GET['branch'];
?>
<table class="table table-bordered table-striped" id="data_1">
   <thead>
      <tr>
         <th>Product</th>
         <th>LY Quantity</th>
         <th>CY Quantity</th>
         <th>LY Revenue (₹)</th>
         <th>CY Revenue (₹)</th>
      </tr>
   </thead>
   <tbody>
      <?php
      $cy = date('m-Y',strtotime($current_date));
      $ly = date('m-Y',strtotime($current_date))-1;
      $total_cy_amount = 0;
      $total_cy_qty = 0;
      $total_ly_amount = 0;
      $total_ly_qty = 0;
      $sql = $con->query("select * from product order by name asc");
      while($row = $sql->fetch_assoc()){
         $product_name = $row['name'];
         $cy_amount = 0;
         $cy_qty = 0;
         $ly_amount = 0;
         $ly_qty = 0;
         $sql2 = $con->query("select * from client_order where branch='$branch'");
         while($row2 = $sql2->fetch_assoc()){
            $pid = explode(",",$row2['product']);
            if(in_array($row['id'],$pid) && $cy == date('m-Y',strtotime($row2['created']))){
               // echo $row['name']." :- ".$row2['total']."<br>";
               $sql3 = $con->query("select * from quote_item where qid='".$row2['qid']."' and pid='".$row['id']."'");
               while($row3 = $sql3->fetch_assoc()){
                  $cy_qty += $row3['qty'];
                  $cy_amount += $row3['price']-($row3['price']*$row3['dis'])/100;
               }
            }
            if(in_array($row['id'],$pid) && $ly == date('m-Y',strtotime($row2['created']))){
               // echo $row['name']." :- ".$row2['total']."<br>";
               $sql3 = $con->query("select * from quote_item where qid='".$row2['qid']."' and pid='".$row['id']."'");
               while($row3 = $sql3->fetch_assoc()){
                  $ly_qty += $row3['qty'];
                  $ly_amount += $row3['price']-($row3['price']*$row3['dis'])/100;
               }
            }
            $total_cy_amount += $cy_amount;
            $total_cy_qty += $cy_qty;
            $total_ly_amount += $ly_amount;
            $total_ly_qty += $ly_qty;
         }
         ?>
         <tr>
            <th><?php echo $product_name ?></th>
            <td><?php echo $ly_qty ?></td>
            <td><?php echo $cy_qty ?></td>
            <td><?php echo $ly_amount ?></td>
            <td><?php echo $cy_amount ?></td>
         </tr>
         <?php
      }
      ?>
      <tr>
         <th>Total</th>
         <td><?php echo $total_ly_qty; ?></td>
         <td><?php echo $total_cy_qty ?></td>
         <td><?php echo $total_ly_amount ?></td>
         <td><?php echo $total_cy_amount ?></td>
      </tr>
   </tbody>
</table>