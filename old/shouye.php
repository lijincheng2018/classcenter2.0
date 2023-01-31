<?php

include './config.php';
include './login_config.php';
session_start();

if(!isset($_SESSION['userid']) )
{
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


$uid=$_SESSION['userid'];
$classid=$_SESSION['classid'];


//$result=mysql_query("SELECT * FROM user WHERE name=$name");
$result=mysqli_query($conn,"SELECT * FROM user WHERE uid=$uid");
$row=mysqli_fetch_assoc($result);

$result_t=mysqli_query($conn,"SELECT * FROM collect_list ORDER BY id desc");
$t_num=mysqli_num_rows($result_t);

$result_user=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
$user_num=mysqli_num_rows($result_user);

$result_all_users=mysqli_query($connect_login,"SELECT * FROM all_users WHERE classid='$classid'");
$row_all_users=mysqli_fetch_assoc($result_all_users);
if($row_all_users['passwd']=="024a11a394466247356c616151b0ad37") $nopass=1;
else $nopass=0;


if($_GET['action']=="query")
{
    $id=$_GET['id'];
    $result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$id'");
    $row=mysqli_fetch_assoc($result);
    
    $classid=$row['classid'];
    $uid=$row['uid'];
    $name=$row['name'];
    $sex=$row['sex'];
    $year=$row['year'];
    $xueyuan=$row['xueyuan'];
    $class=$row['class'];
    $zhiwu=$row['zhiwu'];
    $sushe=$row['sushe'];
    $tel=$row['tel'];
    $zzmm=$row['zzmm'];

    
    exit($name.'+ljc+'.$sex.'+ljc+'.$classid.'+ljc+'.$uid.'+ljc+'.$year.'+ljc+'.$xueyuan.'+ljc+'.$class.'+ljc+'.$tel.'+ljc+'.$zhiwu.'+ljc+'.$sushe.'+ljc+'.$zzmm);
    
}


$title="首页";


?>
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><?=$title?></li>
					</ul>
				</div>
		
	</div>


  <!-- / aside -->
  <style>
.msg-head{text-align: center;min-width: 360px;padding: 7px;background-color: #f9f9f9 !important;}
.msg-body{padding: 15px;margin-bottom: 20px;}
</style>
  <div class="col-sm-12">	
</div>
<div class="col-lg-4 col-md-6 col-sm-12">
	<br>
	
	<div class="panel panel-default">
			<div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>公告</b></font></h3>
			</div>
			    
			<table class="table table-responsive table-striped b-t b-light text-center">
            	<thead>
            		<tr style="white-space:nowrap;">
            			<th class="text-center" width="60%">公告内容</th>
            			<th class="text-center" width="20%">发布者</th>
            			<th class="text-center" width="20%">发布时间</th>
            		</tr>
            	</thead>
            	<tbody id="page_table"><tr></tr></tbody>
            </table>
            <br>
            <div class="text-center" id="page_bar"></div><br>
 
   </div>
	

	<!--div class="panel panel-default">
	    
	    <div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-globe"></i>&nbsp;&nbsp;<b>我的信息</b></font></h3>
			</div>

		<ul class="list-group no-radius">
					<li class="list-group-item"><b>姓名：</b><font color="orange"><b><?php echo $row['name'];?></b></font></li>
					<li class="list-group-item"><b>职务：</b><font color="red"><b><?php echo $row['zhiwu'];?></b></font></li>
					<li style="font-weight:bold" class="list-group-item">用户权限：<a data-toggle="modal" class="btn btn-xs btn-danger"><?php/* 
					        if($_SESSION['usergroup']=="1") echo '系统管理员';
					        if($_SESSION['usergroup']=="2") echo '管理员';
					        if($_SESSION['usergroup']=="3") echo '普通用户';
					     ?></b></a></li>
					<li class="list-group-item"><b>QQ登录绑定状态：</b><?php if($row_all_users['qqid']=="") echo '<font color="red"><b>未绑定</b></font>&nbsp;<a href="javascript:;" onclick="toLogin()" class="btn btn-xs btn-success">绑定</a>'; else echo '<font color="green"><b>已绑定</b></font>';*/?></li>
					<li style="font-weight:bold" class="list-group-item">程序版本：<font color="purple">v1.1(2210.2)</font>&nbsp;<a href="./about.html" target="_blank" class="btn btn-xs btn-warning">关于程序</a>&nbsp;<a href="https://support.qq.com/product/348012" target="_blank" class="btn btn-xs btn-success">反馈</a></li>
		</ul>
	</div>
	    <!--div class="panel panel-default">
			<div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>系统公告</b></font></h3>
			</div>
			<div class="alert alert-info"><center>欢迎进入班级信息中心！</center></div>
<li class="list-group-item"><span class="badge pull-left" style="background-color: #6699FF">信息</span>&nbsp;本系统正式接入QQ登录，绑定QQ即可快速登录</li>
   </div-->
</div>
	

<div class="col-lg-8 col-md-6 col-sm-12">
    <br>
		<div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);padding: 15px;color: white;">
				<div class="widget-content text-right clearfix">
					<img src="./images/icon-user-center-avatar@2x.png" alt="Avatar"
					width="66" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
					<h4><font color="yellow"><?php echo $row['name'];?></font></h4>
					<h4><a data-toggle="modal" class="btn btn-xs btn-info"><b><?php echo $row['zhiwu'];?></b></a>&nbsp;<a data-toggle="modal" class="btn btn-xs btn-danger"><b>
					    <?php 
					        if($_SESSION['usergroup']=="1") echo '系统管理员';
					        if($_SESSION['usergroup']=="2") echo '管理员';
					        if($_SESSION['usergroup']=="3") echo '普通用户';
					     ?></b></a></h4>
				</div>
			</div>

		    <table class="table">
    	           <tbody>
    	               <tr>
                      <td width="50%"><a href="./say" class="bttn-material-flat bttn-md bttn-primary btn-block text-center"><b>我对班委有话说</b></a></td>
                      <td width="50%"><a href="./random" class="bttn-material-flat bttn-md bttn-warning btn-block text-center"><b>随机抽号</b></a></td>
                      </tr>
                    </tbody>
                </table>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12">
    	<div class="panel panel-default">
    	    
    	    <div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
    				<h3 class="panel-title"><font color="#fff"><i class="fa fa-globe"></i>&nbsp;&nbsp;<b>我的</b></font></h3>
    			</div>
    			<table class="table">
    	           <tbody>
    	               <tr>
                      <td><a href="./personal" class="btn btn-success btn-block"><b>我的任务</b></a></td>
                      <td><a href="./class" class="btn btn-danger btn-block"><b>个人荣誉</b></a></td>
                      <td><a href="./document" class="btn btn-info btn-block"><b>履历记录</b></a></td>
                      </tr>
                    </tbody>
                </table>
                <ul class="list-group no-radius">
					<li class="list-group-item"><b>我的任务：</b></li>
		        </ul>
		        <div style="height:220px;overflow: auto;">
                <table class="table table-responsive table-striped b-t b-light text-center" >
                	<thead>
                		<tr style="white-space:nowrap;">
                			<th class="text-center" width="60%">任务名称</th>
                			<th class="text-center" width="20%">截止时间</th>
                			<th class="text-center" width="20%">完成状态</th>
                		</tr>
                	</thead>
                	
                	<tbody>
                	    <?php
                    $a_id=0;
                        for($i=0;$i<$t_num;$i++){
                               $result_arr=mysqli_fetch_assoc($result_t);
                                $a_id++;
                                $title=$result_arr['title'];
                                $fid=$result_arr['id'];
                                $time=$result_arr['time'];
                                $author=$result_arr['author'];
                                $jiezhi_time=$result_arr['jiezhi_time'];
                                   
                                    $people_num=$result_arr['people_num'];
                                    $people=$result_arr['people'];
                                    $peoples=array();
                                    $peoples=explode(',',$people);
                                    
                                    for($j=0;$j<$people_num;$j++)
                                    {
                                        if($peoples[$j]==$uid){
                                            $reg_datalist=$result_arr['bond'];
                                            $result_get_name=mysqli_query($conn,"SELECT * FROM $reg_datalist WHERE id='$uid'");
                                            $row_get_name=mysqli_fetch_assoc($result_get_name);
                                            $pd=$row_get_name['pd'];
                                            if($pd=="0") $mc="<b><font color=\"red\">未完成</font></b>";
                                            else $mc="<b><font color=\"green\">已完成</font></b>";
                                            
                                            echo '<tr>
                                                        <td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td>
                                                        <td>'.$jiezhi_time.'</td>
                                                        <td>'.$mc.'</td>
                                                </tr>';
                                            break;
                                        }
                                    }
                                  
                               }
                               
                               
                           
                           ?>
                	</tbody>
                </table></div>
            
            
    	</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12">
    	<div class="panel panel-default">
    	    
    	    <div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
    				<h3 class="panel-title"><font color="#fff"><i class="fa fa-globe"></i>&nbsp;&nbsp;<b>班级</b></font></h3>
    			</div>
    			<table class="table">
    	           <tbody>
    	               <tr>
                      <td><a href="./personal" class="btn btn-success btn-block"><b>集体荣誉</b></a></td>
                      <td><a href="./class" class="btn btn-danger btn-block"><b>班费明细</b></a></td>
                      <td><a href="./document" class="btn btn-info btn-block"><b>通讯录</b></a></td>
                      </tr>
                    </tbody>
                </table>
                <ul class="list-group no-radius">
					<li class="list-group-item"><b>通讯录：</b></li>
		        </ul>
		        <div style="height:220px;overflow: auto;">
                <table class="table table-responsive table-striped b-t b-light text-center">
                	<thead>
                		<tr style="white-space:nowrap;">
                			<th class="text-center" width="30%">姓名</th>
                			<th class="text-center" width="40%">电话</th>
                			<th class="text-center" width="30%">详细信息</th>
                		</tr>
                	</thead>
                	<tbody>
                	<?php
                        $id=0;
                        for($i=0;$i<$user_num;$i++){
                    
                                   $id++;
                
                                   $result_arr=mysqli_fetch_assoc($result_user);
                                   
                                   $name=$result_arr['name'];
                                   $tel=$result_arr['tel'];
                                       
                                    $del='<a class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal" onclick="ljcquery('.$id.')">详细资料</a>';
                                       echo "<tr>
                                               <td>$name</td>
                                               <td><a href=\"tel:$tel\">$tel</a></td>
                                               <td>$del</td>
                                            </tr>";
                                   
                    	        }
                    	    ?>
                	    
                	</tbody>
                </table></div>
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
							姓名：<span id="s_name"></span><br>
							性别：<span id="s_sex"></span><br>
							学号：<span id="s_classid"></span><br>
							号数：<span id="s_uid"></span><br>
							入学年份：<span id="s_year"></span><br>
							学院：<span id="s_xueyuan"></span><br>
							班级：<span id="s_class"></span><br>
							电话：<span id="s_tel"></span><br>
							职务：<span id="s_zhiwu"></span><br>
							宿舍：<span id="s_sushe"></span><br>
							政治面貌：<span id="s_zzmm"></span><br>
						</div>
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

</div>
</div>

</div>
<div class="container-fluid text-center">
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            
			<h4 class="modal-title" id="myModalLabel">
              	修改密码
            </h4>
						</div>
						<div class="modal-body" style="text-align:left">
						    <h3>系统检测到你还没有修改过密码，为了账号安全，请尽快修改默认密码！</h3>
						    <div class="form-group">
							    <label>原密码：</label><br/>
                        	    <input type="text" name="or_pass" id="or_pass" value="123456" placeholder="请输入原密码" class="form-control" readonly/>
                        	</div>
                        	<div class="form-group">
							    <label>请输入新密码：</label><br/>
                        	    <input type="password" name="new_passwd" id="new_passwd" value="" placeholder="请输入新密码" class="form-control"/>
                        	</div>
                        	<div class="form-group">
							    <label>再次输入新密码：</label><br/>
                        	    <input type="password" name="new_passwd_again" id="new_passwd_again" value="" placeholder="再次输入新密码" class="form-control"/>
                        	</div>
                        	<p style="color:red;">密码至少8个字符,必须包含字母、数字！</p>
                        	<input type="submit" id="confirm_change" name="confirm_change" onclick="change()" value="确认修改" class="btn btn-success form-control"/>
						</div>
						<br>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">暂不修改
            </button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal -->
			</div>
<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
 <script type="text/javascript">
    $("img").mousedown(function(){return false;});
    function toLogin(){window.location.href="./oauth/qq_login.php";}
    $(function ()  
    {
        if(<?=$nopass?>)
        $("#myModal").modal('show');  
    });
    function change(){
        var new_passwd=$('#new_passwd').val();
        var new_passwd_again=$('#new_passwd_again').val();
        var pwdRegex = new RegExp('(?=.*[0-9])(?=.*[a-zA-Z]).{8,30}')
        
        if(new_passwd==""){
            toastr.error("请输入新密码!");
            $('#new_passwd').focus();
            return false;
        }
        else if(!pwdRegex.test(new_passwd)) {
            toastr.error("密码强度不符合要求！");
            $('#new_passwd').focus()
            return false
        }
        else if(new_passwd_again==""){
            toastr.error("请再次输入新密码!");
            $('#new_passwd_again').focus();
            return false;
        }
        else if(new_passwd!=new_passwd_again){
            toastr.error("两次密码不一致，请重新输入!");
            return false;
        }
        else{
            $.ajax({
            type:'post',
            url:'set?action=mod',
               data:{
                    pwd:new_passwd
                },
                dataType:'json',
                success: function(res){
                    console.log(res)
                    $("#myModal").modal('hide');
                    toastr.success("修改成功!");
                }
            })
        }
        
    }
 </script>
 
 <script type="text/javascript">
    var tableData=[];
    $(function(){
        $.ajax({
            type:'get',
            url:'notice?data=notice',
            dataType:'json',
            success: function(res){
                for (let i in res) {
                    tableData.push(res[i])
                 }
                 splitPage(1,7);
            }
        })
    })
    JSON.stringify(tableData)
    console.log(tableData)
	var columns = [ {
		"cid" : "title",
		"ctext" : "公告内容"
	}, {
	    "cid" : "author",
		"ctext" : "发布者"
		
	}, {
		"cid" : "time",
		"ctext" : "发布时间"
	} ];
	function splitPage(page, pageSize) {
		var ptable = document.getElementById("page_table");
		var num = ptable.rows.length;
		for ( var i = num - 1; i > 0; i--) {
			ptable.deleteRow(i);
		}
		var totalNums = tableData.length;
		var totalPage = Math.ceil(totalNums / pageSize);
		var begin = (page - 1) * pageSize;
		var end = page * pageSize;
		end = end > totalNums ? totalNums : end;
		var n = 1;
		for ( var i = begin; i < end; i++) {
			var row = ptable.insertRow(n++);
			var rowData = tableData[i];
			for ( var j = 0; j < columns.length; j++) {
				var col = columns[j].cid;
				var cell = row.insertCell(j);
				var cellData = rowData[col];
				cell.innerHTML = cellData;
				console.log(cellData)
			}
		}
		var pageBar = "第" + page + "页/共" + totalPage + "页" + " ";
		if (page > 1) {
			pageBar += "<br><a href=\"javascript:splitPage(" + 1 + "," + pageSize + ");\" class=\"btn btn-xs btn-success\">首页</a> ";
		} else {
			pageBar += "<br><a href=\"javascript:;\" class=\"btn btn-xs btn-success\" disabled>首页</a> ";
		}
		if (page > 1) {
			pageBar += "<a href=\"javascript:splitPage(" + (page - 1) + "," + pageSize + ");\" class=\"btn btn-xs btn-primary\">上一页</a> ";
		} else {
			pageBar += "<a href=\"javascript:;\" class=\"btn btn-xs btn-primary\" disabled>上一页</a> ";
		}
		if (page < totalPage) {
			pageBar += "<a href=\"javascript:splitPage(" + (page + 1) + "," + pageSize + ");\" class=\"btn btn-xs btn-primary\">下一页</a> ";
		} else {
			pageBar += "<a href=\"javascript:;\" class=\"btn btn-xs btn-primary\" disabled>下一页</a> ";
		}
		if (page < totalPage) {
			pageBar += "<a href=\"javascript:splitPage(" + (totalPage) + "," + pageSize + ");\" class=\"btn btn-xs btn-warning\">尾页</a> ";
		} else {
			pageBar += "<a href=\"javascript:;\" class=\"btn btn-xs btn-warning\" disabled>尾页</a> ";
		}
		document.getElementById("page_bar").innerHTML = pageBar;
	}
	
    
</script>

<script language="JavaScript">
    $(function(){
                $('#myModal').modal("hide");
            });
	function ljcquery(getid) {
        $.get('?action=query&id='+getid,function(data){
       			var strs= new Array(); 
                strs=data.split("+ljc+");

			    $('#s_name').text(strs[0])
                $('#s_sex').text(strs[1])
                $('#s_classid').text(strs[2])
                $('#s_uid').text(strs[3])
                $('#s_year').text(strs[4])
                $('#s_xueyuan').text(strs[5])
                $('#s_class').text(strs[6])
                $('#s_tel').text(strs[7])
                $('#s_zhiwu').text(strs[8])
                $('#s_sushe').text(strs[9])
                $('#s_zzmm').text(strs[10])
       		});
       		
    }
</script>