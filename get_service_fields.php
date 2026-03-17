<?php
include('db.php');
header('Content-Type: application/json');

if(isset($_GET['service_id']) && !empty($_GET['service_id'])) {
    $service_id = intval($_GET['service_id']);
    
    $sql = $con->query("SELECT * FROM service_form_fields 
                        WHERE service_id = '$service_id' 
                        ORDER BY field_order ASC, id ASC");
    
    $fields = [];
    while($row = $sql->fetch_assoc()) {
        $fields[] = $row;
    }
    
    if(count($fields) > 0) {
        echo json_encode([
            'success' => true,
            'fields' => $fields
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No custom fields found for this service'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Service ID not provided'
    ]);
}
?>