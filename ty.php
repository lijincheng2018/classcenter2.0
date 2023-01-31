<?php

session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
$uid=$_SESSION['userid'];


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

//$result=mysql_query("SELECT * FROM user WHERE name=$name");
    $result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
    $row=mysqli_fetch_assoc($result);
    $result_user_1=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
    $user_num_1=mysqli_num_rows($result_user_1);
    $result_user=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
    $user_num=mysqli_num_rows($result_user);
    $ty=0;
    for($i=0;$i<$user_num_1;$i++)
    {
        $result_arr=mysqli_fetch_assoc($result_user_1);
        if($result_arr['zzmm']=="共青团员"){
            $ty++;
        }
    }
    
    
    $title="团员列表";
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
<div class="col-sm-12" id='ljc_bg'>	
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">团员列表</div>
<div class="well well-sm" style="margin: 0;" id="ljc">共<b><?=$ty;?></b>位团员</div>
<div class="table-responsive">
        <table class="table table-striped b-t b-light text-center">
          <thead><th class="text-center">号数</th><th class="text-center">学号</th><th class="text-center">姓名</th><th class="text-center">性别</th><th class="text-center">电话</th><th class="text-center">职务</th><th class="text-center">操作</th></thead>
    <tbody>
    	<tr class="onclick warning">

<?php
$id=0;
for($i=0;$i<$user_num;$i++){
    
                   $id++;

                   $result_arr=mysqli_fetch_assoc($result_user);
                   if($result_arr['zzmm']=="共青团员"){
                       $name=$result_arr['name'];
                       $tel=$result_arr['tel'];
                       $classid=$result_arr['classid'];
                       $sex=$result_arr['sex'];
                       $zhiwu=$result_arr['zhiwu'];
                           
                        $del='<a class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal" onclick="ljcquery('.$id.')">详细资料</a>';
                        echo "<tr>
                               <td>$id</td>
                               <td>$classid</td>
                               <td>$name</td>
                               <td>$sex</td>
                               <td><a href=\"tel:$tel\">$tel</a></td>
                               <td>$zhiwu</td>
                               <td>$del</td>
                            </tr>";
                   }
                   
                   
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


<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>


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
    
    $("img").mousedown(function(){
        return false;
    });
</script>

