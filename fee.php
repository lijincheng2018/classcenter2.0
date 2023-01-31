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
$result_t=mysqli_query($conn,"SELECT * FROM fee ORDER BY id desc");
$t_num=mysqli_num_rows($result_t);

$sys_info=mysqli_query($conn,"SELECT * FROM system_info WHERE tag='classmoney'");
$row_sys_info=mysqli_fetch_assoc($sys_info);
$classmoney=$row_sys_info['content'];


if($_GET['action']=="print"){
    if($_SESSION['usergroup']!="1" && $_SESSION['zhiwu']!="生劳委员"){
        echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回首页</a>';
        exit(0);
    }
   include_once("xlsxwriter.class.php");
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);
    $rand_num=mt_rand(10002,99999);
    
    $tmp='软工2201班班费明细'.date("YmdHis",time()).$rand_num.'.xlsx';
    $filename = 'public/download/'.$tmp;
    
    $rows = array(
        array('序号','流水号','标题','金额','变动后金额','支出/收入','申请人','时间'),
    );
    
    $writer = new XLSXWriter();
    $writer->setAuthor('李锦成'); 
    $id=0;
    for($i=1;$i<=$t_num;$i++){
        $result_arr=mysqli_fetch_assoc($result_t);
        $id++;
        $title=$result_arr['title'];
        $lsid=$result_arr['id'];
        $time=$result_arr['time'];
        $fee=$result_arr['fee'];
        $after_f=$result_arr['after_f'];
        $method=$result_arr['method'];
        $author=$result_arr['author'];
           
        if($method=="1") $method='支出';
        else if($method=="2") $method='收入';
        
        $tmp=array();
        $tmp=explode(',',$people);
        
        
        
        $tmp_data=array();
        
        $tmp_data[0]=$id;
        $tmp_data[1]=$lsid;
        $tmp_data[2]=$title;
        $tmp_data[3]=$fee;
        $tmp_data[4]=$after_f;
        $tmp_data[5]=$method;
        $tmp_data[6]=$author;
        $tmp_data[7]=$time;
        
        $rows[$i]=$tmp_data;
        
    }
    
    
    foreach($rows as $row)
        $writer->writeSheetRow('班费明细', $row);
    
    $writer->writeToFile($filename);
    exit($filename.'+ljc+'.$tmp);
}

if($_GET['action']=="post"){
    if($_SESSION['usergroup']!="1" && $_SESSION['zhiwu']!="生劳委员"){
        echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回首页</a>';
        exit(0);
    }
    $title=$_POST['title'];
    $fee=$_POST['fee'];
    $status=$_POST['status'];
    $time=date("Y-m-d H:i:s",time());
    $author=$_SESSION['username'];
    
    
    $sys_info=mysqli_query($conn,"SELECT * FROM system_info WHERE tag='classmoney'");
    $row_sys_info=mysqli_fetch_assoc($sys_info);
    $classmoney=$row_sys_info['content'];
        
    if($status=="1") $classmoney-=$fee;
    if($status=="2") $classmoney+=$fee;
    
    mysqli_query($conn,"INSERT INTO fee(title,fee,after_f,method,author,time) VALUES ('$title','$fee','$classmoney','$status','$author','$time')");
        
    mysqli_query($conn,"UPDATE system_info SET content='$classmoney' WHERE tag='classmoney'");
    
    
    if(mysqli_error($conn))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($conn).'"}'); 
    }else{
        exit('{"msg":"success","datas":"'.$id.'"}');
    }
}


$title="班费明细";



?>



  <?php include 'head.php';?>
  <div id="content" role="main">
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><?=$title?></li>&nbsp;&nbsp;<a class="bttn-material-flat bttn-xs bttn-primary text-center" href="./index">返回首页</a>
					</ul>
				</div>
  <!-- / aside -->
      <div class="wrapper" id="cancle_activity" style="display:none">
        <div class="col-sm-12">
            <a class="btn btn-warning" href="javascript:;" onclick="cancle()" id="cancle_activity">取消</a>
        </div>
        <br><br>
      </div>
      
    
    
    
    <div class="wrapper" id="post" style="display:none">
