<?php
session_start();

// Check if customer is logged in using cookie
if(!isset($_COOKIE['tax_customer_log']) || empty($_COOKIE['tax_customer_log'])){
    header("Location: ../user-login.php");
    exit();
}

$customer_id = $_COOKIE['tax_customer_log'];

// Include database connection
include("../db.php");

// Get customer details
$sql = $con->query("SELECT * FROM customer WHERE id='$customer_id' AND status='1'");
if(!$sql || $sql->num_rows == 0){
    setcookie("tax_customer_log", "", time() - 3600, "/");
    header("Location: ../user-login.php");
    exit();
}

$customer = $sql->fetch_assoc();

// Set customer variables for this page
$customer_name = $customer["name"];
$customer_email = $customer["email"];
$customer_contact = $customer["contact"];
$customer_image = $customer["image"] ?? '';
$created_date = $customer["created"];

// Get purchased services with details
$purchased_services = [];
$sql = $con->query("SELECT 
    a.id as application_id,
    a.sid as service_id,
    a.created as purchase_date,
    a.status as service_status,
    a.send_to as assigned_to,
    s.title as service_title,
    s.image as service_image,
    s.cate as category_id,
    s.m_price as market_price,
    s.o_price as paid_price,
    s.s_des as short_description,
    cat.name as category_name,
    ca.name as ca_name
    FROM apply a
    LEFT JOIN service s ON a.sid = s.id
    LEFT JOIN cate cat ON s.cate = cat.id
    LEFT JOIN ca ON a.send_to = ca.id
    WHERE a.cid = '$customer_id'
    ORDER BY a.created DESC");

$total_services = $sql->num_rows;
$pending_services = 0;
$completed_services = 0;
$total_spent = 0;

while($row = $sql->fetch_assoc()){
    $purchased_services[] = $row;
    if($row['service_status'] == '0'){
        $pending_services++;
    } elseif($row['service_status'] == '1'){
        $completed_services++;
    }
    $total_spent += $row['paid_price'];
}

// Get recent payments
$recent_payments = [];
$payment_sql = $con->query("SELECT * FROM razorpaybill 
    WHERE cid LIKE '%$customer_id%' 
    ORDER BY created_at DESC 
    LIMIT 5");

if($payment_sql){
    while($row = $payment_sql->fetch_assoc()){
        $recent_payments[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Services - Legal Taxation</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .service-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-bottom: 20px;
      transition: all 0.3s;
      overflow: hidden;
    }
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .service-image {
      height: 150px;
      overflow: hidden;
    }
    .service-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .service-body {
      padding: 15px;
    }
    .service-status {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 1;
    }
    .progress-sm {
      height: 8px;
    }
    .badge-custom {
      font-size: 0.75rem;
      padding: 3px 8px;
    }
    .action-buttons {
      margin-top: 10px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('navbar.php'); ?>
  <!-- /.navbar -->

  <!-- Sidebar -->
  <?php include('sidebar.php'); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
              <i class="fas fa-cogs mr-2"></i>My Services
              <small class="text-muted">(<?php echo $total_services; ?> total)</small>
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">My Services</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Statistics Cards -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $total_services; ?></h3>
                <p>Total Services</p>
              </div>
              <div class="icon">
                <i class="fas fa-cogs"></i>
              </div>
              <a href="#services-list" class="small-box-footer">View All <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $pending_services; ?></h3>
                <p>In Progress</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
              <a href="#pending" class="small-box-footer">View Pending <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $completed_services; ?></h3>
                <p>Completed</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <a href="#completed" class="small-box-footer">View Completed <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>₹<?php echo $total_spent; ?></h3>
                <p>Total Spent</p>
              </div>
              <div class="icon">
                <i class="fas fa-rupee-sign"></i>
              </div>
              <a href="my-payments.php" class="small-box-footer">View Payments <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Filter Options -->
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Filter by Status:</label>
                      <select class="form-control" id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="0">In Progress</option>
                        <option value="1">Completed</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Filter by Category:</label>
                      <select class="form-control" id="categoryFilter">
                        <option value="all">All Categories</option>
                        <?php
                        if(!empty($purchased_services)){
                            $categories = array_unique(array_column($purchased_services, 'category_name'));
                            foreach($categories as $category){
                                if($category) {
                                    echo "<option value='".htmlspecialchars($category)."'>".htmlspecialchars($category)."</option>";
                                }
                            }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Sort by:</label>
                      <select class="form-control" id="sortFilter">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="price_low">Price: Low to High</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>&nbsp;</label>
                      <button class="btn btn-primary btn-block" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Apply Filters
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Purchased Services List -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-shopping-bag mr-2"></i>My Purchased Services
                  <span class="badge badge-primary ml-2"><?php echo $total_services; ?></span>
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <?php if(empty($purchased_services)): ?>
                  <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4>No Services Purchased Yet</h4>
                    <p class="text-muted">You haven't purchased any services yet.</p>
                    <a href="../service.php" class="btn btn-primary btn-lg">
                      <i class="fas fa-plus-circle"></i> Browse Services
                    </a>
                    <p class="mt-3 text-muted">
                      <small>Once you purchase a service, it will appear here.</small>
                    </p>
                  </div>
                <?php else: ?>
                  <div class="row" id="services-list">
                    <?php foreach($purchased_services as $service): 
                      // Get form responses count
                      $form_count = 0;
                      $form_sql = $con->query("SELECT COUNT(*) as count FROM service_form_responses WHERE application_id='".$service['application_id']."'");
                      if($form_sql && $form_row = $form_sql->fetch_assoc()){
                        $form_count = $form_row['count'];
                      }
                      
                      // Get documents count
                      $doc_count = 0;
                      $doc_sql = $con->query("SELECT COUNT(*) as count FROM req_doc WHERE sid='".$service['application_id']."'");
                      if($doc_sql && $doc_row = $doc_sql->fetch_assoc()){
                        $doc_count = $doc_row['count'];
                      }
                    ?>
                      <div class="col-md-4 col-sm-6" 
                           data-status="<?php echo $service['service_status']; ?>"
                           data-category="<?php echo htmlspecialchars($service['category_name']); ?>"
                           data-price="<?php echo $service['paid_price']; ?>"
                           data-date="<?php echo $service['purchase_date']; ?>">
                        <div class="service-card">
                          <div class="service-image">
                            <?php if(!empty($service['service_image'])): ?>
                              <img src="../images/<?php echo $service['service_image']; ?>" 
                                   alt="<?php echo htmlspecialchars($service['service_title']); ?>"
                                   onerror="this.src='../images/default-service.jpg'">
                            <?php else: ?>
                              <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100%;">
                                <i class="fas fa-cog fa-3x text-muted"></i>
                              </div>
                            <?php endif; ?>
                            <div class="service-status">
                              <?php if($service['service_status'] == '0'): ?>
                                <span class="badge badge-warning badge-custom">In Progress</span>
                              <?php elseif($service['service_status'] == '1'): ?>
                                <span class="badge badge-success badge-custom">Completed</span>
                              <?php else: ?>
                                <span class="badge badge-secondary badge-custom"><?php echo $service['service_status']; ?></span>
                              <?php endif; ?>
                            </div>
                          </div>
                          <div class="service-body">
                            <h5 class="mb-1"><?php echo htmlspecialchars($service['service_title']); ?></h5>
                            <p class="text-muted small mb-2">
                              <i class="fas fa-tag"></i> <?php echo htmlspecialchars($service['category_name']); ?>
                            </p>
                            <p class="small mb-2">
                              <strong>Paid:</strong> ₹<?php echo $service['paid_price']; ?>
                              <small class="text-muted">
                                (Market: ₹<?php echo $service['market_price']; ?>)
                              </small>
                            </p>
                            <p class="small mb-3">
                              <i class="fas fa-calendar"></i> 
                              Purchased: <?php echo date('d M Y', strtotime($service['purchase_date'])); ?>
                            </p>
                            
                            <!-- Progress/Status Info -->
                            <div class="mb-3">
                              <div class="d-flex justify-content-between mb-1">
                                <small>Progress:</small>
                                <small>
                                  <?php if($service['service_status'] == '0'): ?>
                                    <span class="text-warning">Processing</span>
                                  <?php elseif($service['service_status'] == '1'): ?>
                                    <span class="text-success">Completed</span>
                                  <?php endif; ?>
                                </small>
                              </div>
                              <div class="progress progress-sm">
                                <div class="progress-bar bg-<?php echo $service['service_status'] == '1' ? 'success' : 'warning'; ?>" 
                                     style="width: <?php echo $service['service_status'] == '1' ? '100' : '50'; ?>%">
                                </div>
                              </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="d-flex justify-content-between mb-3">
                              <span class="badge badge-info">
                                <i class="fas fa-file-alt"></i> Forms: <?php echo $form_count; ?>
                              </span>
                              <span class="badge badge-warning">
                                <i class="fas fa-file-upload"></i> Docs: <?php echo $doc_count; ?>
                              </span>
                              <span class="badge badge-secondary">
                                ID: #<?php echo $service['application_id']; ?>
                              </span>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="action-buttons">
                              <a href="view-service.php?application_id=<?php echo $service['application_id']; ?>" 
                                 class="btn btn-sm btn-info btn-block">
                                <i class="fas fa-eye"></i> View Details
                              </a>
                              <?php if($form_count > 0): ?>
                              <a href="view-form.php?application_id=<?php echo $service['application_id']; ?>" 
                                 class="btn btn-sm btn-primary btn-block mt-1">
                                <i class="fas fa-file-alt"></i> View Form
                              </a>
                              <?php endif; ?>
                              <?php if($doc_count > 0): ?>
                              <a href="my-documents.php?service=<?php echo $service['application_id']; ?>" 
                                 class="btn btn-sm btn-success btn-block mt-1">
                                <i class="fas fa-download"></i> Documents
                              </a>
                              <?php endif; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-6">
                    <small class="text-muted">
                      <i class="fas fa-info-circle"></i> 
                      Showing <?php echo $total_services; ?> service(s)
                      <?php if($pending_services > 0): ?>
                        | <span class="text-warning"><?php echo $pending_services; ?> in progress</span>
                      <?php endif; ?>
                    </small>
                  </div>
                  <div class="col-md-6 text-right">
                    <?php if(!empty($purchased_services)): ?>
                    <div class="btn-group">
                      <button type="button" class="btn btn-default btn-sm" onclick="printServices()">
                        <i class="fas fa-print"></i> Print
                      </button>
                      <a href="../service.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Buy More
                      </a>
                    </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Payments -->
        <?php if(!empty($recent_payments)): ?>
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-credit-card mr-2"></i>Recent Payments
                </h3>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Payment ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Payment Method</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($recent_payments as $payment): ?>
                      <tr>
                        <td><?php echo $payment['order_id']; ?></td>
                        <td>₹<?php echo $payment['pay_amount']; ?></td>
                        <td>
                          <?php if($payment['payment_status'] == 'Success'): ?>
                            <span class="badge badge-success">Success</span>
                          <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                          <?php endif; ?>
                        </td>
                        <td><?php echo date('d M Y', strtotime($payment['created_at'])); ?></td>
                        <td><?php echo ucfirst($payment['payment_option'] ?? 'N/A'); ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer">
                <a href="my-payments.php" class="btn btn-sm btn-primary">
                  <i class="fas fa-list"></i> View All Payments
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div><!-- /.container-fluid -->
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="../index.php">Legal Taxation</a></strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    console.log('My Services page loaded');
});

function applyFilters() {
    var status = $('#statusFilter').val();
    var category = $('#categoryFilter').val();
    var sort = $('#sortFilter').val();
    
    // Get all service cards
    var cards = $('.service-card').parent();
    var visibleCards = [];
    
    cards.each(function() {
        var show = true;
        var card = $(this);
        var cardStatus = card.data('status');
        var cardCategory = card.data('category');
        var cardPrice = parseFloat(card.data('price'));
        var cardDate = card.data('date');
        
        // Status filter
        if(status !== 'all' && cardStatus != status) {
            show = false;
        }
        
        // Category filter
        if(category !== 'all' && cardCategory !== category) {
            show = false;
        }
        
        if(show) {
            card.show();
            visibleCards.push({
                element: card,
                price: cardPrice,
                date: cardDate
            });
        } else {
            card.hide();
        }
    });
    
    // Apply sorting
    sortCards(visibleCards, sort);
}

function sortCards(cards, sortType) {
    var container = $('#services-list');
    
    // Detach all visible cards
    cards.forEach(function(card) {
        card.element.detach();
    });
    
    // Sort based on sort type
    cards.sort(function(a, b) {
        switch(sortType) {
            case 'newest':
                return new Date(b.date) - new Date(a.date);
            case 'oldest':
                return new Date(a.date) - new Date(b.date);
            case 'price_high':
                return b.price - a.price;
            case 'price_low':
                return a.price - b.price;
            default:
                return 0;
        }
    });
    
    // Re-append sorted cards
    cards.forEach(function(card) {
        container.append(card.element);
    });
}

function printServices() {
    var originalContent = document.body.innerHTML;
    var printContent = document.getElementById('services-list').innerHTML;
    
    document.body.innerHTML = `
        <html>
        <head>
            <title>My Services - <?php echo htmlspecialchars($customer_name); ?></title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .service-card { 
                    border: 1px solid #ddd; 
                    border-radius: 5px; 
                    padding: 15px; 
                    margin-bottom: 15px; 
                    page-break-inside: avoid;
                }
                .print-header { 
                    text-align: center; 
                    margin-bottom: 30px; 
                    border-bottom: 2px solid #333; 
                    padding-bottom: 10px; 
                }
                .print-footer { 
                    margin-top: 30px; 
                    text-align: center; 
                    font-size: 12px; 
                    color: #666; 
                }
                .badge {
                    padding: 3px 8px;
                    border-radius: 3px;
                    font-size: 12px;
                }
                .badge-warning { background-color: #ffc107; color: black; }
                .badge-success { background-color: #28a745; color: white; }
                .badge-secondary { background-color: #6c757d; color: white; }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h2>My Purchased Services</h2>
                <h3>Customer: <?php echo htmlspecialchars($customer_name); ?> (ID: #<?php echo $customer_id; ?>)</h3>
                <p>Generated on: <?php echo date('d M Y, h:i A'); ?></p>
                <p>Total Services: <?php echo $total_services; ?> | 
                   In Progress: <?php echo $pending_services; ?> | 
                   Completed: <?php echo $completed_services; ?> | 
                   Total Spent: ₹<?php echo $total_spent; ?></p>
            </div>
            
            ${printContent}
            
            <div class="print-footer">
                <p>Generated by Legal Taxation Customer Panel</p>
                <p>Page generated on: <?php echo date('d M Y, h:i A'); ?></p>
            </div>
        </body>
        </html>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}
</script>
</body>
</html>