<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}
if (!isset($_POST['messageId'], $_POST['studentID'], $_POST['articleId'], $_POST['message'])) {
    header("Status: 422 Unprocessable Entity");
    return;
}

$messageId = $_POST['messageId'];
$db = DB::getInstance();
if (!$db->messageExists($messageId)) {
    header("Status: 422 Unprocessable Entity");
    echo '该留言不存在！';
    return;
}
$error = '';
$studentID = $_POST['studentID'];
$articleId = $_POST['articleId'];
$message = $_POST['message'];
if ($studentID !== '' && !$db->setMessageStudentID($messageId, $studentID)) {
    $error .= '修改留言学号失败！';
}
if ($articleId !== '' && !$db->setMessageArticleID($messageId, $articleId)) {
    $error .= '修改留言文章失败！';
}
if ($message !== '' && !$db->setMessageContent($messageId, $message)) {
    $error .= '修改留言内容失败！';
}
if ($error !== '') {
    header("Status: 422 Unprocessable Entity");
    echo $error;
    return;
}

$array = [];
if ($studentID !== '') {
    $array['studentName'] = $db->getUserName($studentID);
}
if ($articleId !== '') {
    $array['articleTitle'] = $db->getArticleTitle($articleId);
}
if (!empty($array)) {
    $json = json_encode($array);
    echo $json;
}
