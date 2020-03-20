<?php
  include("include/sql.php");
  include("include/title.php");
$booklist = array();
$stmt = $pdo->prepare("SELECT `bookTable`.*,`typeTable`.`typeName` FROM `bookTable` 
          LEFT JOIN `typeTable` ON `typeTable`.`typeId` = `bookTable`.`typeId`
                      ORDER BY `bookTable`.`createTime` desc");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $booklist[] = $row; 
  }
}

$adlist = array();
$stmt = $pdo->prepare("SELECT * FROM `advertisementTable`");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $adlist[] = $row; 
  }
}

$typelist = array();
$stmt = $pdo->prepare("SELECT * FROM `typeTable`");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $typelist[] = $row; 
  }
}

?>

  <body>
    <div class="container">
      <div class="row">

        <?php include("include/header.php");?>

        <div class="col-12 text-center content">
          <form class="searchbar text-left margin_5" action="book.php" method="GET" >
            <input type="image" class="searchbtn" src="include/image/search.png">
            <input class="searchcontent" type="text" name="searchContent" placeholder="Search by Books or Authors">
          </form>

          <div id="carouselExampleIndicators" class="carousel slide margin_5" data-ride="carousel">
            <div class="carousel-inner">
              <?php
              $num = 0;
              foreach ($adlist as $key => $value) {
                $active = "";
                if($num == 0){
                  $active = "active";
                }
                $num++;
              ?>
              <div class="carousel-item <?=$active?>">
                <a href="<?=$value['advertisementLink']?>"><img class="d-block w-100 bannerad" src="<?=$value['advertisementPhoto']?>" alt=""></a>
              </div>
              <?php
              }
              ?>
            </div>
            <ol class="carousel-indicators">
              <?php
              $num = 0;
              foreach ($adlist as $key => $value) {
                $active = "";
                if($num == 0){
                  $active = "active";
                }
              ?>
              <li data-target="#carouselExampleIndicators" data-slide-to="<?=$num?>" class="<?=$active?>"></li>
              <?php
                $num++;
              }
              ?>

            </ol>
          </div>

<!--           <div class="componentup">
            <span class="componenttitle">Best Authors</span>
            <a class="componentlink">Show All</a>
          </div>

          <div class="row">
            <div class="col-3 author">
              <img src="include/image/teacher.png">
              <span>Mart Art</span>
            </div>            
            <div class="col-3 author">
              <img src="include/image/teacher.png">
              <span>Mart Art</span>
            </div>            
            <div class="col-3 author">
              <img src="include/image/teacher.png">
              <span>Mart Art</span>
            </div>            
            <div class="col-3 author">
              <img src="include/image/teacher.png">
              <span>Mart Art</span>
            </div>
          </div> -->

          <!-- recent显示 -->
          <div class="componentup margin_5">
            <span class="componenttitle">Recent Added</span>
            <a class="componentlink" href="book.php?recent=Recent">Show All</a>
          </div>

          <div class="row margin_5">
            <?php
            $num = 0;
            foreach ($booklist as $key => $value) {
              if($num > 2){
                break;
              }
              $num++
            ?>
            <div class="col-4 book text-left">
              <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><img src="<?=$value['bookPic']?>"></a>
              <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><span class="title"><?=$value['bookName']?></span></a>
              <a class="catorgery"><?=$value['typeName']?></a>
              <span class="rate"><?=sprintf("%1\$.1f", $value['viewRate'])?><img src="include/image/star.png"></span>
            </div>   
            <?php
            }
            ?>
          </div>

          <!-- recommend显示 -->
          <div class="componentup margin_5">
            <span class="componenttitle">Recommend</span>
            <a class="componentlink"  href="book.php?recommend=Recommend">Show All</a>
          </div>

          <div class="row margin_5">
            <?php
            $num = 0;
            foreach ($booklist as $key => $value) {
              if($value['bookRecommend'] == 0){
                continue;
              }
              if($num > 2){
                break;
              }
              $num++
            ?>
            <div class="col-4 book text-left">
              <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><img src="<?=$value['bookPic']?>"></a>
              <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><span class="title"><?=$value['bookName']?></span></a>
              <a class="catorgery"><?=$value['typeName']?></a>
              <span class="rate"><?=sprintf("%1\$.1f", $value['viewRate'])?><img src="include/image/star.png"></span>
            </div>   
            <?php
            }
            ?>
          </div>

          <!-- 广告显示 -->
          <div  class="row margin_5" >
            <div style="background-image: url(<?=$adlist[0]['advertisementPhoto']?>)" class="col-12 indexad">
            </div>
          </div>

          <!-- 分类显示 -->
          <div class="componentup margin_5">
            <span class="componenttitle">Categories</span>
          </div>

          <div class="row margin_5">
            <?php
            $num = 0;
            foreach ($typelist as $key => $value) {
              // if($num > 2){
              //   break;
              // }
                $active = "";
              if($num == 0){
                  $active = "active";
              }
              $num++
            ?>
            <div class="col-3 book text-center nopadding">
              <span class="catogory catogory<?=$value['typeId']?>btn <?=$active?>" onclick="showcategory('catogory<?=$value['typeId']?>')"><?=$value['typeName']?></span>
            </div>   
            <?php
            }
            ?>
          </div>

          <?php
          $typenum = 0;
          foreach ($typelist as $typekey => $typevalue) {
            // if($num > 2){
            //   break;
            // }
          ?>
          <div class="catogory<?=$typevalue['typeId']?> hide">
            <div class="componentup margin_5">
              <span class="componenttitle"><?=$typevalue['typeName']?></span>
              <a class="componentlink" href="book.php?typeId=<?=$typevalue['typeId']?>">Show All</a>
            </div>
            <div class="row margin_5">
              <?php
              $num = 0;
              foreach ($booklist as $key => $value) {
                if($typevalue['typeId'] != $value['typeId']){
                  continue;
                }
                if($num > 2){
                  break;
                }
                $num++
              ?>
              <div class="col-4 book text-left">
                <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><img src="<?=$value['bookPic']?>"></a>
                <a href="bookdetail.php?bookId=<?=$value['bookId']?>&language=English"><span class="title"><?=$value['bookName']?></span></a>
                <a class="catorgery"><?=$value['typeName']?></a>
                <span class="rate"><?=sprintf("%1\$.1f", $value['viewRate'])?><img src="include/image/star.png"></span>
              </div>   
              <?php
              }
              ?>
            </div>
          </div>
          <?php
              if($typenum == 0){
                echo '<script type="text/javascript"> var showname = ".catogory'.$typevalue['typeId'].'"</script>';
              }
            $typenum++;
            }
          ?>

          <div class="row margin_5 bottomlink text-left">
            <div class="col-12  ">
              <a class="signlelink bottomline" href="contactus.php">CONTACT</a>        
            </div>
            <div class="col-12 ">
              <a class="signlelink" href="aboutus.php">ABOUT LULU READ</a>        
            </div>
          </div>
          <div class="row text-left copyright">
            <div class="col-9">
              <span>Copyright © 2020 Lulu Read, Powered By<a class="" href="www.Finestudio.ca"> Finestudio.ca</a></span>
            </div>
            <div class="col-3 text-right">
              <a href=""><img src="include/image/facebook.png"></a>
              <a href=""><img src="include/image/twitter.png"></a>
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
      $(showname).show();
    </script>
  </body>
</html>