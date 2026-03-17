<?php
// navbar.php
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
    if(!isset($ca_name)){
        // Check if database connection exists
        if(!isset($con)){
            // Try to include database connection
            if(file_exists('../db.php')){
                include("../db.php");
            } elseif(file_exists('../../db.php')){
                include("../../db.php");
            }
        }
        
        // If we have database connection, fetch CA data
        if(isset($con)){
            $sql = $con->query("SELECT * FROM ca WHERE id='$ca_id' AND status='1'");
            if($sql && $sql->num_rows > 0){
                $ca = $sql->fetch_assoc();
                
                // Set CA variables
                $ca_name = $ca["name"];
                $ca_image = $ca["image"] ?? 'default-profile.jpg';
                $ca_email = $ca["email"] ?? '';
                $ca_id_number = $ca["ca_id"] ?? 'N/A';
            } else {
                // CA not found or inactive
                $ca_name = 'CA User';
                $ca_image = 'default-profile.jpg';
                $ca_id_number = 'N/A';
            }
        } else {
            // No database connection
            $ca_name = 'CA User';
            $ca_image = 'default-profile.jpg';
            $ca_id_number = 'N/A';
        }
    }
} else {
    // Not logged in - set default values
    $ca_name = 'Guest';
    $ca_image = 'default-profile.jpg';
    $ca_id_number = 'N/A';
}

// Get current page for highlighting active links
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="dashboard.php" 
               class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="assigned_services.php" 
               class="nav-link <?php echo $current_page == 'assigned_services.php' ? 'active' : ''; ?>">
                <i class="fas fa-tasks mr-1"></i> Services
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="my-customers.php" 
               class="nav-link <?php echo $current_page == 'my-customers.php' ? 'active' : ''; ?>">
                <i class="fas fa-users mr-1"></i> Customers
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <?php
                // Get pending assignments count for notifications
                $pending_count = 0;
                if(isset($con) && isset($ca_id)){
                    $count_sql = $con->query("SELECT COUNT(*) as count FROM apply WHERE send_to='$ca_id' AND status='0'");
                    if($count_sql && $count_row = $count_sql->fetch_assoc()){
                        $pending_count = $count_row['count'];
                    }
                }
                ?>
                <?php if($pending_count > 0): ?>
                <span class="badge badge-warning navbar-badge"><?php echo $pending_count; ?></span>
                <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    <?php echo $pending_count; ?> Pending Services
                </span>
                <div class="dropdown-divider"></div>
                <a href="assigned_services.php?filter=pending" class="dropdown-item">
                    <i class="fas fa-clock mr-2"></i> View Pending Services
                </a>
                <div class="dropdown-divider"></div>
                <a href="assigned_services.php" class="dropdown-item dropdown-footer">See All Services</a>
            </div>
        </li>
        
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
        
        <!-- User Account Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user-circle"></i> 
                <?php echo isset($ca_name) ? htmlspecialchars($ca_name) : 'CA User'; ?>
                <i class="fas fa-caret-down ml-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <img src="../admin/ca/<?php echo isset($ca_image) ? $ca_image : 'default-profile.jpg'; ?>" 
                         class="img-circle elevation-2" alt="CA Image" 
                         style="width: 60px; height: 60px; object-fit: cover;"
                         onerror="this.src='../assets/images/profile.jpg'">
                    <p class="mb-0 mt-2">
                        <strong><?php echo isset($ca_name) ? htmlspecialchars($ca_name) : 'CA User'; ?></strong>
                    </p>
                    <small>CA ID: <?php echo isset($ca_id_number) ? $ca_id_number : 'N/A'; ?></small>
                </div>
                <div class="dropdown-divider"></div>
                
                <a href="profile.php" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <a href="edit-profile.php" class="dropdown-item">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </a>
                <a href="change-password.php" class="dropdown-item">
                    <i class="fas fa-key mr-2"></i> Change Password
                </a>
                
                <div class="dropdown-divider"></div>
                
                <div class="dropdown-footer">
                    <a href="../logout.php?type=ca" class="btn btn-danger btn-sm btn-block">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </div>
            </div>
        </li>
        
        <!-- Fullscreen Toggle -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        
        <!-- Control Sidebar Toggle -->
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<style>
.navbar-badge {
    font-size: 0.6rem;
    position: absolute;
    right: 5px;
    top: 9px;
}
.dropdown-header img {
    border: 3px solid #007bff;
}
.dropdown-footer {
    padding: 10px;
}
</style>

<script>
// Navbar functionality
$(document).ready(function() {
    // Auto-update notification badge every 30 seconds
    setInterval(function() {
        updateNotificationCount();
    }, 30000);
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});

function updateNotificationCount() {
    $.ajax({
        url: 'get_notification_count.php',
        type: 'GET',
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if(data.success && data.count > 0) {
                    $('.navbar-badge').text(data.count);
                    $('.dropdown-header span').text(data.count + ' Pending Services');
                }
            } catch(e) {
                console.log('Error updating notifications:', e);
            }
        }
    });
}
</script>