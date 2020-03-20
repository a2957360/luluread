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
?>

  <body>
    <div class="container">
      <div class="row">

        <?php include("include/header.php");?>

        <div class="col-12 text-center content">
          <!-- recommend显示 -->
          <div class="componentup margin_5">
            <span class="componenttitle">Catogories</span>
          </div>

          <div class="row margin_5">
            <?php
            foreach ($typelist as $key => $value) {
            ?>
            <a href="book.php?categoryId=<?=$value['typeId']?>" style="background-image: url(<?=$value['typePic']?>)" class="col-4 categoryblcok text-center align-items-center">
              <span class="title"><?=$value['typeName']?></span>
            </a>   
            <?php
            }
            ?>
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