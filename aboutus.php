<?php
  include("include/sql.php");
  include("include/title.php");
  $chapterId=$_GET['chapterId'];
  $stmt = $pdo->prepare("SELECT `Content` FROM `luluread`
                        WHERE `Name`='about us'");
  $stmt->execute();
  if($stmt != null){
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $Content=$row['Content'];
      }
  }
?>

  <body>
    <div class="container">
      <div class="row">
        <?php include("include/header.php");?>
        <div class="col-12 text-left content">
          <span class="chaptertitle margin_d5">About Us</span>
          <pre class="chaptercontent"><?=$Content?>
          </pre>
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