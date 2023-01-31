<?php
//error_reporting(0);
session_start();
if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
include './login_config.php';
$uid=$_SESSION['userid'];

if($_SESSION['usergroup']!=1 && $_SESSION['usergroup']!=2){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回个人中心</a>';
    exit(0);
}


//$result=mysql_query("SELECT * FROM user WHERE name=$name");
$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);
$result_t_1=mysqli_query($conn,"SELECT * FROM collect_list ORDER BY id desc");
$result_t=mysqli_query($conn,"SELECT * FROM collect_list ORDER BY id desc");
$t_num=mysqli_num_rows($result_t);

$list=0;

for($i=0;$i<$t_num;$i++)
{
    $result_arr=mysqli_fetch_assoc($result_t_1);
    if($result_arr['classid']==$_SESSION['classid'] || $_SESSION['usergroup']==1){
        $list++;
    }
}


if($_GET['action']=="post"){
    $title=$_POST['title'];
    $accept_format=$_POST['accept_format'];
    $file_name=$_POST['file_name'];
    $ifrename=$_POST['ifrename'];
    $people=$_POST['people'];
    $sj_notice=$_POST['sj_notice'];
    $time_frame=$_POST['time_frame'];
    
    
    $classid=$_SESSION['classid'];
    $name=$_SESSION['username'];
    
    if($people!=""){
        $peoples=array();
        $peoples=explode(',',$people);
        $people_num=count($peoples)-1;
    }
    else $people_num=0;
    
    if($accept_format!=""){
        $accept_formats=array();
        $accept_formats=explode(',',$accept_format);
        $accept_format_num=count($accept_formats)-1;
    }
    else $accept_format_num=0;
    
    $s1=mt_rand(19,717);
    $s2=time();
    $s3="ljc";
    $s=$s1.$s2.$s3;
    $str = md5($s);
    $ljcid = substr($str,12,14);
    
    $data_list_name='sj_'.$ljcid;
    
    
    $dir='./public/collect_files/'.$data_list_name;
    mkdir($dir,0755,true);
    
    $time=date("Y-m-d H:i:s",time());
    
    $data_base_name=$_SESSION['data_base'];
    
    mysqli_query($conn,"INSERT INTO collect_list(title,people,people_num,file_rename,ifrename,file_format,file_format_num,author,classid,bond,time,notice,time_frame) VALUES ('$title','$people','$people_num','$file_name','$ifrename','$accept_format','$accept_format_num','$name','$classid','$data_list_name','$time','$sj_notice','$time_frame')");
    
    $sql_create_list="CREATE TABLE `$data_base_name`.`$data_list_name` ( `id` INT(100) NOT NULL AUTO_INCREMENT , `classid` CHAR(50) NOT NULL , `name` CHAR(50) NOT NULL , `pd` CHAR(10) NOT NULL , `time` CHAR(30) NOT NULL , `upload_file` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    
    mysqli_query($conn,$sql_create_list);
    
    $tmp=array();
    $tmp=explode(',',$people);
    
    for($i=0;$i<$people_num;$i++){
        $head=$tmp[$i];
        $result_dy=mysqli_query($conn,"SELECT * FROM user WHERE uid=$head");
        $row_dy=mysqli_fetch_assoc($result_dy);
        $now_name=$row_dy['name'];
        $now_classid=$row_dy['classid'];
        mysqli_query($conn,"INSERT INTO $data_list_name(id,classid,name,pd,time) VALUES ($head,'$now_classid','$now_name','0','')");
    }
    
    
    
    if(mysqli_error($conn))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($conn).'"}'); 
    }else{
        exit('{"msg":"success","datas":"'.$title.'"}');
    }

    
}

function delDir(string $path): bool
{
   if (!is_dir($path)) {
       return false;
   }
   $content = scandir($path);
   foreach ($content as $v) {
       if ('.' == $v || '..' == $v) {
           continue;
       }
       $item = $path . '/' . $v;
       if (is_file($item)) {
           unlink($item);
           continue;
       }
       delDir($item);
   }
   return rmdir($path);
}

if($_GET['action']=="del"){
    $id=$_GET['id'];
    if($id!=""){
        $result_list=mysqli_query($conn,"SELECT * FROM collect_list WHERE id=$id");
        $row_list=mysqli_fetch_assoc($result_list);
        $list_name=$row_list['bond'];
        mysqli_query($conn,"DROP TABLE $list_name");
        mysqli_query($conn,"DELETE FROM collect_list WHERE id = $id");
        delDir('./public/collect_files/'.$list_name);
        exit('success');
    }
    else exit('error');
}




$title="文件收集表";

