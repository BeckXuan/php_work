<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}
if (!isset($_POST['studentID'], $_POST['articleId'], $_POST['message'])) {
    header("Status: 422 Unprocessable Entity");
    return;
}

$db = DB::getInstance();
$studentID = $_POST['studentID'];
$articleId = $_POST['articleId'];
if (($messageId = $db->addMessage($articleId, $_POST['message'], $studentID)) !== false) {
    $articleTitle = $db->getArticleTitle($articleId);
    $studentName = $db->getUserName($studentID);
    $time = $db->getMessageTime($messageId);
    $json = json_encode(compact('messageId', 'articleTitle', 'studentName', 'time'));
    echo $json;
} else {
    header("Status: 422 Unprocessable Entity");
    echo '添加失败！';
}
