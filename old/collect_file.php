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

$check_query = mysqli_query($conn,"select title from collect_list where id='$form_id' limit 1");
if (!mysqli_fetch_array($check_query)) $ishere=0;
else $ishere=1;

if($ishere==1){
    $result_t=mysqli_query($conn,"SELECT * FROM collect_list WHERE id=$form_id");
    $row_reg=mysqli_fetch_assoc($result_t);
    
    $reg_title=$row_reg['title'];
    $reg_datalist=$row_reg['bond'];
    $reg_notice=$row_reg['notice'];
    $reg_author=$row_reg['author'];
    $reg_accept=$row_reg['file_format'];
    $reg_accept_num=$row_reg['file_format_num'];
    $reg_file_name=$row_reg['file_rename'];
    $reg_ifrename=$row_reg['ifrename'];
    
    
    $accept_formats=array();
    $accept_formats=explode(',',$reg_accept);
    
    $format_list='';
    $post_check_list='';
    for($i=0;$i<$reg_accept_num;$i++){
        if($accept_formats[$i]=="doc"){
            $format_list=$format_list.' .doc, .docx,';
            $post_check_list=$post_check_list.'doc,docx,';
        }
        if($accept_formats[$i]=="xls"){
            $format_list=$format_list.' .xls, .xlsx,';
            $post_check_list=$post_check_list.'xls,xlsx,';
        }
        if($accept_formats[$i]=="ppt"){
            $format_list=$format_list.' .ppt, .pptx,';
            $post_check_list=$post_check_list.'ppt,pptx,';
        }
        if($accept_formats[$i]=="pdf"){
            $format_list=$format_list.' .pdf,';
            $post_check_list=$post_check_list.'pdf,';
        }
        if($accept_formats[$i]=="png"){
            $format_list=$format_list.' .png, .jpg, .jpeg,';
            $post_check_list=$post_check_list.'png,jpg,jpeg,';
        }
        if($accept_formats[$i]=="zip"){
            $format_list=$format_list.' .zip, .rar, .7z, .tar.gz,';
            $post_check_list=$post_check_list.'zip,rar,7z,gz,';
        }
    }
    //echo($format_list);
    //print_r($accept_formats);
    $result_check_finish=mysqli_query($conn,"SELECT * FROM $reg_datalist WHERE classid='$classid' limit 1");
    $row_check_finish=mysqli_fetch_assoc($result_check_finish);
    if($row_check_finish['pd']=="1") $if_finish=1;
    else $if_finish=0;

    
        
    
    $title=$reg_title."-提交区";
}else $title="参数错误";



