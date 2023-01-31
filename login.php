<?php

session_start();

@header('Content-Type: text/html; charset=UTF-8');

if($_GET['action'] == "logout"){
    unset($_SESSION['userid']);
    unset($_SESSION['username']);
    unset($_SESSION['usergroup']);
    unset($_SESSION['zhiwu']);
    unset($_SESSION['classid']);
    unset($_SESSION['data_base']);
    unset($_SESSION['logo_url']);
    echo ("<script language='javascript'>alert('注销登录成功！');window.location.href='login';</script>");
    exit;
}

if(isset($_SESSION['userid']))
{
	header("Location: index");
	exit();
}
$refer_url=$_GET['refer'];
$_SESSION['refer_url']=$refer_url;


if($_GET['action'] == "login")
{
	$userid = $_POST['username'];
	$password = $_POST['password'];

	include ('./login_config.php');
	
	$check_query = mysqli_query($connect_login,"select * from all_users where classid='$userid' and passwd='$password' limit 1");


	if($result = mysqli_fetch_array($check_query,MYSQLI_ASSOC)){
	    
	    $result_get_classroom=mysqli_query($connect_login,"SELECT * FROM all_users WHERE classid='$userid'");
	    $row_get_classroom=mysqli_fetch_assoc($result_get_classroom);
	    
	    $classroom_id=$row_get_classroom['classroomid'];
	    
	    $result_get_classroom_info=mysqli_query($connect_login,"SELECT * FROM classroom_info WHERE classroomid='$classroom_id'");
	    $row_get_classroom_info=mysqli_fetch_assoc($result_get_classroom_info);
	    
	    $if_open=$row_get_classroom_info['if_open'];
	    if($if_open=="0") exit('{"code":"1002","msg":"not open"}');
	    
	    $_SESSION['data_base']=$row_get_classroom_info['data_base'];
	    $_SESSION['logo_url']=$row_get_classroom_info['logo_url'];
	    
	    
	    $conn=mysqli_connect("localhost","","",$_SESSION['data_base']);
        if (mysqli_connect_errno($conn)){
            echo "连接数据库失败: " . mysqli_connect_error();
        }
        
	    $result1=mysqli_query($conn,"SELECT * FROM user WHERE classid='$userid'");
	    $row=mysqli_fetch_assoc($result1);
	    
    	$uid=$row['uid'];
    	$_SESSION['userid']  = $uid;
    	
    	
    	
    	$_SESSION['username'] = $row['name'];
    	$_SESSION['usergroup'] = $row['usergroup'];
    	$_SESSION['zhiwu'] = $row['zhiwu'];
    	$_SESSION['classid']  = $row['classid'];
    	
    	if(mysqli_error($conn))
    	{
    		$mysql_err=mysqli_error($conn);
    		exit('{"msg":'.$mysql_err.'}');
    	}else{
		 	exit('{"code":"200","msg":"success"}');
    	}
    	//exit;
	} else {
    	exit('{"code":"1001","msg":"wrong username or password"}');
	}
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>班级信息中心-登录</title>
<meta name="keywords" content="班级信息中心" />
<meta name="description" content="软工2201班级信息中心-登录" />
  
  <link href="../css/bootstrap.min.css" rel="stylesheet"/>

<link rel="stylesheet" href="css/style.css" />


  <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
    <style>
    /*
        html {
        filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter:grayscale(100%);-moz-filter:grayscale(100%);-ms-filter:grayscale(100%);-o-filter:grayscale(100%);filter:grayscale(100%);filter:gray
        }*/
        body{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 480rpx;
            background-image: url('./images/bg_img.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            padding: 0 37rpx;
        }
        .footer_1 {
            height: 50px;
            z-index:1;
            color: black;
            left: 0px;
            bottom: 10px;
            width: 100%;
            height: 50px;
            margin-bottom: -10px;
            position: fixed;
        }

       .abs-bottom {
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
       }
        
  </style>

</head>
<body>

<div class="login-container" style="color:black;">
	
		<img height="85px" class="ljc_logo" width="auto" src="./images/login_logo.png">
		<h1>登录</h1>
	
	<form action="#" method="post" id="loginForm">
		<div>
			<input type="text" name="username" id="username" class="username" style="color:black;" placeholder="用户名" autocomplete="off"/>
		</div>
		<div>
			<input type="password" name="password" id="password" class="password" style="color:black;" placeholder="密码" oncontextmenu="return false" onpaste="return false" />
		</div><br>
		<p id="wrong" style="color:red;text-align:center;font-size:15px;display:none;">账号或密码错误</p>
		<p id="class_wrong" style="color:red;text-align:center;font-size:15px;display:none;">当前班级暂未开放</p>
		 
		<button id="submit" type="submit" name="submit">登 录</button><br><br><br>
		<p style="color:grey;text-align:center;font-size:13px;">其他登录方式</p><br>
		<a href="javascript:;" onclick='toLogin()'>
            <img width="45px" src="./images/QQ-circle-fill.png">
         </a>
		
	</form>

</div>

<script src="js/jquery.min.js"></script>
<script src="js/common.js"></script>

<script src="js/jquery.validate.min.js?var1.14.0"></script>
<script src="js/TweenLite.min.js"></script>
<script src="js/EasePack.min.js"></script>
<script src="js/rAF.js"></script>
<script src="js/md5.js"></script>


<div style="text-align:center;">
    <div class="footer_1 abs-bottom">
        	&copy; 2022 班级信息中心&nbsp;|&nbsp;<a href="https://beian.miit.gov.cn/" target="_blank" style="display:inline-block;color:black;">闽ICP备2021019287号-1</a>&nbsp;|&nbsp;
        	<a target="_blank" href="https://www.beian.gov.cn/portal/registerSystemInfo?recordcode=35058202000622" style="display:inline-block;color:black;"><img src="../gonganbeian.png" style="float:left;"/>&nbsp;闽公网安备 35058202000622号</a>&nbsp;|&nbsp;<a href="./about.html" target="_blank" style="display:inline-block;color:black;">关于程序</a>
    </div>
</div>
</body>
<script>
    $("img").mousedown(function(){
        return false;
    });
    
    $(function () {
        $('#loginForm').on('submit', function (e) {  
            e.preventDefault()
            var username=$('#username').val();
            var password=$('#password').val();
            password=hex_md5(hex_md5(password)+'_ljcsys')
            $('#submit').text('正在登录中...');
            document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:none;";
            document.getElementById('class_wrong').style="color:red;text-align:center;font-size:15px;display:none;";
            $.ajax({
                type:'post',
                url:'?action=login&refer=<?=$refer_url?>',
                data:{
                    username:username,
                    password:password
                },
                dataType:'json',
                success: function(res){
                    console.log(res);
                    if(res.code=="200") window.location.href="<?=$refer_url?>";
                    else if(res.code=="1002"){
                        $('#submit').text('登 录');
                        document.getElementById('class_wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                        $('#password').val('');
                    }
                    else{
                        $('#submit').text('登 录');
                        document.getElementById('wrong').style="color:red;text-align:center;font-size:15px;display:block;";
                        $('#password').val('');
                    }
                        
                }
            })
        })
    })
    
    function toLogin(){window.location.href="./oauth/qq_login.php";}
</script>
</html>