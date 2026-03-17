<?php
// client/navbar.php - Self-contained Customer Navbar

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$customer_name = 'Customer';
$customer_id = 'N/A';

// Check if cookie exists for customer login
if(isset($_COOKIE['tax_customer_log']) && !empty($_COOKIE['tax_customer_log'])){
    $customer_id = $_COOKIE['tax_customer_log'];
    
    // If customer variables are not already defined, fetch them
    if(!isset($customer_name)){
        // Try to include database connection
        $db_included = false;
        if(file_exists('../db.php')){
            include_once("../db.php");
            $db_included = true;
        } elseif(file_exists('../../db.php')){
            include_once("../../db.php");
            $db_included = true;
        }
        
        // If we have database connection, fetch customer data
        if($db_included && isset($con)){
            $sql = $con->query("SELECT * FROM customer WHERE id='$customer_id' AND status='1'");
            if($sql && $sql->num_rows > 0){
                $customer = $sql->fetch_assoc();
                $customer_name = $customer["name"];
                $customer_image = $customer["image"] ?? '';
                $customer_email = $customer["email"] ?? '';
            }
        }
    }
}

// Get current page for active links
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
            <a href="my-services.php" 
               class="nav-link <?php echo $current_page == 'my-services.php' ? 'active' : ''; ?>">
                <i class="fas fa-cogs mr-1"></i> My Services
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../index.php" class="nav-link" target="_blank">
                <i class="fas fa-home mr-1"></i> Website
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown -->
        <?php
        $notification_count = 0;
        if(isset($con) && isset($customer_id)){
            $count_sql = $con->query("SELECT COUNT(*) as count FROM apply WHERE cid='$customer_id' AND status='0'");
            if($count_sql && $count_row = $count_sql->fetch_assoc()){
                $notification_count = $count_row['count'];
            }
        }
        ?>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <?php if($notification_count > 0): ?>
                <span class="badge badge-warning navbar-badge"><?php echo $notification_count; ?></span>
                <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    <?php echo $notification_count; ?> Pending Services
                </span>
                <div class="dropdown-divider"></div>
                <a href="my-services.php?filter=pending" class="dropdown-item">
                    <i class="fas fa-clock mr-2"></i> View Pending Services
                </a>
                <div class="dropdown-divider"></div>
                <a href="my-services.php" class="dropdown-item dropdown-footer">See All Services</a>
            </div>
        </li>
        
        <!-- User Account Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user-circle"></i> 
                <?php echo htmlspecialchars($customer_name); ?>
                <i class="fas fa-caret-down ml-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <?php if(!empty($customer_image)): ?>
                        <img src="../uploads/customers/<?php echo $customer_image; ?>" 
                             class="img-circle elevation-2" alt="Customer Image"
                             style="width: 60px; height: 60px; object-fit: cover;"
                             onerror="this.src='../assets/images/default-user.jpg'">
                    <?php else: ?>
                        <div class="img-circle elevation-2 bg-primary d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-user text-white fa-2x"></i>
                        </div>
                    <?php endif; ?>
                    <p class="mb-0 mt-2">
                        <strong><?php echo htmlspecialchars($customer_name); ?></strong>
                    </p>
                    <small>Customer ID: #<?php echo $customer_id; ?></small>
                </div>
                <div class="dropdown-divider"></div>
                
                <a href="my-profile.php" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
                <a href="my-services.php" class="dropdown-item">
                    <i class="fas fa-cogs mr-2"></i> My Services
                </a>
                <a href="change-password.php" class="dropdown-item">
                    <i class="fas fa-key mr-2"></i> Change Password
                </a>
                
                <div class="dropdown-divider"></div>
                
                <div class="dropdown-footer">
                    <a href="../logout.php?return=index" class="btn btn-danger btn-sm btn-block">
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
    border: 3px solid #28a745;
}
.dropdown-footer {
    padding: 10px;
}
</style>