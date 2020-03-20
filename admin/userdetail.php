<?php
    include("sql.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Id=$_POST['userid'];

    if(isset($_POST['year'])){
      $date = $_POST['year'];
    }else if(isset($_POST['month'])){
      $searchmonth = "AND `trade_table`.`Date` like '%".$_POST['month']."%'";
    }

    if (isset($_POST['delsubmit'])) {
        $userId=$_POST['userId'];
        $stmt = $pdo->prepare("UPDATE `userInfo` SET `userState` = '2' WHERE `userId` = '$userId'");
        $stmt->execute();
          if($stmt != null){
          }else{
            die('Error: ' . mysql_error());
        }
        header('location: '.$_SERVER['HTTP_REFERER']);
    }

    if(isset($_POST['changesubmit'])){
        $userId=$_POST['userId'];
        $userName=$_POST['userName'];
        $userPhone=$_POST['userPhone'];

        if($_FILES['userPhoto']['name'] != null){
          $date= date('YmdHis');
          $File_type = strrchr($_FILES['userPhoto']['name'], '.'); 
          $picture = 'include/pic/'.$userId."/".$date.$File_type;
          $picsql = ", `userPhoto`='".$picture."'";
        }

        $stmt = $pdo->prepare("UPDATE `userInfo` SET `userName`= '$userName',`userPhone` = '$userPhone' ".$picsql." WHERE `userId` = '$userId'");
        $stmt->execute();
        if($stmt != null){
            if($_FILES['userPhoto']['name'] != null){
                if (!is_dir('../include/pic/'.$userId)) {
                  mkdir('../include/pic/'.$userId);
                }
                move_uploaded_file($_FILES['userPhoto']['tmp_name'], "../".$picture);
            }
        }else{
            die('Error: ' . mysql_error());
        }
        header('location: '.$_SERVER['HTTP_REFERER']);
    }

  }

    $userId = $_GET['userId'];
    $stmt = $pdo->prepare("SELECT `userInfo`.*,(SELECT count(*) FROM `orderInfo` WHERE `orderInfo`.`userId` = `userInfo`.`userId`) AS `orderNum` FROM `userInfo` WHERE `userId` = '$userId'");
    $stmt->execute();
    if($stmt != null){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $userId=$row['userId'];
          $userName=$row['userName'];
          $userPhone=$row['userPhone'];
          $userPhoto="../".$row['userPhoto'];
          $userRate=$row['userRate'];
          $userMoney=$row['userMoney'];
          $uploadTime=$row['uploadTime'];
          $orderNum=$row['orderNum'];
        }
    }else{
    die('Error: ' . mysql_error());
    }

    $orderlist = array();
    $stmt = $pdo->prepare("SELECT `orderInfo`.*,`cleanerInfo`.`cleanerName`,`userReview`.* From `orderInfo` 
                            JOIN `cleanerInfo`,`userReview` 
                          WHERE `orderInfo`.`cleanerId` = `cleanerInfo`.`cleanerId` 
                          AND `orderInfo`.`orderId` = `userReview`.`orderId`
                          AND `orderInfo`.`userId` = '$userId';");
    $stmt->execute();
    $order = 0;
    if($stmt != null){
      while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
        $orderlist[] = $row;
      }
    }

  $date = date("Y");
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
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/css/demo.css" rel="stylesheet" />
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
                        <div class="col-md-12">
                            <form class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data' class="card">
                              <input type="hidden" name="userId" value="<?=$userId?>">
                                <div class="col-md-2">
                                    <div style="background:url(<?=$userPhoto?>);background-color: lightgrey" class="fileInputContainer" id="preview1">
                                        <input class="fileInput" type="file" name="userPhoto" id="" onchange="imgPreview(this,'preview1')"/>
                                    </div>
                                </div>

                              <div class="col-md-10">
                                <div class="userinfoline">注册日期 ： <?=  $uploadTime?></div>
                                <div class="userinfoline">名字：<input type="text" name="userName" value="<?=  $userName?>"></div>
                                <div class="userinfoline">电话：<input type="text" name="userPhone" value="<?= $userPhone?>"></div>
                                <div class="userinfoline">余额 ： <?=  $userMoney?></div>
                                <?php
                                    if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                ?>
                                <input type="submit" name="changesubmit" value="修改">
                                <?php
                                    }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                    <input type="hidden" name="userid" value="<?=$userId?>">
                                    <?php
                                        if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                    ?>
                                    <input type="submit" name="delsubmit" value="删除客户" onclick="return sumbit_sure()">
                                    <?php
                                        }
                                    ?>
                                </form>

                              </div>
                            </form>
                        </div>
                        <div  class="col-md-12 searchbar">
                            <div class="row">
                                <div class="col-md-2">
                                    <button><</button>
                                    <p class="inlineblock"><?=$date?></p>
                                    <button>></button>
                                </div>
                                <div class="col-md-2">
                                    
                                </div>
                            </div>
                        </div>
                        <div  class="col-md-12 ">
                            <div class="card card-plain table-plain-bg">
                                <div class="card-header ">
                                    <h4 class="card-title">交易列表</h4>
                                </div>
                                <div class="card-body table-full-width table-responsive ">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>服务日期</th>
                                            <th>服务名称</th>
                                            <th>保洁人员</th>
                                            <th>价格</th>
                                            <th>用户评分</th>
                                            <th>用户评论</th>                                            
                                            <th>保洁评分</th>
                                            <th>保洁评论</th>
                                        </thead>
                                        <tbody id="addlist" class="">
<!--                                             <tr>
                                                <td><?=  $Date?></td>
                                                <td><img src="<?=  $Picture?>" style="width: 50px;"></td>
                                                <td><?=  $Name?></td>
                                                <td><?=  $ServerName?></td>
                                                <td>CAD$ <?=$Price?></td>
                                                <td><?=  $Point?></td>
                                                <td><?=  $Start?></td>
                                                <td><?=  $Comment?></td>
                                            </tr> -->
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
              $("#userlist").attr("class","nav-item active");
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
