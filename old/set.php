<?php

session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}

include './config.php';
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

$title="信息修改";

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
<div class="wrapper">
<div class="col-sm-12" id="ljc_bg">
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >用户资料设置</div>
<div class="panel-body">

  <div class="form-group">
	  <label>姓名:</label><br/>
	  <input type="text" name="name" value="<?php echo $row['name'];?>" class="form-control" readonly="readonly"/>
	</div>
    <div class="form-group">
	  <label>用户ID</label>:</label><br/>
	  <input type="text" name="uid" value="<?php echo $row['classid'];?>" class="form-control" readonly="readonly"/>
	</div>
	<div class="form-group">
	  <label>手机号:</label><br/>
	  <input type="text" name="tel" value="<?php echo $row['tel'];?>" class="form-control" readonly="readonly"/>
	</div>
	<div class="form-group">
	  <label>新密码:</label><br/>
	  <input type="text" name="pwd" id="pwd" value="" class="form-control" placeholder="请输入修改的密码"/>
	</div>
	<p style="color:red;">密码至少8个字符,必须包含字母、数字！</p>
	<div class="form-group">
	  <input type="submit" name="submit" value="提交修改" onclick="change()" class="btn btn-primary form-control"/>
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
