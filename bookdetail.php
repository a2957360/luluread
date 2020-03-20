<?php
  include("include/sql.php");
  include("include/title.php");


$typelist = array();
$stmt = $pdo->prepare("SELECT * FROM `typeTable`");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $typelist[] = $row; 
  }
}
$bookId = $_GET['bookId'];
$language = empty($_GET['language'])?"English":$_GET['language'];

$booklist = array();
$stmt = $pdo->prepare("SELECT `bookTable`.*,`typeTable`.`typeName` FROM `bookTable` 
                      LEFT JOIN `typeTable` ON `typeTable`.`typeId` = `bookTable`.`typeId`
                      WHERE `bookTable`.`bookId` = '$bookId'");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $row['bookTranslater'] = ($row['bookTranslater'] != "")?" / Translate by".$row['bookTranslater']:"";
    $booklist = $row; 
  }
}
$chapterlist = array();
$langugaelist = explode(",", $booklist['bookLanguage']);
foreach ($langugaelist as $key => $value) {
  $stmt = $pdo->prepare("SELECT * FROM `chapterTable` WHERE `bookId` = '$bookId' AND `chapterLanguage` = '$value'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $chapterlist[$value][] = $row;
    }
  }
}

$typeId = $booklist['typeId'];
$similarbooklist = array();
$stmt = $pdo->prepare("SELECT `bookTable`.*,`typeTable`.`typeName` FROM `bookTable` 
                      LEFT JOIN `typeTable` ON `typeTable`.`typeId` = `bookTable`.`typeId`
                      WHERE `bookTable`.`typeId` = '$typeId' limit 3");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $similarbooklist[] = $row; 
  }
}

$reviewlist = array();
$stmt = $pdo->prepare("SELECT `reviewTable`.*,`userTable`.`userPic`,`userTable`.`userName` FROM `reviewTable` 
                        LEFT JOIN `userTable` ON `reviewTable`.`userId` = `userTable`.`userId` WHERE `bookId` = '$bookId'");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $reviewlist[$row['userId']] = $row; 
  }
}
$disablereview = "";
if(array_key_exists($_SESSION['userId'], $reviewlist)){
  $disablereview = "disable";
}

$userBooklist = array();
$userId = $_SESSION['userId'];
$stmt = $pdo->prepare("SELECT `chapterList` FROM `userBook` WHERE `bookId` = '$bookId' AND `userId` = '$userId'");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $userBooklist = (explode(",",$row['chapterList']));
  }
}

$librarylist = (explode(",",$_SESSION['userInfo']['userBookList']));
$hidelibrary = "";
if(in_array($bookId, $librarylist)){
  $hidelibrary = "hide";
}

$contributelist = array();
$stmt = $pdo->prepare("SELECT * FROM `contributeType`");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $contributelist[] = $row; 
  }
}

$contributepeoplelist = array();
$stmt = $pdo->prepare("SELECT `contributeTable`.*,`userTable`.`userName`,`userTable`.`userPic` 
                        FROM `contributeTable` LEFT JOIN `userTable` ON `contributeTable`.`userId` = `userTable`.`userId`
                        WHERE `contributeTable`.`bookId` = '$bookId'");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $row['userPic'] = empty($row['userPic'])?"include/image/userimage.png":$row['userPic'];
    $contributepeoplelist[] = $row; 
  }
}


