<?php
if(isset($_POST['submit'])){
   if($con->query("insert into leave_record(emp_id,type,f_date,f_type,t_date,t_type,days,reason,created) values('$eid','".$_POST['type']."','".$_POST['f_date']."','".$_POST['f_type']."','".$_POST['t_date']."','".$_POST['t_type']."','".$_POST['days']."','".$_POST['reason']."','$current_date')") === true){
      echo"<script>window.location='dashboard.php?src=leave.php';</script>";
   }else{
      echo"<script>alert('Server Error!!');window.location='dashboard.php?src=leave.php';</script>";
   }
}
?>
 <script>
        function callMe() {
            console.log("The badge was clicked!");
        }
    </script>
<div class="row">
	<div class="col-sm-12 mb-2">
      <nav aria-label="breadcrumb ">
         <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
               <a class="opacity-3 text-dark" href="dashboard.php">
               Dashboard
               </a>
            </li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">HRM</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Leave</li>
         </ol>
         <!-- <h6 class="font-weight-bolder mb-0">Branch</h6> -->
      </nav>
   </div>
   <div class="col-sm-7">
         <div class="card">
            <div class="card-body">
               <p class="text-sm mb-2 text-capitalize font-weight-bold">Leave Appication Details</p>
               <form method="post" enctype="multipart/form-data" class="row">
                  <div class="col-lg-3">
                     <select class="form-control" name="type" required>
                        <option value="">Leave Type</option>
                        <option value="0">CL</option>
                        <option value="3">EL</option>
                        <option value="1">SL</option>
                        <option value="2">LOP   </option>
                     </select>
                     
                  </div>
                  	<div class="col-lg-1">
   						<a href="#" class="badge bg-primary btn-sm" id="dateP" onclick="callMe()"><i class="fa fa-calendar"></i></a>
   					</div>
                  <div class="col-lg-3 mb-2">
                     <input type="text" name="f_date" id="from_date" class="form-control" placeholder="From Date" readonly required>
                  </div>
                  <div class="col-lg-1 mb-2">
                     <select class="form-control" name="f_type" onchange="daysCal()" id="s_leave">
                        <option value="0">FH</option>
                        <option value="1">SH</option>
                     </select>
                  </div>
                  <div class="col-lg-3 mb-2">
                     <input type="text" name="t_date" id="to_date" class="form-control" placeholder="To Date" readonly required>
                  </div>
                  <div class="col-lg-1 mb-2">
                     <select class="form-control" name="t_type" onchange="daysCal()" id="e_leave">
                        <option value="0">FH</option>
                        <option value="1" selected>SH</option>
                     </select>
                  </div>
                  <input type="hidden" id="f_d">
                  <input type="hidden" id="t_d">
                  <div class="col-lg-12 mb-2">
                     <textarea name="reason" class="form-control" placeholder="Reason" required></textarea>
                  </div>
                  <div class="col-lg-4 mb-2">
                     <label>Supportive Document (Optional)</label>
                     <input type="file" name="doc[]" class="form-control" multiple>
                  </div>
                  <div class="col-lg-4">
                     <label>Branch Manager</label>
                     <input type="text" name="" class="form-control" readonly>
                  </div>
                  <div class="col-lg-4">
                     <label>HR Manager</label>
                     <input type="text" name="" class="form-control" readonly>
                  </div>
                  <div class="col-lg-6 mb-2">
                     <input type="hidden" name="days" class="leave_msg">
                     <button class="btn btn-primary" name="submit">APPLY LEAVE</button>
                  </div>
                  <div class="col-lg-6 mb-2 text-end">
                     <small><b class='leave_msg'></b></small>
                  </div>
               </form>
            </div>   
         </div>
   </div>
</div>