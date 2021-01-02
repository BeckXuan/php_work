<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}

$db = &DB::getInstance();
$error = null;
$studentID = $_POST['value'];
if (!$db->studentIDExists($studentID)) {
    $error = '该学号不存在！';
} else if (!$db->denyUser($studentID)) {
    $error = '执行失败！';
} else {
    return;
}
header("Status: 422 Unprocessable Entity");
echo $error;
