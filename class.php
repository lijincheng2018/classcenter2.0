<?php
error_reporting(0);
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
$result_t=mysqli_query($conn,"SELECT * FROM class ORDER BY id asc");
$t_num=mysqli_num_rows($result_t);


if($_GET['action']=="print"){
    if($_SESSION['usergroup']!=1 && $_SESSION['usergroup']!=2){
        echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回首页</a>';
        exit(0);
    }
   include_once("xlsxwriter.class.php");
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);
    $rand_num=mt_rand(10002,99999);
    
    $tmp='软工2201班级荣誉'.date("YmdHis",time()).$rand_num.'.xlsx';
    $filename = 'public/download/'.$tmp;
    
    $rows = array(
        array('序号','获奖名称','获奖名次','获奖类型','活动时间','参与人员','登记人'),
    );
    
    $writer = new XLSXWriter();
    $writer->setAuthor('李锦成'); 
    $id=0;
    for($i=1;$i<=$t_num;$i++){
        $result_arr=mysqli_fetch_assoc($result_t);
        $id++;
        $title=$result_arr['title'];
        
        $mc=$result_arr['mc'];
        $time=$result_arr['time'];
        $dengji=$result_arr['dengji'];
        $author=$result_arr['author'];
        $people=$result_arr['people'];
        $people_num=$result_arr['people_num'];
        
        $tmp=array();
        $tmp=explode(',',$people);
        
        $list="";
        
        for($j=0;$j<$people_num;$j++){
            $head=$tmp[$j];
            $result_dy=mysqli_query($conn,"SELECT * FROM user WHERE uid=$head");
            $row_dy=mysqli_fetch_assoc($result_dy);
            if($j==$people_num-1){
                $list=$list.$row_dy['name'];
            }else{
                $list=$list.$row_dy['name'].'、';
            }
        }
        
        if($dengji=="1") $dengji="班级";
        else if($dengji=="2") $dengji="院级";
        else if($dengji=="3") $dengji="校级";
        else if($dengji=="4") $dengji="镇级";
        else if($dengji=="5") $dengji="县级";
        else if($dengji=="6") $dengji="市级";
        else if($dengji=="7") $dengji="省级";
        else if($dengji=="8") $dengji="国家级";
           
        
        
        $tmp_data=array();
        
        $tmp_data[0]=$id;
        $tmp_data[1]=$title;
        $tmp_data[2]=$mc;
        $tmp_data[3]=$dengji;
        $tmp_data[4]=$time;
        $tmp_data[5]=$list;
        $tmp_data[6]=$author;
        
        $rows[$i]=$tmp_data;
        
    }
    
    
    foreach($rows as $row)
        $writer->writeSheetRow('班级荣誉', $row);
    
    $writer->writeToFile($filename);
    exit($filename.'+ljc+'.$tmp);
}

if($_GET['action']=="post"){
    if($_SESSION['usergroup']!=1 && $_SESSION['usergroup']!=2){
        exit(0);
    }
    $title=$_POST['title'];
    $time=$_POST['time'];
    $dengji=$_POST['dengji'];
    $name=$_SESSION['username'];
    $mc=$_POST['mc'];
    $people=$_POST['people'];
    
    if($people!=""){
        $peoples=array();
        $peoples=explode(',',$people);
        $people_num=count($peoples)-1;
    }
    else $people_num=0;
    
    mysqli_query($conn,"INSERT INTO class(title,dengji,mc,time,people,people_num,author) VALUES ('$title','$dengji','$mc','$time','$people','$people_num','$name')");
    
    if(mysqli_error($conn))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($conn).'"}'); 
    }else{
        exit('{"msg":"success","datas":"'.$title.'"}');
    }
}


if($_GET['action']=="del"){
    $id=$_GET['id'];
    
    if($id!=""){
        mysqli_query($conn,"DELETE FROM class WHERE id = $id");
        exit('success');
    }
    else exit('error');
}

