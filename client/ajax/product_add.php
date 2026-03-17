<?php
include("../db.php");
if(isset($_GET['delete'])){
   $id = $_GET['id'];
   if($con->query("delete from product where id='$id'")===true){
      echo"<script>window.location='dashboard.php?src=product.php';</script>";
   }
}
if(isset($_POST['submit'])){
   $prod = $_POST['prod'];
   $hsn = $_POST['hsn'];
   $des = $_POST['des'];
   if($con->query("insert into product(name,category,hsn,per,s_price,dis,d_price,des) values('$prod','".$_POST['cate']."','$hsn','".$_POST['per']."','".$_POST['s_price']."','".$_POST['dis']."','".$_POST['d_price']."','$des')")===true){
      echo"<script>window.location='dashboard.php?src=product.php';</script>";
   }else{
      echo"<script>alert('Server Error!!');window.location='dashboard.php?src=product.php';</script>";
   }
}
if(isset($_POST['save'])){
   $id = $_POST['id'];
   $prod = $_POST['prod'];
   $hsn = $_POST['hsn'];
   $des = $_POST['des'];
   if($con->query("update product set name='$prod',category='".$_POST['category']."',hsn='$hsn',per='".$_POST['per']."',s_price='".$_POST['s_price']."',dis='".$_POST['dis']."',d_price='".$_POST['d_price']."',des='$des' where id='$id'")===true){
      echo"<script>window.location='dashboard.php?src=product.php';</script>";
   }else{
      echo"<script>alert('Server Error!!');window.location='dashboard.php?src=product.php';</script>";
   }
}
?>