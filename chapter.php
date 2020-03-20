<?php
  include("include/sql.php");
  include("include/title.php");
    if(isset($_POST['unlockchapter'])){
	  $chapterId = $_POST['chapterId'];
	  $chapterPrice = $_POST['chapterPrice'];
	  $userId = $_SESSION['userId'];
    if(!isset($_SESSION['userId'])){
      header("location: signin.php");
      exit();
    }
    if($_SESSION['userInfo']['luluCoin'] < $chapterPrice){
      header("location: payment.php");
      exit();
    }

	  $stmt = $pdo->prepare("SELECT `bookId` FROM `chapterTable` WHERE `chapterId` = '$chapterId'");
	  $stmt->execute();
	  if($stmt != null){
	    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	    	$bookId = $row['bookId'];
	    }
	  }
	  $stmt = $pdo->prepare("UPDATE `userTable` SET `luluCoin` = `luluCoin` - '$chapterPrice' WHERE `userId` = '$userId'");
	  $stmt->execute();
	  $stmt = $pdo->prepare("INSERT INTO `userBook`(`userId`,`bookId`,`chapterList`) value('$userId','$bookId','$chapterId') 
	  						on duplicate key update `chapterList`=CONCAT(`chapterList`+'$chapterId');");
	  $stmt->execute();
	  header("location: chapter.php?chapterId=".$chapterId);
	}

  $chapterId=$_GET['chapterId'];
  $stmt = $pdo->prepare("SELECT `chapterTable`.*,`contentTable`.`chapterContent`,
                        `bookTable`.`bookAuthor`,`bookTable`.`bookTranslater`
                         FROM `chapterTable` JOIN `contentTable`,`bookTable`
                        WHERE `chapterTable`.`chapterId`=`contentTable`.`chapterId` 
                        AND `chapterTable`.`bookId`=`bookTable`.`bookId` 
                        AND `chapterTable`.`chapterId`='$chapterId'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $bookId=$row['bookId'];
      $chapterLanguage=$row['chapterLanguage'];
      $maxchapter=$row['chapterNo'];
      $chapterName=$row['chapterName'];
      $bookAuthor=empty($row['bookAuthor'])?"":"Author: ".$row['bookAuthor'];
      $bookTranslater=empty($row['bookTranslater'])?"":"Translater: ".$row['bookTranslater'];
      $chapterContent=$row['chapterContent'];
      $chapterState=$row['chapterState'];
      $chapterPrice=$row['chapterPrice'];
    }
  }
  $chapeternolist = array();
  $chapeteridlist = array();
  $stmt = $pdo->prepare("SELECT * FROM `chapterTable` 
                        WHERE `bookId`='$bookId' AND `chapterLanguage`='$chapterLanguage' ORDER BY `chapterNo`");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $chapeternolist[]=$row['chapterNo'];
      $chapeteridlist[]=$row['chapterId'];
    }
  }
  $offset=array_search($maxchapter,$chapeternolist);

  $userBooklist = array();
  $userId = $_SESSION['userId'];
  $stmt = $pdo->prepare("SELECT `chapterList` FROM `userBook` WHERE `bookId` = '$bookId' AND `userId` = '$userId'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $userBooklist = (explode(",",$row['chapterList']));
    }
  }
  if((!in_array($chapterId, $userBooklist) || !isset($_SESSION['userId'])) && $maxchapter > 5 && $chapterState ==1){
  	$lock = "";
  	$chapterContent = substr($chapterContent,0,100);
  }else{
  	$lock = "hide";
  }


