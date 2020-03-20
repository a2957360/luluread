function sumbit_sure(words){
  var gnl=confirm(words);
  if (gnl==true){
    return true;
  }else{
    return false;
  }
}
$(".chaptercontent").click(function(){
  if($(".footer").css("display") == "none"){
    $(".chapterheader").show();
    $(".footer").show();
  }else{
    $(".chapterheader").hide();
    $(".footer").hide();

  }
});
function closeall(){
	$(".addon").each(function(){
		$(this).css("display","none");
	});
}
function showfont(){
	var state = $(".fontset").css("display");
	if(state=="none"){
		$(".fontset").css("display","block");
	}else{
		$(".fontset").css("display","none");
	}
}

function changefontsize(type){
  var fontsize = $(".chaptercontent").css("font-size");
  var fontsize = parseFloat(fontsize , 10);
	if(type=="increase"){
		fontsize = (fontsize<40)?fontsize+4:fontsize;
	}else{
		fontsize = (fontsize>4)?fontsize-4:fontsize;
	}
    $(".chaptercontent").css("font-size",fontsize);
    $(".slider").val(fontsize);

}
function setfontsize(){
  var fontsize = $(".slider").val();
  $(".chaptercontent").css("font-size",fontsize+"px");
}

function changelight(colortype){
  if($("body").hasClass("dark")){
    $("body").removeClass("dark");
  }else{
    $("body").addClass("dark");
  }

}
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

function imgPreviewbyajax(fileDom){
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
    var img = document.getElementById("preview");

        var image = new Image();
        image.src = e.target.result
        var canvas = document.createElement("canvas");
        image.onload = function (imageEvent) {
          var width = 400;
          var height = 400;
          EXIF.getData(image, function() {
          EXIF.getAllTags(this);
          Orientation = EXIF.getTag(this, 'Orientation');
          });
          var cxt = canvas.getContext('2d');
          if(Orientation == 3) {
          canvas.width = width;
          canvas.height = height;
          cxt.rotate(Math.PI);
          cxt.drawImage(image, 0, 0, -width, -height);
          }
          else if(Orientation == 8) {
          canvas.width = height;
          canvas.height = width;
          cxt.rotate(Math.PI * 3 / 2);
          cxt.drawImage(image, 0, 0, -width, height);
          }
          else if(Orientation == 6) {
          canvas.width = height;
          canvas.height = width;
          cxt.rotate(Math.PI / 2);
          cxt.drawImage(image, 0, 0, width, -height);
          }
          else {
          canvas.width = width;
          canvas.height = height;
          cxt.drawImage(image, 0, 0, width, height);
          }
          dataUrl = canvas.toDataURL('image/jpeg');
          img.style.backgroundImage = "url('"+dataUrl+"')";
        };

};
  reader.readAsDataURL(file);
}
  function doUpload() {  
    $("#uploadpic").remove();
    var formData = new FormData($("#rform" )[0]);  
    if(dataUrl == ""){
      alert("请选择图片！");
        location.href='signup.php';
    }
    var blob = dataURLtoBlob(dataUrl);
    formData.append("pic", blob, "a.jpg");
    $.ajax({  
      url: 'signup.php',
      type: 'POST',  
      dataType: "Json",
      data: formData,  
      async: false,  
      cache: false,  
      contentType: false,  
      processData: false,  
      success: function (returndata) {  
        alert(returndata.responseText);
        // location.href='signupsuccess.html';
      },  
      error: function (returndata) {  
        if(returndata.responseText == "success"){
            location.href='signupsuccess.html';
        }
        alert(returndata.responseText);
      }
    });  
  }

function dataURLtoBlob(dataurl) { 
    var arr = dataurl.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], { type: mime });
}

function showcategory(getid){
  $(".hide").each(function(){
    $(this).hide();
  })
  $(".catogory").each(function(){
    $(this).removeClass("active");
  })
  $("."+getid+"btn").addClass("active");
  $("."+getid).show();
}

