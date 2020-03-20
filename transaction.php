<?php
  include("include/sql.php");
  include("include/title.php");
$userbooklist = $_SESSION['userInfo']['userBookList'];
$userId = $_SESSION['userId'];
$transactionlist =array();
$stmt = $pdo->prepare("SELECT * FROM `transactionTable` WHERE `userId` = $userId");
$stmt->execute();
if($stmt != null){
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $row['transactionMethod'] = ($row['transactionMethod'] == 0)?"Paypal":"Credit Card";
    $transactionlist[] = $row; 
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
            <span class="componenttitle">Transaction</span>
          </div>
          <div class="row">
            <div  class="col-4 libraryblcok align-items-center  text-left">
              Activity
            </div>
            <div  class="col-4 libraryblcok align-items-center  text-left">
              Amount
            </div>
            <div  class="col-4 libraryblcok align-items-center  text-left">
              Balance
            </div>
          <div class="row margin_5">
            <?php
            foreach ($transactionlist as $key => $value) {
            ?>
            <div  class="col-4 libraryblcok align-items-center  text-left">
              <?=$value['transactionMethod']?>
            </div>
            <div  class="col-4 libraryblcok align-items-center  text-left">
              <?=$value['transactionCoin']?>
            </div>
            <div  class="col-4 libraryblcok align-items-center  text-left">
              <?=$value['transactionAmount']?>
            </div>
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
    </script>
  </body>
</html>