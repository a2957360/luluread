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
    $userEmail=$_POST['userEmail'];
    $userPassword = $_POST['userPassword'];

    $stmt = $pdo->prepare("SELECT * FROM `userTable` WHERE `userEmail` = '$userEmail';");
    $stmt->execute();
    if($stmt != null){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          if(password_verify($userPassword,$row['userPassword'])){
            $_SESSION["userId"] = $row['userId'];
            echo "<script>alert('Sign In Success')</script>";
            echo "<script> location.href='index.php'; </script>";
          }else{
            echo "<script>alert('用户名密码不正确')</script>";
          }
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
              <a href="index.php"><img src="include/image/logo.png"></a>
            </div>
            <div class="col-12 signup text-left">
            <span class="signupTitle">Sign In</span>
            </div>
            <div class="col-12 signup">
                <input type="text" name="userEmail" placeholder="Email" value="<?=$userEmail?>">
            </div>
            <div class="col-12 signup">
                <input id="userPassword" type="password" name="userPassword" placeholder="Password">
            </div>
            <div class="col-12 signup text-right">
              <a class="forget" href="">Forgot your Password?</a>
            </div>
<!--             <div class="col-12 signup">
                <input type="password" name="reuserPassword" placeholder="Reenter Password">
            </div> -->
            <div class="col-12 signup">
              <input class="accessbtn" type="submit" name="submit" value="Sign In">
            </div>
            <div class="col-12 signup">
              <a href="signup.php" class="signupsigninbtn">Sign Up</a>
            </div>
            <div class="col-12 ">
              <span class="results">OR</span>
            </div>
            <div class="col-4 margin_5 margin_d5">
              <img src="include/image/twitterlogin.png">
            </div>
            <div class="col-4 margin_5 margin_d5">
              <img src="include/image/facebooklogin.png">
            </div>
            <div class="col-4 margin_5 margin_d5">
              <img src="include/image/gmaillogin.png">
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

// $().ready(function() {
//     $("#commentForm").validate();
// });

$(document).ready(function() {
    $("#signupform").validate({
        event : "keyup" || "blur",
        rules : {
            userName : "required",
            userEmail : "required",
            userPassword : "required",
            password : {
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
            userPassword : "required",
            password : {
                required : "required",
                minlength : jQuery.validator.format("password must long than {0} letters"),
                maxlength : jQuery.validator.format("password must short than {0} letters")
            },
            userEmail : {
                required : "required",
                email : "Wrong Format"
            }
        }
    });
});

</script>
</html>