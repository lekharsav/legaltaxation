<?php
if(isset($_POST["profile_update"])){
	$image = $_FILES["file"]["tmp_name"];
	$fname = rand(1000,99999).".".pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
	$name = $_POST["name"];
	$cont = $_POST["cont"];
	$email = $_POST["email"];
	$pass = $_POST["pass"];
	if($image){
		if($con->query("update admin set image='$fname',name='$name',contact='$cont',email='$email' where id='$aid'")===true){
			move_uploaded_file($image,'assets/images/'.$fname);
			echo"<script>window.location='dashboard.php?src=profile.php';</script>";
		}
	}else{
		if($con->query("update admin set name='$name',contact='$cont',email='$email' where id='$aid'")===true){
			echo"<script>window.location='dashboard.php?src=profile.php';</script>";
		}
	}
}
?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="fa fa-user"></i>
    </span> Profile
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span><a href="dashboard.php">Dashboard</a> / Profile
      </li>
    </ul>
  </nav>
</div>
<div class="row">
<div class="col-md-8 mb-3">
  <div class="card">
    <div class="card-header bg-dark">
      <h4 class="text-white card-title">Profile Details</h4>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
          <label class="form-label" for="profile-settings-name">Name</label>
          <input type="text" class="form-control form-control-lg" id="profile-settings-name" name="name" value="<?php echo $admin_name ?>" required>
        </div>
        <div class="mb-4">
          <label class="form-label" for="profile-settings-username">Contact</label>
          <input type="number" class="form-control form-control-lg" id="profile-settings-username" name="cont" value="<?php echo $admin_cont ?>" required>
        </div>
        <div class="mb-4">
          <label class="form-label" for="profile-settings-email">Email Address</label>
          <input type="email" class="form-control form-control-lg" id="profile-settings-email" name="email" value="<?php echo $admin_email ?>" required>
        </div>
        <div class="mb-4">
          <label class="form-label" for="profile-settings-password">Password</label>
          <input type="password" class="form-control form-control-lg" id="profile-settings-password" name="pass" value="<?php echo $admin_pass ?>" required>
        </div>
        <div class="row mb-4">
          <div class="col-md-10 col-xl-6">
            <div class="push">
              <img class="img-avatar" src="assets/images/<?=$admin_image?>" id="profile_image" alt="">
            </div>
            <div class="mb-4">
              <label class="form-label" for="profile-settings-avatar">Choose new avatar</label>
              <input class="form-control" type="file" id="profile-settings-avatar" name="file" onchange="document.getElementById('profile_image').src=window.URL.createObjectURL(this.files[0])">
            </div>
          </div>
        </div>
        <div class="mb-4">
          <button type="submit" class="btn btn-primary" name="profile_update">Update</button>
        </div>
      </form>
    </div>
  </div>
  
</div> 
</div>