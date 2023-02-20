<?php

session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
$uid=$_SESSION['userid'];

if($_GET["data"]=="notice")
{
    $result_t=mysqli_query($conn,"SELECT * FROM notice ORDER BY id desc");
    $t_num=mysqli_num_rows($result_t);
    $output=[];
    while(($row_1=mysqli_fetch_assoc($result_t))!==null){
        $output[]=$row_1;
    }
    echo json_encode($output);
    exit(0);
}

if($_SESSION['usergroup']!=1 && $_SESSION['usergroup']!=2){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回个人中心</a>';
    exit(0);
}

//$result=mysql_query("SELECT * FROM user WHERE name=$name");
$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);
$result_t=mysqli_query($conn,"SELECT * FROM notice ORDER BY id asc");
$t_num=mysqli_num_rows($result_t);

if($_GET['action']=="post"){
    if($_SESSION['usergroup']!=1 && $_SESSION['usergroup']!=2){
        exit(0);
    }
    $title=$_POST['title'];
    $time=date("Y-m-d H:i:s",time());
    $author=$_SESSION['username'];
    mysqli_query($conn,"INSERT INTO notice(title,author,time) VALUES ('$title','$author','$time')");
    
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


$title="公告";


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
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >发布新公告</div>
<div class="panel-body">
  <form name="RegForm" id="RegForm" action="?action=add" method="post" role="form" onsubmit="return InputCheck(this)">

  <div class="form-group">
	  <label>公告内容</label><br/>
	  <textarea type="text" name="title" id="title" value="" class="form-control"/></textarea>
	</div>
	
	<br><p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
	<div class="form-group">
	    
	<input type="submit" name="submit" value="发布" class="btn btn-primary form-control"/>
	  
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
                    $('#wrong').text('请输入公告内容');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                
                $.ajax({
                    type:'post',
                    url:'?action=post',
                    data:{
                        title:title
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

    
</script>