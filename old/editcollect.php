<?php
//error_reporting(0);
session_start();
if(!isset($_SESSION['userid']) ){
    header('Location: login?refer='.$_SERVER["REQUEST_URI"]);
    exit();
}


include './config.php';
$uid=$_SESSION['userid'];

if($_SESSION['usergroup']!=1 && $_SESSION['usergroup']!=2){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回个人中心</a>';
    exit(0);
}

$ac_id=$_GET['id'];
$check_query = mysqli_query($conn,"select title from collect_list where id='$ac_id' limit 1");
if (!mysqli_fetch_array($check_query)) exit('参数错误');
//$result=mysql_query("SELECT * FROM user WHERE name=$name");
$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);



$result_t=mysqli_query($conn,"SELECT * FROM collect_list WHERE id=$ac_id");
$t_row=mysqli_fetch_assoc($result_t);

if($row['classid']!=$t_row['classid'] && $_SESSION['usergroup']!=1){
    echo '<font color="red">你没有权限访问该页面</font><a href="./index">返回个人中心</a>';
    exit(0);
}



if($_GET['action']=="post"){
    $title=$_POST['title'];
    $accept_format=$_POST['accept_format'];
    $file_name=$_POST['file_name'];
    $ifrename=$_POST['ifrename'];
    $sj_notice=$_POST['sj_notice'];
    
    
    $classid=$_SESSION['classid'];
    $name=$_SESSION['username'];
    
    if($accept_format!=""){
        $accept_formats=array();
        $accept_formats=explode(',',$accept_format);
        $accept_format_num=count($accept_formats)-1;
    }
    else $accept_format_num=0;
    
    
    mysqli_query($conn,"UPDATE collect_list SET title='$title',file_rename='$file_name',ifrename='$ifrename',file_format='$accept_format',notice='$sj_notice' WHERE id=$ac_id");
    
    
    if(mysqli_error($conn))
    {
        exit('{"msg":"no","datas":"'.mysqli_error($conn).'"}'); 
    }else{
        exit('{"msg":"success","datas":"'.$title.'"}');
    }

    
}

    $reg_accept=$t_row['file_format'];

    $accept_formats=array();
    $accept_formats=explode(',',$reg_accept);
    
    $isWord=0;
    $isXLS=0;
    $isPPT=0;
    $isPDF=0;
    $isPNG=0;
    $isZIP=0;
    
    for($i=0;$i<=count($accept_formats);$i++){
        if($accept_formats[$i]=="doc"){
            $isWord=1;
        }
        if($accept_formats[$i]=="xls"){
            $isXLS=1;
        }
        if($accept_formats[$i]=="ppt"){
            $isPPT=1;
        }
        if($accept_formats[$i]=="pdf"){
            $isPDF=1;
        }
        if($accept_formats[$i]=="png"){
            $isPNG=1;
        }
        if($accept_formats[$i]=="zip"){
            $isZIP=1;
        }
    }



