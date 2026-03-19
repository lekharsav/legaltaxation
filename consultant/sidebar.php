<?php
// sidebar.php - CA Dashboard Sidebar (Modern Design)
// This file should check for CA session and get CA data if needed

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if CA is logged in
if(isset($_SESSION['ca_logged_in']) && $_SESSION['ca_logged_in'] === true){
    
    // Get CA ID from session
    $ca_id = $_SESSION['ca_id'];
    
    // If CA variables are not already defined, fetch them
    if(!isset($ca_name) || !isset($ca_image)){
        include("../db.php");
        
        $sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
        if($sql->num_rows > 0){
            $ca = $sql->fetch_assoc();
            
            // Set CA variables
            $ca_image = $ca["image"];
            $ca_name = $ca["name"];
            $ca_contact = $ca["cont"];
            $ca_email = $ca["email"];
            $ca_reg_no = $ca["reg_no"];
            $ca_id_number = $ca["ca_id"];
        } else {
            // CA not found or inactive
            $ca_image = 'default-profile.jpg';
            $ca_name = 'CA User';
            $ca_id_number = 'N/A';
        }
    }
} else {
    // Not logged in - set default values
    $ca_image = 'default-profile.jpg';
    $ca_name = 'Guest';
    $ca_id_number = 'N/A';
}

// Get current page for active class
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Inter font (if not already loaded) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

<!-- Modern Sidebar Styles -->
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

  .brand-image {
    border: none !important;
    opacity: 1 !important;
    width: 40px;
    height: 40px;
    object-fit: cover;
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

  .user-panel .image img {
    width: 100%;
    height: 100%;
    border-radius: 12px !important;
    object-fit: cover;
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

  .user-panel .info small {
    font-size: 0.7rem;
    color: #94a3b8 !important; /* slate-400 */
    display: block;
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
    color: #cbd5e1 !important; /* slate-300 */
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
    color: #64748b; /* slate-500 */
    transition: color 0.2s;
  }

  .nav-link:hover {
    background-color: #334155 !important; /* slate-700 */
    color: #f1f5f9 !important;
  }

  .nav-link:hover i {
    color: #3b82f6 !important; /* blue-500 */
  }

  .nav-link.active {
    background-color: #3b82f6 !important; /* blue-500 */
    color: #ffffff !important;
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
  }

  .nav-link.active i {
    color: #ffffff !important;
  }

  /* Logout special styling */
  .nav-link[href*="logout"] {
    color: #f87171 !important; /* red-400 */
  }

  .nav-link[href*="logout"] i {
    color: #f87171 !important;
  }

  .nav-link[href*="logout"]:hover {
    background-color: #334155 !important;
    color: #fecaca !important;
  }

  .nav-link[href*="logout"]:hover i {
    color: #f87171 !important;
  }

  /* Scrollbar styling */
  .sidebar::-webkit-scrollbar {
    width: 4px;
  }

  .sidebar::-webkit-scrollbar-track {
    background: #1e293b;
  }

  .sidebar::-webkit-scrollbar-thumb {
    background: #475569; /* slate-600 */
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
        <img src="../images/logo.png" alt="Legal Taxation Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">Legal Taxation</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../admin/ca/<?php echo isset($ca_image) ? $ca_image : 'default-profile.jpg'; ?>" 
                     class="img-circle elevation-2" alt="CA Image" 
                     onerror="this.src='../assets/images/profile.jpg'">
            </div>
            <div class="info">
                <a href="profile.php" class="d-block">
                    <?php echo isset($ca_name) ? $ca_name : 'CA User'; ?>
                </a>
                <small class="text-muted">
                    CA ID: <?php echo isset($ca_id_number) ? $ca_id_number : 'N/A'; ?>
                </small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="profile.php" class="nav-link <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>My Profile</p>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a href="my-customers.php" class="nav-link <?php echo $current_page == 'my-customers.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>My Customers</p>
                    </a>
                </li> -->

                <li class="nav-item">
                    <a href="assigned_services.php" class="nav-link <?php echo $current_page == 'assigned_services.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Assigned Services</p>
                    </a>
                </li>

                <!-- <li class="nav-item">
                    <a href="documents.php" class="nav-link <?php echo $current_page == 'documents.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Documents</p>
                    </a>
                </li> -->

                <!-- <li class="nav-item">
                    <a href="reports.php" class="nav-link <?php echo $current_page == 'reports.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports</p>
                    </a>
                </li> -->

                <li class="nav-item">
                    <a href="change-password.php" class="nav-link <?php echo $current_page == 'change-password.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="../logout.php?type=ca" class="nav-link">
                        <i class="nav-icon"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>