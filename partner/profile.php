<?php
session_start();
include("../db.php");

if(!isset($_SESSION['ca_logged_in']) || $_SESSION['ca_logged_in'] !== true){
    header("Location: ../partner-login.php");
    exit();
}

$ca_id = $_SESSION['ca_id'];
$sql = $con->query("SELECT * FROM ca WHERE id='$ca_id'");
$ca = $sql->fetch_assoc();

// Handle profile update
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $reg_no = mysqli_real_escape_string($con, $_POST['reg_no']);
    
    // Update query
    $update_sql = "UPDATE ca SET name='$name', cont='$contact', reg_no='$reg_no' WHERE id='$ca_id'";
    
    if($con->query($update_sql)){
        $_SESSION['ca_name'] = $name;
        $success = "Profile updated successfully!";
    } else {
        $error = "Error updating profile: " . $con->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile - CA Dashboard</title>
    <!-- Include your CSS files here -->
</head>
<body>
    <div class="container">
        <h1>My Profile</h1>
        
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>CA ID</label>
                <input type="text" class="form-control" value="<?php echo $ca['ca_id']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($ca['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" value="<?php echo $ca['email']; ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact" class="form-control" value="<?php echo $ca['cont']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Registration Number</label>
                <input type="text" name="reg_no" class="form-control" value="<?php echo $ca['reg_no']; ?>">
            </div>
            
            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>