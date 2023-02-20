<?php

session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}

if($_SESSION['usergroup']!=1){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回首页</a>';
    exit(0);
}


include './config.php';
$uid=$_SESSION['userid'];
$author=$_SESSION['username'];

$bond_s=$_SESSION['bond'];


if($_GET['action']=="admin")
{
    $id=$_GET['id'];
    mysqli_query($conn,"UPDATE user SET usergroup='2' WHERE uid=$id");
    exit(0);
}

if($_GET['action']=="user")
{
    $id=$_GET['id'];
    mysqli_query($conn,"UPDATE user SET usergroup='3' WHERE uid=$id");
    exit(0);
}

if($_GET['action']=="rest")
{
    $id=$_GET['id'];
    include './login_config.php';
    mysqli_query($connect_login,"UPDATE all_users SET passwd='024a11a394466247356c616151b0ad37' WHERE classid='$id'");
    exit(0);
}

//$result=mysql_query("SELECT * FROM user WHERE name=$name");
    $result=mysqli_query($conn,"SELECT * FROM user WHERE uid=$uid");
    $row=mysqli_fetch_assoc($result);

    $result_t=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
    $t_num=mysqli_num_rows($result_t);
    
    $title="系统账号管理";

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
  
<div class="wrapper">
<div class="col-sm-12" id="ljc_bg">	
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">账号列表</div>
<div class="well well-sm" style="margin: 0;" id="ljc">共<b><?=$t_num;?></b>个账号</div>
<div class="table-responsive">
        <table class="table table-striped b-t b-light text-center">
          <thead><th class="text-center">序号</th><th class="text-center">姓名</th><th class="text-center">账号</th><th class="text-center">初始密码</th><th class="text-center">用户组</th><th class="text-center">切换用户组</th><th class="text-center">操作</th></thead>
    <tbody>
    	<tr class="onclick warning">

<?php
$id=0;
for($i=0;$i<$t_num;$i++){
                    $id++;
                   $result_arr=mysqli_fetch_assoc($result_t);
                   
                   $name=$result_arr['name'];
                   $classid=$result_arr['classid'];
                   $usergroup=$result_arr['usergroup'];
                   if($usergroup=="1"){
                       $qx="<font color=\"orange\">系统管理员</font>";
                       $shift='<a class="btn btn-info btn-xs" href="javascript:;">系统管理员</a>';
                   }
                   else if($usergroup=="2"){
                       $qx="<font color=\"red\">管理员</font>";
                       $shift='<a class="btn btn-success btn-xs" onclick="set_user('.$id.')">设置为普通用户</a>';
                   }
                   else if($usergroup=="3"){
                       $qx="普通用户";
                       $shift='<a class="btn btn-danger btn-xs" onclick="set_admin('.$id.')">设置为管理员</a>';
                   }
                   

                   $huifu='<a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" onclick="rest_pwd('.$classid.')">重置密码</a>';
                   
                   echo "<tr>
                               <td>$id</td>
                               <td>$name</td>
                               <td>$classid</td>
                               <td>123456</td>
                               <td>$qx</td>
                               <td>$shift</td>
                               <td>$huifu</td>
                         </tr>";
                   
    	        }
    	    ?>


          </tbody>
        </table>
		      </div>
</div>
</div>
</div>
  
  </div>
  </div>
<br><br>

<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>


<script language="JavaScript">
	function set_admin(getid) {
        var isOK=this.window.confirm("确定要设置该用户为管理员吗？");
        if(isOK){
            $.get('?action=admin&id='+getid);
            alert('操作成功');
            location.reload();
        }
    }
    function set_user(getid) {
        var isOK=this.window.confirm("确定要设置该用户为普通用户吗？");
        if(isOK){
            $.get('?action=user&id='+getid);
            alert('操作成功');
            location.reload();
        }
    }
    function rest_pwd(getid) {
        var isOK=this.window.confirm("确定要重置该用户的密码吗？");
        if(isOK){
            $.get('?action=rest&id='+getid);
            toastr.success("操作成功");
        }
    }


    $("img").mousedown(function(){
        return false;
    });

</script>

