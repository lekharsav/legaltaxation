<?php
include('db.php');
header('Content-Type: application/json');

// Prefer explicit GET param, but fall back to cookie if missing
$partner_id = null;
if (isset($_GET['partner_id']) && $_GET['partner_id'] !== '') {
    $partner_id = $_GET['partner_id'];
} elseif (isset($_COOKIE['tax_partner_log']) && $_COOKIE['tax_partner_log'] !== '') {
    $partner_id = $_COOKIE['tax_partner_log'];
}

if ($partner_id === null) {
    echo json_encode(['success' => false, 'error' => 'Invalid partner id', 'count' => 0]);
    exit;
}

// Use real escaping and treat partner_id as string to match how it's stored
$partner_id_esc = $con->real_escape_string($partner_id);
$count_sql = $con->query("SELECT COUNT(*) as count FROM partner_cart WHERE partner_id = '" . $partner_id_esc . "'");
if ($count_sql) {
    $row = $count_sql->fetch_assoc();
    $count = intval($row['count']);
    echo json_encode(['success' => true, 'count' => $count]);
} else {
    echo json_encode(['success' => false, 'error' => 'DB error', 'count' => 0]);
}
