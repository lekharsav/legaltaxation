<!-- ========== PROFESSIONAL HEADER (CLICK‑ONLY, VERTICAL DROPDOWN) ========== -->
<section class="top-head">
    <div class="container">
        <div class="row align-items-center g-0">
            <div class="col-md-6 col-8">
                <div class="contact-info">
                    <a href="mailto:info@legaltaxation.com"><i class="fa-regular fa-envelope"></i> info@legaltaxation.com</a>
                    <a href="tel:+91-7004407668"><i class="fa-regular fa-phone"></i> +91-7004407668</a>
                </div>
            </div>
            <div class="col-md-6 col-4">
                <div class="user-actions">
                    <ul class="d-flex align-items-center justify-content-end mb-0">
                        <?php
                        // --------------------------------------------------
                        // Authentication logic (unchanged)
                        // --------------------------------------------------
                        $header_user   = '';   
                        $header_name   = '';

                        if (!empty($_COOKIE['tax_customer_log'])) {
                            $cid  = $con->real_escape_string($_COOKIE['tax_customer_log']);
                            $crow = $con->query("SELECT name FROM customer WHERE id='$cid'");
                            if ($crow && $crow->num_rows > 0) {
                                $header_user = 'customer';
                                $header_name = $crow->fetch_assoc()['name'];
                            } else {
                                $past = time() - 3600;
                                foreach (['/', '/legalTaxation/', '/legalTaxation'] as $p)
                                    setcookie('tax_customer_log', '', $past, $p);
                                unset($_COOKIE['tax_customer_log']);
                            }
                        }

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

                        if ($header_user === '' && isset($_SESSION['ca_logged_in']) && $_SESSION['ca_logged_in'] === true) {
                            $header_user = 'ca';
                            $header_name = $_SESSION['ca_name'] ?? 'Consultant';
                        }
                        
                        if ($header_user === 'partner'):
                        ?>
                        <!-- Cart Icon (refined) -->
                        <li class="nav-item cart-item">
                            <a href="cart.php" class="cart-link" title="View Cart">
                                <i class="fa-solid fa-bag-shopping"></i>
                                <span class="cart-badge" id="headerCartCount">0</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="nav-item dropdown user-dropdown">
                            <?php if ($header_user === 'customer'): ?>
                                <a class="nav-link dropdown-toggle user-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-avatar"><i class="fa-regular fa-user"></i></div>
                                    <span class="user-name"><?php echo htmlspecialchars($header_name); ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="client/dashboard.php"><i class="fa-regular fa-gauge-high me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="client/profile.php"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>
                                    <li><a class="dropdown-item" href="client/applications.php"><i class="fa-regular fa-file-lines me-2"></i>My Applications</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-regular fa-arrow-right-from-bracket me-2"></i>Logout</a></li>
                                </ul>

                            <?php elseif ($header_user === 'partner'): ?>
                                <a class="nav-link dropdown-toggle user-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-avatar"><i class="fa-regular fa-user-tie"></i></div>
                                    <span class="user-name"><?php echo htmlspecialchars($header_name); ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="partner/dashboard.php"><i class="fa-regular fa-gauge-high me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="partner/clients.php"><i class="fa-regular fa-users me-2"></i>My Clients</a></li>
                                    <li><a class="dropdown-item" href="partner/earnings.php"><i class="fa-regular fa-indian-rupee-sign me-2"></i>Earnings</a></li>
                                    <li><a class="dropdown-item" href="partner/profile.php"><i class="fa-regular fa-user-gear me-2"></i>Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-regular fa-arrow-right-from-bracket me-2"></i>Logout</a></li>
                                </ul>

                            <?php elseif ($header_user === 'ca'): ?>
                                <a class="nav-link dropdown-toggle user-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-avatar"><i class="fa-regular fa-user"></i></div>
                                    <span class="user-name"><?php echo htmlspecialchars($header_name); ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="consultant/dashboard.php"><i class="fa-regular fa-gauge-high me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="consultant/appointments.php"><i class="fa-regular fa-calendar-check me-2"></i>Appointments</a></li>
                                    <li><a class="dropdown-item" href="consultant/clients.php"><i class="fa-regular fa-users me-2"></i>Clients</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-regular fa-arrow-right-from-bracket me-2"></i>Logout</a></li>
                                </ul>

                            <?php else: ?>
                                <a class="nav-link dropdown-toggle login-toggle" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-regular fa-lock"></i> Login
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                                    <li><a class="dropdown-item" href="user-login.php"><i class="fa-regular fa-user me-2"></i>Client Login</a></li>
                                    <li><a class="dropdown-item" href="partner-login.php"><i class="fa-regular fa-handshake me-2"></i>Partner Login</a></li>
                                    <li><a class="dropdown-item" href="ca-login.php"><i class="fa-regular fa-gavel me-2"></i>Consultant Login</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="customer-reg.php"><i class="fa-regular fa-user-plus me-2"></i>Register as Client</a></li>
                                    <li><a class="dropdown-item" href="partner-reg.php"><i class="fa-regular fa-user-plus me-2"></i>Become a Partner</a></li>
                                </ul>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Navigation -->
