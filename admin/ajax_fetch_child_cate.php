<?php
include("../db.php");
$id = $_GET["id"];
$sql = $con->query("select * from child_cate where cate='$id'");
echo'<option value="" selected disabled>Choose Child Category</option>';
while($row = $sql->fetch_assoc()){
    echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
?>