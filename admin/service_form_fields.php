<?php
include("../db.php");
$aid = $_COOKIE["tax_admin_log"];
if(!$aid){
  echo"<script>window.location='index.php';</script>";
}

// Get service ID
$service_id = $_GET['service_id'] ?? 0;
if(!$service_id){
    echo "<script>alert('Please select a service first!'); window.location='manage_service_forms.php';</script>";
    exit();
}

// Get service details
$service = [];
$sql = $con->query("SELECT s.*, c.name as category_name 
                    FROM service s 
                    LEFT JOIN cate c ON s.cate = c.id 
                    WHERE s.id='$service_id'");
if($row = $sql->fetch_assoc()){
    $service = $row;
} else {
    echo "<script>alert('Service not found!'); window.location='manage_service_forms.php';</script>";
    exit();
}

// Handle form submission for adding/updating fields
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['add_field'])){
        $field_name = mysqli_real_escape_string($con, $_POST['field_name']);
        $field_type = mysqli_real_escape_string($con, $_POST['field_type']);
        $field_label = mysqli_real_escape_string($con, $_POST['field_label']);
        $placeholder = mysqli_real_escape_string($con, $_POST['field_placeholder']);
        $is_required = isset($_POST['is_required']) ? 1 : 0;
        $field_options = mysqli_real_escape_string($con, $_POST['field_options']);
        $field_order = (int)$_POST['field_order'];
        $validation_rules = mysqli_real_escape_string($con, $_POST['validation_rules']);
        $help_text = mysqli_real_escape_string($con, $_POST['help_text']);
        
        // Check if field name already exists for this service
        $check_sql = $con->query("SELECT id FROM service_form_fields WHERE service_id='$service_id' AND field_name='$field_name'");
        if($check_sql->num_rows > 0){
            echo "<script>alert('Field name already exists for this service!');</script>";
        } else {
            $sql = "INSERT INTO service_form_fields 
                    (service_id, field_name, field_type, field_label, field_placeholder, 
                     is_required, field_options, field_order, validation_rules, help_text) 
                    VALUES ('$service_id', '$field_name', '$field_type', '$field_label', 
                            '$placeholder', '$is_required', '$field_options', '$field_order', 
                            '$validation_rules', '$help_text')";
            
            if($con->query($sql)){
                echo "<script>
                        toastr.success('Field added successfully!');
                        setTimeout(function(){ window.location.reload(); }, 1500);
                      </script>";
            }
        }
    }
    
    if(isset($_POST['update_field'])){
        $field_id = $_POST['field_id'];
        $field_label = mysqli_real_escape_string($con, $_POST['field_label']);
        $placeholder = mysqli_real_escape_string($con, $_POST['field_placeholder']);
        $is_required = isset($_POST['is_required']) ? 1 : 0;
        $field_options = mysqli_real_escape_string($con, $_POST['field_options']);
        $field_order = (int)$_POST['field_order'];
        $validation_rules = mysqli_real_escape_string($con, $_POST['validation_rules']);
        $help_text = mysqli_real_escape_string($con, $_POST['help_text']);
        
        $sql = "UPDATE service_form_fields SET 
                field_label='$field_label', 
                field_placeholder='$placeholder', 
                is_required='$is_required', 
                field_options='$field_options', 
                field_order='$field_order',
                validation_rules='$validation_rules',
                help_text='$help_text'
                WHERE id='$field_id' AND service_id='$service_id'";
        
        if($con->query($sql)){
            echo "<script>
                    toastr.success('Field updated successfully!');
                    setTimeout(function(){ window.location.reload(); }, 1500);
                  </script>";
        }
    }
    
    // Handle bulk actions
    if(isset($_POST['bulk_action'])){
        $action = $_POST['bulk_action'];
        $selected_fields = $_POST['selected_fields'] ?? [];
        
        if(!empty($selected_fields)){
            $ids = implode(',', array_map('intval', $selected_fields));
            
            if($action == 'delete'){
                $con->query("DELETE FROM service_form_fields WHERE id IN ($ids) AND service_id='$service_id'");
                echo "<script>alert('Selected fields deleted!'); window.location.reload();</script>";
            } elseif($action == 'enable_required'){
                $con->query("UPDATE service_form_fields SET is_required=1 WHERE id IN ($ids) AND service_id='$service_id'");
                echo "<script>alert('Fields marked as required!'); window.location.reload();</script>";
            } elseif($action == 'disable_required'){
                $con->query("UPDATE service_form_fields SET is_required=0 WHERE id IN ($ids) AND service_id='$service_id'");
                echo "<script>alert('Fields marked as optional!'); window.location.reload();</script>";
            }
        }
    }
}

