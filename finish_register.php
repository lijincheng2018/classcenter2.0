<?php
//error_reporting(0);
session_start();
if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
$uid=$_SESSION['userid'];
$classid=$_SESSION['classid'];

$form_id=$_GET['id'];

$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);

$check_query = mysqli_query($conn,"select title from register_list where id='$form_id' limit 1");
if (!mysqli_fetch_array($check_query)) $ishere=0;
else $ishere=1;

if($ishere==1){
    $result_t=mysqli_query($conn,"SELECT * FROM register_list WHERE id=$form_id");
    $row_reg=mysqli_fetch_assoc($result_t);
    
    $reg_title=$row_reg['title'];
    $reg_datalist=$row_reg['bond'];
    $reg_author=$row_reg['author'];
    $is_public=$row_reg['is_public'];
    
    //echo($format_list);
    //print_r($accept_formats);
    $result_check_finish=mysqli_query($conn,"SELECT * FROM $reg_datalist WHERE uid='$classid' limit 1");
    $row_check_finish=mysqli_fetch_assoc($result_check_finish);
    if($row_check_finish['status']=="1") $if_finish=1;
    else $if_finish=0;
    
    if($_GET['action']=="ok"){
        $time=date("Y-m-d H:i:s",time());
        mysqli_query($conn,"UPDATE $reg_datalist SET status='1',time='$time' WHERE uid='$classid'");
        exit(0);
    }

    
        
    $title=$reg_title."-提交区";
}else $title="参数错误";



?>



  <?php include 'head.php';?>
  <div id="content" role="main">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><a href="./mytask">我的任务</a></li>
						<li><?=$title?></li>&nbsp;&nbsp;<a class="bttn-material-flat bttn-xs bttn-primary text-center" href="./index">返回首页</a>
					</ul>
				</div>
  <!-- / aside --><br>
  <?php
    if($ishere==0){
        echo '<div class="col-sm-12" id="ljc_bg">
      <div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);color: white;">
              <h3 class="panel-title text-center">参数错误</h3>
            </div>
            <div style="background:#fff" class="panel-body text-center">
                <h1>参数错误</h1>
            </div>
        </div>
    </div>';
    exit(0);
    }
    $classid=$_SESSION['classid'];
    $check_query_if = mysqli_query($conn,"select * from $reg_datalist where uid='$classid' limit 1");
    if (!mysqli_fetch_array($check_query_if) || $is_public=="0") exit ('<div class="col-sm-12" id="ljc_bg">
                                                        <div class="panel panel-default">
                                    			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);color: white;">
                                                  <h3 class="panel-title text-center">没有权限填写</h3>
                                                </div>
                                                <div style="background:#fff" class="panel-body text-center">
                                                    <h1>你没有获得填写该表的权限</h1><br>
                                                    <a class="btn btn-info btn-block" href="./index">返回首页</a>
                                                </div>
                                                
                                            </div>
                                        </div>');
  ?>
  <div class="col-sm-12 col-md-12" id="ljc_bg">
      <div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);color: white;">
              <h3 class="panel-title text-center"><?=$reg_title?>-提交区</h3>
            </div>
            
            <div style="background:#fff" class="panel-body text-center">

                <center><h3>是否已经完成：<?=$reg_title?>？</h3>
                <h4>若完成请点击下方“<b>我已完成</b>”按钮！</h4></center><br>

                        
                        <div class="form-group">
                            <span style="font-size:20px;">姓名：<?=$row['name']?></span>
                        </div>
                        <div class="form-group">
                            <span style="font-size:20px;">完成状态：
                                <?php
                                    if($if_finish==1) echo '<a class="btn btn-success btn-xs">已完成</a>';
                                    else echo '<a class="btn btn-danger btn-xs">未完成</a>';
                                ?></span>
                        </div><br>
                <?php
                    if($if_finish==0) echo '<div class="form-group">
                                              <a class="btn btn-success btn-block" name="finish" id="finish" onclick="set()">我已完成</a>
                                            </div>';
                ?>    
            </div>
            <div class="panel-footer">
              <span class="glyphicon glyphicon-info-sign"></span>&nbsp;发布者：<?=$reg_author?>
        </div>
        </div>
    </div>
</div>
  	</div>
	<?php include './footer.php';?>

<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>
<script>
    function set(getid) {
        //var isOK=this.window.confirm("确定完成了？");
        //if(isOK){
            $.get('?action=ok&id=<?=$form_id?>');
            toastr.success("操作成功");
            setTimeout("location.reload()","1000");
            
        //}
    }
</script>

