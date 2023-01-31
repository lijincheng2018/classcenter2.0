<?php

session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
$uid=$_SESSION['userid'];

//$result=mysql_query("SELECT * FROM user WHERE name=$name");

$ac_id=$_GET['id'];

if($ac_id=="") exit("非法访问");

$title="个人荣誉编辑";

$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);
$result_t=mysqli_query($conn,"SELECT * FROM personal WHERE id=$ac_id");
$t_row=mysqli_fetch_assoc($result_t);

if($t_row['title']=="") exit("非法访问");

if($_GET['action']=="edit"){
    
    $title=$_POST['title'];
    $time=$_POST['time'];
    $dengji=$_POST['dengji'];
    $classid=$_SESSION['classid'];
    $name=$_SESSION['username'];
    $mc=$_POST['mc'];
    
    
    
    mysqli_query($conn,"UPDATE personal SET title='$title',mc='$mc',dengji='$dengji',time='$time',author='$name' WHERE id=$ac_id");
    
    if(mysqli_error($conn))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($conn).'"}'); 
    }else{
        exit('{"msg":"success"}');
    }
    
}


?>



  <?php include 'head.php';?>
  <div id="content" role="main">
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><a href="./personal">个人荣誉</a></li>
						<li><?=$title?></li>&nbsp;&nbsp;<a class="bttn-material-flat bttn-xs bttn-primary text-center" href="./index">返回首页</a>
					</ul>
				</div>
  <!-- / aside -->
      <div class="wrapper">
        <div class="col-sm-12">
            <a class="btn btn-info" href="javascript:history.back(-1)"><i class="fa fa-arrow-left" aria-hidden="true"></i>返回</a><br><br>
        </div>
      </div>
    
    <br><br>
  
  <div class="wrapper">
<div class="col-sm-12" id="ljc_bg">
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >个人荣誉编辑</div>
<div class="panel-body">
  <form name="RegForm" id="RegForm" action="#" method="post" role="form">
    <div class="form-group">
	  <label>唯一ID:</label><br/>
	  <input type="text" name="ac_id" id="ac_id" value="<?=$ac_id?>" class="form-control"/ readonly>
	</div>
    <div class="form-group">
	  <label>获奖名称:</label><br/>
	  <input type="text" name="title" id="title" value="<?=$t_row['title']?>" class="form-control"/>
	</div>
    <div class="form-group">
	  <label>获奖名次:</label><br/>
	  <input type="text" name="mc" id="mc" value="<?=$t_row['mc']?>" class="form-control"/>
	</div>
	<div class="form-group">
	  <label>获奖类型:</label><br/>
	  <select id="dengji" class="selectpicker show-tick form-control">
	      <option value="1" <?php if($t_row['dengji']=="1") echo 'selected'?>>班级</option>
	      <option value="2" <?php if($t_row['dengji']=="2") echo 'selected'?>>院级</option>
		  <option value="3" <?php if($t_row['dengji']=="3") echo 'selected'?>>校级</option>
		  <option value="4" <?php if($t_row['dengji']=="4") echo 'selected'?>>镇级</option>
		  <option value="5" <?php if($t_row['dengji']=="5") echo 'selected'?>>县级</option>
		  <option value="6" <?php if($t_row['dengji']=="6") echo 'selected'?>>市级</option>
		  <option value="7" <?php if($t_row['dengji']=="7") echo 'selected'?>>省级</option>
		  <option value="8" <?php if($t_row['dengji']=="8") echo 'selected'?>>国家级</option>
	  </select>
	</div>
	<div class="form-group">
	  <label>获奖时间:</label><br/>
	  <div class="input-group date form_date" id="bengindate" data-data="" data-date-format="yyyy-mm-dd">
            <input id="time" name="time" class="form-control" id="benginText" type="text" value="<?=$t_row['time']?>" placeholder="请点击右侧日期图标设置日期">
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
       </div>
	</div>
	<br><p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
	
	<div class="form-group">
	    
	        <input type="submit" name="submit" value="确认修改" class="btn btn-primary form-control"/>
	  
	</div>	
  </form>
  </div>
</div>
 
</div>
</div>
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

		$(function () {
            $('#RegForm').on('submit', function (e) {  
                document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
                e.preventDefault();
                var title=$('#title').val();
                var time=$('#time').val();
                var dengji=$('#dengji').val();
                var mc=$('#mc').val();
                var ac_id=$('#ac_id').val();
                
                
                if(title==""){
                    $('#wrong').text('请输入获奖名称');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                else if(mc==""){
                    $('#wrong').text('请选择获奖名次');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                else if(time==""){
                    $('#wrong').text('请选择获奖时间');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                
                
                $.ajax({
                    type:'post',
                    url:'?id='+ac_id+'&action=edit',
                    data:{
                        title:title,
                        time:time,
                        dengji:dengji,
                        mc:mc
                    },
                    dataType:'json',
                    success: function(res){
                        console.log(res)
                        alert('操作成功');
                        location.reload();
                    }
                })
            })
        })
        

    
</script>

