<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}
if (!isset($_POST['id'], $_POST['title'], $_POST['content'])) {
    header("Status: 422 Unprocessable Entity");
    return;
}

$id = $_POST['id'];
$db = DB::getInstance();
if (!$db->articleExists($id)) {
    header("Status: 422 Unprocessable Entity");
    echo '该文章不存在！';
    return;
}
$error = '';
$title = $_POST['title'];
$content = $_POST['content'];
if ($title !== '' && !$db->setArticleTitle($id, $title)) {
    $error .= '修改标题失败！';
}
if ($content !== '' && !$db->setArticleContent($id, $content)) {
    $error .= '修改内容失败！';
}
if ($error !== '') {
    header("Status: 422 Unprocessable Entity");
    echo $error;
}
