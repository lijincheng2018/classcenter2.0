<?php
//error_reporting(0);
session_start();
require_once './vendor/autoload.php';
use TencentCloud\Sms\V20210111\SmsClient;
// 导入要请求接口对应的Request类
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;
// 导入可选配置类
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
$uid=$_SESSION['userid'];

//$result=mysql_query("SELECT * FROM user WHERE name=$name");
$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);
$result_t=mysqli_query($conn,"SELECT * FROM queue ORDER BY id desc");
$result_t_1=mysqli_query($conn,"SELECT * FROM queue ORDER BY id desc");
$t_num=mysqli_num_rows($result_t);


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
    $money=$_POST['money'];
    $yt=$_POST['yt'];
    $classid=$_SESSION['classid'];
    $name=$_SESSION['username'];
    $time=date("Y-m-d H:i:s",time());
    
    $extension1 = end(explode(".", $_FILES["file1"]["name"]));
    $extension2 = end(explode(".", $_FILES["file2"]["name"]));
        
  	if ($_FILES["file1"]["error"] > 0){  
        return $_FILES["file1"]["error"]; 
    }
    else if ($_FILES["file2"]["error"] > 0){  
        return $_FILES["file2"]["error"]; 
    }
    else{
        $url1 = "./public/file/";
        if(!is_dir($url1)) mkdir($url1,0755,true);
                
        $whconf1 = $url1.$_FILES['file1']['name'];  
        $filename1 = basename($whconf1);
                
        $whconf2 = $url1.$_FILES['file2']['name'];  
        $filename2 = basename($whconf2);     
                
        $extpos1 = strrpos($filename1,'.');
        $extpos2 = strrpos($filename2,'.');
                
        $ext1 = substr($filename1,$extpos1+1);
        $ext2 = substr($filename2,$extpos2+1);

        $rand_num=mt_rand(10002,99999);
        
        $confname1 = '1-'.date("YmdHis",time()).$rand_num; 
        $confname2 = '2-'.date("YmdHis",time()).$rand_num; 
                
        $path1 = move_uploaded_file($_FILES["file1"]["tmp_name"], $url1 . $confname1 .'.'. $ext1);
        $path2 = move_uploaded_file($_FILES["file2"]["tmp_name"], $url1 . $confname2 .'.'. $ext2);
    }
    
    $file1_name=$confname1 .'.'. $ext1;
    $file2_name=$confname2 .'.'. $ext2;
    
    mysqli_query($conn,"INSERT INTO queue(title,yt,fee,payment,method,photo1,photo2,classid,author,time) VALUES ('$title','$yt','$money','1','0','$file1_name','$file2_name','$classid','$name','$time')");
    
    
    if(mysqli_error($conn))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($conn).'"}'); 
    }else{
        $result_head=mysqli_query($conn,"SELECT * FROM user WHERE zhiwu='生劳委员'");
        $row_head=mysqli_fetch_assoc($result_head);
        $h_tel='+86'.$row_head['tel'];
        $h_name=$row_head['name'];
        $h_zhiwu=$row_head['zhiwu'];
        
        try {

            $cred = new Credential("AKIDx0oJXclO3aT85mrTHo9HD0aaNyTjgomW", "Z6XcGOBnAI0a5ez68wlD7pT0TjLnofMo");
        
            $httpProfile = new HttpProfile();
            $httpProfile->setReqMethod("GET");
            $httpProfile->setReqTimeout(30);
            $httpProfile->setEndpoint("sms.tencentcloudapi.com");
        
            $clientProfile = new ClientProfile();
            $clientProfile->setSignMethod("TC3-HMAC-SHA256");
            $clientProfile->setHttpProfile($httpProfile);
            $client = new SmsClient($cred, "ap-guangzhou", $clientProfile);
            $req = new SendSmsRequest();
            $req->SmsSdkAppId = "1400745644";
            $req->SignName = "小成知识库网";
            $req->TemplateId = "1564076";
            $req->TemplateParamSet = array($h_zhiwu.$h_name,$_SESSION['username']);
            $req->PhoneNumberSet = array($h_tel);
        
            $resp = $client->SendSms($req);
        
            print_r($resp->toJsonString());
        }
        catch(TencentCloudSDKException $e) {
            echo $e;
        }
        
        echo ("<script language='javascript'>alert('申请成功！请等待审核通过！');window.location.href='baoxiao';</script>");
        exit(0);
    }

    
}



