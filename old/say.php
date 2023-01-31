<?php
//error_reporting(0);
session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
$uid=$_SESSION['userid'];

//$result=mysql_query("SELECT * FROM user WHERE name=$name");
$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);



$list=0;

for($i=0;$i<$t_num;$i++)
{
    $result_arr=mysqli_fetch_assoc($result_t_1);
    if($result_arr['classid']==$_SESSION['classid']){
        $list++;
    }
}


if($_GET['action']=="post"){
    $title=$_POST['title'];
    $content=$_POST['content'];
    $banwei=$_POST['banwei'];
    $shiming=$_POST['shiming'];
    //$classid=$_SESSION['classid'];
    $name=$_SESSION['username'];
    $time=date("Y-m-d H:i:s",time());
    
    mysqli_query($conn,"INSERT INTO say(banwei,title,content,shiming,author,time) VALUES ('$banwei','$title','$content','$shiming','$name','$time')");
    
    
    if(mysqli_error($conn))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($conn).'"}'); 
    }else{
        exit('{"msg":"success","datas":"'.$title.'"}');
    }
    
}



$title="我对班委有话说";



?>



  <?php include 'head.php';?>
  <style>
  canvas {
  vertical-align: middle;
  width: 100px;
  height: 34px;
  box-sizing: border-box;
  border: 1px solid #ddd;
  cursor: pointer;
  }
 </style>
  <?php include ("left.php"); ?>
  <div id="content" class="app-content" role="main">
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><?=$title?></li>
					</ul>
				</div>
  <!-- / aside -->
<div class="wrapper" id="post" style="display:block">
    <div class="col-sm-12" id="ljc_bg">
    <div class="panel panel-default">
    <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >我对班委有话说</div>
    <div class="well well-sm" style="margin: 0;" id="ljc">可以建议、批评、指正、求助等，想说的都可以说哦！</div>
    <div class="panel-body">
      <form name="RegForm" id="RegForm" enctype="multipart/form-data" action="?action=post" method="post" role="form">
        <div class="form-group">
    	  <label>留言对象:</label><br/>
    	  <select id="banwei" class="selectpicker show-tick form-control">
    			<option value="1">班长</option>
    			<option value="2">团支书</option>
    			<option value="3">副班长</option>
    			<option value="4">学习委员</option>
    			<option value="5">组织委员</option>
    			<option value="6">文体委员</option>
    			<option value="7">生劳委员</option>
    	  </select>
    	</div>
    	<div class="form-group">
    	  <label>主题:</label><br/>
    	  <input type="text" name="title" id="title" value="" class="form-control"/>
    	</div>
    	<div class="form-group">
    	  <label>内容:</label><br/>
    	  <textarea type="text" name="s_content" id="s_content" rows=6 value="" class="form-control"/></textarea>
    	</div>
        <div class="form-group">
    	  <label>是否实名:</label><br/>
    	  <select id="shiming" class="selectpicker show-tick form-control">
    			<option value="1">实名提交</option>
    			<option value="2">匿名提交</option>
    	  </select>
    	</div>
    	<div class="form-group">
            <label>验证码:</label><br/>
    		<input type="text" id="yz_code" name="yz_code" value="" class="form-control" placeholder="请输入验证码（不区分大小写）">
            <canvas id="canvas"></canvas>
    	</div>
    	  <br>
    	  
    	  <br><p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
    	  
    	
    	<div class="form-group">
    	    
    	        <input type="submit" name="submit" value="提交" class="btn btn-primary form-control"/>
    	  
    	</div>
      </form>
      </div>
      <div class="panel-footer">
              <span class="glyphicon glyphicon-info-sign"></span> <font color="red">你的建议对我们班委十分重要！请放心，所有留言都只有对应的班委可以查看，并且不会外泄。若匿名提交，则不会上传提交者的信息。</font>
        </div>
    </div>
    </div>
</div>


