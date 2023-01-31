<?php

session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}

if($_SESSION['usergroup']!=1 && $_SESSION['usergroup']!=2){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回首页</a>';
    exit(0);
}

include './config.php';
$uid=$_SESSION['userid'];

//$result=mysql_query("SELECT * FROM user WHERE name=$name");

$ac_id=$_GET['id'];
if($ac_id=="") exit("非法访问");

$title="集体荣誉编辑";

$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);
$result_t=mysqli_query($conn,"SELECT * FROM class WHERE id=$ac_id");
$t_row=mysqli_fetch_assoc($result_t);

if($t_row['title']=="") exit("非法访问");


if($_GET['action']=="edit"){
    
    $title=$_POST['title'];
    $time=$_POST['time'];
    $dengji=$_POST['dengji'];
    $classid=$_SESSION['classid'];
    $name=$_SESSION['username'];
    $mc=$_POST['mc'];
    
    $people=$_POST['people'];
    
    if($people!=""){
        $peoples=array();
        $peoples=explode(',',$people);
        $people_num=count($peoples)-1;
    }
    else $people_num=0;
    
    
    mysqli_query($conn,"UPDATE class SET title='$title',mc='$mc',dengji='$dengji',time='$time',people='$people',people_num='$people_num',author='$name' WHERE id=$ac_id");
    
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
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >集体荣誉编辑</div>
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
	<div class="form-group">
	  <label>参与人员:&nbsp;&nbsp;<input type="button" class="btn btn-danger btn-xs" value="全选" class="btn" id="selectAll">&nbsp;<input type="button" class="btn btn-success btn-xs" value="全不选" class="btn" id="unSelect">&nbsp;<input type="button" class="btn btn-info btn-xs" value="反选" class="btn" id="reverse"></label><br/>
	  <?php

	    $result_dy=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
        $dy_num=mysqli_num_rows($result_dy);
        
        $people=$t_row['people'];
        $people_data=array();
        $people_data=explode(',',$people);
        
        $pr=1;
        for($i=1;$i<=$dy_num;$i++){
            $ifchek=0;
            $row=mysqli_fetch_assoc($result_dy);
            $name=$row['name'];
            if($pr==1){
                echo '<div class="form-check">';
            }
            
            for($j=0;$j<=count($people_data)-1;$j++){
                if($i==$people_data[$j]){
                    $ifchek=1;
                    echo('
                        <input type="checkbox" class="form-check-input" id="p_'.$i.'" name="ck_box" value="'.$i.'" title="'.$name.'"" checked="true">
                        <label class="form-check-label" style="width: 60px;">'.$name.'</label>&nbsp;&nbsp;
                    ');
                }
            }
            if($ifchek==0) echo('
                        <input type="checkbox" class="form-check-input" id="p_'.$i.'" name="ck_box" value="'.$i.'" title="'.$name.'"">
                        <label class="form-check-label" style="width: 60px;">'.$name.'</label>&nbsp;&nbsp;
                    ');
            
            
            if($pr==10 || $i==$dy_num) {
                echo '</div>';
            $pr=1;
            }else $pr++;
            
        }
        ?>
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
                
                let str = '';
                var obj = document.getElementsByName('ck_box');
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].checked){
                        str += obj[i].value + ",";
                    }
                }
                
                $.ajax({
                    type:'post',
                    url:'?id='+ac_id+'&action=edit',
                    data:{
                        title:title,
                        time:time,
                        dengji:dengji,
                        mc:mc,
                        people:str
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
        
        
        
    $("#selectAll").click(function () { 
	    $("#RegForm input:checkbox").each(function () {   
		    $(this).prop('checked', true);//
	    }); 
	});
	$("#unSelect").click(function () {   
		$("#RegForm input:checkbox").removeAttr("checked");  
    });
    $("#reverse").click(function () {  
        $("#RegForm input:checkbox").each(function () {   
        	this.checked = !this.checked;  
        }); 
    });
    

    
</script>

