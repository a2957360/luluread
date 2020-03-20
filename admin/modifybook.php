<?php
    include("sql.php");
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['changesubmit'])){
    	$bookId=$_POST['bookId'];
        $typeId=$_POST['typeId'];
        $bookName=$_POST['bookName'];
        $bookAuthor=$_POST['bookAuthor'];
        $bookTranslater=$_POST['bookTranslater'];
        $bookShortDescription=$_POST['bookShortDescription'];
        $bookDescription=$_POST['bookDescription'];
        $bookRecommend=$_POST['bookRecommend'];


        if($_FILES['bookPic']['name'] != null){
          $date= date('YmdHis');
          $File_type = strrchr($_FILES['bookPic']['name'], '.'); 
          $picture = 'include/pic/'.$bookId."/".$date.$File_type;
          $picsql = ", `bookPic`='".$picture."'";
        }

        $stmt = $pdo->prepare("UPDATE `bookTable` SET `typeId`='$typeId', `bookName`='$bookName'".$picsql.", `bookAuthor`='$bookAuthor', `bookTranslater`='$bookTranslater', `bookShortDescription`='$bookShortDescription', `bookDescription`='$bookDescription', `bookRecommend`='$bookRecommend' WHERE `bookId` = '$bookId'");
        $result = $stmt->execute();
        if($stmt != null){
            if (!is_dir('../include/pic/'.$bookId)) {
              mkdir('../include/pic/'.$bookId);
            } 
            if($_FILES['bookPic']['name'] != null){
                move_uploaded_file($_FILES['bookPic']['tmp_name'], "../".$picture);
            }    
      			header('location: '.$_SERVER['HTTP_REFERER']);
        }else{
            die('Error: ' . mysql_error());
        }
    }


    if(isset($_POST['addlanguage'])){
        $bookId=$_POST['bookId'];
        $language=$_POST['language'];
        $bookLanguage = empty($_POST['bookLanguage'])?"":$_POST['bookLanguage'].",";
        $bookLanguage .= $language;
        $stmt = $pdo->prepare("UPDATE `bookTable` SET `bookLanguage` = '$bookLanguage' WHERE `bookId` = '$bookId'");
        $result = $stmt->execute();
        if($stmt != null){
            header('location: '.$_SERVER['HTTP_REFERER']);
          }else{
            die('Error: ' . mysql_error());
        }
    }

    if(isset($_POST['changeLanguage'])){
        $bookId=$_POST['bookId'];
        $language=$_POST['language'];
        $oldlanguage=$_POST['oldlanguage'];
        $bookLanguage=$_POST['bookLanguage'];
        $bookLanguage = str_replace($oldlanguage,$language,$bookLanguage);

        $stmt = $pdo->prepare("UPDATE `bookTable` SET `bookLanguage` = '$bookLanguage' WHERE `bookId` = '$bookId'");
        $result = $stmt->execute();
        if($stmt != null){
            header('location: '.$_SERVER['HTTP_REFERER']);
          }else{
            die('Error: ' . mysql_error());
        }
    }

    if(isset($_POST['deleteLanguage'])){
        $bookId=$_POST['bookId'];
        $language=$_POST['language'];
        $oldlanguage=$_POST['oldlanguage'];
        $bookLanguage=$_POST['bookLanguage'];
        $bookLanguage = str_replace($oldlanguage.",","",$bookLanguage);
        $bookLanguage = str_replace(",".$oldlanguage,"",$bookLanguage);


        $stmt = $pdo->prepare("UPDATE `bookTable` SET `bookLanguage` = '$bookLanguage' WHERE `bookId` = '$bookId'");
        $result = $stmt->execute();
        if($stmt != null){
            header('location: '.$_SERVER['HTTP_REFERER']);
          }else{
            die('Error: ' . mysql_error());
        }
    }

  }
  $bookId=$_GET['bookId'];
  $stmt = $pdo->prepare("SELECT `bookTable`.*,`typeTable`.`typeName` FROM `bookTable` 
              LEFT JOIN `typeTable` ON `typeTable`.`typeId` = `bookTable`.`typeId`
              WHERE `bookId` = '$bookId'");
  $stmt->execute();
  if($stmt != null){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $typeName=$row['typeName'];
      $typeId=$row['typeId'];
      $bookPic="../".$row['bookPic'];
      $bookName=$row['bookName'];
      $bookLanguage = $row['bookLanguage'];
      $bookLanguageList = explode(",",$row['bookLanguage']);
      $bookAuthor=$row['bookAuthor'];
      $bookTranslater=$row['bookTranslater'];
      $bookShortDescription=$row['bookShortDescription'];
      $bookDescription=$row['bookDescription'];
      $bookRecommend=($row['bookRecommend'] == 1)?"checked":"";
      $chapterNum=$row['chapterNum'];
      $viewTime=$row['viewTime'];
      $createTime=$row['createTime'];
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
    $chapterlist= array();
    $stmt = $pdo->prepare("SELECT * FROM `chapterTable` WHERE `bookId`='$bookId' ORDER BY `chapterNo`");
    $stmt->execute();
    if($stmt != null){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $chapterlist[] = $row;
        }
    }

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
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <div id="bg">
    </div>
    <div class="wrapper">
        <?php include("header.php");?>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg " color-on-scroll="500">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Table List</a>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 book">
                            <form class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data' class="card">
                              <input type="hidden" name="bookId" value="<?=$bookId?>">
                                <div class="col-md-4 userinfoline">
                                    <p>图书封面</p>
                                    <div style="background:url(<?=$bookPic?>);background-color: lightgrey" class="fileInputContainer" id="preview1">
                                        <input class="fileInput" type="file" name="bookPic" id="" onchange="imgPreview(this,'preview1')"/>
                                    </div>
                                </div>

                              <div class="col-md-6 userinfoline">
                                <p>图书信息</p>
                                <div class="userinfoline">书名：<input type="text" name="bookName" value="<?=  $bookName?>"></div>
                                <div class="userinfoline">作者：<input type="text" name="bookAuthor" value="<?=  $bookAuthor?>"></div>
                                <div class="userinfoline">译者：<input type="text" name="bookTranslater" value="<?=  $bookTranslater?>"></div>
                                <div class="userinfoline">
                                    分类：<select name="typeId">
                                    <?php
                                        foreach ($typeshowlist as $key => $value) {
                                        	$selected = "";
                                        	if($typeId == $value['typeId']){
                                        		$selected = "selected";
                                        	}
                                            echo "<option value='".$value['typeId']."' ".$selected.">".$value['typeName']."</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="userinfoline">推荐：
                                    <label><input type="radio" name="bookRecommend" value="0" checked="">No</label>&nbsp;
                                    <label><input type="radio" name="bookRecommend" value="1" <?=$bookRecommend?>>Yes</label>
                                </div>

                              </div>
                            <div class="col-md-12 userinfoline">
                                <p>图书介绍</p>
                                <div class="userinfoline"><textarea name="bookShortDescription" placeholder="简短介绍"><?=$bookShortDescription?></textarea></div>
                                <div class="userinfoline"><textarea name="bookDescription" placeholder="完整介绍"><?=$bookDescription?></textarea></div>
                                <?php
                                    if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                ?>
                                <input type="submit" name="changesubmit" value="修改">
                                <?php
                                    }
                                ?>
                              </div>
                            </form>
                        </div>

                        <div id="chaptermodule" class="col-md-12 chapter hide">
                            <form id="chapter" class="row" action="" method="POST" enctype='multipart/form-data' class="card">
                            <div class="col-md-12 userinfoline">
                                <input type="hidden" name="bookId" value="<?=$bookId?>">
                                <input type="hidden" name="chapterLanguage" value="<?=$chapterLanguage?>">
                                <div class="userinfoline">章节号：<input type="text" name="chapterNo" value="<?=  $maxchapter?>" required></div>
                                <div class="userinfoline">章节名：<input type="text" name="chapterName" value="<?=  $chapterName?>" required>
                                    <label class="radiobtn"><input type="radio" name="chapterState" value="" onclick="changeprice('free')" checked="">免费</label>
                                    <label class="radiobtn"><input type="radio" name="chapterState" value="" onclick="changeprice('price')">收费</label>
                                    <input id="chapterPrice" type="number" name="chapterPrice" value="0" readonly="">
<!--                                     <select name="chapterState">
                                        <option>免费</option>
                                        <option>收费</option>
                                    </select> -->
                                </div>
                                <div class="userinfoline"><textarea name="chapterContent" placeholder="内容" required><?=$chapterContent?></textarea></div>
                                <?php
                                    if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                ?>
                                <input type="hidden" name="chapterAdd" value="1">
                                <input type="button" name="changesubmit" value="添加" onclick="runsql('addchapter.php','chapter')">
                                <?php
                                    }
                                ?>
                              </div>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <div class="col-md-12 mb20">
                                <form class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data' class="card">
                                    <input type="hidden" name="bookLanguage" value='<?=$bookLanguage?>'>
                                    <input type="hidden" name="bookId" value="<?=$bookId?>">
                                    <input type="text" name="language">
                                    <input type="submit" name="addlanguage" value="添加语言">
                                </form>
                            </div>
                            <?php
                                foreach ($bookLanguageList as $languagekey => $languagevalue) {
                                    if($languagevalue == ""){
                                        continue;
                                    }
                            ?>
                            <div class="panel-group" id="booklist">
                            <div class="panel panel-default">
                                <div class="panel-heading mb20">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#booklist" 
                                           href="#<?=$languagevalue?>">
                                            <?=$languagevalue?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="<?=$languagevalue?>" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div  class="col-md-12 mb20">
                                            <a class="abtn"  onclick="showcomponent('chaptermodule'),changestate('chapterLanguage','<?=$languagevalue?>')">添加<?=$languagevalue?>章节</a>

                                        </div>
                                        <div class="card strpied-tabled-with-hover">
                                            <div class="card-header ">
                                                <form class="row inlineblock" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
                                                    <input type="text" name="language" value="<?=$languagevalue?>">章节列表
                                                    <input type="hidden" name="oldlanguage" value="<?=$languagevalue?>">
                                                    <input type="hidden" name="bookLanguage" value="<?=$bookLanguage?>">
                                                    <input type="hidden" name="bookId" value="<?=$bookId?>">
                                                    <input type="submit" name="changeLanguage" onclick="return sumbit_sure('确认要修改？')" value="修改名字">
                                                    <input type="submit" name="deleteLanguage" onclick="return sumbit_sure('确认要删除？')" value="删除该语言">
                                                </form>
                                            </div>
                                            <div class="card-body table-full-width table-responsive">
                                                <table class="table table-hover table-striped">
                                                    <thead>
                                                        <th>章节</th>
                                                        <th>名字</th>
                                                        <th>字数</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($chapterlist as $key => $value) {
                                                            if($value['chapterLanguage'] != $languagevalue){
                                                                continue;
                                                            }
                                                        ?>
                                                        <tr id="chapter<?=  $value['chapterId']?>">
                                                            <td><?=  $value['chapterNo']?></td>
                                                            <td><a href="modifychapter.php?bookId=<?=$bookId?>&chapterId=<?=$value['chapterId']?>"><?=  $value['chapterName']?></a></td>
                                                            <td><?=  $value['chapterWords']?></td>
                                                            <td>
                                                                <form class="row" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data' class="card">
                                                                <?php
                                                                    if(isset($_SESSION["Role"]) && $_SESSION["Role"] == '0'){
                                                                ?>
                                                                <input type="hidden" name="chapterId" value="<?=$value['chapterId']?>">
                                                                <input type="hidden" name="chapterDel" value="1">
                                                                <input type="button" name="delete" value="删除" onclick="delsql('addchapter.php','chapter<?=$value['chapterId']?>')">
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
                            <?php
                                }
                            ?>

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
<script src="assets/js/plugins/chartist.min.js"></script>
<!--  Notifications Plugin    -->
<script src="assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
<script src="assets/js/light-bootstrap-dashboard.js?v=2.0.0 " type="text/javascript"></script>
<script src="assets/js/finestudio.js"></script>
<script type="text/javascript">
      $(document).ready(function() { 
          $("#booklist").attr("class","nav-item active");
      }); 
function sumbit_sure(words){
    var gnl=confirm(words);
    if (gnl==true){
    return true;
    }else{
    return false;
    }
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFObd_Eh5_4JGY1qMy9g8DFhqxxcVILyI">
</script>
</html>
