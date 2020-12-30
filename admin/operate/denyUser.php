<?php
require "../../common.php";
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}

$db = &DB::getInstance();
$message = null;
$studentID = $_POST['value'];
if (!$db->studentIDExists($studentID)) {
    $message = '该学号不存在！';
} else if (!$db->denyUser($studentID)) {
    $message = '执行失败！';
} else {
    return;
}
header("Status: 422 Unprocessable Entity");
echo $message;
