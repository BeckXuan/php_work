<?php
require "../common.php";
session_start();
//if (!isAdminLegal()) {
//    header('location: login.php');
//    return;
//}
$db = &DB::getInstance();
$db->initArticleInformation(0, 100);
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
    <title>文章列表</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="assets/css/ace.min.css"/>
    <link rel="stylesheet" href="assets/css/font-awesome.min.css"/>
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/jquery.dataTables.bootstrap.js"></script>
    <script src="assets/layer/layer.js" type="text/javascript"></script>
    <script src="js/common.js" type="text/javascript"></script>
    <script src="js/article.js" type="text/javascript"></script>
</head>
<body>
<div class="margin clearfix">
    <div>
        <div class="border clearfix">
       <span class="l_f">
        <a href="javascript:void(0)" id="article_add" class="btn btn-warning"><i class="icon-plus"></i>&nbsp;添加文章</a>
       </span>
            <span class="r_f">原始共：<b><?= $db->getNrOfArticles() ?></b>条</span>
        </div>
        <!--文章列表-->
        <div class="article_list">
            <table class="table table-striped table-bordered table-hover" id="sample-table">
                <thead>
                <tr>
                    <th><label><input type="checkbox" class="ace"><span class="lbl"></span></label></th>
                    <th>文章ID</th>
                    <th>文章标题</th>
                    <th>文章内容</th>
                    <th>时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($article = $db->getNextArticle()) {
                    $id = $article->getId();
                    $title = $article->getTitle();
                    $content = $article->getContent();
                    $time = $article->getTime();
                    echo <<< tr
                <tr>
                    <td></td>
                    <td>{$id}</td>
                    <td>{$title}</td>
                    <td>{$content}</td>
                    <td>{$time}</td>
                    <td></td>
                </tr>

tr;
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--文章详细-->
<div id="article" style="display:none">
    <div class="content_style">
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章ID </label>
            <div class="col-sm-9" id="view_id">这是文章ID</div>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">添加时间 </label>
            <div class="col-sm-9" id="view_time">这是添加时间</div>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章标题 </label>
            <div class="col-sm-9" id="view_title">这是文章标题</div>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章内容 </label>
            <div class="col-sm-9" id="view_content">这是文章内容</div>
        </div>
    </div>
</div>

<!--文章编辑-->
<div id="article_edit" style="display:none">
    <div class="content_style">
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章ID </label>
            <div class="col-sm-9" id="edit_id">这是文章ID</div>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">添加时间 </label>
            <div class="col-sm-9" id="edit_time">这是添加时间</div>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章标题 </label>
            <textarea class="col-sm-9" id="edit_title" style="height: 30px;"></textarea>
        </div>
        <div class="form-group"><label class="col-sm-2 control-label no-padding-right">文章内容 </label>
            <textarea class="col-sm-9" id="edit_content" style="height: 260px;"></textarea>
        </div>
    </div>
</div>
</body>
</html>