<div class="wrapper" id="after_post" style="display:none">
    <div class="col-sm-12" id="ljc_bg">
    <div class="panel panel-success">
    <div class="panel-heading font-bold" style="background-color: #7CCD7C;color: white;" >提交成功</div>
    <div class="panel-body">
      <h2>提交成功！感谢你的反馈！</h2><br><a href="./index" class="btn btn-success"/>返回首页</a>
      </div>
      <div class="panel-footer">
              <span class="glyphicon glyphicon-info-sign"></span> <font color="red">你的建议对我们班委十分重要！</font>
        </div>
    </div>
    </div>
</div>
    
    
    </div>
</div>
  
  

<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>
  
<script language="JavaScript">
var show_num = [];
$(function(){

    draw(show_num);
    $("#canvas").on('click',function(){
        draw(show_num);
    })
})

    $(function () {
        $('#RegForm').on('submit', function (e) {  
            document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
            e.preventDefault();
            var title=$('#title').val();
            var content=$('#s_content').val();
            var shiming=$('#shiming').val();
            var banwei=$('#banwei').val();
            var yz_code=$('#yz_code').val().toLowerCase();
            console.log(content)
            
            if(title==""){
                toastr.error("请输入主题!");
                $('#title').focus();
                return false;
            }
            if(content==""){
                toastr.error("请输入内容!");
                $('#s_content').focus();
                return false;
            }
            if(yz_code==""){
                toastr.error("请输入验证码!");
                $('#yz_code').focus();
                return false;
            }
            else if(yz_code != show_num.join("")){
                toastr.error("验证码错误!");
                $('#yz_code').focus();
                return false;
            }
        
            
            $.ajax({
                type:'post',
                url:'?action=post',
                data:{
                    title:title,
                    content:content,
                    shiming:shiming,
                    banwei:banwei
                },
                dataType:'json',
                success: function(res){
                    console.log(res)
                    document.getElementById('post').style="display:none;";
                    document.getElementById('after_post').style="display:block;";
                }
            })
        })
    })
</script>
<script>

function draw(show_num) {
    var canvas_width=$('#canvas').width();
    var canvas_height=$('#canvas').height();
    var canvas = document.getElementById("canvas");
    var context = canvas.getContext("2d");
    canvas.width = canvas_width;
    canvas.height = canvas_height;
    var sCode = "a,b,c,d,e,f,g,h,i,j,k,m,n,p,q,r,s,t,u,v,w,x,y,z,A,B,C,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,W,X,Y,Z,1,2,3,4,5,6,7,8,9,0";
    var aCode = sCode.split(",");
    var aLength = aCode.length;
    for (var i = 0; i < 4; i++) {
        var j = Math.floor(Math.random() * aLength);
        var deg = Math.random() - 0.5;
        var txt = aCode[j];
        show_num[i] = txt.toLowerCase();
        var x = 10 + i * 20;
        var y = 20 + Math.random() * 8;
        context.font = "bold 23px 微软雅黑";
        context.translate(x, y);
        context.rotate(deg);
        context.fillStyle = randomColor();
        context.fillText(txt, 0, 0);
        context.rotate(-deg);
        context.translate(-x, -y);
    }
    for (var i = 0; i <= 5; i++) {
        context.strokeStyle = randomColor();
        context.beginPath();
        context.moveTo(Math.random() * canvas_width, Math.random() * canvas_height);
        context.lineTo(Math.random() * canvas_width, Math.random() * canvas_height);
        context.stroke();
    }
    for (var i = 0; i <= 30; i++) {
        context.strokeStyle = randomColor();
        context.beginPath();
        var x = Math.random() * canvas_width;
        var y = Math.random() * canvas_height;
        context.moveTo(x, y);
        context.lineTo(x + 1, y + 1);
        context.stroke();
    }
}

function randomColor() {
    var r = Math.floor(Math.random() * 256);
    var g = Math.floor(Math.random() * 256);
    var b = Math.floor(Math.random() * 256);
    return "rgb(" + r + "," + g + "," + b + ")";
}

</script>

