<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}

$db = &DB::getInstance();
$message = null;
$messageId = $_POST['value'];
if (!$db->messageExists($messageId)) {
    $message = '该留言不存在！';
} else if (!$db->delMessage($messageId)) {
    $message = '执行失败！';
} else {
    return;
}
header("Status: 422 Unprocessable Entity");
echo $message;