?>

  <body>
    <div class="container">
      <div class="row">
        <?php include("include/header.php");?>
        <div class="col-12 text-center chapterheader">
          <a class="pagebtn" href="bookdetail.php?bookId=<?=$bookId?>&langugae=<?=$chapterLanguage?>"><span class="icon"><</span></a><span class="popuptitle"><?=$chapterName?></span>
        </div>

        <div class="col-12 text-left content">
          <span class="chaptertitle "><?=$chapterName?></span>
          <span class="chapterauthor "><?=$bookAuthor?></span>
          <span class="chapterauthor margin_d5"><?=$bookTranslater?></span>
          <pre class="chaptercontent"><?=$chapterContent?>
          </pre>
        </div>
        <div class="col-6 text-center margin_d5">
          <?php
            if(isset($chapeteridlist[$offset - 1])){
          ?>
          <a class="pagebtn prev" href="#" onclick="changechapter('<?=$chapeteridlist[$offset - 1]?>','<?=$userId?>');">Previous</a>
          <?php
            }else{
          ?>
          <a class="pagebtn prev" href="bookdetail.php?bookId=<?=$bookId?>&langugae=<?=$chapterLanguage?>">Home</a>
          <?php
            }
          ?>
          <a class="pagebtn prevhome hide" href="bookdetail.php?bookId=<?=$bookId?>&langugae=<?=$chapterLanguage?>">Home</a>
        </div>
        <div class="col-6 text-center margin_d5">
          <?php
            if(isset($chapeteridlist[$offset + 1])){
          ?>
          <a class="pagebtn next" href="#" onclick="changechapter('<?=$chapeteridlist[$offset + 1]?>','<?=$userId?>');">Next</a>
          <?php
            }else{
          ?>
          <a class="pagebtn next" href="bookdetail.php?bookId=<?=$bookId?>&langugae=<?=$chapterLanguage?>">Home</a>
          <?php
            }
          ?>
          <a class="pagebtn nexthome hide" href="bookdetail.php?bookId=<?=$bookId?>&langugae=<?=$chapterLanguage?>">Home</a>
        </div>

       <div class="blockbg <?=$lock?>">
        <div class="col-12 buychapterblock text-center " onclick="event.cancelBubble = true">
          <span class="locktext margin_d5">Locked</span>
          <div class="lockspace">
          	
          </div>
          <div class="row text-center">
          	<span class="explaintext margin_d5">*This chapter is a “Must Pay” chapter.
 				       Continue to read, please unlock and pay.</span>

              <div class="col-12">
               <span  class="margin_d5 text-center">This Chapter need <span class="explaintext"><?=$chapterPrice?> LuluCoin</span> to Unlock</span>
              </div>
              <div class="col-12">
               <span  class="margin_d5 text-center">Your Have <span class="explaintext"><?=$_SESSION['userInfo']['luluCoin']?> LuluCoin</span></span>
              </div>
              <?php
                $word = "Are you sure to spend ".$chapterPrice." LuluCoin to Unlock?";
                if($_SESSION['userInfo']['luluCoin'] < $chapterPrice){
                  $word = "Your luluCoin is not enough. Would you like to get some?";
                }
                if(!isset($_SESSION['userId'])){
                  $word = "Please Sign In First.";
                }
              ?>
            <form class="col-12 margin_d5" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
              <input type="hidden" name="chapterId" value="<?=$chapterId?>">
              <input type="hidden" name="chapterPrice" value="<?=$chapterPrice?>">
              <input class="unlockbtn" type="submit" name="unlockchapter" value="UNLOCK" onclick="return sumbit_sure('<?=$word?>')">
            </form>
            <div class="col-12">
            	<a class="pagebtn" href="bookdetail.php?bookId=<?=$bookId?>&langugae=<?=$chapterLanguage?>">Home</a>
            </div>
          </div>
        </div>
      </div>


        <div class="col-12 text-center footer">
          <div class="row">
            <div class="col-12 fontset addon">
              <div class="row">
                <div class="col-12">
                  <span class="fonttitle text-left">Font</span>
                  <a onclick="changefontsize('decrease')">A-</a>
                  <input class="slider" type="range" min="12" max="40" step="4" value="16" oninput="setfontsize()" onchange="setfontsize()">
                  <a onclick="changefontsize('increase')">A+</a>
                </div>
<!--                 <div class="col-12">
                  字体：
                  <a href="">黑体</a>
                </div> -->
              </div>
            </div>
            <div class="col-4 ">
              <a onclick="changelight()"><img src="include/image/light.png"></a>
            </div>
            <div class="col-4 ">
              <a onclick="showfont()"><img src="include/image/font.png"></a>
            </div>
            <div class="col-4 ">
              <a onclick="savebook()"><img src="include/image/bookmart.png"></a>
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