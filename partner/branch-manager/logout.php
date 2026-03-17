<?php
// session_start();
setcookie("bm_log",'as',time()-60*60*24*30);
// unset($_SESSION["admin_log"]);
echo"<script>window.location='index.php';</script>";
?>