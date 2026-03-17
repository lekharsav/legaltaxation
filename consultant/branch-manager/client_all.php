<div class="row">
   <div class="col-sm-12 mb-2">
      <nav aria-label="breadcrumb ">
         <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
               <a class="opacity-3 text-dark" href="dashboard.php">
               Dashboard
               </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Entry</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Client</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-lg-12 ">
      <a href="dashboard.php?src=client_all.php" class="btn btn-sm btn-outline-secondary <?php if(!isset($_GET['status']) && !isset($_GET['tf']) && !isset($_GET['pf'])){echo"active";}?>">All</a>
      <a href="dashboard.php?src=client_all.php&tf" class="btn btn-sm btn-outline-secondary <?php if(isset($_GET['tf'])){echo"active";}?>">Today's Followup</a>
      <a href="dashboard.php?src=client_all.php&pf" class="btn btn-sm btn-outline-secondary <?php if(isset($_GET['pf'])){echo"active";}?>">Pending Followup</a>
      <a href="dashboard.php?src=client_all.php&status=0" class="btn btn-sm btn-outline-secondary <?php if(@$_GET['status'] == "0"){echo"active";}?>">Attended</a>
      <a href="dashboard.php?src=client_all.php&status=3" class="btn btn-sm btn-outline-secondary <?php if(@$_GET['status'] == "3"){echo"active";}?>">Quotation Sent</a>
      <a href="dashboard.php?src=client_all.php&status=2" class="btn btn-sm btn-outline-secondary <?php if(@$_GET['status'] == "2"){echo"active";}?>">Pursuing to Purchase</a>
      <a href="dashboard.php?src=client_all.php&status=1" class="btn btn-sm btn-outline-secondary <?php if(@$_GET['status'] == "1"){echo"active";}?>">Not Interested</a>
      <a href="dashboard.php?src=client_all.php" class="btn btn-sm btn-outline-secondary">Order Close</a>
      <a href="dashboard.php?src=client_all.php" class="btn btn-sm btn-outline-secondary">Outstanding</a>
      <a href="dashboard.php?src=client_all.php" class="btn btn-sm btn-outline-secondary">TAX Invoice</a>
      
      <a href="dashboard.php?src=client_all.php" class="btn btn-sm btn-outline-secondary">Next Renewal</a>
      <div class="table-responsive">
         <table class="table table-striped example">
            <thead>
               <tr>
                  <th>SL</th>
                  <th>Entry</th>
                  <th>Executive Name</th>
                  <th>Client ID</th>
                  <th>Branch</th>
                  <th>Company</th>
                  <th>Address</th>
                  <th>District</th>
                  <th>State</th>
                  <th>Country</th>
                  <th>GST</th>
                  <th>Person</th>
                  <th>Contact</th>
                  <th>Alt Contact</th>
                  <th>Email</th>
                  <th>Lead Source</th>
                  <th>Refered By</th>
                  <th>Products</th>
                  <th>Next Followup Date</th>
                  <th>Followup</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $sl = 1;
               $today = date('Y-m-d', strtotime($current_date));
               if(isset($_GET['tf'])){
                  $sql  =$con->query("select * from client where branch='$emp_branch' and next_followup='$today' and status != '1' order by id desc");
               }if(isset($_GET['pf'])){
                  $sql  =$con->query("select * from client where branch='$emp_branch' and next_followup !='' and next_followup < '$today' and status !='1' order by id desc");
               }if(isset($_GET['status'])){
                  $sql  =$con->query("select * from client where branch='$emp_branch' and status='".$_GET['status']."' order by id desc");
               }else{
                  $sql  =$con->query("select * from client where branch='$emp_branch' order by id desc");
               }
               
               
               while($row = $sql->fetch_assoc()){
                  $sql2 = $con->query("select * from branch where id='".$row['branch']."'");
                  if($row2 = $sql2->fetch_assoc()){
                     $branch_name = $row2['name'];
                  }
                  if($row['uploaded_type'] == "admin"){
                     $sql2 = $con->query("select * from admin where id='".$row['uploaded_by']."'");
                     if($row2 = $sql2->fetch_assoc()){
                        $uploaded_by = $row2['name'];
                     }
                  }
                  if($row['ref_by'] == 0){
                     $ref_by = "None";
                  }else{
                     $sql2 = $con->query("select * from refered_by where id='".$row['ref_by']."'");
                     if($row2 = $sql2->fetch_assoc()){
                        $ref_by= $row2['name'];
                     }
                  }
                  
                  ?>
                  <tr>
                     <td><?php echo $sl ?></td>
                     <td><?php echo date('d-F-Y h:i A',strtotime($current_date)) ?></td>
                     <td><?php echo $uploaded_by ?></td>
                     <td><a href="dashboard.php?src=client_details.php&id=<?php echo $row['id'] ?>"><?php echo $row['cid'] ?></a></td>
                     <td><?php echo $branch_name ?></td>
                     <td><?php echo $row['company'] ?></td>
                     <td><?php echo $row['address'] ?></td>
                     <td><?php echo $row['district'] ?></td>
                     <td><?php echo $row['state'] ?></td>
                     <td><?php echo $row['country'] ?></td>
                     <td><?php echo $row['gst'] ?></td>
                     <td><?php echo $row['person'] ?></td>
                     <td><?php echo $row['contact'] ?></td>
                     <td><?php echo $row['alt_contact'] ?></td>
                     <td><?php echo $row['email'] ?></td>
                     <td><?php echo $row['source'] ?></td>
                     <td><?php echo $ref_by ?></td>
                     <td>
                        <?php
                        $prod = explode(",",$row['product']);
                        foreach($prod as $pid){
                           $sql2 = $con->query("select * from product where id='$pid'");
                           if($row2 = $sql2->fetch_assoc()){
                              echo "<span class='badge bg-primary'>".$row2['name']."</span>&nbsp;";
                           }
                        }
                        ?>
                     </td>
                     <td><?php echo $row['next_followup'] ?></td>
                     <td>
                        <a href="" class="btn btn-sm btn-secondary client_followup" data-id="<?php echo $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">Followup</a>
                     </td>
                     <td>
                        <a href="dashboard.php?src=client_edit.php&id=<?php echo $row['id'] ?>" class="badge bg-success"><i class="fa fa-edit"></i></a>
                        <a href="dashboard.php?src=client.php" class="badge bg-danger" onclick="return dlt();"><i class="fa fa-trash"></i></a>
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
