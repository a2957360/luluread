<?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['search'])){
      $date              =  $_POST['date'];
      $name              =  $_POST['name'];
      $address           =  $_POST['address'];
      $fromprice         =  $_POST['fromprice'];
      $toprice           =  $_POST['toprice'];
      $type              =  $_POST['type'];

      $searchsql   .= ($_POST['date'] != "")?     "AND `orderInfo`.`orderDate` like '%".$_POST['date']."%'":"";
      $searchsql   .= ($_POST['name'] != "")?     "AND (`orderInfo`.`orderName` like '%".$_POST['name']."%'":"";
      $searchsql   .= ($_POST['name'] != "")?     "or `userInfo`.`userName` like '%".$_POST['name']."%'":"";
      $searchsql   .= ($_POST['name'] != "")?     "or `cleanerInfo`.`cleanerName` like '%".$_POST['name']."%')":"";
      $searchsql   .= ($_POST['address'] != "")?  "AND `orderInfo`.`orderAddress` like '%".$_POST['address']."%'":"";
      $searchsql   .= ($_POST['fromprice'] != "")?   "AND `orderInfo`.`orderPrice` >= ".$_POST['fromprice']:"";
      $searchsql   .= ($_POST['toprice'] != "")?   "AND `orderInfo`.`orderPrice` <= ".$_POST['toprice']:"";
      $searchsql   .= ($_POST['type'] != "")?   "AND `orderInfo`.`orderType` = ".$_POST['type']:"";

      if($stmt != null){
        }else{
          die('Error: ' . mysql_error());
      }
    }
  }
  $typeuplist= array();
  $stmt = $pdo->prepare("SELECT * FROM `cleanType` WHERE `typeState` = 1");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $typeuplist[] = $row;
    }
  }
?>
<div class="col-md-12">
    <div class="card card-plain table-plain-bg">
        <div class="card-header ">
            <h4 class="card-title">搜索栏</h4>
        </div>
        <div class="card-body table-full-width table-responsive">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
            <table class="table table-hover">
                <thead>
                    <th>日期</th>
                    <th>名字</th>
                    <th>地址</th>
                    <th>价格区间</th>
                    <th>服务类型</th>
                    <th>搜索</th>
                </thead>
                <tbody>

                    <tr>
                        <td><input type="text" name="date" value="<?=$date?>"></td>
                        <td><input type="text" name="name" value="<?=$name?>"></td>
                        <td><input type="text" name="address" value="<?=$address?>"></td>
                        <td><input type="text" name="fromprice" value="<?=$fromprice?>">-<input type="text" name="toprice" value="<?=$toprice?>"></td>
                        <td>
                          <select name="type">
                            <option value=""> </option>
                            <?PHP
                              foreach ($typeuplist as $key => $value) {
                                $select = "";
                                if($value['typeId'] == $type){
                                  $select = "selected";
                                }
                                echo "<option value='".$value['typeId']."' ".$select.">".$value['typeName']."</option>";
                              }
                            ?>
                            
                          </select>
                        </td>
                        <td><input type="submit" name="search" value="搜索"></td>
                    </tr>

                </tbody>
            </table>
          </form>
        </div>
    </div>
</div>
