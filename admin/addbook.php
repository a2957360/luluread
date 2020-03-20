<?php
    include("sql.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['changesubmit'])){
        $typeId=$_POST['typeId'];
        $bookName=$_POST['bookName'];
        $bookAuthor=$_POST['bookAuthor'];
        $bookTranslater=$_POST['bookTranslater'];
        $bookShortDescription=$_POST['bookShortDescription'];
        $bookDescription=$_POST['bookDescription'];

        $stmt = $pdo->prepare("INSERT INTO `bookTable`(`typeId`, `bookName`, `bookAuthor`, `bookTranslater`, `bookShortDescription`, `bookDescription`) 
                              VALUES ('$typeId','$bookName','$bookAuthor','$bookTranslater','$bookShortDescription','$bookDescription')");
        $result = $stmt->execute();
        if($stmt != null){
            if (!is_dir('../include/pic/'.$userPhone)) {
              mkdir('../include/pic/'.$userPhone);
            }
            $lastId = $pdo->lastinsertid();
            if($_FILES['bookPic']['name'] != null){
              $date= date('YmdHis');
              $File_type = strrchr($_FILES['bookPic']['name'], '.'); 
              $picture = 'include/pic/'.$lastId."/".$date.$File_type;
            }
            $stmt = $pdo->prepare("UPDATE `bookTable` SET `bookPic` = '$picture' WHERE `bookId` = '$lastId'");
            $result = $stmt->execute();
            if (!is_dir('../include/pic/'.$lastId)) {
              mkdir('../include/pic/'.$lastId);
            } 
            if($_FILES['bookPic']['name'] != null){
                move_uploaded_file($_FILES['bookPic']['tmp_name'], "../".$picture);
            }    
            if($result){
                echo "<script> location.href='booklist.php'; </script>";
            }else{
                echo "";
            }
        }else{
            die('Error: ' . mysql_error());
        }
    }

  }
    $typeshowlist= array();
    $stmt = $pdo->prepare("SELECT * FROM `typeTable`");
    $stmt->execute();
    if($stmt != null){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $typeshowlist[] = $row;
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
    <!-- CSS Just for demo purpose, don't include it in your project -->
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
                        <div class="col-md-12 book">
                            <form class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data' class="card">
                              <input type="hidden" name="userId" value="<?=$userId?>">
                                <div class="col-md-2 userinfoline">
                                    <p>图书封面</p>
                                    <div style="background:url();background-color: lightgrey" class="fileInputContainer" id="preview1">
                                        <input class="fileInput" type="file" name="bookPic" id="" onchange="imgPreview(this,'preview1')"/>
                                    </div>
                                </div>

                              <div class="col-md-6 userinfoline">
                                <p>图书信息</p>
                                <div class="userinfoline">书名：<input type="text" name="bookName" value="<?=  $bookName?>"></div>
                                <div class="userinfoline">作者：<input type="text" name="bookAuthor" value="<?=  $bookAuthor?>"></div>
                                <div class="userinfoline">译者：<input type="text" name="bookTranslater" value="<?=  $bookTranslater?>"></div>
                                <div class="userinfoline">
                                    分类：<select name="typeId">

                                    <?php
                                        foreach ($typeshowlist as $key => $value) {
                                            echo "<option value='".$value['typeId']."'>".$value['typeName']."</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="userinfoline">推荐：
                                    <label><input type="radio" name="bookRecommend" value="0" checked="">No</label>&nbsp;
                                    <label><input type="radio" name="bookRecommend" value="1">Yes</label>
                                </div>
                              </div>
                            <div class="col-md-12 userinfoline">
                                <p>图书介绍</p>
                                <div class="userinfoline"><textarea name="bookShortDescription" placeholder="简短介绍"></textarea></div>
                                <div class="userinfoline"><textarea name="bookDescription" placeholder="完整介绍"></textarea></div>
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
