<?php
  include("include/sql.php");
  http_response_code(200);
  header('content-type:application/json;charset=utf8');
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
  $chapterId=$_POST['chapterId'];
  $chapeterinfo = array();
  $chapeterinfo['chapterId'] = $chapterId;
  $stmt = $pdo->prepare("SELECT `chapterTable`.*,`contentTable`.`chapterContent` FROM `chapterTable` JOIN `contentTable` 
                        WHERE `chapterTable`.`chapterId`=`contentTable`.`chapterId` 
                        AND `chapterTable`.`chapterId`='$chapterId'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $chapeterinfo=$row;
      $maxchapter = $row['chapterNo'];
      $bookId = $row['bookId'];
      $chapterLanguage = $row['chapterLanguage'];
      $chapterState = $row['chapterState'];
    }
  }
  $chapeternolist = array();
  $chapeteridlist = array();
  $stmt = $pdo->prepare("SELECT * FROM `chapterTable` 
                        WHERE `bookId`='$bookId' AND `chapterLanguage`='$chapterLanguage' ORDER BY `chapterNo`");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $chapeternolist[]=$row['chapterNo'];
      $chapeteridlist[]=$row['chapterId'];
    }
  }

  $userBooklist = array();
  $userId = $_POST['userId'];
  $chapeterinfo['userId'] = $userId;
  $stmt = $pdo->prepare("SELECT `chapterList` FROM `userBook` WHERE `bookId` = '$bookId' AND `userId` = '$userId'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $userBooklist = (explode(",",$row['chapterList']));
    }
  }

  if(!in_array($chapterId, $userBooklist) && $maxchapter > 5 && $chapterState == 1){
    $chapeterinfo['lock'] = "";
    $chapeterinfo['chapterContent'] = substr($chapeterinfo['chapterContent'],0,100);
  }else{
    $chapeterinfo['lock'] = "hide";
  }

  $offset=array_search($maxchapter,$chapeternolist);
  $chapeterinfo['nextChapter'] = isset($chapeteridlist[$offset + 1])?$chapeteridlist[$offset + 1]:"";
  $chapeterinfo['prevChapter'] = isset($chapeteridlist[$offset - 1])?$chapeteridlist[$offset - 1]:"";
  echo json_encode($chapeterinfo);
?>