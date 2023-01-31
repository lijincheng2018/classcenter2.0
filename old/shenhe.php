<?php

session_start();

if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}

if($_SESSION['usergroup']!=1 && $_SESSION['zhiwu']!="生劳委员"){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回个人中心</a>';
    exit(0);
}


include './config.php';
$uid=$_SESSION['userid'];
$author=$_SESSION['username'];

$bond_s=$_SESSION['bond'];


if($_GET['action']=="post")
{
    $id=$_POST['shid'];
    $content=$_POST['content'];
    $status=$_POST['status'];
    $time=date("Y-m-d H:i:s",time());
    $author=$_SESSION['username'];
    
    if($status=="1"){
        $sys_info=mysqli_query($conn,"SELECT * FROM system_info WHERE tag='classmoney'");
        $row_sys_info=mysqli_fetch_assoc($sys_info);
        $classmoney=$row_sys_info['content'];
        
        $result_1=mysqli_query($conn,"SELECT * FROM queue WHERE id='$id'");
        $row_1=mysqli_fetch_assoc($result_1);
        $fee=$row_1['fee'];
        $title=$row_1['title'];
        $method=$row_1['method'];
        $payment=$row_1['payment'];
        $author=$row_1['author'];
        $a_time=$row_1['time'];
        $classmoney=$classmoney-$fee;
        mysqli_query($conn,"INSERT INTO fee(title,fee,after_f,method,author,time) VALUES ('$title','$fee','$classmoney','$payment','$author','$a_time')");
        
        mysqli_query($conn,"UPDATE system_info SET content='$classmoney' WHERE tag='classmoney'");
    }
    mysqli_query($conn,"UPDATE queue SET method='$status',ps='$content',pf_time='$time',pf_author='$author' WHERE id=$id");
    
    if(mysqli_error($conn))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($conn).'"}'); 
    }else{
        exit('{"msg":"success","datas":"'.$id.'"}');
    }
    
}


if($_GET['action']=="query")
{
    $id=$_GET['id'];
    
    $result=mysqli_query($conn,"SELECT * FROM queue WHERE id='$id'");
    $row=mysqli_fetch_assoc($result);
    $classid=$row['classid'];
    $title=$row['title'];
    $yt=$row['yt'];
    $fee=$row['fee'];
    $method=$row['method'];
    $ps=$row['ps'];
    if($ps=="") $ps="暂无批复";
        
    if($method=="0") $method='待审核';
    else if($method=="1") $method='通过';
    else if($method=="2") $method='驳回';
    
        
    $photo1=$row['photo1'];
    $photo2=$row['photo2'];
        
    $time=$row['time'];
    $author=$row['author'];
        
        
    exit($title.'+ljc+'.$fee.'+ljc+'.$method.'+ljc+'.$ps.'+ljc+'.$photo1.'+ljc+'.$photo2.'+ljc+'.$author.'+ljc+'.$time.'+ljc+'.$yt);
}
//$result=mysql_query("SELECT * FROM user WHERE name=$name");
    $result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
    $row=mysqli_fetch_assoc($result);
    $result_t=mysqli_query($conn,"SELECT * FROM queue ORDER BY id desc");
    $t_num=mysqli_num_rows($result_t);
    
    $title="审核队列";

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
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">审核队列</div>
<div class="well well-sm" style="margin: 0;" id="ljc">若显示不全可左右滑动查看</div>
<div class="table-responsive">
        <table class="table table-striped b-t b-light text-center">
          <thead><th class="text-center">审核流水ID</th><th class="text-center">审核内容</th><th class="text-center">金额</th><th class="text-center">支出/收入</th><th class="text-center">状态</th><th class="text-center">操作</th><th class="text-center">申请时间</th></thead>
    <tbody>
    	<tr class="onclick warning"  >

