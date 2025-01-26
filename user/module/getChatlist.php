<?php
session_start();
include '../db.php';

$output = "";
if(isset($_POST['uid'])){
    $uid = $_POST['uid'];
    $chatid = $_POST['chatid'];
    $sql = "select * from chat where uid = $uid order by chatid desc";
    $res = mysqli_query($con,$sql);
    if($res){
        while($chathist = mysqli_fetch_assoc($res)){
            $style = "";
            if($chathist["chatid"] == $chatid){
                $style = "style='background-color: #ffffff;'";
            }
            $date = strtotime($chathist["date"]);
            $newdate = date('d-m-Y',$date);
            $time = strtotime($chathist["time"]);
            $newtime = date("g:i A",$time);
            $output .= "<div class='chathistory' $style onclick='location.href=\"userhome.php?chat=$chathist[chatid]\"'>
            <a class='chatremoveicon' href='javascript:delchat($chathist[chatid])'>&#9940;</a>    
            <p class='query'>$chathist[chatname]</p>
                
                <div class='chatdatetime'>
                    <p class='chatdate'>$newdate</p>
                    <p class='chattime'>$newtime</p>
                </div>
            </div>";
        }
    }
    echo $output;
}

?>