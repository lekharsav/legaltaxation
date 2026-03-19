<?php
// sidebar.php - Admin Dashboard Sidebar (Modern Design)

// Get admin ID from cookie
$aid = $_COOKIE["tax_admin_log"] ?? '';

// If admin variables are not already defined, fetch them
if(!isset($admin_name) && $aid){
    include("../db.php");
    $sql = $con->query("SELECT * FROM admin WHERE id='$aid'");
    if($row = $sql->fetch_assoc()){
        $admin_image = $row["image"];
        $admin_name = $row["name"];
        $admin_email = $row["email"];
    } else {
        $admin_image = '';
        $admin_name = 'Admin';
    }
} else {
    $admin_name = $admin_name ?? 'Admin';
    $admin_image = $admin_image ?? '';
}

// Get CA count for badge
$ca_count = 0;
if(isset($con) && $con){
    $result = $con->query("SELECT COUNT(*) as total FROM ca");
    $ca_count = $result ? $result->fetch_assoc()['total'] : 0;
}
?>

<!-- Google Font: Inter (if not already loaded) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

<style>
  /* Force Inter font on sidebar */
  .main-sidebar * {
    font-family: 'Inter', sans-serif;
  }

  /* Sidebar background - dark slate */
  .main-sidebar {
    background-color: #1e293b !important; /* slate-800 */
    box-shadow: 4px 0 12px rgba(0, 0, 0, 0.05);
    border-right: 1px solid #334155;
  }

  /* Brand link */
  .brand-link {
    border-bottom: 1px solid #334155 !important;
    padding: 1rem 1rem !important;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .brand-link img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border: none !important;
    opacity: 1 !important;
  }

  .brand-text {
    font-weight: 600 !important;
    font-size: 1rem !important;
    color: #f1f5f9 !important;
    letter-spacing: 0.3px;
  }

  /* User panel */
  .user-panel {
    padding: 1.5rem 1rem !important;
    border-bottom: 1px solid #334155 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
  }

  .user-panel .image {
    width: 48px;
    height: 48px;
  }

  .user-panel .image img,
  .user-panel .image div {
    width: 100%;
    height: 100%;
    border-radius: 12px !important;
    object-fit: cover;
  }

  .user-panel .image div {
    background: #3b82f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
  }

  .user-panel .info {
    padding-left: 1rem !important;
  }

  .user-panel .info a {
    font-weight: 600;
    font-size: 0.95rem;
    color: #f1f5f9 !important;
    margin-bottom: 0.2rem;
  }

  /* Sidebar search */
  .form-inline {
    padding: 0 1rem;
    margin-bottom: 1rem;
  }
  .input-group {
    width: 100%;
  }
  .form-control-sidebar {
    background: #334155;
    border: 1px solid #475569;
    color: #f1f5f9;
    border-radius: 0.75rem 0 0 0.75rem;
  }
  .btn-sidebar {
    background: #334155;
    border: 1px solid #475569;
    border-left: none;
    color: #94a3b8;
    border-radius: 0 0.75rem 0.75rem 0;
    transition: all 0.2s;
  }
  .btn-sidebar:hover {
    background: #3b82f6;
    color: white;
  }

  /* Navigation menu */
  .nav-sidebar {
    padding: 1rem 0.75rem;
  }

  .nav-item {
    margin-bottom: 0.25rem;
  }

  .nav-link {
    padding: 0.75rem 1rem !important;
    border-radius: 0.75rem !important;
    color: #cbd5e1 !important;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .nav-link i {
    font-size: 1.1rem;
    width: 1.5rem;
    color: #64748b;
    transition: color 0.2s;
  }

  .nav-link:hover {
    background-color: #334155 !important;
    color: #f1f5f9 !important;
  }

  .nav-link:hover i {
    color: #3b82f6 !important;
  }

  .nav-link.active {
    background-color: #3b82f6 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
  }

  .nav-link.active i {
    color: #ffffff !important;
  }

  /* Treeview submenu */
  .nav-treeview {
    margin-left: 0.5rem;
    padding-left: 0.5rem;
    border-left: 1px solid #334155;
  }

  .nav-treeview .nav-link {
    padding: 0.5rem 1rem !important;
    font-size: 0.85rem;
    border-radius: 0.5rem !important;
  }

  .nav-treeview .nav-link i {
    font-size: 0.9rem;
    width: 1.2rem;
  }

  /* Arrow icon rotation */
  .nav-link .right {
    margin-left: auto;
    transition: transform 0.2s;
  }
  .nav-item.menu-open > .nav-link .right {
    transform: rotate(-90deg);
  }

  /* Badges */
  .nav-link .badge {
    margin-left: auto;
    background: #475569 !important;
    color: #f1f5f9 !important;
    border-radius: 100px;
    padding: 0.25rem 0.6rem;
    font-size: 0.7rem;
  }

  /* Logout special styling */
  .nav-link.text-danger {
    color: #f87171 !important;
  }
  .nav-link.text-danger i {
    color: #f87171 !important;
  }
  .nav-link.text-danger:hover {
    background-color: #334155 !important;
    color: #fecaca !important;
  }
  .nav-link.text-danger:hover i {
    color: #f87171 !important;
  }

  /* Scrollbar */
  .sidebar::-webkit-scrollbar {
    width: 4px;
  }
  .sidebar::-webkit-scrollbar-track {
    background: #1e293b;
  }
  .sidebar::-webkit-scrollbar-thumb {
    background: #475569;
    border-radius: 4px;
  }
  .sidebar::-webkit-scrollbar-thumb:hover {
    background: #64748b;
  }
