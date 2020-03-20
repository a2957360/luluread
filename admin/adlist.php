<?php
    include("sql.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date= date('YmdHis');
    if($_FILES['advertisementPhoto']['name'] != null){
        $File_type = strrchr($_FILES['advertisementPhoto']['name'], '.'); 
        $photo = 'include/pic/ad/'.$date.$File_type;
        $photosql = ", `advertisementPhoto`='".$photo."'";
     }
    if(isset($_POST['add'])){
        $advertisementName=$_POST['advertisementName'];
        $advertisementName="1";
        $advertisementLink="http://".$_POST['advertisementLink'];

        $stmt = $pdo->prepare("INSERT INTO `advertisementTable`(`advertisementName`, `advertisementPhoto`, `advertisementLink`) VALUES ('$advertisementName','$photo','$advertisementLink')");
        $stmt->execute();
        if($stmt != null){
            if($_FILES['advertisementPhoto']['name'] != null){
              $target_path = "../".$photo;
              move_uploaded_file($_FILES['advertisementPhoto']['tmp_name'], $target_path);
            }
          }else{
            die('Error: ' . mysql_error());
        }
        header('location: '.$_SERVER['HTTP_REFERER']);
    }
    if(isset($_POST['changead'])){
        $advertisementId=$_POST['advertisementId'];
        $advertisementName=$_POST['advertisementName'];
        $advertisementLink="http://".$_POST['advertisementLink'];
        $stmt = $pdo->prepare("UPDATE `advertisementTable` SET `advertisementLink`='$advertisementLink' ".$photosql." WHERE `advertisementId`='$advertisementId'");
        $stmt->execute();
        if($stmt != null){
            if($_FILES['advertisementPhoto']['name'] != null){
              $target_path = "../".$photo;
              move_uploaded_file($_FILES['advertisementPhoto']['tmp_name'], $target_path);
            }
          }else{
            die('Error: ' . mysql_error());
        }
        header('location: '.$_SERVER['HTTP_REFERER']);

    }
    if(isset($_POST['deletead'])){
        $advertisementId=$_POST['advertisementId'];
        $stmt = $pdo->prepare("DELETE FROM `advertisementTable` WHERE `advertisementId`='$advertisementId'");
        $stmt->execute();
        if($stmt != null){
          }else{
            die('Error: ' . mysql_error());
        }
        header('location: '.$_SERVER['HTTP_REFERER']);
    }

  }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>SRDC Backend</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/css/demo.css" rel="stylesheet" />
            <style>
     .fileInputContainer{
            background:url(../images/banner1.jpg);
            position:relative;
            margin: 0 auto;
            background-size: contain !important;
            background-repeat: no-repeat !important;
        }
        .fileInput{
            margin: 0 auto;
            height:80% !important;
            width:80% !important;
            overflow: hidden;
            font-size: 150px;
            opacity: 0;
            filter:alpha(opacity=0);
            cursor:pointer;
        }
      .inputlink{
            width:70% !important;
        }
        
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include("header.php");?>
        
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#pablo"> Table List </a>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title">广告图</h4>
                                    <p class="card-category">点击图片更换</p>
                                </div>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                  <div class="card-body ">
                                    <div style="background:url();background-color: lightgrey" class="fileInputContainer" id="previewadd">
                                        <input class="fileInput" type="file" name="advertisementPhoto" id="" onchange="imgPreview(this,'previewadd')" required="" />
                                    </div>
                                  </div>
                                  <div class="card-footer ">
                                    <input type="hidden" name="pic<?=$num?>" value="<?=$Pic?>">
                                    <input type="hidden" name="advertisementId" value="<?=$advertisementId?>">
                                    链接：http:// &nbsp;<input class="inputlink" type="text" name="advertisementLink" value="<?=$advertisementLink?>">
                                    <input type="submit" name="add">
                                  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                          $stmt = $pdo->prepare("SELECT * FROM `advertisementTable`");
                          $stmt->execute();
                          $num = 0;
                          if($stmt != null){
                          while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $advertisementId=$row['advertisementId'];
                            $advertisementName=$row['advertisementName'];
                            $advertisementPhoto=$row['advertisementPhoto'];
                            $advertisementLink=str_replace("http://", "", $row['advertisementLink']);
                            $DorS=$row['DorS'];
                            $num++;
                        ?>
                        <div class="col-md-4">
                            <div class="card ">
                                <div class="card-header ">
                                    <h4 class="card-title">广告图<?=$num?></h4>
                                    <p class="card-category">点击图片更换</p>
                                </div>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                  <div class="card-body ">
                                    <div style="background:url(../<?=$advertisementPhoto?>)" class="fileInputContainer" id="preview<?=$num?>">
                                        <input class="fileInput" type="file" name="advertisementPhoto" id="" onchange="imgPreview(this,'preview<?=$num?>')" />
                                    </div>
                                  </div>
                                  <div class="card-footer ">
                                    <input type="hidden" name="advertisementId" value="<?=$advertisementId?>">
                                    链接：http:// &nbsp;<input class="inputlink" type="text" name="advertisementLink" value="<?=$advertisementLink?>">
                                    <input type="submit" name="changead" value="修改">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                        <input type="hidden" name="advertisementId" value="<?=$advertisementId?>">
                                        <input type="submit" name="deletead" value="删除">
                                    </form>
                                  </div>
                                </form>
                            </div>
                        </div>
                        <?php
                            }
                          }
                        ?>
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
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
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
    $(document).ready(function() { 
        $("#adlist").attr("class","nav-item active");
    }); 
    </script>
</html>
