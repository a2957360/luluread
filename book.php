<?php
  include("include/sql.php");
  include("include/title.php");
$typelist = array();
$stmt = $pdo->prepare("SELECT * FROM `typeTable`");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $typelist[$row['typeId']] = $row; 
  }
}

$typeId     =  isset($_GET['categoryId'])?$_GET['categoryId']:"";
$bookName   =  isset($_GET['searchContent'])?$_GET['searchContent']:"";
$recent   =  isset($_GET['recent'])?$_GET['recent']:"";
$recommend   =  isset($_GET['recommend'])?$_GET['recommend']:"";

$booklist = array();
$stmt = $pdo->prepare("SELECT `bookTable`.*,`typeTable`.`typeName`, count(`reviewTable`.`reviewId`) AS `reviewNum` FROM `bookTable` 
                      LEFT JOIN `typeTable` ON `typeTable`.`typeId` = `bookTable`.`typeId`
                      LEFT JOIN `reviewTable` ON `reviewTable`.`bookId` = `bookTable`.`bookId`
                      GROUP By `bookTable`.`bookId`
                      ORDER BY `bookTable`.`createTime` desc");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // $row['bookLanguage'] = explode(",", $row['bookLanguage'])
    if($typeId != "" && $typeId !== $row['typeId']){
      continue;
    }
    if($bookName != "" && !(stristr($row['bookName'],$bookName) || stristr($row['bookAuthor'],$bookName))){
      continue;
    }
    $date1 = time();
    $date2 = strtotime($row['createTime']);
    $result = round(($date1-$date2)/3600/24);
    if($recent != "" && $result > 180){
      continue;
    }
    if($recommend != "" && $row['bookRecommend'] == 0){
      continue;
    }
    $booklist[] = $row; 
  }
}
$title .= $recent;
$title .= $recommend;
$title .= empty($typeId)?$bookName:$typelist[$typeId]['typeName'];
$userbooklist = $_SESSION['userInfo']['userBookList'];
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
  echo "<script>alert('Add success')</script>";
}
$userbooklist = explode(",", $userbooklist)
?>

  <body>
    <div class="container">
      <div class="row">

        <?php include("include/header.php");?>

        <div class="col-12 text-center content">

          <div class="componentup ">
            <span class="componenttitle"><?=$title?></span>
          </div>

          <form class="searchbar text-left margin_5" action="book.php" method="GET" >
            <input type="image" class="searchbtn" src="include/image/search.png">
            <input class="searchcontent" type="text" name="searchContent" placeholder="Search by Books or Authors">
            <img class="filterimg" src="include/image/filter.png" onclick="showfilter()">
          </form>
          <div class="margin_5">
            <span class="results"><?=count($booklist)?> results for </span><span class="searchtitle">"<?=$title?>"</span>
          </div>

          <div class="row margin_5">
            <?php
            foreach ($booklist as $key => $value) {
              $langugaelist = explode(",", $value['bookLanguage']);
            ?>
            <div class="col-12 bookblock margin_d5 con<?=$value['typeId']?>" data-star="<?=$value['viewRate']?>">
              <div class="row">
                <div class="col-5 lesspadding">
                  <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><img src="<?=$value['bookPic']?>"></a>
                </div>
                <div class="col-7 text-left">
                  <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><span class="title"><?=$value['bookName']?></span></a>
                  <span class="author">By <?=$value['bookAuthor']?></span>
                  <span class="rate"><?=sprintf("%1\$.1f", $value['viewRate'])?><img src="include/image/star.png"></span>
                  <span class="times"><?=$value['reviewNum']?> Reviews</span>
                  <span class="date"><?=date("M d, Y",strtotime($value['createTime']))?></span>
                  <div>
                  <?php
                    foreach ($langugaelist as $skey => $svalue) {
                      echo '<div class="showlanguage"><span>'.substr($svalue,0,2).'</span></div>';
                    }
                  ?>
                  </div>
                  <!-- <span class="date"><?=$value['bookLanguage']?></span> -->
                  <?php
                    if(!in_array($value['bookId'], $userbooklist))
                    {
                  ?>
                    <a class="addbtn" onclick="$('#addbook<?=$key?>').submit()">Add to library</a>
                  <?php
                    }
                  ?>
                  <form id="addbook<?=$key?>" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                    <input type="hidden" name="addlibaray">    
                    <input type="hidden" name="bookId" value="<?=$value['bookId']?>">                
                  </form>
                </div>
              </div>
            </div>   
            <?php
            }
            ?>
          </div>


        </div>
<!--         <div class="col-12 text-center footer">
          底部
        </div> -->

        <div class="filter hide">
          <div class="col-12 filterblock" onclick="event.cancelBubble = true">
            <span class="filtertext text-left">Filter <img class="float-right" src="include/image/downclose.png" onclick="closefilter()"></span>
            <div class="row filterselect">
              <div class="col-12 star margin_5">
                
                <label onclick="changefilter(4)"><input type="radio" name="star"> <img src="include/image/star4.png"> & Up</label>
              </div>
              <div class="col-12 star margin_5">
                <label onclick="changefilter(3)"><input type="radio" name="star"> <img src="include/image/star3.png"> & Up</label>
              </div>
              <div class="col-12 star margin_5">
                <label onclick="changefilter(2)"><input type="radio" name="star"> <img src="include/image/star2.png"> & Up</label>
              </div>
              <div class="col-12 star margin_5">
                <label onclick="changefilter(1)"><input type="radio" name="star"> <img src="include/image/star1.png"> & Up</label>
              </div>
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