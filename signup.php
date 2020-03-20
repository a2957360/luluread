<?php
  include("include/sql.php");
  // ob_start();
  // session_start(); 
  // $dsn = "mysql:host=localhost;dbname=dbs195491";
  // $sqlusername = 'root';
  // $sqlpassword = 'Finestudio123@';
  //   $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
  //   if(!$pdo){
  //       die("can't connect".mysql_error());//如果链接失败输出错误
  //   }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName=$_POST['userName'];
    $userEmail=$_POST['userEmail'];
    $userPassword = password_hash($_POST['userPassword'], PASSWORD_DEFAULT);

    $error = 0;
    $stmt = $pdo->prepare("SELECT count(*) AS `num` FROM `userTable` WHERE `userEmail` = '$userEmail';");
    $stmt->execute();
    if($stmt != null){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if($row['num'] > 0){
              $error = "1";
              echo "<script>alert('该邮箱已被使用')</script>";
            }
        }
    }

    if($error == 0){
      $stmt = $pdo->prepare("INSERT INTO `userTable`(`userName`, `userEmail`, `userPassword`) values ('$userName','$userEmail','$userPassword');");
      $stmt->execute();
      if($stmt != null){
        $lastid = $pdo->lastinsertid();
        $_SESSION["userId"] = $lastid;
        $_SESSION["userName"] = $userName;
        echo "<script>alert('Sign Up Success')</script>";
        echo "<script> location.href='index.php'; </script>";
      }else{
        die('Error: ' . mysql_error());
      }
    }

  }
  include("include/title.php");
?>

  <body>
    <div class="container">
      <div class="row">
<!--         <div class="col-12 text-center header">
          头部
        </div> -->
        <div class="col-12 text-center content">
          <form id="signupform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
          <div class="row">
            <div class="col-12 signup">
              <img src="include/image/logo.png">
            </div>
            <div class="col-12 signup text-left">
            <span class="signupTitle">Sign Up</span>
            </div>
            <div class="col-12 signup">
              <input id="userName" type="text" name="userName" placeholder="UserName" value="<?=$userName?>">
            </div>
            <div class="col-12 signup">
                <input type="text" name="userEmail" placeholder="Email" value="<?=$userEmail?>">
            </div>
            <div class="col-12 signup">
                <input id="userPassword" type="password" name="userPassword" placeholder="Password">
            </div>
<!--             <div class="col-12 signup">
                <input type="password" name="reuserPassword" placeholder="Reenter Password">
            </div> -->
            <div class="col-12 signup">
              <input class="accessbtn" type="submit" name="submit" value="Sign Up">
            </div>
            <div class="col-12 signup">
              <a href="signin.php" class="signupsigninbtn">Sign In</a>
            </div>
          </div>
          </form>
        </div>
        <div class="col-12 text-center ">
            <span class="declare">
              By signing in, creating an account, or checking out as a Guest, you are agreeing to our <a href=""> Terms of Use</a> and our <a href=""> Privacy Policy</a>.
            </span>
        </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <?php
      include("include/js.php");
    ?>
  </body>
  <script type="text/javascript">
var dataUrl = "";

$().ready(function() {
    $("#commentForm").validate();
});

$(document).ready(function() {
    $("#signupform").validate({
        event : "keyup" || "blur",
        rules : {
            userName : "required",
            userEmail : "required",
            userPassword : "required",
            userPassword : {
                required : true,
                minlength : 6,
                maxlength : 15
            },
            userEmail : {
                required : true,
                email : true
            }
        },
        messages : {
            userName : "required",
            userPassword : {
                required : "required",
                minlength : jQuery.validator.format("password must long than {0} letters"),
                maxlength : jQuery.validator.format("password must short than {0} letters")
            },
            userEmail : {
                required : "required",
                email : "Wrong Format",
            }
        }
    });
});

</script>
</html>