if($_GET['action']=="query"){
    $id=$_GET['id'];
    
    $result=mysqli_query($conn,"SELECT * FROM queue WHERE id='$id'");
    $row=mysqli_fetch_assoc($result);
    $classid=$row['classid'];
    if($classid==$_SESSION['classid']){
        $title=$row['title'];
        $yt=$row['yt'];
        $fee=$row['fee'];
        $method=$row['method'];
        $ps=$row['ps'];
        $pf_author=$row['pf_author'];
        $pf_time=$row['pf_time'];
        if($ps=="") $ps="暂无批复";
        
        if($method=="0") $method='待审核';
        else if($method=="1") $method='通过';
        else if($method=="2") $method='驳回';
        
        
        $photo1=$row['photo1'];
        $photo2=$row['photo2'];
        
        $time=$row['time'];
        $author=$row['author'];
        
    
        
        exit($title.'+ljc+'.$fee.'+ljc+'.$method.'+ljc+'.$ps.'+ljc+'.$photo1.'+ljc+'.$photo2.'+ljc+'.$author.'+ljc+'.$time.'+ljc+'.$yt.'+ljc+'.$pf_author.'+ljc+'.$pf_time);
    }else exit(0);
    
}


$title="申请报销";



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
  <ul id="myTab" class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#list" role="tab" data-toggle="tab">报销记录列表</a></li>
    <li><a href="#sq" role="tab" data-toggle="tab">新增报销申请</a></li>
</ul>
  
  <div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="list">
      <div class="wrapper" id="activity_list" style="display:block">
<div class="col-sm-12" id="ljc_bg">	
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">记录列表</div>
<div class="well well-sm" style="margin: 0;">当前共有<b><?=$list?></b>条数据</div>
<div class="table-responsive">
        <table class="table table-responsive b-t b-light text-center">
          <thead><th class="text-center">申请流水号</th><th class="text-center" width="20%">报销名称</th><th class="text-center" width="20%">报销金额</th><th class="text-center" width="20%">申请状态</th><th class="text-center">申请时间</th><th class="text-center">操作</th></thead>
          <tbody>
	<tr class="onclick warning"  >

<?php
    for($i=0;$i<$t_num;$i++){
           $result_arr=mysqli_fetch_assoc($result_t);
           $classid=$result_arr['classid'];
           if($classid==$_SESSION['classid'])
           {
               $title=$result_arr['title'];
               $id=$result_arr['id'];
               $time=$result_arr['time'];
               $fee=$result_arr['fee'];
               $method=$result_arr['method'];
               if($method=="0") $method='<font color="blue">待审核</font>';
               else if($method=="1") $method='<font color="green">通过</font>';
               else if($method=="2") $method='<font color="red">驳回</font>';
               
               
               $control='<a class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#myModal" onclick="query('.$id.')">查看详情</a>';
                
                
            
                $time=$result_arr['time'];
                   echo '<tr>
                               <td>'.$id.'</td>
                               <td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td>
                               <td>'.$fee.'</td>
                               <td>'.$method.'</td>
                               <td>'.$time.'</td>
                               <td>'.$control.'</td>
                         </tr>';
                           
           }
    }
           
           
       
       ?>


          </tbody>
        </table>
		      </div>
