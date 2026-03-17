<?php
include("db.php");
$sql = $con->query("select * from followup_history");
while($row = $sql->fetch_assoc()){
	$sql1 = $con->query("select * from client where cid='".$row['cid']."'");
	if($row1 = $sql1->fetch_assoc()){
		if($con->query("update followup_history set executive='".$row1['uploaded_by']."' where cid='".$row['id']."'") === true){
			echo "yes";
		}else{
			echo "no";
		}
		
	}





	
}

?>