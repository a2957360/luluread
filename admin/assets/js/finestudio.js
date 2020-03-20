function imgPreview(fileDom,prev){
    //判断是否支持FileReader
    if (window.FileReader) {
        var reader = new FileReader();
    } else {
        alert("您的设备不支持图片预览功能，如需该功能请升级您的设备！");
    }

    //获取文件
    var file = fileDom.files[0];
    var imageType = /^image\//;
    //是否是图片
    if (!imageType.test(file.type)) {
        alert("请选择图片！");
        return;
    }
    //读取完成
    reader.onload = function(e) {
        //获取图片dom
        var img = document.getElementById(prev);
        //图片路径设置为读取的图片
        img.style.backgroundImage = "url('"+e.target.result+"')";
    };
    reader.readAsDataURL(file);
}
function sumbit_sure(value){
    var gnl=confirm(value);
    if (gnl==true){
        return true;
    }else{
        return false;
    }
}
function showcomponent(name){
    $("#"+name).show();
    $("#bg").show();
}
function changestate(name,value){
    $("#chaptermodule").find("input[name="+name+"]").val(value);
}


$("#bg").click(function(){
    if(sumbit_sure("确认关闭吗")){
        closeall();
    }
});

function closeall(){
    $(".hide").hide();
    $("#bg").hide(); 
}

//添加
function runsql(page,form) {
    var gnl=confirm("确定要添加?");
    if (gnl==true){
      var data = new FormData($('#'+form)[0]);
      $.ajax({
          type: "POST",//方法
          url: page ,//表单接收url
          data: data,
          processData : false,
          contentType: false,
          success: function (data) {
              clearform(form);
              data.forEach(function(item) {
                addchapter(item);
              })
              closeall();
              // var result=document.getElementById("success");
              // result.innerHTML="成功!";
          },
          error : function(data) {
            //提交失败的提示词或者其他反馈代码
              // var result=document.getElementById("success");
              // result.innerHTML="失败!";
          }
      });
    }
}
//删除
function delsql(page,formname) {
    var gnl=confirm("确定要删除?");
    if (gnl==true){
      var data = new FormData($('#'+formname).find("form")[0]);
      $.ajax({
          type: "POST",//方法
          url: page ,//表单接收url
          data: data,
          processData : false,
          contentType: false,
          success: function (data) {
            if(data['message'] == "success"){
                $('#'+formname).remove();
            }
              // var result=document.getElementById("success");
              // result.innerHTML="成功!";
          },
          error : function(data) {
            //提交失败的提示词或者其他反馈代码
              // var result=document.getElementById("success");
              // result.innerHTML="失败!";
          }
      });
    }
}

function addchapter(item){
var addcomponent =  '<tr id="chapter'+item["chapterId"]+'">'+
                    '    <td>'+item["chapterNo"]+'</td>'+
                    '    <td><a href="modifychapter.php?bookId=<?=$bookId?>&chapterId=">'+item["chapterName"]+'</a></td>'+
                    '    <td>'+item["chapterWords"]+'</td>'+
                    '    <td>'+
                    '        <form class="row" method="POST" enctype="multipart/form-data" class="card">'+
                    '        <input type="hidden" name="chapterId" value="'+item["chapterId"]+'">'+
                    '        <input type="hidden" name="chapterDel" value="'+item["chapterId"]+'">'+
                    '        <input type="button" name="delete" value="删除" onclick="delsql('+"'"+'addchapter.php'+"'"+','+"'"+'chapter'+item["chapterId"]+"'"+')">'+
                    '        </form>'+
                    '    </td>'+
                    '</tr>';
$("#"+item['chapterLanguage']).find("tbody").append(addcomponent);
}

function clearform(target){
    $('#'+target).find("input[type='text']").each(function(){
        $(this).val("");
      });
    $('#'+target).find(".textblock").val("");
}