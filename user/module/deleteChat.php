<?php
session_start();
include '../db.php';

if(isset($_POST['uid']) && isset($_POST['chatid'])){
    $uid = $_POST['uid'];
    $chatid = $_POST['chatid'];
    
    $sql = "delete from chathistory where chatid = $chatid and uid = $uid";
    $res = mysqli_query($con, $sql);
    if($res){
        $sql = "delete from chat where chatid = $chatid";
        $res1 = mysqli_query($con,$sql);
        echo "success";
    }
}

?>