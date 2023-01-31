<?php 
    session_start();
?>
<!-- aside -->
<!--div data-spy="affix" data-offset="0"-->
    <aside id="aside" class="app-aside hidden-xs bg-light dker">
      <div class="aside-wrap">
        <div class="navi-wrap">

          <!-- nav -->
          <nav ui-nav class="navi">
            <ul class="nav">
              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>导航</span>
              </li>
              <li>
                <a href="./">
                  <i class="glyphicon glyphicon-home"></i>
                  <span>首页</span>
                </a>
              </li>
              <li>
                    <a href="./mytask">
                      <i class="fa fa-tasks" aria-hidden="true"></i>
                      <span>我的任务</span>
                    </a>
                  </li>
                <li>
                <a href="./list">
                  <i class="glyphicon glyphicon-th-list"></i>
                  <span>班级通讯录</span>
                </a>
              </li>
              <?php
              
                  if($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2") 
                    echo('<li>
                    <a href="./ty">
                      <i class="glyphicon glyphicon-stats"></i>
                      <span>团员列表</span>
                    </a>
                  </li>
                  </li>
                    <li>
                    <a href="./register">
                      <i class="fa fa-archive" aria-hidden="true"></i>
                      <span>登记表</span>
                    </a>
                  </li>
                  <li>
                    <a href="./collect">
                      <i class="fa fa-cubes" aria-hidden="true"></i>
                      <span>收集表</span>
                    </a>
                  </li>
                  <li>
                    <a href="./notice">
                      <i class="fa fa-bullhorn" aria-hidden="true"></i>
                      <span>发布公告</span>
                    </a>
                  </li>');
                  if(($_SESSION['usergroup']=="1" || $_SESSION['usergroup']=="2") && $_SESSION['zhiwu']!="院实践委员")echo(' 
                  <li>
                    <a href="./re_say">
                      <i class="fa fa-bell" aria-hidden="true"></i>
                      <span>收到的留言</span>
                    </a>
                  </li>');
                   if($_SESSION['usergroup']=="1") 
                    echo('<li>
                    <a href="./qx">
                      <i class="fa fa-sitemap"></i>
                      <span>系统账号管理</span>
                    </a>
                  </li>');
              ?>
              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>荣誉记录</span>
              </li>
              <li>
                <a href="./personal">
                    <i class="glyphicon glyphicon-chevron-right"></i>
                  <span>个人荣誉</span>
                </a>
              </li>
              <li>
                <a href="./class">
                <i class="glyphicon glyphicon-chevron-right"></i>
                  <span>集体荣誉</span>
                </a>
              </li>
              
            <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>个人履历</span>
              </li>
              <li>
                <a href="./document">
                    <i class="glyphicon glyphicon-chevron-right"></i>
                  <span>履历记录</span>
                </a>
              </li>
              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>班费面板</span>
              </li>
              <li>
                <a href="./fee">
                    <i class="glyphicon glyphicon-chevron-right"></i>
                  <span>班费明细</span>
                </a>
              </li>
              <li>
                <a href="./baoxiao">
                    <i class="glyphicon glyphicon-chevron-right"></i>
                  <span>申请报销</span>
                </a>
              </li>
              <?php
              
                  if($_SESSION['usergroup']=="1" || $_SESSION['zhiwu']=="生劳委员") 
                    echo('
              <li>
                <a href="./shenhe">
                    <i class="glyphicon glyphicon-chevron-right"></i>
                  <span>报销审核</span>
                </a>
              </li>');
              ?>
              
              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>账号设置</span>
              </li>
              <li>
                <a href="./set">                      
                  <i class="glyphicon glyphicon-cog"></i>
                  <span>修改信息</span>
                </a>
              </li>
			    <li>
                <a ui-sref="access.signin" href="login?action=logout">
                  <i class="fa fa-power-off"></i>
                  <span>退出登录</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
  </aside>
  <!--/div-->