<?php
session_start();
include 'db.php';
include 'module/codeformatter.php';

if ($_SESSION["uid"]) {
    $uid = $_SESSION["uid"];
    $sql = "select * from user where uid = $uid";
    $res = mysqli_query($con, $sql);
    if ($res) {
        if (mysqli_num_rows($res) > 0)
            $data = mysqli_fetch_assoc($res);
    }
    if(!isset($_GET["chat"])){
        $sql2 = "select * from chat where uid = $uid order by chatid desc limit 1";
        $res2 = mysqli_query($con, $sql2);
        if ($res2) {
            if (mysqli_num_rows($res2) > 0){
                $chatdata = mysqli_fetch_assoc($res2);
                header("location: userhome.php?chat=$chatdata[chatid]");
            }
            else{
                $sql2 = "insert into chat(chatname,uid) values('New Chat',$uid)";
                mysqli_query($con,$sql2);
                header("location: userhome.php");
            }  
        }
    }
    else{
        $sql = "select * from chat where chatid = $_GET[chat] and uid = $uid";
        $res = mysqli_query($con,$sql);
        if($res){
            if (mysqli_num_rows($res) > 0)
                $chatdata = mysqli_fetch_assoc($res);
            else
                header("location: userhome.php");
        }
    }
} else {
    header("location: userlogin.php");
}
?>

<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - <?php if (isset($chatdata['chatname'])) echo $chatdata['chatname']; ?></title>
    <link rel="stylesheet" href="../CSS/comman.css">
    <link rel="stylesheet" href="../CSS/userhome.css">
</head>