<?php
$idd=0;
for($i=0;$i<$t_num;$i++){

                   $result_arr=mysqli_fetch_assoc($result_t);
                   $id=$result_arr['id'];
                   $title=$result_arr['title'];
                   $method=$result_arr['method'];
                   $payment=$result_arr['payment'];
                   $fee=$result_arr['fee'];
                   if($payment=="1") $payment='<font color="red">支出</font>'; else $payment='<font color="green">收入</font>';
                   
                   if($method=="0"){
                       $del='<a class="btn btn-info btn-xs" data-toggle="modal" data-target="#shenhe_panel" onclick="shenhe('.$id.')">处理审核</a>';
                       $method='<font color="blue">待审核</font>';
                   }else if($method=="1"){
                       $del='<a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal" onclick="ljcquery('.$id.')">详情</a>';
                       $method='<font color="green">通过</font>';
                   }else{
                       $del='<a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal"" onclick="ljcquery('.$id.')">详情</a>';
                       $method='<font color="red">驳回</font>';
                   }
        
                    $time=$result_arr['time'];
                    $author=$result_arr['author'];
                   echo "<tr>
                               <td>$id</td>
                               <td>$title</td>
                               <td>$fee</td>
                               <td>$payment</td>
                               <td>$method</td>
                               <td>$del</td>
                               <td>$time</td>
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
  
<div class="container-fluid text-center">
        <!-- 大图 -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            
			<h4 class="modal-title" id="myModalLabel">
              	详情
            </h4>
						</div>
						<div class="modal-body" style="text-align:left">
							报销名称：<span id="s_title"></span><br>
							用途：<span id="s_yt" style="color:red;"></span><br>
							报销金额：<span id="s_fee"></span><br>
							申请状态：<span id="s_status"></span><br>
							申请批复：<span id="s_ps"></span><br>
							报销凭证：<span id="s_photo1"><div style="text-align:center;margin 0 auto;weight:200px;justify-content: center;align-items: center;"><ljc_img></ljc_img></div></span><br>
							收款二维码：<span id="s_photo2"><div style="text-align:center;margin 0 auto;weight:200px;justify-content: center;align-items: center;"><ljc_img></ljc_img></div></span><br>
							申请人：<span id="s_author"></span><br>
							申请时间：<span id="s_time"></span><br>
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
			
<div class="container-fluid text-center">
        <!-- 大图 -->
			<div class="modal fade" id="shenhe_panel" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            
			<h4 class="modal-title" id="myModalLabel">
              	处理审核
            </h4>
						</div>
						<div class="modal-body" style="text-align:left">
							<form name="RegForm" id="RegForm" enctype="multipart/form-data" action="#" method="post" role="form">
							    <div class="form-group">
	                                <label>审核流水ID:</label><br/>
                                	  <input type="text" name="shid" id="shid" value="" class="form-control" readonly="readonly"/>
                                </div>
                                报销名称：<span id="sh_title"></span><br>
                                用途：<span id="sh_yt" style="color:red;"></span><br>
    							报销金额：<span id="sh_fee"></span>元<br>
    							报销凭证：<span id="sh_photo1"><div style="text-align:center;margin 0 auto;weight:200px;justify-content: center;align-items: center;"><sh_ljc_img></sh_ljc_img></div></span><br>
    							收款二维码：<span id="sh_photo2"><div style="text-align:center;margin 0 auto;weight:200px;justify-content: center;align-items: center;"><sh_ljc_img></sh_ljc_img></div></span><br>
    							申请人：<span id="sh_author"></span><br>
    							申请时间：<span id="sh_time"></span><br>
                                <div class="form-group">
                                	<label>批复内容:</label><br/>
                                	  <textarea type="text" name="sh_content" id="sh_content" class="form-control"/></textarea>
                                </div>
                                <div class="form-group">
                            	  <label>审核结果:</label><br/>
                            	  <select id="status" class="selectpicker show-tick form-control">
                            			<option value="1">通过</option>
                            			<option value="2">驳回</option>
                            	  </select>
                            	</div><br><p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
                            	<div class="form-group">
                        	        <input type="submit" name="submit" value="批复" class="btn btn-primary form-control"/>
                        	    </div>	
                                
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
            
            $(function () {
            $('#RegForm').on('submit', function (e) {  
                document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
                e.preventDefault();
                var shid=$('#shid').val();
                var content=$('#sh_content').val();
                var status=$('#status').val();
                
                
                if(content==""){
                    $('#wrong').text('请输入批复内容');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                
                $.ajax({
                    type:'post',
                    url:'?action=post',
                    data:{
                        shid:shid,
                        content:content,
                        status:status
                    },
                    dataType:'json',
                    success: function(res){
                        console.log(res)
                        toastr.success("操作成功");
                        location.reload();
                    }
                })
            })
        });
	function ljcquery(getid) {
        var box=document.getElementsByTagName("ljc_img")[0];
        box.innerHTML="";
        var box=document.getElementsByTagName("ljc_img")[1];
        box.innerHTML="";
        $.get('?action=query&id='+getid,function(data){
       			var strs= new Array(); 
                strs=data.split("+ljc+");
                
                if(strs[4]!=""){
                    var result='<img src="./public/file/'+strs[4]+'" style="max-width: 100%;max-height: 100%;">'
                    var div = document.createElement('div');
                    div.innerHTML = result;
                    document.getElementsByTagName('ljc_img')[0].appendChild(div);
                }else $('#s_photo1').text('暂无数据')
                if(strs[5]!=""){
                    var result='<img src="./public/file/'+strs[5]+'" style="max-width: 100%;max-height: 100%;">'
                    var div = document.createElement('div');
                    div.innerHTML = result;
                    document.getElementsByTagName('ljc_img')[1].appendChild(div);
                }else $('#s_photo2').text('暂无数据')
                console.log(data)
                $('#s_title').text(strs[0])
                $('#s_fee').text(strs[1])
                $('#s_status').text(strs[2])
                $('#s_ps').text(strs[3])
                $('#s_author').text(strs[6])
                $('#s_time').text(strs[7])
                $('#s_yt').text(strs[8])
       		});
    }
    function shenhe(getid) {
        var box=document.getElementsByTagName("sh_ljc_img")[0];
        box.innerHTML="";
        var box=document.getElementsByTagName("sh_ljc_img")[1];
        box.innerHTML="";
        $('#shid').val(getid);
        $.get('?action=query&id='+getid,function(data){
       			var strs= new Array(); 
                strs=data.split("+ljc+");
                
                if(strs[4]!=""){
                    var result='<img src="./public/file/'+strs[4]+'" style="max-width: 100%;max-height: 100%;">'
                    var div = document.createElement('div');
                    div.innerHTML = result;
                    document.getElementsByTagName('sh_ljc_img')[0].appendChild(div);
                }else $('#sh_photo1').text('暂无数据')
                if(strs[5]!=""){
                    var result='<img src="./public/file/'+strs[5]+'" style="max-width: 100%;max-height: 100%;">'
                    var div = document.createElement('div');
                    div.innerHTML = result;
                    document.getElementsByTagName('sh_ljc_img')[1].appendChild(div);
                }else $('#sh_photo2').text('暂无数据')
                console.log(data)
                $('#sh_title').text(strs[0])
                $('#sh_fee').text(strs[1])
                $('#sh_author').text(strs[6])
                $('#sh_time').text(strs[7])
                $('#sh_yt').text(strs[8])
       		});
    }
</script>

