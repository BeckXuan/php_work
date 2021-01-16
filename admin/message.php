<?php
require "../common.php";
session_start();
if (!isAdminLegal()) {
    header('location: login.php');
    return;
}
$db = &DB::getInstance();
$db->initMessageInformation(0, 100);
?>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8"/>
    <title>留言列表</title>
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
    <script src="js/message.js" type="text/javascript"></script>
</head>

<body>
<div class="margin clearfix">
    <div>
        <div class="border clearfix">
       <span class="l_f">
           <a href="javascript:void(0)" id="message_add" class="btn btn-warning"><i class="icon-plus"></i>&nbsp;添加留言</a>
       </span>
            <span class="r_f">原始共：<b><?= $db->getNrOfMessages() ?></b>条</span>
        </div>
        <div class="feedback">
            <table class="table table-striped table-bordered table-hover" id="sample-table">
                <thead>
                <tr>
                    <th><label><input type="checkbox" class="ace"><span class="lbl"></span></label></th>
                    <th>学号</th>
                    <th>用户名</th>
                    <th>文章ID</th>
                    <th>文章标题</th>
                    <th>留言ID</th>
                    <th>留言内容</th>
                    <th>时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($message = $db->getNextMessage()) {
                    $studentID = $message->getStudentID();
                    $studentName = $db->getUserName($studentID);
                    $studentName = json_encode(compact('studentName'));
                    $articleId = $message->getArticleId();
                    $articleTitle = $db->getArticleTitle($articleId);
                    $articleTitle = json_encode(compact('articleTitle'));
                    $messageId = $message->getId();
                    $messageContent = $message->getMessage();
                    $time = $message->getTime();
                    echo <<< tr
                <tr>
                    <td></td>
                    <td>{$studentID}</td>
                    <td>{$studentName}</td>
                    <td>{$articleId}</td>
                    <td>{$articleTitle}</td>
                    <td>{$messageId}</td>
                    <td>{$messageContent}</td>
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

<!--留言详细-->
<div id="message" style="display:none">
    <div class="content_style">
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">用户学号 </span>
            <div class="col-sm-9" id="view_ID"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言用户 </span>
            <div class="col-sm-9" id="view_name"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">文章id </span>
            <div class="col-sm-9" id="view_article_id"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">文章标题 </span>
            <div class="col-sm-9" id="view_article_title"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言id </span>
            <div class="col-sm-9" id="view_message_id"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言内容 </span>
            <div class="col-sm-9" id="view_message_content"></div>
        </div>
    </div>
</div>
<!--留言编辑-->
<div id="message_edit" style="display:none">
    <div class="content_style">
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">用户学号 </span>
            <input class="col-sm-9" id="edit_ID"/>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言用户 </span>
            <div class="col-sm-9" id="edit_name"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">文章id </span>
            <input class="col-sm-9" id="edit_article_id"/>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">文章标题 </span>
            <div class="col-sm-9" id="edit_article_title"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言id </span>
            <div class="col-sm-9" id="edit_message_id"></div>
        </div>
        <div class="form-group"><span class="col-sm-2 control-label no-padding-right">留言内容 </span>
            <textarea class="col-sm-9" id="edit_message_content" style="height: 200px"></textarea>
        </div>
    </div>
</div>
</body>
</html>