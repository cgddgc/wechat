<?php
session_start();
$_SESSION['yourname']=$_POST["yourname"];
$_SESSION['oname']=$_POST["oname"];
header("Location:test2.php");
exit();
?>