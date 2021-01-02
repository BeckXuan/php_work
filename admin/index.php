<?php
require "../common.php";
session_start();
if (!isAdminLegal()) {
    header('location: login.php');
    return;
}
//$db = &DB::getInstance();
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
    <title>班级管理系统后台页面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="assets/css/ace.min.css"/>
    <link rel="stylesheet" href="assets/css/ace-rtl.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <script src="assets/js/ace-extra.min.js"></script>
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/ace.min.js"></script>
    <script src="assets/layer/layer.js" type="text/javascript"></script>
    <script src="js/index.js" type="text/javascript"></script>
</head>
<body>
<div class="navbar navbar-default" id="navbar">
    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="#" class="navbar-brand">
                <small>
                    <img src="images/logo.png" alt="网站后台管理系统">
                </small>
            </a>
        </div>
        <div class="navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <span class="time"><em id="time"></em></span><span
                                class="user-info"><small>欢迎光临,</small><?= $_SESSION['name'] ?></span>
                        <i class="icon-caret-down"></i>
                    </a>
                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li><a href="javascript:void(0)" id="Exit_system"><i class="icon-off"></i>退出</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="main-container" id="main-container">
    <div class="main-container-inner">
        <a class="menu-toggler" id="menu-toggler" href="#">
            <span class="menu-text"></span>
        </a>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                    网站后台管理系统
                </div>
                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                    <span class="btn btn-success"></span>
                    <span class="btn btn-info"></span>
                    <span class="btn btn-warning"></span>
                    <span class="btn btn-danger"></span>
                </div>
            </div>
            <ul class="nav nav-list" id="nav_list">
                <li class="home"><a href="javascript:void(0)" name="home.php" class="iframeurl" title=""><i
                                class="icon-dashboard"></i><span class="menu-text"> 系统首页 </span></a></li>
                <li>
                    <a href="#" class="dropdown-toggle"><i class="icon-user"></i><span class="menu-text"> 学生管理 </span><b
                                class="arrow icon-angle-down"></b></a>
                    <ul class="submenu">
                        <li class="home"><a href="javascript:void(0)" name="user.php" title="全部学生列表"
                                            class="iframeurl"><i class="icon-double-angle-right"></i>全部学生列表</a></li>
                        <li class="home"><a href="javascript:void(0)" name="user.php?type=0" title="待审核学生列表"
                                            class="iframeurl"><i class="icon-double-angle-right"></i>待审核学生列表</a></li>
                        <li class="home"><a href="javascript:void(0)" name="user.php?type=1" title="已允许学生列表"
                                            class="iframeurl"><i class="icon-double-angle-right"></i>已允许学生列表</a></li>
                        <li class="home"><a href="javascript:void(0)" name="user.php?type=-1" title="已拒绝学生列表"
                                            class="iframeurl"><i class="icon-double-angle-right"></i>已拒绝学生列表</a></li>
                    </ul>
                </li>
                <li><a href="#" class="dropdown-toggle"><i class="icon-book"></i><span class="menu-text"> 文章管理 </span><b
                                class="arrow icon-angle-down"></b></a>
                    <ul class="submenu">
                        <li class="home"><a href="javascript:void(0)" name="article.php" title="文章列表"
                                            class="iframeurl"><i class="icon-double-angle-right"></i>文章列表</a></li>
                    </ul>
                </li>
                <li><a href="#" class="dropdown-toggle"><i class="icon-comments"></i><span
                                class="menu-text"> 留言管理 </span><b
                                class="arrow icon-angle-down"></b></a>
                    <ul class="submenu">
                        <li class="home"><a href="javascript:void(0)" name="message.php" title="留言列表"
                                            class="iframeurl"><i class="icon-double-angle-right"></i>留言列表</a></li>
                    </ul>
                </li>
            </ul>
            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon-double-angle-left" data-icon1="icon-double-angle-left"
                   data-icon2="icon-double-angle-right"></i>
            </div>
        </div>
        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="index.php">首页</a>
                    </li>
                    <li class="active"><span class="Current_page iframeurl"></span></li>
                    <li class="active" id="parentIframe"><span class="parentIframe iframeurl"></span></li>
                    <li class="active" id="parentIfour"><span class="parentIfour iframeurl"></span></li>
                </ul>
            </div>
            <iframe id="iframe" style="border:0; width:100%; background-color:#FFF;" name="iframe" frameborder="0"
                    src="home.php"></iframe>
        </div>
    </div>
</div>
</body>
</html>

