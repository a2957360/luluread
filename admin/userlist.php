<?php
    include("sql.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Id=$_POST['Id'];
    if(isset($_POST['delsubmit'])){
        $stmt = $pdo->prepare("UPDATE `customer_table` SET `Vip`= '0' WHERE `Id` = '$Id'");
        $stmt->execute();
        if($stmt != null){
          }else{
            die('Error: ' . mysql_error());
        }
    }else{
        if(!isset($_POST['submit'])){
            exit("错误执行");
        }//判断是否有submit操作
        $name=$_POST['name'];
        $refcode = 'c'.substr($name, 1,1).$Id.substr($name, -1);
        $date= date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("UPDATE `customer_table` SET `RefCode` = '$refcode', `Vip`= '2' WHERE `Id` = '$Id'");
        $stmt->execute();
          if($stmt != null){

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
                    <a class="navbar-brand" href="#pablo"> 用户列表 </a>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-plain table-plain-bg">
                                <div class="card-header ">
                                    <h4 class="card-title">用户列表</h4>
                                </div>
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>照片</th>
                                            <th>用户名字</th>
                                            <th>电话</th>
                                            <th>评分</th>
                                            <th>余额</th>
                                            <th>订单数</th>
                                            <th>注册日期</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $stmt = $pdo->prepare("SELECT *,(SELECT count(*) FROM `orderInfo` WHERE `orderInfo`.`userId` = `userInfo`.`userId`) AS `orderNum` FROM `userInfo` 
                                                                      WHERE `userRole` = '0'");
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
                                            ?>
                                            <tr>
                                                <td><a href="userdetail.php?userId=<?=$userId?>"><img src="<?=$userPhoto?>" style="width: 50px;"></a></td>
                                                <td><a href="userdetail.php?userId=<?=$userId?>"><?=  $userName?></a></td>
                                                <td><?=  $userPhone?></td>
                                                <td><?=  $userRate?></td>
                                                <td><?=  $userMoney?></td>
                                                <td><?=  $orderNum?></td>
                                                <td><?=  $uploadTime?></td>
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
        function sumbit_suredel(){
          var gnl=confirm("确定要拒绝?");
          if (gnl==true){
          return true;
          }else{
          return false;
          }
          }
          $(document).ready(function() { 
              $("#userlist").attr("class","nav-item active");
          }); 
    </script>
</html>
