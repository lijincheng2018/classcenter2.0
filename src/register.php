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

//$result=mysql_query("SELECT * FROM user WHERE name=$name");
$result=mysqli_query($conn,"SELECT * FROM user WHERE uid='$uid'");
$row=mysqli_fetch_assoc($result);
$result_t_1=mysqli_query($conn,"SELECT * FROM register_list ORDER BY id desc");
$result_t=mysqli_query($conn,"SELECT * FROM register_list ORDER BY id desc");
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
    $people=$_POST['people'];
    $classid=$_SESSION['classid'];
    $name=$_SESSION['username'];
    $check=$_POST['check'];
    $time_frame=$_POST['time_frame'];
    
    
    if($check=="yes") $is_public=1;
    else $is_public=0;
    
    if($people!=""){
        $peoples=array();
        $peoples=explode(',',$people);
        $people_num=count($peoples)-1;
    }
    else $people_num=0;
    
    $s1=mt_rand(19,717);
    $s2=time();
    $s3="ljc";
    $s=$s1.$s2.$s3;
    $str = md5($s);
    $ljcid = substr($str,12,8);
    
    $data_list_name='dj_'.$ljcid;
    
    $time=date("Y-m-d H:i:s",time());
    
    $data_base_name=$_SESSION['data_base'];
    
    mysqli_query($conn,"INSERT INTO register_list(title,people,people_num,author,classid,bond,time,is_public,time_frame) VALUES ('$title','$people','$people_num','$name','$classid','$data_list_name','$time','$is_public','$time_frame')");
    
    $sql_create_list="CREATE TABLE `$data_base_name`.`$data_list_name` ( `id` INT(100) NOT NULL AUTO_INCREMENT , `uid` CHAR(50) NOT NULL , `name` CHAR(50) NOT NULL , `status` CHAR(10) NOT NULL , `time` CHAR(30) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    
    mysqli_query($conn,$sql_create_list);
    
    $tmp=array();
    $tmp=explode(',',$people);
    
    for($i=0;$i<$people_num;$i++){
        $head=$tmp[$i];
        $result_dy=mysqli_query($conn,"SELECT * FROM user WHERE uid=$head");
        $row_dy=mysqli_fetch_assoc($result_dy);
        $now_name=$row_dy['name'];
        $now_classid=$row_dy['classid'];
        mysqli_query($conn,"INSERT INTO $data_list_name(id,uid,name,status,time) VALUES ($head,'$now_classid','$now_name','0','')");
    }
    
    
    
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
        $result_list=mysqli_query($conn,"SELECT * FROM register_list WHERE id=$id");
        $row_list=mysqli_fetch_assoc($result_list);
        $list_name=$row_list['bond'];
        mysqli_query($conn,"DROP TABLE $list_name");
        mysqli_query($conn,"DELETE FROM register_list WHERE id = $id");
        exit('success');
    }
    else exit('error');
}




$title="登记表";

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
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">班级信息中心</a></li>
						<li><?=$title?></li>&nbsp;&nbsp;<a class="bttn-material-flat bttn-xs bttn-primary text-center" href="./index">返回首页</a>
					</ul>
				</div>
  <!-- / aside -->
  <ul id="myTab" class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#list" role="tab" data-toggle="tab">已创建的登记表</a></li>
    <li><a href="#sq" role="tab" data-toggle="tab">创建新的登记表</a></li>
</ul>
  
  <div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="list">
      <div class="wrapper" id="activity_list" style="display:block">