if($_GET['action']=="query"){
    $id=$_GET['id'];
    
    $result=mysqli_query($conn,"SELECT * FROM class WHERE id='$id'");
    $row=mysqli_fetch_assoc($result);
    
    $title=$row['title'];
    $dengji=$row['dengji'];
    $mc=$row['mc'];
    $time=$row['time'];
    $author=$row['author'];
    $people=$row['people'];
    $people_num=$row['people_num'];

    if($dengji=="1") $dengji="班级";
    else if($dengji=="2") $dengji="院级";
    else if($dengji=="3") $dengji="校级";
    else if($dengji=="4") $dengji="镇级";
    else if($dengji=="5") $dengji="县级";
    else if($dengji=="6") $dengji="市级";
    else if($dengji=="7") $dengji="省级";
    else if($dengji=="8") $dengji="国家级";
    
    
    $tmp=array();
    $tmp=explode(',',$people);
    
    for($i=0;$i<$people_num;$i++){
        $head=$tmp[$i];
        $result_dy=mysqli_query($conn,"SELECT * FROM user WHERE uid=$head");
        $row_dy=mysqli_fetch_assoc($result_dy);
        if($i==$people_num-1){
            $list=$list.$row_dy['name'];
        }else{
            $list=$list.$row_dy['name'].'、';
        }
        
    }
    
    exit($title.'+ljc+'.$mc.'+ljc+'.$dengji.'+ljc+'.$time.'+ljc+'.$author.'+ljc+'.$list);
}