?>



  <?php include 'head.php';?>
    <style>
        canvas {
            vertical-align: middle;
            width: 100px;
            height: 34px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            cursor: pointer;
        }
    </style>
  <div id="content" role="main">
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><?=$title?></li>&nbsp;&nbsp;<a class="bttn-material-flat bttn-xs bttn-primary text-center" href="./index">返回首页</a>
					</ul>
				</div>
  <!-- / aside -->
  <ul id="myTab" class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#list" role="tab" data-toggle="tab">已创建的文件收集表</a></li>
    <li><a href="#sq" role="tab" data-toggle="tab">创建新的文件收集表</a></li>
</ul>
  
  <div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="list">
      <div class="wrapper" id="activity_list" style="display:block">
<div class="col-sm-12" id="ljc_bg">	
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">文件收集表列表</div>
<div class="well well-sm" style="margin: 0;">共创建了<b><?=$list?></b>张文件收集表</div>
<div class="table-responsive">
    <div style="height:540px;overflow: auto;">
        <table class="table table-responsive b-t b-light text-center">
          <thead><th class="text-center">收集表ID</th><th class="text-center" width="30%">收集表标题</th><th class="text-center">应完成人数</th><th class="text-center">已完成人数</th><th class="text-center">创建人</th><th class="text-center">创建时间</th><th class="text-center">操作</th></thead>
          <tbody>
	<tr class="onclick warning"  >

<?php
    $a_id=0;
    for($i=0;$i<$t_num;$i++){
           $result_arr=mysqli_fetch_assoc($result_t);
           $classid=$result_arr['classid'];
           if($classid==$_SESSION['classid'] || $_SESSION['usergroup']==1)
           {
               $a_id++;
               $title=$result_arr['title'];
               $id=$result_arr['id'];
               $time=$result_arr['time'];
               $people_num=$result_arr['people_num'];
               $author=$result_arr['author'];
               $reg_datalist=$result_arr['bond'];
               
               
               $control='<a class="btn btn-primary btn-xs" href="./showcollect?id='.$id.'">查看数据</a>&nbsp;<a class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal1" onclick="share_url('.$id.')">分享链接</a>&nbsp;<a class="btn btn-info btn-xs" href="./editcollect?id='.$id.'">编辑</a>&nbsp;<a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal" onclick="check_del('.$id.')">删除</a>';
                $count_cnt=mysqli_query($conn,"SELECT classid FROM $reg_datalist where pd='1'");
                $count_cnt_num=mysqli_num_rows($count_cnt);
                $time=$result_arr['time'];
                   echo '<tr>
                               <td>'.$id.'</td>
                               <td style="word-break:break-all; word-wrap:break-word; white-space:inherit">'.$title.'</td>
                               <td>'.$people_num.'</td>
                               <td>'.$count_cnt_num.'</td>
                               <td>'.$author.'</td>
                               <td>'.$time.'</td>
                               <td>'.$control.'</td>
                         </tr>';
                           
           }
    }
           
           
       
       ?>


          </tbody>
        </table><?php if($a_id==0) echo '<center><img src="./images/nodata_img_200px.png" width="150px" height="150px"></center>';?>
		      </div></div>
