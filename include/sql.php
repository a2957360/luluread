<?php
  ob_start();
  session_start(); 
  $dsn = "mysql:host=db5000313223.hosting-data.io;dbname=dbs305668";
  $sqlusername = 'dbu180333';
  $sqlpassword = 'Finestudio123@';
  $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
    if(!$pdo){
        die("can't connect".mysql_error());//如果链接失败输出错误
    }
  if(isset($_SESSION["userId"])){
    $userId = $_SESSION["userId"];
    $stmt = $pdo->prepare("SELECT * FROM `userTable` WHERE `userId` = '$userId';");
    $stmt->execute();
    if($stmt != null){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          $_SESSION["userInfo"] = $row;
        }
    }
  }
  $menuUserPic = empty($_SESSION["userInfo"]['userPic'])?"include/image/userimage.png":$_SESSION["userInfo"]['userPic'];
?>