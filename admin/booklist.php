<?php
  include("sql.php");
  include("../include/config.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['year'])){
      $date = $_POST['year'];
    }else if(isset($_POST['month'])){
      $searchmonth = "AND `orderInfo`.`orderDate` like '%".$_POST['month']."%'";
    }

    if(isset($_POST['delete'])){
        $bookId=$_POST['bookId'];
        $stmt = $pdo->prepare("delete `contentTable` from `contentTable`,`chapterTable` where `chapterTable`.`bookId` = '$bookId'");
        $stmt->execute();
        $stmt = $pdo->prepare("DELETE FROM `chapterTable` WHERE `bookId` = '$bookId'");
        $stmt->execute();
        $stmt = $pdo->prepare("DELETE FROM `bookTable` WHERE `bookId` = '$bookId'");
        $stmt->execute();
        if($stmt != null){
            header('location: '.$_SERVER['HTTP_REFERER']);
          }else{
            die('Error: ' . mysql_error());
        }
    }
  }

?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>KIWE Backend</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <div class="wrapper">
        <?php include("header.php");?>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#pablo">  </a>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                    	<div class="col-md-12 mb20">
                          <a class="abtn" href="addbook.php">添加书籍</a>
                        </div>
                        <!-- <?php include("searchbar.php");?> -->
                        <div class="col-md-12 booklist">
                            <div class="card card-plain table-plain-bg">
                                <div class="card-header ">
                                    <h4 class="card-title">书籍列表</h4>
                                </div>
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>图片</th>
                                            <th>最新上传日期</th>
                                            <th>添加日期</th>
                                            <th>书籍名字</th>
                                            <th>书籍简介</th>
                                            <th>书籍类型</th>
                                            <th>章节数</th>
                                            <th>观看次数</th>
                                        </thead>
                                        <tbody id="tradelist">
                                            <?php
                                              $stmt = $pdo->prepare("SELECT `bookTable`.*,`typeTable`.`typeName` FROM `bookTable` 
                                              						LEFT JOIN `typeTable` ON `typeTable`.`typeId` = `bookTable`.`typeId`
                                                                      ".$searchsql);
                                              $stmt->execute();
                                              if($stmt != null){
                                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                  $bookId=$row['bookId'];
                                                  $typeName=$row['typeName'];
                                                  $bookPic="../".$row['bookPic'];
                                                  $bookName=$row['bookName'];
                                                  $bookAuthor=$row['bookAuthor'];
                                                  $bookTranslater=$row['bookTranslater'];
                                                  $bookShortDescription=$row['bookShortDescription'];
                                                  $bookDescription=$row['bookDescription'];
                                                  $chapterNum=$row['chapterNum'];
                                                  $viewTime=$row['viewTime'];
                                                  $bookRecommend=$bookRecommendTrans[$row['bookRecommend']];
                                                  $createTime=$row['createTime'];
                                            ?>
                                            <tr>
                                                <td><a href="modifybook.php?bookId=<?=$bookId?>"><img src="<?=$bookPic?>"></a></td>
                                                <td class="date"><?=  $createTime?></td>
                                                <td class="date"><?=  $createTime?></td>
                                                <td><a href="modifybook.php?bookId=<?=$bookId?>"><?=  $bookName?></a><span class="tag"><?=$bookRecommend?></span></td>
                                                <td class="des"><?=  $bookShortDescription?></td>
                                                <td><?=  $typeName?></td>
                                                <td><?=  $chapterNum?></td>
                                                <td><?=  $viewTime?></td>
                                                <td>
                                                <form class="inlineblock" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                                    <input type="hidden" name="bookId" value="<?=$bookId?>">
                                                    <?php
                                                        if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                                    ?>
                                                    <input type="submit" name="delete" value="删除" onclick="return sumbit_sure()">
                                                    <?php
                                                        }
                                                    ?>
                                                </form>
                                                </td>
                                                <td></td>
                                            </tr>                                             
                                            <?php
                                                }
                                              }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php include("footer.php");?>
        </div>
    </div>
 
</body>
<!--   Core JS Files   -->
<script src="assets/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>
<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
<script src="assets/js/plugins/bootstrap-switch.js"></script>
<!--  Chartist Plugin  -->
<script src="assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>
        <script type="text/javascript">
          function sumbit_sure(){
          var gnl=confirm("确定要通过?");
          if (gnl==true){
          return true;
          }else{
          return false;
          }
          }
          $(document).ready(function() { 
              $("#booklist").attr("class","nav-item active");
          }); 

        window.onload = function() {
            var tradelist = <?php echo json_encode($tradelist);?>; 
            tradelist.forEach(function(item) {
                $("#tradelist").append(' <tr><a href=""><td>'+item['Date']+" "+item['res']+'</td><td><img src="../'+item['Photo']+'" style="width: 50px;"></td><td>'+item['StoreName']+'</td><td>'+item['ServerName']+'</td><td>'+item['UserName']+'</td><td>'+item['Phone']+'</td><td>'+item['Price']+'</td><td>'+item['GetPoint']+'</td><td>'+item['RefName']+'</td></a></tr>');
            })
        }
    </script>
</html>
