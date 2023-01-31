<?php
//error_reporting(0);
session_start();
if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
$uid=$_SESSION['userid'];

$form_id=$_GET['id'];

$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);

if($_SESSION['usergroup']!="1" && $_SESSION['usergroup']!="2"){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回首页</a>';
    exit(0);
}


$result_t=mysqli_query($conn,"SELECT * FROM collect_list WHERE id=$form_id");
$row_reg=mysqli_fetch_assoc($result_t);

if($row['classid']!=$row_reg['classid'] && $_SESSION['usergroup']!=1){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回个人中心</a>';
    exit(0);
}

$reg_title=$row_reg['title'];
$reg_datalist=$row_reg['bond'];

$result_1=mysqli_query($conn,"SELECT * FROM $reg_datalist ORDER BY id asc");
$dataCount_1=mysqli_num_rows($result_1);
    
$result_11=mysqli_query($conn,"SELECT * FROM $reg_datalist ORDER BY id asc");


if($_GET['action']=="print"){
    if($_SESSION['usergroup']!="1" && $_SESSION['usergroup']!="2"){
        echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回首页</a>';
        exit(0);
    }
   include_once("xlsxwriter.class.php");
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);
    $rand_num=mt_rand(10002,99999);
    
    $tmp=$reg_title.'完成数据'.date("YmdHis",time()).$rand_num.'.xlsx';
    $filename = 'public/download/'.$tmp;
    
    $rows = array(
        array('序号','姓名','学号','完成状态','记录时间'),
    );
    
    $writer = new XLSXWriter();
    $writer->setAuthor('李锦成'); 
    $id=0;
    $result_get=mysqli_query($conn,"SELECT * FROM $reg_datalist ORDER BY id asc");
    for($i=1;$i<=$dataCount_1;$i++){
        $result_arr=mysqli_fetch_assoc($result_get);
        $id++;
        $uid=$result_arr['classid'];
        $list_name=$result_arr['name'];
        $status=$result_arr['pd'];
        $time=$result_arr['time'];
        
    
        
        if($status=="1") $status='已完成';
        else if($status=="0") $status='未完成';
        
        
        $tmp_data=array();
        
        $tmp_data[0]=$id;
        $tmp_data[1]=$list_name;
        $tmp_data[2]=$uid;
        $tmp_data[3]=$status;
        $tmp_data[4]=date("Y-m-d H:i:s",$time);
        
        $rows[$i]=$tmp_data;
        
    }
    
    
    foreach($rows as $row)
        $writer->writeSheetRow($reg_title, $row);
    
    $writer->writeToFile($filename);
    exit($filename.'+ljc+'.$tmp);
}

    
$id=0;
$tot_1=0;
    
for($i=0;$i<$dataCount_1;$i++){
               
    $row_2=mysqli_fetch_array($result_1,MYSQLI_ASSOC);
    
    $ifOK=$row_2['pd'];
    if($ifOK=="1"){
        $tot_1++;
    }
}
    
$weijiao=$dataCount_1-$tot_1;
    

$title=$reg_title."-完成数据";

?>



<?php include 'head.php';?>
<div id="content" role="main">
	<div class="bg-light lter b-b wrapper-sm ng-scope">
		<ul class="breadcrumb" style="padding: 0;margin: 0;">
			<li>
				<i class="fa fa-home"></i>
				<a href="./">班级信息中心</a>
			</li>
			<li>
				<a href="./collect">文件收集表</a>
			</li>
			<li>
				<?=$title?>
			</li>&nbsp;&nbsp;<a class="bttn-material-flat bttn-xs bttn-primary text-center" href="./index">返回首页</a>
		</ul>
	</div>
	<!-- / aside -->
	<div class="col-sm-12" id="ljc_bg">
		<div class="panel panel-success">
			<div class="panel-heading font-bold" style="background-color: #7CCD7C;color: white;">
				<?=$reg_title?>完成总体情况</div>
			<div class="well well-sm" style="margin: 0;">班级：<b>软件工程2201</b>&nbsp;&nbsp;
				<?php echo('总人数：<b>'.($dataCount_1).'</b>&nbsp;&nbsp;已完成：<b>'.($tot_1).'</b>'); ?>
			</div>
			<div class="table-responsive">

				<div id="datasss" style="width: 90%;height:400px;margin:0 auto;"></div>
			</div>

			<div class="table-responsive">
				<div style="display:inline;float:left;">统计时间：
					<?php echo(date("Y-m-d H:i:s",time()));?>&nbsp;&nbsp;&nbsp;<a data-toggle="modal" class="btn btn-xs btn-primary" href="javascript:location.reload();">
						<b>重新统计</b>
					</a>
				</div>
				<div style="display:inline;float:right;">
					<a>
						<font color="#0066f">班级信息中心</font>
					</a>
				</div>
			</div>

		</div>

		<div class="panel panel-success">
			<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">软件工程2201具体情况</div>
			<ul id="myTab" class="nav nav-tabs" style="background-color: #efefef;" role="tablist">
				<li class="active">
					<a href="#all" role="tab" data-toggle="tab">全部</a>
				</li>
				<li>
					<a href="#weiwancheng" role="tab" data-toggle="tab">未完成</a>
				</li>
			</ul>


			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade in active" id="all">
					<div class="well well-sm" style="margin: 0;">
						<?php echo('总人数：'.$dataCount_1.'&nbsp;&nbsp;已完成人数：'.($tot_1)); ?>&nbsp;&nbsp;<a class="btn btn-xs btn-danger" href="javascript:;" onclick="createfile()">导出为Excel数据</a>
						<br>
						<br>
						<a href="./get.php?id=all&fid=<?=$form_id?>" class="btn btn-success" onclick="down()">一键打包并下载</a>
						<br>
						由于总文件较大，云端压缩可能比较<b>慢</b>，点击按钮后，请<b>稍等片刻</b>，未弹出下载框前，<b>不要重复点击按钮</b>！
					</div>
					<div class="table-responsive">
						<table class="table table-bordered  table-striped" style="white-space:nowrap;text-align:center;">
							<thead>
								<tr class="success" style="white-space:nowrap">
									<th class="text-center">号数</th>
									<th class="text-center">姓名</th>
									<th class="text-center">是否完成</th>
									<th class="text-center">完成时间</th>
									<th class="text-center">操作</th>
								</tr>
							</thead>
							<tbody>

								<?php


