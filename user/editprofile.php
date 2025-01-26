<?php
session_start();
include 'db.php';

if($_SESSION["uid"]){
    $uid = $_SESSION["uid"];
    $sql = "select * from user where uid = $uid";
    $res = mysqli_query($con, $sql);
    if($res){
        $data = mysqli_fetch_assoc($res);
    }
}
else
{
    header("location: userlogin.php");
}
?>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - <?php echo $_SESSION["uname"];?></title>
    <link rel="stylesheet" href="../CSS/comman.css">
    <link rel="stylesheet" href="../CSS/userhome.css">
</head>
<body>
    <form action="module/updateprofile.php" method="POST" class="chataccbox" enctype="multipart/form-data" style="min-width:min-content">
        <nav>
            <div style="display:flex;align-items:center;gap:20px;">
                <a class="newchatbtn" href="userhome.php">&#11013</a>
                <h1 style="color:white">Edit Profile</h1>
            </div>
            
        </nav>
        <div class="mainbody" style="flex-direction:column;padding:20px;gap:20px;align-items:flex-start;">
            <div style="display:flex;flex-direction:column;">
                <label for="profile">
                    <img id="proimg" class="profileimg" src="../<?php if(isset($data['image'])) echo $data['image']; 
                    else echo "image/profileimg/profiledef.png" ?>" 
                    width="150px" height="150px">
                </label>
                <input id="profile" hidden type="file" accept="image/png,image/jpeg" name="profileimg"
                <?php if(!isset($data['image'])) echo "disabled";?>>
            </div>
            <div style="display:flex;gap:10px;">
                <label>Name</label>
                <input id="txtname" type="text" class="inputbox" name="uname" style="width:max-content"
                value="<?php if(isset($data['name'])) echo $data['name']; else echo "Unknown User"?>" 
                placeholder="Enter your name" required <?php if(!isset($data['name'])) echo "disabled";?>>
            </div>
            <div style="display:flex;gap:10px;">
                <label>Email</label>
                <input id="txtname" type="text" class="inputbox" name="uname" style="width:min-content"
                value="<?php if(isset($data['email'])) echo $data['email'];?>" 
                placeholder="Enter your email" required <?php if(!isset($data['name'])) echo "disabled";?>>
            </div>
            
        
            <input id="updtbtn" type="submit" class="primarybtn" name="updatepro" value="Update Profile">
        </div>
        
    </form>
    <script>
         var uname = document.getElementById("txtname");
        var profile = document.getElementById("profile");
        var updatebtn = document.getElementById("updtbtn");
        updatebtn.disabled = true;

        var name = uname.value.trim();
        var imgchange = false;

        profile.onchange = () =>{
            readURL(profile, "proimg");
            imgchange = true;
            updatebtn.disabled = false;
        }

        uname.onkeyup = () =>{
            if(uname.value.trim() != name && uname.value.trim() != ""){
                updatebtn.disabled = false;
            }
            else{
                if(!imgchange){
                    updatebtn.disabled = true;
                }
            }
        }

        function readURL(input, nameid){
            if(input.files && input.files[0])
            {
                var reader = new FileReader();
                reader.onload = function(e){
                    var proimg = document.getElementById(nameid);
                    proimg.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>