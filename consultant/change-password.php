<?php
session_start();
include("../db.php");

if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../ca-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];
$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current password hash
    $sql = $con->query("SELECT password FROM ca WHERE id='$ca_id'");
    $ca = $sql->fetch_assoc();
    
    // Verify current password
    if(!password_verify($current_password, $ca['password'])){
        $error = "Current password is incorrect";
    } elseif(strlen($new_password) < 6){
        $error = "New password must be at least 6 characters long";
    } elseif($new_password !== $confirm_password){
        $error = "New passwords do not match";
    } else {
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password
        if($con->query("UPDATE ca SET password='$hashed_password' WHERE id='$ca_id'")){
            $success = "Password changed successfully!";
        } else {
            $error = "Error updating password: " . $con->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password - CA Dashboard</title>
    <!-- Include your CSS files here -->
</head>
<body>
    <div class="container">
        <h1>Change Password</h1>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required minlength="6">
            </div>
            
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required minlength="6">
            </div>
            
            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</body>
</html>