<?php
    include '../config.php';
    
    
    $result_t=mysqli_query($conn,"SELECT * FROM system_info WHERE tag='isopen'");
    $t_row=mysqli_fetch_assoc($result_t);
    

    if($_GET['action']=="query")
    {
        if($t_row['content']=="1") exit('{"msg":"no data"}');
        
        
        $username=$_POST['username'];
        $tel=$_POST['tel'];
        
        
        $check_query = mysqli_query($conn,"select id from dangyuan where name='$username' and tel='$tel' limit 1");
        
        if(mysqli_fetch_array($check_query,MYSQLI_ASSOC)){
        
            $result_sxzz=mysqli_query($conn,"SELECT * FROM sxzz WHERE name='$username'");
            $result_dnshzs=mysqli_query($conn,"SELECT * FROM dnshzs WHERE name='$username'");
            $result_fzzyzs=mysqli_query($conn,"SELECT * FROM fzzyzs WHERE name='$username'");
            $result_myzs=mysqli_query($conn,"SELECT * FROM myzs WHERE name='$username'");
            $result_zxjfzs=mysqli_query($conn,"SELECT * FROM zxjfzs WHERE name='$username'");
            $result_fxkfzs=mysqli_query($conn,"SELECT * FROM fxkfzs WHERE name='$username'");
            $result_glzs=mysqli_query($conn,"SELECT * FROM glzs WHERE name='$username'");
            
            $result_user=mysqli_query($conn,"SELECT * FROM dangyuan WHERE name='$username'");
            
            $row_sxzz=mysqli_fetch_assoc($result_sxzz);
            $row_dnshzs=mysqli_fetch_assoc($result_dnshzs);
            $row_fzzyzs=mysqli_fetch_assoc($result_fzzyzs);
            $row_myzs=mysqli_fetch_assoc($result_myzs);
            $row_zxjfzs=mysqli_fetch_assoc($result_zxjfzs);
            $row_fxkfzs=mysqli_fetch_assoc($result_fxkfzs);
            $row_glzs=mysqli_fetch_assoc($result_glzs);
            
            $row_user=mysqli_fetch_assoc($result_user);
            
            if($row_glzs['gl']=="1") $gl="是";
            else $gl="否";
            
    
            exit('{"msg":"success","datas":"'.$row_sxzz['jifen'].'+ljc+'.$row_dnshzs['jifen'].'+ljc+'.$row_fzzyzs['jifen'].'+ljc+'.$row_myzs['jifen'].'+ljc+'.$row_zxjfzs['jifen'].'+ljc+'.$row_fxkfzs['jifen'].'+ljc+'.$gl.'+ljc+'.$row_user['jifen'].'"}');
            
        }
        else exit('{"msg":"error"}');
    }
    