</div>
</div>
</div>
    
    
    </div>
    <div class="tab-pane fade" id="sq">
            <div class="wrapper">
        <div class="col-sm-12">
        <div class="panel panel-default">
        <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >新增文件收集表</div>
        <div class="panel-body">
          <form name="RegForm" id="RegForm" action="#" method="post" role="form">
            <div class="form-group">
        	  <label>文件收集表标题：</label><br/>
        	  <input type="text" name="title" id="title" value="" class="form-control"/>
        	</div><br>
        	<div class="form-group">
        	  <label>是否重命名文件：</label><br/>
        	    <input type="radio" value="yes" id="y_ifrename" name="ifrename" checked/>是&nbsp;&nbsp;
        	    <input type="radio" value="no" id="n_ifrename" name="ifrename"/>否
        	</div><br>
        	<div class="form-group" id="rename" style="display:block">
        	  <label>文件命名格式：</label><br/>
        	  提供三种变量：{name}、{classid}、{id}，其中{name}表示姓名，{classid}表示学号，{id}表示号数。<br>
        	  例如：想要把文件名自动重命名成：<b>软工2201姓名-号数</b>，则需输入：<b>软工2201{name}-{id}</b><br>
        	  <font color="red">特别注意，{}（是花括号不是括号）和name、classid、id之间没有空格！</font>
        	  <input type="text" name="file_name" id="file_name" value="" class="form-control"/>
        	</div><br>
        	<div class="form-group">
        	  <label>允许提交的文件后缀：</label><br/>
        	  ·Word文档：.doc,.docx<br>
        	  ·PPT演示文稿：.ppt,.pptx<br>
        	  ·Excel表格：.xls,.xlsx<br>
        	  ·PDF文件：.pdf<br>
        	  ·图片文件：.png,.jpg,.jpeg<br>
        	  ·压缩文件：.zip,.rar,.7z,.tar.gz<br>
        	  <font color="red">暂时仅支持以上文件格式</font><br>
        	  <input type="checkbox" class="form-check-input" id="p_1" name="file_format" value="doc" title="Word文档">
                  <label class="form-check-label" style="width: 100px;">Word文档</label>
              <input type="checkbox" class="form-check-input" id="p_2" name="file_format" value="ppt" title="PPT演示文稿">
                  <label class="form-check-label" style="width: 100px;">PPT演示文稿</label>
              <input type="checkbox" class="form-check-input" id="p_3" name="file_format" value="xls" title="Excel表格">
                  <label class="form-check-label" style="width: 100px;">Excel表格</label>
                  <input type="checkbox" class="form-check-input" id="p_4" name="file_format" value="pdf" title="PDF文件">
                  <label class="form-check-label" style="width: 100px;">PDF文件</label>
              <input type="checkbox" class="form-check-input" id="p_5" name="file_format" value="png" title="图片格式">
                  <label class="form-check-label" style="width: 100px;">图片文件</label>
              <input type="checkbox" class="form-check-input" id="p_6" name="file_format" value="zip" title="压缩文件">
                  <label class="form-check-label" style="width: 100px;">压缩文件</label>
        	</div><br>
        	<div class="form-group">
        	  <label>文件收集表公告：</label><br/>
        	  <font color="red">支持HTML代码&nbsp;<a href="https://www.runoob.com/html/html-tutorial.html" target="_blank">快速入门</a></font><br><a class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#myModal_preview" onclick="preview()">预览</a>&nbsp;<a class="btn btn-danger btn-xs" href="javascript:;" onclick="template()">使用模板</a><br>
        	  <textarea type="text" name="sj_notice" id="sj_notice" rows=10 class="form-control"/></textarea>
        	</div>
        	<br>
        	<div class="form-group">
        	  <label>截止日期：</label><br/>
              <input id="time_frame" name="time_frame" class="form-control" type="text" value="" placeholder="请点击输入框设置日期" readonly>
        	</div><br>
        	<div class="form-group">
        	  <label>应完成人员：&nbsp;&nbsp;<input type="button" class="btn btn-danger btn-xs" value="全选" class="btn" id="selectAll">&nbsp;<input type="button" class="btn btn-success btn-xs" value="全不选" class="btn" id="unSelect">&nbsp;<input type="button" class="btn btn-info btn-xs" value="反选" class="btn" id="reverse"></label><br/>
        	  <?php
        
        	    $result_dy=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
                $dy_num=mysqli_num_rows($result_dy);
                
                $pr=1;
                for($i=1;$i<=$dy_num;$i++){
                    
                    $row_1=mysqli_fetch_assoc($result_dy);
                    $name=$row_1['name'];
                    if($pr==1){
                        echo '<div class="form-check">';
                    }
                    echo('
                  <input type="checkbox" class="form-check-input" id="p_'.$i.'" name="ck_box" value="'.$i.'" title="'.$name.'"">
                  <label class="form-check-label" style="width: 60px;">'.$name.'</label>&nbsp;&nbsp;
                ');
                    if($pr==10 || $i==$dy_num) {
                        echo '</div>';
                    $pr=1;
                    }else $pr++;
                    
                }
                
        	    ?>
        	  <br>
        	  <div class="form-group">
                <label>验证码:</label><br/>
        		<input type="text" id="yz_code" name="yz_code" value="" class="form-control" placeholder="请输入验证码（不区分大小写）">
                <canvas id="canvas"></canvas>
        	  </div>
        	  
        	  <br><p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
        	<div class="form-group">
        	  <input type="submit" name="submit" value="创建" class="btn btn-primary form-control"/>
        	</div>	
          </form>
          </div>
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
              	确定要删除吗？
            </h4>
						</div>
						<div class="modal-body" style="text-align:left">
						    <div class="form-group">
							    <label>文件收集表ID：</label><br/>
                        	    <input type="text" name="check_id" id="check_id" value="" class="form-control" readonly/>
                        	</div>
                        	<div class="form-group">
							    <label>请手动输入“<font color="red"><b>确认删除</b></font>”</label><br/>
                        	    <input type="text" name="check_title" id="check_title" value="" placeholder="请输入确认删除" class="form-control"/>
                        	</div>
                        	<input type="submit" id="confirm_del" name="confirm_del" onclick="confirm()" value="确认删除" class="btn btn-danger form-control"/>
						</div>
						<br>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">关闭
            </button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal -->
			</div>
		</div>
			
<div class="container-fluid text-center">
        <!-- 大图 -->
			<div class="modal fade" id="myModal1" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            
			<h4 class="modal-title" id="myModalLabel">
              	分享链接
            </h4>
						</div>
						<div class="modal-body" style="text-align:left">
						    <div class="form-group">
							    <label>文件收集表ID：</label><br/>
                        	    <input type="text" name="check_id_url" id="check_id_url" value="" class="form-control" readonly/>
                        	</div>
                        	<li style="font-weight:bold" class="list-group-item">链接：<a href="" target="_blank" id="copy_p"></a>&nbsp;&nbsp;<button class="btn btn-xs btn-info" id="copy">复制链接</button>&nbsp;<a href="" class="btn btn-xs btn-primary" target="_blank" id="copy_btn">打开链接</a></li>
						</div>
						<br>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">关闭
            </button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal -->
			</div>
		</div>
			
		<div class="container-fluid text-center">
        <!-- 大图 -->
			<div class="modal fade" id="myModal_preview" tabindex="-3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            
			<h4 class="modal-title" id="myModalLabel">
              	代码预览
            </h4>
						</div>
						<div class="modal-body" style="text-align:left">
						    <div style="background:#fff" class="panel-body text-center">

                                <center><h3>请上传<span id="pre_title"></span></h3>
                                <h4>允许重复提交，重复提交版本将覆盖原始版本！</h4></center>
                                    <div id="html_preview"></div><br>
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
                                                        <input class="form-control" placeholder="请选择文档" readonly>
                                                            <label class="input-group-btn">
                                                                <input type="button" value="浏览文件" class="btn btn-warning" disabled>
                                                            </label>
                                                    </div>
                                                </div>
                                                <input type="file" accept="" onchange="" style="display: none">
                                        </div>
                                        
                                      <br/><br/>
                                  <input type="submit" class="btn btn-primary btn-block" name="submit" value="确认上传" disabled>
                
                            </div>
                            
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
		</div>
		<?php include('./footer.php');?>
			

<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>
<script src="./laydate/laydate.js"></script>

<script language="JavaScript">
    var show_num = [];
    $(function(){
        draw(show_num);
        $("#canvas").on('click',function(){
            draw(show_num);
        })
    })
    laydate.render({
      elem: '#time_frame'
    });
    
    $('#y_ifrename').change(function(){
        var check=$('input:radio[name="ifrename"]:checked').val();
        if(check=="yes"){
            $("#rename").css('display','block');
        }
    });
    $('#n_ifrename').change(function(){
        var check=$('input:radio[name="ifrename"]:checked').val();
        if(check=="no"){
            $("#rename").css('display','none');
        }
    });
    function check_del(getid){
        $("#check_id").val(getid)
    }
    
    function confirm(){
        var list_id=$('#check_id').val();
        var check_title=$('#check_title').val();
        if(check_title!="确认删除"){
            toastr.error("输入错误，请重新输入!");
            $('#check_title').focus();
            return false;
        }
        else{
            $.get('?action=del&id='+list_id);
            alert('删除成功');
            location.reload();
        }
        
    }
    function share_url(getid){
        $('#check_id_url').val(getid);
        $('#copy_p').text('https://class.ljcljc.cn/collect_file?id='+getid);
        $('#copy_p').attr('href','https://class.ljcljc.cn/collect_file?id='+getid);
        $('#copy_btn').attr('href','https://class.ljcljc.cn/collect_file?id='+getid);
        
    }
    

    $(function () {
        $('#RegForm').on('submit', function (e) {  
            document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
            e.preventDefault();
            var title=$('#title').val();
            var check=$('input:radio[name="ifrename"]:checked').val();
            var file_name=$('#file_name').val();
            var sj_notice=$('#sj_notice').val();
            var time_frame=$('#time_frame').val();
            var yz_code=$('#yz_code').val().toLowerCase();
            let str = '';
            var obj = document.getElementsByName('ck_box');
            for (var i = 0; i < obj.length; i++) {
                if (obj[i].checked){
                        str += obj[i].value + ",";
                }
            }
            
            let file_gs = '';
            var obj_file = document.getElementsByName('file_format');
            for (var i = 0; i < obj_file.length; i++) {
                if (obj_file[i].checked){
                        file_gs += obj_file[i].value + ",";
                }
            }
            
            
            if(title==""){
                toastr.error("请输入收集标题!");
                $('#title').focus();
                return false;
            }
            if(check=="yes"){
                if(file_name==""){
                    toastr.error("请输入文件命名格式!");
                    $('#file_name').focus();
                    return false;
                }
            }
            else file_name="";
            
            if(file_gs==""){
                toastr.error("请选择允许提交的文件格式!");
                return false;
            }
            if(time_frame==""){
                toastr.error("请选择截止日期!");
                return false;
            }
            if(str==""){
                toastr.error("请选择需要完成的人!");
                return false;
            }
            if(yz_code==""){
                toastr.error("请输入验证码!");
                $('#yz_code').focus();
                return false;
            }
            else if(yz_code != show_num.join("")){
                toastr.error("验证码错误!");
                $('#yz_code').focus();
                return false;
            }
            
            
            
            $.ajax({
                type:'post',
                url:'?action=post',
                data:{
                    title:title,
                    people:str,
                    accept_format:file_gs,
                    file_name:file_name,
                    ifrename:check,
                    sj_notice:sj_notice,
                    time_frame:time_frame
                },
                dataType:'json',
                success: function(res){
                    console.log(res)
                    alert('创建成功');
                    location.reload();
                }
            })
        })
    })
    
    $("#selectAll").click(function () { 
	    $('#RegForm input:checkbox[name="ck_box"]').each(function () {   
		    $(this).prop('checked', true);//
	    }); 
	});
	$("#unSelect").click(function () {   
		$('#RegForm input:checkbox[name="ck_box"]').removeAttr("checked");  
    });
    $("#reverse").click(function () {  
        $('#RegForm input:checkbox[name="ck_box"]').each(function () {   
        	this.checked = !this.checked;  
        }); 
    });
    function copyArticle(event){
        const range = document.createRange();
        range.selectNode(document.getElementById('copy_p'));

        const selection = window.getSelection();
        if(selection.rangeCount > 0) selection.removeAllRanges();
        selection.addRange(range);

        document.execCommand('copy');
        alert('复制成功');
    }       
    document.getElementById('copy').addEventListener('click', copyArticle, false);
    
    function preview(){
        var html_code=$('#sj_notice').val()
        var pre_title=$('#title').val()
        $('#html_preview').html(html_code)
        $('#pre_title').text(pre_title)
    }
    function template(){
        var template_code='<h3><font color="red">请于<b>2022年X月X日 24:00前</b>上交实验报告！</font></h3>';
        $('#html_preview').html(template_code)
        $('#sj_notice').val(template_code)
        $("#myModal_preview").modal('show'); 
    }
</script>
<script>

function draw(show_num) {
    var canvas_width=$('#canvas').width();
    var canvas_height=$('#canvas').height();
    var canvas = document.getElementById("canvas");
    var context = canvas.getContext("2d");
    canvas.width = canvas_width;
    canvas.height = canvas_height;
    var sCode = "a,b,c,d,e,f,g,h,i,j,k,m,n,p,q,r,s,t,u,v,w,x,y,z,A,B,C,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,W,X,Y,Z,1,2,3,4,5,6,7,8,9,0";
    var aCode = sCode.split(",");
    var aLength = aCode.length;
    for (var i = 0; i < 4; i++) {
        var j = Math.floor(Math.random() * aLength);
        var deg = Math.random() - 0.5;
        var txt = aCode[j];
        show_num[i] = txt.toLowerCase();
        var x = 10 + i * 20;
        var y = 20 + Math.random() * 8;
        context.font = "bold 23px 微软雅黑";
        context.translate(x, y);
        context.rotate(deg);
        context.fillStyle = randomColor();
        context.fillText(txt, 0, 0);
        context.rotate(-deg);
        context.translate(-x, -y);
    }
    for (var i = 0; i <= 5; i++) {
        context.strokeStyle = randomColor();
        context.beginPath();
        context.moveTo(Math.random() * canvas_width, Math.random() * canvas_height);
        context.lineTo(Math.random() * canvas_width, Math.random() * canvas_height);
        context.stroke();
    }
    for (var i = 0; i <= 30; i++) {
        context.strokeStyle = randomColor();
        context.beginPath();
        var x = Math.random() * canvas_width;
        var y = Math.random() * canvas_height;
        context.moveTo(x, y);
        context.lineTo(x + 1, y + 1);
        context.stroke();
    }
}

function randomColor() {
    var r = Math.floor(Math.random() * 256);
    var g = Math.floor(Math.random() * 256);
    var b = Math.floor(Math.random() * 256);
    return "rgb(" + r + "," + g + "," + b + ")";
}

</script>

