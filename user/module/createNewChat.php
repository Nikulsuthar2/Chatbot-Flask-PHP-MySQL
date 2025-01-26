<?php
session_start();
include '../db.php';

if(isset($_POST['uid'])){
    $uid = $_POST['uid'];
    $sql = "insert into chat(uid,chatname) 
        values($uid,'New Chat')";
    $res = mysqli_query($con, $sql);
    if($res){
        echo "success";
    }
}

?>