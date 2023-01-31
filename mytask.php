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
$result_t=mysqli_query($conn,"SELECT * FROM collect_list ORDER BY id desc");
$t_num=mysqli_num_rows($result_t);

$result_dj=mysqli_query($conn,"SELECT * FROM register_list ORDER BY id desc");
$dj_num=mysqli_num_rows($result_t);
$result_dj_1=mysqli_query($conn,"SELECT * FROM register_list ORDER BY id desc");

$result_t_1=mysqli_query($conn,"SELECT * FROM collect_list ORDER BY id desc");
$list=0;

for($i=0;$i<$t_num;$i++)
{
    $result_arr=mysqli_fetch_assoc($result_t_1);
    $people_num=$result_arr['people_num'];
    $people=$result_arr['people'];
    $peoples=array();
    $peoples=explode(',',$people);
    
    for($j=0;$j<$people_num;$j++)
    {
        if($peoples[$j]==$uid){
            $list++;
            break;
        }
    }
}

$list_dj=0;

for($i=0;$i<$t_num;$i++)
{
    $result_arr=mysqli_fetch_assoc($result_dj_1);
    $people_num=$result_arr['people_num'];
    $people=$result_arr['people'];
    $is_public=$result_arr['is_public'];
    if($is_public=="1"){
        $peoples=array();
        $peoples=explode(',',$people);
        
        for($j=0;$j<$people_num;$j++)
        {
            if($peoples[$j]==$uid){
                $list_dj++;
                break;
            }
        }
    }
    
}






$title="我的任务";



?>



