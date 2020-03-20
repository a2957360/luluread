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
    // if(!isset($_SESSION["ManageName"])){
    //   echo "<script> location.href='login.php'; </script>";
    // }
    $_SESSION["Role"] = 0;
?>