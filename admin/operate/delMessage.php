<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}
if (!isset($_POST['value'])) {
    http_response_code(400);
    return;
}
$db = &DB::getInstance();
$error = null;
$messageId = $_POST['value'];
if (!$db->messageExists($messageId)) {
    $error = '该留言不存在！';
} else if (!$db->delMessage($messageId)) {
    $error = '执行失败！';
} else {
    return;
}
http_response_code(406);
echo $error;