?>
<!DOCTYPE html>
	<html lang="zh-CN">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>先锋指数查询</title>
	  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel='stylesheet' id='buttons-css'  href='./css/buttons.min.css?ver=6.0.1' type='text/css' media='all' />
    <link rel='stylesheet' id='forms-css'  href='./css/forms.min.css?ver=6.0.1' type='text/css' media='all' />
    <link rel='stylesheet' id='login-css'  href='./css/login.min.css?ver=6.0.1' type='text/css' media='all' />
    <link rel='stylesheet' id='argon_login_css-css'  href='./css/login.css?ver=1.3.5' type='text/css' media='all' />
    
	<meta name='referrer' content='strict-origin-when-cross-origin' />
	<meta name="viewport" content="width=device-width" />
	
	<script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
	
	
	<style>
	    body{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 480rpx;
            background-image: url('../images/query_bk.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            padding: 0 37rpx;
        }
        
  </style>
	
	
	
		</head>
	<body class="login no-js login-action-login wp-core-ui locale-zh-cn">
	    
		<div id="login">
		    <center><img height="100px" width="auto" src="../images/logo.png"></center>
		    <div id="login_page"  style="display:block;">
        		<form name="queryform" id="queryform" action="#" method="post">
        		    <p style="text-align:center;font-size:30px">先锋指数查询系统</p>
        			<p>
        				<label for="user_login">姓名</label>
        				<input type="text" name="username" id="username" class="input" value="" size="20" />
        			</p>
        
        			<div class="user-pass-wrap">
        				<label for="user_pass">手机号</label>
        				<div class="wp-pwd">
        					<input type="text" name="tel" id="tel" class="input" value="" size="20" onkeyup = "if(event.keyCode !=37 && event.keyCode != 39)value=value.replace(/\D/g,'')"/>
        					</button>
        				</div>
        			</div>
        			<p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;"></p>
        			<p class="submit">
        			    <?php
        			        if($t_row['content']=="0") echo('<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="查询" />');
        			        else echo('<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="查询通道未开启" disabled/>');
        			    
        			    ?>
        				
        			</p>
        		</form>
        	</div>
        	<div id="result_page" style="display:none;">
        		<form>
        		    <p style="text-align:center;font-size:30px">先锋指数查询结果</p><br>
        		    <p style="text-align:center;font-size:17px">姓名：<span id="name"></span>&nbsp;&nbsp;&nbsp;&nbsp;年度：<span id="year">2022</span></p>
        			<table class="table table-striped b-t b-light text-center" style="font-size:15px">
        			    
                        <thead><th class="text-center">思想政治指数</th><th class="text-center">党内生活指数</th><th class="text-center">发展作用指数</th><th class="text-center">民意指数</th></thead>
                        <tr>
                            <td id="sxzz"></td>
                            <td id="dnshzs"></td>
                            <td id="fzzyzs"></td>
                            <td id="myzs"></td>
                        </tr>
                        
                        <thead><th class="text-center">正向加分指数</th><th class="text-center">反向扣分指数</th><th class="text-center">归零指数</th><th class="text-center">先锋指数</th></thead>
                        <tr>
                            <td id="zxjfzs"></td>
                            <td id="fxkfzs"></td>
                            <td id="glzs"></td>
                            <td id="jifen"></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <a type="submit" class="button button-primary button-large" onclick="fanhui()"><i class="glyphicon glyphicon-chevron-right"></i>&nbsp;返回</a>
                    </p>
                    
        		</form>
        	</div>
		</div>
			
			
	</body>
	
	<script>
	    $("img").mousedown(function(){
            return false;
        });
        
        function fanhui(){
            document.getElementById('login_page').style="display:block;";
            document.getElementById('result_page').style="display:none;";
            $('#sxzz').text("");
			$('#dnshzs').text("");
			$('#fzzyzs').text("");
			$('#myzs').text("");
			$('#zxjfzs').text("");
			$('#fxkfzs').text("");
			$('#glzs').text("");
			$('#jifen').text("");
			$('#name').text("");
			$('#username').val("");
			$('#tel').val("");
			
        }
        
        $(function () {
            $('#queryform').on('submit', function (e) {  
                document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
                e.preventDefault()
                var username=$('#username').val();
                var tel=$('#tel').val();
                
                if(username=="" && tel==""){
                    $('#wrong').text('请输入姓名和手机号');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                else if(username==""){
                    $('#wrong').text('请输入姓名');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                else if(tel==""){
                    $('#wrong').text('请输入手机号');
                    document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                    return false;
                }
                
                
                $('#name').text(username);
                
                $.ajax({
                    type:'post',
                    url:'?action=query',
                    data:{
                        username:username,
                        tel:tel
                    },
                    dataType:'json',
                    success: function(res){
                        console.log(res);
                        if(res.msg=="success"){
                            var strs= new Array(); 
                            strs=res.datas.split("+ljc+");
			                $('#sxzz').text(strs[0]);
			                $('#dnshzs').text(strs[1]);
			                $('#fzzyzs').text(strs[2]);
			                $('#myzs').text(strs[3]);
			                $('#zxjfzs').text(strs[4]);
			                $('#fxkfzs').text(strs[5]);
			                $('#glzs').text(strs[6]);
			                $('#jifen').text(strs[7]);
                            
                            document.getElementById('login_page').style="display:none;";
                            document.getElementById('result_page').style="display:block;";
                            
                        }
                        else if(res.msg=="no data"){
                            $('#wrong').text('查询通道已关闭');
                            document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                        }
                        
                        else{
                            $('#wrong').text('没有查询到信息，请输入正确的姓名和手机号');
                            document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                        }
                            
                    }
                })
            })
        })
	</script>
	
	
	</html>