?>



  <?php include 'head.php';?>
  <?php include ("left.php"); ?>
  <div id="content" class="app-content" role="main">
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><a href="./mytask">我的任务</a></li>
						<li><?=$title?></li>
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
    $check_query_if = mysqli_query($conn,"select * from $reg_datalist where classid='$classid' limit 1");
    if (!mysqli_fetch_array($check_query_if)) exit ('<div class="col-sm-12" id="ljc_bg">
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
  
  <?php
    if($_GET['action']=="upload")
    {
        echo'  <div class="col-sm-12" id="ljc_bg">
      <div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);color: white;">
              <h3 class="panel-title text-center">'.$reg_title.'-提交区</h3>
            </div>
            
            <div style="background:#fff" class="panel-body text-center">';
    
        $classid=$_SESSION['classid'];
        $check_get_uid = mysqli_query($conn,"select * from $reg_datalist where classid='$classid' limit 1");
        $row_get_uid=mysqli_fetch_assoc($check_get_uid);
        $classid=$row_get_uid['classid'];
        $c_upload_filename=$row_get_uid['upload_file'];
        $cid=$row_get_uid['id'];
        $cname=$row_get_uid['name'];
        
        
        
    	if($_FILES['file1']['name']=="") exit ('<div class="list-group-item list-group-item-info">你还没有选择文件！</div>
    						<div class="list-group-item">
    						<a href="javascript:history.go(-1);" class="btn btn-block btn-warning">返回重试</a>
    					</div></div>
    						</div>');
       
    	
            $extension1 = end(explode(".", $_FILES["file1"]["name"]));
            $check_ex=array();
            $check_ex=explode(',',$post_check_list);
            $isOK=0;
            
            for($i=0;$i<count($check_ex);$i++){
                if($extension1==$check_ex[$i]){
                    $isOK=1;
                    break;
                }
            }
            if($isOK==0)exit ('<div class="list-group-item list-group-item-info">你选择文件格式不符合要求！请重新选择！</div>
    						<div class="list-group-item">
    						<a href="javascript:history.go(-1);" class="btn btn-block btn-warning">返回重试</a>
    					</div></div>
    						</div>');
    						
    		if($_FILES["file1"]["size"]<51200000)
            {
            	if ($_FILES["file1"]["error"] > 0){
                    return $_FILES["file1"]["error"]; 
                }else{
                	$url1 = './public/collect_files/'.$reg_datalist.'/';
                    if(!is_dir($url1)) mkdir($url1,0755,true);
                    
                    if($c_upload_filename!=""){
                        if(file_exists($url1.$c_upload_filename)) unlink($url1.$c_upload_filename);
                    }
                    
                       
                    if($reg_ifrename=="yes"){
                        $whconf1 = $url1.$_FILES['file1']['name'];
                        $filename1 = basename($whconf1);
                        $extpos1 = strrpos($filename1,'.');
                        $ext1 = substr($filename1,$extpos1+1);
                        
                        $search_name="{name}";
                        $search_id="{id}";
                        $search_classid="{classid}";
                        
                        $confname1=$reg_file_name;
                        
                        $confname1=str_ireplace($search_name, $cname, $confname1);
                        $confname1=str_ireplace($search_id, $cid, $confname1);
                        $confname1=str_ireplace($search_classid, $classid, $confname1);
                        
                        $upload_file_name=$confname1.'.'. $ext1;
                        $path1 = move_uploaded_file($_FILES["file1"]["tmp_name"], $url1 . $confname1 .'.'. $ext1);
                    }
                    else{
                        $whconf1 = $url1.$_FILES['file1']['name'];
                        $path1 = move_uploaded_file($_FILES["file1"]["tmp_name"], $whconf1);
                        $upload_file_name=$_FILES['file1']['name'];
                    }
                    
            		if($path1==true){
                        $check_query_1 = mysqli_query($conn,"select * from $reg_datalist where id='$uid' limit 1");
                    	$row=mysqli_fetch_assoc($check_query_1);
                        
            			$now_time=time();
            			
            			mysqli_query($conn,"UPDATE $reg_datalist SET pd='1',time='$now_time',upload_file='$upload_file_name' WHERE id='$uid' ");
            			
            			exit ('<div class="list-group-item list-group-item-info">文件上传成功</div>
            						<div class="list-group-item">
            							<a href="javascript:history.back(-1);" class="btn btn-block btn-success">返回</a>
            						</div></div>
            				</div>');
            		}
            		else{
            			exit ('<div class="list-group-item list-group-item-info">文件上传失败</div>
            						<div class="list-group-item">
            							<a href="javascript:history.go(-1);" class="btn btn-block btn-warning">返回重试</a>
            						</div></div>
            				</div>');
                    }
                }
            }
            else exit ('<div class="list-group-item list-group-item-info">文件超过大小限制！最大支持上传50M以内的文件！</div>
            				<div class="list-group-item">
            					<a href="javascript:history.go(-1);" class="btn btn-block btn-warning">返回重试</a>
            				</div></div>
            			</div>');
    }
  ?>
  <div class="col-sm-12 col-md-9" id="ljc_bg">
      <div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);color: white;">
              <h3 class="panel-title text-center"><?=$reg_title?>-提交区</h3>
            </div>
            
            <div style="background:#fff" class="panel-body text-center">

                <center><h3>请上传<?=$reg_title?></h3>
                <h4>允许重复提交，重复提交版本将覆盖原始版本！</h4></center>
                    <?=$reg_notice?><br>
                    <form action="?id=<?=$form_id?>&action=upload" method="post" class="form-horizontal" id="RegForm" name="RegForm" role="form" enctype="multipart/form-data" onsubmit="return InputCheck(this)">
                        
                        <div class="form-group">
                            <div class="col-sm-3 control-label">姓名：</div>
                            
                            <div class="col-sm-8">
                                <input id="name" type="text" class="form-control" name="name" value="<?=$row['name']?>" class="input" placeholder="请输入姓名" readonly/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-3 control-label">上传文档：</div>
                                <div class="col-sm-8">

                                    <div class="input-group">
                                        <input id='location' class="form-control" onclick="$('#i-file1').click();" placeholder="请选择文档">
                                            <label class="input-group-btn">
                                                <input type="button" id="i-check1" value="浏览文件" class="btn btn-warning" onclick="$('#i-file1').click();">
                                            </label>
                                    </div>
                                </div>
                                <input type="file" name="file1" id='i-file1'  accept="<?=$format_list?>" onchange="$('#location').val($('#i-file1').val());" style="display: none">
                        </div>
                        <p style="text-align:center;color:red;">允许提交的文件后缀：<?php echo substr($format_list,0,strlen($format_list)-1);?></p>
                          <!--label for="theme">主题:</label>
                          <input id="theme" type="text" class="form-control" name="theme" class="input"/-->
                        
                      <br/>
                  <input type="submit" class="btn btn-primary btn-block" name="submit" onclick="upfile()" value="确认上传">
                  <?php if($if_finish==1) echo '<a class="btn btn-info btn-block" href="./download_collect?fid='.$form_id.'" onclick="down()">下载我提交的文件</a>';?>
                  
                    </form>

            </div>
            <div class="panel-footer">
              <span class="glyphicon glyphicon-info-sign"></span>&nbsp;发布者：<?=$reg_author?>
        </div>
        </div>
    </div>

	<div class="col-sm-12 col-md-3">
		<div class="panel panel-success text-center" id="recharge">
			<div class="panel-heading">
				<h2 class="panel-title">完成排行榜</h2>
			</div>
			<div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="white-space:nowrap">
                       <tbody>
                        <?php

                            $result=mysqli_query($conn,"SELECT * FROM $reg_datalist ORDER BY time asc");
                            $dataCount=mysqli_num_rows($result);

                            
                            $id=0;
                            $checkid=0;
                            for($i=0;$i<$dataCount;$i++){
                                       
                                $result_arr=mysqli_fetch_assoc($result);
                                $time=$result_arr['time'];
                                $name=$result_arr['name'];
                                if($time!=0)
                                {
                        
                                    $checkid++;
                                    $id=$checkid;
                                    if($checkid==1) $id='<img width="25px" height="25px" src="https://lijincheng2018.gitee.io/ljcimg/goldmedal.png">';
                                    if($checkid==2) $id='<img width="25px" height="25px" src="https://lijincheng2018.gitee.io/ljcimg/silvermedal.png">';
                                    if($checkid==3) $id='<img width="25px" height="25px" src="https://lijincheng2018.gitee.io/ljcimg/bronzemedal.png">';

                                    echo "<tr style=\"white-space:nowrap\">
                                            <td>$id</td>
                                            <td>$name</td>
                                          </tr>";
                                }
                            }
            
                            if($checkid==0){
                                echo '<tr style="white-space:nowrap">
                                            <td>暂无数据^O^</td>
                                          </tr>';
                            }
                                ?>
                       </tbody>
                    </table>
                    </div>
            </div>
		</div>
	</div>
</div>
  

<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>
<script>
    function down(){
        toastr.success('已开始下载！')
    }
    function upfile(){
        toastr.success('已经开始上传，请不要关闭页面，耐心等待上传完成！')
    }
</script>

