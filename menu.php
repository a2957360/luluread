    <div class="menublock hide">
      <div class="showmenu" onClick="event.cancelBubble = true">
        <div class="imgblock text-center">
          <div class="m-auto">
          <a href="personalcenter.php">
          <img class="" src="<?=$menuUserPic?>">
          <span class="username"><?=$_SESSION["userInfo"]['userName']?></span>
          <span class="useremail"><?=$_SESSION["userInfo"]['userEmail']?></span>
          </a>
          </div>
        </div>
        <div class="menubtnblock text-center">
          <a href="index.php" class="singlebtn text-left m-auto">
            <span class="name">Home</span><span class="icon">></span>
          </a >
          <div class="menuline"></div>
          <a href="Category.php" class="singlebtn text-left m-auto">
            <span class="name">Category</span><span class="icon">></span>
          </a>
          <div class="menuline"></div>
          <a href="library.php" class="singlebtn text-left m-auto">
            <span class="name">Library</span><span class="icon">></span>
          </a>
          <div class="menuline"></div>
          <a href="transaction.php" class="singlebtn text-left m-auto">
            <span class="name">Transaction</span><span class="icon">></span>
          </a>
          <div class="menuline"></div>
          <a href="contactus.php" class="singlebtn text-left m-auto">
            <span class="name">Contact</span><span class="icon">></span>
          </a>
          <div class="menuline"></div>
          <?php
            if(!isset($_SESSION["userInfo"])){
          ?>
          <div class="menusign">
            <span class="tip">Don’t have an account?</span>
            <a class="signupbtn" href="signup.php">Sign Up</a>
            <a class="signinbtn m-auto" href="signin.php">Sign In</a>
          </div>
          <?php
            }else{
          ?>
          <div class="menusign">
            <a class="signupbtn" href="signout.php" onclick="return sumbit_sure('Are you sure to sign out')">Sign Out</a>
          </div>
          <?php
            }
          ?>
        </div>
      </div>
    </div>