$result=mysqli_query($conn,"SELECT * FROM $reg_datalist ORDER BY id asc");
//获取数据表的数据条数
$dataCount=mysqli_num_rows($result);

for($i=0;$i<$dataCount;$i++){
           
	$result_arr=mysqli_fetch_assoc($result);

		$ok='<img src="./images/no.png" width="16px" height="16px">';
		
    	
    	$name=$result_arr['name'];
    	$cid=$result_arr['id'];
		$whether=$result_arr['pd'];
        $src_zip="javascript:toastr.error('未提交');";
        $times="未提交";
	  	if($whether==1)
	  	{
	  	    $ok='<img src="./images/yes.png" width="16px" height="16px">';
	  	    $src_zip='./get.php?id='.$cid.'&fid='.$form_id;
	  	    $times=$result_arr['time'];
	  	    $times=date('Y-m-d H:i:s',$times);
	  	}
	  	
	  	 echo "<tr style=\"white-space:nowrap\">
				<td>$cid</td>
				<td>$name</td>
		  		<td>$ok</td>
		  		<td>$times</td>";
		  		if($whether==1) echo "<td><a href=\"$src_zip\" class=\"btn btn-xs btn-primary\">下载</a></td>";
		  		else echo "<td><a href=\"$src_zip\" class=\"btn btn-xs btn-primary\" disabled=\"true\">下载</a></td>";
  		echo "</tr>";
	  	

                
}

?>
							</tbody>

						</table>
					</div>
				</div>

				<div class="tab-pane fade" id="weiwancheng">
					<div class="well well-sm" style="margin: 0;">
						<?php echo('总人数：'.$dataCount_1.'&nbsp;&nbsp;未完成人数：'.($dataCount_1-$tot_1)); ?>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered  table-striped" style="white-space:nowrap;text-align:center;">
							<thead>
								<tr class="success" style="white-space:nowrap">
									<th class="text-center">号数</th>
									<th class="text-center">姓名</th>
									<th class="text-center">状态</th>
								</tr>
							</thead>
							<tbody>

								<?php
    
    
    $result_weiwancheng=mysqli_query($conn,"SELECT * FROM $reg_datalist where pd='0'");
    //获取数据表的数据条数
    $dataCount_weiwancheng=mysqli_num_rows($result_weiwancheng);
    
    for($i=0;$i<$dataCount_weiwancheng;$i++){
               
    	$result_arr_weiwancheng=mysqli_fetch_assoc($result_weiwancheng);
    
    		
        	$name=$result_arr_weiwancheng['name'];
        	$cid=$result_arr_weiwancheng['id'];
    	  	$ok='<b><font color="red">未完成</font></b>';
    	  	
    	  	 echo "<tr style=\"white-space:nowrap\">
    				<td>$cid</td>
    				<td>$name</td>
    		  		<td>$ok</td>";
      		echo "</tr>";
    	  	
    
                    
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
</div>
</div>
<?php include './footer.php';?>


<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>
<script src="./js/echarts.min.js"></script>

<script language="JavaScript">
	function createfile(){
	         $.get('?action=print&id=<?=$form_id?>',function(data){
	                var strs= new Array(); 
	                strs=data.split("+ljc+");
	       			if(data!="") downloadEvt("https://class.ljcljc.cn/"+strs[0],strs[1]);
				 
	       		});
	    }
	    
	    function downloadEvt(url, fileName) {
	      const el = document.createElement('a');
	      el.style.display = 'none';
	      el.setAttribute('target', '_blank');
	      fileName && el.setAttribute('download', fileName);
	      el.href = url;
	      console.log(el);
	      document.body.appendChild(el);
	      el.click();
	      document.body.removeChild(el);
	    }
	    function down(){
	        toastr.success('已开始下载！')
	    }
</script>

<script type="text/javascript">
	var DySl = echarts.init(document.getElementById('datasss'));
	 
	    var DySl_option = {
	      title: {
	        text: '<?=$reg_title?>',
	        left: 'center',
	        top: 'center'
	      },
	      series: [
	        {
	          type: 'pie',
	          data: [
	            {
	              value: <?=$tot_1?>,
	              name: '已完成<?=$tot_1?>人'
	            },
	            {
	              value: <?=$weijiao?>,
	              name: '未完成<?=$weijiao?>人'
	            },
	          ],
	          radius: ['40%', '70%']
	        }
	      ],
	      tooltip: {
	        show: true,
	        trigger: 'item'
	      }
	    };
	    
	    DySl.setOption(DySl_option);
	    
	 
	 function set(getid) {
	        var isOK=this.window.confirm("确定完成了？");
	        if(isOK){
	            $.get('?action=ok&id=<?=$form_id?>&pid='+getid);
	            alert('操作成功');
	            location.reload();
	        }
	    }
	function cancel(getid) {
	        var isOK=this.window.confirm("确定点错了？");
	        if(isOK){
	            $.get('?action=no&id=<?=$form_id?>&pid='+getid);
	            alert('操作成功');
	            location.reload();
	        }
	    }
</script>