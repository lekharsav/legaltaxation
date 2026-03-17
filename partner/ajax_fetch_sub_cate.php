<?php
include("../db.php");
$id = $_GET["id"];
$sql = $con->query("select * from sub_cate where child_cate='$id'");
echo'<option value="" selected disabled>Choose Sub Category</option>';
while($row = $sql->fetch_assoc()){
    echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
?>