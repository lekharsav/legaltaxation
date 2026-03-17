<?php
// logout.php - Complete version
// Start session if you're using sessions
// session_start();

// Clear all admin cookies with same path as login
setcookie("tax_admin_log", "", time() - 3600, "/");
setcookie("tax_admin_email", "", time() - 3600, "/"); 
setcookie("tax_admin_pass", "", time() - 3600, "/");

// Also try to clear cookies without path (for backward compatibility)
setcookie("tax_admin_log", "", time() - 3600);
setcookie("tax_admin_email", "", time() - 3600);
setcookie("tax_admin_pass", "", time() - 3600);

// If you're using sessions
// session_destroy();
// unset($_SESSION["admin_log"]);

// Debug: Check if cookies are being cleared
error_log("Logout attempt - Cookies cleared");

// Redirect to login page with cache control headers
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Location: index.php");
exit();
?>