<div class="col-sm-12" id="ljc_bg">	
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">登记表列表</div>
<div class="well well-sm" style="margin: 0;">共创建了<b><?=$list?></b>张登记表</div>
<div class="table-responsive">
    <div style="height:540px;overflow: auto;">
        <table class="table table-responsive b-t b-light text-center table-striped" style="white-space:nowrap;text-align:center;">
          <thead><th class="text-center">登记表ID</th><th class="text-center" width="30%">登记表标题</th><th class="text-center">应完成人数</th><th class="text-center">已完成人数</th><th class="text-center">创建人</th><th class="text-center">创建时间</th><th class="text-center">操作</th></thead>
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
               $content=$result_arr['content'];
               $people_num=$result_arr['people_num'];
               $author=$result_arr['author'];
               $is_public=$result_arr['is_public'];
               $reg_datalist=$result_arr['bond'];
               
               if($is_public=="0") $control='<a class="btn btn-primary btn-xs" href="./showdata?id='.$id.'">查看数据</a>&nbsp;<a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal" onclick="check_del('.$id.')">删除</a>';
               else $control='<a class="btn btn-primary btn-xs" href="./showdata?id='.$id.'">查看数据</a>&nbsp;<a class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal1" onclick="share_url('.$id.')">分享链接</a>&nbsp;<a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal" onclick="check_del('.$id.')">删除</a>';
                
                $count_cnt=mysqli_query($conn,"SELECT uid FROM $reg_datalist where status='1'");
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
        <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >新增登记表</div>
        <div class="panel-body">
          <form name="RegForm" id="RegForm" action="#" method="post" role="form">
            <div class="form-group">
        	  <label>登记表标题：</label><br/>
        	  <input type="text" name="title" id="title" value="" class="form-control"/>
        	</div><br>
        	<div class="form-group">
        	  <label>是否允许用户自行完成填报：</label><br/>
        	    <input type="radio" value="yes" id="y_ifallow" name="ifallow"/>是&nbsp;&nbsp;
        	    <input type="radio" value="no" id="n_ifallow" name="ifallow" checked/>否
        	</div><br>
        	<div class="form-group" id="if_allow_date" style="display:none">
        	  <label>截止日期：</label><br/>
              <input id="time_frame" name="time_frame" class="form-control" type="text" value="" placeholder="请点击输入框设置日期" readonly>
        	</div><br>
        	<!--div class="form-group">
        	  <label>是否需要成员自行登记：</label><br/>
        	    <input type="radio" value="yes" id="y_ifpost" name="ifpost" checked/>是&nbsp;&nbsp;
        	    <input type="radio" value="no" id="n_ifpost" name="ifpost"/>否
        	</div-->
        	<div class="form-group">
        	  <label>应完成人员：&nbsp;&nbsp;<input type="button" class="btn btn-danger btn-xs" value="全选" class="btn" id="selectAll">&nbsp;<input type="button" class="btn btn-success btn-xs" value="全不选" class="btn" id="unSelect">&nbsp;<input type="button" class="btn btn-info btn-xs" value="反选" class="btn" id="reverse"></label><br/>
        	  <?php
        
        	    $result_dy=mysqli_query($conn,"SELECT * FROM user ORDER BY uid asc");
                $dy_num=mysqli_num_rows($result_dy);
                
                $pr=1;
                for($i=1;$i<=$dy_num;$i++){
                    
                    $row=mysqli_fetch_assoc($result_dy);
                    $name=$row['name'];
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
							    <label>登记表ID：</label><br/>
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
			</div></div></div>
			<?php include './footer.php';?>
  

<script src="./laydate/laydate.js"></script>
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
    laydate.render({
      elem: '#time_frame'
    });
    
    $('#y_ifallow').change(function(){
        var check=$('input:radio[name="ifallow"]:checked').val();
        if(check=="yes"){
            $("#if_allow_date").css('display','block');
        }
    });
    $('#n_ifallow').change(function(){
        var check=$('input:radio[name="ifallow"]:checked').val();
        if(check=="no"){
            $("#if_allow_date").css('display','none');
        }
    });
    
    function share_url(getid){
        $('#check_id_url').val(getid);
        $('#copy_p').text('https://class.ljcljc.cn/finish_register?id='+getid);
        $('#copy_p').attr('href','https://class.ljcljc.cn/finish_register?id='+getid);
        $('#copy_btn').attr('href','https://class.ljcljc.cn/finish_register?id='+getid);
        
    }
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

    $(function () {
        $('#RegForm').on('submit', function (e) {  
            document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
            e.preventDefault();
            var title=$('#title').val();
            var time_frame=$('#time_frame').val();
            var check=$('input:radio[name="ifallow"]:checked').val();
            var yz_code=$('#yz_code').val().toLowerCase();
            
            if(title==""){
                toastr.error("请输入登记表标题!");
                $('#title').focus();
                return false;
            }
            if(check=="yes" && time_frame==""){
                toastr.error("请输入截止时间");
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
            
            let str = '';
            var obj = document.getElementsByName('ck_box');
            for (var i = 0; i < obj.length; i++) {
                if (obj[i].checked){
                        str += obj[i].value + ",";
                }
            }
            
            $.ajax({
                type:'post',
                url:'?action=post',
                data:{
                    title:title,
                    people:str,
                    check:check,
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
	    $("#RegForm input:checkbox").each(function () {   
		    $(this).prop('checked', true);//
	    }); 
	});
	$("#unSelect").click(function () {   
		$("#RegForm input:checkbox").removeAttr("checked");  
    });
    $("#reverse").click(function () {  
        $("#RegForm input:checkbox").each(function () {   
        	this.checked = !this.checked;  
        }); 
    });
    
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

