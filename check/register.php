<?php
require "../common.php";
setContentType();
session_start();
if (!isset($_POST['name'], $_POST['studentID'], $_POST['password'])) {
    header('location: ../login.php');
    return;
}
if (isUserLegal()) {
    header("Status: 422 Unprocessable Entity");
    echo '您已登录！请退出后再尝试注册！';
    return;
}
$password = $_POST['password'];
if (!preg_match("/^[a-z0-9]{32}$/", $password)) {
    http_response_code(403);
    return;
}
$name = $_POST['name'];
$studentID = $_POST['studentID'];
$message = '';
$db = &DB::getInstance();
if ($db->nameExists($name) || $db->studentIDExists($studentID)) {
    $message = '用户名或学号已存在！';
} else if (!$db->addUser($name, $studentID, $password, false)) {
    $message = '注册失败！数据库错误！';
} else {
    return;
}
header("Status: 422 Unprocessable Entity");
echo $message;