$title="集体荣誉";



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
  <?php if($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2")
      echo'<div class="wrapper" id="post_activity" style="display:block">
        <div class="col-sm-12">
            
                <a class="btn btn-success" href="javascript:;" onclick="post()" id="post_activity">新增荣誉</a>
           
        </div>
      </div>';
       ?>
      <?php if($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2")
            echo '<div class="wrapper" id="cancle_activity" style="display:none">
        <div class="col-sm-12">
            <a class="btn btn-warning" href="javascript:;" onclick="cancle()" id="cancle_activity">取消</a>
        </div>
    </div>';
    ?>
      
    
    <br><br>
  <?php
    if($_SESSION['usergroup']!="3"){
    echo '<div class="wrapper" id="post" style="display:none">
<div class="col-sm-12">
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >新增荣誉</div>
<div class="panel-body">
  <form name="RegForm" id="RegForm" action="#" method="post" role="form">
    <div class="form-group">
	  <label>获奖名称:</label><br/>
	  <input type="text" name="title" id="title" value="" class="form-control"/>
	</div>
	<div class="form-group">
	  <label>获奖名次:</label><br/>
	  <input type="text" name="mc" id="mc" value="" class="form-control"/>
	</div>
	<div class="form-group">
	  <label>获奖级别:</label><br/>
	  <select id="dengji" class="selectpicker show-tick form-control">
			<option value="1">班级</option>
			<option value="2">院级</option>
			<option value="3">校级</option>
			<option value="4">镇级</option>
			<option value="5">县级</option>
			<option value="6">市级</option>
			<option value="7">省级</option>
			<option value="8">国家级</option>
	  </select>
	</div>
	<div class="form-group">
	  <label>获奖时间:</label><br/>
	  <div class="input-group date form_date" id="bengindate" data-data="" data-date-format="yyyy-mm-dd">
            <input id="time" name="time" class="form-control" id="benginText" type="text" value="" placeholder="请点击右侧日期图标设置日期" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
       </div>
	</div>
	<div class="form-group">
	  <label>参与人员:&nbsp;&nbsp;<input type="button" class="btn btn-danger btn-xs" value="全选" class="btn" id="selectAll">&nbsp;<input type="button" class="btn btn-success btn-xs" value="全不选" class="btn" id="unSelect">&nbsp;<input type="button" class="btn btn-info btn-xs" value="反选" class="btn" id="reverse"></label><br/>';

	    $result_dy=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
        $dy_num=mysqli_num_rows($result_dy);
        
        $pr=1;
        for($i=1;$i<=$dy_num;$i++){
            
            $row=mysqli_fetch_assoc($result_dy);
            $name=$row['name'];
            if($pr==1){
                echo '<div class="form-check">';
            }
            
            echo('
          <input type="checkbox" class="form-check-input" id="p_'.$i.'" name="ck_box" value="'.$i.'" title="'.$name.'"">
          <label class="form-check-label" style="width: 60px;">'.$name.'</label>&nbsp;&nbsp;
        ');
            if($pr==10 || $i==$dy_num) {
                echo '</div>';
            $pr=1;
            }else $pr++;
            
        }
        
	    
	  echo'<br>
	  
	  <br><p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
	  
	
	<div class="form-group">
	    
	        <input type="submit" name="submit" value="添加" class="btn btn-primary form-control"/>
	  
	</div>	
  </form>
  </div>
</div>
 
</div>
</div>
</div>';}
  ?>
  
  
  <div class="wrapper" id="activity_list" style="display:block">
<div class="col-sm-12" id="ljc_bg">	
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">记录列表</div>
<div class="well well-sm" style="margin: 0;">本版块由班委负责录入<br>当前共有<b><?=$t_num?></b>条数据&nbsp;&nbsp;<a class="btn btn-xs btn-danger" href="javascript:;" onclick="createfile()">导出数据</a></div>
<div class="table-responsive">
    <div style="height:500px;overflow: auto;">
        <table class="table table-responsive b-t b-light text-center table-striped" style="white-space:nowrap;text-align:center;">
          <thead><th class="text-center">序号</th><th class="text-center" width="30%">获奖名称</th><th class="text-center" width="20%">获奖名次</th><th class="text-center" width="20%">获奖类型</th><th class="text-center">获奖时间</th><th class="text-center">参与人数</th><th class="text-center">操作</th></thead>
          <tbody>
	<tr class="onclick warning"  >

<?php

$a_id=0;
for($i=0;$i<$t_num;$i++){
           $result_arr=mysqli_fetch_assoc($result_t);
           $a_id++;
           $title=$result_arr['title'];
           $id=$result_arr['id'];
           $time=$result_arr['time'];
           $dengji=$result_arr['dengji'];
           $classid=$result_arr['classid'];
           $mc=$result_arr['mc'];
           $people_num=$result_arr['people_num'];
           
           if($dengji=="1") $dengji="班级";
            else if($dengji=="2") $dengji="院级";
            else if($dengji=="3") $dengji="校级";
            else if($dengji=="4") $dengji="镇级";
            else if($dengji=="5") $dengji="县级";
            else if($dengji=="6") $dengji="市级";
            else if($dengji=="7") $dengji="省级";
            else if($dengji=="8") $dengji="国家级";
           
           
           if($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2") $control='<a class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#myModal" onclick="query('.$id.')">查看详情</a>&nbsp;<a class="btn btn-success btn-xs" href="editclass?id='.$id.'">编辑</a>&nbsp;<a class="btn btn-danger btn-xs" href="javascript:;" onclick="del('.$id.')">删除</a>';
           else $control='<a class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#myModal" onclick="query('.$id.')">查看详情</a>';
            
            

            $time=$result_arr['time'];
               echo '<tr>
                           <td>'.$a_id.'</td>
                           <td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td>
                           <td>'.$mc.'</td>
                           <td>'.$dengji.'</td>
                           <td>'.$time.'</td>
                           <td>'.$people_num.'</td>
                           <td>'.$control.'</td>
                     </tr>';
                       
       }
       
       ?>


          </tbody>
        </table><?php if($a_id==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';?>
		      </div></div>
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
							获奖名称：<span id="s_title"></span><br>
							获奖名次：<span id="s_mc"></span><br>
							获奖类型：<span id="s_dengji"></span><br>
							获奖时间：<span id="s_time"></span><br>
							参与人员：<span id="s_people"></span><br>
							记录人：<span id="s_author"></span><br>
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
        </div>
        <?php include './footer.php';?>
  
  

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
    function query(getid){
        $.get('?action=query&id='+getid,function(data){
       			var strs= new Array(); 
                strs=data.split("+ljc+");
                $('#s_title').text(strs[0])
                $('#s_mc').text(strs[1])
                $('#s_dengji').text(strs[2])
                $('#s_time').text(strs[3])
                $('#s_author').text(strs[4])
                $('#s_people').text(strs[5])
       		});
       		
    }
    
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
                var time=$('#time').val();
                var dengji=$('#dengji').val();
                var mc=$('#mc').val();
                
                
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
                    url:'?action=post',
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
        
        
	function del(getid) {
        var isOK=this.window.confirm("确定要删除该记录吗？此操作不可逆！");
        if(isOK){
            $.get('?action=del&id='+getid);
            alert('删除成功');
            location.reload();
        }
    }
    
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