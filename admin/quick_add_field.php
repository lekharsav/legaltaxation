<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}

$service_id = $_GET['service_id'] ?? 0;
if(!$service_id){
    header("Location: manage_service_forms.php");
    exit();
}

// Handle quick add
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quick_add'])){
    $field_name = 'field_' . time() . '_' . rand(100,999);
    $field_label = mysqli_real_escape_string($con, $_POST['field_label']);
    $field_type = 'text';
    $is_required = 1;
    
    $sql = "INSERT INTO service_form_fields (service_id, field_name, field_type, field_label, is_required, field_order) 
            VALUES ('$service_id', '$field_name', '$field_type', '$field_label', '$is_required', 
                   (SELECT COALESCE(MAX(field_order), 0) + 1 FROM service_form_fields WHERE service_id='$service_id'))";
    
    if($con->query($sql)){
        echo "<script>
                alert('Field added successfully!');
                window.location='service_form_fields.php?service_id=$service_id';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quick Add Field</title>
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Quick Add Field</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Field Label</label>
                            <input type="text" class="form-control" name="field_label" required 
                                   placeholder="e.g., Full Name, Email Address">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="quick_add" class="btn btn-primary">Add Field</button>
                            <a href="service_form_fields.php?service_id=<?php echo $service_id; ?>" 
                               class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>