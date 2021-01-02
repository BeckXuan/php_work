<?php
require "../common.php";
setContentType();
session_start();
if (isUserLegal() || isAdminLegal()) {
    header('location: ../index.php');
    return;
}
if (!isset($_POST['studentID'], $_POST['password'])) {
    header('location: ../login.php');
    return;
}

//登录处理
//获取参数
$password = $_POST['password'];
if (!preg_match("/^[a-z0-9]{32}$/", $password)) {
    http_response_code(403);
    return;
}
$studentID = $_POST['studentID'];
$error = '';
$db = &DB::getInstance();
if (!$db->studentIDExists($studentID)) {
    $error = '该学号不存在！';
} else if (!($db->getUserPassword($studentID) === $password)) {
    $error = '密码错误！';
} else if (!$db->isUserAudited($studentID)) {
    $error = '请等待管理员审核后登录！';
} else if (!$db->isUserAdmitted($studentID)) {
    $error = '该学号已被管理员禁止登录！';
} else {
    //写入Session、cookie，并转到内容界面
    $name = $db->getUserName($studentID);
    $_SESSION['name'] = $name;
    $_SESSION['studentID'] = $studentID;
    if (isset($_POST['rem']) && $_POST['rem'] === '1') {
        setcookie('name', $name, time() + 3600, '/', '', false, true);
        setcookie('studentID', $studentID, time() + 3600, '/', '', false, true);
    } else {
        setcookie('name', $name, 0, '/', '', false, true);
        setcookie('studentID', $studentID, 0, '/', '', false, true);
    }
    return;
}
header("Status: 422 Unprocessable Entity");
echo $error;
