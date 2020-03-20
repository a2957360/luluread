<?php
  include("include/sql.php");
  include("include/title.php");
$userbooklist = $_SESSION['userInfo']['userBookList'];
$userId = $_SESSION['userId'];
$stmt = $pdo->prepare("SELECT * FROM `bookTable` WHERE `bookId` IN ($userbooklist)");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $booklist[] = $row; 
  }
}
if(isset($_POST['remove'])){
  $bookList =implode(",",$_POST['bookList']);
  $userId = $_SESSION['userId'];

  $stmt = $pdo->prepare("UPDATE `userTable` SET `userbooklist` = '$bookList' WHERE `userId` = '$userId'");
  $stmt->execute();
  header('location: '.$_SERVER['HTTP_REFERER']);
}

$adlist = array();
$stmt = $pdo->prepare("SELECT * FROM `advertisementTable`");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $adlist[] = $row; 
  }
}
?>

  <body>
    <div class="container">
      <div class="row">

        <?php include("include/header.php");?>

        <div class="col-12 text-center content">
          <!-- recommend显示 -->
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
          <div class="componentup margin_5">
            <span class="componenttitle">Library</span>
            <a class="float-right openbtn" onclick="changeedit('open')">edit</a>
            <a class="float-right closebtn" onclick="changeedit('close')"></a>
            <input class="float-right removebtn" type="submit" name="remove" value="Remove">
          </div>

          <div class="row margin_5">
            <?php
            foreach ($booklist as $key => $value) {
            ?>
            <div  class="col-4 libraryblcok align-items-center  text-left">
              <label class="select text-center align-items-center ">
                <input type="checkbox" name="bookList[]" value="<?=$value['bookId']?>" checked><span><span></span></span>
              </label>
              <a href="bookdetail.php?bookId=<?=$value['bookId']?>">
              <img src="<?=$value['bookPic']?>">
              <span class="title"><?=$value['bookName']?></span>
              </a>
            </div>
            <?php
            }
            ?>
          </div>
          </form>

        </div>
        <div style="background-image: url()" class="col-12 bottomad text-center">
          <img src="<?=$adlist[0]['advertisementPhoto']?>">
          <span class="bonusdetail"> *Paid Advertising </span>
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
    </script>
  </body>
</html>