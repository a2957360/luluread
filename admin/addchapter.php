<?php
    include("sql.php");
    header('Content-Type:text/json;charset=utf-8');
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['chapterAdd'])){
            $bookId=$_POST['bookId'];
            $chapterNo=$_POST['chapterNo'];
            $chapterName=$_POST['chapterName'];
            $chapterContent=addslashes($_POST['chapterContent']);
            $chapterWords=mb_strlen($_POST['chapterContent'], 'UTF-8');
            $chapterLanguage=$_POST['chapterLanguage'];
            $chapterState=$_POST['chapterState'];
            $chapterPrice=$_POST['chapterPrice'];

            if($chapterContent == "" || $chapterName == ""){
                exit();
            }

            $stmt = $pdo->prepare("INSERT INTO `chapterTable`(`bookId`, `chapterNo`, `chapterLanguage`, `chapterName`, `chapterWords`, `chapterState`, `chapterPrice`) 
                                  VALUES ('$bookId','$chapterNo','$chapterLanguage','$chapterName','$chapterWords','$chapterState','$chapterPrice')");
            $stmt->execute();
            if($stmt != null){
                $chapterId = $pdo->lastinsertid();
                $stmt = $pdo->prepare("INSERT INTO `contentTable`(`chapterId`, `chapterContent`) 
                                      VALUES ('$chapterId','$chapterContent')");
                $stmt->execute();
                if($stmt != null){
                  $stmt = $pdo->prepare("SELECT * FROM `chapterTable` WHERE `chapterId` = '$chapterId'");
                  $stmt->execute();
                  $result =array();
                  if($stmt != null){
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $result[] = $row;
                    }
                  }
                    echo json_encode($result);
                    exit();
                }
            }else{
                die('Error: ' . mysql_error());
            }
        }
        if(isset($_POST['chapterDel'])){
            $chapterId=$_POST['chapterId'];
            $stmt = $pdo->prepare("DELETE FROM `chapterTable` WHERE `chapterId` = '$chapterId'");
            $stmt->execute();
            $stmt = $pdo->prepare("DELETE FROM `contentTable` WHERE `chapterId` = '$chapterId'");
            $stmt->execute();
            if($stmt != null){
                echo json_encode(["message"=>"success"]);
                exit();
              }else{
                die('Error: ' . mysql_error());
            }
        }
  }

  $bookId=$_GET['bookId'];
  $chapterLanguage=$_GET['chapterLanguage'];
  $stmt = $pdo->prepare("SELECT `bookTable`.*,`typeTable`.`typeName` FROM `bookTable` 
              LEFT JOIN `typeTable` ON `typeTable`.`typeId` = `bookTable`.`typeId`
              WHERE `bookId` = '$bookId'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $bookId=$row['bookId'];
      $typeName=$row['typeName'];
      $typeId=$row['typeId'];
      $bookPic='../'.$row['bookPic'];
      $bookName=$row['bookName'];
      $bookAuthor=$row['bookAuthor'];
      $bookTranslater=$row['bookTranslater'];
      $bookShortDescription=$row['bookShortDescription'];
      $bookDescription=$row['bookDescription'];
      $chapterNum=$row['chapterNum'];
      $viewTime=$row['viewTime'];
      $createTime=$row['createTime'];
    }
  }
    $stmt = $pdo->prepare("SELECT MAX(chapterNo) AS `maxchapter` FROM `chapterTable` WHERE `bookId`='$bookId'");
    $stmt->execute();
    if($stmt != null){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $maxchapter = empty($row['maxchapter'])?0:$row['maxchapter'];
            $maxchapter++;
        }
    }

?>
