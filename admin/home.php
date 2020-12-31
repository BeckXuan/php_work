<?php
require "../common.php";
session_start();
//if (!isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db = &DB::getInstance();
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/font-awesome.min.css"/>
    <title>home</title>
</head>
<body>
<div class="page-content clearfix">
    <div class="state-overview clearfix">
        <div class="col-lg-3 col-sm-6">
            <div class="panel">
                <div title="学生数量">
                    <div class="symbol green">
                        <i class="icon-user"></i>
                    </div>
                    <div class="value">
                        <h1><?= $db->getNrOfAdmittedUsers() ?></h1>
                        <p>学生数量</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="panel">
                <div title="待审核">
                    <div class="symbol terques">
                        <i class="icon-user"></i>
                    </div>
                    <div class="value">
                        <h1><?= $db->getNrOfUnauditedUsers() ?></h1>
                        <p>待审核</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="panel">
                <div title="文章数量">
                    <div class="symbol yellow">
                        <i class="icon-book"></i>
                    </div>
                    <div class="value">
                        <h1><?= $db->getNrOfArticles() ?></h1>
                        <p>文章数量</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="panel">
                <div title="留言数量">
                    <div class="symbol blue">
                        <i class="icon-comments"></i>
                    </div>
                    <div class="value">
                        <h1><?= $db->getNrOfMessages() ?></h1>
                        <p>留言数量</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
