<?php
    include("sql.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date= date('YmdHis');
    if($_FILES['typePic']['name'] != null){
        $File_type = strrchr($_FILES['typePic']['name'], '.'); 
        $photo = 'include/pic/service/'.$date.$File_type;
        $photosql = ", `typePic`='".$photo."'";
    }
    
    if(isset($_POST['submit'])){
        $typeName=$_POST['typeName'];

        $stmt = $pdo->prepare("INSERT into `typeTable` (`typeName`) VALUES ('$typeName')");
        $stmt->execute();
        if($stmt != null){
        	if($_FILES['typePic']['name'] != null){
              $target_path = "../".$photo;
              move_uploaded_file($_FILES['typePic']['tmp_name'], $target_path);
            }
        	header('location: '.$_SERVER['HTTP_REFERER']);
        }else{
            die('Error: ' . mysql_error());
        }
    }

    if(isset($_POST['delsubmit'])){
        $typeId=$_POST['typeId'];
        $stmt = $pdo->prepare("DELETE FROM `typeTable` WHERE `typeId` = '$typeId'");
        $stmt->execute();
        if($stmt != null){
        	header('location: '.$_SERVER['HTTP_REFERER']);
          }else{
            die('Error: ' . mysql_error());
        }
    }

    if(isset($_POST['changesubmit'])){
        $typeId=$_POST['typeId'];
        $typeName=$_POST['typeName'];

        $stmt = $pdo->prepare("UPDATE `typeTable` SET `typeName` = '$typeName' ".$photosql." WHERE `typeId`='$typeId'");
        $stmt->execute();
        if($stmt != null){
        	if($_FILES['typePic']['name'] != null){
              $target_path = "../".$photo;
              move_uploaded_file($_FILES['typePic']['tmp_name'], $target_path);
            }
        	header('location: '.$_SERVER['HTTP_REFERER']);
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
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Luluread Backend</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
    <!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/light-bootstrap-dashboard.css?v=2.0.0 " rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<style type="text/css">
    form span{
      display:none;
    }
    form:hover .popbox{
      display:block;
      position:absolute;
      top:15px;
      left:-30px;
      width:100px;
      background-color:#424242;
      color:#fff;
      padding:10px;
    }
         .fileInputContainer{
            background:url(../images/banner1.jpg);
            position:relative;
            margin: 0 auto;
            background-size: contain !important;
            background-repeat: no-repeat !important;
        }
        .fileInput{
            margin: 0 auto;
            width:50% !important;
            overflow: hidden;
            opacity: 0;
            filter:alpha(opacity=0);
            cursor:pointer;
        }
</style>
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
                    	<?php
                            if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                        ?>
                        <div class="col-md-12">
                            <div class="card  card-tasks">
                                <div class="card-header ">
                                    <h4 class="card-title">添加图书类别</h4>
                                </div>
                                <div class="card-body ">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                        	            Icon：<div style="background:url(asset/img/default-avatar);height: 50px;width: 100px" class="fileInputContainer inlineblock" id="previewadd">
                                            <input class="fileInput" type="file" name="typePhoto" id="" onchange="imgPreview(this,'previewadd')" />
                                        </div>
                                        名字：<input type="text" name="typeName">
                                        <input type="submit" name="submit" value="添加">
                                    </form>
                                </div>
                            </div>
                        </div>
	                    <?php
	                        }
	                    ?>
                        <div class="col-md-12">
                            <div class="card strpied-tabled-with-hover">
                                <div class="card-header ">
                                    <h4 class="card-title">图书类别列表</h4>
                                </div>
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <th>Icon</th>
                                            <th>名字</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($typeshowlist as $key => $value) {
                                            ?>
                                            <tr>
                                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                                <input type="hidden" name="typeId" value="<?=$value['typeId']?>">
                                                <td>
                                                    <div style="background:url(../<?=$value['typePic']?>);height: 30px;" class="fileInputContainer" id="preview<?=$key?>">
                                                        <input class="fileInput" type="file" name="typePic" id="" onchange="imgPreview(this,'preview<?=$key?>')" />
                                                    </div>
                                                </td>
                                                <td><input type="text" name="typeName" value="<?=  $value['typeName']?>"></td>

                                                <?php
                                                    if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                                ?>
                                                <td><input type="submit" name="changesubmit" value="修改" onclick="return change_sure()"></td>
                                                <?php
                                                    }
                                                ?>
                                                </form>
                                                <td>                      
                                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                                <input type="hidden" name="typeId" value="<?=$value['typeId']?>">
                                                <?php
                                                    if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                                ?>

                                                <input type="submit" name="delsubmit" value="删除" onclick="return sumbit_sure()">
                                                <?php
                                                    }
                                                ?>
                                                </form>
                                                </td>
                                            </tr>
                                            <?php
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
          var gnl=confirm("确认添加?");
              if (gnl==true){
                return true;
              }else{
                return false;
              }
          }
            function change_sure(){
          var gnl=confirm("确认修改?");
              if (gnl==true){
                return true;
              }else{
                return false;
              }
          }
          $(document).ready(function() { 
              $("#typelist").attr("class","nav-item active");
          }); 
          function changerate(formno,name){
            var rate = $('#'+formno+' input[name="rate"]').val();
            console.log('#'+formno +'input[name="rate"]');
            var gnl=confirm(name+rate+",确定?");
            if (gnl==true){
                var form = $('#'+formno);
                $('#'+formno).append("").submit();
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
    </script>
</html>