</div>
</div>
</div>
    
    
    </div>
    <div class="tab-pane fade" id="sq">
            <div class="wrapper" id="post" style="display:block">
        <div class="col-sm-12">
        <div class="panel panel-default">
        <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >新增申请</div>
        <div class="panel-body">
          <form name="RegForm" id="RegForm" enctype="multipart/form-data" action="?action=post" method="post" role="form" onsubmit="return InputCheck(this)">
            <div class="form-group">
        	  <label>报销名称:</label><br/>
        	  <input type="text" name="title" id="title" value="" class="form-control"/>
        	</div>
        	<div class="form-group">
        	  <label>用途:</label><br/>
        	  <input type="text" name="yt" id="yt" value="" class="form-control"/>
        	</div>
        	<div class="form-group">
        	  <label>报销金额:（单位：元）</label><br/>
        	  <input type="text" name="money" id="money" onkeyup="this.value= this.value.match(/\d+(\.\d{0,2})?/) ? this.value.match(/\d+(\.\d{0,2})?/)[0] : ''" value="" class="form-control"/>
        	</div>
        	<div class="form-group">
        	  <label>消费时间:</label><br/>
        	  <div class="input-group date form_date" id="bengindate" data-data="" data-date-format="yyyy-mm-dd">
                    <input id="time" name="time" class="form-control" id="benginText" type="text" value="" placeholder="请点击右侧日期图标设置日期" readonly>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
               </div>
        	</div>
        	<div class="form-group">
        	  <label>报销凭证:</label><br/>
        	</div>
        	<div class="form-group">
                <div class="input-group">
                    <input id='location1' class="form-control" onclick="$('#i-file1').click();" placeholder="请选择图片">
                    <label class="input-group-btn">
                        <input type="button" id="i-check1" value="浏览图片" class="btn btn-warning" onclick="$('#i-file1').click();">
                    </label>
                </div>
                <input type="file" name="file1" id='i-file1'  accept=".png, .jpg, .jpeg" onchange="$('#location1').val($('#i-file1').val());" style="display: none">
            </div>
        	
        	<div class="form-group">
        	  <label>收款二维码:</label><br/>
        	</div>
        	<div class="form-group">
                <div class="input-group">
                    <input id='location2' class="form-control" onclick="$('#i-file2').click();" placeholder="请选择图片">
                    <label class="input-group-btn">
                        <input type="button" id="i-check2" value="浏览图片" class="btn btn-warning" onclick="$('#i-file2').click();">
                    </label>
                </div>
                <input type="file" name="file2" id='i-file2'  accept=".png, .jpg, .jpeg" onchange="$('#location2').val($('#i-file2').val());" style="display: none">
            </div>
        	<div class="form-group">
                <label>验证码:</label><br/>
        		<input type="text" id="yz_code" name="yz_code" value="" class="form-control" placeholder="请输入验证码（不区分大小写）">
                <canvas id="canvas"></canvas>
        	</div>
        	  <br>
        	  
        	  <br><p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
        	  
        	
        	<div class="form-group">
        	    
        	        <input type="submit" name="submit" value="提交申请" class="btn btn-primary form-control"/>
        	  
        	</div>
          </form>
          </div>
          <div class="panel-footer">
                  <span class="glyphicon glyphicon-info-sign"></span> <font color="red">注意：申请后，系统会立即通过短信通知<b>生劳委员</b>处理报销申请！请不要<b>多次</b>提交<b>重复</b>的报销申请，否则后果自负！</font>
            </div>
        </div>
         
        </div>
        </div>
    
    
    
    </div>
