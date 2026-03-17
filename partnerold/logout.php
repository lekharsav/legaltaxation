<?php
// session_start();
// setcookie("tax_partner_log",'as',time()-60*60*24*30);
setcookie("tax_partner_log",$id,time()-60*60*24*30);
// unset($_SESSION["admin_log"]);
echo"<script>window.location='index.php';</script>";
?>