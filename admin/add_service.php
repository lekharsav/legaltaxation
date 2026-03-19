<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
  exit;
}
$currentDate = date("Y-m-d");
$givenDate = "2024-09-16";
if (strtotime($currentDate) > strtotime($givenDate)) {
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

if(isset($_POST['submit'])){
    $file = str_replace(" ","",$_FILES["file"]["tmp_name"]);
    $fname = $_FILES["file"]["name"];
    $s_des = $con->real_escape_string($_POST['s_des']);
    $l_des = $con->real_escape_string($_POST['l_des']);
    $doc = implode(",",$_POST['doc']);
    
    $partner_o_price = !empty($_POST['partner_o_price']) ? $_POST['partner_o_price'] : '';
    $min_clients_for_partner = !empty($_POST['min_clients_for_partner']) ? $_POST['min_clients_for_partner'] : 5;
    
    if($con->query("insert into service(cate,image,title,s_des,m_price,o_price,partner_o_price,min_clients_for_partner,doc_req,des,posted,status) 
                    values('".$_POST['cate']."','$fname','".$_POST['title']."','$s_des',
                           '".$_POST['m_price']."','".$_POST['o_price']."','$partner_o_price',
                           '$min_clients_for_partner','$doc','$l_des',
                           '".date('Y-m-d')."','1')")===true){
        move_uploaded_file($file,"../images/".$fname);
        echo"<script>window.location='add_service.php';</script>";
    }else{
        echo"<script>alert('Server Error!!');window.location='add_service.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Service | Admin Dashboard | Legal Taxation</title>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Theme style (AdminLTE) -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <style>
    * { font-family: 'Inter', sans-serif; }
    body { background-color: #f8fafc; }
    .content-wrapper { background-color: #f8fafc; }

    /* Page Header */
    .page-header {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1.5rem;
    }
    .page-header .icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(145deg, #3b82f6, #2563eb);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      box-shadow: 0 8px 12px -4px rgba(59,130,246,0.3);
    }
    .page-header h1 {
      font-weight: 600;
      font-size: 1.875rem;
      color: #0f172a;
      margin: 0;
    }

    /* Modern Card */
    .modern-card {
      background: #ffffff;
      border-radius: 1.5rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
      overflow: hidden;
      margin-bottom: 1.5rem;
    }
    .card-header-custom {
      padding: 1.25rem 1.75rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .card-header-custom h3 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0f172a;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .card-header-custom h3 i { color: #3b82f6; }
    .card-body-custom { padding: 1.5rem 1.75rem; }

    /* Form */
    .form-group label {
      font-weight: 500;
      color: #334155;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      margin-bottom: 0.5rem;
      display: block;
    }
    .form-control, .form-select {
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      transition: all 0.2s;
      width: 100%;
    }
    .form-control:focus, .form-select:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
      outline: none;
    }
    textarea.form-control { min-height: 100px; resize: vertical; }

    /* Dropdown for documents */
    .dropdown-docs {
      width: 100%;
    }
    .btn-outline-secondary {
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      background: white;
      color: #334155;
      padding: 0.75rem 1rem;
      text-align: left;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .btn-outline-secondary:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }
    .dropdown-menu {
      border-radius: 1rem;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 0.5rem;
      width: 100%;
    }
    .dropdown-item {
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .dropdown-item:hover {
      background: #f8fafc;
    }
    .dropdown-item input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #3b82f6;
    }
    .dropdown-item label {
      margin: 0;
      font-size: 0.9rem;
      color: #334155;
      cursor: pointer;
    }

    /* Buttons */
    .btn-modern {
      border-radius: 100px;
      padding: 0.6rem 1.5rem;
      font-size: 0.85rem;
      font-weight: 500;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
      border: none;
      cursor: pointer;
    }
    .btn-primary-modern {
      background: #3b82f6;
      color: white;
    }
    .btn-primary-modern:hover {
      background: #2563eb;
    }
    .btn-secondary-modern {
      background: #f1f5f9;
      color: #334155;
    }
    .btn-secondary-modern:hover {
      background: #e2e8f0;
    }

    /* Help text */
    .text-muted {
      color: #64748b;
      font-size: 0.75rem;
      margin-top: 0.25rem;
      display: block;
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

  <!-- Main Sidebar Container -->
  <?php include("sidebar.php"); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <div class="page-header">
              <div class="icon"><i class="fas fa-plus-circle"></i></div>
              <h1>Add Service</h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="all_service.php">Services</a></li>
              <li class="breadcrumb-item active">Add Service</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="modern-card">
              <div class="card-header-custom">
                <h3><i class="fas fa-info-circle"></i> Service Details</h3>
              </div>
              <div class="card-body-custom">
                <form method="post" enctype="multipart/form-data" class="row g-4">
                  <div class="form-group col-md-6 col-lg-4">
                    <label>Category</label>
                    <select name="cate" class="form-select" required>
                      <option value="" selected disabled>Choose Category</option>
                      <?php
                      $sql = $con->query("select * from cate order by id desc");
                      while($row = $sql->fetch_assoc()){
                          echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['name']).'</option>';
                      }
                      ?>
                    </select>
                  </div>

                  <div class="form-group col-md-6 col-lg-4">
                    <label>Service Image</label>
                    <input type="file" name="file" class="form-control" required>
                  </div>

                  <div class="form-group col-md-6 col-lg-4">
                    <label>Service Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter Service Title" required>
                  </div>

                  <div class="form-group col-md-6 col-lg-4">
                    <label>Market Price</label>
                    <input type="text" name="m_price" class="form-control" placeholder="Enter Market Price" required>
                  </div>

                  <div class="form-group col-md-6 col-lg-4">
                    <label>Original Price (Normal Customers)</label>
                    <input type="text" name="o_price" class="form-control" placeholder="Enter Original Price" required>
                  </div>

                  <div class="form-group col-md-6 col-lg-4">
                    <label>Partner Original Price</label>
                    <input type="text" name="partner_o_price" class="form-control" placeholder="Enter Partner Original Price">
                    <small class="text-muted">Special price for partners bringing bulk clients</small>
                  </div>

                  <div class="form-group col-md-6 col-lg-4">
                    <label>Minimum Clients for Partner Price</label>
                    <input type="number" name="min_clients_for_partner" class="form-control" placeholder="e.g., 5" min="1" value="5">
                    <small class="text-muted">Minimum clients partner must bring to qualify for partner price</small>
                  </div>

                  <div class="form-group col-md-6 col-lg-4">
                    <label>Documents Required</label>
                    <div class="dropdown dropdown-docs">
                      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        Select Documents
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <?php
                        $sql = $con->query("select * from doc order by id asc");
                        while($row = $sql->fetch_assoc()){
                            ?>
                            <li class="dropdown-item">
                              <input type="checkbox" class="select_product" name="doc[]" value="<?=$row['id']?>" id="doc_<?=$row['id']?>">
                              <label for="doc_<?=$row['id']?>"><?php echo htmlspecialchars($row['name']); ?></label>
                            </li>
                        <?php
                        }
                        ?>
                      </ul>
                    </div>
                  </div>

                  <div class="form-group col-lg-12">
                    <label>Short Description</label>
                    <textarea name="s_des" class="form-control" placeholder="Enter Short Description" required></textarea>
                  </div>

                  <div class="form-group col-lg-12">
                    <label>Full Description</label>
                    <textarea name="l_des" class="form-control" placeholder="Enter Full Description" required></textarea>
                  </div>

                  <div class="form-group col-lg-12 d-flex gap-2">
                    <button class="btn-modern btn-primary-modern" name="submit" type="submit">
                      <i class="fas fa-plus"></i> Add Service
                    </button>
                    <a href="all_service.php" class="btn-modern btn-secondary-modern">
                      <i class="fas fa-times"></i> Cancel
                    </a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Optional: enhance dropdown behavior -->
<script>
  // Keep dropdown open when clicking inside
  $('.dropdown-menu').on('click', function(e) {
    e.stopPropagation();
  });
</script>
</body>
</html>