// 添加修改
if(isset($_POST['writeReview'])){
  $reviewContent = $_POST['reviewContent'];
  $reviewRate = $_POST['rate'];
  $bookId = $_POST['bookId'];
  $userId = $_SESSION['userId'];



  $stmt = $pdo->prepare("INSERT INTO `reviewTable`(`bookId`, `userId`, `reviewRate`, `reviewContent`) VALUES
                                                  ('$bookId','$userId','$reviewRate','$reviewContent')");
  $stmt->execute();
  if($stmt != null){
    $number = (count($reviewlist) == 0) ? 1:count($reviewlist);
    $reviewRate = ($number * $booklist['viewRate'] + $reviewRate)/($number + 1);
    $stmt = $pdo->prepare("UPDATE `bookTable` SET `viewRate` = '$reviewRate' WHERE `bookId` = '$bookId'");
    $stmt->execute();
    header('location: '.$_SERVER['HTTP_REFERER']);
  }
}

if(isset($_POST['addlibaray'])){
  $bookId = $_POST['bookId'];
  $userbooklist = $_SESSION['userInfo']['userBookList'];
  $userId = $_SESSION['userId'];
  $userbooklist .= ($userbooklist == "")?"":",";
  $userbooklist .= $bookId;
  if(!isset($_SESSION['userId'])){
  	echo "<script>alert('Please Sign In First')</script>";
      echo "<script> location.href='signin.php'; </script>";
      exit();
  }

  $stmt = $pdo->prepare("UPDATE `userTable` SET `userbooklist` = '$userbooklist' WHERE `userId` = '$userId'");
  $stmt->execute();
  header('location: '.$_SERVER['HTTP_REFERER']);
}

if(isset($_POST['addcontribute'])){
  $bookId = $_POST['bookId'];
  $Price = $_POST['Price'];
  $userId = $_SESSION['userId'];
  $stmt = $pdo->prepare("INSERT INTO `contributeTable`(`bookId`, `userId`, `contributeCoin`) VALUES('$bookId','$userId','$Price')");
  $stmt->execute();
  header('location: '.$_SERVER['HTTP_REFERER']);
}



?>

  <body>
    <div class="container">
      <div class="row">

        <?php include("include/header.php");?>

        <div class="col-12 text-center content">

          <div style="background-image: url(<?=$booklist['bookPic']?>);" class="row bookbanner">
            <div class="inner"></div>
            <div  class="col-12 text-left upindex margin_5">
              <?php
                foreach ($langugaelist as $key => $value) {
                	$active = "";
                	if($value == $language){
                		$active = "active";
                	}
                  echo '<a  href="bookdetail.php?bookId='.$bookId.'&language='.$value.'"><div class="changelanguage '.$active.'"><span>'.substr($value,0,2).'</span></div></a>';
                }
              ?>
            </div>
            <div  class="col-12 bannerspace">
            </div>
            <div  class="col-12 text-left upindex">
              <span class="title"><?=$booklist['bookName']?></span>
              <input class="float-right  <?=$disablereview?>" type="image" name="" src="include/image/edit.png" alt="Submit" onclick="writereview()">
            </div>
            <div  class="col-12 text-left upindex">
              <span class="author"><?=$booklist['bookAuthor']?></span><span class="translater"><?=$booklist['bookTranslater']?></span>
            </div>
            <div  class="col-12 text-left upindex">
              <span class="rate"><?=sprintf("%1\$.1f", $booklist['viewRate'])?> <img src="include/image/star.png"></span> &nbsp;<span class="times"> <?=count($reviewlist)?> reviews</span>
              <span class="type float-right"><?=$booklist['typeName']?></span>
            </div>
            <div  class="col-12 text-left upindex margin_d5">
              <a class="read inlineblock" href="chapter.php?chapterId=<?=$chapterlist[$language][0]['chapterId']?>">Read</a>
              <a class="add inlineblock <?=$hidelibrary?>" onclick="$('#addbook').submit()">Add to library</a>
              <form id="addbook" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?bookId=<?=$bookId?>" method="POST" enctype='multipart/form-data'>
                <input type="hidden" name="addlibaray">    
                <input type="hidden" name="bookId" value="<?=$bookId?>">                
              </form>
            </div>
          </div>

          <div class="row threebtn">
            <div class="col-4 text-center btnblock bookinfobtn active">
              <a onclick="showbookdetail('bookinfo')">
              <img class="disactive" src="inlcude/iamge/info.png">
              <img class="active" src="inlcude/iamge/infoactive.png">
              <span>Info</span>
              </a>
            </div>
            <div class="col-4 text-center btnblock bookreviewbtn">
              <a onclick="showbookdetail('bookreview')">
              <img class="disactive" src="inlcude/iamge/review.png">
              <img class="active" src="inlcude/iamge/reviewactive.png">
              <span>Reviews</span>
              </a>
            </div>
            <div class="col-4 text-center btnblock bookchapterbtn">
              <a onclick="showbookdetail('bookchapter')">
              <img class="disactive" src="inlcude/iamge/chapter.png">
              <img class="active" src="inlcude/iamge/chapteractive.png">
              <span>Chapters</span>
              </a>
            </div>
          </div>

            <!-- info -->
          <div class="row bookinfo hide">
            <div class="col-6 text-center numberblock">
              <span class="bgblack"><?=count($chapterlist[$language])?></span><span class="smgrey"> Chapters</span>
            </div>
            <div class="col-6 text-center numberblock">
              <span class="bgblack"><?=$booklist['viewTime']?></span><span class="smgrey"> Views</span>
            </div>

            <div class="col-11 m-auto">
              <div class="row">
                <div class="col-12 topline"></div>
                <div class="col-12 text-left margin_5 nopadding margin_d5">
                  <span class="title">Info</span>
                  <span class="content"><?=$booklist['bookDescription']?></span>
                </div>
                <div class="col-12 topline"></div>
                <div class="col-12 text-left margin_5  margin_d5">
                  <div class="row voteblcok">
                    <div class="col-6 nopadding">
                        <span class="title"><?=count($contributepeoplelist)?> <span class="smgrey"> Votes</span></span>
                        <?php
                          $num = 0;
                          foreach ($contributepeoplelist as $key => $value) {
                            $num++;
                            if($num > 6){
                              break;
                            }
                            echo '<img src="'.$value["userPic"].'">';
                          }
                        ?>
                    </div>
                    <div class="col-6 nopadding text-right">
                      <a class="vote" onclick="showcontribute()">Vote</a>
                    </div>
                  </div>

                </div>
                <div class="col-12 topline"></div>
                <div class="col-12 text-left nopadding margin_5 margin_d5">
                  <div class="componentup ">
                    <span class="componenttitle">Some Similar Books</span>
                    <a class="componentlink">Show All</a>
                  </div>
                </div>
                <div class="col-12 text-left  margin_d5">
                 <div class="row margin_5">
                  <?php
                  $num = 0;
                  foreach ($similarbooklist as $key => $value) {
                    if($num > 2){
                      break;
                    }
                    $num++
                  ?>
                  <div class="col-4 book text-left">
                    <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><img src="<?=$value['bookPic']?>"></a>
                    <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><span class="title"><?=$value['bookName']?></span></a>
                    <a class="catorgery"><?=$value['typeName']?></a>
                    <span class="rate"><?=$value['viewRate']?></span>
                  </div>   
                  <?php
                  }
                  ?>
                </div>
              </div>
              </div>
            </div>
          </div>

            <!-- review -->
          <div class="row bookreview hide">
            <div class="col-6 text-center numberblock">
              <span class="overall">Overall</span><span class="smgrey"> <?=count($reviewlist)?> reviews</span>
            </div>
            <div class="col-6 text-center numberblock">
              <span class="allrate"><?=sprintf("%1\$.1f", $booklist['viewRate'])?> <img src="include/image/star.png"></span>
            </div>

            <div class="col-11 m-auto">
              <div class="row">
                    <?php
                    $num = 0;
                    foreach ($reviewlist as $key => $value) {
                      // if($num > 2){
                      //   break;
                      // }
                      $value['userPic'] = empty($value['userPic'])?"include/image/teacher.png":$value['userPic'];
                      $num++
                    ?>
                      <div class="col-12 topline"></div>
                      <div class="col-12 text-left  margin_d5">
                       <div class="row margin_5 review">
                          <div class="col-4 text-left">
                            <img src="<?=$value['userPic']?>">
                          </div>   
                          <div class="col-8 text-left">
                            <span class="name"><?=$value['userName']?></span>
                            <span class="rate"><?=sprintf("%1\$.1f", $value['reviewRate'])?> <img src="include/image/star.png"></span>
                            <span class="content"><?=$value['reviewContent']?></span>
                            <span class="date text-right"><?=date("M d, Y",strtotime($value['createTime']))?></span>
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
              </div>
            </div>
          </div>

          <!-- chapter -->
          <div class="row bookchapter hide">
            <div class="col-6 text-left chapterblock">
              <span class="overall">Chapters</span><span class="smgrey">&nbsp;&nbsp;&nbsp;<?=count($chapterlist[$language])?></span>
            </div>
            <div class="col-6 text-center chapterblock">
            </div>

            <div class="col-11 m-auto">
              <div class="row">
                    <div class="col-12 topline"></div>
                    <?php
                    $num = 0;
                    foreach ($chapterlist[$language] as $key => $value) {
                      $num++;
                        $avaiable = "avaiable";
                      if((!in_array($value['chapterId'],$userBooklist) || !isset($_SESSION['userId'])) && $num > 5 && $value['chapterState'] == "1" ){
                        $avaiable = "";
                      }
                    ?>
                      <div class="col-12 text-left  margin_d5">
                       <div class="row margin_5 review">
                          <div class="col-2 text-left">
                            <span><?=$value['chapterNo']?></span>
                          </div>   
                          <div class="col-8 text-left">
                            <a href="chapter.php?chapterId=<?=$value['chapterId']?>"><span class="name"><?=$value['chapterName']?></span></a>
                            <span class="date"><?=date("M d, Y",strtotime($value['createTime']))?></span>
                          </div>
                          <div class="col-2 text-left">
                            <img class="<?=$avaiable?>" src="include/image/loc.png">
                          </div>
                        </div>
                      </div>
                      <div class="col-12 topline"></div>
                    <?php
                    }
                    ?>
              </div>
            </div>
          </div>
        </div>
          <div class="writereview hide">
            <form class="col-12 writereviewblock" onclick="event.cancelBubble = true" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?bookId=<?=$bookId?>" method="POST" enctype='multipart/form-data'>
              <span class="reviewh1 text-left">Write a review <img class="float-right" src="include/image/downclose.png" onclick="closewritereview()"></span> 
              <span class="reviewh2 text-left">Overall Review</span> 
              <div class="starrating risingstar d-flex justify-content-center flex-row-reverse">
                  <input type="radio" id="star5" name="rate" value="5" /><label for="star5" title="5 star"></label>
                  <input type="radio" id="star4" name="rate" value="4" /><label for="star4" title="4 star"></label>
                  <input type="radio" id="star3" name="rate" value="3" /><label for="star3" title="3 star"></label>
                  <input type="radio" id="star2" name="rate" value="2" /><label for="star2" title="2 star"></label>
                  <input type="radio" id="star1" name="rate" value="1" /><label for="star1" title="1 star"></label>
              </div>
              <textarea class="reviewtextarea" name="reviewContent"></textarea>
              <input type="hidden" name="bookId" value="<?=$bookId?>">
              <input class="reviewbtn" type="submit" name="writeReview" value="Post">
            </form>
          </div>

          <div class="contribute hide">
            <div class="col-12 writereviewblock" onclick="event.cancelBubble = true">
              <span class="reviewh1 text-left">Contibute to Book <img class="float-right" src="include/image/downclose.png" onclick="closecontribute()"></span>
              <div class="row">
                <?php
                $num = 0;
                foreach ($contributelist as $key => $value) {
                  $num++;

                ?>
                <form class="col-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?bookId=<?=$bookId?>" method="POST" enctype='multipart/form-data'>
                  <input type="hidden" name="Price" value="<?=$value['Price']?>">
                  <input type="hidden" name="bookId" value="<?=$bookId?>">
                  <button type="submit" name="addcontribute" class=" contributeblock text-center">
                    <span class="luluname"><?=$value['Name']?></span>
                    <span class="lulucoin">luluCoin:<?=$value['Price']?></span>
                  </button>
                </form>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
<!--         <div class="col-12 text-center footer">
          底部
        </div> -->
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <?php
      include("include/js.php");
    ?>
    <script type="text/javascript">
    	$(".bookinfo").css("display","flex");
    </script>
  </body>
</html>