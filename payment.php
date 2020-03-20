<?php
  include("include/sql.php");
  include("include/title.php");
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
            <div  class="col-12 paymentblcok align-items-center  text-center">
              <a href="getluluCoin.php">
                <div class="paymentimg m-auto">
                <img class="" src="include/image/paypal.png">
                </div>
              </a>
            </div>
            <div  class="col-12 paymentblcok align-items-center  text-center">
              <a href="mycard.php">
                <div class="paymentimg">
                <img class="m-auto" src="include/image/visa.png">
                </div>
              </a>
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