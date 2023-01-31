<?php 
session_start();
require_once("../comm/config.php");
error_reporting(0);
function qq_callback()
{
    //debug
    //print_r($_REQUEST);
    //print_r($_SESSION);

    if($_REQUEST['state'] == $_SESSION['state']) //csrf
    {
        $token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&"
            . "client_id=" . $_SESSION["appid"]. "&redirect_uri=" . urlencode($_SESSION["callback"])
            . "&client_secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];

        $response = file_get_contents($token_url);
        if (strpos($response, "callback") !== false)
        {
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);
            if (isset($msg->error))
            {
                echo "<h3>error:</h3>" . $msg->error;
                echo "<h3>msg  :</h3>" . $msg->error_description;
                exit;
            }
        }

        $params = array();
        parse_str($response, $params);

        //debug
        //print_r($params);

        //set access token to session
        $_SESSION["access_token"] = $params["access_token"];

    }
    else 
    {
        echo("The state does not match. You may be a victim of CSRF.");
    }
}

function get_openid()
{
    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=" 
        . $_SESSION['access_token'];

    $str  = file_get_contents($graph_url);
    if (strpos($str, "callback") !== false)
    {
        $lpos = strpos($str, "(");
        $rpos = strrpos($str, ")");
        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
    }

    $user = json_decode($str);
    if (isset($user->error))
    {
        echo "<h3>error:</h3>" . $user->error;
        echo "<h3>msg  :</h3>" . $user->error_description;
        exit;
    }

    //debug
    //echo("Hello " . $user->openid);

    //set openid to session
    $_SESSION["openid"] = $user->openid;
}

//QQ登录成功后的回调地址,主要保存access token
qq_callback();

//获取用户标示id
get_openid();

//print_r($_SESSION);
$connect_login=mysqli_connect("localhost","","","classcenter_info");
if (mysqli_connect_errno($connect_login)){
    echo "连接数据库失败: " . mysqli_connect_error();
}
mysqli_set_charset($connect_login,"utf8");

$openid=$_SESSION['openid'];
if($_SESSION['username']==""){
    
	$check_query = mysqli_query($connect_login,"SELECT * FROM all_users WHERE qqid='$openid'");
	if(mysqli_fetch_array($check_query,MYSQLI_ASSOC)){
	    
	    $result_get_classroom=mysqli_query($connect_login,"SELECT * FROM all_users WHERE qqid='$openid'");
	    $row_get_classroom=mysqli_fetch_assoc($result_get_classroom);
	    
	    $classroom_id=$row_get_classroom['classroomid'];
	    $classid=$row_get_classroom['classid'];
	    
	    $result_get_classroom_info=mysqli_query($connect_login,"SELECT * FROM classroom_info WHERE classroomid='$classroom_id'");
	    $row_get_classroom_info=mysqli_fetch_assoc($result_get_classroom_info);
	    
	    $_SESSION['data_base']=$row_get_classroom_info['data_base'];
	    $_SESSION['logo_url']=$row_get_classroom_info['logo_url'];
	    
	    
	    $conn=mysqli_connect("localhost","","",$_SESSION['data_base']);
        if (mysqli_connect_errno($conn)){
            echo "连接数据库失败: " . mysqli_connect_error();
        }
	    
	    
	    
	    $result1=mysqli_query($conn,"SELECT * FROM user WHERE classid='$classid'");
	    
    	
    	
    	
    	$row=mysqli_fetch_array($result1,MYSQLI_ASSOC);
    	$uid=$row['uid'];
    	$_SESSION['userid']  = $uid;
    	
    	$_SESSION['username'] = $row['name'];
    	$_SESSION['usergroup'] = $row['usergroup'];
    	$_SESSION['zhiwu'] = $row['zhiwu'];
    	$_SESSION['classid']  = $row['classid'];
    	$times=$row['login'];
    	
    	if(mysqli_error($conn))
    	{
    		$mysql_err=mysqli_error($conn);
    		exit('{"msg":'.$mysql_err.'}');
    	}else{
		 	
            header('Location: ..'.$_SESSION['refer_url']); 
            exit;
    	}
    	//exit;
	} else {
    	echo '<font size="8px" color="red">未绑定账号！请登录后完成QQ绑定后即可使用QQ登录。<a href="../login">返回登录</a></font>';
	}
}
else{
    $classid=$_SESSION['classid'];
    mysqli_query($connect_login,"UPDATE all_users SET qqid='$openid' WHERE classid='$classid'");
    header("Location: ../index"); 
}
?>
