<?php
require "../../common.php";
setContentType();
session_start();
if (!isAdminLegal()) {
    http_response_code(401);
    return;
}
if (!isset($_POST['studentID'], $_POST['name'], $_POST['password'], $_POST['admitted'])) {
    header("Status: 422 Unprocessable Entity");
    return;
}
$password = $_POST['password'];
$admitted = $_POST['admitted'];
if (!preg_match("/^[a-z0-9]{32}$/", $password) || ($admitted !== '-1' && $admitted !== '0' && $admitted !== '1')) {
    http_response_code(403);
    return;
}
$db = DB::getInstance();
$error = '';
$studentID = $_POST['studentID'];
$name = $_POST['name'];
if ($db->studentIDExists($studentID)) {
    $error = '该学号已经存在！';
} else if ($db->nameExists($name)) {
    $error = '该用户名已经存在！';
} else if ($db->addUser($name, $studentID, $password, false) === false) {
    $error = '数据库错误！';
} else if ($admitted === '1' && !$db->admitUser($studentID)) {
    $error = '允许用户失败！';
} else if ($admitted === '-1' && !$db->denyUser($studentID)) {
    $error = '禁止用户失败！';
}
if ($error === '') {
    $time = $db->getUserTime($studentID);
    $json = json_encode(compact('time'));
    echo $json;
} else {
    header("Status: 422 Unprocessable Entity");
    echo $error;
}