<section class="main-nav" id="sticky-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/LOGO.JPG" alt="Legal Taxation">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <?php
                    $header_qry = $con->query("select * from cate order by id asc");
                    while ($header_row = $header_qry->fetch_assoc()) {
                    ?>
                        <li class="nav-item dropdown mega-menu-item">
                            <a href="service.php?cate=<?= $header_row['id'] ?>" class="nav-link dropdown-toggle" id="navbarDropdown-<?= $header_row['id'] ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $header_row['name'] ?>
                            </a>
                            <div class="dropdown-menu mega-menu" aria-labelledby="navbarDropdown-<?= $header_row['id'] ?>">
                                <div class="container">
                                    <div class="row g-4">
                                        <?php
                                        $header_sql2 = $con->query("select * from service where cate='" . $header_row['id'] . "'");
                                        while ($header_row2 = $header_sql2->fetch_assoc()) {
                                        ?>
                                            <div class="col-lg-4 col-md-6">
                                                <a href="service-details.php?id=<?php echo $header_row2['id'] ?>" class="mega-menu-link">
                                                    <i class="fa-regular fa-circle-right"></i>
                                                    <span><?php echo $header_row2['title'] ?></span>
                                                </a>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</section>

<style>
/* ===== PROFESSIONAL HEADER CSS ===== */
:root {
    --primary-dark: #0A1A2F;
    --primary-medium: #1E3A5F;
    --accent-gold: #D4AF37;
    --accent-gold-light: #EAC784;
    --text-light: #F8FAFC;
    --text-dark: #1E293B;
    --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
    --transition: all 0.25s ease;
}

/* Top Header */
.top-head {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
    padding: 0.625rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.contact-info a {
    color: var(--text-light);
    font-size: 0.875rem;
    margin-right: 2rem;
    text-decoration: none;
    opacity: 0.9;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.contact-info a:hover {
    color: var(--accent-gold);
    opacity: 1;
}

.contact-info i {
    font-size: 1rem;
}

/* User Actions */
.user-actions ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Cart Icon */
.cart-item {
    margin-right: 0.25rem;
}

.cart-link {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    color: white;
    transition: var(--transition);
    text-decoration: none;
}

.cart-link:hover {
    background: var(--accent-gold);
    color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.cart-link i {
    font-size: 1.25rem;
}

.cart-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #dc3545;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 1.25rem;
    height: 1.25rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 0.25rem;
    border: 2px solid var(--primary-medium);
}

/* User Dropdown */
.user-dropdown .user-toggle,
.user-dropdown .login-toggle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem !important;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2.5rem;
    color: white !important;
    transition: var(--transition);
    text-decoration: none;
}

.user-dropdown .user-toggle:hover,
.user-dropdown .login-toggle:hover {
    background: rgba(255, 255, 255, 0.2);
}

.user-dropdown .login-toggle {
    background: rgba(255, 255, 255, 0.15);
}

.user-dropdown .login-toggle:hover {
    background: var(--accent-gold);
    color: var(--primary-dark) !important;
}

.user-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.user-name {
    font-weight: 500;
    font-size: 0.9375rem;
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ===== FIX: VERTICAL DROPDOWN ITEMS ===== */
/* Override any old styles that may cause horizontal layout */
.user-dropdown .dropdown-menu li {
    display: block !important;
    width: 100% !important;
    float: none !important;
    clear: both !important;
}

.user-dropdown .dropdown-menu .dropdown-item {
    display: flex !important;
    align-items: center;
    width: 100%;
    white-space: nowrap;
}

/* Ensure the dropdown itself is properly positioned */
.dropdown-menu {
    border: none;
    border-radius: 1rem;
    box-shadow: var(--shadow-md);
    padding: 0.5rem 0;
    margin-top: 0.75rem;
    min-width: 240px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    z-index: 1060;
}

.dropdown-item {
    padding: 0.625rem 1.25rem;
    font-size: 0.9375rem;
    color: var(--text-dark);
    transition: var(--transition);
}

.dropdown-item i {
    width: 1.5rem;
    color: var(--primary-medium);
    font-size: 1rem;
    transition: var(--transition);
}

.dropdown-item:hover {
    background: #F1F5F9;
    color: var(--primary-dark);
    padding-left: 1.75rem;
}

.dropdown-item:hover i {
    color: var(--accent-gold);
}

.dropdown-divider {
    margin: 0.5rem 0;
    border-color: #E2E8F0;
}

/* Main Navigation */
.main-nav {
    background: white;
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: 0;
    z-index: 1020;
    transition: var(--transition);
}

.main-nav.sticky {
    box-shadow: var(--shadow-md);
    backdrop-filter: blur(8px);
    background: rgba(255, 255, 255, 0.95);
}

.navbar {
    padding: 1rem 0;
}

.navbar-brand img {
    max-height: 48px;
    width: auto;
    transition: var(--transition);
}

.navbar-nav .nav-link {
    color: var(--text-dark) !important;
    font-weight: 500;
    font-size: 1rem;
    padding: 0.5rem 1.25rem !important;
    border-radius: 2rem;
    transition: var(--transition);
}

.navbar-nav .nav-link:hover {
    background: #F1F5F9;
    color: var(--primary-dark) !important;
}

.navbar-nav .nav-link::after {
    display: inline-block;
    margin-left: 0.375rem;
    vertical-align: middle;
    content: "\f107";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    border: none;
    font-size: 0.875rem;
    transition: transform 0.2s;
}

.navbar-nav .nav-link.show::after {
    transform: rotate(180deg);
}

/* Mega Menu */
.mega-menu-item {
    position: static !important;
}

.mega-menu {
    width: 100%;
    padding: 2rem !important;
    margin-top: 0.5rem !important;
    border-radius: 1rem;
    left: 0;
    right: 0;
}

.mega-menu .container {
    max-width: 1200px;
}

.mega-menu-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    color: var(--text-dark);
    text-decoration: none;
    transition: var(--transition);
    background: #F8FAFC;
    border: 1px solid transparent;
}

.mega-menu-link:hover {
    background: white;
    border-color: var(--accent-gold-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
    color: var(--primary-dark);
}

.mega-menu-link i {
    color: var(--accent-gold);
    font-size: 1.25rem;
}

.mega-menu-link span {
    font-weight: 500;
}

/* Responsive */
@media (max-width: 991px) {
    .navbar-collapse {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        margin-top: 1rem;
        box-shadow: var(--shadow-md);
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .navbar-nav .nav-link {
        padding: 0.75rem 1rem !important;
    }
    
    .mega-menu {
        padding: 1.5rem !important;
    }
    
    .mega-menu .col-md-6 {
        margin-bottom: 1rem;
    }
}

@media (max-width: 768px) {
    .top-head .row {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .contact-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .contact-info a {
        margin-right: 0;
    }
    
    .user-actions ul {
        justify-content: center;
    }
    
    .user-name {
        display: none;
    }
    
    .user-dropdown .user-toggle {
        padding: 0.5rem !important;
    }
    
    .cart-link {
        width: 2.25rem;
        height: 2.25rem;
    }
}

/* Touch-friendly for mobile */
@media (max-width: 991px) {
    .dropdown-menu {
        margin-top: 0;
        box-shadow: none;
        border-left: 3px solid var(--accent-gold);
    }
}
</style>

<script>
// Cart count update (unchanged logic)
function updateHeaderCartCount() {
    <?php if (isset($header_user) && $header_user === 'partner' && isset($_COOKIE['tax_partner_log'])): ?>
    var partnerId = "<?php echo $_COOKIE['tax_partner_log']; ?>";
    if (partnerId) {
        $.ajax({
            url: 'get_cart_count.php',
            type: 'GET',
            data: { partner_id: partnerId, timestamp: new Date().getTime() },
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

    // Sticky header with blur on scroll
    var header = $('#sticky-header');
    var headerOffset = header.offset().top;

    $(window).scroll(function() {
        if ($(window).scrollTop() > headerOffset) {
            header.addClass('sticky');
        } else {
            header.removeClass('sticky');
        }
    });

    // ===== FIX: REMOVED ALL HOVER CODE =====
    // Dropdowns now work only on click (Bootstrap default)
    // No hover handlers, no click prevention

    // Close dropdowns when clicking outside (optional, but good practice)
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.dropdown').length) {
            $('.dropdown-menu').removeClass('show');
            $('.dropdown-toggle').attr('aria-expanded', 'false');
        }
    });
});
</script>