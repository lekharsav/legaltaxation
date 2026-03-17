<?php
// get_notification_count.php
session_start();

if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    echo json_encode(['success' => false, 'count' => 0]);
    exit();
}

$ca_id = $_SESSION['ca_id'];

include("../db.php");

$count = 0;
$sql = $con->query("SELECT COUNT(*) as count FROM apply WHERE send_to='$ca_id' AND status='0'");
if($sql && $row = $sql->fetch_assoc()){
    $count = $row['count'];
}

echo json_encode(['success' => true, 'count' => $count]);
?>