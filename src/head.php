<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8" />
  <title>班级信息中心-<?=$title?></title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
  <script src="//cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <link href="https://cdn.bootcdn.net/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
  <link href="./css/bttn.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/animate.css" type="text/css" />
  <link rel="stylesheet" href="./css/app.css" type="text/css" />
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
  <script src="./css/app.js"></script>
  
  <style>
   /* html {
        filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter:grayscale(100%);-moz-filter:grayscale(100%);-ms-filter:grayscale(100%);-o-filter:grayscale(100%);filter:grayscale(100%);filter:gray
    }*/
    .cont{
        white-space: nowrap,
        text-overflow: ellipsis,
        overflow: hidden,
        max-width: 60px
    }
  </style>
  <script>

$(document).ready(function () {
    $('.nav').find('li').each(function () {
        var a = $(this).find('a:first')[0];
        if ($(a).attr("href") === location.pathname) {
            $(this).addClass("active");
        } else {
            $(this).removeClass("active");
        }
    })
    $('.dropdown-menu').find('li').each(function () {
        var classname = $(this).attr('class');
        if (classname === 'active') {
            $('.dropdown-menu').addClass('active');
        }
    })
})
</script>


  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="app app-header-fixed  ">
  <header id="header" class="app-header navbar ng-scope" role="menu">
      <div class="navbar-header bg-primary">
               <button type="button" class="pull-right visible-xs" ui-toggle="off-screen" data-toggle="collapse" data-target="#example-navbar-collapse">
                   <i class="glyphicon glyphicon-align-justify"></i>
                </button>
                
        <a href="javascript:;" class="navbar-brand text-lt">
          <span class="hidden-folded"><img height="50px" class="ljc_logo" width="auto" src="./images/<?=$_SESSION['logo_url']?>"></span>
        </a>
        
      </div>
      
      <div class="collapse pos-rlt navbar-collapse box-shadow bg-primary" id="example-navbar-collapse">
        <!-- buttons -->
        <!-- / buttons -->
         <ul class="nav navbar-nav navbar-left">
                <li class="active"><a href="/index">首页</a></li>
                <li class=""><a href="/mytask">我的任务</a></li>
                <li class=""><a href="/say">我对班委有话说</a></li>
                <li class=""><a href="/random">随机抽号</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">我的
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/personal">个人荣誉</a></li>
                        <li><a href="/document">个人履历</a></li>
                        <li><a href="/baoxiao">报销申请</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">班级
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/class">集体荣誉</a></li>
                        <li><a href="/fee">班费明细</a></li>
                        <li><a href="/list">通讯录</a></li>
                    </ul>
                </li>
                <?php if($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2")
                echo '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">班委功能箱
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/ty">团员列表</a></li>
                        <li><a href="/re_say">收到的留言</a></li>
                        <li><a href="/register">登记表</a></li>
                        <li><a href="/collect">收集表</a></li>';
                        if($_SESSION['usergroup']=="1" || $_SESSION['zhiwu']=="生劳委员")
                        echo '<li><a href="/shenhe">报销审核</a></li>';
                echo'
                    </ul>
                </li>';
                ?>
                
            </ul>
<ul class="nav navbar-nav navbar-right">
     
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle clear" data-toggle="dropdown">
              <span class="thumb-sm avatar pull-right m-t-n-sm m-b-n-sm m-l-sm">
                <img src="./images/icon-user-center-avatar@2x.png">
                <i class="on md b-white bottom"></i>
              </span>
              <span id="hd_username"><?php echo $row['name'];?></span> <b class="caret"></b>
            </a>

<ul class="dropdown-menu animated fadeInRight w">
              <li>
                <a href="./">
                  <span>用户中心</span>
                </a>
              </li>
              <li>
                <a href="set">
                  <span>修改资料</span>
                </a>
              </li>
			  <li>

              </li>
              <li class="divider"></li>
              <li>
                <a href="login?action=logout">退出登录</a>
              </li>
            </ul>
                        <!-- / dropdown -->
          </li>
        </ul>
        <!-- / navbar right -->
      </div>
      <!-- / navbar collapse -->
            
  </header>
  <!-- / header -->
