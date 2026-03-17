<?php
include("../db.php");
$aid = $_COOKIE["bm_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}
$sql = $con->query("select * from employee where id='$aid'");
if($row = $sql->fetch_assoc()){
  $admin_image = $row["image"];
  $admin_name = $row["name"];
  $admin_cont = $row["contact"];
  $admin_email = $row["email"];
  $admin_pass = $row["password"];
  $admin_login = $row["log_type"];
}
include("dashboard_function.php");
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="icon" type="image/png" href="https://aimdigitalise.com/images/logo.png">
      <title>Portal - AIM Digitalise </title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <link href="../assets/css/nucleo-icons.css" rel="stylesheet">
      <link href="../assets/css/nucleo-svg.css" rel="stylesheet">
      <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
      <link id="pagestyle" href="../assets/css/material-dashboard.min.css?v=3.0.6" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
      <style>
         a{
            color: #447eff;
         }
         .modal-header{
            padding: 3px !important;
         }
         .async-hide {
         opacity: 0 !important
         }
         .sidenav .navbar-brand {
             padding: 10px 5px !important;
         }
         .navbar-vertical .navbar-brand span {
             font-size: 17pt !important;
         }
         .navbar-vertical.navbar-expand-xs .navbar-nav .nav-link{
            padding: 5px 5px;
            font-size: 9pt;
         }
         .navbar-vertical.navbar-expand-xs .navbar-nav .nav-item .nav-link{
            color: #000 !important;
         }
         .navbar-vertical .navbar-nav>.nav-item .nav-link.active{
            background-color: hsl(339.61deg 82.19% 51.57%);
            color: #000 !important;
    
         }
         .navbar-vertical .navbar-nav>.nav-item .nav-link.active+.collapse .nav-item.active .nav-link.active, .navbar-vertical .navbar-nav>.nav-item .nav-link.active+.collapsing .nav-item.active .nav-link.active{
            background-image: none !important;
            color: #000 !important;
            background-color: #6ba0c1b8;
         }
         .form-control,.form-control:focus{
            box-shadow: 0px 0px 2px 0px grey;
            background-image: none !important;
            border-radius: 0;
            padding: 0px 5px;
            font-size: 9pt;
         }
         input[type="date"]:after{
             color:lightgray;
             content:attr(placeholder);
         }

         input[type="date"].full:after {
           color:black;
           content:""!important;
         }
         form label{
            margin: 0;
         }
         .text-blue{
            color: navy !important;
         }
         select.form-control{
            appearance: auto;
            padding: 4px 5px;
            font-size: 9pt;
/*            display: inline-block;*/
         }
         .card {
            box-shadow: 0px 0px 6px -1px rgba(0,0,0,.1), 0 0px 4px -1px rgba(0,0,0,.06);
         }
         td,th{
            white-space: nowrap;
            font-size: 9pt;
        }
        table.dataTable tbody th, table.dataTable tbody td{
         padding: 3px 3px;
        }
        form .btn{
         border-radius: 0;
         padding: 7px 20px;
        }
        .sm_txt{
         font-size: 8pt !important;
        } 
        .file_choose{
         display: inline-block;
         width: 100%;
         padding: 5px 10px;
         background: lightgrey;
         color: black;
         font-size: 9pt;
        }
        .msg_box{
         width: 25%;
         position: fixed;
         bottom: 40px;
         right: 40px;
        }
        form .col-lg-1,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-lg-10,.col-lg-11,.col-lg-12{
         padding: 0px 4px;
        }
        .dropdown_btn{
         padding: 2px 5px !important;
         font-size: 9pt;
        }
        div.dataTables_wrapper div.dataTables_filter input{
         font-size: 8pt;
         margin-bottom: 10px;
       }
       div.dataTables_wrapper div.dataTables_length select{
         font-size: 8pt;
         margin-bottom: 10px;
       }
       div.dataTables_wrapper div.dataTables_filter label{
         font-size: 8pt;
       }
       div.dataTables_wrapper div.dataTables_length label{
         font-size: 8pt;
       }
       .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active{
         font-size: 8pt;
       }
       .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover{
         padding: 5px 5px;
       }
       div.dataTables_wrapper div.dataTables_info{
         font-size: 8pt;
       }
       .gst_col .gst_label{
         font-size: 8pt;
       }.btn-sm{
         font-size: 8pt;
         padding: 5px 7px;
       }
       .modal td, .modal th{
        padding: 2px 2px !important;
        /*text-align: center;*/
       }
       .modal th{
        background: lightblue;
        /*color: #fff;*/
       }
       .bg-lite{
         background: #f7f7f7;
       }
       .prod_box{
         padding: 10px 15px;
         background: #f7f7f7;
         margin-bottom: 10px;
       }
       .indicate{
         border: 0px solid black;
         width: 230px;
         height: 40px;
         position: absolute;
         top: 0;
         left: 600px;
         text-align: center;
         padding: 6px;
         box-shadow: 0px 0px 6px 0px grey;
         border-radius: 0px 0px 5px 5px;
         background: white;
       }
       .indicate span{
         font-size: 10pt;
         color: navy;
         letter-spacing: 2px;
         text-transform: uppercase;
         font-weight: bold;
       }
       .indicate i{
         font-size: 10pt;
         color: green;
       }
      </style>
   </head>
   <body class="g-sidenav-show  bg-gray-200">
      
      <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-white" id="sidenav-main">
         <div class="sidenav-header bg-gradient-white">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 bg-white text-center" href="dashboard.php" target="_blank">
            <!-- <img src="https://aimdigitalise.com/images/logo.png" class="navbar-brand-img h-100" alt="main_logo"> -->
            <span class="ms-1 font-weight-bold text-dark"><i class="fa fa-cloud"></i> Cloud Portal</span>
            </a>
         </div>
         <!-- <hr class="horizontal light mt-0 mb-2"> -->
         <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
               <li class="nav-item <?php if(!$_GET['src']){echo"active";}?>">
                  <a class="nav-link <?php if(!$_GET['src']){echo"active";}?>" href="dashboard.php">
                  <i class="material-icons-round fa fa-dashboard"></i>
                  <span class="nav-link-text ms-2 ps-1">Dashboard</span>
                  </a>
               </li>
               <!--
               <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link text-white active" aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                  <i class="fa fa-dashboard opacity-10"></i>
                  <span class="nav-link-text ms-2 ps-1">Dashboards</span>
                  </a>
                  <div class="collapse  show " id="dashboardsExamples">
                     <ul class="nav ">
                        <li class="nav-item active">
                           <a class="nav-link text-white " href="sales.html">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Sales </span>
                           </a>
                        </li>
                        <li class="nav-item ">
                           <a class="nav-link text-white " href="automotive.html">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Development </span>
                           </a>
                        </li>
                        <li class="nav-item ">
                           <a class="nav-link text-white " href="smart-home.html">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Payroll </span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>
            -->
               <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#entry_menu" class="nav-link text-white <?php if(@$_GET['src']=="employee.php" || @$_GET['src']=="branch.php" || @$_GET['src']=="department.php" || @$_GET['src']=="product.php" || @$_GET['src']=="vendor.php" || @$_GET['src']=="client.php" || @$_GET['src']=="refered_by.php"){echo"active";}?>" aria-controls="entry_menu" role="button" aria-expanded="false">
                  <i class="fa fa-keyboard opacity-10"></i>
                  <span class="nav-link-text ms-2 ps-1">Entry</span>
                  </a>
                  <div class="collapse <?php if(@$_GET['src']=="employee.php" || @$_GET['src']=="branch.php" || @$_GET['src']=="department.php" || @$_GET['src']=="product.php" || @$_GET['src']=="vendor.php" || @$_GET['src']=="client.php" || @$_GET['src']=="refered_by.php"){echo"show";}?> " id="entry_menu">
                     <ul class="nav ">
                        <li class="nav-item <?php if(@$_GET['src']=="employee.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="employee.php"){echo"active";}?>" href="dashboard.php?src=employee.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> New Employee </span>
                           </a>
                        </li>
                        <li class="nav-item <?php if(@$_GET['src']=="refered_by.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="refered_by.php"){echo"active";}?>" href="dashboard.php?src=refered_by.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1">Refered By</span>
                           </a>
                        </li>
                        <li class="nav-item <?php if(@$_GET['src']=="client.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="client.php"){echo"active";}?>" href="dashboard.php?src=client.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Client Entry </span>
                           </a>
                        </li>
                        
                     </ul>
                  </div>
               </li>
               <li class="nav-item <?php if(@$_GET['src'] == "client_all.php" || @$_GET['src'] == "client_details.php" || @$_GET['src'] == "quote_edit.php"){echo"active";}?>">
                  <a class="nav-link <?php if(@$_GET['src'] == "client_all.php" || @$_GET['src'] == "client_details.php" || @$_GET['src'] == "quote_edit.php"){echo"active";}?>" href="dashboard.php?src=client_all.php">
                  <i class="material-icons-round fa fa-users"></i>
                  <span class="nav-link-text ms-2 ps-1">CRM</span>
                  </a>
               </li>
               <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#hrm_menu" class="nav-link text-white <?php if(@$_GET['src'] == "employee_list.php" || @$_GET['src']=="attendance.php"){echo"active";}?>" aria-controls="hrm_menu" role="button" aria-expanded="false">
                  <i class="fa fa-female opacity-10"></i>
                  <span class="nav-link-text ms-2 ps-1">HRM</span>
                  </a>
                  <div class="collapse <?php if(@$_GET['src'] == "employee_list.php" || @$_GET['src']=="attendance.php"){echo"show";}?> " id="hrm_menu">
                     <ul class="nav ">
                        <li class="nav-item <?php if(@$_GET['src']=="employee_list.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="employee_list.php"){echo"active";}?>" href="dashboard.php?src=employee_list.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Employee List </span>
                           </a>
                        </li>
                        <li class="nav-item <?php if(@$_GET['src']=="leave.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="leave.php"){echo"active";}?>" href="dashboard.php?src=leave_apply.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Apply Leave </span>
                           </a>
                        </li>
                        <li class="nav-item <?php if(@$_GET['src']=="leave.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="leave.php"){echo"active";}?>" href="dashboard.php?src=leave.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Leave Master </span>
                           </a>
                        </li>
                        
                        <li class="nav-item <?php if(@$_GET['src']=="attendance.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="attendance.php"){echo"active";}?>" href="dashboard.php?src=attendance.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Attendance </span>
                           </a>
                        </li>

                        <li class="nav-item <?php if(@$_GET['src']=="attendance.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="attendance.php"){echo"active";}?>" href="dashboard.php?src=employee_report.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Employee Report </span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>
               <li class="nav-item <?php if(@$_GET['src'] == "pdm.php"){echo"active";}?>">
                  <a class="nav-link <?php if(@$_GET['src'] == "pdm.php"){echo"active";}?>" href="dashboard.php">
                  <i class="material-icons-round fa fa-code"></i>
                  <span class="nav-link-text ms-2 ps-1">PDM</span>
                  </a>
               </li>
               <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#purchase_management" class="nav-link text-white <?php if(@$_GET['src']=="stock_entry.php" || @$_GET['src']=="stock_txn.php"){echo"active";}?>" aria-controls="purchase_management" role="button" aria-expanded="false">
                  <i class="fa fa-inr opacity-10"></i>
                  <span class="nav-link-text ms-2 ps-1">Stock Management</span>
                  </a>
                  <div class="collapse <?php if(@$_GET['src']=="stock_entry.php" || @$_GET['src']=="stock_txn.php"){echo"show";}?> " id="purchase_management">
                     <ul class="nav ">
                        <li class="nav-item <?php if(@$_GET['src']=="stock_entry.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="stock_entry.php"){echo"active";}?>" href="dashboard.php?src=stock_entry.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Stock Entry </span>
                           </a>
                        </li>
                        <li class="nav-item <?php if(@$_GET['src']=="stock_txn.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="stock_txn.php"){echo"active";}?>" href="dashboard.php?src=stock_txn.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Stock Transaction </span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>
               <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#accounts_report" class="nav-link text-white <?php if(@$_GET['src']=="stock_entry.php" || @$_GET['src']=="stock_txn.php"){echo"active";}?>" aria-controls="accounts_report" role="button" aria-expanded="false">
                  <i class="fa fa-file opacity-10"></i>
                  <span class="nav-link-text ms-2 ps-1">Accounts & Report</span>
                  </a>
                  <div class="collapse <?php if(@$_GET['src']=="stock_entry.php" || @$_GET['src']=="stock_txn.php"){echo"show";}?> " id="accounts_report">
                     <ul class="nav ">
                        <li class="nav-item <?php if(@$_GET['src']=="stock_entry.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="stock_entry.php"){echo"active";}?>" href="dashboard.php?src=stock_entry.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Account Statement </span>
                           </a>
                        </li>
                        <li class="nav-item <?php if(@$_GET['src']=="stock_txn.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="stock_txn.php"){echo"active";}?>" href="dashboard.php?src=stock_txn.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Balance Sheet </span>
                           </a>
                        </li>
                        <li class="nav-item <?php if(@$_GET['src']=="stock_txn.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="stock_txn.php"){echo"active";}?>" href="dashboard.php?src=stock_txn.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> MIS Report </span>
                           </a>
                        </li>
                        <li class="nav-item <?php if(@$_GET['src']=="stock_txn.php"){echo"active";}?>">
                           <a class="nav-link text-white <?php if(@$_GET['src']=="stock_txn.php"){echo"active";}?>" href="dashboard.php?src=stock_txn.php">
                           <span class="sidenav-mini-icon"> <i class="fa fa-dot-circle-o"></i> </span>
                           <span class="sidenav-normal  ms-2  ps-1"> Import to Tally </span>
                           </a>
                        </li>
                     </ul>
                  </div>
               </li>
               <li class="nav-item acti">
                  <a class="nav-link acti" href="logout.php">
                  <i class="material-icons-round fa fa-power-off"></i>
                  <span class="nav-link-text ms-2 ps-1">Logout</span>
                  </a>
               </li>
            </ul>
         </div>
      </aside>
      <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
         <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
               
               <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
                  <a href="javascript:;" class="nav-link text-body p-0">
                     <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                     </div>
                  </a>
               </div>
               <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                  <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                     <div class="input-group input-group-outline">
                        <label class="form-label">Search here</label>
                        <input type="text" class="form-control">
                     </div>
                  </div>
                  <ul class="navbar-nav  justify-content-end">
                     <li class="nav-item dropdown pe-2">
                        <a href="javascript:;" class="nav-link text-body p-0 position-relative" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class=" fa fa-user">
                        
                        </i>
                        Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2 me-sm-n4" aria-labelledby="dropdownMenuButton">
                           <li class="mb-2">
                              <a class="dropdown-item border-radius-md" href="javascript:;">
                                 <div class="d-flex align-items-center py-1">
                                    <span class="material-icons">email</span>
                                    <div class="ms-2">
                                       <h6 class="text-sm font-weight-normal my-auto">
                                          Check new messages
                                       </h6>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li class="mb-2">
                              <a class="dropdown-item border-radius-md" href="javascript:;">
                                 <div class="d-flex align-items-center py-1">
                                    <span class="material-icons">podcasts</span>
                                    <div class="ms-2">
                                       <h6 class="text-sm font-weight-normal my-auto">
                                          Manage podcast session
                                       </h6>
                                    </div>
                                 </div>
                              </a>
                           </li>
                           <li>
                              <a class="dropdown-item border-radius-md" href="javascript:;">
                                 <div class="d-flex align-items-center py-1">
                                    <span class="material-icons">shopping_cart</span>
                                    <div class="ms-2">
                                       <h6 class="text-sm font-weight-normal my-auto">
                                          Payment successfully completed
                                       </h6>
                                    </div>
                                 </div>
                              </a>
                           </li>
                        </ul>
                     </li>
                  </ul>
               </div>
            </div>
         </nav>
         <?php
         if(isset($_COOKIE['msg'])){
            ?>
         <div class="alert alert-success alert-dismissible text-white fade show msg_box" role="alert">
           <span class="alert-text"><strong>Hey!</strong> <?php echo $_COOKIE['msg'] ?></span>
           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
         </div>
            <?php
         }
         ?>
         
         <?php
         $src = @$_GET['src'];
         if(isset($src)){
         ?>
         <div class="container-fluid bg-white py-4">
            <?php include($src); ?>
         </div>
         <?php
         }else{
         ?>
         <div class="container-fluid py-4">
            <div class="row">
               
               <div class="col-sm-4">
                  <div class="card">
                     <div class="card-body p-3 position-relative">
                        <div class="row">
                           <div class="col-7 text-start">
                              <p class="text-sm mb-1 text-capitalize font-weight-bold">Collection</p>
                              <h5 class="font-weight-bolder mb-0">
                                 <?php
                                 $amt = 0;
                                 $qry = $con->query("select * from client_txn");
                                 while($rws = $qry->fetch_assoc()){
                                    if(date('m-Y',strtotime($current_date)) == date('m-Y',strtotime($rws['p_date']))){
                                       $amt += $rws['margin'];
                                    }
                                 }
                                 echo "₹".$amt;
                                 ?>
                                 
                              </h5>
                              <a href="#" class="badge bg-primary text-white">View Info <i class="fa fa-exclamation-circle"></i></a>
                           </div>
                           <div class="col-5">
                              <div class="dropdown text-end">
                                 <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers1" data-bs-toggle="dropdown" aria-expanded="false">
                                 <span class="text-xs text-secondary"><?php echo date('F',strtotime($current_date)) ?></span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-4 mt-sm-0 mt-4">
                  <div class="card">
                     <div class="card-body p-3 position-relative">
                        <div class="row">
                           <div class="col-7 text-start">
                              <p class="text-sm mb-1 text-capitalize font-weight-bold">Today's Followup</p>
                              <h5 class="font-weight-bolder mb-0">
                                 <?php
                                 $t_f = 0;
                                 $qry = $con->query("select * from client");
                                 while($qry->fetch_assoc()){
                                    $t_f++;
                                 }
                                 echo $t_f;
                                 ?>
                              </h5>
                              <a href="dashboard.php?src=client_all.php&tf" class="badge bg-primary text-white">View Info <i class="fa fa-exclamation-circle"></i></a>
                           </div>
                           <div class="col-5">
                              <div class="dropdown text-end">
                                 <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers1" data-bs-toggle="dropdown" aria-expanded="false">
                                 <span class="text-xs text-secondary"><?php echo date('D',strtotime($current_date)) ?></span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-4 mt-sm-0 mt-4">
                  <div class="card">
                     <div class="card-body p-3 position-relative">
                        <div class="row">
                           <div class="col-7 text-start">
                              <p class="text-sm mb-1 text-capitalize font-weight-bold">Outstanding</p>
                              <h5 class="font-weight-bolder mb-0">
                                 ₹<?php echo $outs_amt ?>
                              </h5>
                              <a href="dashboard.php?src=client_all.php&tf" class="badge bg-primary text-white">View Info <i class="fa fa-exclamation-circle"></i></a>
                           </div>
                           <div class="col-5">
                              <div class="dropdown text-end">
                                 <a href="javascript:;" class="cursor-pointer text-secondary" id="dropdownUsers3" data-bs-toggle="dropdown" aria-expanded="false">
                                 <span class="text-xs text-secondary">Total</span>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row mt-4">
               <div class="col-lg-4 col-sm-6">
                  <div class="card h-100">
                     <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                           <h6 class="mb-0">Lead Generation</h6>
                           <?php echo date('F',strtotime($current_date)) ?>
                        </div>
                     </div>
                     <div class="card-body pb-0 p-3 mt-4">
                        <div class="row">
                           <div class="col-7 text-start">
                              <div class="chart">
                                 <canvas id="chart-pie" class="chart-canvas" height="200"></canvas>
                              </div>
                           </div>
                           <div class="col-5 my-auto">
                              <span class="badge badge-md badge-dot me-4 d-block text-start">
                              <i class="bg-primary"></i>
                              <span class="text-dark text-xs">Reference (<?php echo $ref_leads ?>)</span>
                              </span>
                              <span class="badge badge-md badge-dot me-4 d-block text-start">
                              <i class="bg-info"></i>
                              <span class="text-dark text-xs">Facebook (<?php echo $fb_leads ?>)</span>
                              </span>
                              <span class="badge badge-md badge-dot me-4 d-block text-start">
                              <i class="bg-primary"></i>
                              <span class="text-dark text-xs">Google (<?php echo $google_leads ?>)</span>
                              </span>
                              <span class="badge badge-md badge-dot me-4 d-block text-start">
                              <i class="bg-info"></i>
                              <span class="text-dark text-xs">LinkedIn (<?php echo $linkedin_leads ?>)</span>
                              </span>
                              <span class="badge badge-md badge-dot me-4 d-block text-start">
                              <i class="bg-primary"></i>
                              <span class="text-dark text-xs">Google Ads (<?php echo $google_ads_leads ?>)</span>
                              </span>
                              <span class="badge badge-md badge-dot me-4 d-block text-start">
                              <i class="bg-warning"></i>
                              <span class="text-dark text-xs">Office Database (<?php echo $office_db_leads ?>)</span>
                              </span>
                              <span class="badge badge-md badge-dot me-4 d-block text-start">
                              <i class="bg-dark"></i>
                              <span class="text-dark text-xs">Cold Calling (<?php echo $cold_call_leads ?>)</span>
                              </span>
                           </div>
                        </div>
                     </div>
                     <div class="card-footer pt-0 pb-0 p-3 d-flex align-items-center">
                        <hr>
                        <p>Total Leads Generated: <b><?php echo $total_leads_gen ?></b></p>
                     </div>
                  </div>
               </div>
               <div class="col-lg-8 col-sm-6 mt-sm-0 mt-4">
                  <div class="card">
                     <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                           <h6 class="mb-0">Order Close Analytics Of <?php echo date('Y',strtotime($current_date)) ?></h6>
                        </div>
                        <div class="d-flex align-items-center">
                           <span class="badge badge-md badge-dot me-4">
                           <i class="bg-primary"></i>
                           <span class="text-dark text-xs">Lead Generation</span>
                           </span>
                           <span class="badge badge-md badge-dot me-4">
                           <i class="bg-dark"></i>
                           <span class="text-dark text-xs">Order Closed</span>
                           </span>
                        </div>
                     </div>
                     <div class="card-body p-3">
                        <div class="chart">
                           <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row mt-4">
               <div class="col-lg-8">
                  <div class="card h-100">
                     <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                           <h6 class="mb-0">Sales by Employee</h6>
                        </div>
                     </div>
                     <div class="card-body p-3">
                        <div class="chart">
                           <canvas id="chart-bar" class="chart-canvas" height="340"></canvas>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4 mt-lg-0 mt-4">
                  <div class="card">
                     <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                           <h6 class="mb-0">Sales by Branch</h6>
                        </div>
                     </div>
                     <div class="card-body p-3">
                        <ul class="list-group list-group-flush list my--3">
                           <li class="list-group-item px-0 border-0">
                              <div class="row align-items-center">
                                 <div class="col">
                                    <p class="text-xs font-weight-bold mb-0">Branch:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">Kolkata</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">₹2500</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Outstanding:</p>
                                    <h6 class="text-sm font-weight-normal mb-0 text-danger">₹29.9</h6>
                                 </div>
                              </div>
                              <hr class="horizontal dark mt-3 mb-1">
                           </li>
                           <li class="list-group-item px-0 border-0">
                              <div class="row align-items-center">
                                 <div class="col">
                                    <p class="text-xs font-weight-bold mb-0">Branch:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">Siliguri</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">₹3.900</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Outstanding:</p>
                                    <h6 class="text-sm font-weight-normal mb-0 text-danger">₹40.22</h6>
                                 </div>
                              </div>
                              <hr class="horizontal dark mt-3 mb-1">
                           </li>
                           <li class="list-group-item px-0 border-0">
                              <div class="row align-items-center">
                                 <div class="col">
                                    <p class="text-xs font-weight-bold mb-0">Branch:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">Bangalore</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">₹1.400</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Outstanding:</p>
                                    <h6 class="text-sm font-weight-normal mb-0 text-danger">₹23.44</h6>
                                 </div>
                              </div>
                              <hr class="horizontal dark mt-3 mb-1">
                           </li>
                           <li class="list-group-item px-0 border-0">
                              <div class="row align-items-center">
                                 <div class="col">
                                    <p class="text-xs font-weight-bold mb-0">Branch:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">Hydrabad</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">₹562</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Outstanding:</p>
                                    <h6 class="text-sm font-weight-normal mb-0 text-danger">₹32.14</h6>
                                 </div>
                              </div>
                              <hr class="horizontal dark mt-3 mb-1">
                           </li>
                           <li class="list-group-item px-0 border-0">
                              <div class="row align-items-center">
                                 <div class="col">
                                    <p class="text-xs font-weight-bold mb-0">Branch:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">Mumbai</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                    <h6 class="text-sm font-weight-normal mb-0">₹400</h6>
                                 </div>
                                 <div class="col text-center">
                                    <p class="text-xs font-weight-bold mb-0">Outstanding:</p>
                                    <h6 class="text-sm font-weight-normal mb-0 text-danger">₹56.83</h6>
                                 </div>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row mt-4">
               <div class="col-12">
                  <div class="card mb-4">
                     <div class="card-header pb-0">
                        <h6>Top Selling Products</h6>
                     </div>
                     <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                           <table class="table align-items-center mb-0">
                              <thead>
                                 <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Value</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ads Spent</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Refunds</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td>
                                       <div class="d-flex px-3 py-1">
                                          <div>
                                             <img src="../../../creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/blue-shoe.jpg" class="avatar me-3" alt="image">
                                          </div>
                                          <div class="d-flex flex-column justify-content-center">
                                             <h6 class="mb-0 text-sm">Website Design</h6>
                                             <p class="text-sm font-weight-normal text-secondary mb-0"><span class="text-success">8.232</span> orders</p>
                                          </div>
                                       </div>
                                    </td>
                                    <td>
                                       <p class="text-sm font-weight-normal mb-0">₹130.992</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                       <p class="text-sm font-weight-normal mb-0">₹9.500</p>
                                    </td>
                                    <td class="align-middle text-end">
                                       <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                          <p class="text-sm font-weight-normal mb-0">13</p>
                                          <i class="ni ni-bold-down text-sm ms-1 text-success"></i>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <div class="d-flex px-3 py-1">
                                          <div>
                                             <img src="../../../creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/black-mug.jpg" class="avatar me-3" alt="image">
                                          </div>
                                          <div class="d-flex flex-column justify-content-center">
                                             <h6 class="mb-0 text-sm">Facebook Marketing</h6>
                                             <p class="text-sm font-weight-normal text-secondary mb-0"><span class="text-success">12.821</span> orders</p>
                                          </div>
                                       </div>
                                    </td>
                                    <td>
                                       <p class="text-sm font-weight-normal mb-0">₹80.250</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                       <p class="text-sm font-weight-normal mb-0">₹4.200</p>
                                    </td>
                                    <td class="align-middle text-end">
                                       <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                          <p class="text-sm font-weight-normal mb-0">40</p>
                                          <i class="ni ni-bold-down text-sm ms-1 text-success"></i>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <div class="d-flex px-3 py-1">
                                          <div>
                                             <img src="../../../creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/black-chair.jpg" class="avatar me-3" alt="image">
                                          </div>
                                          <div class="d-flex flex-column justify-content-center">
                                             <h6 class="mb-0 text-sm">Google Ads</h6>
                                             <p class="text-sm font-weight-normal text-secondary mb-0"><span class="text-success">2.421</span> orders</p>
                                          </div>
                                       </div>
                                    </td>
                                    <td>
                                       <p class="text-sm font-weight-normal mb-0">₹40.600</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                       <p class="text-sm font-weight-normal mb-0">₹9.430</p>
                                    </td>
                                    <td class="align-middle text-end">
                                       <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                          <p class="text-sm font-weight-normal mb-0">54</p>
                                          <i class="ni ni-bold-up text-sm ms-1 text-danger"></i>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <div class="d-flex px-3 py-1">
                                          <div>
                                             <img src="../../../creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/bang-sound.jpg" class="avatar me-3" alt="image">
                                          </div>
                                          <div class="d-flex flex-column justify-content-center">
                                             <h6 class="mb-0 text-sm">SEO</h6>
                                             <p class="text-sm font-weight-normal text-secondary mb-0"><span class="text-success">5.921</span> orders</p>
                                          </div>
                                       </div>
                                    </td>
                                    <td>
                                       <p class="text-sm font-weight-normal mb-0">₹91.300</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                       <p class="text-sm font-weight-normal mb-0">₹7.364</p>
                                    </td>
                                    <td class="align-middle text-end">
                                       <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                          <p class="text-sm font-weight-normal mb-0">5</p>
                                          <i class="ni ni-bold-down text-sm ms-1 text-success"></i>
                                       </div>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>
                                       <div class="d-flex px-3 py-1">
                                          <div>
                                             <img src="../../../creativetimofficial/public-assets/master/soft-ui-design-system/assets/img/ecommerce/photo-tools.jpg" class="avatar me-3" alt="image">
                                          </div>
                                          <div class="d-flex flex-column justify-content-center">
                                             <h6 class="mb-0 text-sm">Software</h6>
                                             <p class="text-sm font-weight-normal text-secondary mb-0"><span class="text-success">921</span> orders</p>
                                          </div>
                                       </div>
                                    </td>
                                    <td>
                                       <p class="text-sm font-weight-normal mb-0">₹140.925</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                       <p class="text-sm font-weight-normal mb-0">₹20.531</p>
                                    </td>
                                    <td class="align-middle text-end">
                                       <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                          <p class="text-sm font-weight-normal mb-0">121</p>
                                          <i class="ni ni-bold-up text-sm ms-1 text-danger"></i>
                                       </div>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php
         }
         ?>
         <!-- Followup Modal -->
         <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <form method="post" action="ajax/ajax_followup_update.php" id="ajax_followup_form">
                <div class="modal-body" >
                  <div id="client_details"></div>
                  <button class="btn btn-sm btn-primary" id="save_followup" name="followup_update">Update Details</button>
                </div>
              </form>
              </div>
            </div>
          </div>
         <!-- End -->
         <div class="container-fluid">
            <footer class="footer py-4  ">
               <div class="container-fluid">
                  <div class="row align-items-center justify-content-lg-between">
                     <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                           © Copyright 2024.
                           Powered By 
                           <a href="https://www.aimdigitalise.com" class="font-weight-bold" target="_blank">AIM Digitalise.</a>
                        </div>
                     </div>
                  </div>
               </div>
            </footer>
         </div>
      </main>
      <div class="indicate">
         <i class="fa fa-dot-circle-o"></i>
         <span>Branch Manager</span>
      </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.js"></script>
      <script src="../assets/js/core/popper.min.js"></script>
      <script src="../assets/js/core/bootstrap.min.js"></script>
      <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
      <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
      <script src="../assets/js/plugins/dragula/dragula.min.js"></script>
      <script src="../assets/js/plugins/jkanban/jkanban.js"></script>
      <script src="../assets/js/plugins/chartjs.min.js"></script>
      <script src="https://cdn.ckeditor.com/4.16.1/full/ckeditor.js"></script>




      <!-- Add Product Item -->
      <script type="text/javascript">
      $(document).ready(function(){
       $('.item_list').click(function(){
       	// alert('ok');
         // $('.main_row').remove();
         var random = 10000 + Math.floor(Math.random() * 99999);
         var id = $(this).data('id');
         var item_name = $(this).data("item");
         var hsn = $(this).data("hsn");
         var per = $(this).data("per");
         var des = $(this).data("des");
         var sprice = $(this).data("sprice");
         var dis = $(this).data("dis");
         var dprice = $(this).data("dprice");
         $('#item_append').append('<div class="bg-lite prod_box" id="'+random+'"><div class="row"><div class="col-lg-4 mb-2"><label>Product Name</label><input type="text" value="'+item_name+'" name="name[]" class="form-control" required></div><div class="col-lg-1 mb-2"><label>HSN</label><input type="text" value="'+hsn+'" name="hsn[]" class="form-control" required></div><div class="col-lg-1 mb-2"><label>Qty</label><input type="text" value="1" name="qty[]" onkeypress="return isNumber(event);" class="form-control" required></div><div class="col-lg-1 mb-2"><label>Per</label><input type="text" value="'+per+'" name="per[]" class="form-control" required></div><div class="col-lg-2 mb-2"><label>Selling Price</label><input type="text" value="'+sprice+'" name="s_price[]" onkeypress="return isNumber(event);" class="form-control" required></div><div class="col-lg-1 mb-2"><label>Dis(%)</label><input type="text" value="'+dis+'" name="dis[]" onkeypress="return isNumber(event);" class="form-control" required></div><div class="col-lg-1 mb-2"><label>D. Price</label><input type="text" value="'+dprice+'" name="d_price[]" class="form-control" readonly required></div><div class="col-lg-1"><a href="#" class="btn btn-danger mt-3 remove_sec" onclick="removeAppend('+random+');"><i class="fa fa-trash"></i></a></div><div class="col-lg-12"><textarea name="des[]" class="form-control" >'+des+'</textarea></div></div><input type="hidden" value="'+id+'" name="pid[]"></div>');
         });
      });
      </script>
      <script type="text/javascript">
      	function removeAppend(ids){
	      	// alert(ids);
	      	$('#'+ids).remove();
	      }
      </script>
      <!-- End -->

      <!-- Discounted Price Calculation -->
      <script type="text/javascript">
         $('.selling_price').keyup(function(){
            var s_price = $(this).val();
            var dis = $(this).data('dis');
            var id = $(this).data("val");
            var discount = $('#'+dis).val();
            var result = s_price-((s_price*discount)/100);
            $('#'+id).val(result);
         });

         $('.discounted_price').keyup(function(){
            var discount = $(this).val();
            var s = $(this).data('sel');
            var id = $(this).data("val");
            var s_price = $('#'+s).val();
            var result = s_price-((s_price*discount)/100);
            $('#'+id).val(result);
         });
      </script>
      <!-- End -->

      <!-- Quotation GST Check -->
      <script type="text/javascript">
         $('.quote_gst').change(function(){
            var val = $(this).val();
            if(val == 0){
               $('.gst_no').val("");
               $('.gst_no').attr('readonly',true);
            }else{
               $('.gst_no').val("");
               $('.gst_no').attr('readonly',false);
            }
            
         });
      </script>
      <!-- End -->

      <script type="text/javascript">
      
      $('.remove_sec').click(function(){
         var id = $(this).data("id");
         $('#'+id).remove();
      });
      </script>

      <!-- Anexture Show Hide -->
      <script type="text/javascript">
      	$('.if_anex').change(function(){
      		var val = $(this).val();
      		if(val == 1){
      			$('.anex').removeClass("d-none");
      		}else{
      			$('.anex').addClass("d-none");
      		}
      	});
      </script>
      <!-- End -->

      <script type="text/javascript">
         $(document).ready(function(){
            $('#dis').keyup(function(){
               var dis = $(this).val();
               var s_price = $('#s_price').val();
               var price = s_price-((s_price*dis)/100);
               $('#d_price').val(price);
            });
         });
      </script>
      <script type="text/javascript">
         $('#same_as').change(function(){
            if ($(this).not(':checked').length) {
               $('#p_adrs1').val("");
               $('#p_adrs2').val("");
               $('#p_dist').val("");
               $('#p_state').val("");
               $('#p_pin').val("");
            }else{
               $('#p_adrs1').val($('#adrs1').val());
               $('#p_adrs2').val($('#adrs2').val());
               $('#p_dist').val($('#dist').val());
               $('#p_state').val($('#state').val());
               $('#p_pin').val($('#pin').val());
            }
         });
         
      </script>
      <script>
         $(document).ready( function () {
             $('.example').DataTable({
               // "scrollX": true
               "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]]
             });
         } );
      </script>
      <script type="text/javascript">
        function isNumber(e){
            e = e || window.event;
            var charCode = e.which ? e.which : e.keyCode;
            return /\d/.test(String.fromCharCode(charCode));
        }
      </script>
      <script type="text/javascript">
         function dlt(){
            var t = confirm("Are you sure?");
            return t;
         }
      </script>
      <script type="text/javascript">
        function paymentMode(){
            var val = document.getElementById("p_mode").value;
            // alert(val);
            if(val == 1){
              $("#tnx_sec").removeClass('d-none');
            }else{
              $('#tnx_sec').addClass('d-none');
            }
        }
         $('.payment_mode').change(function(){
            var val = $(this).val();
            // alert(val);
            if(val == 1){
               $("#tnx_sec").removeClass('d-none');
            }else{
               $('#tnx_sec').addClass('d-none');
            }
         });
      </script>
      <script>
        function outstandingCal(){
            var total_amt = $('#total_amt').val();
            var rcv_amt = $('#rcv_amt').val();
            $('#out_amt').val(total_amt-rcv_amt);
        }
        function outstandingCal2(){
            var total_amt = $('#tot_out_amt').val();
            var rcv_amt = $('#rcv_amt').val();
            $('#out_amt').val(total_amt-rcv_amt);
        }
      </script>
      <script type="text/javascript">
            var csgt = 0;
            var sgst = 0;
            var igst = 0;
         $('#stock_rate').keyup(function(){
            var qty = $('#stock_qty').val();
            var type = $('.vendor_sec').children('option:selected').data('type');
            var rate = $(this).val();
            
            var t = rate*qty;
            var cgst = (t*9)/100;
            var sgst = (t*9)/100;
            var igst = (t*18)/100;
            var total = t+igst;
            
            $('#stock_total_rate').val(total);
            // alert(type);
            if(type == "0"){
               $('#stock_cgst').val(cgst);
               $('#stock_sgst').val(sgst);
               $('#stock_igst').val("0");
            }else{
               $('#stock_cgst').val("0");
               $('#stock_sgst').val("0");
               $('#stock_igst').val(igst);
            }
         });
      </script>
      <script type="text/javascript">
        function projectBrief(brief){
          $('#projectBrief').html(brief);
        }
      </script>
      <script type="text/javascript">
         $('#p_amt').keyup(function(){
            var total = $('#stock_total_rate').val();
            var amt = $(this).val();
            $('#pen_amt').val(total-amt);
         });
      </script>
      <script type="text/javascript">
         $(".select_product").click(function(){
            var len = $('.dash_drop_btn').find('input[type="checkbox"]:checked').length;
            $('.dash_drop_btn button').html("Products ("+len+")");
         });
      </script>
      <script type="text/javascript">
	   CKEDITOR.replace( 'anex' );
	  </script>
      <?php
      include('../ajax_script.php');
      ?>
      <script>
         var ctx1 = document.getElementById("chart-line").getContext("2d");
         var ctx2 = document.getElementById("chart-pie").getContext("2d");
         var ctx3 = document.getElementById("chart-bar").getContext("2d");
         
         // Line chart
         new Chart(ctx1, {
           type: "line",
           data: {
             labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
             datasets: [{
                 label: "Facebook Ads",
                 tension: 0,
                 pointRadius: 5,
                 pointBackgroundColor: "#e91e63",
                 pointBorderColor: "transparent",
                 borderColor: "#e91e63",
                 borderWidth: 4,
                 backgroundColor: "transparent",
                 fill: true,
                 data: [50, 100, 200, 190, 400, 350, 500, 450, 700],
                 maxBarThickness: 6
               },
               {
                 label: "Google Ads",
                 tension: 0,
                 borderWidth: 0,
                 pointRadius: 5,
                 pointBackgroundColor: "#3A416F",
                 pointBorderColor: "transparent",
                 borderColor: "#3A416F",
                 borderWidth: 4,
                 backgroundColor: "transparent",
                 fill: true,
                 data: [10, 30, 40, 120, 150, 220, 280, 250, 280],
                 maxBarThickness: 6
               }
             ],
           },
           options: {
             responsive: true,
             maintainAspectRatio: false,
             plugins: {
               legend: {
                 display: false,
               }
             },
             interaction: {
               intersect: false,
               mode: 'index',
             },
             scales: {
               y: {
                 grid: {
                   drawBorder: false,
                   display: true,
                   drawOnChartArea: true,
                   drawTicks: false,
                   borderDash: [5, 5],
                   color: '#c1c4ce5c'
                 },
                 ticks: {
                   display: true,
                   padding: 10,
                   color: '#9ca2b7',
                   font: {
                     size: 14,
                     weight: 300,
                     family: "Roboto",
                     style: 'normal',
                     lineHeight: 2
                   },
                 }
               },
               x: {
                 grid: {
                   drawBorder: false,
                   display: true,
                   drawOnChartArea: true,
                   drawTicks: true,
                   borderDash: [5, 5],
                   color: '#c1c4ce5c'
                 },
                 ticks: {
                   display: true,
                   color: '#9ca2b7',
                   padding: 10,
                   font: {
                     size: 14,
                     weight: 300,
                     family: "Roboto",
                     style: 'normal',
                     lineHeight: 2
                   },
                 }
               },
             },
           },
         });
         
         
         // Pie chart
         new Chart(ctx2, {
           type: "pie",
           data: {
             labels: ['Referance', 'Facebook', 'Google', 'Linkedin','Google Ads','Office Database','Cold Calling'],
             datasets: [{
               label: "Lead Source",
               weight: 9,
               cutout: 0,
               tension: 0.9,
               pointRadius: 2,
               borderWidth: 1,
               backgroundColor: ['#17c1e8', '#e91e63', '#3A416F', '#a8b8d8', '#a8b8d8', '#a8b8d8', '#a8b8d8'],
               data: [<?=$ref_leads?>, <?=$fb_leads?>, <?=$google_leads?>, <?=$linkedin_leads?>,<?=$google_ads_leads?>,<?=$office_db_leads?>,<?=$cold_call_leads?>],
               fill: false
             }],
           },
           options: {
             responsive: true,
             maintainAspectRatio: false,
             plugins: {
               legend: {
                 display: false,
               }
             },
             interaction: {
               intersect: false,
               mode: 'index',
             },
             scales: {
               y: {
                 grid: {
                   drawBorder: false,
                   display: false,
                   drawOnChartArea: false,
                   drawTicks: false,
                   color: '#c1c4ce5c'
                 },
                 ticks: {
                   display: false
                 }
               },
               x: {
                 grid: {
                   drawBorder: false,
                   display: false,
                   drawOnChartArea: false,
                   drawTicks: false,
                   color: '#c1c4ce5c'
                 },
                 ticks: {
                   display: false,
                 }
               },
             },
           },
         });
         
         // Bar chart
         new Chart(ctx3, {
           type: "bar",
           data: {
             labels: ['16-20', '21-25', '26-30', '31-36', '36-42', '42-50', '50+'],
             datasets: [{
               label: "Sales by age",
               weight: 5,
               borderWidth: 0,
               borderRadius: 4,
               backgroundColor: '#3A416F',
               data: [15, 20, 12, 60, 20, 15, 25],
               fill: false
             }],
           },
           options: {
             indexAxis: 'y',
             responsive: true,
             maintainAspectRatio: false,
             plugins: {
               legend: {
                 display: false,
               }
             },
             scales: {
               y: {
                 grid: {
                   drawBorder: false,
                   display: true,
                   drawOnChartArea: true,
                   drawTicks: false,
                   borderDash: [5, 5],
                   color: '#c1c4ce5c'
                 },
                 ticks: {
                   display: true,
                   padding: 10,
                   color: '#c1c4ce5c',
                   font: {
                     size: 14,
                     weight: 300,
                     family: "Roboto",
                     style: 'normal',
                     lineHeight: 2
                   },
                 }
               },
               x: {
                 grid: {
                   drawBorder: false,
                   display: false,
                   drawOnChartArea: true,
                   drawTicks: true,
                   color: '#9ca2b7'
                 },
                 ticks: {
                   display: true,
                   color: '#9ca2b7',
                   padding: 10,
                   font: {
                     size: 14,
                     weight: 300,
                     family: "Roboto",
                     style: 'normal',
                     lineHeight: 2
                   },
                 }
               },
             },
           },
         });
      </script>
      <script>
         var win = navigator.platform.indexOf('Win') > -1;
         if (win && document.querySelector('#sidenav-scrollbar')) {
           var options = {
             damping: '0.5'
           }
           Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
         }
      </script>
      <script src="../assets/js/material-dashboard.min.js?v=3.0.6"></script>
   </body>
</html>