<?php
// client/sidebar.php - Redesigned Customer Sidebar (matching my-services.php)

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$customer_name = 'Customer';
$customer_id = 'N/A';
$customer_image = '';

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
            }
        }
    }
}

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Inter font (same as my-services.php) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

<!-- Custom styles for this sidebar (overrides AdminLTE) -->
<style>
  /* Force Inter font on sidebar */
  .main-sidebar * {
    font-family: 'Inter', sans-serif;
  }

  /* Sidebar background - dark slate to match my-services.php override */
  .main-sidebar {
    background-color: #1e293b !important; /* slate-800 */
    box-shadow: 4px 0 12px rgba(0, 0, 0, 0.05);
    border-right: 1px solid #334155; /* subtle border */
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

  .user-panel .image img,
  .user-panel .image div {
    width: 100%;
    height: 100%;
    border-radius: 12px !important; /* square-ish, but rounded */
    object-fit: cover;
  }

  .user-panel .image div {
    background: #3b82f6; /* blue-500 */
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
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

  /* Special styling for logout */
  .nav-link.text-danger {
    color: #f87171 !important; /* red-400 */
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

  /* Menu headers (if any) */
  .nav-header {
    color: #64748b !important;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 1rem 0.5rem !important;
  }

  /* Scrollbar styling (optional) */
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
        <img src="../images/logo.png" alt="Legal Taxation Logo" class="brand-image " style="opacity: .8">
        <span class="brand-text font-weight-light">Customer Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php if(!empty($customer_image)): ?>
                    <img src="../uploads/customers/<?php echo $customer_image; ?>" 
                         class="img-circle elevation-2" alt="User Image" 
                         onerror="this.src='../assets/images/default-user.jpg'">
                <?php else: ?>
                    <div class="img-circle elevation-2 bg-primary d-flex align-items-center justify-content-center" 
                         style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="info">
                <a href="my-profile.php" class="d-block"><?php echo htmlspecialchars($customer_name); ?></a>
                <small>ID: #<?php echo str_pad($customer_id, 5, '0', STR_PAD_LEFT); ?></small>
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
                
                <!-- <li class="nav-item">
                    <a href="my-profile.php" class="nav-link <?php echo $current_page == 'my-profile.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>My Profile</p>
                    </a>
                </li> -->
                
                <li class="nav-item">
                    <a href="my-services.php" class="nav-link <?php echo $current_page == 'my-services.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>My Services</p>
                    </a>
                </li>
                
                <!-- <li class="nav-item">
                    <a href="my-documents.php" class="nav-link <?php echo $current_page == 'my-documents.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>My Documents</p>
                    </a>
                </li> -->
                
                <!-- <li class="nav-item">
                    <a href="my-payments.php" class="nav-link <?php echo $current_page == 'my-payments.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Payments</p>
                    </a>
                </li> -->
                
                <!-- <li class="nav-item">
                    <a href="support-tickets.php" class="nav-link <?php echo $current_page == 'support-tickets.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-headset"></i>
                        <p>Support</p>
                    </a>
                </li> -->

                <!-- Browse Services -->
                <!-- <li class="nav-item">
                    <a href="../service.php" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>Browse Services</p>
                    </a>
                </li> -->
                
                <!-- Back to Website -->
                <!-- <li class="nav-item">
                    <a href="../index.php" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Back to Website</p>
                    </a>
                </li> -->

                <!-- Change Password -->
                <li class="nav-item">
                    <a href="change-password.php" class="nav-link <?php echo $current_page == 'change-password.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                
                <!-- Logout -->
                <li class="nav-item">
                    <a href="../logout.php?return=index" class="nav-link text-danger">
                        <i class="nav-icon"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>