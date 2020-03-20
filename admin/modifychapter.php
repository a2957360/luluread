<?php
    include("sql.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['changesubmit'])){
        $bookId=$_POST['bookId'];
        $chapterNo=$_POST['chapterNo'];
        $chapterName=$_POST['chapterName'];
        $chapterWords=$_POST['chapterWords'];
        $chapterContent=addslashes($_POST['chapterContent']);

        $stmt = $pdo->prepare("INSERT INTO `chapterTable`(`bookId`, `chapterNo`, `chapterName`, `chapterWords`) 
                              VALUES ('$bookId','$chapterNo','$chapterName','$chapterWords')");
        $result = $stmt->execute();
        if($stmt != null){
            $chapterId = $pdo->lastinsertid();
            $stmt = $pdo->prepare("INSERT INTO `contentTable`(`chapterId`, `chapterContent`) 
                                  VALUES ('$chapterId','$chapterContent')");
            $result = $stmt->execute();

            header('location: modifybook.php?bookId='.$bookId);

        }else{
            die('Error: ' . mysql_error());
        }
    }

  }

  $bookId=$_GET['bookId'];
  $chapterId=$_GET['chapterId'];
  $stmt = $pdo->prepare("SELECT `bookTable`.*,`typeTable`.`typeName` FROM `bookTable` 
              LEFT JOIN `typeTable` ON `typeTable`.`typeId` = `bookTable`.`typeId`
              WHERE `bookId` = '$bookId'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $bookId=$row['bookId'];
      $typeName=$row['typeName'];
      $typeId=$row['typeId'];
      $bookPic="../".$row['bookPic'];
      $bookName=$row['bookName'];
      $bookAuthor=$row['bookAuthor'];
      $bookTranslater=$row['bookTranslater'];
      $bookShortDescription=$row['bookShortDescription'];
      $bookDescription=$row['bookDescription'];
      $chapterNum=$row['chapterNum'];
      $viewTime=$row['viewTime'];
      $createTime=$row['createTime'];
    }
  }
    $stmt = $pdo->prepare("SELECT `chapterTable`.*,`contentTable`.`chapterContent` FROM `chapterTable` JOIN `contentTable` 
                            WHERE `chapterTable`.`chapterId`=`contentTable`.`chapterId` 
                            AND `chapterTable`.`chapterId`='$chapterId'");
    $stmt->execute();
    if($stmt != null){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $maxchapter=$row['chapterNo'];
          $chapterName=$row['chapterName'];
          $chapterContent=$row['chapterContent'];
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
                    <a class="navbar-brand" href="#">Table List</a>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-12 booklist">
                            <div class="card card-plain table-plain-bg">
                                <div class="card-header ">
                                    <h4 class="card-title">书籍内容</h4>
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
                                            <tr>
                                                <td><a href="modifybook.php?bookId=<?=$bookId?>"><img src="<?=$bookPic?>"></a></td>
                                                <td class="date"><?=  $createTime?></td>
                                                <td class="date"><?=  $createTime?></td>
                                                <td><a href="modifybook.php?bookId=<?=$bookId?>"><?=  $bookName?></a></td>
                                                <td class="des"><?=  $bookShortDescription?></td>
                                                <td><?=  $typeName?></td>
                                                <td><?=  $chapterNum?></td>
                                                <td><?=  $viewTime?></td>
                                                <td>
                                                </td>
                                            </tr>                                             
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                          <a href="../chapter.php?chapterId=<?=$chapterId?>">查看页面</a>
                          <div class="col-md-12 chapter">
                            <form class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data' class="card">
                            <div class="col-md-12 userinfoline">
                                <input type="hidden" name="bookId" value="<?=$bookId?>">
                                <div class="userinfoline">章节号：<input type="text" name="chapterNo" value="<?=  $maxchapter?>" required></div>
                                <div class="userinfoline">章节名：<input type="text" name="chapterName" value="<?=  $chapterName?>" required></div>
                                <div class="userinfoline"><textarea name="chapterContent" placeholder="内容" required><?=  $chapterContent?></textarea></div>
                                <?php
                                    if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                ?>
                                <input type="submit" name="changesubmit" value="添加">
                                <?php
                                    }
                                ?>
                              </div>
                            </form>
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
<!--  Google Maps Plugin    -->
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

        window.onload = addlist();

        function addlist(){
            var orderlist = <?php echo json_encode($orderlist);?>; 
            console.log(orderlist);
            orderlist.forEach(function(item) {
                $("#addlist").append(' <tr><a href="">')
                            .append(' <td>'+item['orderDate']+'</td>')
                            .append(' <td>'+item['orderName']+'</td>')
                            .append(' <td>'+item['cleanerName']+'</td>')
                            .append(' <td>'+item['orderPrice']+'</td>')
                            .append(' <td>'+item['userReviewRate']+'</td>')
                            .append(' <td>'+item['userReviewContent']+'</td>')
                            .append(' <td>'+item['cleanerReviewRate']+'</td>')
                            .append(' <td>'+item['cleanerReviewContent']+'</a></tr>');
            })
        }

        function initMap(item) {
            var addresspos = $("#address").val();
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': addresspos}, function(results, status) {
              if (status === 'OK') {
                buildloc = results[0].geometry.location.lat()+","+results[0].geometry.location.lng()
                $("#locpos").val(buildloc);
              } else {
              }
            });
        }
        function imgPreview(fileDom,prev){
        //判断是否支持FileReader
        if (window.FileReader) {
            var reader = new FileReader();
        } else {
            alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
        }

        //获取文件
        var file = fileDom.files[0];
        var imageType = /^image\//;
        //是否是图片
        if (!imageType.test(file.type)) {
            alert("请选择图片！");
            return;
        }
        //读取完成
        reader.onload = function(e) {
            //获取图片dom
            var img = document.getElementById(prev);
            //图片路径设置为读取的图片
            img.style.backgroundImage = "url('"+e.target.result+"')";
        };
        reader.readAsDataURL(file);
        }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFObd_Eh5_4JGY1qMy9g8DFhqxxcVILyI">
    </script>
</html>