$title="编辑收集表";

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
  <?php include ("left.php"); ?>
  <div id="content" class="app-content" role="main">
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><a href="./collect">文件收集表</a></li>
						<li><?=$title?></li>
					</ul>
				</div>
  <!-- / aside -->
            <div class="wrapper">
        <div class="col-sm-12" id="ljc_bg">
        <div class="panel panel-default">
        <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >编辑文件收集表</div>
        <div class="panel-body">
          <form name="RegForm" id="RegForm" action="#" method="post" role="form">
            <div class="form-group">
        	  <label>文件收集表标题：</label><br/>
        	  <input type="text" name="title" id="title" value="<?php echo $t_row['title'];?>" class="form-control"/>
        	</div><br>
        	<div class="form-group">
        	  <label>是否重命名文件：</label><br/>
        	    <input type="radio" value="yes" id="y_ifrename" name="ifrename" <?php if($t_row['ifrename']=="yes") echo 'checked';?>/>是&nbsp;&nbsp;
        	    <input type="radio" value="no" id="n_ifrename" name="ifrename" <?php if($t_row['ifrename']=="no") echo 'checked';?>/>否
        	</div><br>
        	<div class="form-group" id="rename" style="display:block">
        	  <label>文件命名格式：</label><br/>
        	  提供三种变量：{name}、{classid}、{id}，其中{name}表示姓名，{classid}表示学号，{id}表示号数。<br>
        	  例如：想要把文件名自动重命名成：<b>软工2201姓名-号数</b>，则需输入：<b>软工2201{name}-{id}</b><br>
        	  <font color="red">特别注意，{}（是花括号不是括号）和name、classid、id之间没有空格！</font>
        	  <input type="text" name="file_name" id="file_name" value="<?php echo $t_row['file_rename'];?>" class="form-control"/>
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
        	  <input type="checkbox" class="form-check-input" id="p_1" name="file_format" value="doc" title="Word文档" <?php if($isWord==1) echo 'checked';?>>
                  <label class="form-check-label" style="width: 100px;">Word文档</label>
              <input type="checkbox" class="form-check-input" id="p_2" name="file_format" value="ppt" title="PPT演示文稿" <?php if($isPPT==1) echo 'checked';?>>
                  <label class="form-check-label" style="width: 100px;">PPT演示文稿</label>
              <input type="checkbox" class="form-check-input" id="p_3" name="file_format" value="xls" title="Excel表格" <?php if($isXLS==1) echo 'checked';?>>
                  <label class="form-check-label" style="width: 100px;">Excel表格</label>
                  <input type="checkbox" class="form-check-input" id="p_4" name="file_format" value="pdf" title="PDF文件" <?php if($isPDF==1) echo 'checked';?>>
                  <label class="form-check-label" style="width: 100px;">PDF文件</label>
              <input type="checkbox" class="form-check-input" id="p_5" name="file_format" value="png" title="图片格式" <?php if($isPNG==1) echo 'checked';?>>
                  <label class="form-check-label" style="width: 100px;">图片文件</label>
              <input type="checkbox" class="form-check-input" id="p_6" name="file_format" value="zip" title="压缩文件" <?php if($isZIP==1) echo 'checked';?>>
                  <label class="form-check-label" style="width: 100px;">压缩文件</label>
        	</div><br>
        	<div class="form-group">
        	  <label>文件收集表公告：</label><br/>
        	  <font color="red">支持HTML代码&nbsp;<a href="https://www.runoob.com/html/html-tutorial.html" target="_blank">快速入门</a></font><br><a class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#myModal" onclick="preview()">预览</a><br>
        	  <textarea type="text" name="sj_notice" id="sj_notice" rows=10 class="form-control"/><?php echo $t_row['notice'];?></textarea>
        	</div>
        	<br>
        	<div class="form-group">
        	  <label>应完成人员：<b>（此项暂不可更改）</b></label><br/>
        	  <?php
        
        	    $result_dy=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
                $dy_num=mysqli_num_rows($result_dy);
                
                $people=$t_row['people'];
                $people_data=array();
                $people_data=explode(',',$people);
                
                $pr=1;
                for($i=1;$i<=$dy_num;$i++){
                    $ifchek=0;
                    $row_dy=mysqli_fetch_assoc($result_dy);
                    $name=$row_dy['name'];
                    if($pr==1){
                        echo '<div class="form-check">';
                    }
                    for($j=0;$j<=count($people_data)-1;$j++){
                        if($i==$people_data[$j]){
                            $ifchek=1;
                            echo('
                                <input type="checkbox" class="form-check-input" id="p_'.$i.'" name="ck_box" value="'.$i.'" title="'.$name.'"" checked="true" disabled>
                                <label class="form-check-label" style="width: 60px;">'.$name.'</label>&nbsp;&nbsp;
                            ');
                            break;
                        }
                    }
                    if($ifchek==0) echo('
                                <input type="checkbox" class="form-check-input" id="p_'.$i.'" name="ck_box" value="'.$i.'" title="'.$name.'"" disabled>
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
        	  <input type="submit" name="submit" value="确定修改" class="btn btn-primary form-control"/>
        	</div>	
          </form>
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
			

<script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
<script src="//cdn.staticfile.org/toastr.js/latest/toastr.min.js"></script>
<script src="./js/watermark.js"></script>

<script language="JavaScript">
    var show_num = [];
    $(function(){
        draw(show_num);
        $("#canvas").on('click',function(){
            draw(show_num);
        })
    })
    
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
                url:'?id=<?=$ac_id?>&action=post',
                data:{
                    title:title,
                    accept_format:file_gs,
                    file_name:file_name,
                    ifrename:check,
                    sj_notice:sj_notice
                },
                dataType:'json',
                success: function(res){
                    console.log(res)
                    alert('修改成功');
                    location.reload();
                }
            })
        })
    })
    
    function preview(){
        var html_code=$('#sj_notice').val()
        var pre_title=$('#title').val()
        $('#html_preview').html(html_code)
        $('#pre_title').text(pre_title)
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

