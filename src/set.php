<?php

session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}

include './config.php';
include './login_config.php';
$uid=$_SESSION['userid'];
$classid=$_SESSION['classid'];

if($_GET['action']=="mod")
{
    $pwd=$_POST['pwd'];
    $pwd=md5(md5($pwd).'_ljcsys');
    if(!empty($pwd))
    {
        include './login_config.php';
        mysqli_query($connect_login,"UPDATE all_users SET passwd='$pwd' WHERE classid='$classid'");
    }
    if(mysqli_error($connect_login))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($connect_login).'"}'); 
    }else{
        exit('{"msg":"success"}');
    }
    
}

//$result=mysql_query("SELECT * FROM user WHERE name=$name");
$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");

$row=mysqli_fetch_assoc($result);
$result_all_users=mysqli_query($connect_login,"SELECT * FROM all_users WHERE classid='$classid'");
$row_all_users=mysqli_fetch_assoc($result_all_users);

$title="信息修改";

?>
<?php include 'head.php';?>
<div id="content" role="main">
	<div class="app-content-body ">
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

		<div class="wrapper">
		    <div class="col-lg-6 col-md-6 col-sm-12" id="ljc_bg">
				<div class="panel panel-default">
					<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);">
						<h3 class="panel-title">
							<font color="#fff">
								<i class="fa fa-globe"></i>&nbsp;&nbsp;<b>我的信息</b>
							</font>
						</h3>
					</div>
					<ul class="list-group no-radius">
						<li class="list-group-item">
							<b>姓名：</b>
							<font color="orange">
								<b><?php echo $row['name'];?></b>
							</font>
						</li>
						<li class="list-group-item">
							<b>职务：</b>
							<font color="red">
								<b><?php echo $row['zhiwu'];?></b>
							</font>
						</li>
						<li style="font-weight:bold" class="list-group-item">用户权限：<a data-toggle="modal" class="btn btn-xs btn-danger">
								<?php
					        if($_SESSION['usergroup']=="1") echo '系统管理员';
					        if($_SESSION['usergroup']=="2") echo '管理员';
					        if($_SESSION['usergroup']=="3") echo '普通用户';
					     ?>
								</b>
							</a>
						</li>
						<li class="list-group-item">
							<b>QQ登录绑定状态：</b>
							<?php if($row_all_users['qqid']=="") echo '<font color="red"><b>未绑定</b></font>&nbsp;<a href="javascript:;" onclick="toLogin()" class="btn btn-xs btn-success">绑定</a>'; else echo '<font color="green"><b>已绑定</b></font>';?>
						</li>
						<li style="font-weight:bold" class="list-group-item">程序版本：<font color="purple">v2.0.0(2211.1)</font>&nbsp;<a href="./about.html" target="_blank" class="btn btn-xs btn-warning">关于程序</a>&nbsp;<a href="https://support.qq.com/product/348012" target="_blank" class="btn btn-xs btn-success">反馈</a>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="col-lg-6 col-md-6 col-sm-12" id="ljc_bg">
				<div class="panel panel-default">
					<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">用户资料设置</div>
					<div class="panel-body">

						<div class="form-group">
							<label>姓名:</label>
							<br />
							<input type="text" name="name" value="<?php echo $row['name'];?>" class="form-control" readonly="readonly" />
						</div>
						<div class="form-group">
							<label>用户ID</label>:</label>
							<br />
							<input type="text" name="uid" value="<?php echo $row['classid'];?>" class="form-control" readonly="readonly" />
						</div>
						<div class="form-group">
							<label>手机号:</label>
							<br />
							<input type="text" name="tel" value="<?php echo $row['tel'];?>" class="form-control" readonly="readonly" />
						</div>
						<div class="form-group">
							<label>新密码:</label>
							<br />
							<input type="text" name="pwd" id="pwd" value="" class="form-control" placeholder="请输入修改的密码" />
						</div>
						<p style="color:red;">密码至少8个字符,必须包含字母、数字！</p>
						<div class="form-group">
							<input type="submit" name="submit" value="提交修改" onclick="change()" class="btn btn-primary form-control" />
						</div>

					</div>
				</div>

			</div>

			
		</div>

	</div>
	<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
	<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
	<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
	<script src="./js/watermark.js"></script>

	<script>
		$("img").mousedown(function(){
				        return false;
				    });
				    function change(){
				        var pwd=$('#pwd').val()
				        var pwdRegex = new RegExp('(?=.*[0-9])(?=.*[a-zA-Z]).{8,30}')
				        if (pwd=="") {
				            toastr.error("请输入修改的密码！");
				            $('#pwd').focus()
				            return false
				        }
				        else if(!pwdRegex.test(pwd)) {
				            toastr.error("密码强度不符合要求！");
				            $('#pwd').focus()
				            return false
				        }
				        else{
				            $.ajax({
				                type:'post',
				                url:'?action=mod',
				                data:{
				                    pwd:pwd
				                },
				                dataType:'json',
				                success: function(res){
				                    console.log(res)
				                    toastr.success("操作成功")
				                    $('#pwd').val('')
				                }
				            })
				        }
				        
				    }
	</script>