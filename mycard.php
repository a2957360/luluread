<?php
  include("include/sql.php");
  include("include/title.php");

  $userId = $_SESSION['userId'];
  $cardlist =array();
  $stmt = $pdo->prepare("SELECT * FROM `userCard` WHERE `userId` = $userId");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $cardlist[] = $row; 
    }
  }

  if(isset($_POST['addcard'])){
    $cardName = $_POST['cardName'];
    $cardNumber = $_POST['cardNumber'];
    $expireMonth = $_POST['expireMonth'];
    $expireYear = $_POST['expireYear'];
    $cardCvv = $_POST['cardCvv'];
    $userId = $_SESSION['userId'];
    if(empty($cardName) || empty($cardNumber) || empty($expireMonth) || empty($expireYear) || empty($cardCvv)){
      echo "<script>alert('Please fill all part')</script>";
    }else{
      $stmt = $pdo->prepare("INSERT INTO `userCard`(`userId`, `cardName`, `cardNumber`, `expireMonth`, `expireYear`, `cardCvv`) 
                            VALUES ('$userId','$cardName','$cardNumber','$expireMonth','$expireYear','$cardCvv')");
      $stmt->execute();
      header('location: '.$_SERVER['HTTP_REFERER']);
    }


  }
?>
  <body>
    <div class="container">
      <div class="row">
        <?php include("include/header.php");?>
        <div class="col-12 text-center content">
          <!-- recommend显示 -->
          <div class="componentup margin_5">
            <span class="componenttitle">Payment</span>
          </div>

          <div class="row margin_5">
            <?php
            foreach ($cardlist as $key => $value) {
            ?>
            <div  class="col-12 paymentblcok align-items-center  text-center">
              <a href="">
                <div class="paymentimg m-auto">
                <img class="" src="include/image/paypal.png">
                </div>
              </a>
            </div>
            <?php
            }
            ?>
          </div>
        </div>
        <div class="col-12 text-center bottom addcardbtnblock">
          <a onclick="showcard()">Add Card</a>
        </div>

        <div class="addcard hide">
            <div class="col-12 addcardblock" onclick="event.cancelBubble = true">
              <form class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                <input type="hidden" name="Price" value="<?=$value['Price']?>">
                <input type="hidden" name="bookId" value="<?=$bookId?>">
                <div class="col-12">
                  <input type="text" name="cardName" placeholder="Enter card holder name">
                </div>
                <div class="col-12">
                  <input type="text" name="cardNumber" placeholder="Enter card number">
                </div>
                <div class="col-3">
                 <input type="text" name="expireMonth" placeholder="DD">
                </div>
                <div class="col-3">
                  <input type="text" name="expireYear" placeholder="YY">
                </div>
                <div class="col-2"></div>
                <div class="col-4">
                 <input type="text" name="cardCvv"  placeholder="Enter CVV">
                </div>
                <div class="col-12 margin_5">
                  <input class="addcardbtn float-right" type="submit" name="addcard" value="Add Card">
                </div>
              </form>
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
    </script>
  </body>
</html>