<?php
session_start();
include("../db.php");

// Check admin login
if (!isset($_COOKIE["tax_admin_log"])) {
    echo "<script>window.location='index.php';</script>";
    exit;
}

// Initialize message variables
$success_msg = '';
$error_msg = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($stmt = $con->prepare("DELETE FROM cate WHERE id = ?")) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Category deleted successfully.";
        } else {
            $_SESSION['error'] = "Error deleting category.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Database error.";
    }
    header("Location: catagory.php");
    exit;
}

// Fetch category for editing if edit parameter is set
$edit_id = 0;
$edit_name = '';
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    if ($stmt = $con->prepare("SELECT name FROM cate WHERE id = ?")) {
        $stmt->bind_param('i', $edit_id);
        $stmt->execute();
        $stmt->bind_result($edit_name);
        if ($stmt->fetch()) {
            // found
        } else {
            $edit_id = 0; // invalid id
        }
        $stmt->close();
    }
}

// Handle Add / Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if (empty($name)) {
        $error_msg = "Category name is required.";
    } else {
        if ($id > 0) {
            // Update
            if ($stmt = $con->prepare("UPDATE cate SET name = ? WHERE id = ?")) {
                $stmt->bind_param('si', $name, $id);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Category updated successfully.";
                } else {
                    $_SESSION['error'] = "Error updating category.";
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "Database error.";
            }
        } else {
            // Insert
            if ($stmt = $con->prepare("INSERT INTO cate (name) VALUES (?)")) {
                $stmt->bind_param('s', $name);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Category added successfully.";
                } else {
                    $_SESSION['error'] = "Error adding category.";
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "Database error.";
            }
        }
        // Redirect to avoid form resubmission
        header("Location: catagory.php");
        exit;
    }
}

// Fetch all categories
$categories = [];
$result = $con->query("SELECT id, name FROM cate ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Retrieve session messages
if (isset($_SESSION['success'])) {
    $success_msg = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $error_msg = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Legal Taxation - Category Management</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include("navbar.php"); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include("sidebar.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
              <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="fa fa-tags"></i>
              </span>
              Category Management
            </h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Messages -->
        <?php if ($success_msg): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($success_msg); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error_msg); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <!-- Add/Edit Category Card -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas <?php echo $edit_id ? 'fa-edit' : 'fa-plus-circle'; ?> mr-2"></i>
                  <?php echo $edit_id ? 'Edit Category' : 'Add New Category'; ?>
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="POST" action="">
                  <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                  <div class="row">
                    <div class="col-md-8">
                      <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               placeholder="e.g., TAXATION & ACCOUNTING"
                               value="<?php echo htmlspecialchars($edit_name); ?>" 
                               required>
                      </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                      <div class="form-group w-100">
                        <button type="submit" name="submit" class="btn btn-primary">
                          <i class="fas <?php echo $edit_id ? 'fa-save' : 'fa-plus'; ?> mr-2"></i>
                          <?php echo $edit_id ? 'Update Category' : 'Add Category'; ?>
                        </button>
                        <?php if ($edit_id): ?>
                          <a href="catagory.php" class="btn btn-default ml-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                          </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Categories List Card -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-list mr-2"></i>
                  All Categories
                </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php if (count($categories) > 0): ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Category Name</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                          <td><?php echo $cat['id']; ?></td>
                          <td><?php echo htmlspecialchars($cat['name']); ?></td>
                          <td>
                            <a href="catagory.php?edit=<?php echo $cat['id']; ?>" class="btn btn-sm btn-info" title="Edit">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a href="catagory.php?delete=<?php echo $cat['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               title="Delete"
                               onclick="return confirm('Are you sure you want to delete this category?');">
                              <i class="fas fa-trash-alt"></i>
                            </a>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php else: ?>
                  <p class="text-muted text-center py-4">No categories found. Add one above!</p>
                <?php endif; ?>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
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
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- Optional: auto-dismiss alerts after 3 seconds -->
<script>
  setTimeout(function() {
    document.querySelectorAll('.alert').forEach(function(alert) {
      $(alert).alert('close');
    });
  }, 3000);
</script>
</body>
</html>