<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}
$currentDate = date("Y-m-d");
$givenDate = "2024-09-16";
if (strtotime($currentDate) > strtotime($givenDate)) {
  // optional logic
  $sql = $con->query("select * from admin where id='$aid'");
} else {
  $sql = $con->query("select * from admin where id='$aid'");
}

if($row = $sql->fetch_assoc()){
  $admin_image = $row["image"];
  $admin_name = $row["name"];
  $admin_cont = $row["contact"];
  $admin_email = $row["email"];
  $admin_pass = $row["password"];
}

// Dashboard statistics
$total_customers = $con->query("SELECT COUNT(*) as count FROM customer")->fetch_assoc()['count'];
$total_cas = $con->query("SELECT COUNT(*) as count FROM ca WHERE status='1'")->fetch_assoc()['count'];
$total_services = $con->query("SELECT COUNT(*) as count FROM service")->fetch_assoc()['count'];
$total_applications = $con->query("SELECT COUNT(*) as count FROM apply")->fetch_assoc()['count'];
$pending_applications = $con->query("SELECT COUNT(*) as count FROM apply WHERE status='0'")->fetch_assoc()['count'];
$completed_applications = $con->query("SELECT COUNT(*) as count FROM apply WHERE status='1'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - Legal Taxation</title>

  <!-- Google Font: Inter (modern) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style (AdminLTE) - kept for plugin compatibility, but heavily overridden -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

  <!-- Modern Dashboard Styles -->
  <style>
    * {
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: #f8fafc;
    }

    .wrapper {
      background-color: #f8fafc;
    }

    /* Override AdminLTE defaults */
    .main-header {
      background-color: #ffffff !important;
      border-bottom: 1px solid #e9ecef;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .content-wrapper {
      background-color: #f8fafc;
    }

    /* Page Header */
    .page-header {
      margin-bottom: 2rem;
    }
    .page-header h1 {
      font-weight: 600;
      font-size: 1.875rem;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
    }
    .page-header h1 i {
      color: #3b82f6;
      margin-right: 0.75rem;
      font-size: 2rem;
    }
    .page-header .breadcrumb {
      background: transparent;
      padding: 0;
      margin: 0;
      font-size: 0.9rem;
    }
    .page-header .breadcrumb a { color: #64748b; }
    .page-header .breadcrumb .active { color: #0f172a; font-weight: 500; }

    /* KPI Cards */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    @media (max-width: 992px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
      background: #ffffff;
      border-radius: 1.5rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
      border: 1px solid #f1f5f9;
      transition: transform 0.2s, box-shadow 0.2s;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
    }
    .kpi-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
      border-color: #e2e8f0;
    }
    .kpi-icon {
      width: 56px;
      height: 56px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .kpi-icon i { font-size: 1.75rem; }
    .kpi-content { flex: 1; }
    .kpi-label {
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      margin-bottom: 0.25rem;
      font-weight: 500;
    }
    .kpi-value {
      font-size: 2rem;
      font-weight: 700;
      color: #0f172a;
      line-height: 1.2;
      margin-bottom: 0.25rem;
    }
    .kpi-trend {
      font-size: 0.8rem;
      color: #10b981;
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    /* Cards */
    .modern-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      margin-bottom: 1.5rem;
      overflow: hidden;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .modern-card .card-header {
      background: transparent;
      border-bottom: 1px solid #f1f5f9;
      padding: 1.25rem 1.75rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .modern-card .card-header h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .modern-card .card-header h3 i { color: #3b82f6; }
    .modern-card .card-body { padding: 1.5rem 1.75rem; }
    .modern-card .card-footer {
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      padding: 1rem 1.75rem;
    }

    /* Direct chat overrides */
    .direct-chat-messages { background: #f8fafc; }
    .direct-chat-msg { margin-bottom: 10px; }
    .direct-chat-text {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 1rem !important;
      color: #334155;
    }
    .right .direct-chat-text { background: #3b82f6; color: #fff; border: none; }
    .direct-chat-img { border-radius: 12px; }

    /* To-do list */
    .todo-list > li {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 1rem;
      margin-bottom: 8px;
      padding: 0.75rem 1rem;
    }
    .todo-list > li .tools { color: #94a3b8; }

    /* Map card */
    #world-map { height: 250px; width: 100%; }

    /* Badges */
    .badge-modern {
      padding: 0.25rem 0.75rem;
      border-radius: 100px;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    /* Buttons */
    .btn-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.5rem 1.25rem;
      font-size: 0.85rem;
      font-weight: 500;
      color: #334155;
      transition: all 0.2s;
    }
    .btn-modern:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }
    .btn-modern-primary {
      background: #3b82f6;
      border-color: #3b82f6;
      color: #ffffff;
    }
    .btn-modern-primary:hover {
      background: #2563eb;
      border-color: #2563eb;
    }

    /* Footer */
    .main-footer {
      background: #ffffff;
      border-top: 1px solid #f1f5f9;
      color: #64748b;
      font-size: 0.85rem;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include("navbar.php"); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include("sidebar.php"); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6">
            <div class="page-header">
              <h1>
                <i class="fas fa-chart-pie"></i>
                Admin Dashboard
              </h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- KPI Cards (replacing small boxes) -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
              <i class="fas fa-users"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Total Customers</div>
              <div class="kpi-value"><?php echo $total_customers; ?></div>
              <div class="kpi-trend"><i class="fas fa-arrow-up"></i> registered</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #fef9c3; color: #a16207;">
              <i class="fas fa-user-tie"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Active CAs</div>
              <div class="kpi-value"><?php echo $total_cas; ?></div>
              <div class="kpi-trend"><i class="fas fa-check-circle"></i> verified</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #dcfce7; color: #166534;">
              <i class="fas fa-briefcase"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Services</div>
              <div class="kpi-value"><?php echo $total_services; ?></div>
              <div class="kpi-trend"><i class="fas fa-tag"></i> active</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon" style="background: #f1f5f9; color: #334155;">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="kpi-content">
              <div class="kpi-label">Applications</div>
              <div class="kpi-value"><?php echo $total_applications; ?></div>
              <div class="kpi-trend"><?php echo $pending_applications; ?> pending</div>
            </div>
          </div>
        </div>

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">

            <!-- Chart card (Sales) -->
            <div class="modern-card">
              <div class="card-header">
                <h3><i class="fas fa-chart-pie mr-1"></i> Sales Overview</h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content p-0">
                  <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                    <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
                  </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Direct Chat -->
            <div class="modern-card direct-chat direct-chat-primary">
              <div class="card-header">
                <h3><i class="fas fa-comments"></i> Direct Chat</h3>
                <div class="card-tools">
                  <span class="badge badge-primary">3</span>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                  <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle"><i class="fas fa-comments"></i></button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body">
                <!-- Conversations (unchanged) -->
                <div class="direct-chat-messages">
                  <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">Alexander Pierce</span>
                      <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                    </div>
                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                    <div class="direct-chat-text">Is this template really for free? That's unbelievable!</div>
                  </div>
                  <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-right">Sarah Bullock</span>
                      <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                    </div>
                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                    <div class="direct-chat-text">You better believe it!</div>
                  </div>
                  <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">Alexander Pierce</span>
                      <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                    </div>
                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                    <div class="direct-chat-text">Working with AdminLTE on a great new app! Wanna join?</div>
                  </div>
                  <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-right">Sarah Bullock</span>
                      <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                    </div>
                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                    <div class="direct-chat-text">I would love to.</div>
                  </div>
                </div>
                <!-- Contacts (unchanged) -->
                <div class="direct-chat-contacts">
                  <ul class="contacts-list">
                    <li><a href="#"><img class="contacts-list-img" src="dist/img/user1-128x128.jpg"><div class="contacts-list-info"><span class="contacts-list-name">Count Dracula <small class="contacts-list-date float-right">2/28/2015</small></span><span class="contacts-list-msg">How have you been? I was...</span></div></a></li>
                    <li><a href="#"><img class="contacts-list-img" src="dist/img/user7-128x128.jpg"><div class="contacts-list-info"><span class="contacts-list-name">Sarah Doe <small class="contacts-list-date float-right">2/23/2015</small></span><span class="contacts-list-msg">I will be waiting for...</span></div></a></li>
                    <li><a href="#"><img class="contacts-list-img" src="dist/img/user3-128x128.jpg"><div class="contacts-list-info"><span class="contacts-list-name">Nadia Jolie <small class="contacts-list-date float-right">2/20/2015</small></span><span class="contacts-list-msg">I'll call you back at...</span></div></a></li>
                    <li><a href="#"><img class="contacts-list-img" src="dist/img/user5-128x128.jpg"><div class="contacts-list-info"><span class="contacts-list-name">Nora S. Vans <small class="contacts-list-date float-right">2/10/2015</small></span><span class="contacts-list-msg">Where is your new...</span></div></a></li>
                    <li><a href="#"><img class="contacts-list-img" src="dist/img/user6-128x128.jpg"><div class="contacts-list-info"><span class="contacts-list-name">John K. <small class="contacts-list-date float-right">1/27/2015</small></span><span class="contacts-list-msg">Can I take a look at...</span></div></a></li>
                    <li><a href="#"><img class="contacts-list-img" src="dist/img/user8-128x128.jpg"><div class="contacts-list-info"><span class="contacts-list-name">Kenneth M. <small class="contacts-list-date float-right">1/4/2015</small></span><span class="contacts-list-msg">Never mind I found...</span></div></a></li>
                  </ul>
                </div>
              </div>
              <div class="card-footer">
                <form action="#" method="post">
                  <div class="input-group">
                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                      <button type="button" class="btn btn-modern-primary">Send</button>
                    </span>
                  </div>
                </form>
              </div>
            </div>

            <!-- To Do List -->
            <div class="modern-card">
              <div class="card-header">
                <h3><i class="ion ion-clipboard mr-1"></i> To Do List</h3>
                <div class="card-tools">
                  <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                  <li><span class="handle"><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span><div class="icheck-primary d-inline ml-2"><input type="checkbox" value="" name="todo1" id="todoCheck1"><label for="todoCheck1"></label></div><span class="text">Design a nice theme</span><small class="badge badge-danger"><i class="far fa-clock"></i> 2 mins</small><div class="tools"><i class="fas fa-edit"></i><i class="fas fa-trash-o"></i></div></li>
                  <li><span class="handle"><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span><div class="icheck-primary d-inline ml-2"><input type="checkbox" value="" name="todo2" id="todoCheck2" checked><label for="todoCheck2"></label></div><span class="text">Make the theme responsive</span><small class="badge badge-info"><i class="far fa-clock"></i> 4 hours</small><div class="tools"><i class="fas fa-edit"></i><i class="fas fa-trash-o"></i></div></li>
                  <li><span class="handle"><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span><div class="icheck-primary d-inline ml-2"><input type="checkbox" value="" name="todo3" id="todoCheck3"><label for="todoCheck3"></label></div><span class="text">Let theme shine like a star</span><small class="badge badge-warning"><i class="far fa-clock"></i> 1 day</small><div class="tools"><i class="fas fa-edit"></i><i class="fas fa-trash-o"></i></div></li>
                  <li><span class="handle"><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span><div class="icheck-primary d-inline ml-2"><input type="checkbox" value="" name="todo4" id="todoCheck4"><label for="todoCheck4"></label></div><span class="text">Let theme shine like a star</span><small class="badge badge-success"><i class="far fa-clock"></i> 3 days</small><div class="tools"><i class="fas fa-edit"></i><i class="fas fa-trash-o"></i></div></li>
                  <li><span class="handle"><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span><div class="icheck-primary d-inline ml-2"><input type="checkbox" value="" name="todo5" id="todoCheck5"><label for="todoCheck5"></label></div><span class="text">Check your messages</span><small class="badge badge-primary"><i class="far fa-clock"></i> 1 week</small><div class="tools"><i class="fas fa-edit"></i><i class="fas fa-trash-o"></i></div></li>
                </ul>
              </div>
              <div class="card-footer clearfix">
                <button type="button" class="btn btn-modern-primary float-right"><i class="fas fa-plus"></i> Add item</button>
              </div>
            </div>
          </section>

          <!-- Right col -->
          <section class="col-lg-5 connectedSortable">

            <!-- Map card (Visitors) -->
            <div class="modern-card bg-gradient-primary">
              <div class="card-header border-0">
                <h3 class="text-white"><i class="fas fa-map-marker-alt mr-1"></i> Visitors</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-primary btn-sm daterange" title="Date range"><i class="far fa-calendar-alt"></i></button>
                  <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
              </div>
              <div class="card-body">
                <?php include("map.php"); ?>
              </div>
              <div class="card-footer bg-transparent">
                <div class="row">
                  <div class="col-4 text-center"><div id="sparkline-1"></div><div class="text-white">Visitors</div></div>
                  <div class="col-4 text-center"><div id="sparkline-2"></div><div class="text-white">Online</div></div>
                  <div class="col-4 text-center"><div id="sparkline-3"></div><div class="text-white">Sales</div></div>
                </div>
              </div>
            </div>

            <!-- Sales Graph -->
            <div class="modern-card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="text-white"><i class="fas fa-th mr-1"></i> Sales Graph</h3>
                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <div class="card-footer bg-transparent">
                <div class="row">
                  <div class="col-4 text-center"><input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60" data-fgColor="#39CCCC"><div class="text-white">Mail-Orders</div></div>
                  <div class="col-4 text-center"><input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#39CCCC"><div class="text-white">Online</div></div>
                  <div class="col-4 text-center"><input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#39CCCC"><div class="text-white">In-Store</div></div>
                </div>
              </div>
            </div>

            <!-- Calendar -->
            <div class="modern-card bg-gradient-success">
              <div class="card-header border-0">
                <h3 class="text-white"><i class="far fa-calendar-alt"></i> Calendar</h3>
                <div class="card-tools">
                  <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52"><i class="fas fa-bars"></i></button>
                    <div class="dropdown-menu" role="menu">
                      <a href="#" class="dropdown-item">Add new event</a>
                      <a href="#" class="dropdown-item">Clear events</a>
                      <div class="dropdown-divider"></div>
                      <a href="#" class="dropdown-item">View calendar</a>
                    </div>
                  </div>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="remove"><i class="fas fa-times"></i></button>
                </div>
              </div>
              <div class="card-body pt-0">
                <div id="calendar" style="width: 100%"></div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block"><b>Version</b> 1.0.0</div>
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict -->
<script>$.widget.bridge('uibutton', $.ui.button)</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (this initializes the charts and maps) -->
<script src="dist/js/pages/dashboard.js"></script>
</body>
</html>