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

$result_all_users=mysqli_query($connect_login,"SELECT * FROM all_users WHERE classid='$classid'");
$row_all_users=mysqli_fetch_assoc($result_all_users);
if($row_all_users['passwd']=="024a11a394466247356c616151b0ad37") $nopass=1;
else $nopass=0;

$title="首页";


?>

  <?php include 'head.php';?>
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
  <style>
.msg-head{text-align: center;min-width: 360px;padding: 7px;background-color: #f9f9f9 !important;}
.msg-body{padding: 15px;margin-bottom: 20px;}
</style>
  <div class="col-sm-12">	
</div>

<div class="col-sm-12">
</div>
	<div class="col-lg-4 col-md-6 col-sm-12">
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

		          
                  <td><a href="./personal" class="btn btn-success btn-block"><b>个人荣誉</b></a></td>
                  <td><a href="./class" class="btn btn-danger btn-block"><b>集体荣誉</b></a></td>
                  <td><a href="./document" class="btn btn-info btn-block"><b>履历记录</b></a></td>
                  </tr>
                </tbody>
            </table>
	</div>
	
	<div class="panel panel-default">
	    
	    <div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-globe"></i>&nbsp;&nbsp;<b>我的信息</b></font></h3>
			</div>

		<ul class="list-group no-radius">
					<li class="list-group-item"><b>姓名：</b><font color="orange"><b><?php echo $row['name'];?></b></font></li>
					<li class="list-group-item"><b>职务：</b><font color="red"><b><?php echo $row['zhiwu'];?></b></font></li>
					<li style="font-weight:bold" class="list-group-item">用户权限：<a data-toggle="modal" class="btn btn-xs btn-danger"><?php 
					        if($_SESSION['usergroup']=="1") echo '系统管理员';
					        if($_SESSION['usergroup']=="2") echo '管理员';
					        if($_SESSION['usergroup']=="3") echo '普通用户';
					     ?></b></a></li>
					<li class="list-group-item"><b>QQ登录绑定状态：</b><?php if($row_all_users['qqid']=="") echo '<font color="red"><b>未绑定</b></font>&nbsp;<a href="javascript:;" onclick="toLogin()" class="btn btn-xs btn-success">绑定</a>'; else echo '<font color="green"><b>已绑定</b></font>';?></li>
					<li style="font-weight:bold" class="list-group-item">程序版本：<font color="purple">v1.1(2210.2)</font>&nbsp;<a href="./about.html" target="_blank" class="btn btn-xs btn-warning">关于程序</a>&nbsp;<a href="https://support.qq.com/product/348012" target="_blank" class="btn btn-xs btn-success">反馈</a></li>
		</ul>
	</div>
			<div class="panel panel-default">
			<div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>系统公告</b></font></h3>
			</div>
			<div class="alert alert-info"><center>欢迎进入班级信息中心！</center></div>
<li class="list-group-item"><span class="badge pull-left" style="background-color: #6699FF">信息</span>&nbsp;本系统正式接入QQ登录，绑定QQ即可快速登录</li>
   </div>
</div>

<div class="col-lg-8 col-md-6 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>公告</b></font></h3>
			</div>
			    <table class="table">
    	           <tbody>
    	               <tr>
                      <td width="50%"><a href="./say" class="bttn-material-flat bttn-md bttn-primary btn-block text-center"><b>我对班委有话说</b></a></td>
                      <td width="50%"><a href="./random" class="bttn-material-flat bttn-md bttn-warning btn-block text-center"><b>随机抽号</b></a></td>
                      </tr>
                    </tbody>
                </table>
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
<center><img height="50px" width="auto" src="./images/<?=$_SESSION['logo_url']?>"></center>
 
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
<script src="./js/echarts.min.js"></script>

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