</div>
  
      

  
  
  <div class="container-fluid text-center">
        <!-- 大图 -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            
			<h4 class="modal-title" id="myModalLabel">
              	详细资料
            </h4>
						</div>
						<div class="modal-body" style="text-align:left">
							报销名称：<span id="s_title"></span><br>
							用途：<span id="s_yt"></span><br>
							报销金额：<span id="s_fee"></span><br>
							申请状态：<span id="s_status"></span><br>
							申请批复：<span id="s_ps"></span><br>
							报销凭证：<span id="s_photo1"><div style="text-align:center;margin 0 auto;weight:200px;justify-content: center;align-items: center;"><ljc_img></ljc_img></div></span><br>
							收款二维码：<span id="s_photo2"><div style="text-align:center;margin 0 auto;weight:200px;justify-content: center;align-items: center;"><ljc_img></ljc_img></div></span><br>
							申请人：<span id="s_author"></span><br>
							申请时间：<span id="s_time"></span><br>
							处理人：<span id="s_pf_author"></span><br>
							处理时间：<span id="s_pf_time"></span><br>
							</div><br>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">关闭
            </button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal -->
			</div>
  
  
  

<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/bootstrap-datetimepicker.min.js"></script>
<script src="./js/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="./js/watermark.js"></script>
  
  <script language="javascript">
     $("#bengindate").datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
    }).on('changeDate', function (e) {
        var BeginTime = $("#benginText").val();
    });
</script>

<script language="JavaScript">
var show_num = [];
$(function(){

    draw(show_num);
    $("#canvas").on('click',function(){
        draw(show_num);
    })
})
    function InputCheck(RegForm) {
        if (RegForm.title.value == "") {
            toastr.error("报销名称不可为空!");
            RegForm.title.focus();
            return (false)
        }
        if (RegForm.yt.value == "") {
            toastr.error("用途不可为空!");
            RegForm.yt.focus();
            return (false)
        }
        if (RegForm.money.value == "") {
            toastr.error("报销金额不可为空!");
            RegForm.money.focus();
            return (false)
        }
        if (RegForm.time.value == "") {
            toastr.error("请选择消费时间!");
            RegForm.time.focus();
            return (false)
        }
        if(RegForm.yz_code.value.toLowerCase()==""){
            toastr.error("请输入验证码！");
            RegForm.yz_code.focus();
            return (false)
        }
        if(RegForm.yz_code.value.toLowerCase() != show_num.join("")){
            toastr.error("验证码错误！");
            RegForm.yz_code.focus();
            return (false)
        }
    }

    
    function query(getid){
        var box=document.getElementsByTagName("ljc_img")[0];
        box.innerHTML="";
        var box=document.getElementsByTagName("ljc_img")[1];
        box.innerHTML="";
        $.get('?action=query&id='+getid,function(data){
       			var strs= new Array(); 
                strs=data.split("+ljc+");
                
                if(strs[4]!=""){
                    var result='<img src="./public/file/'+strs[4]+'" style="max-width: 100%;max-height: 100%;">'
                    var div = document.createElement('div');
                    div.innerHTML = result;
                    document.getElementsByTagName('ljc_img')[0].appendChild(div);
                }else $('#s_photo1').text('暂无数据')
                if(strs[5]!=""){
                    var result='<img src="./public/file/'+strs[5]+'" style="max-width: 100%;max-height: 100%;">'
                    var div = document.createElement('div');
                    div.innerHTML = result;
                    document.getElementsByTagName('ljc_img')[1].appendChild(div);
                }else $('#s_photo2').text('暂无数据')
                
                $('#s_title').text(strs[0])
                $('#s_fee').text(strs[1])
                $('#s_status').text(strs[2])
                $('#s_ps').text(strs[3])
                $('#s_author').text(strs[6])
                $('#s_time').text(strs[7])
                $('#s_yt').text(strs[8])
                $('#s_pf_author').text(strs[9])
                $('#s_pf_time').text(strs[10])
       		});
       		
    }
    function post(){
        document.getElementById('post').style="display:block";
        document.getElementById('activity_list').style="display:none";
        document.getElementById('post_activity').style="display:none";
        document.getElementById('cancle_activity').style="display:block";
    }
    
    function cancle(){
        document.getElementById('post').style="display:none";
        document.getElementById('activity_list').style="display:block";
        document.getElementById('post_activity').style="display:block";
        document.getElementById('cancle_activity').style="display:none";
    }
    
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

