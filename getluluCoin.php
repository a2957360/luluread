<?php
  include("include/sql.php");
  include("include/title.php");

  $userId = $_SESSION['userId'];
  $stmt = $pdo->prepare("SELECT count(*) AS `num` FROM `transactionTable` WHERE `userId`='$userId'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $bonus = "yes";
      if($row['num'] > 0){
        $bonus = "no";
      }
    }
  }

  $lulucoinTypeLisr = array();
  $stmt = $pdo->prepare("SELECT * FROM `lulucoinType` ORDER BY `Name`");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $lulucoinTypeLisr[] = $row;
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
            <span class="componenttitle">Get Lulu Coin <a href="payment.php"><img class="float-right" src="include/image/close.png" ></a></span>
          </div>
            <div class="row margin_5">
              <?php
                foreach ($lulucoinTypeLisr as $key => $value) {
              ?>
              <label class="col-12 paymentcheck" onclick="changeamount(<?=$value['Name']?>)">
                <div class="row getlulucoinblock">
                  <div  class="col-3 nopadding">
                    <input type="radio" name="lulucoin" checked="">
                      <img class="" src="include/image/lulucoin.png">
                  </div>
                  <div class="col-5 lulucoin align-items-center nopadding">
                     <span><?=$value['Price']?></span> <span class="bonus <?=$bonus?>">+<?=$value['Bonus']?>% Bonus</span>
                  </div>
                  <div class="col-4 dollar align-items-center">
                    <span>CAD <?=$value['Name']?></span> 
                  </div>
                </div>
              </label>
              <?php
                }
              ?>
<!--               <label class="col-12 paymentcheck" onclick="changeamount(4.99)">
                <div class="row getlulucoinblock">
                  <div  class="col-3 nopadding">
                    <input type="radio" name="lulucoin">
                      <img class="" src="include/image/lulucoin.png">
                  </div>
                  <div class="col-5 lulucoin align-items-center nopadding">
                     <span>2500</span> <span class="bonus <?=$bonus?>">+20% Bonus</span>
                  </div>
                  <div class="col-4 dollar align-items-center">
                    <span>CAD 4.99</span> 
                  </div>
                </div>
              </label>
              <label class="col-12 paymentcheck" onclick="changeamount(9.99)">
                <div class="row getlulucoinblock">
                  <div  class="col-3 nopadding">
                    <input type="radio" name="lulucoin">
                      <img class="" src="include/image/lulucoin.png">
                  </div>
                  <div class="col-5 lulucoin align-items-center nopadding">
                     <span>5000</span> <span class="bonus <?=$bonus?>">+30% Bonus</span>
                  </div>
                  <div class="col-4 dollar align-items-center">
                    <span>CAD 9.99</span> 
                  </div>
                </div>
              </label> -->
          </div>
          <div class="row text-left margin_5">
            <div class="col-12">
              <span class="coinexplain">*5 Lulu Coin = 2000 Words</span>
            </div>
          </div>
          <div class="row text-left margin_5 bonustip <?=$bonus?>">
            <div class="col-12">
              <span class="bonustilte">First Top Up Bonus</span>
            </div>
              <?php
                foreach ($lulucoinTypeLisr as $key => $value) {
              ?>
            <div class="col-12">
              <span class="bonusdetail">Pay CAD <?=$value['Name']?> to Get <?=$value['Bonus']?>% Bonus = <?=$value['Price'] * (100 + $value['Bonus']) * 0.01?> Lulu Coin</span>
            </div>
            <?php
              }
            ?>
          </div>
          <div class="row text-left margin_5">
            <div class="col-12">
              <span class="coinexplain">Agreeing to Lulu Terms of Use and our Privacy Policy</span>
            </div>
<!--             <div class="col-12">
              <input class="buycoinbtn" type="submit" name="" value="Pay Now">
            </div> -->
          </div>
          <div id="paypal-button-container"></div>

        </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <?php
      include("include/js.php");
    ?>
              <script src="https://www.paypal.com/sdk/js?client-id=AeZv_u-mXVuvG74wVayNFLNjfmpOGL7sxJKmGuU4UhKVxw7__2NkXxxvQ-x6JJMUaqEKEHTRT5A4l6d8&currency=CAD"></script>
          <script>
            var amount = 0.99;
            var userId = <?php echo $_SESSION['userId']?>;
            var transactionMethod = "paypal";
            paypal.Buttons({
                style: {
                  shape:   'pill'
                },
              createOrder: function(data, actions) {
                return actions.order.create({
                  purchase_units: [{
                    amount: {
                      value: amount
                    }
                  }]
                });
              },
              onApprove: function(data, actions) {
                // This function captures the funds from the transaction.
                return actions.order.capture().then(function(details) {
                  // This function shows a transaction success message to your buyer.
                  finishtrade();
                });
              }
            }).render('#paypal-button-container');
            // This function displays Smart Payment Buttons on your web page.
          </script>
    <script type="text/javascript">
    </script>
  </body>
</html>