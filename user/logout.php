<?php
session_start();
include 'db.php';


unset($_SESSION['uid']);

header("location: userlogin.php");
?>