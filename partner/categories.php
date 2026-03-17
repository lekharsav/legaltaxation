<?php
session_start();
include("../db.php");

// Check if user is logged in
if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../partner-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];

// Get CA details
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
if($sql->num_rows == 0){
    session_destroy();
    header("Location: ../partner-login.php");
    exit();
}
$ca = $sql->fetch_assoc();

$ca_name = $ca["name"];
$ca_image = $ca["image"];
$ca_id_number = $ca["ca_id"];

// Handle category actions
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // Check if category has items
    $check_items = $con->query("SELECT COUNT(*) as count FROM items WHERE item_category IN (SELECT category_name FROM item_categories WHERE id='$delete_id')");
    $item_count = $check_items->fetch_assoc()['count'];
    
    if($item_count > 0) {
        $_SESSION['error'] = "Cannot delete category! $item_count items are associated with this category.";
    } else {
        $con->query("DELETE FROM item_categories WHERE id='$delete_id'");
        $_SESSION['success'] = "Category deleted successfully!";
    }
    header("Location: categories.php");
    exit();
}

if(isset($_GET['toggle_status'])) {
    $cat_id = $_GET['toggle_status'];
    $result = $con->query("SELECT status FROM item_categories WHERE id='$cat_id'");
    if($result->num_rows > 0) {
        $category = $result->fetch_assoc();
        $new_status = $category['status'] == 'active' ? 'inactive' : 'active';
        $con->query("UPDATE item_categories SET status='$new_status' WHERE id='$cat_id'");
        $_SESSION['success'] = "Category status updated!";
    }
    header("Location: categories.php");
    exit();
}

// Handle form submission for adding/editing category
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = trim($con->real_escape_string($_POST['category_name']));
    $description = $con->real_escape_string($_POST['description']);
    
    if(empty($category_name)) {
        $_SESSION['error'] = "Category name is required!";
    } else {
        // Check if category already exists
        $check_sql = "SELECT id FROM item_categories WHERE LOWER(category_name) = LOWER('$category_name')";
        if(isset($_POST['category_id'])) {
            $category_id = $_POST['category_id'];
            $check_sql .= " AND id != '$category_id'";
        }
        
        $check_result = $con->query($check_sql);
        if($check_result->num_rows > 0) {
            $_SESSION['error'] = "Category '$category_name' already exists!";
        } else {
            if(isset($_POST['category_id'])) {
                // Update category
                $sql = "UPDATE item_categories SET 
                        category_name = '$category_name',
                        description = '$description'
                        WHERE id = '{$_POST['category_id']}'";
                
                if($con->query($sql)) {
                    $_SESSION['success'] = "Category updated successfully!";
                } else {
                    $_SESSION['error'] = "Error updating category: " . $con->error;
                }
            } else {
                // Insert new category
                $sql = "INSERT INTO item_categories (category_name, description, created_by) 
                        VALUES ('$category_name', '$description', '$ca_id')";
                
                if($con->query($sql)) {
                    $_SESSION['success'] = "Category added successfully!";
                } else {
                    $_SESSION['error'] = "Error adding category: " . $con->error;
                }
            }
        }
    }
    header("Location: categories.php");
    exit();
}

// Get all categories
$categories = [];
$sql = $con->query("SELECT * FROM item_categories ORDER BY category_name ASC");
if($sql->num_rows > 0) {
    $categories = $sql->fetch_all(MYSQLI_ASSOC);
}

// Get category for editing
$edit_category = null;
if(isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $result = $con->query("SELECT * FROM item_categories WHERE id='$edit_id'");
    if($result->num_rows > 0) {
        $edit_category = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Categories - Legal Taxation</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="items.php" class="nav-link">Items</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="categories.php" class="nav-link active">Categories</a>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php include('sidebar.php'); ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Manage Item Categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="items.php">Items</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php if(isset($_SESSION['success'])): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
          </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
          </div>
        <?php endif; ?>

        <div class="row">
          <div class="col-md-4">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?></h3>
              </div>
              <form method="POST" action="">
                <div class="card-body">
                  <?php if($edit_category): ?>
                    <input type="hidden" name="category_id" value="<?php echo $edit_category['id']; ?>">
                  <?php endif; ?>
                  
                  <div class="form-group">
                    <label for="category_name">Category Name *</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" 
                           value="<?php echo $edit_category ? htmlspecialchars($edit_category['category_name']) : ''; ?>" 
                           placeholder="Enter category name" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" 
                              placeholder="Enter category description"><?php echo $edit_category ? htmlspecialchars($edit_category['description']) : ''; ?></textarea>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                    <?php echo $edit_category ? 'Update Category' : 'Add Category'; ?>
                  </button>
                  <?php if($edit_category): ?>
                    <a href="categories.php" class="btn btn-secondary">Cancel</a>
                  <?php else: ?>
                    <button type="reset" class="btn btn-default">Reset</button>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          </div>

          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Categories</h3>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Category Name</th>
                      <th>Description</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(count($categories) > 0): ?>
                      <?php $counter = 1; ?>
                      <?php foreach($categories as $category): ?>
                        <tr>
                          <td><?php echo $counter++; ?></td>
                          <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                          <td><?php echo htmlspecialchars($category['description'] ?: '-'); ?></td>
                          <td>
                            <?php if($category['status'] == 'active'): ?>
                              <span class="badge badge-success">Active</span>
                            <?php else: ?>
                              <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <a href="categories.php?edit_id=<?php echo $category['id']; ?>" class="btn btn-info btn-sm" title="Edit">
                              <i class="fas fa-edit"></i>
                            </a>
                            <a href="categories.php?toggle_status=<?php echo $category['id']; ?>" class="btn btn-warning btn-sm" title="Toggle Status">
                              <i class="fas fa-toggle-on"></i>
                            </a>
                            <button onclick="confirmDelete(<?php echo $category['id']; ?>)" class="btn btn-danger btn-sm" title="Delete">
                              <i class="fas fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="5" class="text-center">No categories found. Add your first category.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2014-<?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>

<script>
function confirmDelete(categoryId) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'categories.php?delete_id=' + categoryId;
    }
  });
}
</script>
</body>
</html>