<?php include 'head.php';?>
<div id="content" role="main">
	<div class="bg-light lter b-b wrapper-sm ng-scope">
		<ul class="breadcrumb" style="padding: 0;margin: 0;">
			<li>
				<i class="fa fa-home"></i>
				<a href="./">班级信息中心</a>
			</li>
			<li>
				<?=$title?>
			</li>&nbsp;&nbsp;<a class="bttn-material-flat bttn-xs bttn-primary text-center" href="./index">返回首页</a>
		</ul>
	</div>
	<!-- / aside -->

		<div class="col-sm-12" id="ljc_bg">
			<div class="panel panel-default">
				<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">收到的登记表</div>
				<div class="well well-sm" style="margin: 0;">你共需要完成<b>
						<?=$list_dj?>
					</b>张登记表</div>
				<div class="table-responsive">
					<div style="height:240px;overflow: auto;">
						<table class="table table-responsive b-t b-light text-center table-striped" style="white-space:nowrap;text-align:center;">
							<thead>
								<th class="text-center">序号</th>
								<th class="text-center" width="20%">登记表标题</th>
								<th class="text-center" width="20%">是否完成</th>
								<th class="text-center" width="20%">发布人</th>
								<th class="text-center">发布时间</th>
								<th class="text-center">操作</th>
							</thead>
							<tbody>
								<tr class="onclick warning">

									<?php
                                        $a_id=0;
                                        for($i=0;$i<$t_num;$i++){
                                            $result_arr=mysqli_fetch_assoc($result_dj);
                                            $title=$result_arr['title'];
                                            $fid=$result_arr['id'];
                                            $time=$result_arr['time'];
                                            $author=$result_arr['author'];
                                            $is_public=$result_arr['is_public'];
                                            if($is_public=="1")
                                            {
                                                
                                                $people_num=$result_arr['people_num'];
                                                $people=$result_arr['people'];
                                                $peoples=array();
                                                $peoples=explode(',',$people);
                                                
                                                for($j=0;$j<$people_num;$j++)
                                                {
                                                    if($peoples[$j]==$uid){
                                                        $a_id++;
                                                        $reg_datalist=$result_arr['bond'];
                                                        $result_get_name=mysqli_query($conn,"SELECT * FROM $reg_datalist WHERE id='$uid'");
                                                        $row_get_name=mysqli_fetch_assoc($result_get_name);
                                                        $pd=$row_get_name['status'];
                                                        if($pd=="0"){
                                                            $mc="<b><font color=\"red\">未完成</font></b>";
                                                            $control='<a class="btn btn-danger btn-xs" href="./finish_register?id='.$fid.'">前往完成</a>';

                                                        }
                                                        else{
                                                            $mc="<b><font color=\"green\">已完成</font></b>";
                                                            $control='<a class="btn btn-success btn-xs" href="./finish_register?id='.$fid.'">查看记录</a>';
                                                        }
                                                        
                                                        echo '<tr>
                                                                    <td>'.$a_id.'</td>
                                                                    <td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td>
                                                                    <td>'.$mc.'</td>
                                                                    <td>'.$author.'</td>
                                                                    <td>'.$time.'</td>
                                                                    <td>'.$control.'</td>
                                                            </tr>';
                                                        break;
                                                    }
                                                }
                                            }
                                              
                                        }
                                    ?>
							</tbody>
						</table>
						<?php if($a_id==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';?>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12" id="ljc_bg">
			<div class="panel panel-default">
				<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">收到的文件收集表</div>
				<div class="well well-sm" style="margin: 0;">你共需要完成<b>
						<?=$list?>
					</b>张文件收集表</div>
				<div class="table-responsive">
					<div style="height:240px;overflow: auto;">
						<table class="table table-responsive b-t b-light text-center table-striped" style="white-space:nowrap;text-align:center;">
							<thead>
								<th class="text-center">序号</th>
								<th class="text-center" width="20%">收集表标题</th>
								<th class="text-center" width="20%">是否完成</th>
								<th class="text-center" width="20%">发布人</th>
								<th class="text-center">发布时间</th>
								<th class="text-center">操作</th>
							</thead>
							<tbody>
								<tr class="onclick warning">

									<?php
$a_id=0;
    for($i=0;$i<$t_num;$i++){
           $result_arr=mysqli_fetch_assoc($result_t);
            $title=$result_arr['title'];
            $fid=$result_arr['id'];
            $time=$result_arr['time'];
            $author=$result_arr['author'];
               
                $people_num=$result_arr['people_num'];
                $people=$result_arr['people'];
                $peoples=array();
                $peoples=explode(',',$people);
                
                for($j=0;$j<$people_num;$j++)
                {
                    if($peoples[$j]==$uid){
                        $a_id++;
                        $reg_datalist=$result_arr['bond'];
                        $result_get_name=mysqli_query($conn,"SELECT * FROM $reg_datalist WHERE id='$uid'");
                        $row_get_name=mysqli_fetch_assoc($result_get_name);
                        $pd=$row_get_name['pd'];
                        if($pd=="0"){
                            $mc="<b><font color=\"red\">未完成</font></b>";
                            $control='<a class="btn btn-danger btn-xs" href="./collect_file?id='.$fid.'">前往提交</a>';

                        }
                        else{
                            $mc="<b><font color=\"green\">已完成</font></b>";
                            $control='<a class="btn btn-success btn-xs" href="./collect_file?id='.$fid.'">查看记录</a>';
                        }
                        
                        echo '<tr>
                                    <td>'.$a_id.'</td>
                                    <td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td>
                                    <td>'.$mc.'</td>
                                    <td>'.$author.'</td>
                                    <td>'.$time.'</td>
                                    <td>'.$control.'</td>
                            </tr>';
                        break;
                    }
                }
              
           }
           
           
       
       ?>


							</tbody>
						</table>
						<?php if($a_id==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';?>
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
						获奖名称：<span id="s_title"></span>
						<br>
						获奖名次：<span id="s_mc"></span>
						<br>
						获奖类型：<span id="s_dengji"></span>
						<br>
						获奖时间：<span id="s_time"></span>
						<br>
						获奖人学号：<span id="s_classid"></span>
						<br>
						记录人：<span id="s_author"></span>
						<br>
					</div>
					<br>
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
		                $('#s_classid').text(strs[4])
		                $('#s_author').text(strs[5])
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
		                
		                $.ajax({
		                    type:'post',
		                    url:'?action=post',
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