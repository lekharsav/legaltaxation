<?php
include("../db.php");
?>
<table class="table table-striped example" >
   			<thead>
   				<tr>
   					<th>SL</th>
   					<th>Executive Name</th>
   					<th>Quotation Date</th>
   					<th>Quotation No.</th>
   					<th>Products</th>
   					<th>PO No.</th>
   					<th>PO Date</th>
                  <th>Lead Source</th>
   					<th>Refered By</th>
   					<th>Order Close</th>
                  
   					<th>Billing Amount</th>
   					<th>Outstanding & Collection</th>
   					<th>Job Work</th>
                  <th>TAX Invoice No.</th>
   					<th>Action</th>
   				</tr>
   			</thead>
   			<tbody>
   				<?php
               $id = $_GET['cid'];
   				$sl2 = 1;
   				$sql2 = $con->query("select * from quote where client='$id' order by id desc");
   				while($row2 = $sql2->fetch_assoc()){
   					?>
   					<tr>
   						<td><?php echo $sl2 ?></td>
   						<td>
                        <?php
                        if($row['uploaded_type'] == "admin"){
                           $sql3 = $con->query("select * from admin where id='".$row['uploaded_by']."'");
                           if($row3 = $sql3->fetch_assoc()){
                              echo $row3['name'];
                           }
                        }else{
                           $sql3 = $con->query("select * from employee where id='".$row['uploaded_by']."'");
                           if($row3 = $sql3->fetch_assoc()){
                              echo $row3['name'];
                           }
                        }
                        ?>
                     </td>
   						<td><?php echo $row2['created'] ?></td>
   						<td><a href="quote_details.php?qid=<?php echo $row2['quote_id'] ?>&cid=<?php echo $id ?>" target='blank'><?php echo $row2['quote_id'] ?></a></td>
   						<td>
   						    <?php
                         
                            $prod = explode(",",$row2['product']);
                            foreach($prod as $pid){
                               $sql33 = $con->query("select * from product where id='$pid'");
                               if($row33 = $sql33->fetch_assoc()){
                                  echo "<span class='badge bg-primary'>".$row33['name']."</span>&nbsp;";
                               }
                            }
                            
                            // echo $row2['product'];
                        ?>
   						</td>
   						<td><?php echo $row2['p_no'] ?></td>
   						<td><?php echo $row2['p_date'] ?></td>
                     <td><?php echo $row['source'] ?></td>
   						<td>
                        <?php
                        if($row['ref_by']>0){
                           $sql3 = $con->query("select * from refered_by where id='".$row['ref_by']."'");
                           if($row3 = $sql3->fetch_assoc()){
                              echo $row3['name'];
                           }
                        }else{
                           echo"Direct";
                        }
                        
                        ?>
                     </td>
   						<td>
   						    <a href="" class="badge bg-warning <?=$row2['quote_id']?>_order_close order_close_btn <?php if($row2['status']=="1"){echo"d-none";}?>" data-qid="<?php echo $row2['quote_id'] ?>" data-cid="<?php echo $row2['client'];?>" data-bs-toggle="modal" data-bs-target="#ordercloseModal">Pending</a>
   						    <a href="" class="badge bg-success <?=$row2['quote_id']?>_collection collection_update <?php if($row2['status']=="0"){echo"d-none";}?>" data-qid="<?php echo $row2['quote_id'] ?>" data-cid="<?php echo $row2['client'];?>" data-bs-toggle="modal" data-bs-target="#collectionupdateModal">Order Closed</a>
   						</td>
   						<td class="<?=$row2['quote_id']?>_bill">
                        <?php
                        $total_coll= 0;
                        $sql1 = $con->query("select * from client_order where qid='".$row2['quote_id']."'");
                        if($row1 = $sql1->fetch_assoc()){
                           $total_coll = $row1['total'];
                        }
                        echo "₹".$total_coll;
                        ?>            
                     </td>
   						<td>

                        <?php
                        $out = 0;
                        $coll = 0;
                        $sql11 = $con->query("select * from client_txn where qid='".$row2['quote_id']."'");
                        while($row11 = $sql11->fetch_assoc()){
                           $coll += $row11['amt'];
                        }
                        $out = $total_coll-$coll;
                        echo "<span class='text-danger ".$row2['quote_id']."_outs'>₹".$out."</span>";
                        ?>            
                     </td>
   						<td>
                        <?php
                        $sql3 = $con->query("select * from job_work where qid='".$row2['quote_id']."'");
                        if($row3 = $sql3->fetch_assoc()){
                           ?>
                           <a href="" data-bs-toggle="modal" data-bs-target="#jobworkModal" data-cid="<?=$_GET['id']?>" data-qid="<?=$row2['quote_id']?>" class="badge bg-success job_work_btn">Re-assign</a>
                           <?php
                        }else{
                           ?>
                           <a href="" data-bs-toggle="modal" data-bs-target="#jobworkModal" data-cid="<?=$_GET['id']?>" data-qid="<?=$row2['quote_id']?>" class="badge bg-warning job_work_btn">Pending</a>
                           <?php
                        }
                        ?>
                        
                        
                     </td>
   						<td>
                        <?php
                        $sql1 = $con->query("select * from invoice where qid='".$row2['quote_id']."'");
                        if($row1 = $sql1->fetch_assoc()){
                           ?>
                           <a href="invoice.php?cid=<?php echo $_GET['id']?>&qid=<?php echo $row2['quote_id']?>" target="blank"><?=$row1['inv_no']?></a>
                           <?php
                        }else{
                           if($row2['gst_type'] == "0"){
                              ?>
                              <a href="#" onclick="alert('GST Not Applied.');" class="badge bg-info">Generate</a>
                              <?php
                           }else{
                              if($out > 0 || $total_coll == 0){
                                 ?>
                                 <a href="#" onclick="alert('Please clear the outstanding amount.');" class="badge bg-info">Generate</a>
                                 <?php
                              }else{
                                 ?>
                                 <a href="invoice_generate.php?qid=<?=$row2['quote_id']?>&cid=<?=$row['cid']?>" class="badge bg-info">Generate</a>
                                 <?php
                              }
                           }
                           
                        }
                        
                        ?>
                                    
                     </td>
   						<td>
                        <a href="" data-link="https://aimdigitalise.com/Draft2024/portal/quote_user.php?qid=<?php echo $row2['quote_id'] ?>&cid=<?php echo $id ?>" class="copyLink"><i class="fa fa-copy"></i> Copy Pay Link </a>
   							<a href="dashboard.php?src=quote_edit.php&cid=<?php echo $_GET['id']?>&qid=<?php echo $row2['quote_id']?>" class="badge bg-success"><i class="fa fa-edit"></i></a>
   							<a href="dashboard.php?src=client_details.php&id=<?=$_GET['id']?>&qid=<?=$row2['quote_id']?>&delete=delete" class="badge bg-danger" onclick="return dlt();"><i class="fa fa-trash"></i></a>
   						</td>
   					</tr>
   					<?php
   					$sl2++;
   				}
   				?>
   			</tbody>
   		</table>