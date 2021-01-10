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
$studentID = $_POST['value'];
if ($studentID === 'admin') {
    $error = '禁止对该账号操作！';
} else if (!$db->studentIDExists($studentID)) {
    $error = '该学号不存在！';
} else if (!$db->admitUser($studentID)) {
    $error = '执行失败！';
} else {
    return;
}
http_response_code(406);
echo $error;