function showbookdetail(getid){
  $(".hide").each(function(){
    $(this).hide();
  })
  $(".btnblock").each(function(){
    $(this).removeClass("active");
  })
  $("."+getid+"btn").addClass("active");
  $("."+getid).css("display","flex");
}
function showmenu(){
  $(".menublock").removeClass("menuhide");
  $(".menublock").show();
}

$(".menublock").click(function(){
  $(".menublock").addClass("menuhide");
  setTimeout(function(){ $(".menublock").hide(); }, 300);
})

function writereview(){
  $(".writereview").show();
}
function closewritereview(){
  $(".writereview").hide();
}

$(".writereview").click(function(){
  $(".writereview").hide();
})


function showcontribute(){
  $(".contribute").show();
}
function closecontribute(){
  $(".contribute").hide();
}

$(".contribute").click(function(){
  $(".contribute").hide();
})

function showcoin(){
  $(".blackbg").css("display","flex");
}

$(".blackbg").click(function(){
  $(".blackbg").hide();
})

function showfilter(){
  $(".filter").show();
}
function closefilter(){
  $(".filter").hide();
}

$(".filter").click(function(){
  $(".filter").hide();
})

function showcard(){
  $(".addcard").show();
}

$(".addcard").click(function(){
  $(".addcard").hide();
})

function showuserinfo(){
  $(".userinfo").css("display","flex");
}
function closeuserinfo(){
  $(".userinfo").hide();
}


function changeedit(type){
  if(type == "open"){
    $(".openbtn").hide();
    $(".closebtn").show();
    $(".removebtn").show();
    $(".select").each(function(){
      $(this).show()
    })
  }else{
    $(".openbtn").show();
    $(".closebtn").hide();
    $(".removebtn").hide();
    $(".select").each(function(){
      $(this).hide()
    })
  }
}

function changefilter(num){
  $(".bookblock").each(function(){
      $(this).show();
    var start = $(this).data("star");
    var tmp = $(".componentup").data("star");
    if(start < num){
      $(this).hide();
    }
  })
  $(".filter").hide();
}

function changechapter(chapterid,userId){
    $.ajax({  
      url: 'changechapter.php',
      type: 'POST',  
      dataType: "json",
      data:{'chapterId':chapterid,'userId':userId}, 
      async: false,  
      cache: false,  
      // contentType: false,  
      // processData: false,  
      success: function (returndata) {  
        $(".chaptertitle").text(returndata['chapterName']);
        $(".chaptercontent").text(returndata['chapterContent']);
        if(returndata['lock'] == "hide"){
          $(".blockbg").hide();
        }else{
          $(".blockbg").show();
          $(".blockbg").find("input[name='chapterId']").val(returndata['chapterId']);
          $(".blockbg").find("input[name='chapterPrice']").val(returndata['chapterPrice']);

        }
        if(returndata['prevChapter'] == ""){
          $(".prev").hide();
          $(".prevhome").show();
        }else{
          $(".prevhome").hide();
          $(".prev").show();
          $(".prev").attr("onclick","changechapter("+returndata['prevChapter']+","+returndata['userId']+")");
          $(".prev").attr("href","#");
          $(".prev").text("Previous");
        }

        if(returndata['nextChapter'] == ""){
          $(".next").hide();
          $(".nexthome").show();
        }else{
          $(".nexthome").hide();
          $(".next").show();
          $(".next").attr("href","#");
          $(".next").attr("onclick","changechapter("+returndata['nextChapter']+","+returndata['userId']+")");
          $(".next").text("Next");

        }
      },  
      error: function (returndata) {  
      }
    });  
}
function changeamount(value){
  amount = value;
}
function finishtrade(){
    $.ajax({  
      url: 'finishtrade.php',
      type: 'POST',  
      dataType: "json",
      data:{'amount':amount,'userId':userId,'transactionMethod':transactionMethod}, 
      async: false,  
      cache: false,  
      // contentType: false,  
      // processData: false,  
      success: function (returndata) {  
       if (returndata['message'] == "success") {
        alert("Your top up is finished");
       }
      },  
      error: function (returndata) {  
      }
    });  
}
function renderbook(booklist){

}