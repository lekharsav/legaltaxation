<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<!-- Add a unique wrapper class to scope all styles below -->
<div class="header-enhanced">

<section class="top_head">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-8">
                <div class="cont_area">
                    <a href="mailto:info@legaltaxation.com"><i class="fa fa-envelope"></i> info@legaltaxation.com</a>
                    <a href="tel:+91-7004407668"><i class="fa fa-phone"></i> +91-7004407668</a>
                </div>
            </div>
            <div class="col-md-6 col-4">
                <div class="login_area">
                    <ul class="d-flex align-items-center justify-content-end mb-0">
                        <?php
                        // --------------------------------------------------
                        // Define variables at the very beginning
                        // --------------------------------------------------
                        $header_user   = '';   // 'customer' | 'partner' | 'ca'
                        $header_name   = '';

                        // 1. CUSTOMER
                        if (!empty($_COOKIE['tax_customer_log'])) {
                            $cid  = $con->real_escape_string($_COOKIE['tax_customer_log']);
                            $crow = $con->query("SELECT name FROM customer WHERE id='$cid'");
                            if ($crow && $crow->num_rows > 0) {
                                $header_user = 'customer';
                                $header_name = $crow->fetch_assoc()['name'];
                            } else {
                                // Stale cookie — clear on every possible path
                                $past = time() - 3600;
                                foreach (['/', '/legalTaxation/', '/legalTaxation'] as $p)
                                    setcookie('tax_customer_log', '', $past, $p);
                                unset($_COOKIE['tax_customer_log']);
                            }
                        }

                        // 2. PARTNER  (only if customer check didn't match)
                        if ($header_user === '' && !empty($_COOKIE['tax_partner_log'])) {
                            $pid  = $con->real_escape_string($_COOKIE['tax_partner_log']);
                            $prow = $con->query("SELECT name FROM partner WHERE id='$pid'");
                            if ($prow && $prow->num_rows > 0) {
                                $header_user = 'partner';
                                $header_name = $prow->fetch_assoc()['name'];
                            } else {
                                $past = time() - 3600;
                                foreach (['/', '/legalTaxation/', '/legalTaxation'] as $p)
                                    setcookie('tax_partner_log', '', $past, $p);
                                unset($_COOKIE['tax_partner_log']);
                            }
                        }

                        // 3. CONSULTANT (session-based, no cookie to validate)
                        if ($header_user === '' && isset($_SESSION['ca_logged_in']) && $_SESSION['ca_logged_in'] === true) {
                            $header_user = 'ca';
                            $header_name = $_SESSION['ca_name'] ?? 'Consultant';
                        }

                        // Show cart icon for partners
                        if ($header_user === 'partner'):
                        ?>
                        <?php
                        // Compute initial cart count server-side so it persists on page load/refresh
                        $header_cart_count = 0;
                        if (!empty($_COOKIE['tax_partner_log']) && isset($con)) {
                            $pid_for_count = $con->real_escape_string($_COOKIE['tax_partner_log']);
                            $cnt_q = $con->query("SELECT COUNT(*) AS cnt FROM partner_cart WHERE partner_id = '" . $pid_for_count . "'");
                            if ($cnt_q) {
                                $cnt_r = $cnt_q->fetch_assoc();
                                $header_cart_count = intval($cnt_r['cnt']);
                            }
                        }
                        ?>
                        <li class="nav-item cart-item me-3">
                            <a href="cart.php" class="cart-icon-link" title="View Cart">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-count" id="headerCartCount"><?php echo $header_cart_count; ?></span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown user-menu">
                            <?php
                            // Render dropdown based on result
                            if ($header_user === 'customer'):
                            ?>
                                <a class="nav-link dropdown-toggle user-link" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user-circle"></i>
                                    <span class="user-name"><?php echo htmlspecialchars($header_name); ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="client/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="client/profile.php"><i class="fas fa-user me-2"></i>My Profile</a></li>
                                    <li><a class="dropdown-item" href="client/applications.php"><i class="fas fa-file-alt me-2"></i>My Applications</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>

                            <?php elseif ($header_user === 'partner'): ?>
                                <a class="nav-link dropdown-toggle user-link" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user-tie"></i>
                                    <span class="user-name"><?php echo htmlspecialchars($header_name); ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="partner/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="partner/clients.php"><i class="fas fa-users me-2"></i>My Clients</a></li>
                                    <li><a class="dropdown-item" href="partner/earnings.php"><i class="fas fa-rupee-sign me-2"></i>Earnings</a></li>
                                    <li><a class="dropdown-item" href="partner/profile.php"><i class="fas fa-user-cog me-2"></i>Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>

                            <?php elseif ($header_user === 'ca'): ?>
                                <a class="nav-link dropdown-toggle user-link" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user-circle"></i>
                                    <span class="user-name"><?php echo htmlspecialchars($header_name); ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="consultant/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="consultant/appointments.php"><i class="fas fa-calendar-check me-2"></i>Appointments</a></li>
                                    <li><a class="dropdown-item" href="consultant/clients.php"><i class="fas fa-users me-2"></i>Clients</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>

                            <?php else: ?>
                                <a class="nav-link dropdown-toggle login-link" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-lock"></i> Login
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                                    <li><a class="dropdown-item" href="user-login.php"><i class="fas fa-user me-2"></i>Client Login</a></li>
                                    <li><a class="dropdown-item" href="partner-login.php"><i class="fas fa-handshake me-2"></i>Partner Login</a></li>
                                    <li><a class="dropdown-item" href="ca-login.php"><i class="fas fa-gavel me-2"></i>Consultant Login</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="customer-reg.php"><i class="fas fa-user-plus me-2"></i>Register as Client</a></li>
                                    <li><a class="dropdown-item" href="partner-reg.php"><i class="fas fa-user-plus me-2"></i>Become a Partner</a></li>
                                </ul>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="head_nav" id="sticky-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.webp" alt="Legal Taxation">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about-us.php">About Us</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Services
                        </a>

                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            <?php
                            // Fetch all categories
                            $header_qry = $con->query("SELECT * FROM cate ORDER BY id ASC");
                            if ($header_qry && $header_qry->num_rows > 0) {
                                while ($header_row = $header_qry->fetch_assoc()) {
                                    $cate_id = (int) $header_row['id'];
                                    $cate_name = htmlspecialchars($header_row['name'], ENT_QUOTES, 'UTF-8');
                                    
                                    // Fetch services for this category
                                    $header_sql2 = $con->query("SELECT * FROM service WHERE cate = '{$cate_id}' ORDER BY id ASC");
                                    
                                    // If this category has services inside it, make it a nested submenu
                                    if ($header_sql2 && $header_sql2->num_rows > 0) {
                            ?>
                                        <li class="dropdown-submenu">
                                            <a href="service.php?cate=<?php echo $cate_id; ?>" class="dropdown-item dropdown-toggle">
                                                <i class="fas fa-folder-open me-2"></i><?php echo $cate_name; ?>
                                            </a>
                                            <ul class="dropdown-menu submenu">
                                                <?php
                                                while ($header_row2 = $header_sql2->fetch_assoc()) {
                                                    $service_id = (int) $header_row2['id'];
                                                    $service_title = htmlspecialchars($header_row2['title'], ENT_QUOTES, 'UTF-8');
                                                ?>
                                                    <li>
                                                        <a href="service-details.php?id=<?php echo $service_id; ?>" class="dropdown-item">
                                                            <i class="fas fa-angle-right me-2"></i><?php echo $service_title; ?>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                            <?php
                                    } else {
                                        // If no services exist for this category, just show it as a standard link
                            ?>
                                        <li>
                                            <a href="service.php?cate=<?php echo $cate_id; ?>" class="dropdown-item" style="font-weight:600;">
                                                <i class="fas fa-folder-open me-2"></i><?php echo $cate_name; ?>
                                            </a>
                                        </li>
                            <?php
                                    }
                                } // end while categories
                            } else {
                            ?>
                                <li>
                                    <a class="dropdown-item disabled" href="javascript:;">
                                        <i class="fas fa-info-circle me-2"></i>No categories available
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </li>
                    </ul>
            </div>
        </div>
    </nav>
</section>

</div> <!-- end header-enhanced wrapper -->

<style>
/* ========== SCOPED HEADER STYLES (only within .header-enhanced) ========== */
.header-enhanced {
    font-family: 'Poppins', sans-serif;
}

/* Top Header Styles */
.header-enhanced .top_head {
    background: linear-gradient(135deg, #1e2b4f 0%, #2a3b6e 100%);
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.header-enhanced .cont_area a {
    color: #ffffff;
    font-size: 14px;
    margin-right: 25px;
    text-decoration: none;
    transition: color 0.3s ease;
    opacity: 0.9;
}

.header-enhanced .cont_area a:hover {
    color: #ffd700;
    opacity: 1;
}

.header-enhanced .cont_area i {
    margin-right: 8px;
    font-size: 14px;
}

/* Login Area Styles */
.header-enhanced .login_area ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

/* Cart Icon Styles */
.header-enhanced .cart-item {
    position: relative;
    margin-right: 15px;
}

.header-enhanced .cart-icon-link {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.header-enhanced .cart-icon-link:hover {
    background: #ffd700;
    color: #1e2b4f;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.header-enhanced .cart-icon-link i {
    font-size: 18px;
}

.header-enhanced .cart-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: 600;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(220,53,69,0.3);
    border: 2px solid #1e2b4f;
}

/* User Menu Styles */
.header-enhanced .user-menu .user-link {
    display: flex;
    align-items: center;
    color: #ffffff !important;
    padding: 8px 15px !important;
    background: rgba(255,255,255,0.1);
    border-radius: 40px;
    transition: all 0.3s ease;
}

.header-enhanced .user-menu .user-link:hover {
    background: rgba(255,255,255,0.2);
}

.header-enhanced .user-menu .user-link i {
    font-size: 18px;
    margin-right: 8px;
}

.header-enhanced .user-menu .user-link .user-name {
    font-size: 14px;
    font-weight: 500;
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Login Link Styles */
.header-enhanced .login-link {
    display: inline-flex !important;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #2a7fb9, #2f6bb3);
    color: #ffffff !important;
    padding: 8px 18px !important;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.header-enhanced .login-link:hover {
    background: #ffd700;
    color: #1e2b4f !important;
}

.header-enhanced .login-link i {
    margin-right: 8px;
    font-size: 14px;
}

/* Main Dropdown Menu Styles */
.header-enhanced .dropdown-menu {
    border: none;
    border-radius: 10px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    padding: 10px 0;
    margin-top: 0px;
    min-width: 260px;
    background: #ffffff;
    animation: fadeIn 0.2s ease-out;
    left:-90%!important;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

.header-enhanced .dropdown-menu-end {
    right: 0;
    left: auto;
}

.header-enhanced .dropdown-item {
    padding: 10px 20px;
    font-size: 14px;
    color: #333;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    position: relative;
}

.header-enhanced .dropdown-item i.fas {
    width: 20px;
    font-size: 14px;
    color: #1e2b4f;
    transition: all 0.2s ease;
}

.header-enhanced .dropdown-item:hover {
    background: linear-gradient(135deg, #f0f3ff 0%, #e6ecfe 100%);
    color: #1e2b4f;
    padding-left: 25px;
}

.header-enhanced .dropdown-item:hover i.fas {
    color: #ffd700;
}

.header-enhanced .dropdown-divider {
    margin: 8px 0;
    border-top: 1px solid #eaeef2;
}

/* ========== NESTED MULTI-LEVEL DROPDOWN ========== */
.header-enhanced .dropdown-submenu {
    position: relative;
}

/* Hide submenu by default */
.header-enhanced .dropdown-submenu .dropdown-menu {
    top: 0;
    left: -100% !important;
    margin: 0;                 /* Remove any gap */
    display: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

/* For right-aligned parent menus, open submenu to the left */
.header-enhanced .dropdown-menu-end .dropdown-submenu .dropdown-menu {
    left: auto;
    right: -50%;
    margin: 0;                 /* No gap on the right side either */
}
/* Arrow for submenu toggles */
.header-enhanced .dropdown-submenu > .dropdown-toggle::after {
    content: "\f105";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    font-size: 14px;
}

/* Desktop hover behavior */
@media (min-width: 992px) {
    /* Show main dropdown on hover */
    .header-enhanced .navbar-nav .dropdown:hover > .dropdown-menu {
        display: block;
    }
    
    /* Show submenu on hover */
    .header-enhanced .dropdown-submenu:hover > .dropdown-menu {
        display: block;
    }
}

/* Navigation Bar Styles */
.header-enhanced .head_nav {
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    position: relative;
    z-index: 1000;
}

.header-enhanced .navbar {
    padding: 15px 0;
}

.header-enhanced .navbar-brand img {
    max-height: 50px;
    width: auto;
}

.header-enhanced .navbar-nav .nav-link {
    color: #333 !important;
    font-size: 15px;
    font-weight: 500;
    padding: 8px 18px !important;
    transition: all 0.3s ease;
    border-radius: 6px;
}

.header-enhanced .navbar-nav .nav-link:hover {
    color: #1e2b4f !important;
    background: rgba(30,43,79,0.05);
}

.header-enhanced .navbar-nav .nav-link::after {
    display: none;
}

/* Primary Nav Link Dropdown Toggle Arrow */
.header-enhanced .nav-item.dropdown > .dropdown-toggle::after {
    display: inline-block;
    margin-left: 5px;
    vertical-align: middle;
    content: "\f107";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    border: none;
    font-size: 12px;
}

/* Responsive Styles */
@media (max-width: 991px) {
    .header-enhanced .navbar-collapse {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-top: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .header-enhanced .navbar-nav .nav-link {
        padding: 12px 15px !important;
        border-radius: 8px;
    }

    /* Stack submenus vertically on mobile */
    .header-enhanced .dropdown-submenu .dropdown-menu {
        position: static !important;
        float: none;
        width: auto;
        margin-top: 0;
        margin-left: 20px;
        background-color: transparent;
        border: none;
        box-shadow: none;
        display: none; /* controlled by JS */
    }

    .header-enhanced .dropdown-submenu .dropdown-menu.show {
        display: block;
    }

    .header-enhanced .dropdown-submenu > .dropdown-toggle::after {
        content: "\f107"; /* down arrow on mobile */
    }
}

@media (max-width: 768px) {
    .header-enhanced .top_head {
        text-align: center;
    }

    .header-enhanced .cont_area a {
        display: block;
        margin: 5px 0;
    }

    .header-enhanced .login_area {
        text-align: center;
        margin-top: 10px;
    }

    .header-enhanced .user-menu .user-link {
        padding: 6px 12px !important;
    }

    .header-enhanced .user-menu .user-link .user-name {
        max-width: 100px;
    }

    .header-enhanced .cart-icon-link {
        width: 36px;
        height: 36px;
    }
}

/* Sticky Header */
.header-enhanced #sticky-header {
    position: sticky;
    top: 0;
    z-index: 999;
    transition: all 0.3s ease;
}

.header-enhanced #sticky-header.sticky {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { transform: translateY(-100%); }
    to { transform: translateY(0); }
}

/* Badge for new items */
.header-enhanced .nav-item .badge {
    position: absolute;
    top: 0;
    right: 5px;
    background: #ffd700;
    color: #1e2b4f;
    font-size: 10px;
    padding: 2px 5px;
    border-radius: 10px;
}
</style>

<script>
// Function to update cart count in header (scoped to .header-enhanced)
(function() {
    function updateHeaderCartCount() {
        <?php if (isset($header_user) && $header_user === 'partner' && isset($_COOKIE['tax_partner_log'])): ?>
        var partnerId = "<?php echo $_COOKIE['tax_partner_log']; ?>";
        if (partnerId) {
            $.ajax({
                url: 'get_cart_count.php',
                type: 'GET',
                data: {
                    partner_id: partnerId,
                    timestamp: new Date().getTime()
                },
                dataType: 'json',
                cache: false,
                success: function(response) {
                    if (response.success) {
                        $('#headerCartCount').text(response.count).fadeIn();
                    }
                }
            });
        }
        <?php endif; ?>
    }

    $(document).ready(function() {
        // Update cart count
        updateHeaderCartCount();
        setInterval(updateHeaderCartCount, 30000);

        // Sticky header
        var header = $('#sticky-header');
        var headerOffset = header.offset().top;

        $(window).scroll(function() {
            if ($(window).scrollTop() > headerOffset) {
                header.addClass('sticky');
            } else {
                header.removeClass('sticky');
            }
        });

        // Handle mobile clicks for multi-level submenus (Bootstrap 5 compatible)
        $('.header-enhanced .dropdown-submenu > a').on('click', function(e) {
            if ($(window).width() < 992) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle current submenu
                var submenu = $(this).next('.dropdown-menu');
                submenu.toggleClass('show');
                
                // Close other submenus at same level
                $(this).closest('.dropdown-submenu').siblings().find('.dropdown-menu.show').removeClass('show');
            }
        });

        // Prevent parent dropdown from closing when clicking inside submenu on mobile
        $('.header-enhanced .dropdown-menu .dropdown-toggle').on('click', function(e) {
            if ($(window).width() < 992) {
                e.stopPropagation();
            }
        });

        // Close all submenus when clicking outside
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.header-enhanced .dropdown').length) {
                $('.header-enhanced .dropdown-menu').removeClass('show');
                $('.header-enhanced .dropdown-submenu .dropdown-menu').removeClass('show');
            }
        });
    });
})();
</script>