<?php
  include("include/sql.php");
  include("include/title.php");
  if(!isset($_SESSION['userId'])){
    echo "<script>alert('Please Sign In First')</script>";
    echo "<script> location.href='signin.php'; </script>";
    exit();
  }
  $chapterId=$_GET['chapterId'];
  $stmt = $pdo->prepare("SELECT `Content` FROM `luluread`
                        WHERE `Name`='about us'");
  $stmt->execute();
  if($stmt != null){
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $Content=$row['Content'];
      }
  }

  if (isset($_POST['changeinfo'])){
    $userId=$_SESSION["userId"];
    $userName=$_POST['userName'];
    $userEmail=$_POST['userEmail'];
    $userPassword=$_POST['userPassword'];

    $date= date('YmdHis');
    if($_FILES['userPic']['name'] != null){
        $File_type = strrchr($_FILES['userPic']['name'], '.'); 
        $userPic = 'include/pic/user/'.$date.$userId.$File_type;
        $picsql = ",`userPic`='".$userPic."'";
    }
    $stmt = $pdo->prepare("UPDATE `userTable` SET 
                           `userName`='$userName', `userEmail`='$userEmail'".$picsql."
                          WHERE `userId`='$userId'");
    $stmt->execute();
    if($_FILES['userPic']['name'] != null){
      move_uploaded_file($_FILES['userPic']['tmp_name'], $userPic);
    }
    header('location: '.$_SERVER['HTTP_REFERER']);
}
?>

  <body>
    <div class="container">
      <div class="row">
        <?php include("include/header.php");?>
        <div class="col-12 ">
            <div class="row text-center">
              <div class="col-12 persnonalinfo m-auto">
                <img class="userpic" src="<?=$menuUserPic?>">
                <span class="username"><?=$_SESSION["userInfo"]['userName']?><img src="include/image/editinfo.png" onclick="showuserinfo()"></span>
                <span class="useremail"><?=$_SESSION["userInfo"]['userEmail']?></span>
                <span class="username">LuLuCoin: <?=$_SESSION["userInfo"]['luluCoin']?></span>
              </div>
              <div class="col-12 persnonalinfo  margin_5">
                <a class="topup" href="payment.php">Top Up</a>
              </div>
            </div>
            <div class="personalcenter text-center">
              <a href="library.php" class="singlebtn text-left m-auto">
                <span class="name">Library</span><span class="icon">></span>
              </a>
              <div class="menuline"></div>
              <a  href="transaction.php" class="singlebtn text-left m-auto">
                <span class="name">Transaction</span><span class="icon">></span>
              </a>
              <div class="menuline"></div>
              <a href="contactus.php" class="singlebtn text-left m-auto">
                <span class="name">Contact</span><span class="icon">></span>
              </a>
              <div class="menuline"></div>
              <?php
                if(isset($_SESSION["userInfo"])){
              ?>
              <div class="persnonalsign">
                <a class="signupbtn" href="signup.php" onclick="return sumbit_sure('Are you sure to sign out')">Sign Out</a>
              </div>
              <?php
                }
              ?>
            </div>
        </div>
          <div class="hide blackbg flex">
            <div class="col-12 priceblock m-auto" onclick="event.cancelBubble = true">
              <span class="reviewh1 text-left">Top Up Amount</span>
              <div class="row">
                <form class="col-12" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?bookId=<?=$bookId?>" method="POST" enctype='multipart/form-data'>
                  <span class="dollar">$</span><input class="price" type="text" name="price">
                  <input class="coinbtn" type="submit" name="addcoin">
                </form>
              </div>
            </div>
          </div>
          <div class="userinfo blackbg flex hide">
            <div class="col-12 userinfoblock m-auto" onclick="event.cancelBubble = true">
              <span class="reviewh1 text-left">Edit User Info</span>
              <div class="row">
                <form class="col-12 text-center" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                  <div class="fileInputContainer" style="background-image: url(<?=$menuUserPic?>)" class="fileInputContainer" id="aboutadd">
                    <input class="fileInput" type="file" name="userPic" id="" onchange="imgPreview(this,'aboutadd')"/>
                  </div>
                  <input type="text" name="userName" value="<?=$_SESSION["userInfo"]['userName']?>">
                  <input type="text" name="userEmail" value="<?=$_SESSION["userInfo"]['userEmail']?>"><br>
                  <input class="editbtn margin_5 float-right margin_d5" type="submit" name="changeinfo" value="edit">
                </form>
              </div>
            </div>
          </div>
      </div>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <?php
      include("include/js.php");
    ?>

  </body>
</html>