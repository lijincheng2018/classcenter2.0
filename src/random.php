
<?php

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
$result_t=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
$t_num=mysqli_num_rows($result_t);


if($_GET['action']=="start")
{
    $randnum=mt_rand(1,$t_num);
	$result_get=mysqli_query($conn,"SELECT * FROM user WHERE uid=$randnum");
	$row_get=mysqli_fetch_assoc($result_get);
    $name=$row_get['name'];
    exit('{"code":"200","result":"'.$name.'"}');
}

$title="随机抽号";


?>



  <?php include 'head.php';?>
<style>
	.wrap{ float: left; /* 自适应内容宽度 */ position: relative; left: 50%; margin:30px auto; font-family:"微软雅黑";}
	.show{ position: relative; left: -50%; width:300px; height:300px; background-color:#ff3300; line-height:300px; text-align:center; color:#fff; font-size:50px; -moz-border-radius:150px; -webkit-border-radius:150px; border-radius:150px; background-image: -webkit-gradient(linear,0% 0%, 0% 100%, from(#61ffa4), to(#38ff8d), color-stop(0.5,#00d159)); -moz-box-shadow:2px 2px 10px #BBBBBB; -webkit-box-shadow:2px 2px 10px #BBBBBB; box-shadow:2px 2px 10px #BBBBBB;}
	.btn1 a{position: relative; left: -50%; display:block; width:120px; height:50px; margin:30px auto; text-align:center; line-height:50px; text-decoration:none; color:#fff; -moz-border-radius:25px; -webkit-border-radius:25px; border-radius:25px;}
	.btn1 a.start{ background:#42beff;}
	.btn1 a.start:hover{ background:#0097e6;}
	.btn2 a{position: relative; left: -50%; display:block; width:120px; height:50px; margin:30px auto; text-align:center; line-height:50px; text-decoration:none; color:#fff; -moz-border-radius:25px; -webkit-border-radius:25px; border-radius:25px;}
	.btn2 a.start{ background:#ffd527;}
	.btn2 a.start:hover{ background:#ffbb06;}
	.btn3 a{position: relative; left: -50%; display:block; width:120px; height:50px; margin:30px auto; text-align:center; line-height:50px; text-decoration:none; color:#fff; -moz-border-radius:25px; -webkit-border-radius:25px; border-radius:25px;}
	.btn3 a.start{ background:#ee3939;}
	.btn3 a.start:hover{ background:#c9302c;}
	.round_icon{
		position: relative;
		left: -50%; 
		width: 100px;
		height: 100px;
		display: flex;
		border-radius: 50%;
		overflow: hidden;
	}
	
	</style>
  <div id="content" role="main">
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><?=$title?></li>&nbsp;&nbsp;<a class="bttn-material-flat bttn-xs bttn-primary text-center" href="./index">返回首页</a>
					</ul>
				</div>
  <!-- / aside -->
      
  
<div class="wrapper">
<div class="col-lg-8 col-md-8 col-sm-12" id="ljc_bg">
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >随机抽号</div>
<div class="panel-body">
  <div class="wrap">
      <span id="tb_jishu" style="display:none;">0</span>
	<!--div class="wrap"-->
		<div class="show" id="show">Ready</div><br>
		<center><img id="load" src="./images/loading.gif" draggable="false" style="position:relative; left: -50%; display:none;"><!--img id="qq" src="" draggable="flase" style="display:none;" class="round_icon" alt="qqlogo"--></center>
		<div class="btn1">
			<a class="start" id="btn1" onclick="rand()">开始抽号</a>
		</div>
		<div class="btn2">
			<a class="start" id="btn2" style="display:none;" onclick="rest()">重置</a>
			
		</div>
	</div>
  </div>
</div>
</div>

<div class="col-lg-4 col-md-4 col-sm-12" id="ljc_bg_1">
<div class="panel panel-default">
	    <div class="panel-heading font-bold"  style="background: #7CCD7C;color: white;">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-globe"></i>&nbsp;&nbsp;<b>结果区</b></font></h3>
			</div>
			<div class="panel-body">
			<table class="table table-responsive table-striped b-t b-light text-center">
            	<thead>
            		<tr style="white-space:nowrap;">
            			<th class="text-center" width="30%">序号</th>
            			<th class="text-center" width="70%">姓名</th>
            		</tr>
            	</thead>
            	<tbody id="page_table"></tbody>
            </table>

		</div>
 
</div>
</div>
</div>
</div>
</div>

<br><br><?php include './footer.php';?>
  
  

<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/bootstrap-datetimepicker.min.js"></script>
<script src="./js/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="./js/watermark.js"></script>

<script language="JavaScript">
    function rand()
    {
        
    	document.getElementById("btn1").innerHTML="再抽一次";
    	document.getElementById("btn2").style.display='block';
    	document.getElementById("show").innerHTML="Waiting...";
    	document.getElementById("load").style.display='block';
    	var dq_id=$('#tb_jishu').text()
    	dq_id=Number(dq_id)+1
    	var trval = document.createElement("tr")
        var td_id = document.createElement("td")
        var td_name = document.createElement("td")
        td_id.innerText = dq_id
        trval.appendChild(td_id)
    	$.get("./random?action=start",
    	    function(data){
                var obj = JSON.parse(data);
                
                td_name.innerText = obj.result
                trval.appendChild(td_name)
                var bodyTag = document.getElementById("page_table")
                bodyTag.appendChild(trval)
                
                $('#tb_jishu').text(dq_id)
             
            	document.getElementById("show").innerHTML=obj.result;
            	document.getElementById("load").style.display='none';
    	    }
    	);

        disabledSubmitButton02("btn1","再抽一次")
    }
    function rest()
    {
        var box=document.getElementById("page_table");
        box.innerHTML="";
        $('#tb_jishu').text('0')
    	document.getElementById("show").innerHTML="Ready";
    	document.getElementById("btn1").innerHTML="开始抽号";
    	document.getElementById("btn2").style.display="none";
        document.getElementById("load").style.display="none";
    }
    function disabledSubmitButton02(submitButtonName, submitButtonText) {
        $("#" + submitButtonName).removeAttr("onclick");
        var second = 3;
        var intervalObj = setInterval(function () {
            $("#" + submitButtonName).text(submitButtonText + "(" + second + ")");
            if (second == 0) {
                $("#" + submitButtonName).text(submitButtonText);
                $("#" + submitButtonName).attr({ "onclick": "rand()" });
                clearInterval(intervalObj);
            }
            second--;
        }, 1000);
    }
</script>

