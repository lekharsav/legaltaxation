<?php
// partner/sidebar.php - Partner Dashboard Sidebar (Modern Design)
// This file requires $partner variables to be defined before including
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

  .user-panel .image img,
  .user-panel .image div {
    width: 100%;
    height: 100%;
    border-radius: 12px !important;
    object-fit: cover;
  }

  .user-panel .image div {
    background: #f59e0b; /* amber-500 */
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

  /* Top-level nav links */
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

  /* Treeview / submenu styles */
  .nav-treeview {
    margin-left: 0.5rem;
    padding-left: 0.5rem;
    border-left: 1px solid #334155;
  }

  .nav-treeview .nav-item {
    margin-bottom: 0.15rem;
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

  /* Arrow icons for treeview */
  .nav-link .right {
    margin-left: auto;
    transition: transform 0.2s;
  }

  .nav-item.menu-open > .nav-link .right {
    transform: rotate(-90deg);
  }

  /* Logout special styling */
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
        <span class="brand-text font-weight-light">Partner Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php if(!empty($partner_image)): ?>
                    <img src="../uploads/partners/<?php echo $partner_image; ?>" class="img-circle elevation-2" alt="Partner Image" onerror="this.src='../assets/images/default-user.jpg'">
                <?php else: ?>
                    <div class="img-circle elevation-2 bg-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-user-tie text-white"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo htmlspecialchars($partner_name); ?></a>
                <small>Partner ID: #<?php echo str_pad($partner_id, 5, '0', STR_PAD_LEFT); ?></small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                // Get current page for active menu highlighting
                $current_page = basename($_SERVER['PHP_SELF']);
                $query_params = $_SERVER['QUERY_STRING'] ?? '';
                
                // Function to check if page is active with query parameters
                function isActivePage($page, $current_page, $query_params = '') {
                    if (strpos($current_page, $page) === 0) {
                        return true;
                    }
                    return false;
                }
                ?>
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <!-- Partner Business Menu -->
                <!-- <li class="nav-item has-treeview <?php 
                    echo (isActivePage('my-profile', $current_page) || 
                          isActivePage('business-profile', $current_page)) ? 'menu-open' : ''; 
                ?>">
                    <a href="#" class="nav-link <?php 
                        echo (isActivePage('my-profile', $current_page) || 
                              isActivePage('business-profile', $current_page)) ? 'active' : ''; 
                    ?>">
                        <i class="nav-icon"></i>
                        <p>
                            Profile
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="my-profile.php" class="nav-link <?php echo isActivePage('my-profile', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Personal Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="business-profile.php" class="nav-link <?php echo isActivePage('business-profile', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Business Profile</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                
                <!-- Referrals & Commissions -->
                <!-- <li class="nav-item">
                    <a href="referrals.php" class="nav-link <?php echo isActivePage('referrals', $current_page) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Referrals</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="commissions.php" class="nav-link <?php echo isActivePage('commissions', $current_page) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-rupee-sign"></i>
                        <p>Commissions</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="earnings.php" class="nav-link <?php echo isActivePage('earnings', $current_page) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Earnings Report</p>
                    </a>
                </li> -->

                <!-- Services Menu -->
                <li class="nav-item has-treeview <?php 
                    echo (isActivePage('services', $current_page) || 
                          strpos($query_params, 'id=') !== false || 
                          isActivePage('service-details', $current_page) ||
                          isActivePage('client-details', $current_page)) ? 'menu-open' : ''; 
                ?>">
                    <a href="#" class="nav-link <?php 
                        echo (isActivePage('services', $current_page) || 
                              strpos($query_params, 'id=') !== false || 
                              isActivePage('service-details', $current_page) ||
                              isActivePage('client-details', $current_page)) ? 'active' : ''; 
                    ?>">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Services
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="services.php" class="nav-link <?php echo isActivePage('services', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchased Services</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="service-details.php" class="nav-link <?php echo isActivePage('service-details', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service Details</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Accounting Menu -->
                <!-- <li class="nav-item has-treeview <?php 
                    echo (isActivePage('parties', $current_page) || 
                          isActivePage('add_party', $current_page) || 
                          isActivePage('edit_party', $current_page) ||
                          isActivePage('view_party', $current_page) ||
                          isActivePage('items', $current_page) ||
                          isActivePage('add_item', $current_page) ||
                          isActivePage('edit_item', $current_page) ||
                          isActivePage('view_item', $current_page) ||
                          isActivePage('categories', $current_page) ||
                          isActivePage('sales', $current_page) ||
                          isActivePage('add_sale', $current_page) ||
                          isActivePage('edit_sale', $current_page) ||
                          isActivePage('view_sale', $current_page) ||
                          isActivePage('sales_report', $current_page) ||
                          isActivePage('purchase', $current_page) ||
                          isActivePage('add_purchase', $current_page) ||
                          isActivePage('edit_purchase', $current_page) ||
                          isActivePage('view_purchase', $current_page) ||
                          isActivePage('purchase_report', $current_page)) ? 'menu-open' : ''; 
                ?>">
                    <a href="#" class="nav-link <?php 
                        echo (isActivePage('parties', $current_page) || 
                              isActivePage('add_party', $current_page) || 
                              isActivePage('edit_party', $current_page) ||
                              isActivePage('view_party', $current_page) ||
                              isActivePage('items', $current_page) ||
                              isActivePage('add_item', $current_page) ||
                              isActivePage('edit_item', $current_page) ||
                              isActivePage('view_item', $current_page) ||
                              isActivePage('categories', $current_page) ||
                              isActivePage('sales', $current_page) ||
                              isActivePage('add_sale', $current_page) ||
                              isActivePage('edit_sale', $current_page) ||
                              isActivePage('view_sale', $current_page) ||
                              isActivePage('sales_report', $current_page) ||
                              isActivePage('purchase', $current_page) ||
                              isActivePage('add_purchase', $current_page) ||
                              isActivePage('edit_purchase', $current_page) ||
                              isActivePage('view_purchase', $current_page) ||
                              isActivePage('purchase_report', $current_page)) ? 'active' : ''; 
                    ?>">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>
                            Accounting
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="parties.php" class="nav-link <?php echo isActivePage('parties', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Parties/Vendors</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="items.php" class="nav-link <?php echo isActivePage('items', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Items</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="categories.php" class="nav-link <?php echo isActivePage('categories', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="sales.php" class="nav-link <?php echo isActivePage('sales', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Invoices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="purchase.php" class="nav-link <?php echo isActivePage('purchase', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase Invoices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="sales_report.php" class="nav-link <?php echo isActivePage('sales_report', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="purchase_report.php" class="nav-link <?php echo isActivePage('purchase_report', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Expenses (Coming Soon)</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                
                <!-- Partner Resources -->
                <!-- <li class="nav-item has-treeview <?php 
                    echo (isActivePage('documents', $current_page) || 
                          isActivePage('resources', $current_page) || 
                          isActivePage('marketing-materials', $current_page)) ? 'menu-open' : ''; 
                ?>">
                    <a href="#" class="nav-link <?php 
                        echo (isActivePage('documents', $current_page) || 
                              isActivePage('resources', $current_page) || 
                              isActivePage('marketing-materials', $current_page)) ? 'active' : ''; 
                    ?>">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Resources
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="documents.php" class="nav-link <?php echo isActivePage('documents', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Documents</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="resources.php" class="nav-link <?php echo isActivePage('resources', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Resources</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="marketing-materials.php" class="nav-link <?php echo isActivePage('marketing-materials', $current_page) ? 'active' : ''; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Marketing Materials</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                
                <!-- Support -->
                <!-- <li class="nav-item">
                    <a href="support.php" class="nav-link <?php echo isActivePage('support', $current_page) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-headset"></i>
                        <p>Support</p>
                    </a>
                </li> -->
                
                <!-- Settings -->
                <!-- <li class="nav-item">
                    <a href="settings.php" class="nav-link <?php echo isActivePage('settings', $current_page) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li> -->
                
                <!-- Change Password -->
                <li class="nav-item">
                    <a href="change-password.php" class="nav-link <?php echo isActivePage('change-password', $current_page) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                
                <!-- External Links -->
                <!-- <li class="nav-item">
                    <a href="refer-client.php" class="nav-link <?php echo isActivePage('refer-client', $current_page) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user-plus"></i>
                        <p>Refer a Client</p>
                    </a>
                </li> -->
                
                    <!-- <li class="nav-item">
                        <a href="../service.php" class="nav-link" target="_blank">
                            <i class="nav-icon fas fa-plus-circle"></i>
                            <p>Browse Services</p>
                        </a>
                    </li> -->
                
                <!-- <li class="nav-item">
                    <a href="../index.php" class="nav-link" target="_blank">
                        <i class="nav-icon fas fa-globe"></i>
                        <p>Main Website</p>
                    </a>
                </li> -->

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