<?php
include("../db.php");
if(isset($_POST['submit'])){
   $vid = rand(100000,999999);
   $comp = $_POST['comp'];
   $adrs = $_POST['adrs'];
   $loca = $_POST['loca'];
   $gst = $_POST['gst'];
   $gst_ty = $_POST['gst_ty'];
   $person = $_POST['person'];
   $cont_1 = $_POST['cont_1'];
   $cont_2 = $_POST['cont_2'];
   $email = $_POST['email'];

   if($con->query("insert into vendor(vid,company,address,location,gst,gst_type,person,cont_1,cont_2,email) values('$vid','$comp','$adrs','$loca','$gst','$gst_ty','$person','$cont_1','$cont_2','$email')")===true){
      echo"<script>window.location='dashboard.php?src=vendor.php';</script>";
   }
}
if(isset($_POST['save'])){
   $id = $_POST['id'];
   $comp = $_POST['comp'];
   $adrs = $_POST['adrs'];
   $loca = $_POST['loca'];
   $gst = $_POST['gst'];
   $person = $_POST['person'];
   $cont_1 = $_POST['cont_1'];
   $cont_2 = $_POST['cont_2'];
   $email = $_POST['email'];

   if($con->query("update vendor set company='$comp',address='$adrs',location='$loca',gst='$gst',gst_type='".$_POST['gst_type']."',person='$person',cont_1='$cont_1',cont_2='$cont_2',email='$email' where id='$id'")===true){
      echo"<script>window.location='dashboard.php?src=vendor.php&id=$id';</script>";
   }else{
      echo"<script>alert('server error');window.location='dashboard.php?src=vendor.php&id=$id';</script>";
   }
}
if(isset($_GET['delete'])){
   $id = $_GET['id'];
   if($con->query("delete from vendor where id='$id'")===true){
      echo"<script>window.location='dashboard.php?src=vendor.php';</script>";
   }
}
?>