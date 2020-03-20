<?php
    include("sql.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['delsubmit'])){
        $userId=$_POST['userId'];
        $stmt = $pdo->prepare("DELETE FROM `userInfo` WHERE `userId` = '$userId'");
        $stmt->execute();
        if($stmt != null){

          }else{
            die('Error: ' . mysql_error());
        }
        header('location: '.$_SERVER['HTTP_REFERER']);

    }
    if(isset($_POST['submit'])){
        $userName=$_POST['userName'];
        $userPhone=$_POST['userPhone'];
        $userRole=$_POST['userRole'];

        $stmt = $pdo->prepare("INSERT into `userInfo` (`userName`,`userPhone`,`userRole`) VALUES ('$userName','$userPhone','$userRole')");
        $stmt->execute();
        if($stmt != null){
            echo "<script> location.href='managelist.php'; </script>";
        }else{
            die('Error: ' . mysql_error());
        }
    }else if(isset($_POST['changesubmit'])){
        $userId=$_POST['userId'];
        $userName=$_POST['userName'];
        $userPhone=$_POST['userPhone'];
        $userRole=$_POST['userRole'];

        $stmt = $pdo->prepare("UPDATE `userInfo` SET `userName` = '$userName', `userPhone` = '$userPhone', `userRole` = '$userRole' WHERE `userId`='$userId'");
        $stmt->execute();
        if($stmt != null){
            echo "<script> location.href='managelist.php'; </script>";
        }else{
            die('Error: ' . mysql_error());
        }
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
                    <?php
            if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
        ?>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card  card-tasks">
                                <div class="card-header ">
                                    <h4 class="card-title">添加管理员</h4>
                                </div>
                                <div class="card-body ">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                        名字：<input type="text" name="userName">
                                        电话：<input type="text" name="userPhone">
                                        <select style="height: 33px;border:none" name="userRole">
                                            <option value="2">
                                                普通管理员
                                            </option>                                            
                                            <option value="3">
                                                高级管理员
                                            </option>
                                        </select>
                                        <input type="submit" name="submit" value="添加">
                                    </form>
                                </div>
                            </div>
                            <div class="card strpied-tabled-with-hover">
                                <div class="card-header ">
                                    <h4 class="card-title">管理员列表</h4>
                                </div>
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <th>名字</th>
                                            <th>电话</th>
                                            <th>职位</th>
                                            <th>操作</th>

                                        </thead>
                                        <tbody>
                                            <?php
                                                $stmt = $pdo->prepare("SELECT * FROM `userInfo` WHERE `userRole` = '3' or `userRole` = '2'");
                                                $stmt->execute();
                                                if($stmt != null){
                                                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                    $userId=$row['userId'];
                                                    $userName=$row['userName'];
                                                    $userPhone=$row['userPhone'];
                                                    $userRole=$row['userRole'];
                                                    $normal="";
                                                    $up="";
                                                    if($userRole == 2){
                                                        $normal="selected";
                                                    }else{
                                                        $up="selected";
                                                    }
                                            ?>
                                            <tr>
                                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                                <input type="hidden" name="userId" value="<?=$userId?>">
                                                <td><input type="text" name="userName" value="<?=  $userName?>"></td>
                                                <td><input type="text" name="userPhone" value="<?=  $userPhone?>"></td>
                                                <td>
                                                <select style="height: 33px;border:none" name="userRole">
                                                    <option value="2" <?=$normal?>>
                                                        普通管理员
                                                    </option>                                            
                                                    <option value="3" <?=$up?>>
                                                        高级管理员
                                                    </option>
                                                </select>
                                                </td>
                                                <td><input type="submit" name="changesubmit" value="修改"></td>
                                                </form>
                                                <td>                      
                                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                                <input type="hidden" name="userId" value="<?=$userId?>">
                                                <input type="submit" name="delsubmit" value="删除" >
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
                    <?php
            }
        ?>
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
          $(document).ready(function() { 
              $("#managelist").attr("class","nav-item active");
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
    </script>
</html>
