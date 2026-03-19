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
  <title>Category Management | Admin Dashboard | Legal Taxation</title>

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

    /* Cards */
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
    .form-control {
      border: 1px solid #e2e8f0;
      border-radius: 1rem;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      transition: all 0.2s;
    }
    .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
      outline: none;
    }
    .btn-modern {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.6rem 1.25rem;
      font-size: 0.85rem;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s;
    }
    .btn-modern:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }
    .btn-primary-modern {
      background: #3b82f6;
      border-color: #3b82f6;
      color: white;
    }
    .btn-primary-modern:hover {
      background: #2563eb;
    }

    /* Table */
    .table-responsive { overflow-x: auto; }
    .table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 0.5rem;
    }
    .table thead th {
      border: none;
      font-weight: 600;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      background: #f8fafc;
      padding: 0.75rem 1rem;
    }
    .table tbody tr {
      background: #ffffff;
      border-radius: 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.02);
      transition: all 0.2s;
    }
    .table tbody tr:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .table tbody td {
      border: none;
      padding: 1rem;
      vertical-align: middle;
      font-size: 0.9rem;
      color: #334155;
    }

    /* Action buttons */
    .btn-icon {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 100px;
      padding: 0.4rem 0.8rem;
      font-size: 0.75rem;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      transition: all 0.2s;
      margin-right: 0.25rem;
    }
    .btn-icon:hover { background: #f8fafc; border-color: #94a3b8; }
    .btn-icon-danger:hover { background: #fee2e2; border-color: #f87171; color: #991b1b; }

    /* Badge */
    .badge-count {
      background: #f1f5f9;
      color: #334155;
      padding: 0.25rem 0.75rem;
      border-radius: 100px;
      font-size: 0.7rem;
      font-weight: 500;
    }

    /* Alerts */
    .alert {
      border-radius: 1rem;
      border: none;
      padding: 1rem 1.25rem;
    }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-danger { background: #fee2e2; color: #991b1b; }

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
              <div class="icon"><i class="fas fa-tags"></i></div>
              <h1>Category Management</h1>
            </div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

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
        <div class="modern-card">
          <div class="card-header-custom">
            <h3>
              <i class="fas <?php echo $edit_id ? 'fa-edit' : 'fa-plus-circle'; ?>"></i>
              <?php echo $edit_id ? 'Edit Category' : 'Add New Category'; ?>
            </h3>
          </div>
          <div class="card-body-custom">
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
                    <button type="submit" name="submit" class="btn-modern btn-primary-modern">
                      <i class="fas <?php echo $edit_id ? 'fa-save' : 'fa-plus'; ?>"></i>
                      <?php echo $edit_id ? 'Update Category' : 'Add Category'; ?>
                    </button>
                    <?php if ($edit_id): ?>
                      <a href="catagory.php" class="btn-modern ml-2">
                        <i class="fas fa-times"></i> Cancel
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Categories List Card -->
        <div class="modern-card">
          <div class="card-header-custom">
            <h3><i class="fas fa-list"></i> All Categories</h3>
            <span class="badge-count"><?php echo count($categories); ?> total</span>
          </div>
          <div class="card-body-custom">
            <?php if (count($categories) > 0): ?>
              <div class="table-responsive">
                <table class="table">
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
                        <a href="catagory.php?edit=<?php echo $cat['id']; ?>" class="btn-icon" title="Edit">
                          <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="catagory.php?delete=<?php echo $cat['id']; ?>" 
                           class="btn-icon btn-icon-danger" 
                           title="Delete"
                           onclick="return confirm('Are you sure you want to delete this category?');">
                          <i class="fas fa-trash-alt"></i> Delete
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
<!-- Optional: auto-dismiss alerts -->
<script>
  setTimeout(function() {
    document.querySelectorAll('.alert').forEach(function(alert) {
      $(alert).alert('close');
    });
  }, 3000);
</script>
</body>
</html>