</style>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
        <img src="../images/logo.png" alt="Legal Taxation Logo">
        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php if(!empty($admin_image) && file_exists("../uploads/admin/".$admin_image)): ?>
                    <img src="../uploads/admin/<?php echo $admin_image; ?>" 
                         alt="<?php echo htmlspecialchars($admin_name); ?>"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<div><i class=\'fas fa-user-shield\'></i></div>';">
                <?php else: ?>
                    <div><i class="fas fa-user-shield"></i></div>
                <?php endif; ?>
            </div>
            <div class="info">
                <a href="profile.php" class="d-block">
                    <?php echo htmlspecialchars($admin_name); ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Search Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="nav-icon"></i>
                        <p>DASHBOARD</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="customers.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>">
                        <i class="nav-icon"></i>
                        <p>CUSTOMERS</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="catagory.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'catagory.php' ? 'active' : ''; ?>">
                        <i class="nav-icon"></i>
                        <p>CATEGORY</p>
                    </a>
                </li>

                <!-- SERVICES Dropdown -->
                <li class="nav-item has-treeview <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add_service.php', 'all_service.php', 'manage_service_forms.php']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon"></i>
                        <p>
                            SERVICES
                            <i class="fas"></i>
                            <span class="badge badge-info right">7</span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="add_service.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_service.php' ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Services</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="all_service.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'all_service.php' ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View Services</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="manage_service_forms.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_service_forms.php' ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Form Fields</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- CA MANAGEMENT Dropdown -->
                <li class="nav-item has-treeview <?php echo in_array(basename($_SERVER['PHP_SELF']), ['add_ca.php', 'all_ca.php', 'ca_designation.php']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon"></i>
                        <p>
                            CA MANAGEMENT
                            <i class="fas"></i>
                            <span class="badge badge-info right"><?php echo $ca_count; ?></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="add_ca.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_ca.php' ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add CA</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="all_ca.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'all_ca.php' ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View All CA</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="ca_designation.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'ca_designation.php' ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>CA Designations</p>
                            </a>
                        </li> -->
                    </ul>
                </li>

                <!-- LOGOUT BUTTON -->
                <li class="nav-item">
                    <a href="#" class="nav-link text-danger" id="logoutLink">
                        <i class="nav-icon"></i>
                        <p>LOGOUT</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to logout from the admin panel?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Logout Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.getElementById('logoutLink');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            $('#logoutModal').modal('show');
        });
    }
});
</script>