// Handle delete
if(isset($_GET['delete_field'])){
    $field_id = $_GET['delete_field'];
    if($con->query("DELETE FROM service_form_fields WHERE id='$field_id' AND service_id='$service_id'")){
        echo "<script>alert('Field deleted successfully!'); window.location.reload();</script>";
    }
}

// Handle reorder
if(isset($_POST['reorder'])){
    $orders = $_POST['order'];
    foreach($orders as $field_id => $order){
        $field_id = (int)$field_id;
        $order = (int)$order;
        $con->query("UPDATE service_form_fields SET field_order='$order' WHERE id='$field_id' AND service_id='$service_id'");
    }
    echo "<script>alert('Order updated!'); window.location.reload();</script>";
}

// Get all form fields for this service
$form_fields = [];
$sql = $con->query("SELECT * FROM service_form_fields WHERE service_id='$service_id' ORDER BY field_order ASC, id ASC");
while($row = $sql->fetch_assoc()){
    $form_fields[] = $row;
}

// Get total fields count
$total_fields = count($form_fields);
$required_fields = array_filter($form_fields, function($field) {
    return $field['is_required'] == 1;
});
$required_count = count($required_fields);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Form Fields - <?php echo $service['title']; ?> - Legal Taxation</title>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .sortable-handle { cursor: move; }
    .field-preview { border-left: 4px solid #007bff; }
    .required-field { border-left: 4px solid #dc3545; }
    .optional-field { border-left: 4px solid #28a745; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include("navbar.php"); ?>
  <?php include("sidebar.php"); ?>

  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
              <i class="fas fa-list-alt mr-2"></i>
              Form Fields for: <strong><?php echo htmlspecialchars($service['title']); ?></strong>
              <small class="text-muted">(ID: <?php echo $service['id']; ?>)</small>
            </h1>
            <small class="text-muted">Category: <?php echo $service['category_name']; ?></small>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item"><a href="manage_service_forms.php">Service Forms</a></li>
              <li class="breadcrumb-item active"><?php echo substr($service['title'], 0, 20); ?>...</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Service Info Card -->
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3 text-center">
                    <?php if(!empty($service['image'])): ?>
                    <img src="../images/<?php echo $service['image']; ?>" alt="Service Image" class="img-fluid rounded" style="max-height: 150px;">
                    <?php endif; ?>
                  </div>
                  <div class="col-md-9">
                    <h4><?php echo htmlspecialchars($service['title']); ?></h4>
                    <p class="text-muted"><?php echo substr(strip_tags($service['s_des']), 0, 200); ?>...</p>
                    <div class="row">
                      <div class="col-md-4">
                        <small><strong>Market Price:</strong> ₹<?php echo $service['m_price']; ?></small>
                      </div>
                      <div class="col-md-4">
                        <small><strong>Our Price:</strong> ₹<?php echo $service['o_price']; ?></small>
                      </div>
                      <div class="col-md-4">
                        <small><strong>Status:</strong> 
                          <span class="badge badge-<?php echo $service['status'] == '1' ? 'success' : 'danger'; ?>">
                            <?php echo $service['status'] == '1' ? 'Active' : 'Inactive'; ?>
                          </span>
                        </small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <a href="manage_service_forms.php" class="btn btn-secondary">
                  <i class="fa fa-arrow-left"></i> Back to Services
                </a>
                <a href="edit_service.php?id=<?php echo $service['id']; ?>" class="btn btn-info">
                  <i class="fa fa-edit"></i> Edit Service
                </a>
                <a href="all_service.php" class="btn btn-default">
                  <i class="fa fa-list"></i> All Services
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <!-- Add New Field Form -->
          <div class="col-md-4">
            <div class="card">
              <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fa fa-plus-circle"></i> Add New Field</h3>
              </div>
              <form method="POST" id="addFieldForm">
                <div class="card-body">
                  <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                  
                  <div class="form-group">
                    <label>Field Name <small class="text-danger">*</small></label>
                    <input type="text" class="form-control" name="field_name" required 
                           placeholder="e.g., pan_number, financial_year" pattern="[a-z0-9_]+">
                    <small class="form-text text-muted">
                      Use lowercase with underscores. This is the database field name.
                    </small>
                  </div>
                  
                  <div class="form-group">
                    <label>Field Type <small class="text-danger">*</small></label>
                    <select class="form-control" name="field_type" id="field_type" required onchange="toggleOptionsField()">
                      <option value="text">Text</option>
                      <option value="number">Number</option>
                      <option value="email">Email</option>
                      <option value="tel">Phone</option>
                      <option value="date">Date</option>
                      <option value="textarea">Text Area</option>
                      <option value="select">Dropdown</option>
                      <option value="checkbox">Checkbox</option>
                      <option value="radio">Radio Button</option>
                      <option value="file">File Upload</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label>Field Label <small class="text-danger">*</small></label>
                    <input type="text" class="form-control" name="field_label" required 
                           placeholder="e.g., PAN Number, Financial Year">
                    <small class="form-text text-muted">This is what users will see.</small>
                  </div>
                  
                  <div class="form-group">
                    <label>Placeholder Text</label>
                    <input type="text" class="form-control" name="field_placeholder" 
                           placeholder="e.g., Enter your PAN number">
                  </div>
                  
                  <div class="form-group" id="options_field" style="display:none;">
                    <label>Options <small class="text-danger" id="options_required" style="display:none;">*</small></label>
                    <textarea class="form-control" name="field_options" 
                              placeholder="Enter options separated by commas or new lines"></textarea>
                    <small class="form-text text-muted">
                      For dropdown/radio: Option1,Option2,Option3<br>
                      For checkboxes: value1:Label 1,value2:Label 2
                    </small>
                  </div>
                  
                  <div class="form-group">
                    <label>Validation Rules</label>
                    <select class="form-control" name="validation_rules">
                      <option value="">None</option>
                      <option value="pan">PAN Number</option>
                      <option value="aadhaar">Aadhaar Number</option>
                      <option value="gst">GST Number</option>
                      <option value="mobile">Mobile Number</option>
                      <option value="pincode">PIN Code</option>
                      <option value="numeric">Numeric Only</option>
                      <option value="alpha">Alphabet Only</option>
                      <option value="alphanumeric">Alphanumeric</option>
                      <option value="min5">Minimum 5 characters</option>
                      <option value="max10">Maximum 10 characters</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label>Help Text</label>
                    <textarea class="form-control" name="help_text" rows="2" 
                              placeholder="Help text shown below the field"></textarea>
                  </div>
                  
                  <div class="form-group">
                    <label>Display Order</label>
                    <input type="number" class="form-control" name="field_order" value="<?php echo $total_fields; ?>">
                  </div>
                  
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_required" value="1" id="is_required">
                    <label class="form-check-label" for="is_required">
                      <strong>Required Field</strong>
                    </label>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" name="add_field" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Field
                  </button>
                  <button type="reset" class="btn btn-default">Reset</button>
                </div>
              </form>
            </div>
            
            <!-- Field Statistics -->
            <div class="card mt-3">
              <div class="card-header bg-info">
                <h3 class="card-title"><i class="fa fa-chart-pie"></i> Field Statistics</h3>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-6">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-primary"><i class="fa fa-field"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Total Fields</span>
                        <span class="info-box-number"><?php echo $total_fields; ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="info-box bg-light">
                      <span class="info-box-icon bg-danger"><i class="fa fa-exclamation-circle"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Required</span>
                        <span class="info-box-number"><?php echo $required_count; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="progress-group mt-3">
                  Required Fields
                  <span class="float-right"><b><?php echo $required_count; ?></b>/<?php echo $total_fields; ?></span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-danger" style="width: <?php echo $total_fields > 0 ? ($required_count/$total_fields*100) : 0; ?>%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- List of Fields -->
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-list"></i> Form Fields (<?php echo $total_fields; ?>)
                  <form method="POST" class="d-inline float-right" id="reorderForm">
                    <input type="hidden" name="reorder" value="1">
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="fa fa-save"></i> Save Order
                    </button>
                  </form>
                </h3>
              </div>
              
              <?php if(empty($form_fields)): ?>
                <div class="card-body text-center py-5">
                  <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                  <h4>No form fields added yet</h4>
                  <p class="text-muted">Add form fields using the form on the left</p>
                </div>
              <?php else: ?>
                <!-- Bulk Actions -->
                <div class="card-header bg-light">
                  <form method="POST" id="bulkForm" onsubmit="return confirmBulkAction()">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input" id="selectAll">
                          <label class="form-check-label" for="selectAll">Select All</label>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <select class="form-control form-control-sm" name="bulk_action" required>
                          <option value="">Bulk Actions</option>
                          <option value="delete">Delete Selected</option>
                          <option value="enable_required">Mark as Required</option>
                          <option value="disable_required">Mark as Optional</option>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-danger">Apply</button>
                      </div>
                    </div>
                  </form>
                </div>
                
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th width="5%">#</th>
                          <th width="30%">Field Details</th>
                          <th width="15%">Type</th>
                          <th width="10%">Required</th>
                          <th width="10%">Order</th>
                          <th width="30%">Actions</th>
                        </tr>
                      </thead>
                      <tbody id="sortableFields">
                        <?php foreach($form_fields as $index => $field): 
                          $field_class = $field['is_required'] ? 'required-field' : 'optional-field';
                        ?>
                          <tr class="<?php echo $field_class; ?> sortable-row" data-id="<?php echo $field['id']; ?>">
                            <td>
                              <input type="checkbox" name="selected_fields[]" value="<?php echo $field['id']; ?>" class="field-checkbox">
                            </td>
                            <td>
                              <div class="d-flex">
                                <div class="mr-3 sortable-handle" title="Drag to reorder">
                                  <i class="fa fa-arrows-alt text-muted"></i>
                                </div>
                                <div>
                                  <strong><?php echo htmlspecialchars($field['field_label']); ?></strong><br>
                                  <small class="text-muted">
                                    <code><?php echo $field['field_name']; ?></code>
                                    <?php if($field['field_placeholder']): ?>
                                      | <i>"<?php echo htmlspecialchars($field['field_placeholder']); ?>"</i>
                                    <?php endif; ?>
                                  </small>
                                  <?php if($field['help_text']): ?>
                                    <br><small class="text-info"><?php echo htmlspecialchars($field['help_text']); ?></small>
                                  <?php endif; ?>
                                  <?php if($field['field_options']): ?>
                                    <br><small class="text-success">
                                      Options: <?php echo substr(htmlspecialchars($field['field_options']), 0, 50); ?>
                                    </small>
                                  <?php endif; ?>
                                </div>
                              </div>
                            </td>
                            <td>
                              <span class="badge badge-secondary">
                                <?php echo strtoupper($field['field_type']); ?>
                              </span>
                              <?php if($field['validation_rules']): ?>
                                <br><small class="text-warning"><?php echo $field['validation_rules']; ?></small>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if($field['is_required']): ?>
                                <span class="badge badge-danger">Required</span>
                              <?php else: ?>
                                <span class="badge badge-success">Optional</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <input type="number" name="order[<?php echo $field['id']; ?>]" 
                                     value="<?php echo $field['field_order']; ?>" 
                                     class="form-control form-control-sm order-input" 
                                     style="width: 60px;">
                            </td>
                            <td>
                              <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-info edit-field" 
                                        data-field='<?php echo htmlspecialchars(json_encode($field)); ?>'>
                                  <i class="fa fa-edit"></i>
                                </button>
                                <a href="?service_id=<?php echo $service_id; ?>&delete_field=<?php echo $field['id']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Delete this field?\n\nField: <?php echo addslashes($field['field_label']); ?>')">
                                  <i class="fa fa-trash"></i>
                                </a>
                                <button type="button" class="btn btn-warning preview-field" 
                                        data-field='<?php echo htmlspecialchars(json_encode($field)); ?>'>
                                  <i class="fa fa-eye"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                
                <div class="card-footer">
                  <small class="text-muted">
                    <i class="fa fa-info-circle"></i> Drag handles (<i class="fa fa-arrows-alt"></i>) to reorder fields
                  </small>
                </div>
              <?php endif; ?>
            </div>
            
            <!-- Preview Card -->
            <div class="card mt-3" id="previewCard" style="display:none;">
              <div class="card-header bg-warning">
                <h3 class="card-title"><i class="fa fa-eye"></i> Field Preview</h3>
                <button type="button" class="close" onclick="$('#previewCard').hide()">&times;</button>
              </div>
              <div class="card-body" id="previewContent">
                <!-- Preview content will be loaded here -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<!-- Edit Field Modal -->
<div class="modal fade" id="editFieldModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" id="editFieldForm">
        <div class="modal-header bg-primary">
          <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Field</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" id="editFieldContent">
          <!-- Content loaded via JavaScript -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="update_field" class="btn btn-primary">Update Field</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI for sortable -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function(){
    // Initialize sortable
    $("#sortableFields").sortable({
        handle: ".sortable-handle",
        update: function(event, ui) {
            updateOrderNumbers();
        }
    }).disableSelection();
    
    // Update order numbers when dragged
    function updateOrderNumbers() {
        $('.sortable-row').each(function(index) {
            $(this).find('.order-input').val(index);
        });
    }
    
    // Select all checkbox
    $('#selectAll').click(function() {
        $('.field-checkbox').prop('checked', $(this).prop('checked'));
    });
    
    // Toggle options field based on field type
    window.toggleOptionsField = function() {
        var type = $('#field_type').val();
        var optionsField = $('#options_field');
        var optionsRequired = $('#options_required');
        
        if(type === 'select' || type === 'radio' || type === 'checkbox') {
            optionsField.show();
            optionsRequired.show();
        } else {
            optionsField.hide();
            optionsRequired.hide();
        }
    }
    
    // Edit field
    $('.edit-field').click(function(e){
        e.preventDefault();
        var field = JSON.parse($(this).data('field'));
        
        var html = `
            <input type="hidden" name="field_id" value="${field.id}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Field Name</label>
                        <input type="text" class="form-control" value="${field.field_name}" readonly>
                        <small class="text-muted">Field name cannot be changed</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Field Type</label>
                        <input type="text" class="form-control" value="${field.field_type.toUpperCase()}" readonly>
                        <small class="text-muted">Field type cannot be changed</small>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Field Label <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="field_label" value="${field.field_label.replace(/"/g, '&quot;')}" required>
            </div>
            
            <div class="form-group">
                <label>Placeholder Text</label>
                <input type="text" class="form-control" name="field_placeholder" value="${(field.field_placeholder || '').replace(/"/g, '&quot;')}">
            </div>`;
        
        if(field.field_type === 'select' || field.field_type === 'radio' || field.field_type === 'checkbox'){
            html += `
                <div class="form-group">
                    <label>Options</label>
                    <textarea class="form-control" name="field_options" rows="3">${field.field_options || ''}</textarea>
                </div>`;
        }
        
        html += `
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Validation Rules</label>
                        <select class="form-control" name="validation_rules">
                            <option value="">None</option>
                            <option value="pan" ${field.validation_rules === 'pan' ? 'selected' : ''}>PAN Number</option>
                            <option value="aadhaar" ${field.validation_rules === 'aadhaar' ? 'selected' : ''}>Aadhaar Number</option>
                            <option value="gst" ${field.validation_rules === 'gst' ? 'selected' : ''}>GST Number</option>
                            <option value="mobile" ${field.validation_rules === 'mobile' ? 'selected' : ''}>Mobile Number</option>
                            <option value="pincode" ${field.validation_rules === 'pincode' ? 'selected' : ''}>PIN Code</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Display Order</label>
                        <input type="number" class="form-control" name="field_order" value="${field.field_order}">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Help Text</label>
                <textarea class="form-control" name="help_text" rows="2">${field.help_text || ''}</textarea>
            </div>
            
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="is_required" value="1" id="edit_is_required" ${field.is_required == '1' ? 'checked' : ''}>
                <label class="form-check-label" for="edit_is_required">
                    <strong>Required Field</strong>
                </label>
            </div>`;
        
        $('#editFieldContent').html(html);
        $('#editFieldModal').modal('show');
    });
    
    // Preview field
    $('.preview-field').click(function(e){
        e.preventDefault();
        var field = JSON.parse($(this).data('field'));
        
        var html = `
            <div class="form-group">
                <label>${field.field_label} ${field.is_required == '1' ? '<span class="text-danger">*</span>' : ''}</label>`;
        
        switch(field.field_type) {
            case 'textarea':
                html += `<textarea class="form-control" placeholder="${field.field_placeholder || ''}"></textarea>`;
                break;
            case 'select':
                html += `<select class="form-control"><option value="">${field.field_placeholder || 'Select an option'}</option>`;
                if(field.field_options) {
                    var options = field.field_options.split(',');
                    options.forEach(function(option){
                        html += `<option value="${option.trim()}">${option.trim()}</option>`;
                    });
                }
                html += `</select>`;
                break;
            case 'radio':
                if(field.field_options) {
                    var options = field.field_options.split(',');
                    options.forEach(function(option){
                        html += `
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="preview_radio">
                            <label class="form-check-label">${option.trim()}</label>
                        </div>`;
                    });
                }
                break;
            case 'checkbox':
                if(field.field_options) {
                    var options = field.field_options.split(',');
                    options.forEach(function(option){
                        var parts = option.split(':');
                        var value = parts.length > 1 ? parts[0] : option.trim();
                        var label = parts.length > 1 ? parts[1] : option.trim();
                        html += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${value}">
                            <label class="form-check-label">${label}</label>
                        </div>`;
                    });
                }
                break;
            case 'file':
                html += `<input type="file" class="form-control-file">`;
                break;
            default:
                html += `<input type="${field.field_type}" class="form-control" placeholder="${field.field_placeholder || ''}">`;
        }
        
        if(field.help_text) {
            html += `<small class="form-text text-muted">${field.help_text}</small>`;
        }
        
        html += `</div>`;
        
        $('#previewContent').html(html);
        $('#previewCard').show();
    });
});

function confirmBulkAction() {
    var selectedCount = $('.field-checkbox:checked').length;
    if(selectedCount === 0) {
        toastr.warning('Please select at least one field');
        return false;
    }
    var action = $('select[name="bulk_action"]').val();
    if(!action) {
        toastr.warning('Please select a bulk action');
        return false;
    }
    return confirm(`Are you sure you want to ${action.replace('_', ' ')} ${selectedCount} field(s)?`);
}

// Initialize toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};
</script>
</body>
</html>