<?php
session_start();

// Get return parameter for custom redirect
$return = isset($_GET['return']) ? $_GET['return'] : '';

// ---------------------------------------------------------------
// Helper: nuke a cookie regardless of how it was originally set.
// Tries every common path variant so the browser actually deletes it.
// ---------------------------------------------------------------
function clearCookie(string $name): void {
    $past = time() - 3600;                       // 1 hour ago
    $paths = ['/', '/legalTaxation/', '/legalTaxation'];

    foreach ($paths as $path) {
        setcookie($name, '',   $past, $path);              // no flags
        setcookie($name, '',   $past, $path, false, true); // httponly
        setcookie($name, '',   $past, $path, true,  true); // secure + httponly
    }

    // Also kill it in the current request so later code in THIS script
    // doesn't accidentally see the stale value.
    unset($_COOKIE[$name]);
}

// ---------------------------------------------------------------
// 1. CUSTOMER logout (cookie-based)
// ---------------------------------------------------------------
if (isset($_COOKIE['tax_customer_log'])) {
    clearCookie('tax_customer_log');

    session_destroy();

    if ($return === 'index') {
        header("Location: index.php?logout=1");
    } else {
        header("Location: user-login.php?logout=1");
    }
    exit();
}

// ---------------------------------------------------------------
// 2. PARTNER logout (cookie-based)
// ---------------------------------------------------------------
if (isset($_COOKIE['tax_partner_log'])) {
    clearCookie('tax_partner_log');

    session_destroy();

    if ($return === 'index') {
        header("Location: index.php?logout=1");
    } else {
        header("Location: partner-login.php?logout=1");
    }
    exit();
}

// ---------------------------------------------------------------
// 3. CONSULTANT (CA) logout (session-based)
// ---------------------------------------------------------------
if (isset($_SESSION['ca_logged_in']) && $_SESSION['ca_logged_in'] === true) {
    unset($_SESSION['ca_id']);
    unset($_SESSION['ca_name']);
    unset($_SESSION['ca_email']);
    unset($_SESSION['ca_logged_in']);

    session_destroy();

    clearCookie('ca_remember');   // clear remember-me cookie if present

    if ($return === 'index') {
        header("Location: index.php?logout=1");
    } else {
        header("Location: ca-login.php?logout=1");
    }
    exit();
}

// ---------------------------------------------------------------
// 4. ADMIN logout
// ---------------------------------------------------------------
if (isset($_SESSION['admin_log'])) {
    unset($_SESSION['admin_log']);
    session_destroy();

    header("Location: admin/index.php?logout=1");
    exit();
}

// ---------------------------------------------------------------
// 5. Fallback – no active session found; still nuke everything
//    just in case and send the user home.
// ---------------------------------------------------------------
clearCookie('tax_customer_log');
clearCookie('tax_partner_log');
clearCookie('ca_remember');
session_destroy();

header("Location: index.php?logout=1");
exit();
?>