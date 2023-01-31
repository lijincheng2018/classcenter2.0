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

$result_t=mysqli_query($conn,"SELECT * FROM collect_list ORDER BY time_frame desc");
$t_num=mysqli_num_rows($result_t);

$result_dj=mysqli_query($conn,"SELECT * FROM register_list ORDER BY time_frame desc");
$dj_num=mysqli_num_rows($result_dj);

$result_user=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
$user_num=mysqli_num_rows($result_user);

$result_collect=mysqli_query($conn,"SELECT * FROM collect_list ORDER BY id desc");
$collect_num=mysqli_num_rows($result_collect);

$result_register=mysqli_query($conn,"SELECT * FROM register_list ORDER BY id desc");
$register_num=mysqli_num_rows($result_register);

$result_all_users=mysqli_query($connect_login,"SELECT * FROM all_users WHERE classid='$classid'");
$row_all_users=mysqli_fetch_assoc($result_all_users);

$result_ty=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
$ty_num=mysqli_num_rows($result_ty);
$ty=0;
for($i=0;$i<$ty_num;$i++)
{
    $result_arr=mysqli_fetch_assoc($result_ty);
    if($result_arr['zzmm']=="共青团员"){
        $ty++;
    }
}


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
<?php include 'head.php';?>
<div id="content" class="" role="main">
	<div class="bg-light lter b-b wrapper-sm ng-scope">
		<ul class="breadcrumb" style="padding: 0;margin: 0;">
			<li>
				<i class="fa fa-home"></i>
				<a href="./">班级信息中心</a>
			</li>
			<li>
				<?=$title?>
			</li>
		</ul>
	</div>
	<style>
		.msg-head{text-align: center;min-width: 360px;padding: 7px;background-color: #f9f9f9 !important;}
        .msg-body{padding: 15px;margin-bottom: 20px;}
</style>
	<div class="col-sm-12"></div>
	<div class="col-lg-4 col-md-6 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);padding: 15px;color: white;">
				<div class="widget-content text-right clearfix">
					<img src="./images/icon-user-center-avatar@2x.png" alt="Avatar"
						 width="66" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
					<h4>
						<font color="yellow">
							<?php echo $row['name'];?>
						</font>
					</h4>
					<h4>
						<a data-toggle="modal" class="btn btn-xs btn-info">
							<b>
								<?php echo $row['zhiwu'];?>
							</b>
						</a>&nbsp;<a data-toggle="modal" class="btn btn-xs btn-danger">
							<b>
								<?php 
					        if($_SESSION['usergroup']=="1") echo '系统管理员';
					        if($_SESSION['usergroup']=="2") echo '管理员';
					        if($_SESSION['usergroup']=="3") echo '普通用户';
					     ?>
							</b>
						</a>
					</h4>
				</div>
			</div>
			<div style="background:#fff" class="panel-body text-center ">
				<div class="col-lg-6 col-md-6 col-sm-12 clearfix" style="padding: 5px 5px 5px 5px;">
					<a href="./say" class="bttn-material-flat bttn-md bttn-primary btn-block text-center">
						<b>我对班委有话说</b>
					</a>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 clearfix" style="padding: 5px 5px 5px 5px;">
					<a href="./random" class="bttn-material-flat bttn-md bttn-warning btn-block text-center">
						<b>随机抽号</b>
					</a>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 clearfix" style="padding: 5px 5px 5px 5px;">
					<a href="./set" class="bttn-material-flat bttn-md bttn-danger btn-block text-center">
						<b>账号设置</b>
					</a>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title">
					<font color="#fff">
						<i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>公告</b>
					</font>
					<?php
                        if($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2"){
                            echo '&nbsp;&nbsp;<a href="./notice" class="bttn-material-flat bttn-xs bttn-success text-center"><b>发布公告</b></a>';
                        }
                    ?>
				</h3>
			</div>
			<table class="table table-responsive table-striped b-t b-light text-center">
				<thead>
					<tr style="white-space:nowrap;">
						<th class="text-center" width="60%">公告内容</th>
						<th class="text-center" width="20%">发布者</th>
						<th class="text-center" width="20%">发布时间</th>
					</tr>
				</thead>
				<tbody id="page_table">
					<tr></tr>
				</tbody>
			</table>
			<br>
			<div class="text-center" id="page_bar"></div>
			<br>
		</div>
		<?php
        if($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2"){
    		echo '<div class="panel panel-default"><div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);"><h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>收到的留言</b></font></h3></div><div style="height:220px;overflow: auto;"><table class="table table-responsive table-striped b-t b-light text-center"><thead><tr style="white-space:nowrap;"><th class="text-center" width="20%">留言主题</th><th class="text-center" width="50%">留言内容</th><th class="text-center" width="10%">留言人</th><th class="text-center" width="20%">留言时间</th></tr></thead><tbody>';
    				    $result_say=mysqli_query($conn,"SELECT * FROM say ORDER BY id desc");
                        $say_num=mysqli_num_rows($result_say);
                        $sum_say=0;
    					for($i=0;$i<$say_num;$i++){
                           $result_arr=mysqli_fetch_assoc($result_say);
                           $banwei=$result_arr['banwei'];
                           if($banwei=="1") $banwei="班长";
                           if($banwei=="2") $banwei="团支书";
                           if($banwei=="3") $banwei="副班长";
                           if($banwei=="4") $banwei="学习委员";
                           if($banwei=="5") $banwei="组织委员";
                           if($banwei=="6") $banwei="文体委员";
                           if($banwei=="7") $banwei="生劳委员";
                           
                           if($banwei==$_SESSION['zhiwu']){
                               $sum_say++;
                               $shiming=$result_arr['shiming'];
                               $title=$result_arr['title'];
                               $content=$result_arr['content'];
                               $time=$result_arr['time'];
                               
                               if($shiming=="1") $author=$result_arr['author'];
                               if($shiming=="2") $author="匿名";
                               echo "<tr><td>$title</td><td>$content</td><td>$author</td><td>$time</td></tr>";
                           
            	        }
                           }
    					
    					
    					echo '</tr></tbody></table>';
    					if($sum_say==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';
    					echo '</div></div>';
        }
    	?>
		
	</div>
	<div class="col-lg-8 col-md-6 col-sm-12">
		<?php
        if($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2"){
            echo '<div class="col-lg-12 col-md-6 col-sm-12"><div class="panel panel-default"><div class="panel-heading font-bold"  style="background: linear-gradient(to right,#14b7ff,#b221ff);"><h3 class="panel-title"><font color="#fff"><i class="fa fa-server"></i>&nbsp;&nbsp;<b>班委工作台&nbsp;权限：'.$row['zhiwu'];if($_SESSION['usergroup']=="1"){
                              echo('&nbsp;&nbsp;<a href="./qx" class="bttn-material-flat bttn-xs bttn-royal text-center">系统账号管理</a>');
                          }
            echo'
    			</b></font></h3></div><div style="background:#fff" class="panel-body text-center "><div class="col-lg-8 col-md-6 col-sm-12 clearfix" style="padding: 0px;"><div class="col-lg-6 col-md-6 col-sm-12 clearfix" style="padding: 0px 5px 0px 5px;"><a href="./register" class="btn btn-success btn-block"><i class="fa fa-archive"></i>&nbsp;<b>登记表</b></a><div style="height:220px;overflow: auto;"><table class="table table-responsive table-striped b-t b-light text-center"><thead><tr style="white-space:nowrap;"><th class="text-center" width="50%">登记表标题</th><th class="text-center" width="50%">完成进度</th></tr></thead><tbody>';
    			                            $sum_register=0;
                                            for($i=0;$i<$register_num;$i++){
                                                   $result_arr=mysqli_fetch_assoc($result_register);
                                                   $classid=$result_arr['classid'];
                                                   if($classid==$_SESSION['classid'] || $_SESSION['usergroup']==1)
                                                   {
                                                       $sum_register++;
                                                       $title=$result_arr['title'];
                                                       $content=$result_arr['content'];
                                                       $people_num=$result_arr['people_num'];
                                                       $reg_datalist=$result_arr['bond'];
                                                       
                                                        $count_cnt=mysqli_query($conn,"SELECT uid FROM $reg_datalist where status='1'");
                                                        $count_cnt_num=mysqli_num_rows($count_cnt);
                                                        
                                                        $wanchenglu=(number_format($count_cnt_num/$people_num,4))*100;
                                                           echo '<tr><td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td><td><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="70"
                                                                              aria-valuemin="0" aria-valuemax="100" style="width:'.$wanchenglu.'%">
                                                                                '.$wanchenglu.'%
                                                                              </div></div></td></tr>';
                                                                   
                                                   }
                                            }
                                            echo'
                                    	    
                                    	</tbody></table>';
                                    	if($sum_register==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';
                                    	echo '</div></div><div class="col-lg-6 col-md-6 col-sm-12 clearfix" style="padding: 0px 5px 0px 5px;"><td><a href="./collect" class="btn btn-danger btn-block"><i class="fa fa-cubes"></i>&nbsp;<b>收集表</b></a></td><div style="height:220px;overflow: auto;"><table class="table table-responsive table-striped b-t b-light text-center"><thead><tr style="white-space:nowrap;"><th class="text-center" width="50%">收集表标题</th><th class="text-center" width="50%">完成进度</th></tr></thead><tbody>';
                                    	    $sum_collect=0;
                                            for($i=0;$i<$collect_num;$i++){
                                                   $result_arr=mysqli_fetch_assoc($result_collect);
                                                   $classid=$result_arr['classid'];
                                                   if($classid==$_SESSION['classid'] || $_SESSION['usergroup']==1)
                                                   {
                                                       $sum_collect++;
                                                       $title=$result_arr['title'];
                                                       $content=$result_arr['content'];
                                                       $people_num=$result_arr['people_num'];
                                                       $reg_datalist=$result_arr['bond'];
                                                       
                                                        $count_cnt=mysqli_query($conn,"SELECT classid FROM $reg_datalist where pd='1'");
                                                        $count_cnt_num=mysqli_num_rows($count_cnt);
                                                        
                                                        $wanchenglu=(number_format($count_cnt_num/$people_num,4))*100;
                                                           echo '<tr><td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td><td><div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="70"
                                                                              aria-valuemin="0" aria-valuemax="100" style="width:'.$wanchenglu.'%">
                                                                                '.$wanchenglu.'%
                                                                              </div></div></td></tr>';
                                                                   
                                                   }
                                            }
                                        	echo '
                                    	    
                                    	</tbody></table>';
                                    	if($sum_collect==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';
                                    	echo '</div></div></div><div class="col-lg-4 col-md-6 col-sm-12 clearfix" style="padding: 0px 5px 0px 5px;"><a href="./ty" class="btn btn-info btn-block"><i class="glyphicon glyphicon-stats"></i>&nbsp;<b>团员列表</b></a><ul class="list-group no-radius"><li class="list-group-item"><font size="4"><b>团员数量：'.$ty.'人</b></font></li></ul>';
              
                          if($_SESSION['usergroup']=="1" || $_SESSION['zhiwu']=="生劳委员"){
                              echo('
                            <a href="./shenhe" class="btn btn-primary btn-block"><i class="glyphicon glyphicon-chevron-right" aria-hidden="true"></i>&nbsp;<b>报销审核</b></a>');
                            $result_duilie=mysqli_query($conn,"SELECT id FROM queue where method='1'");
                            $duilie_num=mysqli_num_rows($result_duilie);
                            $result_duilie_all=mysqli_query($conn,"SELECT * FROM queue ORDER BY id desc");
                            $duilie_all_num=mysqli_num_rows($result_duilie_all);
                            echo '<ul class="list-group no-radius"><li class="list-group-item"><font size="4"><b>记录总数：'.$duilie_all_num.'</b></font></li><li class="list-group-item"><font size="4"><b>待审核记录：'.$duilie_num.'</b></font></li></ul>';
                          }
                          
                          else echo '<center><img height="50px" width="auto" src="./images/'.$_SESSION['logo_url'].'"></center>';
                          echo '
                    </div></div></div></div>';
            
        }
    ?>
		<div class="col-lg-6 col-md-6 col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);">
					<h3 class="panel-title">
						<font color="#fff">
							<i class="fa fa-globe"></i>&nbsp;&nbsp;<b>我的</b>
						</font>
					</h3>
				</div>
    				<table class="table">
    					<tbody>
        						<tr>
        							<td width="50%">
        								<a href="./mytask" class="btn btn-success btn-block">
        									<b>我的任务</b>
        								</a>
        							</td>
        							<td width="50%">
        								<a href="./personal" class="btn btn-danger btn-block">
        									<b>个人荣誉</b>
        								</a>
        							</td>
        						</tr>
        						<tr>
        							<td width="50%">
        								<a href="./document" class="btn btn-info btn-block">
        									<b>履历记录</b>
        								</a>
        							</td>
        							<td width="50%">
        								<a href="./baoxiao" class="btn btn-primary btn-block">
        									<b>申请报销</b>
        								</a>
        							</td>
        						</tr>
    					</tbody>
    				</table>
				<ul class="list-group no-radius">
					<li class="list-group-item">
						<b>待完成的任务：</b>
					</li>
				</ul>
				<div style="height:220px;overflow: auto;">
					<table class="table table-responsive table-striped b-t b-light text-center">
						<thead>
							<tr style="white-space:nowrap;">
								<th class="text-center" width="55%">任务名称</th>
								<th class="text-center" width="30%">截止时间</th>
								<th class="text-center" width="15%">完成状态</th>
							</tr>
						</thead>
						<tbody>
					<?php
                        $a_id=0;
                        for($i=0;$i<$t_num;$i++){
                               $result_arr=mysqli_fetch_assoc($result_t);
                                
                                $title=$result_arr['title'];
                                $fid=$result_arr['id'];
                                $time=$result_arr['time'];
                                $author=$result_arr['author'];
                                $jiezhi_time=$result_arr['time_frame'];
                                   
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
                                            if($pd=="0"){
                                                $a_id++;
                                                $mc="<b><font color=\"red\">未完成</font></b>";
                                            
                                                echo '<tr><td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td><td>'.$jiezhi_time.'</td><td>'.$mc.'</td></tr>';
                                            }
                                            break;
                                        }
                                    }
                               }
                               
                              for($i=0;$i<$dj_num;$i++){
                                $result_arr=mysqli_fetch_assoc($result_dj);
                                
                                $title=$result_arr['title'];
                                $is_public=$result_arr['is_public'];
                                $author=$result_arr['author'];
                                $jiezhi_time=$result_arr['time_frame'];
                                
                                   
                                    $people_num=$result_arr['people_num'];
                                    $people=$result_arr['people'];
                                    $peoples=array();
                                    $peoples=explode(',',$people);
                                    for($j=0;$j<$people_num;$j++)
                                    {
                                        if($peoples[$j]==$uid && $is_public=="1"){
                                            
                                            $reg_datalist=$result_arr['bond'];
                                            $result_get_name=mysqli_query($conn,"SELECT * FROM $reg_datalist WHERE id='$uid'");
                                            $row_get_name=mysqli_fetch_assoc($result_get_name);
                                            $pd=$row_get_name['status'];
                                            if($pd=="0"){
                                                $a_id++;
                                                $mc="<b><font color=\"red\">未完成</font></b>";
                                            
                                                echo '<tr><td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td><td>'.$jiezhi_time.'</td><td>'.$mc.'</td></tr>';
                                            }
                                            break;
                                        }
                                    }
                                  
                               }
                               
                               
                           
                           ?>
						</tbody>
					</table>
					<?php
					if($a_id==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';
					?>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);">
					<h3 class="panel-title">
						<font color="#fff">
							<i class="fa fa-users"></i>&nbsp;&nbsp;<b>班级</b>
						</font>
					</h3>
				</div>
				<table class="table">
					<tbody>
						<tr>
							<td width="33%">
								<a href="./class" class="btn btn-success btn-block">
									<b>集体荣誉</b>
								</a>
							</td>
							<td width="33%">
								<a href="./fee" class="btn btn-danger btn-block">
									<b>班费明细</b>
								</a>
							</td>
							<td width="33%">
								<a href="./list" class="btn btn-info btn-block">
									<b>通讯录</b>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
				<ul class="list-group no-radius">
					<li class="list-group-item">
						<b>通讯录：</b>
					</li>
				</ul>
				<div style="height:270px;overflow: auto;">
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
                                       echo "<tr><td>$name</td><td><a href=\"tel:$tel\">$tel</a></td><td>$del</td></tr>";
                                   
                    	        }
                    	    ?>
						</tbody>
					</table>
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
							详细资料
						</h4>
					</div>
					<div class="modal-body" style="text-align:left">
						姓名：<span id="s_name"></span>
						<br>
						性别：<span id="s_sex"></span>
						<br>
						学号：<span id="s_classid"></span>
						<br>
						号数：<span id="s_uid"></span>
						<br>
						入学年份：<span id="s_year"></span>
						<br>
						学院：<span id="s_xueyuan"></span>
						<br>
						班级：<span id="s_class"></span>
						<br>
						电话：<span id="s_tel"></span>
						<br>
						职务：<span id="s_zhiwu"></span>
						<br>
						宿舍：<span id="s_sushe"></span>
						<br>
						政治面貌：<span id="s_zzmm"></span>
						<br>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">关闭
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<div class="container-fluid text-center">
	<div class="modal fade" id="myModal_change" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
						<label>原密码：</label>
						<br />
						<input type="text" name="or_pass" id="or_pass" value="123456" placeholder="请输入原密码" class="form-control" readonly />
					</div>
					<div class="form-group">
						<label>请输入新密码：</label>
						<br />
						<input type="password" name="new_passwd" id="new_passwd" value="" placeholder="请输入新密码" class="form-control" />
					</div>
					<div class="form-group">
						<label>再次输入新密码：</label>
						<br />
						<input type="password" name="new_passwd_again" id="new_passwd_again" value="" placeholder="再次输入新密码" class="form-control" />
					</div>
					<p style="color:red;">密码至少8个字符,必须包含字母、数字！</p>
					<input type="submit" id="confirm_change" name="confirm_change" onclick="change()" value="确认修改" class="btn btn-success form-control" />
				</div>
				<br>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">暂不修改
					</button>
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php include './footer.php';?>
	<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
	<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
	<script src="./js/echarts.min.js"></script>
	<script type="text/javascript">
		$("img").mousedown(function(){return false;});
				    function toLogin(){window.location.href="./oauth/qq_login.php";}
				    $(function ()  
				    {
				        if(<?=$nopass?>)
				        $("#myModal_change").modal('show');  
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