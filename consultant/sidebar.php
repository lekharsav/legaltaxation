<?php
// sidebar.php
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

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
        <img src="../images/logo.webp" alt="Legal Taxation Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="profile.php" class="nav-link <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>My Profile</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="my-customers.php" class="nav-link <?php echo $current_page == 'my-customers.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>My Customers</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="assigned_services.php" class="nav-link <?php echo $current_page == 'assigned_services.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Assigned Services</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="documents.php" class="nav-link <?php echo $current_page == 'documents.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Documents</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="reports.php" class="nav-link <?php echo $current_page == 'reports.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="change-password.php" class="nav-link <?php echo $current_page == 'change-password.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="../logout.php?type=ca" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>