<div class="col-sm-12">
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >手动添加记录</div>
<div class="panel-body">
  <form name="RegForm" id="RegForm" enctype="multipart/form-data" action="#" method="post" role="form">
    <div class="form-group">
	  <label>标题:</label><br/>
	  <input type="text" name="title" id="title" value="" class="form-control"/>
	</div>
	<div class="form-group">
	  <label>支出/收入金额:</label><br/>
	  <input type="text" name="fee" id="fee" value="" class="form-control"/>
	</div>
	<div class="form-group">
	  <label>支出/收入:</label><br/>
	  <select id="status" class="selectpicker show-tick form-control">
			<option value="1">支出</option>
			<option value="2">收入</option>
	  </select>
	</div>
	  <br>
	  <br><p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
	  
	
	<div class="form-group">
	    
	        <input type="submit" name="submit" value="发布" class="btn btn-primary form-control"/>
	  
	</div>	
  </form>
  </div>
</div>
 
</div>
</div>
    
  <div class="wrapper" id="activity_list" style="display:block">
<div class="col-sm-12" id="ljc_bg">	
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">班费明细</div>
<div class="well well-sm" style="margin: 0;">当前班费剩余：<b><?=$classmoney?></b>元<br>当前共有<b><?=$t_num?></b>条数据<br>
<?php if($_SESSION['usergroup']=="1" || $_SESSION['zhiwu']=="生劳委员") echo'<a class="btn btn-success" href="javascript:;" onclick="post()">手动添加记录</a>&nbsp;&nbsp;<a class="btn btn-danger" href="javascript:;" onclick="createfile()">导出数据</a>';?></div>
<div class="table-responsive">
    <div style="height:540px;overflow: auto;">
        <table class="table table-responsive b-t b-light text-center table-striped" style="white-space:nowrap;text-align:center;">
          <thead><th class="text-center">流水号</th><th class="text-center" width="30%">标题</th><th class="text-center" width="20%">金额</th><th class="text-center" width="20%">变动后金额</th><th class="text-center">支出/收入</th><th class="text-center">申请人</th><th class="text-center">时间</th></thead>
          <tbody>
	<tr class="onclick warning">

<?php

for($i=0;$i<$t_num;$i++){
           $result_arr=mysqli_fetch_assoc($result_t);
           $title=$result_arr['title'];
           $id=$result_arr['id'];
           $time=$result_arr['time'];
           $fee=$result_arr['fee'];
           $after_f=$result_arr['after_f'];
           $method=$result_arr['method'];
           $author=$result_arr['author'];
           
            if($method=="1") $method='<font color="red">支出</font>';
            else if($method=="2") $method='<font color="green">收入</font>';
           
           
               echo '<tr>
                           <td>'.$id.'</td>
                           <td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td>
                           <td>'.$fee.'</td>
                           <td>'.$after_f.'</td>
                           <td>'.$method.'</td>
                           <td>'.$author.'</td>
                           <td>'.$time.'</td>
                     </tr>';
                       
       }
       
       ?>


          </tbody>
        </table><?php if($a_id==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';?>
		      </div></div>
</div>
</div>
</div>
<?php include './footer.php';?>
  
  
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>
  
<script language="JavaScript">
    
    function createfile(){
         $.get('?action=print',function(data){
                var strs= new Array(); 
                strs=data.split("+ljc+");
       			if(data!="") downloadEvt("https://class.ljcljc.cn/"+strs[0],strs[1]);
			 
       		});
    }
    
    function downloadEvt(url, fileName) {
      const el = document.createElement('a');
      el.style.display = 'none';
      el.setAttribute('target', '_blank');
      fileName && el.setAttribute('download', fileName);
      el.href = url;
      console.log(el);
      document.body.appendChild(el);
      el.click();
      document.body.removeChild(el);
    }
</script>

<script language="JavaScript">

		$(function () {
		    
            $('#RegForm').on('submit', function (e) {  
                document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
                e.preventDefault();
                var title=$('#title').val();
                var fee=$('#fee').val();
                var status=$('#status').val();
                
                
                if(title==""){
                    $('#wrong').text('请输入标题');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                else if(fee==""){
                    $('#wrong').text('请输入金额');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                
                $.ajax({
                    type:'post',
                    url:'?action=post',
                    data:{
                        title:title,
                        fee:fee,
                        status:status
                    },
                    dataType:'json',
                    success: function(res){
                        console.log(res)
                        toastr.success("操作成功");
                        //location.reload();
                    }
                })
            })
        })
        
        
    function post(){
        document.getElementById('post').style="display:block";
        document.getElementById('activity_list').style="display:none";
        document.getElementById('cancle_activity').style="display:block";
    }
    
    function cancle(){
        document.getElementById('post').style="display:none";
        document.getElementById('activity_list').style="display:block";
        document.getElementById('cancle_activity').style="display:none";
    }
    
</script>