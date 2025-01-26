<?php
session_start();
include '../db.php';

if(isset($_POST['uid'])){
    $uid = $_POST['uid'];
    $chatid = $_POST['chatid'];
    $inp = mysqli_real_escape_string($con,trim($_POST['input']));
    $out = mysqli_real_escape_string($con,trim($_POST['output']));
    $sql = "insert into chathistory(uid,chatid,input,output) 
        values($uid,$chatid,'$inp','$out')";
    $res = mysqli_query($con, $sql);
    if($res){
        $sql = "update chat set chatname = '$inp' where chatid = $chatid";
        $res1 = mysqli_query($con,$sql);
        echo "success";
    }
}

?>