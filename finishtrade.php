<?php
  include("include/sql.php");
  http_response_code(200);
  header('content-type:application/json;charset=utf8');
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

  $userId=$_POST['userId'];
  $amount=$_POST['amount'];
  $transactionMethod=$_POST['transactionMethod'];



  $stmt = $pdo->prepare("UPDATE `userTable` SET `luluCoin` =`luluCoin` + '$amount' WHERE `userId` = '$userId'");
  $stmt->execute();
  if($stmt != null){
    $stmt = $pdo->prepare("INSERT INTO `transactionTable` (`userId`, `transactionMethod`, `transactionCoin`, `transactionAmount`) 
                                                    VALUES ('$userId','$transactionMethod','$amount',(SELECT `luluCoin` FROM `userTable` WHERE `userId` = '$userId'))");
    $stmt->execute();
    echo json_encode(["message"=>"success"]);
  }else{
    echo json_encode(["message"=>"fail"]);
  }

?>