<aside class="main-sidebar sidebar-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
      <img src="../images/logo.webp" alt="AdminLTE Logo" width="220">
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">ADMIN</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                DASHBOARD
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="customers.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                CUSTOMERS
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
 <li class="nav-item">
            <a href="catagory.php" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
              <p>
                CATAGORY
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                SERVICES
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">7</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_service.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Services</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="all_service.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Services</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="manage_service_forms.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Form Fields</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- <li class="nav-item">
            <a href="plan.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                PLANS
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li> -->


          <!-- Inside the nav menu in sidebar.php, add these items: -->

<li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-user-tie"></i>
    <p>
      CA MANAGEMENT
      <i class="fas fa-angle-left right"></i>
      <span class="badge badge-info right"><?php 
        $ca_count = $con->query("SELECT COUNT(*) as total FROM ca")->fetch_assoc()['total'];
        echo $ca_count;
      ?></span>
    </p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="add_ca.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add CA</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="all_ca.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>View All CA</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="ca_designation.php" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>CA Designations</p>
      </a>
    </li>
  </ul>
</li>

          <!-- LOGOUT BUTTON - ADDED AT THE BOTTOM -->
          <li class="nav-item">
            <a href="#" class="nav-link text-danger" id="logoutLink">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                LOGOUT
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to logout from the admin panel?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Logout Script -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.getElementById('logoutLink');
    if (logoutLink) {
      logoutLink.addEventListener('click', function(e) {
        e.preventDefault();
        $('#logoutModal').modal('show');
      });
    }
  });
  </script>