<body>
    <nav>
        <button onclick="openDrawer()" class="drawmenubtn">menu</button>
        <label class="appname">CHATBOT</label>
        <input hidden type="text" id="userid" value="<?php echo $_SESSION["uid"]; ?>">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="nav-profile-chip" onclick="location.href='editprofile.php'">
                <img id="profileimg" class="profileimg" src="../<?php if (isset($data['image'])) echo $data['image'];
                                                else echo "image/profileimg/profiledef.png"; ?>" width="25px" height="25px">
                <label class="accname"><?php if (isset($data['name'])) echo $data['name'];
                                            else echo "Unknown User"; ?></label>
            </div>
            <a class="logoutbtn" href="logout.php?uid=<?php echo $uid; ?>">Logout</a>
        </div>
    </nav>
    <div class="mainbody">
        <div id="sidebarmain" class="sidebarmain">
        <div class="sidebar">
            <div style="display: flex;justify-content:space-between;align-items:center">
                <b style="color:white">Chat History</b>
                <a href="javascript:newChat()" class="newchatbtn">+</a>
            </div>
            <div id="chatlist" class='chatlist'>
                <?php
                $sql = "select * from chat where uid = $uid order by chatid desc";
                $res = mysqli_query($con,$sql);
                if($res){
                    while($chathist = mysqli_fetch_assoc($res)){
                        $style = "";
                        if($chathist["chatid"] == $_GET["chat"]){
                            $style = "style='background-color: #ffffff;'";
                        }
                        $date = strtotime($chathist["date"]);
                        $newdate = date('d-m-Y',$date);
                        $time = strtotime($chathist["time"]);
                        $newtime = date("g:i A",$time);
                        echo "<div class='chathistory' $style onclick='location.href=\"userhome.php?chat=$chathist[chatid]\"'>
                            <a class='chatremoveicon' href='javascript:delchat($chathist[chatid])'>&#9940;</a> 
                            <p class='query'>$chathist[chatname]</p>
                            <div class='chatdatetime'>
                                <p class='chatdate'>$newdate</p>
                                <p class='chattime'>$newtime</p>
                            </div>
                        </div>";
                    }
                }
                ?>
            </div>
            </div>
        </div>
        <div class="chatbot">
            <div id="chatbody" class="chats">
                <?php
                $sql = "select * from chathistory where uid = $uid and chatid = $_GET[chat] order by chatid";
                $res = mysqli_query($con,$sql);
                if($res){
                    while($chatmsg = mysqli_fetch_assoc($res)){
                        $dt = date_create($chatmsg["datetime"]);
                        $d = date_format($dt,"j-n-Y g:i A");
                        echo "<div class='userinput'>
                            <div class='user-msgbubble'>
                                <div class='msg'>$chatmsg[input]</div>
                                <div class='msgdt'>$d</div>
                            </div>
                            <img class='profileimg' src='../$data[image]' width='35px' height='35px'>
                        </div>";
                        $botmsg = CodeFormat($chatmsg["output"]);
                        
                        echo "<div class='botoutput'>
                            <img class='profileimg' src='../image/profileimg/profiledef.png' width='35px' height='35px'>
                            <div class='bot-msgbubble'>
                                <div class='msg'>$botmsg</div>
                                <div class='msgdt'>$d</div>
                            </div>
                        </div>";
                    }
                }
                ?>
            </div>
            <div class="input-section">
                <textarea id="input" class="inputbox"></textarea>
                <input type="button" class="sendbtn" onclick="send()" value="Send">
            </div>
        </div>
    </div>
    <script>
        var chatdata = "";
        var currid = 0;
        var input = document.getElementById("input");
        var uid = document.getElementById("userid");
        var proimg = document.getElementById("profileimg");
        var chatbody = document.getElementById("chatbody");
        var chatlist = document.getElementById("chatlist");
    
    
        setTimeout(() => {
            chatbody.scroll({top: chatbody.scrollHeight,behavior: "smooth"});
        }, 1000);
    
    
    
        const send = () => {
            printUserMsg(input.value.trim())
            let xhr1 = new XMLHttpRequest();
            xhr1.open("GET", "http://127.0.0.1:5000/hello/?input="+input.value.trim(), true);
            xhr1.setRequestHeader("Content-Type","application/json; charset=utf-8")
            xhr1.onload = () =>{
                if(xhr1.readyState === XMLHttpRequest.DONE){
                    if(xhr1.status === 200){
                        let data = JSON.parse(xhr1.response)
                        let output = data.message
                        console.log(output)
                        printBotMsg(output)
                        storeindatabase(input.value.trim(),output)
                    }
                }
            }
            xhr1.send();
        }
    
        function storeindatabase(inp,out){
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "module/sendChatHistory.php", true);
            xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded")
            xhr.onload = () =>{
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        console.log(xhr.response)
                    }
                }
            }
            xhr.send("chatid="+"<?php echo $_GET["chat"]; ?>"+"&uid="+uid.value+"&input="+inp+"&output="+out);
        }
    
        function printUserMsg(msg){
            var date = new Date();
            let day = date.getDate();
            let month = date.getMonth();
            let year = date.getFullYear();
            let time = date.toLocaleString('en-US',{hour:'numeric',minute:'numeric',hour12: true})
            chatbody.innerHTML += "<div class='userinput'><div class='user-msgbubble'><div class='msg'>"+msg+"</div><div class='msgdt'>"+day+"-"+month+"-"+year+" "+time+"</div></div><img class='profileimg' src='../<?php echo $data["image"]?>' width='35px' height='35px'></div>";
        }
        function makeFormatOutput(msg){
            msg = msg.replace(/</g,"&lt;")
            msg = msg.replace(/>/g,"&gt;")
            msg = msg.replace(/\\t/g,"&nbsp;&nbsp;&nbsp;&nbsp;")
            msg = msg.replace(/&lt;CODESTART&gt;/g,"<divclass='code'>")
            //msg = msg.rep
            msg = msg.replace(/&lt;CODEEND&gt;/g,",,CODEEND,,")
            msg = msg.replace(/\\n/g,"<br>")
            return msg
        }
        function printBotMsg(output){
            var date = new Date();
            let day = date.getDate();
            let month = date.getMonth();
            let year = date.getFullYear();
            let time = date.toLocaleString('en-US',{hour:'numeric',minute:'numeric',hour12: true})
            var msg = makeFormatOutput(output)
            var words = msg.split(" ")
            chatbody.innerHTML += '<div class="botoutput"><img class="profileimg" src="../image/profileimg/profiledef.png" width="35px" height="35px"><div class="bot-msgbubble"> <div class="msg" id="botmsg'+currid+'"></div><div class="msgdt">'+day+"-"+month+"-"+year+" "+time+'</div></div></div>';
            
            codeid = 0;
            iscode = false;
            var botmsg = document.getElementById('botmsg'+currid)
            currid++;
            const interval = setInterval(() => {
                words[0] = words[0].replace(/divclass/g,"div class")
                if (words[0].includes("<div class='code'>")){
                    console.log("code start")
                    iscode = true;
                    words[0] = words[0].replace(/<div class='code'>/g,"<div id='code"+currid.toString()+codeid.toString()+"' class='code'>")
                    words[0] = codeformat(words[0])
                    botmsg.innerHTML += words[0] + " ";
                    words = words.slice(1);
                }
                if(iscode){
                    codeelmt = document.getElementById("code"+currid.toString()+codeid.toString())
                    if(words[0].includes(",,CODEEND,,")){
                        codeid++;
                        iscode=false;
                        words[0] = words[0].replace(/,,CODEEND,,/g,"")
                        console.log("code finished")
                    }
                    words[0] = codeformat(words[0])
                    codeelmt.innerHTML += words[0] + " ";
                    words = words.slice(1);
                }
                else{
                    botmsg.innerHTML += words[0] + " ";
                    words = words.slice(1);
                }
                chatbody.scroll({top: chatbody.scrollHeight,behavior: "smooth"});
    
                if(!words.length){
                    clearInterval(interval);
                }
            },100)
        }
    
        function newChat()
        {
            var date = new Date();
            let day = date.getDate();
            let month = date.getMonth();
            let year = date.getFullYear();
            let time = date.toLocaleString('en-US',{hour:'numeric',minute:'numeric',hour12: true})
            //chatlist.innerHTML += '<div class="chathistory"><p class="query">New Chat</p><div class="chatdatetime"><p class="chatdate">'+day+'-'+month+'-'+year+'</p><p class="chattime">'+time+'</p></div></div>'
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "module/createNewChat.php", true);
            xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded")
            xhr.onload = () =>{
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        if(xhr.response == "success"){
                            location.href = "userhome.php";
                        }
                    }
                }
            }
            xhr.send("uid="+uid.value);
        }
    
        function delchat(delchatid){
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "module/deleteChat.php", true);
            xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded")
            xhr.onload = () =>{
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        
                    }
                }
            }
            xhr.send("uid="+uid.value+"&chatid="+<?php echo $_GET["chat"] ?>);
        }
        var prevchatlist = "";
        setInterval(()=> {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "module/getChatlist.php", true);
            xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded")
            xhr.onload = () =>{
                if(xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        if(prevchatlist != xhr.response){
                            prevchatlist = xhr.response;
                            chatlist.innerHTML = xhr.response;
                        }
                    }
                }
            }
            xhr.send("uid="+uid.value+"&chatid="+<?php echo $_GET["chat"] ?>);
        },500);
    
        isCodePY = false;
        isHTML = false;
        function codeformat(codesnip){
            temp = codesnip;
            if(codesnip.includes("&lt;CODELN-PY&gt;")){
                isCodePY = true;
                isHTML = false;
                //console.log("Its a python code");
            }
            if(codesnip.includes("&lt;CODELN-HTML&gt;")){
                isCodePY = false;
                isHTML = true;
                //console.log("Its a html code");
            }
            //console.log(codesnip+" "+isCodePY.toString()+" "+isHTML.toString());
            if(isCodePY){
                //console.log("in codepy part");
                codesnip = codesnip.replace(/&lt;CODELN-PY&gt;/gi,"<div class='code-lang-box'>Python<button>Copy</button></div>");
                codesnip = codesnip.replace(/print/gi,"<span style='color:royalblue;'>print</span>");
                codesnip = codesnip.replace(/def/gi,"<span style='color:royalblue;'>def</span>");
                codesnip = codesnip.replace(/for/gi,"<span style='color:violet;'>for</span>");
                codesnip = codesnip.replace(/if/gi,"<span style='color:violet;'>if</span>");
                codesnip = codesnip.replace(/else/gi,"<span style='color:violet;'>else</span>");
                codesnip = codesnip.replace(/elif/gi,"<span style='color:violet;'>elif</span>");
                codesnip = codesnip.replace(/&lt;COMMA&gt;/gi,",");
            }
            if(isHTML){
                //console.log("in html part");
                codesnip = codesnip.replace(/&lt;CODELN-HTML&gt;/gi,"");
                codesnip = codesnip.replace(/&lt;html&gt;/gi,"<span style='color:gray;'>&lt;</span><span style='color:royalblue;'>html</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;\/html&gt;/gi,"<span style='color:gray;'>&lt;/</span><span style='color:royalblue;'>html</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;head&gt;/gi,"<span style='color:gray;'>&lt;</span><span style='color:royalblue;'>head</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;\/head&gt;/gi,"<span style='color:gray;'>&lt;/</span><span style='color:royalblue;'>head</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;title&gt;/gi,"<span style='color:gray;'>&lt;</span><span style='color:royalblue;'>title</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;\/title&gt;/gi,"<span style='color:gray;'>&lt;/</span><span style='color:royalblue;'>title</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;body&gt;/gi,"<span style='color:gray;'>&lt;</span><span style='color:royalblue;'>body</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;\/body&gt;/gi,"<span style='color:gray;'>&lt;/</span><span style='color:royalblue;'>body</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;p&gt;/gi,"<span style='color:gray;'>&lt;</span><span style='color:royalblue;'>body</span><span style='color:gray;'>&gt;</span>");
                codesnip = codesnip.replace(/&lt;\/p&gt;/gi,"<span style='color:gray;'>&lt;/</span><span style='color:royalblue;'>p</span><span style='color:gray;'>&gt;</span>");
            }
            //console.log(codesnip);
            return codesnip;
        }
    
        isDrawOpened = false;
        function openDrawer(){
            if(isDrawOpened == false)
                isDrawOpened = true;
            else
                isDrawOpened = false;
            if(isDrawOpened){
                document.getElementById("sidebarmain").setAttribute("class","sidebarmain dopened")
            }
            else{
                document.getElementById("sidebarmain").setAttribute("class","sidebarmain")
            }
            
        }
    </script>
</body>
</html>