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
    .cont{
        white-space: nowrap,
        text-overflow: ellipsis,
        overflow: hidden,
        max-width: 60px
    }
  </style>


  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="app app-header-fixed  ">
  <header id="header" class="app-header navbar ng-scope" role="menu">
      <div class="navbar-header bg-primary">
        <button class="pull-right visible-xs" ui-toggle="off-screen" target=".app-aside" ui-scroll="app">
          <i class="glyphicon glyphicon-align-justify"></i>
        </button>
        <a href="javascript:;" class="navbar-brand text-lt">
          <span class="hidden-folded"><img height="50px" class="ljc_logo" width="auto" src="./images/<?=$_SESSION['logo_url']?>"></span>
        </a>
      </div>

      <div class="collapse pos-rlt navbar-collapse box-shadow bg-primary">
        <!-- buttons -->
        <div class="nav navbar-nav hidden-xs">
          <a href="#" class="btn no-shadow navbar-btn" ui-toggle="app-aside-folded" target=".app">
            <i class="fa fa-dedent fa-fw text"> 菜单</i>
            <i class="fa fa-indent fa-fw text-active">菜单</i>
          </a>
        </div>
        <!-- / buttons -->
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
