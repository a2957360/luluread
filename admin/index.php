<?php
    include("sql.php");
    $date = date("Y");
    $searchmonth="";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['year'])){
      $date = $_POST['year'];
    }
    if(isset($_POST['month'])){
      $getmonth = explode("-", $_POST['month']);
      $date = $getmonth[0];
      $searchmonth = "AND `orderInfo`.`orderDate` like '%".$_POST['month']."%'";
    }
  }

  $lastyear = $date - 1;
  $nextyear = $date + 1;
  $dis = "";
  if($date === date("Y")){
    $dis = "disabled";
  }
  $tradelist = array();
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
                    <a class="navbar-brand" href="#pablo"> 交易分析 </a>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card strpied-tabled-with-hover">
                                <div class="card-header ">
                                    <h4 class="card-title">交易分析</h4>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                      <input type="submit" name="year" value="<?=$lastyear?>">
                                      <input type="submit" name="year" value="<?=$nextyear?>" <?=$dis?>>
                                    </form>
                                </div>

                                <div class="card-body table-full-width table-responsive">
                                    <h4 class="subtitle">年分析</h4>
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <th>日期</th>
                                            <th>交易量</th>
                                            <th>交易总价</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $stmt = $pdo->prepare("SELECT count(*) AS `total`,sum(`orderPrice`) AS `price` FROM `orderInfo` WHERE `orderDate` LIKE '%$date%'");
                                              $stmt->execute();
                                              if($stmt != null){
                                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                  $total=$row['total'];
                                                  $price=round($row['price'],2);
                                            ?>
                                            <tr>
                                                <td><?=  $date?></td>
                                                <td><?=  $total?></td>
                                                <td>CAD$<?=  $price?></td>

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
                        <div style="max-height: 300px" class="col-md-6 pre-scrollable">
                            <div class="card strpied-tabled-with-hover">
                                <div class="card-body table-full-width table-responsive">
                                    <h4 class="card-title">月分析</h4>
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <th>日期</th>
                                            <th>交易量</th>
                                            <th>交易总价</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                              $tmpm = ($date == date("Y"))?date("m"):12;
                                              for($i=$tmpm;$i >= 1; $i--)
                                              {
                                                $month = $i;
                                                $month=str_pad($month,2,"0",STR_PAD_LEFT); 
                                                $month = $date."-".$month;
                                              $stmt = $pdo->prepare("SELECT count(*) AS `total`,sum(`orderPrice`) AS `price` FROM `orderInfo`
                                                                    WHERE `orderDate` LIKE '%$month%'");
                                              $stmt->execute();
                                              if($stmt != null){
                                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                  $total=$row['total'];
                                                  $price=round($row['price'],2);
                                            ?>
                                            <tr>
                                                <td><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                                  <input type="submit" name="month" value="<?=$month?>">
                                                </form></td>
                                                <td><?=  $total?></td>
                                                <td>CAD$<?=$price?></td>

                                            </tr>
                                            <?php
                                                }
                                              }
                                              }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php include("searchbar.php");?>
                        <div class="col-md-12">
                            <div class="card card-plain table-plain-bg">
                                <div class="card-header ">
                                    <h4 class="card-title">交易列表</h4>
                                </div>
                                <div class="card-body table-full-width table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>日期</th>
                                            <th>地址</th>
                                            <th>顾客名字</th>
                                            <th>保洁名字</th>
                                            <th>服务类型</th>
                                            <th>订单价格</th>
                                            <th>订单要求</th>
                                            <th>订单状态</th>
                                        </thead>
                                        <tbody id="tradelist">
                                            <?php
                                              $stmt = $pdo->prepare("SELECT `orderInfo`.*,`cleanerInfo`.`cleanerName`,`userInfo`.`userName` From `orderInfo` 
                                                                      JOIN `cleanerInfo`,`userInfo` 
                                                                    WHERE `orderInfo`.`cleanerId` = `cleanerInfo`.`cleanerId` 
                                                                    AND `orderInfo`.`userId` = `userInfo`.`userId` 
                                                                      ".$searchsql);
                                              $stmt->execute();
                                              if($stmt != null){
                                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                  $orderId=$row['orderId'];
                                                  $cleanerId=$row['cleanerId'];
                                                  $userId=$row['userId'];
                                                  $orderName=$row['orderName'];
                                                  $userName=$row['userName'];
                                                  $cleanerName=$row['cleanerName'];
                                                  $orderType=$row['orderType'];
                                                  $orderAddress=$row['orderAddress'];
                                                  $orderDate=$row['orderDate'];
                                                  $orderPrice=$row['orderPrice'];
                                                  $orderExtraRequire=$row['orderExtraRequire'];
                                                  $orderState=$row['orderState'];
                                                  switch ($orderState) {
                                                    case '0':
                                                      $orderState = "待接单";
                                                      break;                                                    
                                                    case '1':
                                                      $orderState = "已接单";
                                                      break;                                                    
                                                    case '2':
                                                      $orderState = "已确认";
                                                      break;                                                    
                                                    case '3':
                                                      $orderState = "已到达";
                                                      break;                                                    
                                                    case '4':
                                                      $orderState = "服务中";
                                                      break;                                                    
                                                    case '5':
                                                      $orderState = "服务完成/待确认";
                                                      break;                                                    
                                                    case '6':
                                                      $orderState = "服务完成/已确认";
                                                      break;                                                    
                                                    case '7':
                                                      $orderState = "已评论";
                                                      break;                                                    
                                                    case '8':
                                                      $orderState = "已取消";
                                                      break;                                                    
                                                    case '9':
                                                      $orderState = "仲裁中";
                                                      break;                                                    
                                                    case '10':
                                                      $orderState = "仲裁完成";
                                                      break;
                                                  }
                                            ?>
                                            <tr>
                                                <a href="">
                                                <td><?=  $orderDate?></td>
                                                <td><?=  $orderAddress?></td>
                                                <td><?=  $userName?></td>
                                                <td><?=  $cleanerName?></td>
                                                <td><?=  $orderType?></td>
                                                <td><?=  $orderPrice?></td>
                                                <td><?=  $orderExtraRequire?></td>
                                                <td><?=  $orderState?></td>
                                                </a>
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
              $("#resultlist").attr("class","nav-item active");
          }); 

        window.onload = function() {
            var tradelist = <?php echo json_encode($tradelist);?>; 
            tradelist.forEach(function(item) {
                $("#tradelist").append(' <tr><a href=""><td>'+item['Date']+" "+item['res']+'</td><td><img src="../'+item['Photo']+'" style="width: 50px;"></td><td>'+item['StoreName']+'</td><td>'+item['ServerName']+'</td><td>'+item['UserName']+'</td><td>'+item['Phone']+'</td><td>'+item['Price']+'</td><td>'+item['GetPoint']+'</td><td>'+item['RefName']+'</td></a></tr>');
            })
        }
    </script>
</html>
